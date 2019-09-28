;<?php exit;
; this is an INI file
[file]
    Type=container
	; savepath is now specified inside init() method of string_imports.php
	; because it is pointing to the parent directory of swete-admin, and
	; swete-admin might be a symlink, so we need it to be the parent directory
	; of the symlink.
    ;savepath=livecache/string_imports
    secure=1

[file_format]
    widget:type=select
    vocabulary=import_file_formats
    widget:description="The file format that the import file is in.  The format of the file must follow the import file format guidelines.  <a target="_blank" href="https://raw.github.com/shannah/swete/master/docs/samples/translation_miss_log_results_2013_07_05_00_11_28.csv">View a sample CSV file</a>."
    
[target_translation_memory_uuid]
    widget:label="Target Translation Memory"
    vocabulary=translation_memory_uuids
    widget:description="The translation memory into which the translations should be imported.  Leave this blank to import into the translation memory settings in the import file."
    widget:type=select

[status]
    widget:type=htmlarea

[log]
    widget:type=hidden
