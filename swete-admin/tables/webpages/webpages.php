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
require_once 'inc/SweteWebpage.class.php';
class tables_webpages {

	function block__custom_javascripts(){
		
		$jt = Dataface_JavascriptTool::getInstance();
		$jt->import('swete/actions/add_selected_webpages_to_job.js');
		$jt->import('swete/actions/approve_pages.js');
			
			
	}


	function ckeditor_decorateConfig($record, &$config){
		$site = df_get_record('websites', array('website_id'=>$record->val('website_id') ));
		
		if ( $site ){
			$path = $record->val('webpage_url');
			if ( $path{strlen($path)-1} != '/' ){
				if ( strpos($path, '/') !== false ){
					$parts = explode('/', $path);
					array_pop($parts);
					$path = implode('/', $parts);
				} else {
					$path = '';
				}
				$path .= '/';
			}
			
			$baseurl = $site->val('website_url').$path;
			$config['baseHref'] = $baseurl;
		}
	}

	function beforeDelete($record){
	
		$versions = df_get_records_array('webpage_versions', array('webpage_id'=>'='.$record->val('webpage_id')));
		while ($versions){
			foreach ($versions as $version){
			
				$res = $version->delete(true);
				if ( PEAR::isError($res) ){
					return $res;
				}
			}
			$versions = df_get_records_array('webpage_versions', array('webpage_id'=>'='.$record->val('webpage_id')));
		
		}
		
		
		$logs = df_get_records_array('webpage_check_log', array('webpage_id'=>'='.$record->val('webpage_id')));
		while ($logs ){
			foreach ($logs as $log){
			
				$res = $log->delete(true);
				if ( PEAR::isError($res) ) return $res;
			}
			$logs = df_get_records_array('webpage_check_log', array('webpage_id'=>'='.$record->val('webpage_id')));
		
		}
		
		$children = df_get_records_array('webpages', array('parent_id'=>'='.$record->val('webpage_id')));
		while ($children ){
			foreach ($children as $child){
			
				$res = $child->delete(true);
				if ( PEAR::isError($res) ) return $res;
			}
			$children = df_get_records_array('webpages', array('parent_id'=>'='.$record->val('webpage_id')));
		
		}
		
		
	}
	
	
	function getTitle($record){
		return $record->val('webpage_url');
	}
	
	
	function beforeSave($record){
		if ( $record->valueChanged('active') ){
			// The active flag has changed so we need to update the effective active
			// flag of this page and all its children
			$this->pouch['update_active'] = 1;
		}
		if ( $record->valueChanged('locked') ){
			// The active flag has changed so we need to update the effective active
			// flag of this page and all its children
			$this->pouch['update_locked'] = 1;
		}
			
		if ( $record->valueChanged('translation_memory_id') ){
			// The active flag has changed so we need to update the effective active
			// flag of this page and all its children
			$this->pouch['update_translation_memory_id'] = 1;
		}
		
		if ( $record->valueChanged('auto_approve') ){
			// The active flag has changed so we need to update the effective active
			// flag of this page and all its children
			$this->pouch['update_auto_approve'] = 1;
		}
		
		if ( class_exists('LiveCache') ){
			LiveCache::touchSite($record->val('website_id'));
		}
	}
	
	function afterSave($record){
		if ( @$this->pouch['update_active'] ){
			require_once 'inc/BackgroundProcess/UpdateEffectiveActive.php';
			$task = new BackgroundProcess_UpdateEffectiveActive;
			$task->rootPageId = intval($record->val('webpage_id'));
			$task->lang = $record->lang or 'en';
			$task->property = 'active';
			$task->save();
		
		}
		if ( @$this->pouch['update_locked'] ){
			require_once 'inc/BackgroundProcess/UpdateEffectiveActive.php';
			$task = new BackgroundProcess_UpdateEffectiveActive;
			$task->rootPageId = intval($record->val('webpage_id'));
			$task->lang = $record->lang or 'en';
			$task->property = 'locked';
			$task->save();
		
		}
		if ( @$this->pouch['update_translation_memory_id'] ){
			require_once 'inc/BackgroundProcess/UpdateEffectiveActive.php';
			$task = new BackgroundProcess_UpdateEffectiveActive;
			$task->rootPageId = intval($record->val('webpage_id'));
			$task->lang = $record->lang or 'en';
			$task->property = 'translation_memory_id';
			$task->inheritVal = 0;
			$task->save();
		
		}
		
		if ( @$this->pouch['update_auto_approve'] ){
			require_once 'inc/BackgroundProcess/UpdateEffectiveActive.php';
			$task = new BackgroundProcess_UpdateEffectiveActive;
			$task->rootPageId = intval($record->val('webpage_id'));
			$task->lang = $record->lang or 'en';
			$task->property = 'auto_approve';
			$task->inheritVal = 0;
			$task->save();
		
		}
	}
	
	
	function valuelist__locked(){
		if ( !isset($this->_locked) ){
			$this->_locked = array(
				'-1'=>'Inherit from Parent',
				//0 => 'Unlocked',
				'1' => 'Locked',
				'' => 'Unlocked'
			);
		}
		return $this->_locked;
	
	}
	
	function locked__default(){
		return '-1';
	}
	
	function field__properties($record){
		$p = df_get_record('webpage_properties', array('webpage_id'=>'='.$record->val('webpage_id')));
		if ( !$p ){
			$p = new Dataface_Record('webpage_properties', array());
			$p->setValue('webpage_id', $record->val('webpage_id'));
			$p->pouch['webpage'] = $record;
			$p->save();
		}

		return $p;
	}
	
	
	function field__website($record){
		return df_get_record('websites', array('website_id'=>'='.$record->val('website_id')));
	}
	
	function getSourceLanguage($record){
		if ( !isset($record->pouch['source_language']) ){
			$site = $record->val('website');
			if ( !$site ) return null;
			$record->pouch['source_language'] =  $site->val('source_language');
		}
		return $record->pouch['source_language'];
	}
	
	function getDefaultTargetLanguage($record){
		if ( !isset($record->pouch['target_language']) ){
			$site = $record->val('website');
			if ( !$site ) return null;
			$record->pouch['target_language'] =  $site->val('target_language');
		}
		return $record->pouch['target_language'];
	}
	
	function getTargetLanguages($record){
		return array($this->getDefaultTargetLanguage($record));
	}
	
	function getTranslationMemoryId($record, $source, $dest){
		if ( $source != $this->getSourceLanguage($record) or $dest != $this->getDefaultTargetLanguage($record) ){
			return null;
		}
		
		$wp = new SweteWebpage($record);
		$props = $wp->getProperties();
		//print_r($props->vals());
		return $props->val('translation_memory_id');
	}

	
}