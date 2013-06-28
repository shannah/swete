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
	swete.PageImporter = PageImporter;
	
	
	/**
	 * @class
	 * @description Imports webpages into a site.
	 * @name PageImporter
	 * @memberOf swete
	 * @property {int} websiteId The settings ID of the site to load.
	 * @property {String} startingPoint The URL of the starting point for the crawl.  (Optional)  Will default to the site root if omitted.
	 * @property {int} depth The depth of the crawl.  A depth of 1 means that only the starting point will be loaded.  2 means that the 
	 *	starting point and all pages within the site that are linked by the starting point will be loaded. Etc...  This is optional.  If
	 *	omitted, the default value is 4 - and decided server side.
	 * @property {int[]} addedIds A read-only property that will be set after an import is complete.  This is an array of webpage_ids that were
	 *	added.
	 * @property {int[]} updatedIds A read-only property that will be set after an import is complete.  This is an array of webpages_ids that
	 *	 were updated but not added because they already existed in the database.
	 * @property {String} message A read-only property that is set after an import is complete.  This will contain a status message about the import.
	 * @property {boolean} error A read-only property that is set after an import is complete to indicate whether or not an error occurred.
	 *
	 */
	function PageImporter(/**Object*/ o){
	
		
		this.websiteId = null;
		
		
		this.startingPoint = null;
		this.depth = 4;
		this.addedIds = [];
		this.updatedIds = [];
		this.message = null;
		this.error = null;
		$.extend(this, o);
		
	
	}
	
	$.extend(PageImporter.prototype, {
	
		doImport: doImport
	});
	
	/**
	 * @function
	 * @name doImport
	 * @memberOf swete.PageImporter#
	 * @param {Function} callback A callback function that will be called after the request is complete.  This 
	 *	method will be called with 'this' context being the PageImporter object.  It will be called regardless
	 *	of whether the import succeeded.
	 *
	 * @example
	 * var importer = new PageImporter({
	 *		websiteId: 20,
	 * });
	 * importer->doImport(function(){
	 * 		if ( this.error ){
	 *          alert(this.message);
	 *      } else {
	 *          alert('Added ids '+this.addedIds);
	 * });
	 */
	function doImport(callback){
		if ( typeof(callback) == 'undefined' ) callback = function(){};
		
		var q = {
		
			'-action': 'swete_import_webpages',
			'-table': 'webpages',
			'website_id': this.websiteId
		};
		
		if ( this.depth ) q['--depth'] = this.depth;
		if ( this.startingPoint ) q['--startingPoint'] = this.startingPoint;
		
		var importer = this;
		$.post(DATAFACE_SITE_HREF, q, function(res){
		
			
			try {
				if ( res.code == 200 ){
					importer.addedIds = res.addedIds;
					importer.updatedIds = res.updatedIds;
					importer.message = res.message;
					importer.error = false;
					callback.call(importer);
				} else {
					
					
					if ( res.message ){
						
						throw res.message;
					} else {
						throw 'Failed to import pages due to an unspecified server error.';
					}
				}
			
			} catch (e){
				importer.addedIds = [];
				importer.updatedIds = [];
				importer.message = e;
				importer.error = true;
				callback.call(importer);
				
			
			}
			
		});
	
	}
	
	
	
	
})();