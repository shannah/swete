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
//require <xataface/modules/tm/translation_form.js>
//require-css <xataface/modules/tm/translate.css>
//require <xatajax.util.js>
//require <xatajax.form.core.js>

(function(){

	var $ = jQuery;
	var TranslationForm = xataface.modules.tm.TranslationForm;
	var TranslationFormField = xataface.modules.tm.TranslationFormField;
	
	
	
	function showForm(/*TranslationFormField*/ field){
		var tr = field.tr;
		var nextTr = $(tr).next();
		if ( $(nextTr).hasClass('xf-active-translation-field') ){
			return false; /*The form is already visible*/
		}
		
		var formTr = $('<tr></tr>')
			.addClass('xf-active-translation-field')
			.append(
				$('<td></td>')
					.attr('colspan',3)
					.append(getFieldStatusLabels())
					.append(getFieldFormButtonBar(field))
					.append(field.getFormElement())
					
			)
			.get(0);
			
			
		$(tr).after(formTr);
		$(tr).addClass('active');
		field.evaluateChanged();
		return true;
	}
	
	function getFieldStatusLabels(){
		
		var out = $('<div></div>')
			.addClass('xf-field-status-labels')
			.append(
				$('<span></span>')
					.addClass('changed')
					.text('Changed')
					.attr('title', 'The source string has been modified since it was last translated.')
			)
			.append(
				$('<span></span>')
					.addClass('empty')
					.text('Not Translated')
					.attr('title', 'This field hasn\'t been translated yet.')
			)
			.append(
				$('<span></span>')
					.addClass('translated')
					.text('Up To Date')
					.attr('title', 'This field has been translated and is up-to-date.')
			)
			;
			
		return out.get(0);
		
	}
	
	
	function handleFieldFormSaved(/*TranslationFormField*/ field){
		
		hideForm(field);
		
	}
	
	
	function getFieldFormButtonBar(/*TranslationFormField*/ field){
		var fieldFormButtonBar = null;
		if ( fieldFormButtonBar == null ){
			fieldFormButtonBar = $('<div></div>')
				.addClass('xf-button-bar')
				.append(
					$('<div></div>')
						.addClass('xf-button-bar-actions')
						.append(
							$('<ul></ul>')
								.append(
									$('<li></li>')
									
										.append(
											$('<a></a>')
												.attr('href','#')
												.text('Cancel')
												.click(function(){
												
													hideForm(field);
													return false;
												})
										)
										
								)
								.append(
									
									$('<li></li>')
										.append(
											$('<a></a>')
												.attr('href','#')
												.text('Save')
												.addClass('xf-tm-save-field-btn')
												
												.click(function(){
													field.save(function(res){
														
														if ( res.code == 200 ){
															handleFieldFormSaved(field);
														}
													});
													return false;
												})
										)
								)
						)
				
				)
				.append(
					$('<div></div>')
						.css({
							'clear':'both',
							'height':'1px'
						})
				)
				.get(0);
		
		}

		return fieldFormButtonBar;
	}
	
	
	
	
	
	function hideForm(/*TranslationFormField*/ field){
		$(field.tr).removeClass('active');
		var nextTr = $(field.tr).next();
		if ( nextTr.hasClass('xf-active-translation-field') ){
			$(nextTr).detach();
		}
	}
	
	
	
	
	$(document).ready(function(){
		$('a#cancel_translation-link').click(function(){
			window.history.back();
			return false;
		});
		
		$('li.save-translations > a').click(function(){
			alert("You don't need to save this form.  Translations for each field are saved automatically when you tab out of the field.  Just click 'Cancel' to leave this form and return to the previous page.");
			return false;
		});
	
	
		var wrapper = $('div.xf-translation-form-wrapper');
		if ( wrapper.size() == 0 ){
			alert('No translation form wrapper found.');
			return;
		}
		var source = $(wrapper).attr('data-source-language');
		var dest = $(wrapper).attr('data-destination-language');
		var tmid = $(wrapper).attr('data-translation-memory-id');
		if ( !tmid ) tmid = null;
		var tf = new TranslationForm(source, dest, tmid);
		
		var loading = $('<div></div>')
			.text('Loading Translation Memory.  Please wait...')
			.addClass('xf-loading')
			.get(0);
			
		$('body').append(loading);
		
		tf.load(function(res){
		    $(loading).remove();
			if ( res.error ){
				alert(res.error);
			} else {
				
				$.each(tf.fields, function(){
					var fld = this;
					//console.log('here');
					//console.log(fld);
					$('a.xf-edit-field-translation', this.tr).click(function(){
					    //alert('here');
						showForm(fld);
						return false;
					});
				
					if ( $(this.tr).hasClass('xf-field-translation-null') ){
					    showForm(fld);
					}
				});
			
			}
		});
		
		
		var langPairSelector = $('select.xf-langpair-selector');
		
		
		$('a.xf-change-langpair-link').click(function(){
			langPairSelector.show();
			$(this).hide();
			return false;
			
		});
		
		langPairSelector.change(function(){
			
			var newLang = $(this).val();
			if ( !newLang ) return false;
			
			var params = XataJax.util.getRequestParams();
			params['-destinationLanguage'] = newLang;
			params['-action'] = 'translate';
			var ids = [];
			$('div.xf-record-translation[data-xf-record-id]').each(function(){
				ids.push($(this).attr('data-xf-record-id'));
			});
			params['--selected-ids'] = ids.join('\n');
			XataJax.form.submitForm('post', params);
			
			//window.location.href=XataJax.util.url(params);
			
		
		});
		
		
	});

})();