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
require_once 'inc/SweteTools.php';
require_once 'inc/SiteCrawler.php';
require_once 'modules/tm/lib/XFTranslationMemory.php';

/**
 * @brief A class that imports webpages into the database.  This will perform
 * translations using appropriate translation memories as the pages are imported.
 *
 *
 * @see SiteCrawler
 * @see actions_swete_import_webpages
 */
class PageImporter {
	
	/**
	 * @type SweteSite
	 */
	public $site;
	
	/**
	 * @type String
	 * @brief This doesn't appear to be used right now.
	 */
	public $url;
	
	
	/**
	 * @type string
	 * @brief The URL of the page to import (as a starting point at least).
	 */
	public $startingPoint;
	
	/**
	 * @type boolean
	 */
	public $translate = false;
	
	/**
	 * @type int
	 * @brief For page translations, the minimum status that should be accepted
	 * for translated strings.  Default value is XFTranslationMemory::TRANSLATION_APPROVED
	 *
	 * Possible values are:
	 * 
	 * - XFTranslationMemory::TRANSLATION_SUBMITTED
	 * - XFTranslationMemory::TRANSLATION_REJECTED
	 * - XFTranslationMemory::TRANSLATION_APPROVED
	 */
	public $translateMinStatus = 3;
	public $translateMaxStatus = 5;
	
	/**
	 * @type boolean
	 * @brief A flag indicating whether translation misses should be logged.  I.e.
	 * if no translation can be found for a string, should we log this in the translation
	 * miss log.
	 *
	 * @see ProxyWriter::translateHtml() for the actual translation process.
	 */
	public $logTranslationMisses = false;
	
	/**
	 * @type String
	 * @brief The username of the user who is currently logged in and is performing
	 * the import.
	 *
	 */
	public $username = '';
	
	
	/**
	 * @type int
	 * @brief The depth that should be crawled from the starting point.
	 *
	 */
	public $depth;
	
	/**
	 * @brief Flag to indicate whether content of pages should be loaded as well.
	 * @type boolean
	 */
	public $loadContent = false;
	
	/**
	 * @type boolean
	 * @brief If a webpage is locked, it shouldn't be overwritten.  This flag directs
	 * the importer to ignore this restriction and load the content of the page 
	 * anyways.
	 */
	public $overrideLocks = false;
	
	
	
	/**
	 * @type array
	 * @brief An array of the pages that are added as a result of this import step.
	 * This array should be of the form:
	 * @code
	 * array( $index:int => $page:Dataface_Record(tablename='webpages'))
	 * @endcode
	 *
	 */
	public $pagesAdded = array();
	
	/**
	 * @type array
	 * @brief an array of the pages that were updated as a result of this import
	 
	 * operation.
	 * This array should be of the form:
	 * @code
	 * array( $index:int => $page:Dataface_Record(tablename='webpages'))
	 * @endcode
	 */
	public $pagesUpdated = array();
	
	
	/**
	 * @type array
	 * @brief Cache of translation memories that are loaded for this operation.
	 *
	 */
	public $translationMemories = array();
	
	
	public function __construct(){
		if ( class_exists('Dataface_AuthenticationTool') ){
			$this->username = Dataface_AuthenticationTool::getInstance()->getLoggedInUserName();
		}
	}
	
	
	/**
	 * @brief Performs the import.  The resulting page is available
	 * as the page property.
	 * @throws Exception if the page already exists, or the HTTP response code 
	 * is not in the list of acceptable HTTP codes.
	 *
	 */
	public function doImport(){
		$this->pagesAdded = array();
		$this->pagesUpdated = array();
		// First let's find out if this page already exists
		
		$crawler = new SiteCrawler;
		$crawler->site = $this->site;
		$crawler->loadContent = $this->loadContent;
		$crawler->startingPoint = $this->startingPoint;
		if ( !$crawler->startingPoint ) $crawler->startingPoint = $this->site->getSiteUrl();
		
		$crawler->depth = 3;
		if ( isset($this->depth) ) $crawler->depth = $this->depth;
		
		$crawler->crawl();
		
		
		$this->importNode($crawler->root);
		
	}
	
