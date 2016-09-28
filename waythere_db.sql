-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. Sep 2016 um 15:34
-- Server Version: 5.5.49
-- PHP-Version: 5.4.45-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `555712_3_1`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belongsto`
--

CREATE TABLE IF NOT EXISTS `belongsto` (
  `route_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `spot_id` int(11) NOT NULL,
  UNIQUE KEY `route_id` (`route_id`,`spot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Route`
--

CREATE TABLE IF NOT EXISTS `Route` (
  `route_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `durance_minutes` int(11) NOT NULL,
  `distance_meter` int(11) NOT NULL,
  `image` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`route_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `Route`
--

INSERT INTO `Route` (`route_id`, `name`, `description`, `durance_minutes`, `distance_meter`, `image`) VALUES
(1, 'Churer Altstadt', 'Eine kurze Testroute durch die Altstadt von Chur.', 6, 300, '/files/images/churer_altstadt.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Spot`
--

CREATE TABLE IF NOT EXISTS `Spot` (
  `spot_id` int(11) NOT NULL AUTO_INCREMENT,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `content` varchar(50) NOT NULL,
  PRIMARY KEY (`spot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `Spot`
--

INSERT INTO `Spot` (`spot_id`, `latitude`, `longitude`, `content`) VALUES
(1, 46.8484, 9.5326, 'martinskirche.m4a'),
(2, 46.8491, 9.53122, 'kornplatz.m4a'),
(3, 46.8499, 9.53088, 'fontanapark.m4a');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Tag`
--

CREATE TABLE IF NOT EXISTS `Tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `taged`
--

CREATE TABLE IF NOT EXISTS `taged` (
  `route_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  UNIQUE KEY `route_id` (`route_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
