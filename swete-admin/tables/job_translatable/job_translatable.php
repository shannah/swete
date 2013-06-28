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
class tables_job_translatable {

	function field__website($record){
		$webpage = df_get_record('webpages', array('webpage_id'=>'='.$record->val('webpage_id')));
		if ( !$webpage ) return null;
		
		return df_get_record('websites', array('website_id'=>'='.$webpage->val('website_id')));
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
		$out = array($this->getDefaultTargetLanguage($record));
		return $out;
	}
	
	function getTranslationMemoryId($record, $source, $dest){
		$res = df_q("select translation_memory_id from jobs where job_id='".addslashes($record->val('job_id'))."'");
		list($tmid) = mysql_fetch_row($res);
		@mysql_free_result($res);
		return $tmid;
	}
}