<?php
/**
 * SWeTE Server: Simple Website Translation Engine
 * Copyright (C) 2012  Web Lite Translation Corp.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once 'modules/tm/lib/XFTranslationMemory.php';

class SweteJob {

	const JOB_STATUS_NEW = 1;
	const JOB_STATUS_ASSIGNED = 2;
	const JOB_STATUS_FEEDBACK = 3;
	const JOB_STATUS_RESOLVED = 4;
	const JOB_STATUS_CLOSED = 5;
	
	const JOB_ROLE_READ_ONLY = 1;
	const JOB_ROLE_TRANSLATOR = 2;
	const JOB_ROLE_PROOFREADER = 3;
	const JOB_ROLE_MANAGER = 4;
	const JOB_ROLE_OWNER = 5;

	private $_rec;
	private $site;
	private $_translationMemory;
	
	
	public static function decorateNewJob(SweteSite $site, Dataface_Record $rec){
		require_once 'modules/tm/lib/XFTranslationMemory.php';
		try {
			SweteDb::q('start transaction');
			// First we need to create a translation memory for this job.
			$tm = XFTranslationMemory::createTranslationMemory(
				'Job translation memory for site '.$site->getRecord()->val('website_id').' '.$site->getSourceLanguage().'->'.$site->getDestinationLanguage(), 
				$site->getSourceLanguage(),
				$site->getDestinationLanguage());
				
			$status = SweteJob::JOB_STATUS_NEW;
			if ($rec->val('assigned_to')){
				$status = SweteJob::JOB_STATUS_ASSIGNED;
			}
			
			$rec->setValues(array(
				'website_id'=>$site->getRecord()->val('website_id'),
				'date_created' => date('Y-m-d H:i:s'),
				'job_status' => $status,
				'translation_memory_id' => $tm->getRecord()->val('translation_memory_id'),
				'source_language' => $site->getSourceLanguage(),
				'destination_language' => $site->getDestinationLanguage()
			));
			
			
			
			
			//print_r($rec->vals());
			$res = $rec->save();
			if ( PEAR::isError($res) ){
				//print_r($res);
				throw new Exception($res->getMessage(), $res->getCode());
			}
			
			if ( $rec->val('posted_by') ){
				$res = df_q("insert ignore into job_roles (job_id, username, access_level) values ('".addslashes($rec->val('job_id'))."','".addslashes($rec->val('posted_by'))."','".self::JOB_ROLE_OWNER."')");
			}
			
			if ( $rec->val('assigned_to') ){
				$res = df_q("insert ignore into job_roles (job_id, username, access_level) values ('".addslashes($rec->val('job_id'))."','".addslashes($rec->val('assigned_to'))."','".self::JOB_ROLE_TRANSLATOR."')");
			}
			
			SweteDb::q('commit');
			$out = new SweteJob($rec);
			$out->setTranslationMemory($tm);
			return $out;
			
		} catch (Exception $ex){
			SweteDb::q('rollback');
			throw $ex;
		
		}
	
	}
	
	public static function createJob(SweteSite $site){
		return self::decorateNewJob($site, new Dataface_Record('jobs', array()));
			
			
	}
	
	/*
	 * @brief Counts the number of words in an array of strings
	 * @param $strings array of strings from a webpage
	 */
	public static function getPageWordCount($strings){
		//set the word count
		$pageWordCount = 0;
		foreach($strings as $string){
			$pageWordCount += str_word_count(html_entity_decode(strip_tags($string), ENT_QUOTES, 'UTF-8'));
		}
		return $pageWordCount;
	}
	
	
	public function setTranslationMemory(XFTranslationMemory $tm){
		$this->_translationMemory = $tm;
	}
	
	public function __construct(Dataface_Record $jobRecord){
		$this->_rec = $jobRecord;
	}
	
	public function getRecord(){
		return $this->_rec;
	}
	
	
	public function addTranslationMiss($translationMissId){
		SweteDb::q("insert ignore into job_inputs_translation_misses (job_id,translation_miss_log_id) values ('".addslashes($this->_rec->val('job_id'))."','".addslashes($translationMissId)."')");
	}
	
	
	public function removeTranslationMiss($translationMissId){
		SweteDb::q("delete from job_inputs_translation_misses where job_id='".addslashes($this->_rec->val('job_id'))."' and translation_miss_log_id='".addslashes($translationMissId)."'");
		
	
	}
	
	public function isCompiled(){
		return $this->_rec->getValue('compiled');
	}
	
	/**
	 * @returns XFTranslationMemory
	 */
	public function getTranslationMemory(){
		if ( !isset($this->_translationMemory) ){
			$this->_translationMemory = XFTranslationMemory::loadTranslationMemoryById(
				$this->_rec->val('translation_memory_id')
			);
		}
		return $this->_translationMemory;
	}
	
	public function containsString($string){
	
		
		$estring = TMTools::encode($string, $params);
		$nstring = TMTools::normalize($estring);
		$hash = md5($estring);
		
		$res = SweteDb::q("select job_id from job_inputs_webpages_strings where job_id='".addslashes($this->_rec->val('job_id'))."' and string_hash='".addslashes($hash)."' limit 1");
		if ( mysql_num_rows($res) > 0 ){
			@mysql_free_result($res);
			return true;
		}
		
		@mysql_free_result($res);
		
		$res = SweteDb::q("select job_id 
			from 
				job_inputs_translation_misses jitm 
				inner join translation_miss_log tml on jitm.translation_miss_log_id=tml.translation_miss_log_id 
			where
				jitm.job_id = '".addslashes($this->_rec->val('job_id'))."'
				and
				tml.string_hash = '".addslashes($hash)."'
			limit 1");
			
		if ( mysql_num_rows($res) > 0 ){
			@mysql_free_result($res);
			return true;
		} else {
			@mysql_free_result($res);
			return false;
		}
	}
	
	public function removeString($translation_miss_log_id){
		SweteDb::q("delete from job_inputs_translation_misses where job_id='".addslashes($this->_rec->val('job_id'))."' and translation_miss_log_id='".addslashes($translation_miss_log_id)."';");
	}
	
	public function removeRequestStrings($http_request_log_id){
		SweteDb::q("DELETE FROM job_inputs_translation_misses WHERE translation_miss_log_id IN (SELECT translation_miss_log_id FROM translation_miss_log WHERE http_request_log_id='".$http_request_log_id."');");
	}
	
	public function refresh(){
		error_log("Refreshing ....");
		error_log($this->_rec->getId());
		$this->_rec = df_get_record_by_id($this->_rec->getId());
	}
	
	public function addWebpage(SweteWebpage $webpage, $strings = array(), $ignoreNoStrings = false ){
		
		if ( !$strings ){
			$site = $webpage->getSite();
			$proxyWriter = $site->getProxyWriter();
			
			$tmid = $webpage->getTranslationMemoryId(true);
			$tm = XFTranslationMemory::loadTranslationMemoryById($tmid);
			if ( !$tm ){
				throw new Exception("No translation memory found for webpage.");
			}
			$proxyWriter->setTranslationMemory($tm);
			$proxyWriter->translateHtml($webpage->getRecord()->val('webpage_content'),  $stats, true);
			if ( @$stats['log'] ){
				$strings = $stats['log'];
			}
		}
		
		if ( !$strings && !$ignoreNoStrings){
			throw new Exception("No strings found to translate.");
		}
		
		$sql = "insert ";
		if ($ignoreNoStrings){
			$sql .= "ignore ";
		}
		
		$sql .= "into job_inputs_webpages (job_id, webpage_id) 
			values
				('".addslashes($this->_rec->val('job_id'))."','". addslashes($webpage->getRecord()->val('webpage_id'))."')";
		
		SweteDb::q($sql);
		
		if ($strings){
			foreach ($strings as $string){
				if ( !$this->containsString($string) ){
					$nstr = TMTools::normalize($string);
					unset($params);
					$estr = TMTools::encode($nstr, $params);
					$hash = md5($estr);
				
					SweteDb::q("insert into job_inputs_webpages_strings (job_id, webpage_id, string, string_hash)
					values
					(
						'".addslashes($this->_rec->val('job_id'))."',
						'".addslashes($webpage->getRecord()->val('webpage_id'))."',
						'".addslashes($string)."',
						'".addslashes($hash)."'
					)");
					
				}
			}
		}
	
	}
	
	
	public function getSite(){
		if ( !isset($this->site) ){
			$this->site = SweteSite::loadSiteById($this->_rec->val('website_id'));
		}
		return $this->site;
	
	}
	
	public function removeWebpage(SweteWebpage $webpage){
		SweteDb::q("delete from job_inputs_webpages where job_id='".addslashes($this->_rec->val('job_id'))."' and webpage_id='".addslashes($webpage->getRecord()->val('webpage_id'))."'");
		SweteDb::q("delete from job_inputs_webpages_strings where job_id='".addslashes($this->_rec->val('job_id'))."' and webpage_id='".addslashes($webpage->getRecord()->val('webpage_id'))."'");
		SweteDb::q("delete from job_inputs_translation_misses where job_id='".addslashes($this->_rec->val('job_id'))."' and translation_miss_log_id in ( select translation_miss_log_id from translation_miss_log where webpage_id = '".addslashes($webpage->getRecord()->val('webpage_id'))."')");
	}
	
	public function containsWebpage(SweteWebpage $webpage){
		$res = SweteDb::q("select * from job_inputs_webpages where job_id='"
				.addslashes($this->_rec->val('job_id'))
				."' and webpage_id='"
				.addslashes($webpage->getRecord()->val('webpage_id'))."'");
				
		
		if ( mysql_num_rows($res) > 0 ){
			@mysql_free_result($res);
			return true;
		}
		
		@mysql_free_result($res);
		return false;
	}
	
	/*
	 * @returns info about all the webpages for this job
	 *
	*/
	public function getWebpages(){
		
		if ($this->isCompiled()){
		
			//use the job_translatable
			$res = SweteDb::q("SELECT job_id, webpage_id, job_translatable_id, word_count, source_url as webpage_url FROM job_translatable 
					WHERE job_id='"
					.addslashes($this->_rec->val('job_id'))."' and webpage_id is not null");
					
		
		}else{
		
			$res = SweteDb::q("select job_inputs_webpages.job_id, webpages.webpage_id, webpages.webpage_url
							from job_inputs_webpages 
							inner join webpages on job_inputs_webpages.webpage_id = webpages.webpage_id
							where job_inputs_webpages.job_id ='"
							.addslashes($this->_rec->val('job_id'))."'");
		}
		
		
		$pages = array();		
		while ($row = mysql_fetch_assoc($res) ){
			$pages[] = $row;
		}
		return $pages;
		
	}
	
	/*
	 * @brief Finds all webpages in the static job, including webpages and translation misses,
	 * grouped by webpage
	 * @return array of webpage rows, with values webpage_id, url, word_count . If the job has been compiled, then job_translatable_id is also included.
	 *
	*/
	public function getStaticWebpageTranslatables(){
		
		if ($this->isCompiled()){
			
			//use the job_translatable
			$q = "SELECT job_id, webpage_id, job_translatable_id, word_count, source_url as url FROM job_translatable 
					WHERE job_id='"
					.addslashes($this->_rec->val('job_id'))."' and webpage_id is not null";
			
		}else{
			
			//use the job_input_webpages and job_input_translation misses
			
			$q = "SELECT webpages.webpage_id, concat(websites.website_url, webpages.webpage_url) as url,  SUM(xf_tm_strings.num_words) as word_count
					FROM webpages
					INNER JOIN websites ON websites.website_id = webpages.website_id
					LEFT JOIN job_inputs_webpages ON job_inputs_webpages.webpage_id = webpages.webpage_id
					LEFT JOIN translation_miss_log ON webpages.webpage_id = translation_miss_log.webpage_id
					LEFT JOIN job_inputs_translation_misses ON job_inputs_translation_misses.translation_miss_log_id = translation_miss_log.translation_miss_log_id
					LEFT JOIN xf_tm_strings ON translation_miss_log.string_id = xf_tm_strings.string_id
					WHERE job_inputs_webpages.job_id='".addslashes($this->_rec->val('job_id'))."'
					 OR job_inputs_translation_misses.job_id='".addslashes($this->_rec->val('job_id'))."'
					GROUP BY webpages.webpage_id;";
		
		}
		
		$res = SweteDb::q($q);
		
		$pages = array();		
		while ($row = mysql_fetch_assoc($res) ){
			$pages[] = $row;
		}
		return $pages;
		
	
	}
	
	/**
	 * @brief Gets the translatables for all 'live' webpages in this job.
	 * @returns an array with keys: job_translatable_id or translation_miss_log_id, word_count, url
	 * If the job is not compiled yet, then translation_miss_log_id will be set. Otherwise, job_translatable_id will be set.
	 * ATTENTION : The translation_miss_log_id is not quite correct. One http_request log can contain many translation misses,
	 * and the translation_miss_log_id is only one of them.
	 *
	*/
	public function getLiveWebpageTranslatables(){
		
		if ($this->isCompiled()){
		
			//use the job_translatable
			$q = "SELECT job_translatable_id, word_count, source_url as url FROM job_translatable 
					WHERE job_id='"
					.addslashes($this->_rec->val('job_id'))."' and webpage_id is null";
					
			$res = SweteDb::q($q);
		
			$pages = array();		
			while ($row = mysql_fetch_assoc($res) ){
				$pages[] = $row;
			}	
		
		}else{
		
			//use the http_request_log.
			//job_translatable_id is NOT set
			$q = "SELECT http_request_log.http_request_log_id, http_request_log.request_url as url, translation_miss_log.translation_miss_log_id, SUM(xf_tm_strings.num_words) as word_count
					FROM http_request_log
					INNER JOIN translation_miss_log ON http_request_log.http_request_log_id = translation_miss_log.http_request_log_id
					INNER JOIN job_inputs_translation_misses ON translation_miss_log.translation_miss_log_id = job_inputs_translation_misses.translation_miss_log_id
					LEFT JOIN xf_tm_strings ON translation_miss_log.string_id = xf_tm_strings.string_id
					WHERE job_inputs_translation_misses.job_id='"
					.addslashes($this->_rec->val('job_id'))."' 
					GROUP BY http_request_log.request_url";
					
					
			
			$res = SweteDb::q($q);
			$pages = array();		
			while ($row = mysql_fetch_assoc($res) ){
				$pages[] = $row;
			}
		}
		
		
		return $pages;
	}
	
	/*
	 * @returns array of Dataface_Record webpage records for this job
	 */
	public function getWebpageRecords(){
		
		$res = SweteDb::q("select webpages.*
							from job_inputs_webpages 
							inner join webpages on job_inputs_webpages.webpage_id = webpages.webpage_id
							where job_inputs_webpages.job_id ='"
							.addslashes($this->_rec->val('job_id'))."'");
		
		$pageRecords = array();		
		while ($row = mysql_fetch_assoc($res) ){
			$rec = new Dataface_Record("webpages", $row);
			$pageRecords[] = $rec;
		}
		return $pageRecords;
		
	}
	
	/*
	 * @returns info from all job_translatable records for the job, 
	 * as an array with the webpage_id as the index.
	 *
	*/
	public function getTranslatables(){
		
		$res = SweteDb::q("select job_translatable_id, word_count, webpage_id from job_translatable
							where job_translatable.job_id='"
							.addslashes($this->_rec->val('job_id'))."'");
		
		$translatables = array();
		while ($row = mysql_fetch_assoc($res)){
			$translatables[$row['webpage_id']] = $row;
		}
		
		return $translatables;
	}
	
	/**
	 * @brief Counts the number of strings in the job, 
	 * the number of strings and words that have been translated, 
	 * and the number that have not been translated.
	 *
	 * @returns array of stats. Keys are 'words', 'phrases', 'wordsTranslated',
	 *				 'phrasesTranslated', 'wordsNotTranslated', 'phrasesNotTranslated'
	 *
	 */
	public function getStats(){
	
		$res = SweteDb::q("select translatable_contents, word_count from job_translatable
							where job_translatable.job_id='"
							.addslashes($this->_rec->val('job_id'))."'");
		
		$totalStrings = 0;
		$totalWords = 0;
		while ($row = mysql_fetch_assoc($res)){
			$totalStrings += substr_count($row['translatable_contents'], "<div>");
			$totalWords += $row['word_count'];
		}
		
		if ($totalWords != $this->_rec->val('word_count')){
			error_log("problem in SweteJob getStats. Word count ".$this->_rec->val('word_count')." but total words found are ".$totalWords);
		}
		
		
		//get content of all translatables
		$res = SweteDb::q("select full_contents from job_translatable
							where job_translatable.job_id='"
							.addslashes($this->_rec->val('job_id'))."'");
		$content = "";
		while ($row = mysql_fetch_assoc($res)){
			$content .= $row['full_contents'];
		}
		
		//get the strings for the content
		require_once 'inc/WebLite_Translate.class.php';
		$translator = new Weblite_HTML_Translator();
		$html2 = $translator->extractStrings($content);
		$strings = $translator->strings;
		foreach ($strings as $k=>$v){
			unset($params);
			$strings[$k] = TMTools::encode($v, $params);
		}
		
		//get the translations for the strings
		//translations is array with same length as strings, but only the TRANSLATED values are set
		$translations = $this->getTranslationMemory()->getTranslations($strings);
		
		
		//count the translated and missed words and strings
		$missedStrings = $translatedStrings = 0;
		$missedWordCount = $translatedWordCount = 0;
		
		foreach ($translations as $k=>$v){
			if ( isset($v) ){
				$translatedWordCount += str_word_count($v);
				$translatedStrings++;
			} else {
				$missedStrings++;
				$missedWordCount += str_word_count(strip_tags($strings[$k]));
			}
		}
		
		return array(
				'words'=>$this->_rec->val('word_count'),
				'phrases'=>$totalStrings,
				'wordsTranslated'=>$translatedWordCount,
				'phrasesTranslated'=>$translatedStrings,
				'wordsNotTranslated'=>$missedWordCount,
				'phrasesNotTranslated'=>$missedStrings
		);
		
	}
	
	/**
	 * @brief Retrieves each string in the job, and translations for each string
	 * 
	 * @returns array, with the following keys for each string:
	 *		- source - the original, source string
	 *		- previous - the previous translation, if any
	 *		- new - the new translation, added to this job
	 *		- status - either untranslated or translated
	 */
	public function getTranslations(){
		
		require_once 'inc/SweteSite.class.php';
		
		//get content of all translatables
		$res = SweteDb::q("select translatable_contents, previous_translations from job_translatable
							where job_translatable.job_id='"
							.addslashes($this->_rec->val('job_id'))."'");
		$content = "";
		$previousTranslations = array();
		while ($row = mysql_fetch_assoc($res)){
			$content .=  $row['translatable_contents'];
			$previousTranslations = array_merge($previousTranslations, unserialize($row['previous_translations']));
		}
		
		//get the strings for the content
		require_once 'inc/WebLite_Translate.class.php';
		$translator = new Weblite_HTML_Translator();
		$html2 = $translator->extractStrings($content);
		$strings = $translator->strings;
		foreach ($strings as $k=>$v){
			unset($params);
			$strings[$k] = TMTools::encode($v, $params);
		}
		
		//get the translations for the strings
		//translations is array with same length as strings, but only the TRANSLATED values are set
		$jobTranslations = $this->getTranslationMemory()->getTranslations($strings, XFTranslationMemory::TRANSLATION_SUBMITTED, XFTranslationMemory::TRANSLATION_APPROVED, true);
		
		
		//build an array with the source strings, prev translated and new translated strings
		$results = array();
		foreach ($strings as $k=>$v){
			
			if (isset($jobTranslations[$k])){
				$status = 'new translation';
			}else if (isset($previousTranslations[$k])){
				$status = 'translated previously';
			}else{
				$status = 'not translated';
			}
			
			$new = $new_user = $new_date = null;
			if (isset($jobTranslations[$k])){
				$new = $jobTranslations[$k]['translation_value'];
				$new_user = $jobTranslations[$k]['created_by'];
				$new_date = $jobTranslations[$k]['date_created'];
			}
			
			$results[] = array(
							'source' => $strings[$k],
							'previous' => $previousTranslations[$k],
							'new' => $new,
							'new_user' => $new_user,
							'new_date' => $new_date,
							'status' => $status);
		}
		
		return $results;
	
	}
	
	/**
	 * @brief Get all users added to this job
	 * @returns an array of usernames
	 */
	public function getUsers(){
	
		$res = SweteDb::q("select username from job_roles where job_id='".addslashes($this->_rec->val('job_id'))."'");
		
		$users = array();
		while ($row = mysql_fetch_assoc($res) ){
			$users[] = $row['username'];
		}
		@mysql_free_result($res);
		
		return $users;
	}
	
	/**
	 * Compiles the job inputs into its final form so that it can be worked on.  Before
	 * this step the job just has a loose set of input webpages strings and translation
	 * misses.  This will grab all of the resources that it needs to be able to 
	 * present the job to a translator.  This includes loading all resources for all
	 * pages used into the data structure so that the job doesn't depend on outside
	 * factors.
	 */
	public function compile(){
		require_once 'inc/SweteJobPageSucker.php';
		try {
			
			$res = SweteDb::q("select tml.webpage_id, tml.translation_miss_log_id, tml.string
				from 
					translation_miss_log tml 
					inner join job_inputs_translation_misses jitm on jitm.translation_miss_log_id=tml.translation_miss_log_id
					where jitm.job_id='".addslashes($this->_rec->val('job_id'))."' and
					tml.webpage_id is not null");
			
			$missedWebpageIds = array();
			while ($row = mysql_fetch_assoc($res) ){
				
				$missedWebpageIds[$row['webpage_id']][] = $row;
			}
			
			@mysql_free_result($res);
			
			
			
			// 1. Get all of the webpages 
			$res = SweteDb::q("select webpage_id from job_inputs_webpages where job_id='".addslashes($this->_rec->val('job_id'))."'");
			$wpids = array();
			while ($row = mysql_fetch_row($res) ){
				$wpids[] = $row[0];
			}
			
			$site = $this->getSite();
			$proxyWriter = $site->getProxyWriter();
			
			$jobWordCount = 0;
			
			@mysql_free_result($res);
			foreach ($wpids as $webpageId){
				$webpage = SweteWebpage::loadById($webpageId, $this->_rec->val('source_language'));
				if ( !$webpage ){
					throw new Exception("Could not find webpage with id $webpageId");
				}
				$webpage->setSite($site);
				
				// Use a page sucker to suck all of the resources used by this webpage.
				$pageSucker = new SweteJobPageSucker($this);
				
				$pageContent = $webpage->getRecord()->val('webpage_content');
				$pageUrl = $site->getSiteUrl().$webpage->getRecord()->val('webpage_url');
				$pageContent = $pageSucker->processHtml($pageContent, $pageUrl);
				
				$translatable = new Dataface_Record('job_translatable', array());
				$translatable->setValues(array(
					'job_id' => $this->_rec->val('job_id'),
					'content_type' => $webpage->getRecord()->val('last_checked_content_type'),
					'full_contents' => $pageContent->save(),
					'webpage_id' => $webpageId,
					'source_url' => $pageUrl
				));
				
				
				//strings from static sites
				$strings = array();
				
				$res = SweteDb::q("select `string` from job_inputs_webpages_strings where job_id='".addslashes($this->_rec->val('job_id'))."' and webpage_id='".addslashes($webpageId)."'");
				
				while ( $row = mysql_fetch_row($res) ){
				
					$strings[] = $row[0];
				}
				@mysql_free_result($res);
				
				
				// Lets see if there are any other strings that were added individually to this page.
				if ( isset($missedWebpageIds[$webpageId]) ){
					foreach ($missedWebpageIds[$webpageId] as $row){
						$strings[] = $row['string'];
						
					}
					unset($missedWebpageIds[$webpageId]);
				}
				
				// We need to collapse duplicate strings
				$uniqueStringIndex = array();
				$uniqueStrings = array();
				foreach ( $strings as $k=>$str){
					$nstr = TMTools::normalize($str);
					$estr = TMTools::encode($nstr, $temp);
					if ( !isset($uniqueStringIndex[$estr]) ){
						$uniqueStrings[] = $str;
						$uniqueStringIndex[$estr] = 1;
					}
				}
				$strings = $uniqueStrings;
				
				$translatable->setValue('translatable_contents', '<div>'.implode('</div><div>', $strings).'</div>');
				
				//set the word count
				$pageWordCount = self::getPageWordCount($strings);
				$jobWordCount += $pageWordCount;
				$translatable->setValue('word_count', $pageWordCount);
				
				// Now we need to get the previous translations
				$tmid = $webpage->getTranslationMemoryId(true);
				$tm = XFTranslationMemory::loadTranslationMemoryById($tmid);
				if ( !$tm ){
					throw new Exception("Could not find translation memory with id $tmid");
				}
				$dict = $this->extractDictionaryFromHtml($tm, $webpage->getRecord()->val('webpage_content'));
				$translatable->setValue('previous_translations', serialize($dict));
				
				$res = $translatable->save();
				if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());
	
				
				
			}
			
			
			// Add the remainder of the missed webpages.
			foreach ($missedWebpageIds as $webpageId=>$strings){
			
				$webpage = SweteWebpage::loadById($webpageId, $this->_rec->val('source_language'));
				
				if ( !$webpage ){
					throw new Exception("Could not find webpage with id $webpageId");
				}
				$webpage->setSite($site);
				
				// Use a page sucker to suck all of the resources used by this webpage.
				$pageSucker = new SweteJobPageSucker($this);
				
				$pageContent = $webpage->getRecord()->val('webpage_content');
				$pageUrl = $site->getSiteUrl().$webpage->getRecord()->val('webpage_url');
				$pageContent = $pageSucker->processHtml($pageContent, $pageUrl);
				
				$translatable = new Dataface_Record('job_translatable', array());
				$translatable->setValues(array(
					'job_id' => $this->_rec->val('job_id'),
					'content_type' => $webpage->getRecord()->val('last_checked_content_type'),
					'full_contents' => $pageContent->save(),
					'webpage_id' => $webpageId,
					'source_url' => $pageUrl
				));
				
				
				
				// We need to collapse duplicate strings
				$uniqueStringIndex = array();
				$uniqueStrings = array();
				foreach ( $strings as $k=>$missedstr){
					$str = $missedstr['string'];
					$nstr = TMTools::normalize($str);
					$estr = TMTools::normalize(TMTools::encode($nstr, $temp));
					if ( !isset($uniqueStringIndex[$estr]) ){
						$uniqueStrings[] = $str;
						$uniqueStringIndex[$estr] = 1;
					}
				}
				$strings = $uniqueStrings;
				
				$translatable->setValue('translatable_contents', '<div>'.implode('</div><div>', $strings).'</div>');
				
				//set the word count
				$pageWordCount = self::getPageWordCount($strings);//strings
				$jobWordCount += $pageWordCount;
				$translatable->setValue('word_count', $pageWordCount);
				
				// Now we need to get the previous translations
				$tmid = $webpage->getTranslationMemoryId(true);
				$tm = XFTranslationMemory::loadTranslationMemoryById($tmid);
				if ( !$tm ){
					throw new Exception("Could not find translation memory with id $tmid");
				}
				$dict = $this->extractDictionaryFromHtml($tm, $webpage->getRecord()->val('webpage_content'));
				$translatable->setValue('previous_translations', serialize($dict));
				
				$res = $translatable->save();
				if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());
	
				
			}
			
			
			// 2. Get all of the http requests without associated webpages.
			$res = SweteDb::q("select htl.http_request_log_id, tml.translation_miss_log_id, tml.string, htl.translation_memory_id
				from 
					translation_miss_log tml 
					inner join http_request_log htl on tml.http_request_log_id=htl.http_request_log_id 
					inner join job_inputs_translation_misses jitm on jitm.translation_miss_log_id=tml.translation_miss_log_id
					where jitm.job_id='".addslashes($this->_rec->val('job_id'))."'");
			$hrids = array();
			while ($row = mysql_fetch_assoc($res) ){
				$hrids[$row['http_request_log_id']][] = $row;
			}
			
			//$site = $this->getSite();
			//$proxyWriter = $site->getProxyWriter();
			
			@mysql_free_result($res);
			foreach ($hrids as $hrid=>$tmlids){
				$hrRecord = df_get_record('http_request_log', array('http_request_log_id'=>'='.$hrid));
				if ( !$hrRecord ){
					$ex = new Exception("Cannot add HTTP request to job because it could not be found");
					$ex->http_request_log_id = $hrid;
					throw $ex;
				}
				
				// Use a page sucker to suck all of the resources used by this webpage.
				$pageSucker = new SweteJobPageSucker($this);
				
				$pageContent = $hrRecord->val('response_body');
				
				$pageUrl = $hrRecord->val('proxy_request_url');
				if ( !$pageUrl ){
					$ex = new Exception("Failed to add HTTP request to job because it did not have an associated proxy_request_url.");
					$ex->http_request_log = $hrid;
					throw $ex;
				}
				$pageUrl = $proxyWriter->unproxifyUrl($pageUrl);
				$pageContent = $pageSucker->processHtml($pageContent, $pageUrl)->save();
				
				
				$translatable = new Dataface_Record('job_translatable', array());
				$translatable->setValues(array(
					'job_id' => $this->_rec->val('job_id'),
					'content_type' => 'text/html',
					'full_contents' => $pageContent,
					'webpage_id' => null,
					'source_url' => $hrRecord->val('request_url')
				));
				
				$tmid = null;
				$strings = array();
				foreach ($tmlids as $tmlid){
					$strings[] = $tmlid['string'];
					$tmid = $tmlid['translation_memory_id'];
				}
				
				
				$translatable->setValue('translatable_contents', '<div>'.implode('</div><div>', $strings).'</div>');
				
				//set the word count
				$pageWordCount = self::getPageWordCount($strings);
				$jobWordCount += $pageWordCount;
				$translatable->setValue('word_count', $pageWordCount);
				
				// Now we need to get the previous translations
				//$tmid = $webpage->getTranslationMemoryId(true);
				$tm = XFTranslationMemory::loadTranslationMemoryById($tmid);
				if ( !$tm ){
					throw new Exception("Could not find translation memory with id $tmid");
				}
				$dict = $this->extractDictionaryFromHtml($tm, $pageContent);
				$translatable->setValue('previous_translations', serialize($dict));
				
				$res = $translatable->save();
				if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());


				
				
			}
			
			if ($jobWordCount ==0) throw new Exception("The job has no translatable content.");
			
			$this->getRecord()->setValue('word_count', $jobWordCount);
			$this->getRecord()->setValue('compiled', 1);
			$res = $this->getRecord()->save();
			if ( PEAR::isError($res) ) throw new Exception($res->getMessage());
			SweteDb::q("commit");
		}
		
		catch (Exception $ex){
		
			SweteDb::q("rollback");
			throw $ex;
		}
			
		
		
	}

	
	
	
	/**
	 * Approves the job, accepting all translations that have been added.
	 */
	public function approve($username){
		require_once 'modules/tm/lib/XFTranslationMemory.php';
		
		//use import to copy translation memory from the job to the webpages
		foreach($this->getWebpageRecords() as $webpage){
			
			$tm = XFTranslationMemory::loadTranslationMemoryFor($webpage, 
				$this->_rec->val('source_language'),
				$this->_rec->val('destination_language'));
			
			$tm->import($this->getTranslationMemory());
		}
		
		//close the job
		$this->setStatus( self::JOB_STATUS_CLOSED, 
			$username);
	
	}
	
	
	
	public function proxifyHtml($html, $baseUrl = null){}
	public function proxifyCss($css, $baseUrl = null){}
	
	
	/**
	 * @brief Translates a string of HTML using the @p $existingStrings dictionary
	 * if it contains a match. Does not use the job's translation memory.
	 *
	 * @param string $html The HTML string to be translated.
	 * @param array $existingStrings Dictionary of existing strings.  This is
	 * meant to be the "previous_translations" content from the job_translatable
	 * table which is generated when the job is compiled based on the contents
	 * of the translation memory at the time.  These strings should be in a form
	 * that can be matched without normalization or encoding.
	 * @param int $minStatus The minimum approval status to accept for a translation.
	 * @param int $maxStatus The maximum approval status to accept for a translation.
	 *
	 * @returns string The translated HTML
	 */
	public function translatePreviousHtml($html, $existingStrings = array(), $minStatus=3, $maxStatus=5){
		$mem = $this->getTranslationMemory();
		require_once 'inc/WebLite_Translate.class.php';
		$translator = new Weblite_HTML_Translator();
		$html2 = $translator->extractStrings($html);
		$strings = $translator->strings;
		$paramsArr = array();
		$misses = $matches = 0;
		
		foreach ($strings as $k=>$v){
			unset($params);
			$strings[$k] = TMTools::encode($v, $params);
			$paramsArr[$k] = $params;
			
			//overwrite with existing string, if its set
			if ( isset($existingStrings[$strings[$k]]) ){
				$strings[$k] = $existingStrings[$strings[$k]];
				$matches++;
			} else {
				$misses++;
			}
		}
		
			
		if ( $matches == 0 ){
			
			return $html;
			
		} else {
		
			foreach ($strings as $k=>$v){
				$translator->strings[$k] = TMTools::decode($v, $paramsArr[$k]);
				
			}


			$html = $translator->replaceStrings($html2);
			return $html;
		}
	}
	
	
	/**
	 * @brief Translates a string of HTML using the job's translation memory
	 * if it contains a match - or with the @p $existingStrings dictionary
	 * if it contains a match.
	 *
	 * @param string $html The HTML string to be translated.
	 * @param array $existingStrings Dictionary of existing strings.  This is
	 * meant to be the "previous_translations" content from the job_translatable
	 * table which is generated when the job is compiled based on the contents
	 * of the translation memory at the time.  These strings should be in a form
	 * that can be matched without normalization or encoding.
	 * @param int $minStatus The minimum approval status to accept for a translation.
	 * @param int $maxStatus The maximum approval status to accept for a translation.
	 *
	 * @returns string The translated HTML
	 */
	public function translateHtml($html, $existingStrings = array(), $minStatus=3, $maxStatus=5){
		
		$mem = $this->getTranslationMemory();
		require_once 'inc/WebLite_Translate.class.php';
		$translator = new Weblite_HTML_Translator();
		$html2 = $translator->extractStrings($html);
		$strings = $translator->strings;
		$paramsArr = array();
		foreach ($strings as $k=>$v){
			unset($params);
			$strings[$k] = TMTools::encode($v, $params);
			$paramsArr[$k] = $params;
			
		}
	
		$translations = $mem->getTranslations($strings, $minStatus, $maxStatus);
		$misses = $matches = 0;
		
		
		foreach ($translations as $k=>$v){
			if ( isset($v) ){
				$strings[$k] = $v;
				$matches++;
			} else if ( isset($existingStrings[$strings[$k]]) ){
				$strings[$k] = $existingStrings[$strings[$k]];
				$matches++;
			} else {
				$misses++;
			}
		}
		
			
		if ( $matches == 0 ){
			
			return $html;
			
		} else {
		
			foreach ($strings as $k=>$v){
				$translator->strings[$k] = TMTools::decode($v, $paramsArr[$k]);
				
			}


			$html = $translator->replaceStrings($html2);
			return $html;
		}
	}
	
	
	
	
	
	public function getInbox($username){
		require_once 'inc/SweteJobInbox.class.php';
		return new SweteJobInbox($this, $username);
	}
	
	public function isJobAssigned($username){
	
		$res = SweteDb::q("select * from jobs where job_id='".addslashes($this->_rec->val('job_id'))."' and assigned_to='".addslashes($username)."'");
		if ( mysql_num_rows($res) > 0 ){
			@mysql_free_result($res);
			return true;
		}
		
		@mysql_free_result($res);
		
	}
	
	public function assignJob($assignTo, $assignedBy){
		try {
			SweteDb::q("start transaction");
			$res = SweteDb::q("insert into job_assignments (job_id, assigned_to, assigned_by, date_assigned) values 
			(
				'".addslashes($this->_rec->val('job_id'))."',
				'".addslashes($assignTo)."',
				'".addslashes($assignedBy)."',
				'".addslashes(date('Y-m-d H:i:s'))."'
			)");
			
			SweteDb::q("update jobs set assigned_to='".addslashes($assignTo)."' where job_id='".addslashes($this->_rec->val('job_id'))."'");
			SweteDb::q("commit");
			
		} catch (Exception $ex){
			SweteDb::q("rollback");
			throw $ex;
		}
		
		//notify the user that the job was assigned to them
		
		$res = SweteDb::q("select * from users where username ='"
							.addslashes($assignTo)."'");
		$userRec = null;
		if ( mysql_num_rows($res) > 0 ){
			$userRec = mysql_fetch_assoc($res);
		}
		@mysql_free_result($res);
		
		if ($userRec==null){
			throw new Exception("Failed to retrieve user info for email notification. Username [$assignTo]");
		}
		
		$jobId = $this->_rec->val('job_id');

		require_once 'inc/MailTools.php';
		MailTools::sendMail($userRec['email'], 'Job '.$jobId.' has been assigned to you.',
		"Attention $assignTo : 
\nTranslation Job $jobId (created by ".$this->_rec->val('posted_by').
" on ".$this->_rec->getValueAsString('date_created').") has been assigned to you by $assignedBy.");
	
	}
	
	public function setStatus($statusId, $postedBy){
		try {
			SweteDb::q("start transaction");
			$res = SweteDb::q("insert into job_status_log (job_id, status_id, posted_by, date_posted)
			values
				(
					'".addslashes($this->_rec->val("job_id"))."',
					'".addslashes($statusId)."',
					'".addslashes($postedBy)."',
					'".addslashes(date('Y-m-d H:i:s'))."'
				)");
			SweteDb::q("update jobs set job_status='".addslashes($statusId)."' where job_id='".addslashes($this->_rec->val('job_id'))."'");
			SweteDb::q('commit');
		} catch (Exception $ex){
			SweteDb::q('rollback');
			throw $ex;
		}
	}
	
	/**
	 * Takes the translations and applies them to the sites pages and translation memories.
	 */
	public function applyToSite( $translationMemoryIds = null ){}
	
	
	
	
	/*
	 * @brief Creates a dictionary from html content
	 * @param $mem xftranslationmemory
	 * @param string $html content of a webpage to be translated
	*/
	public function extractDictionaryFromHtml($mem, $html){
	
		//1. extract all the strings from the html content
		//and normalize them
		require_once 'inc/WebLite_Translate.class.php';
		$translator = new Weblite_HTML_Translator();
		$html2 = $translator->extractStrings($html);
		$strings = $translator->strings;
		$paramsArr = array();
		foreach ($strings as $k=>$v){
			unset($params);
			$strings[$k] = TMTools::encode($v, $params);
			$paramsArr[$k] = $params;
			
		}
	
		//2. get existing translations for the strings
		//from translation memory
		return $mem->getTranslations($strings);
	}
	
	/*
	 * @brief Returns true if the job is closed
	*/
	public function isClosed(){
		return $this->_rec->val('job_status') == self::JOB_STATUS_CLOSED ;
	}

	
	
	
}