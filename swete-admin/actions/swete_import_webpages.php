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
class actions_swete_import_webpages {

	function handle($params=array()){
	
		if ( $_POST ){
			$this->handlePost($params);
		} else {
			$this->handleGet($params);
		}
		
	
	}
	
	
	function handleGet($params){
		require_once 'inc/SweteSite.class.php';
	
		$app = Dataface_Application::getInstance();
		if ( !@$_GET['website_id'] ){
			return $this->handleGet_noSettingsId($params);
		}
		$website_id = $_GET['website_id'];
		if ( preg_match('/[0-9]+$/', $website_id, $matches) ){
			$website_id = $matches[0];
		} else {
			return $this->handleGet_noSettingsId($params);
		}
		
		$settings = SweteSite::loadSiteById($website_id);
		if ( !$settings ){
			$params['website_id'] = $website_id;
			return $this->handleGet_settingsNotFound($params);
		}
	
		$jt = Dataface_JavascriptTool::getInstance();
		$jt->import('swete/actions/import_webpages.js');
		
		df_display(
			array(
				'cancel_url'=>$app->url('-action=list&website_id='.$_GET['website_id'].'&parent_id=='),
				'settings'=> $settings,
				'crawlStartingPoint' => @$_GET['--startingPoint']?$_GET['--startingPoint']:$settings->getSiteUrl(),
				'crawlDepth' => @$_GET['--depth']?$_GET['--depth']:4
			),
			'swete/actions/import_webpages.html'
		);
	
	}
	
	function handleGet_noSettingsId($params){
	
		$app = Dataface_Application::getInstance();
		
		
		$sites_to_import = array();
		$sites = df_get_records_array('websites', array());
		foreach ($sites as $site){
		
			if ( $site->checkPermission('import webpages') ){
				$sites_to_import[] = $site;
			}
		}
		
		Dataface_JavascriptTool::getInstance()->import('swete/actions/import_webpages/no_settings_id.js');
		
		df_display(
			array(
				'sites_to_import'=>$sites_to_import
			),
			'swete/actions/import_webpages/no_settings_id.html'
		);
	}
	
	
	function handleGet_settingsNotFound($params){
	
		$website_id = $params['website_id'];
		$settings = df_get_record('websites', array('website_id'=>'='.$website_id));
		if ( !$settings ){
			df_display(
				array(
					
				),
				'swete/actions/import_webpages/no_settings_found.html'
			);
			return;
		} else {
	
			df_display(
				array(
					'settings'=>$settings
				),
				'swete/actions/import_webpages/settings_site_not_found.html'
			);
		}
	}
	
	
	function handlePost($params){
		
		session_write_close();
		header('Connection: close');
		$app = Dataface_Application::getInstance();
		try {
			require_once 'inc/PageImporter.php';
			require_once 'inc/SweteSite.class.php';
			
			if ( !@$_POST['website_id'] ){
				throw new Exception("No Site Specified");
				
			}
			
			$site = SweteSite::loadSiteById($_POST['website_id']);
			if ( !$site ){
				throw new Exception("No site found by that ID.");
			}
			
			$importer = new PageImporter();
			$importer->site = $site;
			$importer->translate = true;
			$importer->logTranslationMisses = true;
			
			if ( @$_POST['--depth'] and intval($_POST['--depth']) ) $importer->depth = intval($_POST['--depth']);
			if ( @$_POST['--startingPoint'] ) $importer->startingPoint = trim($_POST['--startingPoint']);
			$importer->loadContent = true;
			
			$importer->doImport();
			
			$addedIds = array();
			$updatedIds = array();
			
			foreach ($importer->pagesAdded as $p){
				$addedIds[] = $p->val('webpage_id');
			}
			foreach ($importer->pagesUpdated as $p){
				$updatedIds[] = $p->val("webpage_id");
			}
			
			
			$out = array(
				'code'=>200,
				'message'=>'Successfully imported '.(count($importer->pagesAdded)).' pages updated '.(count($importer->pagesUpdated)).' pages.',
				'pagesUpdated'=>count($importer->pagesUpdated),
				'pagesAdded'=>count($importer->pagesAdded),
				'addedIds'=>$addedIds,
				'updatedIds'=>$updatedIds
				
			);
			
			
			
			
		} catch (Exception $ex){
		
		
			$out = array(
			
				'code'=>$ex->getCode(),
				'message'=>$ex->getMessage()
			);
			
		
		}
		
		header('Content-type: text/json; charset="'.$app->_conf['oe'].'"');
		echo json_encode($out);
		return;
		
	
	}
}