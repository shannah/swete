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
    	link.attr({type: 'text/css', rel: 'stylesheet', href: 'css/swete/forms/jobs/webpages.css'});
    	$("head").append( link );
	
		registerXatafaceDecorator(function(){

			//table of job notes / messages	
			$('table.job-webpages').each(function(){
		
				var webpagesTable = this;
				var jobId = $('table.job-messages').attr('data-job-id');
				
				$('tr', webpagesTable).each(function(){
					var webpageRow = this;
					
					//REMOVE: remove the webpage from the job
					$("td.remove", webpageRow).click(function() {
						
						var r=confirm("Remove this webpage including all strings from the translation job?");
						if (r==true){
							
							$.post(DATAFACE_SITE_HREF, {  '-action': 'swete_remove_webpage_from_job', '-webpage_id': $(webpageRow).attr('data-webpage-id'), '-job_id': jobId }, function(error){
							
								if (error){
									alert(error);
								}else{
									$(webpageRow).remove();
									refreshTable();
								}
							});
						}
						
					});
					
				});
			
			});
		
		});
		
		/*
		** Re-display all the webpages in the job-webpages table
		** and register all the event handlers again.
		*/
		var refreshTable = function(jobId){
			var webpageId = $('table.job-webpages').attr('data-webpage-id');
			var jobTable = $('table.job-webpages[data-webpage-id='+webpageId+']');
			
			$(jobTable).load(DATAFACE_SITE_HREF, {  '-action': 'swete_messages', '-job_id': jobId }, function(res){
				decorateXatafaceNode($(jobTable)); //re-decorate events for the table rows
			});
			
		}
	
	});
	
	

	
})();