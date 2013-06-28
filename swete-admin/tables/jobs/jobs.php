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
require_once 'inc/SweteDb.class.php';
require_once 'inc/SweteSite.class.php';
class tables_jobs {

	function block__custom_javascripts(){
		
		$jt = Dataface_JavascriptTool::getInstance();
		$jt->import('swete/actions/compile_job.js');
		$jt->import('swete/actions/approve_job.js');
		$jt->import('swete/actions/assign_job.js');
	}
	

	function __sql__ (){
	
		$user =& SweteTools::getUser();
		
		$username = "";
		
		if (isset($user)){
			$username = $user->val('username');
		}
		return "select j.*, r.access_level from jobs j left join job_roles r on (j.job_id=r.job_id and r.username='".
			htmlspecialchars($username)."')";
		
		
	}
	
	
	
	function init($table){
	
		$user =& SweteTools::getUser();
		
		if ( !SweteTools::isAdmin() ){
			// apply the security filter to non admin users.
			
			$table->setSecurityFilter(array('access_level'=>"!="));
		}
	}
	
	function getPermissions(&$record){
		$user =& SweteTools::getUser();
		if ( !isset($user) ) return null;
		
    	if ( SweteTools::isAdmin() ) return null;
		
		if (isset($record)){
		
			
				
			$job = new SweteJob($record);
			if ($record->val("assigned_to") === $user->val('username')){
				//error_log("job is assigned to ".$user->val('username'));
				return Dataface_PermissionsTool::getRolePermissions('ASSIGNEE');
			}
			
		}
		
		//default
		return null;
		
	}
	
	function section__notes(&$record){
	
		$user =& SweteTools::getUser();
		
		if (isset($user)){
			
			require_once 'inc/SweteJobInboxPresentation.php';
			
			$job = new SweteJob($record);
			$messageList = $job->getInbox($user->val('username'))->getMessageList();
			
			
			$jsTool = Dataface_JavascriptTool::getInstance();
        	$jsTool->import('swete/tables/jobs/notes.js');
        	
			$content = SweteJobInboxPresentation::tableContent($messageList);
			
			$inbox = '<table class="job-messages" data-job-id="'.$record->val('job_id').'" style="width:100%; height:100%">'.
						$content.'</table>';
			
			//Add a new message
			$inbox .= '
					<br/><span class="new_note">Add a new note:</span><br/>
					<textarea class="new_note" data-job-id='.$record->val('job_id').' style="width: 100%; height:100px;"  ></textarea>
					<br/><button type="button" class="add_note">Add</button>
				';
				
			
			return array(
				'content' => $inbox,
				'class' => 'main',
				'label'=> 'Inbox',
				'order'=> 0
			);
			
		}
		
		
	}
	
	/*
	 * @brief a section for all webpages that are included in the job
	 *
	*/
	function section__webpages(&$record){
		
		require_once 'inc/SweteWebpagesPresentation.php';
		
		$jsTool = Dataface_JavascriptTool::getInstance();
        $jsTool->import('swete/tables/jobs/webpages.js');
			
		$job = new SweteJob($record);
		
		$content = '<table class="job-webpages listing resultList" data-job-id="'.$record->val('job_id').'" >'
					.SweteWebpagesPresentation::tableContent($job).'</table>';
		
		return array(
				'content' => $content,
				'class' => 'main',
				'label'=> 'Webpages',
				'order'=> 1
			);
		
	}
	
	/*
	 * @brief a section for all strings that are included in the job
	 *
	*/
	function section__livewebpages(&$record){
		
		require_once 'inc/SweteLiveWebpagesPresentation.php';
		
		$jsTool = Dataface_JavascriptTool::getInstance();
        $jsTool->import('swete/tables/jobs/livewebpages.js');
			
		$job = new SweteJob($record);
		
		$content = '<table class="job-livewebpages listing resultList" data-job-id="'.$record->val('job_id').'" >'
					.SweteLiveWebpagesPresentation::tableContent($job).'</table>';
		
		return array(
				'content' => $content,
				'class' => 'main',
				'label'=> 'Live Webpages',
				'order'=> 1
			);
		
	}
	
	function afterAddNewRelatedRecord($relatedRecord){
		//add the webpage strings to the job
		
		require_once 'inc/SweteJob.class.php';
		require_once 'inc/SweteWebpage.class.php';
		require_once 'inc/SweteDb.class.php';
		
		//todo only for 'webpages' related record
		
		$jobsRecord = $relatedRecord->toRecord('jobs');
		$job = new SweteJob($jobsRecord);
		
		$webpageRecord = df_get_record("webpages",
			array('webpage_id'=>$relatedRecord->toRecord('webpages')->val("webpage_id")));
		
		$job->addWebpage(new SweteWebpage($webpageRecord), null, true);

	
	}
	
	function afterRemoveRelatedRecord($relatedRecord){
		//remove the webpage strings fro the job
		
		//todo only for 'webpages' related record
		error_log("afterRemoveRelatedRecord table is ".$relatedRecord->_record->_tableName);
		//if ($relatedRecord->_record->_tableName == 'webpages'){
			
			require_once 'inc/SweteJob.class.php';
			require_once 'inc/SweteWebpage.class.php';
			require_once 'inc/SweteDb.class.php';
			
			$jobsRecord = $relatedRecord->toRecord('jobs');
			$job = new SweteJob($jobsRecord);
			
			$webpageRecord = df_get_record("webpages",
				array('webpage_id'=>$relatedRecord->toRecord('webpages')->val("webpage_id")));
			
			$job->removeWebpage(new SweteWebpage($webpageRecord));
			
		
	
	}

	
	
	function compiled__default(){
		return "0";
	}
	
	
	function posted_by__default(){
		$user = SweteTools::getUser();
		if ( $user ) return $user->val('username');
		return '';
	}
	
	function beforeInsert($record){
		if ( !$record->val('posted_by') and SweteTools::getUser() ){
			$record->setValue('posted_by', SweteTools::getUser()->val('username'));
			
		}
	
	}
	
	function afterInsert($record){
		$site = SweteSite::loadSiteById($record->val('website_id'));
		SweteJob::decorateNewJob($site, $record);
		
	}
	function afterSave($record){
		if ( $record->val('posted_by') ){
			$res = df_q("insert ignore into job_roles (job_id, username, access_level) values ('".addslashes($record->val('job_id'))."','".addslashes($record->val('posted_by'))."','".SweteJob::JOB_ROLE_OWNER."')");
		}
			
		if ( $record->val('assigned_to') ){
			$res = df_q("insert ignore into job_roles (job_id, username, access_level) values ('".addslashes($record->val('job_id'))."','".addslashes($record->val('assigned_to'))."','".SweteJob::JOB_ROLE_TRANSLATOR."')");
		}
	}
	function getTitle(&$record){
		return 'Job '.$record->val('job_id').' (Created by '.$record->val('posted_by').' on '.$record->getValueAsString('date_created').')';
	}


}