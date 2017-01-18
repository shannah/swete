<?php

//XLIFF Tester file - tests the XLIFFWriter class.
//Author: Kevin Chow
//Date: June 1, 2012

include('XLIFFDocument.php');

class Tester
{
	private $x,
			$first_html_translations = array('Hello'=>'Bonjour', 'Thank you'=>'Merci'),
			$second_html_translations = array('English'=>'Francais', 'Hello Hello Hello'=>'Bonjour Bonjour Bonjour', '!@#$%^&*()[]<>'=>'!@#$%^&*()[]<>'),
			$third_html_translations = array('En1'=>'Fr1', 'En2'=>'Fr2', 'En3'=>'Fr3', 'En4'=>'Fr4'),
			$test1_html_translations = array('Test'=>'Teste', 'Bye'=>'Au revoir'),
			$test2_html_translations = array('Foo'=>'Bar', 'Foo2'=>'Bar2');

	public function testRead()
	{		
		//create instance of the class to be tested.
		$this->x = XLIFFDocument::read('test.xliff');
		
		$result1 = $this->x->getTranslations('first.html');
		if($result1 != $this->first_html_translations)
			return 'Incorrect values read for first.html.';
			
		$result2 = $this->x->getTranslations('second.html');
		if($result2 != $this->second_html_translations)
			return 'Incorrect values read for second.html.';
			
		$result3 = $this->x->getTranslations('third.html');
		if($result3 != $this->third_html_translations)
			return 'Incorrect values read for third.html.';
		
		return 'OK';
	}
	
	//testing setSourceLanguage() and setTargetLanguage()
	public function testsetters_getters()
	{	
		//try setting the global default languages
		$this->x->setSourceLanguage('qw');
		$this->x->setTargetLanguage('er');
		//try setting languages for specific files
		$this->x->setSourceLanguage('as', '1.html');	
		$this->x->setTargetLanguage('df', '1.html');
		
		$result1 = $this->x->getSourceLanguage('1.html');	//should give 'as'
		$result2 = $this->x->getTargetLanguage('1.html');	//should give 'df'
		$result3 = $this->x->getSourceLanguage();	//should give 'qw'
		$result4 = $this->x->getTargetLanguage();	//should give 'er'
		
		if($result1 != 'as')
			return 'getSourceLanguage(1.html) did not return expected value. Returned ' . $result1 . ', should be as.';
		if($result2 != 'df')
			return 'getTargetLanguage(1.html) did not return expected value. Returned ' . $result2 . ', should be df.';
		if($result3 != 'qw')
			return 'getSourceLanguage() did not return expected value. Returned ' . $result3 . ', should be qw.';
		if($result4 != 'er')
			return 'getTargetLanguage() did not return expected value. Returned ' . $result4 . ', should be er.';
			
		//try some exceptional conditions:
		try{
		$this->x->getTargetLanguage('aaa');	//should throw exception - file not found.
		return 'getTargetLanguage() did not throw exception for invalid filename.';
		} 
		catch(Exception $e){}		
		
		try{
		$this->x->getSourceLanguage('aaa');	//should throw exception - file not found.
		return 'getSourceLanguage() did not throw exception for invalid filename.';
		}
		catch(Exception $e){}
		
		return 'OK';
	}
	
	//test adding and retrieving translations from the class
	public function testAddGetTranslations()
	{		
		//default languages for each new file we add
		$this->x->setSourceLanguage('en');	
		$this->x->setTargetLanguage('fr');
		
		//only for 1.html
		$this->x->setSourceLanguage('as', '1.html');
		$this->x->setTargetLanguage('df', '1.html');
		
		$this->x->addTranslations('1.html', $this->test1_html_translations);
		$this->x->addTranslations('2.html', $this->test2_html_translations);
		
		//first.html should contain $translations1
		$result1 = $this->x->getTranslations('1.html');
		if($result1 != $this->test1_html_translations) 
			return 'Error: getTranslations did not return expected result for 1.html.';
			
		//second.html should contain $translations2
		$result2 = $this->x->getTranslations('2.html');
		if($result2 != $this->test2_html_translations) 
			return 'Error: getTranslations did not return expected result for 2.html.';
		
		//getTranslations with no parameter should return all of the stored translations: all of the read() from file contents plus the stuff we added.
		$result3 = $this->x->getTranslations();
		if($result3 != $this->test1_html_translations + $this->test2_html_translations + $this->first_html_translations + $this->second_html_translations + $this->third_html_translations) 
			return 'Error: getTranslations with no params did not return expected result for $translations3.';
			
		//try getting translations for non-existent file
		try{
		$result4 = $this->x->getTranslations('aaa');
		return 'getTranslations() did not throw exception for non existent file.';
		}
		catch(Exception $e){}
			
		return 'OK';
	}
	
	//creates an output XLIFF file and reads it in again to make sure it was created successfully.
	public function testWrite()
	{
		//write the output to file using XLIFFWriter
		$outfile = 'test_output.xliff';
		if(file_exists($outfile))
			unlink($outfile);
		$this->x->write($outfile);
		if(!(file_exists($outfile) && filesize($outfile) > 0))
			return 'Error: file missing or empty file!';
			
		//try reading the file in again and see if it matches what we expect
		$x2 = XLIFFDocument::read($outfile);
		$result = $x2->getTranslations();
		if($result != $this->test1_html_translations + $this->test2_html_translations + $this->first_html_translations + $this->second_html_translations + $this->third_html_translations)
			return 'Error: output file does not contain expected info!';		
		
		//try creating an empty document
		$x3 = new XLIFFDocument();
		$x3->setSourceLanguage('en');
		$x3->setTargetLanguage('en');
		$x3->write('empty.xliff');
		
		//read in the empty document - should give us an empty array for getTranslations().
		$x4 = XLIFFDocument::read('empty.xliff');
		$result2 = $x4->getTranslations();
		if($result2 != NULL) return 'reading/writing an empty document did not return empty array.';
		unlink('empty.xliff');	//delete the empty xliff file
		
		return 'OK';
	}
};

//TESTING CODE

if(!file_exists('test.xliff'))
{
	print 'Required file missing: test.xliff!';
	return;
}

$tester = new Tester();
print 'Testing read(): ' . $tester->testRead() . '<br>';
print 'Testing get/set Source/Target Languages(): ' . $tester->testsetters_getters() . '<br>';
print 'Testing testAddGetTranslations(): ' . $tester->testAddGetTranslations() . '<br>';
print 'Testing write(): ' . $tester->testWrite() . '<br>';
print 'Output file: test_output.xliff<br>';
print 'Done!!!! <br>';

?>