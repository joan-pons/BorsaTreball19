-- CREATE DATABASE IF NOT EXISTS `borsa` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `borsa`;
-- MySQL dump 10.13  Distrib 5.7.32, for Linux (x86_64)
--
-- Host: localhost    Database: borsa
-- ------------------------------------------------------
-- Server version	5.7.32-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

--
-- Dumping data for table `Ajuda`
--

LOCK TABLES `Ajuda` WRITE;
/*!40000 ALTER TABLE `Ajuda`
    DISABLE KEYS */;
INSERT INTO `Ajuda`
VALUES (0,
        '<h3>Borsa de treball de l\'IES Pau Casesnoves</h3><p>Aquesta aplicació web permet gestionar la borsa de treball de l\'institut d\'educació secundària Pau Casesnoves. Està dirigida a tres sectors:</p><p><ul><li>Les empreses: poden apuntar-se a la borsa de treball i des d\'aquest moment podran fer ofertes de treball que arribaran als alumnes que compleixin els requisits demanats a l\'oferta.<br></li><li>Els alumnes: Quan es graduen es donen automàticament d\'alta el sistema, i si ells activen el seu compte podran ser seleccionats com a candidats per a les ofertes de treball.</li><li>Els professors: poden assignar-se uns determinats estudis i des d\'aquest moment hauran de validar les ofertes que publiquen les empreses abans de que arribin als alumnes.</li></ul>Per començar, pitgi a la barra de navegació sobre el col·lectiu al qual pertany, empresa, estudiant o professor.</p>');
/*!40000 ALTER TABLE `Ajuda`
    ENABLE KEYS */;
UNLOCK TABLES;


--
-- Dumping data for table `Configuracio`
--

LOCK TABLES `Configuracio` WRITE;
/*!40000 ALTER TABLE `Configuracio`
    DISABLE KEYS */;
INSERT INTO `Configuracio`
VALUES ('2020-09-01', '2020-12-31', 1);
/*!40000 ALTER TABLE `Configuracio`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Empreses`
--

LOCK TABLES `Empreses` WRITE;
INSERT INTO `Empreses`
VALUES (null, 'CIFP Pau Casesnoves',
        '<p>Aquesta és una empresa fictícia que només s\'utilitza quan un professor del centre ha de gestionar ofertes d\'alguna empresa que per la raó que sigui no està donada d\'alta al sistema.</p><p>Comprovau bé la forma de contacte dins de la descripció de l\'oferta.</p>',
        'Carrer Joan Miró 22', '07300', '0', 'Illes Balears', '971881710', 'borsa.treball@paucasesnovescifp.cat', 1, 0,
        '2020-11-20', 'www.paucasesnovescifp.cat', 'IFC', '', 'Si', 'Si');
UNLOCK TABLES;

--
-- Dumping data for table `EstatLaboral`
--

LOCK TABLES `EstatLaboral` WRITE;
/*!40000 ALTER TABLE `EstatLaboral`
    DISABLE KEYS */;
INSERT INTO `EstatLaboral`
VALUES (1, 'Completa'),
       (2, 'Horabaixa'),
       (3, 'Matí'),
       (4, 'Vespres'),
       (5, 'Caps de setmana');
/*!40000 ALTER TABLE `EstatLaboral`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Estudis`
--

LOCK TABLES `Estudis` WRITE;
/*!40000 ALTER TABLE `Estudis`
    DISABLE KEYS */;
INSERT INTO `Estudis`
VALUES ('ADG11', 'Serveis administratius', 'ADG'),
       ('ADG21', 'Gestió administrativa', 'ADG'),
       ('ADG31', 'Assistència a la direcció', 'ADG'),
       ('ADG32', 'Administració i finances', 'ADG'),
       ('ELE11', 'Electricitat i electrònica', 'ELE'),
       ('ELE21', 'Instal·lacions elèctriques i automàtiques', 'ELE'),
       ('ELE22', 'Instal·lacions de telecomunicacions', 'ELE'),
       ('ELE31', 'Sistemes electrotècnics i automatitzats', 'ELE'),
       ('ELE32', 'Sistemes de telecomunicació i informàtics', 'ELE'),
       ('ELE33', 'Manteniment electrònic', 'ELE'),
       ('ELE34', 'Automatització i robòtica industrial', 'ELE'),
       ('ENA33', 'Energies renovables', 'ENA'),
       ('IFC11', 'Informàtica i comunicacions', 'IFC'),
       ('IFC12', 'Informàtica d\'oficina', 'IFC'),
       ('IFC21', 'Sistemes microinformàtics i xarxes', 'IFC'),
       ('IFC31', 'Administració de sistemes informàtics en xarxa', 'IFC'),
       ('IFC32', 'Desenvolupament d\'aplicacions multiplataforma', 'IFC'),
       ('IFC33', 'Desenvolupament d\'aplicacions web', 'IFC'),
       ('IFCD0110', 'Confecció i publicació de pàgines web', 'IFC'),
       ('TMV11', 'Manteniment de vehicles', 'TMV'),
       ('TMV12', 'Manteniment d\'embarcacions esportives id\'esbarjo', 'TMV'),
       ('TMV21', 'Carrosseria', 'TMV'),
       ('TMV22', 'Electromecànica de vehicles automòbils', 'TMV'),
       ('TMV31', 'Automoció', 'TMV');
