;<?php exit;

[fieldgroup:refresh]
	label="Refresh History"
	
	

[__global__]
	visibility:browse=hidden
	visibility:list=hidden
	
[webpage_id]
	visibility:browse=visible
	visibility:list=visible
	visibility:find=visible

[website_id]
	widget:type=static
	filter=1
	vocabulary=websites
	
[translation_memory_id]	
	widget:type=lookup
	widget:table = xf_tm_translation_memories

[settings_id]
	widget:type=hidden
	
[webpage_url]
	widget:type=static
	visibility:browse=visible
	visibility:list=visible
	display=inline
	
[webpage_content]
	visibility:list=hidden
	visibility:browse=hidden
	visibility:csv=hidden
	visibility:find=hidden
	widget:type=ckeditor
	widget:ckeditor:fullPage="true"
	passthru=1

	
[active]

	visibility:browse=visible
	visibility:list=visible
	
[date_created]
	widget:type=hidden
	timestamp=insert
	
	
[last_modified]
	widget:type=hidden
	timestamp=update
	
[posted_by]
	widget:type=hidden
	
[parent_id]
	widget:type=hidden
	
[locked]
	widget:type=select
	visibility:browse=visible
	visibility:list=visible
	vocabulary=locked
	
[last_refresh]
	widget:type=hidden
	visibility:browse=visible
	group=refresh
	visibility:list=visible
	
[last_refresh_response_code]
	widget:type=hidden
	visibility:browse=visible
	group=refresh
	
[is_loaded]
	widget:type=hidden
	
	
[last_checked]
	widget:type=hidden
	
	
[last_checked_response_code]
	widget:type=hidden
	
[last_checked_by]
	widget:type=hidden
	
[last_checked_content_type]
	widget:type=hidden
	
[webpage_status]
	widget:type=hidden
	visibility:browse=visible
	visibility:list=visible
	vocabulary=webpage_statuses
	filter=1
	
[last_translation_memory_misses]
	widget:label="# Untranslated Sections"
	visibility:browse=visible
	visibility:list=visible
	
[last_translation_memory_hits]
	widget:label="# Translated Sections"
	visibility:browse=visible
	visibility:list=visible
	
	
[effective_active]
	widget:type=hidden
	visibility:browse=visible
	
[effective_locked]
	widget:type=hidden
	visibility:browse=visible
	
[effective_translation_memory_id]
	widget:type=hidden
	