if ( typeof(window.console)=='undefined' ){window.console = {log: function(str){}};}if ( typeof(window.__xatajax_included__) != 'object' ){window.__xatajax_included__={};};
//START xataface/findwidgets/select.js
if ( typeof(window.__xatajax_included__['xataface/findwidgets/select.js']) == 'undefined'){window.__xatajax_included__['xataface/findwidgets/select.js'] = true;






(function(){
	var $ = jQuery;
	var findwidgets = XataJax.load('xataface.findwidgets');
	findwidgets.select = select;

	
	function select(/**Object*/ o){
		this.el = null;
		this.btn = null;
		this.name = null;
		this.hiddenField = null;
		
	}
	
	$.extend(select.prototype, {
	
		install: install,
		toggleAdvanced: toggleAdvanced,
		showAdvanced: showAdvanced,
		hideAdvanced: hideAdvanced
	});
	
	function install(/**HTMLElement*/ el){
		var self = this;
		this.el = el;
		this.name = $(this.el).attr('name');
		
		
		
		$(this.el).removeAttr('name');
		this.hiddenField = $('<input type="hidden" name="'+this.name+'"/>');
		this.hiddenField.insertAfter(this.el);
		$(this.el).change(function(){
			if ( $('option:selected', self.el).size() <= 1 ){
				$(self.hiddenField).val($(self.el).val());
			} else {
				$(self.hiddenField).val($(self.el).val().join(' OR '));
			}
		});
		
		this.btn = $('<button>')
			.addClass('advanced-button')
			.text('...')
			.click(function(){
				self.toggleAdvanced();
				return false;
			})
			;
		this.btn.insertAfter(el);
		
		
		var params = XataJax.util.getRequestParams();
		
		if ( params[this.name] ){
			
			var val = decodeURIComponent(params[this.name]);
			if ( val.match(/ OR /) ){
				val = val.split(/ OR /);
				self.showAdvanced();
				$(self.el).val(val);
			} else {
				$(self.el).val(val);
			}
			$(self.hiddenField).val(val);
		}
		
		
		
	}
	
	function toggleAdvanced(){
	
		if ( $(this.el).hasClass('xf-findfields-select-advanced') ) this.hideAdvanced();
		else this.showAdvanced();
	}
	
	
	function showAdvanced(){
		$(this.el)
			.attr('size', 6)
			.attr('multiple', 1)
			.addClass('xf-findfields-select-advanced')
			;
	}
	
	function hideAdvanced(){
		$(this.el).removeAttr('multiple').attr('size',1)
			.removeClass('xf-findfields-select-advanced');
	}
	
	
	
})();
//END xataface/findwidgets/select.js

}

//START xataface/findwidgets/date.js
if ( typeof(window.__xatajax_included__['xataface/findwidgets/date.js']) == 'undefined'){window.__xatajax_included__['xataface/findwidgets/date.js'] = true;






(function(){
	var $ = jQuery;
	
	var findwidgets = XataJax.load('xataface.findwidgets');
	findwidgets.date = date;
	
	
	function date(/**Object*/ o){
		var self = this;
		this.el = null;
		this.name = null;
		this.from = $('<input type="text">');
		this.to = $('<input type="text">');
		this.rangePanel = $('<div>').css('text-align','right');
		$(this.rangePanel).append('From ').append(this.from).append(' to ').append(this.to);
		this.btn = $('<button>').addClass('advanced-button').text('...').click(function(){
			self.toggleRange();
			return false;
		});
		
	}
	
	$.extend(date.prototype, {
	
		install: install,
		toggleRange: toggleRange,
		showRange: showRange,
		hideRange: hideRange
	});
	
	
	
	function install(/**HTMLElement*/ el){
		var self = this;
		this.el = el;
		this.rangePanel.insertAfter(this.el).hide();
		this.btn.insertAfter(this.el);
		
		$.each([this.from, this.to], function(){
			var dates = $(this).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 3,
				onSelect: function( selectedDate ) {
					var option = this == self.from ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" ),
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
					$(this).change();
					
				}
			});
		});
		
		$.each([this.from, this.to], function(){
		
			$(this).change(function(){
				var from = $(self.from).val();
				var to = $(self.to).val();
				if ( from && to ){
					$(self.el).val(from+'..'+to);
				} else if ( from ){
					$(self.el).val('>='+from);
				} else if ( to ){
					$(self.el).val('<='+to);
				} else {
					$(self.el).val();
				}
				
			});
		});
		
		this.name = $(this.el).attr('name');
		
		
		
		
	}
	
	
	function toggleRange(){
		if ( $(this.el).is(':visible') ) this.showRange();
		else this.hideRange();
	}
	
	function showRange(){
		
		$(this.el).hide();
		var val = $(this.el).val();
		if ( val.match(/\.\./) ){
			var parts = val.split('..');
			$(this.from).val(parts[0]);
			$(this.to).val(parts[1]);
		} else if ( val.match(/^</) ){
			$(this.from).val('');
			val = val.replace(/^[^0-9]+/, '');
			$(this.to).val(val);
			
		} else if ( val.match(/^>/) ){
			$(this.to).val('');
			val = val.replace(/^[^0-9]+/, '');
			$(this.from).val(val);
			
		} else {
			$(this.to).val(val);
			$(this.from).val(val);
		}
		
		$(this.rangePanel).show();
	}
	
	function hideRange(){
		$(this.el).show();
		$(this.rangePanel).hide();
	}
})();
//END xataface/findwidgets/date.js

}
				if ( typeof(XataJax) != "undefined"  ) XataJax.ready();
				