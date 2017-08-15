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
require_once 'modules/tm/lib/TMTools.php';
class tables_translation_miss_log {

	function block__custom_javascripts(){
		
		$jt = Dataface_JavascriptTool::getInstance();
		$jt->import('swete/actions/add_selected_strings_to_job.js');	
	}
	
	function getSourceLanguage($record){
		return $record->val('source_language');
	}
	
	function getDefaultTargetLanguage($record){
		return $record->val('destination_language');
	}
	
	function getTargetLanguages($record){
		return array($this->getDefaultTargetLanguage($record));
	}
	
	function getTranslationMemoryId($record, $source, $dest){
		return $record->val('translation_memory_id');
	}
	
	function getTitle($record){
		return substr(strip_tags($record->val('normalized_string')), 0, 50);
	}
	
	function block__before_result_list_content(){
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		$builder = new Dataface_QueryBuilder($query['-table'], $query);
		$sql = 'select sum(num_words) '.$builder->_from().$builder->_where();
		$res = df_q($sql);
		$row = xf_db_fetch_row($res);
		@xf_db_free_result($res);
		$app->addHeadContent('<style type="text/css">#total-words-found {float:right;width: 200px;}</style>');
		echo '<div id="total-words-found">Total Words: '.$row[0].'</div>';
		Dataface_JavascriptTool::getInstance()->import('swete/actions/batch_google_translate.js');
	}
	
	
	function normalized_string__csvValue(Dataface_Record $record){
	    return TMTools::encode($record->val('string'), $params);
	}
	
	function normalized_translation_value__csvValue(Dataface_Record $record){
	    return $record->val('normalized_translation_value');
	}
	
	
}