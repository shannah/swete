<?php
class actions_swete_translate_page {
    function handle($params) {
        $app = Dataface_Application::getInstance();
        $query = $app->getQuery();
        if ($query['-table'] != 'webpage_status') {
            die("This only works on webpage_status table.");
        }
        $rec = $app->getRecord();
        $strings = @$query['--untranslated-only'] ? $rec->val('missed_strings') : $rec->val('strings');
        if (!$strings) {
            $strings = array();
        } else {
            $strings = json_decode($strings, true);
        }  
        //echo count($strings);exit;
        if (count($strings) > 0) {
            $stringIds = array();
            $res = df_q("select `string_id` from xf_tm_strings where `hash` in ('".implode("','", $strings)."')");
            while ($row = xf_db_fetch_assoc($res)) {
                $stringIds[] = intval($row['string_id']);
            }
            $strings = $stringIds;
        }
        $url = $app->url('-limit='.count($strings).'&-table=swete_strings&website_id=='.urlencode($rec->val('website_id')).'&string_id=='.implode('+OR+', $strings)).'&--msg='.urlencode('Strings for '.$rec->val('page_url'));
        header('Location: '.$url);
        exit;      
    }
}