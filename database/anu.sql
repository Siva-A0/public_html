-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 26, 2026 at 04:41 AM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u400022604_pragya`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `category_id` tinyint(4) NOT NULL,
  `achievement_desc` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`id`, `category_id`, `achievement_desc`) VALUES
(1, 1, 0x5b4150503a53545544454e543a43455254494649434154494f4e5d5b49443a323378303161363661305d20526f726f6e6f612044205b323378303161363661305d202d204973737565723a206e7074656c207c20417265613a2044656570206c6561726e696e67202d20416920666f72206564756361746f7265732424636572745f323378303161363661305f32303236303332323138323234365f313437392e706e67),
(2, 1, 0x5b4150503a53545544454e543a414348494556454d454e545d5b49443a323378303161363661305d20526f726f6e6f612044205b323378303161363661305d202d20436f6c6c6567653a2053696464686172746861207c205468656d653a204861636b6174686f6e202d205365636f6e6420706c6163652424616368765f323378303161363661305f32303236303332323138323434375f383239332e706466),
(3, 1, 0x5b4150503a464143554c54593a43455254494649434154494f4e5d5b49443a325d20536976612054656a6173205b466163756c74792049443a20325d202d204973737565723a206e7074656c207c20417265613a2044656570206c6561726e696e67202d20416920666f72206564756361746f7265732424666163636572745f325f32303236303332323138323533325f383133352e706e67);

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `title`, `created_at`) VALUES
(1, 'Added new course AI-401', '2026-02-25 10:11:23'),
(2, 'Updated staff profile', '2026-02-25 10:11:23'),
(3, 'Created Tech Fest Event', '2026-02-25 10:11:23'),
(4, 'Uploaded gallery images', '2026-02-25 10:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL COMMENT 'customer id is auto increament and primary key',
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
  `created_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'customer created date and time is stored',
  `last_access` timestamp NULL DEFAULT NULL COMMENT 'customer login time and date is stored'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Users details are stored';

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `adminname`, `password`, `mail_id`, `firstname`, `lastname`, `gender`, `address`, `mobile_no`, `qualification`, `image`, `created_on`, `last_access`) VALUES
(3, 'prasad', '$2y$10$O2IVtfCld/qzIBlJiF.Cxe3A1.MdeEJrafNn.KiUcOmb6kABRUtge', 'venkatavaraprasad12@gmail.com', 'gade', 'venkat', 'Male', 'guntur', 9030114200, 'btech', 'admin_3_20260317182800_029b3b01.png', '2026-03-17 17:28:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `alumni_desc` blob NOT NULL,
  `alumni_img` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`id`, `batch_id`, `alumni_desc`, `alumni_img`) VALUES
