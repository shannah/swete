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
require_once 'inc/SweteSite.class.php';
/**
 * @brief Encapsulates a single webpage from a site.
 */
class SweteWebpage {


	/*
	 * Constants for status id
	 */
	const STATUS_NEW=1;
	const STATUS_CHANGED=2;
	const STATUS_PENDING_APPROVAL=3;
	const STATUS_APPROVED=5;
	
	/**
	 * @type Dataface_Record
	 *
	 * @brief The record from the webpages table that encapsulates the row.
	 */
	private $_rec;
	
	/**
	 * @type Dataface_Record
	 */
	private $_properties;
	

	
	private $_site;
	
	

	
	
	/**
	 * @brief Loads a webpage by the url
	 * @param string $url The URL to the webpage.
	 * @param string $lang The 2-digit language code of the translation to load.
	 * @returns SweteWebpage The resulting webpage, or null if none matched the query.
	 */
	public static function loadByURL($website_id, $url, $lang){
		$app = Dataface_Application::getInstance();
		
		$old_lang = $app->_conf['lang'];
		$app->_conf['lang'] = $lang;
		$rec = df_get_record('webpages', array('website_id'=>'='.$website_id, 'webpage_url'=>'='.$url)); 
		$app->_conf['lang'] = $old_lang;
		
		if ( !$rec ) return null;
		else return new SweteWebpage($rec);
	
	}
	
	/**
	 * @brief Loads a webpage by its webpage_id
	 * 
	 * @param int $id The webpage ID.
	 * @param string $lang The 2-digit language code of the translation to load.
	 * @returns SweteWebpage The SweteWebpage object or null if none could be found matching the
	 *		given ID.
	 */
	public static function loadById($id, $lang){
		$app = Dataface_Application::getInstance();
		
		$old_lang = $app->_conf['lang'];
		$app->_conf['lang'] = $lang;
		$rec = df_get_record('webpages', array('webpage_id'=>'='.$id)); 
		$app->_conf['lang'] = $old_lang;
		
		if ( !$rec ) return null;
		else return new SweteWebpage($rec);
	}
	
	
	/**
	 * @brief Creates an object to encapsulate the webpages row.
	 * @param Dataface_Record $rec Record from the webpages table.
	 */
	public function __construct(Dataface_Record $rec){
		$this->_rec = $rec;
	}
	
	/**
	 * @brief Returns the properties that are associated with this page. This actually
	 * returns the corresponding value for this webpage from the @e webpage_properties table.
	 *
	 * Note:  If the properties have been explicitly calculated, then these will
	 * be the effective properties.  Otherwise they may be out of date.
	 *
	 * @returns Dataface_Record From the @e webpage_properties table.
	 * @see isActive()
	 * @see isLocked()
	 * @see getTranslationMemoryId()
	 * @see getEnableLiveTranslation()
	 * @see getAutoApprove()
	 *
	 */
	public function getProperties(){
		if ( !isset($this->_properties) ) $this->_properties = $this->_rec->val('properties');
		return $this->_properties;
	}
	

	
	
	/**
	 * @brief Gets the 2-digit language code of this webpage (or the current translation of it).
	 *
	 * @returns string The 2-digit language code.
	 */
	public function getLanguage(){
		if ( !@$this->_rec->lang ) $this->_rec->lang = Dataface_Application::getInstance()->_conf['lang'];
		return $this->_rec->lang;
	}
	
	
	
