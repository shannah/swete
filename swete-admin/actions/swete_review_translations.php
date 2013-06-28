<?php
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

require_once 'inc/SweteJob.class.php';
require_once 'modules/tm/lib/XFTranslationMemory.php';

class actions_swete_review_translations {

	function handle(&$params){
		
		try{
			
			$app = Dataface_Application::getInstance();
			$query =& $app->getQuery();
			
			$jt = Dataface_JavascriptTool::getInstance();
			$jt->import('swete/ui/filter_translations.js');
			$app->addHeadContent('<link rel="stylesheet" type="text/css" href="css/swete/actions/review_translations.css"/>');
		
			if ( !@$query['-recordid'] ) throw new Exception("No record id was specified");
			
			$record = df_get_record_by_id($query['-recordid']);
			
			$job = new SweteJob($record);
			
			$tm = XFTranslationMemory::loadTranslationMemoryFor($record, $record->val('source_language'), $record->val('destination_language'));
			
			$translations = $job->getTranslations();
			
			$template = 'swete/actions/review_translations.html';
			
			if ( @$query['-isDialog'] ) {
				$template = 'swete/actions/review_translations_dlg.html';
			}
			
			df_display(
				array(
					'job'=> $job,
					'translations'=>$translations
				),
				$template
			);
			
			
		} catch (Exception $e){
			
			if ($e->getCode() == E_USER_ERROR){
				echo $e->getMessage();
				
			}else{
				throw $e;
			}
			
		
		}
        
	}

}