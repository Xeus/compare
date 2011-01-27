-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2011 at 01:05 AM
-- Server version: 5.0.91
-- PHP Version: 5.2.6

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
  `Circus Peanuts` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Overall`
--

CREATE TABLE IF NOT EXISTS `Overall` (
  `id` int(11) NOT NULL,
  `Overall` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Results`
--

CREATE TABLE IF NOT EXISTS `Results` (
  `VoteTime` text NOT NULL,
  `Winner` text NOT NULL,
  `Loser` text NOT NULL,
  `IPAddress` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
