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
	
	 $(document).ready(function(){
		
		registerXatafaceDecorator(function(){
			$('th input').keyup(doFilter);
			$('th select').change(doFilter);
		});
		
		function doFilter(){
			var phrase = $('th input#phrase');
			var status = $('th select#status');
			
			if( $(phrase).val() == "" && $(status).val() == "all"){
				$(".translations tbody>tr.translation-row").show();
			}else if ($(phrase).val() != "" &&  $(status).val() == "all"){
				// Show only matching phrase rows, hide rest of them
				$(".translations tbody>tr.translation-row").hide();
				$(".translations td.phrase:containsNC('" + $(phrase).val() + "')").parent("tr.translation-row").show();
				
			}else{
				$(".translations tbody>tr.translation-row").hide();
				$(".translations td.status").each(function(){
					if ($(this).text()==$(status).val()){
						if ( $(phrase).val() != ""){
							//match the phrase AND the status
							var translationrow = $(this).parent("tr.translation-row");
							$("td.phrase", $(translationrow)).filter(":containsNC('" + $(phrase).val() + "')").parent("tr.translation-row").show();	
						}else{
							//match status only
							$(this).parent("tr.translation-row").show();
						}
					}
				});
			}
		}
		
		
		$.extend($.expr[":"], {"containsNC": 
			function(elem, i, match, array) {
				return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
			}
		});

		
	});


})();