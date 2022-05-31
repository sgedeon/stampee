-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 05 mai 2022 à 21:20
-- Version du serveur : 5.7.34
-- Version de PHP : 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ENCHERES`
--

-- --------------------------------------------------------

--
-- Structure de la table `ENCHERE`
--

CREATE TABLE `ENCHERE` (
  `id_enchere` int(11) UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `prix_plancher` smallint(8) UNSIGNED NOT NULL,
  `coup_de_coeur_du_lord` varchar(42) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ENCHERE`
--

INSERT INTO `ENCHERE` (`id_enchere`, `date_debut`, `date_fin`, `prix_plancher`, `coup_de_coeur_du_lord`) VALUES
(1, '2022-01-23', '2022-02-23', 200, 'non'),
(2, '2022-05-01', '2022-05-25', 500, 'non'),
(3, '2022-05-03', '2022-05-28', 500, 'non'),
(4, '2022-05-05', '2022-06-01', 500, 'non'),
(5, '2022-04-05', '2022-05-01', 500, 'non'),
(6, '2022-05-08', '2022-06-05', 500, 'non'),
(7, '2022-05-09', '2022-06-09', 500, 'non'),
(8, '2022-05-09', '2022-06-17', 500, 'non'),
(9, '2022-05-09', '2022-06-19', 500, 'oui'),
(10, '2022-05-10', '2022-06-25', 500, 'oui'),
(11, '2022-05-10', '2022-06-26', 500, 'oui'),
(12, '2022-05-10', '2022-06-18', 500, 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `IMAGES`
--

CREATE TABLE `IMAGES` (
  `id_image` int(10) UNSIGNED NOT NULL,
  `url` varchar(255) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `image_principale` tinyint(1) DEFAULT NULL,
  `id_timbre_image` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `IMAGES`
--

INSERT INTO `IMAGES` (`id_image`, `url`, `titre`, `image_principale`, `id_timbre_image`) VALUES
(1, './Images/timbre-01.jpg', 'Marianne', 1, 1),
(2, './Images/timbre-02.jpeg', 'Scott 157', 1, 2),
(3, './Images/timbre-03.jpeg', 'Scott 302', 2, 2),
(4, './Images/timbre-04.jpeg', 'Scott 352', 3, 2),
(5, './Images/timbre-03.jpeg', 'Scott 302', 1, 3),
(6, './Images/timbre-04.jpeg', 'Scott 352', 1, 4),
(7, './Images/timbre-05.jpeg', 'American FA', 1, 5),
(8, './Images/timbre-06.jpeg', 'Poste nationale', 1, 6),
(9, './Images/timbre-07.jpeg', 'Princesse', 1, 7),
(10, './Images/timbre-08.jpeg', 'Chine contemporaine', 1, 8),
(11, './Images/timbre-09.jpeg', 'Napoléon 3', 1, 9),
(12, './Images/timbre-10.jpeg', 'Napoléon 3', 1, 10),
(13, './Images/timbre-11.jpeg', 'Nasser', 1, 11),
(14, './Images/timbre-12.jpeg', 'Ghandi', 1, 12);

-- --------------------------------------------------------

--
-- Structure de la table `PARTICIPE`
--

CREATE TABLE `PARTICIPE` (
  `id_enchere_mise` int(10) UNSIGNED NOT NULL,
  `utilisateur_id_mise` int(10) UNSIGNED NOT NULL,
  `mise` smallint(8) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `PARTICIPE`
--

INSERT INTO `PARTICIPE` (`id_enchere_mise`, `utilisateur_id_mise`, `mise`) VALUES
(1, 1, 200),
(2, 1, 2000),
(3, 1, 8800),
(4, 1, 720),
(5, 1, 890),
(6, 1, 500),
(7, 1, 800),
(8, 1, 7700),
(9, 1, 900),
(10, 1, 80),
(12, 1, 500);

-- --------------------------------------------------------

--
-- Structure de la table `TIMBRE`
--

CREATE TABLE `TIMBRE` (
  `id_timbre` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `date_de_creation` int(4) UNSIGNED DEFAULT NULL,
  `couleurs` varchar(42) DEFAULT NULL,
  `pays_origine` varchar(255) NOT NULL,
  `tirage` varchar(42) DEFAULT NULL,
  `dimensions` varchar(255) NOT NULL,
  `etat` varchar(42) DEFAULT NULL,
  `certification` varchar(42) DEFAULT NULL,
  `id_timbre_enchere` int(10) UNSIGNED DEFAULT NULL,
  `id_timbre_utilisateur` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `TIMBRE`
--

INSERT INTO `TIMBRE` (`id_timbre`, `nom`, `date_de_creation`, `couleurs`, `pays_origine`, `tirage`, `dimensions`, `etat`, `certification`, `id_timbre_enchere`, `id_timbre_utilisateur`) VALUES
(1, 'Marianne', 1995, 'Orange, blanc', 'France', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 1, 1),
(2, 'Scott 157', 1950, 'Rouge, blanc', 'Canada', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 2, 1),
(3, 'Scott 302', 1955, 'Bleu, blanc', 'Canada', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 3, 1),
(4, 'Scott 352', 1934, 'Orcre, blanc', 'Canada', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 4, 1),
(5, 'American FA', 1940, 'Orange, blanc', 'USA', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 5, 1),
(6, 'Poste nationale', 1949, 'Orcre, blanc', 'Turquie', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 6, 1),
(7, 'Princesse', 1923, 'Orange, blanc, bleu, vert', 'Monaco', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 7, 1),
(8, 'Chine contemporaine', 1995, 'Orange, blanc, bleu, vert', 'Chine', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 8, 1),
(9, 'Napoléon 3', 1870, 'Vert, blanc', 'France', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 9, 1),
(10, 'Napoléon 3', 1870, 'Orange, blanc', 'France', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 10, 1),
(11, 'Nasser', 1956, 'Vert, blanc', 'Égypte', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 11, 1),
(12, 'Ghandi', 1969, 'Orange, blanc', 'Inde', '500 exemplaires', '24 mm x 20 mm', 'Bonne', 'certifié', 12, 1);
-- --------------------------------------------------------

--
-- Structure de la table `UTILISATEUR`
--

CREATE TABLE `UTILISATEUR` (
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  `utilisateur_prenom` varchar(255) NOT NULL,
  `utilisateur_nom` varchar(255) NOT NULL,
  `utilisateur_profil` varchar(255) DEFAULT 'utilisateur',
  `utilisateur_courriel` varchar(255) NOT NULL,
  `utilisateur_mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `UTILISATEUR`
--

INSERT INTO `UTILISATEUR` (`utilisateur_id`, `utilisateur_prenom`, `utilisateur_nom`, `utilisateur_profil`, `utilisateur_courriel`, `utilisateur_mdp`) VALUES
(1, 'Sébastien', 'Gedeon', 'administrateur', 's.gedeon@hotmail.fr', '3627909a29c31381a071ec27f7c9ca97726182aed29a7ddd2e54353322cfb30abb9e3a6df2ac2c20fe23436311d678564d0c8d305930575f60e2d3d048184d79');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ENCHERE`
--
ALTER TABLE `ENCHERE`
  ADD PRIMARY KEY (`id_enchere`);

--
-- Index pour la table `IMAGES`
--
ALTER TABLE `IMAGES`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `id_timbre_image` (`id_timbre_image`);

--
-- Index pour la table `PARTICIPE`
--
ALTER TABLE `PARTICIPE`
  ADD PRIMARY KEY (`id_enchere_mise`,`utilisateur_id_mise`),
  ADD KEY `utilisateur_id_mise` (`utilisateur_id_mise`);

--
-- Index pour la table `TIMBRE`
--
ALTER TABLE `TIMBRE`
  ADD PRIMARY KEY (`id_timbre`),
  ADD KEY `id_timbre_utilisateur` (`id_timbre_utilisateur`),
  ADD KEY `id_timbre_enchere` (`id_timbre_enchere`);

--
-- Index pour la table `UTILISATEUR`
--
ALTER TABLE `UTILISATEUR`
  ADD PRIMARY KEY (`utilisateur_id`),
  ADD UNIQUE KEY `utilisateur_courriel` (`utilisateur_courriel`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ENCHERE`
--
ALTER TABLE `ENCHERE`
  MODIFY `id_enchere` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `IMAGES`
--
ALTER TABLE `IMAGES`
  MODIFY `id_image` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `TIMBRE`
--
ALTER TABLE `TIMBRE`
  MODIFY `id_timbre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `UTILISATEUR`
--
ALTER TABLE `UTILISATEUR`
  MODIFY `utilisateur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `IMAGES`
--
ALTER TABLE `IMAGES`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`id_timbre_image`) REFERENCES `TIMBRE` (`id_timbre`) ON DELETE CASCADE;

--
-- Contraintes pour la table `PARTICIPE`
--
ALTER TABLE `PARTICIPE`
  ADD CONSTRAINT `participe_ibfk_1` FOREIGN KEY (`utilisateur_id_mise`) REFERENCES `UTILISATEUR` (`utilisateur_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participe_ibfk_2` FOREIGN KEY (`id_enchere_mise`) REFERENCES `ENCHERE` (`id_enchere`) ON DELETE CASCADE;

--
-- Contraintes pour la table `TIMBRE`
--
ALTER TABLE `TIMBRE`
  ADD CONSTRAINT `timbre_ibfk_1` FOREIGN KEY (`id_timbre_utilisateur`) REFERENCES `UTILISATEUR` (`utilisateur_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timbre_ibfk_2` FOREIGN KEY (`id_timbre_enchere`) REFERENCES `ENCHERE` (`id_enchere`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
