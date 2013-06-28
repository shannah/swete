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
require_once 'inc/BackgroundProcess.php';
class BackgroundProcess_UpdateChangedPages extends BackgroundProcess {
	
	public $changedTranslationMemories = array();
	private $_tms = array();
	private $_sites = array();
	public function run(){
		
		require_once 'inc/SweteSite.class.php';
		require_once 'inc/SweteWebpage.class.php';
		require_once 'inc/PageProcessor.php';
		require_once 'modules/tm/lib/XFTranslationMemory.php';
				
		$wpids = array();

		$numPages = 0;
		foreach ($this->changedTranslationMemories as $tmid=>$strids){
			if ( !$strids ) $strids = array(0);
			$strids = implode(',',$strids);
			$sql = "select distinct wp.webpage_id, s.source_language
				from 
					webpage_properties wp 
					inner join webpage_strings ws on wp.webpage_id=ws.webpage_id
					inner join webpages w on wp.webpage_id=w.webpage_id
					inner join websites s on w.website_id=s.website_id
				where
					wp.effective_translation_memory_id='".addslashes($tmid)."' and
					ws.string_id in (".$strids.")";		
			$res = df_q($sql);
			$numPages += mysql_num_rows($res);
			$wplangs = array();
			while ( $row = mysql_fetch_row($res) ){
				list($webpageId, $sourceLanguage) = $row;
				$wpids[] = $webpageId;
				$wplangs[] = $sourceLanguage;
			}
			@mysql_free_result($res);
		}
		
		df_q("update background_processes set 
				status_message='".addslashes('Updating webpages with new translations')."',
				status_current_position=0,
				status_total='".addslashes($numPages)."'
				where process_id='".addslashes($this->getProcessId())."'");
		$count = 1;
		foreach ($wpids as $k=>$webpageId ){
		
			$pageWrapper = SweteWebpage::loadById($webpageId, $wplangs[$k]);
			$pageWrapper->setSite(
				$this->getSite(
					$pageWrapper->getRecord()->val('website_id')
				)
			);
			
			$tmid = $pageWrapper->getTranslationMemoryId(true);
			if ( $tmid ){
			
				$tm = $this->getTranslationMemory($tmid);
				if ( $tm ){
					
					$processor = new PageProcessor;
					$processor->site = $pageWrapper->getSite();
					$processor->translationMemory = $tm;
					$processor->page = $pageWrapper;
					$processor->translateMinStatus = 3;
					$processor->translateMaxStatus = 5;
					$processor->logTranslationMisses = true;
					$processor->savePage = true;
					$processor->saveTranslationLogRecord = true;
					$processor->process();
					
				}
			}
			df_q("update background_processes set 
				status_current_position='".addslashes($count)."'
				where process_id='".addslashes($this->getProcessId())."'");
			$count++;

		}
		
		df_q("update background_processes set 
				status_message='".addslashes('New translations successfully applied to '.$numPages.' pages.')."',
				status_current_position=0,
				status_total='".addslashes($numPages)."'
				where process_id='".addslashes($this->getProcessId())."'");
	
	}
	
	function getTranslationMemory($tmid){
		if ( !isset($this->_tms[$tmid]) ){
			$this->_tms[$tmid] = XFTranslationMemory::loadTranslationMemoryById($tmid);
		}
		return $this->_tms[$tmid];
	}
	
	function getSite($siteId){
		if ( !isset($this->_sites[$siteId]) ){
			$this->_sites[$siteId] = SweteSite::loadSiteById($siteId);
		}
		return $this->_sites[$siteId];
	}
	
}