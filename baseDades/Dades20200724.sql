-- MySQL dump 10.13  Distrib 5.7.30, for Linux (x86_64)
--
-- Host: localhost    Database: borsa
-- ------------------------------------------------------
-- Server version	5.7.30-0ubuntu0.18.04.1

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
-- Dumping data for table `Ajuda`
--

LOCK TABLES `Ajuda` WRITE;
/*!40000 ALTER TABLE `Ajuda` DISABLE KEYS */;
INSERT INTO `Ajuda` VALUES (0,'<h3>Borsa de treball de l\'IES Pau Casesnoves</h3><p>Aquesta aplicació web permet gestionar la borsa de treball de l\'institut d\'educació secundària Pau Casesnoves. Està dirigida a tres sectors:</p><p><ul><li>Les empreses: poden apuntar-se a la borsa de treball i des d\'aquest moment podran fer ofertes de treball que arribaran als alumnes que compleixin els requisits demanats a l\'oferta.<br></li><li>Els alumnes: Quan es graduen es donen automàticament d\'alta el sistema, i si ells activen el seu compte podran ser seleccionats com a candidats per a les ofertes de treball.</li><li>Els professors: poden assignar-se uns determinats estudis i des d\'aquest moment hauran de validar les ofertes que publiquen les empreses abans de que arribin als alumnes.</li></ul>Per començar, pitgi a la barra de navegació sobre el col·lectiu al qual pertany, empresa, estudiant o professor.</p>');
/*!40000 ALTER TABLE `Ajuda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Alumne_has_EstatLaboral`
--

LOCK TABLES `Alumne_has_EstatLaboral` WRITE;
/*!40000 ALTER TABLE `Alumne_has_EstatLaboral` DISABLE KEYS */;
INSERT INTO `Alumne_has_EstatLaboral` VALUES (6,1),(10,1),(3,2),(6,2),(10,3);
/*!40000 ALTER TABLE `Alumne_has_EstatLaboral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Alumne_has_Estudis`
--

LOCK TABLES `Alumne_has_Estudis` WRITE;
/*!40000 ALTER TABLE `Alumne_has_Estudis` DISABLE KEYS */;
INSERT INTO `Alumne_has_Estudis` VALUES (2,'IFC32',2020,5),(3,'IFC32',2020,7),(6,'IFC33',2020,6),(8,'IFC33',2020,8),(10,'TMV31',2018,6);
/*!40000 ALTER TABLE `Alumne_has_Estudis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Alumne_has_Idiomes`
--

LOCK TABLES `Alumne_has_Idiomes` WRITE;
/*!40000 ALTER TABLE `Alumne_has_Idiomes` DISABLE KEYS */;
INSERT INTO `Alumne_has_Idiomes` VALUES (2,4,2),(6,3,2),(6,5,2),(2,3,3),(3,3,3),(3,4,3),(10,5,3),(2,1,4),(2,2,4),(3,1,4),(3,2,4),(6,1,4),(6,2,4),(8,3,4),(10,1,4),(10,2,4),(10,4,4);
/*!40000 ALTER TABLE `Alumne_has_Idiomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Alumnes`
--

LOCK TABLES `Alumnes` WRITE;
/*!40000 ALTER TABLE `Alumnes` DISABLE KEYS */;
INSERT INTO `Alumnes` VALUES (1,'Antoni','Noguera',NULL,NULL,NULL,NULL,NULL,'antoni@fictici.fic',0,NULL,NULL,0,NULL,'IFC32','No','No','2020-07-10 15:46:43'),(2,'Pedro','Bosch','','','','','','pedro@fictici.fic',1,'','<p><br></p>',1,6,'IFC32','No','No','2020-07-10 15:46:43'),(3,'Miquel','Riera','','','','','','miquel@fictici.fic',1,'','<p><br></p>',1,6,'IFC32','No','No','2020-07-10 15:46:43'),(4,'Josep','Rubio',NULL,NULL,NULL,NULL,NULL,'josep@fictici.fic',0,NULL,NULL,0,NULL,'IFC32','No','No','2020-07-10 15:46:43'),(5,'Antoni','Simon',NULL,NULL,NULL,NULL,NULL,'antoni@fictici.web',0,NULL,NULL,0,NULL,'IFC33','No','No','2020-07-10 15:46:43'),(6,'Josep Lluis','Aloy','','','','','','josep@fictici.web',1,'','<p><br></p>',1,6,'IFC33','No','No','2020-07-10 15:46:43'),(7,'Miquel','Bennassar',NULL,NULL,NULL,NULL,NULL,'miquel@fictici.web',0,NULL,NULL,0,NULL,'IFC33','No','No','2020-07-10 15:46:43'),(8,'Francesc','Arrom','','','','','','francesc@fictici.web',1,'','<p><br></p>',1,6,'IFC33','No','No','2020-07-10 15:46:43'),(9,'Salvador','Murano',NULL,NULL,NULL,NULL,NULL,'salvador@fictici.mec',0,NULL,NULL,0,NULL,'TMV31','No','No','2020-07-10 15:46:43'),(10,'Rocio','Gonzalez','','','','','','rocio@fictici.mec',1,'','<p><br></p>',1,6,'TMV31','No','No','2020-07-10 15:46:43'),(11,'Joan','Negre',NULL,NULL,NULL,NULL,NULL,'joan@fictici.mec',0,NULL,NULL,0,NULL,'TMV31','No','No','2020-07-10 15:46:43');
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
-- Dumping data for table `Configuracio`
--

