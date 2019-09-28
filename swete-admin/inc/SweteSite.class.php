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
require_once 'inc/SweteDb.class.php';
require_once 'inc/SweteTools.php';
require_once 'inc/SweteWebpage.class.php';
/**
 * @brief Encapsulates a single website.  This essentially wraps around a
 * record of the websites table, but it provides some extra functionality.
 */
class SweteSite {

	/**
	 * @type Dataface_Record from the settings_sites table.
	 */
	private $_rec;

	/**
	 * @deprecated  This appears not to be used.  Sites can only have
	 * one target language so even the name of this looks out of date.
	 */
	private $_languages = null;


	/**
	 * @brief Loads a site based on a URL.  The @c $url parameter
	 * would usually be the URL to a specific page or directory within
	 * a site.  This function will check for the first site that
	 * contains this URL based on the path and hostname, and will
	 * return a SweteSite object wrapping the found site.
	 *
	 * @param string $url The URL to a page within the target site.
	 * @returns SweteSite or null if the site cannot be found.
	 */
	public static function loadSiteByUrl($url){

		$parts = parse_url($url);
		$res = SweteDb::q("
			select
			website_id
			from websites
			where
			(
				'".addslashes($parts['path'])."' like concat(`base_path`,'%')
				or
				base_path is null
				or
				base_path = ''
			)
			and
			(
				`host`='".addslashes($parts['host'])."'
				or
				`host` is null
				or
				`host` = ''
			)
			limit 1");
		if ( xf_db_num_rows($res) > 0 ){
			list($id) = xf_db_fetch_row($res);
			@xf_db_free_result($res);
			$rec = df_get_record('websites', array('website_id'=>'='.$id));
			if ( !$rec ){
				throw new Exception("Failed to load record when we already established that it exists.");
			}
		} else {
			return null;
		}


		return new SweteSite($rec);


	}

	/**
	 * @brief Loads a site by the website_id field.
	 * @param int $website_id The website_id of the site to load.
	 * @returns SweteSite or null if the site cannot be found.
	 */
	public static function loadSiteById($website_id){



		$rec = df_get_record('websites', array('website_id'=>'='.$website_id));
		if ( !$rec ) return null;
		return new SweteSite($rec);
	}


	/**
	 * @brief Constructor for a SweteSite object.  It wraps the
	 * record from the websites table.
	 *
	 * @param Dataface_Record $rec A record from the websites table.
	 */
	public function __construct(Dataface_Record $rec){
		$this->_rec = $rec;
	}


	/**
	 * @brief Gets the 2-digit language code of the source language of the
	 * site.
	 * @returns string 2-digit language code.
	 */
	public function getSourceLanguage(){
		return $this->_rec->val('source_language');
	}

	/**
	 * @brief Gets the 2-digit language code of hte destination language
	 * of the site.
	 * @param string 2-digit language code.
	 */
	public function getDestinationLanguage(){
		return $this->_rec->val('target_language');
	}

	/**
	 * @brief Returns an array with one element: the destination language code.
	 *
	 * Originally it was undecided whether a site could have multiple destination languages
	 * this method is left from the original implementation for backward compatibility.
	 * Now it will always just return a single language.
	 * @returns $languages:array($index:int=>$code:string)
	 */
	public function getLanguages(){
		return array($this->getDestinationLanguage());
	}


	/**
	 * @brief Adds a language table for the specific language.  This will
	 * be a copy of the webpages_en table.  This is because each language
	 * needs its own language table to store translations for the webpages.
	 * At install time, none exist.  When a user adds a new language, it
	 * dynamically creates the appropriate language tables.
	 *
	 * Note: It may be necessary to augment this method to create other
	 * language tables (e.g. for jobs).
	 *
	 * @param string $lang The 2-digit language code of the language for
	 * which the language will be created.
	 *
	 * @returns boolean True if the table was added successfully.  False if the
	 *  language table already exists.
	 * @throws Exception If the language code is not a valid language code.
	 *
	 * @see <a href="http://xataface.com/documentation/tutorial/internationalization-with-dataface-0.6/dynamic_translations">Dynamic Translations</a> for information about creating translation tables in Xataface.
	 *
	 */
	public static function addLanguageTable($lang){
		if ( !preg_match('/^[a-z0-9]{2}$/', $lang) ){
			throw new Exception("Language $lang is not a valid language code.");
		}
		$webpages = Dataface_Table::loadTable('webpages');
		$translations = $webpages->getTranslations();
		// If the translation is already there we just return false
		if ( isset($translations[$lang]) ) return false;

		// First we need to create the translation table for webpages if it isn't created already
		$res = SweteDb::q("show create table `webpages_en`");
		list($sql) = xf_db_fetch_row($res);
		@xf_db_free_result($res);
		$sql = str_replace('`webpages_en`', '`webpages_'.$lang, $sql);
		$sql = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $sql);
		$res = SweteDb::q($sql);

		// The translation table was added successfully
		return true;
	}


