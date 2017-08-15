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
 * The base class for the translation memory module.
 */
class modules_tm {

    private $pathsRegistered = false;
	
	/**
	 * @brief The base URL to the datepicker module.  This will be correct whether it is in the 
	 * application modules directory or the xataface modules directory.
	 *
	 * @see getBaseURL()
	 */
	private $baseURL = null;
	/**
	 * @brief Returns the base URL to this module's directory.  Useful for including
	 * Javascripts and CSS.
	 *
	 */
	public function getBaseURL(){
		if ( !isset($this->baseURL) ){
			$this->baseURL = Dataface_ModuleTool::getInstance()->getModuleURL(__FILE__);
		}
		return $this->baseURL;
	}
	
	
	
	public function __construct(){
		$base = 'xf_tm_';
		$tables = array(
			$base.'records',
			$base.'record_strings',
			$base.'strings',
			$base.'translations',
			$base.'translations_comments',
			$base.'translations_score',
			$base.'translations_status',
			$base.'translation_memories',
			$base.'translation_memories_managers',
			$base.'translation_memory_translations',
			$base.'translation_statuses',
			$base.'workflows',
			$base.'workflow_records',
			$base.'workflow_steps',
			$base.'workflow_step_changes',
			$base.'workflow_step_panels',
			$base.'workflow_step_panel_actions',
			$base.'workflow_step_panel_members',
			$base.'workflow_strings'
			
		
		);
		
		foreach ($tables as $table){
			Dataface_Table::setBasePath($table, dirname(__FILE__));
		}
		
		// Make sure that people can't browse these tables directly.
		//Dataface_Application::getInstance()->_conf['_disallowed_tables']['translation memory'] = '/^xf_tm_/';
		
	}
	
	public function registerPaths(){
	    if ( !$this->pathsRegistered ){
	        $jt = Dataface_JavascriptTool::getInstance();
            $jt->addPath(dirname(__FILE__).'/js', $this->getBaseURL().'/js');
            
            $ct = Dataface_CSSTool::getInstance();
            $ct->addPath(dirname(__FILE__).'/css', $this->getBaseURL().'/css');
            
            df_register_skin('tm', dirname(__FILE__).'/templates');
	    }
	}
	
}