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
//require <jquery.packed.js>
//require <jquery-ui.min.js>
//require-css <jquery-ui/jquery-ui.css>

(function(){

	var $ = jQuery;
	
	function addPagesToJob(job, selectedIds){
			
		var q = {
			'-table': 'webpages',
			'-action': 'swete_add_selected_webpages_to_job',
			'--selected-ids': selectedIds,
			'-job': job
		};
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
		
			var message;
			
			if (res.isNewJob){
				message = "Job "+res.jobId+" was created, and "+res.pagesAdded.length+" webpages were added.";
			}else{
				if (res.pagesAdded.length <1){
					message = "No new webpages were added. The selected webpages were already added to Job "+res.jobId;
				}else{
					message = "Successfully added "+res.pagesAdded.length+" new webpages to Job "+res.jobId;
				}
			}
			
			window.location.search ='-table=webpages&--msg='+message;
			
			
		}, "json");
				
	}
	
	function addPageToJob(job, recordId){
	
		var q = {
			'-table': 'webpages',
			'-action': 'swete_add_webpage_to_job',
			'-record-id': recordId,
			'-job': job
		};
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
			var message;
			
			if (res.isNewJob){
				message = "Job "+res.jobId+" was created, and the webpage was successfully added.";
			}else{
				if (res.pageAdded=="false"){
					message = "The selected webpage was already added to Job "+res.jobId;
				}else{
					message = "Successfully added the webpage to Job " +res.jobId;
				}
			}
			
			window.location.search ='-table=webpages&-action=browse&-recordid='+recordId+'&--msg='+message;
			
		}, "json");
		
	}
	
	function getWebsiteFromWebpages(selectedIds, recordId, callback){
	
		var q = {
			'-table': 'webpages',
			'-action': 'swete_get_website_from_record',
			'-record-id': recordId,
			'-selected-ids' : selectedIds
		};
		
		$.get(DATAFACE_SITE_HREF, q, function(res){
			callback(res);
		});
		
	}
	
	function getAvailableJobs(siteId, callback){
	
		var q = {
			'-table': 'websites',
			'-action': 'swete_get_jobs_for_site',
			'-site-id': siteId,
			'-compiled' : false
		};
		
		$.get(DATAFACE_SITE_HREF, q, function(res){
			callback(res);
		}, "json");
	
	}
	
	/**
	**  Uses Recordbrowser to select a job, and then adds webpages to it:
	**  specified by either an array of selected Ids or a single id.
	**  selectedIds is an array of selected website record ids
	**  recordId is one website record id
	**/
	function selectJobandAddPages(selectedIds, recordId){
		
		getWebsiteFromWebpages(selectedIds, recordId, function(res){
				
				//res is either the website id or an error message
				if (isNaN(parseInt(res)) || parseInt(res)==0){
					alert(res);
					return false;
				}
				
				var websiteId = res;
				
				
				getAvailableJobs(websiteId, function(jobs){
				
					var numJobs = jobs.length;
					if (isNaN(parseInt(numJobs))){
						return false;
					}
					
					if (numJobs<2){
						//No need for user to select a job. Either a new job must be created, 
						//or there is only 1 available job, so the pages will be added it.
						if (selectedIds){
							addPagesToJob(null, selectedIds);
						}else{
							addPageToJob(null, recordId);
						}	
					
					}else{
						
						//user must select a job to add to
						var  username = $('meta#xf-meta-username').attr('content');
						
						//display modal dialog with list of users
						var dlgContent = '<div>Add webpages to: <select class="jobs">';
						for(var i=0;i<jobs.length;i++){
							dlgContent += '<option value="'+jobs[i].job_id+'">'+jobs[i].title+'</option>';
						}
						dlgContent += '<option value="new">New Job</option>';
						dlgContent +='</select></div>';
						
								
						var dlg = $('<div>')
							.append($(dlgContent))
							.dialog({
								title: 'Add Webpages To Job',
								modal: true,
								stack: false,
								width: 400,
								close: function() {
									 $(this).dialog('destroy');
									 $(this).remove();
								},
								buttons: { "Select": function() {
									selectedJobId=$('select.jobs').val();
									$(this).dialog("close");
									if (selectedIds){
										addPagesToJob(selectedJobId, selectedIds);
									}else{
										addPageToJob(selectedJobId, recordId);
									}
									
									}
								}
								
						});
						
							
					}
					
			});
			
		});
		
		return false;
	
	}

    $(document).ready(function(){
    
    $('li.swete_add_webpage_to_job > a').click(function() {
    		
    		var currRecordId = $('meta#xf-meta-record-id').attr('content');

    		selectJobandAddPages(null, currRecordId);
    		return false;
    });
    
	$('li.swete_add_selected_webpages_to_job > a').click(function() {
			
			var resultList = $('.resultList'); // The wrapper table has CSS class "resultList"
			var getSelectedIds = XataJax.load('XataJax.actions.getSelectedIds');
			var selectedIds = getSelectedIds(resultList, true);
			
			if ( selectedIds.length == 0 ){
				alert("No webpages were selected");
				return false;
			
			}else {
				selectJobandAddPages(selectedIds);
				return false;
				
			}
		
	});

});

})();