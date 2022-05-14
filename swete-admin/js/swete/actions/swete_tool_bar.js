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
//require <swete/Website/toggleCaptureStrings.js>
//require <xataface/Comet.js>
//require <xatajax.core.js>
(function(){
	var $ = jQuery;
	var Website = XataJax.load('swete.Website');
	var Comet = XataJax.load('xataface.Comet');
	
	var toolbar = $('#swete-toolbar');
	var startButton = $('li.start-capture > a', toolbar);
	var endButton = $('li.end-capture > a', toolbar);
	var exitButton = $('li.exit > a', toolbar);
	
	
	
	var websiteId = toolbar.attr('data-website-id');
	
	var website = new Website({
		website_id: websiteId
	});
	
	function update(){
		var captureEnabled = website.getValue('log_translation_misses');
		if ( captureEnabled ){
			toolbar.addClass('capturing');
		} else {
			toolbar.removeClass('capturing');
		}
	}
	website.load(function(){});
	website.ready(function(){
		
		$(website).bind('propertyChanged', function(event, data){
			if ( data.propertyName == 'log_translation_misses' ){
				update();
			}
			
			
		});
		
		startButton.click(function(){
			website.startCaptureStrings(function(res){
				
			});
			return false;
		});
		
		endButton.click(function(){
		
			website.stopCaptureStrings(function(res){
			
			});
			return false;
		});
		
		
		
		/*
		var comet = new Comet({
			url: DATAFACE_SITE_HREF+'?-action=swete_tool_bar_comet&website_id='+websiteId,
			context: {
				website: website
			}
		});
		
		comet.open();
		*/
		
	});
	
	
	function resizeIframe(){
		$("#iframe").height( WindowHeight() - getObjHeight(document.getElementById("swete-toolbar")) );
	}
	
	function WindowHeight(){
		var de = document.documentElement;
		return self.innerHeight ||
			(de && de.clientHeight ) ||
			document.body.clientHeight;
	}
	
	function getObjHeight(obj){
		if( obj.offsetWidth ){
			return obj.offsetHeight;
		}
		return obj.clientHeight;
	}
	
	
	$(window).resize(function(){ resizeIframe();});
	resizeIframe();
	

})();