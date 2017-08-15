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
/**
 * This class is meant to be a lightweight version of the XFTranslationMemory class.
 * It provides only methods to read from a translation memory and it has no outside
 * dependencies other than TMTools.  This is designed to be as light and fast as 
 * possible so that it can be used as a part of a translation proxy.
 * @author Steve Hannah <steve@weblite.ca>
 */
class XFTranslationMemoryReader implements XFTranslationDictionary {

	const TRANSLATION_REJECTED=1;
	//const TRANSLATION_VOTE_DOWN=2;
	const TRANSLATION_SUBMITTED=3;
	//const TRANSLATION_VOTE_UP=4;
	const TRANSLATION_APPROVED=5;

	public $error_message = null;
	
	private $_rec;
	
	
	public static $db = null;
	

	/**
	 * @brief Creates a new TranslationMemory record that wraps a record of the 
	 * xf_tm_translation_memories table.
	 * @param Dataface_Record $rec A record from the xf_tm_translation_memories table.
	 */
	public function __construct(stdClass $record){
		$this->_rec = $record;
	}
	
	
	public function getRecord(){
		return $this->_rec;
	}
	
	public function getSourceLanguage(){
		return $this->_rec->source_language;
	}
	
	public function getDestinationLanguage(){
		return $this->_rec->destination_language;
	}

	
	public static function q($sql){
		
		if ( !isset(self::$db) ){
			throw new Exception("DB handle is null.");
		}
		$res = xf_db_query($sql, self::$db);
		if ( !$res ) throw new Exception(xf_db_error(self::$db));
		return $res;
	}
	

	/**
	 * @brief Loads a translation memory by its translation_memory_id
	 * @param int $id The translation_memory_id of the memory to load.
	 * @return XFTranslationMemory
	 */
	public static function loadTranslationMemoryById($id){
		$res = self::q("select * from xf_tm_translation_memories where translation_memory_id='".intval($id)."'");
		$tmrec = xf_db_fetch_object($res);
		
		if ( !$tmrec ) return null;
		return new XFTranslationMemoryReader($tmrec);
	}

	
	/**
	 * @brief Checks if the current translation memory contains the specified 
	 * 	translation.
	 * @param string $string The source string
	 * @param string $translation The translated string.
	 * @return boolean True if it contains the translation.
	 */
	public function containsTranslation($string, $translation){
		$tr = $this->findTranslation($string, $translation);
		if ( !$tr ) return false;
		$res = self::q("select translation_id from xf_tm_translation_memory_translations
			where
				translation_memory_id='".addslashes($this->_rec->translation_memory_id)."'
				and
				translation_id='".addslashes($tr->translation_id)."'");
		$row = xf_db_fetch_row($res);
		@xf_db_free_result($res);
		if ( $row ) return true;
		else return false;
		
	}
	
	
	/**
	 * @brief Finds a record encapsulating the given string.
	 * @param string $string The string that we want to find.
	 * @return stdClass The record for the string from the xf_tm_strings table.
	 */
	public static function findString($string, $language){
		$normalized = TMTools::normalize($string);
		$hash = md5($normalized);
		$res = self::q("select * from xf_tm_strings 
			where
				normalized_value='".addslashes($normalized)."'
				and
				`hash`='".addslashes($hash)."'
				and
				`language`='".addslashes($language)."'
			");
		$strRec = xf_db_fetch_object($res);
		@xf_db_free_result($res);
		if ( !$strRec ) return null;
		
		// We want to fill variables in this string to match what was asked of us.
		$strRec->string_value = TMTools::fillVars(
				$strRec->normalized_value,
				$string
			);
		return $strRec;
	}
	
	/**
	 * @brief Finds a string record by id
	 * @param $string_id The string_id of the string that we want to find.
	 * @return stdClass The record for the string from the xf_tm_strings table.
	 */
	public static function loadStringById($string_id){
		$res = self::q("select * from xf_tm_strings where
				string_id='".addslashes($string_id)."'");
		$strRec = xf_db_fetch_object($res);
		@xf_db_free_result($res);
		if ( !$strRec ) return null;
		return $strRec;
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
		$strid = null;
		if ( is_int($string) ) $strid = $string;
		
		if ( !$strid ){
			
			$strRec = self::findString($string, $this->getSourceLanguage());
			if ( $strRec ){
				$strid = intval($strRec->string_id);
			}
			
		}
		if ( !$strid ) return null;
		
		$normalizedTranslation = TMTools::normalize($translation);
		$hashTranslation = md5($normalizedTranslation);
		$res = self::q("select * from xf_tm_translations where
			string_id='".addslashes($strid)."'
			and
			normalized_translation_value='".addslashes($normalizedTranslation)."'
			and
			translation_hash='".addslashes($hashTranslation)."'
			and
			`language`='".addslashes($this->getDestinationLanguage())."'
			limit 1
			");
		$trRec = xf_db_fetch_object($res);
		
		if ( !$trRec ) return null;
		
		// We want to fill the vars in the translation so that it matches
		// the requested translation.
		$trRec->translation_value = TMTools::fillVars(
				$trRec->normalized_translation_value,
				$translation
			)
		;
		
		return $trRec;
		
		
	}
	
	/**
	 * @brief Finds if there is a translation in this TM for the given source string id.
	 * @param string $string The source string or the integer string id of the string.
	 * @param int $minStatus The minimum status of a translation to return (5=approved, 3=submitted, 1=rejected)
	 * @param int $maxStatus The maximum status of a translation to return (5=approved, 3=submitted, 1=rejected)
	 * @return results: translation_id, translation_value, status_id
	 */
	public function findTranslationByString($string, $minStatus=3, $maxStatus=5){
		$strid = null;
		if ( is_int($string) ) $strid = $string;
		
		if ( !$strid ){
			
			$strRec = self::findString($string, $this->getSourceLanguage());
			if ( $strRec ){
				$strid = intval($strRec->string_id);
			}
			
		}
		if ( !$strid ) return null;
		
		
		$hash = $strRec->hash;
		
		$tmid = $this->_rec->translation_memory_id; 
		
		$sql = "select
				t.translation_id,
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
				and s.`hash`='".addslashes($hash)."'
				and tts.status_id>=".intval($minStatus)."
				and tts.status_id<=".intval($maxStatus)."
			order by tts.status_id desc, tts.last_touched desc
				";

		$res  = self::q($sql);
		
		$row =  xf_db_fetch_assoc($res);
		
		return $row;
	}
	
	
	/**
	 * @brief Gets translated strings corresponding to the given source strings.
	 * @param array $sources Array of source strings.
	 * @param $info if this is true, the return value will include more info (translation_value, date_created, created_by)
	 * @param int $minStatus The minimum status of a translation to return (5=approved, 3=submitted, 1=rejected)
	 * @param int $maxStatus The maximum status of a translation to return (5=approved, 3=submitted, 1=rejected)
	 */
	public function getTranslations(array $sources, $minStatus=3, $maxStatus=5, $info=false){
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
		$tmid = $this->_rec->translation_memory_id; 
		
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
			order by tts.status_id desc, tts.last_touched desc
				";

		$res  = self::q($sql);
		
		while ($row = xf_db_fetch_assoc($res) ){
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
		
		@xf_db_free_result($res);
		
		if ($info)
			return $outInfo;
		else
			return $out;
			
	}
	
	
	
	
	
	
	
	
	
	
	
	
}