LOCK TABLES `Configuracio` WRITE;
/*!40000 ALTER TABLE `Configuracio` DISABLE KEYS */;
INSERT INTO `Configuracio` VALUES ('2020-04-20','2020-07-31',1);
/*!40000 ALTER TABLE `Configuracio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Contactes`
--

LOCK TABLES `Contactes` WRITE;
/*!40000 ALTER TABLE `Contactes` DISABLE KEYS */;
INSERT INTO `Contactes` VALUES (2,'Sandra','Pujol','666366633','sandra@incasoft.emp','Cap RRHH',1);
/*!40000 ALTER TABLE `Contactes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Empreses`
--

LOCK TABLES `Empreses` WRITE;
/*!40000 ALTER TABLE `Empreses` DISABLE KEYS */;
INSERT INTO `Empreses` VALUES (1,'Inca Soft','<p>Aplicacions web des del Raiguer.&nbsp;</p>','','','0','','','info@incasoft.emp',1,1,'2020-04-20','','IFC','','Si','Si'),(2,'Autos Raiguer','<p>Taller multimarca</p>','','','0','','','info@autos.emp',0,0,'2020-04-20','','TMV',NULL,'Si','Si'),(3,'RN3','<p>Desenvolupadors per la teva empresa.&nbsp;</p>','','','0','','','info@rn3.emp',0,0,'2020-04-20','','IFC',NULL,'Si','Si');
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
-- Dumping data for table `EstatLaboral`
--

