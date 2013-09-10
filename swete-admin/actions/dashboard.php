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
class actions_dashboard {
	function handle($params){
		if ( !SweteTools::getUser() ) return Dataface_Error::permissionDenied("You must log into access the dashboard");
		$app = Dataface_Application::getInstance();
		
		// Get sites summary
		import('Dataface/ResultReader.php');
		$reader = new Dataface_ResultReader("select
			ws.source_language,
			ws.target_language,
			ws.log_translation_misses,
			ws.website_id, 
			ws.website_name,
			ws.website_url,
			concat('http://',ws.host,ws.base_path) as proxy_url,
			ws.source_language,
			ws.target_language,
			(
				select count(*) from webpages w where w.website_id=ws.website_id
			) as numpages,
			(
				select count(*) from swete_strings tml where tml.website_id=ws.website_id
			) as numphrases,
			ifnull((
				select sum(tml.num_words) from xf_tm_strings xts 
					inner join swete_strings tml on tml.string_id=xts.string_id
				where tml.website_id=ws.website_id
			), 0) as numwords,
			(
				select count(*) from swete_strings tml
					inner join websites ws2 on ws2.website_id=tml.website_id
					inner join xf_tm_translation_memory_strings xttms on xttms.translation_memory_id=ws2.translation_memory_id and xttms.string_id=tml.string_id
				where xttms.current_translation_id is not null
				 	and tml.website_id=ws.website_id
				
			) as translated_phrases,
			
			ifnull((
				select sum(tml.num_words) from swete_strings tml
					inner join websites ws2 on ws2.website_id=tml.website_id
					inner join xf_tm_translation_memory_strings xttms on xttms.translation_memory_id=ws2.translation_memory_id and xttms.string_id=tml.string_id
					inner join xf_tm_strings xts on xttms.string_id=xts.string_id
				where xttms.current_translation_id is not null
					and tml.website_id=ws.website_id
					
			), 0) as translated_words
			
				
			from websites ws
			", df_db());
		$results = array();
		
		$languages = Dataface_Table::loadTable('websites')->getValuelist('languages');
		
		foreach ($reader as $row){
			$results[] = $row;
			$row->untranslated_words = $row->numwords-$row->translated_words;
			$row->untranslated_phrases = $row->numphrases-$row->translated_phrases;
			$row->source_label = @$languages[$row->source_language] ? $languages[$row->source_language] : $row->source_language;
			$row->target_label = @$languages[$row->target_language] ? $languages[$row->target_language] : $row->target_language;
			
		}
		
		Dataface_JavascriptTool::getInstance()->import('swete/actions/dashboard.js');
		
		$res = df_q("select count(*) from webpages");
		list($numPages) = mysql_fetch_row($res);
		@mysql_free_result($res);
		
		$res = df_q("select count(*) num_phrases, sum(xts.num_words) as num_words from swete_strings tml 
			left join xf_tm_strings xts on tml.string_id=xts.string_id");
		list($numPhrases, $numWords) = mysql_fetch_row($res);
		@mysql_free_result($res);
		
		$res = df_q("select count(*) from websites");
		list($numSites) = mysql_fetch_row($res);
		@mysql_free_result($res);
		
		$res = df_q("select count(*) as numphrases, ifnull(sum(xts.num_words),0) as num_words from swete_strings tml 
			inner join websites w on w.website_id=tml.website_id
			inner join xf_tm_translation_memory_strings xttms on w.translation_memory_id=xttms.translation_memory_id and xttms.string_id=tml.string_id
			inner join xf_tm_strings xts on xts.string_id=tml.string_id
			where xttms.current_translation_id is not null");
		list($translatedPhrases, $translatedWords) = mysql_fetch_row($res);
		@mysql_free_result($res);
		
		
		df_display(array(
			'results' => $results,
			'systemStats' => array(
				'numWords' => $numWords,
				'numPhrases' => $numPhrases,
				'numSites' => $numSites,
				'numPages' => $numPages,
				'translatedPhrases' => $translatedPhrases,
				'translatedWords' => $translatedWords,
				'untranslatedWords' => $numWords-$translatedWords,
				'untranslatedPhrases' => $numPhrases-$translatedPhrases
			),
			'swete_version' => file_get_contents('version.txt')
			), 'swete/actions/dashboard.html');
			
	}
}