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
class PageProcessor {


	/**
	 * @type SweteWebpage
	 *
	 * @brief The webpage that is to be processed.
	 */
	public $page = null;
	
	/**
	 * @type SweteWebsite
	 *
	 * @brief The website in which the webpage resides.
	 */
	public $site = null;
	
	/**
	 * @type int
	 * @brief The minimum translation status to accept.
	 */
	public $translateMinStatus = 3;
	
	/**
	 * @type int
	 *
	 * @brief The max translation status to accept.
	 *
	 */
	public $translateMaxStatus = 5;
	
	/**
	 * @type boolean
	 *
	 * @brief An "in" parameter.  Specifies whether translation misses should be logged.
	 */
	public $logTranslationMisses = false;
	
	public $webpageRefreshLogId = null;
	
	
	public $savePage = true;
	public $saveTranslationLogRecord = true;
	
	public $translationMemory = null;
	
	/// OUT PARAMETERS
	
	/**
	 * @type array
	 * @brief An "out" parameter that is set after processing is complete.  It is an associative
	 * array containing the translation stats from the translation step (e.g. misses, matches, etc..).
	 * @see ProxyWriter::translateHtml() for details.
	 */
	public $translationStats = null;
	
	/**
	 * @type Dataface_Record
	 * @brief An "out" parameter that is set after processing is complete.  If $logTranslationMisses
	 * is set to true, then a log entry will be placed in the translation_miss_log table.  The 
	 * record that was added will be saved to this parameter.
	 */
	public $translationMissLogRecord = null;
	
	/**
	 * @type SweteWebpage
	 * @brief An "out" parameter that is set after processing is complete.  It is a record containing
	 * the destination language version of the webpage.
	 */
	public $translatedPage = null;
	
