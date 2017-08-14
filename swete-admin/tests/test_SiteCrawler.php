<?php
import('PHPUnit.php');
require_once 'inc/SiteCrawler.php';

class test_SiteCrawler extends PHPUnit_TestCase {

	

	function test_SitCrawler( $name = 'test_SiteCrawler'){
		$this->PHPUnit_TestCase($name);
		
		
		
	}

	function setUp(){
		
		
		
	}
	
	
	
	
	
	
	
	function tearDown(){
		
		
		
	}
	
	
	
	
	function testCrawlSite(){
	
		$s = new Dataface_Record('websites', array());
		$s->setValues(array(
			'website_url'=>'http://solutions.weblite.ca/',
			'source_language'=>'en',
			'target_language'=>'fr',
			'website_name'=>'Site 1 french',
			'active'=>1,
			'base_path'=>'/fr/',
			'host'=>'localhost'
		));
		$s->save();
		
		
		$site = SweteSite::loadSiteById($s->val('website_id'));
		
		
		$crawler = new SiteCrawler;
		$crawler->site = $site;
		$crawler->startingPoint = 'http://solutions.weblite.ca/';
		$crawler->depth = 3;
		$crawler->crawl();
		//print_r($crawler->root);
	}
	
	
		
		

		
		
		
		
			
	
	static function q($sql){
		$res = xf_db_query($sql, df_db());
		if ( !$res ) throw new Exception(xf_db_error(df_db()));
		return $res;
	}
	
		


}


// Add this test to the suite of tests to be run by the testrunner
Dataface_ModuleTool::getInstance()->loadModule('modules_testrunner')
		->addTest('test_SiteCrawler');
