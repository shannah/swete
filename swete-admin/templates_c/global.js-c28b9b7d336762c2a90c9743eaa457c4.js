if ( typeof(window.console)=='undefined' ){window.console = {log: function(str){}};}if ( typeof(window.__xatajax_included__) != 'object' ){window.__xatajax_included__={};};
(function(){
					var headtg = document.getElementsByTagName("head")[0];
					if ( !headtg ) return;
					var linktg = document.createElement("link");
					linktg.type = "text/css";
					linktg.rel = "stylesheet";
					linktg.href="/swete-git/swete-admin/index.php?-action=css&--id=Background-af0f21a43fdeb34e3dfa71e0cdc47280";
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

//START swete/actions/import_translations.js
if ( typeof(window.__xatajax_included__['swete/actions/import_translations.js']) == 'undefined'){window.__xatajax_included__['swete/actions/import_translations.js'] = true;
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


//START xataface/modules/uitk/components/UIForm.js
if ( typeof(window.__xatajax_included__['xataface/modules/uitk/components/UIForm.js']) == 'undefined'){window.__xatajax_included__['xataface/modules/uitk/components/UIForm.js'] = true;
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */





//START xataface/model/Model.js
if ( typeof(window.__xatajax_included__['xataface/model/Model.js']) == 'undefined'){window.__xatajax_included__['xataface/model/Model.js'] = true;




(function(){
	var $ = jQuery;
	var model = XataJax.load('xataface.model');
	model.Model = Model;
	Model.wrap = wrap;
	Model.addProperty = addProperty;
	Model.addProperties = addProperties;
	
	
	
	
	
	
	/**
	 * @function 
	 * @memberOf xataface.model.Model
	 * @name wrap
	 * @description Wraps an object in a Model object.   If the provided object
	 * is a Model already, it just returns that model.
	 *
	 * @param {mixed} o The object to be wrapped.
	 * @returns {Model} The model object that wrapped the input object.
	 */
	function wrap(o){
		if ( o instanceof Model ) return o;
		return new Model({
			data : o
		});
	}
	
	/**
	 * @function
	 * @memberOf xataface.model.Model
	 * @name addProperty
	 * @description Adds a property to a class.
	 * @param {Object} o The object that the property is being added to.
	 * @param {String} name The name of the property.
	 * @param {mixed} defaultValue The default value of the property.
	 * @returns {void}
	 */
	function addProperty(o, name){
		Object.defineProperty(o, name, {
			enumerable : true,
			configurable : true,
			get : function(){
				return this.get(name);
			},
			set : function(value){
				return this.set(name, value);
			}
		
		});
	}
	
	/**
	 * @function
	 * @memberOf xataface.model.Model
	 * @name addProperties
	 * @description Adds a bunch of properties to an object.
	 *
	 * @param {Object} o The object to which properties are being added.
	 * @param {Object} properties  An object whose keys are the properties to be added
	 * 	and whose values are the corresponding default values.
	 * @returns {void}
	 */
	function addProperties(o, properties){
		$.each(properties, function(k, name){
			Model.addProperty(o, name);
		});
	}
	
	/**
	 * @class
	 * @name Model
	 * @memberOf xataface.model
	 * @description A model object that can sends changed events to notify listeners
	 *  when data in the model has changed.
	 *
	 */
	function Model(/*Object*/ o){
		if ( typeof(o) == 'undefined' ){
			o = {};
		}
		if ( typeof(o.data) != 'object' ){
			o.data = {};
		}
		this.data = o.data;
		delete o.data;
		$.extend(this, o);
		
		
		
		$(this).bind('changed', function(evt, data){
			if ( typeof(data) != 'undefined' ){
				if ( data.ignoreDirtyBit ){
					// If this change was a result of setting
					// the dirty bit, then we'll ignore this
					// event.
					return;
				}
			}
			this.dirty = true;
		});
	}
	
	(function(){
		$.extend(Model.prototype, {
			_inTransaction: false,
			_changedInUpdate : false,
			get: get,
			set: set,
			startUpdate: startUpdate,
			endUpdate: endUpdate,
			
			dirty : false,
			setDirty : setDirty
		});
		
		/**
		 * @function 
		 * @name setDirty
		 * @memberOf xataface.model.Model#
		 * @description Sets the dirty flag to indicate that changes have been made
		 * 	to the model's data since it was last "saved".
		 *
		 * <h3>Events</h3>
		 * <p>This will fire the "changed" event.</p>
		 *
		 * @param {boolean} dirty Whether it is dirty or not.
		 * @returns {xataface.model.Model} Self for chaining.
		 */
		function setDirty(dirty){
			if ( this.dirty != dirty ){
				this.dirty = dirty;
				$(this).trigger('changed', {
					ignoreDirtyBit : true
				});
			}
			return this;
		}
		
		
		
		
		
		
		/**
		 * @function
		 * @name get
		 * @memberOf xataface.model.Model#
		 * @description Gets a data value by name. (key value coding).
		 * @param {String} key The key of the value to return.  If this is omitted, then
		 * 	all values will be returned in an object.
		 *
		 * @returns {mixed} Either the value corresponding value for the provided key or
		 *	an Object of the values if the key is omitted.
		 */
		function get(key){
			if ( typeof(this.data) != 'object' ) return null;
			var self = this;
			

			if ( typeof(key) == 'undefined' ){
				var out = {};
				$.each(this.data, function(k,v){
					out[k] = v;
				});
				return out;
			} else if ( typeof(key) == 'object' && !key.substring ){
				// Key is an object and not a string
				$.each(key, function(k,v){
					key[k] = self.get(k);
				});
				
			} else {
				if ( this.data == null ) return null;
				
				// Handle kvc dot notation.
				if ( key.indexOf('.') != -1 ){
					var keyparts = key.split(/\./);
					var k,o=this.data;
					while ( keyparts.length > 0 ){
						k = keyparts.shift();
						if ( typeof(o) == 'object' ){
							o = o[k];
						} else {
							return null;
						}
						
					}
					
					return o;
				} else {
					return this.data[key];
				}
			}
		}
		
		/**
		 * @function
		 * @name set
		 * @memberOf xataface.model.Model#
		 * @description Sets a key-value pair, or sets multiple key-value pairs depending
		 * 	on the types of the parameters. 
		 *	<p>If <em>key</em> is a String, then this sets the value of that key.  If it
		 *	is an Object, then all of the key-value pairs in the object will be set.</p>
		 *  <h3>Events</h3>
		 *  <p>This fires a "changed" event if the value is different than the previous
		 *	 value for the provided key.  Only one "changed" event will be fired even
		 *   if a set of key-value pairs is provided here.</p>
		 * @see startUpdate() For starting a transaction so that you can make multiple 
		 *	set() calls without firing a "changed" event until the end the subsequent 
		 *	call to endUpdate().
		 * @param {String} key The key to set.  If this is an Object, then it will set
		 *	all key-value pairs in the object.
		 * @param {mixed} val The value to set for the key.
		 * @returns {xataface.model.Model} Self for chaining.
		 */
		function set(key, val){
			var self = this;
			if ( typeof(key) == 'object' && !key.substring ){
				// The key is not a string.
				var changed = false;
				$.each(key, function(k,v){
					if ( self.data[k] != v ){
						changed = true;
						var old = self.data[k];
						self.data[k] = v;
						$(self).trigger('propertyChanged', {
							oldValue : old,
							newValue : v,
							propertyName : k,
							undo : function(){
								self.set(k, old);
							}
						});
					}
				});
				if ( changed ){
					if ( !this._inTransaction ){
						$(this).trigger('changed');
					} else {
						this._changedInUpdate = true;
					}
				}
			} else {
				// The key is a string
				if ( this.data == null ){
					this.data = {};
				}
				if ( val != this.data[key] ){
					var old = self.data[key];
					this.data[key] = val;
					$(self).trigger('propertyChanged', {
						oldValue : old,
						newValue : val,
						propertyName : key,
						undo : function(){
							self.set(key, old);
						}
					});
					if ( !this._inTransaction ){
						
						$(this).trigger('changed');
					} else {
						this._changedInUpdate = true;
					}
				}
			}
			return this;
		}
		
		
		/**
		 * @name startUpdate
		 * @function
		 * @memberOf xataface.model.Model#
		 * @description Starts a transaction so that you can set multiple values
		 *  in this model without a changed event being fired until the next call
		 * 	to endUpdate();
		 * @see endUpdate() To end a transaction
		 *
		 * @returns {xataface.model.Model} Self for chaining.
		 */
		function startUpdate(){
			if ( this._inTransaction ){
				if ( this._changedInUpdate ){
					$(this).trigger('changed');
				} 
			} else {
				this._inTransaction = true;
			}
			this._changedInUpdate = false;
			return this;
		}
		
		
		/**
		 * @name endUpdate
		 * @function
		 * @memberOf xataface.model.Model#
		 * @description Ends a transaction and fires a "changed" event if any changes
		 * have been made since the last call to startUpdate().
		 * @see startUpdate()
		 * @returns {xataface.model.Model} Self for chaining.
		 */
		function endUpdate(){
			this._inTransaction = false;
			if ( this._changedInUpdate ){
				this._changedInUpdate = false;
				$(this).trigger('changed');
			}
			return this;
		}
		
		
		
	})();
})();
//END xataface/model/Model.js

}
(function(){
    var $ = jQuery;
    var Model = xataface.model.Model;
    var pkg = XataJax.load('xataface.modules.uitk.components');
    pkg.UIForm = UIForm;
    
    var counter = 0;
    
    function UIForm(/*Object*/ o){
        this.formId = counter++;
        o = o || {};
        var self = this;
        this.table = null;
        this.query = {};
        this.fields = null;
        this.isNew = false;
        this.showHeadings = true;
        this.showSubheadings = true;
        this.showInternalSubmitButtons = true;
        this.addCancelButton = true;
        this._changed = false;
        this._submitting = false;
        this.cancelAction = this.refresh;
        
        
        
        $.extend(this, o);
        
       
        
        this.el = $('<iframe>')
            .css({
                width : '500px',
                height : '500px'
            })
            .attr('allowTransparency', true)
            .load(function(){
                var url = this.contentWindow.location.search;
                if ( url.indexOf('--saved=1') !== -1 ){
                    $(self).trigger('afterSave');
                }
                $(this.contentWindow.document).find('meta#quickform-error').each(function(){
                    $(self).trigger('error', {
                       message : $(this).attr('value') 
                    });
                });
                $(this.contentWindow.document).find('body').css({
                    'background-color': 'transparent'
                });
                
                if ( !self.showHeadings ){
                    $(this.contentWindow.document).find('h3').hide();
                }
                
                if ( !self.showInternalSubmitButtons ){
                    
                    $(this.contentWindow.document).find('input[type="submit"]').hide();
                }
                
                if ( self.addCancelButton ){
                    
                    $(this.contentWindow.document).find('input[type="submit"]').after('<button class="cancel-btn">Cancel</button>');
                    $(this.contentWindow.document).find('button.cancel-btn').click(function(){
                        self.cancel();
                        return false;
                    })
                }
                
                $(self).trigger('loaded');
                self._changed = false;
                self._submitting = false;
                self.decorateFrame();
                
            })
            .get(0);
       
        
    }
    
    (function(){
        $.extend(UIForm.prototype, {
            refresh : refresh,
            decorateFrame : decorateFrame,
            submit : submit,
            cancel : cancel,
            startObservingTable : startObservingTable,
            stopObservingTable : stopObservingTable,
            getValue : getValue,
            getValues : getValues,
            setValue : setValue,
            setValues : setValues,
            getRecordId : getRecordId
        });
        
        /**
         * @function
         * @memberOf xataface.modules.uitk.components.UIForm#
         * @returns {void}
         */
        function refresh(){
            
            if ( !this.table ){
                throw {
                    code : 500,
                    message : 'No table specified for form'
                };
            }

            if ( !this.query ){
                throw {
                    code : 500,
                    message : 'NO query specified for form'
                }
            }
            
            var q = this.query;
            q['-headless'] = 1;
            if ( !this.isNew ){
                q['-action'] = 'edit';
            } else {
                q['-action'] = 'new';
            }
            q['-table'] = this.table;
            if ( this.fields !== null ){
                q['-fields'] = this.fields.join(' ');
            } else {
                if ( typeof(q['-fields']) !== 'undefined' ){
                    delete q['-fields'];
                }
            }
            var qstr = [];
            for ( var i in q ){
                qstr.push(encodeURIComponent(i)+'='+encodeURIComponent(q[i]));
            }
            qstr = qstr.join('&');
            var url = DATAFACE_SITE_HREF+'?'+qstr;
            $(this.el).attr('src', url);

        }
        
        /**
         * @function
         * @memberOf xataface.modules.uitk.components.UIForm#
         * @returns {void}
         */
        function submit(){
            $(this.el.contentWindow.document).find('form').submit();
        }
        
        function cancel(){
            if ( typeof(this.cancelAction) == 'function' ){
                this.cancelAction.call(this);
            }
            $(this).trigger('afterCancel');
        }
        
        /**
         * @private
         * @returns {Boolean}
         */
        function onSubmit(){
            var evt = {
                code : 200
            };
            
            
            $(this).trigger('beforeSubmit', evt);
            if ( evt.code !== 200 ){
                return false;
            }
            
            this._submitting = true;
            
            return true;
        }   
        
        
        /**
         * @function
         * @memberOf xataface.modules.uitk.components.UIForm#
         * @returns {void}
         */
        function decorateFrame(){
            
            var self = this;
            var iframe = self.el;
            var $form = $(iframe.contentWindow.document).find('form');
            var iframeWin = iframe.contentWindow;
            // Register a submit handler on the form so that 
            // we can handle the submit event of the form
            $form.bind('submit', onSubmit.bind(self));

            // Register change events on the form fields so that we can 
            // prevent people from accidentally leaving the page.
            $form.find(':input').change(function(){
                if ( !self._changed ){
                    self._changed = true;
                    $(self).trigger('change');
                }
            });
            
            iframeWin.onbeforeunload = beforeOnUnload.bind(this);
        }
        
        /**
         * @private
         * @returns {String}
         */
        function beforeOnUnload(){
            if ( this._changed && !this._submitting ){
                return 'This form has unsaved changes that will be lost if you navigate away.  Do you wish to proceed?';
            }
            this.submitting = false;
        }
        
        
        /**
         * @name startObservingTable
         * @memberOf xataface.modules.uitk.components.UITable#
         * @function
         * @param {xataface.modules.uitk.components.UITable} table Another table to observer
         *  for selection events.  When that table fires a selectionChanged event, the 
         *  result set of this table will be updated with a new filter according to the 
         *  associated mapping object.
         * @param {Object} mapping
         * @returns {void}
         */
        function startObservingTable(/*UITable*/ table, /*Object*/ mapping){
            var self = this;
            $(table.model).bind('selectionChanged.UIForm.'+this.formId, function(evt, data){
                if ( data.newValue.length === 0 ){
                    return;
                }
                for ( var k in mapping ){
                    self.query[k] = '='+Model.wrap(data.newValue[0]).get(mapping[k]);
                }
                self.isNew = false;
                self.refresh();
            });
        }
        
        /**
         * @function
         * @memberOf xataface.modules.uitk.components.UITable#
         * @description Stops observing another table for selection changes.  This
         * is the inverse operation of the startObservingTable() method.
         * @param {xataface.modules.uitk.components.UITable} table Another table to stop
         * observing.
         * @returns {void}
         */
        function stopObservingTable(/*UITable*/ table){
            $(table.model).unbind('selectionChanged.UIForm.'+this.formId);
        }
        
        /**
         * @function
         * @memberOf xataface.modules.uitk.components.UIForm#
         * 
         * @returns {String} The record ID of the record that is currently 
         *  the subject of this form.
         */
        function getRecordId(){
            return $(this.el.contentWindow.document)
                    .find('table.xf-form-group[data-xf-record-id]')
                        .attr('data-xf-record-id');
        }
        
        /**
         * @function
         * @memberOf xataface.modules.uitk.components.UIForm#
         * @param {Array} fields The names of the fields whose values to retrieve.  Leave
         *  empty to retrieve all fields.
         * @returns {Object} Key/value pairs of field values.
         */
        function getValues(fields){
            fields = fields || null;
            var out = {};
            $(this.el.contentWindow.document).find('form').find(':input').each(function(){
               var name = $(this).attr('name');
               if ( !name ){
                   return;
               }
               if ( fields !== null && fields.indexOf(name) === -1 ){
                   return;
               }
               var value = $(this).val();
               out[name] = value;
            });
            return out;
        }
        
        /**
         * @function
         * @memberOf xataface.modules.uitk.components.UIForm#
         * 
         * @param {String} name The name of the field whose value to retrieve.
         * @returns {String}
         */
        function getValue(name){
            var out = this.getValues([name]);
            if ( typeof(out[name]) !== 'undefined' ){
                return out[name];
            } else {
                return undefined;
            }
        }
        
        /**
         * @function
         * @memberOf xataface.modules.uitk.components.UIForm#
         * @param {Object} vals Key value pairs to set.
         * @returns {xataface.modules.uitk.components.UIForm} Self for chaining.
         */
        function setValues(vals){
            $(this.el.contentWindow.document).find('form').find(':input').each(function(){
               var name = $(this).attr('name');
               if ( !name ){
                   return;
               }
               if ( typeof(vals[name]) !== 'undefined' ){
                   $(this).val(vals[name]);
               } 
            });
            return this;
        }
        
        /**
         * @function
         * @memberOf xataface.modules.uitk.components.UIForm#
         * @param {String} key The name of the field whose value we're setting.
         * @param {String} val The value of the field that we are setting.
         * @returns {xataface.modules.uitk.components.UIForm} Self for chaining.
         */
        function setValue(key,val){
            var vals = {};
            vals[key] = val;
            this.setValues(vals);
            return this;
        }
        
    })();
    
    
    
    
   
})();

//END xataface/modules/uitk/components/UIForm.js

}


//START xataface/view/View.js
if ( typeof(window.__xatajax_included__['xataface/view/View.js']) == 'undefined'){window.__xatajax_included__['xataface/view/View.js'] = true;



(function(){
	var $ = jQuery;
	var view = XataJax.load('xataface.view');
	var model = XataJax.load('xataface.model');
	var Model = model.Model;
	var $m = Model.wrap;
	view.View = View;
	
	
	function View(/*Object*/ o){
		$.extend(this, o);
		var self = this;
		this.onChangeHandler = function(){
			self.update();
		}
		if ( this.model != null ){
			var m = this.model;
			this.model = null;
			this.setModel(m);
			//$(this.model).bind('changed', this.onChangeHandler);
		}
		
		if ( this.el == null ){
			this.el = this.createElement();
		} else {
			this.el = $(this.el);
		}
		
		this.decorate();
	}
	
	
	(function(){
		$.extend(View.prototype, {
			update : update,
			_update : _update,
			decorate : decorate,
			_decorate : _decorate,
			undecorate : undecorate,
			_undecorate : _undecorate,
			model : null,
			setModel : setModel,
			createElement : createElement,
			el : null
		});
		
		
		function createElement(){
			return $('<div>').get(0);
		}
		
		function setModel(/*Model*/ model){
			if ( model != this.model ){
				var oldModel = this.model;
				if ( this.model != null ){
					$(this.model).unbind('changed', this.onChangeHandler);
				}
				this.model = model;
				if ( this.model != null ){
					$(this.model).bind('changed', this.onChangeHandler);
				}
				$(this).trigger('modelChanged', {
					oldModel : oldModel,
					newModel : this.model
				});
			}
			return this;
		}
		
		
		function update(){
			$(this).trigger('beforeUpdate');
			
			this._update();
			$(this).trigger('afterUpdate');
			return this;	
		}
		
		function _update(){
			var self = this;
			var model = $m(this.model);
                        $('[data-kvc]:not(.subview [data-kvc])', this.el).add(this.el).each(function(){
				if ( !$(this).attr('data-kvc') ){
					return;
				}
				var el = this;
				var $this = $(el);
				var kvc = $this.attr('data-kvc');
				kvc = kvc.split(';');
				$.each(kvc, function(k,v){
					var parts = v.split(':');
					$.each(parts, function(k,v){
						parts[k] = $.trim(v);
					});
					if ( parts.length === 2 ){
						$this.attr(parts[0], model.get(parts[1]));
						
					} else {
						
						var setFunc = $this.text;
						if ( $this.is(':input') ){
							setFunc = $this.val;
						}
                                                var val = model.get(parts[0]);
                                                if ( val === undefined ){
                                                    val = '';
                                                }
                                                setFunc.call($this, val);
                                                
                                            
					}
				});
				
				
			});
		}
		
		function decorate(){
			$(this).trigger('beforeDecorate');
			this._decorate();
			$(this).trigger('afterDecorate');
			return this;
		}
		
		function _decorate(){
			return this;
		}
		
		function undecorate(){
			$(this).trigger('beforeUndecorate');
			this._undecorate();
			$(this).trigger('afterUndecorate');
			return this;
		}
		
		
		function _undecorate(){
			return this;
		}
		
	})();
})();
//END xataface/view/View.js

}


//START xataface/store/Document.js
if ( typeof(window.__xatajax_included__['xataface/store/Document.js']) == 'undefined'){window.__xatajax_included__['xataface/store/Document.js'] = true;



(function(){
	var $ = jQuery;
	var model = XataJax.load('xataface.model');
	var $m = model.Model.wrap;
	var store = XataJax.load('xataface.store');
	var extractCallback = XataJax.util.extractCallback;
	store.Document = Document;
	
	Document.CLOSED = 0x1;
	Document.OPEN = 0x2;
	Document.SAVING = 0x4;
	Document.LOADING = 0x8;
	Document.DEFAULT_STATUS = 0x10;
	Document.DIRTY = 0x20;
	
	/**
	 * @class
	 * @name Document
	 * @memberOf xataface.store
	 * @description A document wrapper for a Model object that handles the loading,
	 * saving, and closing of the model.  This can be hooked into by any UI to implement
	 * open and close dialogs.
	 */
	function Document(/*Object*/ o){
		$.extend(this, o);
	}

	
	(function(){
		$.extend(Document.prototype, {
			model : null,
			query : null,
			saveQuery : null,
			setModel : setModel,
			open : open,
			openPrompt : openPrompt,
			close : close,
			closePrompt : closePrompt,
			savePrompt: savePrompt,
			getStatus : getStatus,
			load : load,
			_load : _load,
			handleLoadResponse : handleLoadResponse,
			save : save,
			_save : _save,
			_saveRequest : _saveRequest,
			saveRequest : null,
			handleSaveResponse : handleSaveResponse,
			
			_openCloseStatus : Document.CLOSED,
			getSaveQuery : getSaveQuery,
			getLoadQuery : getLoadQuery,
			setOpenCloseStatus : setOpenCloseStatus,
			
			getStatus : getStatus
			
		});
		
		/**
		 * @function
		 * @memberOf xataface.store.Document#
		 * @name setModel
		 * @description Sets the model object that is wrapped by this document.
		 * @param {xataface.model.Model} model The model object to be wrapped.
		 * @returns {xataface.store.Document} Self for chaining.
		 */
		function setModel(/*Model*/ model){
			if ( this.model != model ){
				if ( this.model != null ){
					//Do some cleanup
				}
				this.model = model;
				if ( this.model != null ){
					// Do some attaching
				}
			}
			return this;
		}
		
		
		/**
		 * @function
		 * @memberOf xataface.store.Document#
		 * @name load
		 * @description Loads the document using the query that is currently set.
		 * It is usually better to use the open() method as this ties into the 
		 * status of the document to call close the current model first and save
		 * it if necessary.
		 */
		function load(callback){
			$(this).trigger('beforeLoad');
			$(this).trigger('loading', {
				loading: true
			});
			this._loading = true;
			this._load(callback);
			
			return this;
		}
		
		/**
		 * @function
		 * @memberOf xataface.store.Document#
		 * @name _load
		 * @description Method intended to be extended by subclasses
		 * performing the load() function on this document.  This method
		 * is called by the load() method so that the load method itself can call events
		 * before and after _load() is called.
		 */
		function _load(callback){
			var cb = extractCallback(callback);
			//console.log(cb);
			var self = this;
			var q = this.getLoadQuery();
			if ( q != null && q['-action'] == 'export_json' ){
				q['--var'] = 'data';
				q['--single'] = 1;
				q['-mode'] = 'view';
			}
			$.get(DATAFACE_SITE_HREF, q, function(res){
				self.handleLoadResponse(res);
				self._loading = false;
				$(self).trigger('loading', {
					loading: false
				});
				if ( res.code == 200 ){
					cb.onSuccess.call(self, res);
				} else {
					cb.onFail.call(self, res);
				}
				
				$(self).trigger('afterLoad', res);
			});
			return this;
		}
		
		/**
		 * @function
		 * @memberOf xataface.store.Document#
		 * @name handleLoadResponse
		 * @description Handles the server response from a request to load the
		 * current model's data.
		 * @param res The response object that was returned by the server.
		 *
		 */
		function handleLoadResponse(res){
			
			var self = this;
			var model = this.model;
			if ( res.code == 200 ){
				$m(model)
					.set(res.data)
					.setDirty(false);
				this.setOpenCloseStatus(Document.OPEN);
				
			} else {
				$(self).trigger('error', {
					message : res.message
				});
			}
			return this;
		}
		
		/**
		 * @function
		 * @memberOf xataface.store.Document#
		 * @name setOpenCloseStatus
		 * @description Sets the open/closed status of this document.
		 * @param {int} status The status to set.  This should be one of
		 *	 - Document.OPEN
		 *	 - Document.CLOSED
		 */
		function setOpenCloseStatus(status){
			var self = this;
			if ( this._openCloseStatus != status ){
				var oldValue = this._openCloseStatus;
				var newValue = status;
				this._openCloseStatus = status;
				
				$(this).trigger('openCloseStatusChanged', {
					oldValue : oldValue,
					newValue : newValue,
					undo : function(){
						self.setOpenCloseStatus(oldValue);
					}
				});
			}
			return this;
				
				
		}
		
		/**
		 * @function
		 * @name save
		 * @memberOf xataface.store.Document#
		 * @description Saves the current document.  This will check the status
		 * of the document and try to save it if necessary.  If the saveQuery is
		 * null, this will prompt the user with a savePrompt to get the query
		 * then save it.
		 * @param {xataface.store.Document.SaveCallback} callback function or object.
		 * @param {xataface.store.Document.SaveCallback.onSuccess} callback.onSuccess 
		 *		The function to be called on a successful save.
		 * @param {xataface.store.Document.SaveCallback.onCancel} callback.onCancel
		 *		The function to be called if the save is cancelled by the user.
		 * @param {xataface.store.Document.SaveCallback.onFail} callback.onFail
		 * 		The function to be called if the save operation fails.
		 *
		 * @returns {xataface.store.Document} Self for chaining.
		 *
		 */
		function save(callback){
			var self = this;
			var cb = extractCallback(callback);
			var q = this.getSaveQuery();
			if ( q == null ){
				this.savePrompt({
					save : function(query){
						if ( query == null ){
							
							cb.onCancel.call(self);
						} else {
							self.saveQuery = query;
							self.save(callback);
						}
					},
					cancel : function(){
						cb.onCancel.call(self);
					}
				});
			} else {
		
				$(this).trigger('beforeSave');
				$(this).trigger('saving', {
					saving : true
				});
				this._saving = true;
				this._save(callback);
			}
			return this;
		}
		
		/**
		 * @function
		 * @name savePrompt
		 * @memberOf xataface.store.Document#
		 * @description Opens a save dialog to allow the user to select some
		 * save parameters.
		 * @param {xataface.store.Document.SavePromptCallback} o The object with parameters.
		 * @param {xataface.store.Document.SavePromptCallback.save} o.save
		 * 		The function to be called if the user selects "Save" in the dialog.
		 * @param {xataface.store.Document.SavePromptCallback.cancel} o.cancel
		 * 		The function to be called if the user selects "Cancel" in the dialog.
		 * @returns {xataface.store.Document} Self for chaining.
		 */
		function savePrompt(/*Object*/ o){
			o.save.call(this, this.saveQuery||{});
			return this;
		}
		
		/**
		 * @function
		 * @name _save
		 * @memberOf xataface.store.Document#
		 * @description saves the current model.  This is called by the save() method
		 * 	so that the save() method can handle all of the pre and post save event handling
		 *  making it easier to override just the save functionality in subclasses. 
		 *  Subclasses wishing to override how the save process works, should override
		 * this method instead of the save() method.
		 *
		* @param {xataface.store.Document.SaveCallback} callback function or object.
		 * @param {xataface.store.Document.SaveCallback.onSuccess} callback.onSuccess 
		 *		The function to be called on a successful save.
		 * @param {xataface.store.Document.SaveCallback.onCancel} callback.onCancel
		 *		The function to be called if the save is cancelled by the user.
		 * @param {xataface.store.Document.SaveCallback.onFail} callback.onFail
		 * 		The function to be called if the save operation fails.
		 * @returns {xataface.store.Document} Self for chaining.
		 */
		function _save(callback){
			var cb = extractCallback(callback);
			var self = this;
			var model = this.model;
			function handleError(res){
				$(self).trigger('error', {
					message : 'Failed to save due to a server error.'
				});
				cb.onFail.call(self, res);
			}
			
			function handleComplete(res){
				self._saving = false;
				$(self).trigger('saving', {
					saving : false
				});
				$(self).trigger('afterSave', res);
			}
			
			function handleSuccess(res){
				self.handleSaveResponse(res);
				if ( res.code == 200 ){
					cb.onSuccess.call(self, res);
				} else {
					cb.onFail.call(self, res);
				}
			}
			
			this._saveRequest(handleSuccess, handleError, handleComplete);
			
			return this;
		}
		
		
		/**
		 * @function
		 * @name _saveRequest
		 * @memberOf xataface.store.Document#
		 * @description Sends a save request to the datasource.  The default 
		 * 	implementation makes an ajax request, but this method is designed
		 *  to be overridden if you don't want to use AJAX.
		 *
		 * @param {Function} success Callback function called on success.
		 * @param {Function} error Callback function called on error.
		 * @param {Function} complete Callback function called on complete.
		 * @returns {xataface.store.Document} Self for chaining.
		 */
		function _saveRequest(success, error, complete){
			if ( this.saveRequest != null ){
				
				return this.saveRequest(success, error, complete);
			}
			var self = this;
			$.post(DATAFACE_SITE_HREF, this.getSaveQuery(), function(res){
				success.call(self, res);
			})
			.error(function(res){
				error.call(res);
			})
			.complete(function(res){
				complete.call(res);
			})
			
			;
			return this;
		}
		
		/**
		 * @function
		 * @name handleSaveResponse
		 * @memberOf xataface.store.Document#
		 * @description Handles the response to a save request.
		 * @param {xataface.store.Document.SaveResponse}  res The save response
		 */
		function handleSaveResponse(res){
			var model = this.model;
			var self = this;
			if ( res.code == 200 ){
				$m(model)
					.set(res.data)
					.setDirty(false);
				
			} else {
				$(self).trigger('error', {
					message: res.message
				});
			}
		}
		
		/**
		 * @function
		 * @name getSaveQuery
		 * @memberOf xataface.store.Document#
		 * @description Returns the query used to save this document.
		 * @returns {Object} The query used to save this document.
		 */
		function getSaveQuery(){
			return this.saveQuery;
		}
		
		/**
		 * @function
		 * @name getLoadQuery
		 * @memberOf xataface.store.Document#
		 * @description Returns the query used to load this document.
		 * @returns {Object} The query used to load this document.
		 */
		function getLoadQuery(){
			return this.query;
		}
		
		
		/**
		 * @function
		 * @name getStatus
		 * @memberOf xataface.store.Document#
		 * description Returns a status mask that can be used to determine
		 * all of the status flags relating to this document.
		 * The status flags include:
		 *  - Document.OPEN : Indicates the document is currently opened.
		 *  - Document.CLOSED : Indicates the document is currently closed.
		 *  - Document.LOADING : Indicates the document is currently loading.
		 *  - Document.SAVING : Indicates that a save is currently in progress.
		 *  - Document.DIRTY : Indicates that the model of this document has changed
		 *		since load or last save.
		 *
		 * @example
		 *  if ( doc.getStaus() & Document.LOADING ){
		 *      // The document is currently loading.
		 *  }
		 * 
		 * @returns {int} Mask that can be checked for all of the status flags.
		 */
		function getStatus(){
			var status = 0x0;
			
			if ( this._loading ){
				status = status | Document.LOADING;
			}
			if ( this._saving ){
				status = status | Document.SAVING;
			}
			
			if ( $m(this.model).dirty ){
				status = status | Document.DIRTY;
			}
			
			status = status | this._openCloseStatus;
			
			
			
			
			return status;
		}
		
		/**
		 * @function
		 * @name closePrompt
		 * @memberOf xataface.store.Document#
		 * @description Displays a prompt to the user to see if they want to 
		 * close the document.
		 * @param {xataface.store.Document.ClosePromptCallback} o The callback object
		 * 		that specifies what to do in response to the dialog.
		 * @returns {xataface.store.Document} Self for chaining.
		 */
		function closePrompt(o){
			if ( o.yes && o.yes.call ){
				o.yes.call(this);
			}
			return this;
		}
		
		/**
		 * @function
		 * @name close
		 * @memberOf xataface.store.Document#
		 * @description Closes the document, but (as long as force is not true) goes 
		 *	through the necessary and familiar steps to ensure that unsaved changes
		 *  have been saved etc.. before completing.
		 * @param {xataface.store.Document.CloseCallback} callback The callback object
		 *	that contains function that are to be called after successful, cancelled, or
		 *	failed close.
		 * @param {boolean} force Optional flag to indicate whether the close should 
		 *	occur despite unsaved changes.  The default value is false.
		 * @returns {xataface.store.Document} Self for chaining.
		 */
		function close(callback, force){
			if ( typeof(force) == 'undefined' ) force = false;
			if ( typeof(callback) == 'undefined' ){
				callback = function(){};
			}
			var self = this;
			var cb = extractCallback(callback);
			if ( this.model == null ){
				cb.onSuccess.call(this);
				return this;
			}
			var status = this.getStatus();
			
			if ( status & Document.CLOSED ){
				cb.onSuccess.call(this);
				return this;
			}
			if ( !force ){
				
				
				if ( status & Document.SAVING ){

					// The document is currently saving
					function afterSave(res){
						$(self).unbind('afterSave', afterSave);
						self.close(callback);
					}
					$(this).bind('afterSave', afterSave);
				} else if ( status & Document.LOADING ){
					function afterLoad(res){
						$(self).unbind('afterLoad', afterLoad);
						self.close(callback);
					}
					$(this).bind('afterLoad', afterLoad);
				} else if ( status & Document.DIRTY ){
					
					this.closePrompt({
						yes : function(){
							self.save(function(res){
								self.close(callback);
							});
						},
						cancel : function(){
							cb.onCancel.call(self);
						},
						no : function(){
							self.close(callback, true);
						}
						
					});
					
				} else {
					self.close(callback, true);
				}
			} else {
				this.setOpenCloseStatus(Document.CLOSED);
				$(this).trigger('statusChanged');
				cb.onSuccess.call(this);
				$(this).trigger('afterClose');
			}
			return this;
		}
		
		/**
		 * @function
		 * @name open
		 * @memberOf xataface.store.Document#
		 * @description Opens the document with the current load query.  If the load 
		 * 	query is null (or force is true), then it will first prompt the user
		 *  with an "open" dialog to select the entity that the wish to open.
		 * @param {xataface.store.Document.OpenCallback} callback The callback object 
		 *	containing functions to be called on success, failure, or user cancelling.
		 * @param {boolean} force Optional flag to "force" the open dialog to show up.
		 *  If this is true then the open dialog will be shown to allow the user to
		 * 	select an entity even if the query is already set.  Otherwise, it will try
		 *  to use the existing query to silently load the file if the query is not-null.
		 * @returns {xataface.store.Document} Self for chaining.
		 */
		function open(callback, force){
			var cb = extractCallback(callback);
			if ( typeof(force) == 'undefined' ){
				force = false;
			}
				
			var self = this;
			if ( this.query == null || force ){
				this.openPrompt({
					open : function(query){
						this.query = query;
						self.open(callback);
					},
					cancel : function(){
						cb.onCancel.call(self);
					}
				}); 
			} else {
				var status = this.getStatus();
				if ( status & Document.OPEN ){
					this.close(function(){
						self.open(callback);
					});
				} else {
					this.load({
						onSuccess : function(res){
							cb.onSuccess.call(this, res);
							$(self).trigger('afterOpen');
						},
						onFail : cb.onFail,
						onCancel : cb.onCancel
						
					});
				}
			}
		}
		
		
		/**
		 * @function
		 * @name openPrompt 
		 * @memberOf xataface.store.Document#
		 * @description Prompts the user to select the model that they wish to open
		 * in this document.  This is meant to be overridden by instances or subclasses
		 * to provide a concrete implementation.
		 * 
		 * @param {xataface.store.Document.OpenPromptCallback} callback The callback object
		 * 	that includes functions to be called to handle the user's action with the prompt.
		 * @returns {xataface.store.Document} Self for chaining.
		 */
		function openPrompt(callback){
			
			if ( callback && callback.open && callback.open.call ){
				callback.open.call(this, {});
			}
			
			return this;
		}
		
		
		
		
	})();
})();
//END xataface/store/Document.js

}


(function(){
    var $ = jQuery;
    var UIForm = xataface.modules.uitk.components.UIForm;
    var View = xataface.view.View;
    var Document = xataface.store.Document;
    var Model = xataface.model.Model;
    
    var resultsView = new View({
        model : new Model(),
        el : $('#import-form-results-wrapper').get(0),
        _update : function(){
            View.prototype._update.call(this);
        },
        _decorate : function(){
            View.prototype._decorate.call(this);
            
            
        },
        _undecorate : function(){
            View.prototype._undecorate.call(this);
            
        }
                
    });
    
    $('#view-strings-btn').click(function(){
        
        if ( resultsView.model.get('target_translation_memory_uuid') ){
            window.location=DATAFACE_SITE_HREF+
                '?-action=list'+
                '&-table=translation_miss_log'+
                '&translation_memory_uuid=='+encodeURIComponent(resultsView.model.get('target_translation_memory_uuid'))+
                '&-sort=date_inserted+desc'+
                ((parseInt(resultsView.model.get('succeeded'))>0)?('&-limit='+encodeURIComponent(resultsView.model.get('succeeded'))):'')
                ;
        } else {
            window.location=DATAFACE_SITE_HREF+
                '?-action=list'+
                '&-table=translation_miss_log'+
                '&-sort=date_inserted+desc'+
                (parseInt(resultsView.model.get('succeeded'))>0?('&-limit='+encodeURIComponent(resultsView.model.get('succeeded'))):'');
        }
        
        return false;
    });
    
    $('#import-more-strings-btn').click(function(){
        $('#import-form-results-wrapper').hide();
        $('#import-form-wrapper').show();
        importForm.refresh();
        return false;
    });
    
    
    var resultsLoader = new Document({
        model : resultsView.model,
        query : {
            '-action' : 'export_json',
            '-table' : 'string_imports'
        }
    });
    
    var importForm = new UIForm({
        fields : [
            'file',
            'file_format',
            'target_translation_memory_uuid'
        ],
        table : 'string_imports',
        isNew : true,
        showHeadings : false,
        showSubheadings : false,
        cancelAction : function(){
            window.history.back();
        }
    });
    
    $('#import-form-wrapper').append(importForm.el);
    
    
    $(importForm.el)
        .css({
            'border' : '3px solid rgb(187,204,255)',
            'border-radius' : '15px',
            'width' : '100%',
            'height' : '400px'
        });
    $(importForm)
        .bind('beforeSubmit', function(evt, data){
            if ( data.code == 200 ){
                $('#import-form-progress').show();
                $('#import-form-wrapper').hide();
            }
        });
        
    
    $(importForm).bind('afterSave', function(){
       resultsLoader.query['--recordid'] = importForm.getRecordId();
       
       resultsLoader.load(function(){
           $('#import-form-progress').hide();
           resultsView.update();
           $('#import-form-results-wrapper').fadeIn();
       });
       
    });
    
    importForm.refresh();
    
    
    
})();


//END swete/actions/import_translations.js

}

//START swete/actions/add_selected_strings_to_job.js
if ( typeof(window.__xatajax_included__['swete/actions/add_selected_strings_to_job.js']) == 'undefined'){window.__xatajax_included__['swete/actions/add_selected_strings_to_job.js'] = true;
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
	
	function addStringsToJob(job, selectedIds){
			
		var q = {
			'-table': 'webpages',
			'-action': 'swete_add_selected_strings_to_job',
			'--selected-ids': selectedIds,
			'-job': job
		};
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
			
			var message;
			
			if (res.isNewJob){
				message = "Job "+res.jobId+" was created, and "+res.stringsAdded.length+" strings were added.";
			}else{
				if (res.stringsAdded.length <1){
					message = "No new strings were added. The selected strings were already added to Job "+res.jobId;
				}else{
					message = "Successfully added "+res.stringsAdded.length+" new strings to Job "+res.jobId;
				}
			}
			
			window.location.search ='-table=translation_miss_log&--msg='+message;
			
			
		}, "json");
				
	}
	
	function addStringToJob(job, recordId){
	
		var q = {
			'-table': 'webpages',
			'-action': 'swete_add_string_to_job',
			'-record-id': recordId,
			'-job': job
		};
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
			
			var message;
			
			if (res.isNewJob){
				message = "Job "+res.jobId+" was created, and the string was successfully added.";
			}else{
				if (res.stringAdded=="false"){
					message = "The selected string was already added to Job "+res.jobId;
				}else{
					message = "Successfully added the string to Job " +res.jobId;
				}
			}
			
			window.location.search ='-table=translation_miss_log&-action=browse&-recordid='+recordId+'&--msg='+message;
			
		}, "json");
	}
	
	function getWebsiteFromStrings(selectedIds, recordId, callback){
	
		var q = {
			'-table': 'webpages',
			'-action': 'swete_get_website_from_record',
			'-record-id': recordId,
			'-selected-ids' : selectedIds
		};
		
		$.get(DATAFACE_SITE_HREF, q, function(res){
			callback(res);
		});
		
	}
	
	function getAvailableJobs(siteId, callback){
	
		var q = {
			'-table': 'websites',
			'-action': 'swete_get_jobs_for_site',
			'-site-id': siteId,
			'-compiled' : false
		};
		
		$.get(DATAFACE_SITE_HREF, q, function(res){
			callback(res);
		}, "json");
	
	}
	
	/**
	**  
	**	Uses Recordbrowser to select a job, and then adds strings to it:
	**  specified by either an array of selected Ids or a single id.
	**  selectedIds is an array of selected transalation_miss_log record ids
	**  recordId is one transalation_miss_log record id
	**/
	function selectJobandAddStrings(selectedIds, recordId){
		
		getWebsiteFromStrings(selectedIds, recordId, function(res){
			//res is either the website id or an error message
			if (isNaN(parseInt(res)) || parseInt(res)==0){
				alert(res);
				return false;
			}
			var websiteId = res;
			
			getAvailableJobs(websiteId, function(jobs){
			
				var numJobs = jobs.length;
				if (isNaN(parseInt(numJobs))){
					return false;
				}
				
				if (numJobs<2){
					//No need for user to select a job. Either a new job must be created, 
					//or there is only 1 available job, so the strings will be added it.
					if (selectedIds){
						addStringsToJob(null, selectedIds);
					}else{
						addStringToJob(null, recordId);
					}	
				
				}else{
					
					//user must select a job to add to
					var  username = $('meta#xf-meta-username').attr('content');
					
					//display modal dialog with list of users
					var dlgContent = '<div>Add strings to: <select class="jobs">';
					for(var i=0;i<jobs.length;i++){
						dlgContent += '<option value="'+jobs[i].job_id+'">'+jobs[i].title+'</option>';
					}
					dlgContent += '<option value="new">New Job</option>';
					dlgContent +='</select></div>';
					
							
					var dlg = $('<div>')
						.append($(dlgContent))
						.dialog({
							title: 'Add Strings To Job',
							modal: true,
							stack: false,
							width: 400,
							close: function() {
								 $(this).dialog('destroy');
								 $(this).remove();
							},
							buttons: { "Select": function() {
								selectedJobId=$('select.jobs').val();
								$(this).dialog("close");
								if (selectedIds){
									addStringsToJob(selectedJobId, selectedIds);
								}else{
									addStringToJob(selectedJobId, recordId);
								}
								
								}
							}
							
					});
					
						
				}
				
		});
		
		return false;
		
		});
	
	}
	
	
	$(document).ready(function(){
	
		$('li.swete_add_string_to_job > a').click(function() {
    		
    		var currRecordId = $('meta#xf-meta-record-id').attr('content');

    		selectJobandAddStrings(null, currRecordId);
    		return false;
    	});
    	
    	$('li.swete_add_selected_strings_to_job > a').click(function() {
			
			var resultList = $('.resultList'); // The wrapper table has CSS class "resultList"
			var getSelectedIds = XataJax.load('XataJax.actions.getSelectedIds');
			var selectedIds = getSelectedIds(resultList, true);
			
			if ( selectedIds.length == 0 ){
				alert("No strings were selected");
				return false;
			
			}else {
				selectJobandAddStrings(selectedIds);
				return false;
				
			}
		
	});
	
	});

})();
//END swete/actions/add_selected_strings_to_job.js

}
				if ( typeof(XataJax) != "undefined"  ) XataJax.ready();
				