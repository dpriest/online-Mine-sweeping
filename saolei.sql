-- phpMyAdmin SQL Dump
-- version 3.5.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 15, 2012 at 09:13 PM
-- Server version: 5.1.52-log
-- PHP Version: 5.4.0RC4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `saolei`
--
CREATE DATABASE `saolei` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `saolei`;

-- --------------------------------------------------------

--
-- Table structure for table `battle`
--

CREATE TABLE IF NOT EXISTS `battle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `buid` int(11) NOT NULL,
  `result` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `battle`
--

INSERT INTO `battle` (`id`, `uid`, `buid`, `result`) VALUES
(1, 4, 6, 1),
(2, 3, 16, 1),
(3, 3, 16, 0),
(4, 3, 16, 1),
(5, 3, 16, 1),
(6, 3, 16, -1),
(7, 3, 16, 1),
(8, 3, 16, 1),
(9, 3, 16, 1),
(10, 3, 16, 1),
(11, 4, 3, 1),
(12, 6, 4, -1),
(13, 13, 4, -1),
(14, 13, 4, -1),
(15, 3, 4, -1),
(16, 3, 4, -1),
(17, 3, 4, 1),
(18, 3, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `challenge`
--

CREATE TABLE IF NOT EXISTS `challenge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cuid` int(11) NOT NULL,
  `result` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `challenge`
--

INSERT INTO `challenge` (`id`, `uid`, `cuid`, `result`) VALUES
(1, 2, 3, 0),
(2, 4, 1, -1),
(3, 4, 4, 1),
(4, 1, 2, 0),
(5, 2, 3, -1),
(6, 4, 3, 1),
(7, 3, 3, 1),
(8, 3, 3, 1),
(9, 3, 4, 0),
(10, 3, 4, 0),
(11, 3, 4, 0),
(12, 3, 4, 1),
(13, 4, 1, -1),
(14, 4, 1, -1),
(15, 14, 3, -1),
(16, 14, 3, -1),
(17, 14, 4, 0),
(18, 14, 3, -1),
(19, 14, 3, -1),
(20, 14, 3, 0),
(21, 14, 3, -1),
(22, 14, 3, -1),
(23, 14, 3, -1),
(24, 14, 3, -1),
(25, 14, 3, -1),
(26, 4, 3, 0),
(27, 3, 4, -1),
(28, 3, 3, 1),
(29, 3, 14, 1),
(30, 3, 4, 0),
(31, 3, 14, -1),
(32, 3, 3, -1),
(33, 3, 14, -1);

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `seconds` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `userid`, `seconds`) VALUES
(38, 3, 0),
(2, 3, 7),
(5, 4, 5),
(7, 4, 6),
(8, 4, 8),
(9, 4, 15),
(10, 4, 21),
(11, 4, 1),
(12, 3, 7),
(13, 4, 9),
(14, 3, 6),
(43, 3, 44),
(18, 3, 5),
(19, 3, 4),
(20, 3, 2),
(42, 3, 62),
(22, 4, 9),
(23, 4, 11),
(24, 14, 16),
(25, 14, 6),
(41, 3, 43),
(40, 3, 4),
(28, 14, 3),
(37, 3, 7),
(39, 3, 0),
(36, 4, 4),
(32, 14, 1),
(33, 14, 1),
(34, 14, 2),
(35, 14, 3);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid1` int(11) DEFAULT NULL,
  `uid2` int(11) DEFAULT NULL,
  `state1` int(11) DEFAULT NULL,
  `state2` int(11) DEFAULT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `uid1`, `uid2`, `state1`, `state2`, `num`) VALUES
(1, NULL, NULL, NULL, NULL, 0),
(2, NULL, NULL, NULL, NULL, 0),
(3, NULL, NULL, NULL, NULL, 0),
(4, NULL, NULL, NULL, NULL, 0),
(5, NULL, NULL, NULL, NULL, 0),
(6, NULL, NULL, NULL, NULL, 0),
(7, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`) VALUES
(16, 'fbd', '704a9613f075f9dafff1a0146b76558e', 'fbd@fbd.com'),
(3, 'tang1', 'f1c1592588411002af340cbaedd6fc33', '451015188@qq.com'),
(4, 'tang2', 'f1c1592588411002af340cbaedd6fc33', '451015188@qq.com'),
(5, '222', 'bcbe3365e6ac95ea2c0343a2395834dd', '222@163.com'),
(6, 'tang3', 'f1c1592588411002af340cbaedd6fc33', '4@qq.com'),
(7, 'pasu1', 'f1c1592588411002af340cbaedd6fc33', '1@qq.com'),
(8, 'pasu2', 'f1c1592588411002af340cbaedd6fc33', '1@qq.com'),
(9, 'dpriest', 'e10adc3949ba59abbe56e057f20f883e', 'wenhaoz100@gmail.com'),
(10, 'huangxiaojian', 'f379eaf3c831b04de153469d1bec345e', 'xiaojianh@126.com'),
(11, 'å”æ˜¯å°ç¾Žå¥³', 'e10adc3949ba59abbe56e057f20f883e', 'hh111@126.com'),
(12, 'shuguang', 'e10adc3949ba59abbe56e057f20f883e', '554874771@qq.com'),
(13, 'tang4', 'f1c1592588411002af340cbaedd6fc33', '4@qq.com'),
(14, 'linshuguang', 'e10adc3949ba59abbe56e057f20f883e', '554874771@qq.com'),
(15, 'maple', '25f9e794323b453885f5181f1b624d0b', '123456789@132.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
