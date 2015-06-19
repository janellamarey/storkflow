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

-- Dumping structure for table bacoor_dev.b_attendances
CREATE TABLE IF NOT EXISTS `b_attendances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_date_id` int(11) NOT NULL DEFAULT '0',
  `sys_user_role_id` int(11) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_attendances: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_attendances` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_budgets
CREATE TABLE IF NOT EXISTS `b_budgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `link_to_file` varchar(1000) DEFAULT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_budgets: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_budgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_budgets` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_emails
CREATE TABLE IF NOT EXISTS `b_emails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `body` varchar(10000) NOT NULL,
  `body_formatted` varchar(10000) NOT NULL,
  `priority` varchar(1000) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table bacoor_dev.b_emails: ~14 rows (approximately)
/*!40000 ALTER TABLE `b_emails` DISABLE KEYS */;
INSERT INTO `b_emails` (`id`, `body`, `body_formatted`, `priority`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(9, 'Ordinance has been approved.', 'Ordinance has been approved.', 'High', '2014-06-14', '', '0000-00-00', '', 0),
	(10, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(11, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(12, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(13, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(14, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(15, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(16, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(17, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(18, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(19, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(20, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(21, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(22, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(23, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0),
	(24, 'Ordinance has been approved.', 'Ordinance has been approved.', '1', '2014-06-14', '', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_emails` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_files
CREATE TABLE IF NOT EXISTS `b_files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table bacoor_dev.b_files: ~7 rows (approximately)
/*!40000 ALTER TABLE `b_files` DISABLE KEYS */;
INSERT INTO `b_files` (`id`, `name`, `url`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, '', 'C:\\Web\\simple/uploads/12/2014-05-11_15-15-20_Sample PDF.pdf', '2014-05-11', 'admin', '0000-00-00', '', 0),
	(2, '', 'C:\\Web\\simple/uploads/13/2014-05-11_15-16-36_Sample PDF.pdf', '2014-05-11', 'admin', '0000-00-00', '', 0),
	(3, '2014-05-11_18-52-38_Sample PDF.pdf', 'C:\\Web\\simple/uploads/18/2014-05-11_18-52-38_Sample PDF.pdf', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(4, '2014-05-11_18-52-38_Sample PDF - Copy.pdf', 'C:\\Web\\simple/uploads/18/2014-05-11_18-52-38_Sample PDF - Copy.pdf', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(5, '2014-05-11_18-52-38_Sample PDF - Copy (2).pdf', 'C:\\Web\\simple/uploads/18/2014-05-11_18-52-38_Sample PDF - Copy (2).pdf', '2014-05-11', 'admin', '2014-05-17', 'admin', 1),
	(6, '2014-05-11_21-02-59_Sample PDF - Copy.pdf', 'C:\\Web\\simple/uploads/19/2014-05-11_21-02-59_Sample PDF - Copy.pdf', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(7, '2014-05-11_21-02-59_slide1.png', 'C:\\Web\\simple/uploads/19/2014-05-11_21-02-59_slide1.png', '2014-05-11', 'admin', '0000-00-00', '', 0),
	(8, '2014-05-15_01-57-31_Sample PDF - Copy (2).pdf', 'C:\\Web\\simple/uploads/21/2014-05-15_01-57-31_Sample PDF - Copy (2).pdf', '2014-05-15', 'admin', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_files` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_forgotten_password_requests
CREATE TABLE IF NOT EXISTS `b_forgotten_password_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_add` varchar(100) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_forgotten_password_requests: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_forgotten_password_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_forgotten_password_requests` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_images
CREATE TABLE IF NOT EXISTS `b_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(50) NOT NULL,
  `img` longblob NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_images: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_images` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_maps
CREATE TABLE IF NOT EXISTS `b_maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '0',
  `link_to_file` varchar(1000) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_maps: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_maps` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_maps` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_ordinances
CREATE TABLE IF NOT EXISTS `b_ordinances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '0',
  `summary` varchar(1000) NOT NULL,
  `link_to_file` varchar(1000) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_ordinances: ~18 rows (approximately)
/*!40000 ALTER TABLE `b_ordinances` DISABLE KEYS */;
INSERT INTO `b_ordinances` (`id`, `name`, `summary`, `link_to_file`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'RA2561', '', '/ord/2561', '0000-00-00', '', '2014-05-14', 'admin', 1),
	(2, 'Test', 'Ttest', '0', '2014-05-11', 'admin', '2014-05-14', 'admin', 1),
	(3, 'test', 'test', '0', '2014-05-11', 'admin', '2014-05-14', 'admin', 1),
	(4, 'This is the corrrect assumoptn', 'Current applicable data is added here', '0', '2014-05-11', 'admin', '2014-05-14', 'admin', 1),
	(5, 'Chapter 13.05 Specified Animal Regulations', '13.05.005 Definitions.\r\n\r\n \r\n\r\n(Amended by Ordinance Nos. 172635 and 181539, effective February 15, 2008.)  As used in this Chapter, unless the context requires otherwise: \r\n\r\n \r\n\r\nA.   "Director" means the Director of the Multnomah County Health Department Vector and Nuisance Control, or the director\'s designee.\r\n\r\n \r\n\r\nB.  "Keeper" means any person or legal entity who harbors, cares for, exercises control over or knowingly permits any animal to remain on premises occupied by that person for a period of time not less than 72 hours or someone who accepted the animal for purposes of safe keeping.\r\n\r\n \r\n\r\nC.   "Livestock" means animals including, but not limited to, fowl, horses, mules, burros, asses, cattle, sheep, goats, llamas, emu, ostriches, rabbits, swine, or other farm animals excluding dogs and cats.\r\n\r\n \r\n\r\nD.   "Person" means any natural person, association, partnership, firm, or corporation.\r\n\r\n \r\n\r\nE.  "A Secure Enclosure" shall be:\r\n\r\n    1.   A fully fenced pen, kennel or s', '0', '2014-05-11', 'admin', '2014-05-14', 'admin', 1),
	(6, 'Chapter 13.05 Specified Animal Regulations', '13.05.040 Diseased Animals to be Confined.\r\nA.  It is unlawful for any specified animal keeper who has reason to believe that the animal is infected with mange, eczema or other disease contagious to animals, or who has been notified as provided in Subsection C hereof, not to confine such animal until the animal is examined and declared free of disease by a licensed veterinarian or by the Director.', '0', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(7, 'Chapter 13.05 Specified Animal Regulations', '13.05.040 Diseased Animals to be Confined.\r\nA.  It is unlawful for any specified animal keeper who has reason to believe that the animal is infected with mange, eczema or other disease contagious to animals, or who has been notified as provided in Subsection C hereof, not to confine such animal until the animal is examined and declared free of disease by a licensed veterinarian or by the Director.', '0', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(8, 'Chapter 13.05 Specified Animal Regulations', '13.05.040 Diseased Animals to be Confined.\r\nA.  It is unlawful for any specified animal keeper who has reason to believe that the animal is infected with mange, eczema or other disease contagious to animals, or who has been notified as provided in Subsection C hereof, not to confine such animal until the animal is examined and declared free of disease by a licensed veterinarian or by the Director.', '0', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(9, 'Chapter 13.05 Specified Animal Regulations', '13.05.040 Diseased Animals to be Confined.\r\nA.  It is unlawful for any specified animal keeper who has reason to believe that the animal is infected with mange, eczema or other disease contagious to animals, or who has been notified as provided in Subsection C hereof, not to confine such animal until the animal is examined and declared free of disease by a licensed veterinarian or by the Director.', '0', '2014-05-11', 'admin', '2014-06-11', 'admin', 1),
	(10, 'Chapter 13.05 Specified Animal Regulations', '13.05.040 Diseased Animals to be Confined.\r\nA.  It is unlawful for any specified animal keeper who has reason to believe that the animal is infected with mange, eczema or other disease contagious to animals, or who has been notified as provided in Subsection C hereof, not to confine such animal until the animal is examined and declared free of disease by a licensed veterinarian or by the Director.', '0', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(11, 'test', 'test', '0', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(12, 'Chapter 13.05 Specified Animal Regulations', 'This is a good test', '0', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(13, 'Chapter 13.05 Specified Animal Regulations', 'ccccc', '0', '2014-05-11', 'admin', '2014-05-15', 'admin', 1),
	(14, 'Chapter 13.05 Specified Animal Regulations  13.05.005 Definitions.', 'Director.\r\n \r\nB.  It is unlawful for any specified animal keeper who has reason to believe that the animal is infected with ringworm, hepatitis, rabies or other disease contagious to humans, or who has been notified as provided in Subsection C hereof, not to confine such animal until the animal is examined and declared free of disease by a licensed veterinarian or by the Director.', '0', '2014-05-11', 'admin', '0000-00-00', '', 0),
	(15, 'Chapter 13.05 Specified Animal Regulations  13.05.005 Definitions.', 'Chapter 13.05 Specified Animal Regulations\r\n\r\n13.05.005 Definitions.', '0', '2014-05-11', 'admin', '0000-00-00', '', 0),
	(16, 'Chapter 13.05 Specified Animal Regulations  13.05.005 Definitions.', 'Chapter 13.05 Specified Animal Regulations\r\n\r\n13.05.005 Definitions.', '0', '2014-05-11', 'admin', '0000-00-00', '', 0),
	(17, 'Chapter 13.05 Specified Animal Regulations  13.05.005 Definitions.', 'Chapter 13.05 Specified Animal Regulations\r\n\r\n13.05.005 Definitions.', '0', '2014-05-11', 'admin', '0000-00-00', '', 0),
	(18, 'test', 'test', '0', '2014-05-11', 'admin', '0000-00-00', '', 0),
	(19, '(Amended by Ordinance Nos. 172635 and 181539, effective February 15, 2008.)  As used in this Chapter', '\r\nA.   "Director" means the Director of the Multnomah County Health Department Vector and Nuisance Control, or the director\'s designee.\r\n\r\n \r\n\r\nB.  "Keeper" means any person or legal entity who harbors, cares for, exercises control over or knowingly permits any animal to remain on premises occupied by that person for a period of time not less than 72 hours or someone who accepted the animal for purposes of safe keeping.\r\n\r\n \r\n\r\nC.   "Livestock" means animals including, but not limited to, fowl, horses, mules, burros, asses, cattle, sheep, goats, llamas, emu, ostriches, rabbits, swine, or other farm animals excluding dogs and cats.\r\n\r\n \r\n\r\nD.   "Person" means any natural person, association, partnership, firm, or corporation.', '0', '2014-05-11', 'admin', '0000-00-00', '', 0),
	(20, 'This is a city ordinance', 'Add summary here', '0', '2014-05-15', 'admin', '2014-05-15', 'admin', 1),
	(21, 'This is the corrrect assumoptn', 'ss', '0', '2014-05-15', 'admin', '0000-00-00', '', 0),
	(22, 'This is a new ordinance good government shit', 'Code like a boss', '0', '2014-05-26', 'councilor', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_ordinances` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_ordinances_files
CREATE TABLE IF NOT EXISTS `b_ordinances_files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ordinance_id` int(10) NOT NULL DEFAULT '0',
  `file_id` int(10) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table bacoor_dev.b_ordinances_files: ~6 rows (approximately)
/*!40000 ALTER TABLE `b_ordinances_files` DISABLE KEYS */;
INSERT INTO `b_ordinances_files` (`id`, `ordinance_id`, `file_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 13, 2, '2014-05-11', 'admin', '0000-00-00', '', 0),
	(2, 18, 3, '2014-05-11', 'admin', '0000-00-00', '', 0),
	(3, 18, 4, '2014-05-11', 'admin', '0000-00-00', '', 0),
	(4, 18, 5, '2014-05-11', 'admin', '0000-00-00', '', 0),
	(5, 19, 6, '2014-05-11', 'admin', '0000-00-00', '', 0),
	(6, 19, 7, '2014-05-11', 'admin', '0000-00-00', '', 0),
	(7, 21, 8, '2014-05-15', 'admin', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_ordinances_files` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_ordinances_users
CREATE TABLE IF NOT EXISTS `b_ordinances_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ordinance_id` int(11) NOT NULL,
  `sys_user_id` int(11) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Dumping data for table bacoor_dev.b_ordinances_users: ~22 rows (approximately)
/*!40000 ALTER TABLE `b_ordinances_users` DISABLE KEYS */;
INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 14, 5, '0000-00-00', '', '0000-00-00', '', 0),
	(2, 9, 5, '2014-05-26', 'councilor', '0000-00-00', '', 0),
	(3, 18, 5, '2014-05-26', 'councilor', '0000-00-00', '', 0),
	(4, 15, 5, '2014-05-27', 'councilor', '0000-00-00', '', 0),
	(5, 15, 5, '2014-05-27', 'councilor', '0000-00-00', '', 0),
	(6, 16, 5, '2014-05-27', 'councilor', '0000-00-00', '', 0),
	(7, 19, 5, '2014-05-27', 'councilor', '0000-00-00', '', 0),
	(8, 17, 5, '2014-06-09', 'councilor', '0000-00-00', '', 0),
	(9, 9, 48, '2014-06-09', 'jean', '0000-00-00', '', 0),
	(10, 22, 48, '2014-06-09', 'jean', '0000-00-00', '', 0),
	(11, 21, 48, '2014-06-09', 'jean', '0000-00-00', '', 0),
	(12, 18, 48, '2014-06-09', 'jean', '0000-00-00', '', 0),
	(13, 19, 48, '2014-06-09', 'jean', '0000-00-00', '', 0),
	(14, 17, 48, '2014-06-09', 'jean', '0000-00-00', '', 0),
	(15, 14, 48, '2014-06-09', 'jean', '0000-00-00', '', 0),
	(16, 15, 48, '2014-06-09', 'jean', '0000-00-00', '', 0),
	(17, 16, 48, '2014-06-09', 'jean', '0000-00-00', '', 0),
	(18, 9, 49, '2014-06-09', 'nba', '0000-00-00', '', 0),
	(19, 14, 49, '2014-06-09', 'nba', '0000-00-00', '', 0),
	(20, 15, 49, '2014-06-09', 'nba', '0000-00-00', '', 0),
	(21, 16, 49, '2014-06-09', 'nba', '0000-00-00', '', 0),
	(22, 22, 49, '2014-06-09', 'nba', '0000-00-00', '', 0),
	(23, 9, 3, '2014-06-11', 'admin', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_ordinances_users` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_posts
CREATE TABLE IF NOT EXISTS `b_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '0',
  `body` varchar(10000) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_posts: ~12 rows (approximately)
/*!40000 ALTER TABLE `b_posts` DISABLE KEYS */;
INSERT INTO `b_posts` (`id`, `title`, `body`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'Contact Us', 'trst', '0000-00-00', '', '0000-00-00', '', 0),
	(2, 'Contact Us', 'Sed ut perspiciatis unde omnis iste natus error sit v oluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum uuuuuuuuuuuureprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur? At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia andssssssssssssssssssssssssimi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat. trrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr', '0000-00-00', 'admin', '2014-04-27', 'admin', 0),
	(3, 'Contact Us', 'Sed ut perspiciatis unde omnis iste natus error sit v oluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quidddddddddddddddddddddddddddds autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur? At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat. ', '0000-00-00', 'admin', '2014-04-18', 'admin', 0),
	(4, 'Contact Us', 'ert', '2014-04-17', 'admin', '0000-00-00', '', 0),
	(5, 'Contact Us', 'rggggs', '2014-04-17', 'admin', '0000-00-00', '', 0),
	(6, 'Enrollments Exceed Obama?s Target for Health Care Act', 'WASHINGTON ? President Obama announced Thursday that eight million people have signed up for health insurance under the Affordable Care Act, including what the White House said were a sufficient number of young, healthy adults, a critical milestone that might counter election-year attacks by Republicans on the law?s success and viability.\r\n\r\nThe total number of enrollees exceeds by a million the target set by the administration for people to buy insurance through government-run health care exchanges. In particular, the number of young people signing up appears to have surged during the final weeks of enrollment.\r\n\r\n?This thing is working,? Mr. Obama told reporters in the White House briefing room, in what amounted to a second victory lap after he announced two weeks ago that 7.1 million people had signed up for insurance during an initial enrollment period. ?The Affordable Care Act is covering more people at less cost than most people would have predicted a few months ago.?\r\n\r\nStill, critics of the law cautioned that promising top-line numbers were not by themselves proof of success.', '2014-04-18', 'admin', '2014-05-01', 'admin', 1),
	(7, 'Enrollments Exceed Obama?s Target for Health Care Act', 'WASHINGTON ? President Obama announced Thursday that eight million people have signed up for health insurance under the Affordable Care Act, including what the White House said were a sufficient number of young, healthy adults, a critical milestone that might counter election-year attacks by Republicans on the law?s success and viability.\r\n\r\nThe total number of enrollees exceeds by a million the target set by the administration for people to buy insurance through government-run health care exchanges. In particular, the number of young people signing up appears to have surged during the final weeks of enrollment.\r\n\r\n?This thing is working,? Mr. Obama told reporters in the White House briefing room, in what amounted to a second victory lap after he announced two weeks ago that 7.1 million people had signed up for insurance during an initial enrollment period. ?The Affordable Care Act is covering more people at less cost than most people would have predicted a few months ago.?\r\n\r\nStill, critics of the law cautioned that promising top-line numbers were not by themselves proof of success.', '2014-04-18', 'admin', '2014-04-30', 'admin', 0),
	(8, 'Enrollments Exceed Obama?s Target for Health Care Act', 'WASHINGTON ? President Obama announced Thursday that eight million people have signed up for health insurance under the Affordable Care Act, including what the White House said were a sufficient number of young, healthy adults, a critical milestone that might counter election-year attacks by Republicans on the law?s success and viability.\r\n\r\nThe total number of enrollees exceeds by a million the target set by the administration for people to buy insurance through government-run health care exchanges. In particular, the number of young people signing up appears to have surged during the final weeks of enrollment.\r\n\r\n?This thing is working,? Mr. Obama told reporters in the White House briefing room, in what amounted to a second victory lap after he announced two weeks ago that 7.1 million people had signed up for insurance during an initial enrollment period. ?The Affordable Care Act is covering more people at less cost than most people would have predicted a few months ago.?\r\n\r\nStill, critics of the law cautioned that promising top-line numbers were not by themselves proof of success.', '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(9, 'Enrollments Exceed Obama’s Target for Health Care Act', 'WASHINGTON — President Obama announced Thursday that eight million people have signed up for health insurance under the Affordable Care Act, including what the White House said were a sufficient number of young, healthy adults, a critical milestone that might counter election-year attacks by Republicans on the law’s success and viability.\r\n\r\nThe total number of enrollees exceeds by a million the target set by the administration for people to buy insurance through government-run health care exchanges. In particular, the number of young people signing up appears to have surged during the final weeks of enrollment.\r\n\r\n“This thing is working,” Mr. Obama told reporters in the White House briefing room, in what amounted to a second victory lap after he announced two weeks ago that 7.1 million people had signed up for insurance during an initial enrollment period. “The Affordable Care Act is covering more people at less cost than most people would have predicted a few months ago.”\r\n\r\nStill, critics of the law cautioned that promising top-line numbers were not by themselves proof of success.', '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(10, 'Enrollments Exceed Obama’s Target for Health Care Act', 'WASHINGTON — President Obama announced Thursday that eight million people have signed up for health insurance under the Affordable Care Act, including what the White House said were a sufficient number of young, healthy adults, a critical milestone that might counter election-year attacks by Republicans on the law’s success and viability.\r\n\r\nThe total number of enrollees exceeds by a million the target set by the administration for people to buy insurance through government-run health care exchanges. In particular, the number of young people signing up appears to have surged during the final weeks of enrollment.\r\n\r\n“This thing is working,” Mr. Obama told reporters in the White House briefing room, in what amounted to a second victory lap after he announced two weeks ago that 7.1 million people had signed up for insurance during an initial enrollment period. “The Affordable Care Act is covering more people at less cost than most people would have predicted a few months ago.”\r\n\r\nStill, critics of the law cautioned that promising top-line numbers were not by themselves proof of success.', '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(11, 'Enrollments Exceed Obama?s Target for Health Care Act', 'Enrollments Exceed Obama?s Target for Health Care Act', '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(12, 'Enrollments Exceed Obama’s Target for Health Care Act', 'Enrollments Exceed Obama’s Target for Health Care Act', '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(13, 'South Korea ferry: Third officer \'had the helm\'', 'The third officer was at the helm of the ferry that capsized off South Korea, investigators said, as divers worked to access the sunken hull.\r\n\r\nA total of 268 people - including scores of high school students - remain missing after Wednesday\'s disaster.\r\n\r\nTwenty-eight people are now known to have died and 179 were rescued.\r\n\r\nIt is not clear why the ferry sank, but experts have suggested it either hit a rock or turned sharply, unbalancing the vessel as cargo shifted.\r\n\r\nThe vessel - named Sewol - had been travelling from Incheon, in the north-west, to the southern resort island of Jeju. It capsized and sank within a period of two hours, officials said. ', '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(14, 'test', 'this is test', '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(15, 'test', 'test', '2014-04-30', 'admin', '2014-04-30', 'admin', 1);
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_posts_post_types: ~11 rows (approximately)
/*!40000 ALTER TABLE `b_posts_post_types` DISABLE KEYS */;
INSERT INTO `b_posts_post_types` (`id`, `post_type_id`, `post_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 1, 2, '2014-04-17', '', '0000-00-00', '', 0),
	(2, 3, 3, '0000-00-00', '', '0000-00-00', '', 0),
	(3, 2, 6, '2014-04-18', 'admin', '2014-05-01', 'admin', 1),
	(4, 2, 7, '2014-04-18', 'admin', '2014-04-30', 'admin', 0),
	(5, 2, 8, '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(6, 2, 9, '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(7, 2, 10, '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(8, 2, 11, '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(9, 2, 12, '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(10, 2, 13, '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(11, 2, 14, '2014-04-18', 'admin', '2014-04-30', 'admin', 1),
	(12, 2, 15, '2014-04-30', 'admin', '2014-04-30', 'admin', 1);
/*!40000 ALTER TABLE `b_posts_post_types` ENABLE KEYS */;


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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_post_types: ~3 rows (approximately)
/*!40000 ALTER TABLE `b_post_types` DISABLE KEYS */;
INSERT INTO `b_post_types` (`id`, `name`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'ABOUT', '0000-00-00', '', '0000-00-00', '', 0),
	(2, 'NEWS', '0000-00-00', '', '0000-00-00', '', 0),
	(3, 'CONTACT', '0000-00-00', '', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_post_types` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_procurement_plans
CREATE TABLE IF NOT EXISTS `b_procurement_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '0',
  `link_to_file` varchar(1000) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_procurement_plans: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_procurement_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_procurement_plans` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_resolutions
CREATE TABLE IF NOT EXISTS `b_resolutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '0',
  `link_to_file` varchar(1000) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_resolutions: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_resolutions` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_resolutions` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_session_dates
CREATE TABLE IF NOT EXISTS `b_session_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `duration` int(11) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_session_dates: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_session_dates` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_session_dates` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_surveys
CREATE TABLE IF NOT EXISTS `b_surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `b_survey_question_id` int(11) NOT NULL,
  `b_survey_option_id` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_surveys: ~22 rows (approximately)
/*!40000 ALTER TABLE `b_surveys` DISABLE KEYS */;
INSERT INTO `b_surveys` (`id`, `b_survey_question_id`, `b_survey_option_id`, `published`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(13, 29, 28, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(14, 29, 29, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(15, 29, 30, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(16, 30, 31, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(17, 30, 32, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(18, 30, 33, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(19, 31, 34, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(20, 31, 35, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(21, 31, 36, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(22, 32, 37, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(23, 32, 38, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(24, 32, 39, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(25, 33, 40, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(26, 33, 41, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(27, 33, 42, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(28, 33, 43, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(29, 34, 44, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(30, 34, 45, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(31, 34, 46, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(32, 34, 47, 0, '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(33, 35, 48, 0, '2014-05-25', 'admin', '0000-00-00', '', 0),
	(34, 35, 49, 0, '2014-05-25', 'admin', '0000-00-00', '', 0);
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
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_survey_options: ~19 rows (approximately)
/*!40000 ALTER TABLE `b_survey_options` DISABLE KEYS */;
INSERT INTO `b_survey_options` (`id`, `answer_text`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(28, 'Josh', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(29, 'Man', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(30, 'Auza', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(31, 'Malaysia', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(32, 'Manila', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(33, 'Singapore', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(34, 'Marikina', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(35, 'Argentina', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(36, 'Chile', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(37, 'Who ', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(38, 'Who', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(39, 'Who', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(40, 'ds', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(41, 'ds', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(42, 'ds', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(43, 'ds', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(44, 'te', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(45, 'te', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(46, 'te', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(47, 'te', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(48, 'good ', '2014-05-25', 'admin', '0000-00-00', '', 0),
	(49, 'good\r\n', '2014-05-25', 'admin', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_survey_options` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_survey_questions
CREATE TABLE IF NOT EXISTS `b_survey_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(1000) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_survey_questions: ~6 rows (approximately)
/*!40000 ALTER TABLE `b_survey_questions` DISABLE KEYS */;
INSERT INTO `b_survey_questions` (`id`, `question`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(29, 'Who are you?', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(30, 'Which country do you want to live?', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(31, 'Which country do you want to live?', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(32, 'Who are you?', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(33, 'ds', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(34, 'this is a test', '2014-05-25', 'admin', '2014-05-25', 'admin', 1),
	(35, 'This is ', '2014-05-25', 'admin', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `b_survey_questions` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_voting_history_ordinances
CREATE TABLE IF NOT EXISTS `b_voting_history_ordinances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sys_users_roles_id` int(11) NOT NULL DEFAULT '0',
  `ordinances_id` int(11) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_voting_history_ordinances: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_voting_history_ordinances` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_voting_history_ordinances` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.b_voting_history_resolutions
CREATE TABLE IF NOT EXISTS `b_voting_history_resolutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sys_user_roles_id` int(11) NOT NULL DEFAULT '0',
  `resolution_id` int(11) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.b_voting_history_resolutions: ~0 rows (approximately)
/*!40000 ALTER TABLE `b_voting_history_resolutions` DISABLE KEYS */;
/*!40000 ALTER TABLE `b_voting_history_resolutions` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.sys_menus
CREATE TABLE IF NOT EXISTS `sys_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.sys_menus: ~0 rows (approximately)
/*!40000 ALTER TABLE `sys_menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_menus` ENABLE KEYS */;


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
	(1, 'Super Administrator', 'Manages accounts of other users.', '0000-00-00', '', '0000-00-00', '', 0),
	(2, 'Super User', '', '0000-00-00', '', '0000-00-00', '', 0),
	(3, 'Administrator', '', '0000-00-00', '', '0000-00-00', '', 0),
	(4, 'Voter', '', '0000-00-00', '', '0000-00-00', '', 0),
	(5, 'Councilor', 'Councilors', '0000-00-00', '', '0000-00-00', '', 0),
	(6, 'Guest', 'Guest', '0000-00-00', '', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `sys_roles` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.sys_roles_menus
CREATE TABLE IF NOT EXISTS `sys_roles_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `menu_id` int(11) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.sys_roles_menus: ~2 rows (approximately)
/*!40000 ALTER TABLE `sys_roles_menus` DISABLE KEYS */;
INSERT INTO `sys_roles_menus` (`id`, `role_id`, `menu_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 1, 1, '0000-00-00', '', '0000-00-00', '', 0),
	(2, 2, 1, '0000-00-00', '', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `sys_roles_menus` ENABLE KEYS */;


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
  `address` varchar(100) NOT NULL,
  `contacts` varchar(50) NOT NULL,
  `email_add` varchar(100) NOT NULL,
  `signature` varchar(200) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.sys_users: ~30 rows (approximately)
/*!40000 ALTER TABLE `sys_users` DISABLE KEYS */;
INSERT INTO `sys_users` (`id`, `firstname`, `lastname`, `address`, `contacts`, `email_add`, `signature`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'Superadmin', 'Auza', 'San Jose, Tacloban City', '', '', '', '0000-00-00', '', '0000-00-00', '', 1),
	(2, 'Superuser', 'Auza', '', '', '', '', '0000-00-00', '', '0000-00-00', '', 0),
	(3, 'Administrator', 'Auza', 'Makati City', '', '', '', '0000-00-00', '', '0000-00-00', '', 0),
	(4, 'Voters', 'Auza', 'san jose', '', '', '', '0000-00-00', '', '0000-00-00', '', 0),
	(5, 'Councilors', 'Auza', 'San Jose Tacloban', '', '', 'sig.png', '0000-00-00', '', '2014-06-08', 'councilor', 0),
	(6, 'Guest', 'Auza', 'San Jose Tacloban', '', '', '', '0000-00-00', '', '0000-00-00', '', 0),
	(20, 'Josh', 'Auza', 'Quezon City', '', '', '', '0000-00-00', '', '0000-00-00', '', 0),
	(26, 'ffd', 'f;f;f', 'fd', 'fd', 'fdfd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(27, 'dsd', 'dsds', 'dsds', 'sdds', 'dsdd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(28, 'lebron', 'james', 'ds', 'ds', 'ds', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(29, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(30, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(31, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(32, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(33, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(34, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(35, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(36, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(37, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(38, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(39, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(40, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(41, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(42, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(43, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(44, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(45, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(46, 'run', 'jay', 'test', 'dsds', 'dssd', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(47, 'James', 'Manila', 'Manila', '09133', 'hsojauza@gmail.com', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(48, 'gray', 'jean', 'manila', 's', 'a', 'sig.png', '2014-06-07', 'superuser', '2014-06-09', 'jean', 0),
	(49, 'auza', 'auza', 'auza', 'auza', 'auza', 'sig.png', '2014-06-09', 'superuser', '2014-06-09', 'nba', 0);
/*!40000 ALTER TABLE `sys_users` ENABLE KEYS */;


-- Dumping structure for table bacoor_dev.sys_user_roles
CREATE TABLE IF NOT EXISTS `sys_user_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sys_user_id` int(10) NOT NULL,
  `sys_role_id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table bacoor_dev.sys_user_roles: ~34 rows (approximately)
/*!40000 ALTER TABLE `sys_user_roles` DISABLE KEYS */;
INSERT INTO `sys_user_roles` (`id`, `sys_user_id`, `sys_role_id`, `username`, `password`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 1, 1, 'superadmin', 'a', '0000-00-00', '', '0000-00-00', '', 0),
	(2, 2, 2, 'superuser', 'a', '0000-00-00', '', '0000-00-00', '', 0),
	(3, 3, 3, 'admin', 'a', '0000-00-00', '', '0000-00-00', '', 0),
	(4, 4, 4, 'voter', 'a', '0000-00-00', '', '0000-00-00', '', 0),
	(5, 5, 5, 'councilor', 'a', '0000-00-00', '', '0000-00-00', '', 0),
	(9, 6, 6, 'guest', 'a', '0000-00-00', '', '0000-00-00', '', 0),
	(19, 20, 1, 'josh', 'a', '0000-00-00', '', '0000-00-00', '', 0),
	(20, 21, 1, '', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(21, 22, 1, '', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(22, 23, 1, '', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(23, 24, 1, '', '', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(24, 25, 1, 'sasa', 'sasa', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(25, 26, 1, 'fdf', 'fdd', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(26, 27, 1, 'dsds', 'dss', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(27, 28, 1, 'dsds', 'sds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(28, 29, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(29, 30, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(30, 31, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(31, 32, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(32, 33, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(33, 34, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(34, 35, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(35, 36, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(36, 37, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(37, 38, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(38, 39, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(39, 40, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(40, 41, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(41, 42, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(42, 43, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(43, 44, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(44, 45, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(45, 46, 6, 'ds', 'dsds', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(46, 48, 5, 'jean', 'a', '2014-06-07', 'superuser', '0000-00-00', '', 0),
	(47, 49, 5, 'nba', 'a', '2014-06-09', 'superuser', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `sys_user_roles` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
