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
	var swete = XataJax.load('swete');
	swete.PageApprover = PageApprover;
	function PageApprover(o){
	
		if ( typeof(o) == 'undefined' ) o = {};
		$.extend(this, o);
	
	}
	
	(function(){
		$.extend(PageApprover.prototype, {
			recordIds: [],
			comments: '',
			approve: approve,
			dialog: dialog
		
		});
		
		function approve(callback){
			var self = this;
		
			var q = {
				'-action': 'swete_approve_pages',
				'--selected-ids': this.recordIds.join("\n")
			};
			
			$.post(DATAFACE_SITE_HREF, q, function(res){
				if ( typeof(callback) == 'function' ) callback.call(self, res);
			
			});
			
		}
		
		function dialog(o){
			if ( this.recordIds.length == 0 ){
				alert('There are currently no pages selected to approve.  Please select some pages and try again.');
				return;
			}
			var callback = o.callback;
			delete o.callback;
			var self = this;
			if ( typeof(o) == 'undefined' ) o = {};
			var dlg = $('<div>').addClass('page-approver-dialog').html(@@(swete/PageApprover/dialog.html));
			$('textarea.comments', dlg)
				.val(self.comments)
				.change(function(){
					self.comments = $(this).val();
				});
			
			o.buttons = {
				'OK': function(){
					self.approve(callback);
				},
				'Cancel': function(){
					$(this).dialog('close');
				}
			};
			o.title = "Approve "+this.recordIds.length+" Webpages";
			o.width = Math.min($(window).width()*0.75, 500);
			o.height = Math.min($(window).height()*0.75, 300);
			$(dlg).dialog(o);
		
		}
		
		
	})();
	
})();