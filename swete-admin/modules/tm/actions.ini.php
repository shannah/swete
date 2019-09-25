;<?php exit;
[tm_translate_records]
	label="Translate"
	description="Translate records into other languages"
	condition = "($tableobj =& Dataface_Table::loadTable($table)) and count($tableobj->getTranslations()) > 0"
	category = "result_list_actions"
	url="{$site_href}?-action=translate"
	class="selected-action"
    
	
[cancel_translation]
	label="Cancel"
	description="Return to list"
	category="translate_record_form_actions"
	url="{$this->url('-action=list')}"
	
[save_translation]
	label="Save"
	description="Return to list"
	category="translate_record_form_actions"
	url="#"
	class="save-translations"
	
[tm_export_as_xliff]
	label="Export XLIFF"
	category=record_export_actions
	url="{$this->url('-action=tm_export_as_xliff')}"
	 condition="$query['-table'] == 'xf_tm_translation_memories' and $record"
	 permission="tm:export xliff"

[tm_import_from_xliff]
	template=tm_import_from_xliff.html
	label="Import from XLIFF"
	category=record_actions
	url="{$this->url('-action=tm_import_from_xliff')}"
 	condition="$query['-table'] == 'xf_tm_translation_memories' and $record"
 	permission="tm:import xliff"
 		
[tm_update_word_index]
    category=management_actions
    ;permission=tm:update word index
    url="{$site_href}?-action=tm_update_word_index"
    label="Update Translation Index"
    description="Updates the word index used to look up similar strings for the translation memory."