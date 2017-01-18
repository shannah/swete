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
 * @brief Handler for the @p tm_get_strings action which provides a REST API to retrieve
 * translations from the translation memory that match a set of strings.
 *
 * @section tm_get_strings_request_parameters Request Parameters
 *
 * <table>
 *		<tr><th>Name</th><th>Description</th></tr>
 *		<tr><td>@p --source</td><td>The 2-digit ISO code of the source language.</td></tr>
 *		<tr><td>@p --dest</td><td>The 2-digit ISO code of the destination language.</td></tr>
 *		<tr><td>@p --strings</td><td>An array of strings that we wish to retrieve translations for.</td></tr>
 *		<tr><td>@p --tmid</td><td>Optional translation memory ID to load translations from.</td></tr>
 * </table>
 *
 * @section tm_get_strings_response_structure Response Structure
 *
 * This action will return mimetype text/json with a JSON object of the following structure:
 *
 * @code
 * {
 *   code:  <int>,       // The response code.  200 for success.
 *   message: <string>,  // The response message.  Error or success.
 *   strings: <object>, // Object where the keys are the source strings, and the values
 *                       // are the corresponding translations.  Null if the translation 
 *                       // was not found.
 * }
 * @endcode
 *
 * @section tm_get_strings_permissions Permissions
 *
 * This action requires that the user has the @p tm:get strings permission.
 *
 * 
 */
class actions_tm_get_strings {

	function handle($params){
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		session_write_close();
		header('Connection:close');
		
		try {
			
			if ( !isset($_POST['--source']) ){
				throw new Exception("No source language specified");
			}
			
			if ( !isset($_POST['--dest']) ){
				throw new Exception("No destination language specified");
			}
			
			if ( !isset($_POST['--strings']) or !is_array($_POST['--strings']) ){
				throw new Exception("No strings provided for translation.");
			}
			
			
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
			if ( !$tm->getRecord()->checkPermission('tm:get strings') ){
				throw new Exception("You don't have permission to get strings from this translation memory.");
			}
			if ( strcmp($_POST['--source'], $tm->getSourceLanguage()) !== 0 ){
				throw new Exception("Translation memory source language does not match language of translations being added.");
			}
			if ( strcmp($_POST['--dest'], $tm->getDestinationLanguage()) !== 0 ){
				throw new Exception("Translation memory destination language does not match language of translations being added.");
			}
			
			
			$translations = $tm->getTranslations($_POST['--strings'], 3, 5);
			$out = array();
			foreach ($translations as $k=>$v){
				$out[$_POST['--strings'][$k]] = $v;
			}
			
			$this->out(array(
				'strings'=>$out,
				'code'=>200,
				'message'=>'Successfully retrieved translations'
			));
	
		
		} catch (Exception $ex){
			error_log(__FILE__.'['.__LINE__.']: '.$ex->getMessage().' Code='.$ex->getCode());
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