-- MySQL dump 10.13  Distrib 5.6.51, for Linux (x86_64)
--
-- Host: mysql3001.mochahost.com    Database: malheiro_dgarden
-- ------------------------------------------------------
-- Server version	5.6.33

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
-- Table structure for table `backup`
--

DROP TABLE IF EXISTS `backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `send_email` varchar(255) DEFAULT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_encryption` varchar(255) DEFAULT NULL,
  `smtp_port` varchar(255) DEFAULT NULL,
  `smtp_username` varchar(255) DEFAULT NULL,
  `smtp_password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup`
--

LOCK TABLES `backup` WRITE;
/*!40000 ALTER TABLE `backup` DISABLE KEYS */;
INSERT INTO `backup` VALUES (1,'weezgarden@gmail.com','smtp.gmail.com','ssl','465','weezgarden@gmail.com','Ozzstraicker162330');
/*!40000 ALTER TABLE `backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'Sofex');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dry_method`
--

DROP TABLE IF EXISTS `dry_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dry_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dry_method`
--

LOCK TABLES `dry_method` WRITE;
/*!40000 ALTER TABLE `dry_method` DISABLE KEYS */;
INSERT INTO `dry_method` VALUES (1,'Natural');
/*!40000 ALTER TABLE `dry_method` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genetic`
--

DROP TABLE IF EXISTS `genetic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genetic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genetic_name` varchar(255) DEFAULT NULL,
  `plant_name` varchar(255) DEFAULT NULL,
  `photo_clone` varchar(255) DEFAULT NULL,
  `photo_veg` varchar(255) DEFAULT NULL,
  `photo_flower` varchar(255) DEFAULT NULL,
  `grams` varchar(255) DEFAULT NULL,
  `htc` varchar(255) DEFAULT NULL,
  `cbd` varchar(255) DEFAULT NULL,
  `other` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genetic`
--

LOCK TABLES `genetic` WRITE;
/*!40000 ALTER TABLE `genetic` DISABLE KEYS */;
INSERT INTO `genetic` VALUES (1,'Sativa','Royal Gorila','18','18','12','20','27','02','0');
/*!40000 ALTER TABLE `genetic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `event` longtext,
  `date` varchar(255) DEFAULT NULL,
  `days` varchar(255) DEFAULT NULL,
  `plant_UID` varchar(255) DEFAULT NULL,
  `lot_id` varchar(255) DEFAULT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `mother_UID` varchar(255) DEFAULT NULL,
  `packing_number` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history_reportprint_sell`
--

DROP TABLE IF EXISTS `history_reportprint_sell`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history_reportprint_sell` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producer_name` varchar(255) DEFAULT NULL,
  `producer_address` varchar(255) DEFAULT NULL,
  `lot_number` varchar(255) DEFAULT NULL,
  `packing_quantity` varchar(255) DEFAULT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `net_weight` varchar(255) DEFAULT NULL,
  `shipping_date` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `recipient_address` varchar(255) DEFAULT NULL,
  `packing_code` varchar(255) DEFAULT NULL,
  `gross_weight` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history_reportprint_sell`
--

LOCK TABLES `history_reportprint_sell` WRITE;
/*!40000 ALTER TABLE `history_reportprint_sell` DISABLE KEYS */;
/*!40000 ALTER TABLE `history_reportprint_sell` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `index_clone`
--

DROP TABLE IF EXISTS `index_clone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_clone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(255) DEFAULT NULL,
  `plant_id` int(255) DEFAULT NULL,
  `genetic_id` int(255) DEFAULT NULL,
  `lot_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `index_clone`
--

LOCK TABLES `index_clone` WRITE;
/*!40000 ALTER TABLE `index_clone` DISABLE KEYS */;
/*!40000 ALTER TABLE `index_clone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `index_dry`
--

DROP TABLE IF EXISTS `index_dry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_dry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(255) DEFAULT NULL,
  `plant_id` int(255) DEFAULT NULL,
  `lot_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `index_dry`
--

LOCK TABLES `index_dry` WRITE;
/*!40000 ALTER TABLE `index_dry` DISABLE KEYS */;
/*!40000 ALTER TABLE `index_dry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `index_flower`
--

DROP TABLE IF EXISTS `index_flower`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_flower` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(255) DEFAULT NULL,
  `plant_id` int(255) DEFAULT NULL,
  `lot_id` int(255) DEFAULT NULL,
  `genetic_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `index_flower`
--

LOCK TABLES `index_flower` WRITE;
/*!40000 ALTER TABLE `index_flower` DISABLE KEYS */;
/*!40000 ALTER TABLE `index_flower` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `index_mother`
--

DROP TABLE IF EXISTS `index_mother`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_mother` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(255) DEFAULT NULL,
  `plant_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `index_mother`
--

LOCK TABLES `index_mother` WRITE;
/*!40000 ALTER TABLE `index_mother` DISABLE KEYS */;
/*!40000 ALTER TABLE `index_mother` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `index_packing`
--

DROP TABLE IF EXISTS `index_packing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_packing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(255) DEFAULT NULL,
  `lot_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `index_packing`
--

LOCK TABLES `index_packing` WRITE;
/*!40000 ALTER TABLE `index_packing` DISABLE KEYS */;
/*!40000 ALTER TABLE `index_packing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `index_trimming`
--

DROP TABLE IF EXISTS `index_trimming`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_trimming` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(255) DEFAULT NULL,
  `lot_id` int(255) DEFAULT NULL,
  `plant_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `index_trimming`
--

LOCK TABLES `index_trimming` WRITE;
/*!40000 ALTER TABLE `index_trimming` DISABLE KEYS */;
/*!40000 ALTER TABLE `index_trimming` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `index_vault`
--

DROP TABLE IF EXISTS `index_vault`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_vault` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(255) DEFAULT NULL,
  `lot_id` int(255) DEFAULT NULL,
  `genetic_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `index_vault`
--

LOCK TABLES `index_vault` WRITE;
/*!40000 ALTER TABLE `index_vault` DISABLE KEYS */;
/*!40000 ALTER TABLE `index_vault` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `index_veg`
--

DROP TABLE IF EXISTS `index_veg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_veg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(255) DEFAULT NULL,
  `plant_id` int(255) DEFAULT NULL,
  `lot_id` int(255) DEFAULT NULL,
  `genetic_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `index_veg`
--

LOCK TABLES `index_veg` WRITE;
/*!40000 ALTER TABLE `index_veg` DISABLE KEYS */;
/*!40000 ALTER TABLE `index_veg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `last_index`
--

DROP TABLE IF EXISTS `last_index`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `last_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mother` int(255) DEFAULT NULL,
  `clone` int(255) DEFAULT NULL,
  `lot` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `last_index`
--

LOCK TABLES `last_index` WRITE;
/*!40000 ALTER TABLE `last_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `last_index` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lot_id`
--

DROP TABLE IF EXISTS `lot_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lot_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qr_code` varchar(255) DEFAULT NULL,
  `lot_ID` varchar(11) DEFAULT NULL,
  `genetic_ID` varchar(255) DEFAULT NULL,
  `mother_ID` varchar(255) DEFAULT NULL,
  `start_plant_ID` varchar(255) DEFAULT NULL,
  `end_plant_ID` varchar(255) DEFAULT NULL,
  `born_date` varchar(255) DEFAULT NULL,
  `dry_method` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `mother_ID_text` varchar(255) DEFAULT NULL,
  `harvest_date` varchar(255) DEFAULT NULL,
  `room_date` varchar(255) DEFAULT NULL,
  `trimming_method` varchar(255) DEFAULT NULL,
  `compound_lot_ID` varchar(11) DEFAULT NULL,
  `number_of_plants` varchar(255) DEFAULT NULL,
  `weight_in` varchar(11) DEFAULT NULL,
  `weight_out` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lot_id`
--

LOCK TABLES `lot_id` WRITE;
/*!40000 ALTER TABLE `lot_id` DISABLE KEYS */;
/*!40000 ALTER TABLE `lot_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plants`
--

DROP TABLE IF EXISTS `plants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qr_code` varchar(255) DEFAULT NULL,
  `plant_UID` bigint(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `planting_date` varchar(255) DEFAULT NULL,
  `mother_text` varchar(255) DEFAULT NULL,
  `mother_id` varchar(255) DEFAULT NULL,
  `genetic` varchar(255) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  `lot_ID` varchar(255) DEFAULT NULL,
  `mother_UID` bigint(255) DEFAULT NULL,
  `room_date` varchar(255) DEFAULT NULL,
  `dry_method` varchar(255) DEFAULT NULL,
  `total_weight` varchar(11) DEFAULT NULL,
  `waste_weight` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plants`
--

LOCK TABLES `plants` WRITE;
/*!40000 ALTER TABLE `plants` DISABLE KEYS */;
/*!40000 ALTER TABLE `plants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_clone`
--

DROP TABLE IF EXISTS `room_clone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_clone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_clone`
--

LOCK TABLES `room_clone` WRITE;
/*!40000 ALTER TABLE `room_clone` DISABLE KEYS */;
INSERT INTO `room_clone` VALUES (1,'Clonadora 1'),(2,'Clonadora 2'),(3,'Clonadora 3'),(4,'Clonadora 4'),(5,'Clonadora 5'),(6,'Clonadora 6'),(7,'Clonadora 7'),(8,'Clonadora 8'),(9,'Clonadora 10');
/*!40000 ALTER TABLE `room_clone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_dry`
--

DROP TABLE IF EXISTS `room_dry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_dry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_dry`
--

LOCK TABLES `room_dry` WRITE;
/*!40000 ALTER TABLE `room_dry` DISABLE KEYS */;
INSERT INTO `room_dry` VALUES (1,'Sala Secagem');
/*!40000 ALTER TABLE `room_dry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_flower`
--

DROP TABLE IF EXISTS `room_flower`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_flower` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_flower`
--

LOCK TABLES `room_flower` WRITE;
/*!40000 ALTER TABLE `room_flower` DISABLE KEYS */;
INSERT INTO `room_flower` VALUES (1,'FloraÃ§Ã£o 1 - FA'),(2,'FloraÃ§Ã£o 1 - FB'),(3,'FloraÃ§Ã£o 1 - FC'),(4,'FloraÃ§Ã£o 1 - FD'),(5,'FloraÃ§Ã£o 2 - FA'),(6,'FloraÃ§Ã£o 2 - FB'),(7,'FloraÃ§Ã£o 2 - FC'),(8,'FloraÃ§Ã£o 2 - FD');
/*!40000 ALTER TABLE `room_flower` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_mother`
--

DROP TABLE IF EXISTS `room_mother`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_mother` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_mother`
--

LOCK TABLES `room_mother` WRITE;
/*!40000 ALTER TABLE `room_mother` DISABLE KEYS */;
INSERT INTO `room_mother` VALUES (1,'Sala Maes');
/*!40000 ALTER TABLE `room_mother` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_packing`
--

DROP TABLE IF EXISTS `room_packing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_packing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_packing`
--

LOCK TABLES `room_packing` WRITE;
/*!40000 ALTER TABLE `room_packing` DISABLE KEYS */;
INSERT INTO `room_packing` VALUES (1,'Sala Embalamento');
/*!40000 ALTER TABLE `room_packing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_trimming`
--

DROP TABLE IF EXISTS `room_trimming`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_trimming` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_trimming`
--

LOCK TABLES `room_trimming` WRITE;
/*!40000 ALTER TABLE `room_trimming` DISABLE KEYS */;
INSERT INTO `room_trimming` VALUES (1,'Sala Corte');
/*!40000 ALTER TABLE `room_trimming` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_vault`
--

DROP TABLE IF EXISTS `room_vault`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_vault` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_vault`
--

LOCK TABLES `room_vault` WRITE;
/*!40000 ALTER TABLE `room_vault` DISABLE KEYS */;
INSERT INTO `room_vault` VALUES (1,'Sala Cofre');
/*!40000 ALTER TABLE `room_vault` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_veg`
--

DROP TABLE IF EXISTS `room_veg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_veg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_veg`
--

LOCK TABLES `room_veg` WRITE;
/*!40000 ALTER TABLE `room_veg` DISABLE KEYS */;
INSERT INTO `room_veg` VALUES (1,'Vegetativo Mesa 1'),(2,'Vegetativo Mesa 2'),(3,'Vegetativo Mesa 3');
/*!40000 ALTER TABLE `room_veg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sell`
--

DROP TABLE IF EXISTS `sell`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sell` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lot_ID` varchar(255) DEFAULT NULL,
  `packing_number` int(11) DEFAULT NULL,
  `genetic` varchar(255) DEFAULT NULL,
  `grams` varchar(255) DEFAULT NULL,
  `seeds_amount` varchar(255) DEFAULT NULL,
  `sell_date` varchar(255) DEFAULT NULL,
  `grams_price` varchar(255) DEFAULT NULL,
  `total_price` varchar(255) DEFAULT NULL,
  `client` varchar(255) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sell`
--

LOCK TABLES `sell` WRITE;
/*!40000 ALTER TABLE `sell` DISABLE KEYS */;
/*!40000 ALTER TABLE `sell` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trimming_method`
--

DROP TABLE IF EXISTS `trimming_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trimming_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trimming_method`
--

LOCK TABLES `trimming_method` WRITE;
/*!40000 ALTER TABLE `trimming_method` DISABLE KEYS */;
INSERT INTO `trimming_method` VALUES (1,'Manual'),(2,'Maquina');
/*!40000 ALTER TABLE `trimming_method` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `mother` varchar(255) DEFAULT NULL,
  `clone` varchar(255) DEFAULT NULL,
  `veg` varchar(255) DEFAULT NULL,
  `flower` varchar(255) DEFAULT NULL,
  `dry` varchar(255) DEFAULT NULL,
  `trimming` varchar(255) DEFAULT NULL,
  `vault` varchar(255) DEFAULT NULL,
  `sell` varchar(255) DEFAULT NULL,
  `history` varchar(255) DEFAULT NULL,
  `client` varchar(255) DEFAULT NULL,
  `setting` varchar(255) DEFAULT NULL,
  `genetic` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `packing` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permissions`
--

LOCK TABLES `user_permissions` WRITE;
/*!40000 ALTER TABLE `user_permissions` DISABLE KEYS */;
INSERT INTO `user_permissions` VALUES (1,'Super Administrator','1','1','1','1','1','1','1','1','1','1','1','1','1','1'),(2,'utilizador cultivo','1','1','1','1','','','','','1','','','','1',''),(3,'admin','1','1','1','1','1','1','1','1','1','1','1','1','1','1'),(4,'processing','','','','','1','1','1','1','1','','','','1','1'),(5,'Quality Manager','1','1','1','1','1','1','1','1','1','1','1','','','1'),(6,'utilizador total','1','1','1','1','1','1','1','1','1','','1','','1','1');
/*!40000 ALTER TABLE `user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `language` varchar(255) DEFAULT NULL,
  `permissions_id` varchar(255) DEFAULT NULL,
  `superAdmin` varchar(255) DEFAULT NULL,
  `supervisor` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@admin.com','f6fdffe48c908deb0f4c3bd36c032e72','English','1','1',1),(2,'jteixeira','jteixeira@weezgarden.com','8e1ba83fc0de960b6fc50727c4b6381e','English','6',NULL,0),(3,'jpiloto','jpiloto@weezgarden.com','06f3f088d38e05508c6e199b103d8b69','English','6',NULL,0),(4,'jmalheiro','jmalheiro@weezgarden.com','95b89916dc4dd949214b170a7d50a12f','English','5',NULL,0),(5,'rmalheiro','rmalheiro@weezgarden.com','1f598531bd53de9ee081bdf39310bd64','English','1',NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vault`
--

DROP TABLE IF EXISTS `vault`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vault` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lot_ID` varchar(255) DEFAULT NULL,
  `packing_number` int(11) DEFAULT NULL,
  `genetic_ID` varchar(255) DEFAULT NULL,
  `packing_date` varchar(255) DEFAULT NULL,
  `grams_amount` varchar(255) DEFAULT NULL,
  `seeds_amount` varchar(255) DEFAULT NULL,
  `thc` varchar(255) DEFAULT NULL,
  `cbd` varchar(255) DEFAULT NULL,
  `other` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `producer_name` varchar(255) DEFAULT NULL,
  `place_origin` varchar(255) DEFAULT NULL,
  `room_date` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vault`
--

LOCK TABLES `vault` WRITE;
/*!40000 ALTER TABLE `vault` DISABLE KEYS */;
/*!40000 ALTER TABLE `vault` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-18 12:00:07
