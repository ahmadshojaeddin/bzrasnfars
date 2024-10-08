-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: localhost    Database: setad
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `inspection_status`
--

DROP TABLE IF EXISTS `inspection_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inspection_status` (
  `id` int(11) NOT NULL,
  `desc_` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inspection_status`
--

LOCK TABLES `inspection_status` WRITE;
/*!40000 ALTER TABLE `inspection_status` DISABLE KEYS */;
INSERT INTO `inspection_status` VALUES (1,'╪»╪▒ ╪¡╪º┘ä ╪º┘å╪¼╪º┘à'),(2,'╪º┘å╪¼╪º┘à ╪┤╪»┘ç');
/*!40000 ALTER TABLE `inspection_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inspections`
--

DROP TABLE IF EXISTS `inspections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inspections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_` varchar(10) NOT NULL,
  `state_code` int(11) NOT NULL,
  `status_code` int(11) NOT NULL,
  `desc_` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inspections`
--

LOCK TABLES `inspections` WRITE;
/*!40000 ALTER TABLE `inspections` DISABLE KEYS */;
INSERT INTO `inspections` VALUES (1,'1402/06/01',1,2,'╪»╪º┘ê╪▒╪»╪º┘å'),(2,'1402/07/02',2,2,'╪»╪º┘ê╪▒╪»╪º┘å'),(3,'1402/08/03',3,2,'╪»╪º┘ê╪▒╪»╪º┘å'),(4,'1402/09/04',4,2,'╪»╪º┘ê╪▒╪»╪º┘å'),(5,'1402/10/05',5,2,'╪»╪º┘ê╪▒╪»╪º┘å'),(6,'1402/11/06',6,2,'╪»╪º┘ê╪▒╪»╪º┘å'),(7,'1403/02/07',7,2,'╪»╪º┘ê╪▒╪»╪º┘å'),(8,'1403/03/08',8,1,'╪»╪º┘ê╪▒╪»╪º┘å'),(9,'1403/06/18',14,1,''),(10,'1403/06/15',4,1,'by-code'),(11,'1403/06/01',14,1,'by-code'),(12,'1403/06/13',5,1,'by-code'),(13,'1403/06/08',2,1,'by-code'),(14,'1403/06/05',9,1,'by-code'),(15,'1403/06/05',9,1,'by-code'),(16,'1403/06/18',6,1,'by-code'),(17,'1403/06/18',13,1,'by-code'),(18,'1403/06/26',26,1,'by-code'),(19,'1403/05/29',7,1,'by-code'),(20,'1403/06/29',4,1,'by-code'),(21,'1403/06/17',24,1,'by-code'),(22,'1403/06/05',9,1,'by-code'),(23,'1403/06/25',5,1,'by-code'),(24,'1403/06/31',9,1,'by-code'),(25,'1403/06/11',12,1,'by-code'),(26,'1403/06/03',9,1,'by-code'),(27,'1403/06/07',6,1,'by-code'),(28,'1403/06/13',13,1,'by-code'),(29,'1403/07/09',6,1,'by-code'),(30,'1403/07/16',1,1,'by-code'),(31,'1403/07/11',7,1,'by-code'),(32,'1403/07/02',4,1,'by-code'),(33,'1403/07/16',5,1,'by-code');
/*!40000 ALTER TABLE `inspections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states`
--

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
INSERT INTO `states` VALUES (1,'╪ó╪░╪▒╪¿╪º█î╪¼╪º┘å ╪┤╪▒┘é█î'),(2,'╪ó╪░╪▒╪¿╪º█î╪¼╪º┘å ╪║╪▒╪¿█î'),(3,'╪º╪▒╪»╪¿█î┘ä'),(4,'╪º╪╡┘ü┘ç╪º┘å'),(5,'╪º┘ä╪¿╪▒╪▓'),(6,'╪º█î┘ä╪º┘à'),(7,'╪¿┘ê╪┤┘ç╪▒'),(8,'╪¬┘ç╪▒╪º┘å'),(9,'┌å┘ç╪º╪▒┘à╪¡╪º┘ä ┘ê ╪¿╪«╪¬█î╪º╪▒█î'),(10,'╪«╪▒╪º╪│╪º┘å ╪¼┘å┘ê╪¿█î'),(11,'╪«╪▒╪º╪│╪º┘å ╪▒╪╢┘ê█î'),(12,'╪«╪▒╪º╪│╪º┘å ╪┤┘à╪º┘ä█î'),(13,'╪«┘ê╪▓╪│╪¬╪º┘å'),(14,'╪▓┘å╪¼╪º┘å'),(15,'╪│┘à┘å╪º┘å'),(16,'╪│█î╪│╪¬╪º┘å ┘ê ╪¿┘ä┘ê┌å╪│╪¬╪º┘å'),(17,'┘ü╪º╪▒╪│'),(18,'┘é╪▓┘ê█î┘å'),(19,'┘é┘à'),(20,'┌⌐╪▒╪»╪│╪¬╪º┘å'),(21,'┌⌐╪▒┘à╪º┘å'),(22,'┌⌐╪▒┘à╪º┘å╪┤╪º┘ç'),(23,'┌⌐┘ç┌»█î┘ä┘ê█î┘ç ┘ê ╪¿┘ê█î╪▒╪º╪¡┘à╪»'),(24,'┌»┘ä╪│╪¬╪º┘å'),(25,'┌»█î┘ä╪º┘å'),(26,'┘ä╪▒╪│╪¬╪º┘å'),(27,'┘à╪º╪▓┘å╪»╪▒╪º┘å'),(28,'┘à╪▒┌⌐╪▓█î'),(29,'┘ç╪▒┘à╪▓┌»╪º┘å'),(30,'┘ç┘à╪»╪º┘å'),(31,'█î╪▓╪»');
/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(26) DEFAULT NULL,
  `surename` varchar(26) DEFAULT NULL,
  `username` char(10) DEFAULT NULL,
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1: admin 2: bazres 3: dr, 4: pharmacy, 5: lab, 6: patology 7: clinic 8: hos',
  `tel` varchar(13) NOT NULL,
  `mobile` varchar(13) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0: none, 1:female, 2: male',
  `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '0: guest user, ... (refer to ''roles'' table)',
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'╪º╪¡┘à╪»','╪┤╪¼╪º╪╣ ╪º┘ä╪»█î┘å','3520210622','81dc9bdb52d04dc20036dbd8313ed055',0,'','09176925326','shojaeddin.ahmad@tamin.ir',2,1,1),(3,'┘à┘ç╪▒╪º┘å','╪º┘ü╪▓╪º','4240162599','81dc9bdb52d04dc20036dbd8313ed055',0,'','09175185818','dtctboy@gmail.com',2,1,1),(4,'╪¿┘å█î╪º┘à█î┘å','╪╣╪│┌⌐╪▒█î',NULL,'81dc9bdb52d04dc20036dbd8313ed055',0,'',NULL,NULL,2,1,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-01  9:44:46
