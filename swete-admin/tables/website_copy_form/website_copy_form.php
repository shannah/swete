<?php
class tables_website_copy_form {
    function beforeInsert(Dataface_Record $record) {
        $source = df_get_record('websites', array('website_id'=>'='.$record->val('website_id')));
        $dest = new Dataface_Record('websites', array());
        foreach (array_keys($source->table()->fields()) as $fkey) {
            if ($fkey == 'website_id') {
                continue;
            }
            
            if ($fkey == 'translation_memory_id' and !$record->val('use_same_translation_memory')) {
                continue;
            }
            
            $val = $source->val($fkey);
            
            if ($fkey == 'website_name') {
                if ($record->val('website_name')) {
                    $val = $record->val('website_name');
                } else {
                    $val = $val.' Copy';
                }
            }
            
            if ($fkey == 'website_url') {
                if ($record->val('website_url')) {
                    $val = $record->val('website_url');
                }
            }
            
            if ($fkey == 'active') {
                $val = 0;
            }
            
            $dest->setValue($fkey, $val);
        }
        
        $res = $dest->save(null, true);
        if (PEAR::isError($res)) {
            return $res;
        }
        
        // Now that it has been inserted, we need to copy the config.
        // Add default text filters.
        df_q("delete from site_text_filters where website_id='".addslashes($dest->val('website_id'))."'");

		df_q("insert into site_text_filters (website_id, filter_id, filter_type, filter_order)
			select ".intval($dest->val('website_id')).", filter_id, filter_type, filter_order
			from site_text_filters where website_id='".addslashes($source->val('website_id'))."'");
			
			
		if ($record->val('copy_tm_strings') and $dest->val('translation_memory_id') != $source->val('translation_memory_id')) {
		    // We need to copy all of the strings from one translation memory to the other.
		    df_q("create temporary table temp_xf_tm_translation_memory_strings select ".intval($dest->val('translation_memory_id')).", string_id, status_id, current_translation_id, flagged, last_touched from xf_tm_translation_memory_strings");
		    df_q("insert into xf_tm_translation_memory_strings (translation_memory_id, string_id, status_id, current_translation_id, flagged, last_touched)
		        select * from temp_xf_tm_translation_memory_strings");
		    df_q("drop table temp_xf_tm_translation_memory_strings");
		    
		    df_q("create temporary table temp_xf_tm_translation_memory_translations select ".intval($dest->val('translation_memory_id')).", translation_id, status_id, current from xf_tm_translation_memory_translations");
		    df_q("insert into xf_tm_translation_memory_translations (translation_memory_id, translation_id, status_id, current)
		        select * from temp_xf_tm_translation_memory_translations");
		    df_q("drop table temp_xf_tm_translation_memory_translations");
		    
		    df_q("create temporary table temp_xf_tm_translations_status select ".intval($dest->val('translation_memory_id')).", translation_id, username, status_id, date_created, last_modified from xf_tm_translations_status");
		    df_q("insert into xf_tm_translations_status (translation_memory_id, translation_id, username, status_id, date_created, last_modified) 
		        select * from temp_xf_tm_translations_status");
		    df_q("drop table temp_xf_tm_translations_status");
		        
		}
		
		// Now let's redirect
		
		header('Location: '.$dest->getURL('-action=edit').'&--msg='.urlencode('Site successfully copied.  You may also need to copy and modify the delegate files in swete-admin/sites/'.$source->val('website_id')));
		exit;

    }
    
    function block__left_column() {
        echo "";
    }
    
    function block__search_form() {
        echo "";
    }
    
    function block__before_new_record_form() {
        echo '<script>jQuery(document).ready(function(){jQuery(".insert-record-label").text("Copy Website Profile");});</script>';
    }
    
}