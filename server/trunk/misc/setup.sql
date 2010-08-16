-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 12. März 2009 um 21:05
-- Server Version: 5.0.67
-- PHP-Version: 5.2.6-2ubuntu4.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE `categories` (
  `id` int(16) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `schema` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `markers`
--

CREATE TABLE `markers` (
  `id` int(16) NOT NULL auto_increment,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `alt` double NOT NULL,
  `cat_id` int(16) NOT NULL,
  `data` longtext NOT NULL,
  `usr_id` int(16) NOT NULL,
  `last_updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `added` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tracks`
--

CREATE TABLE `tracks` (
  `id` int(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `markers` text NOT NULL,
  `user_id` int(16) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(16) NOT NULL auto_increment,
  `prename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `nick` varchar(255) NOT NULL,
  `home_lat` double NOT NULL,
  `home_lng` double NOT NULL,
  `last_updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL default '0000-00-00 00:00:00',
  `login_count` int(16) NOT NULL,
  `joined` timestamp NOT NULL default '0000-00-00 00:00:00',
  `last_ip` varchar(15) NOT NULL,
  `birthday` timestamp NOT NULL default '0000-00-00 00:00:00',
  `cell` int(16) NOT NULL,
  `web` varchar(255) NOT NULL,
  `icq` int(9) NOT NULL,
  `mail` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
