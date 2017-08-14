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
class SweteJobInbox {


	const UNREAD = 1;
	const READ = 2;
	const READ_AND_UNREAD = 3;
	
	const DELETED = 1;
	const UNDELETED = 2;
	const DELETED_AND_UNDELETED = 3;
	

	
	const SORT_DATE_DESC = 1;
	const SORT_DATE_ASC = 2;

	
	/**
	 * @type SweteJob
	 */
	private $job;
	private $username;
	
	
	public function __construct(SweteJob $job, $username){
		$this->job = $job;
		$this->username = $username;
	}
	
	
	/**
	 * @returns SweteJob
	 */
	public function getJob(){ return $this->job;}
	
	/**
	 * @returns string
	 */
	public function getUsername(){ return $this->username;}
	
	
	private function _where($unread = null, $deleted = null){
		if ( !isset($unread) ) $unread = self::READ_AND_UNREAD;
		if ( !isset($deleted) ) $deleted = self::UNDELETED;
		$sql = " where
				(jnr.username='".addslashes($this->username)."' or jnr.username is null)
				and jn.job_id='".addslashes($this->job->getRecord()->val('job_id'))."'
				";
				
		
		
		
		switch ( $unread ){
			case self::UNREAD:
				$sql .= " and jnr.date_read is null"; break;
			case self::READ:
				$sql .= " and jnr.date_read is not null"; break;
		
		}
		
		
		switch ( $deleted ){
			case self::DELETED:
				$sql .= " and jn.deleted = 1"; break;
			case self::DELETED_AND_UNDELETED:
				break;
			default:
				$sql .= " and (jn.deleted is null or jn.deleted = 0 )";
		}

		return $sql;
	
	
	}
	
	/**
	 * @brief Returns the number of messages in the inbox subject to the specified
	 * flags.
	 *
	 * @param int $unread A flag to indicate whether to include previously read messages.  This 
	 *		will take one of:
	 *		- SweteJobInbox::UNREAD
	 *		- SweteJobInbox::READ
	 *		- SweteJobInbox::READ_AND_UNREAD
	 *	Default value is SweteJobInbox::READ_AND_UNREAD
	 *
	 * @param int $deleted Parameter indicating how to handle deleted messages.  This should be 
	 *	one of :
	 *		- SweteJobInbox::DELETED
	 *		- SweteJobInbox::UNDELETED
	 *		- SweteJobInbox::DELETED_AND_UNDELETED
	 *	Default value is SweteJobInbox::UNDELETED
	 *
	 * 
	 * @returns int The number of messages in the inbox with the specified settings.
	 */
	public function getNumMessages($unread = null, $deleted = null){

		if ( !isset($unread) ) $unread = self::READ_AND_UNREAD;
		if ( !isset($deleted) ) $deleted = self::UNDELETED;
		$sql = "select count(*) from 
			job_notes jn
			left join job_notes_read jnr on (jn.job_note_id=jnr.job_note_id and jnr.username='".addslashes($this->getUsername())."')";
			
			
		$sql .= $this->_where($unread, $deleted);
			
		
		$res = SweteDb::q($sql);
		list($num) = xf_db_fetch_row($res);
		@xf_db_free_result($res);
		return $num;
	
	}
	
	
	public function getMessageList( 
				$unread = null, 
				$deleted = null, 
				$sort = null,
				$start = 0, 
				$limit = 30
		){
				
		if ( !isset($unread) ) $unread = self::READ_AND_UNREAD;
		if ( !isset($deleted) ) $deleted = self::UNDELETED;
		if ( !isset($sort) ) $sort = self::SORT_DATE_ASC;
		
		$sql = "select 
			jn.job_note_id as note_id, 
			substring(jn.note_content,1, 100) as note_content, 
			jn.date_posted,
			jn.posted_by,
			jnr.*
			
			from 
			job_notes jn
			left join job_notes_read jnr on (jn.job_note_id=jnr.job_note_id and jnr.username='".addslashes($this->getUsername())."')";
		$sql .= $this->_where($unread, $deleted);
		
		switch ( $sort ){
			case self::SORT_DATE_DESC:
				$sql .= " order by date_posted asc"; break;
			default:
				$sql .= " order by date_posted desc"; break;
		}
		
		$sql .= " limit ".intval($start).", ".intval($limit);
		
		$res = SweteDb::q($sql);
		$out = array();
		while ($row = xf_db_fetch_object($res) ){
			$out[] = $row;
		}
		
		@xf_db_free_result($res);
		return $out;
		
	}
	
