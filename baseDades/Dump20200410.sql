CREATE DATABASE  IF NOT EXISTS `borsa` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `borsa`;
-- MySQL dump 10.13  Distrib 5.7.29, for Linux (x86_64)
--
-- Host: localhost    Database: borsa
-- ------------------------------------------------------
-- Server version	5.7.29-0ubuntu0.18.04.1

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
-- Dumping data for table `Ajuda`
--

LOCK TABLES `Ajuda` WRITE;
/*!40000 ALTER TABLE `Ajuda` DISABLE KEYS */;
INSERT INTO `Ajuda` VALUES (0,'<h3>Borsa de treball de l\'IES Pau Casesnoves</h3><p>Aquesta aplicació web permet gestionar la borsa de treball de l\'institut d\'educació secundària Pau Casesnoves. Està dirigida a tres sectors:</p><p><ul><li>Les empreses: poden apuntar-se a la borsa de treball i des d\'aquest moment podran fer ofertes de treball que arribaran als alumnes que compleixin els requisits demanats a l\'oferta.<br></li><li>Els alumnes: Quan es graduen es donen automàticament d\'alta el sistema, i si ells activen el seu compte podran ser seleccionats com a candidats per a les ofertes de treball.</li><li>Els professors: poden assignar-se uns determinats estudis i des d\'aquest moment hauran de validar les ofertes que publiquen les empreses abans de que arribin als alumnes.</li></ul>Per començar, pitgi a la barra de navegació sobre el col·lectiu al qual pertany, empresa, estudiant o professor.</p>');
/*!40000 ALTER TABLE `Ajuda` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `Alumne_has_EstatLaboral`
--

LOCK TABLES `Alumne_has_EstatLaboral` WRITE;
/*!40000 ALTER TABLE `Alumne_has_EstatLaboral` DISABLE KEYS */;
INSERT INTO `Alumne_has_EstatLaboral` VALUES (1,1),(1,3);
/*!40000 ALTER TABLE `Alumne_has_EstatLaboral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Alumne_has_Estudis`
--

DROP TABLE IF EXISTS `Alumne_has_Estudis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Alumne_has_Estudis` (
  `Alumnes_idAlumne` int(11) NOT NULL,
  `Estudis_codi` char(7) NOT NULL,
  `any` int(11) DEFAULT NULL,
  `nota` int(11) DEFAULT NULL,
  PRIMARY KEY (`Alumnes_idAlumne`,`Estudis_codi`),
  KEY `fk_Alumnes_has_Estudis_Estudis1_idx` (`Estudis_codi`),
  KEY `fk_Alumnes_has_Estudis_Alumnes1_idx` (`Alumnes_idAlumne`),
  CONSTRAINT `fk_Alumnes_has_Estudis_Alumnes1` FOREIGN KEY (`Alumnes_idAlumne`) REFERENCES `Alumnes` (`idAlumne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Alumnes_has_Estudis_Estudis1` FOREIGN KEY (`Estudis_codi`) REFERENCES `Estudis` (`codi`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Alumne_has_Estudis`
--

LOCK TABLES `Alumne_has_Estudis` WRITE;
/*!40000 ALTER TABLE `Alumne_has_Estudis` DISABLE KEYS */;
INSERT INTO `Alumne_has_Estudis` VALUES (1,'IFC32',2017,5),(1,'IFC33',2017,7),(2,'IFC31',2017,7),(3,'IFC32',2017,8),(3,'IFC33',2017,8);
/*!40000 ALTER TABLE `Alumne_has_Estudis` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `Alumne_has_Idiomes`
--

LOCK TABLES `Alumne_has_Idiomes` WRITE;
/*!40000 ALTER TABLE `Alumne_has_Idiomes` DISABLE KEYS */;
INSERT INTO `Alumne_has_Idiomes` VALUES (1,3,3),(1,1,4),(1,2,4),(1,4,4);
/*!40000 ALTER TABLE `Alumne_has_Idiomes` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`idAlumne`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Alumnes`
--

LOCK TABLES `Alumnes` WRITE;
/*!40000 ALTER TABLE `Alumnes` DISABLE KEYS */;
INSERT INTO `Alumnes` VALUES (1,'Rafel','Sastre','Carrer s\'olivera 12','07320','Selva','Illes Balears','666555444','rafel@iespaucasesnoves.cat',1,NULL,NULL,0,NULL,'IFC32'),(2,'Borja','Perez','Plaça Major 4','07514','Llucmajor','Iiles Balears','698523654','borja@iespaucasesnoves.cat',0,NULL,NULL,0,NULL,'IFC31'),(3,'Cristian','Martínez','Carrer Albada 32','07436','Can Picafort','Illes Balears','647854123','cristian@paucasesnovescifp.cat',0,NULL,NULL,0,NULL,'IFC33'),(8,'asdasdas','orytroity uoituy roityu ro',NULL,NULL,NULL,NULL,NULL,'2134@sdf.cde',0,NULL,NULL,0,NULL,'IFC33');
/*!40000 ALTER TABLE `Alumnes` ENABLE KEYS */;
UNLOCK TABLES;
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
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Alumnes_AFTER_UPDATE` AFTER UPDATE ON `borsa`.`Alumnes` FOR EACH ROW
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Alumnes_AFTER_DELETE` AFTER DELETE ON `Alumnes` FOR EACH ROW
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Contactes`
--

LOCK TABLES `Contactes` WRITE;
/*!40000 ALTER TABLE `Contactes` DISABLE KEYS */;
INSERT INTO `Contactes` VALUES (1,'Jo','Mateix','','jomateix@nissan.jp',NULL,2),(2,'Jo','Mateix','','jomateix@intel.es','RRHH',3),(3,'Miquel','Servera','','miquel@intel.es','RRHH',3);
/*!40000 ALTER TABLE `Contactes` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`idEmpresa`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Empreses`
--

