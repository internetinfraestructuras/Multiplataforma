-- MySQL dump 10.13  Distrib 5.5.39, for Win32 (x86)
--
-- Host: localhost    Database: conexionppoe
-- ------------------------------------------------------
-- Server version	5.5.39

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
-- Table structure for table `superusuarios`
--

DROP TABLE IF EXISTS `superusuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `superusuarios` (
  `cif_superuser` varchar(20) NOT NULL,
  `superusuario` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(20) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `prefijo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cif_superuser`),
  KEY `superusuario` (`superusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*
alter table superusuarios add column usuarioXabber varchar(50);
alter table superusuarios add column router varchar(15);
alter table superusuarios add column routerUsuario varchar(15);
alter table superusuarios add column routerPass varchar(30);

alter table superusuarios add column routerClienteUsuario varchar(15) default 'admin';
alter table superusuarios add column routerClientePassword varchar(30) default 'mmhermoso2015*';

alter table superusuarios add column linkGrafica varchar(300) default NULL;

*/
--
-- Dumping data for table `superusuarios`
--

LOCK TABLES `superusuarios` WRITE;
/*!40000 ALTER TABLE `superusuarios` DISABLE KEYS */;
INSERT INTO `superusuarios` VALUES ('11777907K','franciscogil','1234','paco.perez@gruporeq.com','FG_');
/*!40000 ALTER TABLE `superusuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-18 13:03:04
