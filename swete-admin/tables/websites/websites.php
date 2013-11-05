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
class tables_websites {

    var $system_locales = null;
    function valuelist__system_locales(){
        if ( !isset($this->system_locales) ){
            exec('locale -a', $out);
            $this->system_locales = array_flip($out);
            //$this->system_locales = array_flip(array_map('trim',explode("\n", $out)));
            foreach ( $this->system_locales as $k=>$v){
                $this->system_locales[$k] = $k;
            }
            
        }
        return $this->system_locales;
    }

	function init(Dataface_Table $table){
		if ( !@Dataface_Application::getInstance()->_conf['enable_static'] ){
			$efl =& $table->getField('enable_live_translation');
			$efl['Default'] = 1;
			$efl['widget']['type'] = 'hidden';
		}
	}

	function beforeDelete($record){
	
		// We need to delete all associated webpages
		$pages = df_get_records_array('webpages', array('website_id'=>'='.$record->val('website_id')));
		while ( $pages ){
		
			foreach ($pages as $page ){
			
				$res = $page->delete(true);
				if ( PEAR::isError($res) ){
					return PEAR::raiseError("Failed to delete page ".$page->getTitle(), DATAFACE_E_NOTICE);
				}
			}
			
			$pages = df_get_records_array('webpages', array('website_id'=>'='.$record->val('website_id')));
		}
		
		if ( class_exists('LiveCache') ){
			LiveCache::touchSite($record->val('website_id'));
		}
		
		
		
		
		
	}
	
	function translation_parser_version__default(){
	    return 2;
	}
	
	
	function beforeInsert($record){
		if ( !$record->val('translation_memory_id') ){
			require_once 'modules/tm/lib/XFTranslationMemory.php';
			$tm = XFTranslationMemory::createTranslationMemory($record->val('website_name').' Dictionary', $record->val('source_language'), $record->val('target_language'));
			$record->setValue('translation_memory_id', $tm->getRecord()->val('translation_memory_id'));
			
		}
	}
	
	function beforeSave($record){
		//if base_path doesn't start with // then add it on
		$base_path = $record->val('base_path');
		$changedBasePath = false;
		if ( strpos($base_path, '//') !== false ){
			$base_path = preg_replace('#//#', '/', $base_path);
			$changedBasePath = true;
		}
		if ( substr($base_path, 0, 1) !== '/' ){
			$base_path = '/'.$base_path;
			
			$changedBasePath = true;
		}
		if ( !preg_match('/\/$/', $base_path) ){
			$base_path .= '/';
			$changedBasePath = true;
		}
		if ( $changedBasePath ){
			$record->setValue('base_path', $base_path);
		}
		
		if ( class_exists('LiveCache') ){
			LiveCache::touchSite($record->val('website_id'));
		}
		
	}
	
	function host__default(){
		return $_SERVER['HTTP_HOST'];
	}
	
	function base_path__default(){
		//add a trailing slash if the site url doesn't already have one
		if (substr(DATAFACE_SITE_URL, 0, 1) == '/'){
			return dirname(DATAFACE_SITE_URL);
		}else{
			return dirname(DATAFACE_SITE_URL).'/';
		}
		
	}
	
	
	function afterInsert($record){
		require_once 'inc/SweteTools.php';
		SweteTools::updateDb();
		
		// Add default text filters.
		df_q("insert into site_text_filters (website_id, filter_id, filter_type, filter_order)
			select ".intval($record->val('website_id')).", filter_id, 'Prefilter', default_order
			from text_filters where is_default_prefilter=1 and 
			(`language` IS NULL or `language`='".addslashes($record->val('source_language'))."')");
		
		df_q("insert into site_text_filters (website_id, filter_id, filter_type, filter_order)
			select ".intval($record->val('website_id')).", filter_id, 'Postfilter', default_order
			from text_filters where is_default_postfilter=1 and 
			(`language` IS NULL or `language`='".addslashes($record->val('source_language'))."')");
		
		
	}
}