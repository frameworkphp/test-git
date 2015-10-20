-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 20, 2015 at 01:51 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `framework`
--
CREATE DATABASE IF NOT EXISTS `framework` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `framework`;

-- --------------------------------------------------------

--
-- Table structure for table `tb_logs`
--

CREATE TABLE IF NOT EXISTS `tb_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT '',
  `type` int(3) DEFAULT '0',
  `content` text,
  `created_at` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE IF NOT EXISTS `tb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `email` varchar(200) CHARACTER SET utf8 NOT NULL,
  `password` varchar(200) CHARACTER SET utf8 NOT NULL,
  `role` enum('admin','employee','member') CHARACTER SET utf8 NOT NULL DEFAULT 'member',
  `gender` enum('male','female') CHARACTER SET utf8 NOT NULL DEFAULT 'male',
  `status` smallint(1) NOT NULL DEFAULT '0',
  `datecreated` int(10) NOT NULL DEFAULT '0',
  `datemodified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `name`, `email`, `password`, `role`, `gender`, `status`, `datecreated`, `datemodified`) VALUES
(1, 'Phan Nguyen', 'phannguyen2020@gmail.com', '$2a$08$33rFl856XekXiAjP3sewv.y6FXRHQTtxa1Hyaw0WapwcHBy.fLgaC', 'member', 'male', 0, 1444642120, 0),
(2, 'Nguyen Phan', 'chephong45@yahoo.com', '$2a$08$elwR07L2EMSH7QiJECWL5OFgFhMVSiWe0VxOjf3MOpYHWlXAnywCe', 'member', 'male', 0, 1444795997, 0),
(6, 'Phan Nguyen', 'phan@cent.vn', '$2a$08$aqmgMe9sYe5HimCvLmUSm.TSoTJWj6cFVr3kVmv4yJAVcm6CWqJWq', 'member', 'female', 0, 1444816891, 0),
(20, 'Phan Nguyen', 'anh.phanhai7@gmail.com', '$2a$08$QMPKZMhfAaXVk3VfKWhz6e9Prb65BsdviOf/GGEhU4uGWjY70TGkm', 'member', 'female', 0, 1445250629, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
