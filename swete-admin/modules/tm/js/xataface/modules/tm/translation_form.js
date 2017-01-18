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
//require <xataface/modules/tm/string_parser.js>
//require <xataface/modules/tm/translation_memory.js>
(function(){
	
	var $ = jQuery;
	
	
	var StringForm = xataface.modules.tm.StringForm;
	var TranslationMemory = xataface.modules.tm.TranslationMemory;
	
	
	xataface.modules.tm.TranslationForm = TranslationForm;
	xataface.modules.tm.TranslationFormField = TranslationFormField;
	
	/**
	 * @class
	 * @memberOf xataface.modules.tm
	 * @name TranslationForm
	 * @description A class that builds and encapsulates a translation form.
	 *
	 * @param {String} source The source language code of the translation.
	 * @param {String} dest The target language code of the translation/
	 * @param {int} tmid The translation memory id to use.
	 * @property {Array[xataface.modules.tm.TranslationFormField]} The individual fields on this form.
	 * @property {int} tmid The translation memory id that is being used.
	 * @property {xataface.modules.tm.TranslationMemory} translationMemory The translation memory that is
	 * 	used to retrieve and save translations.
	 */
	function TranslationForm(source, dest, tmid){
		var self = this;
		this.fields = [];
		this.tmid = tmid;
		this.translationMemory = new TranslationMemory(source, dest, tmid);
		
		$(this.translationMemory).bind('afterSave', function(evt, res){
			//try {
				if ( res.outTranslations ){
					//alert('here');
					$.each(self.fields, function(){
						var fld = this;
						$.each(fld.stringForm.getFormFields(), function(){
							var subfld = this;
							var orig = $(subfld).attr('data-orig-value');
							//alert(orig);
							//alert(res.outTranslations[orig]);
							if ( res.outTranslations[orig] ){
								$(subfld).removeClass('xf-tm-nomatch');
								$(subfld).addClass('xf-tm-match');
								if ( $(subfld).val() != res.outTranslations[orig] ){
									$(subfld).val(res.outTranslations[orig]);
									$(subfld).trigger('change');
								}
							}
							
						});
					});
				}
			//} catch (e){
			//	alert(e);
			//}
		});
		this.translationMemory.autosave(true);
		$('tr.xf-field-translation').each(function(){
			var fld = new TranslationFormField(source, dest, this, self.translationMemory)
			self.fields.push(fld);
			
			
			
		});
		$.each(this.getStrings(), function(key,val){
			self.translationMemory.add(key);
		});
		
	}
	
	TranslationForm.prototype.getStrings = TranslationForm_getStrings;
	TranslationForm.prototype.load = TranslationForm_load;
	
	/**
	 * @function
	 * @name load
	 * @memberOf xataface.modules.tm.TranslationForm#
	 * @description Loads translations from the translation memory.
	 * @param {Function} callback The callback function to be called when loading is complete.
	 * This is called whether or not the load was successful.
	 */
	function TranslationForm_load(callback){
		var self = this;
		this.translationMemory.load(function(o){
			if ( o.error ){
				if ( typeof(callback) == 'function' ) callback(o);
				$(self).trigger('afterLoad',o);
			} else {
					
				$.each(self.fields, function(){
					var flds = this.stringForm.getFormFields();
					$.each(flds, function(){
						var val = self.translationMemory.get($(this).attr('data-orig-value'));
						if ( !val ) val = '';
						if ( val ){
							$(this).addClass('xf-tm-match');
							$(this).removeClass('xf-tm-nomatch');
							$(this).val(val);
						} else {
							$(this).removeClass('xf-tm-match');
							$(this).addClass('xf-tm-nomatch');
						}
					});
					
					
					
					
				});
				
				if ( typeof(callback) == 'function' ) callback(o);
				$(self).trigger('afterLoad', o);
			}
		});
	
	}
	
	/**
	 * @function
	 * @name getStrings
	 * @memberOf xataface.modules.tm.TranslationForm#
	 * @description Returns an associative array of all strings
	 * and translations in this form.
	 * @returns {Object} Key-value of translations.
	 */
	function TranslationForm_getStrings(){
	
		var strings = {};
		
		$.each(this.fields, function(){
			var mystrings = this.getStrings();
			for ( var i = 0; i<mystrings.length; i++){
				strings[mystrings[i]] = mystrings[i];
			}
		});
		
		return strings;
		
		
	}
	
	/**
	 * @class
	 * @name TranslationFormField
	 * @memberOf xataface.modules.tm
	 * @description A class that encapsulates a single field of a translation form.
	 * @param {String} source The source language code.
	 * @param {String} dest The destination langauge code.
	 * @param {HTMLElement} tr The <tr> tag in the table that stores the content being translated.
	 * @param {xataface.modules.tm.TranslationMemory} tm The translation memory to use for the field.
	 *
	 * @property {String} source The source language of the field.
	 * @property {String} dest The destination language of the field.
	 * @property {HTMLElement} tr The <tr> tag.
	 * @property {String} fieldName The name of the field from the record that is being edited.
	 * @property {String} recordID The recordID of the field that is being edited.
	 * @property {boolean} showButtonBar Whether to show the button bar for this field (the save and cancel buttons).
	 * @property {HTMLElement} textarea The <textarea> field that is used to edit the translation.
	 * @property {xataface.modules.tm.StringForm} stringForm The String form that handles the parsing
	 *	of the strings in the textarea.  If the content of this form field is HTML then it will be 
	 *  parsed down into smaller fields.
	 * @property {xataface.modules.tm.TranslationMemory} translationMemory The translation memory
	 * 	that is being used for the translation (to retrieve and save translations).
	 * @property {HTMLElement} formElement The form element used for submission.  Always use getFormElement()
	 * 	to retrieve this property, since it may be null otherwise.
	 *
	 */
	function TranslationFormField(source, dest, tr, tm){
	
		this.source = source;
		this.dest = dest;
		this.tr = tr;
		this.translationMemory = tm;
		this.fieldName = $(tr).attr('data-xf-fieldname');
		this.recordID = $(tr).parents('.xf-record-translation').first().attr('data-xf-record-id');
		this.showButtonBar = true;
		if ( $(tr).attr('data-xf-hide-button-bar') ) this.showButtonBar = false;
		
		this.textarea = $('<textarea></textarea')
			.val($('td.xf-field-translation-src', tr).text())
			.addClass('xf-hidden-translation-field')
			.get(0)
			;
			
		this.stringForm = StringForm.create(this.textarea);
		this.stringForm.parse();
		
		this.formElement = null;
		
	}
	
	
	
	TranslationFormField.prototype.getStrings = TranslationFormField_getStrings;
	TranslationFormField.prototype.getFormElement = TranslationFormField_getFormElement;
	TranslationFormField.prototype.save = TranslationFormField_save;
	TranslationFormField.prototype.evaluateChanged = TranslationFormField_evaluateChanged;
	
	/**
	 * @function
	 * @name evaluateChanged
	 * @memberOf xataface.modules.tm.TranslationFormField#
	 * @description Checks to see if the contents of the translation for this field have
	 * changed.  If the field has changed it will add the CSS class 'x-field-changed'
	 * to the <tr> tag containing the field.  If it hasn't changed it will remove the CSS
	 * class.
	 * @returns {void}
	 */
	function TranslationFormField_evaluateChanged(){
		var self = this;
		var field = this;
		var existingVal = $('td.xf-field-translation-dest', field.tr).text();
		field.stringForm.pushFields();
		field.stringForm.unparse();
		var tmVal = $(field.stringForm.el).html();
		
		if ( !existingVal ){
			$(self.tr).addClass('xf-field-empty');
			$(self.tr).next('tr.xf-active-translation-field').addClass('xf-field-empty');
		} else {
			$(self.tr).removeClass('xf-field-empty');
			$(self.tr).next('tr.xf-active-translation-field').removeClass('xf-field-empty');
		}
		
		if ( tmVal != existingVal ){
			//alert('We have a change');
			$(self.tr).addClass('xf-field-changed');
			$(self.tr).next('tr.xf-active-translation-field').addClass('xf-field-changed');
		} else {
			$(self.tr).removeClass('xf-field-changed');
			$(self.tr).next('tr.xf-active-translation-field').removeClass('xf-field-changed');
		}
		
	}
	
	/**
	 * @function
	 * @name getStrings
	 * @memberOf xataface.modules.tm.TranslationFormField#
	 * @description Gets all of the parsed strings in this field.  Since this field
	 * is parsed into the individual HTML components, this will return an array of 
	 * strings of the original values (i.e. in the source language).
	 * @returns {Array} An array of strings in the source language.
	 */
	function TranslationFormField_getStrings(){
		var out = [];
		var fields = this.stringForm.getFormFields();
		$.each(fields, function(){
			out.push($(this).attr('data-orig-value'));
			
		});
		return out;
		
	}
	
	/**
	 * @function
	 * @name getFormElement
	 * @memberOf xataface.modules.tm.TranslationFormField#
	 * @description Returns an HTML DOMElement that contains the UI for this
	 * field form.  It contains all of the text fields etc..
	 * It can be added to the DOM anywhere.
	 * @returns {HTMLElement} 
	 */
	function TranslationFormField_getFormElement(){
		var self = this;
		if ( this.formElement == null ){
			this.formElement = $('<div></div>')
				.addClass('xf-translation-form-field-form')
				.get(0);
			var fields = this.stringForm.getFormFields();
			$.each(fields, function(){
				$(self.formElement).append(
					$('<div></div>')
						.text($(this).attr('data-orig-value'))
						.get(0)
				);
				$(self.formElement).append(
					$('<div></div>')
						.append(this)
						.get(0)
				);
				
				$(this).change(function(){
					var res = self.translationMemory.addTranslation(
						$(this).attr('data-orig-value'),
						$(this).val()
					);
					
					self.evaluateChanged();
					if ( res ){
						// We only mark it changed if the translation memory
						// accepted the add.  It would have returned false
						// if the same translation is already found in it
						
					}
					
					
				});
			});
		}
		return this.formElement;
	
	}
	
	
	
	/**
	 * @name save
	 * @memberOf xataface.modules.tm.TranslationFormField#
	 * @function
	 * @description Saves the translation in this form field.
	 * @param {Function} callback A callback function that is called on
	 * a successful save.  It is not called on failure.
	 * @returns {boolean} True if the field is changed and a save was initiated.
	 * False if the field had not been changed and did not require updating
	 */
	function TranslationFormField_save(callback){	
		var self = this;
		if ( !$(self.tr).hasClass('xf-field-changed') ){
			return false;
		}
		this.stringForm.pushFields();
		this.stringForm.unparse();
		var newval = $(this.stringForm.el).html();
		var oldval = $('td.xf-field-translation-dest', this.tr).text();
		if ( newval == oldval ){
			$(self.tr).removeClass('xf-field-changed');
			return false;
		}
		
		var q = {
			'-action': 'tm_save_field',
			'--lang': this.dest,
			'--record_id': this.recordID,
			'--field': this.fieldName,
			'--newval': newval
		};
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
		
			try {
				
				if ( res.code == 200 ){
					$(self.tr).removeClass('xf-field-changed');
					$(self.tr).removeClass('xf-field-empty');
					
					
					$('td.xf-field-translation-dest', self.tr).text(res.fieldContent);
					if ( typeof(callback) == 'function' ) callback(res);
					$(self).trigger('afterSave', res);
				
				} else {
					if ( res.message ) throw res.message;
					else throw 'Failed to save translation due to an unspecified server error.  See error log for details.';
					
				}
				
			} catch (e){
				if ( typeof(callback) == 'function' ) callback(res);
				$(self).trigger('afterSave', res);
			}
			
		});
		
		return true;
		
		
	
	}
	
	

})();