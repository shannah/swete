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
	swete.JobCompiler = JobCompiler;
	
	
	/**
	 * @class
	 * @description Compiles a job.
	 * @name JobCompiler
	 * @memberOf swete
	 * @property {int} recordId The id of the job to be compiled.
	 * @property {String} message A read-only property that is set after an compile is complete.  This will contain a status message.
	 * @property {boolean} error A read-only property that is set after an import is complete to indicate whether or not an error occurred.
	 *
	 */
	function JobCompiler(/**Object*/ o){
		
		this.recordId = null;
		this.message = null;
		this.error = null;
		$.extend(this, o);
		
	
	}
	
	$.extend(JobCompiler.prototype, {
	
		doCompile: doCompile
	});
	
	/**
	 * @function
	 * @name doCompile
	 * @memberOf swete.JobCompiler#
	 * @param {Function} callback A callback function that will be called after the request is complete.  This 
	 *	method will be called with 'this' context being the JobCompiler object.  It will be called regardless
	 *	of whether the compile succeeded.
	 *
	 * @example
	 * var compiler = new JobCompiler({
	 *		recordId: 20,
	 * });
	 * compiler->doCompile(function(){
	 * 		if ( this.error ){
	 *          alert(this.message);
	 *      } else {
	 *          alert("Success: " + this.message);
	 * });
	 */
	function doCompile(callback){
		if ( typeof(callback) == 'undefined' ) callback = function(){};
		
		var q = {
		
			'-action': 'swete_compile_job',
			'-table': 'jobs',
			'-record-id': this.recordId
		};
		
		var compiler = this;
		$.post(DATAFACE_SITE_HREF, q, function(res){
		
			
			try {
				if ( res.code == 200 ){
					compiler.message = res.message;
					compiler.error = false;
					callback.call(compiler);
				} else {
					
					if ( res.message ){
						throw res.message;
					} else {
						throw 'Failed to compile job due to an unspecified server error.';
					}
				}
			
			} catch (e){
				compiler.message = e;
				compiler.error = true;
				callback.call(compiler);
			}
			
		});
	
	}
	
	
	
	
})();