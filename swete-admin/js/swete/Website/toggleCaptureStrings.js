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
//require <swete/Website/load.js>
//require <xataface/IO.js>
//require <xatajax.core.js>
(function(){
	var $ = jQuery;
	var Website = XataJax.load('swete.Website');
	var IO = XataJax.load('xataface.IO');
	
	(function(){
		
		$.extend(Website.prototype, {
			startCaptureStrings: startCaptureStrings,
			stopCaptureStrings: stopCaptureStrings,
			toggleCaptureStrings: toggleCaptureStrings
		
		});
		
		
		function startCaptureStrings(callback){
		
			this.toggleCaptureStrings(true, callback);
		}
		
		function stopCaptureStrings(callback){
			this.toggleCaptureStrings(false, callback);
		}
		
		
		function toggleCaptureStrings(enabled, callback){
			var self = this;
			
			
			
			var vals = {
				log_translation_misses: enabled?1:0
			};
			
			
			IO.update(this.getRecordId(), vals, function(res){
				if ( typeof(callback) == 'function') callback.call(self, res);
				try {
					if ( res.code == 200 ){
						self.setValues(vals);
						$(self).trigger('toggleCaptureStringsSucceeded', {enabled: enabled, res:res});
					} else {
						throw 'Failed';
					}
				} catch (e){
					$(self).trigger('toggleCaptureStringsFailed', {enabled: enabled, res: res});
				}
			});
			
			
		}
	})();
})();