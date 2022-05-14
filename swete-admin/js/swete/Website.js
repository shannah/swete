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
	swete.Website = Website;
	
	function Website(o){
		if ( typeof(o) == 'undefined') o = {};
		$.extend(this, o);
	}
	
	(function(){
		
		$.extend(Website.prototype, {
			website_id: null,
			getRecordId: getRecordId,
			vals: {},
			setValue: setValue,
			setValues: setValues,
			getValue: getValue,
			getValues: getValues
		});
		
		
		function getRecordId(){
			if ( this.website_id == null ) throw "Website ID is not set, but is required to get the record ID";
			return 'websites?website_id='+this.website_id;
		}
		
		function setValues(vals){
			var self = this;
			$.each(vals, function(k,v){
				self.setValue(k,v);
			});
		}
		
		function setValue(k,v){
		
			var old = this.vals[k];
			this.vals[k] = v;
			if ( v != old ){
				$(this).trigger('propertyChanged', {propertyName: k, oldValue: old, newValue: v});
			}
		}
		
		function getValue(k){
			return this.vals[k];
		}
		
		function getValues(){
			return this.vals;
		}
		
	})();
	
})();