	/**
	 * @brief Gets the effective value of an inheritable property associated with this webpage.
	 * Since webpages are hierarchical, properties set on a webpage are propagated
	 * down to all descendent webpages unless explicitly overridden by the webpage
	 * itself. 
	 *
	 * @param string $name  The property name to retrieve.  Properties correspond to 
	 * columns in the webpage_properties table, and may include:
	 * 	- active
	 * 	- locked
	 *	- translation_memory_id
	 * 	- enable_live_translation
	 *	- auto_approve
	 *
	 * @param boolean $calculate If true, this will recalculate effective values.
	 * @param $inheritVal
	 * @returns mixed The effective value of the specified property.
	 *
	 * @see isActive()
	 * @see isLocked()
	 * @see getTranslationMemoryId()
	 * @see getEnableLiveTranslation()
	 * @see getAutoApprove()
	 *
	 */
	public function getInheritableProperty($name, $calculate=false, $inheritVal = -1){
		$effectiveActive = $this->_rec->val($name);
		if ( $effectiveActive <= $inheritVal ){
			$effectiveActive = $this->getProperties()->val('effective_'.$name);
			if ( !isset($effectiveActive) and $calculate ){
				$this->calculateInheritableProperty($name, $inheritVal);
				$effectiveActive = $this->getProperties()->val('effective_'.$name);
			}
		}
		return $effectiveActive;
		
	}
	
	/**
	 * @brief Checks if the webpage is currently active.
	 * @param boolean $calculate Recalculates the effective active property if true.
	 * @return boolean True if the webpage is active.
	 */
	public function isActive($calculate=false){
		return $this->getInheritableProperty('active', $calculate);
	
	}
	
	/**
	 * @brief Checks if the webpage is currently locked.
	 * @param boolean $calculate Recalculates the effective locked property if true.
	 * @return boolean True if the webpage is locked.
	 */
	public function isLocked($calculate=false){
		return $this->getInheritableProperty('locked', $calculate);
	}
	
	/**
	 * @brief Checks if the webpage is currently active.
	 * @param boolean $calculate Recalculates the effective active property if true.
	 * @return boolean True if the webpage is active.
	 */
	public function getTranslationMemoryId($calculate=false){
		return $this->getInheritableProperty('translation_memory_id', $calculate, 0);
	}
	
	/**
	 * @brief Checks if the webpage currently has live translation enabled..
	 * @param boolean $calculate Recalculates the effective live translation property if true.
	 * @return boolean True if the webpage has live translation enabled.
	 */
	public function getEnableLiveTranslation($calculate=false){
		return $this->getInheritableProperty('enable_live_translation', $calculate);
	}
	
	
	/**
	 * @brief Checks to see if this webpage has auto approve enabled.
	 * @param boolean $calculate Recalculates the effective auto approve value if true.
	 * @returns boolean True if the webpage has a auto approve turned on.
	 */
	public function getAutoApprove($calculate=false){
		return $this->getInheritableProperty('auto_approve', $calculate);
	}
	
	/**
	 * @brief Calculates an inheritable property for this webpage.
	 *
	 * @param string $name An inheritable property name. Properties correspond to 
	 * columns in the webpage_properties table, and may include:
	 * 	- active
	 * 	- locked
	 *	- translation_memory_id
	 * 	- enable_live_translation
	 *	- auto_approve
	 * @param int $inheritVal
	 * @returns mixed The calculated value.
	 */
	public function calculateInheritableProperty($name, $inheritVal = -1){
		$active = $this->_rec->val($name);
		if ( $active > $inheritVal ){
			$this->getProperties()->setValue('effective_'.$name, intval($active));
			$this->getProperties()->save();
		} else if ( $this->_rec->val('parent_id') ){
			$parent = self::loadById($this->_rec->val('parent_id'), $this->getLanguage());
			$active = $parent->getInheritableProperty($name, true, $inheritVal);
			$this->getProperties()->setValue('effective_'.$name, intval($active));
			$this->getProperties()->save();
		} else {
			$active = $this->getSite()->getRecord()->val($name);
			$this->getProperties()->setValue('effective_'.$name, intval($active));
			$this->getProperties()->save();
		}
		return $active;
	
	}
	
	/**
	 * @brief Calculates the @e active property for this webpage.
	 * @returns mixed The active property.
	 */
	public function calculateActive(){
	
		return $this->calculateInheritableProperty('active');
	
		
	}
	
