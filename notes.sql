-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 25 mars 2019 à 05:20
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `notes`
--

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
CREATE TABLE IF NOT EXISTS `matieres` (
  `ID_matiere` int(11) NOT NULL AUTO_INCREMENT,
  `Libelle` varchar(100) NOT NULL,
  `user_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID_matiere`),
  KEY `user_ID` (`user_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `matieres`
--

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `ID_note` int(250) NOT NULL AUTO_INCREMENT,
  `note` int(2) NOT NULL,
  `commentaire` varchar(500) DEFAULT NULL,
  `user_ID` int(250) NOT NULL,
  `test_ID` int(250) NOT NULL,
  PRIMARY KEY (`ID_note`),
  KEY `user_ID` (`user_ID`),
  KEY `test_ID` (`test_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `notes`
--

-- --------------------------------------------------------

--
-- Structure de la table `tests`
--

DROP TABLE IF EXISTS `tests`;
CREATE TABLE IF NOT EXISTS `tests` (
  `ID_test` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(200) NOT NULL,
  `matiere_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID_test`),
  KEY `matiere_ID` (`matiere_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tests`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permit` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `permit`, `created_at`) VALUES
(2, 'root', '$2y$10$BkIAYw.McjkaJWOsOAlGG.E.mrRRBc1jdbHEYxpp56fhnOFPv8uMe', 2, '2019-02-18 15:08:03'),
(23, 'Prof1', '$2y$10$fgeaVg.SsgfB4VkKDYWdIeCzH7Wq6JGtcrGZ6XwhkdqGkeo5EqLw2', 1, '2019-02-20 16:32:13'),
(24, 'Prof2', '$2y$10$JZkVBinPHDnXpO/bL/nYpORfb2B.ikDMwnBfvx0C1WlLf8R91UbNy', 1, '2019-02-20 16:32:24'),
(25, 'Eleve1', '$2y$10$kpTkbksuQHA2GN5EywvlPu4w5QUX7Qzrmy50MjXHss9UAOW8iqNc6', 0, '2019-02-20 16:33:35'),
(26, 'Eleve2', '$2y$10$kLpcy46YQm8fkx/xDVLiqe0a6f1/QAKSesV/kzGYawPzrvOpYS.Dy', 0, '2019-03-07 11:47:32'),
(27, 'Eleve3', '$2y$10$weHaryCor4cxZffGN02A4.D2dUdkTOssVoAjpP7.3a4eRhsBjLXkK', 0, '2019-03-07 11:47:40'),
(28, 'Eleve4', '$2y$10$Its5mPAAtwxDKa5hvrEZZe4rXwVvZAnY300nOokWyUA.6eHk6MZU6', 0, '2019-03-07 11:47:50');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD CONSTRAINT `matieres_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notes_ibfk_3` FOREIGN KEY (`test_ID`) REFERENCES `tests` (`ID_test`);

--
-- Contraintes pour la table `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`matiere_ID`) REFERENCES `matieres` (`ID_matiere`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