	/**
	 * @brief Gets the Dataface_Record object that is being wrapped by this object.
	 * The Dataface_Record itself encapsulates a row of the @e websites table.
	 * @returns Dataface_Record The record of the @e websites table that this object
	 * wraps.
	 */
	public function getRecord(){
		return $this->_rec;
	}

	/**
	 * @brief Gets the Proxy URL for this site.  The proxy URL is the base URL
	 * from which a user can request a page in the proxy and expect to receive
	 * the translated version.
	 *
	 * <p>For example, if SWeTE server is installed at http://example.com/swete
	 * and the particular site was set to handle requests with a base URL of
	 * http://example.com/swete/mysite, then the proxy URL would be
	 * http://example.com/swete/mysite
	 *
	 * @returns string The base URL of the proxy.
	 * @see getSiteUrl()
	 */
	public function getProxyUrl(){

		$host = $this->_rec->val('host');
		if ( !$host ) $host = $_SERVER['HTTP_HOST'];
		$protocol = 'http';
		if ( @$_SERVER['HTTPS'] == 'on' ) $protocol .= 's';

		$basepath = $this->_rec->val('base_path');
		if ( !$basepath ) $basepath = '/';
		if ( $basepath{strlen($basepath)-1} != '/' ) $basepath .= '/';

		$port = '';
		if ( ($protocol == 'http' and intval($_SERVER['SERVER_PORT']) != 80) or
				($protocol == 'https' and intval($_SERVER['SERVER_PORT']) != 443 )){

			$port = ':'.$_SERVER['SERVER_PORT'];
		}

		$addr = $protocol.'://'.$host.$port.$basepath;
		return $addr;
	}

	/**
	 * @brief Returns the base URL of the original website.  It is often
	 * helpful to compare the output of this method with the output of the getProxyUrl()
	 * method to aid in translating URLs from the proxy site to the source site and
	 * back again.
	 *
	 * @returns string The Base URL of the source website.
	 * @see getProxyUrl()
	 */
	public function getSiteUrl(){
		$url = $this->_rec->val('website_url');
		if ( !$url ) return null;
		if ( $url{strlen($url)-1} != '/' ) $url .= '/';
		return $url;
	}

	private $_proxyWriter = null;