/*!40000 ALTER TABLE `Estudis`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Idiomes`
--

LOCK TABLES `Idiomes` WRITE;
/*!40000 ALTER TABLE `Idiomes`
    DISABLE KEYS */;
INSERT INTO `Idiomes`
VALUES (1, 'Català'),
       (2, 'Castellà'),
       (3, 'Anglès'),
       (4, 'Alemany'),
       (5, 'Àrab'),
       (6, 'Francés'),
       (7, 'Rus'),
       (8, 'Italià');
/*!40000 ALTER TABLE `Idiomes`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Illes`
--

LOCK TABLES `Illes` WRITE;
/*!40000 ALTER TABLE `Illes`
    DISABLE KEYS */;
INSERT INTO `Illes`
VALUES ('071', 'Mallorca'),
       ('072', 'Menorca'),
       ('073', 'Eivissa'),
       ('074', 'Formentera');
/*!40000 ALTER TABLE `Illes`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Localitats`
--

LOCK TABLES `Localitats` WRITE;
/*!40000 ALTER TABLE `Localitats`
    DISABLE KEYS */;
INSERT INTO `Localitats`
VALUES ('071007300', 'Inca', '071');
/*!40000 ALTER TABLE `Localitats`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `NivellsIdioma`
--

LOCK TABLES `NivellsIdioma` WRITE;
/*!40000 ALTER TABLE `NivellsIdioma`
    DISABLE KEYS */;
INSERT INTO `NivellsIdioma`
VALUES (1, 'Gens'),
       (2, 'Un poc'),
       (3, 'Bé'),
       (4, 'Molt bé');
/*!40000 ALTER TABLE `NivellsIdioma`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Professors`
--

LOCK TABLES `Professors` WRITE;
/*!40000 ALTER TABLE `Professors`
    DISABLE KEYS */;
INSERT INTO `Professors`
VALUES (null, 'Joan', 'Pons Tugores', '666555444', 'ptj@paucasesnovescifp.cat', 1, 1);
/*!40000 ALTER TABLE `Professors`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Rols`
--

LOCK TABLES `Rols` WRITE;
/*!40000 ALTER TABLE `Rols`
    DISABLE KEYS */;
INSERT INTO `Rols`
VALUES (10, 'Professor'),
       (20, 'Empresa'),
       (30, 'Alumne'),
       (40, 'Administrador');
/*!40000 ALTER TABLE `Rols`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `TipusUsuaris`
--

LOCK TABLES `TipusUsuaris` WRITE;
/*!40000 ALTER TABLE `TipusUsuaris`
    DISABLE KEYS */;
INSERT INTO `TipusUsuaris`
VALUES (10, 'Professor'),
       (20, 'Empresa'),
       (30, 'Estudiant'),
       (40, 'Administrador');
/*!40000 ALTER TABLE `TipusUsuaris`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Usuaris_has_Rols`
--

LOCK TABLES `Usuaris_has_Rols` WRITE;
/*!40000 ALTER TABLE `Usuaris_has_Rols` DISABLE KEYS */;
INSERT INTO `Usuaris_has_Rols` VALUES (2,40);
/*!40000 ALTER TABLE `Usuaris_has_Rols` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Dumping data for table `familiesProfessionals`
--

LOCK TABLES `familiesProfessionals` WRITE;
/*!40000 ALTER TABLE `familiesProfessionals`
    DISABLE KEYS */;
INSERT INTO `familiesProfessionals`
VALUES ('ADG', 'ADMINISTRACIÓ I GESTIÓ'),
       ('ELE', 'ELECTRICITAT I ELECTRÒNICA'),
       ('ENA', 'ENERGIA I AIGUA'),
       ('IFC', 'INFORMÀTICA I COMUNICACIONS'),
       ('TMV', 'TRANSPORT I MANTENIMENT DE VEHICLES');
/*!40000 ALTER TABLE `familiesProfessionals`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'borsa'
--
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

-- Dump completed on 2020-11-30 12:37:32
