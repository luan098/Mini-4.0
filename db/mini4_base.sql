-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: mini4
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
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu_father` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `page` varchar(200) DEFAULT NULL,
  `icon` varchar(150) DEFAULT NULL,
  `status` binary(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `menus_FK` (`id_menu_father`),
  CONSTRAINT `menus_FK` FOREIGN KEY (`id_menu_father`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (2,NULL,'Users','','fas fa-users-cog',_binary '1'),(3,NULL,'Configurations','','fas fa-tools',_binary '1'),(4,3,'System','settings','far fa-circle',_binary '1'),(7,3,'Texts','texts','far fa-circle',_binary '1'),(8,NULL,'Products','','fas fa-shopping-cart',_binary '1'),(9,8,'Categories','product-categories','far fa-circle',_binary '1'),(10,8,'Products','products','far fa-circle',_binary '1'),(11,NULL,'Orders','orders','fas fa-scroll',_binary '1'),(15,3,'Plans','plans','far fa-circle',_binary '1'),(16,2,'Users','users','far fa-circle',_binary '1'),(17,2,'Sellers','users/sellers','far fa-circle',_binary '1'),(18,2,'Clients','users/clients','far fa-circle',_binary '1'),(19,2,'Mediators','users/mediators','far fa-circle',_binary '1'),(20,NULL,'Subscriptions','','fas fa-thumbs-up',_binary '1'),(21,20,'Subscriptions','subscriptions','far fa-circle',_binary '1'),(22,20,'Expiring','subscriptions/expiring','far fa-circle',_binary '1'),(23,20,'Monthly Payments','subscriptions/monthly-payments','far fa-circle',_binary '1'),(24,2,'User Types','user-types','far fa-circle',_binary '1');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings_email`
--

DROP TABLE IF EXISTS `settings_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_name` varchar(255) NOT NULL,
  `sender_email` varchar(200) NOT NULL,
  `sender_password` varchar(255) NOT NULL,
  `sender_host` varchar(200) NOT NULL,
  `port` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings_email`
--

LOCK TABLES `settings_email` WRITE;
/*!40000 ALTER TABLE `settings_email` DISABLE KEYS */;
INSERT INTO `settings_email` VALUES (1,'Fast Import','sistema@fastimport.net','vm3134199F','mail.ydeal.net.br',587);
/*!40000 ALTER TABLE `settings_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings_general`
--

DROP TABLE IF EXISTS `settings_general`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings_general` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `pix_code` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings_general`
--

LOCK TABLES `settings_general` WRITE;
/*!40000 ALTER TABLE `settings_general` DISABLE KEYS */;
INSERT INTO `settings_general` VALUES (1,'Fast Import',NULL);
/*!40000 ALTER TABLE `settings_general` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL COMMENT 'Nome de exibição do arquivo, não precisa da extensão',
  `file` varchar(350) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL COMMENT 'Nome real do arquivo junto com sua extensão, use um nome unico sempre para evitar duplicação de arquivos',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uploaded_files_un` (`file`),
  KEY `uploaded_files_FK` (`created_by`) USING BTREE,
  KEY `uploaded_files_FK_1` (`updated_by`) USING BTREE,
  CONSTRAINT `uploaded_files_FK` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `uploaded_files_FK_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela centralizadora de anexos, todos os anexos ficam aqui e e são invocados por tabelas relacionais';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
INSERT INTO `uploads` VALUES (1,'controle.webp','6501c8b887fb4.webp','2023-09-13 14:35:36',NULL,1,NULL,NULL),(2,'controle.webp','6501c9291c8ed.webp','2023-09-13 14:37:29',NULL,1,NULL,NULL),(3,'bateria-carro.webp','6501c972e1bd1.webp','2023-09-13 14:38:42',NULL,1,NULL,NULL),(4,'bateria-carro.webp','6501c9808cc8e.webp','2023-09-13 14:38:56',NULL,1,NULL,NULL),(5,'Prensa-mesa.webp','6501c9e404775.webp','2023-09-13 14:40:36',NULL,1,NULL,NULL),(6,'Prensa-mesa.webp','6501c9ecb5a06.webp','2023-09-13 14:40:44',NULL,1,NULL,NULL),(7,'S5b9d9be95bda4b9a831b298f295385abu.jpg_.webp','6501ca3f1c23c.webp','2023-09-13 14:42:07',NULL,1,NULL,NULL),(8,'S5b9d9be95bda4b9a831b298f295385abu.jpg_.webp','6501ca4575819.webp','2023-09-13 14:42:13',NULL,1,NULL,NULL),(9,'S817f049a38864724a3a1289282a83efcw.jpg_.webp','6501ca9072424.webp','2023-09-13 14:43:28',NULL,1,NULL,NULL),(10,'S817f049a38864724a3a1289282a83efcw.jpg_.webp','6501ca97e63b7.webp','2023-09-13 14:43:35',NULL,1,NULL,NULL),(11,'S817f049a38864724a3a1289282a83efcw.jpg_.webp','650c8d3537846.png','2023-09-13 14:43:35',NULL,1,NULL,NULL),(12,'S817f049a38864724a3a1289282a83efcw.jpg_.webp','650d845c58a5d.jpg','2023-09-13 14:43:35',NULL,1,NULL,NULL),(13,'S817f049a38864724a3a1289282a83efcw.jpg_.webp','650d84796a7fd.jpg','2023-09-13 14:43:35',NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_type_permissions`
--