	/**
	 * @brief Obtains the default @ref ProxyWriter object for this site.  The ProxyWriter
	 * is responsible for actually converting the source HTML that is returned from
	 * the source site into proxified and translated HTML that is output to the user.
	 *
	 * <p>The proxy writer is built and cached on the first request so that
	 * this can be called multiple times to retrieve the same @ref ProxyWriter object.</p>
	 *
	 * @returns ProxyWriter The proxy writer for this class.
	 */
	public function getProxyWriter(){
		if ( !isset($this->_proxyWriter) ){
			import('inc/ProxyWriter.php');
			$proxy = new ProxyWriter;
			if ( $this->_rec->val('translation_parser_version') ){
			    $proxy->translationParserVersion = intval($this->_rec->val('translation_parser_version'));
			}
			$proxy->setProxyUrl($this->getProxyUrl());
			$proxy->setSrcUrl($this->getSiteUrl());
			$res = SweteDb::q("select `name`,`alias` from path_aliases where website_id='".addslashes($this->_rec->val('website_id'))."'");
			while ( $row = xf_db_fetch_assoc($res) ){
				$proxy->addAlias($row['name'], $row['alias']);
			}
			$proxy->setSourceLanguage($this->getSourceLanguage());
			$proxy->setProxyLanguage($this->getDestinationLanguage());
			$proxy->sourceDateLocale = $this->_rec->val('source_date_locale');
			$proxy->targetDateLocale = $this->_rec->val('target_date_locale');
			$proxy->snapshotsPath = SWETE_DATA_ROOT . DIRECTORY_SEPARATOR . 'snapshots'.DIRECTORY_SEPARATOR.$this->getRecord()->val('website_id');
			
			$this->_proxyWriter = $proxy;

		}
		return $this->_proxyWriter;
	}

	/**
	 * @brief Sets the proxy writer for this site.  If this is not set explicitly, then
	 * the proxyWriter will be built automatically for requests to getProxyWriter().
	 *
	 * It is probably best not to use this method unless you really know what you are
	 * doing.
	 */
	public function setProxyWriter(ProxyWriter $w){
		$this->_proxyWriter = $w;
	}



	/**
	 * @brief Loads a webpage based on its path in the source website.
	 * @param string $path The path to the webpage.
	 * @returns SweteWebpage
	 */
	public function loadWebpageBySrcPath($path){
		$path = SweteTools::normalizeUrl($path);
		return SweteWebpage::loadByURL($this->_rec->val('website_id'), $path, $this->getDestinationLanguage());

	}

	/**
	 * @brief Loads a webpage based on its proxified path (i.e. the path in the
	 * proxy site.
	 * @param string $path The path to the webpage.
	 * @returns SweteWebpage
	 */
	public function loadWebpageByProxifiedPath($path){
		$path = $this->getProxyWriter()->unproxifyPath($path);
		return $this->loadWebpageBySrcPath($path);
	}

	/**
	 * @brief Loads a webpage based on its source URL (i.e. the URL to the page within
	 * the source website.
	 * @param string $url The URL to the webpage.
	 * @returns SweteWebpage
	 * @see loadWebpageBySrcPath()
	 * @see loadWebpageByProxifiedUrl()
	 */
	public function loadWebpageBySrcUrl($url){
		$p = $this->getProxyWriter();
		$path = $p->stripBasePath($url, $this->getSrcUrl());
		return $this->loadWebpageBySrcPath($path);

	}

	/**
	 * @brief Loads a webpage by the proxified URL.
	 * @param string $url The URL to the webpage within the proxy site.
	 * @returns SweteWebpage
	 */
	public function loadWebpageByProxifiedUrl($url){
		$p = $this->getProxyWriter();
		$path = $p->stripBasePath($url, $this->getProxyUrl());
		return $this->loadWebpageByProxifiedPath($path);
	}



	/**
	 * @brief Creates a new webpage with the specified proxified URL.
	 *
	 * @param array $vals An associative array of values associated with
	 * the webpage.  This array must include at least the @e webpage_url key.
	 * @param boolean $checkExists A flag to direct the method to check for
	 * existence of the webpage before inserting it.  If the webpage already
	 * exists in the database, then an exception will be thrown (if this flag is set).
	 * @param boolean $secure A flag to indicate whether this should check permissions
	 * before inserting the page.  If this flag is set and the current user
	 * doesn't have permission to add the page, then an exception will be thrown.
	 * @returns SweteWebpage
	 */
	public function newWebpageWithProxifiedUrl($vals, $checkExists=true, $secure=false){

		// First check if the webpage already exists
		if ( !@$vals['webpage_url'] ){
			throw new Exception("No url provided");
		}
		if ( $checkExists ){
			$page = $this->loadWebpageByProxifiedUrl($vals['webpage_url']);
			if ( $page ){
				throw new Exception("Webpage Already Exists");
			}
		}

		$vals['webpage_url'] = $this->getProxyWriter()->stripBasePath($vals['webpage_url'], $this->getProxyUrl());
		return $this->newWebpageWithProxifiedPath($vals, false, $secure);
	}

