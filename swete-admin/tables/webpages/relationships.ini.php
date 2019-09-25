;<?php exit;
[refresh_log]
	__sql__ = "select * from webpage_refresh_log where webpage_id='$webpage_id' order by date_checked desc"
	actions:addnew=0
	actions:addexisting=0