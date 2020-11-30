DROP DATABASE `borsa`;
CREATE DATABASE  IF NOT EXISTS `borsa` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `borsa`;
-- MySQL dump 10.13  Distrib 5.7.32, for Linux (x86_64)
--
-- Host: localhost    Database: borsa
-- ------------------------------------------------------
-- Server version	5.7.32-0ubuntu0.18.04.1

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
-- Table structure for table `Ajuda`
--

DROP TABLE IF EXISTS `Ajuda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ajuda` (
  `idAjuda` int(11) NOT NULL,
  `missatge` text NOT NULL,
  PRIMARY KEY (`idAjuda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Alumne_has_EstatLaboral`
--

DROP TABLE IF EXISTS `Alumne_has_EstatLaboral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Alumne_has_EstatLaboral` (
  `Alumnes_idAlumne` int(11) NOT NULL,
  `EstatLaboral_idEstatLaboral` int(11) NOT NULL,
  PRIMARY KEY (`Alumnes_idAlumne`,`EstatLaboral_idEstatLaboral`),
  KEY `fk_Alumnes_has_EstatLaboral_EstatLaboral1_idx` (`EstatLaboral_idEstatLaboral`),
  KEY `fk_Alumnes_has_EstatLaboral_Alumnes1_idx` (`Alumnes_idAlumne`),
  CONSTRAINT `fk_Alumnes_has_EstatLaboral_Alumnes1` FOREIGN KEY (`Alumnes_idAlumne`) REFERENCES `Alumnes` (`idAlumne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Alumnes_has_EstatLaboral_EstatLaboral1` FOREIGN KEY (`EstatLaboral_idEstatLaboral`) REFERENCES `EstatLaboral` (`idEstatLaboral`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Alumne_has_Estudis`
--

DROP TABLE IF EXISTS `Alumne_has_Estudis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Alumne_has_Estudis` (
  `Alumnes_idAlumne` int(11) NOT NULL,
  `Estudis_codi` char(10) NOT NULL,
  `any` int(11) DEFAULT NULL,
  `nota` int(11) DEFAULT NULL,
  PRIMARY KEY (`Alumnes_idAlumne`,`Estudis_codi`),
  KEY `fk_Alumnes_has_Estudis_Alumnes1_idx` (`Alumnes_idAlumne`),
  KEY `fk_Alumne_has_Estudis_Estudis1` (`Estudis_codi`),
  CONSTRAINT `fk_Alumne_has_Estudis_Estudis1` FOREIGN KEY (`Estudis_codi`) REFERENCES `Estudis` (`codi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Alumnes_has_Estudis_Alumnes1` FOREIGN KEY (`Alumnes_idAlumne`) REFERENCES `Alumnes` (`idAlumne`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Alumne_has_Idiomes`
--

DROP TABLE IF EXISTS `Alumne_has_Idiomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Alumne_has_Idiomes` (
  `Alumne_idAlumne` int(11) NOT NULL,
  `Idiomes_idIdiomes` int(11) NOT NULL,
  `NivellsIdioma_idNivellIdioma` int(11) NOT NULL,
  PRIMARY KEY (`Alumne_idAlumne`,`Idiomes_idIdiomes`),
  KEY `fk_Alumne_has_Idiomes_Idiomes1_idx` (`Idiomes_idIdiomes`),
  KEY `fk_Alumne_has_Idiomes_Alumne1_idx` (`Alumne_idAlumne`),
  KEY `fk_Alumne_has_Idiomes_NIvellsIdioma1_idx` (`NivellsIdioma_idNivellIdioma`),
  CONSTRAINT `fk_Alumne_has_Idiomes_Alumne1` FOREIGN KEY (`Alumne_idAlumne`) REFERENCES `Alumnes` (`idAlumne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Alumne_has_Idiomes_Idiomes1` FOREIGN KEY (`Idiomes_idIdiomes`) REFERENCES `Idiomes` (`idIdioma`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Alumne_has_Idiomes_NIvellsIdioma1` FOREIGN KEY (`NivellsIdioma_idNivellIdioma`) REFERENCES `NivellsIdioma` (`idNivellIdioma`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Alumnes`
--

DROP TABLE IF EXISTS `Alumnes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Alumnes` (
  `idAlumne` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  `llinatges` varchar(45) NOT NULL,
  `adreca` varchar(100) DEFAULT NULL,
  `codiPostal` char(5) DEFAULT NULL,
  `localitat` varchar(45) DEFAULT NULL,
  `provincia` varchar(45) DEFAULT NULL,
  `telefon` char(9) DEFAULT NULL,
  `email` varchar(75) NOT NULL,
  `actiu` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(150) DEFAULT NULL,
  `descripcio` text,
  `validat` tinyint(1) DEFAULT '0',
  `profValidat` int(11) DEFAULT NULL,
  `estudisAlta` char(7) DEFAULT NULL,
  `guardar` char(2) NOT NULL DEFAULT 'No',
  `cedir` char(2) NOT NULL DEFAULT 'No',
  `dataAlta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idAlumne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Alumnes_AFTER_INSERT` AFTER INSERT ON `Alumnes` FOR EACH ROW
BEGIN
   INSERT INTO Usuaris
   (tipusUsuari,nomUsuari,contrasenya,idEntitat)
   VALUES
   (30,NEW.email,substring(md5(rand()),-8), NEW.idAlumne);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger Alumnes_AFTER_UPDATE
    after update
    on Alumnes
    for each row
BEGIN
    if NEW.email <> OLD.email THEN
        update borsa.Usuaris u set u.nomUsuari=NEW.email where u.idEntitat = New.idAlumne and u.tipusUsuari=30;
    end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger Alumnes_AFTER_DELETE
    after delete
    on Alumnes
    for each row
BEGIN
DECLARE id INT;
 set id=(select idUsuari from borsa.Usuaris u where u.idEntitat=OLD.idAlumne and u.tipusUsuari=30);
 delete from borsa.Usuaris_has_Rols where usuaris_idUsuari=id;
 delete from borsa.Usuaris where idUsuari=id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Configuracio`
--

DROP TABLE IF EXISTS `Configuracio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Configuracio` (
  `inici` date NOT NULL,
  `final` date NOT NULL,
  `idConf` int(11) NOT NULL,
  PRIMARY KEY (`idConf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Contactes`
--

DROP TABLE IF EXISTS `Contactes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contactes` (
  `idContacte` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  `llinatges` varchar(45) NOT NULL,
  `telefon` char(9) DEFAULT NULL,
  `email` varchar(75) NOT NULL,
  `carrec` varchar(75) DEFAULT NULL,
  `Empreses_idEmpresa` int(11) NOT NULL,
  PRIMARY KEY (`idContacte`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_Contactes_Empreses1_idx` (`Empreses_idEmpresa`),
  CONSTRAINT `fk_Contactes_Empreses1` FOREIGN KEY (`Empreses_idEmpresa`) REFERENCES `Empreses` (`idEmpresa`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Destinataris`
--

DROP TABLE IF EXISTS `Destinataris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Destinataris` (
  `idEmail` int(11) NOT NULL,
  `adreca` varchar(100) NOT NULL,
  PRIMARY KEY (`idEmail`,`adreca`),
  CONSTRAINT `fk_Email` FOREIGN KEY (`idEmail`) REFERENCES `Emails` (`idEmail`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Emails`
--

DROP TABLE IF EXISTS `Emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Emails` (
  `idEmail` int(11) NOT NULL AUTO_INCREMENT,
  `textEmail` text NOT NULL,
  PRIMARY KEY (`idEmail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Empreses`
--

DROP TABLE IF EXISTS `Empreses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Empreses` (
  `idEmpresa` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  `descripcio` text,
  `adreca` varchar(100) DEFAULT NULL,
  `codiPostal` char(5) DEFAULT NULL,
  `localitat` varchar(45) DEFAULT NULL,
  `provincia` varchar(45) DEFAULT NULL,
  `telefon` char(9) DEFAULT NULL,
  `email` varchar(75) NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '0',
  `validada` tinyint(1) NOT NULL DEFAULT '0',
  `dataAlta` date DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `familia` char(3) DEFAULT NULL,
  `rebuig` varchar(250) DEFAULT NULL,
  `guardar` char(2) NOT NULL DEFAULT 'No',
  `cedir` char(2) NOT NULL DEFAULT 'No',
  PRIMARY KEY (`idEmpresa`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Empreses_BEFORE_INSERT` BEFORE INSERT ON `Empreses` FOR EACH ROW
BEGIN
 SET New.dataAlta=curdate();
 if NEW.validada = false THEN
	set NEW.activa=false;
 end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Empreses_AFTER_INSERT` AFTER INSERT ON `Empreses` FOR EACH ROW
BEGIN
   INSERT INTO Usuaris
   (tipusUsuari,nomUsuari,contrasenya,idEntitat)
   VALUES
   (20,NEW.email,substring(md5(rand()),-8),NEW.idEmpresa);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Empreses_AFTER_UPDATE` AFTER UPDATE ON `Empreses` FOR EACH ROW
BEGIN
if NEW.email <> OLD.email THEN
update Usuaris set nomUsuari=NEW.email where nomUsuari=OLD.email;
end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Empreses_AFTER_DELETE` AFTER DELETE ON `Empreses` FOR EACH ROW
BEGIN
DECLARE id INT;
 set id=(select idUsuari from Usuaris where nomUsuari=OLD.email);
 delete from Usuaris_has_Rols where usuaris_idUsuari=id;
 delete from Usuaris where idUsuari=id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EstatLaboral`
--

DROP TABLE IF EXISTS `EstatLaboral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EstatLaboral` (
  `idEstatLaboral` int(11) NOT NULL AUTO_INCREMENT,
  `nomEstatLaboral` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idEstatLaboral`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Estudis`
--

DROP TABLE IF EXISTS `Estudis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Estudis` (
  `codi` char(10) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `familia` char(3) NOT NULL,
  PRIMARY KEY (`codi`),
  KEY `fk_Estudis_familiesProfessionals1_idx` (`familia`),
  CONSTRAINT `fk_Estudis_familiesProfessionals1` FOREIGN KEY (`familia`) REFERENCES `familiesProfessionals` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Estudis_has_Responsables`
--

DROP TABLE IF EXISTS `Estudis_has_Responsables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Estudis_has_Responsables` (
  `Estudis_codi` char(10) NOT NULL,
  `Professors_idProfessor` int(11) NOT NULL,
  PRIMARY KEY (`Estudis_codi`,`Professors_idProfessor`),
  KEY `fk_Estudis_has_Professors_Professors1_idx` (`Professors_idProfessor`),
  KEY `fk_Estudis_has_Professors_Estudis1_idx` (`Estudis_codi`),
  CONSTRAINT `fk_Estudis_has_Professors_Professors1` FOREIGN KEY (`Professors_idProfessor`) REFERENCES `Professors` (`idProfessor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Estudis_has_Responsables_Estudis1` FOREIGN KEY (`Estudis_codi`) REFERENCES `Estudis` (`codi`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Idiomes`
--

DROP TABLE IF EXISTS `Idiomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Idiomes` (
  `idIdioma` int(11) NOT NULL AUTO_INCREMENT,
  `idioma` varchar(45) NOT NULL,
  PRIMARY KEY (`idIdioma`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Illes`
--

DROP TABLE IF EXISTS `Illes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Illes` (
  `idIlla` varchar(3) NOT NULL,
  `nomIlla` varchar(45) NOT NULL,
  PRIMARY KEY (`idIlla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Localitats`
--

DROP TABLE IF EXISTS `Localitats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Localitats` (
  `idLocalitat` varchar(9) NOT NULL,
  `nomLocalitat` varchar(100) NOT NULL,
  `idIlla` varchar(3) NOT NULL,
  PRIMARY KEY (`idLocalitat`),
  KEY `fk_Localitats_Illes1_idx` (`idIlla`),
  CONSTRAINT `fk_Localitats_Illes1` FOREIGN KEY (`idIlla`) REFERENCES `Illes` (`idIlla`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `NivellsIdioma`
--

DROP TABLE IF EXISTS `NivellsIdioma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NivellsIdioma` (
  `idNivellIdioma` int(11) NOT NULL AUTO_INCREMENT,
  `nivell` varchar(45) NOT NULL,
  PRIMARY KEY (`idNivellIdioma`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Ofertes`
--

DROP TABLE IF EXISTS `Ofertes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ofertes` (
  `idOferta` int(11) NOT NULL AUTO_INCREMENT,
  `titol` varchar(100) NOT NULL,
  `descripcio` text NOT NULL,
  `dataPublicacio` date DEFAULT NULL,
  `dataFinal` date DEFAULT NULL,
  `validada` tinyint(1) NOT NULL DEFAULT '0',
  `professorValidada` int(11) DEFAULT NULL,
  `Empreses_idEmpresa` int(11) NOT NULL,
  `tipusContracte` varchar(45) DEFAULT NULL,
  `horari` varchar(60) DEFAULT NULL,
  `localitat` varchar(60) DEFAULT NULL,
  `rebuig` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idOferta`),
  KEY `fk_Ofertes_Empreses1_idx` (`Empreses_idEmpresa`),
  KEY `fk_Ofertes_Professors1_idx` (`professorValidada`),
  CONSTRAINT `fk_Ofertes_Empreses1` FOREIGN KEY (`Empreses_idEmpresa`) REFERENCES `Empreses` (`idEmpresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ofertes_Professors1` FOREIGN KEY (`professorValidada`) REFERENCES `Professors` (`idProfessor`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Ofertes_enviada_Alumnes`
--

DROP TABLE IF EXISTS `Ofertes_enviada_Alumnes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ofertes_enviada_Alumnes` (
  `Ofertes_idOferta` int(11) NOT NULL,
  `Alumnes_idAlumne` int(11) NOT NULL,
  PRIMARY KEY (`Ofertes_idOferta`,`Alumnes_idAlumne`),
  KEY `fk_Ofertes_has_Alumnes_Alumnes1_idx` (`Alumnes_idAlumne`),
  KEY `fk_Ofertes_has_Alumnes_Ofertes1_idx` (`Ofertes_idOferta`),
  CONSTRAINT `fk_Ofertes_has_Alumnes_Alumnes1` FOREIGN KEY (`Alumnes_idAlumne`) REFERENCES `Alumnes` (`idAlumne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ofertes_has_Alumnes_Ofertes1` FOREIGN KEY (`Ofertes_idOferta`) REFERENCES `Ofertes` (`idOferta`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Ofertes_has_Contactes`
--

DROP TABLE IF EXISTS `Ofertes_has_Contactes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ofertes_has_Contactes` (
  `Ofertes_idOferta` int(11) NOT NULL,
  `Contactes_idContacte` int(11) NOT NULL,
  PRIMARY KEY (`Ofertes_idOferta`,`Contactes_idContacte`),
  KEY `fk_Ofertes_has_Contactes_Contactes1_idx` (`Contactes_idContacte`),
  KEY `fk_Ofertes_has_Contactes_Ofertes1_idx` (`Ofertes_idOferta`),
  CONSTRAINT `fk_Ofertes_has_Contactes_Contactes1` FOREIGN KEY (`Contactes_idContacte`) REFERENCES `Contactes` (`idContacte`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ofertes_has_Contactes_Ofertes1` FOREIGN KEY (`Ofertes_idOferta`) REFERENCES `Ofertes` (`idOferta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Ofertes_has_EstatLaboral`
--

DROP TABLE IF EXISTS `Ofertes_has_EstatLaboral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ofertes_has_EstatLaboral` (
  `Ofertes_idOferta` int(11) NOT NULL,
  `EstatLaboral_idEstatLaboral` int(11) NOT NULL,
  PRIMARY KEY (`Ofertes_idOferta`,`EstatLaboral_idEstatLaboral`),
  KEY `fk_Ofertes_has_EstatLaboral_EstatLaboral1_idx` (`EstatLaboral_idEstatLaboral`),
  KEY `fk_Ofertes_has_EstatLaboral_Ofertes1_idx` (`Ofertes_idOferta`),
  CONSTRAINT `fk_Ofertes_has_EstatLaboral_EstatLaboral1` FOREIGN KEY (`EstatLaboral_idEstatLaboral`) REFERENCES `EstatLaboral` (`idEstatLaboral`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ofertes_has_EstatLaboral_Ofertes1` FOREIGN KEY (`Ofertes_idOferta`) REFERENCES `Ofertes` (`idOferta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Ofertes_has_Estudis`
--

DROP TABLE IF EXISTS `Ofertes_has_Estudis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ofertes_has_Estudis` (
  `Ofertes_idOferta` int(11) NOT NULL,
  `Estudis_codi` char(10) NOT NULL,
  `any` int(11) DEFAULT NULL,
  `nota` int(11) DEFAULT NULL,
  PRIMARY KEY (`Ofertes_idOferta`,`Estudis_codi`),
  KEY `fk_Ofertes_has_Estudis_Ofertes1_idx` (`Ofertes_idOferta`),
  KEY `fk_Ofertes_has_Estudis_Estudis1` (`Estudis_codi`),
  CONSTRAINT `fk_Ofertes_has_Estudis_Estudis1` FOREIGN KEY (`Estudis_codi`) REFERENCES `Estudis` (`codi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ofertes_has_Estudis_Ofertes1` FOREIGN KEY (`Ofertes_idOferta`) REFERENCES `Ofertes` (`idOferta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Ofertes_has_Idiomes`
--

DROP TABLE IF EXISTS `Ofertes_has_Idiomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ofertes_has_Idiomes` (
  `Ofertes_idOferta` int(11) NOT NULL,
  `Idiomes_idIdioma` int(11) NOT NULL,
  `NivellsIdioma_idNivellIdioma` int(11) NOT NULL,
  PRIMARY KEY (`Ofertes_idOferta`,`Idiomes_idIdioma`,`NivellsIdioma_idNivellIdioma`),
  KEY `fk_Ofertes_has_Idiomes_Idiomes1_idx` (`Idiomes_idIdioma`),
  KEY `fk_Ofertes_has_Idiomes_Ofertes1_idx` (`Ofertes_idOferta`),
  KEY `fk_Ofertes_has_Idiomes_NivellsIdioma1_idx` (`NivellsIdioma_idNivellIdioma`),
  CONSTRAINT `fk_Ofertes_has_Idiomes_Idiomes1` FOREIGN KEY (`Idiomes_idIdioma`) REFERENCES `Idiomes` (`idIdioma`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ofertes_has_Idiomes_NivellsIdioma1` FOREIGN KEY (`NivellsIdioma_idNivellIdioma`) REFERENCES `NivellsIdioma` (`idNivellIdioma`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ofertes_has_Idiomes_Ofertes1` FOREIGN KEY (`Ofertes_idOferta`) REFERENCES `Ofertes` (`idOferta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Professors`
--

DROP TABLE IF EXISTS `Professors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Professors` (
  `idProfessor` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  `llinatges` varchar(45) NOT NULL,
  `telefon` char(9) DEFAULT NULL,
  `email` varchar(75) NOT NULL,
  `actiu` tinyint(1) NOT NULL DEFAULT '0',
  `validat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idProfessor`),
  UNIQUE KEY `Email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Professors_BEFORE_INSERT` BEFORE INSERT ON `Professors` FOR EACH ROW
BEGIN
if NEW.validat = false THEN
	set NEW.actiu=false;
 end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Professors_AFTER_INSERT` AFTER INSERT ON `Professors` FOR EACH ROW
BEGIN
   INSERT INTO Usuaris
   (tipusUsuari,nomUsuari,contrasenya,idEntitat)
   VALUES
   (10,NEW.email,substring(md5(rand()),-8),NEW.idProfessor);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Professors_AFTER_UPDATE` AFTER UPDATE ON `Professors` FOR EACH ROW
BEGIN
if NEW.email <=> OLD.email THEN
update Usuaris set nomUsuari=NEW.email where nomUsuari=OLD.email;
end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Professors_AFTER_DELETE` AFTER DELETE ON `Professors` FOR EACH ROW
BEGIN
DECLARE id INT;
DECLARE tipus INT;
 set id=(select idUsuari from Usuaris where idEntitat=OLD.idProfessor and tipusUsuari=20);
 delete from Usuaris_has_Rols where usuaris_idUsuari=id;
 delete from Usuaris where idUsuari=id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Rols`
--

DROP TABLE IF EXISTS `Rols`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Rols` (
  `idRol` int(11) NOT NULL,
  `nomRol` varchar(45) NOT NULL,
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TipusUsuaris`
--

DROP TABLE IF EXISTS `TipusUsuaris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TipusUsuaris` (
  `idTipusUsuaris` int(11) NOT NULL,
  `nomTipusUsuari` varchar(45) NOT NULL,
  PRIMARY KEY (`idTipusUsuaris`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Tokens`
--

DROP TABLE IF EXISTS `Tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tokens` (
  `idUsuari` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`token`),
  KEY `fk_idUsuari_idx` (`idUsuari`),
  CONSTRAINT `fk_idUsuari` FOREIGN KEY (`idUsuari`) REFERENCES `Usuaris` (`idUsuari`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Usuaris`
--

DROP TABLE IF EXISTS `Usuaris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Usuaris` (
  `idUsuari` int(11) NOT NULL AUTO_INCREMENT,
  `tipusUsuari` int(11) NOT NULL,
  `nomUsuari` varchar(75) NOT NULL,
  `contrasenya` varchar(75) NOT NULL,
  `idEntitat` int(11) NOT NULL,
  PRIMARY KEY (`idUsuari`),
  UNIQUE KEY `nomUsuari_UNIQUE` (`nomUsuari`),
  KEY `fk_usuaris_TipusUsuaris1_idx` (`tipusUsuari`),
  CONSTRAINT `fk_usuaris_TipusUsuaris1` FOREIGN KEY (`tipusUsuari`) REFERENCES `TipusUsuaris` (`idTipusUsuaris`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`usuaris_AFTER_INSERT` AFTER INSERT ON `Usuaris` FOR EACH ROW
BEGIN
INSERT INTO Usuaris_has_Rols
   (usuaris_idusuari, rols_idrol)
   VALUES
   (New.idusuari,New.tipusUsuari);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Usuaris_has_Rols`
--

DROP TABLE IF EXISTS `Usuaris_has_Rols`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Usuaris_has_Rols` (
  `Usuaris_idUsuari` int(11) NOT NULL,
  `Rols_idRol` int(11) NOT NULL,
  PRIMARY KEY (`Usuaris_idUsuari`,`Rols_idRol`),
  KEY `fk_Usuaris_has_Rols_Rols1_idx` (`Rols_idRol`),
  KEY `fk_Usuaris_has_Rols_Usuaris1_idx` (`Usuaris_idUsuari`),
  CONSTRAINT `fk_Usuaris_has_Rols_Rols1` FOREIGN KEY (`Rols_idRol`) REFERENCES `Rols` (`idRol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Usuaris_has_Rols_Usuaris1` FOREIGN KEY (`Usuaris_idUsuari`) REFERENCES `Usuaris` (`idUsuari`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `familiesProfessionals`
--

DROP TABLE IF EXISTS `familiesProfessionals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `familiesProfessionals` (
  `id` char(3) NOT NULL,
  `nom` varchar(75) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'borsa'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-11-30 12:34:36
