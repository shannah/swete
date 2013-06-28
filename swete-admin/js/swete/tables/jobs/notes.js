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
(function(){

	var $ = jQuery;
	
	
	
	$(document).ready(function(){
	
		//set the stylesheet
    	var link = $("<link>");
    	link.attr({type: 'text/css', rel: 'stylesheet', href: 'css/swete/forms/jobs/notes.css'});
    	$("head").append( link );
    	
		//$.require("css/swete/forms/jobs/notes.css");
		
		registerXatafaceDecorator(function(){

			//table of job notes / messages	
			$('table.job-messages').each(function(){
		
				var inboxTable = this;
				
				$('tr.job-message-data', inboxTable).each(function(){
					
					var messageRow = this;
					
					//SHOW-HIDE: show or hide the full message contents, in a new row, with ajax slide down
					$("td.show-hide", messageRow).click(function() {
						
						//if a message-details row already exists, then remove it
						var foundRow = $(inboxTable).find('tr.slidedown');
						if ( $(foundRow).length){
						
							//HIDE: if the old message-details are for the same message, then remove it and return
							if ($(messageRow).attr('data-note-id')  == $(foundRow).attr('data-note-id') ) {
						
								$(foundRow).remove();
								refreshTable(); // update the date of the read message
								return;
								
							}else{ //SHOW: if the old message-details are for a different message row, then just remove it and continue to add another one
							
								if ( $(foundRow).length ) {
									$(foundRow).remove();
								
								}
							}
							
						}
						
						//create a new message-details row with full message contents, and use slide down to show it
						var numCols = $('table.job-messages').find('tr')[0].cells.length;
						
						var newRow = $('<tr></tr>');
						$(newRow).addClass("slidedown");
						$(newRow).attr("data-note-id", $(messageRow).attr('data-note-id'));
						
						var newTd = $('<td></td>');
						$(newTd).attr('colspan', numCols);
						$(newRow).append($(newTd));
						
						var newDiv = $('<div></div>');
						$(newDiv).addClass("slidedown");
						newDiv.attr('id', "message-details");
						$(newTd).append($(newDiv));
						
						$(messageRow).after($(newRow));
						
						$('#message-details').load(DATAFACE_SITE_HREF, {  '-action': 'swete_message_details', '-job_note_id': $(messageRow).attr('data-note-id')}, function(res){
						
					
						});
						
						$('#message-details').slideDown();
						
						//mark the message as read here
						$.post(DATAFACE_SITE_HREF, {  '-action': 'swete_message_read', '-job_note_id': $(messageRow).attr('data-note-id')}, function(error){
							
							if (error){
								alert(error);
							}else{
								$(messageRow).removeClass('unread').addClass('read');
							}
						});
						
						
					});
					
					//DELETE: delete the selected message row
					$("td.delete", messageRow).click(function() {
						
						var r=confirm("Delete this message?");
						if (r==true){
						
							$.post(DATAFACE_SITE_HREF, {  '-action': 'swete_message_delete', '-job_note_id': $(messageRow).attr('data-note-id')}, function(error){
							
								if (error){
									alert(error);
								}else{
						
									//remove the deleted record's row and slidedown row too (if present)
									var foundRow = $(inboxTable).find('tr.slidedown');
									if ( $(foundRow).length && $(messageRow).attr('data-note-id')  == $(foundRow).attr('data-note-id') ) {
									
											$(foundRow).remove();
									}
											
									$(messageRow).remove();
									
									refreshTable();
								}
							});
						}
						
						
					});
					
					
				});
			
			
			});
		
		});
		
		/*
		** Re-display all the notes in the job-messages table
		** and register all the event handlers again.
		*/
		var refreshTable = function(jobId){
			var jobId = $('table.job-messages').attr('data-job-id');
			var jobTable = $('table.job-messages[data-job-id='+jobId+']');
			
			$(jobTable).load(DATAFACE_SITE_HREF, {  '-action': 'swete_messages', '-job_id': jobId }, function(res){
				decorateXatafaceNode($(jobTable)); //re-decorate events for the table rows
			});
			
		}
		
		
		//handler for the new note
		$('button.add_note').click(function() {
			
			$.post(DATAFACE_SITE_HREF, {  '-action': 'swete_message_add', '-content' :  $('textarea.new_note').val(),
					'-job_id': $('textarea.new_note').attr('data-job-id') }, function(error){
					
					if (error){
						alert(error);
					}else{
					
						$('textarea.new_note').val("");
						refreshTable($('textarea.new_note').attr('data-job-id'));
					}
			});
			
			
		});
		
		
		
		
	});
	
	

	
})();