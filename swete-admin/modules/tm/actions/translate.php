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
class actions_translate {
    
        private static $E_USER=501;

	private $translationMemories = array();
	
	
	public function getTranslationMemory(Dataface_Record $record, $source, $dest){
		$recordId = $record->getId();
		if ( !isset($this->translationMemories[$recordId][$source][$dest]) ){
			$this->translationMemories[$recordId][$source][$dest] = XFTranslationMemory::loadTranslationMemoryFor($record, $source, $dest);
		}
		return $this->translationMemories[$recordId][$source][$dest];
	}
	
        function handle($params){
            try {
                $this->handle2($params);
            } catch ( Exception $ex){
                if ( $ex->getCode() === self::$E_USER ){
                    return Dataface_Error::permissionDenied($ex->getMessage());
                } else {
                    throw $ex;
                }
            }
        }
        
	function handle2($params){
		import('modules/tm/lib/XFTranslationMemory.php');
		$app =& Dataface_Application::getInstance();
		$query = $app->getQuery();
		$table = Dataface_Table::loadTable($query['-table']);
		$source = $app->_conf['lang'];
		$dest = ( isset($query['-destinationLanguage']) ? $query['-destinationLanguage'] : null);
		$mod = Dataface_ModuleTool::getInstance()->loadModule('modules_tm');
		
		if ( !@$app->_conf['languages'] ){
			throw new Exception("This application doesn't have multilingual content enabled.", self::$E_USER);
		}
		

		$translatableLanguages = array_keys($app->_conf['languages']);
		while (!isset($dest) ){
			foreach ($translatableLanguages as $l){
				if ( $l != $source ){
					$dest = $l;
				}
			}
		}
		
		if ( !isset($dest) ) throw new Exception("Cannot find applicable destination language.", self::$E_USER);
		
		
		$records = df_get_selected_records($query);
		if ( !$records ){
			if ( @$query['-multiple'] ){
				$records = df_get_records_array($query['-table'], $query, null, null, false);
			} else {
				$r = $app->getRecord();
				$records = array();
				$records[] = $r;
				
				
			}
		}
		
		if ( !$records ){
			throw new Exception("No records matches your query.", self::$E_USER);
		}
		
		$out = array();
		$tmid = -1;
		foreach ($records as $record){
			
			$tm = $this->getTranslationMemory($record, $source, $dest);

			$_tmid = '';
			if ( $tm ){
				$_tmid = $tm->getRecord()->val('translation_memory_id');
				$source = $tm->getSourceLanguage();
				$dest = $tm->getDestinationLanguage();
			}
			if ( $_tmid and $tmid >= 0 and $tmid !== $_tmid ){
				throw new Exception("Records on this translation form use different translation memories.  You can only use one translation memory at a time.", self::$E_USER);
			}
			if ( $_tmid and $tmid < 0 ) $tmid = $_tmid;
			
			$row = array();
			$tlangs = $record->table()->getTranslations();
			foreach (array_keys($tlangs) as $trans){
				$record->table()->getTranslation($trans);
			}
			$tlangs = $record->table()->getTranslations();
			
			
			
			$row['title'] = $record->getTitle();
			$row['id'] = $record->getId();
			
			if ( !$record->checkPermission('translate') ){
				$row['error'] = 'Cannot translate this record because permission is denied.';
				$out[] = $row;
				continue;
			}
			
			if ( !isset($tlangs[$dest]) ){
				$row['error'] = 'Record cannot be translated into '.$app->_conf['languages'][$dest];
				
				$out[] = $row;
				continue;
			}
			
			//print_r($tlangs);
			$tfields = $tlangs[$dest];
			
			
			$tr = $this->getTranslationRow($record, $dest);
			//print_r($tr); echo $dest;exit;
		
			$row['data'] = array();
			
			
			$keys = $record->table()->keys();
			
			foreach ($tfields as $f){
				if ( isset($keys[$f]) ) continue;
				$row['data'][$f]['fielddef'] = $record->table()->getField($f);
				$row['data'][$f][$source] = $record->strval($f);
				//echo $f;exit;
				if ( $tr and isset($tr->{$f}) ){
					$row['data'][$f][$dest] = $tm->findTranslationByString($row['data'][$f][$source]);
				}
			}
			$out[] = $row;
			
			
			
			
			
		}
		import('Dataface/LanguageTool.php');
		$otherDests = $app->_conf['languages'];
		foreach ($otherDests as $k=>$v){
			if ( $k == $source or $k == $dest ){
				unset($otherDests[$k]);
				
			} else {
				$otherDests[$k] = Dataface_LanguageTool::getInstance()->getLanguageLabel($k);
			}
		}
		
		if ( $tmid < 0 ) $tmid = '';
		
		$translationSettings = @$app->_conf['_translate_settings'];
		if ( !$translationSettings ) $translationSettings = array();
		
		$context = array(
			'otherDests'=>$otherDests,
			'records' => $out,
			'sourceCode'=>$source,
			'destCode'=>$dest,
			'sourceLabel'=>Dataface_LanguageTool::getInstance()->getLanguageLabel($source),
			'destLabel'=>Dataface_LanguageTool::getInstance()->getLanguageLabel($dest),
			'translationMemoryId'=>$tmid,
			'translationSettings'=>$translationSettings
		);
		
		
		$jt = Dataface_JavascriptTool::getInstance();
		$jt->addPath(dirname(__FILE__).'/../js', $mod->getBaseURL().'/js');
		$jt->import('xataface/modules/tm/translate.js');
		$ct = Dataface_CSSTool::getInstance();
		$ct->addPath(dirname(__FILE__).'/../css', $mod->getBaseURL().'/css');
		
		df_register_skin('tm', dirname(__FILE__).'/../templates');
		$tpl = 'xataface/modules/tm/translate.html';
		if ( @$query['-nowrapper'] ){
			$tpl = 'xataface/modules/tm/translate_nowrapper.html';
		}
		df_display($context, $tpl );
		
		
		
	}
	
	
	function getTranslationRow(Dataface_Record $record, $dest){
		if ( !preg_match('/^[a-z0-9]{2}$/', $dest) ){
			throw new Exception("Invalid language: $dest");
		}
		if ( $dest != Dataface_Application::getInstance()->_conf['default_language'] ){
			$table = $record->table()->tablename.'_'.$dest;
		} else {
			$table = $record->table()->tablename;
		}
		$sql = "select * from `$table` where ";
		$where = array();
		$serializer = new Dataface_Serializer($record->table()->tablename);
		
		foreach ( $record->table()->keys() as $k=>$fld){
			$where[] = '`'.$k."`='".addslashes($serializer->serialize($k, $record->val($k)))."'";
		}
		$where = implode(' AND ', $where);
		$sql .= $where;
		$res = xf_db_query($sql, df_db());
		if ( !$res ){
			throw new Exception(xf_db_error(df_db()));
		}
		if ( xf_db_num_rows($res) > 1 ){
			throw new Exception("Duplicate translation rows for record ".$record->getId().".");
		}
		$out = xf_db_fetch_object($res);
		@xf_db_free_result($res);
		return $out;
		
		
	}
}