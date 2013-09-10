<?php
/**
 * Description of swete_strings
 *
 * @author shannah
 */
require_once 'modules/tm/lib/TMTools.php';
class tables_swete_strings {
    
    function getPermissions($record){
        if ( SweteTools::isAdmin() ){
            $perms = Dataface_PermissionsTool::ALL();
            $perms['edit'] = 0;
            $perms['new'] = 0;
            $perms['copy'] = 0;
            $perms['update'] = 0;
            $perms['update_set'] = 0;
            return $perms;
        }
    }
    
    function block__custom_javascripts(){

            $jt = Dataface_JavascriptTool::getInstance();
            $jt->import('swete/actions/add_selected_strings_to_job.js');	
    }

    function getSourceLanguage($record){
            return $record->val('source_language');
    }

    function getDefaultTargetLanguage($record){
            return $record->val('destination_language');
    }

    function getTargetLanguages($record){
            return array($this->getDefaultTargetLanguage($record));
    }

    function getTranslationMemoryId($record, $source, $dest){
            return $record->val('translation_memory_id');
    }

    function getTitle($record){
            return substr(strip_tags($record->val('normalized_string')), 0, 50);
    }

    function block__before_result_list_content(){
            $app = Dataface_Application::getInstance();
            $query = $app->getQuery();
            $builder = new Dataface_QueryBuilder($query['-table'], $query);
            $sql = 'select sum(num_words) '.$builder->_from().$builder->_where();
            $res = df_q($sql);
            $row = mysql_fetch_row($res);
            @mysql_free_result($res);
            $app->addHeadContent('<style type="text/css">#total-words-found {float:right;width: 200px;}</style>');
            echo '<div id="total-words-found">Total Words: '.$row[0].'</div>';
            Dataface_JavascriptTool::getInstance()->import('swete/actions/batch_google_translate.js');
    }


    function normalized_string__csvValue(Dataface_Record $record){
        return TMTools::encode($record->val('string'), $params);
    }

    function normalized_translation_value__csvValue(Dataface_Record $record){
        return $record->val('normalized_translation_value');
    }
    
    function deleteRecord($record){
        if ( $record->val('translation_memory_id') ){
            $res = df_q(sprintf("delete from translation_miss_log where string_id=%d and translation_memory_id=%d", 
                    $record->val('string_id'), 
                    $record->val('translation_memory_id')
                  )
            );
            $res = df_q(sprintf("delete from xf_tm_translation_memory_strings where string_id=%d and translation_memory_id=%d",
                    $record->val('string_id'),
                    $record->val('translation_memory_id')
            ));
            
        } else {
            $res = df_q(sprintf("delete from translation_miss_log where string_id=%d and translation_memory_id IS NULL", 
                    $record->val('string_id')
                  )
            );
        }
        
        
        return $res;
    }
}

