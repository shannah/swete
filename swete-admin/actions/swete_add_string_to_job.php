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

class actions_swete_add_string_to_job {

	function handle(&$params){
		try{
			// First get the selected records
			$app =& Dataface_Application::getInstance();
			$query =& $app->getQuery();
			
			if ( !@$query['-record-id'] ) throw new Exception("No record id was specified");
			$record = df_get_record_by_id($query['-record-id']);
			
			$isNewJob = false;
			
			//get the selected job
			if ( $query['-job'] && is_numeric($query['-job'])){
			
				$selectedJob = df_get_record('jobs', array('job_id'=>'='.$query['-job']));
			}else{
				//no job was selected by user
				$site_id =  $record->val('website_id');
				
				$jobs = df_get_records_array('jobs', array('website_id'=>$site_id, 'compiled'=>'false'));
				
				$createNewJob = false;
				if ( $query['-job'] == "new"){
					$createNewJob = true;
				}
				
				if (count($jobs)==0 || $createNewJob){
					//create a new job
					$selectedJob = SweteJob::createJob(SweteSite::loadSiteById($site_id))->getRecord();
					$isNewJob = true;
				}else if (count($jobs)==1){
					//only one available job
					$selectedJob = $jobs[0];
				}else{
					throw new Exception("No Job id was specified, but there are ".$count($jobs)." available jobs to add to");
				}
			}
			
			if (!$selectedJob){
				throw new Exception("Job could not be found", E_USER_ERROR);
			}
			
			if ( !$selectedJob->checkPermission('edit') ){
				throw new Exception("You don't have permission to edit this job");
			}
			
			$job = new SweteJob($selectedJob);
			
			if ( intval($record->val('website_id')) !== intval($selectedJob->val("website_id")) ){
				throw new Exception("The string ".$record->val('string')." is not in the same site as the job.");
			}
			
			//If string was already added to ANOTHER job, it doesn't matter
			//It will also be added to this one
				
			//if string was already added to this job, do nothing
			if (! $job->containsString($record->val('string'))){
				$job->addTranslationMiss($record->val('translation_miss_log_id'));
				
				$results = array('stringAdded'=>$record->val('translation_miss_log_id'), 
									'jobId'=>$selectedJob->val('job_id'),
									'isNewJob'=>$isNewJob);
				
				
			}else{
			
				$results = array('stringAdded'=>"false", 
									'jobId'=>$selectedJob->val('job_id'),
									'isNewJob'=>$isNewJob);
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