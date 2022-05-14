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
//require <swete/PageApprover.js>
//require <xatajax.core.js>
(function(){
	var $ = jQuery;
	var getSelectedIds = XataJax.load('XataJax.actions.getSelectedIds');
	var PageApprover = XataJax.load('swete.PageApprover');
			
	registerXatafaceDecorator(function(node){
	
		$('.approve-selected-pages-action > a', node).click(function(){

			try {
				var resultList = $('.resultList');
				var selectedIds = getSelectedIds(resultList, false);
				console.log(selectedIds);
				if ( selectedIds.length == 0 ){
					alert("No webpages were selected");
					return false;
				
				}else {
					
					var approver = new PageApprover({
						recordIds: selectedIds
					});
					
					approver.dialog({
						callback: function(res){
							console.log(res);return;
							try {
								if ( res.code == 200 ){
									var msg = 'Approved '+res.success+' pages.  Skipped '+res.failed+' pages.';
									window.location.reload();
									return;
								} else {
									throw "Failed to perform action.  See error log for details.";
								}
							} catch (e){
								alert(e);
							}
						
						}
					});
					
				}
			} catch (e){
				alert(e);
			}
				
			return false;
		
		});
	});
	
	
})();