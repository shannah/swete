<?php
/**
 * SWeTE Server: Simple Website Translation Engine
 * Copyright (C) 2012  Web Lite Translation Corp.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
//require_once dirname(__FILE__).'/simple_html_dom.php';
//require_once 'lib/simple_html_dom.php';
//if ( !function_exists('_n') ) require_once dirname(__FILE__).'/webpage.functions.inc.php';
require_once dirname(__FILE__).'/../lib/JSLikeHTMLElement.php';


function normalizeSourceString($str){
	mb_regex_encoding('UTF-8');
	return trim(mb_ereg_replace('\s+', ' ', $str));
}

function _n($str){ return normalizeSourceString($str);}

function getApprovedStringKey($str){
	mb_regex_encoding('UTF-8');
	return html_entity_decode(trim(mb_ereg_replace('\s+', '', $str)), ENT_QUOTES, 'UTF-8');
}
function _k($str){ return getApprovedStringKey($str);}


class WebLite_HTML_Translator {
    private $dateFormatters = array();
    public $sourceDateLocale = null;
    public $targetDateLocale = null;
    public $useHtml5Parser = false;
	
	public static $atts = array(
		'a' => array('title'),
		'img' => array('alt', 'title'),
		'meta' => array('content')
		);
		
	public static $tags = array(
		'option',
		'textarea',
		'caption',
		'h1',
		'h2',
		'h3',
		'h4',
		'h5',
		'h6',
		'h7',
		'h8',
		'h9',
		'h10',
		'legend',
		'title',
		'li',
		'dt',
		'dd',
		'p',
		'th',
		'td',
		'div',
		'body'
		);
	
	// Tags that represent translatable sections
	public static $sectionTags = array(
		'option',
		'p',
		'div',
		'td',
		'th',
		'li',
		'dt',
		'dd',
		'dl',
		'blockquote');
		
	public static $inlineTags = array(
		'a',
		'em',
		'abbr',
		'i',
		'u',
		'b',
		'span',
		'strong',
		'acronym',
		'font',
		'sup',
		'sub'
		);
			
	
	
	
		
	public static $splitters = array('input','hr','br','textarea','table','ul','ol','dl','select','option');
		
	
	public $replacements=array();
	public $strings=array();
	private $translationMemory = null;
	
	public function setTranslationMemory(WebLite_Translation_Memory $mem){
		$this->translationMemory = $mem;
	}
	
	
	public function __construct(){
		$this->setTranslationMemory(new Default_Translation_Memory());
	}
	
		
	
	/*
	** @brief Sets $this->strings to an array map of strings extracted from the html
	** @param html the html content to extract strings from
	** @return dom, from the html content
	*/
	public function extractStrings($html){
	    $dom = null;
	    if ( $this->useHtml5Parser ){
	        $intro = substr($html,0, 255);
	        if ( stripos($intro, '<!DOCTYPE html>') !== false ){
	            // this is html5 so we'll use the html5 
                require_once 'lib/HTML5.php';
                $options = new StdClass;
                $options->decorateDocument = function(DOMDocument $dom){
                    $dom->registerNodeClass('DOMElement', 'JSLikeHTMLElement');
                };
                $dom =  HTML5::loadHTML($html, $options);
                // noscripts contents are treated like text which causes problems when 
                // filters/replacements are run on them.  Let's just remove them
                $noscripts = $dom->getElementsByTagName('noscript');
                foreach ( $noscripts as $noscript ){
                    $noscript->parentNode->removeChild($noscript);
                }
                
	        }
	    }
		//$dom = str_get_html($html);
		if ( !isset($dom) ){
            $dom = new DOMDocument();
            $dom->registerNodeClass('DOMElement', 'JSLikeHTMLElement');
            @$dom->loadHtml('<?xml encoding="UTF-8">'.$html);
            // dirty fix
            foreach ($dom->childNodes as $item)
                if ($item->nodeType == XML_PI_NODE)
                    $dom->removeChild($item); // remove hack
            $dom->encoding = 'UTF-8'; // insert proper
        }
		//print_r($dom);
		$strings = array();
		$this->strings =& $strings;
		$stringsIndex = array();
		
		
		$xpath = new DOMXPath($dom);
		$this->translateDates($xpath);
		//$text = $xpath->query('//text()[normalize-space() and not(ancestor::script | ancestor::style)]');
		//$translatables = $dom->find('[translate]');
		$translatables = $xpath->query('//*[@translate]');
		foreach ($translatables as $tr){
		
			$index = count($strings);
			
			//$strings[] = trim(_n($tr->innertext));
			//$strings[] = trim(_n($tr->innerHTML));
			$trStr = trim(_n($tr->innerHTML));
			if ( $tr->hasAttribute('data-swete-delimiters') ){
			    
			    $delim = trim($tr->getAttribute('data-swete-delimiters'));
			    if ( $delim ){
			        $delimSplitter = $delim{0};
			        $delimiters = explode($delimSplitter, $delim);
			        $delimiters2 = array();
			        foreach ( $delimiters as $delimiterIdx => $delimiter ){
			            if ( !trim($delimiter) ){
			                continue;
			            }
			            $delimiters2[] = '('.preg_quote($delimiter, '/').')';
			        }
			        $delimiters = $delimiters2;
			        $pattern = '/'.implode('|', $delimiters).'/';
			        $toks = preg_split($pattern, $trStr, -1, PREG_SPLIT_DELIM_CAPTURE);
			        $innerHTML = array();
			        foreach ( $toks as $tokIdx => $tok ){
			            if ( !trim($tok) ){
			                $innerHTML[] = $tok;
			            } else if ( $tokIdx % 2 === 1 ){
			                // It is a delimiter
			                $innerHTML[] = $tok;
			            } else {
			                $strings[] = trim(_n($tok));
			                $stringsIndex[trim(_n($tok))] = $index;
			                $innerHTML[] = '{{$'.$index.'$}}';
			                $index++;
			                if ( $tok{strlen($tok)-1} === ' ' ){
			                    $innerHTML[] = ' ';
			                }
			            }
			        }
			        $tr->innerHTML = implode('', $innerHTML);
			        $trStr = '';
			        
			    }
			    
			} 
			
			if ( $trStr ){
			    $strings[] = trim(_n($trStr));
			    $stringsIndex[trim(_n($trStr))] = $index;
			    $tr->innerHTML = '{{$'.$index.'$}}';
			    $index++;
			}
			
			$gchildren = $xpath->query('./text()', $tr);
			foreach ($gchildren as $gchild) $gchild->isCovered = 1;
		}
		
		
		//$untranslatables = $dom->find('[notranslate]');
		$untranslatables = $xpath->query('//*[@notranslate]');
		foreach ($untranslatables as $tr){
			//error_log('Found untranslatable: '.$tr->outertext);
			
			//$gchildren = $tr->find('text');
			$gchildren = $xpath->query('./text()', $tr);
			//error_log(count($gchildren).' found');
			//foreach ($gchildren as $gchild) $gchild->isCovered = 1;
			foreach ($gchildren as $gchild) $gchild->isCovered = 1;
		}
		
		
		$textX = $xpath->query('//text()[normalize-space() and not(ancestor::script | ancestor::style | ancestor::*[@notranslate] | ancestor::*[@translate])]');
		$text = array();
		foreach ($textX as $x){
			$text[] = $x;
		}
		//echo "Found ".$text->length;
		foreach ($text as $tx){
			if ( !($tx instanceof DOMNode) ) continue;
			if ( !isset($tx->parentNode) ) continue;
			if ( !($tx->parentNode instanceof DOMElement) ) continue;
			// the data-swete-translate is a little different than the notranslate attribute
			// the notranslate attribute confers block level status to its owner tag.
			// data-swete-translate simply marks a segment of text as not to be translated
		    // (or to be translated) within the flow of the document.  Therefore we don't 
		    // use a text node whose parent has the data-swete-translate as an anchor
		    // to start building a group of text.  But we will allow a tag with this
		    // to be included in a group of text (that contains content before and/or after).
		    // The SweteTools::encode() method will take care of variablizing the content
		    // at translation time.
			if ( $tx->parentNode->hasAttribute('data-swete-translate') and $tx->parentNode->getAttribute('data-swete-translate') === '0' ){
			    continue;
			}
			//if ( !trim($tx->innertext) ) continue;
			if ( !trim($tx->nodeValue) ) continue;
			//if ( in_array($tx->parent->tag , array('comment','script','style','code') )) continue;
			if ( in_array(strtolower($tx->parentNode->tagName) , array('comment','script','style','code') )) continue;
			if ( $this->isCovered($tx) ) {
				//echo "This one's covered!!!";
				continue;
			}
			//echo "[".$tx->nodeValue."]";
			//continue;
			$group = array();
			$start = $tx;
			//if ( $tx->parent->children ){
			if ( !isset($tx->parentNode) ) {
				//error_log("skipping ".$tx->nodeValue);
				continue;
			}
			if ( $tx->parentNode->childNodes->length > 0 ){
				$pos = -1;
				//foreach ( $tx->parent->nodes as $idx=>$child ){
				foreach ( $tx->parentNode->childNodes as $idx=>$child ){
					if ( $child === $tx ){
						$pos = $idx;
						break;
					}
				}
				$mypos = $pos;
				for ( $i=$pos; $i>=0; $i--){
					//$node = $tx->parent->nodes[$i];
					$node = $tx->parentNode->childNodes->item($i);
					//if ( $node->tag != 'text' and !in_array($node->tag, self::$inlineTags) ){
					if ( $node->nodeType != XML_TEXT_NODE and 
						!in_array(strtolower(@$node->tagName), self::$inlineTags) and
						!($node instanceof DOMElement and $node->hasAttribute('data-swete-inline'))
						 ){
						break;
					}
					
					//if ( $node->notranslate ){
					if ( $node instanceof DOMElement and  $node->hasAttribute('notranslate') ){
						break;
					}
					
					if ( $node instanceof DOMElement and $node->hasAttribute('data-swete-block') ){
						break;
					}
					$pos = $i;
				}
				//if ( $mypos == $pos or $this->isFirstText($tx->parent, $mypos, $pos)){
				if ( $mypos == $pos or $this->isFirstText($tx->parentNode, $mypos, $pos)){	
					$startIdx = $pos;
					//for ( $i=$startIdx; $i<count($tx->parent->nodes); $i++ ){
					for ( $i=$startIdx; $i<$tx->parentNode->childNodes->length; $i++ ){
						//$node = $tx->parent->nodes[$i];
						$node = $tx->parentNode->childNodes->item($i);
						if ( !$node ) break;
						//if ( $node->tag != 'text' and !in_array($node->tag, self::$inlineTags) ){
						if ( $node->nodeType != XML_TEXT_NODE and 
							!in_array(strtolower(@$node->tagName), self::$inlineTags) and
							!($node instanceof DOMElement and $node->hasAttribute('data-swete-inline'))
							 ){
							break;
						}
						//if ( $node->notranslate ){
						if ( $node instanceof DOMElement and $node->hasAttribute('notranslate') ){
							break;
						}
						
						if ( $node instanceof DOMElement and $node->hasAttribute('data-swete-block') ){
							break;
						}
						
						//if ( $node->tag != 'text' ){
						//	if ( preg_match('/^<'.$node->tag.'[^>]*>/', $node->outertext, $matches) ){
						//		
						//		$node->outertext = preg_replace('/^<'.$node->tag.'([^>]*)>/', '<'.$node->tag.' id="{{R'.count($this->replacements).'R}}">', $node->outertext);
						//		$this->replacements[] = $matches[0];
						//	}
						//	
						//}
						$group[] = $node;
					}
				}
			} else {
				$group[] = $tx;
			}
			
			
			$combinedText = array();
			foreach ($group as $item){
				//$combinedText[] = trim($item->outertext);
				// REquires PHP 5.3.6 or higher.. passing element to saveHtml()
				
				$combinedText[] = preg_replace_callback('#<(\w+)([^>]*)\s*/>#s', create_function('$m', '
					$xhtml_tags = array("br", "hr", "input", "frame", "img", "area", "link", "col", "base", "basefont", "param");
					return in_array($m[1], $xhtml_tags) ? "<$m[1]$m[2]/>" : "<$m[1]$m[2]></$m[1]>";
					'), 
					$dom->saveXml($item)
				);
			}
			$combinedText = implode(' ', $combinedText);
			$combinedText = _n($this->replaceStrings($combinedText));
			if ( !trim(str_ireplace('&nbsp;','', $combinedText)) ){
			
				continue;
			
			}
			
			if ( isset($stringsIndex[$combinedText]) ){
				$index = $stringsIndex[$combinedText];
			} else {
				$index = count($strings);
				$strings[] = $combinedText;
				$stringsIndex[$combinedText] = $index;
			}
			foreach ($group as $gnode){
				
				//$gchildren = $gnode->find('text');
				$gchildren = @$xpath->query('./text()', $gnode);
				if ( !$gchildren ) continue;
				foreach ($gchildren as $gchild) $gchild->isCovered = 1;
				
			}
			
			//$group[0]->outertext = '{{$'.$index.'$}}';
			//$group[0]->nodeValue = '{{$'.$index.'$}}';
			
			for ( $i=1; $i<count($group); $i++){
				//$group[$i]->outertext = '';
				//if ( !@$group[$i] ) continue;
				if ( @$group[$i]->parentNode )
					$group[$i]->parentNode->removeChild($group[$i]);
				
			}
			if ( !@$group[0] ) continue;
			if ( !@$group[0]->parentNode ) continue;
			$group[0]->parentNode->replaceChild($dom->createTextNode('{{$'.$index.'$}}'), $group[0]);
			
		
			
		}
		
		
		// Now we need to translate the keywords and the description
		//foreach ($dom->find('meta') as $el){
		foreach ($xpath->query('//meta[@name="keywords" or @name="description"]') as $el){
			
			//$content = _n($el->content);
			if ( !$el->hasAttribute('content') ) continue;
			$content = _n($el->getAttribute('content'));
			//if ( $content and in_array(strtolower(strval($el->name)), array('keywords','description')) ){
			
			if ( isset($stringsIndex[$content]) ){
				$index = $stringsIndex[$content];
			} else {
				$index = count($strings);
				$strings[] = $content;
				$stringsIndex[$content] = $index;
			}
			//$el->content = '{{$'.$index.'$}}';
			$el->setAttribute('content', '{{$'.$index.'$}}');
			//}
		}
		
	
		$this->strings = array_map(array($this,'cleanString'), $this->strings);
		//return $dom->save();
		return $dom->saveHtml();
		
	
	}
	
	
	//WebLite.Translator.prototype.isCovered = function(node){
	function isCovered($node){
		return ( isset($node->isCovered) and $node->isCovered == 1 );
		//$p = $node->parent();
		//while ($p){
		//	if ( preg_match('/^\{\{\$\d+\$\}\}$/', $p->innertext) ) return true;
		//	$p = $p->parent();
		//}
		//return false;
		
	}
	
	public function cleanString($str){
		return str_replace(array("\t","\r\n","\r","\n",'  '),array(' ',"\n",' ',' ',' '), $str);
	}
	
	public function replaceStrings($html){
		return preg_replace_callback('/\{\{\$([0-9]+)\$\}\}/', array($this,'replaceString'), $html);
	}
	
	private function getDateFormatter($locale, $format){
	    if ( !isset($this->dateFormatters[$locale.'.'.$format]) ){
	        $this->dateFormatters[$locale.'.'.$format] = IntlDateFormatter::create(
                $locale, 
                IntlDateFormatter::NONE,
                IntlDateFormatter::NONE,
                null,
                null,
                $format
            );
	        
	    } 
	    return $this->dateFormatters[$locale.'.'.$format];
	}
	
	public function translateDates(DOMXPath $xpath){
	    if ( $this->sourceDateLocale and $this->targetDateLocale and class_exists('IntlDateFormatter') ){

            $dates = $xpath->query('//*[@data-date-format]');
            foreach ($dates as $date){
                $sourceFormat = $date->getAttribute('data-date-format');
                $sourceFormatter = $this->getDateFormatter(
                    $this->sourceDateLocale, 
                    $sourceFormat
                );
                
                $targetFormat = $sourceFormat;
                if ( $date->hasAttribute('data-date-format-target') ){
                    $targetFormat = $date->getAttribute('data-date-format-target');
                }
                $targetFormatter = $this->getDateFormatter(
                    $this->targetDateLocale,
                    $targetFormat
                );
                $dateString = trim($date->nodeValue);
                $dt = $sourceFormatter->parse($dateString);
                if ( $dt ){
                    $date->nodeValue = $targetFormatter->format($dt);
                } else {
                    error_log("Failed to parse date {$dateString} with format {$sourceFormat}");
                }
                $date->setAttribute('data-swete-translate', '0');
            }
	    }
	}
	
	public function translate($html, $approvalLevel=0){
	
		$out = $this->extractStrings($html);
		foreach ($this->strings as $key=>$value){
			$this->strings[$key] = $this->translationMemory->getString($value, $approvalLevel);
		}
		
		$this->translationMemory->save();
		
		//$out = $dom->save();
		$out = preg_replace_callback('/\{\{\$([0-9]+)\$\}\}/', array($this,'replaceString'), $out);
		
		
		return $out;
		//return $strings;
	}
	
	public function replaceString($matches){
		return $this->strings[$matches[1]];
	}
	
	
	private function isFirstText($node, $mypos, $pos){
		for ( $i=$pos; $i<$mypos; $i++ ){
			//if ( $node->nodes[$i]->tag == 'text' and trim($node->nodes[$i]->innertext) ) return false;
			$child = $node->childNodes->item($i);
			if ( $child->nodeType == XML_TEXT_NODE and trim($child->nodeValue) ) return false;
		}
		return true;
	}
	
	public static function test(){
	
		
		//$dom = file_get_html('http://www.mozartschool.com/register.htm');
		//$text = $dom->find('text');
		//foreach ($text as $tx){
		//	if ( !trim($tx->innertext) ) continue;
		//	echo $tx->tag."\n".$tx->parent->tag."\n";
		//	echo $tx->innertext."\n\n";
		//}
		//exit;
		$translator = new WebLite_HTML_Translator();
		$translator->translate(file_get_contents('http://dev.translate.weblite.ca/index.html'));
		print_r($translator->strings);
		//print_r($translator->translate(file_get_contents('http://en.wikipedia.org/wiki/Richard_Marx')));
		
		//$translator->translateNode($dom->find('html',0));
		//echo $dom->save();
		//print_r($translator->strings);
	}
	
	
}


interface WebLite_Translation_Memory {
	public function getString($id, $approvalLevel=0);
	
	public function save();
}

class Default_Translation_Memory implements WebLite_Translation_Memory {
	public function getString($id, $approvalLevel=0){
		return $id;
	}
	
	public function save(){}
}



if ( @$argv and $argv[1] == 'test' ){
	WebLite_HTML_Translator::test();
}