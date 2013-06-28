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
//require <swete/PageImporter.js>
//require-css <swete/actions/import_webpages.css>




(function(){
	var $ = jQuery;
	$(document).ready(function(){
	
		
		var PageImporter = XataJax.load('swete.PageImporter');
		var website_id = $('fieldset.import-webpage-form').attr('data-swete-settings-id');
		var fieldset = $('fieldset.import-webpage-form');
		
		fieldset.each(function(){
			$('button.submit', fieldset).click(function(){
				
				var importer = new PageImporter({
					websiteId: website_id,
					startingPoint: $('input.startingPoint', fieldset).val(),
					depth: $('input.depth', fieldset).val()
				});
				
				
				
				var dlg = $('<div>')
					.append($('<div><img src="'+DATAFACE_URL+'/images/progress.gif"/>  Please wait...</div>'))
					.dialog({
						title: 'Import In Progress',
						modal: true
					});
					
				//$('body').append(dlg);
				importer.doImport(function(){
					dlg.dialog('close');
					
					if ( this.error ){
						alert(this.message);
						
					} else {
					
						window.location.search = '-action=list&-table=webpages&parent_id==&website_id='+this.websiteId+'&--msg='+encodeURIComponent(this.addedIds.length+' pages added. '+this.updatedIds.length+' pages updated');
						
						return;
					}
				});
				
				
			});
		});
		
		
	});

})();