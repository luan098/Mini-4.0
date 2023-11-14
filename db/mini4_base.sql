-- MySQL dump 10.13  Distrib 8.0.35, for Linux (x86_64)
--
-- Host: localhost    Database: mini4
-- ------------------------------------------------------
-- Server version	8.0.35-0ubuntu0.22.04.1

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
-- Table structure for table `_errors`
--

DROP TABLE IF EXISTS `_errors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `_errors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'json_encoded error object',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `created_by_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `_errors_FK` (`created_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_errors`
--

LOCK TABLES `_errors` WRITE;
/*!40000 ALTER TABLE `_errors` DISABLE KEYS */;
/*!40000 ALTER TABLE `_errors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_menu_father` int DEFAULT NULL,
  `name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `page` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` binary(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `menus_FK` (`id_menu_father`),
  CONSTRAINT `menus_FK` FOREIGN KEY (`id_menu_father`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,NULL,'Usuário','','fas fa-users-cog',_binary '1'),(2,NULL,'Configurações','','fas fa-tools',_binary '1'),(3,2,'Sistema','settings','far fa-circle',_binary '1'),(4,1,'Usuários','users','far fa-circle',_binary '1'),(5,1,'Tipos','user-types','far fa-circle',_binary '1');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings_email`
--

DROP TABLE IF EXISTS `settings_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings_email` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `sender_email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `sender_password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `host` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `port` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings_email`
--

LOCK TABLES `settings_email` WRITE;
/*!40000 ALTER TABLE `settings_email` DISABLE KEYS */;
INSERT INTO `settings_email` VALUES (1,'Your Sender Name','yourappemail','yourpassword','mail.yourhost.net.br',587);
/*!40000 ALTER TABLE `settings_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings_general`
--

DROP TABLE IF EXISTS `settings_general`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings_general` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_general_ci,
  `pix_code` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(254) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL COMMENT 'Nome de exibição do arquivo, não precisa da extensão',
  `file` varchar(350) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL COMMENT 'Nome real do arquivo junto com sua extensão, use um nome unico sempre para evitar duplicação de arquivos',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uploaded_files_un` (`file`),
  KEY `uploaded_files_FK` (`created_by`) USING BTREE,
  KEY `uploaded_files_FK_1` (`updated_by`) USING BTREE,
  CONSTRAINT `uploaded_files_FK` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `uploaded_files_FK_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela centralizadora de arquivos, todos as demais telas que tem relacao com upload dos usuarios do sistema fazem relacao com essa';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_type_permissions`
--

DROP TABLE IF EXISTS `user_type_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_type_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `route` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `sub_route` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_user_type` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_type_accesses_FK` (`id_user_type`),
  CONSTRAINT `user_type_accesses_FK` FOREIGN KEY (`id_user_type`) REFERENCES `user_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_type_permissions`
--

LOCK TABLES `user_type_permissions` WRITE;
/*!40000 ALTER TABLE `user_type_permissions` DISABLE KEYS */;
INSERT INTO `user_type_permissions` VALUES (1,'users','index',2),(2,'users','clients',2),(5,'users','edit',2),(6,'users','cover',2);
/*!40000 ALTER TABLE `user_type_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `is_admin` tinyint(1) DEFAULT '0',
  `is_customer` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` VALUES (1,'Administrador',1,1,0),(2,'Supporte',1,0,0),(3,'Cliente',1,0,1);
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user_type` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `temp_password` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `id_upload_cover` int DEFAULT NULL,
  `lock_screen_login_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receive_emails` tinyint(1) DEFAULT '1',
  `spam_prevent_timer` datetime DEFAULT NULL,
  `approved` tinyint(1) DEFAULT '0',
  `terms` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `users_FK` (`id_user_type`),
  KEY `users_FK_3` (`updated_by`),
  KEY `users_FK_2` (`created_by`),
  KEY `users_FK_4` (`id_upload_cover`),
  CONSTRAINT `users_FK` FOREIGN KEY (`id_user_type`) REFERENCES `user_types` (`id`),
  CONSTRAINT `users_FK_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `users_FK_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `users_FK_4` FOREIGN KEY (`id_upload_cover`) REFERENCES `uploads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Admin','admin@email.com.br','25f9e794323b453885f5181f1b624d0b',1,'','2023-09-21 15:00:41','2023-10-20 13:44:44',NULL,NULL,NULL,NULL,1,NULL,1,1),(2,2,'Suporte','suporte@email.com.br','25f9e794323b453885f5181f1b624d0b',1,'','2023-09-21 15:00:41','2023-10-20 13:43:30',NULL,NULL,NULL,'26532ae3229d6b',1,NULL,1,1),(3,3,'Cliente','cliente@email.com.br','25f9e794323b453885f5181f1b624d0b',1,NULL,'2023-09-21 15:00:41','2023-10-19 11:18:38',NULL,NULL,NULL,NULL,0,NULL,1,1),(4,1,'teste','teste@email.com','25f9e794323b453885f5181f1b624d0b',1,'f8383542c5c28439a1439700e0728ad5','2023-10-20 10:46:51','2023-10-20 13:42:29',NULL,NULL,NULL,NULL,1,NULL,0,1);
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

-- Dump completed on 2023-11-14 11:57:54
