-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 13, 2013 at 06:21 PM
-- Server version: 5.5.31
-- PHP Version: 5.4.4-14+deb7u4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test_lotto`
--

-- --------------------------------------------------------

--
-- Table structure for table `bets`
--

CREATE TABLE IF NOT EXISTS `bets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combinations` varchar(32) NOT NULL,
  `bet_date` datetime NOT NULL,
  `code` varchar(8) NOT NULL COMMENT 'unique bet code',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `draws`
--

CREATE TABLE IF NOT EXISTS `draws` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `combinations` varchar(32) NOT NULL COMMENT 'the winning numbers',
  `draw_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `winners`
--

CREATE TABLE IF NOT EXISTS `winners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `draw_id` int(10) unsigned NOT NULL,
  `bet_id` int(10) unsigned NOT NULL,
  `claimed` tinyint(4) NOT NULL DEFAULT '0',
  `date_claimed` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bet_id` (`bet_id`),
  KEY `draw_id` (`draw_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
