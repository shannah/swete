<?php
/**
 * Description of swete_strings
 *
 * @author shannah
 */
require_once 'modules/tm/lib/TMTools.php';
import('xf/db/Database.php');
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
            $row = xf_db_fetch_row($res);
            @xf_db_free_result($res);
            $app->addHeadContent('<style type="text/css">#total-words-found {float:right;width: 200px;}</style>');
            echo '<div id="total-words-found">Total Words: '.$row[0].'</div>';
            Dataface_JavascriptTool::getInstance()->import('swete/actions/batch_google_translate.js');
            $this->pages_select();
    }
    
    function find_common_prefix($array = array()) {
        $pl = 0; // common prefix length
        $n = count($array);
        if ($n === 0) {
            return '';
        }
        $l = strlen($array[0][1]);
        while ($pl < $l) {
            $c = $array[0][1][$pl];
            for ($i=1; $i<$n; $i++) {
                if ($array[$i][1][$pl] !== $c) break 2;
            }
            $pl++;
        }
        $prefix = substr($array[0][1], 0, $pl);
        return $prefix;
    }
    
    function pages_select() {
        
        $app = Dataface_Application::getInstance();
        $query = $app->getQuery();

        $siteId = @$query['website_id'];
        if (!$siteId) {
            $webpageStatus = $app->getDelegate()->getLastLoadedWebpageStatus();
            if ($webpageStatus) {
                $siteId = $webpageStatus->val('website_id');
            }
        }
        if (!$siteId) {
            return;
        }
        if ($siteId{0} == '=') {
            $siteId = substr($siteId, 1);
        }
        $db = new xf\db\Database(df_db());
        $res = $db->query('select webpage_status_id, page_url from webpage_status where website_id=:site_id order by page_url', array(
            'site_id' => $siteId
        ));
        $rows = array();
        while ($row = xf_db_fetch_row($res)) {
            if (strpos($row[1], ':') !== false) {
                $row[1] = substr($row[1], strpos($row[1], ':')+1);
            }
            $rows[] = $row;
        }
        $prefix = $this->find_common_prefix($rows);

        $prefixLen = strlen($prefix);
        if ($prefixLen > 0) {
            foreach ($rows as $k=>$row) {
                $row[1] = substr($row[1], $prefixLen);
                $rows[$k] = $row;
            }
        }
        echo '<select>'."\n";
        foreach ($rows as $row) {
            echo '<option value="'.htmlspecialchars($row[0]).'">'.htmlspecialchars($row[1]).'</option>'."\n";
        }
        echo '</select>';
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

