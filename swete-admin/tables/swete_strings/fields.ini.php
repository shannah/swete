;<?php exit;
[request_url]
    order=-50

[translation_miss_log_id]
	visibility:list=hidden
	visibility:find=hidden
	visibility:csv=hidden

[webpage_refresh_log_id]
	visibility:list=hidden
	visibility:find=hidden
	visibility:csv=hidden

[http_request_log_id]
	visibility:list=hidden
	visibility:find=hidden
	visibility:csv=hidden

[normalized_string]
	visibility:list=hidden
	visibility:find=hidden
	title=1
	order=-100


[string]
	order=-100
	visibility:csv=hidden
    collate=utf8_general_ci

[encoded_string]
	visibility:list=hidden
	visibility:find=hidden
	visibility:csv=hidden

[string_hash]
	visibility:list=hidden
	visibility:find=hidden
	order=-91
	visibility:csv=hidden

[webpage_id]
	visibility:list=hidden
	visibility:find=hidden
	order=-93

[source_language]
	visibility:list=hidden
	visibility:find=hidden
	order=-95

[destination_language]
	visibility:list=hidden
	visibility:find=hidden
	order=-94

[string_id]
	visibility:list=hidden
	visibility:find=hidden
	order=-96
        Key=PRI


[translation_memory_id]
	visibility:list=hidden
        visibility:csv=hidden
	order=-97
        Key=PRI

[translation_memory_uuid]
        visibility:list=hidden
        order=-96


[normalized_translation_value]
	order=-99
	widget:label="Current Translation"
    collate=utf8_general_ci

[num_words]
	widget:label="Num Words"
	order=-98

[website_id]
	filter=1
	vocabulary=websites
	order=-10