DROP TABLE IF EXISTS `user_type_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_type_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(255) NOT NULL,
  `sub_route` varchar(255) DEFAULT NULL,
  `id_user_type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_type_accesses_FK` (`id_user_type`),
  CONSTRAINT `user_type_accesses_FK` FOREIGN KEY (`id_user_type`) REFERENCES `user_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_type_permissions`
--

LOCK TABLES `user_type_permissions` WRITE;
/*!40000 ALTER TABLE `user_type_permissions` DISABLE KEYS */;
INSERT INTO `user_type_permissions` VALUES (10,'orders',NULL,2),(11,'products',NULL,2);
/*!40000 ALTER TABLE `user_type_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `status` binary(1) DEFAULT '1',
  `is_admin` binary(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` VALUES (1,'Customer',_binary '1',_binary '0'),(2,'Seller',_binary '1',_binary '0'),(3,'Mediator (Transporter)',_binary '1',_binary '0'),(4,'Administrator',_binary '1',_binary '1'),(5,'Support Team',_binary '1',_binary '0');
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_type` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(256) NOT NULL,
  `cpf_cnpj` varchar(50) NOT NULL,
  `date_birth` date NOT NULL,
  `terms` binary(1) DEFAULT '0',
  `approved` tinyint(1) DEFAULT 0,
  `status` binary(1) DEFAULT '1',
  `temp_password` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `id_upload_cover` int(11) DEFAULT NULL,
  `lock_screen_login_token` varchar(255) DEFAULT NULL,
  `receive_emails` tinyint(1) DEFAULT 1,
  `spam_prevent_timer` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cpf_cnpj` (`cpf_cnpj`),
  KEY `users_FK` (`id_user_type`),
  KEY `users_FK_3` (`updated_by`),
  KEY `users_FK_2` (`created_by`),
  KEY `users_FK_4` (`id_upload_cover`),
  CONSTRAINT `users_FK` FOREIGN KEY (`id_user_type`) REFERENCES `user_types` (`id`),
  CONSTRAINT `users_FK_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `users_FK_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `users_FK_4` FOREIGN KEY (`id_upload_cover`) REFERENCES `uploads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,4,'Suporte Ydeal','suporte@ydeal.com.br','25f9e794323b453885f5181f1b624d0b','19.471.199/0001-64','2014-06-01',_binary '1',1,_binary '1','Rec6511e19e85381','2023-09-21 15:00:41','2023-10-05 10:59:25',NULL,NULL,11,NULL,0,NULL),(2,2,'Felipe S. Anjos','vendedor@ydeal.com.br','25f9e794323b453885f5181f1b624d0b','081.680.299-84','1992-11-09',_binary '1',1,_binary '1','','2023-09-21 15:00:41','2023-10-04 16:21:34',NULL,NULL,12,NULL,1,NULL),(6,1,'Guilherme Simas','cliente@ydeal.com.br','25f9e794323b453885f5181f1b624d0b','964.651.489-51','2001-03-03',_binary '1',1,_binary '1',NULL,'2023-09-21 15:00:41','2023-10-04 16:21:34',NULL,NULL,13,NULL,1,NULL),(7,3,'Fabricio Silva','transportador@ydeal.com.br','25f9e794323b453885f5181f1b624d0b','844.651.615-18','1969-05-03',_binary '1',1,_binary '1',NULL,'2023-09-21 15:00:41','2023-10-04 16:21:34',NULL,NULL,NULL,NULL,1,NULL),(8,4,'Luan','luan.ydeal@gmail.com','6ebe76c9fb411be97b3b0d48b791a7c9','342.020.040-43','1990-01-01',_binary '1',1,_binary '1','716550fce1c0bff324ac56b861794fab','2023-10-04 10:45:55','2023-10-09 11:45:50',NULL,NULL,NULL,NULL,1,'2023-10-05 16:37:04'),(9,1,'Thiago Fernando de Camargo ','thiagofc972@gmail.com','5a369292ca3dabd0636d954460003696','319.812.828-58','1999-01-01',_binary '1',0,_binary '1',NULL,'2023-10-03 19:10:02',NULL,NULL,NULL,NULL,NULL,1,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'mini4'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-09 14:14:01