	/**
	 * @brief Calculates the @e locked property for this webpage.
	 * @returns mixed The @e locked property.
	 *
	 */
	public function calculateLocked(){
		return $this->calculateInheritableProperty('locked');
	}
	
	
	/**
	 * @brief Calculates the translation_memory_id for this webpage.
	 * @returns mixed The @e translation_memory_id property
	 */
	public function calculateTranslationMemoryId(){
		return $this->calculateInheritableProperty('translation_memory_id', 0);
	}
	

	
	
	
	
	
	/**
	 * @brief Gets an alternate translation of this webpage.
	 *
	 * @param string $language The language code of the translation to retrieve.
	 * @returns SweteWebpage SweteWebpage object wrapping the alternate translation.
	 */
	public function getTranslation($language){
		return self::loadById($this->_rec->val('webpage_id'), $language);
	}
	
	/**
	 * @brief Gets a reference to the Dataface_Record object from the webpages table that this
	 * object wraps.
	 * @returns Dataface_Record
	 */
	public function getRecord(){
		return $this->_rec;
	}
	
	
	/**
	 * @brief Returns the site of which this page is a member.
	 * @returns SweteSite The site.
	 */
	public function getSite(){
	
		if ( !isset($this->_site) ){
			$this->_site = SweteSite::loadSiteById($this->_rec->val('website_id'));
			if ( !$this->_site ){
				$this->_site = new SweteSite(new Dataface_Record('websites', array()));
				
			}
		}
		return $this->_site;
	}
	
	
	/**
	 * @brief Sets the site of which this page is a member.
	 * @param SweteSite $site The site.
	 */
	public function setSite(SweteSite $site){
		$this->_site = $site;
	}
	
	
	
	
	
	
	/**
	 * @brief Sets the status of the current page to the specified approval status.  This will add 
	 * a record to the webpage_versions table with the specified status id.  I.e. this effectively
	 *	takes a snapshot of the record.
	 *
	 * @param int $status_id  The status id to set.  This should be one of:
	 *		<ul>
	 *			<li>SweteWebpage::STATUS_NEW</li>
	 *			<li>SweteWebpage::STATUS_CHANGED</li>
	 *			<li>SweteWebpage::STATUS_PENDING_APPROVAL</li>
	 *			<li>SweteWebpage::STATUS_APPROVED</li>
	 *		</ul>
	 * @param string $username The username of the user who is to be attributed with posting this
	 *	update.
	 * @param string $comments Comments associated with this status change.  This can be used
	 * 	to add a reason for the status change.
	 * @param boolean $secure Whether to make this subject to standard Xataface security.
	 * @returns Dataface_Record The resulting webpage_versions record.
	 */
	public function setStatus($status_id, $username, $comments, $secure=false){
		$rec = new Dataface_Record('webpage_versions', array());
		//$rec->setValues($this->_rec->vals());
		$rec->setValues(array(
			'approval_status'=>$status_id,
			'language'=>$this->getLanguage(),
			'posted_by'=>$username,
			'comments'=>$comments,
			'webpage_id'=>$this->_rec->val("webpage_id"),
			'page_content'=>$this->_rec->val('webpage_content'),
			'date_posted'=>date('Y-m-d H:i:s')
		));
		$res = $rec->save($secure);
		if ( PEAR::isError($res) ) throw new Exception(xf_db_error(df_db()));
		
		return $rec;
	}
	
	
	/**
	 * @brief Gets the Dataface_Record from the webpage_versions table with the specified ID.
	 *
	 * This can only be used to retrieve versions of this webpage in this language.  An exception
	 * will be thrown otherwise.
	 *
	 * @param int $version The webpage_version_id Of the version to retrieve.
	 * @returns Dataface_Record Record from the webpage_versions table.
	 */
	public function getVersion($version){
	
		$rec = df_get_record('webpage_versions', array('webpage_version_id'=>'='.$version));
		if ( !$rec ) return null;
		
		if ( $rec->val('language') != $this->getLanguage() ){
			throw new Exception("Version had wrong language");
		}
		
		if ( $rec->val('webpage_id') != $this->_rec->val('webpage_id') ){
			throw new Exception('Version for wrong record.');
		}
		
		return $rec;
	}
	
	
	/**
	 * @brief Gets the most recent Dataface_Record (from the webpage_versions table) that has the specified
	 * approval status.
	 *
	 * @param int $status_id The approval status.  This should be one of:
	 *		<ul>
	 *			<li>SweteWebpage::STATUS_NEW</li>
	 *			<li>SweteWebpage::STATUS_CHANGED</li>
	 *			<li>SweteWebpage::STATUS_PENDING_APPROVAL</li>
	 *			<li>SweteWebpage::STATUS_APPROVED</li>
	 *		</ul>
	 * @returns Dataface_Record Record from the webpage_versions table.
	 */
	public function getLastVersionWithStatus($status_id, $lang=null){
		if ( !isset($lang) ) $lang = $this->getLanguage();
		return df_get_record('webpage_versions', 
			array(
				'approval_status'=>'='.$status_id, 
				'-sort'=>'date_posted desc',
				'webpage_id'=>'='.$this->_rec->val('webpage_id'),
				'language'=>'='.$lang
			)
		);
		
	}
	
