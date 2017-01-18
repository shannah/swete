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
require_once 'modules/tm/lib/TMTools.php';
require_once 'modules/tm/lib/XFTranslationDictionary.php';
require_once 'modules/tm/lib/XFTranslationMemoryReader.php';

/**
 * Class that handles all of the translation memory functionality.
 * @author Steve Hannah <steve@weblite.ca>
 */
class XFTranslationMemory implements XFTranslationDictionary {

	const TRANSLATION_REJECTED=XFTranslationMemoryReader::TRANSLATION_REJECTED;
	//const TRANSLATION_VOTE_DOWN=2;
	const TRANSLATION_SUBMITTED=XFTranslationMemoryReader::TRANSLATION_SUBMITTED;
	//const TRANSLATION_VOTE_UP=4;
	const TRANSLATION_APPROVED=XFTranslationMemoryReader::TRANSLATION_APPROVED;

	/**
	 * @type Dataface_Record
	 *
	 * Encapsulated record from the xf_tm_translation_memories table
	 */
	private $_rec;
	
	/**
	 * @brief All methods for simply reading translations from a translation
	 * memory have been factored out into the XFTranslationMemoryReader
	 * class which, itself, has no dependencies.  This allows it to be used in
	 * situations where performance is a premium.
	 *
	 * @type XFTranslationMemoryReader
	 */
	private $_reader;
	
	public $error_message = null;
	

	/**
	 * @brief Creates a new TranslationMemory record that wraps a record of the 
	 * xf_tm_translation_memories table.
	 * @param Dataface_Record $rec A record from the xf_tm_translation_memories table.
	 */
	public function __construct(Dataface_Record $rec){
		XFTranslationMemoryReader::$db = df_db();
		$this->_rec = $rec;
	}
	
	/**
	 * @brief Returns the reader object used to read from the translation memory.
	 *
	 * @returns XFTranslationMemoryReader
	 */
	protected function getReader(){
		if ( !isset($this->_reader) ){
			$this->_reader = new XFTranslationMemoryReader($this->_rec->getStdObject());
			XFTranslationMemoryReader::$db = df_db();
		}
		return $this->_reader;
	}
	
	/**
	 * @brief Gets the encapsulated xf_tm_translation_memories record.
	 * @return Dataface_Record A record from the xf_tm_translation_memories table.
	 */
	public function getRecord(){
		return $this->_rec;
	}
	
	/**
	 * @brief Gets the 2-digit language code of the source language of this
	 * translation memory.
	 */
	public function getSourceLanguage(){
		return $this->_rec->val('source_language');
	}
	
	
	/**
	 * @brief Gets the 2-digit language code of the destination language of this
	 * translation memory.
	 */
	public function getDestinationLanguage(){
		return $this->_rec->val('destination_language');
	}

	
	/**
	 * @brief Stores the default translation memories that are in the system.
	 * Translation memories can be assigned to individual records.  But if none
	 * is assigned to a record, then it uses the default translation memory.
	 */
	private static $defaultTranslationMemories = null;
	
	
	/**
	 * @brief Gets the default translation memory for the given source
	 * and destination languages.
	 *
	 * @param string $source The 2-digit language code of the source language.
	 * @param string $dest The 2-diggit language code of the destination language.
	 * @param boolean $secure True if inserting a translation memory record should
	 *		be subject to Xataface permissions.
	 * @return XFTranslationMemory The defautl translation memory with the given 
	 *		source and destination languages.
	 */
	public static function getDefaultTranslationMemory($source, $dest, $secure=false){
		$source = strtolower($source);
		$dest = strtolower($dest);
		if ( !isset(self::$defaultTranslationMemories) ){
			self::$defaultTranslationMemories = array();
		}
		
		if ( !isset(self::$defaultTranslationMemories[$source]) ){
			self::$defaultTranslationMemories[$source] = array();
		}
		
		if ( !isset(self::$defaultTranslationMemories[$source][$dest]) ){
			$rec = df_get_record('xf_tm_records',
				array(
					'record_id'=>'=*',
					'source_language'=>'='.$source,
					'destination_language'=>'='.$dest
				)
			);
			
			if ( $rec ){
			
				self::$defaultTranslationMemories[$source][$dest] = self::loadTranslationMemoryById($rec->val('translation_memory_id'));
				
			}
		}
		
		if ( !isset(self::$defaultTranslationMemories[$source][$dest]) ){
			$tm = self::createTranslationMemory('Default '.$source.'=>'.$dest, $source, $dest, $secure);
			$tm->assignTo('*');
			self::$defaultTranslationMemories[$source][$dest] = $tm;
		}
		return self::$defaultTranslationMemories[$source][$dest];
	}
	
