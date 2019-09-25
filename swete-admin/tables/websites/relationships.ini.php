;<?php exit;
[jobs]
jobs.website_id = "$website_id"
actions:addnew = false
actions:addexisting = false
action:condition="$this->_conf['enable_jobs']"

[pages]
webpages.website_id = "$website_id"
actions:addnew = false
actions:addexisting = false
action:condition="$this->_conf['enable_static']"

[text_filters]
	action:label="Text Filters"
	site_text_filters.website_id="$website_id"
	site_text_filters.filter_id="text_filters.filter_id"

[snapshots]
	snapshots.website_id=$website_id
	
[path_aliases]
	path_aliases.website_id=$website_id
	action:label="Path Aliases"
