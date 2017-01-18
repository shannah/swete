<?php
class tables_xf_tm_translation_memories {
	function afterCopy(Dataface_Record $original, Dataface_Record $copy){
		require_once 'modules/tm/lib/XFTranslationMemory.php';
		$tmOriginal = new XFTranslationMemory($original);
		$tmCopy = new XFTranslationMemory($copy);
		$tmCopy->import($tmOriginal);
	}
	
	function afterInsert(Dataface_Record $tm){
	    df_q(sprintf(
	        "update xf_tm_translation_memories set translation_memory_uuid=UUID() where translation_memory_id=%d",
	        $tm->val('translation_memory_id')
	    ));
	    $res = df_q(sprintf(
	        "select translation_memory_uuid from xf_tm_translation_memories where translation_memory_id=%d",
	        $tm->val('translation_memory_id')
	    ));
	    list($uuid) = mysql_fetch_row($res);
	    @mysql_free_result($res);
	    $tm->setValue('translation_memory_uuid', $uuid);
	    
	}
}