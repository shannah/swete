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
// JSLikeHTMLElement will be included already in the default WebLite_Translate class import
//require_once dirname(__FILE__).'/../lib/JSLikeHTMLElement.php';

// We need to include the default translate class because it has some global functions
// defined
require_once dirname(__FILE__).'/WebLite_Translate.class.php'; 

class WebLite_HTML_Translator_v2 {
    private $dateFormatters = array();
    public $sourceDateLocale = null;
    public $targetDateLocale = null;
    public $useHtml5Parser = false;
    
    // Optional flag set to strip all swete-block tags
    // out before extracting text.  This is useful
    // when all we want to do is calculate the translation checksum
    // since we don't want to the checksum to include translations occurring
    // inside blocks.
    public $excludeBlocks = false;
    
    // This flag will be set if blocks are found when trying to exclude blocks.
    private $foundBlocks = false;
    private $foundBlockIds = array();
    public function foundBlocks() {
        return $this->foundBlocks;
    }
    public function foundBlockIds() {
        return $this->foundBlockIds;
    }
    
    
	
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
	
	
	public function __construct(WebLite_HTML_Translator_v2 $src = null){
	    if (isset($src)) {
	        $this->dateFormatters = $src->dateFormatters;
	        $this->sourceDateLocale = $src->sourceDateLocale;
	        $this->targetDateLocale = $src->targetDateLocale;
	        $this->useHtml5Parser = $src->useHtml5Parser;
	        $this->excludeBlocks = $src->excludeBlocks;
	        $this->translationMemory = $src->translationMemory;
	    } else {
	        $this->setTranslationMemory(new Default_Translation_Memory());
	    }
		
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
		$strings = array();
		$this->strings =& $strings;
		$stringsIndex = array();
		$xpath = new DOMXPath($dom);
		
		if ($this->excludeBlocks) {
		    $blocks = $xpath->query('//swete-block[@id]');
		    foreach ($blocks as $block) {
		        $this->foundBlocks = true;
		        $this->foundBlockIds[] = $block->getAttribute('id');
		        error_log("Removing block ".$block->getAttribute('id'));
		        $block->parentNode->removeChild($block);
		    }
		}
		
		$this->translateDates($xpath);
		$translateAttrs = $xpath->query('//*[@data-swete-translate-attrs or @alt or @title]');
		$otherAtts = array('title','alt');
		foreach ( $translateAttrs as $el ){
			if ( $el->hasAttribute('data-swete-translate-attrs') ){
				$attNames = explode(' ', $el->getAttribute('data-swete-translate-attrs'));
			} else {
				$attNames = array();
			}
			
			foreach ( $otherAtts as $attName ){
				if ( $el->hasAttribute($attName) ){
					$attNames[] = $attName;
				}
			}
			foreach ( $attNames as $attName ){
				$attVal = $el->getAttribute($attName);
				if ( $attVal and trim($attVal) ){
					$index = count($strings);
					$trimmedAttVal = trim(_n($attVal));
					$strings[] = $trimmedAttVal;
			        $stringsIndex[$trimmedAttVal] = $index;
			        $el->setAttribute($attName, '{{$'.$index.'$}}');
			        $index++;
				}
				
			}
			
		}
		
		$translatables = $xpath->query('//*[@translate]');
		foreach ($translatables as $tr){
		
			$index = count($strings);
			
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
			                $trimmedTok = trim(_n($tok));
			                $strings[] = $trimmedTok;
			                $stringsIndex[$trimmedTok] = $index;
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
			    $trimmedStr = trim(_n($trStr));
			    $strings[] = $trimmedStr;
			    $stringsIndex[$trimmedStr] = $index;
			    $tr->innerHTML = '{{$'.$index.'$}}';
			    $index++;
			}
			
			$gchildren = $xpath->query('./text()', $tr);
			foreach ($gchildren as $gchild) $gchild->isCovered = 1;
		}
		
		$untranslatables = $xpath->query('//*[@notranslate]');
		foreach ($untranslatables as $tr){
			$gchildren = $xpath->query('./text()', $tr);
			foreach ($gchildren as $gchild) $gchild->isCovered = 1;
		}
		
		
		