	/**
	 * @brief Creates a new webpage given its proxified path.
	 *
	 * @see newWebpageWithProxifiedUrl() for detailed on the other arguments.
	 */
	public function newWebpageWithProxifiedPath($vals, $checkExists=true, $secure=false){

		if ( !@$vals['webpage_url'] ) throw new Exception("No path provided");
		if ( $checkExists ){
			$page = $this->loadWebpageByProxifiedPath($vals['webpage_url']);
			if ( $page ){
				throw new Exception("Webpage already exists");
			}
		}

		$vals['webpage_url'] = $this->getProxyWriter()->unproxifyPath($vals['webpage_url']);
		return $this->newWebpageWithSrcPath($vals, false, $secure);
	}

	/**
	 * @brief Creates a new webpage with the given source path.
	 *
	 * @see newWebpageWithProxifiedUrl() for details on other arguments.
	 */
	public function newWebpageWithSrcPath($vals, $checkExists=true, $secure=false){

		if ( !@$vals['webpage_url'] ) throw new Exception("No path provided");
		if ( $checkExists ){
			$page = $this->loadWebpageBySrcPath($vals['webpage_url']);
			if ( $page ){
				throw new Exception("Webpage already exists");
			}
		}

		$vals['webpage_url'] = SweteTools::normalizeUrl($vals['webpage_url']);

		if ( !isset($vals['webpage_content']) ) $vals['webpage_content'] = '';

		$rec = new Dataface_Record('webpages', array());
		$rec->setValues($vals);
		$res = $rec->save($this->getSourceLanguage(), $secure);
		if ( PEAR::isError($res) ){
			throw new Exception($res->getMessage(), $res->getCode());
		}
		$page = $this->loadWebpageBySrcPath($vals['webpage_url']);
		if ( !$page ) throw new Exception("We have inserted a page, but cannot seem to load it.");

		return $page;


	}

	/**
	 * @brief Creates a new webpage given the source url.
	 *
	 * @see newWebpageWithProxifiedUrl() for details on other arguments.
	 */
	public function newWebpageWithSrcUrl($vals, $checkExists=true, $secure=false){
		if ( !@$vals['webpage_url'] ) throw new Exception("No path provided");
		if ( $checkExists ){
			$page = $this->loadWebpageBySrcUrl($vals['webpage_url']);
			if ( $page ){
				throw new Exception("Webpage already exists");
			}
		}

		$vals['webpage_url'] = $this->getProxyWriter()->stripBasePath($vals['webpage_url'], $this->getSrcUrl());
		return $this->newWebpageWithSrcPath($vals, false, $secure);

	}




	/**
	 * @brief Gets the profile that applies to this webpage.
	 *
	 * @return Dataface_Record Record from the directory_profiles_view view (that is based on
	 *		the directory_profiles table.
	 */
	public function getProfile($path){
		$lang = $this->getDestinationLanguage();
		$parts = explode('/', $path);
		$q = array('');	// Start with empty path because we need to find root profiles if they are available
		$base = array();
		while ( $parts ){
			$p = array_shift($parts);
			$base[] = $p;
			$q[] = implode('/', $base);
		}
		$q = '='.implode('OR =', $q);
		$profile = df_get_record(
			'webpages',
			array(
				'website_id'=>'='.$this->_rec->val('website_id'),
				'webpage_url'=>$q,
				'-sort'=>'webpage_url desc'
			)
		);
		if ( !$profile ){
			return $this->getRecord();
		}
		$webpage = new SweteWebpage($profile);
		return $webpage->getProperties();



	}

