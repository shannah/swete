/*
 * Xataface Translation Memory Module
 * Copyright (C) 2011  Steve Hannah <steve@weblite.ca>
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Library General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Library General Public License for more details.
 * 
 * You should have received a copy of the GNU Library General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA  02110-1301, USA.
 *
 */
//require <jquery.packed.js>
(function(){
	
	if ( typeof(window.xataface) == 'undefined' ) window.xataface = {};
	if ( typeof(window.xataface.modules) == 'undefined' ) window.xataface.modules={};
	if ( typeof(window.xataface.modules.tm) == 'undefined' ) window.xataface.modules.tm = {};
	
	window.xataface.modules.tm.TranslationMemory = TranslationMemory;
	
	
	var $ = jQuery;
	
	function TranslationMemory(src, dst, id){
		this.sourceLanguage = src;
		this.destinationLanguage = dst;
		this.unloadedDictionary = {};
		this.loadedDictionary = {};
		this.unsaved = {};
		this.saving = {};
		this.id = id;
		if ( typeof(id) == 'undefined' ) this.id = null;
		
		
	
	}
	
	
	TranslationMemory.prototype.load = TranslationMemory_load;
	TranslationMemory.prototype.get = TranslationMemory_get;
	TranslationMemory.prototype.add = TranslationMemory_add;
	TranslationMemory.prototype.addAll = TranslationMemory_addAll;
	TranslationMemory.prototype.addTranslation = TranslationMemory_addTranslation;
	TranslationMemory.prototype.save = TranslationMemory_save;
	TranslationMemory.prototype.autosave = TranslationMemory_autosave;
	
	
	function TranslationMemory_add(str){
		this.unloadedDictionary[str] = null;
		
	}
	
	function TranslationMemory_addAll(vals){
		var self = this;
		$.each(vals, function(){
			self.add(this);
		});
	}
	
	function TranslationMemory_addTranslation(key,val){
		if ( this.loadedDictionary[key] == val ){
			return false;
		}
		this.unsaved[key] = val;
		this.loadedDictionary[key] = val;
		return true;
	}
	
	
	
	function TranslationMemory_load(callback){
		var self = this;
		var strings = [];
		$.each(this.unloadedDictionary, function(key,val){
			strings.push(key);
		});
		
		var q = {
			'--strings[]': strings,
			'-action': 'tm_get_strings',
			'--source': this.sourceLanguage,
			'--dest': this.destinationLanguage
		};
		if ( this.id ) q['--tmid'] = this.id;
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
			try {
				
				if ( res.code != 200 ){
					if ( res.message ) throw res.message;
					else throw 'Unspecified server error loading translation memory.  See server log for details.';
					
				} else {
				
					$.each(res.strings, function(key,val){
						delete self.unloadedDictionary[key];
						self.loadedDictionary[key] = val;
					});
					
					if ( typeof(callback) == 'function' ) callback({strings:res.strings});
					$(self).trigger('afterLoad', {strings:res.strings});
				}
				
			} catch (e){
				callback({error: e});
			}
		});
		
		return true;
	
	}
	
	
	function TranslationMemory_autosave(enabled){
		if ( enabled && this.intervalID ) return; // it's already enabled
		else if ( !enabled && !this.intervalID ) return ; // it's already off
		var self = this;
		if ( enabled ){
			this.intervalID = setInterval(
				function(){
					
					var saving = $('<div></div>')
						.addClass('xf-saving-in-progress')
						.text('Saving Translation Memory...')
						.get(0)
						;
					
				
					var res = self.save(function(){
						$(saving).remove();
					});
					if ( res ){
						$('body').append(saving);
					}
				}, 
				1000
			);
				
		}
		if ( this.intervalID ) return
	}
	
	function TranslationMemory_save(callback){
		if ( $.isEmptyObject(this.unsaved) ) return false; // If there is nothing to save
		if ( !$.isEmptyObject(this.saving) ) throw 'Save already in progress';
		var self = this;
		
		var q = {
			'-action': 'tm_add_translations',
			'--source': this.sourceLanguage,
			'--dest': this.destinationLanguage
		
		};
		if ( this.id ) q['--tmid'] = this.id;
		
		var strings = [];
		var translations = [];
		
		
		$.each(this.unsaved, function(key,val){
			strings.push(key);
			translations.push(val);
			self.saving[key] = val;
			delete self.unsaved[key];
		});
		
		q['--strings'] = strings;
		q['--translations'] = translations;
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
		
			try {
				
				if ( res.saved ){
					$.each(res.saved, function(){
						delete self.saving[this];
					});
				}
				
				$.each(self.saving, function(key,val){
					self.unsaved[key] = val;
					delete self.saving[key];
				});
				
				
				
				if ( res.code != 200 ){
					
					
				
					if ( res.message ) throw res.message;
					else throw 'Unspecified server error loading translation memory. See server log for details.';
				} else {
					if ( typeof(callback) == 'function' ) callback(res);
					$(self).trigger('afterSave', res);
				
				}
			} catch (e){
				if ( typeof(callback) == 'function' ) callback(res);
				$(self).trigger('afterSave', res);
			}
		});
		
		return true;
		
		
	}
	
	
	function TranslationMemory_get(string){
		return this.loadedDictionary[string];
	}
	
})();