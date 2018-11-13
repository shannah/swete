;<?php exit;
__sql__ = "select s.*, if(w.current_snapshot_id=s.snapshot_id,'YES','No') as active from snapshots s left join websites w on s.website_id=w.website_id"
[website_id]
  widget:type=select
  vocabulary=websites

[date_created]
  timestamp=insert
  widget:type=hidden
  title=1

[date_completed]
  widget:type=hidden

[pagelist]
  widget:description="Paste a list of pages to be included in this snapshot"
  struct=1

[pagestatus]
  widget:type=hidden
  visibility:browse=hidden
  visibility:list=hidden
  visibility:find=hidden
  struct=1
