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
require_once 'lib/http_build_url.php';
class SweteTools {
	
	const USER=1;
	const TRANSLATOR=2;
	const ADMIN=3;
	
	public static $USE_HTML5_PARSER = false;
	
	/**
	 * @brief Converts a URL to something that is more normalized for storage.
	 *
	 * This will strip off the fragment (i.e. anything following #), and it
	 * will sort the query parameters.  It will not do anything else to 
	 * modify the URL.
	 *
	 * @param string $url The URL to be normalized.
	 */
	public static function normalizeUrl($url){
	
		if ( strpos($url, '#') !== false ){
			list($url) = explode('#', $url);
		}
		
		if ( strpos($url, '?') !== false ){
			list($base, $query) = explode('?', $url);
			$qparts = explode('&', $query);
			asort($qparts);
			$url = $base.'?'.implode('&', $qparts);
		}
		return $url;
	}
	
	/**
	 * @brief Returns an absolute URL given a URL from a webpage
	 * and the baseURL of that webpage.
	 *
	 * @param string $url A URL that may be relative or absolute.
	 * @param mixed $baseURL The Base URL for the webpage.  This may either be
	 * a string URL or an array of parsed parameters returned from the 
	 * <a href="http://php.net/parse_url">parse_url</a> function.
	 * @returns string 
	 */
	public static function absoluteUrl($url, $baseURL){
		
		if ( !is_array($baseURL) ) $baseURL = parse_url($baseURL);
		if ( preg_match('#^//#', $url) ){
			return $baseURL['scheme'].':'.self::removeDotDot($url);
		}
		if ( strpos($url,'javascript:') === 0 ) return $url;
		if ( strpos($url, 'mailto:') === 0 ) return $url;
		if ( preg_match('#^[a-z]{1,5}://#i', $url) ) return $url; 
		if ( preg_match('#^/#', $url) ){
			$baseURL['path'] = self::removeDotDot($url);
			return http_build_url($baseURL); 
		}
		
		$path = '/';
		if (isset($baseURL['path'])){
			$path = $baseURL['path'];
		}
		if ( $path{strlen($path)-1} != '/' ) $path .= '/';
		$path .= $url;
		
		$baseURL['path'] = self::removeDotDot($path);
		return http_build_url($baseURL);
		
	}
	