	public function process(){
		$this->translationStats = null;
		$this->translationMissLogRecord = null;
		$this->translatedPage = null;
		
		$proxyWriter = $this->site->getProxyWriter();
		$pageWrapper = $this->page;
		
		$page = $pageWrapper->getRecord();
		$tmid = null;
		if ( !isset($this->translationMemory) ){
			$tmid = $pageWrapper->getTranslationMemoryId(true);
		} else {
			$tmid = $this->translationMemory->getRecord()->val('translation_memory_id');
		}
		$translatedContent = null;
		$untranslatedContent = $page->val('webpage_content');
		if ( !trim($untranslatedContent) ){
			// There is nothing to process on this page.
			return;
		}
		if ( $tmid or $this->translationMemory){
			if ( $this->translationMemory ){
				$tm = $this->translationMemory;
			} else {
				$tm = $this->getTranslationMemory($tmid);
			}
			
			if ( $tm ){
				if ( $tm->getSourceLanguage() != $pageWrapper->getLanguage() ){
					throw new Exception("Translation memory language does not match the record language.  Translation memory source language is ".$tm->getSourceLanguage()." but the page language is ".$pageWrapper->getLanguage().'.');
				}
				$proxyWriter->setTranslationMemory($tm);
				$proxyWriter->setMinTranslationStatus($this->translateMinStatus);
				$translatedContent = $proxyWriter->translateHtml($untranslatedContent, $translationStats, $this->logTranslationMisses);
				$this->translationStats = $translationStats;
				$page->setValues(array(
					'last_translation_memory_applied' => date('Y-m-d H:i:s'),
					'last_translation_memory_misses' => $translationStats['misses'],
					'last_translation_memory_hits' => $translationStats['matches']
				
				));
				
				// Let's record the strings in this page.
				$res = df_q("delete from webpage_strings where webpage_id='".addslashes($page->val('webpage_id'))."'");
				
				if ( $proxyWriter->lastStrings ){
					//print_r($proxyWriter->lastStrings);exit;
					$sqlpre = "insert into webpage_strings (webpage_id,string_id) values ";
					$sql = array();
					$wpid = $page->val('webpage_id');
					foreach ($proxyWriter->lastStrings as $str){
						if ( !trim($str) ) continue;
						if ( preg_match('/^[^\w]+$/', trim($str) ) ){
							// This is to skip any strings that contain only 
							// non-word characters(e.g. numbers)
							continue;
						}
					
						$encStr = TMTools::encode($str, $params);
						
						$strRec = XFTranslationMemory::addString($encStr, $tm->getSourceLanguage());
						$sql[] = '('.$wpid.','.$strRec->val('string_id').')';
					}
					$sql = $sqlpre.implode(',', $sql);
					df_q($sql);
				}
				
				
				$translatedPage = SweteWebpage::loadById($page->val('webpage_id'), $this->site->getDestinationLanguage());
				$translatedPage->getRecord()->setValue('webpage_content', $translatedContent);
				$res = $translatedPage->getRecord()->save();
				if ( PEAR::isError($res) ) throw new Exception(mysql_error(df_db()));
				
				
				
				$lastApproved = $translatedPage->getLastVersionWithStatus(SweteWebpage::STATUS_APPROVED);
				
				if ( $lastApproved and  $lastApproved->val('webpage_content') == $translatedContent ){
					$page->setValue('webpage_status', SweteWebpage::STATUS_APPROVED);
				} else {
					if ( $translationStats['matches'] > 0 and $translationStats['misses'] == 0 ){
						// We have perfect matches in what we are supposed to be translating
						// We are either approving this page or we are marking it pending approval
						if ( $translatedPage->getAutoApprove(true) ){
							$page->setValue('webpage_status', SweteWebpage::STATUS_APPROVED);
							$lastApproved = $translatedPage->setStatus(SweteWebpage::STATUS_APPROVED);
						} else {
							$page->setValue('webpage_status', SweteWebpage::STATUS_PENDING_APPROVAL);
							
						}
					
					} else if ( $translationStats['misses'] > 0 ){
						$page->setValue('webpage_status', SweteWebpage::STATUS_CHANGED);
					} else {
						$page->setValue('webpage_status', null);
					}
				}
				
				if ( $this->logTranslationMisses and @$translationStats['log']){
					//print_r($translationStats);exit;
					foreach ($translationStats['log'] as $str){
						$tlogEntry = new Dataface_Record('translation_miss_log', array());
						
						$nstr = TMTools::normalize($str);
						$estr = TMTools::encode($str, $junk);
						$hstr = md5($estr);
						$strRec = XFTranslationMemory::findString($estr, $this->site->getSourceLanguage());
						if ( !$strRec ) $strRec = XFTranslationMemory::addString($estr, $this->site->getSourceLanguage());
						
						$tlogEntry->setValues(array(
							//'webpage_refresh_log_id' => $logEntry->val('refresh_log_id'),
							'string' => $str,
							'normalized_string' => $nstr,
							'encoded_string' => $estr,
							'string_hash' => $hstr,
							'date_inserted'=> date('Y-m-d H:i:s'),
							'webpage_id'=>$page->val('webpage_id'),
							'website_id'=>$page->val('website_id'),
							'source_language' => $this->site->getSourceLanguage(),
							'destination_language' => $this->site->getDestinationLanguage(),
							'translation_memory_id' => $tmid,
							'string_id' => $strRec->val("string_id")
							
						
						));
						
						if ( isset($this->webpageRefreshLogId) ){
							$tlogEntry->setValue('webpage_refresh_log_id', $this->webpageRefreshLogId);
						}
						
						if ( $this->saveTranslationLogRecord ){
							$res = $tlogEntry->save();
							
							if ( PEAR::isError($res) ){
								
								//throw new Exception($res->getMessage());
								// This will throw an error if there is a duplicate... we don't care... we're not interested in duplicates
							}
						}
						$this->translationMissLogRecord = $tlogEntry;
					
					}
				}
				
				if ( $this->savePage ){
					$res = $page->save();
					if ( PEAR::isError($res) ) throw new Exception($res->getMessage());
				}
				
				
				
				
			}
			
		}
		
		
	
	}
	
}