-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.16 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table storkflow.sflow_houses
CREATE TABLE IF NOT EXISTS `sflow_houses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `searchable` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- Dumping data for table storkflow.sflow_houses: ~0 rows (approximately)
/*!40000 ALTER TABLE `sflow_houses` DISABLE KEYS */;
/*!40000 ALTER TABLE `sflow_houses` ENABLE KEYS */;


-- Dumping structure for table storkflow.sflow_stories
CREATE TABLE IF NOT EXISTS `sflow_stories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `searchable` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- Dumping data for table storkflow.sflow_stories: ~0 rows (approximately)
/*!40000 ALTER TABLE `sflow_stories` DISABLE KEYS */;
/*!40000 ALTER TABLE `sflow_stories` ENABLE KEYS */;


-- Dumping structure for table storkflow.sflow_tasks
CREATE TABLE IF NOT EXISTS `sflow_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `searchable` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- Dumping data for table storkflow.sflow_tasks: ~0 rows (approximately)
/*!40000 ALTER TABLE `sflow_tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `sflow_tasks` ENABLE KEYS */;


-- Dumping structure for table storkflow.sflow_teams
CREATE TABLE IF NOT EXISTS `sflow_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `searchable` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table storkflow.sflow_teams: ~1 rows (approximately)
/*!40000 ALTER TABLE `sflow_teams` DISABLE KEYS */;
/*!40000 ALTER TABLE `sflow_teams` ENABLE KEYS */;


-- Dumping structure for table storkflow.sys_email_audit
CREATE TABLE IF NOT EXISTS `sys_email_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` char(50) CHARACTER SET latin1 NOT NULL,
  `subject` varchar(300) CHARACTER SET latin1 NOT NULL,
  `message` varchar(1000) CHARACTER SET latin1 NOT NULL,
  `recipients` text CHARACTER SET latin1 NOT NULL,
  `sent_datetime` datetime NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) CHARACTER SET latin1 NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) CHARACTER SET latin1 NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table storkflow.sys_email_audit: ~2 rows (approximately)
/*!40000 ALTER TABLE `sys_email_audit` DISABLE KEYS */;
INSERT INTO `sys_email_audit` (`id`, `from`, `subject`, `message`, `recipients`, `sent_datetime`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(9, 'Administrator', 'Bacoor City Council Notice: Your new password for www.bacoorcitycouncil.com', '                Dear User,\n\n                As you requested, your password has now been reset. Your new details are as follows:\n                \n                Username: admin\n                Password: 9b25fe4\n                    \n                To change your password, login to http://storkflow.xxx.com/ and in your dashboard click \'Change Password\'.\n                \n                All the best,\n                Administrator\n                www.lambsmarketing.com', 'joshauza@gmail.com', '2015-06-24 00:33:55', '2015-06-24', '', '0000-00-00', '', 0),
	(10, 'Administrator', 'Your new password for www.storkflow.xxx.com', '                Dear User,\n\n                As you requested, your password has now been reset. Your new details are as follows:\n                \n                Username: admin\n                Password: 5f8a7de\n                    \n                To change your password, login to http://storkflow.xxx.com/ and in your dashboard click \'Change Password\'.\n                \n                All the best,\n                Administrator\n                www.lambsmarketing.com', 'joshauza@gmail.com', '2015-06-24 00:35:23', '2015-06-24', '', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `sys_email_audit` ENABLE KEYS */;


-- Dumping structure for table storkflow.sys_email_queue
CREATE TABLE IF NOT EXISTS `sys_email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` char(50) CHARACTER SET latin1 NOT NULL,
  `subject` varchar(300) CHARACTER SET latin1 NOT NULL,
  `message` varchar(1000) CHARACTER SET latin1 NOT NULL,
  `recipients` text CHARACTER SET latin1 NOT NULL,
  `date_created` date NOT NULL,
  `user_created` varchar(50) CHARACTER SET latin1 NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) CHARACTER SET latin1 NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table storkflow.sys_email_queue: ~0 rows (approximately)
/*!40000 ALTER TABLE `sys_email_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_email_queue` ENABLE KEYS */;


-- Dumping structure for table storkflow.sys_roles
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table storkflow.sys_roles: ~4 rows (approximately)
/*!40000 ALTER TABLE `sys_roles` DISABLE KEYS */;
INSERT INTO `sys_roles` (`id`, `name`, `description`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'Administrator\r\n', 'Administrator', '0000-00-00', '', '0000-00-00', '', 0),
	(2, 'Scrum Master', 'Scrum Master', '0000-00-00', '', '0000-00-00', '', 0),
	(3, 'Member', 'Member', '0000-00-00', '', '0000-00-00', '', 0),
	(4, 'Guest', 'Guest', '0000-00-00', '', '0000-00-00', '', 0);
/*!40000 ALTER TABLE `sys_roles` ENABLE KEYS */;


-- Dumping structure for table storkflow.sys_role_mappings
CREATE TABLE IF NOT EXISTS `sys_role_mappings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL,
  `child_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table storkflow.sys_role_mappings: ~4 rows (approximately)
/*!40000 ALTER TABLE `sys_role_mappings` DISABLE KEYS */;
INSERT INTO `sys_role_mappings` (`id`, `parent_id`, `child_id`) VALUES
	(1, 1, 2),
	(2, 2, 3),
	(3, 3, 4),
	(4, 4, 0);
/*!40000 ALTER TABLE `sys_role_mappings` ENABLE KEYS */;


-- Dumping structure for table storkflow.sys_users
CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `mi` char(1) NOT NULL,
  `designation` char(10) NOT NULL,
  `address` varchar(100) NOT NULL DEFAULT 'Metro Manila',
  `contacts` varchar(50) NOT NULL,
  `email_add` varchar(100) NOT NULL,
  `searchable` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table storkflow.sys_users: ~7 rows (approximately)
/*!40000 ALTER TABLE `sys_users` DISABLE KEYS */;
INSERT INTO `sys_users` (`id`, `firstname`, `lastname`, `mi`, `designation`, `address`, `contacts`, `email_add`, `searchable`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 'Admin', 'Admin', 'J', '', 'Bacoor City', '0918888222', 'gh@gmail.com', 1, '0000-00-00', '', '2015-06-28', 'admin', 0),
	(2, 'SM', 'SM', 'H', 'Sr', 'Bacoor City', '0918888222', 'joshauza@gmail.com', 1, '0000-00-00', '', '2015-06-29', 'sm', 0),
	(3, 'Member', 'Member', 'K', '', 'Manila', '', 'joshauza@gmail.com', 1, '0000-00-00', '', '2014-08-02', '', 0),
	(4, 'Salcedo', 'Janel', 'K', '', 'Manila', '', '', 1, '0000-00-00', '', '2015-06-28', 'admin', 0),
	(5, 'Semilla', 'Jahir', 'K', '', 'Manila', '', '', 1, '0000-00-00', '', '2015-06-28', 'admin', 0),
	(6, 'Teodoro', 'April', 'K', '', 'Manila', '', '', 1, '0000-00-00', '', '2014-08-02', '', 0),
	(7, 'Angeles', 'Harvey', 'K', '', 'Manila', '', '', 1, '0000-00-00', '', '2015-06-30', 'admin', 1);
/*!40000 ALTER TABLE `sys_users` ENABLE KEYS */;


-- Dumping structure for table storkflow.sys_user_roles
CREATE TABLE IF NOT EXISTS `sys_user_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sys_user_id` int(10) NOT NULL,
  `sys_role_id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `status` char(10) NOT NULL DEFAULT 'NEW',
  `date_created` date NOT NULL,
  `user_created` varchar(50) NOT NULL,
  `date_last_modified` date NOT NULL,
  `user_last_modified` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table storkflow.sys_user_roles: ~8 rows (approximately)
/*!40000 ALTER TABLE `sys_user_roles` DISABLE KEYS */;
INSERT INTO `sys_user_roles` (`id`, `sys_user_id`, `sys_role_id`, `username`, `password`, `status`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
	(1, 1, 1, 'admin', 'b', 'REG', '0000-00-00', '', '2015-06-29', 'admin', 0),
	(2, 2, 2, 'sm', 'b', 'REG', '0000-00-00', '', '2015-06-29', 'sm', 0),
	(3, 3, 3, 'member', 'a', 'REG', '0000-00-00', '', '0000-00-00', '', 0),
	(4, 4, 3, 'janel', 'a', 'REG', '0000-00-00', '', '2015-06-28', 'admin', 0),
	(5, 5, 3, 'jahir', 'a', 'REG', '0000-00-00', '', '2015-06-28', 'admin', 0),
	(6, 6, 3, 'april', 'a', 'REG', '0000-00-00', '', '0000-00-00', '', 0),
	(7, 7, 3, 'harvey', 'a', 'REG', '0000-00-00', '', '2015-06-30', 'admin', 1);
/*!40000 ALTER TABLE `sys_user_roles` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
