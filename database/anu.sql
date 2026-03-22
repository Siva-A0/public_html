-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 29, 2014 at 05:07 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `anu`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE IF NOT EXISTS `achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` tinyint(4) NOT NULL,
  `achievement_desc` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'customer id is auto increament and primary key',
  `adminname` varchar(200) NOT NULL COMMENT 'user name',
  `password` varchar(255) NOT NULL COMMENT 'customer password is stored',
  `mail_id` varchar(500) NOT NULL COMMENT 'customer mail_id is stored',
  `firstname` varchar(500) NOT NULL COMMENT 'customer first name is stored',
  `lastname` varchar(500) NOT NULL COMMENT 'customer last name is stored',
  `gender` varchar(200) NOT NULL COMMENT 'gender',
  `address` varchar(255) NOT NULL COMMENT 'customer address is stored',
  `mobile_no` bigint(20) NOT NULL COMMENT 'customers mobile no is stored',
  `qualification` varchar(200) NOT NULL COMMENT 'Qualification',
  `image` varchar(500) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'customer created date and time is stored',
  `last_access` timestamp NULL DEFAULT NULL COMMENT 'customer login time and date is stored',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`adminname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Users details are stored' AUTO_INCREMENT=4 ;

-- INSERT INTO admin 
-- (adminname, password, mail_id, firstname, lastname, gender, address, mobile_no, qualification, image, last_access)
-- VALUES 
-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE IF NOT EXISTS `alumni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL,
  `alumni_desc` blob NOT NULL,
  `alumni_img` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`id`, `batch_id`, `alumni_desc`, `alumni_img`) VALUES
(1, 2, 0x686169, 'Tulips.jpg'),
(2, 2, '', 'Penguins.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category`) VALUES
(1, 'document'),
(2, 'non_document');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_code` varchar(500) NOT NULL,
  `class_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `class_code`, `class_name`) VALUES
(0, 'passout', 'Pass Out'),
(1, 'IV IT', '4th Year IT SEM I'),
(2, 'III IT', '3rd Year IT SEM I'),
(3, 'II IT', '2nd Year IT SEM I'),
(4, 'I IT', '1st Year IT SEM I'),
(6, 'IV IT', '4th Year IT SEM II'),
(7, 'III IT', '3rd Year IT SEM II'),
(8, 'II IT', '2nd Year IT SEM II');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `type` varchar(100) NOT NULL,
  `qualification` varchar(500) NOT NULL,
  `designation` varchar(500) NOT NULL,
  `comment` blob NOT NULL,
  `image` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `name`, `type`, `qualification`, `designation`, `comment`, `image`) VALUES
(1, 'prasad', 'hod', 'phd', 'hod', 0x717171717171717171, 'hod.png'),
(2, 'prasad', 'principal', 'phd', 'professor', 0x707070, 'principal.png'),
(3, 'prasad', 'chairman', 'phd', 'professor', 0x70726f66, 'chairman.png');

-- --------------------------------------------------------

--
-- Table structure for table `committee`
--

CREATE TABLE IF NOT EXISTS `committee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `committee_cat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `member_name` varchar(255) NOT NULL DEFAULT '',
  `member_about` text,
  `member_image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `committee`
--

INSERT INTO `committee` (`id`, `committee_cat_id`, `user_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `committee_cat`
--