	/**
	**	@brief returns Dataface Records of all uncompiled jobs for this site
	*/
	public function getUncompiledJobs(){
		return df_get_records_array('jobs', array('website_id'=>$this->_rec->val('website_id')));
	}

	/**
	 * @brief Calculates the effective property for a tree.  Since
	 * webpages in the database are meant to be hierarchical (i.e. properties
	 * are propagated down to children unless overridden), we need a way
	 * to calculate the effective properties for any page.
	 * @param string $name The property name.  Available properties are defined in the
	 * columns of the webpage_properties table and include:
	 *	- active
	 *	- locked
	 *	- translation_memory_id
	 *	- enable_live_translation
	 *	- auto_approve
	 * @param SweteWebpage $root The root webpage from which to calculate.
	 * @param mixed $parentEffectiveProperty The effective property value of the $root's parent.
	 * @param mixed $inheritVal
	 * @returns void
	 */
	public static function calculateEffectivePropertyToTree($name, SweteWebpage $root, $parentEffectiveProperty, $inheritVal = -1 ){
		$active = $root->getRecord()->val($name);
		if ( $active <= $inheritVal  ){
			$active = $parentEffectiveProperty;
			$root->getProperties()->setValue('effective_'.$name, $active);
			$res = $root->getProperties()->save();
			if ( PEAR::isError($res) ) throw new Exception($res->getMessage(), $res->getCode());
		} else if ( $active != $root->getProperties()->val('effective_'.$name) ){
			$root->getProperties()->setValue('effective_'.$name, $active);
			$root->getProperties()->save();
		}


		$active = $root->getInheritableProperty($name, true, $inheritVal);
		$skip = 0;
		while ($children = df_get_records_array(
			'webpages',
			array(
				'parent_id'=>'='.$root->getRecord()->val('webpage_id'),
				'-skip'=>$skip,
				'-limit'=>30
			))){
			$skip += 30;
			foreach ($children as $child){
				$c = new SweteWebpage($child);

				self::calculateEffectivePropertyToTree($name, $c, $active, $inheritVal);
			}

		}

	}

	/**
	 * @brief Calculates the "active" property for all webpages in the subtree rooted at
	 * @c $root
	 * @param SweteWebpage $root The root of the subtree on which to operate.
	 * @param int $parentEffectiveActive The effective "active" property of the parent page.
	 * @returns void
	 */
	public static function calculateEffectiveActiveToTree(SweteWebpage $root, $parentEffectiveActive){

		self::calculateEffectivePropertyToTree('active', $root, $parentEffectiveActive, -1);


	}

	/**
	 * @brief Calculates the "locked" property for all webpages in the subtree rooted at
	 * @c $root
	 * @param SweteWebpage $root The root of the subtree on which to operate.
	 * @param int $parentEffectiveLocked The effective "locked" property of the parent page.
	 * @returns void
	 */
	public static function calculateEffectiveLockedToTree(SweteWebpage $root, $parentEffectiveLocked){

		self::calculateEffectivePropertyToTree('locked', $root, $parentEffectiveLocked, -1);


	}

	/**
	 * @brief Calculates the "translation_memory_id" property for all webpages in the subtree rooted at
	 * @c $root
	 * @param SweteWebpage $root The root of the subtree on which to operate.
	 * @param int $parentEffectiveTranslationMemoryId The effective "translation_memory_id" property of the parent page.
	 * @returns void
	 */
	public static function calculateEffectiveTranslationMemoryIdToTree(SweteWebpage $root, $parentEffectiveTranslationMemoryId){
		self::calculateEffectivePropertyToTree('translation_memory_id', $root, $parentEffectiveTranslationMemoryId, 0);

	}

	/**
	 * @brief Currently not used
	 */
	public static function handleGet($url, $getParams){}

	/**
	 * @brief Currently not used
	 */
	public static function handlePost($url, $postParams){}



}