LOCK TABLES `EstatLaboral` WRITE;
/*!40000 ALTER TABLE `EstatLaboral` DISABLE KEYS */;
INSERT INTO `EstatLaboral` VALUES (1,'Estudiant'),(2,'Aturat'),(3,'Treballant');
/*!40000 ALTER TABLE `EstatLaboral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Estudis`
--

LOCK TABLES `Estudis` WRITE;
/*!40000 ALTER TABLE `Estudis` DISABLE KEYS */;
INSERT INTO `Estudis` VALUES ('ADG11','Serveis administratius','ADG'),('ADG21','Gestió administrativa','ADG'),('ADG31','Assistència a la direcció','ADG'),('ADG32','Administració i finances','ADG'),('ELE11','Electricitat i electrònica','ELE'),('ELE21','Instal·lacions elèctriques i automàtiques','ELE'),('ELE22','Instal·lacions de telecomunicacions','ELE'),('ELE31','Sistemes electrotècnics i automatitzats','ELE'),('ELE32','Sistemes de telecomunicació i informàtics','ELE'),('ELE33','Manteniment electrònic','ELE'),('ELE34','Automatització i robòtica industrial','ELE'),('ENA33','Energies renovables','ENA'),('IFC11','Informàtica i comunicacions','IFC'),('IFC12','Informàtica d\'oficina','IFC'),('IFC21','Sistemes microinformàtics i xarxes','IFC'),('IFC31','Administració de sistemes informàtics en xarxa','IFC'),('IFC32','Desenvolupament d\'aplicacions multiplataforma','IFC'),('IFC33','Desenvolupament d\'aplicacions web','IFC'),('TMV11','Manteniment de vehicles','TMV'),('TMV12','Manteniment d\'embarcacions esportives id\'esbarjo','TMV'),('TMV21','Carrosseria','TMV'),('TMV22','Electromecànica de vehicles automòbils','TMV'),('TMV31','Automoció','TMV');
/*!40000 ALTER TABLE `Estudis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Estudis_has_Responsables`
--

LOCK TABLES `Estudis_has_Responsables` WRITE;
/*!40000 ALTER TABLE `Estudis_has_Responsables` DISABLE KEYS */;
INSERT INTO `Estudis_has_Responsables` VALUES ('IFC32',6),('IFC33',6),('TMV31',6);
/*!40000 ALTER TABLE `Estudis_has_Responsables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Idiomes`
--

LOCK TABLES `Idiomes` WRITE;
/*!40000 ALTER TABLE `Idiomes` DISABLE KEYS */;
INSERT INTO `Idiomes` VALUES (1,'Català'),(2,'Castellà'),(3,'Anglès'),(4,'Alemany'),(5,'Àrab');
/*!40000 ALTER TABLE `Idiomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Illes`
--

LOCK TABLES `Illes` WRITE;
/*!40000 ALTER TABLE `Illes` DISABLE KEYS */;
INSERT INTO `Illes` VALUES ('071','Mallorca'),('072','Menorca'),('073','Eivissa'),('074','Formentera');
/*!40000 ALTER TABLE `Illes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Localitats`
--

LOCK TABLES `Localitats` WRITE;
/*!40000 ALTER TABLE `Localitats` DISABLE KEYS */;
INSERT INTO `Localitats` VALUES ('071007300','Inca','071');
/*!40000 ALTER TABLE `Localitats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `NivellsIdioma`
--

LOCK TABLES `NivellsIdioma` WRITE;
/*!40000 ALTER TABLE `NivellsIdioma` DISABLE KEYS */;
INSERT INTO `NivellsIdioma` VALUES (1,'Gens'),(2,'Un poc'),(3,'Bé'),(4,'Molt bé');
/*!40000 ALTER TABLE `NivellsIdioma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Ofertes`
--

LOCK TABLES `Ofertes` WRITE;
/*!40000 ALTER TABLE `Ofertes` DISABLE KEYS */;
INSERT INTO `Ofertes` VALUES (1,'Programador android','Cercam dues persones amb nocions d\'android i ganes d\'aprendre.&nbsp;\n                        \n                    ',NULL,'2020-04-30',0,NULL,1,'Pràctiques','De 8 a 15','Inca',NULL),(2,'Desenvolupador MEAN ','Programador MEAN amb experiència.','2020-04-20','2020-04-30',0,NULL,1,'Indefinit','de 8 a 15','Inca',NULL);
/*!40000 ALTER TABLE `Ofertes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Ofertes_enviada_Alumnes`
--

LOCK TABLES `Ofertes_enviada_Alumnes` WRITE;
/*!40000 ALTER TABLE `Ofertes_enviada_Alumnes` DISABLE KEYS */;
INSERT INTO `Ofertes_enviada_Alumnes` VALUES (2,6),(2,8);
/*!40000 ALTER TABLE `Ofertes_enviada_Alumnes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Ofertes_has_Contactes`
--

LOCK TABLES `Ofertes_has_Contactes` WRITE;
/*!40000 ALTER TABLE `Ofertes_has_Contactes` DISABLE KEYS */;
INSERT INTO `Ofertes_has_Contactes` VALUES (2,2);
/*!40000 ALTER TABLE `Ofertes_has_Contactes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Ofertes_has_EstatLaboral`
--

LOCK TABLES `Ofertes_has_EstatLaboral` WRITE;
/*!40000 ALTER TABLE `Ofertes_has_EstatLaboral` DISABLE KEYS */;
/*!40000 ALTER TABLE `Ofertes_has_EstatLaboral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Ofertes_has_Estudis`
--

LOCK TABLES `Ofertes_has_Estudis` WRITE;
/*!40000 ALTER TABLE `Ofertes_has_Estudis` DISABLE KEYS */;
INSERT INTO `Ofertes_has_Estudis` VALUES (2,'IFC33',2017,5);
/*!40000 ALTER TABLE `Ofertes_has_Estudis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Ofertes_has_Idiomes`
--

LOCK TABLES `Ofertes_has_Idiomes` WRITE;
/*!40000 ALTER TABLE `Ofertes_has_Idiomes` DISABLE KEYS */;
/*!40000 ALTER TABLE `Ofertes_has_Idiomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Professors`
--

LOCK TABLES `Professors` WRITE;
/*!40000 ALTER TABLE `Professors` DISABLE KEYS */;
INSERT INTO `Professors` VALUES (6,'Joan','Pons Tugores','666555444','ptj@paucasesnovescifp.cat',1,1);
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
-- Dumping data for table `Rols`
--

