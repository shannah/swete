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
require_once 'inc/SweteJob.class.php';
require_once 'inc/SweteWebpage.class.php';

class actions_swete_remove_webpage_from_job {

    function handle(&$params){
    	
    	try{
    	
			$app = Dataface_Application::getInstance();
			$query = $app->getQuery();
			
			$jobRecord = df_get_record('jobs', array('job_id'=>'='.$query['-job_id']));
			if ( !$jobRecord->checkPermission('edit') ){
				
				throw new Exception("You do not have permission make changes to this translation job.", E_USER_ERROR);
			}
			
			$job = new SweteJob($jobRecord);
			
			if (array_key_exists('-webpage_id', $query)){
				$webpageRecord = df_get_record('webpages', array('webpage_id'=>'='.$query['-webpage_id']));
				$webpage = new SweteWebpage($webpageRecord);
				$job->removeWebpage($webpage);
			}else if (array_key_exists('-data-http-request-log-id', $query)){
				$job->removeRequestStrings($query['-data-http-request-log-id']);
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