-- MySQL dump 10.13  Distrib 5.5.9, for osx10.6 (i386)
--
-- Host: localhost    Database: swete
-- ------------------------------------------------------
-- Server version	5.5.9

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `background_processes`
--

DROP TABLE IF EXISTS `background_processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `background_processes` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `background_processes`
--

LOCK TABLES `background_processes` WRITE;
/*!40000 ALTER TABLE `background_processes` DISABLE KEYS */;
/*!40000 ALTER TABLE `background_processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataface__failed_logins`
--

DROP TABLE IF EXISTS `dataface__failed_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataface__failed_logins` (
  `attempt_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `time_of_attempt` int(11) NOT NULL,
  PRIMARY KEY (`attempt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataface__failed_logins`
--

LOCK TABLES `dataface__failed_logins` WRITE;
/*!40000 ALTER TABLE `dataface__failed_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `dataface__failed_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataface__modules`
--

DROP TABLE IF EXISTS `dataface__modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataface__modules` (
  `module_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_version` int(11) DEFAULT NULL,
  PRIMARY KEY (`module_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataface__modules`
--

LOCK TABLES `dataface__modules` WRITE;
/*!40000 ALTER TABLE `dataface__modules` DISABLE KEYS */;
INSERT INTO `dataface__modules` VALUES ('modules_tm',2);
/*!40000 ALTER TABLE `dataface__modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataface__mtimes`
--

DROP TABLE IF EXISTS `dataface__mtimes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataface__mtimes` (
  `name` varchar(255) NOT NULL,
  `mtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataface__mtimes`
--

LOCK TABLES `dataface__mtimes` WRITE;
/*!40000 ALTER TABLE `dataface__mtimes` DISABLE KEYS */;
INSERT INTO `dataface__mtimes` VALUES ('background_processes',1331839491),('dataface__failed_logins',1331850988),('dataface__modules',1331839491),('dataface__mtimes',1331839492),('dataface__preferences',1331844115),('dataface__version',1331839491),('dataface__view_4886c0e037ccde7524246d5dc4182d6e',1331854338),('dataface__view_690a1bd77f2ee2963dabb8e62a728ad7',1331854778),('dataface__view_8d840dd7d400e59095d84c75bf7d654c',1331854304),('dataface__view_ce91d5e85305e1db1d53adc827171c6e',1331844092),('http_request_log',1331839491),('jobs',1331843575),('job_assignments',1331839491),('job_content',1331839491),('job_inputs_translation_misses',1331839491),('job_inputs_webpages',1331839491),('job_inputs_webpages_strings',1331839491),('job_notes',1331858894),('job_notes_read',1331839491),('job_roles',1331852439),('job_status_log',1331839491),('job_translatable',1331839491),('languages',1331839491),('path_aliases',1331839491),('translation_miss_log',1331839491),('translation_miss_log_en',1331839491),('translation_miss_log_fr',1331839491),('users',1331854718),('user_roles',1331925904),('webpages',1331839491),('webpages_en',1331839491),('webpages_fr',1331839491),('webpage_check_log',1331839491),('webpage_properties',1331839491),('webpage_refresh_log',1331839491),('webpage_statuses',1331839491),('webpage_versions',1331839491),('websites',1331843469),('xf_tm_records',1331839491),('xf_tm_record_strings',1331839491),('xf_tm_strings',1331839491),('xf_tm_translations',1331839491),('xf_tm_translations_comments',1331839491),('xf_tm_translations_score',1331839491),('xf_tm_translations_status',1331839491),('xf_tm_translation_memories',1331843469),('xf_tm_translation_memories_cache',1331839491),('xf_tm_translation_memories_managers',1331839491),('xf_tm_translation_memory_translations',1331839491),('xf_tm_translation_statuses',1331839491),('xf_tm_workflows',1331839491),('xf_tm_workflow_records',1331839491),('xf_tm_workflow_steps',1331839491),('xf_tm_workflow_step_changes',1331839491),('xf_tm_workflow_step_panels',1331839491),('xf_tm_workflow_step_panel_actions',1331839491),('xf_tm_workflow_step_panel_members',1331839491),('xf_tm_workflow_strings',1331839491);
/*!40000 ALTER TABLE `dataface__mtimes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataface__preferences`
--

DROP TABLE IF EXISTS `dataface__preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataface__preferences` (
  `pref_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `table` varchar(128) NOT NULL,
  `record_id` varchar(255) NOT NULL,
  `key` varchar(128) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`pref_id`),
  KEY `username` (`username`),
  KEY `table` (`table`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataface__preferences`
--

LOCK TABLES `dataface__preferences` WRITE;
/*!40000 ALTER TABLE `dataface__preferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `dataface__preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataface__version`
--

DROP TABLE IF EXISTS `dataface__version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataface__version` (
  `version` int(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataface__version`
--

LOCK TABLES `dataface__version` WRITE;
/*!40000 ALTER TABLE `dataface__version` DISABLE KEYS */;
INSERT INTO `dataface__version` VALUES (1);
/*!40000 ALTER TABLE `dataface__version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `dataface__view_4886c0e037ccde7524246d5dc4182d6e`
--

DROP TABLE IF EXISTS `dataface__view_4886c0e037ccde7524246d5dc4182d6e`;
/*!50001 DROP VIEW IF EXISTS `dataface__view_4886c0e037ccde7524246d5dc4182d6e`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `dataface__view_4886c0e037ccde7524246d5dc4182d6e` (
  `job_id` int(11) unsigned,
  `website_id` int(11) unsigned,
  `date_created` datetime,
  `job_status` int(5) unsigned,
  `translation_memory_id` int(11) unsigned,
  `source_language` varchar(2),
  `destination_language` varchar(2),
  `assigned_to` varchar(100),
  `compiled` tinyint(1) unsigned,
  `access_level` int(5)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `dataface__view_690a1bd77f2ee2963dabb8e62a728ad7`
--

DROP TABLE IF EXISTS `dataface__view_690a1bd77f2ee2963dabb8e62a728ad7`;
/*!50001 DROP VIEW IF EXISTS `dataface__view_690a1bd77f2ee2963dabb8e62a728ad7`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `dataface__view_690a1bd77f2ee2963dabb8e62a728ad7` (
  `job_id` int(11) unsigned,
  `website_id` int(11) unsigned,
  `date_created` datetime,
  `job_status` int(5) unsigned,
  `translation_memory_id` int(11) unsigned,
  `source_language` varchar(2),
  `destination_language` varchar(2),
  `assigned_to` varchar(100),
  `compiled` tinyint(1) unsigned,
  `access_level` int(5)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `dataface__view_8d840dd7d400e59095d84c75bf7d654c`
--

DROP TABLE IF EXISTS `dataface__view_8d840dd7d400e59095d84c75bf7d654c`;
/*!50001 DROP VIEW IF EXISTS `dataface__view_8d840dd7d400e59095d84c75bf7d654c`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `dataface__view_8d840dd7d400e59095d84c75bf7d654c` (
  `job_id` int(11) unsigned,
  `website_id` int(11) unsigned,
  `date_created` datetime,
  `job_status` int(5) unsigned,
  `translation_memory_id` int(11) unsigned,
  `source_language` varchar(2),
  `destination_language` varchar(2),
  `assigned_to` varchar(100),
  `compiled` tinyint(1) unsigned,
  `access_level` int(5)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `dataface__view_ce91d5e85305e1db1d53adc827171c6e`
--

DROP TABLE IF EXISTS `dataface__view_ce91d5e85305e1db1d53adc827171c6e`;
/*!50001 DROP VIEW IF EXISTS `dataface__view_ce91d5e85305e1db1d53adc827171c6e`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `dataface__view_ce91d5e85305e1db1d53adc827171c6e` (
  `job_id` int(11) unsigned,
  `website_id` int(11) unsigned,
  `date_created` datetime,
  `job_status` int(5) unsigned,
  `translation_memory_id` int(11) unsigned,
  `source_language` varchar(2),
  `destination_language` varchar(2),
  `assigned_to` varchar(100),
  `compiled` tinyint(1) unsigned,
  `access_level` int(5)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `http_request_log`
--

DROP TABLE IF EXISTS `http_request_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `http_request_log` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `http_request_log`
--

LOCK TABLES `http_request_log` WRITE;
/*!40000 ALTER TABLE `http_request_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `http_request_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_assignments`
--

DROP TABLE IF EXISTS `job_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_assignments` (
  `job_assignment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) unsigned NOT NULL,
  `assigned_to` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `assigned_by` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_assigned` datetime DEFAULT NULL,
  PRIMARY KEY (`job_assignment_id`),
  KEY `job_id` (`job_id`),
  KEY `assigned_to` (`assigned_to`),
  KEY `assigned_by` (`assigned_by`),
  CONSTRAINT `job_assignments_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_notes` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `job_assignments_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`username`) ON UPDATE CASCADE,
  CONSTRAINT `job_assignments_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`username`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_assignments`
--

LOCK TABLES `job_assignments` WRITE;
/*!40000 ALTER TABLE `job_assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_content`
--

DROP TABLE IF EXISTS `job_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_content` (
  `job_content_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) unsigned NOT NULL,
  `url` text COLLATE utf8_unicode_ci,
  `url_hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` longblob,
  PRIMARY KEY (`job_content_id`),
  KEY `job_id` (`job_id`),
  CONSTRAINT `job_content_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_content`
--

LOCK TABLES `job_content` WRITE;
/*!40000 ALTER TABLE `job_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_inputs_translation_misses`
--

DROP TABLE IF EXISTS `job_inputs_translation_misses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_inputs_translation_misses` (
  `job_id` int(11) unsigned NOT NULL,
  `translation_miss_log_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`job_id`,`translation_miss_log_id`),
  KEY `translation_miss_log_id` (`translation_miss_log_id`),
  CONSTRAINT `job_inputs_translation_misses_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `job_inputs_translation_misses_ibfk_2` FOREIGN KEY (`translation_miss_log_id`) REFERENCES `translation_miss_log` (`translation_miss_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_inputs_translation_misses`
--

LOCK TABLES `job_inputs_translation_misses` WRITE;
/*!40000 ALTER TABLE `job_inputs_translation_misses` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_inputs_translation_misses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_inputs_webpages`
--

DROP TABLE IF EXISTS `job_inputs_webpages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_inputs_webpages` (
  `job_id` int(11) unsigned NOT NULL,
  `webpage_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`job_id`,`webpage_id`),
  KEY `webpage_id` (`webpage_id`),
  CONSTRAINT `job_inputs_webpages_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `job_inputs_webpages_ibfk_2` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_inputs_webpages`
--

LOCK TABLES `job_inputs_webpages` WRITE;
/*!40000 ALTER TABLE `job_inputs_webpages` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_inputs_webpages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_inputs_webpages_strings`
--

DROP TABLE IF EXISTS `job_inputs_webpages_strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_inputs_webpages_strings` (
  `job_inputs_webpages_string_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) unsigned DEFAULT NULL,
  `webpage_id` int(11) unsigned DEFAULT NULL,
  `string` text COLLATE utf8_unicode_ci,
  `string_hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`job_inputs_webpages_string_id`),
  KEY `job_id` (`job_id`,`webpage_id`),
  KEY `webpage_id` (`webpage_id`),
  CONSTRAINT `job_inputs_webpages_strings_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `job_inputs_webpages_strings_ibfk_2` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_inputs_webpages_strings`
--

LOCK TABLES `job_inputs_webpages_strings` WRITE;
/*!40000 ALTER TABLE `job_inputs_webpages_strings` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_inputs_webpages_strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_notes`
--

DROP TABLE IF EXISTS `job_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_notes` (
  `job_note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) unsigned NOT NULL,
  `note_content` text COLLATE utf8_unicode_ci NOT NULL,
  `date_posted` datetime DEFAULT NULL,
  `deleted` tinyint(1) unsigned DEFAULT NULL,
  `posted_by` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`job_note_id`),
  KEY `job_id` (`job_id`),
  KEY `posted_by` (`posted_by`),
  CONSTRAINT `job_notes_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `job_notes_ibfk_2` FOREIGN KEY (`posted_by`) REFERENCES `users` (`username`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_notes`
--

LOCK TABLES `job_notes` WRITE;
/*!40000 ALTER TABLE `job_notes` DISABLE KEYS */;
INSERT INTO `job_notes` VALUES (1,1,'This is a really long message that goes on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on and on','2012-03-15 17:48:00',0,'admin'),(3,1,'working on it','2012-03-19 15:43:01',0,'pippa');
/*!40000 ALTER TABLE `job_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_notes_read`
--

DROP TABLE IF EXISTS `job_notes_read`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_notes_read` (
  `job_note_id` int(11) unsigned NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `read` tinyint(1) unsigned DEFAULT NULL,
  `date_read` datetime DEFAULT NULL,
  PRIMARY KEY (`job_note_id`,`username`),
  KEY `username` (`username`),
  CONSTRAINT `job_notes_read_ibfk_1` FOREIGN KEY (`job_note_id`) REFERENCES `job_notes` (`job_note_id`) ON DELETE CASCADE,
  CONSTRAINT `job_notes_read_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_notes_read`
--

LOCK TABLES `job_notes_read` WRITE;
/*!40000 ALTER TABLE `job_notes_read` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_notes_read` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_roles`
--

DROP TABLE IF EXISTS `job_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_roles` (
  `job_id` int(11) unsigned NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `access_level` int(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`job_id`,`username`),
  KEY `username` (`username`),
  CONSTRAINT `job_roles_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `job_roles_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_roles`
--

LOCK TABLES `job_roles` WRITE;
/*!40000 ALTER TABLE `job_roles` DISABLE KEYS */;
INSERT INTO `job_roles` VALUES (1,'admin',1),(1,'pippa',1),(1,'test_user',1);
/*!40000 ALTER TABLE `job_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_status_log`
--

DROP TABLE IF EXISTS `job_status_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_status_log` (
  `job_status_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) unsigned NOT NULL,
  `status_id` int(11) unsigned NOT NULL,
  `posted_by` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_posted` datetime DEFAULT NULL,
  PRIMARY KEY (`job_status_log_id`),
  KEY `job_id` (`job_id`),
  KEY `posted_by` (`posted_by`),
  CONSTRAINT `job_status_log_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `job_status_log_ibfk_3` FOREIGN KEY (`posted_by`) REFERENCES `users` (`username`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_status_log`
--

LOCK TABLES `job_status_log` WRITE;
/*!40000 ALTER TABLE `job_status_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_status_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_translatable`
--

DROP TABLE IF EXISTS `job_translatable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_translatable` (
  `job_translatable_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) unsigned NOT NULL,
  `content_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `webpage_id` int(11) unsigned DEFAULT NULL,
  `full_contents` longtext COLLATE utf8_unicode_ci,
  `translatable_contents` longtext COLLATE utf8_unicode_ci,
  `previous_translations` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`job_translatable_id`),
  KEY `job_id` (`job_id`),
  KEY `webpage_id` (`webpage_id`),
  CONSTRAINT `job_translatable_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `job_translatable_ibfk_2` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_translatable`
--

LOCK TABLES `job_translatable` WRITE;
/*!40000 ALTER TABLE `job_translatable` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_translatable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
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
  KEY `website_id` (`website_id`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,1,'2012-03-15 13:31:00',NULL,NULL,NULL,NULL,'pippa',0);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `language_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `language_label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES ('en','English'),('fr','French');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `path_aliases`
--

DROP TABLE IF EXISTS `path_aliases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `path_aliases` (
  `path_alias_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` int(11) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`path_alias_id`),
  KEY `website_id` (`website_id`),
  CONSTRAINT `path_aliases_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `path_aliases`
--

LOCK TABLES `path_aliases` WRITE;
/*!40000 ALTER TABLE `path_aliases` DISABLE KEYS */;
/*!40000 ALTER TABLE `path_aliases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translation_miss_log`
--

DROP TABLE IF EXISTS `translation_miss_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translation_miss_log` (
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
  KEY `website_id` (`website_id`),
  CONSTRAINT `translation_miss_log_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE,
  CONSTRAINT `translation_miss_log_ibfk_2` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE,
  CONSTRAINT `translation_miss_log_ibfk_3` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translation_miss_log`
--

LOCK TABLES `translation_miss_log` WRITE;
/*!40000 ALTER TABLE `translation_miss_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `translation_miss_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translation_miss_log_en`
--

DROP TABLE IF EXISTS `translation_miss_log_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translation_miss_log_en` (
  `translation_miss_log_id` int(11) unsigned NOT NULL,
  `string` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`translation_miss_log_id`),
  CONSTRAINT `translation_miss_log_en_ibfk_1` FOREIGN KEY (`translation_miss_log_id`) REFERENCES `translation_miss_log` (`translation_miss_log_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translation_miss_log_en`
--

LOCK TABLES `translation_miss_log_en` WRITE;
/*!40000 ALTER TABLE `translation_miss_log_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `translation_miss_log_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translation_miss_log_fr`
--

DROP TABLE IF EXISTS `translation_miss_log_fr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translation_miss_log_fr` (
  `translation_miss_log_id` int(11) unsigned NOT NULL,
  `string` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`translation_miss_log_id`),
  CONSTRAINT `translation_miss_log_fr_ibfk_1` FOREIGN KEY (`translation_miss_log_id`) REFERENCES `translation_miss_log` (`translation_miss_log_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translation_miss_log_fr`
--

LOCK TABLES `translation_miss_log_fr` WRITE;
/*!40000 ALTER TABLE `translation_miss_log_fr` DISABLE KEYS */;
/*!40000 ALTER TABLE `translation_miss_log_fr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_roles` (
  `user_role_id` int(11) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`user_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES (1,'USER'),(2,'TRANSLATOR'),(3,'ADMIN');
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `job_id_2` (`username`),
  KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`user_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (128,'test_user',1,'foo','test_user@example.com'),(129,'test_user2',1,'foo','test_user2@example.com'),(130,'pippa',1,'password',NULL),(131,'admin',3,'password',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webpage_check_log`
--

DROP TABLE IF EXISTS `webpage_check_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webpage_check_log` (
  `check_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `webpage_id` int(11) unsigned NOT NULL,
  `date_checked` datetime DEFAULT NULL,
  `response_code` int(5) DEFAULT NULL,
  `content_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `checked_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`check_log_id`),
  KEY `webpage_id` (`webpage_id`),
  CONSTRAINT `webpage_check_log_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webpage_check_log`
--

LOCK TABLES `webpage_check_log` WRITE;
/*!40000 ALTER TABLE `webpage_check_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `webpage_check_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webpage_properties`
--

DROP TABLE IF EXISTS `webpage_properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webpage_properties` (
  `webpage_id` int(11) unsigned NOT NULL,
  `effective_active` tinyint(1) DEFAULT NULL,
  `effective_locked` tinyint(1) DEFAULT NULL,
  `effective_translation_memory_id` int(11) unsigned DEFAULT NULL,
  `effective_enable_live_translation` tinyint(1) DEFAULT NULL,
  `effective_auto_approve` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`webpage_id`),
  CONSTRAINT `webpage_properties_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webpage_properties`
--

LOCK TABLES `webpage_properties` WRITE;
/*!40000 ALTER TABLE `webpage_properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `webpage_properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webpage_refresh_log`
--

DROP TABLE IF EXISTS `webpage_refresh_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webpage_refresh_log` (
  `refresh_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `webpage_id` int(11) unsigned NOT NULL,
  `date_checked` datetime DEFAULT NULL,
  `response_code` int(5) DEFAULT NULL,
  `content_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `checked_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`refresh_log_id`),
  KEY `webpage_id` (`webpage_id`),
  CONSTRAINT `webpage_refresh_log_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webpage_refresh_log`
--

LOCK TABLES `webpage_refresh_log` WRITE;
/*!40000 ALTER TABLE `webpage_refresh_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `webpage_refresh_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webpage_statuses`
--

DROP TABLE IF EXISTS `webpage_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webpage_statuses` (
  `status_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webpage_statuses`
--

LOCK TABLES `webpage_statuses` WRITE;
/*!40000 ALTER TABLE `webpage_statuses` DISABLE KEYS */;
INSERT INTO `webpage_statuses` VALUES (2,'Translation Required'),(3,'Pending Approval'),(5,'Approved');
/*!40000 ALTER TABLE `webpage_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webpage_versions`
--

DROP TABLE IF EXISTS `webpage_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webpage_versions` (
  `webpage_version_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `webpage_id` int(11) unsigned NOT NULL,
  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `page_content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `posted_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_posted` datetime DEFAULT NULL,
  `approval_status` int(5) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`webpage_version_id`),
  KEY `webpage_id` (`webpage_id`),
  CONSTRAINT `webpage_versions_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webpage_versions`
--

LOCK TABLES `webpage_versions` WRITE;
/*!40000 ALTER TABLE `webpage_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `webpage_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webpages`
--

DROP TABLE IF EXISTS `webpages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webpages` (
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
  KEY `website_id` (`website_id`),
  CONSTRAINT `webpages_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE,
  CONSTRAINT `webpages_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webpages`
--

LOCK TABLES `webpages` WRITE;
/*!40000 ALTER TABLE `webpages` DISABLE KEYS */;
/*!40000 ALTER TABLE `webpages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webpages_en`
--

DROP TABLE IF EXISTS `webpages_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webpages_en` (
  `webpage_id` int(11) unsigned NOT NULL DEFAULT '0',
  `webpage_content` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`webpage_id`),
  CONSTRAINT `webpages_en_ibfk_1` FOREIGN KEY (`webpage_id`) REFERENCES `webpages` (`webpage_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webpages_en`
--

LOCK TABLES `webpages_en` WRITE;
/*!40000 ALTER TABLE `webpages_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `webpages_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webpages_fr`
--

DROP TABLE IF EXISTS `webpages_fr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webpages_fr` (
  `webpage_id` int(11) unsigned NOT NULL DEFAULT '0',
  `webpage_content` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`webpage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webpages_fr`
--

LOCK TABLES `webpages_fr` WRITE;
/*!40000 ALTER TABLE `webpages_fr` DISABLE KEYS */;
/*!40000 ALTER TABLE `webpages_fr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `websites`
--

DROP TABLE IF EXISTS `websites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `websites` (
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
  KEY `translation_memory_id` (`translation_memory_id`),
  CONSTRAINT `websites_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `websites`
--

LOCK TABLES `websites` WRITE;
/*!40000 ALTER TABLE `websites` DISABLE KEYS */;
INSERT INTO `websites` VALUES (1,'Fake Site','local.fakey.com','en','en','local.swete','//',1,0,1,0,0);
/*!40000 ALTER TABLE `websites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_record_strings`
--

DROP TABLE IF EXISTS `xf_tm_record_strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_record_strings` (
  `record_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `string_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`record_id`,`string_id`),
  KEY `string_id` (`string_id`),
  CONSTRAINT `xf_tm_record_strings_ibfk_1` FOREIGN KEY (`string_id`) REFERENCES `xf_tm_strings` (`string_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_record_strings`
--

LOCK TABLES `xf_tm_record_strings` WRITE;
/*!40000 ALTER TABLE `xf_tm_record_strings` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_record_strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_records`
--

DROP TABLE IF EXISTS `xf_tm_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_records` (
  `record_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mtime` int(11) DEFAULT NULL,
  `translation_memory_id` int(11) unsigned NOT NULL DEFAULT '0',
  `last_string_extraction_time` int(11) DEFAULT NULL,
  `locked` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`record_id`,`translation_memory_id`),
  KEY `translation_memory_id` (`translation_memory_id`),
  CONSTRAINT `xf_tm_records_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_records`
--

LOCK TABLES `xf_tm_records` WRITE;
/*!40000 ALTER TABLE `xf_tm_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_strings`
--

DROP TABLE IF EXISTS `xf_tm_strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_strings` (
  `string_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `string_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `normalized_value` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`string_id`),
  UNIQUE KEY `language` (`language`,`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_strings`
--

LOCK TABLES `xf_tm_strings` WRITE;
/*!40000 ALTER TABLE `xf_tm_strings` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_translation_memories`
--

DROP TABLE IF EXISTS `xf_tm_translation_memories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_translation_memories` (
  `translation_memory_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `translation_memory_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `source_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `destination_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `auto_approve` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`translation_memory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_translation_memories`
--

LOCK TABLES `xf_tm_translation_memories` WRITE;
/*!40000 ALTER TABLE `xf_tm_translation_memories` DISABLE KEYS */;
INSERT INTO `xf_tm_translation_memories` VALUES (1,'Fake Site Dictionary','en','en',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `xf_tm_translation_memories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_translation_memories_cache`
--

DROP TABLE IF EXISTS `xf_tm_translation_memories_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_translation_memories_cache` (
  `translation_memory_id` int(11) unsigned NOT NULL,
  `string_id` int(11) unsigned NOT NULL,
  `approved_translation_id` int(11) unsigned DEFAULT NULL,
  `most_recent_translation_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`translation_memory_id`,`string_id`),
  KEY `approved_translation_id` (`approved_translation_id`),
  KEY `most_recent_translation_id` (`most_recent_translation_id`),
  KEY `string_id` (`string_id`),
  CONSTRAINT `xf_tm_translation_memories_cache_ibfk_2` FOREIGN KEY (`string_id`) REFERENCES `xf_tm_translations` (`string_id`),
  CONSTRAINT `xf_tm_translation_memories_cache_ibfk_3` FOREIGN KEY (`approved_translation_id`) REFERENCES `xf_tm_translations` (`translation_id`),
  CONSTRAINT `xf_tm_translation_memories_cache_ibfk_4` FOREIGN KEY (`most_recent_translation_id`) REFERENCES `xf_tm_translations` (`translation_id`),
  CONSTRAINT `xf_tm_translation_memories_cache_ibfk_5` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_translation_memories_cache`
--

LOCK TABLES `xf_tm_translation_memories_cache` WRITE;
/*!40000 ALTER TABLE `xf_tm_translation_memories_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_translation_memories_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_translation_memories_managers`
--

DROP TABLE IF EXISTS `xf_tm_translation_memories_managers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_translation_memories_managers` (
  `translation_memory_id` int(11) unsigned NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`translation_memory_id`,`username`),
  CONSTRAINT `xf_tm_translation_memories_managers_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_translation_memories_managers`
--

LOCK TABLES `xf_tm_translation_memories_managers` WRITE;
/*!40000 ALTER TABLE `xf_tm_translation_memories_managers` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_translation_memories_managers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_translation_memory_translations`
--

DROP TABLE IF EXISTS `xf_tm_translation_memory_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_translation_memory_translations` (
  `translation_memory_id` int(11) unsigned NOT NULL,
  `translation_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`translation_memory_id`,`translation_id`),
  KEY `translation_id` (`translation_id`),
  CONSTRAINT `xf_tm_translation_memory_translations_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE,
  CONSTRAINT `xf_tm_translation_memory_translations_ibfk_2` FOREIGN KEY (`translation_id`) REFERENCES `xf_tm_translations` (`translation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_translation_memory_translations`
--

LOCK TABLES `xf_tm_translation_memory_translations` WRITE;
/*!40000 ALTER TABLE `xf_tm_translation_memory_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_translation_memory_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_translation_statuses`
--

DROP TABLE IF EXISTS `xf_tm_translation_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_translation_statuses` (
  `status_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status_name` int(11) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_translation_statuses`
--

LOCK TABLES `xf_tm_translation_statuses` WRITE;
/*!40000 ALTER TABLE `xf_tm_translation_statuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_translation_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_translations`
--

DROP TABLE IF EXISTS `xf_tm_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_translations` (
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
  KEY `string_id` (`string_id`),
  CONSTRAINT `xf_tm_translations_ibfk_1` FOREIGN KEY (`string_id`) REFERENCES `xf_tm_strings` (`string_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_translations`
--

LOCK TABLES `xf_tm_translations` WRITE;
/*!40000 ALTER TABLE `xf_tm_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_translations_comments`
--

DROP TABLE IF EXISTS `xf_tm_translations_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_translations_comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `translation_id` int(11) unsigned NOT NULL,
  `translation_memory_id` int(11) unsigned NOT NULL,
  `posted_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`comment_id`),
  KEY `translation_id` (`translation_id`),
  CONSTRAINT `xf_tm_translations_comments_ibfk_1` FOREIGN KEY (`translation_id`) REFERENCES `xf_tm_translations` (`translation_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_translations_comments`
--

LOCK TABLES `xf_tm_translations_comments` WRITE;
/*!40000 ALTER TABLE `xf_tm_translations_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_translations_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_translations_score`
--

DROP TABLE IF EXISTS `xf_tm_translations_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_translations_score` (
  `translation_memory_id` int(11) unsigned NOT NULL,
  `translation_id` int(11) unsigned NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `score` int(11) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`translation_memory_id`,`username`,`translation_id`),
  KEY `translation_id` (`translation_id`),
  CONSTRAINT `xf_tm_translations_score_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE,
  CONSTRAINT `xf_tm_translations_score_ibfk_2` FOREIGN KEY (`translation_id`) REFERENCES `xf_tm_translations` (`translation_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_translations_score`
--

LOCK TABLES `xf_tm_translations_score` WRITE;
/*!40000 ALTER TABLE `xf_tm_translations_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_translations_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_translations_status`
--

DROP TABLE IF EXISTS `xf_tm_translations_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_translations_status` (
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
  KEY `status_id` (`status_id`),
  CONSTRAINT `xf_tm_translations_status_ibfk_1` FOREIGN KEY (`translation_memory_id`) REFERENCES `xf_tm_translation_memories` (`translation_memory_id`) ON DELETE CASCADE,
  CONSTRAINT `xf_tm_translations_status_ibfk_4` FOREIGN KEY (`translation_id`) REFERENCES `xf_tm_translations` (`translation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_translations_status`
--

LOCK TABLES `xf_tm_translations_status` WRITE;
/*!40000 ALTER TABLE `xf_tm_translations_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_translations_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_workflow_records`
--

DROP TABLE IF EXISTS `xf_tm_workflow_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_workflow_records` (
  `workflow_id` int(11) unsigned NOT NULL,
  `record_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`workflow_id`,`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_workflow_records`
--

LOCK TABLES `xf_tm_workflow_records` WRITE;
/*!40000 ALTER TABLE `xf_tm_workflow_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_workflow_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_workflow_step_changes`
--

DROP TABLE IF EXISTS `xf_tm_workflow_step_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_workflow_step_changes` (
  `workflow_step_id` int(11) unsigned NOT NULL,
  `translations_log_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`workflow_step_id`,`translations_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_workflow_step_changes`
--

LOCK TABLES `xf_tm_workflow_step_changes` WRITE;
/*!40000 ALTER TABLE `xf_tm_workflow_step_changes` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_workflow_step_changes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_workflow_step_panel_actions`
--

DROP TABLE IF EXISTS `xf_tm_workflow_step_panel_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_workflow_step_panel_actions` (
  `action_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `workflow_step_id` int(11) unsigned NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_type_id` int(11) unsigned NOT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_workflow_step_panel_actions`
--

LOCK TABLES `xf_tm_workflow_step_panel_actions` WRITE;
/*!40000 ALTER TABLE `xf_tm_workflow_step_panel_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_workflow_step_panel_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_workflow_step_panel_members`
--

DROP TABLE IF EXISTS `xf_tm_workflow_step_panel_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_workflow_step_panel_members` (
  `panel_id` int(11) unsigned NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`panel_id`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_workflow_step_panel_members`
--

LOCK TABLES `xf_tm_workflow_step_panel_members` WRITE;
/*!40000 ALTER TABLE `xf_tm_workflow_step_panel_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_workflow_step_panel_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_workflow_step_panels`
--

DROP TABLE IF EXISTS `xf_tm_workflow_step_panels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_workflow_step_panels` (
  `panel_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `workflow_step_id` int(11) unsigned NOT NULL,
  `panel_type_id` int(11) unsigned NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`panel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_workflow_step_panels`
--

LOCK TABLES `xf_tm_workflow_step_panels` WRITE;
/*!40000 ALTER TABLE `xf_tm_workflow_step_panels` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_workflow_step_panels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_workflow_steps`
--

DROP TABLE IF EXISTS `xf_tm_workflow_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_workflow_steps` (
  `workflow_step_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) unsigned NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `step_number` int(5) NOT NULL,
  PRIMARY KEY (`workflow_step_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_workflow_steps`
--

LOCK TABLES `xf_tm_workflow_steps` WRITE;
/*!40000 ALTER TABLE `xf_tm_workflow_steps` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_workflow_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_workflow_strings`
--

DROP TABLE IF EXISTS `xf_tm_workflow_strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_workflow_strings` (
  `workflow_id` int(11) unsigned NOT NULL,
  `string_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`workflow_id`,`string_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_workflow_strings`
--

LOCK TABLES `xf_tm_workflow_strings` WRITE;
/*!40000 ALTER TABLE `xf_tm_workflow_strings` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_workflow_strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xf_tm_workflows`
--

DROP TABLE IF EXISTS `xf_tm_workflows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xf_tm_workflows` (
  `workflow_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `temp_translation_memory_id` int(11) unsigned NOT NULL,
  `translation_memory_id` int(11) unsigned NOT NULL,
  `created_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `current_step_id` int(11) unsigned NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`workflow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xf_tm_workflows`
--

LOCK TABLES `xf_tm_workflows` WRITE;
/*!40000 ALTER TABLE `xf_tm_workflows` DISABLE KEYS */;
/*!40000 ALTER TABLE `xf_tm_workflows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `dataface__view_4886c0e037ccde7524246d5dc4182d6e`
--

/*!50001 DROP TABLE IF EXISTS `dataface__view_4886c0e037ccde7524246d5dc4182d6e`*/;
/*!50001 DROP VIEW IF EXISTS `dataface__view_4886c0e037ccde7524246d5dc4182d6e`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = latin1 */;
/*!50001 SET character_set_results     = latin1 */;
/*!50001 SET collation_connection      = latin1_swedish_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `dataface__view_4886c0e037ccde7524246d5dc4182d6e` AS select `j`.`job_id` AS `job_id`,`j`.`website_id` AS `website_id`,`j`.`date_created` AS `date_created`,`j`.`job_status` AS `job_status`,`j`.`translation_memory_id` AS `translation_memory_id`,`j`.`source_language` AS `source_language`,`j`.`destination_language` AS `destination_language`,`j`.`assigned_to` AS `assigned_to`,`j`.`compiled` AS `compiled`,`r`.`access_level` AS `access_level` from (`jobs` `j` left join `job_roles` `r` on(((`j`.`job_id` = `r`.`job_id`) and (`r`.`username` = '')))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dataface__view_690a1bd77f2ee2963dabb8e62a728ad7`
--

/*!50001 DROP TABLE IF EXISTS `dataface__view_690a1bd77f2ee2963dabb8e62a728ad7`*/;
/*!50001 DROP VIEW IF EXISTS `dataface__view_690a1bd77f2ee2963dabb8e62a728ad7`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = latin1 */;
/*!50001 SET character_set_results     = latin1 */;
/*!50001 SET collation_connection      = latin1_swedish_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `dataface__view_690a1bd77f2ee2963dabb8e62a728ad7` AS select `j`.`job_id` AS `job_id`,`j`.`website_id` AS `website_id`,`j`.`date_created` AS `date_created`,`j`.`job_status` AS `job_status`,`j`.`translation_memory_id` AS `translation_memory_id`,`j`.`source_language` AS `source_language`,`j`.`destination_language` AS `destination_language`,`j`.`assigned_to` AS `assigned_to`,`j`.`compiled` AS `compiled`,`r`.`access_level` AS `access_level` from (`jobs` `j` left join `job_roles` `r` on(((`j`.`job_id` = `r`.`job_id`) and (`r`.`username` = 'admin')))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dataface__view_8d840dd7d400e59095d84c75bf7d654c`
--

/*!50001 DROP TABLE IF EXISTS `dataface__view_8d840dd7d400e59095d84c75bf7d654c`*/;
/*!50001 DROP VIEW IF EXISTS `dataface__view_8d840dd7d400e59095d84c75bf7d654c`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = latin1 */;
/*!50001 SET character_set_results     = latin1 */;
/*!50001 SET collation_connection      = latin1_swedish_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `dataface__view_8d840dd7d400e59095d84c75bf7d654c` AS select `j`.`job_id` AS `job_id`,`j`.`website_id` AS `website_id`,`j`.`date_created` AS `date_created`,`j`.`job_status` AS `job_status`,`j`.`translation_memory_id` AS `translation_memory_id`,`j`.`source_language` AS `source_language`,`j`.`destination_language` AS `destination_language`,`j`.`assigned_to` AS `assigned_to`,`j`.`compiled` AS `compiled`,`r`.`access_level` AS `access_level` from (`jobs` `j` left join `job_roles` `r` on(((`j`.`job_id` = `r`.`job_id`) and (`r`.`username` = 'pippa')))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dataface__view_ce91d5e85305e1db1d53adc827171c6e`
--

/*!50001 DROP TABLE IF EXISTS `dataface__view_ce91d5e85305e1db1d53adc827171c6e`*/;
/*!50001 DROP VIEW IF EXISTS `dataface__view_ce91d5e85305e1db1d53adc827171c6e`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = latin1 */;
/*!50001 SET character_set_results     = latin1 */;
/*!50001 SET collation_connection      = latin1_swedish_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `dataface__view_ce91d5e85305e1db1d53adc827171c6e` AS select `j`.`job_id` AS `job_id`,`j`.`website_id` AS `website_id`,`j`.`date_created` AS `date_created`,`j`.`job_status` AS `job_status`,`j`.`translation_memory_id` AS `translation_memory_id`,`j`.`source_language` AS `source_language`,`j`.`destination_language` AS `destination_language`,`j`.`assigned_to` AS `assigned_to`,`j`.`compiled` AS `compiled`,`r`.`access_level` AS `access_level` from (`jobs` `j` left join `job_roles` `r` on((`j`.`job_id` = `r`.`job_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-03-19 18:55:22