(1, 2, 0x686169, 'Tulips.jpg'),
(2, 2, '', 'Penguins.jpg');

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `class_code` varchar(500) NOT NULL,
  `class_name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `class_code`, `class_name`) VALUES
(22, 'Y1-S1', '1st Year AIML SEM I'),
(23, 'Y1-S2', '1st Year AIML SEM II'),
(24, 'Y2-S1', '2nd Year AIML SEM I'),
(25, 'Y2-S2', '2nd Year AIML SEM II'),
(26, 'Y3-S1', '3rd Year AIML SEM I'),
(27, 'Y3-S2', '3rd Year AIML SEM II'),
(28, 'Y4-S1', '4th Year AIML SEM I'),
(29, 'Y4-S2', '4th Year AIML SEM II');



-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `type` varchar(100) NOT NULL,
  `qualification` varchar(500) NOT NULL,
  `designation` varchar(500) NOT NULL,
  `comment` blob NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `name`, `type`, `qualification`, `designation`, `comment`, `image`) VALUES
(1, 'prasad', 'hod', 'phd', 'hod', 0x4d722e20476164652056656e6b617461205661726120507261736164206973207468652048656164206f6620746865204465706172746d656e742028484f4429206f66204172746966696369616c20496e74656c6c6967656e636520616e64204d616368696e65204c6561726e696e67202841494d4c29206174204e52434d20456e67696e656572696e6720436f6c6c6567652e20486520697320612064656469636174656420666163756c7479206d656d6265722077697468207374726f6e67206b6e6f776c6564676520696e204172746966696369616c20496e74656c6c6967656e63652c204d616368696e65204c6561726e696e672c20616e64206d6f6465726e20746563686e6f6c6f676965732e20486520776f726b73206163746976656c7920746f2067756964652073747564656e747320696e2061636164656d6963206c6561726e696e672c2072657365617263682c20616e642070726163746963616c20736b696c6c732e20556e64657220686973206c6561646572736869702c207468652041494d4c206465706172746d656e7420656e636f75726167657320696e6e6f766174696f6e2c20746563686e6963616c20646576656c6f706d656e742c20616e642070726f6a6563742d6261736564206c6561726e696e672e2048697320737570706f727420616e642067756964616e63652068656c702073747564656e7473206275696c64207374726f6e6720746563686e6963616c20616e642070726f66657373696f6e616c206162696c69746965732e, 'ITHOD.png'),
(2, 'R. Lokanadham', 'principal', 'phd', 'principal', 0x44722e20522e204c6f6b616e616468616d20697320746865205072696e636970616c206f66204e617273696d686120526564647920456e67696e656572696e6720436f6c6c6567652e2048652069732061206465646963617465642061636164656d696369616e2077697468207661737420657870657269656e636520696e20656e67696e656572696e6720656475636174696f6e20616e642072657365617263682e20486520697320636f6d6d697474656420746f20696d70726f76696e672061636164656d6963207374616e646172647320616e642073747564656e7420646576656c6f706d656e742e20556e64657220686973206c6561646572736869702c2074686520636f6c6c65676520666f6375736573206f6e207175616c69747920656475636174696f6e2c20696e6e6f766174696f6e2c20616e64206469736369706c696e652e204869732067756964616e6365206d6f746976617465732073747564656e747320746f206163686965766520746563686e6963616c206b6e6f776c6564676520616e642070726f66657373696f6e616c20737563636573732e, 'principal.png'),
(3, 'Sri Jakkula Narsimha Reddy', 'chairman', 'phd', 'chariman', 0x537269204a616b6b756c61204e617273696d68612052656464792069732074686520666f756e64657220616e642063686169726d616e206f66204e52434d20456e67696e656572696e6720436f6c6c6567652e204865206973206120766973696f6e617279206c65616465722077686f20697320636f6d6d697474656420746f2070726f6d6f74696e67207175616c69747920656475636174696f6e20616e642061636164656d696320657863656c6c656e63652e204869732064656469636174696f6e20616e64206861726420776f726b2068656c70656420696e2065737461626c697368696e672074686520696e737469747574696f6e20746f2070726f7669646520746563686e6963616c20656475636174696f6e20746f2073747564656e74732e, 'chairman.png'),
(4, 'Mr. Mohan Babu', 'director', '', 'director', 0x4d722e204d6f68616e204261627520697320746865204469726563746f72206f66204e617273696d686120526564647920456e67696e656572696e6720436f6c6c6567652e20486520706c61797320616e20696d706f7274616e7420726f6c6520696e207468652061646d696e697374726174696f6e20616e6420646576656c6f706d656e74206f662074686520696e737469747574696f6e2e20486973206c6561646572736869702068656c70732074686520636f6c6c656765206d61696e7461696e2068696768207374616e646172647320696e20656475636174696f6e20616e64206469736369706c696e652e20486520737570706f7274732061637469766974696573207468617420656e636f75726167652073747564656e742067726f7774682c20696e6e6f766174696f6e2c20616e6420746563686e6963616c206c6561726e696e672e204869732064656469636174696f6e20636f6e747269627574657320746f20746865206f766572616c6c2070726f677265737320616e642073756363657373206f662074686520636f6c6c6567652e, 'director.png');

-- --------------------------------------------------------

--
-- Table structure for table `committee`
--

CREATE TABLE `committee` (
  `id` int(11) NOT NULL,
  `committee_cat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `member_name` varchar(255) NOT NULL DEFAULT '',
  `member_about` text DEFAULT NULL,
  `member_image` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `committee`
--