	/**
	 * @returns stdObject An object representing a message.  This row is a 
	 * join between the job_notes table and the job_notes_read table.
	 * This method can be called without a SweteJobInbox instance 
	 */
	public function getMessageStatic($jobNoteId, $username){
		$sql = "select 
			jn.job_id,
			jn.job_note_id, 
			jn.note_content,
			jn.date_posted,
			jn.deleted,
			jnr.read,
			jnr.date_read
			
			from 
			job_notes jn
			left join job_notes_read jnr on (jn.job_note_id=jnr.job_note_id and jnr.username='".addslashes($username)."')
			where jn.job_note_id='".addslashes($jobNoteId)."' limit 1
			";
		$res = SweteDb::q($sql);
		if ( xf_db_num_rows($res) == 0 ){
			return null;
		} else {
			$out = xf_db_fetch_object($res);
			@xf_db_free_result($res);
			return $out;
		}
	
	}
	
	
	/**
	 * @returns stdObject An object representing a message.  This row is a 
	 * join between the job_notes table and the job_notes_read table.
	 */
	public function getMessage($jobNoteId){
	
		return self::getMessageStatic($jobNoteId, $this->getUsername());
		
	}
	
	public function setReadStatic($jobNoteId, $username){
		$res = SweteDb::q("select 'read' from job_notes_read where job_note_id='".addslashes($jobNoteId)."' and username='".addslashes($username)."' limit 1");
		$sql = "update job_notes_read set `read`=1, date_read='".addslashes(date('Y-m-d H:i:s'))."' where job_note_id='".addslashes($jobNoteId)."' and `username`='".addslashes($username)."'";
		if ( xf_db_num_rows($res) == 0 ){
			$sql = "insert into job_notes_read (`job_note_id`,`username`,`read`,`date_read`)
				values (
					'".addslashes($jobNoteId)."',
					'".addslashes($username)."',
					1,
					'".addslashes(date('Y-m-d H:i:s'))."')";
		}
		SweteDb::q($sql);
	
	}
	
	public function setRead($jobNoteId){
		self::setReadStatic($jobNoteId, $this->username);
	}
	
	public function setUnread($jobNoteId){
		$sql = "update job_notes_read set `read`=0, `date_read`=NULL where job_note_id='".addslashes($jobNoteId)."' and username='".addslashes($this->username)."'";
		SweteDb::q($sql);
		
	}
	
	
	public function deleteMessage($jobNoteId){
		$res = SweteDb::q("update job_notes set `deleted`=1 where job_note_id='".addslashes($jobNoteId)."'");
		
	}
	
	public function unDeleteMessage($jobNoteId){
		$res = SweteDb::q("update job_notes set `deleted`=0 where job_note_id='".addslashes($jobNoteId)."'");
		
	}
	
	public function addMessage($content){
		
		$note = new Dataface_Record('job_notes', array());
		$note->setValues(array(
			'job_id'=>$this->getJob()->getRecord()->val('job_id'),
			'note_content' => $content,
			'date_posted'=>date('Y-m-d H:i:s'),
			'posted_by' => $this->getUsername()
		
		));
		$res = $note->save();
		if ( PEAR::isError($res) ){
			throw new Exception($res->getMessage(), $res->getCode());
		}
		
		
		return $note;
		
	
	}
	
	
	
	
}