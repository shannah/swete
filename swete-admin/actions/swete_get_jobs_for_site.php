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

class actions_swete_get_jobs_for_site {

	function handle(&$params){
		try{
			
			$app =& Dataface_Application::getInstance();
			$query =& $app->getQuery();
			
			$auth =& Dataface_AuthenticationTool::getInstance();
			$user =& $auth->getLoggedInUser();
			
			if (!isset($query['-site-id']))	throw new Exception("No site id specified");
			if (isset($query['-compiled'])){ 
				if ($query['-compiled']=='true' || $query['-compiled']==1){
					$compiled = 1;
				}else{
					$compiled = 0;
				}
				$jobs = df_get_records_array('jobs', array('website_id'=>$query['-site-id'], 'posted_by'=>$user->val('username'), 'compiled'=>$compiled));
			}else{
				$jobs = df_get_records_array('jobs', array('website_id'=>$query['-site-id'], 'posted_by'=>$user->val('username')));
			}
			
			//array of job ids and job titles to present to user
			$results = array();
			foreach($jobs as $job){
				$results[] = array('job_id'=>$job->val('job_id'), 'title'=>$job->getTitle());
			}
			echo json_encode($results);
			
		
		}catch (Exception $e){
			if ($e->getCode() == E_USER_ERROR){
				echo $e->getMessage();
				
			}else{
				throw $e;
			}
		}
        
	}

}