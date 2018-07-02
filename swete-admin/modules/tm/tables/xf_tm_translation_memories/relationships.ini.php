;<?php exit;
[translations]
	__sql__ = "select * from xf_tm_translation_memory_strings tms
		inner join xf_tm_translations tt on tms.current_translation_id=tt.translation_id
		inner join xf_tm_strings ts on tt.string_id=ts.string_id
		 where translation_memory_id='$translation_memory_id'"