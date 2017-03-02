-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 02 Mars 2017 à 10:56
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `espacenutrition`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnements`
--

CREATE TABLE IF NOT EXISTS `abonnements` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(100) NOT NULL,
  `DATEDEBUT` date NOT NULL,
  `DATEFIN` date NOT NULL,
  `TYPE` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `auteur` varchar(50) NOT NULL,
  `partie1` text NOT NULL,
  `partie2` text NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `article_categorie`
--

CREATE TABLE IF NOT EXISTS `article_categorie` (
  `id_article` bigint(20) NOT NULL,
  `id_categorie` bigint(20) NOT NULL,
  PRIMARY KEY (`id_article`,`id_categorie`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) NOT NULL,
  `id_parent` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE IF NOT EXISTS `paiements` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `txnid` varchar(20) NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `payment_status` varchar(25) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `createdtime` datetime NOT NULL,
  `payer_id` varchar(100) NOT NULL,
  `payer_last_name` varchar(100) NOT NULL,
  `payer_first_name` varchar(100) NOT NULL,
  `payer_email` varchar(100) NOT NULL,
  `business` varchar(100) NOT NULL,
  `mode` varchar(10) NOT NULL,
  UNIQUE KEY `Id` (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `poids`
--

CREATE TABLE IF NOT EXISTS `poids` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `DATEMESURE` date NOT NULL,
  `POIDS` float NOT NULL,
  `COMMENTAIRE` varchar(4000) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `repas`
--

CREATE TABLE IF NOT EXISTS `repas` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(100) NOT NULL,
  `DATEHEUREMODIFICATION` datetime NOT NULL,
  `DATEHEUREMESURE` datetime NOT NULL,
  `REPAS` varchar(4000) NOT NULL,
  `COMMENTAIRE` varchar(4000) DEFAULT NULL,
  `COMMENTAIREDIET` varchar(4000) DEFAULT NULL,
  `DATEHEURECOMMENTAIREDIET` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `temoignages`
--

CREATE TABLE IF NOT EXISTS `temoignages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(100) NOT NULL,
  `age` int(3) NOT NULL,
  `objectif` varchar(200) NOT NULL,
  `temoignage` text NOT NULL,
  `date` date NOT NULL,
  `valide` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(100) NOT NULL,
  `PASSWORD` varchar(50) NOT NULL,
  `NOM` varchar(100) NOT NULL,
  `PRENOM` varchar(100) NOT NULL,
  `DATENAISSANCE` date NOT NULL,
  `role` int(11) NOT NULL DEFAULT '0',
  `ACTIF` tinyint(1) NOT NULL,
  `TOKEN` varchar(40) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
