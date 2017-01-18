<?php
/*
 * Xataface Translation Memory Module
 * Copyright (C) 2011  Steve Hannah <steve@weblite.ca>
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Library General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Library General Public License for more details.
 * 
 * You should have received a copy of the GNU Library General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA  02110-1301, USA.
 *
 */
 
/**
 * @brief Handler for the @p tm_save_field action which provides a REST API to save a 
 * translated field's contents.
 *
 * @section tm_save_field_request_parameters Request Parameters
 *
 * <table>
 *		<tr><th>Name</th><th>Description</th></tr>
 *		<tr><td>@p --lang</td><td>The 2-digit language code to save the field in.</td></tr>
 *		<tr><td>@p --record_id</td><td>The Xataface record id of the record to save.</td></tr>
 *		<tr><td>@p --field</td><td>The name of the field to save.</td></tr>
 *		<tr><td>@p --newval</td><td>The value to save in the field.</td></tr>
 * </table>
 *
 * @section tm_save_field_response_structure Response Structure
 *
 * This action will return mimetype text/json with a JSON object of the following structure:
 *
 * @code
 * {
 *   code:  <int>,       // The response code.  200 for success.
 *   message: <string>,  // The response message.  Error or success.
 *   fieldContent: <string>, // The saved field content.  (Only returned on success)
 *   error: <boolean>    // Will contain 1 if there was an error.
 * }
 * @endcode
 *
 * @section tm_save_field_permissions Permissions
 *
 * This action requires that the user has the @p edit permission granted for the given field.
 *
 * 
 */
class actions_tm_save_field {

	function handle($params){
	
	
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		
		try {
		
			if ( !@$_POST['--lang'] ){
				throw new Exception("No language specified.");
				
			}
			
			if ( !preg_match('/^[0-9-a-z]{2}$/', $_POST['--lang']) ){
				throw new Exception("Invalid language code.");
			}
			
			
			if ( !@$_POST['--record_id'] ){
				throw new Exception("No record specified");
			}
			
			$record = df_get_record_by_id($_POST['--record_id']);
			
			if ( !$record ){
				throw new Exception("Record could not be found.");
			}
			
			$tlangs = $record->table()->getTranslations();
			foreach (array_keys($tlangs) as $trans){
				$record->table()->getTranslation($trans);
			}
			$tlangs = $record->table()->getTranslations();
			if ( !isset($tlangs[$_POST['--lang']]) ){
				throw new Exception("Failed to save translation because the specified record does not support this language.");
				
			}
			
			
			if ( !@$_POST['--field'] ){
				throw new Exception("No field was specified for the update.");
				
			}
			
			
			
			if ( !$record->checkPermission('edit', array('field'=>$_POST['--field']) ) ){
				throw new Exception("Failed to update field because permission was denied.");
			}
			
			$record->setValue($_POST['--field'], @$_POST['--newval']);
			$res = $record->save();
			if ( PEAR::isError($res) ){
				error_log($res->getMessage());
				throw new Exception('Failed to update field.  See server error log for details.');
			}
			
			$this->out(array(
				'code'=>200,
				'message'=>'Successfully saved field value.',
				'fieldContent'=>$record->strval($_POST['--field'])
			));
			
		} catch (Exception $ex){
		
			$this->out(array(
				'code'=>$ex->getCode(),
				'message'=>$ex->getMessage(),
				'error'=>1
			));
		
		
		}
	}
	
	function out($params){
	
		header('Content-type: text/json; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		echo json_encode($params);
	}
}