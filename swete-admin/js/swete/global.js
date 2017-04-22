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
//require <swete/BackgroundProcess.js>
(function(){
	var $ = jQuery;
	//var BackgroundProcess = XataJax.load('swete.BackgroundProcess');
	
	//var bgProcess = new BackgroundProcess();
	//bgProcess.installMenu();
	//bgProcess.setUpdateFrequency(5000);
	
	window.sweteClearCache = function() {
	    if (confirm('Are you sure you want to clear the cache?  \nThis may affect performance while the cache is rebuilt.', 'Proceed', 'Cancel')) {
	        var modal = $('<div class="modal">Please wait.  Do not leave this page until complete.</div>').get(0);
	        $('body').append(modal);
	        $('body').addClass('loading');
	        $.post(DATAFACE_SITE_HREF, {'-action' : 'swete_clear_cache'})
	            .done(function(res) {
                    
                    try {
                        if (res.code == 200) {
                            alert("The cache has been successfully cleared");
                            window.location.reload();
                        } else {
                            throw new Error("Failed to clear cache");
                        }
                    } catch (e){
                        var msg = "An error occurred while clearing the cache: ";
                        if (res && res.message) {
                            msg += res.message;
                        } else {
                            msg += "See server log";
                        }
                        alert(msg);
                    }
                })
                .fail(function() {
                    alert("HTTP failure.  Please see server logs.");
                })
                .always(function() {
                    $(modal).remove();
                    $('body').removeClass('loading');
                });
	        
	    }
	};
	
})();