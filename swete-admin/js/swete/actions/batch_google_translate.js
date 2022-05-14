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
//require <xataface/Comet.js>
//require <jquery-ui.min.js>
//require-css <jquery-ui/jquery-ui.css>
//require <xatajax.core.js>
(function(){
	var $ = jQuery;
	var getSelectedIds = XataJax.load('XataJax.actions.getSelectedIds');
	var Comet = XataJax.load('xataface.Comet');
	
	
	registerXatafaceDecorator(function(node){
		
		$('.batch-google-translate > a', node).click(function(){
			try {
				var resultList = $('.resultList');
				var selectedIds = getSelectedIds(resultList, false);
				if ( selectedIds.length == 0 ){
					alert("No strings were selected.");
					return false;
				
				}else {
					
					var q = {
						'-action': 'batch_google_translate',
						'--selected-ids': selectedIds.join("\n")
					};
					
					var progressWindow = $('<div>').addClass('progressWindow');
					var progressLabel = $('<div>').addClass('progressLabel').text('Sending '+selectedIds.length+' strings to Google for translation.  Please wait...');
					var progressBar = $('<div>').addClass('progressBar');
					var progressLog = $('<textarea>').addClass('progressLog').css({height: 300}).hide();
					var progressLogLabel = $('<label>[+] Show Log</label>');
					var successFailedWrapper = $('<div>');
					var successLabel = $('<label>Success:</label>');
					var successMarker = $('<span>0</span>');
					var failedMarker = $('<span>0</span>');
					var failedLabel = $('<label>&nbsp;&nbsp;Failed:</label>');
					successFailedWrapper
						.append(successLabel)
						.append(successMarker)
						.append(failedLabel)
						.append(failedMarker);

					
					
					
					progressLogLabel.click(function(){
						if ( $(this).hasClass('visible') ){
							$(this).removeClass('visible');
							$(this).text('[+] Show Log');
							progressLog.slideUp();
						} else {
							$(this).addClass('visible');
							$(this).text('[-] Hide Log');
							progressLog.slideDown();
						}
						return false;
					});
					
					
					progressWindow
						.append(progressLabel)
						.append(successFailedWrapper)
						.append(progressBar)
						.append(progressLogLabel)
						.append(progressLog);
						
					progressBar.progressbar({value: 0});
					progressWindow.dialog({
						title: 'Translation in Progress'
					});
					
					
					var comet = new Comet({
						query: q,
						context: {
							progressBar: progressBar,
							progressLabel: progressLabel,
							progressLog: progressLog,
							successMarker: successMarker,
							failedMarker: failedMarker
						}
					});
					
					comet.open();
					/*
					$.post(DATAFACE_SITE_HREF, q, function(res){
						alert(res.message);
						//window.location.reload();
					
					});
					*/
					
					
					
				}
			} catch (e){
				alert(e);
			}
				
			return false;
			
		});
		
	});
})();