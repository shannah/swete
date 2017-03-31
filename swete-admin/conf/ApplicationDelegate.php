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

include_once 'Dataface/AuthenticationTool.php';
include_once 'inc/SweteTools.php';

class conf_ApplicationDelegate {

	private $changedTranslationMemories = array();

	function block__after_global_footer(){
		echo '<script src="'.DATAFACE_SITE_HREF.'?-action=swete_background_processes"></script>';
	
	}
	
	
	function handleTranslationStatusChanged(stdClass $event){
		if ( !$this->changedTranslationMemories ){
			require_once 'inc/BackgroundProcess/UpdateChangedPages.php';
			register_shutdown_function(array($this, 'updateChangedPages'));
		}
		$tmid = $event->translationMemory->getRecord()->val('translation_memory_id');
		if ( !isset($this->changedTranslationMemories[$tmid]) ){
			if ( class_exists('LiveCache') ){
				LiveCache::touchTranslationMemory($tmid);
			}
		}
		$this->changedTranslationMemories[$tmid][] = $event->translationRecord->val('string_id');
		
	}
	
	function updateChangedPages(){
		$task = new BackgroundProcess_UpdateChangedPages;
		$task->changedTranslationMemories = $this->changedTranslationMemories;
		$task->save();
		error_log('Added task UpdateChangedPages');
	}
	
	function block__head(){
		
		$user = SweteTools::getUser();
		if ( $user ){
			echo '<link rel="stylesheet" type="text/css" href="css/swete/UncompiledJobs.css" />';
			echo '<meta id="xf-meta-username" name="xf-username" content="'.htmlspecialchars($user->val('username')).'"/>';
			echo '<script>WEBLITE_TRANSLATOR_DISABLE_EXTRACTION=true;</script>';
		}
		
	}
	
	/**
     * Returns permissions array.  This method is called every time an action is 
     * performed to make sure that the user has permission to perform the action.
     * @param record A Dataface_Record object (may be null) against which we check
     *               permissions.
     * @see Dataface_PermissionsTool
     * @see Dataface_AuthenticationTool
    */
    function getPermissions(&$record){
		if ( SweteTools::isAdmin() ) return Dataface_PermissionsTool::ALL();
		else return Dataface_PermissionsTool::NO_ACCESS();
		
		
	}
	
	function beforeHandleRequest(){
                
		$app = Dataface_Application::getInstance();
		$app->addHeadContent('<link rel="stylesheet" type="text/css" href="'.htmlspecialchars(DATAFACE_SITE_URL.'/css/swete/global.css').'"/>');
		
		$query =& $app->getQuery();
                
		$res = df_q("select language_code, language_label from languages");
		$langs = array();
		while ($row = mysql_fetch_row($res) ){
			$langs[$row[0]] = $row[1];
		}
		@mysql_free_result($res);
		$app->_conf['languages'] = $langs;
		$app->registerEventListener('tm.setTranslationStatus', array($this, 'handleTranslationStatusChanged'));
		Dataface_JavascriptTool::getInstance()->import('swete/global.js');
		
		if ( @$app->_conf['using_default_action'] and $query['-table'] == 'dashboard' ){
			$query['-action'] = 'dashboard';
		}
		
		if ($query['-table'] == 'website_copy_form' and $query['-action'] != 'new') {
		    $query['-action'] = 'new';
		}
               
                
	}
	
	function block__after_left_column(){
		//add info here when the user has an uncompiled job (or jobs).
		$app = Dataface_Application::getInstance();
		if ( $app->_conf['enable_jobs'] ){
			$jobs = SweteTools::uncompiledJobs();
			if ( isset($jobs) && !empty($jobs) ){
				$url = df_absolute_url(DATAFACE_SITE_HREF.'?-table=jobs&-action=list&compiled=0&posted_by=='.SweteTools::getUser()->val('username'));
				echo '<div class="uncompiled-jobs">There are <a href="'.$url.'">'.count($jobs).' jobs '.'</a>'.'waiting to be compiled.</div>';
			}
		}
	}
	
	function getNavItem($key, $label){
		$app = Dataface_Application::getInstance();
		if ( in_array($key, array('webpages')) ){
			if (@$app->_conf['enable_static']){
				throw new Exception("Default");
			} else {
				return null; // hide
			}
		} else 
		
		if ( in_array($key, array('jobs')) ){
			if (@$app->_conf['enable_jobs']){
				throw new Exception("Default");
			} else {
				return null; // hide
			}
		} else {
			throw new Exception("Default");
		}
	}
       
}