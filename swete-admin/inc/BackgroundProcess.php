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
/**
 * @brief An abstract class for a process that should be run in the background.  
 *
 * The idea is that subclasses will be serialized and stored in the database (in the 
 * background_processes table.)
 */
require_once 'inc/SweteDb.class.php';
class BackgroundProcess {

	private $processId;
	private $clean = false;
	
	

	
	public function isClean(){
		return $this->clean;
	}
	
	public static function runProcess($id){
		$res = SweteDb::q("select * from background_processes where process_id='".addslashes($id)."'");
		if ( mysql_num_rows($res) == 0 ) throw new Exception("Process ".$id." could not be found.");
		$row = mysql_fetch_assoc($res);
		@mysql_free_result($res);
		$classname = basename($row['process_class']);
		if ( !class_exists($classname) ){
			$file = substr($classname, strlen('BackgroundProcess_'));
			@include 'inc/BackgroundProcess/'.$file.'.php';
			if ( !class_exists($classname) ){
				throw new Exception("Failed to load class $classname for process $id because the file $file could not be found.");
			}
			
		}
		$obj = unserialize($row['process_data']);
		$obj->start();
	}
	
	
	public static function getRunningProcessInfo(){
		$username = '';
		$user = SweteTools::getUser();
		if ( $user ) $username = $user->val('username');
		
		$res = df_q("select process_id, complete, running, time_started, time_finished, error, error_message, status_message, status_current_position, status_total from background_processes where 
			username='".addslashes($username)."' and
			running=1
			order by time_started desc
			limit 1");
		$row = mysql_fetch_object($res);
		@mysql_free_result($res);
		return $row;
	}
	
	
	public static function getRecentProcessInfo($limit=10){
		$username = '';
		$user = SweteTools::getUser();
		if ( $user ) $username = $user->val('username');
		$out = array();
		$res = df_q("select process_id, complete, running, time_started, time_finished, error, error_message, status_message, status_current_position, status_total from background_processes where 
			username='".addslashes($username)."'
			order by time_started desc
			limit ".intval($limit));
		$out[] = mysql_fetch_object($res);
		@mysql_free_result($res);
		return $out;
	}
	
	
	public static function runProcesses(){
		$username = '';
		$user = SweteTools::getUser();
		if ( $user ) $username = $user->val('username');
		
		$res = SweteDb::q("select process_id, `running` from background_processes where complete=0 and error=0 and username='".addslashes($username)."' order by `running` desc, `error` asc");
		while ($row = mysql_fetch_row($res) ){
			if ( $row[1] ) return false;
			else {
				try {
					self::runProcess($row[0]);
				} catch (Exception $ex){
					error_log("Failed to run process ".$row[0].": ".$ex->getMessage());
				}
			}
		}
		return true;
	
	}
	

	public function save(){
		
		$username = '';
		$user = SweteTools::getUser();
		if ( $user ) $username = $user->val('username');
		
		$res = SweteDb::q("insert into background_processes (`complete`,`running`,`time_started`,`time_finished`,`error`, `error_message`, `process_class`,`username`) values
			(0,0,NULL,NULL,0,NULL,'".addslashes(get_class($this))."','".addslashes($username)."')");
			
		$this->processId = mysql_insert_id(df_db());
		SweteDb::q("update background_processes set process_data='".addslashes(serialize($this))."' where process_id='".addslashes($this->processId)."'");
		return $this->processId;
	}
	
	public function getProcessId(){
		return $this->processId;
	}


	public function start(){
		$username = '';
		$user = SweteTools::getUser();
		if ( $user ) $username = $user->val('username');
		
	
		$res = SweteDb::q("select running from background_processes where running=1 and username='".addslashes($username)."' limit 1");
		if ( mysql_num_rows($res) > 0 ){
			// There is already a process running... we'll bow out.
			return false;
		}
		
		@mysql_free_result($res);
		$res = SweteDb::q("update background_processes set running=1, time_started=NOW() where process_id='".addslashes($this->processId)."'");
		if ( mysql_affected_rows(df_db()) == 0 ){
			throw new Exception("Cannot run process ".$this->processId." because it could not find itself in the background_processes table.");
		}
		
		register_shutdown_function(array($this, 'cleanup'));
		
		$this->clean = false;
		$this->run();
		SweteDb::q("update background_processes set complete=1, running=0, time_finished=NOW() where process_id='".addslashes($this->processId)."'");
		$this->cleanup();
		return true;
		
		
	}
	
	public function run(){}
	
	
	public function cleanup(){
		if ( $this->clean ) return;
		$this->clean = true;
		$res = SweteDb::q("select complete from background_processes where process_id='".addslashes($this->processId)."'");
		if ( mysql_num_rows($res) == 0 ){
			error_log("Problem with background process ".$this->processId.".  It could not be found in the table during cleanup.");
		} else {
			list( $complete ) = mysql_fetch_row($res);
			@mysql_free_result($res);
			if ( !$complete ){
			
				SweteDb::q("update background_processes set running=0, error=1, error_message='Process exited without completing.  See error log.' where process_id='".addslashes($this->processId)."'");
				
			} else {
				//SweteDb::q("update background_processes set complete=1, running=0, time_finished=NOW() where process_id='".addslashes($this->processId)."'");
				
			}
		}
	}
	
	
}