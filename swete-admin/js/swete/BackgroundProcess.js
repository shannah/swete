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
//require-css <swete/BackgroundProcess.css>
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