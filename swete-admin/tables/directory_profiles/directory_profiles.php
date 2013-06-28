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
class tables_directory_profiles {
	function beforeDelete($record){
	
		// We need to delete all associated proxy sites.
		$tms = df_get_records_array('directory_profile_translation_memories', array('directory_profile_id'=>'='.$record->val('directory_profile_id')));
		while ($tms){
		
			foreach ($tms as $tm){
			
				$res = $tm->delete(true);
				if ( PEAR::isError($res) ){
					return PEAR::raiseError('Failed to delete profile site "'.$tm->getTitle(), DATAFACE_E_NOTICE);
				}
			}
			$tms = df_get_records_array('directory_profile_translation_memories', array('directory_profile_id'=>'='.$record->val('directory_profile_id')));
			
		}
	}
}