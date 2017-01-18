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
 * @brief Handler for the @p tm_flag_translation action which provides a REST API to flag
 * strings in a translation memory.
 *
 * @section tm_add_translations_request_parameters Request Parameters
 *
 * <table>
 *		<tr><th>Name</th><th>Description</th></tr>
 *		<tr><td>@p --source</td><td>The 2-digit ISO code of the source language.</td></tr>
 *		<tr><td>@p --dest</td><td>The 2-digit ISO code of the destination language.</td></tr>
 *		<tr><td>@p --strings</td><td>An array of source strings.</td></tr>
 *      <tr><td>@p --flag_value</td><td>Either 0 or 1.  1 for flagging. 0 For unflag.</td></tr>
 *		<tr<td>@p --tmid</td><td>Optional translation memory id that the translations should be added to.</td></tr>
 * </table>
 *
 * @section tm_flag_translation_response_structure Response Structure
 *
 * This action will return mimetype text/json with a JSON object of the following structure:
 *
 * @code
 * {
 *   code:  <int>,               // The response code.  200 for success.
 *   message: <string>,          // The response message.  Error or success.
 * }
 * @endcode
 *
 * @section tm_flag_translation_permissions Permissions
 *
 * This action requires that the user has the @p "tm:flag translation"  permission for the
 * translation memory to which the strings are being added.
 *
 *
 * 
 */
class actions_tm_flag_translation {

	function handle($params){
	
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		session_write_close();
		header('Connection:close');
		
		try {
		
			
			if ( !@$_POST['--source'] ) throw new Exception("No source language provided");
			if ( !@$_POST['--dest'] ) throw new Exception("No destination language provided");
			if ( !@$_POST['--strings'] or !is_array($_POST['--strings']) ){
				throw new Exception("No strings provided");
			}
			
			$flagValue = '1';
			if ( @$_POST['--flag_value'] ) $flagValue = $_POST['--flag_value'];
			
			$comments = '';
			if ( @$_POST['--comments'] ) $comments = $_POST['--comments'];
			
			if ( !preg_match('/^[a-z0-9]{2}$/', $_POST['--source']) ){
				throw new Exception("Invalid source language.");
			}
			
			if ( !preg_match('/^[a-z0-9]{2}$/', $_POST['--dest']) ){
				throw new Exception("Invalid destination language");
			}
			
			
			import(dirname(__FILE__).'/../lib/XFTranslationMemory.php');
			
			$tm = null;
			if ( @$_POST['--tmid'] ){
				$tm = XFTranslationMemory::loadTranslationMemoryById($_POST['--tmid']);
			} else {
				$tm = XFTranslationMemory::getDefaultTranslationMemory($_POST['--source'], $_POST['--dest']);
			}
			if ( !$tm ){
				throw new Exception("Failed to load translation memory.");
			}
			if ( !$tm->getRecord()->checkPermission('tm:flag translation') ){
				throw new Exception("You don't have permission to add translations to this translation memory.");
			}
			if ( strcmp($_POST['--source'], $tm->getSourceLanguage()) !== 0 ){
				throw new Exception("Translation memory source language does not match language of translations being added.");
			}
			if ( strcmp($_POST['--dest'], $tm->getDestinationLanguage()) !== 0 ){
				throw new Exception("Translation memory destination language does not match language of translations being added.");
			}
			
			
			$username = 'Anonymous';
			if ( class_exists('Dataface_AuthenticationTool') ){
				$username = Dataface_AuthenticationTool::getInstance()->getLoggedInUserName();
			}
			
			$saved = array();
			$code = 200;
			foreach ($_POST['--strings'] as $k=>$str){
				try {
				
					$tm->flag($str, $username, $flagValue, $comments);
					
				} catch (Exception $ex){
					error_log('Failed to flag translation "$str"" : '.$ex->getMessage());
				}
				
			
			}
			
			$this->out(array(
				'code'=>200,
				'message'=>'Saving successful'
			));
			
			
			
		} catch (Exception $ex){
		
			$this->out(array(
				'code'=>$ex->getCode(),
				'message'=>$ex->getMessage()
			));
		
		}
	
	}
	
	function out($params){
	
		header('Content-type: text/json; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		echo json_encode($params);
	}
}