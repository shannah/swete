<?php
import('PHPUnit.php');
require_once 'inc/SweteWebpage.class.php';
require_once 'inc/SweteJob.class.php';

class test_SweteJob extends PHPUnit_TestCase {
	
	function setUp(){
		SweteDb::q("delete from jobs");
		SweteDb::q("delete from websites");
		
		$siteRec = new Dataface_Record('websites', array());
		$siteRec->setValues(array(
			'website_name' => 'Live site',
			'website_url' => df_absolute_url(DATAFACE_SITE_URL.'/tests/testsites/site2/'),
			'source_language' => 'en',
			'target_language' => 'fr',
			'host' => $_SERVER['HTTP_HOST'],
			'base_path' => dirname(DATAFACE_SITE_URL).'/site2/',
			'active'=>1,
			'locked' => 0,
			'enable_live_translation' => 1
		));
		$res = $siteRec->save();
		if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());
		df_q("delete from site_text_filters where website_id='".addslashes($siteRec->val('website_id'))."'");	
		
		$liveSite = new SweteSite($siteRec);
		$this->liveSite = $liveSite;
		
		
		$staticSiteRec = new Dataface_Record('websites', array());
		$staticSiteRec->setValues(array(
			'website_name' => 'Static site',
			'website_url' => df_absolute_url(DATAFACE_SITE_URL.'/tests/testsites/site1/'),
			'source_language' => 'en',
			'target_language' => 'fr',
			'host' => $_SERVER['HTTP_HOST'],
			'base_path' => dirname(DATAFACE_SITE_URL).'/site1/',
			'active'=>1,
			'locked' => 0,
			'enable_live_translation' => 0
		));
		$res = $staticSiteRec->save();
		if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());
		df_q("delete from site_text_filters where website_id='".addslashes($staticSiteRec->val('website_id'))."'");	
		
		$staticSite = new SweteSite($staticSiteRec);
		$this->staticSite = $staticSite;
		
		
		$cuser = Dataface_AuthenticationTool::getInstance()->getLoggedInUser();
		SweteDb::q("delete from users");
		$cuser->save();
		if (!isset($cuser) ) die("You need to be logged in as an admin user for the tests to work");
		
		
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
		
		
		
		$siteRec = $this->liveSite;
		$job = SweteJob::createJob($this->liveSite);
		
		// Create a translation miss 
		$server = new ProxyServer;
		$server->logger->saveBodies = true;
		$server->site = $this->liveSite;
		$server->SERVER = array(
			'REQUEST_METHOD' => 'get'
		);
		$server->URL = $server->site->getProxyUrl().'index.html';
		$server->buffer = true;
		$server->logTranslationMisses = true;
		SweteDb::q('commit');
		$server->handleRequest();
		
		$misses = df_get_records_array('translation_miss_log', array('website_id'=>'='.$siteRec->getRecord()->val('website_id')));
		foreach ($misses as $miss){
			$job->addTranslationMiss($miss->val('translation_miss_log_id'));
		}
		
		$this->jobWithTM = $job;
		
	}
	
	
	function tearDown(){
	}
	
	
	//SweteJob functions still requiring unit tests:
	
	// containsWebpage - more test cases
	// removeWebpage
	// getWebpages, getWebpageRecords, getTranslatables ?
	// compile -  need more test cases
	
	//these require setup of job's tm:
	// extractDictionaryFromHtml
	// translatePreviousHtml
	// getTranslations
	
	
	function testCreateJob(){
		
		$siteRec = $this->liveSite;
		$job = SweteJob::createJob($siteRec);
		$this->assertTrue($job instanceof SweteJob, 'Job should be a swete job object.');
		
		$this->assertEquals($this->liveSite->getSourceLanguage(), $job->getRecord()->val('source_language'), 'Job should have same source language as the site it originated from.');
		$this->assertEquals($this->liveSite->getDestinationLanguage(), $job->getRecord()->val('destination_language'), 'Job should have same destination language as the site it originated from.');
		$this->assertEquals(SweteJob::JOB_STATUS_NEW, $job->getRecord()->val('job_status'), 'Job status should be new.');
		$this->assertEquals($siteRec->getRecord()->val('website_id'), $job->getRecord()->val('website_id'), 'Job website id should match the website.');
		
	}
		
	
	function testInbox(){
		
		$job = SweteJob::createJob($this->liveSite);
		
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
		$this->assertTrue($msgObj instanceof stdClass, 'The message should be a standard class');
		
		$this->assertEquals($msg->val('job_note_id'), $msgObj->job_note_id);
		$this->assertEquals(1, $msgObj->deleted, 'The message should have the deleted flag set');
		
		$this->assertEquals(0, $inbox->getNumMessages(), 'Inbox should be empty now since we deleted a message');
		$inbox->unDeleteMessage($msg->val('job_note_id'));
		$this->assertEquals(1, $inbox->getNumMessages(), 'Input should once again have a single message because we undeleted the message.');
		
	}	
	
	function testAssign(){
		
		$job = SweteJob::createJob($this->liveSite);
		
		$this->assertNotNull($job);
		
		$this->assertFalse($job->isJobAssigned('test_user'), "Job is already assigned to test_user");
		$job->assignJob('test_user','test_user');
		$job->refresh();
		
		$this->assertNotNull($job);
		$this->assertNotNull($job->getRecord());
		
		$this->assertEquals('test_user', $job->getRecord()->val('assigned_to'));
		$this->assertEquals(true, $job->isJobAssigned('test_user'));
		
		$this->assertFalse($job->isJobAssigned('test_user2'), "Job is already assigned to test_user2");
		
		$job->assignJob('test_user2', 'test_user');
		$job->refresh();
		$this->assertEquals('test_user2', $job->getRecord()->val('assigned_to'));
		$this->assertEquals(true, $job->isJobAssigned('test_user2'));
		
		$res = df_q("select count(*) from job_assignments where job_id='".addslashes($job->getRecord()->val('job_id'))."'");
		list($num) = mysql_fetch_row($res);
		$this->assertEquals(2, $num, 'Should be 2 assignments now');
		
		
		$job->setStatus(SweteJob::JOB_STATUS_NEW, 'test_user');
		$job->refresh();
		$this->assertEquals(SweteJob::JOB_STATUS_NEW, $job->getRecord()->val('job_status'));
		$job->refresh();
		$job->setStatus(SweteJob::JOB_STATUS_ASSIGNED, 'test_user');
	}
	
	function testAddAndRemoveTranslationMisses(){
		
		$siteRec = $this->liveSite;
		$job = SweteJob::createJob($this->liveSite);
		
		$this->assertFalse($job->getRecord()->val('compiled'), 'Job is not compiled yet but has compiled flag set.');
		
		// Create a translation miss 
		$server = new ProxyServer;
		$server->logger->saveBodies = true;
		$server->site = $this->liveSite;
		$server->SERVER = array(
			'REQUEST_METHOD' => 'get'
		);
		$server->URL = $server->site->getProxyUrl().'index.html';
		$server->buffer = true;
		$server->logTranslationMisses = true;
		SweteDb::q('commit');
		$server->handleRequest();
		
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
		
	}
	
	
	function createLiveJobWithTranslationMisses(){
	
		$siteRec = $this->liveSite;
		$job = SweteJob::createJob($this->liveSite);
		
		$this->assertFalse($job->getRecord()->val('compiled'), 'Job is not compiled yet but has compiled flag set.');
		
		// Create a translation miss 
		$server = new ProxyServer;
		$server->logger->saveBodies = true;
		$server->site = $this->liveSite;
		$server->SERVER = array(
			'REQUEST_METHOD' => 'get'
		);
		$server->URL = $server->site->getProxyUrl().'index.html';
		$server->buffer = true;
		$server->logTranslationMisses = true;
		SweteDb::q('commit');
		$server->handleRequest();
		
		// We should have some misses:
		$res = SweteDb::q("select count(*) from translation_miss_log where website_id='".addslashes($siteRec->getRecord()->val('website_id'))."'");
		list($num) = mysql_fetch_row($res);
		@mysql_free_result($res);
		
		$this->assertEquals(13, $num, 'There should be 13 misses in our translation log.');
		
		$misses = df_get_records_array('translation_miss_log', array('website_id'=>'='.$siteRec->getRecord()->val('website_id')));
		foreach ($misses as $miss){
		
			$job->addTranslationMiss($miss->val('translation_miss_log_id'));
		}
		
		
		return $job;
	}
	
	function testAddWebpageToStaticSite(){
	
		$siteRec = $this->staticSite;
		$job = SweteJob::createJob($this->staticSite);
		
		$this->assertFalse($job->getRecord()->val('compiled'), 'Job is not compiled yet but has compiled flag set.');
		
		//add webpage to static site
		$pg = new Dataface_Record('webpages', array());
		$pg->lang = 'en';
		$pg->setValues(array(
			'website_id'=>$this->staticSite->getRecord()->val('website_id'),
			'webpage_url'=>'index',
			'webpage_content' => 'Test string 1',
			'active'=>1,
			'posted_by'=>'test_user'
		));
		
		$res = $pg->save();
		if (PEAR::isError($res) ) throw new Exception($res->getMessage());
		
		$pgid = $pg->val('webpage_id');
		
		$swete1 = SweteWebpage::loadByUrl($this->staticSite->getRecord()->val('website_id'), 'index', 'en');
		$this->assertTrue($swete1 instanceof SweteWebpage);
		
		$this->assertEquals('en', $swete1->getLanguage());
		$this->assertEquals($pgid, $swete1->getRecord()->val('webpage_id'));
		
		$job->addWebpage($swete1);
		$this->assertTrue($job->containsWebpage($swete1));
		
	}
	
	function addWebpageForStaticSite($url, $strings, $user = 'test_user', $lang='en'){
		$content="";
		$stringRecords = array();
		foreach ($strings as $string){
			$strRec = XFTranslationMemory::addString($string, $lang);
			$stringId = $strRec->val('string_id');
			//$strings[$string]['string_id'] = $stringId;
			$stringRecords[$stringId] = $string;
			$content .= '<div>'.$string.'</div>';
		}
		
		//create a webpage for the strings
		$pg = new Dataface_Record('webpages', array());
		$pg->lang = 'en';
		$pg->setValues(array(
			'website_id'=>$this->staticSite->getRecord()->val('website_id'),
			'webpage_url'=>$url,
			'webpage_content' => $content,
			'active'=>1,
			'posted_by'=>'test_user'
		));
		
		$res = $pg->save();
		if (PEAR::isError($res) ) throw new Exception($res->getMessage());
		
		$pgid = $pg->val('webpage_id');
		
		//create some translation misses for the page
		foreach ($stringRecords as $id => $string){
			$estring = TMTools::encode($string, $params);
			$nstring = TMTools::normalize($estring);
			$hash = md5($estring);
			
			$tml = new Dataface_Record('translation_miss_log', array());
			$tml->setValues(array(
				'string'=> $string,
				'normalized_string'=>$nstring,
				'encoded_string'=>$estring,
				'string_hash'=>$hash,
				'translation_memory_id' =>$this->staticSite->getRecord()->val('translation_memory_id'),
				'webpage_id'=>$pgid,
				'website_id'=>$this->staticSite->getRecord()->val('website_id'),
				'source_language'=>'en',
				'destination_language'=>'fr',
				'string_id'=>$id
			));
			$res = $tml->save();
			if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());
		
		}
		
		
	
		return $pg;
	
	}
	
	function testGetStaticWebpageTranslatables_Websites(){
		
		$siteRec = $this->staticSite;
		$job = SweteJob::createJob($this->staticSite);
		
		$strings = array('Test Page 1');
		
		$pg = $this->addWebpageForStaticSite('index.html', $strings);
		
		$swetepage1 = SweteWebpage::loadById($pg->val('webpage_id'), 'en');
		$this->assertNotNull($swetepage1);
		$this->assertTrue($swetepage1 instanceof SweteWebpage, 'The page was not loaded for '.'index.html');  
		$this->assertEquals('en', $swetepage1->getLanguage());
		$this->assertEquals('<div>Test Page 1</div>', $swetepage1->getRecord()->val('webpage_content'));
		$job->addWebpage($swetepage1);
		
		$translatables = $job->getStaticWebpageTranslatables();
		$this->assertEquals(1, sizeof($translatables)); //1 webpage
		
		foreach ($translatables as $t){
			$this->assertEquals($swetepage1->getRecord()->val('webpage_id'), $t['webpage_id'], "The webpage id is wrong");
			
			$expectedPosition = strlen($t['url']) - strlen('index.html');
	   		$this->assertTrue(strrpos($t['url'], 'index.html', 0) === $expectedPosition, 
	   			'The url '.$t['url'].' should end in index.html');
	   		
	   		$this->assertEquals(2, $t['word_count'], "The word count value is wrong");
		}
		
		//ok, now add 3 more webpages
		$pgA = $this->addWebpageForStaticSite('pageA', array('Test Page A'));
		$job->addWebpage(SweteWebpage::loadById($pgA->val('webpage_id'), 'en'));
		$pgB = $this->addWebpageForStaticSite('pageB', array('Test Page B B'));
		$job->addWebpage(SweteWebpage::loadById($pgB->val('webpage_id'), 'en'));
		$pgC = $this->addWebpageForStaticSite('pageC', array('Test Page C C C'));
		$job->addWebpage(SweteWebpage::loadById($pgC->val('webpage_id'), 'en'));
		
		$translatables = $job->getStaticWebpageTranslatables();
		$this->assertEquals(4, sizeof($translatables));
		
		foreach ($translatables as $t){
			$swetepage = SweteWebpage::loadById($t['webpage_id'], 'en'); 
			$url = $swetepage->getRecord()->val('webpage_url');
			
			$expectedPosition = strlen($t['url']) - strlen($url );
	   		$this->assertTrue(strrpos($t['url'], $url , 0) === $expectedPosition, 
	   			'The url '.$t['url'].' should end in'.$url);
	   		
	   		switch ($url){
	   			case 'index.html':
	   				$expected = 2;
	   				break;
	   			case 'pageA':
	   				$expected = 3;
	   				break;
	   			case 'pageB':
	   				$expected = 4;
	   				break;
	   			case 'pageC':
	   				$expected = 5;
	   				break;
	   		
	   		}
	   		$this->assertEquals($expected, $t['word_count'], "The word count value is wrong for page url $url");
		}
		
		
		
		//now test compiled job
		$job->compile();
		$compiledTranslatables = $job->getStaticWebpageTranslatables();
		$this->assertEquals(4, sizeof($translatables));
		
		foreach ($translatables as $t){
			$swetepage = SweteWebpage::loadById($t['webpage_id'], 'en'); 
			$url = $swetepage->getRecord()->val('webpage_url');
			
			$expectedPosition = strlen($t['url']) - strlen($url );
	   		$this->assertTrue(strrpos($t['url'], $url , 0) === $expectedPosition, 
	   			'The url '.$t['url'].' should end in'.$url);
	   		
	   		switch ($url){
	   			case 'index.html':
	   				$expected = 2;
	   				break;
	   			case 'pageA':
	   				$expected = 3;
	   				break;
	   			case 'pageB':
	   				$expected = 4;
	   				break;
	   			case 'pageC':
	   				$expected = 5;
	   				break;
	   		
	   		}
	   		$this->assertEquals($expected, $t['word_count'], "The word count value is wrong for page url $url");
		}
		
		
	}
	
	function testGetStaticWebpageTranslatables_Strings(){
		
		$siteRec = $this->staticSite;
		$job = SweteJob::createJob($this->staticSite);
		$url = 'index.html';
		$strings = array("Blah blah blah string", "Doop doop doop doop string");
		
		$this->addWebpageForStaticSite($url, $strings);
		
		foreach ($strings as $string){
			$this->assertFalse( $job->containsString($string));
		}
		
		//add them to the job
		$misses = df_get_records_array('translation_miss_log', array('website_id'=>'='.$siteRec->getRecord()->val('website_id')));
		foreach ($misses as $miss){
			$job->addTranslationMiss($miss->val('translation_miss_log_id'));
		}
		
		foreach ($strings as $string){
			$this->assertTrue( $job->containsString($string));
		}
		
		$swetepage = SweteWebpage::loadByUrl($siteRec->getRecord()->val('website_id'), $url, 'en');
		$pgid = $swetepage->getRecord()->val('webpage_id');
				
		$translatables = $job->getStaticWebpageTranslatables();
		$this->assertEquals(1, sizeof($translatables));	//1 page
		
		foreach($translatables as $t){
			$this->assertEquals($pgid, $t['webpage_id']);
			
			foreach ($translatables as $t){
				$expectedPosition = strlen($t['url']) - strlen($url);
	   			$this->assertTrue(strrpos($t['url'], $url, 0) === $expectedPosition, 
	   			'The url '.$t['url'].' should end in '.$url);
			}			
			$this->assertEquals(9, $t['word_count'], "The word count value is wrong");
		}
		
		//test compiled job
		$job->compile();
		$translatables = $job->getStaticWebpageTranslatables();
		$this->assertEquals(1, sizeof($translatables));	//1 page
		
		foreach($translatables as $t){
			$this->assertEquals($pgid, $t['webpage_id']);
			
			foreach ($translatables as $t){
				$expectedPosition = strlen($t['url']) - strlen($url);
	   			$this->assertTrue(strrpos($t['url'], $url, 0) === $expectedPosition, 
	   			'The url '.$t['url'].' should end in '.$url);
			}
			$this->assertEquals(9, $t['word_count'], "The word count value is wrong");
		}
		
		
	}
	
	function testGetLiveWebpageTranslatables(){
		
		$siteRec = $this->liveSite;
		
		$job = $this->createLiveJobWithTranslationMisses();
		
		$translatables = $job->getLiveWebpageTranslatables();
		
		$this->assertEquals(1, sizeof($translatables)); //only 1 result, because all the misses are for the same page/url
		
		foreach ($translatables as $t){

			//get all the strings for this job_translatable_id
			
			$res = SweteDb::q("SELECT http_request_log.request_url, translation_miss_log.normalized_string as string
				FROM  translation_miss_log
				INNER JOIN  http_request_log ON translation_miss_log.http_request_log_id = http_request_log.http_request_log_id
				WHERE http_request_log.request_url = '".$t['url']."'");
			
			while ($row = mysql_fetch_assoc($res) ){
				$s = $row['string'];
				$this->assertTrue($job->containsString($s),  'The job is missing a string that should be there: '.$s);
				
			}
			
		}
		
	}
	
	
	function testCompileNoTranslatables(){
		$siteRec = $this->liveSite;
		$job = SweteJob::createJob($this->liveSite);
		
		$this->assertFalse($job->getRecord()->val('compiled'), 'Job is not compiled yet but has compiled flag set.');
		
		try{
			$job->compile();
		}catch(Exception $e){
			$this->assertEquals("The job has no translatable content.", $e->getMessage());
			return;
		}
		$this->fail('Expected exception trying to compile a job with no translatable content');
		
		
	}
	
	function testCompileStaticSite(){
	
		$siteRec = $this->staticSite;
		$job = SweteJob::createJob($this->staticSite);
		
		$this->assertFalse($job->getRecord()->val('compiled'), 'Job is not compiled yet but has compiled flag set.');
		
		//add webpage to static site
		$webpageContent = 'Test string 1';
		$pg = new Dataface_Record('webpages', array());
		$pg->lang = 'en';
		$pg->setValues(array(
			'website_id'=>$this->staticSite->getRecord()->val('website_id'),
			'webpage_url'=>'index',
			'webpage_content' => $webpageContent,
			'active'=>1,
			'posted_by'=>'test_user'
		));
		
		$res = $pg->save();
		if (PEAR::isError($res) ) throw new Exception($res->getMessage());
		
		$pgid = $pg->val('webpage_id');
		
		$page1 = SweteWebpage::loadByUrl($this->staticSite->getRecord()->val('website_id'), 'index', 'en');
		$this->assertTrue($page1 instanceof SweteWebpage);
		
		
		//add webpage to job
		$job->addWebpage($page1);
		$this->assertTrue($job->containsWebpage($page1));
		
		$translatable = df_get_record('job_translatable', array('job_id'=>'='.$job->getRecord()->val('job_id')));
		$this->assertFalse($translatable);
		
		$job->compile();
		
		$res = df_q("select count(*) from job_translatable where job_id='".addslashes($job->getRecord()->val('job_id'))."'");
		list($num) = mysql_fetch_row($res);
		$this->assertEquals(1, $num, 'Should be one translatable created for job.');
		
		
		$translatable = df_get_record('job_translatable', array('job_id'=>'='.$job->getRecord()->val('job_id')));
		$this->assertTrue($translatable instanceof Dataface_Record, 'No translatable found for job.');
		$this->assertEquals($webpageContent, strip_tags($translatable->val('full_contents')));
		
		$job->refresh();
		$this->assertEquals(1, $job->getRecord()->val('compiled'), 'Job compiled flag is not set but should be');
		
	}
	
	
	
	function testCompileLiveSite(){
		
		$job = $this->createLiveJobWithTranslationMisses();
		
		$job->compile();
		
		$res = df_q("select count(*) from job_translatable where job_id='".addslashes($job->getRecord()->val('job_id'))."'");
		list($num) = mysql_fetch_row($res);
		$this->assertEquals(1, $num, 'Should be one translatable created for job.');
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
	
	function testGetPageWordCount(){
	
		//numbers not counted
		$strings = array('String one', 'String two', 'String 3 12/06/2012'); 
		
		$this->assertEquals(5, SweteJob::getPageWordCount($strings));

		//tags not counted
		$strings = array(
			'<div id="wrapper">',
			'<div id="top-bar"><span id="top-left"></span><span id="top-right"></span></div>',
			'<div id="inner-wrapper">',
			'   <div id="heading">',
			'       <div id="languages">',
			'
				   </div>
			 ',      
			 '      <div id="site-network">',
			'   <h4>Site Network:</h4>',
			'   <ul>
				   <li class="selected"><a href="http://weblite.ca" title="Web Lite Corporate">Corporate</a></li>
				   <li><a href="http://solutions.weblite.ca" title="Web Lite Solutions Corp.">Solutions</a></li>
				   <li><a href="http://translate.weblite.ca" title="Web Lite Translation Corp.">Translation</a></li>
			   </ul>',
			'   <div style="clear:both">&nbsp;</div>',
			'</div>',
			'       
				   <h1><span>Web Lite Group of Companies</span></h1>');
		
		$this->assertEquals(10, SweteJob::getPageWordCount($strings));
	}
	
	/**
	*	Tests approve() on a job for a live site, where several translations were added to the job
	*/
	function testApproveLive(){
		$job = $this->createLiveJobWithTranslationMisses();
		
		$job->compile();
		
		$username = 'test_user';
		
		$tm = $job->getTranslationMemory();
		$trec = $tm->setTranslationStatus('Services', 'Services Translated', XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		$trec = $tm->setTranslationStatus('Products', 'Products Translated', XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		$trec = $tm->setTranslationStatus('Quality, Affordable, Web-based Software Solutions', 'Quality, Affordable, Web-based Software Solutions Translated', XFTranslationMemory::TRANSLATION_SUBMITTED, $username);

		$this->assertEquals (SweteJob::JOB_STATUS_NEW, $job->getRecord()->val('job_status'));
		
		$job->approve('test_user');
		$job->refresh();
		
		$this->assertEquals (SweteJob::JOB_STATUS_CLOSED, $job->getRecord()->val('job_status'));
		
		$this->assertTrue(sizeof($job->getWebpageRecords()>0), 'Job must contain webpages');
		
		foreach($job->getWebpageRecords() as $webpage){
			
			$tm = XFTranslationMemory::loadTranslationMemoryFor($webpage, 
				$job->getRecord()->val('source_language'),
				$job->getRecord()->val('destination_language'));
			
			$this->assertTrue($tm->containsTranslation('Services', 'Services Translated'));
			$this->assertTrue($tm->containsTranslation('Products', 'Products Translated'));
			$this->assertTrue($tm->containsTranslation('Quality, Affordable, Web-based Software Solutions', 'Quality, Affordable, Web-based Software Solutions Translated'));
		}
	}
	
	/**
	*	Tests approve() on a job for a static site, where a webpage was added to the job, and one translation for the webpage
	*/
	function testApproveStaticWebpage(){
	
		$job = SweteJob::createJob($this->staticSite);
		$username = 'test_user';
		
		$pg = $this->addWebpageForStaticSite('page', array('Test String'));
		$job->addWebpage(SweteWebpage::loadById($pg->val('webpage_id'), 'en'));
		
		$job->compile();
	
		$tm = $job->getTranslationMemory();
		$trec = $tm->setTranslationStatus('Test String', 'Test String Translated', XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		
		$this->assertEquals (SweteJob::JOB_STATUS_NEW, $job->getRecord()->val('job_status'));
		
		$job->approve('test_user');
		$job->refresh();
		
		$this->assertEquals (SweteJob::JOB_STATUS_CLOSED, $job->getRecord()->val('job_status'));
		
		foreach($job->getWebpageRecords() as $webpage){
			
			$tm = XFTranslationMemory::loadTranslationMemoryFor($webpage, 
				$job->getRecord()->val('source_language'),
				$job->getRecord()->val('destination_language'));
			$this->assertTrue($tm->containsTranslation('Test String', 'Test String Translated'));
			
		}
	}
	
	
	/**
	* Tests approve() on a job for a static site, where several strings were added to the job, and some of the strings were translated
	*/
	function testApproveStaticString(){
	
		$job = SweteJob::createJob($this->staticSite);
		$username = 'test_user';
		
		//add strings to the job
		$strings = array("Blah blah blah string", "Doop doop doop doop string", "Whee whee whee whee whee string", "Hey hey hey hey hey strings");
		$this->addWebpageForStaticSite($url, $strings);
		$misses = df_get_records_array('translation_miss_log', array('website_id'=>'='.$this->staticSite->getRecord()->val('website_id')));
		foreach ($misses as $miss){
			$job->addTranslationMiss($miss->val('translation_miss_log_id'));
		}
		
		$job->compile();
	
		$tm = $job->getTranslationMemory();
		$trec = $tm->setTranslationStatus('Blah blah blah string', 'string',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		$trec = $tm->setTranslationStatus('Whee whee whee whee whee string', 'string',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		
		$this->assertEquals (SweteJob::JOB_STATUS_NEW, $job->getRecord()->val('job_status'));
		
		$job->approve('test_user');
		$job->refresh();
		
		$this->assertEquals (SweteJob::JOB_STATUS_CLOSED, $job->getRecord()->val('job_status'));
		
		foreach($job->getWebpageRecords() as $webpage){
			
			$tm = XFTranslationMemory::loadTranslationMemoryFor($webpage, 
				$job->getRecord()->val('source_language'),
				$job->getRecord()->val('destination_language'));
			$this->assertTrue($tm->containsTranslation('Blah blah blah string', 'string'));
			$this->assertTrue($tm->containsTranslation('Whee whee whee whee whee string', 'string'));
		}
	}
	
	/**
	* Tests getStats() on a job for a static site
	*/
	function testGetStatsStaticSite(){
	
		$job = SweteJob::createJob($this->staticSite);
		$username = 'test_user';
		
		//add strings to the job
		$strings = array("Blah blah blah string", "Doop doop doop doop string", "Whee whee whee whee whee string", "Hey hey hey hey hey strings");
		$this->addWebpageForStaticSite($url, $strings);
		$misses = df_get_records_array('translation_miss_log', array('website_id'=>'='.$this->staticSite->getRecord()->val('website_id')));
		foreach ($misses as $miss){
			$job->addTranslationMiss($miss->val('translation_miss_log_id'));
		}
		
		$job->compile();
	
		$tm = $job->getTranslationMemory();
		$trec = $tm->setTranslationStatus('Blah blah blah string', 'blah',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		$trec = $tm->setTranslationStatus('Whee whee whee whee whee string', 'whee',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		
		$stats = $job->getStats();
		
		//find the no of translated words/phrases
		$words = 2;
		$phrases = 2;
		
		$this->assertEquals(21, $stats['words']);
		$this->assertEquals(4, $stats['phrases']);
		$this->assertEquals($words, $stats['wordsTranslated']);
		$this->assertEquals($phrases, $stats['phrasesTranslated']);
		$this->assertEquals(11, $stats['wordsNotTranslated']);
		$this->assertEquals(2, $stats['phrasesNotTranslated']);
		
	}
	
	/**
	* Tests getStats() on a job for a live site
	*/
	function testGetStatsLiveSite(){
		
		$job = $this->createLiveJobWithTranslationMisses();
		$job->compile();
		$username = 'test_user';
		$tm = $job->getTranslationMemory();
		$trec = $tm->setTranslationStatus('Services', 'Services Translated',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		$trec = $tm->setTranslationStatus('Products', 'Products Translated',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		$trec = $tm->setTranslationStatus('Quality, Affordable, Web-based Software Solutions', 'Quality, Affordable, Web-based Software Solutions Translated',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		
		$stats = $job->getStats();
		
		//find the no of translated words/phrases
		$words = 10;
		$phrases = 3;
		
		$this->assertEquals(63, $stats['words']);
		$this->assertEquals(13, $stats['phrases']);
		$this->assertEquals($words, $stats['wordsTranslated']);
		$this->assertEquals($phrases, $stats['phrasesTranslated']);
		$this->assertEquals(56, $stats['wordsNotTranslated']);
		$this->assertEquals(10, $stats['phrasesNotTranslated']);
	
	}
	
	function testExtractDictionaryFromHtml(){
	
		//create a job so that we can use its translation memory
		$job = SweteJob::createJob($this->staticSite);
		$username = 'test_user';
		
		//add strings to the job
		$strings = array("Blah blah blah string", "Doop doop doop doop string", "Whee whee whee whee whee string", "Hey hey hey hey hey strings");
		$this->addWebpageForStaticSite($url, $strings);
		$misses = df_get_records_array('translation_miss_log', array('website_id'=>'='.$this->staticSite->getRecord()->val('website_id')));
		foreach ($misses as $miss){
			$job->addTranslationMiss($miss->val('translation_miss_log_id'));
		}
		$job->compile();
	
		$tm = $job->getTranslationMemory();
		$trec = $tm->setTranslationStatus('cheese', 'fromage',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		$trec = $tm->setTranslationStatus('ham', 'jambon',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
		
		$html = "<div>apple</div>
			<div>cheese</div>
			<div>Ham</div>
			<div>ham and cheese</div>";
		
		$dict = $job->extractDictionaryFromHtml($job->getTranslationMemory(), $html);
		
		$this->assertTrue(isset($dict));
		$this->assertEquals(4, sizeof($dict));
		
		
		foreach ($dict as $k=>$v){
			error_log("extractDictionaryFromHtml result: $k => $v");
		}
		
		$this->assertTrue(empty($dict[0]));
		$this->assertEquals("fromage", $dict[1]);
		$this->assertTrue(empty($dict[2]));
		$this->assertTrue(empty($dict[3]));
	}
	
	// function testTranslatePreviousHtml(){
// 	
// 		//create a job so that we can use its translation memory
// 		$job = SweteJob::createJob($this->staticSite);
// 		$username = 'test_user';
// 		
// 		//add strings to the job
// 		$strings = array("Blah blah blah string", "Doop doop doop doop string", "Whee whee whee whee whee string", "Hey hey hey hey hey strings");
// 		$this->addWebpageForStaticSite($url, $strings);
// 		$misses = df_get_records_array('translation_miss_log', array('website_id'=>'='.$this->staticSite->getRecord()->val('website_id')));
// 		foreach ($misses as $miss){
// 			$job->addTranslationMiss($miss->val('translation_miss_log_id'));
// 		}
// 		$job->compile();
// 	
// 		$tm = $job->getTranslationMemory();
// 		$trec = $tm->setTranslationStatus('cheese', 'fromage',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
// 		$trec = $tm->setTranslationStatus('ham', 'jambon',  XFTranslationMemory::TRANSLATION_SUBMITTED, $username);
// 		$trec = $tm->setTranslationStatus('wine', 'du vin',  XFTranslationMemory::TRANSLATION_APPROVED, $username);
// 		
// 		$html = "<div>apple</div>
// 			<div>cheese</div>
// 			<div>Ham</div>
// 			<div>ham and cheese</div>
// 			<div>wine</div>";
// 		
// 		
// 		//get dictionary from job
// 		$dict = $job->extractDictionaryFromHtml($job->getTranslationMemory(), $html);
// 		
// 		
// 		$result1 = $job->translatePreviousHtml($html, $dict , 3, 5);
// 		
// 		error_log("result1 is : $result1");
// 		
// 		$result2 = $job->translatePreviousHtml($html, $dict , 3, 3);
// 		error_log("result2 is : $result2");
// 		
// 		$result3 = $job->translatePreviousHtml($html, $dict , 3, 5);
// 		error_log("result3 is : $result3");
// 	}
	
	
}


// Add this test to the suite of tests to be run by the testrunner
Dataface_ModuleTool::getInstance()->loadModule('modules_testrunner')
		->addTest('test_SweteJob');