INSERT INTO `committee` (`id`, `committee_cat_id`, `user_id`, `member_name`, `member_about`, `member_image`) VALUES
(1, 1, 0, 'Siva', 'jkndndjn2', 'committee_1772634619_7152.jpg'),
(2, 3, 0, 'giyu', 'calm', 'committee_1772633048_8340.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `committee_cat`
--

CREATE TABLE `committee_cat` (
  `id` int(11) NOT NULL,
  `category_name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_type_id` int(11) NOT NULL,
  `event_name` varchar(500) NOT NULL,
  `event_desc` blob NOT NULL,
  `event_address` varchar(500) NOT NULL,
  `event_date` date NOT NULL,
  `reg_frm_date` date NOT NULL,
  `reg_to_date` date NOT NULL,
  `is_registration` tinyint(4) NOT NULL,
  `is_public` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'controls public events visibility',
  `show_in_gallery` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'controls gallery visibility'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_type_id`, `event_name`, `event_desc`, `event_address`, `event_date`, `reg_frm_date`, `reg_to_date`, `is_registration`, `is_public`, `show_in_gallery`) VALUES
(5, 3, 'Farewell Celebration - 2026', 0x4173207468652061636164656d6963207965617220647261777320746f206120636c6f73652c20776520636f6d6520746f67657468657220746f2063656c6562726174652061207369676e69666963616e74206d696c6573746f6e6520e28094207468652067726164756174696f6e206f66206f75722072656d61726b61626c6520436c617373206f6620323032362e2054686973206661726577656c6c206576656e74206973206120686561727466656c74207472696275746520746f207468656972206a6f75726e6579206f662067726f7774682c206c6561726e696e672c20616e642063616d617261646572696520666f726765642077697468696e206f75722063616d7075732e200d0a0d0a46726f6d206c6174652d6e696768742073747564792073657373696f6e7320746f2073706972697465642063756c747572616c20666573746976616c732c2066726f6d20636c617373726f6f6d20627265616b7468726f7567687320746f206669656c6420747269756d7068732c206f7572206772616475617465732068617665206c65667420616e20696e64656c69626c65206d61726b206f6e20636f6c6c656765206c6966652e20546865792068617665206e6f74206f6e6c7920657863656c6c65642061636164656d6963616c6c7920627574206861766520616c736f206c656420776974682070617373696f6e2c20696e7370697265642070656572732c20616e6420656d626f646965642074686520737069726974206f6620657863656c6c656e636520616e6420696e746567726974792e0d0a0d0a576520686f6e6f7220746865697220616368696576656d656e74732c206368657269736820746865206d656d6f72696573207368617265642c20616e642065787072657373206f757220646565706573742067726174697475646520746f2074686520666163756c74792c2066616d696c6965732c20616e6420667269656e64732077686f206861766520737570706f72746564207468656d2e20417320746865792073746570206265796f6e64206f757220676174657320696e746f206120776f726c64206f66206e657720706f73736962696c69746965732c2077652073656e64207468656d206f666620776974682070726964652c20686f70652c20616e6420756e7761766572696e6720656e636f75726167656d656e742e200d0a0d0a54686973206973206e6f74206120676f6f646279652c2062757420612063656c6562726174696f6e206f6620626567696e6e696e67732e20546f206f75722067726164756174696e672073747564656e74733a206d617920796f75722066757475726573206265206272696768742c20626f6c642c20616e6420626f756e646c6573732e, 'SUGUNAVATHI AMPHITHEATRE', '2026-04-04', '2026-04-04', '2026-04-04', 0, 1, 1),
(6, 4, 'PTM (Parent-Teacher Meeting)', 0x4120666f726d616c20616e6420636f6c6c61626f7261746976652073657373696f6e20776865726520636f6c6c65676520666163756c747920616e6420706172656e7473206469736375737320612073747564656e74e28099732061636164656d69632070726f67726573732c20617474656e64616e63652c206265686176696f722c20616e64206f766572616c6c20646576656c6f706d656e742e20497420666f7374657273207374726f6e6720686f6d652d696e737469747574696f6e20636f6d6d756e69636174696f6e20746f20737570706f72742073747564656e7420737563636573732e, 'M.T BLOCK(SEMINAR HALL)', '2026-04-12', '2026-04-12', '2026-04-12', 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `event_reg`
--

CREATE TABLE `event_reg` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_results`
--

CREATE TABLE `event_results` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `award` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE `event_types` (
  `id` int(11) NOT NULL,
  `event_type` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `event_types`
--

INSERT INTO `event_types` (`id`, `event_type`) VALUES
(3, 'Farewell Fiesta'),
(1, 'MBA'),
(2, 'MBA Department'),
(4, 'Parent-Teacher Meeting');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
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
  `password` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



-- --------------------------------------------------------

--
-- Table structure for table `faculty_category`
--

CREATE TABLE `faculty_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `faculty_category`
--

INSERT INTO `faculty_category` (`id`, `category_name`) VALUES
(1, 'Faculty');





-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `description` blob NOT NULL,
  `image_name` varchar(500) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `event_id`, `name`, `description`, `image_name`, `category_id`) VALUES
(1, 1, 'Luffy', 0x6b696e67206f66207468652070697261746573, 'luffy.jpg', 3),
(6, 3, 'tg', 0x6e206b, 'tg_20260312071832_56bb6ff4.jpg', 4),
(8, 0, 'Luffy', 0x69616a61736369636a617361, 'luffy_20260312145000_11d102e3.jpg', 5),
(9, 0, 'chiller', '', 'chiller_20260312145023_2a4c4924.jpg', 5),
(10, 0, 'vvv', '', 'vvv_20260312145042_c1045ac1.jpg', 5),
(11, 0, 'momo', '', 'momo_20260312145103_21399528.jpg', 5),
(12, 0, 'chiller', '', 'chiller_20260312145121_c07caf3d.jpg', 5),
(13, 0, 'momo', '', 'momo_20260312145135_bf823017.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_category`
--

CREATE TABLE `gallery_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(500) NOT NULL,
  `linked_event_id` int(11) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `gallery_category`
--

INSERT INTO `gallery_category` (`id`, `category_name`, `linked_event_id`, `sort_order`, `is_active`) VALUES
(3, 'bkh', 1, 3, 1),
(4, 'Free Fire', 3, 2, 1),
(5, 'Sample', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `highlights`
--

CREATE TABLE `highlights` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `high_light` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `highlights`
--

INSERT INTO `highlights` (`id`, `type`, `high_light`) VALUES
(14, 2, 0x48616920546865204d61747465722041626f7574204465706172746d656e74204576656e7473),
(15, 1, 0x41696d6c2069732074686520746f70);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `sub_id` varchar(500) NOT NULL,
  `material_name` varchar(500) NOT NULL,
  `mater_file` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `sub_id`, `material_name`, `mater_file`) VALUES
(2, '32', 'Befa', 'BEFA UNIT-1 AIML.pptx');



-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(45) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token_hash`, `expires_at`, `used_at`, `created_at`, `ip`, `user_agent`) VALUES
(1, 79, 'f7f061d9ea84ff55359eb23851140f50e9079fe5e92dbe0cf5c72f34a5db007f', '2026-03-16 07:43:58', NULL, '2026-03-16 06:43:58', '103.51.55.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0'),
(2, 77, 'ef526e6163f29fe49c888b750c6e0d2fce197af48a5500a832bb0d7ad3c1a74c', '2026-03-16 07:45:32', NULL, '2026-03-16 06:45:32', '103.51.55.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36'),
(3, 77, '3b5d4de0167d648b573d5e74381afd0c181c58378fe9ce5d040cfc538aa2c858', '2026-03-16 07:48:46', '2026-03-16 06:49:46', '2026-03-16 06:48:46', '103.51.55.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36'),
(4, 79, '48bcee19c84b4c48dc10bb501ed33de438dbb75e222f6ebbb8647e046699f3e3', '2026-03-16 07:57:34', NULL, '2026-03-16 06:57:34', '103.51.55.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0'),
(5, 79, 'af3e4b8e091e0bd180b6d3f1dd6a35b137edce0f22de5245d87ba2ac3385bf32', '2026-03-16 07:58:49', NULL, '2026-03-16 06:58:49', '103.51.55.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0'),
(6, 77, '706323938b30bd3120303d01f760152e038f02777267b8bb0a0969926c300670', '2026-03-16 07:58:50', NULL, '2026-03-16 06:58:50', '103.51.55.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36'),
(7, 77, '2dbd4a682eb392c7a0939a48f37bad8424f6f7d38f127e88539afa31c275ac49', '2026-03-16 08:02:20', '2026-03-16 07:03:31', '2026-03-16 07:02:20', '103.51.55.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36');

-- --------------------------------------------------------

--
-- Table structure for table `placements`
--

CREATE TABLE `placements` (
  `id` int(11) NOT NULL,
  `category_id` tinyint(4) NOT NULL,
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
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `placements`
--

INSERT INTO `placements` (`id`, `category_id`, `placement_desc`, `academic_year`, `batch_label`, `student_name`, `course_branch`, `company_name`, `role_title`, `package_label`, `package_sort`, `profile_photo`, `is_featured`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(5, 3, 0x6a736f6e3a3a7b2261636164656d69635f79656172223a22323032352d3236222c2262617463685f6c6162656c223a2232303236222c2273747564656e745f6e616d65223a225072616268616b61722043686175626579222c22636f757273655f6272616e6368223a2241494d4c222c22636f6d70616e795f6e616d65223a224c554d454e20546563686e6f6c6f67696573222c22726f6c655f7469746c65223a22222c227061636b6167655f6c6162656c223a22372e34204c5041222c227061636b6167655f736f7274223a6e756c6c2c2270726f66696c655f70686f746f223a22706c6163656d656e745f7072616268616b61725f636861756265795f32303236303332353130333734355f63663865653665322e6a7067222c2269735f6665617475726564223a312c22736f72745f6f72646572223a302c2269735f616374697665223a312c22706c6163656d656e745f64657363223a225072616268616b6172204368617562657920706c61636564206174204c554d454e20546563686e6f6c6f67696573227d, '2025-26', '2026', 'Prabhakar Chaubey', 'AIML', 'LUMEN Technologies', '', '7.4 LPA', NULL, 'placement_prabhakar_chaubey_20260325103745_cf8ee6e2.jpg', 1, 0, 1, '2026-03-25 09:02:17', '2026-03-25 09:37:45'),
(6, 3, 0x6a736f6e3a3a7b2261636164656d69635f79656172223a22323032352d3236222c2262617463685f6c6162656c223a2232303236222c2273747564656e745f6e616d65223a2253616d616c612053696e646875205072697961222c22636f757273655f6272616e6368223a2241494d4c222c22636f6d70616e795f6e616d65223a224c554d454e20546563686e6f6c6f67696573222c22726f6c655f7469746c65223a22222c227061636b6167655f6c6162656c223a22372e34204c5041222c227061636b6167655f736f7274223a6e756c6c2c2270726f66696c655f70686f746f223a22706c6163656d656e745f73616d616c615f73696e6468755f70726979615f32303236303332353130333830375f65363934316239312e6a7067222c2269735f6665617475726564223a312c22736f72745f6f72646572223a302c2269735f616374697665223a312c22706c6163656d656e745f64657363223a2253616d616c612053696e64687520507269796120706c61636564206174204c554d454e20546563686e6f6c6f67696573227d, '2025-26', '2026', 'Samala Sindhu Priya', 'AIML', 'LUMEN Technologies', '', '7.4 LPA', NULL, 'placement_samala_sindhu_priya_20260325103807_e6941b91.jpg', 1, 0, 1, '2026-03-25 09:03:11', '2026-03-25 09:39:24'),
(7, 3, 0x6a736f6e3a3a7b2261636164656d69635f79656172223a22323032352d3236222c2262617463685f6c6162656c223a2232303236222c2273747564656e745f6e616d65223a22562e20536169205072616b617368222c22636f757273655f6272616e6368223a2241494d4c222c22636f6d70616e795f6e616d65223a2256697274757361222c22726f6c655f7469746c65223a22222c227061636b6167655f6c6162656c223a22332e34204c5041222c227061636b6167655f736f7274223a6e756c6c2c2270726f66696c655f70686f746f223a22222c2269735f6665617475726564223a312c22736f72745f6f72646572223a302c2269735f616374697665223a312c22706c6163656d656e745f64657363223a22562e20536169205072616b61736820706c616365642061742056697274757361227d, '2025-26', '2026', 'V. Sai Prakash', 'AIML', 'Virtusa', '', '3.4 LPA', NULL, '', 1, 0, 1, '2026-03-25 09:06:12', '2026-03-25 09:39:34'),
(9, 3, 0x6a736f6e3a3a7b2261636164656d69635f79656172223a22323032352d3236222c2262617463685f6c6162656c223a2232303236222c2273747564656e745f6e616d65223a22562e2053616d7975746861222c22636f757273655f6272616e6368223a2241494d4c222c22636f6d70616e795f6e616d65223a2256697274757361222c22726f6c655f7469746c65223a22222c227061636b6167655f6c6162656c223a22332e34204c5041222c227061636b6167655f736f7274223a6e756c6c2c2270726f66696c655f70686f746f223a22222c2269735f6665617475726564223a312c22736f72745f6f72646572223a302c2269735f616374697665223a312c22706c6163656d656e745f64657363223a22562e2053616d797574686120706c616365642061742056697274757361227d, '2025-26', '2026', 'V. Samyutha', 'AIML', 'Virtusa', '', '3.4 LPA', NULL, '', 1, 0, 1, '2026-03-25 09:07:23', '2026-03-25 09:07:23'),
(10, 3, 0x6a736f6e3a3a7b2261636164656d69635f79656172223a22323032352d3236222c2262617463685f6c6162656c223a2232303236222c2273747564656e745f6e616d65223a22532e526f6869746861205265646479222c22636f757273655f6272616e6368223a2241494d4c222c22636f6d70616e795f6e616d65223a224e65786572222c22726f6c655f7469746c65223a22222c227061636b6167655f6c6162656c223a22342e35204c5041222c227061636b6167655f736f7274223a6e756c6c2c2270726f66696c655f70686f746f223a22706c6163656d656e745f73726f68697468615f72656464795f32303236303332353130333634345f38376366626434652e6a7067222c2269735f6665617475726564223a312c22736f72745f6f72646572223a302c2269735f616374697665223a312c22706c6163656d656e745f64657363223a22532e526f686974686120526564647920706c61636564206174204e65786572227d, '2025-26', '2026', 'S.Rohitha Reddy', 'AIML', 'Nexer', '', '4.5 LPA', NULL, 'placement_srohitha_reddy_20260325103644_87cfbd4e.jpg', 1, 0, 1, '2026-03-25 09:09:15', '2026-03-25 09:36:44'),
(11, 3, 0x6a736f6e3a3a7b2261636164656d69635f79656172223a22323032352d3236222c2262617463685f6c6162656c223a2232303236222c2273747564656e745f6e616d65223a2252756b736869746820416e67616e746869222c22636f757273655f6272616e6368223a2241494d4c222c22636f6d70616e795f6e616d65223a224e65786572222c22726f6c655f7469746c65223a22222c227061636b6167655f6c6162656c223a22342e35204c5041222c227061636b6167655f736f7274223a6e756c6c2c2270726f66696c655f70686f746f223a22222c2269735f6665617475726564223a312c22736f72745f6f72646572223a302c2269735f616374697665223a312c22706c6163656d656e745f64657363223a2252756b736869746820416e67616e74686920706c61636564206174204e65786572227d, '2025-26', '2026', 'Rukshith Anganthi', 'AIML', 'Nexer', '', '4.5 LPA', NULL, '', 1, 0, 1, '2026-03-25 09:13:00', '2026-03-25 09:13:00'),
(12, 3, 0x6a736f6e3a3a7b2261636164656d69635f79656172223a22323032352d3236222c2262617463685f6c6162656c223a2232303236222c2273747564656e745f6e616d65223a224b617070616c6120486172736869746861222c22636f757273655f6272616e6368223a2241494d4c222c22636f6d70616e795f6e616d65223a224e65786572222c22726f6c655f7469746c65223a22222c227061636b6167655f6c6162656c223a22342e35204c5041222c227061636b6167655f736f7274223a6e756c6c2c2270726f66696c655f70686f746f223a22706c6163656d656e745f6b617070616c615f6861727368697468615f32303236303332353130333631335f39383666353537632e6a7067222c2269735f6665617475726564223a312c22736f72745f6f72646572223a302c2269735f616374697665223a312c22706c6163656d656e745f64657363223a224b617070616c612048617273686974686120706c61636564206174204e65786572227d, '2025-26', '2026', 'Kappala Harshitha', 'AIML', 'Nexer', '', '4.5 LPA', NULL, 'placement_kappala_harshitha_20260325103613_986f557c.jpg', 1, 0, 1, '2026-03-25 09:15:03', '2026-03-25 09:36:13'),
(13, 3, 0x6a736f6e3a3a7b2261636164656d69635f79656172223a22323032352d3236222c2262617463685f6c6162656c223a2232303236222c2273747564656e745f6e616d65223a224e616c6c616761737520416e75736861222c22636f757273655f6272616e6368223a2241494d4c222c22636f6d70616e795f6e616d65223a22696e766f69636520636c6f7564222c22726f6c655f7469746c65223a22222c227061636b6167655f6c6162656c223a2239204c5041222c227061636b6167655f736f7274223a6e756c6c2c2270726f66696c655f70686f746f223a22706c6163656d656e745f6e616c6c61676173755f616e757368615f32303236303332353130333535305f61353063623733312e6a7067222c2269735f6665617475726564223a312c22736f72745f6f72646572223a302c2269735f616374697665223a312c22706c6163656d656e745f64657363223a224e616c6c616761737520416e7573686120706c6163656420617420696e766f69636520636c6f7564227d, '2025-26', '2026', 'Nallagasu Anusha', 'AIML', 'invoice cloud', '', '9 LPA', NULL, 'placement_nallagasu_anusha_20260325103550_a50cb731.jpg', 1, 0, 1, '2026-03-25 09:19:00', '2026-03-25 09:35:50'),
(14, 3, 0x6a736f6e3a3a7b2261636164656d69635f79656172223a22323032342d3235222c2262617463685f6c6162656c223a2232303235222c2273747564656e745f6e616d65223a2255746b61727368204d6973687261222c22636f757273655f6272616e6368223a2241494d4c222c22636f6d70616e795f6e616d65223a22436174616c6f67222c22726f6c655f7469746c65223a22222c227061636b6167655f6c6162656c223a223235204c5041222c227061636b6167655f736f7274223a6e756c6c2c2270726f66696c655f70686f746f223a22222c2269735f6665617475726564223a312c22736f72745f6f72646572223a302c2269735f616374697665223a312c22706c6163656d656e745f64657363223a2255746b61727368204d697368726120706c6163656420617420436174616c6f67227d, '2024-25', '2025', 'Utkarsh Mishra', 'AIML', 'Catalog', '', '25 LPA', NULL, '', 1, 0, 1, '2026-03-25 09:24:15', '2026-03-25 09:24:15');

-- --------------------------------------------------------

--
-- Table structure for table `placement_stats`
--

CREATE TABLE `placement_stats` (
  `id` int(11) NOT NULL,
  `students_placed` varchar(50) NOT NULL DEFAULT '',
  `companies_visited` varchar(50) NOT NULL DEFAULT '',
  `highest_package` varchar(50) NOT NULL DEFAULT '',
  `average_package` varchar(50) NOT NULL DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `placement_stats`
--

INSERT INTO `placement_stats` (`id`, `students_placed`, `companies_visited`, `highest_package`, `average_package`, `updated_at`) VALUES
(1, '0', '0', '', '', '2026-03-25 06:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `prev_papers`
--

CREATE TABLE `prev_papers` (
  `id` int(11) NOT NULL,
  `subj_id` int(11) NOT NULL,
  `paper_name` varchar(500) NOT NULL,
  `paper_file` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `prev_papers`
--

INSERT INTO `prev_papers` (`id`, `subj_id`, `paper_name`, `paper_file`) VALUES
(1, 32, 'Befa-mid', 'BEFA IMP MID AUG 2025.pdf');



-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_code` varchar(500) NOT NULL,
  `section_name` varchar(500) NOT NULL,
  `batch_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `class_id`, `section_code`, `section_name`, `batch_id`) VALUES
(1, 1, 'IV IT A SEC ', '4th IT A Section', 0),
(2, 2, 'III IT A Sec', '3rd IT A Sec', 0),
(3, 3, 'II IT A Sec', '2nd IT A Section', 0),
(4, 3, 'II IT B Sec', '2nd IT B Section', 0),
(5, 4, 'I IT A Sec', '1st IT A Section', 0),
(7, 27, 'A', 'Section-A', 8),
(8, 27, 'B', 'Section-B', 8),
(9, 27, 'C', 'Section-C', 8),
(11, 30, 'PASSOUT', 'Passed Out', 8),
(12, 30, 'PASSOUT-A', 'Passed Out - A', 9),
(13, 30, 'PASSOUT-C', 'Passed Out - C', 8),
(14, 30, 'PASSOUT-B', 'Passed Out - B', 8),
(15, 31, 'A', 'Section-A', 13),
(16, 31, 'B', 'Section-B', 12),
(17, 31, 'C', 'Section-C', 13),
(18, 22, 'A', 'Section-A', 13);



--
-- Table structure for table `stream`
--

CREATE TABLE `stream` (
  `id` int(11) NOT NULL,
  `stream_code` varchar(500) NOT NULL,
  `stream_name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stream`
--

INSERT INTO `stream` (`id`, `stream_code`, `stream_name`) VALUES
(1, 'IT', 'Information Technology'),
(2, 'CSE', 'Computer science & Enginering'),
(5, 'ECE', 'Electronics and Communication Engineering'),
(6, 'EEE', 'Electronics and Electrical Engineering'),
(7, 'Other', 'Any Other Branch'),
(13, 'CSM', 'CSM'),
(14, '3', 'Year 3');



-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL COMMENT 'user name',
  `password` varchar(255) NOT NULL COMMENT 'user password is stored',
  `mail_id` varchar(500) NOT NULL COMMENT 'user mail_id is stored',
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `batch_id` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  `section` varchar(10) DEFAULT NULL COMMENT 'section',
  `admission_id` varchar(300) NOT NULL COMMENT 'Admission Id',
  `image` varchar(500) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'customer created date and time is stored',
  `last_access` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0,
  `is_alumni` tinyint(1) NOT NULL DEFAULT 0,
  `alumni_original_section_id` int(11) DEFAULT NULL,
  `alumni_original_section_label` varchar(20) DEFAULT NULL,
  `alumni_graduated_on` date DEFAULT NULL,
  `user_type` enum('student','alumni') NOT NULL DEFAULT 'student',
  `passout_year` year(4) DEFAULT NULL,
  `role` enum('student','alumni','admin') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Users details are stored';

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `username`, `password`, `mail_id`, `firstname`, `lastname`, `gender`, `address`, `mobile_no`, `batch_id`, `stream_id`, `section`, `admission_id`, `image`, `created_on`, `last_access`, `status`, `is_alumni`, `alumni_original_section_id`, `alumni_original_section_label`, `alumni_graduated_on`, `user_type`, `passout_year`, `role`) VALUES
(1, 'Siva', '$2y$10$zIHz5TtKOurx1wYapXaMtOfbv1T.1dNSnVaZRtGL8bqfOvXNyJ4aa', 'nstejas707@gmail.com', 'Siva', 'Tejas', 'male', 'manikonda', '1234567881', 8, 1, '13', '12345', 'user_12345_20260316054559_a64770af.jpg', '2026-02-25 08:03:22', '2026-03-18 18:12:21', 1, 1, 13, 'C', '2026-03-18', 'alumni', '2026', 'alumni'),
(2, 'Sami Ahmed', '$2y$10$7D/zhosw6PbeegqoasXRgeF0hjyZ6FnAu9X/ha58GNX3Rzc4w5IIe', '23x01a6678@nrcmec.org', 'MAHAMMAD', 'SAMI AHMED', 'Male', 'surya nagar , medchal', '6304450890', 8, 0, '8', '23X01A6678', 'user_23X01A6678_20260318183122_b9f20bbb.jpg', '2026-03-18 17:31:22', '2026-03-18 17:40:56', 1, 0, NULL, NULL, NULL, 'student', NULL, 'student'),
(4, 'Zoro', '$2y$10$UFBPtE0ITl.OYPT4EsuSqOcU2xMdB5q4eEhL96N8WTTSrByCRm0A.', 'zoro@gmail.com', 'Roronoa', 'D', 'Male', 'Heaven', '9999999999', 8, 0, '8', '23x01a66a0', '', '2026-03-20 03:29:39', '2026-03-20 03:29:52', 1, 0, NULL, NULL, NULL, 'student', NULL, 'student');




-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `sub_code` varchar(500) NOT NULL,
  `sub_name` varchar(500) NOT NULL,
  `class_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `sub_code`, `sub_name`, `class_id`, `batch_id`) VALUES
(1, 'IS', 'Information Security', 1, 0),
(2, 'NP', 'Network Programming', 1, 0),
(3, 'SPM', 'Software Project Management', 1, 0),
(4, 'ES', 'Embeded Systems', 1, 0),
(5, 'MAD', 'Multimedia And Application Developement', 1, 0),
(6, 'MC', 'Mobile Computing', 1, 0),
(7, 'CG', 'Computer Graphics', 2, 0),
(8, 'ADS', 'Adv Data Stractures', 2, 0),
(9, 'CN', 'Computer Networks', 2, 0),
(10, 'OS', 'Opreting System', 2, 0),
(11, 'SE', 'Softwre Engineering', 2, 0),
(12, 'WT', 'Web Technology', 0, 0),
(13, 'WT', 'Web Technology', 2, 0),
(14, 'MS', 'MANAGEMENT SCIENCE', 6, 0),
(15, 'DP', 'DESIGN PATTERN', 6, 0),
(16, 'NMS', 'NETWORK MANAGEMENT SYSTEMS', 6, 0),
(17, 'DAA', 'DESIGN AND ANALYSIS OF ALGORITHMS', 7, 0),
(18, 'UNIX', 'UNIX', 7, 0),
(19, 'OOAD', 'OBJECT ORIENTED ANALYSIS AND DESIGN', 7, 0),
(20, 'ACN', 'ADV COMPUTER NETWORKS', 7, 0),
(21, 'AJP', 'ADV JAVA PROGAMMING', 7, 0),
(22, 'MS', 'MANAGEMENT SCIENCE', 7, 0),
(23, 'DC', 'DATA COMMUNICATION', 8, 0),
(24, 'PPL', 'PRINCIPLES OF PROGRAMINNG LANGUAGES', 8, 0),
(25, 'OOPS', 'OBJECT ORIENTED PROGRAMMING', 8, 0),
(26, 'CO', 'COMPUTER ORGANIZATION AND ARCHITECTURE', 8, 0),
(27, 'DBMS', 'DATABASE MANAGEMENT SYSTEMS', 8, 0),
(28, 'ACD', 'AUTOMATA AND COMPILER DESIGN', 8, 0),
(29, '', '', 1, 0),
(30, '12333', 'Anatomy', 19, 0),
(31, '11001010', 'Muscle training', 19, 0),
(32, 'BEFA', 'Befa', 27, 8);



-- --------------------------------------------------------

--
-- Table structure for table `support_settings`
--

CREATE TABLE `support_settings` (
  `id` int(11) NOT NULL,
  `support_email` varchar(255) NOT NULL DEFAULT '',
  `whatsapp_number` varchar(30) NOT NULL DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `smtp_host` varchar(255) NOT NULL DEFAULT '',
  `smtp_port` int(11) NOT NULL DEFAULT 587,
  `smtp_secure` varchar(10) NOT NULL DEFAULT 'tls',
  `smtp_username` varchar(255) NOT NULL DEFAULT '',
  `smtp_password` varchar(255) NOT NULL DEFAULT '',
  `smtp_from_email` varchar(255) NOT NULL DEFAULT '',
  `smtp_from_name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `support_settings`
--

INSERT INTO `support_settings` (`id`, `support_email`, `whatsapp_number`, `updated_at`, `smtp_host`, `smtp_port`, `smtp_secure`, `smtp_username`, `smtp_password`, `smtp_from_email`, `smtp_from_name`) VALUES
(1, 'im8937861@gmail.com', '', '2026-03-04 13:29:32', 'smtp.gmail.com', 587, 'tls', 'im8937861@gmail.com', 'ctpx unjg raxg vmce', 'im8937861@gmail.com', 'AIML Support Desk');

-- --------------------------------------------------------

--
-- Table structure for table `syllabus`
--

CREATE TABLE `syllabus` (
  `id` int(11) NOT NULL,
  `syllabus_name` varchar(500) NOT NULL,
  `class_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `syllabus`
--

INSERT INTO `syllabus` (`id`, `syllabus_name`, `class_id`, `batch_id`) VALUES
(2, 'III-II SYLLABUS.pdf', 27, 8);



-- --------------------------------------------------------

--
-- Table structure for table `year_batch`
--

CREATE TABLE `year_batch` (
  `id` int(11) NOT NULL,
  `batch` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `year_batch`
--

INSERT INTO `year_batch` (`id`, `batch`) VALUES
(8, '2023-2027'),
(9, '2022-2026'),
(10, '2021-2025'),
(12, '2020-2024'),
(13, '2019-2023');



--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`adminname`);

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`id`);

-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `committee`
--
ALTER TABLE `committee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `committee_cat`
--
ALTER TABLE `committee_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_events_type_date` (`event_type_id`,`event_date`);

--
-- Indexes for table `event_reg`
--
ALTER TABLE `event_reg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_results`
--
ALTER TABLE `event_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_types_name` (`event_type`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty_category`
--
ALTER TABLE `faculty_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_category`
--
ALTER TABLE `gallery_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `highlights`
--
ALTER TABLE `highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_token_hash` (`token_hash`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_expires_at` (`expires_at`);

--
-- Indexes for table `placements`
--
ALTER TABLE `placements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_placements_category_batch` (`category_id`,`batch_label`),
  ADD KEY `idx_placements_category_year` (`category_id`,`academic_year`),
  ADD KEY `idx_placements_student_name` (`student_name`),
  ADD KEY `idx_placements_company_name` (`company_name`);

--
-- Indexes for table `placement_stats`
--
ALTER TABLE `placement_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prev_papers`
--
ALTER TABLE `prev_papers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);


-- Indexes for table `stream`
--
ALTER TABLE `stream`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`mail_id`),
  ADD UNIQUE KEY `unique_admission` (`admission_id`),
  ADD KEY `idx_users_is_alumni` (`is_alumni`),
  ADD KEY `idx_users_user_type` (`user_type`),
  ADD KEY `idx_users_passout_year` (`passout_year`),
  ADD KEY `idx_users_role` (`role`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD KEY `idx_subject_batch_class` (`batch_id`,`class_id`);

--
-- Indexes for table `syllabus`
--
ALTER TABLE `syllabus`
  ADD KEY `idx_syllabus_batch_class` (`batch_id`,`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `committee`
--
ALTER TABLE `committee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `committee_cat`
--
ALTER TABLE `committee_cat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty_category`
--
ALTER TABLE `faculty_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gallery_category`
--
ALTER TABLE `gallery_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `placements`
--
ALTER TABLE `placements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `placement_stats`
--
ALTER TABLE `placement_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prev_papers`
--
ALTER TABLE `prev_papers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `stream`
--
ALTER TABLE `stream`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `syllabus`
--
ALTER TABLE `syllabus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `year_batch`
--
ALTER TABLE `year_batch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
