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
//require <swete/JobApprover.js>

(function(){

	var $ = jQuery;
	
	function doApproval(approver){
		
		//Dialog with progress indicator
		var dlg = $('<div>')
			.append($('<div><img src="'+DATAFACE_URL+'/images/progress.gif"/>  Please wait...</div>'))
			.dialog({
				title: 'Approval In Progress',
				modal: true
			});
			
		approver.doApprove(function(){
			dlg.dialog('close');
			
			if ( this.error ){
				alert(this.message);
				
			} else {
				
				window.location.search ='-action=browse&-table=jobs&&-cursor=1&-skip=0&-limit=30&-mode=list&-recordid='+approver.recordId+'&--msg='+'The job was successfully approved.';
				
				return;
			}
		});
		
	}
	

    $(document).ready(function(){
    
    	var JobApprover = XataJax.load('swete.JobApprover');
    
    	$('li.swete_approve_job > a').click(function() {
    	
				var currRecordId = $('meta#xf-meta-record-id').attr('content');
				var currUser = $('meta#xf-meta-username').attr('content');
				
				var approver = new JobApprover({
					recordId: currRecordId,
					username: currUser
				});
				
				approver.doConfirm(function(){
					
					if ( this.error ){
					
						alert(this.message);
						
					} else if (this.confirmed){
					
						doApproval(this);
						
					} else if (this.details){
					
						//display details - untranslated phrases
						
						this.displayDetailsDialog( function(){
						
							if ( this.error ){
							
								alert("Error displayDetailsDialog"+ this.message);
								
							} else if (this.confirmed){
							
								doApproval(this);
							}
						});
						
					
					}
						
				});
				
				
				
				
				return false;
				
				
		});
	
	});

})();