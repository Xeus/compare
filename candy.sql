-- phpMyAdmin SQL Dump
-- version 3.3.10.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 30, 2011 at 08:35 PM
-- Server version: 5.0.92
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `benturne_candy`
--

-- --------------------------------------------------------

--
-- Table structure for table `candy_records`
--

CREATE TABLE IF NOT EXISTS `candy_records` (
  `id` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Overall` text NOT NULL,
  `Abba-Zaba` text NOT NULL,
  `Almond Joy` text NOT NULL,
  `Atomic Fire Balls` text NOT NULL,
  `Baby Ruth` text NOT NULL,
  `Blow Pop` text NOT NULL,
  `Butterfinger` text NOT NULL,
  `Candy Corn` text NOT NULL,
  `Caramels` text NOT NULL,
  `Circus Peanuts` text NOT NULL,
  `M&Ms` text NOT NULL,
  `Milky Way` text NOT NULL,
  `Reese's` text NOT NULL,
  `Snickers` text NOT NULL,
  `Three Musketeers` text NOT NULL,
  `Pop Rocks` text NOT NULL,
  `Nerds` text NOT NULL,
  `Twizzlers` text NOT NULL,
  `Twix` text NOT NULL,
  `Hershey Kisses` text NOT NULL,
  `Rolo` text NOT NULL,
  `Gummi Bears` text NOT NULL,
  `Sour Worms` text NOT NULL,
  `Jolly Rancher` text NOT NULL,
  `Life Savers` text NOT NULL,
  `Gobstoppers` text NOT NULL,
  `Sugar Daddy` text NOT NULL,
  `Warheads` text NOT NULL,
  `Sour Patch Kids` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `candy_records`
--

INSERT INTO `candy_records` (`id`, `Name`, `Overall`, `Abba-Zaba`, `Almond Joy`, `Atomic Fire Balls`, `Baby Ruth`, `Blow Pop`, `Butterfinger`, `Candy Corn`, `Caramels`, `Circus Peanuts`, `M&Ms`, `Milky Way`, `Reese's`, `Snickers`, `Three Musketeers`, `Pop Rocks`, `Nerds`, `Twizzlers`, `Twix`, `Hershey Kisses`, `Rolo`, `Gummi Bears`, `Sour Worms`, `Jolly Rancher`, `Life Savers`, `Gobstoppers`, `Sugar Daddy`, `Warheads`, `Sour Patch Kids`) VALUES
(1, 'Abba-Zaba', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(2, 'Almond Joy', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(3, 'Atomic Fire Balls', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(4, 'Baby Ruth', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(5, 'Blow Pop', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(6, 'Butterfinger', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(7, 'Candy Corn', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(8, 'Caramels', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(9, 'Circus Peanuts', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(10, 'M&Ms', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(11, 'Milky Way', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(12, 'Reese''s', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(13, 'Snickers', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(14, 'Three Musketeers', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(28, 'Pop Rocks', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(15, 'Nerds', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(16, 'Twizzlers', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(17, 'Twix', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(18, 'Hershey Kisses', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(19, 'Rolo', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(20, 'Gummi Bears', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(21, 'Sour Worms', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(22, 'Jolly Rancher', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(23, 'Life Savers', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(24, 'Gobstoppers', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(25, 'Sugar Daddy', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(26, 'Warheads', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(27, 'Sour Patch Kids', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
