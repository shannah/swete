if ( typeof(window.console)=='undefined' ){window.console = {log: function(str){}};}if ( typeof(window.__xatajax_included__) != 'object' ){window.__xatajax_included__={};};
(function(){
					var headtg = document.getElementsByTagName("head")[0];
					if ( !headtg ) return;
					var linktg = document.createElement("link");
					linktg.type = "text/css";
					linktg.rel = "stylesheet";
					linktg.href="/swete-git/swete-admin/index.php?-action=css&--id=Background-f658a3cf8811928a8058916fab70b730";
					linktg.title="Styles";
					headtg.appendChild(linktg);
				})();
//START xataface/modules/g2/global.js
if ( typeof(window.__xatajax_included__['xataface/modules/g2/global.js']) == 'undefined'){window.__xatajax_included__['xataface/modules/g2/global.js'] = true;
/**
 * Global Javascript Functions included in all pages when the g2 
 * module is enabled.
 *
 * @author Steve Hannah <steve@weblite.ca>
 * Copyright (c) 2011 Web Lite Solutions Corp.
 * All rights reserved.
 */






//START xatajax.actions.js
if ( typeof(window.__xatajax_included__['xatajax.actions.js']) == 'undefined'){window.__xatajax_included__['xatajax.actions.js'] = true;






//START xatajax.form.core.js
if ( typeof(window.__xatajax_included__['xatajax.form.core.js']) == 'undefined'){window.__xatajax_included__['xatajax.form.core.js'] = true;




(function(){
	var $ = jQuery;
	
	/**
	 * @class
	 * @name form
	 * @memberOf XataJax
	 * @description A class with static utility functions for working with forms.
	 */
	XataJax.form = {
		findField: findField,
		createForm: createForm,
		submitForm: submitForm
	
	};
	
	/**
	 * @function
	 * @memberOf XataJax.form
	 * @description
	 * Finds a field by name relative to a starting point.  It will search only within
	 * the startNode's form group (i.e. class xf-form-group).
	 *
	 * <p>This method of finding sibling fields is compatible with the grid widget
	 * so that it will return the sibling widget of the specified name in the same
	 * row as the source widget.  However it will also work when the widgets are
	 * displayed normally.</p>
	 *
	 * <p><b>Note:</b> This is designed to work with fields in the Xataface edit and new
	 * record forms and not just general html forms.  It looks for the <em>data-xf-field-fieldname</em>
	 * HTML attribute to identify the fields by name.  Xataface automatically adds this
	 * attribute to all fields on its forms.</p>
	 *
	 * @param {HTMLElement} startNode The starting point of our search (we search for siblings).
	 * @param {String} fieldName The name of the field we are searching for.
	 *
	 * @return {HTMLElement} The found field or null if it cannot find it.
	 *
	 * @example
	 * //require &lt;xatajax.form.core.js&gt;
	 * var form = XataJax.load('XataJax.form');
	 * var firstNameField = jQuery('#first_name');
	 * var lastNameField = form.findField(firstNameField, 'last_name');
	 * // lastNameField should contain the last name field in the same form
	 * // group as the given first name field.
	 *
	 * 
	 */
	function findField(startNode, fieldName){
		
		var parentGroup = $(startNode).parents('.xf-form-group').get(0);
		if ( !parentGroup ) parentGroup = $(startNode).parents('form').get(0);
		if ( !parentGroup ) return null;
		//alert('here');
		var fld = $('[data-xf-field="'+fieldName+'"]', parentGroup).get(0);
		return fld;
	}
	
	
	/**
	 * @function 
	 * @memberOf XataJax.form
	 * @description
	 * Creates a form with the specified parameters.  This can be handy if you 
	 * want to submit a form dynamically and don't want to use AJAX.
	 *
	 * @param {String} method The method.  Either 'get' or 'post'
	 * @param {Object} params The key/value pairs that the form should submit.
	 * @param {String} target The target of the form.
	 * @return {HTMLElement} jQuery object wrapping the form tag.
	 *
	 * @example
	 * XataJax.form.createForm('GET', {
	 *     '-action': 'my_special_action',
	 *     'val1': 'My first value'
	 *     'val2'; 'My second value'
	 *  });
	 */
	function createForm(method, params, target, action){
		if ( typeof(action) == 'undefined' ) action = DATAFACE_SITE_HREF;
		var form = $('<form></form>')
			.attr('action', action)
			.attr('method', method);
		if ( target ) form.attr('target',target);
		
		$.each(params, function(key,value){
			form.append(
				$('<input/>')
					.attr('type', 'hidden')
					.attr('name', key)
					.attr('value', value)
					
			);
		});
		
		return form;
	}
	
	
	/**
	 * @function
	 * @memberOf XataJax.form
	 * @description
	 * Creates and submits a form with the specified parameters.
	 * @param {String} method The method of the form (e.g. get or post)
	 * @param {Object} The key/value pairs to submit with the form.
	 * @param {String} target The target of the form.
	 * @return {void}
	 *
	 * @example
	 * @example
	 * XataJax.form.submitForm('POST', {
	 *     '-action': 'my_special_action',
	 *     'val1': 'My first value'
	 *     'val2'; 'My second value'
	 *  });
	 */
	function submitForm(method, params, target, action){
		var form = createForm(method, params, target, action);
		$('body').append(form);
		form.submit();
	}
	
})();
//END xatajax.form.core.js

}
(function(){
	
	var $ = jQuery;
	
	/**
	 * @class
	 * @name actions
	 * @memberOf XataJax
	 * @description Utility functions for dealing with actions and selected actions.
	 */
	if ( typeof(XataJax.actions) == 'undefined' ){
		XataJax.actions = {};
	}
	
	XataJax.actions.doSelectedAction = doSelectedAction;
	XataJax.actions.handleSelectedAction = handleSelectedAction;
	XataJax.actions.hasRecordSelectors = hasRecordSelectors;
	XataJax.actions.getSelectedIds = getSelectedIds;
	
	/**
	 * @function
	 * @memberOf XataJax.actions
	 * @name ConfirmCallback
	 * @description
	 * A callback function that can be passed to doSelectedAction() to serve as 
	 * a confirmation to the user that they want to proceed with the action.
	 *
	 * @param {Array} recordIds An array of record ids that are to be acted upon.
	 * @returns {Boolean} true if the user confirms that they want to proceed.  False otherwise.
	 */
	
	
	/**
	 * @function
	 * @memberOf XataJax.actions
	 * @description
	 * In a result list with checkboxes to select record ids, this gets an array
	 * of the recordIds of the checked records (or a newline-delimited string).
	 *
	 * <p>This is useful for sending to Xataface actions in the --selected-ids parameter
	 * because the df_get_selected_records() function is set up to read the --selected-ids
	 * parameter and return the corresponding records.</p>
	 *
	 * @param {HTMLElement} container The HTML DOM element that contains the checkboxes.
	 * This may be the result list table or a container thereof.
	 * @param {boolean} asString If false, this will return an array of record ids.  If true,
	 * this will return the ids as a newline-delimited string.
	 * @return {mixed} Either an array of record ids or a newline-delimited string of
	 * record ids depending on the value of the <var>asString</var> parameter.
	 *
	 * @example
	 * var ids = XataJax.actions.getSelectedIds($('#result_list'), true);
	 * $.post(DATAFACE_SITE_HREF, {'-action': 'myaction', '--selected-ids': ids}, function(res){
	 *		alert("we did it");
	 * });
	 */
	function getSelectedIds(/*HTMLElement*/ container, asString){
		if ( typeof(asString) == 'undefined' ) asString = false;
		var ids = [];
		var checkboxes = $('input.rowSelectorCheckbox', container);
		checkboxes.each(function(){
			if ( $(this).is(':checked') && $(this).attr('xf-record-id') ){
				ids.push($(this).attr('xf-record-id'));
			}
		});
		if ( asString ) return ids.join("\n");
		return ids;
	
	}
	
	/**
	 * @function
	 * @memberOf XataJax.actions
	 * @description
	 * Performs an action on the currently selected records in a container.
	 *
	 * <p>Note that the selected IDs will be sent to the action in the --selected-ids
	 * POST parameter.  One record ID per line.  See df_get_selected_records() PHP function to load these records.</p>
	 *
	 * @param {Object} params The POST parameters to send to the action.
	 * @param {HTMLElement} container The container that houses the checkboxes.
	 * @param {XataJax.actions.ConfirmCallback} confirmCallback Optional callback function that can be used to prompt the user to confirm that they would like to proceed.
	 * @param {Function} emptyCallback Callback to be called if there are no records currently selected.
	 * @return {void}
	 *
	 * @example
	 * // This will perform the my_special_action action on all selected records in 
	 * // the result_list section of the page.  It looks through the checkboxes.
	 *
	 * XataJax.actions.doSelectedAction({
	 *     '-action': 'my_special_action'
	 *     },
	 *     jQuery('#result_list'),
	 *     function(ids){
	 *         return confirm('This will perform my special action on '+ids.length+' records.  Are you sure you want to proceed?');
	 *     }
	 * });
	 * 
	 */
	function doSelectedAction(/*Object*/ params, /*HTMLElement*/container, /*XataJax.actions.ConfirmCallback*/confirmCallback, /*Function*/emptyCallback){
		var ids = [];
		var checkboxes = $('input.rowSelectorCheckbox', container);
		checkboxes.each(function(){
			if ( $(this).is(':checked') && $(this).attr('xf-record-id') ){
				ids.push($(this).attr('xf-record-id'));
			}
		});

		if ( ids.length == 0 ){
			if ( typeof(emptyCallback) == 'function' ){
				emptyCallback(params, container);
			} else {
				alert('No records are currently selected.  Please first select the records that you wish to act upon.');
			}
			
			return;
		}
		
		if ( typeof(confirmCallback) == 'function' ){
			if ( !confirmCallback(ids) ){
				return;
			}
		}
		//alert(ids);
		params['--selected-ids'] = ids.join("\n");
		
		XataJax.form.submitForm('post', params);
	
	}
	
	/**
	 * @function
	 * @memberOf XataJax.actions
	 * @description
	 * Checks to see if the given element contains any selector checkboxes for selecting records.
	 *
	 * @param {HTMLElement} container  The html element to check.
	 * @return {boolean} True if it contains at least one selector checkbox.
	 */
	function hasRecordSelectors(/*HTMLElement*/container){
		return ($('input.rowSelectorCheckbox', container).size() > 0);
	}
	
	
	/**
	 * @function
	 * @memberOf XataJax.actions
	 * @description
	 * Handles a selected action that was triggered using a given link.  The link itself
	 * should contain the information about the action to be performed.
	 *
	 * @param {HTMLElement} aTag The html link that was clicked to invoke the action.  The 
	 *   href tag for this link is used as the target action to perform - except the parameters
	 *   are parsed out so that the action will ultimately be submitted via POST.
	 *
	 * @param {String} selector The selector to the container thart contains the checkboxes
	 *   representing the selected records on which to perform this action.
	 */
	function handleSelectedAction(/*HTMLElement*/ aTag, selector){
		var href = $(aTag).attr('href');
		var confirmMsg = $(aTag).attr('data-xf-confirm-message');
		var confirmCallback = null;
		if ( confirmMsg ){
			confirmCallback = function(){
				return confirm(confirmMsg);
			};
		}
		//alert(confirmMsg);
		var params = XataJax.util.getRequestParams(href);

		XataJax.actions.doSelectedAction(params, $(selector), confirmCallback);
		return false;
	
	}
	
})();
//END xatajax.actions.js

}


//START xataface/modules/g2/advanced-find.js
if ( typeof(window.__xatajax_included__['xataface/modules/g2/advanced-find.js']) == 'undefined'){window.__xatajax_included__['xataface/modules/g2/advanced-find.js'] = true;






(function(){
	var $ = jQuery;
	
	$(document).ajaxError(function(e, xhr, settings, exception) {
	   if ( !console ) return;
	   console.log(e);
	   console.log(xhr);
	   console.log(settings);
	   console.log(exception);
	});
	
	
	var g2 = XataJax.load('xataface.modules.g2');
	g2.AdvancedFind = AdvancedFind;
	
	function AdvancedFind(/**Object*/ o){
		this.table = $('meta#xf-meta-tablename').attr('content');
		this.el = $('<div>').addClass('xf-advanced-find').css('display','none').get(0);

		$.extend(this, o);
		this.loaded = false;
		this.loading = false;
		this.installed = false;
	}
	
	$.extend(AdvancedFind.prototype, {
	
		load: load,
		ready: ready,
		show: show,
		hide: hide,
		install: install
	});
	
	
	function load(/**Function*/ callback){
		callback = callback || function(){};
		var self = this;
		$(this.el).load(DATAFACE_SITE_HREF+'?-table='+encodeURIComponent(this.table)+'&-action=g2_advanced_find_form', function(){
			
			var params = XataJax.util.getRequestParams();
			var widgets = [];
			var formEl = this;
			
			$('[name]', this).each(function(){
				if ( params[$(this).attr('name')] ){
					$(this).val(params[$(this).attr('name')]);
				}
				var widget = null;
				
				if ( $(this).attr('data-xf-find-widget-type') ){
					widget = $(this).attr('data-xf-find-widget-type');
				} else if ( $(this).get(0).tagName.toLowerCase() == 'select' ){
					widget = 'select';
				} 
				if ( widget ){
					widgets.push('xataface/findwidgets/'+widget+'.js');
				}
				
			});
			
			
			
			if ( widgets.length > 0 ){
				XataJax.util.loadScript(widgets.join(','), function(){
					self.loaded = true;

					callback.call(self);
					
					$('[name]', formEl).each(function(){
						if ( params[$(this).attr('name')] ){
							$(this).val(params[$(this).attr('name')]);
						}
						var widget = null;
						
						if ( $(this).attr('data-xf-find-widget-type') ){
							widget = $(this).attr('data-xf-find-widget-type');
						} else if ( $(this).get(0).tagName.toLowerCase() == 'select' ){
							widget = 'select';
						} 
						if ( widget ){
							var w = new xataface.findwidgets[widget]();
							w.install(this);
							
						}
						
					});
					
					
					$('button.xf-advanced-find-clear', formEl).click(function(){
						$('input[name],select[name]', formEl).val('');
						return false;
					});
					
					$('button.xf-advanced-find-search', formEl).click(function(){
						$(this)
							
							.parents('form').submit();
					});
					
					$(self).trigger('onready');
						
				});
			} else {
				
				self.loaded = true;
				callback.call(self);
				$(self).trigger('onready');
			}
		});
	}
	
	
	function ready(/**Function*/ callback){
		if ( this.loaded ){
			callback.call(this);
		} else {
			$(this).bind('onready', callback);
			if ( !this.loading ){
				this.load();
			}
		}
		
	}
	
	function install(){
		if ( this.installed ) return;
		$(this.el).insertAfter('a.xf-show-advanced-find');
		this.installed = true;
		
	}
	
	function show(){
		//alert('hello');
		this.ready(function(){
			//alert('now');
			if ( !this.loaded ) throw "Cannot show advanced find until it is ready.";
			//alert('here');
			if ( !this.installed ) this.install();
			//alert('here');
			if ( !$(this.el).is(':visible') ){
				//alert(this.el);
				$(this.el).slideDown(function(){
					// Make sure it is only the width of the window.
					var x = $(this).offset().left;
					//alert(x);
					$(this).width($(window).width()-x-5);
				});
			}
		});
	}
	
	function hide(){
		this.ready(function(){
			if ( !this.loaded || !this.installed ) return;
			if ( $(this.el).is(':visible') ){
				$(this.el).slideUp();
			}
		});
	}
	
	

})();
//END xataface/modules/g2/advanced-find.js

}
(function(){
	var $ = jQuery;
	
	
	/**
	 * Help to format the page when it is finished loading.  Attach listeners
	 * etc...
	 */
	$(document).ready(function(){
	
		// START Left column fixes
		/**
		 * We need to hide the left column if there is nothing in it.  Helps for 
		 * page layout.
		 */
		$('#dataface-sections-left-column').each(function(){
			var txt = $(this).text().replace(/^\W+/,'').replace(/\W+$/);
			if ( !txt ) $(this).hide();
		});
		
		$('#left_column').each(function(){
			var txt = $(this).text().replace(/^\W+/,'').replace(/\W+$/);
			if ( !txt ) $(this).hide();
		});
		
		// END Left column fixes
	
	
	
		// START Prune List Actions
		/**
		 * We need to hide the list actions that aren't relevant.
		 */
		var resultListTable = $('#result_list').get(0);
		
		if ( resultListTable ){
			var rowPermissions = {};
			$('input.rowSelectorCheckbox[data-xf-permissions]', resultListTable).each(function(){
				var perms = $(this).attr('data-xf-permissions').split(',');
				$.each(perms, function(){
					rowPermissions[this] = 1;
				});
			});
			// We need to remove any actions for which there are no rows that can be acted upon
			$('.result-list-actions li.selected-action').each(function(){
				var perm = $(this).children('a').attr('data-xf-permission');
				if ( perm && !rowPermissions[perm]){
					$(this).hide();
				}
				
			});
			
			
		}
			
		// END Prune List Actions


		// START Adjust List cell sizes
		/**
		 * We need to improve the look of the list view so we'll calculate some 
		 * appropriate sizes for the cells.
		 */
		 /*
		$('table.listing td.field-content, table.listing th').each(function(){
			if ( $(this).width() > 200 ){
				//alert($(this).width());
				
				var div = $('<div></div')
					.css({
						'white-space': 'normal',
						'height': '1em',
						'overflow': 'hidden',
						'padding':0,
						'margin':0
					});
				$(div).append($(this).contents());
				$(this).empty();
				$(this).append(div);
				$(this).css({
					'white-space':'normal !important'
				});
				//$(this).css('white-space','normal !important').css('height','1em').css('overflow','hidden');
				
			}
		});
		*/
		$('table.listing > tbody > tr > td span[data-fulltext]').each(function(){
		    var span = this;
		    $(span).addClass('short-text');
		    var moreDiv = null;
		    var td = $(this).parent();
		    while ( $(td).prop('tagName').toLowerCase() != 'td' ){
		        td = $(td).parent();
		    }
		    td = $(td).get(0);
		    $(td).css({
                        //position : 'relative',
                        //display: 'block'
                    });
		    var moreButton = $('<a>')
		        .addClass('listing-show-more-button')
		        .attr('href','#')
		        .html('...')
		        .click(showMore).
		        get(0);
		    var lessButton = $('<a href="#" class="listing-show-less-button">...</a>').click(showLess).get(0);
		    
		    function showMore(){
		        var width = $(td).width();
		        
		        if ( moreDiv == null ){
		            var divContent = null;
		            
		            var parentA = $(span).parent('a');
		            if ( parentA.size() > 0 ){
		                
		                divContent = parentA.clone();
		                $('span', divContent)
		                    .removeClass('short-text')
		                    .removeAttr('data-fulltext')
		                    .text($(span).attr('data-fulltext'));
		            } else {
		                divContent = $(span).clone();
		                divContent.removeClass('short-text').text($(span).attr('data-fulltext'));
		            }
		                
		            var divWidth = width-$(moreButton).width()-10;
		            moreDiv = $('<div style="white-space:normal;"></div>')
		                .css('width', divWidth)
		                .append(divContent)
		                .addClass('full-text')
		                .get(0);
		            $(td).prepend(moreDiv);
		        }
		        $(td).addClass('expanded');
		        
		        
		        return false;
		        
		    }
		    
		    function showLess(){
		        $(td).removeClass('expanded');
		        return false;
		    }
		    $(td).append(moreButton);
		    $(td).append(lessButton);
		});
		$('table.listing td.row-actions-cell').each(function(){
		
			var reqWidth = 0;
			$('.row-actions a', this).each(function(){
				reqWidth += $(this).outerWidth(true);
			});
			
			$(this).width(reqWidth);
			$(this).css({
				padding: 0,
				margin: 0,
				'padding-right': '5px',
				'padding-top': '3px'
			});
			
		});


		// END Adjust List Cell Sizes
		
		
		// START Set Up Drop-Down Actions
		/**
		 * Some of the actions are drop-down menus.  We need to attach the 
		 * appropriate behaviors to them and also show the corrected "selected"
		 * state depending on which action or mode is currently selected.
		 */
		$(".xf-dropdown a.trigger")
			.each(function(){
				var atag = this;
				$(this).parent().find('ul li.selected > a').each(function(){
					$(atag).append(': '+$(this).text());
					$(atag).parent().addClass('selected');
				});
			})
			.append('<span class="arrow"></span>')
			.click(function() { //When trigger is clicked...
			
				var atag = this;
				//Following events are applied to the subnav itself (moving subnav up and down)
				if ( $(this).hasClass('menu-visible') ){
					$(this).removeClass('menu-visible');
					$(this).parent().find(">ul").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up
					$('body').unbind('click.xf-dropdown');
				} else {
					$(this).addClass('menu-visible');
					$(this).parent().find(">ul")
						.each(function(){
							if ( $(atag).hasClass('horizontal-trigger') ){
								//alert($(atag).offset().top);
								var pos = $(atag).position();
								$(this)
									.css('top',0)
									.css('left', 20)
									;
									
								//$(this).offset({top: pos.top-100, left: pos.left+$(atag).outerWidth()});
								
							}
							$(this).css('z-index', 10000);
						
						})
						.slideDown('fast', function(){
							$('body').bind('click.xf-dropdown', function(){
								$(atag).trigger('click');
							});
						
						}).show(); //Drop down the subnav on click
					
				}
				return false;
				
	
			//Following events are applied to the trigger (Hover events for the trigger)
			})
			.hover(function() { 
					$(this).addClass("subhover"); //On hover over, add class "subhover"
				}, 
				function(){	//On Hover Out
					$(this).removeClass("subhover"); //On hover out, remove class "subhover"
				}
			);
		
		
		// END Set up Drop-down Actions
		
		
		// START PRUNE List actions further
		/**
		 * We previously pruned the list actions based on permissions.  Now we're going 
		 * to prunt them if there are no checkboxes. 
		 */
		//check to see if there are any checkboxes available to select
		var hasResultListCheckboxes = XataJax.actions.hasRecordSelectors($('.resultList'));
		var hasRelatedListCheckboxes = XataJax.actions.hasRecordSelectors($('.relatedList'));
		
		
		$('.selected-action a')
			.each(function(){
				if ( !hasResultListCheckboxes ){
					$(this).parent().hide();
				}
			})
			.click(function(){
				XataJax.actions.handleSelectedAction(this, '.resultList');
				return false;
			}
		);
		
		$('.related-selected-action a')
			.each(function(){
				if ( !hasRelatedListCheckboxes ){
					$(this).parent().hide();
				}
			})
			.click(function(){
				XataJax.actions.handleSelectedAction(this, '.relatedList');
				return false;
			}
		);
		
		// END PRUNE List actions further
		
		
		// Handler to set the size of the button bars and stay in correct place
		// when scrolling
		$('.xf-button-bar').each(function(){
			var bar = this;
			var container = $(bar).parent();
			var containerOffset = $(container).offset();
			if ( containerOffset  == null ) containerOffset = {left:0, top:0};
			var parentWidth = $(container).width();
			var rightBound = containerOffset.left+parentWidth;
			var windowWidth = $(window).width();
			var pos = $(this).offset();
			var left = pos.left;
			var screenWidth = $(window).width();
			//alert(screenWidth);
			var outerWidth = $(this).outerWidth();
			var excess = outerWidth+pos.left-screenWidth;
			if ( excess > 0 ){
				var oldWidth = $(this).width();
				$(this).width(oldWidth-excess);
				var newWidth = oldWidth-excess;
			}
			//$(this).outerWidth(screenWidth-pos.left);
			
			$(window).scroll(function(){
			
				var container = $(bar).parent();
				var containerOffset = $(container).offset();
				if ( containerOffset == null ) containerOffset = {left:0, top:0};
				var leftMost = containerOffset.left;
				var rightMost = leftMost + $(container).innerWidth();
				
				var currMarginLeft = $(bar).css('margin-left');
				
				var scrollLeft = $(window).scrollLeft();
				
				
				if ( scrollLeft < left ){
					$(bar).css('margin-left', -30);

					$(bar).width(Math.min(newWidth+scrollLeft, $(container).innerWidth()-10));
				} else if ( scrollLeft < excess + 60 ){
					$(bar).css('margin-left', scrollLeft-left-30);
					
				}
				
			});
			
		});
		
		
		// Make sure the list view menu doesn't show up if there's only 
		// one option in it
		$('.list-view-menu').each(function(){
			var self = this;
			if ( $('.action-sub-menu', this).children().size() < 2 ){
				$(self).hide();
			}
		
		});
		
		
		// If there is only one collapsible sidebar in a form, then we remove it
		$('form h3.Dataface_collapsible_sidebar').each(function(){
			var siblings = $(this).parent().find('>h3.Dataface_collapsible_sidebar:visible');
			if ( siblings.size() <= 1 ) $(this).hide();
		});
		
		
		$('.xf-save-new-related-record a').click(function(){
			$('form input[name="-Save"]').click();
			return false;
		});
		
		$('.xf-save-new-record a').click(function(){
			$('form input[name="--session:save"]').click();
			return false;
		});
		
		
		// START Result Controller
		/**
		 * We are handling the result controller differently in this version.
		 * We provide a popup that allows the user to change the start and limit
		 * fields with a popup dialog.
		 */
		
		$('.result-stats').each(function(){
			if ( $(this).hasClass('details-stats') ) return;
			var resultStats = this;
                        var isRelated = $(resultStats).hasClass('related-result-stats');
			var start = $('span.start', this).text().replace(/^\W+/,'').replace(/\W+$/);
			var end = $('span.end', this).text().replace(/^\W+/,'').replace(/\W+$/);
			var found = $('span.found', this).text().replace(/^\W+/,'').replace(/\W+$/);
			var limit = $('.limit-field input').val();
			
			start = parseInt(start)-1;
			end = parseInt(end);
			found = parseInt(found);
			limit = parseInt(limit);

			$(this).css('cursor', 'pointer');
			
			$(this).click(function(){
				
				var div = $('<div>')
					.addClass('xf-change-limit-dialog')
					;
					
				var label = $('<p>Show <input class="limitter" type="text" value="'+(limit)+'" size="2"/> per page starting at <input type="text" value="'+start+'" class="starter" size="2"/> </p>');
				$('input.limitter', label).change(function(){
				
					var query = XataJax.util.getRequestParams();
                                        var limitParam = '-limit';
                                        if ( isRelated ){
                                            limitParam = '-related:limit';
                                        }
					query[limitParam] = $(this).val();
					window.location.href = XataJax.util.url(query);
				}).css({
					'font-size': '12px'
				});
				$('input.starter', label).change(function(){
				
					var query = XataJax.util.getRequestParams();
                                        var skipParam = '-skip';
                                        if ( isRelated ){
                                            skipParam = '-related:skip';
                                        }
					query[skipParam] = $(this).val();
					window.location.href = XataJax.util.url(query);
				}).css({
					'font-size': '12px'
				});
				
				div.append(label);
				var offset = $(resultStats).offset();
				
				
				
				$('body').append(div);
				
				$(div).css({
					position: 'absolute',
					top: offset.top+$(resultStats).height(),
					left: Math.min(offset.left, $(window).width()-275),
					'background-color': '#bbccff',
					'z-index': 1000,
					'padding': '2px 5px 2px 10px',
					'border-radius': '5px'
				});
				$(div).show();
				$(div).click(function(e){
					e.preventDefault();
					e.stopPropagation();
				});
				
				function onBodyClick(){
					$(div).remove();
					$('body').unbind('click', onBodyClick);
				}
				setTimeout(function(){
					$('body').bind('click', onBodyClick);
				}, 1000);
				
				
			});
			
		});
		
		
		$('.details-stats').each(function(){
			var resultStats = this;
			var cursor = $('span.cursor', this).text();
			var found = $('span.found', this).text();
			cursor = parseInt(cursor);
			found = parseInt(found);
			$(this).click(function(){
				
				var div = $('<div>')
					.addClass('xf-change-limit-dialog')
					;
					
				var label = $('<p>Show <input class="limitter" type="text" value="'+(cursor)+'" size="2"/> of '+found+' </p>');
				$('input.limitter', label).change(function(){
				
					var query = XataJax.util.getRequestParams();
					query['-cursor'] = parseInt($(this).val())-1;
					window.location.href = XataJax.util.url(query);
				}).css({
					'font-size': '12px'
				});
				
				
				div.append(label);
				var offset = $(resultStats).offset();
				
				
				
				$('body').append(div);
				
				$(div).css({
					position: 'absolute !important',
					top: offset.top+$(resultStats).height(),
					left: Math.min(offset.left, $(window).width()-150),
					'background-color': '#bbccff',
					'z-index': 1000,
					'padding': '2px 5px 2px 10px',
					'border-radius': '5px'
				});
				$(div).show();
				$(div).click(function(e){
					e.preventDefault();
					e.stopPropagation();
				});
				
				function onBodyClick(){
					$(div).remove();
					$('body').unbind('click', onBodyClick);
				}
				setTimeout(function(){
					$('body').bind('click', onBodyClick);
				}, 1000);
				
				
			})
			.css('cursor', 'pointer')
			;
			
		
		});
		
		// END Result Controller
		
		// Handle search
		
		(function(){
			var searchField = $('.xf-search-field').parents('form').submit(function(){
				$(this).find(':input[value=""]').attr('disabled', true);
			});
		})();
		
		
		
		// Handle navigation storage.
		(function(){
			if ( typeof(sessionStorage) == 'undefined' ){
				sessionStorage = {};
			}
			
			
			function parseString(str){
				var parts = str.split('&');
				var out = [];
				$.each(parts, function(){
					var kv = this.split('=');
					out[decodeURIComponent(kv[0])] = decodeURIComponent(kv[1]);
				});
				return out;
			}
			
			var currTable = $('meta#xf-meta-tablename').attr('content');
			
			if ( currTable ){
				var currSearch = $('meta#xf-meta-search-query').attr('content');
				var currSearchUrl = window.location.href;
				var searchSelected = false;
				if ( !currSearch ){
					currSearch = sessionStorage['xf-currSearch-'+currTable+'-params'];
					currSearchUrl = sessionStorage['xf-currSearch-'+currTable+'-url'];
					
				} else {
					searchSelected = true;
					sessionStorage['xf-currSearch-'+currTable+'-params'] = currSearch;
					sessionStorage['xf-currSearch-'+currTable+'-url'] = currSearchUrl;
					
				}
				if ( currSearch ){
					var item = $('<li>');
					if ( searchSelected ) item.addClass('selected');
					var a = $('<a>')
						.attr('href', currSearchUrl)
						.attr('title', 'View Search results')
						.text('Search Results');
					item.append(a);
					
					$('.tableQuicklinks').append(item);
				}
				
				
				
				var currRecord = $('meta#xf-meta-record-title').attr('content');
				var currRecordUrl = window.location.href;
				var recordSelected = false;
				if ( !currRecord ){
					currRecord = sessionStorage['xf-currRecord-'+currTable+'-title'];
					currRecordUrl = sessionStorage['xf-currRecord-'+currTable+'-url'];
					
				} else {
					recordSelected = true;
					sessionStorage['xf-currRecord-'+currTable+'-title'] = currRecord;
					sessionStorage['xf-currRecord-'+currTable+'-url'] = currRecordUrl;
					
				}
				
				
				// Record the parent record when clicking on related links.  This is used
				// by the navigator
				var currRecordId = $('meta#xf-meta-record-id').attr('content');
				if ( currRecordId ){
					(function(){

						$('a.xf-related-record-link[data-xf-related-record-id]').click(function(){
							//alert('here');
							var idKey = 'xf-parent-of-'+$(this).attr('data-xf-related-record-id');
							var idUrl = 'xf-parent-of-url-'+$(this).attr('data-xf-related-record-id');
							var idTitle = 'xf-parent-of-title-'+$(this).attr('data-xf-related-record-id');
							sessionStorage[idKey] = currRecordId;
							sessionStorage[idUrl] = currRecordUrl;
							sessionStorage[idTitle] = currRecord;
							
							return true;
							
						});
					
					})();
					
					
					
					
				}
				
				
				
				
				if ( currRecord ){
					var isChildRecord = false;
					if ( currRecordId ){
						(function(){
						
							var idKey = 'xf-parent-of-'+currRecordId;
							var idUrl = 'xf-parent-of-url-'+currRecordId;
							var idTitle = 'xf-parent-of-title-'+currRecordId;
							//sessionStorage[idKey] = currRecordId;
							//sessionStorage[idUrl] = currRecordUrl;
							//sessionStorage[idTitle] = currRecord;
						
						
							if ( sessionStorage[idUrl] ){
								var item = $('<li>');
								//if ( recordSelected ) item.addClass('selected');
								var a = $('<a>')
									.attr('href', sessionStorage[idUrl])
									.attr('title', sessionStorage[idTitle])
									.text(sessionStorage[idTitle]);
								item.append(a);
								
								$('.tableQuicklinks').append(item);
								isChildRecord = true;
							}
						
						})();
					
					
					}
				
				
					var item = $('<li>');
					if ( recordSelected ) item.addClass('selected');
					var a = $('<a>')
						.attr('href', currRecordUrl)
						.attr('title', currRecord)
						.text(currRecord);
					if ( isChildRecord ){
						$(a).addClass('xf-child-record');
					}
					item.append(a);
					
					$('.tableQuicklinks').append(item);
				}
				
				
				
				var g2 = XataJax.load('xataface.modules.g2');
				var advancedFindForm = new g2.AdvancedFind({});
					
				function handleShowAdvancedFind(){
					advancedFindForm.show();
					$(this).text('Hide Advanced Search');
					$(this).unbind('click', handleShowAdvancedFind);
					$(this).bind('click', handleHideAdvancedFind);
				};
				
				function handleHideAdvancedFind(){
					advancedFindForm.hide();
					$(this).text('Advanced Search');
					$(this).unbind('click', handleHideAdvancedFind);
					$(this).bind('click', handleShowAdvancedFind);
				}
				
				$('a.xf-show-advanced-find').bind('click', handleShowAdvancedFind);
				
				
				
				
				
			}
		})();
		
		
		
		
		
	
				
			
			
		
		
		
	});
	
	
	
	
	
	
})();
//END xataface/modules/g2/global.js

}

