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

class actions_swete_approve_job {

	function handle(&$params){
	
		session_write_close();
		header('Connection: close');
		$app = Dataface_Application::getInstance();
		
		try{
			
			$query =& $app->getQuery();
			
			if ( !@$query['-record-id'] ) throw new Exception("No record id was specified");
			
			$record = df_get_record_by_id($query['-record-id']);
			$username = $query['-username'];
			
			if ( !$username ) throw new Exception("No username was specified");
			
			if ($record->val('compiled')==0){
				throw new Exception("The job has must be compiled before it can be approved");
			}
			
			if ($record->val('job_status')==SweteJob::JOB_STATUS_CLOSED){
				throw new Exception("The job has already been approved");
			}
			
			$job = new SweteJob($record);
			
			$job->approve($username);
			$out = array(
				'code'=>200,
				'message'=>'Successfully approved your job '
			);
			
			
		} catch (Exception $ex){
			
			$out = array(
			
				'code'=>$ex->getCode(),
				'message'=>$ex->getMessage()
			);
			
		
		}
		
		header('Content-type: text/json; charset="'.$app->_conf['oe'].'"');
		echo json_encode($out);
		return;
        
	}

}