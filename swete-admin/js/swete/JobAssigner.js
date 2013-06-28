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
//require <xatajax.core.js>
(function(){
	
	var $ = jQuery;
	
	/**
	 * @namespace
	 * @name swete
	 */
	var swete = XataJax.load('swete');
	swete.JobAssigner = JobAssigner;
	
	
	/**
	 * @class
	 * @description Approves a job.
	 * @name JobAssigner
	 * @memberOf swete
	 * @property {int} recordId The id of the job to be approved.
	 * @property {array} stats Statistics about translations in this job, to display to user for confirmation.
	 * @property {String} message A read-only property that is set after approval is complete.  This will contain a status message.
	 * @property {boolean} error A read-only property that is set after an approval is complete to indicate whether or not an error occurred.
	 *
	 */
	function JobAssigner(/**Object*/ o){
		
		this.recordId = null;
		this.message = null;
		this.error = null;
		this.username = null;
		$.extend(this, o);
		
	
	}
	
	$.extend(JobAssigner.prototype,
		{doGetUser: doGetUser},
		{doAssign: doAssign});
	
	/**
	 * @class
	 * @description Display a modal dialog, to select the user to assign the job to
	*/
	function doGetUser(callback){
		var assigner = this;
		
		//get the list of users added to the job
		var q = {
		
			'-action': 'swete_job_users',
			'-record-id': this.recordId
		};

		$.post(DATAFACE_SITE_HREF, q, function(res){
		
			try {
				if ( res.code != 200 ){
					
					if ( res.message ){
						throw res.message;
					} else {
						throw 'Failed to retrieve job users due to an unspecified server error.';
					}
				}
			
			} catch (e){
				callback.call(e);
			}
		
			//display modal dialog with list of users
			var dlgContent = '<div>Assign the job to: <select class="job_users">';
			for(var i=0;i<res.users.length;i++){
				dlgContent += '<option value="'+res.users[i]+'">'+res.users[i]+'</option>';
			}
			dlgContent +='</select></div>';
					
			var dlg = $('<div>')
				.append($(dlgContent))
				.dialog({
					title: 'Assign Job',
					modal: true,
					stack: false,
					width: 400,
					close: function() {
						 $(this).dialog('destroy');
						 $(this).remove();
					},
					buttons: { "Assign": function() {
						assigner.username=$('select.job_users').val();
						$(this).dialog("close");
						callback.call();
					} }
					
				});
		});
		
	
	}
	
	
	/**
	 *
	 *
	*/
	function doAssign(callback){
		if ( typeof(callback) == 'undefined' ) callback = function(){};
		
		var q = {
		
			'-action': 'swete_assign_job',
			'-table': 'jobs',
			'-record-id': this.recordId,
			'-assign-to-user': this.username
		};
		
		var approver = this;
		$.post(DATAFACE_SITE_HREF, q, function(res){
		
			try {
				if ( res.code == 200 ){
					approver.message = res.message;
					approver.error = false;
					callback.call(approver);
				} else {
					
					if ( res.message ){
						throw res.message;
					} else {
						throw 'Failed to retrieve job statistics due to an unspecified server error.';
					}
				}
			
			} catch (e){
				approver.message = e;
				approver.error = true;
				callback.call(approver);
			}
					
		});	
		
	}
	
	
	
	
})();