	/**
	 * @brief Creates a translation memory and saves it to the database.
	 *
	 * 
	 * @param string $name The name of the translation memory.
	 * @param string $source The 2-digit language code of the source language for this 
	 *		translation memory.
	 * @param string $dest The 2-digit language code of the destination language.
	 * @param boolean $secure True if inserting the translation memory should be 
	 *		subject to xataface permissions.
	 * @return XFTranslationMemory The translation memory object.
	 * @throws Exception If no such memory is found AND it fails to create a new one.
	 *
	 *
	 */
	public static function createTranslationMemory($name, $source, $dest, $secure=false){
		$source = strtolower($source); $dest = strtolower($dest);
		if ( !preg_match('/^[a-z0-9]{2}$/', $source) ){
			throw new Exception("Invalid source language code inserting a translation memory: $source");
		}
		if ( !preg_match('/^[a-z0-9]{2}$/', $dest) ){
			throw new Exception("Invalid destination language code inserting a translation memory: $dest");
		}
		
		$rec = new Dataface_Record('xf_tm_translation_memories',array());
		$rec->setValues(array(
			'translation_memory_name'=>$name,
			'source_language'=>$source,
			'destination_language'=>$dest
		));
		$res = $rec->save($secure);
		if ( PEAR::isError($res) ){
			throw new Exception("Failed to create translation memory ".$res->getMessage(), $res->getCode());
		}
		
		$tm = new XFTranslationMemory($rec);
		
		return $tm;	
	}
	
	
	
	/**
	 * @brief Assigns the current translation memory to the record with the specified id.
	 * 
	 * @param string $recid The ID of the record to assign this translation memory to.  '*' for default.
	 * @param boolean $secure True if inserting this join record should be subject to xatafae 
	 * 	permissions.
	 * @return void
	 * @throws Exception If it fails to save.
	 */
	public function assignTo($recid, $secure = false){
		$rec = new Dataface_Record('xf_tm_records', array());
		$rec->setValues(array(
			'record_id'=>$recid,
			'translation_memory_id'=>$this->_rec->val('translation_memory_id')
		));
		$res = $rec->save($secure);
		if ( PEAR::isError($res) ){
			throw new Exception("Failed to assign the translation memory to the record with id ".$recid);
			
		}
		
	}

	/**
	 * @brief Loads a translation memory by its translation_memory_id
	 * @param int $id The translation_memory_id of the memory to load.
	 * @return XFTranslationMemory
	 */
	public static function loadTranslationMemoryById($id){
		$tmrec  = df_get_record('xf_tm_translation_memories', array('translation_memory_id'=>'='.$id));
		if ( !$tmrec ) return null;
		return new XFTranslationMemory($tmrec);
	}
        
        /**
	 * @brief Loads a translation memory by its translation_memory_id
	 * @param int $id The translation_memory_id of the memory to load.
	 * @return XFTranslationMemory
	 */
	public static function loadTranslationMemoryByUuid($id){
		$tmrec  = df_get_record('xf_tm_translation_memories', array('translation_memory_uuid'=>'='.$id));
		if ( !$tmrec ) return null;
		return new XFTranslationMemory($tmrec);
	}

	/**
	 * @brief Loads the translation memory for a given record.
	 *
	 * @param Dataface_Record $record The record for which the translation memory is used.
	 * @param string $source The 2-digit language code of the source language.
	 * @param string $dest The 2-digit language code of the destination language.
	 * @return XFTranslationMemory or null if it cannot be found.
	 */
	public static function loadTranslationMemoryFor(Dataface_Record $record, $source, $dest){
		$del = $record->table()->getDelegate();
		if ( isset($del) and method_exists($del, 'getTranslationMemoryId') ){
			$tmid = $del->getTranslationMemoryId($record, $source, $dest);
			if ( $tmid ){
				return self::loadTranslationMemoryById($tmid);
			}
		}
		
		$rec = df_get_record('xf_tm_records', 
			array(
				'record_id'=>'='.$record->getId(),
				'source_language'=>'='.$source,
				'destination_language'=>'='.$dest
			)
		);
		if ( !$rec ){
			return self::getDefaultTranslationMemory($source, $dest);
		}
		
		
		
		if ( !$rec->val('translation_memory_id') ){
			return null;
		}
		
		return self::loadTranslationMemoryById($rec->val('translation_memory_id'));
	}
	
