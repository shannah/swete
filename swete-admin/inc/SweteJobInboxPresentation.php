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
class SweteJobInboxPresentation {

	public function tableContent($messageList){
	
		if (count($messageList)==0){
		
			//no rows
			$content = "";
			
			
		}else{
	
			$content = 
					'<tr>
						<th>Note</th>
						<th>Date Posted</th>
						<th>Posted By</th>
						<th>Read On</th>';
						
			$content .= '<th></th>'; //delete column
			
					
			$content .= '		<th></th>
				 </tr>';
			
			foreach( $messageList as $message ){
				
				if ($message->read){
					$tr_class="read";
				}else{
					$tr_class = "unread";
				}
			
				$content .= '<tr class="job-message-data '.$tr_class.'" data-note-id='.$message->note_id.'>
					<td class="job-message-content">'.$message->note_content.'</td>
					<td>'.$message->date_posted.'</td>
					<td>'.$message->posted_by.'</td>
					<td class="date-read">'.$message->date_read.'</td>';
					
				
				$jobNote =& df_get_record("job_notes", array('JobNoteId'=>$message->job_note_id));
				if ($jobNote->checkPermission('delete')){
					$content .= '<td class="delete message-action">Delete</td>';
				}else{
					$content .= '<td></td>';
				}
					
				$content .= '<td class="show-hide message-action">Show/Hide</td></tr>';
				
			}
		}
		
		return $content;

	}
}