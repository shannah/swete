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
//require <jquery-ui.min.js>
//require-css <jquery-ui/jquery-ui.css>
//require <swete/JobCompiler.js>

(function(){

	var $ = jQuery;
	

    $(document).ready(function(){
    
    	var JobCompiler = XataJax.load('swete.JobCompiler');
    
    	$('li.swete_compile_job > a').click(function() {
    		
    		displayConfirmDialog(function(){
    			var currRecordId = $('meta#xf-meta-record-id').attr('content');
				
				var compiler = new JobCompiler({
					recordId: currRecordId
				});
				
				//Dialog with progress indicator
				var dlg = $('<div>')
					.append($('<div><img src="'+DATAFACE_URL+'/images/progress.gif"/>  Please wait...</div>'))
					.dialog({
						title: 'Compile In Progress',
						modal: true
					});
					
				//$('body').append(dlg);
				compiler.doCompile(function(){
					dlg.dialog('close');
					
					if ( this.error ){
						alert(this.message);
						
					} else {
						
						window.location.search ='-action=browse&-table=jobs&&-cursor=1&-skip=0&-limit=30&-mode=list&-recordid='+currRecordId+'&--msg='+'The job was successfully compiled.';
						
						return;
					}
				});
				
    		
    		});

				
			return false;
				
				
		});
	
	});
	
	function displayConfirmDialog(callback){
		var confirmDlg = $('<div><p>After compiling, no more webpages can be added to the translation job.</p><p>Are you sure you want to compile?</p></div>')
    				.dialog({
						title: 'Confirm Compile',
						modal: true,
						buttons: { "Ok": function() { $(this).dialog("close");callback.call(); },
									"Cancel": function() {$(this).dialog("close"); }}
						});
	}

})();