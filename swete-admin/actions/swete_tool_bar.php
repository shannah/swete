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
class actions_swete_tool_bar {

	function handle($params){
		
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		$website = df_get_record('websites', array('website_id'=> '='.$query['website_id']));
		if ( !$website ) throw new Exception("Website could not be found.");
		
		if ( !$website->checkPermission('capture strings') ){
			return Dataface_Error::permissionDenied("You don't have permission to perform this action.");
		}
		
		Dataface_JavascriptTool::getInstance()->import('swete/actions/swete_tool_bar.js');
		import('inc/SweteSite.class.php');
		df_display(array(
			'website' => $website,
			'websiteWrapper' => new SweteSite($website)
			), 'swete/actions/toolbar_wrapper.html');
	}
}