	/**
	 * @brief Adds a translation to the current translation memory.  If the translation
	 *  already exists in the system then that same translation will be linked to the
	 *  translation memory.  Otherwise the translation will be created and linked.
	 * @param string $string The source string.
	 * @param string $translation The translated string.
	 * @param string $username The username of the user who is adding the string.  If omitted
	 *		it will use the currently logged-in user.
	 * @param boolean $secure True if adding this translation should be subject to 
	 *		xataface permissions.
	 * @return Dataface_Record The translation record from the xf_tm_translations table.
	 *
	 */
	public function addTranslation($string, $translation, $username=null, $secure=false){
	    // Find permutations
	    $permutedString = preg_replace('#<g id=\"(\d+)"></g>#', '<x id="$1"/>', $string);
	    if ($permutedString != $string) {
	        $this->addTranslation($permutedString, preg_replace('#<g id=\"(\d+)"></g>#', '<x id="$1"/>', $translation), $username, $secure);
	    }
		if ( !$username ) $username = Dataface_AuthenticationTool::getInstance()->getLoggedInUserName();
		$strid = null;
		if ( is_int($string) ){
			$strid = $string;
		}
		
		if ( !$strid ){
			$strRec = self::findString($string, $this->getSourceLanguage());
			if ( !$strRec ){
				$strRec = self::addString($string, $this->getSourceLanguage(), $secure);
				
			}
			if ( !$strRec ){
				throw new Exception("Failed to add string $string");
			}
			$strid = intval($strRec->val('string_id'));
			
		}
		
		
		$trec = $this->findTranslation($strid, $translation);
		if ( !$trec ){
			
			$normalized = TMTools::normalize($translation);
			$hash = md5($normalized);
			$trec = new Dataface_Record('xf_tm_translations', array());
			$trec->setValues(array(
				'string_id'=>$strid,
				'translation_value'=>$translation,
				'normalized_translation_value'=> $normalized,
				'language'=> $this->getDestinationLanguage(),
				'translation_hash'=>$hash,
				'created_by' => $username
			));
			$res = $trec->save($secure);
			if ( PEAR::isError($res) ){
				throw new Exception('Failed to add translation "$translation": '.$res->getMessage(), $res->getCode());
			}
		}
		
		$tmid = $this->_rec->val('translation_memory_id');
		// Now add this translation to the translation memory
		$res = mysql_query("insert ignore into xf_tm_translation_memory_translations 
			(translation_memory_id,translation_id)
			values
			('".addslashes($tmid)."',
			 '".addslashes($trec->val('translation_id'))."'
			 )", df_db());
		if ( !$res ) throw new Exception(mysql_error(df_db()));
		
		/*
		$res = df_q("insert ignore into xf_tm_translation_memory_strings (
			translation_memory_id,
			string_id,
			status_id
			) values (
				'".addslashes($tmid)."',
				'".addslashes($strid)."',
				NULL
			)");
		*/	
		
		
		return $trec;
			
		
		
		
	}
	
	/**
	 * @brief Checks if the current translation memory contains the specified 
	 * 	translation.
	 * @param string $string The source string
	 * @param string $translation The translated string.
	 * @return boolean True if it contains the translation.
	 */
	public function containsTranslation($string, $translation){
		return $this->getReader()->containsTranslation($string, $translation);
		/*
		$tr = $this->findTranslation($string, $translation);
		if ( !$tr ) return false;
		$tm = df_get_record('xf_tm_translation_memory_translations', 
			array(
				'translation_memory_id'=>'='.$this->_rec->val('translation_memory_id'),
				'translation_id'=>'='.$tr->val('translation_id')
			)
		);
		if ( !$tm ) return false;
		else return true;
		*/
	}
	
	
	/**
	 * @brief Finds a record encapsulating the given string.
	 * @param string $string The string that we want to find.
	 * @return Dataface_Record The record for the string from the xf_tm_strings table.
	 */
	public static function findString($string, $language){
		$rec = XFTranslationMemoryReader::findString($string, $language);
		if ( $rec ){
			return new Dataface_Record('xf_tm_strings', $rec);
		} else {
			return null;
		}
		/*
		$normalized = TMTools::normalize($string);
		$hash = md5($normalized);
		$strRec = df_get_record('xf_tm_strings', 
			array(
				'normalized_value'=>'='.$normalized, 
				'hash'=>'='.$hash,
				'language'=>'='.$language));
		if ( !$strRec ) return null;
		
		// We want to fill variables in this string to match what was asked of us.
		$strRec->setValue(
			'string_value', 
			TMTools::fillVars(
				$strRec->val('normalized_value'),
				$string
			)
		);
		return $strRec;
		*/
	}
	
	/**
	 * @brief Finds a string record by id
	 * @param $string_id The string_id of the string that we want to find.
	 * @return Dataface_Record The record for the string from the xf_tm_strings table.
	 */
	public static function loadStringById($stringId){
		$rec = XFTranslationMemoryReader::loadStringById($stringId);
		if ( $rec ){
			return new Dataface_Record('xf_tm_strings', $rec);
		} else {
			return null;
		}
	}
	
	
	/**
	 * @brief Adds the given string to the database.
	 * @param string $string The string to be added.
	 * @param string $language The 2-digit language code of the language for the string.
	 * @param boolean $secure True if adding the string should be subject
	 *		to Xataface permissions.
	 * @return Dataface_Record Record from the xf_tm_strings table.
	 */
	public static function addString($string, $language, $secure=false){
		
		$str = self::findString($string, $language);
		if ( !$str ){
			$app = Dataface_Application::getInstance();
			$strRec = new Dataface_Record('xf_tm_strings', array());
			$normalized = TMTools::normalize($string);
			$hash = md5($normalized);
			$strRec->setValues(array(
				'language'=>$language,
				'string_value'=>$string,
				'normalized_value'=>$normalized,
				'hash'=> $hash,
				'num_words' => TMTools::numWords($normalized)
			));
			$res = $strRec->save($secure);
			if ( PEAR::isError($res) ){
				error_log("Error: ".$string);
				throw new Exception($res->getMessage());
			}
			return $strRec;
				
		}
		return $str;
	}
	
	
	/**
	 * @brief Finds the translation record for the given translation.
	 * @param string $string The source string or the integer string id of the string.
	 * 
	 * @param string $translation The translated String
	 * @return Dataface_Record Record from the xf_tm_translations table.
	 *
	 */
	public function findTranslation($string, $translation){
		
		$trRec = $this->getReader()->findTranslation($string, $translation);
		if ( $trRec ){
			return new Dataface_Record('xf_tm_translations', $trRec);
		} else {
			return null;
		}
		/*
		$strid = null;
		if ( is_int($string) ) $strid = $string;
		
		if ( !$strid ){
			
			$strRec = self::findString($string, $this->getSourceLanguage());
			if ( $strRec ){
				$strid = intval($strRec->val('string_id'));
			}
			
		}
		if ( !$strid ) return null;
		
		$normalizedTranslation = TMTools::normalize($translation);
		$hashTranslation = md5($normalizedTranslation);
		
		$trRec = df_get_record('xf_tm_translations', 
			array(
				'string_id'=>'='.$strid,
				'normalized_translation_value'=>'='.$normalizedTranslation,
				'translation_hash'=>'='.$hashTranslation,
				'language'=>'='.$this->getDestinationLanguage()
				
			)
		);
		
		if ( !$trRec ) return null;
		
		// We want to fill the vars in the translation so that it matches
		// the requested translation.
		$trRec->setValue(
			'translation_value',
			TMTools::fillVars(
				$trRec->val('normalized_translation_value'),
				$translation
			)
		);
		
		return $trRec;
		*/
		
	}
	
	/**
	 * @brief Scores a translation.  
	 *
	 * @param string $string The source string (or integer string id)
	 * @param string $translation The translation string (or integer translation id)
	 * @param int $score The score to be applied to the translation.
	 * @param string $username The username of the user who is marking it (default is currently logged in user).
	 * @param boolean $secure True if marking this translation should be subject to Xataface permissions.
	 * @return Dataface_Record record from the xf_tm_translations_score table.
	 *
	 */
	 		
	public function scoreTranslation($string, $translation, $score, $username=null, $secure=false){
		
		if ( !$username ) $username = Dataface_AuthenticationTool::getInstance()->getLoggedInUserName();
		$trec = $this->findTranslation($string, $translation);
		
		if ( !$trec ){
			$trec = $this->addTranslation($string, $translation, $username, $secure);
		}
		
		if ( !$trec ){
			throw new Exception("Could not find matching translation and failed to add one.");
		}
		
		$arec = new Dataface_Record('xf_tm_translations_score', array());
		$arec->setValues(array(
			'translation_id'=>$trec->val('translation_id'),
			'translation_memory_id'=>$this->_rec->val('translation_memory_id'),
			'username'=>$username,
			'score'=>$score
		));
		$res = $arec->save($secure);
		if ( PEAR::isError($res) ){
			throw new Exception("Failed to approve translation: ".$res->getMessage());
			
		}
		return $arec;
		
	}
	
	
	/**
	 * @brief Sets the status of a translation.  Should be one of <ol>
	 *	<li>@code XFTranslationMemory::TRANSLATION_REJECTED @endcode</li>
	 *	<li>@code XFTranslationMemory::TRANSLATION_SUBMITTED @endcode</li>
	 *  <li>@code XFTranslationMemory::TRANSLATION_APPROVED @endcode</li>
	 *	</ol>
	 *
	 * @param string $string The source string (or int string id).
	 * @param string $translation The translated string (or int translation id)
	 * @param string $username The username of the user who is setting this (default to current
	 *		logged in user.
	 * @param boolean $secure True if setting the status should be subject to xataface permissions.
	 * @return Dataface_Record Record from the xf_tm_translations_status table.
	 *
	 * <h3>Xataface Event</h3>
	 *
	 * <p>This function will fire a Xataface event that can be responded to 
	 * by registering an event listener with the Dataface_Application class.</p>
	 *
	 * @code
	 * function handleEvent(stdObject $event){
	 *		//$event->string:String => The source string
	 *		//$event->translation:String => The translated string
	 *		//$event->status:int => The status
	 *		//$event->username:String => The username of the user who set the status
	 *		//$event->translationMemory:XFTranslationMemory => The translation memory object.
	 *		//$event->statusRecord:Dataface_Record => The record from the xf_tm_translation_memory_status table.
	 *		//$event->translationRecord:Dataface_Record => The record from the xf_tm_translations table.
	 * }
	 *
	 * Dataface_Application::getInstance()->registerEventListener('tm.setTranslationStatus', 'handleEvent');
	 * @endcode
	 */
	public function setTranslationStatus($string, $translation, $status, $username=null, $secure=false){
	    $permutedString = preg_replace('#<g id=\"(\d+)"></g>#', '<x id="$1"/>', $string);
	    if ($permutedString != $string) {
	        $this->setTranslationStatus($permutedString, preg_replace('#<g id=\"(\d+)"></g>#', '<x id="$1"/>', $translation), $status, $username, $secure);
	    }
		df_q("start transaction");
		try {
			if ( !$username ) $username = Dataface_AuthenticationTool::getInstance()->getLoggedInUserName();
			$trec = $this->findTranslation($string, $translation);
			
			if ( !$trec ){
				$trec = $this->addTranslation($string, $translation, $username, $secure);
			}
			
			if ( !$trec ){
				throw new Exception("Could not find matching translation and failed to add one.");
			}
			
			$arec = new Dataface_Record('xf_tm_translations_status', array());
			$arec->setValues(array(
				'translation_id'=>$trec->val('translation_id'),
				'translation_memory_id'=>$this->_rec->val('translation_memory_id'),
				'username'=>$username,
				'status_id'=>$status
			));
			$res = $arec->save($secure);
			if ( PEAR::isError($res) ){
				throw new Exception("Failed to set translation status: ".$res->getMessage());
				
			}
			$tmid = $this->_rec->val('translation_memory_id');
			$strid = $trec->val('string_id');
			$trid = $trec->val('translation_id');
			
			//$res = df_q("update xf_tm_translation_memory_translations set current=0 where translation_memory_id='".addslashes($this->_rec->val('translation_memory_id'))."'");
			$res = df_q("update xf_tm_translation_memory_translations 
				set status_id='".addslashes($status)."' 
				where 
					translation_memory_id='".addslashes($tmid)."' and 
					translation_id='".addslashes($trid)."'"
			);
			$doInsert = false;
            try {
            	error_log("Updating strings");
                $res = df_q("update xf_tm_translation_memory_strings set 
                    current_translation_id='".addslashes($trid)."', 
                    status_id='".addslashes($status)."',
                    flagged=0,
                    last_touched=NOW()
                    where
                        translation_memory_id='".addslashes($tmid)."' and 
                        string_id='".addslashes($strid)."' and 
                        (status_id='".addslashes($status)."' or current_translation_id='".addslashes($trid)."')");
                error_log("Affected rows: ". mysql_affected_rows(df_db()));
            } catch ( Exception $ex){
            	error_log("Deleting entries because ".$ex->getMessage());
                $res = df_q("delete from xf_tm_translation_memory_strings where 
                    translation_memory_id='".addslashes($tmid)."' and 
                    string_id='".addslashes($strid)."'");
                $doInsert = true;
                    
            }
			if ( $doInsert or  mysql_affected_rows(df_db()) == 0 ){
				try {
					$res = df_q("insert into xf_tm_translation_memory_strings (
						translation_memory_id,
						string_id,
						status_id,
						current_translation_id,
						last_touched)
						values (
						'".addslashes($tmid)."',
						'".addslashes($strid)."',
						'".addslashes($status)."',
						'".addslashes($trid)."',
						NOW())");
				} catch ( Exception $ex){
					error_log("Failed to insert string $strid into translation memory $tmid with status $status and translation $trid because ".$ex->getMessage());
				}
			}
			
			$event = new stdClass;
			$event->string = $string;
			$event->translation = $translation;
			$event->status = $status;
			$event->username = $username;
			$event->translationMemory = $this;
			$event->statusRecord = $arec;
			$event->translationRecord = $trec;
			Dataface_Application::getInstance()->fireEvent('tm.setTranslationStatus', $event);
			
			df_q("commit");
			return $arec;
		} catch ( Exception $ex){
			df_q("rollback");
			throw $ex;
		}
	}
	
	
	/**
	 * @brief Adds a comment to a translation.
	 * @param string $string The source string (or int string id).
	 * @param string $translation The translated string (or int translation id)
	 * @param string $username The usernmae of hte user who is adding the comment (default to logged in user).
	 * @param boolean $secure True if adding the comment should be subject to xataface permissions.
	 * @return Dataface_Record record from the xf_tm_translations_comments table.
	 */
	public function addTranslationComment($string, $translation, $comment, $username=null, $secure=false){
		if ( !$username ) $username = Dataface_AuthenticationTool::getInstance()->getLoggedInUserName();
		$trec = $this->findTranslation($string, $translation);
		
		if ( !$trec ){
			$trec = $this->addTranslation($string, $translation, $username, $secure);
		}
		
		if ( !$trec ){
			throw new Exception("Could not find matching translation and failed to add one.");
		}
		
		$arec = new Dataface_Record('xf_tm_translations_comments', array());
		$arec->setValues(array(
			'translation_id'=>$trec->val('translation_id'),
			'translation_memory_id'=>$this->_rec->val('translation_memory_id'),
			'posted_by'=>$username,
			'comments'=>$comment
		));
		$res = $arec->save($secure);
		if ( PEAR::isError($res) ){
			throw new Exception("Failed to approve translation: ".$res->getMessage());
			
		}
		return $arec;
	}
	
	public function getFlaggedTranslations(array $sources){
		$out = array();
		$normalized = array();
		$hashed = array();
		$hashIndex = array();
		$disqualified = array();
		foreach ($sources as $k=>$src){
			$normalized[$k] = TMTools::normalize($src);
			$hashed[$k] = md5($normalized[$k]);
			$hashIndex[$hashed[$k]][] = $k;
			$out[$k] = null;
		}
		
		$hashesStr = "'".implode("','", $hashed)."'";
		if ( !$hashesStr ) $hashesStr = '0';
		$hashesStr = '('.$hashesStr.')';
		$tmid = $this->_rec->val('translation_memory_id'); 
		
		$sql = "select 
			s.`hash`,
			t.normalized_translation_value as translation_value,
			tts.status_id
			from 
				xf_tm_translations t
				inner join xf_tm_translation_memory_strings tts on (
					t.translation_id=tts.current_translation_id and 
					tts.translation_memory_id='".addslashes($tmid)."' and
					tts.flagged=1
				)
				inner join xf_tm_strings s on t.string_id=s.string_id
			where 
				tts.translation_memory_id='".addslashes($tmid)."'
				and s.`hash` in $hashesStr
				and tts.flagged=1
			
				";
		$res  = mysql_query($sql, df_db());
		if ( !$res ) throw new Exception(mysql_error(df_db()));
		
		while ($row = mysql_fetch_assoc($res) ){
			$ks = $hashIndex[$row['hash']];
			foreach ($ks as $k){
				if ( !isset($k) ){
					throw new Exception("Invalid hash returned");
				}
				if ( !isset($out[$k]) ){
					//if ( $row['status_id'] < $minStatus or $row['status_id'] > $maxStatus ){
					//	$disqualified[$k] = true;
					//} else if ( !@$disqualified[$k] ){
						$out[$k] = $row['translation_value'];
					//}
				}
			}
		}
		
		// Un-normalize
		foreach ($out as $k=>$v){
			if ( isset($v) ){
				$out[$k] = TMTools::fillVars($v, $sources[$k]);
			}
		}
		
		@mysql_free_result($res);
		return $out;
		
		
	}
	
	/**
	 * @brief Finds if there is a translation in this TM for the given source string id.
	 * @param string $string The source string or the integer string id of the string.
	 * @param $info if this is true, the return value will include more info (translation_value, date_created, created_by)
	 * @param int $minStatus The minimum status of a translation to return (5=approved, 3=submitted, 1=rejected)
	 * @param int $maxStatus The maximum status of a translation to return (5=approved, 3=submitted, 1=rejected)
	 * @return results: translation_id, translation_value, status_id
	 */
	public function findTranslationByString($string, $minStatus=3, $maxStatus=5, $info=false){
		return $this->getReader()->findTranslationByString($string, $minStatus=3, $maxStatus=5);
	}
	
	
	/**
	 * @brief Gets translated strings corresponding to the given source strings.
	 * @param array $sources Array of source strings.
	 * @param $info if this is true, the return value will include more info (translation_value, date_created, created_by)
	 * @param int $minStatus The minimum status of a translation to return (5=approved, 3=submitted, 1=rejected)
	 * @param int $maxStatus The maximum status of a translation to return (5=approved, 3=submitted, 1=rejected)
	 */
	public function getTranslations(array $sources, $minStatus=3, $maxStatus=5, $info=false){
		return $this->getReader()->getTranslations($sources, $minStatus, $maxStatus, $info);
		/*
		$out = array();
		$outInfo = array(); //more info than $out
		$normalized = array();
		$hashed = array();
		$hashIndex = array();
		$disqualified = array();
		foreach ($sources as $k=>$src){
			$normalized[$k] = TMTools::normalize($src);
			$hashed[$k] = md5($normalized[$k]);
			$hashIndex[$hashed[$k]][] = $k;
			$out[$k] = null;
		}
		
		$hashesStr = "'".implode("','", $hashed)."'";
		if ( !$hashesStr ) $hashesStr = '0';
		$hashesStr = '('.$hashesStr.')';
		$tmid = $this->_rec->val('translation_memory_id'); 
		
		$sql = "select 
			s.`hash`,
			t.normalized_translation_value as translation_value,
			tts.status_id
			from 
				xf_tm_translations t
				inner join xf_tm_translation_memory_strings tts on (
					t.translation_id=tts.current_translation_id and 
					tts.translation_memory_id='".addslashes($tmid)."'
					and tts.status_id <= '".addslashes($maxStatus)."'
					and tts.status_id >= '".addslashes($minStatus)."'
				)
				inner join xf_tm_strings s on t.string_id=s.string_id
				
			where 
				tts.translation_memory_id='".addslashes($tmid)."'
				and s.`hash` in $hashesStr
				and tts.status_id>=".intval($minStatus)."
				and tts.status_id<=".intval($maxStatus)."
			order by tts.status_id desc
				";

		$res  = mysql_query($sql, df_db());
		if ( !$res ) throw new Exception(mysql_error(df_db()));
		
		while ($row = mysql_fetch_assoc($res) ){
			$ks = $hashIndex[$row['hash']];
			foreach ($ks as $k){
				if ( !isset($k) ){
					throw new Exception("Invalid hash returned");
				}
				if ( !isset($out[$k]) ){
					//if ( $row['status_id'] < $minStatus or $row['status_id'] > $maxStatus ){
					//	$disqualified[$k] = true;
					//} else if ( !@$disqualified[$k] ){
						$out[$k] = $row['translation_value'];
						$outInfo[$k] = $row;
					//}
				}
			}
		}
		
		// Un-normalize
		foreach ($out as $k=>$v){
			if ( isset($v) ){
				$out[$k] = TMTools::fillVars($v, $sources[$k]);
				
				$outInfo[$k]['translation_value'] = $out[$k];
			}
		}
		
		@mysql_free_result($res);
		
		if ($info)
			return $outInfo;
		else
			return $out;
		*/	
	}
	
	
	public function flag($string, $username, $flag_value=true, $comment=null){
		$flag_value = $flag_value ? '1':'0';
		$strid = null;
		if ( is_int($string) ) $strid = $string;
		
		if ( !$strid ){
			
			$strRec = self::findString($string, $this->getSourceLanguage());
			if ( $strRec ){
				$strid = intval($strRec->val('string_id'));
			}
			
		}
		
		if ( !$strid ) throw new Exception("Failed to flag string [$string] because it could not be found");
		$tmid = $this->_rec->val('translation_memory_id');
		$rec = new Dataface_Record('xf_tm_translation_memory_flags', array());
		$rec->setValues(array(
			'translation_memory_id' => $tmid,
			'string_id' => $strid,
			'username' => $username,
			'comment' => $comment,
			'flag_value' => $flag_value
		));
		$res = $rec->save();
		if ( PEAR::isError($res) ){
			throw new Exception($res->getMessage(), $res->getCode());
		}
		
		$res = df_q("update xf_tm_translation_memory_strings 
			set 
				flagged='".addslashes($flag_value)."' 
			where 
				translation_memory_id='".addslashes($tmid)."' and 
				string_id='".addslashes($strid)."'");
		
		
	}
	
	
	
	public function rebuild($transaction=false){
		try {
			if ( $transaction ){
				df_q("start transaction");
			}
			$tmid = $this->_rec->val('translation_memory_id');
			$res = df_q("delete from xf_tm_translation_memory_strings where translation_memory_id='".addslashes($tmid)."'");
			
			df_q("
				insert into xf_tm_translation_memory_strings (
					translation_memory_id,
					string_id,
					status_id,
					current_translation_id
				)
				select distinct tts.translation_memory_id, t.string_id, (
					select status_id from xf_tm_translations_status tts2
						inner join xf_tm_translations t2 on tts2.translation_id=t2.translation_id
						where 
							tts2.translation_memory_id=tts.translation_memory_id and
							t2.string_id=t.string_id
						order by tts2.date_created desc
						limit 1
					) as status_id, (
					select tts2.translation_id from xf_tm_translations_status tts2
						inner join xf_tm_translations t2 on tts2.translation_id=t2.translation_id
						where 
							tts2.translation_memory_id=tts.translation_memory_id and
							t2.string_id=t.string_id
						order by tts2.date_created desc
						limit 1
					) as current_translation_id
					from 
						xf_tm_translations_status tts
						inner join xf_tm_translations t on tts.translation_id=t.translation_id
					where tts.translation_memory_id='".addslashes($tmid)."'
					group by tts.translation_memory_id, t.string_id, status_id, current_translation_id");
					
			$res = df_q("delete from xf_tm_translation_memory_translations where translation_memory_id='".addslashes($tmid)."'");
			
			df_q("
				insert into xf_tm_translation_memory_translations (
					translation_memory_id,
					translation_id,
					status_id
				)
				select tts.translation_memory_id, tts.translation_id, (
					select status_id from xf_tm_translations_status tts2
						where 
							tts2.translation_memory_id=tts.translation_memory_id and
							tts2.translation_id=tts.translation_id
						order by tts2.date_created desc
						limit 1
					) as status_id
					
					from 
						xf_tm_translations_status tts
						
					where tts.translation_memory_id='".addslashes($tmid)."'
					group by tts.translation_memory_id, tts.translation_id");
			
			
			
			if ( $transaction ){
				df_q("commit");
			}
		} catch (Exception $ex){
			if ( $transaction ){
				df_q("rollback");
				
			}
			throw $ex;
		}
	
	
	}
	
	
	
	/**
	 * @brief Imports the translations and associated comments, statuses, scores, and
	 * comments from one translation memory into another.
	 *
	 * @param XFTranslationMemory $from The translation memory from which translations 
	 * are to be copied.
	 * @returns boolean True on success, False on failure.  If it fails, an error
	 * string will be reported in the $this->error_message variable.
	 *
	 */
	public function import(XFTranslationMemory $from){
		$fromId = $from->getRecord()->val('translation_memory_id');
		$toId = $this->_rec->val('translation_memory_id');
		$sql[] = "start transaction";
		//$sql[] = "create temporary table temp_translation_memory_strings as select * from xf_tm_translation_memory_strings where translation_memory_id='".addslashes($fromId)."'";
		//$sql[] = "insert ignore 
		//	into `xf_tm_translation_memory_strings` (translation_memory_id, string_id, status_id, current_translation_id)  
		//	select '".addslashes($toId)."' as translation_memory_id, string_id, status_id, current_translation_id from temp_translation_memory_strings";
	    //$sql[] = "drop table temp_translation_memory_strings";
		
		//$sql[] = "create temporary table temp_translation_memory_translations as select * from xf_tm_translation_memory_translations where translation_memory_id='".addslashes($fromId)."'";
		//$sql[] = "insert ignore 
		//	into `xf_tm_translation_memory_translations` (translation_memory_id, translation_id, status_id)  
		//	select '".addslashes($toId)."' as translation_memory_id, translation_id, status_id from temp_translation_memory_translations";
	    //$sql[] = "drop table temp_translation_memory_translations";
		
		$sql[] = "create temporary table temp_translations_status as 
			select * from xf_tm_translations_status where translation_memory_id='".addslashes($fromId)."'";
		$sql[] = "insert ignore 
			into xf_tm_translations_status (translation_memory_id, translation_id, username, status_id, date_created, last_modified) 
			 select '".addslashes($toId)."' as translation_memory_id, translation_id, username, status_id, date_created, last_modified from temp_translations_status
			where translation_memory_id='".addslashes($fromId)."'";
		$sql[] = "drop table temp_translations_status";
		
		$sql[] = "create temporary table temp_translation_memory_strings as 
			select * from xf_tm_translation_memory_strings where translation_memory_id='".addslashes($fromId)."'";
		$sql[] = "insert ignore 
			into xf_tm_translation_memory_strings (translation_memory_id, string_id, status_id, current_translation_id, flagged, last_touched) 
			 select '".addslashes($toId)."' as translation_memory_id, string_id, status_id, current_translation_id, flagged, last_touched from temp_translation_memory_strings
			where translation_memory_id='".addslashes($fromId)."'";
		$sql[] = "drop table temp_translation_memory_strings";
		
		$sql[] = "create temporary table temp_translations_score as select * from xf_tm_translations_score where translation_memory_id='".addslashes($fromId)."'";
		$sql[] = "insert ignore into xf_tm_translations_score (translation_memory_id, translation_id, username, score, date_created, last_modified)
			 select '".addslashes($toId)."' as translation_memory_id, translation_id, username, score, date_created, last_modified from temp_translations_score";
		$sql[] = "drop table temp_translations_score";
		$sql[] = "create temporary table temp_translations_comments as select * from xf_tm_translations_comments where translation_memory_id='".addslashes($fromId)."'";
		$sql[] = "insert ignore into xf_tm_translations_comments (translation_id, translation_memory_id, posted_by, date_created, last_modified, comments)
		  select translation_id, '".addslashes($toId)."' as translation_memory_id, posted_by, date_created, last_modified, comments from temp_translations_comments";
		$sql[] = "drop table temp_translations_comments";
		
		try {
			df_q($sql);
			$this->rebuild();
			df_q("commit");
			$evt = new stdClass;
			$evt->sourceTranslationMemory = $from;
			$evt->destinationTranslationMemory = $this;
			Dataface_Application::getInstance()->fireEvent('XFTranslationMemory.afterImport', $evt);
			$this->error_message = null;
			return true;
		} catch (Exception $ex){
			$this->error_message = $ex->getMessage();
			error_log($this->error_message);
			df_q("rollback");
			return false;
		}
		
		
	}	
	
	
	
}