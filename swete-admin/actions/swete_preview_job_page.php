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
class actions_swete_preview_job_page {

	function handle($params){
		session_write_close();
		header('Connection: close');
	
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		
		if ( !@$query['-job_translatable_id'] ){
			throw new Exception("No translatable id specified");
			
		}
		
		
		
		$translatable = df_get_record('job_translatable', array('job_translatable_id'=>'='.$query['-job_translatable_id']));
		if ( !$translatable ){
			throw new Exception("Translatable could not be found.");
		}
		
		$job = df_get_record('jobs', array('job_id'=>'='.$translatable->val('job_id')));
		if ( !$job ){
			throw new Exception("Job could not be loaded.");
		}
		
		if ( !$job->checkPermission('preview job') ){
			header('HTTP/1.0 401 Forbidden');
			exit;
		}
		
		require_once 'inc/SweteJob.class.php';
		require_once 'inc/SweteJobPageSucker.php';
		$jobO = new SweteJob($job);
		$pageSucker = new SweteJobPageSucker($jobO);
		
		$translation="source";
		if ( @$query['-translation'] ){
			$translation = $query['-translation'];
		}
		if ($translation == "source"){
			$output = $translatable->val('full_contents');
			$output = $pageSucker->renderHtml($output, DATAFACE_SITE_HREF.'?-action=swete_job_serve_content&job_id='.$job->val('job_id').'&url_hash=');
			//$output = $jobO->translateHtml($output, unserialize($job->val('previous_translations')));
			
		}else if ($translation == "previous"){
			$output = $translatable->val('full_contents');
			$output = $pageSucker->renderHtml($output, DATAFACE_SITE_HREF.'?-action=swete_job_serve_content&job_id='.$job->val('job_id').'&url_hash=');
			$output = $jobO->translatePreviousHtml($output, unserialize($job->val('previous_translations')));
			
		}else if ($translation == "new"){
			$output = $translatable->val('full_contents');
			$output = $pageSucker->renderHtml($output, DATAFACE_SITE_HREF.'?-action=swete_job_serve_content&job_id='.$job->val('job_id').'&url_hash=');
			$output = $jobO->translateHtml($output, unserialize($job->val('previous_translations')));
			
		}else{
			throw new Exception("Invalid translation parameter ".$translation);
		}
		
		
		
		header('Content-Length: '.strlen($output));
		header('Content-type: text/html; charset="UTF-8"');
		
		echo $output;
		
	}
}