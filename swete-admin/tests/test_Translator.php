<?php
import('PHPUnit.php');
require_once 'inc/WebLite_Translate.class.php';

class test_Translator extends PHPUnit_TestCase {

	

	function test_Translator( $name = 'test_Translator'){
		$this->PHPUnit_TestCase($name);
		
		
		
	}

	function setUp(){
		
		
		
	}
	
	
	
	function testExtractStrings(){
		$html = trim(<<<END
<body>
	<div>Test1</div>
	<div>Test2</div>
</body>
END
);
		$expected = trim('
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html><body>
	<div>{{$0$}}</div>
	<div>{{$1$}}</div>
</body></html>');
	
		$tr = new WebLite_HTML_Translator();
		$extracted = $tr->extractStrings($html);
		$this->assertEquals(trim($expected), trim($extracted));
		
		$expected = array (
		  0 => 'Test1',
		  1 => 'Test2',
		);
		$this->assertEquals($expected, $tr->strings);
		
		
	
	}
	
	
	function testExtractStringsWithEntities(){
		$html = trim(<<<END
<body>
	<div>Entr&eacute;es</div>
	<div>Test2</div>
</body>
END
);
		$expected = trim('
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html><body>
	<div>{{$0$}}</div>
	<div>{{$1$}}</div>
</body></html>');

		$tr = new WebLite_HTML_Translator();
		$extracted = $tr->extractStrings($html);
		
		$tr = new WebLite_HTML_Translator();
		$extracted = $tr->extractStrings($html);
		$this->assertEquals(trim($expected), trim($extracted));
		$expected = array (
		  0 => 'Entrées',
		  1 => 'Test2',
		);
		$this->assertEquals($expected, $tr->strings);
		
		
		
	}
	
	
	function testExtractStringsKarelia(){
		$s = DIRECTORY_SEPARATOR;
		$html = file_get_contents(dirname(__FILE__).$s.'testsites'.$s.'site3'.$s.'index.html');
		
		$tr = new WebLite_HTML_Translator();
		$extracted = $tr->extractStrings($html);
		//echo $extracted;exit;
		$expected = file_get_contents(dirname(__FILE__).$s.'testsites'.$s.'site3'.$s.'index.extracted.html');
		$this->assertEquals(trim($expected), trim($extracted));
		
		$expected = file_get_contents(dirname(__FILE__).$s.'testsites'.$s.'site3'.$s.'index.strings.json');
		$expected = json_decode($expected, true);
		$this->assertEquals($expected, $tr->strings);
	}
	
	
	
	
	function tearDown(){
		
		
		
	}
	
	
	


}


// Add this test to the suite of tests to be run by the testrunner
Dataface_ModuleTool::getInstance()->loadModule('modules_testrunner')
		->addTest('test_Translator');
