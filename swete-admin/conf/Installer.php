<?php
/**
 * SWeTE Server: Simple Website Translation Engine
 * Copyright (C) 2012  Web Lite Translation Corp.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class conf_Installer {
	function update_1(){
	
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `background_processes` (
		  `process_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `process_data` longblob,
		  `complete` tinyint(1) NOT NULL,
		  `running` tinyint(1) NOT NULL,
		  `time_started` datetime DEFAULT NULL,
		  `time_finished` datetime DEFAULT NULL,
		  `error` tinyint(1) NOT NULL,
		  `error_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `process_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  PRIMARY KEY (`process_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1266 ;
		";
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `http_request_log` (
		  `http_request_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `proxy_request_url` text COLLATE utf8_unicode_ci NOT NULL,
		  `proxy_request_method` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `proxy_request_headers` text COLLATE utf8_unicode_ci,
		  `proxy_request_post_vars` text COLLATE utf8_unicode_ci,
		  `website_id` int(11) unsigned DEFAULT NULL,
		  `webpage_id` int(11) unsigned DEFAULT NULL,
		  `webpage_not_used_reason` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `webpage_version_id` int(11) DEFAULT NULL,
		  `request_method` enum('GET','POST') COLLATE utf8_unicode_ci NOT NULL,
		  `request_url` text COLLATE utf8_unicode_ci,
		  `request_headers` text COLLATE utf8_unicode_ci,
		  `request_post_vars` text COLLATE utf8_unicode_ci,
		  `response_headers` text COLLATE utf8_unicode_ci,
		  `response_body` longtext COLLATE utf8_unicode_ci,
		  `request_date` datetime DEFAULT NULL,
		  `response_content_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `response_status_code` int(11) DEFAULT NULL,
		  `live_translation_enabled` tinyint(1) unsigned NOT NULL,
		  `live_translation_min_status` int(5) unsigned DEFAULT NULL,
		  `translation_memory_id` int(11) unsigned DEFAULT NULL,
		  `output_content` longtext COLLATE utf8_unicode_ci,
		  `output_response_headers` text COLLATE utf8_unicode_ci,
		  `live_translation_hits` int(11) unsigned DEFAULT NULL,
		  `live_translation_misses` int(11) unsigned DEFAULT NULL,
		  PRIMARY KEY (`http_request_log_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=911 ;
		";
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `jobs` (
		  `job_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `website_id` int(11) unsigned NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `job_status` int(5) unsigned DEFAULT NULL,
		  `translation_memory_id` int(11) unsigned DEFAULT NULL,
		  `source_language` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `destination_language` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `assigned_to` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `compiled` tinyint(1) unsigned NOT NULL,
		  PRIMARY KEY (`job_id`),
		  KEY `website_id` (`website_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;
		";
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `job_assignments` (
		  `job_assignment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `job_id` int(11) unsigned NOT NULL,
		  `assigned_to` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `assigned_by` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `date_assigned` datetime DEFAULT NULL,
		  PRIMARY KEY (`job_assignment_id`),
		  KEY `job_id` (`job_id`),
		  KEY `assigned_to` (`assigned_to`),
		  KEY `assigned_by` (`assigned_by`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;
		
		";
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `job_content` (
		  `job_content_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `job_id` int(11) unsigned NOT NULL,
		  `url` text COLLATE utf8_unicode_ci,
		  `url_hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `content_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `content` longblob,
		  PRIMARY KEY (`job_content_id`),
		  KEY `job_id` (`job_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=68 ;
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `job_inputs_translation_misses` (
		  `job_id` int(11) unsigned NOT NULL,
		  `translation_miss_log_id` int(11) unsigned NOT NULL,
		  PRIMARY KEY (`job_id`,`translation_miss_log_id`),
		  KEY `translation_miss_log_id` (`translation_miss_log_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `job_inputs_webpages` (
		  `job_id` int(11) unsigned NOT NULL,
		  `webpage_id` int(11) unsigned NOT NULL,
		  PRIMARY KEY (`job_id`,`webpage_id`),
		  KEY `webpage_id` (`webpage_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `job_inputs_webpages_strings` (
		  `job_inputs_webpages_string_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `job_id` int(11) unsigned DEFAULT NULL,
		  `webpage_id` int(11) unsigned DEFAULT NULL,
		  `string` text COLLATE utf8_unicode_ci,
		  `string_hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
		  PRIMARY KEY (`job_inputs_webpages_string_id`),
		  KEY `job_id` (`job_id`,`webpage_id`),
		  KEY `webpage_id` (`webpage_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `job_notes` (
		  `job_note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `job_id` int(11) unsigned NOT NULL,
		  `note_content` text COLLATE utf8_unicode_ci NOT NULL,
		  `date_posted` datetime DEFAULT NULL,
		  `deleted` tinyint(1) unsigned DEFAULT NULL,
		  `posted_by` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`job_note_id`),
		  KEY `job_id` (`job_id`),
		  KEY `posted_by` (`posted_by`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `job_notes_read` (
		  `job_note_id` int(11) unsigned NOT NULL,
		  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		  `read` tinyint(1) unsigned DEFAULT NULL,
		  `date_read` datetime DEFAULT NULL,
		  PRIMARY KEY (`job_note_id`,`username`),
		  KEY `username` (`username`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `job_roles` (
		  `job_id` int(11) unsigned NOT NULL,
		  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		  `access_level` int(5) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`job_id`,`username`),
		  KEY `username` (`username`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `job_status_log` (
		  `job_status_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `job_id` int(11) unsigned NOT NULL,
		  `status_id` int(11) unsigned NOT NULL,
		  `posted_by` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `date_posted` datetime DEFAULT NULL,
		  PRIMARY KEY (`job_status_log_id`),
		  KEY `job_id` (`job_id`),
		  KEY `posted_by` (`posted_by`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=41 ;
		";
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `job_translatable` (
		  `job_translatable_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `job_id` int(11) unsigned NOT NULL,
		  `content_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `webpage_id` int(11) unsigned DEFAULT NULL,
		  `full_contents` longtext COLLATE utf8_unicode_ci,
		  `translatable_contents` longtext COLLATE utf8_unicode_ci,
		  `previous_translations` longtext COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`job_translatable_id`),
		  KEY `job_id` (`job_id`),
		  KEY `webpage_id` (`webpage_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `languages` (
		  `language_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `language_label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`language_code`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "
		
		INSERT INTO `languages` (`language_code`, `language_label`) VALUES
		('en', 'English'),
		('fr', 'French');
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `path_aliases` (
		  `path_alias_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `website_id` int(11) unsigned NOT NULL,
		  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`path_alias_id`),
		  KEY `website_id` (`website_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `translation_miss_log` (
		  `translation_miss_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `webpage_refresh_log_id` int(11) unsigned DEFAULT NULL,
		  `http_request_log_id` int(11) unsigned DEFAULT NULL,
		  `string` text COLLATE utf8_unicode_ci,
		  `normalized_string` text COLLATE utf8_unicode_ci,
		  `encoded_string` text COLLATE utf8_unicode_ci,
		  `string_hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `date_inserted` datetime DEFAULT NULL,
		  `translation_memory_id` int(11) unsigned DEFAULT NULL,
		  `webpage_id` int(11) unsigned DEFAULT NULL,
		  `website_id` int(11) unsigned DEFAULT NULL,
		  `source_language` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `destination_language` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
		  PRIMARY KEY (`translation_miss_log_id`),
		  UNIQUE KEY `translation_memory_id` (`translation_memory_id`,`string_hash`),
		  KEY `webpage_id` (`webpage_id`),
		  KEY `website_id` (`website_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=326 ;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `translation_miss_log_en` (
		  `translation_miss_log_id` int(11) unsigned NOT NULL,
		  `string` text COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`translation_miss_log_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `translation_miss_log_fr` (
		  `translation_miss_log_id` int(11) unsigned NOT NULL,
		  `string` text COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`translation_miss_log_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `users` (
		  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `role_id` int(11) unsigned DEFAULT NULL,
		  `password` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  PRIMARY KEY (`user_id`),
		  UNIQUE KEY `job_id_2` (`username`),
		  KEY `username` (`username`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=130 ;
		";
		
		
		$sql[] = "
		
		INSERT INTO `users` (`user_id`, `username`, `role_id`, `password`, `email`) VALUES
		(128, 'test_user', 3, 'foo', 'test_user@example.com'),
		(129, 'test_user2', 1, 'foo', 'test_user2@example.com');
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `webpages` (
		  `webpage_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `website_id` int(11) unsigned NOT NULL,
		  `webpage_url` text COLLATE utf8_unicode_ci NOT NULL,
		  `webpage_content` longtext COLLATE utf8_unicode_ci,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `posted_by` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `parent_id` int(11) unsigned DEFAULT NULL,
		  `locked` tinyint(1) DEFAULT NULL,
		  `last_refresh` datetime DEFAULT NULL,
		  `last_refresh_response_code` int(5) DEFAULT NULL,
		  `last_translation_memory_applied` datetime DEFAULT NULL,
		  `last_translation_memory_misses` int(5) DEFAULT NULL,
		  `last_translation_memory_hits` int(5) DEFAULT NULL,
		  `is_loaded` int(11) DEFAULT '0',
		  `last_checked` datetime DEFAULT NULL,
		  `last_checked_response_code` int(5) DEFAULT NULL,
		  `last_checked_content_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `last_checked_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `active` tinyint(1) DEFAULT '-1',
		  `webpage_status` int(5) unsigned DEFAULT NULL,
		  `translation_memory_id` int(11) unsigned DEFAULT NULL,
		  `enable_live_translation` tinyint(1) DEFAULT '-1',
		  `auto_approve` tinyint(1) DEFAULT '-1',
		  PRIMARY KEY (`webpage_id`),
		  KEY `parent_id` (`parent_id`),
		  KEY `website_id` (`website_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=555 ;
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `webpages_en` (
		  `webpage_id` int(11) unsigned NOT NULL DEFAULT '0',
		  `webpage_content` longtext COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`webpage_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `webpages_fr` (
		  `webpage_id` int(11) unsigned NOT NULL DEFAULT '0',
		  `webpage_content` longtext COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`webpage_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `webpage_check_log` (
		  `check_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `webpage_id` int(11) unsigned NOT NULL,
		  `date_checked` datetime DEFAULT NULL,
		  `response_code` int(5) DEFAULT NULL,
		  `content_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `checked_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  PRIMARY KEY (`check_log_id`),
		  KEY `webpage_id` (`webpage_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `webpage_properties` (
		  `webpage_id` int(11) unsigned NOT NULL,
		  `effective_active` tinyint(1) DEFAULT NULL,
		  `effective_locked` tinyint(1) DEFAULT NULL,
		  `effective_translation_memory_id` int(11) unsigned DEFAULT NULL,
		  `effective_enable_live_translation` tinyint(1) DEFAULT NULL,
		  `effective_auto_approve` tinyint(4) DEFAULT NULL,
		  PRIMARY KEY (`webpage_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `webpage_refresh_log` (
		  `refresh_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `webpage_id` int(11) unsigned NOT NULL,
		  `date_checked` datetime DEFAULT NULL,
		  `response_code` int(5) DEFAULT NULL,
		  `content_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `checked_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `content` longtext COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`refresh_log_id`),
		  KEY `webpage_id` (`webpage_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `webpage_statuses` (
		  `status_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `status_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`status_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;
		";
		
		
		$sql[] = "
		INSERT INTO `webpage_statuses` (`status_id`, `status_name`) VALUES
		(2, 'Translation Required'),
		(3, 'Pending Approval'),
		(5, 'Approved');
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `webpage_versions` (
		  `webpage_version_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `webpage_id` int(11) unsigned NOT NULL,
		  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `page_content` longtext COLLATE utf8_unicode_ci NOT NULL,
		  `posted_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `date_posted` datetime DEFAULT NULL,
		  `approval_status` int(5) NOT NULL,
		  `comments` text COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`webpage_version_id`),
		  KEY `webpage_id` (`webpage_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=456 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `websites` (
		  `website_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `website_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `website_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `source_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `target_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `base_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `active` tinyint(1) DEFAULT '1',
		  `locked` tinyint(1) DEFAULT '0',
		  `translation_memory_id` int(11) unsigned DEFAULT '0',
		  `enable_live_translation` tinyint(1) DEFAULT '0',
		  `auto_approve` tinyint(1) DEFAULT '0',
		  PRIMARY KEY (`website_id`),
		  KEY `translation_memory_id` (`translation_memory_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=428 ;
		";
		
		/*
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_records` (
		  `record_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `mtime` int(11) DEFAULT NULL,
		  `translation_memory_id` int(11) unsigned NOT NULL DEFAULT '0',
		  `last_string_extraction_time` int(11) DEFAULT NULL,
		  `locked` tinyint(1) DEFAULT NULL,
		  PRIMARY KEY (`record_id`,`translation_memory_id`),
		  KEY `translation_memory_id` (`translation_memory_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `xf_tm_record_strings` (
		  `record_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `string_id` int(11) unsigned NOT NULL,
		  PRIMARY KEY (`record_id`,`string_id`),
		  KEY `string_id` (`string_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `xf_tm_strings` (
		  `string_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `string_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `normalized_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`string_id`),
		  UNIQUE KEY `language` (`language`,`hash`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_translations` (
		  `translation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `string_id` int(11) unsigned NOT NULL,
		  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `translation_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `normalized_translation_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `created_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `translation_hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`translation_id`),
		  KEY `string_id` (`string_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_translations_comments` (
		  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `translation_id` int(11) unsigned NOT NULL,
		  `translation_memory_id` int(11) unsigned NOT NULL,
		  `posted_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `comments` text COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`comment_id`),
		  KEY `translation_id` (`translation_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_translations_score` (
		  `translation_memory_id` int(11) unsigned NOT NULL,
		  `translation_id` int(11) unsigned NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `score` int(11) NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  PRIMARY KEY (`translation_memory_id`,`username`,`translation_id`),
		  KEY `translation_id` (`translation_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_translations_status` (
		  `translation_status_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `translation_memory_id` int(11) unsigned NOT NULL,
		  `translation_id` int(11) unsigned NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `status_id` int(11) unsigned NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  PRIMARY KEY (`translation_status_id`),
		  KEY `translation_memory_id` (`translation_memory_id`),
		  KEY `translation_id` (`translation_id`),
		  KEY `username` (`username`),
		  KEY `status_id` (`status_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_translation_memories` (
		  `translation_memory_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `translation_memory_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		  `source_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `destination_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `mtime` int(11) DEFAULT NULL,
		  `auto_approve` tinyint(1) DEFAULT NULL,
		  PRIMARY KEY (`translation_memory_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=988 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_translation_memories_cache` (
		  `translation_memory_id` int(11) unsigned NOT NULL,
		  `string_id` int(11) unsigned NOT NULL,
		  `approved_translation_id` int(11) unsigned DEFAULT NULL,
		  `most_recent_translation_id` int(11) unsigned DEFAULT NULL,
		  PRIMARY KEY (`translation_memory_id`,`string_id`),
		  KEY `approved_translation_id` (`approved_translation_id`),
		  KEY `most_recent_translation_id` (`most_recent_translation_id`),
		  KEY `string_id` (`string_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `xf_tm_translation_memories_managers` (
		  `translation_memory_id` int(11) unsigned NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`translation_memory_id`,`username`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `xf_tm_translation_memory_translations` (
		  `translation_memory_id` int(11) unsigned NOT NULL,
		  `translation_id` int(11) unsigned NOT NULL,
		  PRIMARY KEY (`translation_memory_id`,`translation_id`),
		  KEY `translation_id` (`translation_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `xf_tm_translation_statuses` (
		  `status_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `status_name` int(11) NOT NULL,
		  PRIMARY KEY (`status_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_workflows` (
		  `workflow_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `temp_translation_memory_id` int(11) unsigned NOT NULL,
		  `translation_memory_id` int(11) unsigned NOT NULL,
		  `created_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `current_step_id` int(11) unsigned NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  PRIMARY KEY (`workflow_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_workflow_records` (
		  `workflow_id` int(11) unsigned NOT NULL,
		  `record_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`workflow_id`,`record_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_workflow_steps` (
		  `workflow_step_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `workflow_id` int(11) unsigned NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  `step_number` int(5) NOT NULL,
		  PRIMARY KEY (`workflow_step_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_workflow_step_changes` (
		  `workflow_step_id` int(11) unsigned NOT NULL,
		  `translations_log_id` int(11) unsigned NOT NULL,
		  PRIMARY KEY (`workflow_step_id`,`translations_log_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		CREATE TABLE IF NOT EXISTS `xf_tm_workflow_step_panels` (
		  `panel_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `workflow_step_id` int(11) unsigned NOT NULL,
		  `panel_type_id` int(11) unsigned NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  `last_modified` datetime DEFAULT NULL,
		  PRIMARY KEY (`panel_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_workflow_step_panel_actions` (
		  `action_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `workflow_step_id` int(11) unsigned NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `action_type_id` int(11) unsigned NOT NULL,
		  `date_created` datetime DEFAULT NULL,
		  PRIMARY KEY (`action_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_workflow_step_panel_members` (
		  `panel_id` int(11) unsigned NOT NULL,
		  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`panel_id`,`username`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		
		$sql[] = "
		
		CREATE TABLE IF NOT EXISTS `xf_tm_workflow_strings` (
		  `workflow_id` int(11) unsigned NOT NULL,
		  `string_id` int(11) unsigned NOT NULL,
		  PRIMARY KEY (`workflow_id`,`string_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		*/
		$sql[] = "
		ALTER TABLE `jobs`
		  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE;
		";
		
		$sql[] = "
		ALTER TABLE `job_assignments`
		  ADD CONSTRAINT `job_assignments_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_notes` (`job_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `job_assignments_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`username`) ON UPDATE CASCADE,
		  ADD CONSTRAINT `job_assignments_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`username`) ON UPDATE CASCADE;
		";
		
		$sql[] = "
		ALTER TABLE `job_content`
		  ADD CONSTRAINT `job_content_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE;
		";
		
		$sql[] = "
		ALTER TABLE `job_inputs_translation_misses`
		  ADD CONSTRAINT `job_inputs_translation_misses_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `job_inputs_translation_misses_ibfk_2` FOREIGN KEY (`translation_miss_log_id`) REFERENCES `translation_miss_log` (`translation_miss_log_id`);
		";
		
		
		$sql[] = "
		ALTER TABLE `job_inputs_webpages`
		  ADD CONSTRAINT `job_inputs_webpages_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `job_inputs_webpages_ibfk_2` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`);
		";
		
		
		$sql[] = "
		ALTER TABLE `job_inputs_webpages_strings`
		  ADD CONSTRAINT `job_inputs_webpages_strings_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `job_inputs_webpages_strings_ibfk_2` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`);
		";
		
		
		$sql[] = "
		--
		ALTER TABLE `job_notes`
		  ADD CONSTRAINT `job_notes_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `job_notes_ibfk_2` FOREIGN KEY (`posted_by`) REFERENCES `users` (`username`) ON UPDATE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `job_notes_read`
		  ADD CONSTRAINT `job_notes_read_ibfk_1` FOREIGN KEY (`job_note_id`) REFERENCES `job_notes` (`job_note_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `job_notes_read_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `job_roles`
		  ADD CONSTRAINT `job_roles_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
		  ADD CONSTRAINT `job_roles_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `job_status_log`
		  ADD CONSTRAINT `job_status_log_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `job_status_log_ibfk_3` FOREIGN KEY (`posted_by`) REFERENCES `users` (`username`) ON UPDATE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `job_translatable`
		  ADD CONSTRAINT `job_translatable_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `job_translatable_ibfk_2` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`);
		";
		
		$sql[] = "
		
		ALTER TABLE `path_aliases`
		  ADD CONSTRAINT `path_aliases_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE;
		";
		
		$sql[] = "
		
		ALTER TABLE `translation_miss_log`
		  ADD CONSTRAINT `translation_miss_log_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `translation_miss_log_ibfk_2` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `translation_miss_log_ibfk_3` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `translation_miss_log_en`
		  ADD CONSTRAINT `translation_miss_log_en_ibfk_1` FOREIGN KEY (`translation_miss_log_id`) REFERENCES `translation_miss_log` (`translation_miss_log_id`) ON DELETE CASCADE;
		";
		$sql[] = "
		ALTER TABLE `translation_miss_log_fr`
		  ADD CONSTRAINT `translation_miss_log_fr_ibfk_1` FOREIGN KEY (`translation_miss_log_id`) REFERENCES `translation_miss_log` (`translation_miss_log_id`) ON DELETE CASCADE;
		";
		$sql[] = "
		ALTER TABLE `webpages`
		  ADD CONSTRAINT `webpages_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `webpages_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE;
		";
		$sql[] = "
		ALTER TABLE `webpages_en`
		  ADD CONSTRAINT `webpages_en_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `webpage_check_log`
		  ADD CONSTRAINT `webpage_check_log_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `webpage_properties`
		  ADD CONSTRAINT `webpage_properties_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `webpage_refresh_log`
		  ADD CONSTRAINT `webpage_refresh_log_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "

		ALTER TABLE `webpage_versions`
		  ADD CONSTRAINT `webpage_versions_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `websites`
		  ADD CONSTRAINT `websites_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`);
		";
		/*
		$sql[] = "
		ALTER TABLE `xf_tm_records`
		  ADD CONSTRAINT `xf_tm_records_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `xf_tm_record_strings`
		  ADD CONSTRAINT `xf_tm_record_strings_ibfk_1` FOREIGN KEY (`string_id`) REFERENCES `xf_tm_strings` (`string_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `xf_tm_translations`
		  ADD CONSTRAINT `xf_tm_translations_ibfk_1` FOREIGN KEY (`string_id`) REFERENCES `xf_tm_strings` (`string_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `xf_tm_translations_comments`
		  ADD CONSTRAINT `xf_tm_translations_comments_ibfk_1` FOREIGN KEY (`translation_id`) REFERENCES `xf_tm_translations` (`translation_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `xf_tm_translations_score`
		  ADD CONSTRAINT `xf_tm_translations_score_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `xf_tm_translations_score_ibfk_2` FOREIGN KEY (`translation_id`) REFERENCES `xf_tm_translations` (`translation_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `xf_tm_translations_status`
		  ADD CONSTRAINT `xf_tm_translations_status_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `xf_tm_translations_status_ibfk_4` FOREIGN KEY (`translation_id`) REFERENCES `xf_tm_translations` (`translation_id`);
		";
		
		
		$sql[] = "
		ALTER TABLE `xf_tm_translation_memories_cache`
		  ADD CONSTRAINT `xf_tm_translation_memories_cache_ibfk_2` FOREIGN KEY (`string_id`) REFERENCES `xf_tm_translations` (`string_id`),
		  ADD CONSTRAINT `xf_tm_translation_memories_cache_ibfk_3` FOREIGN KEY (`approved_translation_id`) REFERENCES `xf_tm_translations` (`translation_id`),
		  ADD CONSTRAINT `xf_tm_translation_memories_cache_ibfk_4` FOREIGN KEY (`most_recent_translation_id`) REFERENCES `xf_tm_translations` (`translation_id`),
		  ADD CONSTRAINT `xf_tm_translation_memories_cache_ibfk_5` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `xf_tm_translation_memories_managers`
		  ADD CONSTRAINT `xf_tm_translation_memories_managers_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE;
		";
		
		
		$sql[] = "
		ALTER TABLE `xf_tm_translation_memory_translations`
		  ADD CONSTRAINT `xf_tm_translation_memory_translations_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE,
		  ADD CONSTRAINT `xf_tm_translation_memory_translations_ibfk_2` FOREIGN KEY (`translation_id`) REFERENCES `xf_tm_translations` (`translation_id`);
		";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS `dataface__modules` (
		  `module_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `module_version` int(11) DEFAULT NULL,
		  PRIMARY KEY (`module_name`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		$sql[] = "
			INSERT INTO `dataface__modules` (`module_name`, `module_version`) VALUES
			('modules_tm', 2);
		";
		*/
		foreach ($sql as $q){
			//echo $q;
			mysql_query($q, df_db());
		}
		//df_q($sql);
		
		
		
	}
	
	function update_3445(){
	
		$sql[] = "CREATE TABLE IF NOT EXISTS `user_roles` (
			`user_role_id` int(11) unsigned NOT NULL,
			`name` varchar(64) NOT NULL,
			`display_name` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			PRIMARY KEY (`user_role_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			
		$sql[] = "ALTER TABLE `jobs` ADD COLUMN
			  `posted_by` varchar(100) COLLATE utf8_unicode_ci NOT NULL
			  AFTER `destination_language`;";

    	foreach ($sql as $q){
      		mysql_query($q, df_db());
      	}
    }
    
    function update_3446(){
    
    	$sql[] = "INSERT INTO `user_roles` VALUES 
    		(1,'USER','USER'),(2,'TRANSLATOR','TRANSLATOR'),(3,'ADMIN','ADMIN');";
    		
    	foreach ($sql as $q){
      		mysql_query($q, df_db());
      	}
    	
    }
    
    function update_3461(){
    
    	$sql[] = "ALTER TABLE  `users` DROP  `user_id`";
    	$sql[] = "ALTER TABLE  `users` ADD PRIMARY KEY (  `username` )";
    	try {
    		df_q($sql);
    	} catch (Exception $ex){}
    	df_clear_views();
    	
    }
    
    function update_3565(){
    	
    	$sql[] = "ALTER TABLE `jobs` ADD COLUMN
			  `word_count` int(11);";
		$sql[] = "ALTER TABLE `job_translatable` ADD COLUMN
			  `word_count` int(11);";

    	try {
    		df_q($sql);
    	} catch (Exception $ex){}
    	df_clear_views();
    }
    
    function update_3650(){
    	$sql[] = "CREATE TABLE `webpage_strings` (
			 `webpage_id` int(11) unsigned NOT NULL,
			 `string_id` int(11) unsigned NOT NULL,
			 PRIMARY KEY (`webpage_id`,`string_id`),
			 KEY `string_id` (`string_id`) ".
//			 CONSTRAINT `webpage_strings_ibfk_2` FOREIGN KEY (`string_id`) REFERENCES `xf_tm_strings` (`string_id`) ON UPDATE CASCADE,
//			 CONSTRAINT `webpage_strings_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE ON UPDATE CASCADE
			") ENGINE=InnoDB DEFAULT CHARSET=utf8";
			
		$sql[] = "ALTER TABLE  `background_processes` ADD  `status_message` VARCHAR( 255 ) NULL ,
			ADD  `status_current_position` INT( 11 ) NULL ,
			ADD  `status_total` INT( 11 ) NULL DEFAULT  '100'";
			
		$sql[] = "ALTER TABLE  `background_processes` ADD  `username` VARCHAR( 100 ) NULL";
			
		try {
    		df_q($sql);
    	} catch (Exception $ex){
    		throw $ex;
    	}
    	df_clear_views();
    }
    
    
    function update_3684(){
    	$sql[] = "ALTER TABLE  `webpage_versions` ADD  `source_content` LONGTEXT NULL AFTER  `page_content`";
    	$sql[] = "CREATE TABLE `webpage_version_translations` (
			 `webpage_version_id` int(11) unsigned NOT NULL,
			 `string_id` int(11) unsigned NOT NULL,
			 `translation_id` int(11) unsigned NOT NULL,
			 `translated_by` varchar(255) DEFAULT NULL,
			 `translated_date` datetime DEFAULT NULL,
			 `translation_status` int(11) unsigned DEFAULT NULL,
			 PRIMARY KEY (`webpage_version_id`,`string_id`),
			 KEY `string_id` (`string_id`),
			 KEY `translation_id` (`translation_id`) ,"
//			 CONSTRAINT `webpage_version_translations_ibfk_3` FOREIGN KEY (`translation_id`) REFERENCES `xf_tm_translations` (`translation_id`) ON UPDATE CASCADE,
."			 CONSTRAINT `webpage_version_translations_ibfk_1` FOREIGN KEY (`webpage_version_id`) REFERENCES `webpage_versions` (`webpage_version_id`) ON DELETE CASCADE ON UPDATE CASCADE"
//			 CONSTRAINT `webpage_version_translations_ibfk_2` FOREIGN KEY (`string_id`) REFERENCES `xf_tm_strings` (`string_id`) ON UPDATE CASCADE
."			) ENGINE=InnoDB DEFAULT CHARSET=utf8";
			
		$sql[] = "ALTER TABLE  `translation_miss_log` ADD FOREIGN KEY (  `website_id` ) REFERENCES  `websites` (
			`website_id`
			) ON DELETE CASCADE ON UPDATE CASCADE ;";
			
		$sql[] = "CREATE TABLE `text_filters` (
			 `filter_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `filter_title` varchar(100) NOT NULL,
			 `pattern` varchar(255) NOT NULL,
			 `replacement` text NOT NULL,
			 `comments` text,
			 `default_order` int(11) NOT NULL DEFAULT '0' COMMENT 'The default priority of this filter',
			 `is_default_prefilter` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Whether this filter should be included with new sites',
			 `is_default_postfilter` tinyint(1) unsigned NOT NULL DEFAULT '0',
			 `language` varchar(2) DEFAULT NULL,
			 PRIMARY KEY (`filter_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8";
			
		$sql[] = "CREATE TABLE `site_text_filters` (
			 `website_id` int(11) unsigned NOT NULL,
			 `filter_id` int(11) unsigned NOT NULL,
			 `filter_type` enum('Prefilter','Postfilter') NOT NULL,
			 `filter_order` int(11) NOT NULL DEFAULT '0',
			 PRIMARY KEY (`website_id`,`filter_id`),
			 KEY `filter_id` (`filter_id`),
			 CONSTRAINT `site_text_filters_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			 CONSTRAINT `site_text_filters_ibfk_2` FOREIGN KEY (`filter_id`) REFERENCES `text_filters` (`filter_id`) ON UPDATE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8";
			
		$sql[] = "INSERT INTO `text_filters` VALUES(1, 'Wrap Numbers in Variables', '/\\\\b(?<!&\\#)([0-9]+[\\\\/\\\\.\\\\-,]?)+/', '<span data-swete-translate=\"0\">\$0</span>', 'Wraps all numbers in <span data-swete-translate=\"0\">..</span> tags so that they will be treated as variables by the TMTools::encode() method.', 10, 1, 0, NULL);";
		$sql[] = "INSERT INTO `text_filters` VALUES(2, 'English Days of Week', '/\\\\b(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)\\\\b/', '<span data-swete-translate=\"0\" data-date-format=\"%B\">\$0</span>', 'Converts English days of the week into variables.', 0, 1, 0, 'en');";
		$sql[] = "INSERT INTO `text_filters` VALUES(3, 'English Days of Week Abbrev', '/\\\\b(Mon|Tue|Wed|Thu|Fri|Sat|Sun)\\\\b/', '<span data-swete-translate=\"0\" data-date-format=\"%a\">\$0</span>', 'Converts abbreviated english dates to variables.', 0, 1, 0, 'en');";
		$sql[] = "INSERT INTO `text_filters` VALUES(4, 'English Full Month Names', '/\\\\b(January|February|March|April|May|June|July|August|September|October|November|December)\\\\b/', '<span data-swete-translate=\"0\" data-date-format=\"%B\">\$0</span>', NULL, 0, 1, 0, 'en');";
		$sql[] = "INSERT INTO `text_filters` VALUES(5, 'English Abbreviated Month Names', '/\\\\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\\\\b/', '<span data-swete-translate=\"0\" data-date-format=\"%b\">$0</span>', 'Converts abbreviated english month names to variables.', 0, 1, 0, 'en');";
		$sql[] = "INSERT INTO `text_filters` VALUES(6, 'Dot-delimitted date', '/\\\\b[0-3][0-9]\\\\.[01][0-9]\\\\.[0-9]{4}\\\\b/', '<span data-swete-translate=\"0\" data-date-format=\"%d.%m.%Y\">\$0</span>', NULL, 0, 1, 0, 'en');";
		$sql[] = "INSERT INTO `text_filters` VALUES(7, '12 Hour Time', '/\\\\b[01]?[0-9]:[0-5][0-9]( )?(AM|PM)\\\\b/', '<span data-swete-translate=\"0\" data-date-format=\"%l:%M$1%p\">\$0</span>', NULL, 0, 1, 0, 'en');";


    	try {
    		df_q($sql);
    	} catch (Exception $ex){}
    	df_clear_views();
    
    }
    
    function update_3685(){
    	$sql[] = "ALTER TABLE  `job_assignments` DROP FOREIGN KEY  `job_assignments_ibfk_1` ;";
    	$sql[] = "ALTER TABLE  `job_assignments` ADD FOREIGN KEY (  `job_id` ) REFERENCES  `jobs` (`job_id`) ON DELETE CASCADE ON UPDATE CASCADE ;";
    	
		try {
    		df_q($sql);
    	} catch (Exception $ex){}
    	df_clear_views();
    }
    
    function update_3686(){
    	$sql[] = "ALTER TABLE `job_translatable` ADD COLUMN `source_url` varchar(255) COLLATE utf8_unicode_ci;";
    	
    	 try {
    		df_q($sql);
    	} catch (Exception $ex){}
    	df_clear_views();
    }
    
    function update_3766(){
    	$sql[] = "ALTER TABLE  `websites` ADD  `log_translation_misses` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0'";
    	try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_3766: '.$ex->getMessage());
    	}	
    }
    
    function udpate_3767(){
    	$sql[] = "ALTER TABLE  `websites` ADD  `log_requests` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0'";
    	try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_3767: '.$ex->getMessage());
    	}
    }
    
    function update_3770(){
    	$sql[] = "ALTER TABLE  `websites` ADD  `google_api_key` VARCHAR( 100 ) NULL";
    	try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_3770: '.$ex->getMessage());
    	}
    }
    
    function update_3771(){
    	$sql[] = "ALTER TABLE  `languages` ADD  `google_language_code` VARCHAR( 10 ) NULL";
    	try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_3771: '.$ex->getMessage());
    	}
    }
    
    function update_3772(){
    	$sql[] = "INSERT IGNORE INTO `languages` (`language_code`,`language_label`,`google_language_code`) VALUES
    		('zh', 'Chinese (Simplified)', 'zh'),
    		('zt', 'Chinese (Traditional)', 'zh-TW'),
    		('de', 'German', 'de'),
    		('it', 'Italian', 'it'),
    		('es', 'Spanish', 'es'),
    		('ja', 'Japanese', 'ja'),
    		('hi', 'Hindi', 'hi'),
    		('ar', 'Arabic', 'ar'),
    		('ru', 'Russian', 'ru'),
    		('nl', 'Dutch', 'nl')";
    		
    	try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_3772: '.$ex->getMessage());
    	}
    }
    
    function update_3824(){
    	$sql[] = "ALTER TABLE  `translation_miss_log` ADD INDEX (  `webpage_refresh_log_id` )";
    	$sql[] = "ALTER TABLE  `translation_miss_log` ADD INDEX (  `http_request_log_id` )";
    	
    	$res = df_q("select max(filter_id) from text_filters");
    	list($filterId) = mysql_fetch_row($res);
    	@mysql_free_result($res);
    	$filterId++;
    	$sql[] = "INSERT INTO `text_filters` VALUES($filterId, 'English Date With Full Month Names', '/\\b(January|February|March|April|May|June|July|August|September|October|November|December) \\\\d{1,2}, \\\\d{4}\\\\b/', '<span data-swete-translate=\"0\" data-date-format=\"%B %e, %Y\">\$0</span>', NULL, 0, 1, 0, 'en');";
    	$filterId++;
    	$sql[] = "INSERT INTO `text_filters` VALUES($filterId, 'English Date with Abbreviated Month Names', '/\\\\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\\\\.? \\\\d{1,2}, \\\\d{4}\\\\b/', '<span data-swete-translate=\"0\" data-date-format=\"%b %e, %Y\">\$0</span>', 'Converts abbreviated english month names to variables.', 0, 1, 0, 'en');";
    	
    	$sql[] = "UPDATE `text_filters` set is_default_prefilter=0 where filter_id in (4,5)";
    	
    	$sql[] = "INSERT IGNORE INTO `users` (`username`,`role_id`,`password`,`email`) VALUES ('admin',3, '".md5('password')."', 'swete-default-admin@weblite.ca')";
    	
    	$sql[] = "DELETE FROM `users` where `username` in ('test_user','test_user2')";
		$sql[] = "UPDATE text_filters set `pattern`='/\\\\b(?<!&\\#)([0-9]+[\\\\/\\\\.\\\\-,]?)+/' where filter_id=1";
    	try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_3824: '.$ex->getMessage());
    	}
    }
    
    function update_3839(){
    	$sql[] = "CREATE TABLE  `dashboard` (
		`dashboard_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
		) ENGINE = INNODB;";
		
		try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_3839: '.$ex->getMessage());
    	}
    }
    
    function update_4631(){
        $sql[] = "alter table websites add source_date_locale varchar(10);";
        $sql[] = "alter table websites add target_date_locale varchar(10);";
        try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_4631: '.$ex->getMessage());
    	}
    }
    
    function update_4632(){
        $replacements = array(
            'English Date with Abbreviated Month Names' => '<span data-swete-translate="0" data-date-format="MMM d, y">$0</span>',
            'English Date With Full Month Names' => '<span data-swete-translate="0" data-date-format="MMMM d, y">$0</span>',
            'English Days of Week' => '<span data-swete-translate="0" data-date-format="eeee">$0</span>',
            'English Days of Week Abbrev' => '<span data-swete-translate="0" data-date-format="eee">$0</span>',
            'English Full Month Names' => '<span data-swete-translate="0" data-date-format="LLLL">$0</span>',
            'English Abbreviated Month Names' => '<span data-swete-translate="0" data-date-format="LLL">$0</span>',
            'Dot-delimitted date' => '<span data-swete-translate="0" data-date-format="dd.MM.y">$0</span>',
            '12 Hour Time' => '<span data-swete-translate="0" data-date-format="hh:mm a">$0</span>'
        );
        foreach ($replacements as $name=>$replacement){
            $sql[] = sprintf("update `text_filters` set replacement='%s' where `filter_title`='%s'",
                addslashes($replacement),
                addslashes($name)
            );
        }
        try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_4632: '.$ex->getMessage());
    	}
    
    }
    
    function update_4633(){
        $replacements = array(
            'English Date with Abbreviated Month Names' => '1',
            'English Date With Full Month Names' => '0',
            'English Days of Week' => '2',
            'English Days of Week Abbrev' => '3',
            'English Full Month Names' => '2',
            'English Abbreviated Month Names' => '3',
            'Dot-delimitted date' => '4',
            '12 Hour Time' => '5'
        );
        
        foreach ( $replacements as $name=>$order ){
            $sql[] = sprintf("update text_filters set default_order=%d where filter_title='%s'",
                intval($order),
                addslashes($name)
            );
        }
        try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_4633: '.$ex->getMessage());
    	}
    }
    
    function update_4634(){
        $replacements = array(
            'English Date with Abbreviated Month Names' => '/\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\.? \d{1,2}, \d{4}\b/',
            'English Date With Full Month Names' => '/(January|February|March|April|May|June|July|August|September|October|November|December) \d{1,2}, \d{4}\b/',
            'English Days of Week' => '/\b(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)\b/',
            'English Days of Week Abbrev' => '/\b(Mon|Tue|Wed|Thu|Fri|Sat|Sun)\b/',
            'English Full Month Names' => '/\b(January|February|March|April|May|June|July|August|September|October|November|December)\b/',
            'English Abbreviated Month Names' => '/\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\b/',
            'Dot-delimitted date' => '/\b[0-3][0-9]\.[01][0-9]\.[0-9]{4}\b/',
            '12 Hour Time' => '/\b[01]?[0-9]:[0-5][0-9]( )?(AM|PM)\b/',
            'Wrap Numbers in Variables' => '/\b(?<!&#)([0-9]+[\/\.\-,]?)+/'
        );
        foreach ($replacements as $name=>$replacement){
            $sql[] = sprintf("update `text_filters` set `pattern`='%s' where `filter_title`='%s'",
                addslashes($replacement),
                addslashes($name)
            );
        }
        try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_4634: '.$ex->getMessage());
    	}
    
    }
    
    function update_4635(){
        $replacements = array(
            'English Date with Abbreviated Month Names' => '<span data-swete-translate="0" data-date-format="MMM. d, y">$0</span>'
        );
        foreach ($replacements as $name=>$replacement){
            $sql[] = sprintf("update `text_filters` set replacement='%s' where `filter_title`='%s'",
                addslashes($replacement),
                addslashes($name)
            );
        }
        try {
    		df_q($sql);
    		df_clear_views();
    		
    	} catch (Exception $ex){
    		error_log('Update_4635: '.$ex->getMessage());
    	}
    
    }
    
    
    function update_4636(){
        $sql[] = "ALTER TABLE `websites` ADD COLUMN `webservice_secret_key` VARCHAR(255) NULL  AFTER `google_api_key`";
        df_q($sql);
        df_clear_views();
        df_clear_cache();
        
    }
    
    function update_4637(){
        // Moved this over from the tm module where it was incorrectly placed.
        $sql[] = "ALTER TABLE  `translation_miss_log` ADD  `string_id` INT( 11 ) UNSIGNED NULL ,
			ADD INDEX (  `string_id` )";
        try {
            df_q($sql);
            df_clear_views();
            df_clear_cache();
        } catch ( Exception $ex){}
        
    }
    
    function update_4638(){
        // Create a directory to store the uploads
        mkdir('livecache/string_imports');
        
        // Create the string_imports table for handling the importing of 
        // CSV files, etc...
        $sql[] = "CREATE  TABLE `string_imports` (
            `string_import_id` INT(11) NOT NULL AUTO_INCREMENT ,
            `file` VARCHAR(255) NULL ,
            `file_mimetype` VARCHAR(45) NULL ,
            `file_format` ENUM('CSV') NULL ,
            `target_translation_memory_uuid` VARCHAR(45) NULL ,
            `log` LONGTEXT NULL,
            `status` ENUM('COMPLETE','FAILED','PENDING') DEFAULT 'PENDING',
            PRIMARY KEY (`string_import_id`) );
          ";
        try {
            df_q($sql);
            df_clear_views();
            df_clear_cache();
        } catch ( Exception $ex){}
        
    }
    
    function update_4640(){
        
        $sql[] = "ALTER TABLE `string_imports` ADD `succeeded` INT(11) UNSIGNED NULL, ADD `failed` INT(11) UNSIGNED NULL";
        try {
            df_q($sql);
            df_clear_views();
            df_clear_cache();
        } catch ( Exception $ex){}      
    }
    
    function update_4790(){
        $sql[] = "CREATE VIEW `swete_strings` AS
            select tml.*,
                    s.num_words,
                    t.normalized_translation_value,
                    if(tml.webpage_id is null,hrl.proxy_request_url, concat(ws.website_url,w.webpage_url)) as request_url,
                    tm.translation_memory_uuid
                    from 
                    xf_tm_strings s
                    left join translation_miss_log tml on tml.string_id=s.string_id
                    left join xf_tm_translation_memories tm on tml.translation_memory_id=tm.translation_memory_id

                    left join xf_tm_translation_memory_strings tms on tms.string_id=tml.string_id and tms.translation_memory_id=tml.translation_memory_id
                    left join xf_tm_translations t on tms.current_translation_id=t.translation_id
                    left join http_request_log hrl on tml.http_request_log_id=hrl.http_request_log_id
                    left join webpages w on tml.webpage_id=w.webpage_id
                    left join websites ws on tml.website_id=ws.website_id
            ";
        
        
        df_q($sql);
        
        
        $res = df_q('select distinct `target_language` from websites');
        $languages = array();
        while ($row = mysql_fetch_row($res) ) $languages[] = $row[0];
        @mysql_free_result($res);

        $res = df_q('select distinct `source_language` from websites');
        while ($row = mysql_fetch_row($res) ) $languages[] = $row[0];
        @mysql_free_result($res);

        $languages = array_unique($languages);

        $missing = $languages;

        foreach ($missing as $lang){
            if ( !preg_match('/^[a-zA-Z0-9]{2}/', $lang) ) throw new Exception("Invalid language code ".$lang);


            $sql = <<<END
CREATE TABLE IF NOT EXISTS `swete_strings_$lang` (
`string_id` int(11) unsigned not null,
`string` text COLLATE utf8_unicode_ci,
PRIMARY KEY (`string_id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
END
;
            df_q($sql);


        }
    }
}