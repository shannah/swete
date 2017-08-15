<?php
/**
 * @brief A class that is able to add tags to parts of an HTML string that match 
 * a one of a set of specified regular expressions.  This is handy for preprocessing
 * a string before passing it to the TMTools::encode() function (which will apply
 * further string processing) so that we can wrap certain patterns in HTML tags that
 * will be recognized by encode() and further converted.
 *
 * <p>The most obvious use of this is for dates and numbers that we want to wrap in
 * @code <span data-swete-translate="0">..</span>@endcode tags.  Then strings that
 * are the same except for numbers and dates will be regarded as the same string
 * as far as translation is concerned.</p>
 *
 * <h2>Example</h2>
 *
 * @code
 * $tagger = new TranslationStringTagger;
 *		$tagger->addPatternReplacement(array(
 *			'/\d{4}-\d{2}-\d{2}/' => '<span data-swete-translate="0" data-date-format="Y-m-d">$0</span>',
 *			'/\d{2}\/\d{2}\/\d{2}/' => '<span data-swete-translate="0" data-date-format="m/d/y">$0</span>',
 *			'/(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday) (January|February|March|April|May|June|July|August|September|October|November|December) \d{1,2}, \d{4}' => 
 *				'<span data-swete-translate="0" data-date-format="l F j, Y">$0</span>',
 *			'/(?<!&)[0-9\.,]+/' => '<span data-swete-translate="0" data-number-format="0">$0</span>'
 *		));
 * $tagged = $tagger->addTags('Today is 2012-06-01');
 *      // Today is <span data-swete-translate="0">2012-06-01</span>
 * 
 * @endcode
 */
class TranslationStringTagger {

	private $patterns;
	
	public function __construct(){
		$this->patterns = array();
	}
	
	
	/**
	 * @brief Adds a regular expression matching and replacement expression with a specified
	 * priority.
	 *
	 * @param String $pattern The Regular expression pattern.  This should include the regex
	 *  delimiters (e.g.  '/bob/' not 'bob')
	 * @param String $replacement The replacement for matching patterns.  This is where you
	 * should specify how the pattern should be wrapped.
	 * @param int $priority The priority of this pattern.  Patterns will be applied to 
	 * 	strings in decreasing order of priority.  (If a call is made to compilePatterns()
	 * 	after adding all of the patterns.
	 * @returns TranslationStringTagger Self for chaining.
	 */
	public function addPatternReplacement($pattern, $replacement, $priority = 0){
		
		//check for existing entry. If it exists, update it
		foreach($this->patterns as $p)
		{
			if($p['pattern'] === $pattern)
			{
				$p['replacement'] = $replacement;
				$p['priority'] = $priority;
			}
		}
		
		//entry not found. Create new entry
		$patternEntry = array('pattern'=>$pattern, 'replacement'=>$replacement, 'priority'=>$priority);
		array_push($this->patterns, $patternEntry);
		
		return $this;
	}
	
