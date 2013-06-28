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
//require <swete/Website.js>
//require <xataface/IO.js>
(function(){
	var $ = jQuery;
	var Website = XataJax.load('swete.Website');
	var IO = XataJax.load('xataface.IO');
	
	(function(){
		$.extend(Website.prototype, {
			load: load,
			_loading: false,
			ready: ready,
			_ready: false,
			_readyCallbacks: [],
			refresh: refresh
		});
		
		function load(callback){
			if ( this._loading ) return;
			this._loading = true;
			var self = this;
			IO.load({website_id: this.website_id, '-table': 'websites'}, function(res){
				self.setValues(res);
				if ( typeof(callback) == 'function' ) callback.call(self);
				self._loading = false;
				while ( self._readyCallbacks.length > 0 ){
					self._readyCallbacks.shift().call(self);
				}
				self._ready = true;
			});
		
		}
		
		
		function ready(callback){
			var self = this;
			if ( !this._ready ){
				this._readyCallbacks.push(callback);
			} else {
				callback.call(this);
			}
		}
		
		function refresh(callback){
			this._loading = false;
			this._ready = false;
			this.load(callback);
		}
		
	})();
})();