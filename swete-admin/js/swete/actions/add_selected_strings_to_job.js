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
//require <xatajax.core.js>
(function(){

	var $ = jQuery;
	
	function addStringsToJob(job, selectedIds){
			
		var q = {
			'-table': 'webpages',
			'-action': 'swete_add_selected_strings_to_job',
			'--selected-ids': selectedIds,
			'-job': job
		};
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
			
			var message;
			
			if (res.isNewJob){
				message = "Job "+res.jobId+" was created, and "+res.stringsAdded.length+" strings were added.";
			}else{
				if (res.stringsAdded.length <1){
					message = "No new strings were added. The selected strings were already added to Job "+res.jobId;
				}else{
					message = "Successfully added "+res.stringsAdded.length+" new strings to Job "+res.jobId;
				}
			}
			
			window.location.search ='-table=translation_miss_log&--msg='+message;
			
			
		}, "json");
				
	}
	
	function addStringToJob(job, recordId){
	
		var q = {
			'-table': 'webpages',
			'-action': 'swete_add_string_to_job',
			'-record-id': recordId,
			'-job': job
		};
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
			
			var message;
			
			if (res.isNewJob){
				message = "Job "+res.jobId+" was created, and the string was successfully added.";
			}else{
				if (res.stringAdded=="false"){
					message = "The selected string was already added to Job "+res.jobId;
				}else{
					message = "Successfully added the string to Job " +res.jobId;
				}
			}
			
			window.location.search ='-table=translation_miss_log&-action=browse&-recordid='+recordId+'&--msg='+message;
			
		}, "json");
	}
	
	function getWebsiteFromStrings(selectedIds, recordId, callback){
	
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
	**  
	**	Uses Recordbrowser to select a job, and then adds strings to it:
	**  specified by either an array of selected Ids or a single id.
	**  selectedIds is an array of selected transalation_miss_log record ids
	**  recordId is one transalation_miss_log record id
	**/
	function selectJobandAddStrings(selectedIds, recordId){
		
		getWebsiteFromStrings(selectedIds, recordId, function(res){
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
					//or there is only 1 available job, so the strings will be added it.
					if (selectedIds){
						addStringsToJob(null, selectedIds);
					}else{
						addStringToJob(null, recordId);
					}	
				
				}else{
					
					//user must select a job to add to
					var  username = $('meta#xf-meta-username').attr('content');
					
					//display modal dialog with list of users
					var dlgContent = '<div>Add strings to: <select class="jobs">';
					for(var i=0;i<jobs.length;i++){
						dlgContent += '<option value="'+jobs[i].job_id+'">'+jobs[i].title+'</option>';
					}
					dlgContent += '<option value="new">New Job</option>';
					dlgContent +='</select></div>';
					
							
					var dlg = $('<div>')
						.append($(dlgContent))
						.dialog({
							title: 'Add Strings To Job',
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
									addStringsToJob(selectedJobId, selectedIds);
								}else{
									addStringToJob(selectedJobId, recordId);
								}
								
								}
							}
							
					});
					
						
				}
				
		});
		
		return false;
		
		});
	
	}
	
	
	$(document).ready(function(){
	
		$('li.swete_add_string_to_job > a').click(function() {
    		
    		var currRecordId = $('meta#xf-meta-record-id').attr('content');

    		selectJobandAddStrings(null, currRecordId);
    		return false;
    	});
    	
    	$('li.swete_add_selected_strings_to_job > a').click(function() {
			
			var resultList = $('.resultList'); // The wrapper table has CSS class "resultList"
			var getSelectedIds = XataJax.load('XataJax.actions.getSelectedIds');
			var selectedIds = getSelectedIds(resultList, true);
			
			if ( selectedIds.length == 0 ){
				alert("No strings were selected");
				return false;
			
			}else {
				selectJobandAddStrings(selectedIds);
				return false;
				
			}
		
	});
	
	});

})();