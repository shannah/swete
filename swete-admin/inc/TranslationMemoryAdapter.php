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
/**
 * @brief A wrapper class for a translation memory that takes care of some standard
 * housekeeping that is often necessary before adding and retrieving translations
 * from a translation memory.  
 *
 * <p>The main reason for the existence of this class is to pre-encode and post-decode
 * strings that are added to the translation memory. The XFTranslationMemory class
 * does not do any encoding to strings that it receives, however it has a powerful
 * variable substitution feature when returning translations.  In order for this to 
 * work, we need to provide preprocessing and post processing so that our standard HTML
 * code can be used.</p>
 *
 */
class TranslationMemoryAdapter {

	private XFTranslationMemory $tm;
	private SweteSite $site;
	
	/**
	 * @see XFTranslationMemory::addString()
	 */
	public function addString($string){
		$enc = TMTools::encode($string, $params);
		return XFTranslationMemory::addString($enc, $site->getSourceLanguage());
	}
	
	/**
	 * @see XFTranslationMemory::findString()
	 */
	public function findString($string){
		$enc = TMTools::encode($string, $params);
		return XFTranslationMemory::findString($enc, $site->getSourceLanguage());
	}
	
	/**
	 * @see XFTranslationMemory::addTranslation()
	 */
	public function addTranslation($string, $translation){
		$senc = TMTools::encode($string, $p1);
		$tenc = TMTools::encode($translation, $p2);
		return $this->tm->addTranslation($senc, $tenc);
	}
	
	/**
	 * @see XFTranslationMemory::setTranslationStatus()
	 */
	public function setTranslationStatus($string, $translation, $status){
		$senc = TMTools::encode($string, $p1);
		$tenc = TMTools::encode($translation, $p2);
		return $this->tm->setTranslationStatus($senc, $tenc, $status);
	}
	
	
	public function getTranslations(array $sources, $minStatus=3, $maxStatus=5){
		$paramsArr = array();
		$encodedSources = array();
		foreach ($sources as $k=>$v){
			$params = array();
			$encodedSources[$k] = TMTools::encode($v, $params);
			$paramsArr[$k] = $params;
		}
		$translations = $this->tm->getTranslations($encodedSources, $minStatus, $maxStatus, false);
		
		$decodedTranslations = array();
		foreach ($translations as $k=>$v){
			$decodedTranslations[$k] = TMTools::decode($v, $paramsArr[$k]);
		}
		return $decodedTranslations;
	}
	
	
	
}