//START swete/global.js
if ( typeof(window.__xatajax_included__['swete/global.js']) == 'undefined'){window.__xatajax_included__['swete/global.js'] = true;
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


//START swete/BackgroundProcess.js
if ( typeof(window.__xatajax_included__['swete/BackgroundProcess.js']) == 'undefined'){window.__xatajax_included__['swete/BackgroundProcess.js'] = true;
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



(function(){
		
	var $ = jQuery;
	var swete = XataJax.load('swete');
	swete.BackgroundProcess = BackgroundProcess;
	
	function BackgroundProcess(o){
		
		this.menuEl = $('<div>').addClass('background-processes-menu');
		
		this.runningMessageEl = $('<div>').addClass('background-processes-running-message');
		this.menuEl.append(this.runningMessageEl);
	
	}
	
	(function(){
		$.extend(BackgroundProcess.prototype, {
			installMenu: installMenu,
			runningProcessInfo: null,
			recentProcessInfo: null,
			update: update,
			_intervalId: null,
			checkServer: checkServer,
			setUpdateFrequency: setUpdateFrequency
		});
		
		
		function installMenu(){
			
			$('body').append(this.menuEl);
		}
		
		function update(){
			if ( this.running ){
				this.runningMessageEl.text(this.running.status_message);
			} else {
				this.runningMessageEl.text('No processes currently running');
			}
		}
		
		
		function checkServer(){
		
			var q = {
				'-action': 'swete_background_process_info'
			};
			var self = this;
			$.get(DATAFACE_SITE_HREF, q, function(res){
				self.running = res.running;
				self.recent = res.recent;
				self.update()
			});
		}
		
		
		function setUpdateFrequency(millis){
			if ( this._intervalId != null ){
				clearInterval(this._intervalId);
			}
			if ( millis != null ){
				var self = this;
				this._intervalId = setInterval(function(){
					self.checkServer();
				}, millis);
			}
		}
	})();
	

})();
//END swete/BackgroundProcess.js

}
(function(){
	var $ = jQuery;
	//var BackgroundProcess = XataJax.load('swete.BackgroundProcess');
	
	//var bgProcess = new BackgroundProcess();
	//bgProcess.installMenu();
	//bgProcess.setUpdateFrequency(5000);
})();
//END swete/global.js

}