	/**
	 * @brief A convenience method to add patterns in one method.  
	 * 
	 * @param array(String=>String) $patterns Associative array of regex patterns and 
	 * replacement strings.
	 * @param int $priority The priority that is assigned to each of these expressions.
	 * @returns TranslationStringTagger Self for chaining.
	 */
	public function addPatternReplacements(array $patterns, $priority=0){
		
		foreach($patterns as $pattern=>$replacement)
			$this->addPatternReplacement($pattern, $replacement, $priority);
			
		return $this;
	}
	
	
	/**
	 * @brief Removes a pattern.
	 *
	 * @param String $pattern The pattern to be removed.
	 * @returns TranslationStringTagger Self for chaining
	 */
	public function removePatternReplacement($pattern){
	
		// TODO: Implement method
		foreach($this->patterns as $p)
		{
			if($p['pattern' == $pattern])	//this should delete the array entry containing that pattern.
			{
				unset($p);
				return;
			}
		}
		
		return $this;
	
	}
	
	
	/**
	 * @brief Removes multiple patterns at once.
	 * @param array(String) An array of pattern strings to be removed.
	 * @returns TranslationStringTagger Self for chaining.
	 */
	public function removePatternReplacements(array $patterns){
	
		foreach($patterns as $p)
			$this->removePatternReplacement($p);	
	
		return $this;
	}
	
	
	/**
	 * @brief Returns an array of patterns that have been registered.
	 * @returns array An array of patterns in the order in which they will
	 * are intended to be applied (i.e. highest priority first).  Each
	 * pattern is itself an associative array with 3 keys:<ol>
	 *		<li>pattern: String</li>
	 *		<li>replacement: String</li>
	 *		<li>priority: int</li>
	 *	</ol>
	 *
	 * @code
	 * //Return structure
	 * array(
	 *		0 => array(
	 *			'pattern' => '/\d{4}-\d{2|-\d{2}/',
	 *			'replacement' => '<span data-swete-translate="0">$0</span>',
	 *			'priority' => 10
	 *		),
	 *		1 => etc...
	 * )
	 * @endcode
	 */ 
	public function getPatterns(){
		// Return structure e.g.
		
		//sort the patterns before returning them
		$this->compilePatterns();
		
		return $patterns;	
	}
	
	
	/**
	 * @brief Applies pattern replacement to a provided string to add tags.  This will
	 * only affect text elements - not the content of a tag.  E.g. If your string 
	 * contains: <span id="23">23</span> and a pattern replacement should replace 
	 * the number 23 with <b>23</b>, then the result will be
	 *
	 * @code
	 * <span id="23"><b>23</b></span>
	 * @endcode
	 * Not
	 * @code
	 * <span id="<b>23</b>"><b>23</b></span>
	 * @endcode
	 *
	 * @param String $string The HTML string have patterns applied to it.
	 * @returns String $str the tagged string.
	 */
	public function addTags($string){

		$tags = array();	//contains tags that should be ignored by our function
		$strings = array();	//content that will be parsed by our function
		
		//sort the patterns by priority
		$this->compilePatterns();	
		
		//TODO: tag the input string...
		
		//split the HTML into an array of substrings, which are separated by tags.
		print "SPLITTING STRINGS...<br>";
		$this->splitString($string, $strings, $tags);
		
		print "Strings:<br>";
		foreach($strings as $s)
			print "[" . htmlspecialchars($s) . "]<br>";
		
		print "Tags:<br>";
		foreach($tags as $t)
			print "[" . htmlspecialchars($t) . "]<br>";
		
		//replace matching patterns
		print "REPLACING PATTERNS...<br>";
		$this->replacePatterns($strings);
		
		//reassemble the strings and tags
		print "REASSEMBLING STRINGS...<br>";
		$result = $this->reassembleStrings($strings, $tags);
		print "RESULT = ". htmlspecialchars($result) . "<br>";
		
		return $result;
	}
	
	//this function is called after all of the patterns have been entered, and before we start adding tags to the string
	//Sorts the patterns in order of priority
	//This lets the user enter the patterns first, and the patterns are only sorted when they are actually needed.
	private function compilePatterns()
	{
		//sort the patterns by priority
		usort($this->patterns, "TranslationStringTagger_compareByPriority");
	}
	
	//replace all patterns in the strings array that match the list of regular expressions given by the user.
	public function replacePatterns(&$strings)
	{
		foreach($this->patterns as $p)
		{
			$pattern = $p['pattern'];
			$replaceWith = $p['replacement'];
			
			print "Pattern: " . htmlspecialchars($pattern) . "<br>";
			print "Replace: " . htmlspecialchars($replaceWith) . "<br><br>";
			
			foreach($strings as $s)
				$s = preg_replace($pattern, $replaceWith, $s);
		}
	}
	
	//splits the input into two separate arrays.
	//$strings will contain the plaintext sections of the HTML in an array in the order they appear
	//$tags will contain the tagged sections of the HTML in the order they appear.
	//note: this function WILL create empty entries in the output arrays. This is to preserve the order of the entries.
	//To reassemble the string, combine strings and tags in alternating order.
	private function splitString($string, &$strings, &$tags)
	{
		//initialize the output arrays to be empty
		$strings = array();
		$tags = array();
	
		$textLength = 0;
		$taglength = 0;		
		$currentPos = 0;
		
		//split the HTML into an array of substrings, and an array of tags
		do
		{
			//get a text section
			$textLength = $this->getTextLength($string, $currentPos);
			array_push($strings, substr($string, $currentPos, $textLength));
			$currentPos += $textLength;

			//get a tag
			$tagLength = $this->getTagLength($string, $currentPos);
			array_push($tags, substr($string, $currentPos, $tagLength));
			$currentPos += $tagLength;
			
		} while ($textLength != 0 || $tagLength != 0);
		
	}
	
