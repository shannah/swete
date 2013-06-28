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

/**
** Finds the website id from the selected record(s).
** All records must have a 'website_id' and must be for the same website.
** Returns the website id or an error message, if the website_ids are not the same.
** parameter -record-id is only used if there were no records selected (using df_get_selected_records)
**/
class actions_swete_get_website_from_record {

	function handle(&$params){
		try{
			
			$app =& Dataface_Application::getInstance();
			$query =& $app->getQuery();
			
			
			if (isset($query['-record-id'])){ //todo fix the php notice here. Undefined index: -record-id 
				
				$selectedRecord = df_get_record_by_id($query['-record-id']);
				echo $selectedRecord->val('website_id');
				
			}else{
				
				$selectedRecords = df_get_selected_records($query);
			
				if (empty($selectedRecords)) throw new Exception("No records specified");
				
				$record = $selectedRecords[0];
				
				$websiteId = $record->val('website_id');
				
				//ensure that all selectedRecords are for the same website
				foreach($selectedRecords as $record){
					if ($record->val('website_id') != $websiteId){
						throw new Exception("All records must be from the same site.", E_USER_ERROR);
					}
				}
				
				//return the website id
				echo $websiteId;
			}
		
		}catch (Exception $e){
			if ($e->getCode() == E_USER_ERROR){
				echo $e->getMessage();
				
			}else{
				throw $e;
			}
		}
	
	}


}