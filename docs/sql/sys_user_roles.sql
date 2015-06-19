-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2014 at 03:32 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lomdb_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `sys_user_roles`
--

CREATE TABLE IF NOT EXISTS `sys_user_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sys_user_id` int(10) NOT NULL,
  `sys_role_id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `sys_user_roles`
--

INSERT INTO `sys_user_roles` (`id`, `sys_user_id`, `sys_role_id`, `username`, `password`, `deleted`) VALUES
(1, 1, 3, 'founder', 'a', 0),
(2, 2, 2, 'natoff', 'a', 0),
(3, 3, 1, 'npagnes', 'a', 0),
(4, 5, 3, 'FFOUNDER', 'password', 0),
(5, 6, 3, 'FFOUNDER', 'password', 0),
(9, 10, 1, 'drose', 'password', 0),
(7, 8, 1, 'TTEST', 'password', 0),
(8, 9, 3, 'jauza', 'password', 1),
(11, 12, 3, 'arte', 'a', 0),
(12, 13, 2, 'gegi', 'b', 0),
(13, 14, 1, 'josh', 'pass', 0),
(14, 15, 1, 'timi', 'a', 0),
(15, 16, 1, 'eric', 'a', 0),
(16, 17, 3, 'jen', 'a', 0),
(17, 18, 3, 'a', 'a', 0),
(18, 19, 1, 'jauza', 'a', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