		$textX = $xpath->query('//text()[not(ancestor::script | ancestor::style | ancestor::*[@notranslate] | ancestor::*[@translate])]');
		$text = array();
		foreach ($textX as $x){
			$text[] = $x;
		}
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
			if ( !trim($tx->nodeValue) ) continue;
			if ( in_array(strtolower($tx->parentNode->tagName) , array('comment','script','style','code') )) continue;
			if ( $this->isCovered($tx) ) {
				continue;
			}
			$group = array();
			$start = $tx;
			if ( !isset($tx->parentNode) ) {
				continue;
			}
			if ( $tx->parentNode->childNodes->length > 0 ){
				$pos = -1;
				foreach ( $tx->parentNode->childNodes as $idx=>$child ){
					if ( $child === $tx ){
						$pos = $idx;
						break;
					}
				}
				$mypos = $pos;
				for ( $i=$pos; $i>=0; $i--){
					$node = $tx->parentNode->childNodes->item($i);
					if ( $node->nodeType != XML_TEXT_NODE and /*$node->nodeType != XML_ENTITY_NODE and*/
                            !in_array(strtolower(@$node->tagName), self::$inlineTags) and
                            !($node instanceof DOMElement and $node->hasAttribute('data-swete-inline'))
                             ){
						break;
					}
					
					if ( $node instanceof DOMElement and  $node->hasAttribute('notranslate') ){
						break;
					}
					
					if ( $node instanceof DOMElement and $node->hasAttribute('data-swete-block') ){
						break;
					}
					$pos = $i;
				}
				if ( $mypos == $pos or $this->isFirstText($tx->parentNode, $mypos, $pos)){	
					$startIdx = $pos;
					for ( $i=$startIdx; $i<$tx->parentNode->childNodes->length; $i++ ){
						$node = $tx->parentNode->childNodes->item($i);
						if ( !$node ) break;
						if ( $node->nodeType != XML_TEXT_NODE and /*$node->nodeType != XML_ENTITY_NODE and*/
							!in_array(strtolower(@$node->tagName), self::$inlineTags) and
							!($node instanceof DOMElement and $node->hasAttribute('data-swete-inline'))
							 ){
							break;
						}
						if ( $node instanceof DOMElement and $node->hasAttribute('notranslate') ){
							break;
						}
						
						if ( $node instanceof DOMElement and $node->hasAttribute('data-swete-block') ){
							break;
						}
						$group[] = $node;
					}
				}
			} else {
				$group[] = $tx;
			}
			
			$combinedText = array();
			foreach ($group as $item){
				// REquires PHP 5.3.6 or higher.. passing element to saveHtml()
				
				$combinedText[] = preg_replace_callback('#<(\w+)([^>]*)\s*/>#s', create_function('$m', '
					$xhtml_tags = array("br", "hr", "input", "frame", "img", "area", "link", "col", "base", "basefont", "param");
					return in_array($m[1], $xhtml_tags) ? "<$m[1]$m[2]/>" : "<$m[1]$m[2]></$m[1]>";
					'), 
					$dom->saveXml($item)
				);
			}
			$combinedText = implode('', $combinedText);
			$leadingWhiteSpace = '';
			$trailingWhiteSpace = '';
			if ( preg_match('#^[\p{Z}\s]+#u', $combinedText, $m1 ) ){
			    $leadingWhiteSpace = $m1[0];
			}
			if ( preg_match('#[\p{Z}\s]+$#u', $combinedText, $m1 ) ){
			    $trailingWhiteSpace = $m1[0];
			}
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
				$gchildren = @$xpath->query('./text()', $gnode);
				if ( !$gchildren ) continue;
				foreach ($gchildren as $gchild) $gchild->isCovered = 1;
			}
			
			for ( $i=1; $i<count($group); $i++){
				if ( @$group[$i]->parentNode )
					$group[$i]->parentNode->removeChild($group[$i]);
				
			}
			if ( !@$group[0] ) continue;
			if ( !@$group[0]->parentNode ) continue;
			$textNodeContent = $leadingWhiteSpace.'{{$'.$index.'$}}'.$trailingWhiteSpace;
			$group[0]->parentNode->replaceChild($dom->createTextNode($textNodeContent), $group[0]);

		}
		
		
		// Now we need to translate the keywords and the description
		foreach ($xpath->query('//meta[@name="keywords" or @name="description"]') as $el){
			if ( !$el->hasAttribute('content') ) continue;
			$content = _n($el->getAttribute('content'));
			if ( isset($stringsIndex[$content]) ){
				$index = $stringsIndex[$content];
			} else {
				$index = count($strings);
				$strings[] = $content;
				$stringsIndex[$content] = $index;
			}
			$el->setAttribute('content', '{{$'.$index.'$}}');
		}
		
		foreach ($xpath->query('//*[@placeholder or @title or @data-swete-translate-atts]') as $el){
		    $atts = array('placeholder', 'title');
		    if ($el->hasAttribute('data-swete-translate-atts')) {
		        $append = array_map('trim', explode(' ', $el->getAttribute('data-swete-translate-atts')));
		        foreach ($append as $a) {
		            $atts[] = $a;
		        }
		    }
			foreach ($atts as $att) {
			    if (!$el->hasAttribute($att)) {
			        continue;
			    }
			    $content = _n($el->getAttribute($att));
                if ( isset($stringsIndex[$content]) ){
                    $index = $stringsIndex[$content];
                } else {
                    $index = count($strings);
                    $strings[] = $content;
                    $stringsIndex[$content] = $index;
                }
                $el->setAttribute($att, '{{$'.$index.'$}}');
            }
		}
		
	
		$this->strings = array_map(array($this,'cleanString'), $this->strings);
		return $dom->saveHtml();

	}
	
	
	function isCovered($node){
		return ( isset($node->isCovered) and $node->isCovered == 1 );
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
	
	
}