LOCK TABLES `Empreses` WRITE;
/*!40000 ALTER TABLE `Empreses` DISABLE KEYS */;
INSERT INTO `Empreses` VALUES (1,'Sa Meva','<h1>Sa Meva</h1><p>Empresa dedicada a tota casta de projectes.</p>','Carrer nou, 10','07300','Inca','Balears','971456985','info@sameva.cat',0,0,'2019-08-11','www.sameva.cat',NULL,NULL),(2,'Nissan','<h1>Nissan</h1><p>Idò això, una altra empresa de cotxes.</p>','Plaça Major 6','07300','Inca','Balears','654785214','info@nissan.jp',0,0,'2019-08-11','www.nissan.jp',NULL,NULL),(3,'Intel','<h1>Intel</h1><p>Una gran companyia que fa coses petites.</p>','Avinguda No Sé Què, s/n','07300','Inca','Balears','','info@intel.es22',1,1,'2019-08-11','www.intel.com','IFC',''),(4,'Proves','<p>lakfj alsdfjalsd fjsdlfj lsdfjaljsldja fsk fjsdlf jalsdf jsdjf</p>','carrer desconegut 3','07957','Ariany','Illes Balears','698478523','info@poves.com',0,0,'2019-10-08','',NULL,NULL),(5,'lsdnsdlf','<p><br></p>','','','','','','asdfg@sdfsf.sdf',0,0,'2019-10-23','',NULL,NULL),(6,'Prova 27','<p>sañdflskdñ fsñdf ksñldkf sñdlkf sñdlkf sñdlkf sñdlkf sñkf&nbsp;</p>','faf sdf sfas sd','0730','Inca','Illes Balears','971179654','prova@empresa.cat',0,0,'2020-03-27','',NULL,NULL),(7,'Prova 27 v2','<p>a lñskd añskdañsldk añsldkañsldk añlsdk aska lñsd añk</p>','salksj dasdjalsjd l dsa','07300','Inca',' Illes Balears','987456789','prova27@asdasd.com',0,0,'2020-03-27','',NULL,NULL),(8,'sdfas fsdf asdf s','<p>&nbsp;sdf sñdlkfñsdl kfsñdlfk sñldkf ñsdlfk ñsdfk sñk</p>','sdfdfsdfsd','07300','Inca','Illes Balears','654654646','asdfsdf@sdsdffsdf.fdd',0,0,'2020-03-27','',NULL,NULL),(9,'lsdnsdlfxcvxcvx x','<p><br></p>','','','','','','asdfg456456@sdfsf.sdf',0,0,'2020-03-27','',NULL,NULL),(10,'hjkhkhjkhjkh khjkhj khjjh','<p><br></p>','','','','','','hjkhkhjkhkh@dell.com',0,0,'2020-03-27','',NULL,NULL),(11,'gjnbtkgnbkb','<p><br></p>','','','','','','cdedcedn@rty.com',0,0,'2020-03-27','','ADG',NULL),(12,'infoInfo','<p>.ma.sdm. d,a.sd,am s.dm a.sdm a.s,dmas.dma.sdma.s,md a.ds,madalsjd lakjdaljdal salskdj alskdj alskdj alskdj alksdjallaskjd alkdjalsjd a</p>','alkdalkjslda','07300','Inca','Illes Balears','123456789','info2@info2.com',0,0,'2020-03-28','www.info2.com','IFC',NULL),(13,'aasdads','<p><br></p>','','','','','asdasd','dasdasdads@asd.asd',1,1,'2020-03-28','','IFC',NULL),(14,'Darrera d&#39;avui','<p><br></p>','','','','','','ertertetre@darrera.avui',0,0,'2020-03-30','','IFC','NO sap escriure ni el seu nom'),(15,'Aquesta si que és la darrera d&#39;avui','<p><br></p>','','','','','','wsxedcefvr@rfvgb.com',0,0,'2020-03-30','','IFC',''),(16,'D\'avui & d\'ahir','<p><br></p>','','','0','','','sdfsfdd@wer.ert',0,0,'2020-03-30','','IFC',NULL);
/*!40000 ALTER TABLE `Empreses` ENABLE KEYS */;
UNLOCK TABLES;
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
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `borsa`.`Empreses_AFTER_DELETE` AFTER DELETE ON `Empreses` FOR EACH ROW
BEGIN
DECLARE id INT;
 set id=(select idUsuari from usuaris where nomUsuari=OLD.email);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EstatLaboral`
--

LOCK TABLES `EstatLaboral` WRITE;
/*!40000 ALTER TABLE `EstatLaboral` DISABLE KEYS */;
INSERT INTO `EstatLaboral` VALUES (1,'Estudiant'),(2,'Aturat'),(3,'Treballant');
/*!40000 ALTER TABLE `EstatLaboral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Estudis`
--

DROP TABLE IF EXISTS `Estudis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Estudis` (
  `codi` char(7) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `familia` char(3) NOT NULL,
  PRIMARY KEY (`codi`),
  KEY `fk_Estudis_familiesProfessionals1_idx` (`familia`),
  CONSTRAINT `fk_Estudis_familiesProfessionals1` FOREIGN KEY (`familia`) REFERENCES `familiesProfessionals` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Estudis`