CREATE TABLE IF NOT EXISTS `committee_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `committee_cat`
--

INSERT INTO `committee_cat` (`id`, `category_name`) VALUES
(1, 'Chairman'),
(2, 'Vice Chairman'),
(3, 'President'),
(4, 'Vice-President'),
(5, 'Secretary'),
(6, 'Join-Secretary'),
(7, 'Tresurer'),
(8, 'join-Tresurer');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type_id` int(11) NOT NULL,
  `event_name` varchar(500) NOT NULL,
  `event_desc` blob NOT NULL,
  `event_address` varchar(500) NOT NULL,
  `event_date` date NOT NULL,
  `reg_frm_date` date NOT NULL,
  `reg_to_date` date NOT NULL,
  `is_registration` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_type_id`, `event_name`, `event_desc`, `event_address`, `event_date`, `reg_frm_date`, `reg_to_date`, `is_registration`) VALUES
(1, 1, 'bkh', 0x206d766e, 'mbvnj', '2014-01-09', '2014-01-02', '2014-01-31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `event_reg`
--

CREATE TABLE IF NOT EXISTS `event_reg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_results`
--

CREATE TABLE IF NOT EXISTS `event_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `award` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE IF NOT EXISTS `event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `event_types`
--

INSERT INTO `event_types` (`id`, `event_type`) VALUES
(1, 'AIML'),
(2, 'AIML Department');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `description` blob NOT NULL,
  `image_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `highlights`
--

CREATE TABLE IF NOT EXISTS `highlights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `high_light` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `highlights`
--

INSERT INTO `highlights` (`id`, `type`, `high_light`) VALUES
(14, 2, 0x48616920546865204d61747465722041626f7574204465706172746d656e74204576656e7473);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE IF NOT EXISTS `materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_id` varchar(500) NOT NULL,
  `material_name` varchar(500) NOT NULL,
  `mater_file` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `placements`
--

CREATE TABLE IF NOT EXISTS `placements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` tinyint(4) NOT NULL,
  `placement_desc` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `placements`
--

INSERT INTO `placements` (`id`, `category_id`, `placement_desc`) VALUES
(2, 1, 0x323030382d313220426174636820506c6163616d656e74732424706c6163656d656e74732e646f6378);

-- --------------------------------------------------------

--
-- Table structure for table `prev_papers`
--

CREATE TABLE IF NOT EXISTS `prev_papers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subj_id` int(11) NOT NULL,
  `paper_name` varchar(500) NOT NULL,
  `paper_file` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_code` varchar(500) NOT NULL,
  `section_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `class_id`, `section_code`, `section_name`) VALUES
(1, 1, 'IV IT A SEC ', '4th IT A Section'),
(2, 2, 'III IT A Sec', '3rd IT A Sec'),
(3, 3, 'II IT A Sec', '2nd IT A Section'),
(4, 3, 'II IT B Sec', '2nd IT B Section'),
(5, 4, 'I IT A Sec', '1st IT A Section'),
(6, 4, 'I IT B Sec', '1st IT B Section');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE IF NOT EXISTS `faculty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_categ_id` int(11) NOT NULL,
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
  `image` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `faculty_category`
--

CREATE TABLE IF NOT EXISTS `faculty_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `faculty_category`
--

INSERT INTO `faculty_category` (`id`, `category_name`) VALUES
(1, 'Teaching'),
(2, 'Non Teaching');

-- --------------------------------------------------------

--
-- Table structure for table `stream`
--

CREATE TABLE IF NOT EXISTS `stream` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stream_code` varchar(500) NOT NULL,
  `stream_name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `stream`
--