	/**
	 * @brief Removes a .. from a URL.  E.g. for paths that contain .. like
	 * /path/to/../page.html
	 * This would translate to
	 * /path/page.html
	 * @param string $path The path to convert
	 * @returns string The converted path.
	 */
	public static function removeDotDot($path){
		$parts = explode('/', $path);
		$out = array();
		foreach ($parts as $p){
			if ( $p == '..' ){
				if ( $out ) array_pop($out);
				else throw new Exception("Failed to remove dots to parent directory for path $path");
			} else {
				$out[] = $p;
			}
		}
		return implode('/', $out);
	}
	
	
	/**
	 * @brief Updates the database, adding appropriate translation tables for the 
	 * webpages and translation_miss_log tables based on the languages
	 * of sites in the database.
	 */
	public static function updateDb(){
		require_once 'inc/SweteDb.class.php';
		$res = SweteDb::q('select distinct `target_language` from websites');
		$languages = array();
		while ($row = xf_db_fetch_row($res) ) $languages[] = $row[0];
		@xf_db_free_result($res);
		
		$res = SweteDb::q('select distinct `source_language` from websites');
		while ($row = xf_db_fetch_row($res) ) $languages[] = $row[0];
		@xf_db_free_result($res);
		
		$languages = array_unique($languages);
		
		$tWebpages = Dataface_Table::loadTable('webpages');
		$wLanguages = array_keys($tWebpages->getTranslations());
		
		$missing = array_diff($languages, $wLanguages);
		
		foreach ($missing as $lang){
			if ( !preg_match('/^[a-zA-Z0-9]{2}/', $lang) ) throw new Exception("Invalid language code ".$lang);
			
			$sql = <<<END
CREATE TABLE IF NOT EXISTS `webpages_$lang` (
  `webpage_id` int(11) unsigned not null,
  `webpage_content` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`webpage_id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
END
;			
			//echo $sql;
			SweteDb::q($sql);
			$sql = 'ALTER TABLE  `webpages_'.$lang.'` ENGINE = INNODB';
			SweteDb::q($sql);
			
			
			$sql = 'ALTER TABLE  `webpages_'.$lang.'` ADD FOREIGN KEY (  `webpage_id` ) REFERENCES  `webpages` (
				`webpage_id`
				) ON DELETE CASCADE ;';
				
			SweteDb::q($sql);
			
			

			
		
		}
		
		
		$tMissLog = Dataface_Table::loadTable('translation_miss_log');
		$wLanguages = array_keys($tMissLog->getTranslations());
		
		$missing = array_diff($languages, $wLanguages);
		
		foreach ($missing as $lang){
			if ( !preg_match('/^[a-zA-Z0-9]{2}/', $lang) ) throw new Exception("Invalid language code ".$lang);
			
			
			$sql = <<<END
CREATE TABLE IF NOT EXISTS `translation_miss_log_$lang` (
	`translation_miss_log_id` int(11) unsigned not null,
	`string` text COLLATE utf8_unicode_ci,
	PRIMARY KEY (`translation_miss_log_id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
END
;
			SweteDb::q($sql);
			$sql = 'ALTER TABLE `translation_miss_log_'.$lang.'` ENGINE=INNODB';
			SweteDb::q($sql);
			$sql = 'ALTER TABLE  `translation_miss_log_'.$lang.'` ADD FOREIGN KEY (  `translation_miss_log_id` ) REFERENCES  `translation_miss_log` (
				`translation_miss_log_id`
				) ON DELETE CASCADE ;';
				
			SweteDb::q($sql);

			
		
		}
		
		
                $tStrings = Dataface_Table::loadTable('swete_strings');
		$wLanguages = array_keys($tStrings->getTranslations());
		
		$missing = array_diff($languages, $wLanguages);
		
		foreach ($missing as $lang){
			if ( !preg_match('/^[a-zA-Z0-9]{2}/', $lang) ) throw new Exception("Invalid language code ".$lang);
			
			
			$sql = <<<END
CREATE TABLE IF NOT EXISTS `swete_strings_$lang` (
	`string_id` int(11) unsigned not null,
        `translation_memory_id` int(11) unsigned not null,
	`string` text COLLATE utf8_unicode_ci,
	PRIMARY KEY (`string_id`,`translation_memory_id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
END
;
			SweteDb::q($sql);
			
			

			
		
		}
                
		
		$tJobTranslatable = Dataface_Table::loadTable('job_translatable');
		$wLanguages = array_keys($tJobTranslatable->getTranslations());
		
		$missing = array_diff($languages, $wLanguages);
		
		foreach ($missing as $lang){
			if ( !preg_match('/^[a-zA-Z0-9]{2}/', $lang) ) throw new Exception("Invalid language code ".$lang);
			
			
			$sql = <<<END
CREATE TABLE IF NOT EXISTS `job_translatable_$lang` (
	`job_translatable_id` int(11) unsigned not null,
	`translatable_contents` longtext COLLATE utf8_unicode_ci,
	PRIMARY KEY (`job_translatable_id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
END
;
			SweteDb::q($sql);
			$sql = 'ALTER TABLE `job_translatable_'.$lang.'` ENGINE=INNODB';
			SweteDb::q($sql);
			$sql = 'ALTER TABLE  `job_translatable_'.$lang.'` ADD FOREIGN KEY (  `job_translatable_id` ) REFERENCES  `job_translatable` (
				`job_translatable_id`
				) ON DELETE CASCADE ;';
				
			SweteDb::q($sql);

			
		
		}
		
		
		
		
		
		
	}
	
	
	/**
	 * @brief Stores reference to the current user record.  Lazily loaded.
	 */
	private static $user=null;
	
	/**
	 * @brief Stores reference to the current user's role id.
	 */
	private static $role=null;
	
	
	/*
	 * The Following are constants to store the Role IDs of the various 
	 * roles of the system.
	 */
	 
	
	static $roleNames = null;
	
	
	/**
	 * @brief Gets the currently logged in user record.
	 *
	 * @return Dataface_Record A record from the dtg_users table encapsulating the currently logged in user.
	 */
	static function &getUser(){
		if ( !isset(self::$user) ){
			self::$user = Dataface_AuthenticationTool::getInstance()->getLoggedInUser();
	
		}
		return self::$user;
	}
	
	/**
	 * @brief Gets the role ID of the currently logged in user.
	 * @return int
	 */
	static function getRole(){
		if ( !isset(self::$role) ){
			$user = self::getUser();
			if ( $user ){
				self::$role = $user->val('role_id');
				
			}
		}
		return self::$role;
	}
	
	
	/**
	 * @brief Checks to see if the currently logged in user is a particular role id.
	 *
	 *
	 * @return boolean True if the currently logged in user has the specified role id. False otherwise.
	 */
	static function isRole($role){
		return (intval(self::getRole()) === intval($role));
	}
	
	/**
	 * There is no assignee role
	 *
	 * @deprecated
	 */
	static function isAssignee(){
		return self::isRole('ASSIGNEE');
	}
	
	static function isAdmin(){
		return self::isRole(self::ADMIN);
	}
	
	static function isTranslator(){
		return self::isRole(self::TRANSLATOR);
	}
	
	static function isUser(){
		return self::isRole(self::USER);
	}
	
	/**
	 * @brief Finds any uncompiled jobs for the current user
	 *
	 * @return array of job records
	 */ 
	static function uncompiledJobs(){
		require_once 'inc/SweteDb.class.php';
		if ( !self::getUser() ) return array();
		$res = SweteDb::q("select * from jobs where compiled=false and posted_by ='"
							.addslashes(self::getUser()->val('username'))."'");
		
		$jobs = array();		
		while ($row = xf_db_fetch_assoc($res) ){
			$jobs[] = $row;
		}
		@xf_db_free_result($res);
		return $jobs;
	
	}
	
	static function _encode_scripts($matches){
		if ( !trim($matches[2]) ) return $matches[0];
		return $matches[1].'eval('.
			json_encode(
				preg_replace(
					array('/&/','/</'),
					array('&amp;','&lt;'),
					$matches[2]
				)
			).'.replace(/&lt;/g, \'<\').replace(/&amp;/g, \'&\'));'.$matches[3];
	}
	
	static function loadHtml($html){
		$intro = substr($html,0, 255);
		if ( self::$USE_HTML5_PARSER and stripos($intro, '<!DOCTYPE html>') !== false ){
            // this is html5 so we'll use the html5 
            require_once 'lib/HTML5.php';
            $out =  HTML5::loadHTML($html);
            // noscripts contents are treated like text which causes problems when 
            // filters/replacements are run on them.  Let's just remove them
            $noscripts = $out->getElementsByTagName('noscript');
            foreach ( $noscripts as $noscript ){
                $noscript->parentNode->removeChild($noscript);
            }
            return $out;
        }
        
		$doc = new DOMDocument;
		
		// Remove the doctype tag if it is provided.  We are going to output
		// a new doctype tag.
		if ( stripos($intro, '<!DOCTYPE') !== false ){

			$html = preg_replace('/^[^<]*<\!DOCTYPE[^>]+>/i', '', $html, 1);
		}
		// If we are dealing with XHTML then we need to do some special treatment
		// for scripts so that they don't F** us up with CDATA stuff.
		if ( (defined('SWETE_ENCODE_SCRIPTS') and SWETE_ENCODE_SCRIPTS) or  stripos($intro, 'XHTML') !== false){
			$html = preg_replace_callback('/(<script[^>]*>)([\s\S]*?)(<\/script>)/', array('SweteTools','_encode_scripts'), $html);
		}
		// This was experimental to add a UTF-8 meta tag to fix encoding issues with the DOM 
		// parser. It helped on an HTML5 site, but there were other problems with the site
		// so we added the optional $USE_HTML5_PARSER flag which fixed the issue properly.
		//if ( !preg_match('/<meta [^>]*Content-Type[^>]*UTF-8/i', $html) ){
		//    $html = preg_replace('/(<head[^>]*>)/i', '$1<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>', $html, 1);
		//}
		$res = @$doc->loadHtml('<?xml encoding="UTF-8" ?'.'>'.$html);
		if ( !$res ){
			$outfile = tempnam();
			file_put_contents($outfile, $orig);
			error_log("Failed to parse HTML: ".$outfile);
			throw new Exception("Failed to parse HTML");
		}
		// dirty fix
		foreach ($doc->childNodes as $item)
			if ($item->nodeType == XML_PI_NODE)
				$doc->removeChild($item); // remove hack
		$doc->encoding = 'UTF-8'; // insert proper
		
		return $doc;
	}
	
	static function startsWith($haystack, $needle) {
         $length = strlen($needle);
         return (substr($haystack, 0, $length) === $needle);
    }
    
    static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
    
        return (substr($haystack, -$length) === $needle);
    }
	
}
if ( defined('SWETE_USE_HTML5_PARSER') and SWETE_USE_HTML5_PARSER ){
    SweteTools::$USE_HTML5_PARSER = true;
}