LOCK TABLES `Rols` WRITE;
/*!40000 ALTER TABLE `Rols` DISABLE KEYS */;
INSERT INTO `Rols` VALUES (10,'Professor'),(20,'Empresa'),(30,'Alumne'),(40,'Administrador');
/*!40000 ALTER TABLE `Rols` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `TipusUsuaris`
--

LOCK TABLES `TipusUsuaris` WRITE;
/*!40000 ALTER TABLE `TipusUsuaris` DISABLE KEYS */;
INSERT INTO `TipusUsuaris` VALUES (10,'Professor'),(20,'Empresa'),(30,'Estudiant');
/*!40000 ALTER TABLE `TipusUsuaris` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Tokens`
--

LOCK TABLES `Tokens` WRITE;
/*!40000 ALTER TABLE `Tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `Tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Usuaris`
--

LOCK TABLES `Usuaris` WRITE;
/*!40000 ALTER TABLE `Usuaris` DISABLE KEYS */;
INSERT INTO `Usuaris` VALUES (41,10,'ptj@paucasesnovescifp.cat','$2y$10$pMzolv4j7fp4KN7nZncX0uKa4WY/qHUENhfzpj19onqcTnHZ/38bC',6),(42,30,'antoni@fictici.fic','ac682996',1),(43,30,'pedro@fictici.fic','$2y$10$SFINXL2RoRmQVL6UCQ9hZORYckCt0YUripZAqKDZfBQwWaOXWujQG',2),(44,30,'miquel@fictici.fic','$2y$10$lZ.HonxDq3OOEU010fkB/OuOrWpmepxHTFQPqu0/cbYTu2Lqy0xaa',3),(45,30,'josep@fictici.fic','f2ee52d4',4),(46,30,'antoni@fictici.web','103d6a67',5),(47,30,'josep@fictici.web','$2y$10$x0AwDbJemTY2sU158qV.2O3zLMF8MBujyS7vl.rI/jqCy4riNxbVq',6),(48,30,'miquel@fictici.web','e1ac131a',7),(49,30,'francesc@fictici.web','$2y$10$2SEN7AeFYMQeT5O9OLVyM.CzFIa588yrk4waATHK/loSTC8qv64OS',8),(50,30,'salvador@fictici.mec','396a97a9',9),(51,30,'rocio@fictici.mec','$2y$10$x9ZtOj7mDxZDfuO7JgXMN.8I4PM9kKJIa8DxbMVoYohMN6dqjsCzq',10),(52,30,'joan@fictici.mec','c3a2b01b',11),(53,20,'info@incasoft.emp','$2y$10$lQpSpMC0h4hOU8aIWHFbvuH5MYtU6wDsRGwSj8ezUWvKCEIkN6BWa',1),(54,20,'info@autos.emp','beb6aeb9',2),(55,20,'info@rn3.emp','787bbadd',3),(57,10,'vqm@paucasesnovescifp.cat','b4b90746',7);
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
-- Dumping data for table `Usuaris_has_Rols`
--

LOCK TABLES `Usuaris_has_Rols` WRITE;
/*!40000 ALTER TABLE `Usuaris_has_Rols` DISABLE KEYS */;
INSERT INTO `Usuaris_has_Rols` VALUES (41,10),(57,10),(53,20),(54,20),(55,20),(42,30),(43,30),(44,30),(45,30),(46,30),(47,30),(48,30),(49,30),(50,30),(51,30),(52,30),(41,40);
/*!40000 ALTER TABLE `Usuaris_has_Rols` ENABLE KEYS */;
UNLOCK TABLES;

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

-- Dump completed on 2020-07-24  9:48:49
