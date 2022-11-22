-- MySQL dump 10.13  Distrib 5.7.34, for osx11.0 (x86_64)
--
-- Host: localhost    Database: ENCHERES
-- ------------------------------------------------------
-- Server version	5.7.34

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ENCHERE`
--

DROP TABLE IF EXISTS `ENCHERE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ENCHERE` (
  `id_enchere` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `prix_plancher` smallint(8) unsigned NOT NULL,
  `coup_de_coeur_du_lord` varchar(42) DEFAULT NULL,
  PRIMARY KEY (`id_enchere`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ENCHERE`
--

LOCK TABLES `ENCHERE` WRITE;
/*!40000 ALTER TABLE `ENCHERE` DISABLE KEYS */;
INSERT INTO `ENCHERE` VALUES (1,'2022-01-23','2022-02-23',200,'non'),(2,'2022-05-01','2022-05-25',500,'non'),(3,'2022-05-03','2022-05-28',500,'non'),(4,'2022-05-05','2022-06-01',500,'non'),(5,'2022-04-05','2022-05-01',500,'non'),(6,'2022-05-08','2022-06-05',500,'non'),(7,'2022-05-09','2022-06-09',500,'non'),(8,'2022-05-09','2022-06-17',500,'non'),(9,'2022-05-09','2022-06-19',500,'oui'),(10,'2022-05-10','2022-06-25',500,'oui'),(11,'2022-05-10','2022-06-26',500,'oui'),(12,'2022-05-10','2022-06-18',500,'oui'),(16,'2022-05-21','2022-05-22',5,'non');
/*!40000 ALTER TABLE `ENCHERE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `IMAGES`
--

DROP TABLE IF EXISTS `IMAGES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `IMAGES` (
  `id_image` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `image_principale` tinyint(1) DEFAULT NULL,
  `id_timbre_image` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_image`),
  KEY `id_timbre_image` (`id_timbre_image`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`id_timbre_image`) REFERENCES `TIMBRE` (`id_timbre`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `IMAGES`
--

LOCK TABLES `IMAGES` WRITE;
/*!40000 ALTER TABLE `IMAGES` DISABLE KEYS */;
INSERT INTO `IMAGES` VALUES (1,'./Images/timbre-01.jpg','Marianne',1,1),(2,'./Images/timbre-02.jpeg','Scott 157',1,2),(3,'./Images/timbre-03.jpeg','Scott 302',2,2),(4,'./Images/timbre-04.jpeg','Scott 352',3,2),(5,'./Images/timbre-03.jpeg','Scott 302',1,3),(6,'./Images/timbre-04.jpeg','Scott 352',1,4),(7,'./Images/timbre-05.jpeg','American FA',1,5),(8,'./Images/timbre-06.jpeg','Poste nationale',1,6),(9,'./Images/timbre-07.jpeg','Princesse',1,7),(10,'./Images/timbre-08.jpeg','Chine contemporaine',1,8),(11,'./Images/timbre-09.jpeg','Napoléon 3',1,9),(12,'./Images/timbre-10.jpeg','Napoléon 3',1,10),(13,'./Images/timbre-11.jpeg','Nasser',1,11),(14,'./Images/timbre-01.jpg','Ghandi',1,12),(16,'./Images/timbre-01.jpg','asfsadfas',1,16);
/*!40000 ALTER TABLE `IMAGES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PARTICIPE`
--

DROP TABLE IF EXISTS `PARTICIPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PARTICIPE` (
  `id_enchere_mise` int(10) unsigned NOT NULL,
  `utilisateur_id_mise` int(10) unsigned NOT NULL,
  `mise` smallint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_enchere_mise`,`utilisateur_id_mise`),
  KEY `utilisateur_id_mise` (`utilisateur_id_mise`),
  CONSTRAINT `participe_ibfk_1` FOREIGN KEY (`utilisateur_id_mise`) REFERENCES `UTILISATEUR` (`utilisateur_id`) ON DELETE CASCADE,
  CONSTRAINT `participe_ibfk_2` FOREIGN KEY (`id_enchere_mise`) REFERENCES `ENCHERE` (`id_enchere`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PARTICIPE`
--

LOCK TABLES `PARTICIPE` WRITE;
/*!40000 ALTER TABLE `PARTICIPE` DISABLE KEYS */;
INSERT INTO `PARTICIPE` VALUES (1,1,200),(2,1,10010),(3,1,8800),(4,1,720),(5,1,890),(6,1,500),(7,1,800),(8,1,7700),(9,1,900),(10,1,90),(11,1,500),(12,1,500);
/*!40000 ALTER TABLE `PARTICIPE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TIMBRE`
--

DROP TABLE IF EXISTS `TIMBRE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TIMBRE` (
  `id_timbre` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `date_de_creation` int(4) unsigned DEFAULT NULL,
  `couleurs` varchar(42) DEFAULT NULL,
  `pays_origine` varchar(255) NOT NULL,
  `tirage` varchar(42) DEFAULT NULL,
  `dimensions` varchar(255) NOT NULL,
  `etat` varchar(42) DEFAULT NULL,
  `certification` varchar(42) DEFAULT NULL,
  `id_timbre_enchere` int(10) unsigned DEFAULT NULL,
  `id_timbre_utilisateur` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_timbre`),
  KEY `id_timbre_utilisateur` (`id_timbre_utilisateur`),
  KEY `id_timbre_enchere` (`id_timbre_enchere`),
  CONSTRAINT `timbre_ibfk_1` FOREIGN KEY (`id_timbre_utilisateur`) REFERENCES `UTILISATEUR` (`utilisateur_id`) ON DELETE CASCADE,
  CONSTRAINT `timbre_ibfk_2` FOREIGN KEY (`id_timbre_enchere`) REFERENCES `ENCHERE` (`id_enchere`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TIMBRE`
--

LOCK TABLES `TIMBRE` WRITE;
/*!40000 ALTER TABLE `TIMBRE` DISABLE KEYS */;
INSERT INTO `TIMBRE` VALUES (1,'Marianne',1995,'Orange, blanc','France','500 exemplaires','24 mm x 20 mm','Bonne','certifié',1,1),(2,'Scott 157',1950,'Rouge, blanc','Canada','500 exemplaires','24 mm x 20 mm','Bonne','certifié',2,1),(3,'Scott 302',1955,'Bleu, blanc','Canada','500 exemplaires','24 mm x 20 mm','Bonne','certifié',3,1),(4,'Scott 352',1934,'Orcre, blanc','Canada','500 exemplaires','24 mm x 20 mm','Bonne','certifié',4,1),(5,'American FA',1940,'Orange, blanc','USA','500 exemplaires','24 mm x 20 mm','Bonne','certifié',5,1),(6,'Poste nationale',1949,'Orcre, blanc','Turquie','500 exemplaires','24 mm x 20 mm','Bonne','certifié',6,1),(7,'Princesse',1923,'Orange, blanc, bleu, vert','Monaco','500 exemplaires','24 mm x 20 mm','Bonne','certifié',7,1),(8,'Chine contemporaine',1995,'Orange, blanc, bleu, vert','Chine','500 exemplaires','24 mm x 20 mm','Bonne','certifié',8,1),(9,'Napoléon 3',1870,'Vert, blanc','France','500 exemplaires','24 mm x 20 mm','Bonne','certifié',9,1),(10,'Napoléon 3',1870,'Orange, blanc','France','500 exemplaires','24 mm x 20 mm','Bonne','certifié',10,1),(11,'Nasser',1956,'Vert, blanc','Égypte','500 exemplaires','24 mm x 20 mm','Bonne','certifié',11,1),(12,'Ghandi',1969,'Orange, blanc','Inde','500 exemplaires','24 mm x 20 mm','Bonne','Certifié',12,1),(16,'sdafasdf',1872,'fasfsad','fsfsa','500','24 mm x 20 mm','Bonne','Certifié',16,1);
/*!40000 ALTER TABLE `TIMBRE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UTILISATEUR`
--

DROP TABLE IF EXISTS `UTILISATEUR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UTILISATEUR` (
  `utilisateur_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `utilisateur_prenom` varchar(255) NOT NULL,
  `utilisateur_nom` varchar(255) NOT NULL,
  `utilisateur_profil` varchar(255) DEFAULT 'utilisateur',
  `utilisateur_courriel` varchar(255) NOT NULL,
  `utilisateur_mdp` varchar(255) NOT NULL,
  PRIMARY KEY (`utilisateur_id`),
  UNIQUE KEY `utilisateur_courriel` (`utilisateur_courriel`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UTILISATEUR`
--

LOCK TABLES `UTILISATEUR` WRITE;
/*!40000 ALTER TABLE `UTILISATEUR` DISABLE KEYS */;
INSERT INTO `UTILISATEUR` VALUES (1,'Sébastien','Gedeon','administrateur','s.gedeon@hotmail.fr','3627909a29c31381a071ec27f7c9ca97726182aed29a7ddd2e54353322cfb30abb9e3a6df2ac2c20fe23436311d678564d0c8d305930575f60e2d3d048184d79');
/*!40000 ALTER TABLE `UTILISATEUR` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-22 13:46:10
