-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.16 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table bacoor_dev.b_email_audit
CREATE TABLE IF NOT EXISTS `b_email_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` char(50) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `recipients` text NOT NULL,
  `sent_datetime` datetime NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table bacoor_dev.b_email_audit: ~3 rows (approximately)
/*!40000 ALTER TABLE `b_email_audit` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_email_audit` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_email_queue
CREATE TABLE IF NOT EXISTS `b_email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` char(50) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `recipients` text NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table bacoor_dev.b_email_queue: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_email_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_email_queue` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_maps
CREATE TABLE IF NOT EXISTS `b_maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '0',
  `url` varchar(1000) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_maps: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_maps` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_maps` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_ordinances
CREATE TABLE IF NOT EXISTS `b_ordinances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `summary` longtext NOT NULL,
  `link_to_file` varchar(1000) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'NOT_PUBLISHED',
  `type` varchar(50) NOT NULL DEFAULT 'ORDINANCE',
  `searchable` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL DEFAULT 'Anonymous',
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL DEFAULT 'Anonymous',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_ordinances: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_ordinances` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_ordinances` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_ordinances_files
CREATE TABLE IF NOT EXISTS `b_ordinances_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordinance_id` int(11) NOT NULL,
  `filename` varchar(1000) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL DEFAULT 'Anonymous',
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL DEFAULT 'Anonymous',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- Dumping data for table bacoor_dev.b_ordinances_files: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_ordinances_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_ordinances_files` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_ordinances_users
CREATE TABLE IF NOT EXISTS `b_ordinances_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ordinance_id` int(11) NOT NULL,
  `sys_user_id` int(11) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL DEFAULT 'Anonymous',
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL DEFAULT 'Anonymous',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Dumping data for table bacoor_dev.b_ordinances_users: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_ordinances_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_ordinances_users` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_posts
CREATE TABLE IF NOT EXISTS `b_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '0',
  `body` mediumtext NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_posts: ~1 rows (approximately)
/*!40000 ALTER TABLE `b_posts` DISABLE KEYS */;
INSERT INTO `b_posts` (`id`, `title`, `body`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'About Us', 'TAGLISH Version\r\n\r\nThis is the official website of the Sanggguniang Panglungsod of the City of Bacoor, Cavite.\r\n\r\nThe historic town of Bacoor, was founded in 1671. Its former name "Bakood" (meaning "fence" in Tagalog) \r\nwas suggestive of its role as the boundary between the towns of Paranaque and Kawit. Bacoor was also the site of the Battle of Zapote Bridge in 1897 between the Filipinos and Americans. Upon the declaration of Philippine Independence on 12 June 1898, the town was then designated as the first capital of Philippine Revolutionary Government by Emilio Aguinaldo.\r\n\r\nOn June 23, 2012, under the administration of its 18th Municipal Mayor, Hon. Strike B. Revilla, Bacoor was converted into the City of Bacoor by virtue of Republic Act No. 10160 known as the "Charter of the City of Bacoor". The city is composed of seventy-three (73) barangays under two (2) legislative districts namely Bacoor East and Bacoor West and has a population of around 650,000.\r\n\r\nUnder the Article IV, Section 11 of RA 10160, the Sangguniang Panlungsod is the legislative body of the City which has the power to enact ordinances, approve resolutions, and appropriate funds for the general welfare of the City and its inhabitants. The sanggunian is headed by the City Vice Mayor, Hon. catherine sarino - Evaristo, and comprised of twelve (12) city councilors as regular sanggunian members, the president of the city chapter of the Liga ng mga Barangay, and the president of the Panlungsod na Pederasyon ng mga Sangguniang Kabataan.\r\n\r\nThis website is the product of the Sangguniang Panlungsod\'s determination to be more transparent and to directly involve the people of the City of Bacoor not only in the crafting of vital legislative measures but also to have active role in shaping the future of their beloved city. Welcom to the historic City of Bacoor!\r\n\r\nTAGALOG\r\n\r\nIto ang opisyal na pahina ng Sangguniang Panlungsod ng Lungsod ng Bacoor, Cavite.\r\n\r\nAng makasaysayang Lungsod ng Bacoor ay Itinatag noong 1671. Ang dati nitong pangalan na "Bakood" (o "bakod" sa Tagalog) ay sumasagisag sa pagkakahati ng mga bayan ng Paranaque at Kawit. Ito rin ang lugar kung saan naganap ang Battle of Zapote Bridge noong 1897 sa pagitan ng mga Pilipino at mga Amerikano. Matapos ang deklarasyon ng Kalayaan ng Pilipinas noong ika-12 ng Hunyo taong 1898, ang bayan ng Bacoor ay itinalaga bilang unang kapitolyo ng Pamahalaang Rebolusyonaryo sa ilalim ni Hen. Emilio Aguinaldo.\r\n\r\nNong ika-23 ng Hunyo taong 2012, sa ilalin ng pamumuno ng ika-18 Alkalde ng Bacoor na si Hon. Strike B. Revilla, ang bayan ng Bacoor at naiconvert bilang isang lungsod ayon sa Republic Act No. 10160 na kilala rin bilang "Charter of the City of Bacoor". Ang lungsod ay binubuo ng 73 na barangay na kumakatawan sa dalawang distrito, ang Bacoo East at Bacoor West na may populasyon na humigit kumulang na 650,000.\r\n\r\nAyon sa Articile IV, Section 11 ng RA 10160 ng nasabing batas, ang Sangguniang Panlungsod ay ang legislative body ng lungsod na may kapangyarihan na magpatupad ng mga ordinansa, mag-apruba ng mga resolusyon, at ng mga kinauukulang pondo para sa ikauunlad ng lungsod at ng mga mamamayan. Ang Sanggunian ay pinamumunuan ng Bise-Alkalde ng Lungsod, Hon. Catherine Sarino-Evaristo at binubuo ng labindalawang (12) konsehal ng lungsod, presidente ng liga ng mga barangay,at nang presidente ng Panlungsod na Pederasyon ng mga Sangguniang Kabataan.\r\n\r\nAng website na ito ay produkto ng determinasyon ng Sangguniang Panlungsod na maging mas tranparent at upang bigyan ang mga mamamayan ng direktang partisipasyon di lamang sa pagbalangkas ng mga ordinansa at resolusyon ngunit upang magkaroon din ng aktibong pagganap sa paghubog sa kinabukasan ng ating pinakamamahal na lungsod. Welcome sa makasaysayang Lungsod ng Bacoor.', '2014-08-04', 'admin1', '2014-08-12', 'admin1', 0);
/*!40000 ALTER TABLE `b_posts` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_posts_post_types
CREATE TABLE IF NOT EXISTS `b_posts_post_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_type_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_posts_post_types: ~1 rows (approximately)
/*!40000 ALTER TABLE `b_posts_post_types` DISABLE KEYS */;
INSERT INTO `b_posts_post_types` (`id`, `post_type_id`, `post_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 1, 1, '0000-00-00', '', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_posts_post_types` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_post_images
CREATE TABLE IF NOT EXISTS `b_post_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(10000) NOT NULL,
  `caption` varchar(1000) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL DEFAULT 'Anonymous',
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL DEFAULT 'Anonymous',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- Dumping data for table bacoor_dev.b_post_images: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_post_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_post_images` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_post_types
CREATE TABLE IF NOT EXISTS `b_post_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_post_types: ~4 rows (approximately)
/*!40000 ALTER TABLE `b_post_types` DISABLE KEYS */;
INSERT INTO `b_post_types` (`id`, `name`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'ABOUT', '0000-00-00', '', '0000-00-00', '', 0),
	(2, 'NEWS', '0000-00-00', '', '0000-00-00', '', 0),
	(3, 'CONTACT', '0000-00-00', '', '0000-00-00', '', 0),
	(4, 'EVENT', '0000-00-00', '', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_post_types` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_surveys
CREATE TABLE IF NOT EXISTS `b_surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `b_survey_question_id` int(11) NOT NULL,
  `b_survey_option_id` int(11) NOT NULL,
  `votes` int(11) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_surveys: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_surveys` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_surveys` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_survey_options
CREATE TABLE IF NOT EXISTS `b_survey_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `answer_text` varchar(1000) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_survey_options: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_survey_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_survey_options` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_survey_questions
CREATE TABLE IF NOT EXISTS `b_survey_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(1000) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'NOT_PUBLISHED',
  `featured` varchar(50) NOT NULL DEFAULT 'NOT_FEATURED',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_survey_questions: ~1 rows (approximately)
/*!40000 ALTER TABLE `b_survey_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_survey_questions` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_survey_users
CREATE TABLE IF NOT EXISTS `b_survey_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `b_survey_question_id` int(11) NOT NULL,
  `sys_user_id` int(11) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- Dumping data for table bacoor_dev.b_survey_users: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_survey_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_survey_users` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.sys_roles
CREATE TABLE IF NOT EXISTS `sys_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.sys_roles: ~6 rows (approximately)
/*!40000 ALTER TABLE `sys_roles` DISABLE KEYS */;
INSERT INTO `sys_roles` (`id`, `name`, `description`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'Super Administrator', 'Vice Mayor account', '0000-00-00', '', '0000-00-00', '', 0),
	(2, 'Super User', 'City Lawyer account', '0000-00-00', '', '0000-00-00', '', 0),
	(3, 'Administrator', 'Web Administrator', '0000-00-00', '', '0000-00-00', '', 0),
	(4, 'Voter', 'Citizens', '0000-00-00', '', '0000-00-00', '', 0),
	(5, 'Councilor', 'Councilors', '0000-00-00', '', '0000-00-00', '', 0),
	(6, 'Guest', 'Guest', '0000-00-00', '', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `sys_roles` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.sys_role_mappings
CREATE TABLE IF NOT EXISTS `sys_role_mappings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL,
  `child_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.sys_role_mappings: ~6 rows (approximately)
/*!40000 ALTER TABLE `sys_role_mappings` DISABLE KEYS */;
INSERT INTO `sys_role_mappings` (`id`, `parent_id`, `child_id`) VALUES
	(1, 1, 2),
	(2, 2, 3),
	(3, 3, 4),
	(4, 4, 5),
	(6, 5, 6),
	(7, 6, 0);
/*!40000 ALTER TABLE `sys_role_mappings` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.sys_users
CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `mi` char(1) NOT NULL,
  `designation` char(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contacts` varchar(50) NOT NULL,
  `email_add` varchar(100) NOT NULL,
  `signature` varchar(200) NOT NULL,
  `searchable` tinyint(1) NOT NULL DEFAULT '0',
  `district` tinyint(1) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.sys_users: ~17 rows (approximately)
/*!40000 ALTER TABLE `sys_users` DISABLE KEYS */;
INSERT INTO `sys_users` (`id`, `firstname`, `lastname`, `mi`, `designation`, `address`, `contacts`, `email_add`, `signature`, `searchable`, `district`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'Catherine', 'Evaristo', 'J', '', 'Bacoor City', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-02', 'khalid', 0),
	(2, 'Khalid', 'Atega', 'H', 'Sr', 'Bacoor City', '09156473743', 'joshauza@gmail.com', 'sig.png?1407856938', 1, 2, '0000-00-00', '', '2014-08-12', 'khalid', 0),
	(3, 'Avelino', 'Solis', 'K', '', 'Manila', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-02', 'khalid', 0),
	(4, 'Edwin', 'Gawaran', 'K', '', 'Brgy. Holy Spirit Mandaluyong', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-08', 'egawaran', 0),
	(5, 'Miguel', 'Bautista', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'mbautista', 0),
	(6, 'Rowena', 'Bautista Mendiola', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'rbmendiola', 0),
	(7, 'Reynaldo', 'Fabian', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'rfabian', 0),
	(8, 'Venus', 'De Castro', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'vdecastro', 0),
	(9, 'Victorio', 'Guerrero', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'khalid', 0),
	(10, 'Reynaldo', 'Palabrica', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 2, '0000-00-00', '', '2014-07-25', 'rpalabrica', 0),
	(11, 'Hernando', 'Gutierrez', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 2, '0000-00-00', '', '2014-07-25', 'hgutierrez', 0),
	(12, 'Leandro', 'De Leon', '', '', '', '', 'joshauza@gmail.com', 'sig.png?1407857722', 1, 2, '0000-00-00', '', '2014-08-12', 'ldeleon', 0),
	(13, 'Bayani', 'De Leon', '', '', '', '', 'joshauza@gmail.com', 'sig.png?1407857091', 1, 2, '0000-00-00', '', '2014-08-12', 'bdeleon', 0),
	(14, 'Gaudencio', 'Nolasco', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 2, '0000-00-00', '', '2014-07-25', 'gnolasco', 0),
	(15, 'Robert', 'Javier', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 2, '0000-00-00', '', '2014-07-25', 'rjavier', 0),
	(16, 'Admin', 'Admin', 'G', '', 'Manila', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-04', 'admin1', 0),
	(17, 'Voter', 'Voter', 'G', '', 'Manila', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-04', 'admin1', 0);
/*!40000 ALTER TABLE `sys_users` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.sys_user_roles
CREATE TABLE IF NOT EXISTS `sys_user_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sys_user_id` int(10) NOT NULL,
  `sys_role_id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `pwc` tinyint(10) NOT NULL DEFAULT '1',
  `status` char(10) NOT NULL DEFAULT 'NEW',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.sys_user_roles: ~17 rows (approximately)
/*!40000 ALTER TABLE `sys_user_roles` DISABLE KEYS */;
INSERT INTO `sys_user_roles` (`id`, `sys_user_id`, `sys_role_id`, `username`, `password`, `pwc`, `status`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 1, 1, 'cevaristo', 'a', 2, 'REG', '0000-00-00', '', '2014-08-02', 'khalid', 0),
	(2, 2, 2, 'khalid', 'a', 2, 'REG', '0000-00-00', '', '2014-08-02', 'khalid', 0),
	(3, 3, 5, 'asolis', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
	(4, 4, 5, 'egawaran', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
	(5, 5, 5, 'mbautista', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
	(6, 6, 5, 'rbmendiola', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
	(7, 7, 5, 'rfabian', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
	(8, 8, 5, 'vdecastro', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
	(9, 9, 5, 'vguerrero', 'a', 0, 'REG', '0000-00-00', '', '2014-07-31', 'admin1', 0),
	(10, 10, 5, 'rpalabrica', 'a', 0, 'REG', '0000-00-00', '', '2014-08-04', 'admin1', 0),
	(11, 11, 5, 'hgutierrez', 'a', 0, 'REG', '0000-00-00', '', '0000-00-00', '', 0),
	(12, 12, 5, 'ldeleon', 'a', 0, 'REG', '0000-00-00', '', '0000-00-00', '', 0),
	(13, 13, 5, 'bdeleon', 'a', 0, 'REG', '0000-00-00', '', '0000-00-00', '', 0),
	(14, 14, 5, 'gnolasco', 'a', 0, 'REG', '0000-00-00', '', '0000-00-00', '', 0),
	(15, 15, 5, 'rlavier', 'a', 0, 'REG', '0000-00-00', '', '2014-07-31', 'admin1', 0),
	(16, 16, 3, 'admin1', 'a', 0, 'REG', '0000-00-00', '', '2014-08-04', 'admin1', 0),
	(17, 17, 4, 'voter1', 'a', 0, 'REG', '0000-00-00', '', '2014-08-04', 'admin1', 0);
/*!40000 ALTER TABLE `sys_user_roles` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
