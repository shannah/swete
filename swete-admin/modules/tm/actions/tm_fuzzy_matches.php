<?php
class actions_tm_fuzzy_matches {

    function handle($params=array()) {
        import(XFLIB.'Text/Diff.php');
		import(XFLIB.'Text/Diff/Renderer/inline.php');
        $app = Dataface_Application::getInstance();
        $query = $app->getQuery();
        if ($query['-table'] !== '_tmp_xf_tm_fuzzy_matches') {
            die("not supported on this table.");

        }
        $results = df_get_records_array('_tmp_xf_tm_fuzzy_matches', $query);
        if (PEAR::isError($results)) {
            echo $results->getMessage();
            exit;
        }
        $firstResult = array_shift($results);
        $tmp = array();
        foreach ($results as $match) {
            
            $match->setValue('score', round(floatval($match->val('score') / floatval($firstResult->val('score')) * 100.0)));
            if ($match->val('similar_text_score') > 50) {
                $tmp[] = $match;
            } else {
               // echo "Skipping because ".$match->val('similar_text_score');
            }
            
        }
        $results = $tmp;
        df_register_skin('tm', dirname(__FILE__).'/../templates');
        df_display(array('matches' => $results), 'modules/tm/tm_fuzzy_matches.html');
    }
}