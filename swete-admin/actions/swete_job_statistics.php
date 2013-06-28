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
class actions_swete_job_statistics {

	function handle($params){
		
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		
		
		if ( !@$query['-record-id'] ) throw new Exception("No record id was specified");
			
		$record = df_get_record_by_id($query['-record-id']);
		
		if (!isset($record) || !($record instanceof Dataface_Record))
			throw new Exception("Record could not be found for the record id [".
				$query['-record-id']."] that was specified");
		
		require_once 'inc/SweteJob.class.php';
		
		$job = new SweteJob($record);
		
		$stats = $job->getStats();
		
		$out = array(
				'code'=>200,
				'message'=>'Successfully retrieved job translation stats.',
				'stats'=>$stats
		);
		
		
		
		header('Content-type: text/json; charset="'.$app->_conf['oe'].'"');
		echo json_encode($out);
		return;
	}

}