-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2023 at 03:07 PM
-- Server version: 5.6.15-log
-- PHP Version: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `local_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE IF NOT EXISTS `cards` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `info` varchar(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`card_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`card_id`, `user_id`, `name`, `info`) VALUES
(6, '1', 'Test 2', 'Hehe'),
(5, '1', 'Vcera', 'Info pro ucitele'),
(4, '1', 'Test', 'Zkusim otestnout'),
(7, '1', 'Prijem', 'Prijem z main prace'),
(8, '1', 'Test 3', 'bubuu'),
(9, '1', 'Test 4', 'kamo to musi fungovat'),
(10, '1', 'Test 5', 'Tedka uz to musi fungovat bro'),
(11, '1', 'Test 6', 'Konecne!! - Ted uz to musi'),
(12, '1', 'Test 7', 'Tohle je čistě text'),
(13, '1', 'Dalši test', 'graf kamo'),
(14, '1', 'Test 8', 'heheeeeee'),
(15, '2', 'Přijem', 'Můj osobní přijem'),
(16, '5', 'Prijem', 'Megga moc penez');

-- --------------------------------------------------------

--
-- Table structure for table `money`
--

CREATE TABLE IF NOT EXISTS `money` (
  `money_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `money` float(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`money_id`),
  KEY `card_id` (`card_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=32 ;

--
-- Dumping data for table `money`
--

INSERT INTO `money` (`money_id`, `card_id`, `user_id`, `money`, `date`) VALUES
(31, 16, 5, 4500.00, '2023-03-02'),
(30, 15, 2, 20664.76, '2023-02-27'),
(29, 10, 1, 1079.09, '2023-02-27'),
(28, 12, 1, -1200.00, '2023-02-27'),
(27, 8, 1, 500.00, '2023-02-27'),
(26, 5, 1, 1846.00, '2023-02-27'),
(25, 4, 1, 1006.00, '2023-02-28');

--
-- Triggers `money`
--
DROP TRIGGER IF EXISTS `transaction_history`;
DELIMITER //
CREATE TRIGGER `transaction_history` AFTER UPDATE ON `money`
 FOR EACH ROW BEGIN
    INSERT INTO `transaction`(`money_id`,`card_id`, `user_id`, `suma`, `date`,`money`) VALUES (OLD.money_id,OLD.card_id,OLD.user_id,NEW.money - OLD.money,curdate(), OLD.money);
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `money_id` int(11) DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `suma` float(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `money` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=201 ;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `money_id`, `card_id`, `user_id`, `suma`, `date`, `money`) VALUES
(160, 25, 4, 1, 400.00, '2023-02-27', 1067.00),
(159, 25, 4, 1, -300.00, '2023-02-27', 1367.00),
(161, 25, 4, 1, -1139.00, '2023-02-27', 1467.00),
(157, 25, 4, 1, 567.00, '2023-02-27', 0.00),
(158, 25, 4, 1, 800.00, '2023-02-27', 567.00),
(162, 25, 4, 1, 578.00, '2023-02-27', 328.00),
(163, 25, 4, 1, 100.00, '2023-02-27', 906.00),
(164, 26, 5, 1, 1890.00, '2023-02-27', 0.00),
(165, 26, 5, 1, 756.00, '2023-02-27', 1890.00),
(166, 26, 5, 1, -400.00, '2023-02-27', 2646.00),
(167, 26, 5, 1, 400.00, '2023-02-27', 2246.00),
(168, 26, 5, 1, -1200.00, '2023-02-27', 2646.00),
(169, 26, 5, 1, 400.00, '2023-02-27', 1446.00),
(170, 27, 8, 1, 100.00, '2023-02-27', 0.00),
(171, 27, 8, 1, 100.00, '2023-02-27', 100.00),
(172, 27, 8, 1, 100.00, '2023-02-27', 200.00),
(173, 27, 8, 1, 100.00, '2023-02-27', 300.00),
(174, 27, 8, 1, 100.00, '2023-02-27', 400.00),
(175, 27, 8, 1, -100.00, '2023-02-27', 500.00),
(176, 27, 8, 1, 100.00, '2023-02-27', 400.00),
(177, 28, 12, 1, 1800.00, '2023-02-27', 0.00),
(178, 28, 12, 1, -800.00, '2023-02-27', 1800.00),
(179, 28, 12, 1, 800.00, '2023-02-27', 1000.00),
(180, 28, 12, 1, -800.00, '2023-02-27', 1800.00),
(181, 28, 12, 1, 800.00, '2023-02-27', 1000.00),
(182, 28, 12, 1, -1000.00, '2023-02-27', 1800.00),
(183, 28, 12, 1, -1000.00, '2023-02-27', 800.00),
(184, 28, 12, 1, -1000.00, '2023-02-27', -200.00),
(185, 29, 10, 1, 200.00, '2023-02-27', 0.00),
(186, 29, 10, 1, 946.98, '2023-02-27', 200.00),
(187, 29, 10, 1, -100.00, '2023-02-27', 1146.98),
(188, 29, 10, 1, 600.00, '2023-02-27', 1046.98),
(189, 29, 10, 1, -567.89, '2023-02-27', 1646.98),
(190, 30, 15, 2, 55000.00, '2023-02-27', 0.00),
(191, 30, 15, 2, -22000.00, '2023-02-27', 55000.00),
(192, 30, 15, 2, -5789.00, '2023-02-27', 33000.00),
(193, 30, 15, 2, -6000.00, '2023-02-27', 27211.00),
(194, 30, 15, 2, -1290.00, '2023-02-27', 21211.00),
(195, 30, 15, 2, -3337.00, '2023-02-27', 19921.00),
(196, 30, 15, 2, 4080.76, '2023-02-27', 16584.00),
(197, 25, 4, 1, -800.00, '2023-02-28', 1006.00),
(198, 25, 4, 1, 800.00, '2023-02-28', 206.00),
(199, 31, 16, 5, 5000.00, '2023-03-02', 0.00),
(200, 31, 16, 5, -500.00, '2023-03-02', 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_key` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `user_name` char(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_surname` char(50) COLLATE utf8_bin DEFAULT NULL,
  `nickname` char(50) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `password` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `admin` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_key`, `user_name`, `user_surname`, `nickname`, `email`, `phone`, `password`, `admin`) VALUES
(1, 'jhfd6%$', 'Daniel', 'Tarelunga', 'MDfun', 'danilegos.t@gmail.com', 775696129, 'pepega', NULL),
(2, 'dkjf&875', 'Roman', 'Tarelunga', 'Peacemaker', 'r.tarelunga@yahoo.com', 775698236, '12345', NULL),
(3, 'lkf87@', 'Leoš', 'Gjumija', 'lTechnik', 'leos.gjumija@gmail.com', 778425325, 'technik22', NULL),
(4, 'w#s@m5j#4se', 'admin', 'admin', 'admin', 'admin@admin.com', 123456789, 'admin', 1),
(5, 'phn2uslnk', 'Vojta', 'Barton', 'Vojcech', 'vojtajeborec@educanet.cz', 567432998, '1234', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
