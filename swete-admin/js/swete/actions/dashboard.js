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
(function(){
	var $ = jQuery;
	
	
	$('.actions-menu-item').click(function(){
		if ( $(this).parent().hasClass('enabled') ){
			$(this).parent().removeClass('enabled');
			$('body').unbind('click.dashboard');
			
		} else {
			var self = this;
			$(this).parent().addClass('enabled');
			$('body').bind('click.dashboard', function(){
				$(self).parent().removeClass('enabled');
				$('body').unbind('click.dashboard');
			});
		}
		return false;
	});
	
	$('.log-translation-miss-warning').click(function(){
		alert('String capturing is currently enabled for this website.  If you are not currently in the process of capturing strings, you should disable this option as it causes a lot of extra processing power for the server to handle requests.');
		return false;
	});
	
	$('a.site-stats').click(function(){
		$(this).parent().children('.popup-panel').fadeIn();
		return false;
	});
	
	$('a.close-panel').click(function(){
		$(this).parents('.popup-panel').fadeOut();
		return false;
	});
	
})();