//START RecordDialog/RecordDialog.js
if ( typeof(window.__xatajax_included__['RecordDialog/RecordDialog.js']) == 'undefined'){window.__xatajax_included__['RecordDialog/RecordDialog.js'] = true;




(function($){
	if ( typeof(window.xataface) == 'undefined' ){
		window.xataface = {};
	}
	
	window.xataface.RecordDialog = RecordDialog;
	
	
	/**
	 * @name Callback
	 * @memberOf xataface.RecordDialog
	 * @function
	 * @description The callback function that is to be passed to a RecordDialog so that it can be
	 *   called on completion.
	 *
	 * @param {Object} o
	 * @param {String} o.__title__ The record title of the record that was just saved.
	 * @param {String} o.__id__ The id of the record that was just saved.
	 * @param {String} o.$$key$$ Each field of the record is included in this data as key value pairs.
	 */


	/**
	 * @name RecordDialog
	 * @class
	 * @memberOf xataface
	 * @description A dialog that opens a new record form or edit record form as an internal
	 * 	jQuery dialog.
	 *
	 * @property {HTMLElement} el The HTML element that is used to house this dialog.
	 * @property {String} recordid The ID of the record to edit (if null then this will be a new record form).
	 * @property {String} table The table of the record to edit or to add to.
	 * @property {String} baseURL The base URL of the RecordDialog folder.  Default is DATAFACE_URL+'/js/RecordDialog'
	 * 
	 * @param {Object} o
	 * @param {String} o.recordid The Record ID of the record to edit.
	 * @param {String} o.table The name of the table to add new records to.
	 * @param {xataface.RecordDialog.Callback} o.callback The callback method to be called when saving is complete.
	 *
	 */
	function RecordDialog(o){
		this.el = document.createElement('div');
		this.recordid = null;
		this.table = null;
		this.baseURL = DATAFACE_URL+'/js/RecordDialog';
		this.formChanged = false;
		for ( var i in o ) this[i] = o[i];
	};
	
	RecordDialog.prototype = {
	
	
		/**
		 * @function
		 * @name display
		 * @memberOf xataface.RecordDialog#
		 * @description Displays the record dialog.
		 */
		display: function(){
			var dialog = this;
			$(this.el).load(this.baseURL+'/templates/dialog.html', function(){
				var frame = $(this).find('.xf-RecordDialog-iframe')
					.css({
						'width': '100%',
						'height': '96%',
						'padding':0,
						'margin':0,
						'border': 'none'
					})
					
					.attr('src', dialog.getURL());
				/*	
				var $scroller = $(this).find('.xf-RecordDialog-iframe-scroller')
					.css({
					
						'height' : '96%',
						'width' : '100%',
						'padding' : 0,
						'margin': 0
					});
				*/	
				
				$(frame).hide();
				//alert(frame.attr('width'));
				frame.load(function(){
					$(frame).hide();
					dialog.formChanged = false;
					var iframe = $(this).contents();
					try {
						var parsed  = null;
						
						eval('parsed = '+iframe.text()+';');
						if ( parsed['response_code'] == 200 ){
							
							// We saved it successfully
							// so we can close our window
							if ( dialog.callback ){
								dialog.callback(parsed['record_data']);
							}
							
							$(dialog.el).dialog('close');
							if ( parsed['response_message'] ){
								alert(parsed['response_message']);
							}
							return;
						
						}
					} catch (err){
						//alert(err);
					
					}
					
					var portalMessage = iframe.find('.portalMessage');
					portalMessage.detach();
					
					iframe.find('.xf-button-bar').remove();
					
					var dc =iframe.find('.documentContent');
					if ( dc.length == 0 ) dc = iframe.find('#main_section');
					if ( dc.length == 0 ) dc = iframe.find('#main_column');
					
					dc.remove();
					dc.prepend(portalMessage);
					
					var ibody = iframe.find('body');
					var hidden = $(':hidden', ibody);
					
					iframe.find('body').empty();
					$('script', dc).remove();	// So script tags don't get run twice.
					dc.appendTo(ibody);
					hidden.each(function(){
						if ( this.tagName == 'SCRIPT'  ){
							return;
						}
						//alert('About to append tag: '+this.tagName+' '+ $(this).text());
						$('script',this).remove();
						$(this).appendTo(ibody);
						$(this).hide();
						
					});
					//hidden.appendTo(ibody);
					//hidden.hide();
					$('#details-controller, .contentViews, .contentActions', ibody).hide();
					$(ibody).css('background-color','transparent');
					$('.documentContent', ibody).css({
						'border':'none',
						'margin' : 0,
						'padding' : 0,
						'background-color': 'transparent',
						'overflow' : 'scroll'
					});
					$(frame).fadeIn(function(){
						dc.height($(frame).parent().innerHeight() - 25);
					});
					
					
					$('input, textarea, select', ibody).change(function(){
						dialog.formChanged = true;
					});
					
						
				});
					
				
			});
			$(this.el).appendTo('body');
			
			//function noScrollTouch(e){
			//	e.preventDefault();
			//}
			
			
			$('body').addClass('stop-scrolling');
			//$(document).bind('touchstart touchmove', noScrollTouch);
			
			$(this.el).dialog({
				beforeClose: function(){
					
					$('body').removeClass('stop-scrolling')
					//$(document).unbind('touchstart touchmove', noScrollTouch);

					if ( dialog.formChanged ){
						return confirm('You have unsaved changes.  Clicking "OK" will discard these changes.  Do you wish to proceed?');
						
					}
				},
				buttons: {
					OK : function(){
						
						if ( dialog.callback ){
							dialog.callback();
						}
						$(this).dialog('close');
					}
					
				},
				height: $(window).height()-25,
				width: $(window).width()-25,
				title: (this.recordid?'Edit '+this.table+' Record':'Create New '+this.table+' Record'),
				modal: true
			});
			
		},
		
		/**
		 * @function
		 * @name getURL
		 * @memberOf xataface.RecordDialog
		 * @description Gets the URL to the form for this dialog.
		 * @returns {String} The url for the form of this dialog.
		 */
		getURL: function(){
			var action;
			if ( !this.recordid ){
				action='new';
			} else {
				action='edit';
			}
			var url = DATAFACE_SITE_HREF+'?-table='+encodeURIComponent(this.table)+(this.recordid?'&-recordid='+encodeURIComponent(this.recordid):'')+'&-action='+encodeURIComponent(action)+'&-response=json';
			
			if ( typeof(this.params) == 'object' ){
				// We have some parameters to pass along
				
				$.each(this.params, function(key,val){
					url += '&'+encodeURIComponent(key)+'='+encodeURIComponent(val);
				});
			}
			return url;
		
		}
	};
	
	RecordDialog.constructor = RecordDialog;
	
	
	
	$.fn.RecordDialog = function(options){
		return this.each(function(){
		
			$(this).click(function(){
				var d = new RecordDialog(options);
				d.display();
			});
		});
	};
	
	
	
})(jQuery);
//END RecordDialog/RecordDialog.js

}
				if ( typeof(XataJax) != "undefined"  ) XataJax.ready();
				