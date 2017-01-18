<?php
/*
 * Xataface Translation Memory Module
 * Copyright (C) 2011  Steve Hannah <steve@weblite.ca>
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Library General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Library General Public License for more details.
 * 
 * You should have received a copy of the GNU Library General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA  02110-1301, USA.
 *
 */
class modules_tm_installer {
	
	
	
	
	public function update_1(){
		error_log("Starting tm update 1");
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_records` (
		  `record_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `mtime` int(11) DEFAULT NULL,
		  `translation_memory_id` int(11) NOT NULL DEFAULT '0',
		  `last_string_extraction_time` int(11) DEFAULT NULL,
		  `locked` tinyint(1) DEFAULT NULL,
		  PRIMARY KEY (`record_id`,`translation_memory_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_record_strings` (
		  `record_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `string_id` int(11) NOT NULL,
		  PRIMARY KEY (`record_id`,`string_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_strings` (
		  `string_id` int(11) NOT NULL AUTO_INCREMENT,
		  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `string_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `normalized_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`string_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_translations` (
		  `translation_id` int(11) NOT NULL AUTO_INCREMENT,
		  `string_id` int(11) NOT NULL,
		  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `translation_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `normalized_translation_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `created_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `translation_hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`translation_id`),
		  KEY `string_id` (`string_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_translations_comments` (
		  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
		  `translation_id` int(11) NOT NULL,
		  `translation_memory_id` int(11) NOT NULL,
		  `posted_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `comments` text COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`comment_id`),
		  KEY `translation_id` (`translation_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_translations_score` (
		  `translation_memory_id` int(11) NOT NULL,
		  `translation_id` int(11) NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `score` int(11) NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  PRIMARY KEY (`translation_memory_id`,`username`,`translation_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_translations_status` (
		  `translation_status_id` int(11) NOT NULL AUTO_INCREMENT,
		  `translation_memory_id` int(11) NOT NULL,
		  `translation_id` int(11) NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `status_id` int(11) NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  PRIMARY KEY (`translation_status_id`),
		  KEY `translation_memory_id` (`translation_memory_id`),
		  KEY `translation_id` (`translation_id`),
		  KEY `username` (`username`),
		  KEY `status_id` (`status_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_translation_memories` (
		  `translation_memory_id` int(11) NOT NULL AUTO_INCREMENT,
		  `translation_memory_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		  `source_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `destination_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `mtime` int(11) DEFAULT NULL,
		  PRIMARY KEY (`translation_memory_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_translation_memories_managers` (
		  `translation_memory_id` int(11) NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`translation_memory_id`,`username`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_translation_memory_translations` (
		  `translation_memory_id` int(11) NOT NULL,
		  `translation_id` int(11) NOT NULL,
		  PRIMARY KEY (`translation_memory_id`,`translation_id`)
		)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_translation_statuses` (
		  `status_id` int(11) NOT NULL AUTO_INCREMENT,
		  `status_name` int(11) NOT NULL,
		  PRIMARY KEY (`status_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_workflows` (
		  `workflow_id` int(11) NOT NULL AUTO_INCREMENT,
		  `temp_translation_memory_id` int(11) NOT NULL,
		  `translation_memory_id` int(11) NOT NULL,
		  `created_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `current_step_id` int(11) NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  PRIMARY KEY (`workflow_id`)
		)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_workflow_records` (
		  `workflow_id` int(11) NOT NULL,
		  `record_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`workflow_id`,`record_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_workflow_steps` (
		  `workflow_step_id` int(11) NOT NULL AUTO_INCREMENT,
		  `workflow_id` int(11) NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `step_number` int(5) NOT NULL,
		  PRIMARY KEY (`workflow_step_id`)
		)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_workflow_step_changes` (
		  `workflow_step_id` int(11) NOT NULL,
		  `translations_log_id` int(11) NOT NULL,
		  PRIMARY KEY (`workflow_step_id`,`translations_log_id`)
		)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_workflow_step_panels` (
		  `panel_id` int(11) NOT NULL AUTO_INCREMENT,
		  `workflow_step_id` int(11) NOT NULL,
		  `panel_type_id` int(11) NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  PRIMARY KEY (`panel_id`)
		)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_workflow_step_panel_actions` (
		  `action_id` int(11) NOT NULL AUTO_INCREMENT,
		  `workflow_step_id` int(11) NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `action_type_id` int(11) NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  PRIMARY KEY (`action_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_workflow_step_panel_members` (
		  `panel_id` int(11) NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`panel_id`,`username`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `xf_tm_workflow_strings` (
		  `workflow_id` int(11) NOT NULL,
		  `string_id` int(11) NOT NULL,
		  PRIMARY KEY (`workflow_id`,`string_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		self::query($sql);
		self::clearViews();
		error_log('finished tm update 1');
	}
	
	public function update_2(){
		$sql[] = "ALTER TABLE  `xf_tm_strings` ADD UNIQUE (
			`language` ,
			`hash`
			)";
		self::query($sql);
	}
	
	
	public function update_3(){
	
		$sql[] = "ALTER TABLE `xf_tm_strings` ADD  `encoded_value` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL AFTER  `normalized_value`";
		$sql[] = "ALTER TABLE `xf_tm_strings` ADD  `encoded_hash` VARCHAR( 32 ) NULL";
		$sql[] = "ALTER TABLE `xf_tm_translations` ADD  `encoded_translation_value` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL AFTER  `normalized_translation_value`";
		$sql[] = "ALTER TABLE `xf_tm_translations` ADD  `encoded_translation_hash` VARCHAR( 32 ) NULL AFTER  `translation_hash`";
		
		
		self::query($sql);
	}
	
	
	public function update_4(){
	
		$sql[] = "CREATE TABLE IF NOT EXISTS  `xf_tm_translation_memories_cache` (
			`translation_memory_id` INT( 11 ) UNSIGNED NOT NULL ,
			`string_id` INT( 11 ) UNSIGNED NOT NULL ,
			`approved_translation_id` INT( 11 ) UNSIGNED NULL ,
			`most_recent_translation_id` INT( 11 ) UNSIGNED NULL ,
			PRIMARY KEY (  `translation_memory_id` ,  `string_id` )
			) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
			
			
		$sql[] = "ALTER TABLE  `xf_tm_translation_memories` ENGINE = INNODB";
		$str = "ALTER TABLE  `xf_tm_records` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_record_strings` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_strings` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_translations` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_translations_comments` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_translations_score` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_translations_status` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_translation_memories_cache` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_translation_memories_managers` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_translation_memory_translations` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_translation_statuses` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_workflows` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_workflow_records` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_workflow_steps` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_workflow_step_changes` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_workflow_step_panels` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_workflow_step_panel_actions` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_workflow_step_panel_members` ENGINE = INNODB;
			ALTER TABLE  `xf_tm_workflow_strings` ENGINE = INNODB
			";
			
		$sql = array_merge($sql, explode(';', $str));
		$sql[] = "ALTER TABLE  `xf_tm_strings` CHANGE  `string_id`  `string_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT";
		$sql[] = "ALTER TABLE  `xf_tm_translations` CHANGE  `translation_id`  `translation_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
				CHANGE  `string_id`  `string_id` INT( 11 ) UNSIGNED NOT NULL";
		$sql[] = "ALTER TABLE  `xf_tm_translations_comments` CHANGE  `comment_id`  `comment_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
			CHANGE  `translation_id`  `translation_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `translation_memory_id`  `translation_memory_id` INT( 11 ) UNSIGNED NOT NULL";
			
		$sql[] = "ALTER TABLE  `xf_tm_translations_score` CHANGE  `translation_memory_id`  `translation_memory_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `translation_id`  `translation_id` INT( 11 ) UNSIGNED NOT NULL";
			
		$sql[] = "ALTER TABLE  `xf_tm_translations_status` CHANGE  `translation_status_id`  `translation_status_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
			CHANGE  `translation_memory_id`  `translation_memory_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `translation_id`  `translation_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `status_id`  `status_id` INT( 11 ) UNSIGNED NOT NULL";
			
		$sql[] = "ALTER TABLE  `xf_tm_translation_memories` CHANGE  `translation_memory_id`  `translation_memory_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT";
		$sql[] = "ALTER TABLE  `xf_tm_translation_memories_managers` CHANGE  `translation_memory_id`  `translation_memory_id` INT( 11 ) UNSIGNED NOT NULL";
		$sql[] = "ALTER TABLE  `xf_tm_translation_memory_translations` CHANGE  `translation_memory_id`  `translation_memory_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `translation_id`  `translation_id` INT( 11 ) UNSIGNED NOT NULL";
		$sql[] = "ALTER TABLE  `xf_tm_translation_statuses` CHANGE  `status_id`  `status_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT";
		$sql[] = "ALTER TABLE  `xf_tm_workflows` CHANGE  `workflow_id`  `workflow_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
			CHANGE  `temp_translation_memory_id`  `temp_translation_memory_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `translation_memory_id`  `translation_memory_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `current_step_id`  `current_step_id` INT( 11 ) UNSIGNED NOT NULL";
			
		$sql[] = "ALTER TABLE  `xf_tm_workflow_records` CHANGE  `workflow_id`  `workflow_id` INT( 11 ) UNSIGNED NOT NULL";
		$sql[] = "ALTER TABLE  `xf_tm_workflow_steps` CHANGE  `workflow_step_id`  `workflow_step_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
			CHANGE  `workflow_id`  `workflow_id` INT( 11 ) UNSIGNED NOT NULL";
			
		$sql[] = "ALTER TABLE  `xf_tm_workflow_step_changes` CHANGE  `workflow_step_id`  `workflow_step_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `translations_log_id`  `translations_log_id` INT( 11 ) UNSIGNED NOT NULL";
			
		$sql[] = "ALTER TABLE  `xf_tm_workflow_step_panels` CHANGE  `panel_id`  `panel_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
			CHANGE  `workflow_step_id`  `workflow_step_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `panel_type_id`  `panel_type_id` INT( 11 ) UNSIGNED NOT NULL";
			
		$sql[] = "ALTER TABLE  `xf_tm_workflow_step_panel_actions` CHANGE  `action_id`  `action_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
			CHANGE  `workflow_step_id`  `workflow_step_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `action_type_id`  `action_type_id` INT( 11 ) UNSIGNED NOT NULL";
			
		$sql[] = "ALTER TABLE  `xf_tm_workflow_step_panel_members` CHANGE  `panel_id`  `panel_id` INT( 11 ) UNSIGNED NOT NULL";
		$sql[] = "ALTER TABLE  `xf_tm_workflow_strings` CHANGE  `workflow_id`  `workflow_id` INT( 11 ) UNSIGNED NOT NULL ,
			CHANGE  `string_id`  `string_id` INT( 11 ) UNSIGNED NOT NULL";
			
		$sql[] = "ALTER TABLE  `xf_tm_records` CHANGE  `translation_memory_id`  `translation_memory_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0'";
		$sql[] = "ALTER TABLE  `xf_tm_record_strings` CHANGE  `string_id`  `string_id` INT( 11 ) UNSIGNED NOT NULL";
		$sql[] = "ALTER TABLE  `xf_tm_records` ADD FOREIGN KEY (  `translation_memory_id` ) REFERENCES  `xf_tm_translation_memories` (
			`translation_memory_id`
			) ON DELETE CASCADE ;";
			
		$sql[] = "ALTER TABLE  `xf_tm_record_strings` ADD FOREIGN KEY (  `string_id` ) REFERENCES  `xf_tm_strings` (
				`string_id`
				) ON DELETE CASCADE ;";
				
		$sql[] = "ALTER TABLE  `xf_tm_translations` ADD FOREIGN KEY (  `string_id` ) REFERENCES  `xf_tm_strings` (
			`string_id`
			) ON DELETE CASCADE ;";
			
		$sql[] = "ALTER TABLE  `xf_tm_translations_comments` ADD FOREIGN KEY (  `translation_id` ) REFERENCES  `xf_tm_translations` (
			`translation_id`
			) ON DELETE CASCADE ;";
		$sql[] = "ALTER TABLE  `xf_tm_translations_score` ADD FOREIGN KEY (  `translation_memory_id` ) REFERENCES  `xf_tm_translation_memories` (
			`translation_memory_id`
			) ON DELETE CASCADE ;";
		$sql[] = "ALTER TABLE  `xf_tm_translations_score` ADD FOREIGN KEY (  `translation_id` ) REFERENCES  `xf_tm_translations` (
			`translation_id`
			) ON DELETE CASCADE ;";
			
		$sql[] = "ALTER TABLE  `xf_tm_translations_status` ADD FOREIGN KEY (  `translation_memory_id` ) REFERENCES  `xf_tm_translation_memories` (
			`translation_memory_id`
			) ON DELETE CASCADE ;";
			
		$sql[] = "ALTER TABLE  `xf_tm_translations_status` ADD FOREIGN KEY (  `translation_id` ) REFERENCES  `xf_tm_translations` (
			`translation_id`
			) ON DELETE RESTRICT ;";
		
		
		$sql[] = "ALTER TABLE  `xf_tm_translation_memories_cache` ADD FOREIGN KEY (  `translation_memory_id` ) REFERENCES  `xf_tm_translation_memories` (
			`translation_memory_id`
			) ON DELETE CASCADE ;
			";
			
		$sql[] = "ALTER TABLE  `xf_tm_translation_memories_cache` ADD FOREIGN KEY (  `string_id` ) REFERENCES  `xf_tm_translations` (
			`string_id`
			) ON DELETE RESTRICT ;";
			
		$sql[] = "ALTER TABLE  `xf_tm_translation_memories_cache` ADD FOREIGN KEY (  `approved_translation_id` ) REFERENCES  `xf_tm_translations` (
			`translation_id`
			) ON DELETE RESTRICT ;";
			
		$sql[] = "ALTER TABLE  `xf_tm_translation_memories_cache` ADD FOREIGN KEY (  `translation_memory_id` ) REFERENCES  `xf_tm_translation_memories` (
			`translation_memory_id`
			) ON DELETE CASCADE ;";
			
		$sql[] = "ALTER TABLE  `xf_tm_translation_memories_managers` ADD FOREIGN KEY (  `translation_memory_id` ) REFERENCES  `xf_tm_translation_memories` (
			`translation_memory_id`
			) ON DELETE CASCADE ;";
			
		$sql[] = "ALTER TABLE  `xf_tm_translation_memory_translations` ADD FOREIGN KEY (  `translation_memory_id` ) REFERENCES  `xf_tm_translation_memories` (
			`translation_memory_id`
			) ON DELETE CASCADE ;";
			
		$sql[] = "ALTER TABLE  `xf_tm_translation_memory_translations` ADD FOREIGN KEY (  `translation_id` ) REFERENCES  `xf_tm_translations` (
			`translation_id`
			) ON DELETE RESTRICT ;";
			
			
		$sql[] = "ALTER  TABLE  `xf_tm_translation_memories` ADD  `auto_approve` TINYINT( 1 ) NULL";
		// NOTE:  TODO  Still neet to add foreign keys for workflows
		
		
		
		self::query($sql);
	
	}
	
	public function update_5(){
		$sql[] = "ALTER TABLE  `xf_tm_translation_memory_translations` ADD  `status_id` INT( 11 ) UNSIGNED NULL";
		$sql[] = "ALTER TABLE  `xf_tm_translation_memory_translations` ADD  `current` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0'";
		self::query($sql);
	}
	
	public function update_6(){
		$sql[] = "CREATE TABLE  `xf_tm_translation_memory_strings` (
			`translation_memory_id` INT( 11 ) UNSIGNED NOT NULL ,
			`string_id` INT( 11 ) UNSIGNED NOT NULL ,
			`status_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT 0 ,
			`current_translation_id` INT( 11 ) UNSIGNED ,
			`flagged` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0',
			PRIMARY KEY (  `translation_memory_id` ,  `string_id` ,  `status_id` ) ,
			INDEX (  `current_translation_id` )
			) ENGINE = INNODB;";
		
		$sql[] = "ALTER TABLE `xf_tm_translation_memory_strings` ADD FOREIGN KEY (`translation_memory_id`) REFERENCES  `xf_tm_translation_memories`(`translation_memory_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
		$sql[] = "ALTER TABLE `xf_tm_translation_memory_strings` ADD FOREIGN KEY (`string_id`) REFERENCES `xf_tm_strings`(`string_id`) ON DELETE RESTRICT ON UPDATE CASCADE;";
		
		$sql[] = "ALTER TABLE `xf_tm_translation_memory_strings` ADD FOREIGN KEY (`current_translation_id`) REFERENCES `xf_tm_translations`(`translation_id`) ON DELETE RESTRICT ON UPDATE CASCADE;";
		$sql[] = "CREATE TABLE  `xf_tm_translation_memory_flags` (
			`translation_memory_flag_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`translation_memory_id` INT( 11 ) UNSIGNED NOT NULL ,
			`string_id` INT( 11 ) UNSIGNED NOT NULL ,
			`username` VARCHAR( 255 ) NOT NULL ,
			`date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			`comment` TEXT NULL ,
			INDEX (  `translation_memory_id` ,  `string_id` )
			) ENGINE = INNODB;";
		$sql[] = "ALTER TABLE  `xf_tm_translation_memory_flags` ADD INDEX (  `string_id` )";
		$sql[] = "ALTER TABLE  `xf_tm_translation_memory_flags` ADD FOREIGN KEY (  `translation_memory_id` ) REFERENCES  `xf_tm_translation_memories` (
			`translation_memory_id`
			) ON DELETE CASCADE ON UPDATE CASCADE ;";


		$sql[] = "ALTER TABLE  `xf_tm_translation_memory_flags` ADD FOREIGN KEY (  `string_id` ) REFERENCES  `xf_tm_strings` (
			`string_id`
			) ON DELETE RESTRICT ON UPDATE CASCADE ;";
		
		self::query($sql);
	
	}
	
	
	public function update_7(){
		$sql[] = "ALTER TABLE  `xf_tm_translation_memory_strings` ADD  `last_touched` DATETIME NULL";
		self::query($sql);
	}
	
	public function update_8(){
		$sql[] = "ALTER TABLE  `xf_tm_strings` ADD  `num_words` INT UNSIGNED NULL";
		/**
		 * THIS UPDATE BELONGS IN SWETE NOT THE TM MODULE
		 *
		$sql[] = "ALTER TABLE  `translation_miss_log` ADD  `string_id` INT( 11 ) UNSIGNED NULL ,
			ADD INDEX (  `string_id` )";
		$sql[] = "ALTER TABLE  `translation_miss_log` ADD FOREIGN KEY (  `string_id` ) REFERENCES  `xf_tm_strings` (
			`string_id`
			) ON DELETE RESTRICT ON UPDATE CASCADE ;";
		*/
		self::query($sql);
	
	}
	
        public function update_11(){
	    $sql[] = "ALTER TABLE `xf_tm_translation_memories` ADD COLUMN `translation_memory_uuid` CHAR(36) NULL  AFTER `translation_memory_id`, ADD UNIQUE INDEX `translation_memory_uuid_UNIQUE` (`translation_memory_uuid` ASC)";
	    $sql[] = "update xf_tm_translation_memories set translation_memory_uuid=UUID()";
	    df_q($sql);
	    df_clear_views();
	    
	}
        
	public static function query($sql){
		if ( is_array($sql) ){
			$res = null;
			foreach ($sql as $q){
				$res = self::query($q);
			}
			return $res;
		} else {
			$res = mysql_query($sql, df_db());
			if ( !$res ){
				
				throw new Exception(mysql_error(df_db()));
			}
			return $res;
		}
	
	}
	
	public static function clearViews(){
            df_clear_views();
		
	}

}