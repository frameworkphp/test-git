-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2016 at 02:46 AM
-- Server version: 5.7.9
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `framework`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_logs`
--

DROP TABLE IF EXISTS `tb_logs`;
CREATE TABLE IF NOT EXISTS `tb_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT '',
  `type` int(3) DEFAULT '0',
  `content` text,
  `created_at` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_logs`
--

INSERT INTO `tb_logs` (`id`, `name`, `type`, `content`, `created_at`) VALUES
(1, 'Login member', 6, 'a:5:{s:7:"user_id";s:2:"20";s:5:"email";s:22:"anh.phanhai7@gmail.com";s:4:"role";s:6:"member";s:10:"user_agent";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36";s:10:"ip_address";s:9:"127.0.0.1";}', 1457145401),
(2, 'Login member', 6, 'a:5:{s:7:"user_id";s:2:"20";s:5:"email";s:22:"anh.phanhai7@gmail.com";s:4:"role";s:6:"member";s:10:"user_agent";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36";s:10:"ip_address";s:9:"127.0.0.1";}', 1457145462),
(3, 'Login member', 6, 'a:5:{s:7:"user_id";s:2:"20";s:5:"email";s:22:"anh.phanhai7@gmail.com";s:4:"role";s:6:"member";s:10:"user_agent";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36";s:10:"ip_address";s:9:"127.0.0.1";}', 1457145635),
(4, 'Login admin', 6, 'a:5:{s:7:"user_id";s:2:"20";s:5:"email";s:22:"anh.phanhai7@gmail.com";s:4:"role";s:5:"admin";s:10:"user_agent";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36";s:10:"ip_address";s:9:"127.0.0.1";}', 1457145693),
(5, 'Login admin', 6, 'a:5:{s:7:"user_id";s:2:"20";s:5:"email";s:22:"anh.phanhai7@gmail.com";s:4:"role";s:5:"admin";s:10:"user_agent";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36";s:10:"ip_address";s:9:"127.0.0.1";}', 1457145731),
(6, 'Login admin', 6, 'a:5:{s:7:"user_id";s:2:"20";s:5:"email";s:22:"anh.phanhai7@gmail.com";s:4:"role";s:5:"admin";s:10:"user_agent";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36";s:10:"ip_address";s:9:"127.0.0.1";}', 1457145960);

-- --------------------------------------------------------

--
-- Table structure for table `tb_token`
--

DROP TABLE IF EXISTS `tb_token`;
CREATE TABLE IF NOT EXISTS `tb_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `datecreated` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_token`
--

INSERT INTO `tb_token` (`id`, `user_id`, `token`, `user_agent`, `datecreated`) VALUES
(1, 20, '67c2349b7214b4ff1e05e1d6661111ec', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36', 1457145960);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE IF NOT EXISTS `tb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `email` varchar(200) CHARACTER SET utf8 NOT NULL,
  `password` varchar(200) CHARACTER SET utf8 NOT NULL,
  `role` enum('admin','employee','member') CHARACTER SET utf8 NOT NULL DEFAULT 'member',
  `gender` enum('male','female') CHARACTER SET utf8 NOT NULL DEFAULT 'male',
  `avatar` varchar(255) DEFAULT '',
  `status` smallint(1) NOT NULL DEFAULT '0',
  `datecreated` int(10) NOT NULL DEFAULT '0',
  `datemodified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `name`, `email`, `password`, `role`, `gender`, `avatar`, `status`, `datecreated`, `datemodified`) VALUES
(1, 'Phan Nguyen', 'phannguyen2020@gmail.com', '$2a$08$5DfGNKxvsN85qQnBEiWMFelALs5Ad/tVTu0X0COzDCLGwYTg/JNsy', 'admin', 'male', '2016/March/5/853ffe834f4c73a9258e1db3343afc99-56da485053f84.png', 1, 1444642120, 1457145936),
(2, 'Nguyen Phan', 'chephong45@yahoo.com', '$2a$08$elwR07L2EMSH7QiJECWL5OFgFhMVSiWe0VxOjf3MOpYHWlXAnywCe', 'member', 'male', '', 0, 1444795997, 0),
(6, 'Phan Nguyen', 'phan@cent.vn', '$2a$08$aqmgMe9sYe5HimCvLmUSm.TSoTJWj6cFVr3kVmv4yJAVcm6CWqJWq', 'member', 'female', '', 0, 1444816891, 0),
(20, 'Phan Nguyen', 'anh.phanhai7@gmail.com', '$2a$08$QMPKZMhfAaXVk3VfKWhz6e9Prb65BsdviOf/GGEhU4uGWjY70TGkm', 'admin', 'female', '', 0, 1445250629, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