	//returns the size of the text section in string starting at pos
	//the end of the text section is marked when a < or the end of the string is encountered. 
	private function getTextLength($string, $pos)
	{
		$string_end = strpos($string, "<", $pos);
			
		if($string_end === false)	//reached the end of the input?
			return strlen($string) - $pos;
		else return $string_end - $pos;
	}
	
	//returns the size of the tag in string starting at pos
	private function getTagLength($string, $pos)
	{
		$string_end = strpos($string, ">", $pos);
		
		if($string_end === false)	//reached the end of the input?
			return strlen($string) - $pos;
		else return $string_end - $pos + 1;
	}
	
	//returns a string containing the reassembled content of strings and tags
	private function reassembleStrings($strings, $tags)
	{
		$nTags = count($tags);
		$result = "";
		$i = 0;
	
		while($i < $nTags)
		{
			$result .= $strings[$i];
			$result .= $tags[$i];
			$i++;
		}
		
		return $result;
	}
	
}

//compares two instances of TranslationStringTagger_pattern.
//Should return a value greater than 0 if a>b and a value less than 0 if a<b
//used for usort() - to sort an array of these things by their priority
function TranslationStringTagger_compareByPriority($a, $b)
{
	return $a['priority'] - $b['priority'];
}

class TranslationStringTagger_tester {

	public function run(){
	
		$tagger = new TranslationStringTagger;
		$tagger->addPatternReplacements(array(
			'/\d{4}-\d{2}-\d{2}/' => '<span data-swete-translate="0" data-date-format="Y-m-d">$0</span>',
			'/\d{2}\/\d{2}\/\d{2}/' => '<span data-swete-translate="0" data-date-format="m/d/y">$0</span>',
			'/(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday) (January|February|March|April|May|June|July|August|September|October|November|December) \d{1,2}, \d{4}/' => 
				'<span data-swete-translate="0" data-date-format="l F j, Y">$0</span>',
			'/(?<!&)[0-9\.,]+/' => '<span data-swete-translate="0" data-number-format="0">$0</span>'
		));
		
		$strings = array(
			"Today's date is Monday March 31, 2009" =>
				"Today's date is <span data-swete-translate=\"0\ data-date-format=\"l F j, Y\">Monday March 31, 2009</span>",
			
			"<span>Steve</span> says 56 is his <span style=\"color:red\">favourite number.</span>" =>
				"<span>Steve</span> says <span data-swete-translate=\"0\" data-number-format=\"0\">56</span> is his <span style=\"color:red\">favourite number.</span>",
				
			"<span data-custom=\"56\">Steve</span> says 56 is his <span style=\"color:red\">favourite number.</span>" =>
				"<span data-custom=\"56\">Steve</span> says <span data-swete-translate=\"0\" data-number-format=\"0\">56</span> is his <span style=\"color:red\">favourite number.</span>",
				
			"<span data-custom=\"56\">Steve</span> says <font size=\"23\">56</font> (2008-12-24) is his <span style=\"color:red\">favourite number.</span>" =>
				"<span data-custom=\"56\">Steve</span> says <font size=\"23\"><span data-swete-translate=\"0\" data-number-format=\"0\">56</span></font> (2008-12-24) is his <span style=\"color:red\">favourite number.</span>",
			
			
		
		
		);
		
		foreach ( $strings as $source => $expected ){
			$tagged = $tagger->addTags($source);
			if ( strcmp($tagged, $expected) !== 0 ){
				throw new Exception("Test Failed adding tags to [".$source."].  Expected [".$expected."] but received [".$tagged."]");
				
			}
			
		}
	}
	
}