-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.36-community-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema seeker
--

CREATE DATABASE IF NOT EXISTS seeker;
USE seeker;

--
-- Definition of table `audit_contract`
--

DROP TABLE IF EXISTS `audit_contract`;
CREATE TABLE `audit_contract` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contractid` int(10) unsigned NOT NULL,
  `submitted` varchar(30) NOT NULL,
  `actual` varchar(30) NOT NULL,
  `source` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Definition of table `audit_status`
--

DROP TABLE IF EXISTS `audit_status`;
CREATE TABLE `audit_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `previous` tinyint(1) NOT NULL,
  `new` tinyint(1) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Definition of table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` (`id`,`name`,`value`,`description`) VALUES 
 (1,'open','yes','Are new contracts being issued?  (yes, no)');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;


--
-- Definition of table `contract`
--

DROP TABLE IF EXISTS `contract`;
CREATE TABLE `contract` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assassin` int(10) unsigned NOT NULL,
  `target` int(10) unsigned NOT NULL,
  `assigned` datetime NOT NULL,
  `expiration` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `status` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_contract_1` (`assassin`),
  KEY `FK_contract_2` (`target`),
  KEY `FK_contract_3` (`status`),
  CONSTRAINT `FK_contract_1` FOREIGN KEY (`assassin`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_contract_2` FOREIGN KEY (`target`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_contract_3` FOREIGN KEY (`status`) REFERENCES `status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Definition of table `mail`
--

DROP TABLE IF EXISTS `mail`;
CREATE TABLE `mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `to` varchar(45) NOT NULL,
  `subject` varchar(45) NOT NULL,
  `body` text NOT NULL,
  `when` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Definition of table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Definition of table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` (`id`,`value`) VALUES 
 (1,'Open'),
 (2,'Succeeded'),
 (3,'Expired'),
 (4,'Failed'),
 (5,'Missed');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;


--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `secret` varchar(10) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `spawn` datetime NOT NULL,
  `uupdated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index_username` (`username`),
  UNIQUE KEY `Index_email` (`email`),
  UNIQUE KEY `Index_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Definition of table `zeep_mo`
--

DROP TABLE IF EXISTS `zeep_mo`;
CREATE TABLE `zeep_mo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `body` varchar(255) NOT NULL,
  `return` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Definition of table `zeep_out`
--

DROP TABLE IF EXISTS `zeep_out`;
CREATE TABLE `zeep_out` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `response` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  `notes` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Definition of table `zeep_sub`
--

DROP TABLE IF EXISTS `zeep_sub`;
CREATE TABLE `zeep_sub` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `min` varchar(255) NOT NULL,
  `return` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