INSERT INTO `stream` (`id`, `stream_code`, `stream_name`) VALUES
(1, 'IT', 'Information Technology'),
(2, 'CSE', 'Computer science & Enginering'),
(5, 'ECE', 'Electronics and Communication Engineering'),
(6, 'EEE', 'Electronics and Electrical Engineering'),
(7, 'Other', 'Any Other Branch');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_code` varchar(500) NOT NULL,
  `sub_name` varchar(500) NOT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `sub_code`, `sub_name`, `class_id`) VALUES
(1, 'IS', 'Information Security', 1),
(2, 'NP', 'Network Programming', 1),
(3, 'SPM', 'Software Project Management', 1),
(4, 'ES', 'Embeded Systems', 1),
(5, 'MAD', 'Multimedia And Application Developement', 1),
(6, 'MC', 'Mobile Computing', 1),
(7, 'CG', 'Computer Graphics', 2),
(8, 'ADS', 'Adv Data Stractures', 2),
(9, 'CN', 'Computer Networks', 2),
(10, 'OS', 'Opreting System', 2),
(11, 'SE', 'Softwre Engineering', 2),
(12, 'WT', 'Web Technology', 0),
(13, 'WT', 'Web Technology', 2),
(14, 'MS', 'MANAGEMENT SCIENCE', 6),
(15, 'DP', 'DESIGN PATTERN', 6),
(16, 'NMS', 'NETWORK MANAGEMENT SYSTEMS', 6),
(17, 'DAA', 'DESIGN AND ANALYSIS OF ALGORITHMS', 7),
(18, 'UNIX', 'UNIX', 7),
(19, 'OOAD', 'OBJECT ORIENTED ANALYSIS AND DESIGN', 7),
(20, 'ACN', 'ADV COMPUTER NETWORKS', 7),
(21, 'AJP', 'ADV JAVA PROGAMMING', 7),
(22, 'MS', 'MANAGEMENT SCIENCE', 7),
(23, 'DC', 'DATA COMMUNICATION', 8),
(24, 'PPL', 'PRINCIPLES OF PROGRAMINNG LANGUAGES', 8),
(25, 'OOPS', 'OBJECT ORIENTED PROGRAMMING', 8),
(26, 'CO', 'COMPUTER ORGANIZATION AND ARCHITECTURE', 8),
(27, 'DBMS', 'DATABASE MANAGEMENT SYSTEMS', 8),
(28, 'ACD', 'AUTOMATA AND COMPILER DESIGN', 8);

-- --------------------------------------------------------

--
-- Table structure for table `syllabus`
--

CREATE TABLE IF NOT EXISTS `syllabus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `syllabus_name` varchar(500) NOT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'customer id is auto increament and primary key',
  `username` varchar(200) NOT NULL COMMENT 'user name',
  `password` varchar(255) NOT NULL COMMENT 'user password is stored',
  `mail_id` varchar(500) NOT NULL COMMENT 'user mail_id is stored',
  `firstname` varchar(50) NOT NULL COMMENT 'user first name is stored',
  `lastname` varchar(50) NOT NULL COMMENT 'user last name is stored',
  `gender` varchar(20) NOT NULL COMMENT 'gender',
  `address` varchar(255) NOT NULL COMMENT 'user address is stored',
  `mobile_no` bigint(20) NOT NULL COMMENT 'user mobile no is stored',
  `batch_id` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  `section` varchar(10) NOT NULL COMMENT 'section',
  `admission_id` varchar(300) NOT NULL COMMENT 'Admission Id',
  `image` varchar(500) NOT NULL COMMENT 'Image Of User',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'customer created date and time is stored',
   `last_access` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Users details are stored' AUTO_INCREMENT=76 ;

-- --------------------------------------------------------

--
-- Table structure for table `year_batch`
--

CREATE TABLE IF NOT EXISTS `year_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `year_batch`
--

INSERT INTO `year_batch` (`id`, `batch`) VALUES
(1, '2008-12 Batch'),
(2, '2009-13 Batch'),
(3, '2010-14 Batch'),
(4, '2011-15 Batch'),
(5, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `support_settings`
--

CREATE TABLE IF NOT EXISTS `support_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `support_email` varchar(255) NOT NULL DEFAULT '',
  `whatsapp_number` varchar(30) NOT NULL DEFAULT '',
  `smtp_host` varchar(255) NOT NULL DEFAULT '',
  `smtp_port` int(11) NOT NULL DEFAULT 587,
  `smtp_secure` varchar(10) NOT NULL DEFAULT 'tls',
  `smtp_username` varchar(255) NOT NULL DEFAULT '',
  `smtp_password` varchar(255) NOT NULL DEFAULT '',
  `smtp_from_email` varchar(255) NOT NULL DEFAULT '',
  `smtp_from_name` varchar(255) NOT NULL DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `support_settings`
--

INSERT INTO `support_settings` (`id`, `support_email`, `whatsapp_number`, `smtp_host`, `smtp_port`, `smtp_secure`, `smtp_username`, `smtp_password`, `smtp_from_email`, `smtp_from_name`) VALUES
(1, '', '', '', 587, 'tls', '', '', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
