;<?php exit;?>
;
;debug=1
;debug_sql=1
__include__="conf.db.ini.php"
multilingual_content=1
enable_static=0
enable_jobs=0
title="SWeTE Server"


[languages]
	en = English
	fr = French
	
[_tables]
	dashboard=Dashboard
	websites=Sites
	webpages=Pages
	jobs=Jobs
	swete_strings=Strings
	xf_tm_translation_memories=Translation Memories
	
	
[_modules]
	modules_ckeditor=modules/ckeditor/ckeditor.php
	modules_tm=modules/tm/tm.php
	;modules_testrunner=modules/testrunner/testrunner.php
    modules_uitk=modules/uitk/uitk.php
	;modules_scaler=modules/scaler/scaler.php
    modules_excel=modules/excel/excel.php
	
[_allowed_tables]
	translation_memories=xf_tm_translation_memories
	
[Dataface_JavascriptTool]
	debug=0
	
[_auth]
     users_table=users
     username_column=username
     password_column=password
     allow_register=1
     
[password]
    encryption=md5
    
[_translate_settings]
	hide_button_bar=1
	hide_record_headings=1
	hide_status_labels=1

[export_csv]
    format=excel
    
[_prefs]
    disable_master_detail=1