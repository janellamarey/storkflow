-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2014 at 03:33 PM
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
-- Table structure for table `sys_users`
--

CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `sys_users`
--

INSERT INTO `sys_users` (`id`, `firstname`, `lastname`, `address`, `deleted`) VALUES
(1, 'Joshua Joseph', 'Auza', 'San Jose, Tacloban City', 0),
(2, 'Natoff', 'Natoff', '', 1),
(3, 'Agnes Carmela', 'Exconde', 'Makati City', 0),
(7, 'Joshua', 'Auza', 'San Jose Tacloban', 0),
(5, 'Founder', 'Founder', 'san jose', 1),
(6, 'founder', 'founder', 'San Jose Tacloban', 0),
(8, 'test', 'test', 'test', 1),
(9, 'Joshua', 'Auza', 'Mandaluyong', 1),
(10, 'Derrick', 'Roses', 'Chicago', 0),
(11, 'Joshua', 'Auza', 'Mandaluyong', 0),
(12, 'Lebron', 'James', 'Cleveland', 0),
(13, 'lebron', 'james', 'Cleveland', 0),
(14, 'Joshua', 'Auza', 'Mandaluyong', 1),
(15, 'Timi', 'Nublado', 'Manila', 0),
(16, 'Eric', 'Padol', 'Makati', 0),
(17, 'Jenny', 'Agron', 'USA', 0),
(18, 'josh', 'auza', 'tacloban city', 0),
(19, 'Joshua', 'Auza', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
