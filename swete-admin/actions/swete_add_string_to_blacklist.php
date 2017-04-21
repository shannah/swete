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
class actions_swete_add_string_to_blacklist {

	function handle(&$params){
		try{
			$app = Dataface_Application::getInstance();
			$query = $app->getQuery();
			$selectedRecords = df_get_selected_records($query);
			$stringIds = array();
			$failed = 0;
			foreach($selectedRecords as $record){
			    if (!$record->checkPermission('translate')) {
			        $failed++;
			        continue;
			    }
			    $stringIds[] = $record->val('string_id');
			}
			if ($stringIds) {
			    
                
                $sql = "REPLACE INTO swete_strings_blacklist (string_id) values ";
                $first = true;
                foreach ($stringIds as $stringId) {
                    if ($first) {
                        $first = false;
                    } else {
                        $sql .= ', ';
                    }
                    $sql .= '('.addslashes($stringId).')';
                }
                df_q($sql);
			}
			
			$url = $app->url('-action=list');
            if ( @$_POST['--redirect'] ) $url = base64_decode($_POST['--redirect']);
                    $msgStr = df_translate('x strings were blacklisted', '%d strings were blacklisted.');
                    $msgStr = sprintf($msgStr, count($stringIds));
            $url .= '&--msg='.urlencode($msgStr);
            $app->redirect($url);
			
		}catch (Exception $e){
			if ($e->getCode() == E_USER_ERROR){
				echo $e->getMessage();
				
			}else{
				throw $e;
			}
		}

	}


}