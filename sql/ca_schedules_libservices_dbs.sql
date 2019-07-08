-- MySQL dump 10.13  Distrib 8.0.13, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: libservices
-- ------------------------------------------------------
-- Server version	5.7.14

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `libservices`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `libservices` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `libservices`;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'admin','Administrator'),(2,'supervisor','Supervisor'),(3,'staff','Faculty/Staff'),(4,'guest','Guest');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `login_attempts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `barcode` varchar(15) NOT NULL,
  `emergencyContact` text,
  `barcodeLogin` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `barcode_UNIQUE` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=9612 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (9604,_binary 'na','admin','R8LJQg2MJsa93c172659bbfe84c4a84d025bc73e',NULL,'ess@ess.com',NULL,NULL,NULL,NULL,1559534400,1559914408,1,'adminFirst','adminLast','ESS','000000000','12345','ess contact street',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_groups`
--

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` VALUES (29,9604,1);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'libservices'
--

--
-- Dumping routines for database 'libservices'
--

--
-- Current Database: `ca_schedules`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ca_schedules` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `ca_schedules`;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `body` text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `uid` int(11) NOT NULL,
  `type` varchar(45) DEFAULT 'manual',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exceptiondates`
--

DROP TABLE IF EXISTS `exceptiondates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `exceptiondates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `onDate` date DEFAULT NULL,
  `swapDate` date DEFAULT NULL,
  `noWork` tinyint(4) DEFAULT '0',
  `announcementID` int(11) DEFAULT '0',
  `semesterID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exceptiondates`
--

LOCK TABLES `exceptiondates` WRITE;
/*!40000 ALTER TABLE `exceptiondates` DISABLE KEYS */;
/*!40000 ALTER TABLE `exceptiondates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scheduledlocation`
--

DROP TABLE IF EXISTS `scheduledlocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `scheduledlocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locationText` varchar(45) DEFAULT NULL,
  `locationIMG` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scheduledlocation`
--

LOCK TABLES `scheduledlocation` WRITE;
/*!40000 ALTER TABLE `scheduledlocation` DISABLE KEYS */;
INSERT INTO `scheduledlocation` VALUES (1,'Multimedia','multimediaICO.png'),(2,'Internet Lab','internetLabICO.png'),(3,'Periodicals','periodicalsICO.png'),(4,'Circulation','circulationICO.png');
/*!40000 ALTER TABLE `scheduledlocation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scheduledshifts`
--

DROP TABLE IF EXISTS `scheduledshifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `scheduledshifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `dayOfWeek` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Semester_id` int(11) DEFAULT NULL,
  `ShiftTypes_id` int(11) DEFAULT NULL,
  `caID` int(11) DEFAULT NULL,
  `scheduledDate` datetime DEFAULT NULL,
  `scheduledLocation_id` int(11) NOT NULL,
  `isRecursive` varchar(3) CHARACTER SET latin1 DEFAULT NULL,
  `recursiveEndDate` date DEFAULT NULL,
  `listView` tinyint(1) DEFAULT '1' COMMENT 'List view identifiies whether to list this on the webpage.. If this shift is generated by recursive methods it will return 0 and not list on webpage.',
  `recursiveID` int(11) DEFAULT '0',
  `updateBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ScheduledShifts_Semester_idx` (`Semester_id`),
  KEY `fk_ScheduledShifts_ShiftTypes1_idx` (`ShiftTypes_id`),
  KEY `fk_ScheduledShifts_scheduledLocation1_idx` (`scheduledLocation_id`),
  CONSTRAINT `fk_ScheduledShifts_Semester` FOREIGN KEY (`Semester_id`) REFERENCES `semester` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ScheduledShifts_ShiftTypes1` FOREIGN KEY (`ShiftTypes_id`) REFERENCES `shifttypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ScheduledShifts_scheduledLocation1` FOREIGN KEY (`scheduledLocation_id`) REFERENCES `scheduledlocation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=402 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scheduledshifts`
--

LOCK TABLES `scheduledshifts` WRITE;
/*!40000 ALTER TABLE `scheduledshifts` DISABLE KEYS */;
/*!40000 ALTER TABLE `scheduledshifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `scheduletable`
--

