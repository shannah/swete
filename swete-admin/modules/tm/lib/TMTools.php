<?php
require_once 'modules/tm/lib/TranslationStringParser.php';
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
 * @brief Utility class to perform useful functions for the translation memory.
 * @author Steve Hannah <steve@weblite.ca>
 */
class TMTools {

	
	/**
	 * @brief Normalizes a string for insertion or comparison with the translation
	 * memory.
	 *
	 * Note that this function also strips out variable tags (<v></v>) so that 
	 * variable strings are all stored the exact same way in the database.
	 *
	 *
	 * @param string $str The string to be normalized.
	 * @return string The normalized string.
	 */
	public static function normalize($str){
	
		mb_regex_encoding('UTF-8');
		$str = mb_ereg_replace('&lt;', '&amp;lt;', $str);
		$str = mb_ereg_replace('&gt;', '&amp;gt;', $str);
		$str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
		$str = mb_ereg_replace('<v ([^>]+)>([\s\S]*?)</v>', '<v \\1></v>', $str);
		return trim(mb_ereg_replace('\s+', ' ', $str));
	}
	
	/**
	 * @brief Refills the variable tags in a normalized string (or template) with
	 *  the values from an un-normalized string.
	 * @param string $template A normalized string with empty <v></v> tags that need to
	 * 	be filled.
	 * @param string $source An unnormalized string that contains the full <v> tags.
	 * @return The template string with variables filled in.
	 */
	public static function fillVars($template, $source){
		$count = preg_match_all('/(<v [^>]+>)([\s\S]+?)(<\/v>)/', $source, $matches, PREG_SET_ORDER);
		foreach ($matches as $match){
			$template = preg_replace(
				'/'.preg_quote($match[1], '/').preg_quote($match[3], '/').'/',
				$match[1].$match[2].$match[3],
				$template
			);
		}
		return $template;
		
	}
	
	public static function encode($str, &$params){
		if ( !preg_match('/<[a-zA-Z][^>]*>/', $str) ){
			$params = array();
			return $str;
		}
		return TranslationStringParser::toXML($str, $params);
	}
	
	public static function decode($str, $params){
		if ( !preg_match('/<[a-zA-Z][^>]*>/', $str) ) return $str;
		return TranslationStringParser::toHTML($str, $params);
	}
	
	public static function encodeForXLIFF($str){
	    $enc = preg_replace('/<v ([^>]+)>([\s\S]*?)<\/v>/', '<g ctype="x-variable" $1>$2</g>', $str);
	    $enc = preg_replace('/&/', '&amp;', $enc);
	    return $enc;
	}
	
	public static function decodeFromXLIFF($str){
	    $dec = preg_replace('/&amp;/', '&', $str);
	    $dec = preg_replace('/<g ctype="x-variable" ([^>]+)>([\s\S]*?)<\/g>/', '<v $1>$2</v>', $str);
	    return $dec;
	}
	
	public static function numWords($str){
		return str_word_count($str);
	}
}