<?php
import('PHPUnit.php');
require_once 'inc/SweteTools.php';

class test_SweteTools extends PHPUnit_TestCase {

	

	function test_SweteTools( $name = 'test_SweteTools'){
		$this->PHPUnit_TestCase($name);
		
		
		
	}

	function setUp(){
		
		
		
	}
	
	
	
	
	
	
	
	function tearDown(){
		
		
		
	}
	
	function testNormalizeUrl(){
	
		$strs = array(
			'http://xataface.com/index.php?foo=1&bar=2&foo=3' => 'http://xataface.com/index.php?bar=2&foo=1&foo=3',
			'index.php?foo=1&bar=2&foo=3' => 'index.php?bar=2&foo=1&foo=3',
			'http://xataface.com/index.php?foo=1&bar=2&foo=3#hello_world' => 'http://xataface.com/index.php?bar=2&foo=1&foo=3',
			'index.php?foo=1&bar=2&foo=3#hello_world' => 'index.php?bar=2&foo=1&foo=3',
			'/index.php?foo=1&bar=2&foo=3#hello_world' => '/index.php?bar=2&foo=1&foo=3',
			'../index.php?foo=1&bar=2&foo=3#hello_world' => '../index.php?bar=2&foo=1&foo=3',
			'index.php?foo=1&bar=2&foo=3#hello_world?foo=15' => 'index.php?bar=2&foo=1&foo=3',
			'https://xataface.com/index.php?foo=1&bar=2&foo=3' => 'https://xataface.com/index.php?bar=2&foo=1&foo=3',
		);
		foreach ($strs as $k=>$v){
			$this->assertEquals($v, SweteTools::normalizeUrl($k));
		}
	}
	
	
	function testAbsoluteUrl(){
	
		$tests = array(
			array(
				'base' => 'http://solutions.weblite.ca/',
				'in' => 'index.html',
				'out' => 'http://solutions.weblite.ca/index.html'
			),
			array(
				'base'=> 'http://solutions.weblite.ca/',
				'in' => '/index.html',
				'out' => 'http://solutions.weblite.ca/index.html'
			),
			array(
				'base' => 'http://solutions.weblite.ca/foo/',
				'in' => '/index.html',
				'out' => 'http://solutions.weblite.ca/index.html'
			),
			array(
				'base' => 'http://solutions.weblite.ca',
				'in' => 'index.html',
				'out' => 'http://solutions.weblite.ca/index.html'
			),
			array(
				'base' => 'http://solutions.weblite.ca/foo',
				'in' => 'index.html',
				'out'=> 'http://solutions.weblite.ca/foo/index.html'
			),
			array(
				'base' => 'http://solutions.weblite.ca/foo/',
				'in' => 'index.html',
				'out' => 'http://solutions.weblite.ca/foo/index.html'
			),
			array(
				'base' => 'https://foo.bar.com/foo/',
				'in' => 'http://foo.bar.com/foo/index.html',
				'out' => 'http://foo.bar.com/foo/index.html'
			),
			array(
				'base' => 'https://foo.bar.com/foo/',
				'in' => '//foo.bar.com/foo/index.html',
				'out' => 'https://foo.bar.com/foo/index.html'
			),
			array(
				'base' => 'http://foo.bar.com/foo/',
				'in' => 'index.html?go=1&bar=2',
				'out' => 'http://foo.bar.com/foo/index.html?go=1&bar=2'
			)
		);
		
		foreach ($tests as $test){
			$this->assertEquals($test['out'], SweteTools::absoluteUrl($test['in'], $test['base']), 'Converting '.$test['in'].', '.$test['base']);
		}
	}
	
	
	
		


}


// Add this test to the suite of tests to be run by the testrunner
Dataface_ModuleTool::getInstance()->loadModule('modules_testrunner')
		->addTest('test_SweteTools');
