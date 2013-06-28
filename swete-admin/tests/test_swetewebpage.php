<?php
import('PHPUnit.php');
require_once 'inc/SweteWebpage.class.php';

class test_swetewebpage extends PHPUnit_TestCase {

	private $t_webpages;
	private $t_versions;
	private $t_websites;

	private $mainSite;
	private $mainUser;


	function test_swetewebpage( $name = 'test_swetewebpage'){
	
		$this->PHPUnit_TestCase($name);
		
		/*
		$this->t_webpages = 'temp_webpages_'.time();
		$this->t_websites = 'temp_websites_'.time();
		$this->t_versions = 'temp_webpage_versions_'.time();
	*/	
		
	}

	function setUp(){
		SweteDb::q("delete from jobs");
		SweteDb::q("delete from websites");
		
		$siteRec = new Dataface_Record('websites', array());
		$siteRec->setValues(array(
			'website_name' => 'Test site',
			'website_url' => df_absolute_url(DATAFACE_SITE_URL.'/tests/testsites/site2/'),
			'source_language' => 'en',
			'target_language' => 'fr',
			'host' => $_SERVER['HTTP_HOST'],
			'base_path' => dirname(DATAFACE_SITE_URL).'/site2/',
			'active'=>1,
			'locked' => 0,
			'enable_live_translation' => 1
		));
		error_log("Site URL is ".$siteRec->val('website_url'));
		error_log('base_path is '.$siteRec->val('base_path'));
		error_log('base path should be '.dirname(DATAFACE_SITE_URL).'/site2/');
		$res = $siteRec->save();
		if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());
		df_q("delete from site_text_filters where website_id='".addslashes($siteRec->val('website_id'))."'");
			
		
		$site = new SweteSite($siteRec);
		$this->mainSite = $site;
		
		
		SweteDb::q("delete from users");
		$user = new Dataface_Record('users', array());
		$user->setValues(array(
			'username'=>'test_user',
			'email' => 'test_user@example.com',
			'password' => 'foo',
			'role_id' => 3
		));
		$res = $user->save();
		if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());
		$this->mainUser = $user;
		
		$user = new Dataface_Record('users', array());
		$user->setValues(array(
			'username'=>'test_user2',
			'email' => 'test_user2@example.com',
			'password' => 'foo',
			'role_id' => 3
		));
		$res = $user->save();
		if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());
		
		
	
		/*
		self::q("create table `".$this->t_webpages."` select * from webpages");
		self::q("delete from websites");
		self::q("create table `".$this->t_websites."` select * from websites");
		//self::q("insert into `".$this->t_webpages."` select * from webpages");
		self::q("delete from webpages");
		
		
		self::q("create table `".$this->t_versions."` select * from webpage_versions");
		self::q("delete from webpage_versions");
		*/
		
	}
	
	
	
	
	
	
	
	function tearDown(){
		/*
		self::q("delete from websites");

		self::q("delete from webpages");
		
		self::q("insert into webpages select * from ".$this->t_webpages);
		self::q("delete from ".$this->t_webpages);
		self::q("drop table ".$this->t_webpages);
		
		
		self::q("delete from webpage_versions");
		self::q("insert into webpage_versions select * from ".$this->t_versions);
		self::q("drop table ".$this->t_versions);
		*/
	}
	
	/*
	//Moved the tests in testJobs to test_SweteJob.php testJob, testInbox
	function testJobs(){
		require_once 'inc/SweteJob.class.php';
		$siteRec = $this->mainSite;
		$job = SweteJob::createJob($siteRec);
		$this->assertTrue($job instanceof SweteJob, 'Job should be a swete job object.');
		
		$this->assertEquals($this->mainSite->getSourceLanguage(), $job->getRecord()->val('source_language'), 'Job should have same source language as the site it originated from.');
		$this->assertEquals($this->mainSite->getDestinationLanguage(), $job->getRecord()->val('destination_language'), 'Job should have same destination language as the site it originated from.');
		$this->assertEquals(SweteJob::JOB_STATUS_NEW, $job->getRecord()->val('job_status'), 'Job status should be new.');
		$this->assertEquals($siteRec->getRecord()->val('website_id'), $job->getRecord()->val('website_id'), 'Job website id should match the website.');
		
		
		// Create a translation miss 
		$server = new ProxyServer;
		$server->logger->saveBodies = true;
		$server->site = $this->mainSite;
		$server->SERVER = array(
			'REQUEST_METHOD' => 'get'
		);
		$server->URL = $server->site->getProxyUrl().'index.html';
		error_log("Checking server ".$server->URL);
		$server->buffer = true;
		$server->logTranslationMisses = true;
		//SweteDb::q('commit');
		$server->handleRequest();
		return;
		//$expected = file_get_contents('tests/testsites/site1_output/index.html');
		//$this->assertEquals($expected, $server->contentBuffer);
		
		// We should have some misses:
		$res = SweteDb::q("select count(*) from translation_miss_log where website_id='".addslashes($siteRec->getRecord()->val('website_id'))."'");
		list($num) = mysql_fetch_row($res);
		@mysql_free_result($res);
		
		$this->assertEquals(13, $num, 'There should be 13 misses in our translation log.');
		
		$misses = df_get_records_array('translation_miss_log', array('website_id'=>'='.$siteRec->getRecord()->val('website_id')));
		foreach ($misses as $miss){
		
			$job->addTranslationMiss($miss->val('translation_miss_log_id'));
			
		}
		$res = SweteDb::q("select count(*) from job_inputs_translation_misses where job_id='".$job->getRecord()->val('job_id')."'");
		list($num) = mysql_fetch_row($res);
		$this->assertEquals(13, $num, 'There should be exactly 13 misses added to the translation inputs.');
		
		foreach ($misses as $miss){
		
			$job->removeTranslationMiss($miss->val('translation_miss_log_id'));
			
		}
		
		$res = SweteDb::q("select count(*) from job_inputs_translation_misses where job_id='".$job->getRecord()->val('job_id')."'");
		list($num) = mysql_fetch_row($res);
		$this->assertEquals(0, $num, 'There should be exactly 0 misses added to the translation inputs after we removed them all.');
		
		
		foreach ($misses as $miss){
		
			$job->addTranslationMiss($miss->val('translation_miss_log_id'));
			
		}
		$res = SweteDb::q("select count(*) from job_inputs_translation_misses where job_id='".$job->getRecord()->val('job_id')."'");
		list($num) = mysql_fetch_row($res);
		$this->assertEquals(13, $num, 'There should be exactly 13 misses added to the translation inputs.');
		
		
		foreach ($misses as $miss){
		
			$this->assertTrue($job->containsString($miss->val('encoded_string')), 'The job is missing a string that should be there: '.$miss->val('string'));
			
		}
		
		
		
		$inbox = $job->getInbox('test_user');
		$this->assertTrue($inbox instanceof SweteJobInbox, 'Failed to get the swete job index.');
		
		$this->assertEquals('test_user', $inbox->getUsername(), 'Username does not match.');
		$this->assertEquals(0, $inbox->getNumMessages(), 'Inbox should be empty at this point.');
		$this->assertEquals(array(), $inbox->getMessageList(), 'Inbox should return empty array.');
		$msg = $inbox->addMessage('This is a test message');
		$this->assertEquals(1, $inbox->getNumMessages(), 'Inbox should contain only one message at this point.');
		//exit;
		$inbox->deleteMessage($msg->val('job_note_id'));
		
		$msgObj = $inbox->getMessage($msg->val('job_note_id'));
		//print_r($msgObj);
		$this->assertTrue($msgObj instanceof stdClass, 'The message should be a standard class');
		
		$this->assertEquals($msg->val('job_note_id'), $msgObj->job_note_id);
		$this->assertEquals(1, $msgObj->deleted, 'The message should have the deleted flag set');
		
		$this->assertEquals(0, $inbox->getNumMessages(), 'Inbox should be empty now since we deleted a message');
		$inbox->unDeleteMessage($msg->val('job_note_id'));
		$this->assertEquals(1, $inbox->getNumMessages(), 'Input should once again have a single message because we undeleted the message.');
		
		
		
		$job->assignJob('test_user','test_user');
		$job->refresh();
		$this->assertEquals('test_user', $job->getRecord()->val('assigned_to'));
		
		$job->assignJob('test_user2', 'test_user');
		$job->refresh();
		$this->assertEquals('test_user2', $job->getRecord()->val('assigned_to'));
		
		$res = df_q("select count(*) from job_assignments where job_id='".addslashes($job->getRecord()->val('job_id'))."'");
		list($num) = mysql_fetch_row($res);
		$this->assertEquals(2, $num, 'Should be 2 assignments now');
		
		
		$job->setStatus(SweteJob::JOB_STATUS_NEW, 'test_user');
		$job->refresh();
		$this->assertEquals(SweteJob::JOB_STATUS_NEW, $job->getRecord()->val('job_status'));
		$job->refresh();
		$job->setStatus(SweteJob::JOB_STATUS_ASSIGNED, 'test_user');
		
		
		$this->assertEquals(0, $job->getRecord()->val('compiled'), 'Job is not compiled yet but has compiled flag set.');
		
		$job->compile();
		
		$res = df_q("select count(*) from job_translatable where job_id='".addslashes($job->getRecord()->val('job_id'))."'");
		list($num) = mysql_fetch_row($res);
		$this->assertEquals(1, $num, 'Should be one translatble created for job.');
		$res = df_q("select count(*) from job_content where job_id='".addslashes($job->getRecord()->val('job_id'))."'");
		list($num) = mysql_fetch_row($res);
		$this->assertEquals(10, $num, 'Should be 10 content items in job_content for this job.');
		
		
		$translatable = df_get_record('job_translatable', array('job_id'=>'='.$job->getRecord()->val('job_id')));
		$this->assertTrue($translatable instanceof Dataface_Record, 'No translatable found for job.');
		
		$expectedContents = strip_tags(file_get_contents(dirname(__FILE__).'/testsites/site2/index.html'));
		$this->assertEquals($expectedContents, strip_tags($translatable->val('full_contents')));
		$job->refresh();
		$this->assertEquals(1, $job->getRecord()->val('compiled'), 'Job compiled flag is not set but should be');
		
	}
	*/
	
	
	function test_active(){
	
		$pg = new Dataface_Record('webpages', array());
		$pg->setValues(array(
			'website_id'=>$this->mainSite->getRecord()->val('website_id'),
			'webpage_url' => 'root',
			'webpage_content' => 'root page',
			'active' => 1
		));
		$res = $pg->save();
		if (PEAR::isError($res) ) throw new Exception($res->getMessage());
		
		$child = new Dataface_Record('webpages', array());
		$child->setValues(array(
			'website_id'=>$this->mainSite->getRecord()->val('website_id'),
			'webpage_url' => 'root/child1',
			'webpage_content' => 'root page',
			'active' => -1,
			'parent_id'=>$pg->val('webpage_id')
		));
		$res = $child->save();
		if (PEAR::isError($res) ) throw new Exception($res->getMessage());
		
		
		$wrapper = new SweteWebpage($child);
		$this->assertTrue($wrapper->isActive(true), 'Child should inherit active setting from parent.');
		
		$child->setValue('active', 0);
		$this->assertTrue(!$wrapper->isActive(true), 'Child now overrides its parents setting');
		
		$child->setValue('active', -1);
		$this->assertTrue($wrapper->isActive(true), 'Child again again inherits its parents setting.');
		
		$pg = new Dataface_Record('webpages', array());
		$pg->setValues(array(
			'website_id'=>$this->mainSite->getRecord()->val('website_id'),
			'webpage_url' => 'root',
			'webpage_content' => 'root page',
			'active' => 1
		));
		$res = $pg->save();
		if (PEAR::isError($res) ) throw new Exception($res->getMessage());
		
		$child = new Dataface_Record('webpages', array());
		$child->setValues(array(
			'website_id'=>$this->mainSite->getRecord()->val('website_id'),
			'webpage_url' => 'root/child1',
			'webpage_content' => 'root page',
			'active' => -1,
			'parent_id'=>$pg->val("webpage_id")
		));
		$res = $child->save();
		if (PEAR::isError($res) ) throw new Exception($res->getMessage());
		
		$w1 = new SweteWebpage($pg);
		//print_r($pg->vals());
		SweteSite::calculateEffectiveActiveToTree($w1, 1);
		
		$child2 = df_get_record_by_id($child->getId());
		$child2W = new SweteWebpage($child2);
		$this->assertTrue($child2W->isActive());
	}
	
	function testSweteWebpage(){
	
		try {
		
			$app = Dataface_Application::getInstance();
			$currLang = $app->_conf['lang'];
		
			$pg = new Dataface_Record('webpages', array());
			$pg->lang = 'en';
			$pg->setValues(array(
				'website_id'=>$this->mainSite->getRecord()->val('website_id'),
				'webpage_url'=>'index',
				'webpage_content' => 'Test string 1',
				'active'=>1,
				'posted_by'=>'shannah'
			));
			
			$res = $pg->save();
			if (PEAR::isError($res) ) throw new Exception($res->getMessage());
			
			$pgid = $pg->val('webpage_id');
			
			$swete1 = SweteWebpage::loadByUrl($this->mainSite->getRecord()->val('website_id'), 'index', 'en');
			$this->assertTrue($swete1 instanceof SweteWebpage);
			
			$this->assertEquals('en', $swete1->getLanguage());
			$this->assertEquals($pgid, $swete1->getRecord()->val('webpage_id'));
			
			$swete2 = SweteWebpage::loadById($pgid, 'en');
			$this->assertTrue($swete2 instanceof SweteWebpage);
			$this->assertEquals('en', $swete2->getLanguage());
			$this->assertEquals($pgid, $swete2->getRecord()->val('webpage_id'));
			
			
			$version = $swete2->setStatus(SweteWebpage::STATUS_APPROVED, 'shannah', 'Looked ok');
			$this->assertTrue($version instanceof Dataface_Record);
			$this->assertEquals('webpage_versions', $version->table()->tablename);
			
			$this->assertEquals(SweteWebpage::STATUS_APPROVED, intval($version->val('approval_status')));
			
			
			$recentApproved = $swete2->getLastVersionWithStatus(SweteWebpage::STATUS_APPROVED);
			$this->assertTrue($recentApproved instanceof Dataface_Record);
			$this->assertEquals('webpage_versions', $recentApproved->table()->tablename);
			$this->assertEquals($version->val('webpage_version_id'), $recentApproved->val('webpage_version_id'));
			
			
			$version2 = $swete2->setStatus(SweteWebpage::STATUS_CHANGED, 'shannah', 'Now it is changed.');
			$this->assertTrue($version2 instanceof Dataface_Record);
			$this->assertEquals('webpage_versions', $version2->table()->tablename);
			$this->assertEquals($version->val('webpage_id'), $swete2->getRecord()->val('webpage_id'));
			
			$recentApproved2 = $swete2->getLastVersionWithStatus(SweteWebpage::STATUS_APPROVED);
			$this->assertTrue($recentApproved2 instanceof Dataface_Record);
			$this->assertEquals('webpage_versions', $recentApproved2->table()->tablename);
			$this->assertEquals($recentApproved2->val('webpage_version_id'), $recentApproved->val('webpage_version_id'));
			
			
			$this->assertTrue(!$swete2->isChanged());
			
			$swete2->getRecord()->setValue('webpage_content', 'Test string 21');
			$this->assertTrue($swete2->isChanged());
			
			
			$swete2->getRecord()->setValue('webpage_content', "
				<div id='str1'>First String</div>
				<div id='str2'>Second String</div>");
				
			$tm = XFTranslationMemory::createTranslationMemory('test memory', 'en','fr');
			
			
			$trans = $swete2->applyTranslationsFromMemory($tm, $stats);
			$this->assertTrue($trans instanceof SweteWebpage);
			$this->assertEquals(2, $stats['misses']);
			$this->assertEquals(0, $stats['matches']);
			$this->assertEquals($swete2->getRecord()->val('webpage_content'), $trans->getRecord()->val('webpage_content'));
			$this->assertEquals('fr', $trans->getLanguage());
			
			$tm->addTranslation('First String','The premier string', 'shannah');
			$tm->setTranslationStatus('First String', 'The premier string', XFTranslationMemory::TRANSLATION_APPROVED, 'shannah');
			
			$trans = $swete2->applyTranslationsFromMemory($tm, $stats);
			$this->assertTrue($trans instanceof SweteWebpage);
			$this->assertEquals(1, $stats['misses']);
			$this->assertEquals(1, $stats['matches']);
			
			$expectedDoc = new DOMDocument;
			@$expectedDoc->loadHtml('<div id="str1">The premier string</div><div id="str2">Second String</div>');
			
			$actualDoc = new DOMDocument;
			@$actualDoc->loadHtml($trans->getRecord()->val('webpage_content'));
			
			$expectedXpath = new DOMXPath($expectedDoc);
			$actualXpath = new DOMXPath($actualDoc);
			
			$q = '//*[@id="str1"]';
			$this->assertEquals(
				$expectedXpath->query($q)->item(0)->textContent, 
				$actualXpath->query($q)->item(0)->textContent
			);
			$q = '//*[@id="str2"]';
			$this->assertEquals(
				$expectedXpath->query($q)->item(0)->textContent, 
				$actualXpath->query($q)->item(0)->textContent
			);
			
			unset($expectedDoc, $actualDoc);
			$this->assertEquals('fr', $trans->getLanguage());
			
			$swete2->getRecord()->setValue('webpage_content',"
				<div>My name is <span>Steve</span></div>");
				
			$tm->addTranslation('My name is <g id="1">Steve</g>', 'Je m\'appele <g id="1">Steve</g>', 'shannah');
			$tm->setTranslationStatus('My name is <g id="1">Steve</g>', 'Je m\'appele <g id="1">Steve</g>', XFTranslationMemory::TRANSLATION_APPROVED, 'shannah');
			
			$trans = $swete2->applyTranslationsFromMemory($tm, $stats);
			$this->assertEquals(0, $stats['misses']);
			$this->assertEquals(1, $stats['matches']);
			
			
			$tm->addTranslation('My name is <v id="1">Steve</v>', 'Mon nom est <v id="1">Steve</v>', 'shannah');
			$tm->setTranslationStatus('My name is <v id="1">Steve</v>', 'Mon nom est <v id="1">Steve</v>', XFTranslationMemory::TRANSLATION_APPROVED, 'shannah');
			$swete2->getRecord()->setValue('webpage_content',
				'<div id="str1">My name is <span data-swete-translate="1">Robert</span></div>
				<div id="str2">My name is <span data-swete-translate="1">John</span></div>
				<div id="str3">Unmatched string</div>');
				
			$trans = $swete2->applyTranslationsFromMemory($tm, $stats);
			$this->assertEquals(1, $stats['misses']);
			$this->assertEquals(2, $stats['matches']);
			
			$expectedDoc = new DOMDocument;
			@$expectedDoc->loadHtml('<div id="str1">Mon nom est <span data-swete-translate="1">Robert</span></div>
				<div id="str2">Mon nom est <span data-swete-translate="1">John</span></div>
				<div id="str3">Unmatched string</div>');
				
			$actualDoc = new DOMDocument;
			@$actualDoc->loadHtml($trans->getRecord()->val('webpage_content'));
			
			$expectedXpath = new DOMXpath($expectedDoc);
			$actualXpath = new DOMXpath($actualDoc);
			
			$q = '//*[@id="str1"]';
			$this->assertEquals(
				$expectedDoc->saveHtml($expectedXpath->query($q)->item(0)),
				$actualDoc->saveHtml($actualXpath->query($q)->item(0))
			);
			
			$q = '//*[@id="str2"]';
			$this->assertEquals(
				$expectedDoc->saveHtml($expectedXpath->query($q)->item(0)),
				$actualDoc->saveHtml($actualXpath->query($q)->item(0))
			);
			
			$q = '//*[@id="str3"]';
			$this->assertEquals(
				$expectedDoc->saveHtml($expectedXpath->query($q)->item(0)),
				$actualDoc->saveHtml($actualXpath->query($q)->item(0))
			);
			
			unset($expectedDoc, $actualDoc, $expectedXpath, $actualXpath);
			
			
			
			
			
		} catch (Exception $ex){
			echo $ex->getTraceAsString();
		}
		
		
		
	}
		
		

		
		
		
		
			
	
	static function q($sql){
		$res = mysql_query($sql, df_db());
		if ( !$res ) throw new Exception(mysql_error(df_db()));
		return $res;
	}
	
		


}


// Add this test to the suite of tests to be run by the testrunner
Dataface_ModuleTool::getInstance()->loadModule('modules_testrunner')
		->addTest('test_swetewebpage');
