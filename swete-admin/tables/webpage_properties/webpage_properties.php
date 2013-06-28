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
class tables_webpage_properties {
	function field__active($record){ 
		$out = $record->val('effective_active');
		if ( !isset($out) ){
			$webpage = new SweteWebpage($record->val('webpage'));
			$record->setValue('effective_active', $webpage->isActive(true));
			$out = $record->val('effective_active');
		}
		return $out;
	}
	function field__locked($record){ 
		$out = $record->val('effective_locked');
		if ( !isset($out) ){
			$webpage = new SweteWebpage($record->val('webpage'));
			$record->setValue('effective_locked', $webpage->isLocked(true));
			$out = $record->val('effective_locked');
		}
		return $out;
		
	}
	function field__translation_memory_id($record){ 
		$out = $record->val('effective_translation_memory_id');
		if ( !isset($out) ){
			$webpage = new SweteWebpage($record->val('webpage'));
			$record->setValue('effective_translation_memory_id', $webpage->getTranslationMemoryId(true));
			$out = $record->val('effective_translation_memory_id');
		}
		return $out;
		
	}
	function field__enable_live_translation($record){ 
		$out = $record->val('effective_enable_live_translation');
		if ( !isset($out) ){
			$webpage = new SweteWebpage($record->val('webpage'));
			$record->setValue('effective_enable_live_translation', $webpage->getEnableLiveTranslation(true));
			$out = $record->val('effective_enable_live_translation');
		}
		return $out;
		
	}
	function field__live_translation_min_approval_level($record){ 
		$out = $record->val('effective_live_translation_min_approval_level');
		if ( !isset($out) ){
			$webpage = new SweteWebpage($record->val('webpage'));
			$record->setValue('effective_live_translation_min_approval_level', $webpage->getLiveTranslationMinApprovalLevel(true));
			$out = $record->val('effective_live_translation_min_approval_level');
		}
		return $out;
	}
	
	
	function field__webpage($record){
		if ( !isset($record->pouch['webpage']) ){
			$record->pouch['webpage'] = df_get_record('webpages', array('webpage_id'=>'='.$record->val('webpage_id')));
		}
		return $record->pouch['webpage'];
	
	}
	
}