;<?php exit;
;;__sql__ = "select wp.*,
;;	ss.language as `language`,
;;	ss.label as `proxy_label`
;;	from
;;		webpage_properties wp
;;	left join settings_sites ss on wp.settings_site_id=ss.settings_site_id
;;	"