DROP TABLE IF EXISTS `scheduletable`;
/*!50001 DROP VIEW IF EXISTS `scheduletable`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `scheduletable` AS SELECT 
 1 AS `id`,
 1 AS `Semester_id`,
 1 AS `caID`,
 1 AS `username`,
 1 AS `scheduledDate`,
 1 AS `dayOfWeek`,
 1 AS `startTime`,
 1 AS `endTime`,
 1 AS `scheduledLocation_id`,
 1 AS `listView`,
 1 AS `recursiveID`,
 1 AS `recursiveEndDate`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `scheduletable_bylab`
--

DROP TABLE IF EXISTS `scheduletable_bylab`;
/*!50001 DROP VIEW IF EXISTS `scheduletable_bylab`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `scheduletable_bylab` AS SELECT 
 1 AS `id`,
 1 AS `Semester_id`,
 1 AS `caID`,
 1 AS `username`,
 1 AS `scheduledDate`,
 1 AS `dayOfWeek`,
 1 AS `startTime`,
 1 AS `endTime`,
 1 AS `scheduledLocation_id`,
 1 AS `listView`,
 1 AS `recursiveID`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `semester`
--

DROP TABLE IF EXISTS `semester`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` varchar(20) DEFAULT NULL,
  `calendarLink` text,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `semester`
--

LOCK TABLES `semester` WRITE;
/*!40000 ALTER TABLE `semester` DISABLE KEYS */;
INSERT INTO `semester` VALUES (2,'TempSemester 2019','http://www.citytech.cuny.edu/registrar/docs/fall_2019.pdf','2019-08-29','2019-12-22');
/*!40000 ALTER TABLE `semester` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `shifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `startTime` varchar(20) DEFAULT NULL,
  `endTime` varchar(20) DEFAULT '*',
  `caID` int(11) DEFAULT NULL,
  `ScheduledShifts_id` int(11) NOT NULL,
  `note` text,
  `approved` tinyint(4) DEFAULT '1',
  `approvedBy` int(11) DEFAULT NULL,
  `signatureIn` mediumblob,
  `signatureOut` mediumblob,
  `signinTimestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_Shifts_ScheduledShifts1_idx` (`ScheduledShifts_id`),
  CONSTRAINT `fk_Shifts_ScheduledShifts1` FOREIGN KEY (`ScheduledShifts_id`) REFERENCES `scheduledshifts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1 COMMENT='	';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts`
--

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
/*!40000 ALTER TABLE `shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shifttypes`
--

DROP TABLE IF EXISTS `shifttypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `shifttypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `types` varchar(10) DEFAULT NULL,
  `desc` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifttypes`
--

LOCK TABLES `shifttypes` WRITE;
/*!40000 ALTER TABLE `shifttypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `shifttypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `timesheetstatus`
--

DROP TABLE IF EXISTS `timesheetstatus`;
/*!50001 DROP VIEW IF EXISTS `timesheetstatus`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `timesheetstatus` AS SELECT 
 1 AS `signInstatus`,
 1 AS `scheduleID`,
 1 AS `dayOfWeek`,
 1 AS `Semester_id`,
 1 AS `scheduled_caID`,
 1 AS `username`,
 1 AS `scheduledDate`,
 1 AS `scheduled_startTime`,
 1 AS `listView`,
 1 AS `scheduledLocation_id`,
 1 AS `shiftID`,
 1 AS `startTime`,
 1 AS `endTime`,
 1 AS `shift_caID`,
 1 AS `note`,
 1 AS `timeDiff`,
 1 AS `approved`,
 1 AS `approvedBy`,
 1 AS `signatureIn`,
 1 AS `signatureOut`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `timesheetstatus_bylab`
--

DROP TABLE IF EXISTS `timesheetstatus_bylab`;
/*!50001 DROP VIEW IF EXISTS `timesheetstatus_bylab`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `timesheetstatus_bylab` AS SELECT 
 1 AS `signInstatus`,
 1 AS `scheduleID`,
 1 AS `dayOfWeek`,
 1 AS `Semester_id`,
 1 AS `scheduled_caID`,
 1 AS `username`,
 1 AS `scheduledDate`,
 1 AS `scheduled_startTime`,
 1 AS `listView`,
 1 AS `scheduledLocation_id`,
 1 AS `shiftID`,
 1 AS `startTime`,
 1 AS `endTime`,
 1 AS `shift_caID`,
 1 AS `timeDiff`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'ca_schedules'
--

--
-- Dumping routines for database 'ca_schedules'
--
/*!50003 DROP PROCEDURE IF EXISTS `piv_scheduleTable` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `piv_scheduleTable`(
IN sun varchar(10),
IN mon varchar(10),
IN tue varchar(10),
IN wed varchar(10),
IN thur varchar(10),
IN fri varchar(10),
IN sat varchar(10))
BEGIN

SET @sql = NULL;



SELECT
GROUP_CONCAT(DISTINCT
CONCAT(
'(case when `scheduledDate` = ''',
`scheduledDate`,
''' then concat(DATE_FORMAT(startTime,''%H:%i''), "-", DATE_FORMAT(endTime, ''%H:%i'')) end) AS ',
concat("_", replace(`scheduledDate`, '-', ''))
)
) INTO @sql
from scheduleTable where (
scheduledDate = sun or 
scheduledDate = mon or 
scheduledDate = tue or 
scheduledDate = wed or 
scheduledDate = thur or 
scheduledDate = fri or 
scheduledDate = sat);

IF (@sql is null) THEN 
SET @sql = CONCAT('SELECT id, Semester_id as semesterID, caID, scheduledLocation_id AS locationID from scheduleTable 
where (scheduledDate = ''',
sun,
''' or scheduledDate = ''',
mon,
''' or scheduledDate = ''',
tue,
''' or scheduledDate = ''',
wed,
''' or scheduledDate = ''',
thur,
''' or scheduledDate = ''',
fri,
''' or scheduledDate = ''',
sat,
''')');
ELSE 
SET @sql = CONCAT('SELECT id, Semester_id as semesterID, caID, scheduledLocation_id AS locationID, ', @sql, ' 
from scheduleTable 
where (scheduledDate = ''',
sun,
''' or scheduledDate = ''',
mon,
''' or scheduledDate = ''',
tue,
''' or scheduledDate = ''',
wed,
''' or scheduledDate = ''',
thur,
''' or scheduledDate = ''',
fri,
''' or scheduledDate = ''',
sat,
''')');
    END IF; 

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `piv_scheduleTable_byLab` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `piv_scheduleTable_byLab`(IN sun varchar(10),
IN mon varchar(10),
IN tue varchar(10),
IN wed varchar(10),
IN thur varchar(10),
IN fri varchar(10),
IN sat varchar(10))
BEGIN

SET @sql = NULL;



SELECT
GROUP_CONCAT(DISTINCT
CONCAT(
'(case when `scheduledDate` = ''',
`scheduledDate`,
''' then concat(DATE_FORMAT(startTime,''%H:%i''), "-", DATE_FORMAT(endTime, ''%H:%i'')) end) AS ',
concat("_", replace(`scheduledDate`, '-', ''))
)
) INTO @sql
from scheduleTable_byLab where (
scheduledDate = sun or 
scheduledDate = mon or 
scheduledDate = tue or 
scheduledDate = wed or 
scheduledDate = thur or 
scheduledDate = fri or 
scheduledDate = sat);

IF (@sql is null) THEN 
SET @sql = CONCAT('SELECT id, Semester_id as semesterID, caID, scheduledLocation_id AS locationID from scheduleTable_byLab 
where (scheduledDate = ''',
sun,
''' or scheduledDate = ''',
mon,
''' or scheduledDate = ''',
tue,
''' or scheduledDate = ''',
wed,
''' or scheduledDate = ''',
thur,
''' or scheduledDate = ''',
fri,
''' or scheduledDate = ''',
sat,
''')');
ELSE 
SET @sql = CONCAT('SELECT id, Semester_id as semesterID, caID, scheduledLocation_id AS locationID, ', @sql, ' 
from scheduleTable_byLab 
where (scheduledDate = ''',
sun,
''' or scheduledDate = ''',
mon,
''' or scheduledDate = ''',
tue,
''' or scheduledDate = ''',
wed,
''' or scheduledDate = ''',
thur,
''' or scheduledDate = ''',
fri,
''' or scheduledDate = ''',
sat,
''')');
    END IF; 

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `piv_timesheetTable` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `piv_timesheetTable`(
IN sun varchar(10),
IN mon varchar(10),
IN tue varchar(10),
IN wed varchar(10),
IN thur varchar(10),
IN fri varchar(10),
IN sat varchar(10))
BEGIN

SET @sql = NULL;

SELECT
GROUP_CONCAT(DISTINCT
CONCAT(
'(case when `scheduledDate` = ''',
`scheduledDate`,
''' then concat(DATE_FORMAT(startTime,''%H:%i''), "-", DATE_FORMAT(endTime, ''%H:%i'')) end) AS ',
concat("_", replace(`scheduledDate`, '-', ''))
)
) INTO @sql
from timesheetStatus where (
scheduledDate = sun or 
scheduledDate = mon or 
scheduledDate = tue or 
scheduledDate = wed or 
scheduledDate = thur or 
scheduledDate = fri or 
scheduledDate = sat);

IF (@sql is null) THEN 

SET @sql = CONCAT('SELECT shiftID, scheduleID, Semester_id as semesterID, shift_caID as caID, scheduledLocation_id AS locationID from timesheetStatus 
where shift_caID is not null and 
(scheduledDate = ''',
sun,
''' or scheduledDate = ''',
mon,
''' or scheduledDate = ''',
tue,
''' or scheduledDate = ''',
wed,
''' or scheduledDate = ''',
thur,
''' or scheduledDate = ''',
fri,
''' or scheduledDate = ''',
sat,
''')');
else

SET @sql = CONCAT('SELECT shiftID, scheduleID, Semester_id as semesterID, shift_caID as caID, scheduledLocation_id AS locationID, ', @sql, ' from timesheetStatus 
where shift_caID is not null and 
(scheduledDate = ''',
sun,
''' or scheduledDate = ''',
mon,
''' or scheduledDate = ''',
tue,
''' or scheduledDate = ''',
wed,
''' or scheduledDate = ''',
thur,
''' or scheduledDate = ''',
fri,
''' or scheduledDate = ''',
sat,
''')');
    END IF; 


PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `piv_timesheetTable_byLab` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `piv_timesheetTable_byLab`(
IN sun varchar(10),
IN mon varchar(10),
IN tue varchar(10),
IN wed varchar(10),
IN thur varchar(10),
IN fri varchar(10),
IN sat varchar(10))
BEGIN

SET @sql = NULL;

SELECT
GROUP_CONCAT(DISTINCT
CONCAT(
'(case when `scheduledDate` = ''',
`scheduledDate`,
''' then concat(DATE_FORMAT(startTime,''%H:%i''), "-", DATE_FORMAT(endTime, ''%H:%i'')) end) AS ',
concat("_", replace(`scheduledDate`, '-', ''))
)
) INTO @sql
from timesheetStatus_byLab where (
scheduledDate = date_format(sun, '%Y-%m-%d') or 
scheduledDate = date_format(mon, '%Y-%m-%d') or 
scheduledDate = date_format(tue, '%Y-%m-%d') or 
scheduledDate = date_format(wed, '%Y-%m-%d') or 
scheduledDate = date_format(thur, '%Y-%m-%d') or 
scheduledDate = date_format(fri, '%Y-%m-%d') or 
scheduledDate = date_format(sat, '%Y-%m-%d'));

IF (@sql is null) THEN 

SET @sql = CONCAT('SELECT shiftID, scheduleID, Semester_id as semesterID, shift_caID as caID, scheduledLocation_id AS locationID from timesheetStatus_byLab 
where shift_caID is not null and 
(scheduledDate = ''',
date_format(sun, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(mon, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(tue, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(wed, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(thur, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(fri, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(sat, '%Y-%m-%d'),
''')');
ELSE 
SET @sql = CONCAT('SELECT shiftID, scheduleID, Semester_id as semesterID, shift_caID as caID, scheduledLocation_id AS locationID, ', @sql, ' 
from timesheetStatus_byLab 
where shift_caID is not null and 
(scheduledDate = ''',
date_format(sun, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(mon, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(tue, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(wed, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(thur, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(fri, '%Y-%m-%d'),
''' or scheduledDate = ''',
date_format(sat, '%Y-%m-%d'),
''')');
    END IF; 


PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `scheduleEmployeeTable` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `scheduleEmployeeTable`(IN semesterID int,IN employeeID int)
BEGIN

	select scheduleTable.*, ScheduledShifts.isRecursive from scheduleTable, ScheduledShifts 
    where scheduleTable.id = ScheduledShifts.id and scheduleTable.Semester_id = semesterID and scheduleTable.caID = employeeID and scheduleTable.listView = 1
    order by ScheduledShifts.isRecursive desc, scheduleTable.scheduledDate asc, scheduleTable.startTime asc,scheduledLocation_id asc;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `timesheetEmployeeTable` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `timesheetEmployeeTable`(IN employeeID int,IN fromDate varchar(10),IN toDate varchar(10))
BEGIN
	select timesheetStatus.* from timesheetStatus
    where shift_caID = employeeID and scheduledDate >= fromDate and scheduledDate <= toDate
    order by timesheetStatus.scheduledDate asc, timesheetStatus.startTime asc,scheduledLocation_id asc;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Current Database: `libservices`
--

USE `libservices`;

--
-- Current Database: `ca_schedules`
--

USE `ca_schedules`;

--
-- Final view structure for view `scheduletable`
--

/*!50001 DROP VIEW IF EXISTS `scheduletable`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `scheduletable` AS select `ca_schedules`.`scheduledshifts`.`id` AS `id`,`ca_schedules`.`scheduledshifts`.`Semester_id` AS `Semester_id`,`ca_schedules`.`scheduledshifts`.`caID` AS `caID`,`libservices`.`users`.`username` AS `username`,cast(`ca_schedules`.`scheduledshifts`.`scheduledDate` as date) AS `scheduledDate`,`ca_schedules`.`scheduledshifts`.`dayOfWeek` AS `dayOfWeek`,`ca_schedules`.`scheduledshifts`.`startTime` AS `startTime`,`ca_schedules`.`scheduledshifts`.`endTime` AS `endTime`,`ca_schedules`.`scheduledshifts`.`scheduledLocation_id` AS `scheduledLocation_id`,`ca_schedules`.`scheduledshifts`.`listView` AS `listView`,`ca_schedules`.`scheduledshifts`.`recursiveID` AS `recursiveID`,`ca_schedules`.`scheduledshifts`.`recursiveEndDate` AS `recursiveEndDate` from (`ca_schedules`.`scheduledshifts` join `libservices`.`users`) where (`libservices`.`users`.`id` = `ca_schedules`.`scheduledshifts`.`caID`) order by `libservices`.`users`.`username`,`ca_schedules`.`scheduledshifts`.`scheduledLocation_id`,cast(`ca_schedules`.`scheduledshifts`.`scheduledDate` as date),`ca_schedules`.`scheduledshifts`.`startTime` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `scheduletable_bylab`
--

/*!50001 DROP VIEW IF EXISTS `scheduletable_bylab`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `scheduletable_bylab` AS select `ca_schedules`.`scheduledshifts`.`id` AS `id`,`ca_schedules`.`scheduledshifts`.`Semester_id` AS `Semester_id`,`ca_schedules`.`scheduledshifts`.`caID` AS `caID`,`libservices`.`users`.`username` AS `username`,cast(`ca_schedules`.`scheduledshifts`.`scheduledDate` as date) AS `scheduledDate`,`ca_schedules`.`scheduledshifts`.`dayOfWeek` AS `dayOfWeek`,`ca_schedules`.`scheduledshifts`.`startTime` AS `startTime`,`ca_schedules`.`scheduledshifts`.`endTime` AS `endTime`,`ca_schedules`.`scheduledshifts`.`scheduledLocation_id` AS `scheduledLocation_id`,`ca_schedules`.`scheduledshifts`.`listView` AS `listView`,`ca_schedules`.`scheduledshifts`.`recursiveID` AS `recursiveID` from (`ca_schedules`.`scheduledshifts` join `libservices`.`users`) where (`libservices`.`users`.`id` = `ca_schedules`.`scheduledshifts`.`caID`) order by `ca_schedules`.`scheduledshifts`.`scheduledLocation_id`,cast(`ca_schedules`.`scheduledshifts`.`scheduledDate` as date),`ca_schedules`.`scheduledshifts`.`startTime` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `timesheetstatus`
--

/*!50001 DROP VIEW IF EXISTS `timesheetstatus`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `timesheetstatus` AS select if(((time_to_sec(timediff(cast(`ca_schedules`.`scheduledshifts`.`startTime` as time),cast(`ca_schedules`.`shifts`.`startTime` as time))) / 60) < -(15)),'Special',if(time_to_sec(((timediff(cast(`ca_schedules`.`scheduledshifts`.`startTime` as time),cast(`ca_schedules`.`shifts`.`startTime` as time)) / 60) < -(0))),'Late',if(isnull(`ca_schedules`.`shifts`.`startTime`),'NA','On-Time'))) AS `signInstatus`,`ca_schedules`.`scheduledshifts`.`id` AS `scheduleID`,`ca_schedules`.`scheduledshifts`.`dayOfWeek` AS `dayOfWeek`,`ca_schedules`.`scheduledshifts`.`Semester_id` AS `Semester_id`,`ca_schedules`.`scheduledshifts`.`caID` AS `scheduled_caID`,`libservices`.`users`.`username` AS `username`,cast(`ca_schedules`.`scheduledshifts`.`scheduledDate` as date) AS `scheduledDate`,`ca_schedules`.`scheduledshifts`.`startTime` AS `scheduled_startTime`,`ca_schedules`.`scheduledshifts`.`listView` AS `listView`,`ca_schedules`.`scheduledshifts`.`scheduledLocation_id` AS `scheduledLocation_id`,`ca_schedules`.`shifts`.`id` AS `shiftID`,`ca_schedules`.`shifts`.`startTime` AS `startTime`,`ca_schedules`.`shifts`.`endTime` AS `endTime`,`ca_schedules`.`shifts`.`caID` AS `shift_caID`,`ca_schedules`.`shifts`.`note` AS `note`,time_format(timediff(`ca_schedules`.`shifts`.`endTime`,`ca_schedules`.`shifts`.`startTime`),'%H:%i') AS `timeDiff`,`ca_schedules`.`shifts`.`approved` AS `approved`,`ca_schedules`.`shifts`.`approvedBy` AS `approvedBy`,`ca_schedules`.`shifts`.`signatureIn` AS `signatureIn`,`ca_schedules`.`shifts`.`signatureOut` AS `signatureOut` from ((`ca_schedules`.`scheduledshifts` left join `ca_schedules`.`shifts` on((`ca_schedules`.`scheduledshifts`.`id` = `ca_schedules`.`shifts`.`ScheduledShifts_id`))) join `libservices`.`users`) where (`libservices`.`users`.`id` = `ca_schedules`.`scheduledshifts`.`caID`) order by `libservices`.`users`.`username`,`ca_schedules`.`scheduledshifts`.`scheduledLocation_id`,cast(`ca_schedules`.`scheduledshifts`.`scheduledDate` as date),`ca_schedules`.`scheduledshifts`.`startTime`,`ca_schedules`.`scheduledshifts`.`caID` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `timesheetstatus_bylab`
--

/*!50001 DROP VIEW IF EXISTS `timesheetstatus_bylab`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `timesheetstatus_bylab` AS select `timesheetstatus`.`signInstatus` AS `signInstatus`,`timesheetstatus`.`scheduleID` AS `scheduleID`,`timesheetstatus`.`dayOfWeek` AS `dayOfWeek`,`timesheetstatus`.`Semester_id` AS `Semester_id`,`timesheetstatus`.`scheduled_caID` AS `scheduled_caID`,`timesheetstatus`.`username` AS `username`,`timesheetstatus`.`scheduledDate` AS `scheduledDate`,`timesheetstatus`.`scheduled_startTime` AS `scheduled_startTime`,`timesheetstatus`.`listView` AS `listView`,`timesheetstatus`.`scheduledLocation_id` AS `scheduledLocation_id`,`timesheetstatus`.`shiftID` AS `shiftID`,`timesheetstatus`.`startTime` AS `startTime`,`timesheetstatus`.`endTime` AS `endTime`,`timesheetstatus`.`shift_caID` AS `shift_caID`,`timesheetstatus`.`timeDiff` AS `timeDiff` from `ca_schedules`.`timesheetstatus` order by `timesheetstatus`.`scheduledLocation_id`,`timesheetstatus`.`scheduledDate`,`timesheetstatus`.`startTime`,`timesheetstatus`.`scheduled_caID` */;
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

-- Dump completed on 2019-06-17 13:49:59
