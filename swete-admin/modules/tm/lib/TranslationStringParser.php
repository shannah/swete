<?php
/**
 * A functor class whose job it is to parse a string.
 *
 * @example 
 * <code>
 * $xml = TranslationStringParser::toXML("<p>Hello World</p>", $params);
 * echo $xml;  // "<g id="1">Hello World</g>"
 * $string = TranslationStringParser::toString($xml, $params);
 * echo $string; // "<p>Hello World</p>"
 * </code>
 *
 */
class TranslationStringParser {
	private $c;
	private $pos;
	private $string;
	private $len;
	
	protected $matches;
	private $stack;
	protected $out;
	
	/**
	 *-------------- STATIC METHODS ----------------------------------------
	 */
	
	/**
	 * Converts an HTML string to XML with <x> and <g> tags for placeholders.
	 * Also outputs the parameters so that the string can be re-merged later
	 * by the toString() method.
	 *
	 * @param string $string The HTML string to convert.
	 * @param out array &$params Out parameter for associative array  Parameters
	 *	will be of the form:
	 *	Array(
	 *		0 => HTML tag & attributes for first tag.
	 *		1 => HTML tag & attributes for second tag.
	 *		2 => HTML tag & attributes for third tag.
	 *		etc ...
	 *	)
	 
	 * 	Where the order of the array corresponds with the order in which
	 *	the open tags are encountered when parsing the HTML linearly.
	 *
	 * 	For example, given the string:
	 *	<div id="1">Mark<span id="3">said</span> <br/> <p i="2">go home</p></div>
	 *
	 * The $params array would contain:
	 *	Array(
	 *		0 => div id="1"
	 *		1 => span id="3"
	 *		2 => br/
	 *		3 => p id="2"
	 *	)
	 *
	 * @return string An XML string.
	 **/
	public static function toXML($string, &$params){
		$parser = new TranslationStringParser($string);
		$parser->parse();
		$params = $parser->matches;
		return $parser->out;
	}
	
	/**
	 * Takes parsed XML and corresponding parameters, and outputs a string.
	 *
	 * @param string $xml The XML string that was parsed.
	 * @param array $params Associative array of parameters as output
	 * @return string An HTML string
	 */
	public static function toHTML($xml, $params){
		$parser = new TranslationStringParser($xml);
		$string = $parser->unparse($params);
		return $string;
	}
	
	/**
	 *--------------- END STATIC METHODS -----------------------------------
	 */
	 
	 
	
	public function __construct($string){
		$this->string = $string;
		$this->len = strlen($string);
		$this->pos = -1;
		$this->c = null;
		$this->matches = array();
		$this->stack = array();
	}
	
	/**
	 * Converts a string from HTML to XML ... it decodes HTML entities, then
	 * encodes XML entities.
	 */
	private function encode($string){
		return $this->xmlentities($string, ENT_QUOTES, 'UTF-8') ;
	}
	
	
	/**
	 * Encodes a string with XML entities.
	 */
	protected function xmlentities($string) {
		$map = array(
			//'&'=>'&amp;',
			//'"'=>'&quot;',
			//"'"=>'&apos;',
			'<'=>'&lt;',
			'>'=>'&gt;'
		);
		foreach ($map as $k=>$v){
			$string = mb_ereg_replace($k,$v, $string);
		}
		
		return $string;
	}
	
	
	/**
	 * Decodes XML entities from a string.
	 */
	protected function xml_entity_decode($string) {
		$map = array(
			//'&'=>'&amp;',
			//'"'=>'&quot;',
			//"'"=>'&apos;',
			'<'=>'&lt;',
			'>'=>'&gt;'
		);
		foreach ($map as $v=>$k){
			$string = mb_ereg_replace($k,$v, $string);
		}
		
		return $string;
	
		
	}
	
	/**
	 * Converts a string from XML to HTML.  It first decodes XML entites,
	 * then encodes the HTML entities.
	 */
	private function decode($string){
		return $this->xml_entity_decode($string);
	}
	
