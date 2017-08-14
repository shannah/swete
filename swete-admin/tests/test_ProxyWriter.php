<?php
import('PHPUnit.php');
require_once 'inc/ProxyWriter.php';

class test_ProxyWriter extends PHPUnit_TestCase {

	

	function test_ProxyWriter( $name = 'test_ProxyWriter'){
		$this->PHPUnit_TestCase($name);
		
		
		
	}

	function setUp(){
		
		
		
	}
	
	
	
	
	
	
	
	function tearDown(){
		
		
		
	}
	
	
	function testProxifyUrl(){
	
		$proxy = new ProxyWriter();
		$proxy->setSrcUrl(df_absolute_url(DATAFACE_SITE_URL.'/tests/testsites/site1/'));
		$proxy->setProxyUrl(df_absolute_url(DATAFACE_SITE_URL.'/tests/proxysites/site1/'));
		$expected = df_absolute_urL(DATAFACE_SITE_URL.'/tests/testsites/site1/index.html');
		$src = df_absolute_urL(DATAFACE_SITE_URL.'/tests/proxysites/site1/index.html');
		$this->assertEquals($expected, $proxy->unproxifyUrl($src));
	
	
		$proxy = new ProxyWriter();
		$proxy->setSrcUrl('http://www.xataface.com/');
		$proxy->setProxyUrl('http://en.xataface.com/path/en/');
		
		$strs = array(
		
			'foo' => 'foo',
			'/foo' => '/path/en/foo',
			'http://www.xataface.com/foo' => 'http://en.xataface.com/path/en/foo',
			'http://www.xataface.com/foo?bar=1' => 'http://en.xataface.com/path/en/foo?bar=1',
			'https://www.xataface.com/foo' => 'https://www.xataface.com/foo', // shouldn't change urls in different domains
			'http://www.foo.bar/foo' => 'http://www.foo.bar/foo'
		);
		
		foreach ($strs as $k=>$v){
			$this->assertEquals($v, $proxy->proxifyUrl($k));
			$this->assertEquals($k, $proxy->unproxifyUrl($v));
		}
		
		
		$proxy = new ProxyWriter();
		$proxy->setSrcUrl('http://www.xataface.com/');
		$proxy->setProxyUrl('http://en.xataface.com/path/en/');
		$proxy->addAlias('foo', 'roof');
		
		$strs = array(
		
			'foo' => 'roof',
			'/foo' => '/path/en/roof',
			'http://www.xataface.com/foo' => 'http://en.xataface.com/path/en/roof',
			'https://www.xataface.com/foo' => 'https://www.xataface.com/foo', // shouldn't change urls in different domains
			'http://www.foo.bar/foo' => 'http://www.foo.bar/foo',
			'foo/overview' => 'roof/overview',
			'/foo/overview' => '/path/en/roof/overview',
			'http://www.xataface.com/foo/overview' => 'http://en.xataface.com/path/en/roof/overview',
			'https://www.xataface.com/foo/overview' => 'https://www.xataface.com/foo/overview', // shouldn't change urls in different domains
		);
		
		foreach ($strs as $k=>$v){
			$this->assertEquals($v, $proxy->proxifyUrl($k));
			$this->assertEquals($k, $proxy->unproxifyUrl($v));
		}
		
		
		$proxy = new ProxyWriter();
		$proxy->setSrcUrl('http://www.xataface.com/siteroot/');
		$proxy->setProxyUrl('http://en.xataface.com/path/en/');
		$proxy->addAlias('foo', 'roof');
		
		$strs = array(
		
			'foo' => 'roof',
			'/foo' => '/foo',
			'http://www.xataface.com/siteroot/foo' => 'http://en.xataface.com/path/en/roof',
			'https://www.xataface.com/siteroot/foo' => 'https://www.xataface.com/siteroot/foo', // shouldn't change urls in different domains
			'http://www.foo.bar/siteroot/foo' => 'http://www.foo.bar/siteroot/foo',
			'foo/overview' => 'roof/overview',
			'/siteroot/foo/overview' => '/path/en/roof/overview',
			'http://www.xataface.com/siteroot/foo/overview' => 'http://en.xataface.com/path/en/roof/overview',
			'https://www.xataface.com/foo/overview' => 'https://www.xataface.com/foo/overview', // shouldn't change urls in different domains
		);
		
		foreach ($strs as $k=>$v){
			$this->assertEquals($v, $proxy->proxifyUrl($k));
			$this->assertEquals($k, $proxy->unproxifyUrl($v));
		}
		
		
	}
	
	function testProxifyHtml(){
	
		$proxy = new ProxyWriter();
		$proxy->setSrcUrl('http://www.xataface.com/');
		$proxy->setProxyUrl('http://en.xataface.com/path/en/');
		
		$strs = array(
		
			'<a href="foo">Foo</a>' => '<a href="foo">Foo</a>',
			'<a href="/foo">Foo</a>' => '<a href="/path/en/foo">Foo</a>'
		
		);
		
		foreach ($strs as $k=>$v){
			$this->assertEquals($v, $proxy->proxifyHtml($k));
			
		}
	}
	
	function testProxifyCss(){
	
		$proxy = new ProxyWriter();
		$proxy->setSrcUrl('http://www.xataface.com/');
		$proxy->setProxyUrl('http://en.xataface.com/path/en/');
		
		$strs = array(
		
			'div {
				background-image: url(foo);
			}' => 'div {
				background-image: url(foo);
			}',
			'div {
				background-image: url(/foo);
			}' => 'div {
				background-image: url(/path/en/foo);
			}'
		
		);
		
		foreach ($strs as $k=>$v){
			$this->assertEquals($v, $proxy->proxifyCss($k));
			
		}
	}
	
	
		
		

		
		
		
		
			
	
	static function q($sql){
		$res = xf_db_query($sql, df_db());
		if ( !$res ) throw new Exception(xf_db_error(df_db()));
		return $res;
	}
	
		


}


// Add this test to the suite of tests to be run by the testrunner
Dataface_ModuleTool::getInstance()->loadModule('modules_testrunner')
		->addTest('test_ProxyWriter');
