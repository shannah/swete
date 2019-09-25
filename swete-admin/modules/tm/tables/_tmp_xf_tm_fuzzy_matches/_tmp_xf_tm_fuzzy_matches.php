<?php
import(XFLIB.'Text/Diff.php');
import(XFLIB.'Text/Diff/Renderer/inline.php');

class tables__tmp_xf_tm_fuzzy_matches {
    private $sql;
    
    static function supportsInnodbFulltext() {
        $version = mysqli_get_server_info(df_db());
        if (stripos($version, 'mariadb') !== false) {
            $parts = explode('-', $version);
            $mariadbPos = -1;
            $len = count($parts);
            for ($i=0; $i<$len; $i++) {
                if (stripos($parts[$i], 'mariadb') !== false) {
                    $mariadbPos = $i;
                    break;
                }
            }
            if (version_compare($parts[$i-1], '10.0.5') >= 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $parts = explode('-', $version);
            $mariadbPos = -1;
            $len = count($parts);
            for ($i=0; $i<$len; $i++) {
                if (stripos($parts[$i], 'mysql') !== false) {
                    $mariadbPos = $i;
                    break;
                }
            }
            if (version_compare($parts[$i-1], '5.6.0') >= 0) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    function __sql__() {
        if (!isset($this->sql)) {
            $app = Dataface_Application::getInstance();
            $query = $app->getQuery();
            $needle = @$query['-needle'];
            if (!$needle) {
                $needle = @$query['-search'];
            }
            $translationMemoryId = @$query['-translation_memory_id'];
            if (!$translationMemoryId) {
                $translationMemoryId = 0;
            }
            if (!$needle) {
                $stringId = @$query['-string_id'];
                if (!$stringId) {
                    $needle = '';
                } else {

                    $sql = "select normalized_value from xf_tm_strings where string_id='".addslashes($stringId)."'";
                    $res = xf_db_query($sql, df_db());
                    $row = xf_db_fetch_row($res);
                    $needle = $row[0];
                    xf_db_free_result($res);
                }
                
                
            }
            
            if (!self::supportsInnodbFulltext()) {
                xf_db_query("create temporary table xf_tm_strings_fulltext_tmp 
                    select s.string_id, s.normalized_value from xf_tm_strings s 
                    where 
                        not exists(select string_id from xf_tm_strings_fulltext ft 
                            where string_id=s.string_id
                        )", df_db());
                xf_db_query("insert into xf_tm_strings_fulltext (string_id, normalized_value) select string_id, normalized_value from xf_tm_strings_fulltext_tmp", df_db());
                xf_db_query("delete from xf_tm_strings_fulltext_tmp", df_db());
                xf_db_query("insert into xf_tm_strings_fulltext_tmp (string_id)
                    select ft.string_id from xf_tm_strings_fulltext ft where not exists(
                        select s.string_id from xf_tm_strings s where s.string_id=ft.string_id
                        )", df_db());
                xf_db_query("delete from xf_tm_strings_fulltext where string_id in (
                    select string_id from xf_tm_strings_fulltext_tmp
                    )", df_db());
                
                
                $this->sql = "select 
                    distinct
                        s1.string_id,
                        t1.translation_id,
                        s1.normalized_value, 
                        t1.normalized_translation_value,
                        t1.created_by,
                        t1.last_modified,
                        s1.language,
                        s1.num_words,
                        match (ft.normalized_value) 
                            against ('".addslashes($needle)."') score 
                        from xf_tm_strings s1
                        inner join xf_tm_strings_fulltext ft
                        inner join xf_tm_translations t1 on s1.string_id=t1.string_id
                        inner join  xf_tm_translation_memory_translations tmt on t1.translation_id=tmt.translation_id
                        where 
                            match (ft.normalized_value) against ('".addslashes($needle)."')  
                            and tmt.translation_memory_id='".addslashes($translationMemoryId)."' 
                        order by tmt.current, score desc, t1.last_modified desc limit 10";
            } else {

                $this->sql = "select 
                    distinct
                        s1.string_id,
                        t1.translation_id,
                        s1.normalized_value, 
                        t1.normalized_translation_value,
                        t1.created_by,
                        t1.last_modified,
                        s1.language,
                        s1.num_words,
                        match (s1.normalized_value) 
                            against ('".addslashes($needle)."') score 
                        from xf_tm_strings s1
                        inner join xf_tm_translations t1 on s1.string_id=t1.string_id
                        inner join  xf_tm_translation_memory_translations tmt on t1.translation_id=tmt.translation_id
                        where 
                            match (s1.normalized_value) against ('".addslashes($needle)."')  
                            and tmt.translation_memory_id='".addslashes($translationMemoryId)."' 
                        order by tmt.current, score desc, t1.last_modified desc limit 10";
            }

        }
        //echo $this->sql;exit;
        return $this->sql;
    }

    function field__nice_modified_date(Dataface_Record $record) {
        $date = $record->strval('last_modified');
        
        return df_offset($date);
    }

    function field__similar_text_score(Dataface_Record $record) {
        $app = Dataface_Application::getInstance();
        $query = $app->getQuery();
        //echo "Needle: ".$query['-needle'].' vs '.$record->val('normalized_value');
        if ($query['-needle'] == $record->val('normalized_value')) {
            return 100;
        }
        return similar_text(
            $query['-needle'], 
            $record->val('normalized_value')
        );
    }

    function field__source_diff(Dataface_Record $record) {
        $app = Dataface_Application::getInstance();
        $query = $app->getQuery();
        $renderer = new Text_Diff_Renderer_inline();
        $diff = new Text_Diff(explode("\n", $query['-needle']), explode("\n", $record->val('normalized_value')));
        
        $out = $renderer->render($diff);
        if (trim($out)) {
            return $out;
        }
        return $record->val('normalized_value');
    }
}