	/**
	 * Unparses parameter array that was generated from the parse() method.
	 *
	 * @param array $params Associative array of parameters that were parsed out
	 * of a string.
	 * @return string The unparsed string.
	 */
	private function unparse($params){
	
		$out = array();
		$pos = 0;
		$stack = array();
		$len = strlen($this->string);
		while (true){
			$bpos = strpos($this->string, '<', $pos);
			if ( $bpos === false ){
				$out[] = $this->decode(substr($this->string, $pos));
				break;
			} else {
				$out[] = $this->decode(substr($this->string, $pos, $bpos-$pos));
				if ( $bpos >= $len-1 ){
					$out[] = $this->encode('<');
					break;
				} else {
					$c = $this->string{$bpos+1};
					if ( $c === '/' ){
						if ( !$stack ){
							//throw new Exception("Parse error at position $bpos.  Closing tag found with no matching open tag.");
							$out[] = $this->encode('<').$c;
							$pos = $bpos+2;
							continue;
							
						}
						$id = array_pop($stack);
						if ( !isset($params[$id]) ){
							throw new Exception("Parse error at position $bpos.  No corresponding input tag found.");
						}
						$p = $params[$id];
						$tagname = '';
						if ( preg_match('#^[a-zA-Z0-9\-:]+#', $p, $matches)){
							$tagname = $matches[0];
							
						} else {
							throw new Exception("Parse error.  Invalid tag name found at position $bpos");
						}
						$out[] = '</'.$tagname.'>';
						$cpos = strpos($this->string, '>', $bpos);
						if ( $cpos === false ){
							throw new Exception("Parse error.  Invalid closing tag found at $bpos.  No closing bracket.");
						}
						$pos = $cpos+1;
						
					} else if ( $c === 'x' or $c === 'g' or $c === 'v' ){
						$id = '';
						if ( preg_match('#[^><]+id="(\d+)"[^>]*>#s', $this->string, $matches, 0, $bpos) ){
							$id = intval($matches[1])-1;
						} else {
							$out[] = $this->encode('<').$c;
							$pos = $bpos+2;
							continue;
							//throw new Exception("Parse error.  Invalid $c tag at $bpos.  No id attribute.  String[".$this->string."] Checking portion: [".substr($this->string, $bpos)."]");
							
						}
						if ( !isset($params[$id]) ){
							throw new Exception("Replacement error at $bpos.  The passed parameters don't have a key with id $id as required by the x tag.");
							
						}
						$out[] = '<'.$params[$id].'>';
						$cpos = strpos($this->string, '>', $bpos);
						if ( $cpos === false ){
							throw new Exception("Parse error.  Invalid closing tag found at $bpos.  No closing bracket.");
						}
						$pos = $cpos+1;
						if ( $c === 'g' or $c === 'v' ) $stack[] = $id;
						
					} else {
						//throw new Exception("Parse error. Invalid tag found at $bpos");
						$out[] = $this->encode('<').$c;
						$pos = $bpos+2;
					}
				}
			}
		}
		
		return implode('', $out);
	}
	
	/**
	 * Parses the string in this parser to produce an associative array of 
	 * parameters.
	 */
	private function parse(){
		$mode = 0;
		$out = array();
		//echo $this->len;
		//echo '['.($this->pos).']';
		while (true) {
			if ( $this->pos >= $this->len-1 ) break;
			$this->c = $this->string{(++$this->pos)};
			//echo '['.$this->c.']';
			//print_r($this->matches);
			switch ($mode){
				case 0:
					//echo "case 0";
					// We are in default mode (outside tags).
					$tagpos = strpos($this->string, '<', $this->pos);
					if ( $tagpos !== false ){
						$nextTagPos = strpos($this->string, '<', $tagpos+1);
						$nextClosePos = strpos($this->string,'>', $tagpos+1);
						//echo "tagpos: $tagpos;";
						if ( $nextClosePos === false ){
							$tagpos = false;
						}
						else if ( $nextTagPos !== false and $nextTagPos < $nextClosePos ){
							$tagpos = $nextTagPos;
						}
					}
					if ( $tagpos !== false ){
						if ( $tagpos>$this->pos ) $out[] = $this->encode(substr($this->string, $this->pos, $tagpos-$this->pos));
						$mode = 1;
						$this->pos = $tagpos;
						//echo "about to break";
						break;
					} else {
						// We found no tags.
						$out[] = $this->encode(substr($this->string, $this->pos));
						$this->pos = $this->len-1;
						$mode = 0;
						break;
					}
					
				case 1:
					//echo "[case 1 {$this->c}]";
					// We are inside a possible tag.
					$closingBracketPos = strpos($this->string, '>', $this->pos);
					if ( $closingBracketPos === false ){
						// No closing bracket was found... we should fix the opening
						// bracket
						$out[] = $this->encode('<').$this->encode(substr($this->string, $this->pos));
						$this->pos = $this->len-1;
						$mode = 0;
						break;
					} else {
						// We have a closing bracket
						// let's do this.
						$tagContents = substr($this->string, $this->pos, $closingBracketPos-$this->pos);
						if ( $this->c == '/' ){
							// This is a closing tag
							// Check the stack
							$tagName = trim(substr($tagContents,1));
							
							$tempStack = array();
							while ( $this->stack ){
								$item = array_pop($this->stack);
								if ( strcasecmp($item['tagName'], $tagName) === 0 ){
									$tagC = 'g';
									if ( $item['var'] ){
										$tagC = 'v';
										
									}
									$out[] = '</'.$tagC.'>';
									$out[$item['outIndex']] = '<'.$tagC.' id="'.($item['id']+1).'">';
									
									break;
								} else {
									// This tag has already been added as an <x> tag
									// so we'll just mosey on
								}
							}
						} else if ( ctype_alpha($this->c) ) {
							// This is an open tag.
							list($tagName) = explode(' ', $tagContents);
							$id = count($this->matches);
							$this->matches[] = $tagContents;
							$outIndex = count($out);
							$out[] = '<x id="'.($id+1).'"/>';
							$var = ( strcasecmp($tagName, 'v') === 0 or ( strcasecmp($tagName, 'span') === 0 and preg_match('/data-swete-translate="[^"]+"/', $tagContents) ) );
							if ( $var ){
								
								$out[$outIndex] = '<v id="'.($id+1).'"/>';
							}
							$this->stack[] = array('tagName'=>$tagName, 'outIndex'=>$outIndex, 'id'=>$id, 'var'=>$var);
							
						} else if ( $this->c == '!' ){
							// This is a comment
							// Do nothing here ... we just skip it.
						
						} else {
							// This was a lone open bracket
							$out[] = $this->encode('<');
							$mode = 0;
							$this->pos++;
							break;
						}
							
						$this->pos = $closingBracketPos;
						$mode = 0; // back to default mode
				}
					
			}
			
			
		}
		$this->out = implode('', $out);
		return $out;
	}
	
	
	
	
}

