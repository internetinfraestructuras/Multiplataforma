<?php
/**
 * Created by PhpStorm.
 * User: telefonia
 * Date: 24/07/2018
 * Time: 10:21
 */

DROP TABLE IF EXISTS `paquetesdestino`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paquetesdestino` (
  `id_paquetedestino` int(11) NOT NULL AUTO_INCREMENT,
  `cif_super` varchar(20) NOT NULL DEFAULT '',
  `nombrepaquete` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id_paquetedestino`),
  CONSTRAINT `paquetesdestinosuperusuario_ibfk_1` FOREIGN KEY (`cif_super`) REFERENCES `superusuarios` (`cif_superuser`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `paquetesdestino_tarifas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paquetesdestino_tarifas` (
  `paquetedestino_id` int(11) NOT NULL,
  `grupo` varchar(80) DEFAULT NULL,
  `descripcion` varchar(80) DEFAULT NULL,
  `prefijo` varchar(20) NOT NULL DEFAULT '',
  `coste` double DEFAULT NULL,
  PRIMARY KEY (`prefijo`,`paquetedestino_id`),
  CONSTRAINT `paquetedestino_tar_ibfk_2` FOREIGN KEY (`paquetedestino_id`) REFERENCES `paquetesdestino` (`id_paquetedestino`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `numericosdisponiblesprovincias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `numericosdisponiblesprovincias` (
  `id_provincia` int(11) NOT NULL AUTO_INCREMENT,
  `provincia` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_provincia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `numericosdisponibles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `numericosdisponibles` (
  `provincia_id` int(11) NOT NULL,
  `numero` varchar(9) DEFAULT NULL,
  `estado` varchar(15) DEFAULT "LIBRE",
  PRIMARY KEY (`numero`),
  CONSTRAINT `numdispon_tar_ibfk_2` FOREIGN KEY (`provincia_id`) REFERENCES `numericosdisponiblesprovincias` (`id_provincia`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `numericosportas`;
CREATE TABLE `numericosportas` (
  `id_numero` int(11) NOT NULL AUTO_INCREMENT,
  `cif_super` varchar(20) NOT NULL DEFAULT '',
  `numero` varchar(9) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL, /** lanzada, portado, asignado ,libre */
  `fechalanzamiento` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fechaportabilidad` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_numero`),
  CONSTRAINT `numericosportas_ibfk_1` FOREIGN KEY (`cif_super`) REFERENCES `superusuarios` (`cif_superuser`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;