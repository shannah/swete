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

(function(){

	


	if ( typeof(window.xataface) == 'undefined' )  window.xataface = {};
	if ( typeof(window.xataface.modules) == 'undefined' ) window.xataface.modules = {};
	if ( typeof(window.xataface.modules.tm) == 'undefined' ) window.xataface.modules.tm = {};
	

	var WebLite = {};
	var $ = jQuery;
	
	
	function StringForm(el){
		this.el = el;
		this.strings = null;
		var translator = new WebLite.Translator();
		this.strings = translator.extractStrings(this.el);
		if ( this.strings == null ){
			alert('Null strings for '+$(this.el).html());
		}
		
	}
	
	window.xataface.modules.tm.StringForm = StringForm;
	
	
	StringForm.prototype.parse = parseStrings;
	StringForm.prototype.unparse = unparseStrings;
	StringForm.prototype.getFormFields = getFormFields;
	StringForm.prototype.buildField = buildField;
	StringForm.create = createStringForm;
	StringForm.prototype.pushFields = pushFields;
	StringForm.prototype.pullFields = pullFields;
	StringForm.unstripHtml = unstripHtml;
	StringForm.stripHtml = stripHtml;
	
	
	
	
	
	
	/**
	 * Parses the strings in the element into an array of objects with
	 * each object having keys:
	 *		element: The dom element
	 *		text: The text (this text may contain html)
	 */
	function parseStrings(){
		
		//alert(this.strings.length);
		$.each(this.strings, function(){
		
			var htmlTags = {};
			var strippedText = null;
			try {
				strippedText = stripHtml(this.text, htmlTags);
			} catch (e){
				strippedText = stripText(this.text);
			}
			//alert('stripped '+strippedText);
			this.strippedText = strippedText;
			this.htmlTags = htmlTags;
		});
		
		$(this).trigger('afterParse');
		
	}
	
	
	function unparseStrings(){
		
		$.each(this.strings, function(){
			//alert("Unstripping "+this.strippedText);
			this.text = unstripHtml(this.strippedText, this.htmlTags);
			//alert(this.text);
			//alert("Element html before: "+$(this.element).html());
			$(this.element).html(this.text);
			//alert("Element html after: "+$(this.element).html());
		});
		//this.strings = null;
		$(this).trigger('afterUnparse');
	}
	
	
	/**
	 * Returns all of the form fields that can be used to edit the text in this form.
	 * @return {Array} Array of HTMLElement objects that can be used to edit this
	 *  form.  These elements are bound to the data of this form using the onchange handler.
	 */
	function getFormFields(){
		
		var out = [];
		var self = this;
		if ( this.strings == null ){
			//alert('Strings is null');
		}
		$.each(this.strings, function(){
			if ( !this.field ){
				self.buildField(this);
			}
			out.push(this.field);
			
		});
		return out;
	}
	
	
	function pushFields(){
	
		this.getFormFields();
		$.each(this.strings, function(){
			this.strippedText = $(this.field).val();
		});
	}
	
	function pullFields(){
		this.getFormFields();
		$.each(this.strings, function(){
			$(this.field).val(this.strippedText);
		});
	}
	
	/**
	 * Builds a field to edit the stripped value for the string object.
	 * @param {Object} o An element of the strings array with keys:
	 *		- text : The html text
	 *		- element : The DOMElement that houses the text
	 *		- strippedText: The parsed text for the element
	 * 		- htmlTags: The HTML Tags that were parsed out of the text when stripping it.
	 */
	function buildField(o){
		//alert(o.text);
		var fld = $('<textarea></textarea>')
			.on('keydown', function(e) {
				if (e.shiftKey) {
					//console.log('shift key');
				}
			})
			.change(function(){
				
				o.strippedText = $(this).val();
			})
			.val(o.strippedText)
			.attr('data-orig-value', o.strippedText)
			.attr('data-orig-html', o.text)
			.css('height', 1.5*Math.ceil(o.strippedText.length/72)+'em')
			.get(0);
		o.field = fld;
	
	}
	
	
	
	
	/**
	 * Creates a string form for a given element.
	 */
	function createStringForm(el){
		if ( $(el).is('textarea') || $(el).is('input') ){
			
			var div = document.createElement('div');
			var str = $(el).val()
				.replace(/<script[^>]*>[\s\S]*?<\/script>/mg, '')
				.replace(/<link[^>]*>/mg, '')
				.replace(/<style[^>]*>[\s\S]*<\/style>/mg, '')
				.replace(/<([^>]+)src=([^>]+)>/, '<$1src_disabled=$2>');
			
			$(div).html(str);
	
			
			var strform = new StringForm(div);
			$(strform).bind('afterUnparse', function(e,d){
				$(el).val($(div).html().replace(/<([^>]+)src_disabled=([^>]+)>/, '<$1src=$2>'));
			});
			return strform;
			
		} else {
			return new StringForm(el);
		}
	}
	
	
	
	
	function InlineEditor(el){
		this.el = el;
		this.stringForm = createStringForm(el);
		this.editorElement = null;
		this.buttonBar = null;
		this.cancelButton = null;
		this.saveButton = null;
		
		
	
	}
	
	InlineEditor.prototype.replace = InlineEditor_replace;
	
	xataface.modules.tm.InlineEditor = InlineEditor;
	
	
	function InlineEditor_replace(){
		var self = this;
		var el = this.el;

		this.editorElement = $('<div></div>')
			.css({
				'width': $(window).width(),
				'height': $(window).height()-30,
				'position': 'absolute',
				'top': $(window).scrollTop()+30,//$(el).offset().top,
				'left': 0,//$(el).offset().left,
				'z-index': 100000,
				'overflow': 'scroll',
				'border': '1px solid gray',
				'background-color': '#eaeaea'
			})
			.get(0);
		
		this.stringForm.parse();
		var fields = this.stringForm.getFormFields();
		
		$.each(fields, function(){
			$(this).css({
				'height': (Math.ceil($(this).val().length / 72)*1.5)+'em',
				'width': $(window).width()-20
			});
			$(self.editorElement).append(this);
		});
		//alert('nowhere');
		this.buttonBar = $('<div></div>')
			.css({
				'width': $(window).width(),
				'height': 30,
				'position': 'absolute',
				'top': $(window).scrollTop(),//$(el).offset().top-30,
				'left': 0,//$(el).offset().left,
				'z-index': 100000,
				'background-color': '#ccc'
			
			})
			.get(0);
			
		this.cancelButton = $('<button></button>')
			.text('Cancel')
			.click(function(){
				$(self.editorElement).remove();
				$(self.buttonBar).remove();
			})
			.get(0);
			
		//$(this.buttonBar).append(this.cancelButton);
		
		this.saveButton = $('<button></button>')
			.text('Return to HTML Code')
			.click(function(){
				self.stringForm.unparse();
				$(self.editorElement).remove();
				$(self.buttonBar).remove();
				$('body').css('overflow','auto');
			})
			.get(0);
		$(this.buttonBar).append(this.saveButton);
		
		$('body')
			.append(this.buttonBar)
			.append(this.editorElement)
			.css({overflow:'hidden'})
			;
		
	
	}
	

	function unstripHtml(str, htmlTags){
		return stripHtml(str, htmlTags, true);
	}
	
	/**
	 * A function to strip the tags from an HTML string and replace them with
	 * shorter <g> and <x> tags like in the XLIFF specification.
	 */
	function stripHtml(str, htmlTags, merge){
	
		if ( !str.match(/<[a-zA-Z][^>]*>/) ) return str;
	
		// Get rid of pesky MS Word markup.  eradicate it.
		var oldStr = str;
		str = str//.replace(/<!--.*?-->/mg, '')
			.replace(/<w:[^>]*?>/mg, '')
			.replace(/<\/w:[^>]*?>/mg, '')
			.replace(/<m:[^>]*?>/mg, '')
			.replace(/<\/m:[^>]*?>/mg, '')
			.replace(/<xml[^>]*?>/mg, '')
			.replace(/<\/xml[^>]*?>/mg, '')
			.replace(/<link[^>]*?>/mg, '')
			.replace(/<meta [^>]*?>/mg, '')
			.replace(/<([^>]+)src=([^>]+)>/mg, '<$1src_disabled=$2>')
			;
		//if (str != oldStr ) alert(str+"\nvs\n"+oldStr);
		var dummyDiv = document.createElement('div');
		if ( !merge) str = $(dummyDiv).html(str).html();
		if ( typeof(merge) == 'undefined' ) merge = false;
		var usedTags = {}; // Keep track of which tags we have used in the merge
							// We use this at the end to see if we missed any, and
							// throw an error.
		var id=1;
		var tagStack = [];
		
		var out = '';
		for ( var i=0; i<str.length; i++){
			var ch = str.charAt(i);
			if ( ch == '<' ){
				
				var tagStr = str.substring(i, str.indexOf('>', i));
				if ( tagStr.length >= 4  && tagStr.substring(0,4) == '<!--'){
					var closeComment = str.indexOf('-->', i);
					if ( closeComment < 0 ) closeComment = str.length;
					i = closeComment+2;
					continue;
				}
				//alert(tagStr);
				var tagName = '';
				if ( tagStr.charAt(1) == '/' ){
					// we have a closing tag
					tagName = tagStr.substring(2, tagStr.length);
					//alert(tagName+'( '+tagStr+')');
					//alert(str);
					//alert(tagName);
					var buffer = [];
					while ( tagStack.length > 0 && tagStack[tagStack.length-1].tagName != tagName ){
						var t = tagStack.pop();
						if ( t.type == 'tag' ) {
							if ( !merge ){
								buffer.push('<x id="'+t.inlineID+'"/>');
							} else {
								buffer.push('<'+t.tagObj.tagContents+'>');
							}
						}
						else {
							buffer.push(t.text);
							
						}
					}
					
					//foo
					
					
					if ( tagStack.length == 0 ){
						throw "HTML Syntax Error: Closing tag </"+tagName+"> with no opening tag. ("+str+")";
						
						tagStack.push({
							type: 'tag',
							inlineID: id++,
							'tagName': tagName,
							tagContents: '<'+tagName+'>'
						});
						//tagStack.push(tagObj);
							
					}
					var o = '';
					var currTag = tagStack.pop();
					var tagChar = 'g';
					if ( currTag.isVariableTag ){
						tagChar = 'v';
					}
					if ( !merge) {
						if ( tagName != 'style' ){
							o += '<'+tagChar+' id="'+currTag.inlineID+'">';
						}
					} else {
						
						o += '<'+currTag.tagObj.tagContents+'>';
					}
					while ( buffer.length > 0 ){
						if ( tagName != 'style' ) o += buffer.pop();
						else buffer.pop();
					}
					
					if ( !merge ){
						if ( tagName != 'style' ) o += '</'+tagChar+'>';
					} else {
						o += '</'+currTag.tagObj.tagName+'>';
					}
					tagStack.push({
						type: 'text',
						text: o
					});
					i += tagStr.length;
				} else {
					// it's an open tag
					var tagContents = tagStr.substring(1, tagStr.length);
					//alert('Contents: '+tagContents);
					if ( tagContents.indexOf(' ') != -1 ){
						tagName = tagContents.substring(0, tagContents.indexOf(' '));
						
					} else {
						tagName = tagContents.replace(/\//, '');
					}
					
					var isVariableTag = (tagName.toLowerCase() == 'v' || ( tagName.toLowerCase() == 'span' && tagContents.match(/data-swete-translate=/) ) );
					
					var tagObj = {
						type: 'tag',
						inlineID: id++,
						tagName: tagName,
						tagContents: tagContents,
						isVariableTag: isVariableTag
					};
					
					if ( merge ){
						
						var idpos = tagContents.indexOf(' id="')+5;
						if ( idpos < 5 ){
							//alert("No id in "+tagContents);
							// there is no id attribute on this one.
							// so we stick with the original tagObj
						} else {
							var tagID = parseInt(tagContents.substring(idpos, tagContents.indexOf('"', idpos)));
							if ( htmlTags[tagID] ){
								//alert("Found html tag for "+tagID);
								tagObj.tagObj = htmlTags[tagID];
								usedTags[tagID] = true;
								
							} else {
								//alert("No tag with id "+tagID+" found in "+tagContents);
							}
						}
					}
					if ( !merge ) htmlTags[tagObj.inlineID] = tagObj;
					tagStack.push(tagObj);
					i += tagStr.length;
					
				
				}
				
			} else {
				
				var tagObj = {
					type: 'text',
					text: str.substring(i, str.indexOf('<', i) >= 0 ? str.indexOf('<', i) : str.length)
				};
				i += tagObj.text.length-1;
				if ( !merge ) tagObj.text = $(dummyDiv).html(tagObj.text.replace(/\s+/g, ' ')).text().replace(/</g,'&lt;').replace(/>/g, '&gt;');
				else tagObj.text = $(dummyDiv).html(tagObj.text).html();
				tagStack.push(tagObj);
				
			
			}
			
		}
		
		var buffer = [];
		while ( tagStack.length > 0 ){
			var t = tagStack.pop();
			if ( t.type == 'tag' ){
				if ( !merge) buffer.push('<x id="'+t.inlineID+'"/>');
				else buffer.push('<'+t.tagObj.tagContents+'>');
			} else {
				buffer.push(t.text);
			} 
			
		}
		
		while ( buffer.length > 0 ){
			out += buffer.pop();
			
		}
		
		return out.replace(/<([^>]+)src_disabled=([^>]+)>/mg, '<$1src=$2>');
	}
	
	/**
	 * Gets the translated text from a translated-text cell
	 */
	var getTranslatedText = function(td){
		return $(td).attr('translated-text');
	};
	
	var stripText = function(str){
		var dummyDiv = document.createElement('div');
		return $(dummyDiv).html(str.replace(/<([^>]+)src=([^>]+)>/mg, '<$1src_disabled=$2>')).text().replace(/<([^>]+)src_disabled=([^>]+)>/mg, '<$1src=$2>').replace(/\s+/g, ' ').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	}
	
	
	
	
	
	
	///--------------------------Supporting things -------------------------------
	
	/** Add trim functions to String class **/
	function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
	}
	
	function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
	}
	
	function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
	}
	
	String.prototype.trim = function(chars){
		return trim(this, chars);
	};
	
	
	
	/** END TRIM Functions **/
	function array_map( callback ) {
		// http://kevin.vanzonneveld.net
		// +   original by: Andrea Giammarchi (http://webreflection.blogspot.com)
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// *     example 1: array_map( function(a){return (a * a * a)}, [1, 2, 3, 4, 5] );
		// *     returns 1: [ 1, 8, 27, 64, 125 ]
	
	
		var argc = arguments.length, argv = arguments;
		var j = argv[1].length, i = 0, k = 1, m = 0;
		var tmp = [], tmp_ar = [];
	
		while (i < j) {
			while (k < argc){
				tmp[m++] = argv[k++][i];
			}
	
			m = 0;
			k = 1;
	
			if( callback ){
				tmp_ar[i++] = callback.apply(null, tmp);
			} else{
				tmp_ar[i++] = tmp;
			}
	
			tmp = [];
		}
	
		return tmp_ar;
	}
	
	if(!document.documentElement.outerHTML){
	  Node.prototype.getAttributes = function(){
		var attStr = "";
		if(this && this.attributes.length > 0){
		  for(var a = 0; a < this.attributes.length; a++){
			attStr += " " + this.attributes.item(a).nodeName + "=\"";
			attStr += this.attributes.item(a).nodeValue + "\"";
		  }
		}
		return attStr;
	  };
	
	  Node.prototype.getInsideNodes = function(){
		if(this){
		  var cNodesStr = "", i = 0;
		  var iEmpty = /^(img|embed|input|br|hr)$/i;
		  var cNodes = this.childNodes;
		  for(var i = 0; i < cNodes.length; i++){
			switch(cNodes.item(i).nodeType){
			  case 1 :
				cNodesStr += "<" + cNodes.item(i).nodeName.toLowerCase();
				if(cNodes.item(i).attributes.length > 0){
				  cNodesStr += cNodes.item(i).getAttributes();
				}
				cNodesStr += (cNodes.item(i).nodeName.match(iEmpty))? "" : ">";
				if(cNodes.item(i).childNodes.length > 0){
				  cNodesStr += cNodes.item(i).getInsideNodes();
				}
				if(cNodes.item(i).nodeName.match(iEmpty)){
				  cNodesStr += " />";
				} else {
				  cNodesStr += "</" + cNodes.item(i).nodeName.toLowerCase() + ">";
				}
				break;
			  case 3 :
				cNodesStr += cNodes.item(i).nodeValue;
				break;
			  case 8 :
				cNodesStr += "<!--" + cNodes.item(i).nodeValue + "-->";
				break;
			}
		  }
		  return cNodesStr;
		}
	  };
	
	  HTMLElement.prototype.__defineGetter__('outerHTML',function(){
		var strOuter = "";
		var iEmpty = /^(img|embed|input|br|hr)$/i;
		switch(this.nodeType){
		  case 1 :
			strOuter += "<" + this.nodeName.toLowerCase();
			strOuter += this.getAttributes();
			if(this.nodeName.match(iEmpty)){
			  strOuter += " />";
			} else {
			  strOuter += ">" + this.getInsideNodes();
			  strOuter += "</" + this.nodeName.toLowerCase() + ">";
			}
			break;
		  case 3 :
			strOuter += this.nodeValue;
			break;
		  case 8 :
			cNodesStr += "<!--" + this.nodeValue + "-->";
			break;
		}
		return strOuter;
	  });
	
	  HTMLElement.prototype.__defineSetter__('outerHTML',function(str){
		var iRange = document.createRange();
	
		iRange.setStartBefore(this);
	
		var strFragment = iRange.createContextualFragment(str);
		var sRangeNode = iRange.startContainer;
	
		iRange.insertNode(strFragment);
		sRangeNode.removeChild(this);
	  });
	}
	
	function str_replace(search, replace, subject) {
		// http://kevin.vanzonneveld.net
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   improved by: Gabriel Paderni
		// +   improved by: Philip Peterson
		// +   improved by: Simon Willison (http://simonwillison.net)
		// +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
		// +   bugfixed by: Anton Ongson
		// +      input by: Onno Marsman
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +    tweaked by: Onno Marsman
		// *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
		// *     returns 1: 'Kevin.van.Zonneveld'
		// *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
		// *     returns 2: 'hemmo, mars'
	
		var f = search, r = replace, s = subject;
		var ra = r instanceof Array, sa = s instanceof Array, f = [].concat(f), r = [].concat(r), i = (s = [].concat(s)).length;
	
		while (j = 0, i--) {
			if (s[i]) {
				while (s[i] = s[i].split(f[j]).join(ra ? r[j] || "" : r[0]), ++j in f){};
			}
		};
	
		return sa ? s : s[0];
	}
	
	
	function in_array(needle, haystack, strict) {
		// http://kevin.vanzonneveld.net
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
		// *     returns 1: true
	
		var found = false, key, strict = !!strict;
	
		for (key in haystack) {
			if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
				found = true;
				break;
			}
		}
	
		return found;
	}
	
	if ( typeof(WebLite) == 'undefined' ) WebLite = {};
	
	WebLite.Translator = function(srcLang,destLang){
		this.srcLang = srcLang;
		this.destLang = destLang;
		//this.setTranslationMemory(new DefaultTranslationMemory());
	};
	
		
	WebLite.Translator.atts = {
			'a'	: ['title'],
			'img' : ['alt', 'title'],
			'meta' : ['content']
			};
			
	WebLite.Translator.tags = [
			'option',
			'textarea',
			'caption',
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'h7',
			'h8',
			'h9',
			'h10',
			'legend',
			'title',
			'li',
			'dt',
			'dd',
			'p',
			'th',
			'td',
			'div',
			'body'
			];
		
		// Tags that represent translatable sections
	WebLite.Translator.sectionTags = [
			'option',
			'p',
			'div',
			'td',
			'th',
			'li',
			'dt',
			'dd',
			'dl',
			'blockquote'];
			
	WebLite.Translator.inlineTags = [
			'a',
			'em',
			'abbr',
			'i',
			'u',
			'b',
			'span',
			'strong',
			'acronym',
			'font',
			'sup',
			'sub',
			'g',
			'x',
			'v'
			];
				
		
		
		
			
	WebLite.Translator.splitters = ['input','hr','br','textarea','table','ul','ol','dl','select','option'];
			
		
	WebLite.Translator.prototype.replacements={};
	WebLite.Translator.prototype.strings={};
	WebLite.Translator.prototype.translationMemory = null;
		
	WebLite.Translator.prototype.setTranslationMemory = function(mem){
		this.translationMemory = mem;
	};
	
	if ( typeof(WebLite.Translator.DOM) == 'undefined' ) WebLite.Translator.DOM={};
	WebLite.Translator.DOM.find = function(root,type){
		this.nodes = [];
		this.find_rec(root,type);
		return this.nodes;
	};
	
	WebLite.Translator.DOM.find_rec = function(root,type){
		
		for ( var i=0; i<root.childNodes.length; i++){
			this.find_rec(root.childNodes[i], type);
		}
		
		if ( root.nodeName == type ) this.nodes[this.nodes.length] = root;
	};
		
	WebLite.Translator.prototype.extractStrings = function(dom){
	    if ( typeof(WEBLITE_TRANSLATOR_DISABLE_EXTRACTION) !== 'undefined' && WEBLITE_TRANSLATOR_DISABLE_EXTRACTION ){
	        return [{
	            element : dom,
	            text : dom.innerHTML
	        }];
	    }
	
	    var tx;
		var text = WebLite.Translator.DOM.find(dom,'#text');
		this.strings = {};
		var stringsIndex = {};
		var thekeys = [];
		var thisTranslator = this;
		
		var elements = [];
		for (var txkey=0; txkey<text.length; txkey++ ){
			
			tx = text[txkey];
			(function(tx){
				//alert(tx.nodeValue);
				if ( !tx.nodeValue || !tx.nodeValue.trim() ) return;
				if ( !tx.parentNode ) return;
				if ( in_array(tx.parentNode.tagName.toLowerCase() , ['comment','script','style','code'] )) return;
		
				if ( thisTranslator.isCovered(tx) ) return;
				var group = [];
				var start = tx;
				if ( tx.parentNode.childNodes ){
					var pos = -1;
					for ( var idx=0; idx< tx.parentNode.childNodes.length; idx++){
						var child = tx.parentNode.childNodes[idx];
						if ( child == tx ){
							pos = idx;
							break;
						}
					}
					var mypos = pos;
					for ( var i=pos; i>=0; i--){
						var node = tx.parentNode.childNodes[i];
						if ( node == null || !node.nodeName || !node.tagName ) continue;
						if ( node.nodeName != '#text' && !in_array(node.tagName.toLowerCase(), WebLite.Translator.inlineTags) ){
							break;
						}
						pos = i;
					}
					if ( mypos == pos || thisTranslator.isFirstText(tx.parentNode, mypos, pos)){
						
						var startIdx = pos;
						for ( i=startIdx; i<tx.parentNode.childNodes.length; i++ ){
							node = tx.parentNode.childNodes[i];
		
							if ( !node ) break;
							var tagName = node.tagName;
							if (tagName) tagName = tagName.toLowerCase();
							if ( node.nodeName != '#text' && !in_array(tagName, WebLite.Translator.inlineTags) ){
								break;
							}
							
							
							group[group.length] = node;
						}
					}
				} else {
					group[group.length] = tx;
				}
				if ( group.length == 0 ) return;
				
				var combinedText = [];
				for (var i=0; i< group.length; i++){
					var item = group[i];
					if ( item.outerHTML ){
						combinedText[combinedText.length] = item.outerHTML;
					}
					else combinedText[combinedText.length] = item.nodeValue;
				}
				combinedText = combinedText.join(' ');
				//var strkey = this.cleanString(combinedText).replace(/[\s\u0085\u00A0'"\u0060\u00B4\u2018\u2019\u201C\u201D\u2026\u0091-\u0096]*/g, '').replace(/<[^>]+>/g, '').toLowerCase().replace(/&amp;/g,'&').replace(/&nbsp;/g,'');
				//thekeys[thekeys.length] = strkey;
				//alert(thisTranslator.srcLang+thisTranslator.destLang);
				
				var theElement = null;
				
				if ( group.length < group[0].parentNode.childNodes.length ){
					var span = document.createElement('span');
					$(span).html(combinedText);
					group[0].parentNode.insertBefore(span, group[0]);
					for (var i=0; i<group.length; i++){
						group[i].parentNode.removeChild(group[i]);
					}
					theElement = span;
				} else {
					theElement = group[0].parentNode;
				}
				
				elements.push({element: theElement, text: combinedText});
				
			})(tx);
			
	
			
		}
	
		
		return elements;
	
	};
	
	WebLite.Translator.prototype.isCovered = function(node){
		var p = node.parentNode;
		var c = node;
		while ( p ){
			var tagName = p.tagName;
			if (tagName) tagName = tagName.toLowerCase();
			if ( p.getAttribute('notranslate') ) return true;
			if ( p.childNodes ){
				var foundMe = false;
				var currNode = c;
				while ( currNode.previousSibling ){
					currNode = currNode.previousSibling;
					if ( currNode.nodeName == '#text' && currNode.nodeValue && currNode.nodeValue.trim() ) return true;
					var currTagName = currNode.tagName;
					if ( currTagName ) currTagName = currTagName.toLowerCase();
					if ( !in_array(currTagName, WebLite.Translator.inlineTags) ) break; //return false;
					
				}
				
				if ( c.nodeName != '#text' ){
					currNode = c;
					while (currNode.nextSibling){
						currNode = currNode.nextSibling;
						if ( currNode.nodeName == '#text' && currNode.nodeValue && currNode.nodeValue.trim() ) return true;
						var currTagName = currNode.tagName;
						if ( currTagName ) currTagName = currTagName.toLowerCase();
						if ( currNode.nodeName != '#text' && !in_array(currTagName, WebLite.Translator.inlineTags) ) return false;
						
					}
					
				}	
			}
			
			if ( !in_array(tagName, WebLite.Translator.inlineTags) ){
				// we are a child of a block tag, so we are not covered!
				return false;
			}
			
			c = p;
			p = p.parentNode;
			
		}
		
		return false;
	};
		
	WebLite.Translator.prototype.cleanString = function(str){
		return str_replace(["\t","\r\n","\r","\n",'  '],[' ',"\n",' ',' ',' '], str);
	};
		
	WebLite.Translator.prototype.replaceStrings = function(html){
		return html.replace(/\{\{\$([0-9]+)\$\}\}/, this.replaceString);
	};
		
	WebLite.Translator.prototype.translate = function (html, approvalLevel){
		if ( typeof(approvalLevel)=='undefined' ) approvalLevel=0;
	
		var out = this.extractStrings(html);
		for (var key in this.strings ){
			var value = this.strings[key];
			this.strings[key] = this.translationMemory.getString(value, approvalLevel);
		}
		
		this.translationMemory.save();
		
		//$out = $dom->save();
		out = out.replace(/\{\{\$([0-9]+)\$\}\}/, this.replaceString);
		
		
		return out;
		//return $strings;
	};
		
	WebLite.Translator.prototype.replaceString = function(matches){
		return this.strings[matches[1]];
	};
		
		
	WebLite.Translator.prototype.isFirstText = function(node, mypos, pos){
		for ( var i=pos; i<mypos; i++ ){
			if ( node.childNodes[i].nodeName == '#text' && node.childNodes[i].nodeValue.trim() != '' ) return false;
		}
		return true;
	};
	/*
	window.onload=function(){
		var tr = new WebLite.Translator();
		tr.replacements = WebLite.strings;
		alert(tr.extractStrings(document.body));
	};
	*/
	
	
	
	

})();


