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

class actions_swete_assign_job {

	function handle(&$params){
	
		session_write_close();
		header('Connection: close');
		$app = Dataface_Application::getInstance();
		
		try{
			
			$query =& $app->getQuery();
			
			if ( !@$query['-record-id'] ) throw new Exception("No record id was specified");
			
			$record = df_get_record_by_id($query['-record-id']);
			
			$assignToUser = $query['-assign-to-user'];
			
			$username = null;
			$user = SweteTools::getUser();
			if ( $user ) $username = $user->val('username');
			
			$job = new SweteJob($record);
			
			if (!in_array($assignToUser, $job->getUsers())){
				throw new Exception ("Cannot assign the job to ".$assignToUser." because this user was not added to the job");
			}
			
			if ($job->isJobAssigned($assignToUser)){
				$out = array(
					'code'=>200,
					'message'=>'No change. Job is already assigned to '.$assignToUser
				);
			}else{
			
				$job->assignJob($assignToUser, $username);
				
				$out = array(
					'code'=>200,
					'message'=>'Successfully assigned the job to '.$assignToUser
				);
			}
			
			
			
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