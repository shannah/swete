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
class actions_swete_messages {

    function handle(&$params){
    	
    	// returns html for a table with all the messages for -job_id
    	
    	$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		
		$auth =& Dataface_AuthenticationTool::getInstance();
		$user =& $auth->getLoggedInUser();
		
		$job_id = $query['-job_id'];
		
		$job =& df_get_record("jobs", array('job_id'=>$job_id));
    	
    	require_once 'inc/SweteDb.class.php';
    	require_once 'inc/SweteJob.class.php';
    	require_once 'inc/SweteJobInbox.class.php';
    	require_once 'inc/SweteJobInboxPresentation.php';
    	
		$sweteJob = new SweteJob($job);
		$messageList = $sweteJob->getInbox($user->val('username'))->getMessageList();
		
		echo SweteJobInboxPresentation::tableContent($messageList);

    }
    
}