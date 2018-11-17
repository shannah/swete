<?php
class actions_dashboard_site_stats {

    function handle($params){
        $query =Dataface_Application::getInstance()->getQuery();
        if ( !isset($query['website_id']) ){
            throw new Exception("No site id provided");
        }
        
        $site = df_get_record('websites', array('website_id'=>'='.$query['website_id']));
        if ( !$site->checkPermission('view') ){
            throw new Exception("No permission to perform action");
        }
        
        import('Dataface/ResultReader.php');
		
		$reader = new Dataface_ResultReader("select
			ws.source_language,
			ws.target_language,
			ws.log_translation_misses,
			ws.website_id, 
			ws.website_name,
			ws.website_url,
			ws.translation_memory_id,
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
			
				
			from websites ws where website_id='".addslashes($query['website_id'])."'
			", df_db());
		
		$results = array();
		
		//$languages = Dataface_Table::loadTable('websites')->getValuelist('languages');
		
		foreach ($reader as $row){
			$results[] = $row;
			$row->untranslated_words = $row->numwords-$row->translated_words;
			$row->untranslated_phrases = $row->numphrases-$row->translated_phrases;
			//$row->source_label = @$languages[$row->source_language] ? $languages[$row->source_language] : $row->source_language;
			//$row->target_label = @$languages[$row->target_language] ? $languages[$row->target_language] : $row->target_language;
			
		}
		if (@$query['--format'] == 'json') {
		    header('Content-type: application/json; charset="UTF-8"');
		    echo json_encode(array('results' => $results));
		    exit;
		}
		df_display(array(
			'results' => $results
			), 'swete/actions/dashboard_site_stats.html');
		
    }
}