	/**
	 * @brief Applies translations from a translation memory to the webpage contents.  It returns another SweteWebpage
	 * object representing the same webpage but in the destination language of the translation memory.
	 * No changes are saved to the database.. you need to save the resulting SweteWebpage to make the changes
	 * persist.  
	 *
	 * Note: If there are no matches in the translation memory, then the resulting object will just contain
	 * the source language.  This could be overwriting a previous translation so be very careful before 
	 * saving.
	 *
	 * @param XFTranslationMemory $mem The translation memory to apply to the webpage contents.
	 * @param array &$stats Out array of stats from the translation.  This array contains the following
	 *		keys:
	 *			<ul>
	 *				<li><b>misses</b> - The number of strings for which there were no translations in the memory.</li>
	 *				<li><b>matches</b> - The number of strings that were replaced with a translation.</li>
	 *			</ul>
	 * @param int $minStatus The minimum approval status to accept for a translation.
	 * @param int $maxStatus The maximum approval status to accept for a translation.
	 * @returns SweteWebpage A webpage in the destination language including the translations.
	 */
	public function applyTranslationsFromMemory(XFTranslationMemory $mem, &$stats, $minStatus=3, $maxStatus=5){
		
	
		if ( $mem->getSourceLanguage() != $this->getLanguage() ){
			throw new Exception("Translation memory language does not match the record language.");
		}
		
		import('inc/ProxyWriter.php');
		$proxy = new ProxyWriter();
		$proxy->setTranslationMemory($mem);
		$proxy->setMinTranslationStatus($minStatus);
		$proxy->setMaxTranslationStatus($maxStatus);
		
		$html = $proxy->translateHtml($this->_rec->val('webpage_content'), $stats);
		
		
		$out = $this->getTranslation($mem->getDestinationLanguage());
		$out->getRecord()->setValue('webpage_content', $html);
		return $out;
		
		
		
		
	
	}
	
	
	/**
	 * @brief Checks whether this page has changed since it was last approved.  
	 *
	 * Note that it is possible for a page to be changed, yet not require translation.  E.g. If 
	 * contents have been added that are already in the translation memory - or if non-text elements
	 * have been added to the page.
	 *
	 * @returns boolean True if the page has changed since it was last approved.
	 */
	public function isChanged(){
		$approved = $this->getLastVersionWithStatus(self::STATUS_APPROVED);
		
		// If we couldn't find an approved version, then this record is most definitely 
		// changed.
		if ( !$approved ) return true;
		
		return (trim($approved->val('page_content')) != trim($this->_rec->val('webpage_content')));
		
	}
	
	/**
	 * @brief Checks whether this page requires translation, with respect to the specified
	 *  translation memory.
	 */
	public function requiresTranslation(XFTranslationMemory $mem, $minStatus=3, $maxStatus=5){
	
		$trans = $this->applyTranslationsFromMemory($mem, $stats, $minStatus, $maxStatus);
		
		return ($stats['misses'] > 0);
	}
	
	

	
	
}