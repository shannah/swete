<?php

//XLIFF Tester file - tests the XLIFFWriter class.
//Author: Kevin Chow
//Date: May 26, 2012

include('XLIFFWriter.php');

class Tester
{
	private $x;	//XLIFFWriter instance
	
	public function init()
	{
		//create instance of the class to be tested.
		$this->x = new XLIFFWriter('en', 'fr');
		
		//testing constructor: new XLIFFWriter('en', 'fr');	- should have sourceLanguage set to 'en' and TargetLanguage set to 'fr'
		$srcLang = $this->x->getSourceLanguage();
		$targLang = $this->x->getTargetLanguage();
		if($srcLang != 'en') return 'problem with constructor. SourceLanguage is ' . $srcLang . ' (Should be en).';
		if($targLang != 'fr') return 'problem with constructor. TargetLanguage is ' . $targLang . ' (Should be fr).';
		return 'OK';
	}
	
	public function testGettersAndSetters()
	{
		//test the setters
		$this->x->setSourceLanguage('as');
		$this->x->setTargetLanguage('df');
		$srcLang = $this->x->getSourceLanguage();
		$targLang = $this->x->getTargetLanguage();
		if($srcLang != 'as') return 'problem with setSourceLanguage(). SourceLanguage is ' . $srcLang . ' (Should be as).';
		if($targLang != 'df') return 'problem with setTargetLanguage(). TargetLanguage is ' . $targLang . ' (Should be df).';
		
		//set the languages back to en,fr (not really necessary)
		$this->x->setSourceLanguage('en');
		$this->x->setTargetLanguage('fr');
		$srcLang = $this->x->getSourceLanguage();
		$targLang = $this->x->getTargetLanguage();
		if($srcLang != 'en') return 'problem with setSourceLanguage(). SourceLanguage is ' . $srcLang . ' (Should be en).';
		if($targLang != 'fr') return 'problem with setTargetLanguage(). TargetLanguage is ' . $targLang . ' (Should be fr).';
		
		//try adding an invalid language (should throw exception)
		try
		{
			$this->x->setSourceLanguage('asdf');
			return 'XLIFFWriter::setSourceLanguage() - did not throw exception when given invalid language.';
		}
		catch(Exception $e){}
		try
		{
			$this->x->setTargetLanguage('asdf');
			return 'XLIFFWriter::setTargetLanguage() - did not throw exception when given invalid language.';
		}
		catch(Exception $e){}

		//sourceLanguage should be 'en' and targetLanguage should be 'fr' at this point.
		return 'OK';
	}
	
	public function testSetFile_addTranslation()
	{
		//try adding translation before setting the file (should throw exception)
		try
		{
			$this->x->addTranslation('foo', 'bar');
			return 'problem with addTranslation(). Should have thrown exception if setFile() was not called first.';
		}
		catch (Exception $e){}
		
		//how setFile() and addTranslation should normally be used.
		$this->x->setFile('first.html');
		$this->x->addTranslation('Hello', 'Bonjour');
		$this->x->addTranslation('Thank you', 'Merci');
		$this->x->setFile('second.html');
		$this->x->addTranslation('English', 'Francais');
		$this->x->addTranslation('Hello Hello Hello', 'Bonjour Bonjour Bonjour');
		$this->x->addTranslation('!@#$%^&*()[]<>', '!@#$%^&*()[]<>');
		$this->x->setFile('third.html');
		$this->x->setSourceLanguage('as');
		return 'OK';
	}
	
	public function testAddTranslations()
	{
		$translations = array();	//empty array - shouldn't do anything
		$translations2 = array('En1'=>'Fr1','En2'=>'Fr2','En3'=>'Fr3','En4'=>'Fr4');
		$this->x->addTranslations($translations);
		$this->x->addTranslations($translations2);
		return 'OK';
	}
	
	public function testWrite()
	{
		$fhandle = fopen('output.xliff', 'r+');	//create new file
		$this->x->write($fhandle);
		fclose($fhandle);
		
		if(!file_exists('output.xliff')) return 'Error: output file missing!';
		if(filesize('output.xliff') == 0) return 'Error: empty file!';
		
		return 'OK';
	}
	public function checkXLIFF()
	{
		$dd = new DOMDocument();
		$dd->load('output.xliff');
		if($dd->schemaValidate('xliff-core-1.2-strict.xsd'))
			return 'The document is valid.';
		else return 'Error: Invalid Document!';
	}
	
	public function testFlush()
	{
		$fhandle = fopen('output2.xliff', 'w+');
		$this->x->flush($fhandle);
		fclose($fhandle);
		return 'OK';
	}
};

//TESTING CODE

$tester = new Tester();
print 'Testing constructor: ' . $tester->init() . '<br>';
print 'Testing getters/setters: ' . $tester->testGettersAndSetters() . '<br>';
print 'Testing setFile() / addTranslation(): ' . $tester->testSetFile_addTranslation() . '<br>';
print 'Testing addTranslations(): ' . $tester->testAddTranslations() . '<br>';
print 'Testing flush(): ' . $tester->testFlush() . '<br>';
print 'Testing write(): ' . $tester->testWrite() . '<br>';
//print 'Checking if valid XLIFF: ' . $tester->checkXLIFF() . '<br>';		//Validating with the scheme is SLOW! comment this if not needed.
print 'Output file: output.xliff' . '<br>';
print 'Done!!!! <br>';

?>