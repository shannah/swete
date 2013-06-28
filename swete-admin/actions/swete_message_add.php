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
class actions_swete_message_add {

    function handle(&$params){
    
    	try{
    	
			// add a new message to the current job record
			//-content is the new message content
			$app = Dataface_Application::getInstance();
			$query = $app->getQuery();
			
			$auth =& Dataface_AuthenticationTool::getInstance();
			$user =& $auth->getLoggedInUser();
			
			$content = trim(htmlspecialchars($query['-content']));
			if ( !$content ){
				throw new Exception("No message contents entered.", E_USER_ERROR);
			}
			
			$job_id = $query['-job_id'];
			
			$job_rec =& df_get_record("jobs", array('job_id'=>$job_id));
			if ( !$job_rec->checkPermission('add new related record') ){
				
				throw new Exception("You do not have permission to add a note to this job.", E_USER_ERROR);
				
			}
			
			require_once 'inc/SweteDb.class.php';
			require_once 'inc/SweteJob.class.php';
			require_once 'inc/SweteJobInbox.class.php';
			
			$job = new SweteJob($job_rec);
			
			$inbox = $job->getInbox($user->val('username'));
			
			$noteRec = $inbox->addMessage($content);
		
		}catch (Exception $e){
			if ($e->getCode() == E_USER_ERROR){
				echo $e->getMessage();
				
			}else{
				throw $e;
			}
		}
		
    }
    
}