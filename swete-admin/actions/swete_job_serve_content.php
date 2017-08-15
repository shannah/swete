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
class actions_swete_job_serve_content {

	function handle($params){
		session_write_close();
		header('Connection: close');
		
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		
		if ( !@$query['job_id'] ){
			throw new Exception("NO job id specified");
		}
		
		if ( !@$query['url_hash'] ){
			throw new Exception("No URL Hash specified");
		}
		
		$job = df_get_record('jobs', array('job_id'=>'='.$query['job_id']));
		if ( !$job ){
			throw new Exception("Job could not be found.");
		}
		require_once 'inc/SweteJob.class.php';
		require_once 'inc/SweteJobPageSucker.php';
		
		$jobO = new SweteJob($job);
		$pageSucker = new SweteJobPageSucker($jobO);
		
		$resource  = $pageSucker->loadResource($query['url_hash']);
		if ( !$resource ){
			header('HTTP/1.0 404 Not Found');
			exit;
		}
		
		if ( !$job->checkPermission('preview job') ){
			header('HTTP/1.0 400 Permission denied');
			exit;
		}
		
		$res = df_q("select * from job_content where job_content_id='".addslashes($resource->val('job_content_id'))."' limit 1");
		$content = xf_db_fetch_object($res);
		$output = $content->content;
		
		if ( preg_match('#css#', $content->content_type) ){
			$output = $pageSucker->renderCss($output, DATAFACE_SITE_HREF.'?-action=swete_job_serve_content&job_id='.$query['job_id'].'&url_hash=');
			
		}
		
		header('Content-Length: '.strlen($output));
		header('Content-Type: '.$content->content_type);
		echo $output;
		flush();
		
		
		
		
		
		
	}

}