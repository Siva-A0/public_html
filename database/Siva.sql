-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: anu
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `achievements`
--

DROP TABLE IF EXISTS `achievements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `achievements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` tinyint NOT NULL,
  `achievement_desc` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `achievements`
--

LOCK TABLES `achievements` WRITE;
/*!40000 ALTER TABLE `achievements` DISABLE KEYS */;
INSERT INTO `achievements` VALUES (1,1,_binary '[APP:STUDENT:CERTIFICATION][ID:23x01a66a0] Roronoa D [23x01a66a0] - Issuer: nptel | Area: Deep learning - Ai for educatores$$cert_23x01a66a0_20260322182246_1479.png'),(2,1,_binary '[APP:STUDENT:ACHIEVEMENT][ID:23x01a66a0] Roronoa D [23x01a66a0] - College: Siddhartha | Theme: Hackathon - Second place$$achv_23x01a66a0_20260322182447_8293.pdf'),(3,1,_binary '[APP:FACULTY:CERTIFICATION][ID:2] Siva Tejas [Faculty ID: 2] - Issuer: nptel | Area: Deep learning - Ai for educatores$$faccert_2_20260322182532_8135.png');
/*!40000 ALTER TABLE `achievements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (1,'Added new course AI-401','2026-02-25 10:11:23'),(2,'Updated staff profile','2026-02-25 10:11:23'),(3,'Created Tech Fest Event','2026-02-25 10:11:23'),(4,'Uploaded gallery images','2026-02-25 10:11:23');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adminname` varchar(200) NOT NULL COMMENT 'user name',
  `password` varchar(255) NOT NULL COMMENT 'customer password is stored',
  `mail_id` varchar(500) NOT NULL COMMENT 'customer mail_id is stored',
  `firstname` varchar(500) NOT NULL COMMENT 'customer first name is stored',
  `lastname` varchar(500) NOT NULL COMMENT 'customer last name is stored',
  `gender` varchar(200) NOT NULL COMMENT 'gender',
  `address` varchar(255) NOT NULL COMMENT 'customer address is stored',
  `mobile_no` bigint NOT NULL COMMENT 'customers mobile no is stored',
  `qualification` varchar(200) NOT NULL COMMENT 'Qualification',
  `image` varchar(500) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'customer created date and time is stored',
  `last_access` timestamp NULL DEFAULT NULL COMMENT 'customer login time and date is stored',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`adminname`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Users details are stored';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (3,'prasad','$2y$10$O2IVtfCld/qzIBlJiF.Cxe3A1.MdeEJrafNn.KiUcOmb6kABRUtge','venkatavaraprasad12@gmail.com','gade','venkat','Male','guntur',9030114200,'btech','admin_3_20260317182800_029b3b01.png','2026-03-26 09:52:03',NULL);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alumni`
--

DROP TABLE IF EXISTS `alumni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alumni` (
  `id` int NOT NULL,
  `batch_id` int NOT NULL,
  `alumni_desc` blob NOT NULL,
  `alumni_img` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumni`
--

LOCK TABLES `alumni` WRITE;
/*!40000 ALTER TABLE `alumni` DISABLE KEYS */;
INSERT INTO `alumni` VALUES (1,2,_binary 'hai','Tulips.jpg'),(2,2,'','Penguins.jpg');
/*!40000 ALTER TABLE `alumni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_code` varchar(500) NOT NULL,
  `class_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
INSERT INTO `class` VALUES (22,'Y1-S1','1st Year AIML SEM I'),(23,'Y1-S2','1st Year AIML SEM II'),(24,'Y2-S1','2nd Year AIML SEM I'),(25,'Y2-S2','2nd Year AIML SEM II'),(26,'Y3-S1','3rd Year AIML SEM I'),(27,'Y3-S2','3rd Year AIML SEM II'),(28,'Y4-S1','4th Year AIML SEM I'),(29,'Y4-S2','4th Year AIML SEM II');
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL,
  `name` varchar(500) NOT NULL,
  `type` varchar(100) NOT NULL,
  `qualification` varchar(500) NOT NULL,
  `designation` varchar(500) NOT NULL,
  `comment` blob NOT NULL,
  `image` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,'prasad','hod','phd','hod',_binary 'Mr. Gade Venkata Vara Prasad is the Head of the Department (HOD) of Artificial Intelligence and Machine Learning (AIML) at NRCM Engineering College. He is a dedicated faculty member with strong knowledge in Artificial Intelligence, Machine Learning, and modern technologies. He works actively to guide students in academic learning, research, and practical skills. Under his leadership, the AIML department encourages innovation, technical development, and project-based learning. His support and guidance help students build strong technical and professional abilities.','ITHOD.png'),(2,'R. Lokanadham','principal','phd','principal',_binary 'Dr. R. Lokanadham is the Principal of Narsimha Reddy Engineering College. He is a dedicated academician with vast experience in engineering education and research. He is committed to improving academic standards and student development. Under his leadership, the college focuses on quality education, innovation, and discipline. His guidance motivates students to achieve technical knowledge and professional success.','principal.png'),(3,'Sri Jakkula Narsimha Reddy','chairman','phd','chariman',_binary 'Sri Jakkula Narsimha Reddy is the founder and chairman of NRCM Engineering College. He is a visionary leader who is committed to promoting quality education and academic excellence. His dedication and hard work helped in establishing the institution to provide technical education to students.','chairman.png'),(4,'Mr. Mohan Babu','director','','director',_binary 'Mr. Mohan Babu is the Director of Narsimha Reddy Engineering College. He plays an important role in the administration and development of the institution. His leadership helps the college maintain high standards in education and discipline. He supports activities that encourage student growth, innovation, and technical learning. His dedication contributes to the overall progress and success of the college.','director.png');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `committee`
--

DROP TABLE IF EXISTS `committee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `committee` (
  `id` int NOT NULL AUTO_INCREMENT,
  `committee_cat_id` int NOT NULL,
  `user_id` int NOT NULL,
  `member_name` varchar(255) NOT NULL DEFAULT '',
  `member_about` text,
  `member_image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `committee`
--

