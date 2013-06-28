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
require_once dirname(__FILE__).'/../lib/simple_html_dom.php';
//require_once 'lib/simple_html_dom.php';
//if ( !function_exists('_n') ) require_once dirname(__FILE__).'/webpage.functions.inc.php';
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
		$dom = str_get_html($html);
		//print_r($dom);
		$strings = array();
		$this->strings =& $strings;
		$stringsIndex = array();
		
		
		$text = $dom->find('text');
		
		$translatables = $dom->find('[translate]');
		foreach ($translatables as $tr){
			$index = count($strings);
			
			$strings[] = trim(_n($tr->innertext));
			$stringsIndex[trim(_n($tr->innertext))] = $index;
			$tr->innertext = '{{$'.$index.'$}}';
		}
		
		
		$untranslatables = $dom->find('[notranslate]');
		foreach ($untranslatables as $tr){
			//error_log('Found untranslatable: '.$tr->outertext);
			$gchildren = $tr->find('text');
			//error_log(count($gchildren).' found');
			foreach ($gchildren as $gchild) $gchild->isCovered = 1;
		}
		
		
		
		foreach ($text as $tx){
			if ( !trim($tx->innertext) ) continue;
			if ( in_array($tx->parent->tag , array('comment','script','style','code') )) continue;
			if ( $this->isCovered($tx) ) {
				//echo "This one's covered!!!";
				continue;
			}

			//continue;
			$group = array();
			$start = $tx;
			if ( $tx->parent->children ){
				$pos = -1;
				foreach ( $tx->parent->nodes as $idx=>$child ){
					if ( $child === $tx ){
						$pos = $idx;
						break;
					}
				}
				$mypos = $pos;
				for ( $i=$pos; $i>=0; $i--){
					$node = $tx->parent->nodes[$i];
					if ( $node->tag != 'text' and !in_array($node->tag, self::$inlineTags) ){
						break;
					}
					if ( $node->notranslate ){
						break;
					}
					$pos = $i;
				}
				if ( $mypos == $pos or $this->isFirstText($tx->parent, $mypos, $pos)){
					
					$startIdx = $pos;
					for ( $i=$startIdx; $i<count($tx->parent->nodes); $i++ ){
						$node = $tx->parent->nodes[$i];
						if ( !$node ) break;
						if ( $node->tag != 'text' and !in_array($node->tag, self::$inlineTags) ){
							break;
						}
						if ( $node->notranslate ){
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
				$combinedText[] = trim($item->outertext);
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
				$gchildren = $gnode->find('text');
				foreach ($gchildren as $gchild) $gchild->isCovered = 1;
				
			}
			
			$group[0]->outertext = '{{$'.$index.'$}}';
			
			for ( $i=1; $i<count($group); $i++){
				$group[$i]->outertext = '';
				
			}
		
			
		}
		
		
		// Now we need to translate the keywords and the description
		foreach ($dom->find('meta') as $el){
			
			$content = _n($el->content);
			if ( $content and in_array(strtolower(strval($el->name)), array('keywords','description')) ){
				if ( isset($stringsIndex[$content]) ){
					$index = $stringsIndex[$content];
				} else {
					$index = count($strings);
					$strings[] = $content;
					$stringsIndex[$content] = $index;
				}
				$el->content = '{{$'.$index.'$}}';
			}
		}
		
	
		$this->strings = array_map(array($this,'cleanString'), $this->strings);
		return $dom->save();
	
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
			if ( $node->nodes[$i]->tag == 'text' and trim($node->nodes[$i]->innertext) ) return false;
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