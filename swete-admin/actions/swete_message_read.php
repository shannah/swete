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
class actions_swete_message_read {

    function handle(&$params){
    	
    	// mark the message as read, if hasn't been read yet -job_note_id
    	
    	try{
    	
			$app = Dataface_Application::getInstance();
			$query = $app->getQuery();
			
			$auth =& Dataface_AuthenticationTool::getInstance();
			$user =& $auth->getLoggedInUser();
			
			$note_id = $query['-job_note_id'];
			
			$jobNote =& df_get_record("job_notes", array('JobNoteId'=>$note_id));
			
			
			$job =& df_get_record("jobs", array('job_id'=>$jobNote->val('job_id')));
			
			if ( !$job->checkPermission('read message') ){
				
				throw new Exception("You do not have permission to read this note", E_USER_ERROR);
			}
			
			require_once 'inc/SweteDb.class.php';
			require_once 'inc/SweteJobInbox.class.php';
			
			SweteJobInbox::setReadStatic($note_id, $user->val('username'));
			
		}catch (Exception $e){
			if ($e->getCode() == E_USER_ERROR){
				echo $e->getMessage();
				
			}else{
				throw $e;
			}
		}
		
    }
    
}