	/**
	 * @brief Imports a node and all of its children into the database.
	 *
	 * @param stdClass $node The root node to import.
	 * @param Dataface_Record $parentPage A record of the @e webpages table
	 * that represents the parent page of the current page.
	 *
	 * @see SiteCrawler for more information about nodes and the properties
	 * they can have.
	 */
	public function importNode(stdClass $node, $parentPage = null){
		$page = null;
		if ( isset($node->path) and isset($node->httpStatus) ){
			$page = df_get_record(
				'webpages', 
				array(
					'webpage_url'=>'='.$node->path,
					'website_id'=> '='.$this->site->getRecord()->val('website_id')
				)
			);
			
			if ( !$page ){
				
				$page = new Dataface_Record('webpages', array());
				$page->setValues(array(
					'website_id'=>$this->site->getRecord()->val('website_id'),
					'webpage_url'=>$node->path,
					'active'=>-1,
					'posted_by'=>$this->username,
					'parent_id'=>$parentPage?$parentPage->val('webpage_id'):null,
					'is_loaded'=>0
					
				));
				$res = $page->save();
				if ( PEAR::isError($res) ) throw new Exception($res->getMessage());
				$this->pagesAdded[] = $page;
			} else {
				$this->pagesUpdated[] = $page;
			}
			
			$page->setValues(array(
				'last_checked'=>date('Y-m-d H:i:s'),
				'last_checked_response_code'=>$node->httpStatus,
				'last_checked_content_type'=>$node->contentType,
				'last_checked_by'=>$this->username
			));
			
			$updateRefreshLog = false;
			$translationStats = array();
			//if ( $node->content and $this->loadContent and (!$page->val('locked') or $this->overrideLocks) ){
			if ( @$node->content and @$this->loadContent ){
				if ( $page->val('locked') and !$this->overrideLocks ){
					error_log("Skipping refresh of ".@$node->path." because the page is locked.");
				} else {
					$page->setValues(array(
						'last_refresh' => date('Y-m-d H:i:s'),
						'last_refresh_response_code'=>$node->httpStatus,
						'is_loaded'=>1,
						'webpage_content'=>$node->content
					));
					
					
					
					// Now log the check
					$logEntry = new Dataface_Record('webpage_refresh_log', array());
					$logEntry->setValues(array(
						'webpage_id'=>$page->val('webpage_id'),
						'date_checked'=>date('Y-m-d H:i:s'),
						'response_code'=>$node->httpStatus,
						'content_type'=>$node->contentType,
						'content'=>$node->content,
						'checked_by'=> $this->username
					));
					$res = $logEntry->save();
					if ( PEAR::isError($res) ) throw new Exception($res->getMessage());
					
					
					
					
					
					if ( $this->translate ){
						$pageWrapper = new SweteWebpage($page);
						$tmid = $pageWrapper->getTranslationMemoryId(true);
						if ( $tmid ){
						
							$tm = $this->getTranslationMemory($tmid);
							if ( $tm ){
								import('inc/PageProcessor.php');
								$processor = new PageProcessor;
								$processor->webpageRefreshLogId = $logEntry->val('refresh_log_id');
								$processor->site = $this->site;
								$processor->translationMemory = $tm;
								$processor->page = $pageWrapper;
								$processor->translateMinStatus = $this->translateMinStatus;
								$processor->translateMaxStatus = $this->translateMaxStatus;
								$processor->logTranslationMisses = $this->logTranslationMisses;
								$processor->savePage = false;
								$processor->saveTranslationLogRecord = true;
								$processor->process();
							}
						}
					
					}
					
				}
				
			}
			
			
			
			
			
			$res = $page->save();
			
			// Now log the check
			$logEntry = new Dataface_Record('webpage_check_log', array());
			$logEntry->setValues(array(
				'webpage_id'=>$page->val('webpage_id'),
				'date_checked'=>date('Y-m-d H:i:s'),
				'response_code'=>$node->httpStatus,
				'content_type'=>$node->contentType,
				'checked_by'=>$this->username
			));
			$res = $logEntry->save();
			if ( PEAR::isError($res) ) throw new Exception($res->getMessage());
			
		}
		
		if ( isset($node->children) and is_array($node->children) ){
			foreach ($node->children as $child){
				$this->importNode($child, $page);
			}
		}
		
		
		
		
		
		
	}
	
	/**
	 * @brief Gets a translation memory by ID.  It uses a cache so that if the same memory
	 * is loaded multiple times, it will load from the cache and not have to hit the
	 * database subsequent times.
	 * @param int $tmid The translation memory id.
	 * @returns XFTranslationMemory The translation memory.
	 */
	function getTranslationMemory($tmid){
		if ( !isset($this->translationMemories[$tmid]) ){
			$this->translationMemories[$tmid] = XFTranslationMemory::loadTranslationMemoryById($tmid);
			
		}
		return $this->translationMemories[$tmid];
	}

}