--

LOCK TABLES `Estudis` WRITE;
/*!40000 ALTER TABLE `Estudis` DISABLE KEYS */;
INSERT INTO `Estudis` VALUES ('ADG11','Serveis administratius','ADG'),('ADG21','Gestió administrativa','ADG'),('ADG31','Assistència a la direcció','ADG'),('ADG32','Administració i finances','ADG'),('ELE11','Electricitat i electrònica','ELE'),('ELE21','Instal·lacions elèctriques i automàtiques','ELE'),('ELE22','Instal·lacions de telecomunicacions','ELE'),('ELE31','Sistemes electrotècnics i automatitzats','ELE'),('ELE32','Sistemes de telecomunicació i informàtics','ELE'),('ELE33','Manteniment electrònic','ELE'),('ELE34','Automatització i robòtica industrial','ELE'),('IFC11','Informàtica i comunicacions','IFC'),('IFC12','Informàtica d\'oficina','IFC'),('IFC21','Sistemes microinformàtics i xarxes','IFC'),('IFC31','Administració de sistemes informàtics en xarxa','IFC'),('IFC32','Desenvolupament d\'aplicacions multiplataforma','IFC'),('IFC33','Desenvolupament d\'aplicacions web','IFC'),('TMV11','Manteniment de vehicles','TMV'),('TMV12','Manteniment d\'embarcacions esportives id\'esbarjo','TMV'),('TMV21','Carrosseria','TMV'),('TMV22','Electromecànica de vehicles automòbils','TMV'),('TMV31','Automoció','TMV');
/*!40000 ALTER TABLE `Estudis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Estudis_has_Responsables`
--

DROP TABLE IF EXISTS `Estudis_has_Responsables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Estudis_has_Responsables` (
  `Estudis_codi` char(7) NOT NULL,
  `Professors_idProfessor` int(11) NOT NULL,
  PRIMARY KEY (`Estudis_codi`,`Professors_idProfessor`),
  KEY `fk_Estudis_has_Professors_Professors1_idx` (`Professors_idProfessor`),
  KEY `fk_Estudis_has_Professors_Estudis1_idx` (`Estudis_codi`),
  CONSTRAINT `fk_Estudis_has_Professors_Estudis1` FOREIGN KEY (`Estudis_codi`) REFERENCES `Estudis` (`codi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Estudis_has_Professors_Professors1` FOREIGN KEY (`Professors_idProfessor`) REFERENCES `Professors` (`idProfessor`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Estudis_has_Responsables`
--

LOCK TABLES `Estudis_has_Responsables` WRITE;
/*!40000 ALTER TABLE `Estudis_has_Responsables` DISABLE KEYS */;
INSERT INTO `Estudis_has_Responsables` VALUES ('IFC32',1),('IFC31',2),('IFC33',2);
/*!40000 ALTER TABLE `Estudis_has_Responsables` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Idiomes`
--

LOCK TABLES `Idiomes` WRITE;
/*!40000 ALTER TABLE `Idiomes` DISABLE KEYS */;
INSERT INTO `Idiomes` VALUES (1,'Català'),(2,'Castellà'),(3,'Anglès'),(4,'Alemany'),(5,'Àrab');
/*!40000 ALTER TABLE `Idiomes` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `Illes`
--

LOCK TABLES `Illes` WRITE;
/*!40000 ALTER TABLE `Illes` DISABLE KEYS */;
INSERT INTO `Illes` VALUES ('071','Mallorca'),('072','Menorca'),('073','Eivissa'),('074','Formentera');
/*!40000 ALTER TABLE `Illes` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `Localitats`
--

LOCK TABLES `Localitats` WRITE;
/*!40000 ALTER TABLE `Localitats` DISABLE KEYS */;
INSERT INTO `Localitats` VALUES ('071007300','Inca','071'),('071007310','Campanet','071');
/*!40000 ALTER TABLE `Localitats` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NivellsIdioma`
--

LOCK TABLES `NivellsIdioma` WRITE;
/*!40000 ALTER TABLE `NivellsIdioma` DISABLE KEYS */;
INSERT INTO `NivellsIdioma` VALUES (1,'Gens'),(2,'Malament'),(3,'Bé'),(4,'Molt bé');
/*!40000 ALTER TABLE `NivellsIdioma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Ofertes`
--

DROP TABLE IF EXISTS `Ofertes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ofertes` (
  `idOferta` int(11) NOT NULL AUTO_INCREMENT,
  `titol` varchar(50) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ofertes`
--

LOCK TABLES `Ofertes` WRITE;
/*!40000 ALTER TABLE `Ofertes` DISABLE KEYS */;
INSERT INTO `Ofertes` VALUES (1,'asdasd','\n                        Hola\n                    ',NULL,'2020-10-26',0,NULL,3,'asdasd','asdasd','asdasd',NULL),(2,'rfvrfv','Hola',NULL,'2020-05-15',0,NULL,3,'vrfvrfv','rfvrfv','rfvrfv',NULL),(3,'nyhnyhn','Hola',NULL,'2020-05-05',0,NULL,3,'nyhnyhn','yhnyhn','yhnyhn',NULL),(4,'Programador junior Java','Hola','2020-03-31','2020-05-12',1,2,3,'Contracte en pràctiques','','',NULL),(5,'asdsdasd','\n                        \n                    ',NULL,'2020-10-18',0,NULL,3,'aasdasd','asdas','asdasd',NULL);
/*!40000 ALTER TABLE `Ofertes` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `Ofertes_enviada_Alumnes`
--

LOCK TABLES `Ofertes_enviada_Alumnes` WRITE;
/*!40000 ALTER TABLE `Ofertes_enviada_Alumnes` DISABLE KEYS */;
INSERT INTO `Ofertes_enviada_Alumnes` VALUES (4,1);
/*!40000 ALTER TABLE `Ofertes_enviada_Alumnes` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `Ofertes_has_Contactes`
--

LOCK TABLES `Ofertes_has_Contactes` WRITE;
/*!40000 ALTER TABLE `Ofertes_has_Contactes` DISABLE KEYS */;
INSERT INTO `Ofertes_has_Contactes` VALUES (1,2),(4,2),(2,3),(4,3);
/*!40000 ALTER TABLE `Ofertes_has_Contactes` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `Ofertes_has_EstatLaboral`
--

LOCK TABLES `Ofertes_has_EstatLaboral` WRITE;
/*!40000 ALTER TABLE `Ofertes_has_EstatLaboral` DISABLE KEYS */;
INSERT INTO `Ofertes_has_EstatLaboral` VALUES (4,1),(4,3);
/*!40000 ALTER TABLE `Ofertes_has_EstatLaboral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Ofertes_has_Estudis`
--

DROP TABLE IF EXISTS `Ofertes_has_Estudis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ofertes_has_Estudis` (
  `Ofertes_idOferta` int(11) NOT NULL,
  `Estudis_codi` char(7) NOT NULL,
  `any` int(11) DEFAULT NULL,
  `nota` int(11) DEFAULT NULL,
  PRIMARY KEY (`Ofertes_idOferta`,`Estudis_codi`),
  KEY `fk_Ofertes_has_Estudis_Estudis1_idx` (`Estudis_codi`),
  KEY `fk_Ofertes_has_Estudis_Ofertes1_idx` (`Ofertes_idOferta`),
  CONSTRAINT `fk_Ofertes_has_Estudis_Estudis1` FOREIGN KEY (`Estudis_codi`) REFERENCES `Estudis` (`codi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ofertes_has_Estudis_Ofertes1` FOREIGN KEY (`Ofertes_idOferta`) REFERENCES `Ofertes` (`idOferta`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ofertes_has_Estudis`
--

LOCK TABLES `Ofertes_has_Estudis` WRITE;
/*!40000 ALTER TABLE `Ofertes_has_Estudis` DISABLE KEYS */;
INSERT INTO `Ofertes_has_Estudis` VALUES (1,'IFC32',2019,5),(2,'ADG32',2017,5),(3,'IFC33',2014,5),(4,'IFC32',2017,5),(4,'IFC33',0,0);
/*!40000 ALTER TABLE `Ofertes_has_Estudis` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `Ofertes_has_Idiomes`
--

LOCK TABLES `Ofertes_has_Idiomes` WRITE;
/*!40000 ALTER TABLE `Ofertes_has_Idiomes` DISABLE KEYS */;
INSERT INTO `Ofertes_has_Idiomes` VALUES (4,3,3),(2,4,4),(4,4,4);
/*!40000 ALTER TABLE `Ofertes_has_Idiomes` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Professors`
--

LOCK TABLES `Professors` WRITE;
/*!40000 ALTER TABLE `Professors` DISABLE KEYS */;
INSERT INTO `Professors` VALUES (1,'Joan','Pons Tugores','666555444','ptj@iespaucasesnoves.cat',1,1),(2,'Tomeu','Campaner Fornés','699855477','ptj@paucasesnovescifp.cat',1,1),(4,'Jo','Mateix','','asd@paucasesnovescifp.cat',0,2);
/*!40000 ALTER TABLE `Professors` ENABLE KEYS */;
UNLOCK TABLES;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`usuariWeb`@`%`*/ /*!50003 TRIGGER `borsa`.`Professors_AFTER_INSERT` AFTER INSERT ON `Professors` FOR EACH ROW
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
-- Dumping data for table `Rols`
--

LOCK TABLES `Rols` WRITE;
/*!40000 ALTER TABLE `Rols` DISABLE KEYS */;
INSERT INTO `Rols` VALUES (10,'Professor'),(20,'Empresa'),(30,'Alumne'),(40,'Administrador');
/*!40000 ALTER TABLE `Rols` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `TipusUsuaris`
--

LOCK TABLES `TipusUsuaris` WRITE;
/*!40000 ALTER TABLE `TipusUsuaris` DISABLE KEYS */;
INSERT INTO `TipusUsuaris` VALUES (10,'Professor'),(20,'Empresa'),(30,'Estudiant');
/*!40000 ALTER TABLE `TipusUsuaris` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuaris`
--

LOCK TABLES `Usuaris` WRITE;
/*!40000 ALTER TABLE `Usuaris` DISABLE KEYS */;
INSERT INTO `Usuaris` VALUES (1,30,'rafel@iespaucasesnoves.cat','12345678',1),(2,30,'borja@iespaucasesnoves.cat','12345678',2),(3,30,'cristian@iespaucasesnoves.cat','12345678',3),(4,20,'info@sameva.cat','12345678',1),(5,20,'info@nissan.jp','12345678',2),(7,10,'ptj@iespaucasesnoves.cat','12345678',1),(8,10,'ptj@paucasesnovescifp.cat','12345678',2),(9,20,'info@poves.com','076cc671',4),(10,20,'asdfg@sdfsf.sdf','4217a2b8',5),(11,20,'prova@empresa.cat','58676827',6),(12,20,'prova27@asdasd.com','4412def6',7),(13,20,'asdfsdf@sdsdffsdf.fdd','82b10d3f',8),(14,20,'asdfg456456@sdfsf.sdf','51841494',9),(15,20,'hjkhkhjkhkh@dell.com','0f330518',10),(16,20,'cdedcedn@rty.com','a2ed0c36',11),(17,20,'info2@info2.com','6b574949',12),(18,20,'dasdasdads@asd.asd','42f23e38',13),(19,20,'ertertetre@darrera.avui','036fb40d',14),(20,20,'wsxedcefvr@rfvgb.com','1a56ba19',15),(21,20,'sdfsfdd@wer.ert','207a570e',16),(23,10,'asd@paucasesnovescifp.cat','f0f6b7b4',4),(24,20,'info@intel.es22','12345679',3),(29,30,'2134@sdf.cde','ee902ea4',8);
/*!40000 ALTER TABLE `Usuaris` ENABLE KEYS */;
UNLOCK TABLES;
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
  CONSTRAINT `fk_Usuaris_has_Rols_Usuaris1` FOREIGN KEY (`Usuaris_idUsuari`) REFERENCES `Usuaris` (`idUsuari`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuaris_has_Rols`
--

LOCK TABLES `Usuaris_has_Rols` WRITE;
/*!40000 ALTER TABLE `Usuaris_has_Rols` DISABLE KEYS */;
INSERT INTO `Usuaris_has_Rols` VALUES (7,10),(8,10),(23,10),(4,20),(5,20),(9,20),(10,20),(11,20),(12,20),(13,20),(14,20),(15,20),(16,20),(17,20),(18,20),(19,20),(20,20),(21,20),(24,20),(1,30),(2,30),(3,30),(29,30),(7,40),(23,40);
/*!40000 ALTER TABLE `Usuaris_has_Rols` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `familiesProfessionals`
--

LOCK TABLES `familiesProfessionals` WRITE;
/*!40000 ALTER TABLE `familiesProfessionals` DISABLE KEYS */;
INSERT INTO `familiesProfessionals` VALUES ('ADG','ADMINISTRACIÓ I GESTIÓ'),('ELE','ELECTRICITAT I ELECTRÒNICA'),('ENA','ENERGIA I AIGUA'),('IFC','INFORMÀTICA I COMUNICACIONS'),('TMV','TRANSPORT I MANTENIMENT DE VEHICLES');
/*!40000 ALTER TABLE `familiesProfessionals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'borsa'
--

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

-- Dump completed on 2020-04-10 20:14:25
