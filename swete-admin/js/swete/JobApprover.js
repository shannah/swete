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
	swete.JobApprover = JobApprover;
	
	
	/**
	 * @class
	 * @description Approves a job.
	 * @name JobApprover
	 * @memberOf swete
	 * @property {int} recordId The id of the job to be approved.
	 * @property {array} stats Statistics about translations in this job, to display to user for confirmation.
	 * @property {String} message A read-only property that is set after approval is complete.  This will contain a status message.
	 * @property {boolean} error A read-only property that is set after an approval is complete to indicate whether or not an error occurred.
	 *
	 */
	function JobApprover(/**Object*/ o){
		
		this.recordId = null;
		this.stats = null;
		this.message = null;
		this.error = null;
		this.username = null;
		$.extend(this, o);
		
	
	}
	
	$.extend(JobApprover.prototype,
		{doConfirm: doConfirm },
		{doApprove: doApprove},
		{displayConfirmDialog: displayConfirmDialog},
		{displayDetailsDialog: displayDetailsDialog});
	
	/**
	 *
	 *
	*/
	function doConfirm(callback){
		if ( typeof(callback) == 'undefined' ) callback = function(){};
		
		var q = {
		
			'-action': 'swete_job_statistics',
			'-table': 'jobs',
			'-record-id': this.recordId
		};
		
		var approver = this;
		$.post(DATAFACE_SITE_HREF, q, function(res){
		
			try {
				if ( res.code == 200 ){
					approver.stats = res.stats;
					approver.message = res.message;
					approver.error = false;
					approver.displayConfirmDialog(callback);
					
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
	
	function displayConfirmDialog(callback){
	
		var approver = this;
	
		var statsContent = '<div>Approval will apply the translations to the site\'s translation memories</div>'+
				 '<div><ol>'+
				 '<li>Words in the job: '+approver.stats.words+'</li>'+
				 '<li>Phrases in the job: '+approver.stats.phrases+'</li>'+
				 '<li>Words translated: '+approver.stats.wordsTranslated+'</li>'+
				 '<li>Phrases translated: '+approver.stats.phrasesTranslated+'</li>'+
				 '<li>Words not translated: '+approver.stats.wordsNotTranslated+'</li>'+
				 '<li>Phrases not translated: '+approver.stats.phrasesNotTranslated+'</li>'+
				 '</ol></div>';
				
		//a confirmation dialog with translation stats
		var statsDlg = $('<div>')
			.append($(statsContent))
			.dialog({
				title: 'Approve Job',
				modal: true,
				stack: false,
				width: 400,
				close: function() {
					 $(this).dialog('destroy');
					 $(this).remove();
				}
				
			});
		
		var buttons = [
			{
				text: "Approve",
				click: function() {
					$(this).dialog("close");
					approver.confirmed = true;
					callback.call(approver);
				}
			},
			
			{
				text: "Cancel",
				click: function() {
					$(this).dialog("close");
					approver.confirmed = false;
					approver.details = false;
					callback.call(approver);
				}
			}
			
		];
		
		
		if (approver.stats.phrasesNotTranslated >0){
		
			buttons.unshift(
				{
					text: "Show Details",
					click: function() {
						approver.details = true;
						callback.call(approver);
					}
				}
			)
		
		}
			
		
		statsDlg.dialog( "option", "buttons", buttons );
		
	}
	
	function displayDetailsDialog(callback){
	
		var approver = this;
	
		//get the content from action swete_review_translations
		var q = {
		
			'-action': 'swete_review_translations',
			'-recordid': this.recordId,
			'-isDialog': true
		};

		$.post(DATAFACE_SITE_HREF, q, function(res){
		
				
			//display a confirmation dialog with content
			var dlg = $('<div>')
				.append($(res))
				.dialog({
					title: 'Translations',
					modal: true,
					stack: true,
					width: 800,
					close: function() {
					 $(this).dialog('destroy');
					 $(this).remove();
					}
				});
			
			//actions for filter by phrase & status
			decorateXatafaceNode($('th'));
		
		});
	
	}
	
	/**
	 * @function
	 * @name doApprove
	 * @memberOf swete.JobApprover#
	 * @param {Function} callback A callback function that will be called after the request is complete.  This 
	 *	method will be called with 'this' context being the JobApprover object.  It will be called regardless
	 *	of whether the approval succeeded.
	 *
	 * @example
	 * var compiler = new JobApprover({
	 *		recordId: 20,
	 * });
	 * approver->doApprove(function(){
	 * 		if ( this.error ){
	 *          alert(this.message);
	 *      } else {
	 *          alert("Success: " + this.message);
	 * });
	 */
	function doApprove(callback){
		if ( typeof(callback) == 'undefined' ) callback = function(){};
		
		var q = {
		
			'-action': 'swete_approve_job',
			'-table': 'jobs',
			'-record-id': this.recordId,
			'-username' : this.username
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
						throw 'Failed to approve job due to an unspecified server error.';
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