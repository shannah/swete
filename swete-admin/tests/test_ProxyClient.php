<?php
import('PHPUnit.php');
require_once 'inc/ProxyClient.php';

class test_ProxyClient extends PHPUnit_TestCase {

	

	function __construct( $name = 'test_ProxyClient'){
		$this->PHPUnit_TestCase($name);
		
		
		
	}

	function setUp(){
		
		
		
	}
	
	
	
	
	
	
	
	function tearDown(){
		
		
		
	}
	
	
	function testDefaultRequest(){
		$client = new ProxyClient;
		$client->URL = df_absolute_url('tests/test_ProxyClient/test1.html');
		$client->process();
		$content = file_get_contents('tests/test_ProxyClient/test1.out.html');
		$this->assertEquals(trim($content), trim($client->content));
		
	}
	
	function testRequestToOutputFile(){
		$client = new ProxyClient;
		$client->URL = df_absolute_url('tests/test_ProxyClient/test1.html');
		$outputFile = tempnam(sys_get_temp_dir(), 'test_ProxyClient');
		$client->outputFile = $outputFile;
		$client->process();
		$this->assertEquals(null, $client->content, 'Content should be written to output file, not saved to variable.');
		
		
		$expected = file_get_contents('tests/test_ProxyClient/test1.html');
		$doc = new DOMDocument;
		@$doc->loadHtml($expected);
		$expected = $doc->saveHtml();
		$actual = file_get_contents($outputFile);
		$actual = '';
		$fh = fopen($outputFile, 'r');
		while ( !feof($fh) and trim($line = fgets($fh, 1024) )){
			// We skip the headers
		}
		ob_start();
		fpassthru($fh);
		fclose($fh);
		$actual = ob_get_contents();
		
		ob_end_clean();
		
		unset($doc);
		$doc = new DOMDocument;
		@$doc->loadHtml($actual);
		$actual = $doc->saveHtml();
		unset($doc);
		$this->assertEquals($expected, $actual);
	}
	
	

	
	
	
		


}


// Add this test to the suite of tests to be run by the testrunner
Dataface_ModuleTool::getInstance()->loadModule('modules_testrunner')
		->addTest('test_ProxyClient');
