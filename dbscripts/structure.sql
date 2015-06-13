-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 09, 2015 at 03:22 PM
-- Server version: 5.5.42
-- PHP Version: 5.6.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tad_courier`
--

-- --------------------------------------------------------

--
-- Table structure for table `courrier_service`
--

CREATE TABLE IF NOT EXISTS `courrier_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `telephone` varchar(64) NOT NULL,
  `other_telephone` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE IF NOT EXISTS `driver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NIC` varchar(20) DEFAULT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `mobile_number` varchar(200) NOT NULL,
  `other_number` varchar(200) NOT NULL,
  `courrier_service_provide_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courrier_service_provide_id` (`courrier_service_provide_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--
-- 
-- CREATE TABLE IF NOT EXISTS `location` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `name` varchar(600) NOT NULL,
--   `lat` float(30,27) NOT NULL,
--   `log` float(30,27) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Table structure for table `route`
--

CREATE TABLE IF NOT EXISTS `route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` int(11) NOT NULL DEFAULT '0',
  `destination` int(11) NOT NULL DEFAULT '0',
  `depature_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `days` varchar(255) NOT NULL,
  `driver_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `route_ibfk_2` (`driver_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;



--
-- Table structure for table `way_point`
--

 CREATE TABLE IF NOT EXISTS `way_point` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`route_id` int(11) NOT NULL,
 `lat` float(30,27) NOT NULL,
 `log` float(30,27) NOT NULL,
 `order` int(11) NOT NULL,
 PRIMARY KEY (`id`),
 FOREIGN KEY (`route_id`) REFERENCES `route`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE  `driver` ADD  `lat` FLOAT( 30, 27 ) NOT NULL AFTER  `courrier_service_provide_id` ,
ADD  `log` FLOAT( 30, 27 ) NOT NULL AFTER  `lat` ;

--
-- Table structure for table `service_log`
--

CREATE TABLE IF NOT EXISTS `service_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `driver_id` int(11) NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `customer_phone_no` varchar(10) NOT NULL,
  `from` varchar(20) NOT NULL,
  `to` varchar(20) NOT NULL,
  `service_status` tinyint(1) NOT NULL COMMENT 'false for delivery true for pickup',
  PRIMARY KEY (`id`),
FOREIGN KEY (`driver_id`) REFERENCES `driver`(`id`) ON DELETE CASCADE

) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


CREATE TABLE IF NOT EXISTS `location` (
	`country_code` char(2),
	`postal_code` varchar(20),
	`place_name` varchar(180),
	`admin_name1` varchar(100),
	`admin_code1` varchar(20),
	`admin_name2` varchar(100),
	`admin_code2` varchar(20),
	`admin_name3` varchar(100),
	`admin_code3` varchar(20),
	`latitude` decimal(7, 5),
	`longitude` decimal(8, 5),
	`accuracy` int(1)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