LOCK TABLES `committee` WRITE;
/*!40000 ALTER TABLE `committee` DISABLE KEYS */;
INSERT INTO `committee` VALUES (1,1,0,'Siva','jkndndjn2','committee_1772634619_7152.jpg'),(2,3,0,'giyu','calm','committee_1772633048_8340.jpg');
/*!40000 ALTER TABLE `committee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `committee_cat`
--

DROP TABLE IF EXISTS `committee_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `committee_cat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `committee_cat`
--

LOCK TABLES `committee_cat` WRITE;
/*!40000 ALTER TABLE `committee_cat` DISABLE KEYS */;
INSERT INTO `committee_cat` VALUES (1,'Chairman'),(2,'Vice Chairman'),(3,'President'),(4,'Vice-President'),(5,'Secretary'),(6,'Join-Secretary'),(7,'Tresurer'),(8,'join-Tresurer');
/*!40000 ALTER TABLE `committee_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_reg`
--

DROP TABLE IF EXISTS `event_reg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_reg` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `user_id` int NOT NULL,
  `status` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_reg`
--

LOCK TABLES `event_reg` WRITE;
/*!40000 ALTER TABLE `event_reg` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_reg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_results`
--

DROP TABLE IF EXISTS `event_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_results` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `user_id` int NOT NULL,
  `award` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_results`
--

LOCK TABLES `event_results` WRITE;
/*!40000 ALTER TABLE `event_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_types`
--

DROP TABLE IF EXISTS `event_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_type` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_event_types_name` (`event_type`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_types`
--

LOCK TABLES `event_types` WRITE;
/*!40000 ALTER TABLE `event_types` DISABLE KEYS */;
INSERT INTO `event_types` VALUES (3,'Farewell Fiesta'),(1,'MBA'),(2,'MBA Department'),(4,'Parent-Teacher Meeting');
/*!40000 ALTER TABLE `event_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_type_id` int NOT NULL,
  `event_name` varchar(500) NOT NULL,
  `event_desc` blob NOT NULL,
  `event_address` varchar(500) NOT NULL,
  `event_date` date NOT NULL,
  `reg_frm_date` date NOT NULL,
  `reg_to_date` date NOT NULL,
  `is_registration` tinyint NOT NULL,
  `is_public` tinyint NOT NULL DEFAULT '1' COMMENT 'controls public events visibility',
  `show_in_gallery` tinyint NOT NULL DEFAULT '1' COMMENT 'controls gallery visibility',
  PRIMARY KEY (`id`),
  KEY `idx_events_type_date` (`event_type_id`,`event_date`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (5,3,'Farewell Celebration - 2026',_binary 'As the academic year draws to a close, we come together to celebrate a significant milestone — the graduation of our remarkable Class of 2026. This farewell event is a heartfelt tribute to their journey of growth, learning, and camaraderie forged within our campus. \r\n\r\nFrom late-night study sessions to spirited cultural festivals, from classroom breakthroughs to field triumphs, our graduates have left an indelible mark on college life. They have not only excelled academically but have also led with passion, inspired peers, and embodied the spirit of excellence and integrity.\r\n\r\nWe honor their achievements, cherish the memories shared, and express our deepest gratitude to the faculty, families, and friends who have supported them. As they step beyond our gates into a world of new possibilities, we send them off with pride, hope, and unwavering encouragement. \r\n\r\nThis is not a goodbye, but a celebration of beginnings. To our graduating students: may your futures be bright, bold, and boundless.','SUGUNAVATHI AMPHITHEATRE','2026-04-04','2026-04-04','2026-04-04',0,1,1),(6,4,'PTM (Parent-Teacher Meeting)',_binary 'A formal and collaborative session where college faculty and parents discuss a student’s academic progress, attendance, behavior, and overall development. It fosters strong home-institution communication to support student success.','M.T BLOCK(SEMINAR HALL)','2026-04-12','2026-04-12','2026-04-12',0,1,1);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculty`
--

DROP TABLE IF EXISTS `faculty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faculty` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_categ_id` int NOT NULL,
  `first_name` varchar(500) NOT NULL,
  `last_name` varchar(500) NOT NULL,
  `qualification` varchar(500) NOT NULL,
  `designation` varchar(500) NOT NULL,
  `industry_exp` varchar(500) NOT NULL,
  `teach_exp` varchar(500) NOT NULL,
  `research` varchar(500) NOT NULL,
  `publ_national` blob NOT NULL COMMENT 'national wise publications',
  `publ_international` blob NOT NULL COMMENT 'inter-national wise publications',
  `conf_national` blob NOT NULL COMMENT 'national wise conferences',
  `conf_international` blob NOT NULL COMMENT 'inter-national wise conferences',
  `e_mail` varchar(500) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty`
--

LOCK TABLES `faculty` WRITE;
/*!40000 ALTER TABLE `faculty` DISABLE KEYS */;
INSERT INTO `faculty` VALUES (1,1,'Sami','ahmed','btech','python lecturer','0','9','','','','','','samiahmed5963@gmail.com','$2y$10$BwiNGVNX7TsjPPwzMVyXVOi4rwqWMlowvV7WMYPP8rFG5mGQ7JXaO','');
/*!40000 ALTER TABLE `faculty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculty_category`
--

DROP TABLE IF EXISTS `faculty_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faculty_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty_category`
--

LOCK TABLES `faculty_category` WRITE;
/*!40000 ALTER TABLE `faculty_category` DISABLE KEYS */;
INSERT INTO `faculty_category` VALUES (1,'Faculty');
/*!40000 ALTER TABLE `faculty_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_id` int NOT NULL,
  `name` varchar(500) NOT NULL,
  `description` blob NOT NULL,
  `image_name` varchar(500) NOT NULL,
  `category_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES (1,1,'Luffy',_binary 'king of the pirates','luffy.jpg',3),(6,3,'tg',_binary 'n k','tg_20260312071832_56bb6ff4.jpg',4),(8,0,'Luffy',_binary 'iajascicjasa','luffy_20260312145000_11d102e3.jpg',5),(9,0,'chiller','','chiller_20260312145023_2a4c4924.jpg',5),(10,0,'vvv','','vvv_20260312145042_c1045ac1.jpg',5),(11,0,'momo','','momo_20260312145103_21399528.jpg',5),(12,0,'chiller','','chiller_20260312145121_c07caf3d.jpg',5),(13,0,'momo','','momo_20260312145135_bf823017.jpg',5);
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_category`
--

DROP TABLE IF EXISTS `gallery_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(500) NOT NULL,
  `linked_event_id` int DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_category`
--

LOCK TABLES `gallery_category` WRITE;
/*!40000 ALTER TABLE `gallery_category` DISABLE KEYS */;
INSERT INTO `gallery_category` VALUES (3,'bkh',1,3,1),(4,'Free Fire',3,2,1),(5,'Sample',NULL,1,1);
/*!40000 ALTER TABLE `gallery_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `highlights`
--

DROP TABLE IF EXISTS `highlights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `highlights` (
  `id` int NOT NULL,
  `type` int NOT NULL,
  `high_light` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `highlights`
--

LOCK TABLES `highlights` WRITE;
/*!40000 ALTER TABLE `highlights` DISABLE KEYS */;
INSERT INTO `highlights` VALUES (14,2,_binary 'Hai The Matter About Department Events'),(15,1,_binary 'Aiml is the top');
/*!40000 ALTER TABLE `highlights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sub_id` varchar(500) NOT NULL,
  `material_name` varchar(500) NOT NULL,
  `mater_file` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (2,'32','Befa','BEFA UNIT-1 AIML.pptx');
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(45) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token_hash` (`token_hash`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES (1,79,'f7f061d9ea84ff55359eb23851140f50e9079fe5e92dbe0cf5c72f34a5db007f','2026-03-16 07:43:58',NULL,'2026-03-16 06:43:58','103.51.55.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0'),(2,77,'ef526e6163f29fe49c888b750c6e0d2fce197af48a5500a832bb0d7ad3c1a74c','2026-03-16 07:45:32',NULL,'2026-03-16 06:45:32','103.51.55.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36'),(3,77,'3b5d4de0167d648b573d5e74381afd0c181c58378fe9ce5d040cfc538aa2c858','2026-03-16 07:48:46','2026-03-16 06:49:46','2026-03-16 06:48:46','103.51.55.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36'),(4,79,'48bcee19c84b4c48dc10bb501ed33de438dbb75e222f6ebbb8647e046699f3e3','2026-03-16 07:57:34',NULL,'2026-03-16 06:57:34','103.51.55.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0'),(5,79,'af3e4b8e091e0bd180b6d3f1dd6a35b137edce0f22de5245d87ba2ac3385bf32','2026-03-16 07:58:49',NULL,'2026-03-16 06:58:49','103.51.55.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0'),(6,77,'706323938b30bd3120303d01f760152e038f02777267b8bb0a0969926c300670','2026-03-16 07:58:50',NULL,'2026-03-16 06:58:50','103.51.55.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36'),(7,77,'2dbd4a682eb392c7a0939a48f37bad8424f6f7d38f127e88539afa31c275ac49','2026-03-16 08:02:20','2026-03-16 07:03:31','2026-03-16 07:02:20','103.51.55.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `placement_stats`
--

DROP TABLE IF EXISTS `placement_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `placement_stats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `students_placed` varchar(50) NOT NULL DEFAULT '',
  `companies_visited` varchar(50) NOT NULL DEFAULT '',
  `highest_package` varchar(50) NOT NULL DEFAULT '',
  `average_package` varchar(50) NOT NULL DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `placement_stats`
--

LOCK TABLES `placement_stats` WRITE;
/*!40000 ALTER TABLE `placement_stats` DISABLE KEYS */;
INSERT INTO `placement_stats` VALUES (1,'0','0','','','2026-03-25 06:39:47');
/*!40000 ALTER TABLE `placement_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `placements`
--

DROP TABLE IF EXISTS `placements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `placements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` tinyint NOT NULL,
  `placement_desc` blob NOT NULL,
  `academic_year` varchar(100) NOT NULL DEFAULT '',
  `batch_label` varchar(60) NOT NULL DEFAULT '',
  `student_name` varchar(255) NOT NULL DEFAULT '',
  `course_branch` varchar(255) NOT NULL DEFAULT '',
  `company_name` varchar(255) NOT NULL DEFAULT '',
  `role_title` varchar(255) NOT NULL DEFAULT '',
  `package_label` varchar(100) NOT NULL DEFAULT '',
  `package_sort` decimal(10,2) DEFAULT NULL,
  `profile_photo` varchar(255) NOT NULL DEFAULT '',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_placements_category_batch` (`category_id`,`batch_label`),
  KEY `idx_placements_category_year` (`category_id`,`academic_year`),
  KEY `idx_placements_student_name` (`student_name`),
  KEY `idx_placements_company_name` (`company_name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `placements`
--

LOCK TABLES `placements` WRITE;
/*!40000 ALTER TABLE `placements` DISABLE KEYS */;
INSERT INTO `placements` VALUES (5,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"Prabhakar Chaubey\",\"course_branch\":\"AIML\",\"company_name\":\"LUMEN Technologies\",\"role_title\":\"\",\"package_label\":\"7.4 LPA\",\"package_sort\":null,\"profile_photo\":\"placement_prabhakar_chaubey_20260325103745_cf8ee6e2.jpg\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Prabhakar Chaubey placed at LUMEN Technologies\"}','2025-26','2026','Prabhakar Chaubey','AIML','LUMEN Technologies','','7.4 LPA',NULL,'placement_prabhakar_chaubey_20260325103745_cf8ee6e2.jpg',1,0,1,'2026-03-25 09:02:17','2026-03-25 09:37:45'),(6,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"Samala Sindhu Priya\",\"course_branch\":\"AIML\",\"company_name\":\"LUMEN Technologies\",\"role_title\":\"\",\"package_label\":\"7.4 LPA\",\"package_sort\":null,\"profile_photo\":\"placement_samala_sindhu_priya_20260325103807_e6941b91.jpg\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Samala Sindhu Priya placed at LUMEN Technologies\"}','2025-26','2026','Samala Sindhu Priya','AIML','LUMEN Technologies','','7.4 LPA',NULL,'placement_samala_sindhu_priya_20260325103807_e6941b91.jpg',1,0,1,'2026-03-25 09:03:11','2026-03-25 09:39:24'),(7,3,_binary 'json::{\"academic_year\":\"2024-25\",\"batch_label\":\"2025\",\"student_name\":\"V. Sai Prakash\",\"course_branch\":\"AIML\",\"company_name\":\"Virtusa\",\"role_title\":\"\",\"package_label\":\"3.4 LPA\",\"package_sort\":null,\"profile_photo\":\"\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"V. Sai Prakash placed at Virtusa\"}','2024-25','2025','V. Sai Prakash','AIML','Virtusa','','3.4 LPA',NULL,'',1,0,1,'2026-03-25 09:06:12','2026-03-30 08:41:01'),(9,3,_binary 'json::{\"academic_year\":\"2024-25\",\"batch_label\":\"2025\",\"student_name\":\"G. Samyutha\",\"course_branch\":\"AIML\",\"company_name\":\"Virtusa\",\"role_title\":\"\",\"package_label\":\"3.4 LPA\",\"package_sort\":null,\"profile_photo\":\"\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"G. Samyutha placed at Virtusa\"}','2024-25','2025','G. Samyutha','AIML','Virtusa','','3.4 LPA',NULL,'',1,0,1,'2026-03-25 09:07:23','2026-03-30 08:40:37'),(10,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"S.Rohitha Reddy\",\"course_branch\":\"AIML\",\"company_name\":\"Nexer\",\"role_title\":\"\",\"package_label\":\"4.5 LPA\",\"package_sort\":null,\"profile_photo\":\"placement_srohitha_reddy_20260325103644_87cfbd4e.jpg\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"S.Rohitha Reddy placed at Nexer\"}','2025-26','2026','S.Rohitha Reddy','AIML','Nexer','','4.5 LPA',NULL,'placement_srohitha_reddy_20260325103644_87cfbd4e.jpg',1,0,1,'2026-03-25 09:09:15','2026-03-25 09:36:44'),(11,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"Rukshith Anganthi\",\"course_branch\":\"AIML\",\"company_name\":\"Nexer\",\"role_title\":\"\",\"package_label\":\"4.5 LPA\",\"package_sort\":null,\"profile_photo\":\"\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Rukshith Anganthi placed at Nexer\"}','2025-26','2026','Rukshith Anganthi','AIML','Nexer','','4.5 LPA',NULL,'',1,0,1,'2026-03-25 09:13:00','2026-03-25 09:13:00'),(12,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"Kappala Harshitha\",\"course_branch\":\"AIML\",\"company_name\":\"Nexer\",\"role_title\":\"\",\"package_label\":\"4.5 LPA\",\"package_sort\":null,\"profile_photo\":\"placement_kappala_harshitha_20260325103613_986f557c.jpg\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Kappala Harshitha placed at Nexer\"}','2025-26','2026','Kappala Harshitha','AIML','Nexer','','4.5 LPA',NULL,'placement_kappala_harshitha_20260325103613_986f557c.jpg',1,0,1,'2026-03-25 09:15:03','2026-03-25 09:36:13'),(13,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"Nallagasu Anusha\",\"course_branch\":\"AIML\",\"company_name\":\"invoice cloud\",\"role_title\":\"\",\"package_label\":\"9 LPA\",\"package_sort\":null,\"profile_photo\":\"placement_nallagasu_anusha_20260325103550_a50cb731.jpg\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Nallagasu Anusha placed at invoice cloud\"}','2025-26','2026','Nallagasu Anusha','AIML','invoice cloud','','9 LPA',NULL,'placement_nallagasu_anusha_20260325103550_a50cb731.jpg',1,0,1,'2026-03-25 09:19:00','2026-03-25 09:35:50'),(14,3,_binary 'json::{\"academic_year\":\"2024-25\",\"batch_label\":\"2025\",\"student_name\":\"Utkarsh Mishra\",\"course_branch\":\"AIML\",\"company_name\":\"Catalog\",\"role_title\":\"\",\"package_label\":\"25 LPA\",\"package_sort\":null,\"profile_photo\":\"\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Utkarsh Mishra placed at Catalog\"}','2024-25','2025','Utkarsh Mishra','AIML','Catalog','','25 LPA',NULL,'',1,0,1,'2026-03-25 09:24:15','2026-03-25 09:24:15'),(15,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"Puskuri Sai Krishna\",\"course_branch\":\"AIML\",\"company_name\":\"B.D.O\",\"role_title\":\"\",\"package_label\":\"6.0 LPA\",\"package_sort\":null,\"profile_photo\":\"\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Puskuri Sai Krishna placed at B.D.O\"}','2025-26','2026','Puskuri Sai Krishna','AIML','B.D.O','','6.0 LPA',NULL,'',1,0,1,'2026-03-30 08:46:48','2026-03-30 08:46:48'),(16,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"Allam Akshitha\",\"course_branch\":\"AIML\",\"company_name\":\"Nexer\",\"role_title\":\"\",\"package_label\":\"4.5 LPA\",\"package_sort\":null,\"profile_photo\":\"\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Allam Akshitha placed at Nexer\"}','2025-26','2026','Allam Akshitha','AIML','Nexer','','4.5 LPA',NULL,'',1,0,1,'2026-03-30 09:30:34','2026-03-30 09:30:34'),(17,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"Gorige ChandraShekar\",\"course_branch\":\"AIML\",\"company_name\":\"TCS\",\"role_title\":\"\",\"package_label\":\"6.0 LPA\",\"package_sort\":null,\"profile_photo\":\"\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Gorige ChandraShekar placed at TCS\"}','2025-26','2026','Gorige ChandraShekar','AIML','TCS','','6.0 LPA',NULL,'',1,0,1,'2026-03-30 09:32:10','2026-03-30 09:32:10'),(18,3,_binary 'json::{\"academic_year\":\"2025-26\",\"batch_label\":\"2026\",\"student_name\":\"Lakshetti Nithin\",\"course_branch\":\"AIML\",\"company_name\":\"Caliber\",\"role_title\":\"\",\"package_label\":\"6.0 LPA\",\"package_sort\":null,\"profile_photo\":\"\",\"is_featured\":1,\"sort_order\":0,\"is_active\":1,\"placement_desc\":\"Lakshetti Nithin placed at Caliber\"}','2025-26','2026','Lakshetti Nithin','AIML','Caliber','','6.0 LPA',NULL,'',1,0,1,'2026-03-30 09:33:20','2026-03-30 09:33:20');
/*!40000 ALTER TABLE `placements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prev_papers`
--

DROP TABLE IF EXISTS `prev_papers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prev_papers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subj_id` int NOT NULL,
  `paper_name` varchar(500) NOT NULL,
  `paper_file` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prev_papers`
--

LOCK TABLES `prev_papers` WRITE;
/*!40000 ALTER TABLE `prev_papers` DISABLE KEYS */;
INSERT INTO `prev_papers` VALUES (1,32,'Befa-mid','BEFA IMP MID AUG 2025.pdf');
/*!40000 ALTER TABLE `prev_papers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `section` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `section_code` varchar(500) NOT NULL,
  `section_name` varchar(500) NOT NULL,
  `batch_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
INSERT INTO `section` VALUES (1,1,'IV IT A SEC ','4th IT A Section',0),(2,2,'III IT A Sec','3rd IT A Sec',0),(3,3,'II IT A Sec','2nd IT A Section',0),(4,3,'II IT B Sec','2nd IT B Section',0),(5,4,'I IT A Sec','1st IT A Section',0),(7,27,'A','Section-A',8),(8,27,'B','Section-B',8),(9,27,'C','Section-C',8),(11,30,'PASSOUT','Passed Out',8),(12,30,'PASSOUT-A','Passed Out - A',9),(13,30,'PASSOUT-C','Passed Out - C',8),(14,30,'PASSOUT-B','Passed Out - B',8),(15,31,'A','Section-A',13),(16,31,'B','Section-B',12),(17,31,'C','Section-C',13),(18,22,'A','Section-A',13),(19,29,'A','Section-A',10),(20,29,'A','Section-A',12);
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stream`
--

DROP TABLE IF EXISTS `stream`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stream` (
  `id` int NOT NULL AUTO_INCREMENT,
  `stream_code` varchar(500) NOT NULL,
  `stream_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stream`
--

LOCK TABLES `stream` WRITE;
/*!40000 ALTER TABLE `stream` DISABLE KEYS */;
INSERT INTO `stream` VALUES (1,'IT','Information Technology'),(2,'CSE','Computer science & Enginering'),(5,'ECE','Electronics and Communication Engineering'),(6,'EEE','Electronics and Electrical Engineering'),(7,'Other','Any Other Branch'),(13,'CSM','CSM'),(14,'3','Year 3');
/*!40000 ALTER TABLE `stream` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL COMMENT 'user name',
  `password` varchar(255) NOT NULL COMMENT 'user password is stored',
  `mail_id` varchar(500) NOT NULL COMMENT 'user mail_id is stored',
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `batch_id` int NOT NULL,
  `stream_id` int NOT NULL,
  `section` varchar(10) DEFAULT NULL COMMENT 'section',
  `admission_id` varchar(300) NOT NULL COMMENT 'Admission Id',
  `image` varchar(500) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'customer created date and time is stored',
  `last_access` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '0',
  `is_alumni` tinyint(1) NOT NULL DEFAULT '0',
  `alumni_original_section_id` int DEFAULT NULL,
  `alumni_original_section_label` varchar(20) DEFAULT NULL,
  `alumni_graduated_on` date DEFAULT NULL,
  `user_type` enum('student','alumni') NOT NULL DEFAULT 'student',
  `passout_year` year DEFAULT NULL,
  `role` enum('student','alumni','admin') NOT NULL DEFAULT 'student',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`mail_id`),
  UNIQUE KEY `unique_admission` (`admission_id`),
  KEY `idx_users_is_alumni` (`is_alumni`),
  KEY `idx_users_user_type` (`user_type`),
  KEY `idx_users_passout_year` (`passout_year`),
  KEY `idx_users_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=latin1 COMMENT='Users details are stored';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'23X01A6601','$2y$10$iQzZKJDK2Wz0b7ajNV9qHOlMsYllZDAeCN390CxdNvUKQXisW36Xm','23X01A6601@nrcmec.org','AAKULA','JASHWANTHSAIMUDIRAJ','Male','395B 1st floor sri vivekananda nagar','8008208084',8,0,'7','23X01A6601','','2026-03-30 06:03:29','2026-03-30 06:03:29',1,0,NULL,NULL,NULL,'student',NULL,'student'),(2,'23X01A6602','$2y$10$rBkCxTkKib7gcToh/BPRx.CrIuaC7jmYQ..cXJHIOYsd.7jWpwCaO','23X01A6602@nrcmec.org','Karteek','Aavula','Male','Hyderabad','9491831420',8,0,'7','23X01A6602','','2026-03-30 06:03:30','2026-03-30 06:03:30',1,0,NULL,NULL,NULL,'student',NULL,'student'),(3,'23X01A6607','$2y$10$MBd16Ge4PCSsg/ID9t4wDOWP0hBjApZLZMQkDM65rPxFSnF9lCuDm','23X01A6607@nrcmec.org','Avanigadda','Shashank','Male','8-3162/A/51, Sanjaya nagar, yousufguda','9100892799',8,0,'7','23X01A6607','','2026-03-30 06:03:30','2026-03-30 06:03:30',1,0,NULL,NULL,NULL,'student',NULL,'student'),(4,'23X01A6608','$2y$10$bN1typK/UKabVDudc.8yB.NfJiTXGqhYKaQy/uqZ145oj.caOEX3S','23X01A6608@nrcmec.org','Avidi','Harish','Male','Plot no : 44, JS Residency, madinaguda','6303694922',8,0,'7','23X01A6608','','2026-03-30 06:03:30','2026-03-30 06:03:30',1,0,NULL,NULL,NULL,'student',NULL,'student'),(5,'23X01A6611','$2y$10$DJwt3.NGjoBb4e151YynFO2TykNS0m9Rp7z4JrPVXMH2Rknx4anq2','23X01A6611@nrcmec.org','Badavath','Sai Harshith','Male','venkatapuram alwal','9398003863',8,0,'7','23X01A6611','','2026-03-30 06:03:30','2026-03-30 06:03:30',1,0,NULL,NULL,NULL,'student',NULL,'student'),(6,'23X01A6614','$2y$10$yo1UgBnsUcqD3ap0rIpxpe115ZWGuUN.ZSNP61/lpsjgB7QTVib06','23X01A6614@nrcmec.org','Bandi','Sreenidhi','Female','Mig-2-225 , 9th phase, kphb, Kukatpally, Hyderabad','6303934659',8,0,'7','23X01A6614','','2026-03-30 06:03:30','2026-03-30 06:03:30',1,0,NULL,NULL,NULL,'student',NULL,'student'),(7,'23X01A6616','$2y$10$q.DFKLKHLvUPv6EHAfQPTuJ4zc/6przxoh0PMfOCVz/uzUB/ceICG','23X01A6616@nrcmec.org','Bathula','Rohith','Male','1-8-518/I2-SF-1 NBT NAGAR PATIGADDA','6304297674',8,0,'7','23X01A6616','','2026-03-30 06:03:31','2026-03-30 06:03:31',1,0,NULL,NULL,NULL,'student',NULL,'student'),(8,'23X01A6618','$2y$10$JoHXxtHPPw/Thp17vMjKOu0EEjl4lFF2Qs.rU2pjukWyaH/B5JMda','23X01A6618@nrcmec.org','Bodla','Navya','Female','Medchal','9502695292',8,0,'7','23X01A6618','','2026-03-30 06:03:31','2026-03-30 06:03:31',1,0,NULL,NULL,NULL,'student',NULL,'student'),(9,'23X01A6622','$2y$10$OEVB7zlNjSIkoRSDldfpmOQtl0rt7KnwXNRJQBN1ZLeHfk1zyqjpi','23X01A6622@nrcmec.org','Burra','Rahul','Male','Karimnagar, Krishna nagar, Housing Board.','8008819203',8,0,'7','23X01A6622','','2026-03-30 06:03:31','2026-03-30 06:03:31',1,0,NULL,NULL,NULL,'student',NULL,'student'),(10,'23X01A6623','$2y$10$ETcBaK4QaAEfSSdoxCo9HOdVV/FKKZUyxv1a741yrqThI546seB.S','23X01A6623@nrcmec.org','Chalamalla Vaishnav','Kumar Reddy','Male','Hno 2-2-221/55 lane2 venkanna enclave Ganesh nagar macha bollaram','8309038016',8,0,'7','23X01A6623','','2026-03-30 06:03:31','2026-03-30 06:03:31',1,0,NULL,NULL,NULL,'student',NULL,'student'),(11,'23X01A6626','$2y$10$nHVqDbZzFzgOcHDBza60KeW629zpBO6pqZzBKpdD30RHaUzMvYLea','23X01A6626@nrcmec.org','Cheruku','Prashanth','Male','Vemulawada, lingampally','9666554687',8,0,'7','23X01A6626','','2026-03-30 06:03:32','2026-03-30 06:03:32',1,0,NULL,NULL,NULL,'student',NULL,'student'),(12,'23X01A6628','$2y$10$go/SKaQqxOiJA2VsIM1mIu8eYNjJ./cyQJBEc4QwRakIVxkpaz5Vy','23X01A6628@nrcmec.org','Chiluka Harshith','Reddy','Male','7-1-38/1/52, D.J.R. Nagar, Hyderabad, District: Hyderabad, State: Andhra Pradesh, PIN Code: 500016','9866895566',8,0,'7','23X01A6628','','2026-03-30 06:03:32','2026-03-30 06:03:32',1,0,NULL,NULL,NULL,'student',NULL,'student'),(13,'23X01A6630','$2y$10$mSDWHoIYAOmOIUG6R/NDauCNeGSVViy7wLkqQInrsuEgDT/UxI1lm','23X01A6630@nrcmec.org','Chinnavar','Shivakumar','Male','Lokeshwaram dis: Nirmal','7981602616',8,0,'7','23X01A6630','','2026-03-30 06:03:32','2026-03-30 06:03:32',1,0,NULL,NULL,NULL,'student',NULL,'student'),(14,'23X01A6634','$2y$10$6vnC4zkJFLUuYivuj3kV8eIjSgy3s4/zrWGMJaADfS4ISV1UZafie','23X01A6634@nrcmec.org','Chunduri','Kiran Sai Chowdary','Male','Flat no 301 sri sai baba nilayam , venkateshwara colony, sagar tailors,Hyderabad,500067','9704653689',8,0,'7','23X01A6634','','2026-03-30 06:03:32','2026-03-30 06:03:32',1,0,NULL,NULL,NULL,'student',NULL,'student'),(15,'23X01A6636','$2y$10$4LAO1d3qVQCoiHI05yoHZOZTTFOv2e5IwL4YLUb4VgNQX6ohAsM8e','23X01A6636@nrcmec.org','Devarakonda','Avinash','Male','8-3-228/678/1607 , Sriram Nagar, yousufguda , Hyderabad','8074936317',8,0,'7','23X01A6636','','2026-03-30 06:03:32','2026-03-30 06:03:32',1,0,NULL,NULL,NULL,'student',NULL,'student'),(16,'23X01A6637','$2y$10$1UKxbps8CNlt5NW5hfoz8eIEKxJ06oXOU.Bnw64jJw6o52rhRttiG','23X01A6637@nrcmec.org','Rithwik','Ram','Male','Hyderabad','9505089719',8,0,'7','23X01A6637','','2026-03-30 06:03:33','2026-03-30 06:03:33',1,0,NULL,NULL,NULL,'student',NULL,'student'),(17,'23X01A6638','$2y$10$seaY6MqmhvSHw20R90LOUO0tZsliwav061rYyKwh3w.lCh/nsUUJ6','23X01A6638@nrcmec.org','SAI','KIRAN','Male','21-613/7/2, Shivalaya nagar , jeedimetla, Hyderabad 500055','6302135667',8,0,'7','23X01A6638','','2026-03-30 06:03:33','2026-03-30 06:03:33',1,0,NULL,NULL,NULL,'student',NULL,'student'),(18,'23X01A6640','$2y$10$FYBm1nG8cqHaZcWzryYKeOY2IXtBV6ZoOXtJXNlrt/fc.5EdcFDiO','23X01A6640@nrcmec.org','Endluri','Nikitha','Female','10-86/3/B Vinayaka nagar Balanagar','9381505192',8,0,'7','23X01A6640','','2026-03-30 06:03:33','2026-03-30 06:03:33',1,0,NULL,NULL,NULL,'student',NULL,'student'),(19,'23X01A6641','$2y$10$jOdQfusRJnZNMEdsB7zWxO3lyqjIHB0LvXHE5aFZmV31bjJuytnvm','23X01A6641@nrcmec.org','Evuri','Kavya','Female','1-c82/2 pathuru mundlapadu ntr andhrapradesh','6303419138',8,0,'7','23X01A6641','','2026-03-30 06:03:33','2026-03-30 06:03:33',1,0,NULL,NULL,NULL,'student',NULL,'student'),(20,'23X01A6644','$2y$10$RHaLGizWsOSy49YMBwW2lu1vrlERLsvXNqdZbbNArWS7jHgxEvAfW','23X01A6644@nrcmec.org','Gandla','Akshara','Female','7-5-48 Nagarjuna colony backside hanuman temple balanagar','6302224892',8,0,'7','23X01A6644','','2026-03-30 06:03:33','2026-03-30 06:03:33',1,0,NULL,NULL,NULL,'student',NULL,'student'),(21,'23X01A6654','$2y$10$OCAdhRg0Bdg3WrCAOIU0OupTNLK8d5fDAVPATX86ZAQ9QiCAnG0ne','23X01A6654@nrcmec.org','Guguloth','Eswar','Male','Maisammaguda, Dhulapally, 500100','9392729921',8,0,'7','23X01A6654','','2026-03-30 06:03:34','2026-03-30 06:03:34',1,0,NULL,NULL,NULL,'student',NULL,'student'),(22,'23X01A6659','$2y$10$kvVBnsY8FSe2.1dlV0897ejoOWK0fBw8demb3qvRXTyaa23QtZTlC','23X01A6659@nrcmec.org','Jarupula','Vamshi','Male','Maisammaguda near medchal','6304912870',8,0,'7','23X01A6659','','2026-03-30 06:03:34','2026-03-30 06:03:34',1,0,NULL,NULL,NULL,'student',NULL,'student'),(23,'23X01A6661','$2y$10$eLpLkP7ccWrqaKlTHeUyfO4PAWqKknByH5eZLDQFyAeDv4KMyh8ba','23X01A6661@nrcmec.org','Deepika Sai','Kalla','Female','Medchal','8897306768',8,0,'7','23X01A6661','','2026-03-30 06:03:34','2026-03-30 06:03:34',1,0,NULL,NULL,NULL,'student',NULL,'student'),(24,'23X01A6664','$2y$10$QaNkeXGDOfHwn7qRNt9mNOvl3P53y2a.8fPkRAX2RC8MawyA13Gc6','23X01A6664@nrcmec.org','KASARLA','RAKSHITHA','Female','Secunderabad, Hyderabad,India','8008347268',8,0,'7','23X01A6664','','2026-03-30 06:03:34','2026-03-30 06:03:34',1,0,NULL,NULL,NULL,'student',NULL,'student'),(25,'23X01Q6632','$2y$10$if1AhNQxHHLDY6h/pM0NV.qnpNYKTvs4tfIDgI/vzRtOYofUhfpw2','23X01Q6632@nrcmec.org','Chinthalapally','Sathwika','Female','H.N: 11-64 Mandal: Mallpur ,District: Jagityal ,Village: Chittapur','9618057752',8,0,'7','23X01Q6632','','2026-03-30 06:03:34','2026-03-30 06:03:34',1,0,NULL,NULL,NULL,'student',NULL,'student'),(26,'23X01A6665','$2y$10$30tEDMaZYWz5823Sk5ZtEuj5M7ar2/xultfB5eukZGXerGqIwUyte','23X01A6665@nrcmec.org','Kesaboyina','Santhosh','Male','Kphb colony road no 3','6301081924',8,0,'8','23X01A6665','','2026-03-30 06:03:35','2026-03-30 06:03:35',1,0,NULL,NULL,NULL,'student',NULL,'student'),(27,'23X01A6666','$2y$10$6wVcifV/i63DIoBKWvqAp.ZU5U6Ip3G2/zhxQksyuSNlWfDHBgk5y','23X01A6666@nrcmec.org','K','Venky','Male','Bachupally','7386171403',8,0,'8','23X01A6666','','2026-03-30 06:03:35','2026-03-30 06:03:35',1,0,NULL,NULL,NULL,'student',NULL,'student'),(28,'23X01A6667','$2y$10$qLTP.aEqwNkTtiWiBpPEiO3d38xsAlXP2a7q7j94SEvkmKqsuekbS','23X01A6667@nrcmec.org','Kilaru','Jathin reddy','Male','Maisammaguda,doolapally,Kompally,Hyderabad','8688064533',8,0,'8','23X01A6667','','2026-03-30 06:03:35','2026-03-30 06:03:35',1,0,NULL,NULL,NULL,'student',NULL,'student'),(29,'23X01A6668','$2y$10$p7G1Hr5Q3VJ6/wAewtge4.Did9cr3hzeOpZnSrYY44fC2RkdXNjCC','23X01A6668@nrcmec.org','Killi','Himanth','Male','A.v.b puram Kukatpally Hyderabad','9063495342',8,0,'8','23X01A6668','','2026-03-30 06:03:35','2026-03-30 06:03:35',1,0,NULL,NULL,NULL,'student',NULL,'student'),(30,'23X01A6671','$2y$10$C6O5a23YQQP4xICvS2D4ZuWfPIXyy8e9Dd3rI/n22RgvCIBaC1nPm','23X01A6671@nrcmec.org','Komati','Niharika','Female','Suraram colony','8919511294',8,0,'8','23X01A6671','','2026-03-30 06:03:35','2026-03-30 06:03:35',1,0,NULL,NULL,NULL,'student',NULL,'student'),(31,'23X01A6672','$2y$10$lE0Uwvpk3KW9f8W8YuoicerWiawynWtp6iysLG6rUck5xfA3Wu65m','23X01A6672@nrcmec.org','Yamuna','Koninti','Female','Hyderabad','8309762236',8,0,'8','23X01A6672','','2026-03-30 06:03:36','2026-03-30 06:03:36',1,0,NULL,NULL,NULL,'student',NULL,'student'),(32,'23X01A6673','$2y$10$Wx4M8M4K9JI3pQXcU4Z/XO/hcVKVceWFunfImT2Id3Q2bN2.4ZIrm','23X01A6673@nrcmec.org','Kota','Varshitha','Female','Sai ram colony metapally','9951510223',8,0,'8','23X01A6673','','2026-03-30 06:03:36','2026-03-30 06:03:36',1,0,NULL,NULL,NULL,'student',NULL,'student'),(33,'23X01A6678','$2y$10$vw3pOIz/fBsGd0MRCfapxudZ7zN6my48U7hQNgKFsluo6.0GmhFcu','23X01A6678@nrcmec.org','Mahammad Sami','Ahmed','Male','Nagole','6304450890',8,0,'8','23X01A6678','','2026-03-30 06:03:36','2026-03-30 06:03:36',1,0,NULL,NULL,NULL,'student',NULL,'student'),(34,'23X01A6680','$2y$10$73FfBvMSp/x/RI1cSZKAIuszb35Bkfr.y1DSPr5OxbU3w.X1M8bZC','23X01A6680@nrcmec.org','Makkala','Madhuri','Female','HNO:6-100/4,Subhash Nagar,Gangaram,Chanda Nagar,Hyderabad','7675934168',8,0,'8','23X01A6680','','2026-03-30 06:03:36','2026-03-30 06:03:36',1,0,NULL,NULL,NULL,'student',NULL,'student'),(35,'23X01A6681','$2y$10$1sKydbx0f7/ktMSh69hf6.Hn/Vw1GhwpyeT8Kim.jpramTvPV3arO','23X01A6681@nrcmec.org','Hema Manikanta','Mangalagiri','Male','DEENABUNDHU COLONY ROAD NO 7 4-38-143 jagathgiri gutta Kukatpally Hyderabad','7013352846',8,0,'8','23X01A6681','','2026-03-30 06:03:36','2026-03-30 06:03:36',1,0,NULL,NULL,NULL,'student',NULL,'student'),(36,'23X01A6682','$2y$10$JfKIpnF405X9PRCfL9tgBOKg7zFzeeJOK.7shTJpMz/3afAAcLJOu','23X01A6682@nrcmec.org','Mangamudi','Sony Harshitha','Female','Nandigama bypass road madhira, khammam','9392817649',8,0,'8','23X01A6682','','2026-03-30 06:03:37','2026-03-30 06:03:37',1,0,NULL,NULL,NULL,'student',NULL,'student'),(37,'23X01A6683','$2y$10$ufvjKNNn68H3rWDyEC5vauXZkIOAXnWMFAAw3x1jAJfKSZBWAijfe','23X01A6683@nrcmec.org','Kranthi','Patel','Male','Jagtial','6309744645',8,0,'8','23X01A6683','','2026-03-30 06:03:37','2026-03-30 06:03:37',1,0,NULL,NULL,NULL,'student',NULL,'student'),(38,'23X01A6687','$2y$10$U2XIsYmflFbkk5IQ0xkqxukUhR2wBp8NZ0AoSXJqujJLYQeIZIt2S','23X01A6687@nrcmec.org','Mohammed','Sohail','Male','8-3-169/60/1378/A/6 T anjaya nagar SPR Hills','9290411122',8,0,'8','23X01A6687','','2026-03-30 06:03:37','2026-03-30 06:03:37',1,0,NULL,NULL,NULL,'student',NULL,'student'),(39,'23X01A6688','$2y$10$VFiJ0LbX0DMLja5xicgm3eZYyg7Z1qBOXRIfx0tHLcOAvoQGys0ca','23X01A6688@nrcmec.org','Mukkamula','Vishnuvardhan','Male','Kattangur','9398030136',8,0,'8','23X01A6688','','2026-03-30 06:03:37','2026-03-30 06:03:37',1,0,NULL,NULL,NULL,'student',NULL,'student'),(40,'23X01A6689','$2y$10$X9D7VD8jWpDi2hp3auTjdeEMuPCeH8KXOXA5Iv.vU02gqI.CqWurC','23X01A6689@nrcmec.org','Mundru','Jagadeesh kumar','Male','Gandimisamma','7013720496',8,0,'8','23X01A6689','','2026-03-30 06:03:38','2026-03-30 06:03:38',1,0,NULL,NULL,NULL,'student',NULL,'student'),(41,'23X01A6690','$2y$10$MIPDCu2hK6sJHPZ1uebPYe2Jrvl.08.bpEo1msiomINTcZmQ5xT9S','23X01A6690@nrcmec.org','Pallavi','Munjeti','Female','Suraram colony Rajiv gruha kalpa','9908976305',8,0,'8','23X01A6690','','2026-03-30 06:03:38','2026-03-30 06:03:38',1,0,NULL,NULL,NULL,'student',NULL,'student'),(42,'23X01A6691','$2y$10$tNFSkDmWG/C5g6x9duuzkOGeyeyxVsv108IkWmDRKmWSOArwZqWZq','23X01A6691@nrcmec.org','Shital','Muthineni','Female','3-8-75 nehru nagar ramanthapur','9381063172',8,0,'8','23X01A6691','','2026-03-30 06:03:38','2026-03-30 06:03:38',1,0,NULL,NULL,NULL,'student',NULL,'student'),(43,'23X01A6694','$2y$10$UmeD.LgRMVOmyOEsIgXwIOPPOSVjwp18/ot/A9zC.elyKpu3HXzki','23X01A6694@nrcmec.org','Nadiminti Sakshith','Reddy','Male','13-18/1  vil: Ailapur, m: Lingampet, Dist : Kamareddy state: Telangana','7661009876',8,0,'8','23X01A6694','','2026-03-30 06:03:38','2026-03-30 06:03:38',1,0,NULL,NULL,NULL,'student',NULL,'student'),(44,'23X01A6696','$2y$10$AOlPNzA41SNSa5rdCzPPeuvFWEUYZvmtxCBRDD3suei52vY3Gf4mG','23X01A6696@nrcmec.org','Nakkala','Jagadeeshwar Reddy','Male','Sri Sainath homes, Kousalya colony, bachupally (vil), bachupally (man), medchal (dist),pin code:-500090','77806695774',8,0,'8','23X01A6696','','2026-03-30 06:03:39','2026-03-30 06:03:39',1,0,NULL,NULL,NULL,'student',NULL,'student'),(45,'23X01A6697','$2y$10$q/W8wP6NXuhci.KN5mT7f.QUOwFO2fjlwwe2amRADnys6EtVNxTOa','23X01A6697@nrcmec.org','Nalla','Sridhar reddy','Male','Maisammaguda','8523868457',8,0,'8','23X01A6697','','2026-03-30 06:03:39','2026-03-30 06:03:39',1,0,NULL,NULL,NULL,'student',NULL,'student'),(46,'23X01A6698','$2y$10$f3R5yCrVkCcEqvLmGu3O6uQsRp4sjFriWRJPxGNKePMiS/s67VUoS','23X01A6698@nrcmec.org','Nareddy','Pavani','Female','Athvelly, Medchal','7093721836',8,0,'8','23X01A6698','','2026-03-30 06:03:39','2026-03-30 06:03:39',1,0,NULL,NULL,NULL,'student',NULL,'student'),(47,'23X01A6699','$2y$10$EWT6PCo4NDcnfiu9DK/.uu29VvPHh3nBjwr7PpMeILNUrRVxvxj1q','23X01A6699@nrcmec.org','Nayini','Snehitha','Female','Hyderabad','9603010631',8,0,'8','23X01A6699','','2026-03-30 06:03:39','2026-03-30 06:03:39',1,0,NULL,NULL,NULL,'student',NULL,'student'),(48,'23X01A66A0','$2y$10$WfM6ZmqWEI107xIgLuB2QeoDDwmS2U3YnxbNmAqwyJjmj6R/yDyZy','23X01A66A0@nrcmec.org','Neelakantarao','Siva Tejas','Male','Suraram','7075274930',8,0,'8','23X01A66A0','','2026-03-30 06:03:39','2026-03-30 06:03:39',1,0,NULL,NULL,NULL,'student',NULL,'student'),(49,'23X01A66A1','$2y$10$VOD/gdAKP7UIL1wD0AIqsO8YCqEQWQuzVoVGk6ehUUnMtZeeFtNte','23X01A66A1@nrcmec.org','Nerella','Sreeshanth','Male','Maisammaguda, dulapally','8919786820',8,0,'8','23X01A66A1','','2026-03-30 06:03:40','2026-03-30 06:03:40',1,0,NULL,NULL,NULL,'student',NULL,'student'),(50,'23X01A66A2','$2y$10$BcJpHlVb/EgdA6wpAHoGOuo4IBBdZ/B.VPZIFp2TbP0QkrOhIMXyK','23X01A66A2@nrcmec.org','Keerthana','Nimma','Female','2-29, nemtoor village, wargal mandal, Siddipet district, telangana,502334','7095439191',8,0,'8','23X01A66A2','','2026-03-30 06:03:40','2026-03-30 06:03:40',1,0,NULL,NULL,NULL,'student',NULL,'student'),(51,'23X01A66A3','$2y$10$RC0YAu2AaB4fqhZZ085WQOQMQNhgt7gkcJzYt8NwZeH7oYfCkzjnm','23X01A66A3@nrcmec.org','Nukala','Sai','Male','Kompally','6302504213',8,0,'8','23X01A66A3','','2026-03-30 06:03:40','2026-03-30 06:03:40',1,0,NULL,NULL,NULL,'student',NULL,'student'),(52,'23X01A66A5','$2y$10$trwmKaxsn76nKArWXbhDAOHAz9Yymire9yI0PNKuw8aVKeDakKu1K','23X01A66A5@nrcmec.org','Ollem','ChandraMohan','Male','Morthad, Nizamabad','7337489455',8,0,'8','23X01A66A5','','2026-03-30 06:03:41','2026-03-30 06:03:41',1,0,NULL,NULL,NULL,'student',NULL,'student'),(53,'23X01A66A8','$2y$10$/Nas.jubzBxzI6XLIe0SdezKMQb66kBfUv8AH6WMRYx83cVn8uwUi','23X01A66A8@nrcmec.org','PANDULA','NAGARAJ','Male','Hyderabad','9949805643',8,0,'8','23X01A66A8','','2026-03-30 06:03:41','2026-03-30 06:03:41',1,0,NULL,NULL,NULL,'student',NULL,'student'),(54,'23X01A66A9','$2y$10$FIDN08V4qtzGct36UW0nYOcE.EzKj5AvKTYbWm3SvEAC6ClsIrcNS','23X01A66A9@nrcmec.org','Parapelli','Karthik','Male','1-1-29/5/10 Devi nagar Kapra Hyderabad Telangana India','9154265864',8,0,'8','23X01A66A9','','2026-03-30 06:03:41','2026-03-30 06:03:41',1,0,NULL,NULL,NULL,'student',NULL,'student'),(55,'23X01A66B0','$2y$10$O1ox3HS3j7DlF0.ZJOERquh4lxQFzjAN824tFpr6.sC5nzqqdf4g.','23X01A66B0@nrcmec.org','Sanjana','Reddy','Female','Hyderabad','9182597678',8,0,'8','23X01A66B0','','2026-03-30 06:03:41','2026-03-30 06:03:41',1,0,NULL,NULL,NULL,'student',NULL,'student'),(56,'23X01A66B1','$2y$10$Up.CNp9GF.RSHLK/cMRFPOhy4dRXDUSHknUDMPBbd68l9LNthywCu','23X01A66B1@nrcmec.org','pasuladi','rakesh','Male','hyd','7093500327',8,0,'8','23X01A66B1','','2026-03-30 06:03:42','2026-03-30 06:03:42',1,0,NULL,NULL,NULL,'student',NULL,'student'),(57,'23X01A66B2','$2y$10$ZEALe22Xpfjj4ymbnjAi5el55j14NoINc891J61TWaYAhRgqUj3T6','23X01A66B2@nrcmec.org','Peddolla','Madhuri','Female','Hyderabad','8897345215',8,0,'8','23X01A66B2','','2026-03-30 06:03:42','2026-03-30 06:03:42',1,0,NULL,NULL,NULL,'student',NULL,'student'),(58,'23X01A66B5','$2y$10$qvVDGH9efrRW6zPXcG.WnuVbHgGFuBgS4wXbYnWpZJYv5VrB9Xvkq','23X01A66B5@nrcmec.org','Peram Kousthub','Yadav','Male','Kompally, Hyderabad','8886193338',8,0,'8','23X01A66B5','','2026-03-30 06:03:42','2026-03-30 06:03:42',1,0,NULL,NULL,NULL,'student',NULL,'student'),(59,'23X01A66B7','$2y$10$nZwl0.F4t2KOIgfhXmBmmOiFSGQilGqHNAs8DcKyKSMWQP.wCL.Lu','23X01A66B7@nrcmec.org','Rashmitha','Pippera','Female','H.No:1-63/1 vill:palem Mdl: Morthad Dist:Nizamabad Telangana','7893137982',8,0,'8','23X01A66B7','','2026-03-30 06:03:43','2026-03-30 06:03:43',1,0,NULL,NULL,NULL,'student',NULL,'student'),(60,'23X01A66B8','$2y$10$bYqKMIvPnx3E2jGREA7MkuC6wcu6cLNYbNhZfDvr.OKtgEEQw45wa','23X01A66B8@nrcmec.org','Pitta','Ravi Teja','Male','Jagtial','9390314800',8,0,'8','23X01A66B8','','2026-03-30 06:03:43','2026-03-30 06:03:43',1,0,NULL,NULL,NULL,'student',NULL,'student'),(61,'23X01A66B9','$2y$10$HUG52Rt/tWQ1E6tWMPY/Xe1AfAlcujKGVB0ro82ojwie.v43hTTqS','23X01A66B9@nrcmec.org','Polishetty','Pavan','Male','Kukatpally','6303746996',8,0,'8','23X01A66B9','','2026-03-30 06:03:43','2026-03-30 06:03:43',1,0,NULL,NULL,NULL,'student',NULL,'student'),(62,'23X01A66C0','$2y$10$zOwpecMNBYEPfdjaNO7V/Odnpmf56PIhUj4.FCDpu2k0SW.tsmuT.','23X01A66C0@nrcmec.org','PRAJAPATI','YASH','Male','2-2-84,Pan Bazar, Secunderabad','6304659873',8,0,'8','23X01A66C0','','2026-03-30 06:03:44','2026-03-30 06:03:44',1,0,NULL,NULL,NULL,'student',NULL,'student'),(63,'23X01A66C2','$2y$10$ePdyd4ljl39CvWwircRCvuCJQbC5hCI9kIOMOkcELPhshOvtJhksq','23X01A66C2@nrcmec.org','Dileep','Goud','Male','Medchal','6303641873',8,0,'8','23X01A66C2','','2026-03-30 06:03:44','2026-03-30 06:03:44',1,0,NULL,NULL,NULL,'student',NULL,'student'),(64,'23X01A66C4','$2y$10$KC/ol7Vj.bx5jM7.9LrxNu/8psWzyl639XVp4fhkGjkXa6/C26Tla','23X01A66C4@nrcmec.org','Samala','Shravan kumar','Male','Lakshmi narshimha colony ecil  Hyderabad','9182542749',8,0,'8','23X01A66C4','','2026-03-30 06:03:44','2026-03-30 06:03:44',1,0,NULL,NULL,NULL,'student',NULL,'student'),(65,'23X01A66C5','$2y$10$uw6RUTMTRvCCfe0BBy5tGOIkPnv0lkIW/g6blDFh9mTRrPOQaD0k.','23X01A66C5@nrcmec.org','Swetha','Sanivarapu','Female','Hyderabad, Telangana,Madhinaguda','9391919837',8,0,'8','23X01A66C5','','2026-03-30 06:03:45','2026-03-30 06:03:45',1,0,NULL,NULL,NULL,'student',NULL,'student'),(66,'23X01A66C7','$2y$10$TIaH4u6xJsopzHcwX9I1m.385az3cJfDEtk7Y6mBQNiUrl0wYOHwy','23X01A66C7@nrcmec.org','Seelam','Deepika','Female','H.No:1-56, Siripuram(V) Madhira (M) khammma (D)','8341411328',8,0,'8','23X01A66C7','','2026-03-30 06:03:45','2026-03-30 06:03:45',1,0,NULL,NULL,NULL,'student',NULL,'student'),(67,'23X01A66C8','$2y$10$6joh4tEkQmGi4Uje/KpxYeA9EwjGddyQim.l.PfgWu0eoObGiLBFS','23X01A66C8@nrcmec.org','Shetty','Ashritha','Female','Bahadurpally, Hyderabad, India','9059865188',8,0,'8','23X01A66C8','','2026-03-30 06:03:46','2026-03-30 06:03:46',1,0,NULL,NULL,NULL,'student',NULL,'student'),(68,'23X01A66C9','$2y$10$D8kp2O5tqiE/9sYP6AKm8uWG3XmzQ.Y9JGpWTEiz5It59EnTtL2/O','23X01A66C9@nrcmec.org','Shivarla','Yashwanth ram','Male','20-33/1, Ganesh Nagar, ramakrishnapur,mancherial, telangana','9866465298',8,0,'9','23X01A66C9','','2026-03-30 06:03:46','2026-03-30 06:03:46',1,0,NULL,NULL,NULL,'student',NULL,'student'),(69,'23X01A66D0','$2y$10$rh2QzfN3xiynOjmlhxgj5uUTxqzVnsJEoOq48pv0G56DvbuR5FMAG','23X01A66D0@nrcmec.org','Manasa','Singathi','Female','Maisammaguda, hyderabad','8639857232',8,0,'9','23X01A66D0','','2026-03-30 06:03:46','2026-03-30 06:03:46',1,0,NULL,NULL,NULL,'student',NULL,'student'),(70,'23X01A66D1','$2y$10$RCQwNnEQCkjo4BgOAxbweuOz2jeek77mFtgOA9OPjNofbLz4U5V..','23X01A66D1@nrcmec.org','Srinivas','Rao','Male','Pragati nagar','7416760097',8,0,'9','23X01A66D1','','2026-03-30 06:03:47','2026-03-30 06:03:47',1,0,NULL,NULL,NULL,'student',NULL,'student'),(71,'23X01A66D3','$2y$10$1icprexkLo2TB0yRgcNn/ei.xnqZsOelmMts4sw6rgR/5m9496naS','23X01A66D3@nrcmec.org','Sunkaraneni','Ashwitha','Female','Maisammaguda, Hyderabad, Telangana','7893245986',8,0,'9','23X01A66D3','','2026-03-30 06:03:47','2026-03-30 06:03:47',1,0,NULL,NULL,NULL,'student',NULL,'student'),(72,'23X01A66D4','$2y$10$IIWU1Zn.OY0MyfFcteT82O52TeCufO7g6u.bcRlvDV7Ge8qHVjfYW','23X01A66D4@nrcmec.org','Sunkari Eshwari','Prasanna','Female','Madepalli moinabad Hyderabad Telangana','7386911094',8,0,'9','23X01A66D4','','2026-03-30 06:03:47','2026-03-30 06:03:47',1,0,NULL,NULL,NULL,'student',NULL,'student'),(73,'23X01A66D5','$2y$10$yVQnpTIYRzsfgRVYXNkYAOl.K5uiu7eGS.sD8PTuhsBjdLfBQGw76','23X01A66D5@nrcmec.org','Sunkari','Kavya sri','Female','Warangal','8074767828',8,0,'9','23X01A66D5','','2026-03-30 06:03:47','2026-03-30 06:03:47',1,0,NULL,NULL,NULL,'student',NULL,'student'),(74,'23X01A66D6','$2y$10$MbyASyh7CZDXfiTd/2Fp6uaFxkADCmCU7AoOSIozlYZF/qs3LwD3C','23X01A66D6@nrcmec.org','Pranay','Surugula','Male','Maishammaguda, dullapally','6301542797',8,0,'9','23X01A66D6','','2026-03-30 06:03:47','2026-03-30 06:03:47',1,0,NULL,NULL,NULL,'student',NULL,'student'),(75,'23X01A66D7','$2y$10$MhARNDujf9jRaF2s1C2DWuqvaD.aIeJDq0cm9QD7/pFY2/TcAe0WK','23X01A66D7@nrcmec.org','Surukutla','Charanya','Female','Nizamabad','7416015086',8,0,'9','23X01A66D7','','2026-03-30 06:03:48','2026-03-30 06:03:48',1,0,NULL,NULL,NULL,'student',NULL,'student'),(76,'23X01A66E0','$2y$10$y.l1fjGhMUxRbaJbAmuWMOx2OnHmJjzdwstJ2ND3sRYOoSQqAmVY6','23X01A66E0@nrcmec.org','Thaduri','Sai Naveen','Male','Yusufguda,Hyderabad,500045.','9392702940',8,0,'9','23X01A66E0','','2026-03-30 06:03:48','2026-03-30 06:03:48',1,0,NULL,NULL,NULL,'student',NULL,'student'),(77,'23X01A66E1','$2y$10$Dyne9YVaKK/Gpah/kTCj0O1sw6r1COBFiHNJyfHLzrtFu4lEulvQK','23X01A66E1@nrcmec.org','Naveen','Kumar Thiruveedhi','Male','BR junction,Tripuranthakam(m),Prakasam(D),AP','9392403283',8,0,'9','23X01A66E1','','2026-03-30 06:03:48','2026-03-30 06:03:48',1,0,NULL,NULL,NULL,'student',NULL,'student'),(78,'23X01A66E2','$2y$10$hEX2d/EBYzZtbNloLATEMOvqJz.L7RZds6VZQeZZJDlYhHu118Lou','23X01A66E2@nrcmec.org','TUNDENA','LOKESH KUMAR','Male','H.NO:- 1-85 vill:- LAXMAPUR MDL:- RANAYAPET DIST:- MEDAK','9346300628',8,0,'9','23X01A66E2','','2026-03-30 06:03:49','2026-03-30 06:03:49',1,0,NULL,NULL,NULL,'student',NULL,'student'),(79,'23X01A66E3','$2y$10$lDFloDhEdJBwXrfhsFnl2O6rFCGQG5Vndv.uEJ.sKa/v0/RFzkceq','23X01A66E3@nrcmec.org','Maithri','Uppari','Female','Warangal, Siddipet, Telangana','9381006696',8,0,'9','23X01A66E3','','2026-03-30 06:03:49','2026-03-30 06:03:49',1,0,NULL,NULL,NULL,'student',NULL,'student'),(80,'23X01A66E4','$2y$10$2sVKYMQrD72l05VJUGjZrOQ.O7KbIfEYuCykkNhTmsS/ubXYN3reW','23X01A66E4@nrcmec.org','Utthur','Ashwanth Yadav','Male','6-4, pipri, Armoor, Nizamabad, Telangana 503224','9542264615',8,0,'9','23X01A66E4','','2026-03-30 06:03:49','2026-03-30 06:03:49',1,0,NULL,NULL,NULL,'student',NULL,'student'),(81,'23X01A66E5','$2y$10$naN2Q15PNJJUnfKfgIfIu.d8QMuiocbA4NFx/s3Jm5z2sX0AypMEu','23X01A66E5@nrcmec.org','VINESH','VADIJARLA','Male','1-2/2D Lingampet,Kamareddy','6305738550',8,0,'9','23X01A66E5','','2026-03-30 06:03:49','2026-03-30 06:03:49',1,0,NULL,NULL,NULL,'student',NULL,'student'),(82,'23X01A66E6','$2y$10$mt3gfGf2OAPaZS/7uz8NO.6BaRcK/eeoRCXvvbTey6MWtcffghFmq','23X01A66E6@nrcmec.org','Sai','Kumar','Male','Hyderabad','9949602552',8,0,'9','23X01A66E6','','2026-03-30 06:03:49','2026-03-30 06:03:49',1,0,NULL,NULL,NULL,'student',NULL,'student'),(83,'23X01A66E8','$2y$10$8nwavyUi4.HeCDLc4Kau3..AUZ6AF4rvQjRUUQCfYQtAx0FRyXiu6','23X01A66E8@nrcmec.org','Vanga','Praneeth','Male','H.no:- 1-5,Vill:- chekkapally, mdl:- vemulawada,dis:- rajanna sircilla','9390132775',8,0,'9','23X01A66E8','','2026-03-30 06:03:50','2026-03-30 06:03:50',1,0,NULL,NULL,NULL,'student',NULL,'student'),(84,'23X01A66E9','$2y$10$.zdr54YubiakQ/3WaYFAT.zc9TBgJYdoth6AuJYwO27vcdgExldh2','23X01A66E9@nrcmec.org','Vangala','ANIL','Male','Chennavaram village, gampalagudam mandal, krishna','8247586712',8,0,'9','23X01A66E9','','2026-03-30 06:03:50','2026-03-30 06:03:50',1,0,NULL,NULL,NULL,'student',NULL,'student'),(85,'23X01A66F0','$2y$10$127Y.9I4RcNEf0BXaTC7xuSNJZhIZlzPpdZeq14jI3rochVIO0RHW','23X01A66F0@nrcmec.org','VATTIKUNTA','KARTHEEK KUMAR','Male','902 ,RV SILPA DHARMISTA PHASE-I, Bollaram Rd, Sri Rangapuram colony, Rangapuram, Hyderabad, Miyapur, Telangana 500049','9347156038',8,0,'9','23X01A66F0','','2026-03-30 06:03:50','2026-03-30 06:03:50',1,0,NULL,NULL,NULL,'student',NULL,'student'),(86,'23X01A66F1','$2y$10$zTpJqHoQ6JOAshA/7roCJ.zkClsK8f98jrmgLhB0RRUjDk6yCCYuC','23X01A66F1@nrcmec.org','Veeranki','Rishi Kumar','Male','6-251/2 vani nagar,chintal','9553040595',8,0,'9','23X01A66F1','','2026-03-30 06:03:50','2026-03-30 06:03:50',1,0,NULL,NULL,NULL,'student',NULL,'student'),(87,'23X01A66F2','$2y$10$4F9kPlT3rcWEdo7sATPTyutaalYV311..6BJ5TnO/xANSvk2bx.te','23X01A66F2@nrcmec.org','Vennapureddy','Sai varshith','Male','Mahadevpur','6301026100',8,0,'9','23X01A66F2','','2026-03-30 06:03:51','2026-03-30 06:03:51',1,0,NULL,NULL,NULL,'student',NULL,'student'),(88,'23X01A66F3','$2y$10$aRRvj1YimFLYhJmAq4P9j.OATDZW7odwHNe.2/pg7hS35NuG8r9Jy','23X01A66F3@nrcmec.org','Yakkali','Uma Vaishnavi','Female','12-95,Adarsh Nagar Colony,IDPL,Hyd','6305837187',8,0,'9','23X01A66F3','','2026-03-30 06:03:51','2026-03-30 06:03:51',1,0,NULL,NULL,NULL,'student',NULL,'student'),(89,'23X01A66F4','$2y$10$.66//SrggAdLmXQkAPHIlevrVijKfo2vnbQTMwMxm2eayXVz/v.1G','23X01A66F4@nrcmec.org','Yaramasu','Bhavya','Female','Flat no -104 Kesava Apartments, Nacharam, Hyderabad','8074940462',8,0,'9','23X01A66F4','','2026-03-30 06:03:51','2026-03-30 06:03:51',1,0,NULL,NULL,NULL,'student',NULL,'student'),(90,'23X01A66F6','$2y$10$j3vUOGHIzebLyhxoIPIgIO5eK2ed23EjyFECxQW4Jjyq5XcZ6lkLC','23X01A66F6@nrcmec.org','Yasa','Pooja Reddy','Female','Noothankal, Suryapet (dist).','8186832652',8,0,'9','23X01A66F6','','2026-03-30 06:03:51','2026-03-30 06:03:51',1,0,NULL,NULL,NULL,'student',NULL,'student'),(91,'23X01A66F7','$2y$10$sHv7ibHcRqlW1bMgT5152.J4tJR1C0vgWiwGm5uzdjXAmuXIR8iD2','23X01A66F7@nrcmec.org','Yekkladev','Sravanthi','Female','8-3-229/D/84, Road Number 10B, Hylam Colony','8121048520',8,0,'9','23X01A66F7','','2026-03-30 06:03:52','2026-03-30 06:03:52',1,0,NULL,NULL,NULL,'student',NULL,'student'),(92,'23X01A66F8','$2y$10$S.Kx2oiaot3szkTkXP4nTOloG61TMVtApKLZB4hfkbVQRvWs.en7G','23X01A66F8@nrcmec.org','Yenumula','Hari Sri Surya Vikash','Male','House No1-1-307, plot.no -  44, Sri ram nagar colony, kapra','6300056635',8,0,'9','23X01A66F8','','2026-03-30 06:03:52','2026-03-30 06:03:52',1,0,NULL,NULL,NULL,'student',NULL,'student'),(93,'23X01A66F9','$2y$10$bYxcBiUbZSPDVhB3ifEjre3QOUg2bDTX6thyCb1zhNJX4SLLfjEKK','23X01A66F9@nrcmec.org','YERRAMSHETTI','THANMAI','Female','Maisammaguda','8019491379',8,0,'9','23X01A66F9','','2026-03-30 06:03:52','2026-03-30 06:03:52',1,0,NULL,NULL,NULL,'student',NULL,'student'),(94,'24X05A6601','$2y$10$8bBlkD5cwED5jxoopKTjKugaeJIjOHnL0qIwCZtVxs1NHy6jpaYd2','24X05A6601@nrcmec.org','Aleti','Sai vardhan Reddy','Male','Baswapur,Siddipet, Telangana','9346796877',8,0,'9','24X05A6601','','2026-03-30 06:03:53','2026-03-30 06:03:53',1,0,NULL,NULL,NULL,'student',NULL,'student'),(95,'24X05A6602','$2y$10$AVIcs/gs/8sU4bPNXcWjuOhA8driqocR88mJe23XDF2aNSm5gCA.e','24X05A6602@nrcmec.org','Bandari','Srinija','Female','Maisammaguda','9652922864',8,0,'9','24X05A6602','','2026-03-30 06:03:53','2026-03-30 06:03:53',1,0,NULL,NULL,NULL,'student',NULL,'student'),(96,'24X05A6603','$2y$10$.6rcM8h0ifshG1OQcL8K1uhpN/5YSJR8Mbm1QpGR2/rz9K2PmRVfW','24X05A6603@nrcmec.org','Banoth','Akhila','Female','Hyderabad, maisammaguda','7842315502',8,0,'9','24X05A6603','','2026-03-30 06:03:53','2026-03-30 06:03:53',1,0,NULL,NULL,NULL,'student',NULL,'student'),(97,'24X05A6604','$2y$10$mvUTIlALdEO3wSyJ2NhTgez9Om7ws6HO5.CEzi5q2ivB6o67lSaAW','24X05A6604@nrcmec.org','Bhuvanesh','Sai','Male','Jntu college , kphb','9014288320',8,0,'9','24X05A6604','','2026-03-30 06:03:53','2026-03-30 06:03:53',1,0,NULL,NULL,NULL,'student',NULL,'student'),(98,'24X05A6605','$2y$10$n8a8asYYyptB3vtfYqprceW8ZF.bnA94NuYIP/lixyUd8UHqkRZE2','24X05A6605@nrcmec.org','Dasari','Shashank','Male','2-67,deshrajpally,Kamalapur,Hanamkonda','6300719874',8,0,'9','24X05A6605','','2026-03-30 06:03:54','2026-03-30 06:03:54',1,0,NULL,NULL,NULL,'student',NULL,'student'),(99,'24X05A6606','$2y$10$3p0OyKLaD7w.btOeA8Ecuu2W3ZITVH/Iz8mStiZGL30wJIznhY3dK','24X05A6606@nrcmec.org','Gurrala','Thrishul','Male','Narsimha reddy engineering college','7330674350',8,0,'9','24X05A6606','','2026-03-30 06:03:54','2026-03-30 06:03:54',1,0,NULL,NULL,NULL,'student',NULL,'student'),(100,'24X05A6608','$2y$10$IY.17mD8MG8Y7raTWBbXSutn0VmCq4rgrRB2bcOocbYhSEJ2EIHu.','24X05A6608@nrcmec.org','Srujan','Kumar','Male','Flat no 305, Lalitha delight block 2, odf colony, ameenpur Hyderabad 502032','7995274199',8,0,'9','24X05A6608','','2026-03-30 06:03:54','2026-03-30 06:03:54',1,0,NULL,NULL,NULL,'student',NULL,'student'),(101,'24X05A6609','$2y$10$oqYA7lj/mqgjRSqcgNpdb.V1cLGkA6pHZMNb9nbvB6qBq5y1wH0wq','24X05A6609@nrcmec.org','Abhiram','Madupu','Male','Karimnagar','9391200470',8,0,'9','24X05A6609','','2026-03-30 06:03:55','2026-03-30 06:03:55',1,0,NULL,NULL,NULL,'student',NULL,'student'),(102,'24X05A6610','$2y$10$1v/GBfaZvn8OcGVVDNdMZeVLP75p8YROZZS5i7PoEsDPU2CFU3NAy','24X05A6610@nrcmec.org','Mantha','Soojal','Male','18-469 bhupalpally','9392968334',8,0,'9','24X05A6610','','2026-03-30 06:03:55','2026-03-30 06:03:55',1,0,NULL,NULL,NULL,'student',NULL,'student'),(103,'24X05A6611','$2y$10$67Iz.KVwzzMMHqy8xBWRhenwZVRU9AiyFMFwk/NzdaaZOrIaUoiAW','24X05A6611@nrcmec.org','Maraboina','Reethika','Female','2-292/3, Nekkonda, warangal, 506122','9885551570',8,0,'9','24X05A6611','','2026-03-30 06:03:55','2026-03-30 06:03:55',1,0,NULL,NULL,NULL,'student',NULL,'student'),(104,'24X05A6613','$2y$10$lV3eaQwbqcCBCeVMDKTEpuv/8JMaRXnqaFii9qcv656fN/tM3R6/S','24X05A6613@nrcmec.org','Mittapally','Shivanandini','Female','Maisammaguda','9676905206',8,0,'9','24X05A6613','','2026-03-30 06:03:55','2026-03-30 06:03:55',1,0,NULL,NULL,NULL,'student',NULL,'student'),(105,'24X05A6617','$2y$10$4o.ajv.GOxY0OZz5qLy6kONB1j.0zVJ4EhxlqFYShur2jVT1VfLl2','24X05A6617@nrcmec.org','Thoutam','Thirumala','Female','Hyderabad Maisammaguda','7386396938',8,0,'9','24X05A6617','','2026-03-30 06:03:56','2026-03-30 06:03:56',1,0,NULL,NULL,NULL,'student',NULL,'student'),(106,'24X05A6618','$2y$10$3NRwUPjnApCQwmRTAuioL.1BmnftUtb2TC2L3nxHv5v8sck0j0wz2','24X05A6618@nrcmec.org','Vangala','Sai Ramana','Male','6-6-285 , Sharmanagar , Karimnagar','7330692147',8,0,'9','24X05A6618','','2026-03-30 06:03:56','2026-03-30 06:03:56',1,0,NULL,NULL,NULL,'student',NULL,'student'),(107,'24X05A6619','$2y$10$fyGR3h0caH/lOe.9ZjD56uT178.A/uqnZaU0tvhtoLqvQL3CNhZRy','24X05A6619@nrcmec.org','VEMULAWADA','KRANTHI KUMAR','Male','12-120 Rallapeta,Mancherial, Mancherial,504208','9398316553',8,0,'9','24X05A6619','','2026-03-30 06:03:56','2026-03-30 06:03:56',1,0,NULL,NULL,NULL,'student',NULL,'student'),(108,'21X01A6601','$2y$10$axRiD6luhZz/BXyxeo49EeRcHuaqeP6TUDua4pphZcsW6zLQRErGS','21X01A6601@nrcmec.org','p','ADITYA GOUD','male','','',10,0,'19','21X01A6601','','2026-03-30 09:09:12','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(109,'21X01A6602','$2y$10$2zgWaSrR4RNw1kDnu3drPuHmBXrqsp5dkMPjH.q1r/WXATYnVSdmW','21X01A6602@nrcmec.org','AJAY','SINGH','male','','',10,0,'19','21X01A6602','','2026-03-30 09:09:12','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(110,'21X01A6603','$2y$10$Vg39Xl.AUc8/JxlXzvwU2OEa49pgWjv3ZoYkIMHA0KthzZY8R8hhy','21X01A6603@nrcmec.org','AKASH','SHARMA','male','','',10,0,'19','21X01A6603','','2026-03-30 09:09:12','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(111,'21X01A6604','$2y$10$35ZudGbG8WP/MbY/kxPdyuF8SrMsD9Qauv6GA4RUyUPgTpCeAbCVO','21X01A6604@nrcmec.org','AMEERPETA','SHASHIKANTH','male','','',10,0,'19','21X01A6604','','2026-03-30 09:09:12','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(112,'21X01A6605','$2y$10$KdEWpt8UNbz/8Zz55q5oMOBCBFsZ8iJruZJzqx0KOV1mQXPfei/8G','21X01A6605@nrcmec.org','HALDER','ASHISH','male','','',10,0,'19','21X01A6605','','2026-03-30 09:09:13','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(113,'21X01A6606','$2y$10$zfEvRBCZNhu4Ysf3wJPmo.t5Gx1pW1S4WY2vFqo.rkpQ7EjHMpRXK','21X01A6606@nrcmec.org','BELLALA','INDHU','Female','','',10,0,'19','21X01A6606','','2026-03-30 09:09:13','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(114,'21X01A6607','$2y$10$auU75nM7qQWEuL7e/B6WXOHTNT65eD/g0VAi6HAq54nSMlworVKHi','21X01A6607@nrcmec.org','BELLAMKONDA','MAHESH','male','','',10,0,'19','21X01A6607','','2026-03-30 09:09:13','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(115,'21X01A6608','$2y$10$l3sw2UaMl4MDEWNILMCUfenJJymUMsbGDeHNARvc3FfytmxDBS0/G','21X01A6608@nrcmec.org','BOLLAM','VARSHA','female','','',10,0,'19','21X01A6608','','2026-03-30 09:09:13','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(116,'21X01A6609','$2y$10$AwxCP0YQO81LrvDjySaEwetMNzlchnB1HTHbYiTUD6w/bJT98D/wG','21X01A6609@nrcmec.org','CHIKKA','RAHUL GOUD','male','','',10,0,'19','21X01A6609','','2026-03-30 09:09:13','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(117,'21X01A6610','$2y$10$dIQqJdQ2CHss8FxGLMerx.r.ivJ/mJmQNKhBhhqGBbrDzDjuDscke','21X01A6610@nrcmec.org','CHENNA','VIGNAN','male','','',10,0,'19','21X01A6610','','2026-03-30 09:09:14','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(118,'21X01A6611','$2y$10$vIEep9QL7S04yNSTlmy5tuR8tEuTNPI3AuTPFHH75Kxrf/1Du3Bvm','21X01A6611@nrcmec.org','CHENNURI','AKSHITHA','female','','',10,0,'19','21X01A6611','','2026-03-30 09:09:14','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(119,'21X01A6613','$2y$10$QwmRAKLsr7ekDytaI/sI0e9wQEf/l9brWvYKjHv3PzjalkGyjlElm','21X01A6613@nrcmec.org','DECHINENI','YASHVANTH','male','','',10,0,'19','21X01A6613','','2026-03-30 09:09:14','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(120,'21X01A6614','$2y$10$xRsXdBdOALwjPJJ/0k8FOeLl.k/neHnzo1pYrZlH2NnblHPWYZzCW','21X01A6614@nrcmec.org','DONE','CHARAN','male','','',10,0,'19','21X01A6614','','2026-03-30 09:09:14','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(121,'21X01A6615','$2y$10$A6wND0WzZIyhaoLavvM8Ie7n5j7JudZodsag6PVxCP4S1XEWMvK1i','21X01A6615@nrcmec.org','DOSAVADA','YESHWANTH','male','','',10,0,'19','21X01A6615','','2026-03-30 09:09:15','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(122,'21X01A6616','$2y$10$ExdWz.2Csv.r4sdTmprLMeXKC2ucLx9yBvFaqpTsVsZEPkUcNnVtC','21X01A6616@nrcmec.org','ERRABELLI','SRUTHI RAO','female','','',10,0,'19','21X01A6616','','2026-03-30 09:09:15','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(123,'21X01A6617','$2y$10$WDs94CeiD349yue0j2qFr.ud0HofdHVtGgeBkGazS2XkCGXL0Dtvq','21X01A6617@nrcmec.org','G','SAMPATH','male','','',10,0,'19','21X01A6617','','2026-03-30 09:09:15','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(124,'21X01A6618','$2y$10$4efSC4Z0nujHmlx45NWbKOxH.wOmOhQnERGxl.grJz5VqFhg0Z07K','21X01A6618@nrcmec.org','GADE','SAI CHARAN','male','','',10,0,'19','21X01A6618','','2026-03-30 09:09:15','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(125,'21X01A6619','$2y$10$HABAW/fB5Oopk/MaZJrAU.Quol/WPAO3rV1K7zi3nFYTByIi9nAEe','21X01A6619@nrcmec.org','GAJULA','VIVEK','male','','',10,0,'19','21X01A6619','','2026-03-30 09:09:15','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(126,'21X01A6620','$2y$10$d2stxJ7AdUzoXUnMcXSfX./0QkEQpR84YdCw4Ns4LN92xxBqrFCVq','21X01A6620@nrcmec.org','GARALLOLA','SUPRAJA','female','','',10,0,'19','21X01A6620','','2026-03-30 09:09:15','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(127,'21X01A6621','$2y$10$A8I8NdqZguQZucshndET8uXBQhkR.mTE3JwMmB9sEqrL7tUyZhO0y','21X01A6621@nrcmec.org','GODA','MADHU KIRAN REDDY','male','','',10,0,'19','21X01A6621','','2026-03-30 09:09:15','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(128,'21X01A6622','$2y$10$36gz.kOTqVxjXzmg2qFBmeW4rkIUqpzqmIB7/AJGNtp//L8Z0gwFu','21X01A6622@nrcmec.org','GOLLA','BHAVANI SHANKER','male','','',10,0,'19','21X01A6622','','2026-03-30 09:09:15','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(129,'21X01A6623','$2y$10$3GiyZ.DbpeFRSEvQNo.gEu5bzUP4TZG2lq3oVFWMgSrIzqZjhnByG','21X01A6623@nrcmec.org','GRANDHAM','YOGI YESHWANTH','male','','',10,0,'19','21X01A6623','','2026-03-30 09:09:16','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(130,'21X01A6624','$2y$10$73WQxXukryCJqNrDLou9m.kwtLG9iuHhyrn0dgd.tYWcyJRWhVUFW','21X01A6624@nrcmec.org','GUDDOJI','SWATHI','female','','',10,0,'19','21X01A6624','','2026-03-30 09:09:16','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(131,'21X01A6625','$2y$10$Wr21vvXrVJ4IEB05wQ5ZyOzXqTdmVjJWnPeEWD0HclXsJYgCmJY8O','21X01A6625@nrcmec.org','GUGULOTH','HARSHITH','female','','',10,0,'19','21X01A6625','','2026-03-30 09:09:16','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(132,'21X01A6626','$2y$10$nwxZs3qBzaRiHNlahmqRHeuAVkWOuykJ76SleMJ1bBIE4dDiFkuxG','21X01A6626@nrcmec.org','GUNDREDDY','SAMYUTHA','female','','',10,0,'19','21X01A6626','','2026-03-30 09:09:16','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(133,'21X01A6627','$2y$10$6f9njnCtHHnqW7reX7bWk.D/nFzNSg2Q8uNzEl0TZIOKTXemBgxMy','21X01A6627@nrcmec.org','GUDIKANDULA','AKSHAY','male','','',10,0,'19','21X01A6627','','2026-03-30 09:09:16','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(134,'21X01A6628','$2y$10$X3X/4xZXKHni3LmXpzB/QOHvhgYdDwv1twUUAN1u456vQs9glWR.e','21X01A6628@nrcmec.org','ILAPAKURTI','NITYA SANTOSH KUMAR','male','','',10,0,'19','21X01A6628','','2026-03-30 09:09:16','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(135,'21X01A6629','$2y$10$jxNBEs5COepBG8aRF5xdIeryAuM.HZWWpafwngxJpgytpc9R98ZfG','21X01A6629@nrcmec.org','INDRASENAREDDY','-','male','','',10,0,'19','21X01A6629','','2026-03-30 09:09:17','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(136,'21X01A6630','$2y$10$.b0J17IwZgXCSsGP3NdkD.qDlAj6smCwvzUGNZ1PDQtnx/vkYFw4S','21X01A6630@nrcmec.org','JAIDI','MAHATHI REDDY','female','','',10,0,'19','21X01A6630','','2026-03-30 09:09:17','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(137,'21X01A6631','$2y$10$O5j7AlllLnTjG98LFVYUpe82VVGb6EpMrBII6G08hDpammvoTeIiC','21X01A6631@nrcmec.org','K','SHIVA KUMAR','male','','',10,0,'19','21X01A6631','','2026-03-30 09:09:17','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(138,'21X01A6632','$2y$10$ETfxw3fESbByCSqx8typt.cnehvrMV87f7u84oNrUOdJ51IXe1usS','21X01A6632@nrcmec.org','K','SRIYA','female','','',10,0,'19','21X01A6632','','2026-03-30 09:09:17','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(139,'21X01A6633','$2y$10$em6kvi8r9fTRZsunOzUPveLfBWquyq7GGVvBXP9F7WJ5t1OXf9VAu','21X01A6633@nrcmec.org','KAMSALI','MANJU SRI','female','','',10,0,'19','21X01A6633','','2026-03-30 09:09:17','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(140,'21X01A6634','$2y$10$5eWPv7OHKId.HE3fsdYm2uEINn5qMCZaGKVhWgOL53GBWxR8bA4.e','21X01A6634@nrcmec.org','KANDI','TEJA REDDY','male','','',10,0,'19','21X01A6634','','2026-03-30 09:09:17','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(141,'21X01A6635','$2y$10$XQVCbXHjB0JRU7nxmq3tneA2Z4iP6Ny5Fs6XOP3Po3XgGiZH5zu5W','21X01A6635@nrcmec.org','KILLA','SPOORTHI','female','','',10,0,'19','21X01A6635','','2026-03-30 09:09:17','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(142,'21X01A6636','$2y$10$OIWgVBevMf5eXlMA8JnrrOpJTi3XkThfVPdwAfZcnV.M6QBLK.YnC','21X01A6636@nrcmec.org','KOLE','SUPRIYA','female','','',10,0,'19','21X01A6636','','2026-03-30 09:09:17','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(143,'21X01A6637','$2y$10$AhgmlkA1JGvz/0p8eyTGaOritWYhJBzSRLVAi0ZbFqKu8EJ/82OkO','21X01A6637@nrcmec.org','KOLLU','DIVYA SRI','female','','',10,0,'19','21X01A6637','','2026-03-30 09:09:18','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(144,'21X01A6638','$2y$10$82X9c.E1UTAD2lL0dPtDMe60twEB7UNK2vIn9C560Bs6l/5niTlVW','21X01A6638@nrcmec.org','KOTTE','SAI SRINIVAS','male','','',10,0,'19','21X01A6638','','2026-03-30 09:09:18','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(145,'21X01A6639','$2y$10$rg6zAkkOJX8k/pFbJRGZAeR5r8.ul/coU9xBJ8vCceMJlLxV9mrhK','21X01A6639@nrcmec.org','KURUVA','RAHUL','male','','',10,0,'19','21X01A6639','','2026-03-30 09:09:18','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(146,'21X01A6640','$2y$10$aIUD16wFn8IdDojS.FzO6eGckL7qn7OWNYqvgkx3xxNGtIXsQqmgy','21X01A6640@nrcmec.org','MALLADI','DURGA PRASAD','male','','',10,0,'19','21X01A6640','','2026-03-30 09:09:18','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(147,'21X01A6641','$2y$10$CRX6jHALW0KPY7ssYDe1heIkWSRJO5JypEPqIZMfbTax2SUqoAkuC','21X01A6641@nrcmec.org','MARGAM','VINAY','male','','',10,0,'19','21X01A6641','','2026-03-30 09:09:18','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(148,'21X01A6642','$2y$10$uMJOreVm4diHfu/82ge2b.H0RUSRrbrmij1nrdKmyzIKlfrZoGZf.','21X01A6642@nrcmec.org','MODULLA','JEYENDRA REDDY','male','','',10,0,'19','21X01A6642','','2026-03-30 09:09:18','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(149,'21X01A6643','$2y$10$CJ06.HNkfDu2OZ7F.kVBdO/HJbOJqZD2WVOyUHr7qbZ/4oiO1DdLC','21X01A6643@nrcmec.org','MOHAMMED','RIYADH','male','','',10,0,'19','21X01A6643','','2026-03-30 09:09:19','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(150,'21X01A6645','$2y$10$exvdF8BC2h1pLBv00n67ue8fZFMBGXHB/ltv1i8qotfWiMUUZHi02','21X01A6645@nrcmec.org','NAMALA','SRI NIKHIL','male','','',10,0,'19','21X01A6645','','2026-03-30 09:09:19','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(151,'21X01A6646','$2y$10$ETJeWsURh.OHLniEJtAhE.MRX3aXIG.pxzLrh3D41QOnn90nJSwy6','21X01A6646@nrcmec.org','POLEMONI','VAMSHI','male','','',10,0,'19','21X01A6646','','2026-03-30 09:09:19','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(152,'21X01A6647','$2y$10$l6O.d7VaZYRriQbLPxWzwet85tq5kdxza0NN1cF0ppmhhcoP6R61K','21X01A6647@nrcmec.org','PARAMATI','AASHISH','male','','',10,0,'19','21X01A6647','','2026-03-30 09:09:19','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(153,'21X01A6648','$2y$10$0eyJMEIg9GKKcLij85eUnuzJbYgXy7LFSfsSr1vHSc0figKylRstG','21X01A6648@nrcmec.org','RAAVI','JATIN','male','','',10,0,'19','21X01A6648','','2026-03-30 09:09:19','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(154,'21X01A6649','$2y$10$mhcayx25o6m/907I4v17HO3NG6yUZBtITUdit8P/fgeNRHCpAetkW','21X01A6649@nrcmec.org','RAVULA','MANICHAND','male','','',10,0,'19','21X01A6649','','2026-03-30 09:09:19','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(155,'21X01A6650','$2y$10$9IjW0qjJ8zoJBG/K4NERqeJPcQLn5m0hwbJEi7mwtgC1OMO.6YZQS','21X01A6650@nrcmec.org','SAMUDRALA','NIKHIL','male','','',10,0,'19','21X01A6650','','2026-03-30 09:09:20','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(156,'21X01A6651','$2y$10$fekVIYDsLbZAve1eGCOA2.5p5EA4CEQOgibkh6S5YfIkTdrbNotLW','21X01A6651@nrcmec.org','SANGAM','SANDEEP','male','','',10,0,'19','21X01A6651','','2026-03-30 09:09:20','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(157,'21X01A6652','$2y$10$93/XVYcjgVbzMkVoDShpRuV/EFNPlWQa9z6AbuEy0SgXUmKYUoAmS','21X01A6652@nrcmec.org','SHIVAPALLI','PRANAY','male','','',10,0,'19','21X01A6652','','2026-03-30 09:09:20','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(158,'21X01A6653','$2y$10$9mKJACC3mksV5XkxyTdQ7e8uvftg0ACkGEHsJRGX8wklIWIgzOw6S','21X01A6653@nrcmec.org','SUBBARAJU','SAGI SATYA HARI','male','','',10,0,'19','21X01A6653','','2026-03-30 09:09:20','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(159,'21X01A6654','$2y$10$iXW9NtU5h68KYx9HNfx80e2DywaLU4uPhLgnGvJcLEwuE8ARLwMJa','21X01A6654@nrcmec.org','SHAIK','GAFFAR','male','','',10,0,'19','21X01A6654','','2026-03-30 09:09:20','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(160,'21X01A6655','$2y$10$nG8xuI/ICmg7q8oWmASst.otge34xpp93W5IiCxMJ/NdcbvAH7FIG','21X01A6655@nrcmec.org','SURAM','RAMANA REDDY','male','','',10,0,'19','21X01A6655','','2026-03-30 09:09:21','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(161,'21X01A6656','$2y$10$4PdN.YB3aEOX0crymds3Qew5NrmiAgZePsR5PFJotLpT7JQvK0aEq','21X01A6656@nrcmec.org','THUMALAPALLY','UDAY','male','','',10,0,'19','21X01A6656','','2026-03-30 09:09:21','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(162,'21X01A6657','$2y$10$asoyM0osLV65L/NYLIzBze1PXnapYphXRG59HewcvndE0i40WnuOu','21X01A6657@nrcmec.org','UTKARSH','MISHRA','male','','',10,0,'19','21X01A6657','','2026-03-30 09:09:21','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(163,'21X01A6658','$2y$10$e8yihYqCesf78bw/W1n6Ju7caXgj3RTnI93n3lvvON32XLEGQ8ic2','21X01A6658@nrcmec.org','VASAMSETTY','MAHIMA KUMAR','male','','',10,0,'19','21X01A6658','','2026-03-30 09:09:21','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(164,'21X01A6659','$2y$10$PScW9piss2LuFtoa2PfdqeZbvQvQzozsypA.47Y51jsXqSwVeV5W.','21X01A6659@nrcmec.org','VEMULA','PAVAN KALYAN','male','','',10,0,'19','21X01A6659','','2026-03-30 09:09:21','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(165,'21X01A6660','$2y$10$lMRWd39uiv6x0VrMUxp7C.ssxvpTRLttACPc98ttTmMWyYLO.rFxW','21X01A6660@nrcmec.org','VANGARI','DINESH KUMAR','male','','',10,0,'19','21X01A6660','','2026-03-30 09:09:21','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(166,'21X01A6661','$2y$10$FlQ1EaZ3qN3rQG/Z93evOeP70.gTPuhrQpTFwl.6xUU3CWoyPc0ue','21X01A6661@nrcmec.org','VENNA','PRAVEEN KUMAR','male','','',10,0,'19','21X01A6661','','2026-03-30 09:09:22','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(167,'21X01A6662','$2y$10$9RKtQy.pR5jkDMHt0Wa1q.3ytEZL/yD5QbvI1twz3ply6859LRjG.','21X01A6662@nrcmec.org','YELLANKI','RASAGNA','Female','','',10,0,'19','21X01A6662','','2026-03-30 09:09:22','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(168,'21X01A6663','$2y$10$gV2DXyrDt5uvJ5PeWE01eOgoTma9zsQRs6edNC21BPdGqiKbJ3K2m','21X01A6663@nrcmec.org','YENKUGARI','ASHVITH REDDY','male','','',10,0,'19','21X01A6663','','2026-03-30 09:09:22','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(169,'22X05A6601','$2y$10$pM1BtNTWxTvuBOlFwyBWmebSoSksKi2O90kxLDzES3ZRpBEPFOzp2','22X05A6601@nrcmec.org','AMBATI','MEHER SAI','male','','',10,0,'19','22X05A6601','','2026-03-30 09:09:22','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(170,'22X05A6602','$2y$10$RXSBSdwkHbZGjTwJUUO.weI4.UTN5rPIShFjxnFVuL7PicRJdf.Vu','22X05A6602@nrcmec.org','B','SAI KUMAR','male','','',10,0,'19','22X05A6602','','2026-03-30 09:09:22','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(171,'22X05A6603','$2y$10$u.B3iAH47lS773GA4slFWuJ8ahkVb96U96oP6cxKVUnqAEbSCS3.u','22X05A6603@nrcmec.org','KARNAPURAM','KARTHIK','male','','',10,0,'19','22X05A6603','','2026-03-30 09:09:22','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(172,'22X05A6604','$2y$10$Gggy9HaXX58Oxer54oQp6OmjmjqW5F7q2KaP//ofEZ3xD7tcqe0H.','22X05A6604@nrcmec.org','MATTAPALLI','TEJA','male','','',10,0,'19','22X05A6604','','2026-03-30 09:09:22','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(173,'22X05A6605','$2y$10$byNUiOGFX5DWzaQD6.ftC.Cnrhwc6tQ4P/UZcOev3TasJ1lpN6Rf6','22X05A6605@nrcmec.org','THALAKOKKULA','MADHAV','male','','',10,0,'19','22X05A6605','','2026-03-30 09:09:23','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(174,'22X05A6606','$2y$10$ssYETXCqV6.DISwUxs8I0.2Ia.s/UiWKCR7cbUpWgddWudxzd.Kq.','22X05A6606@nrcmec.org','VENKAIAHGARI','SAI PRAKASH','male','','',10,0,'19','22X05A6606','','2026-03-30 09:09:23','2026-03-30 09:09:37',1,1,19,'A',NULL,'alumni',2025,'alumni'),(175,'20X01A6601','$2y$10$8ftZcCDezIJ/DWswOYn0jei8gpA/JEQdaBj6bY8sWSD6ImZEf4FBi','20X01A6601@nrcmec.org','AREDDY','SATHVIK REDDY','male','','',12,0,'20','20X01A6601','','2026-03-30 09:18:26','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(176,'20X01A6602','$2y$10$PuNDPtvZeFSDeCkjGWY7tuI7A8E3m690yTPC/.3vRzoIctZXu8rji','20X01A6602@nrcmec.org','BADDAM','MANIDEEP REDDY','male','','',12,0,'20','20X01A6602','','2026-03-30 09:18:26','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(177,'20X01A6603','$2y$10$TsfSUtzlD.ssbC3WrqW/t.eec3k5.bERArDZZ5xDP.eYN8bunBnQ2','20X01A6603@nrcmec.org','BOJJA','SURESH','male','','',12,0,'20','20X01A6603','','2026-03-30 09:18:26','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(178,'20X01A6604','$2y$10$mE8laMK/cX4CsQFTIcdVq.znCLNz8H4H8GwOvEZudSMi.sVhDE0.6','20X01A6604@nrcmec.org','CHOKKA','CHOKKA DEVI VARAPRASAD','male','','',12,0,'20','20X01A6604','','2026-03-30 09:18:27','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(179,'20X01A6605','$2y$10$Y4sdPVoo3riXWCUiUhJmwOqGUzoUDdpDZGvx85dcPvoBN0mw8GxEm','20X01A6605@nrcmec.org','DASOJU','BHANU','male','','',12,0,'20','20X01A6605','','2026-03-30 09:18:27','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(180,'20X01A6606','$2y$10$rmhywL7jyNdd9S.R.XCe3OE0u35fMWgqyn/SNejno/71AvLPVBuhG','20X01A6606@nrcmec.org','BURRA','SRIKARUN','male','','',12,0,'20','20X01A6606','','2026-03-30 09:18:27','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(181,'20X01A6607','$2y$10$hzU2JUdycIF0sh/n9MAszOEEEmIKlzRMaCPRxOML/h07qjIgfNwVO','20X01A6607@nrcmec.org','EDA','THARUN REDDY','male','','',12,0,'20','20X01A6607','','2026-03-30 09:18:27','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(182,'20X01A6608','$2y$10$9qtpSNBWKDMiSOMnK1u55OIH8yDWPclE66w1F5lEJNKFsMIdvbC1O','20X01A6608@nrcmec.org','JOSHUA','RUFUS','male','','',12,0,'20','20X01A6608','','2026-03-30 09:18:28','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(183,'20X01A6609','$2y$10$f/mNMZRIncio603QQxVlRuXYCUTLFhGKQruUM.cjLlYk9gNodFE4S','20X01A6609@nrcmec.org','KATTA','SAMYUKTHA','female','','',12,0,'20','20X01A6609','','2026-03-30 09:18:28','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(184,'20X01A6610','$2y$10$eebaX6w4kznwDkIgO1buyuJcKlGUcZmpLOBHI100jB6epz1bvlvRG','20X01A6610@nrcmec.org','K','AJAY','male','','',12,0,'20','20X01A6610','','2026-03-30 09:18:28','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(185,'20X01A6611','$2y$10$JT2LcUdmWTbUCNU2pE9qHubxET9NXAV2bdaJZGNKkKzwlCczcx1iu','20X01A6611@nrcmec.org','MAMIDIPALLY','MOUNIKA RAJESHWARI','female','','',12,0,'20','20X01A6611','','2026-03-30 09:18:28','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(186,'20X01A6612','$2y$10$dwI3fOUZtOE3p2SYwXdCDewLVZ2CCpUPo8B5C3hTGJgDAv6lbKJrm','20X01A6612@nrcmec.org','YEDDULA','BHARGAVA REDDY','male','','',12,0,'20','20X01A6612','','2026-03-30 09:18:28','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(187,'20X01A6613','$2y$10$32sAyGLMYfy2YZu6Ibn1s.mKDz/tX4IE9.IsTg7wTsa5tbzOt0QH.','20X01A6613@nrcmec.org','NALLAVENI','SIDDU ABHILASH','male','','',12,0,'20','20X01A6613','','2026-03-30 09:18:28','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(188,'20X01A6614','$2y$10$w6jxsqLdwUmaviu0kcvTQ.n8Cvg8EIIIfPrzEs2FM5sHcbYrNGlN2','20X01A6614@nrcmec.org','NOMULA','VIJAY','male','','',12,0,'20','20X01A6614','','2026-03-30 09:18:29','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(189,'20X01A6615','$2y$10$KPxnx72XTHjXQjefD2k6Z.CCyaW3TEVxhNicPzReE67pcNJl.PzVW','20X01A6615@nrcmec.org','PODDATURI','ADHITYA','male','','',12,0,'20','20X01A6615','','2026-03-30 09:18:29','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(190,'20X01A6616','$2y$10$VFCTKZEqAOVDccvMOg4xFewm8OeW3SkhSM1uchWHDjpq30Aoks2gG','20X01A6616@nrcmec.org','PONNABOINA','VINAYAK YADAV','male','','',12,0,'20','20X01A6616','','2026-03-30 09:18:29','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(191,'20X01A6617','$2y$10$8g9sYo0pfdCGXcCmeH.42uKAttoOW24vNGUgzdTARlFpRc6i1qgXW','20X01A6617@nrcmec.org','PANDILLAPALLI','VISHNUVARDHAN REDDY','male','','',12,0,'20','20X01A6617','','2026-03-30 09:18:29','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(192,'20X01A6618','$2y$10$.ok0VgtlDchPZC0dxRrqeuxZVA57GEZYiGda.j1UUhgx0qIrlRuDq','20X01A6618@nrcmec.org','KULAKARNI','SAKETH','male','','',12,0,'20','20X01A6618','','2026-03-30 09:18:29','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(193,'20X01A6619','$2y$10$KNeeiwP5bPm9ddJGRdCbNOHNNp95PVNfgX8bxqtbcwIZ62X63fjU.','20X01A6619@nrcmec.org','SYEED','AZHAR','male','','',12,0,'20','20X01A6619','','2026-03-30 09:18:29','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(194,'20X01A6620','$2y$10$pnP0SAYbgR46yP4hiHdWfu7sP4afDE/J70dSunYfAwLa49m1MPRNG','20X01A6620@nrcmec.org','DUMPALA','VENKATA SAI','male','','',12,0,'20','20X01A6620','','2026-03-30 09:18:30','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(195,'21X05A6601','$2y$10$JufKodEVFjnEVqoWemeG8O9NkolGJ.fxtJJsr7f641j1MNBysw.LO','21X05A6601@nrcmec.org','ANTIGARI','SREEKANTH','male','','',12,0,'20','21X05A6601','','2026-03-30 09:18:30','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(196,'21X05A6602','$2y$10$45r59d7.de/0JIsrXIQYUu1hz8UbZhPe4fmIKA2qBPSdojrSR0og6','21X05A6602@nrcmec.org','ANUGURTHI','HARSHA TEJA','male','','',12,0,'20','21X05A6602','','2026-03-30 09:18:30','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(197,'21X05A6603','$2y$10$NAR7O0oxfeRaG0/TeHXu/uAuHv9yMteQ9aILaFhXFXnxkfMWR5yZC','21X05A6603@nrcmec.org','ARRA','POOJA','female','','',12,0,'20','21X05A6603','','2026-03-30 09:18:30','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(198,'21X05A6604','$2y$10$Qc8HygRy4ITSku/Nz4D1AuI6ro6Xdta3gh3NSdYAUkUCEYYBcTDTS','21X05A6604@nrcmec.org','BHUPATHI','PURNA CHANDRA RAO','male','','',12,0,'20','21X05A6604','','2026-03-30 09:18:30','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(199,'21X05A6605','$2y$10$KSCLy.e2nlFwF3eSvFex.OrN0swvsmfg7rxQAVuEIV4IoRWUgMuEq','21X05A6605@nrcmec.org','BOMMA','SAI GANESH','male','','',12,0,'20','21X05A6605','','2026-03-30 09:18:30','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(200,'21X05A6606','$2y$10$Cg89Mh2TlH7PRY7CaZ6SDOElzIBedfSY6sgRAIi6Lpxz2Eiaqwqpu','21X05A6606@nrcmec.org','CHARKA','JITHENDER DEVENDAR','male','','',12,0,'20','21X05A6606','','2026-03-30 09:18:31','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(201,'21X05A6607','$2y$10$YlZciL278y.Up/DC8p.2Ye/js4Kj0O0QGP.JLsQrg4TxSWuypmf1y','21X05A6607@nrcmec.org','CHILAKESI','AJAY KUMAR','male','','',12,0,'20','21X05A6607','','2026-03-30 09:18:31','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(202,'21X05A6608','$2y$10$eHHzi2n8Z9FfelumcmnhCOsFQ03h3ci7WvMCySpBY6Vqp7ZZ64U62','21X05A6608@nrcmec.org','CHILUMULA','KRANTHI KUMAR','male','','',12,0,'20','21X05A6608','','2026-03-30 09:18:31','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(203,'21X05A6609','$2y$10$c7fEjvQ0HKGW/wxQAA8sfOQ7w5NBG.a867reNixeiUEmgRSmPn9vO','21X05A6609@nrcmec.org','DODLA','VIVEK REDDY','male','','',12,0,'20','21X05A6609','','2026-03-30 09:18:31','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(204,'21X05A6610','$2y$10$0xlDkzK9ngx/dP2Gv4hIg.VG1F7CydeB.JpJl8gjGzeSqEK7IwiSi','21X05A6610@nrcmec.org','GANDRA','NAGENDHAR','male','','',12,0,'20','21X05A6610','','2026-03-30 09:18:31','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(205,'21X05A6611','$2y$10$tk7fYqjgX0y0.ddaJmvIJuplgPIEe9CXOvmDwD84sWN0v2lefP/l2','21X05A6611@nrcmec.org','GOLIPALLY','DINESH REDDY','male','','',12,0,'20','21X05A6611','','2026-03-30 09:18:31','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(206,'21X05A6612','$2y$10$6jrxpfjpa9o3/iBKiTzqq.raceMZU9p2Qt.qg.9yvfVd12G/yfw2q','21X05A6612@nrcmec.org','GUVVA','VEERESH','male','','',12,0,'20','21X05A6612','','2026-03-30 09:18:32','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(207,'21X05A6613','$2y$10$N2.4uY9XhDeULxd80e3AJuWC3EAXHjELKGhevu4LJ3igiM5n16qkq','21X05A6613@nrcmec.org','KALAGANI','BHAVITHA','female','','',12,0,'20','21X05A6613','','2026-03-30 09:18:32','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(208,'21X05A6614','$2y$10$v2Y0r4pMg/5qp4.MwTwedONz6e0pJ/foB9KhHpDUuKCi6KjkrBLVi','21X05A6614@nrcmec.org','KOMATIREDDY','NAVEEN REDDY','male','','',12,0,'20','21X05A6614','','2026-03-30 09:18:32','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(209,'21X05A6615','$2y$10$oYlJMXERnkC3GydTYz.bu.mWRLMoLIZETCkPxmllcRTj0nIaIrTYO','21X05A6615@nrcmec.org','KONDABOINA','RADHA KRISHNA','male','','',12,0,'20','21X05A6615','','2026-03-30 09:18:32','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(210,'21X05A6616','$2y$10$AJADbrmCzo/pzYrIcGXOc.emUDAfv2mBVFX9XVD8msfGyQOsp5age','21X05A6616@nrcmec.org','KOTA','HEMALATHA','female','','',12,0,'20','21X05A6616','','2026-03-30 09:18:32','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(211,'21X05A6617','$2y$10$ORNUbZCnXQ68qZc4/771kOV5eHpysLxidMPPMMXBdkyN8RmAOuPYC','21X05A6617@nrcmec.org','KOTAGIRI','KRISHNA','male','','',12,0,'20','21X05A6617','','2026-03-30 09:18:32','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(212,'21X05A6618','$2y$10$5xCmda7zJsfHDssEJzIfCu3dWTUvRXLAyWv2xWrYbCMSokVm2jSru','21X05A6618@nrcmec.org','KUMMARI','RAMESH','male','','',12,0,'20','21X05A6618','','2026-03-30 09:18:32','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(213,'21X05A6619','$2y$10$0J1DJ/qG.wfYfWP2wkV/N.QwYosA2WsD7jU2CeYmggx4w5uJ6pmoW','21X05A6619@nrcmec.org','MAGIRI','DHARMA TEJA','male','','',12,0,'20','21X05A6619','','2026-03-30 09:18:33','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(214,'21X05A6620','$2y$10$Z2dF4bk7pCN5TD9I4NGInOumlfDzGpuNjse9bGlGWQ99VvwW7Vpc2','21X05A6620@nrcmec.org','MANGILLIPALLY','VISHAL KUMAR','male','','',12,0,'20','21X05A6620','','2026-03-30 09:18:33','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(215,'21X05A6621','$2y$10$0CUguPO.sGrExHFfBgdVNehF2pYeRS/RjaqkzNm1hFapNLdeudlh2','21X05A6621@nrcmec.org','MANGILLIPALLY','CHAITHANYA ACHYUTHA','female','','',12,0,'20','21X05A6621','','2026-03-30 09:18:33','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(216,'21X05A6622','$2y$10$32gvEnn9/8HadgrKvmk3Ie4TSjRFhOibPUa1quk8CLlw1ZEnLhH3G','21X05A6622@nrcmec.org','MOHAMMAD','TAJUDDIN','male','','',12,0,'20','21X05A6622','','2026-03-30 09:18:33','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(217,'21X05A6623','$2y$10$v7LYp34v4XYwqq25smdjouY5CUaZFwuGTfjHKRfg.XQoJ4D9yjXWG','21X05A6623@nrcmec.org','PAALINI','MAHESH','male','','',12,0,'20','21X05A6623','','2026-03-30 09:18:33','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(218,'21X05A6624','$2y$10$1cApcHilxKEnEwT9GZuLrecUcMF7JHYLbWI82l68ZpzRodYHajuSa','21X05A6624@nrcmec.org','PALAKURTHI','SAI KIRAN','male','','',12,0,'20','21X05A6624','','2026-03-30 09:18:33','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(219,'21X05A6625','$2y$10$0E87eUFxdAVKOhRTue55E.gh8mNj34S4Sa/rJT1vN/V6czlzbsT5.','21X05A6625@nrcmec.org','PANTLA','SAI KUMAR','male','','',12,0,'20','21X05A6625','','2026-03-30 09:18:34','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(220,'21X05A6626','$2y$10$jBfvNd31S5rZzrNfP.6c/.aAKXV1aac5XOjR7DGPpJnRXnUyUZgHy','21X05A6626@nrcmec.org','P','RESHMA','female','','',12,0,'20','21X05A6626','','2026-03-30 09:18:34','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(221,'21X05A6627','$2y$10$iB9nzVHgeBMjExJYV5RHueUwOB0YpQyrLEg3ac3h7uD0SQTPChUs6','21X05A6627@nrcmec.org','RANGANAGARA','KEERTHANA','female','','',12,0,'20','21X05A6627','','2026-03-30 09:18:34','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(222,'21X05A6628','$2y$10$AND1KNH5n.ZTuzqCj6MErOMIcjXajKtmXcpOfcmAMVeEorYlCBovW','21X05A6628@nrcmec.org','RANGANAMONI','SATHISH','male','','',12,0,'20','21X05A6628','','2026-03-30 09:18:34','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(223,'21X05A6629','$2y$10$.AUmwlE0L7ozO131XRo6pO9WLgbTLVIh/nE.1RJaWIzif8DfSW27a','21X05A6629@nrcmec.org','S','AKHIL','male','','',12,0,'20','21X05A6629','','2026-03-30 09:18:34','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(224,'21X05A6630','$2y$10$5l7hMUHF6Lz8R8Oq/6iSBu0G8Na2DKBnefMpbWjVAupIPFZYbzhmO','21X05A6630@nrcmec.org','DHINAKAR','-','male','','',12,0,'20','21X05A6630','','2026-03-30 09:18:34','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(225,'21X05A6631','$2y$10$sR61Z3I48Np.QjJBL659feMYYSNxTPpIf.SnGYkogaFYm5aAURCea','21X05A6631@nrcmec.org','SALIKALA','ANIL','male','','',12,0,'20','21X05A6631','','2026-03-30 09:18:34','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(226,'21X05A6632','$2y$10$g6prDZzElj2TgG/Q4qmTKeSFbvjzyjy.FCso0TwFpQ58KzKrUZY6e','21X05A6632@nrcmec.org','SHAIK','SHAREEF','male','','',12,0,'20','21X05A6632','','2026-03-30 09:18:35','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(227,'21X05A6633','$2y$10$PvNiOKrvYkq9Y9wDzC62b.K3RXACN/6/eKqHRfUBWOcOn3Lp37Zpy','21X05A6633@nrcmec.org','SINGAVARAPU','CHANDRAKANTH','male','','',12,0,'20','21X05A6633','','2026-03-30 09:18:35','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(228,'21X05A6634','$2y$10$zFf6QR78wXkExbZD1H.3SO4Ke2gwSWCBbzwUX6PnI5/V6FkKk5ta6','21X05A6634@nrcmec.org','SYED','MOHAMMED SHAFI','male','','',12,0,'20','21X05A6634','','2026-03-30 09:18:35','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(229,'21X05A6635','$2y$10$E2YH3MWdlJJBXTIGEjzI9us.HPrI0WaL8OrwOYH0zY2Q2yP5777am','21X05A6635@nrcmec.org','THUPAKULA','VAMSHI KRISHNA','male','','',12,0,'20','21X05A6635','','2026-03-30 09:18:35','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(230,'21X05A6636','$2y$10$SlzoAyDmffsTzLy1C0yHKO3GxsSm7zdafATok3JWIljd4aveojPB6','21X05A6636@nrcmec.org','NAMANA','DIVISHA','female','','',12,0,'20','21X05A6636','','2026-03-30 09:18:36','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(231,'21X05A6637','$2y$10$I5GbI99L07YOzxDWSDTO/OzYab5I1oVUM4ooiJJfHrl.tWRyXlp2y','21X05A6637@nrcmec.org','PADIMALA','SAI RAM','male','','',12,0,'20','21X05A6637','','2026-03-30 09:18:36','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni'),(232,'21X05A6638','$2y$10$Gsx5E2tDVqrK6qNAqKSl5.J1O4ZBzz/3b4FfjB4Yua7WUsJWtKegi','21X05A6638@nrcmec.org','CHINTHAKINDI','ADITYA','male','','',12,0,'20','21X05A6638','','2026-03-30 09:18:36','2026-03-30 09:18:46',1,1,20,'A',NULL,'alumni',2024,'alumni');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sub_code` varchar(500) NOT NULL,
  `sub_name` varchar(500) NOT NULL,
  `class_id` int NOT NULL,
  `batch_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_subject_batch_class` (`batch_id`,`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'IS','Information Security',1,0),(2,'NP','Network Programming',1,0),(3,'SPM','Software Project Management',1,0),(4,'ES','Embeded Systems',1,0),(5,'MAD','Multimedia And Application Developement',1,0),(6,'MC','Mobile Computing',1,0),(7,'CG','Computer Graphics',2,0),(8,'ADS','Adv Data Stractures',2,0),(9,'CN','Computer Networks',2,0),(10,'OS','Opreting System',2,0),(11,'SE','Softwre Engineering',2,0),(12,'WT','Web Technology',0,0),(13,'WT','Web Technology',2,0),(14,'MS','MANAGEMENT SCIENCE',6,0),(15,'DP','DESIGN PATTERN',6,0),(16,'NMS','NETWORK MANAGEMENT SYSTEMS',6,0),(17,'DAA','DESIGN AND ANALYSIS OF ALGORITHMS',7,0),(18,'UNIX','UNIX',7,0),(19,'OOAD','OBJECT ORIENTED ANALYSIS AND DESIGN',7,0),(20,'ACN','ADV COMPUTER NETWORKS',7,0),(21,'AJP','ADV JAVA PROGAMMING',7,0),(22,'MS','MANAGEMENT SCIENCE',7,0),(23,'DC','DATA COMMUNICATION',8,0),(24,'PPL','PRINCIPLES OF PROGRAMINNG LANGUAGES',8,0),(25,'OOPS','OBJECT ORIENTED PROGRAMMING',8,0),(26,'CO','COMPUTER ORGANIZATION AND ARCHITECTURE',8,0),(27,'DBMS','DATABASE MANAGEMENT SYSTEMS',8,0),(28,'ACD','AUTOMATA AND COMPILER DESIGN',8,0),(29,'','',1,0),(30,'12333','Anatomy',19,0),(31,'11001010','Muscle training',19,0),(32,'BEFA','Befa',27,8);
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_settings`
--

DROP TABLE IF EXISTS `support_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_settings` (
  `id` int NOT NULL,
  `support_email` varchar(255) NOT NULL DEFAULT '',
  `whatsapp_number` varchar(30) NOT NULL DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `smtp_host` varchar(255) NOT NULL DEFAULT '',
  `smtp_port` int NOT NULL DEFAULT '587',
  `smtp_secure` varchar(10) NOT NULL DEFAULT 'tls',
  `smtp_username` varchar(255) NOT NULL DEFAULT '',
  `smtp_password` varchar(255) NOT NULL DEFAULT '',
  `smtp_from_email` varchar(255) NOT NULL DEFAULT '',
  `smtp_from_name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_settings`
--

LOCK TABLES `support_settings` WRITE;
/*!40000 ALTER TABLE `support_settings` DISABLE KEYS */;
INSERT INTO `support_settings` VALUES (1,'im8937861@gmail.com','','2026-03-04 13:29:32','smtp.gmail.com',587,'tls','im8937861@gmail.com','ctpx unjg raxg vmce','im8937861@gmail.com','AIML Support Desk');
/*!40000 ALTER TABLE `support_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `syllabus`
--

DROP TABLE IF EXISTS `syllabus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `syllabus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `syllabus_name` varchar(500) NOT NULL,
  `class_id` int NOT NULL,
  `batch_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_syllabus_batch_class` (`batch_id`,`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `syllabus`
--

LOCK TABLES `syllabus` WRITE;
/*!40000 ALTER TABLE `syllabus` DISABLE KEYS */;
/*!40000 ALTER TABLE `syllabus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `year_batch`
--

DROP TABLE IF EXISTS `year_batch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `year_batch` (
  `id` int NOT NULL AUTO_INCREMENT,
  `batch` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `year_batch`
--

LOCK TABLES `year_batch` WRITE;
/*!40000 ALTER TABLE `year_batch` DISABLE KEYS */;
INSERT INTO `year_batch` VALUES (8,'2023-2027'),(9,'2022-2026'),(10,'2021-2025'),(12,'2020-2024'),(13,'2019-2023');
/*!40000 ALTER TABLE `year_batch` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-30 15:12:42
