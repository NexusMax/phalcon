CREATE DATABASE  IF NOT EXISTS `phalcon_blog` /*!40100 DEFAULT CHARACTER SET ucs2 */;
USE `phalcon_blog`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: phalcon_blog
-- ------------------------------------------------------
-- Server version 5.7.16

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `logo` varchar(255) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=ucs2;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,0,NULL,'category-1','Category 1',1,NULL,NULL),(2,0,NULL,'category-2','Category 2',1,NULL,NULL),(3,0,NULL,'nazvanie','название',1,'2018-07-17 16:35:00','2018-07-17 16:35:00'),(4,0,'default.png','nazvanie2','название2',1,'2018-07-17 16:36:00','2018-07-17 16:36:00');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `text` text,
  `active` int(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,4,1,0,'Комментраий',1,'2018-07-18 09:56:00','2018-07-18 09:56:00'),(2,4,1,1,'Комментраий',1,'2018-07-18 09:57:00','2018-07-18 10:15:00');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
INSERT INTO `galleries` VALUES (3,'Галерея 1',1,'2018-07-26 11:35:00','2018-07-26 16:27:00');
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_item`
--

DROP TABLE IF EXISTS `gallery_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=ucs2;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_item`
--

LOCK TABLES `gallery_item` WRITE;
/*!40000 ALTER TABLE `gallery_item` DISABLE KEYS */;
INSERT INTO `gallery_item` VALUES (4,3,'trener6.jpg',0),(5,3,'7971152.jpg',0),(6,3,'trener6.jpg',0),(7,3,'sc_trener_0089999--1---1--min1531822119.jpg',0);
/*!40000 ALTER TABLE `gallery_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'Меню 1','menyu-1',1,'2018-07-18 16:11:00','2018-07-18 16:11:00');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  `position` int(11) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,1,0,2,1,'Пункт меню','punkt-menyu',1,0,'2018-07-24 12:01:00','2018-07-24 14:42:00'),(2,1,1,2,1,'Пункт меню 2','punkt-menyu-2',1,0,'2018-07-24 17:02:00','2018-07-24 17:02:00');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `text` text,
  `active` int(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Страница 1','stranica-1','<h1 style=\"text-align:center\"><span style=\"color:#27ae60\"><span style=\"font-size:18px\">Страница 1</span></span></h1>\r\n\r\n<ul>\r\n <li><span style=\"color:#27ae60\"><span style=\"font-size:18px\">1</span></span></li>\r\n <li><span style=\"color:#27ae60\"><span style=\"font-size:18px\">2</span></span></li>\r\n <li><span style=\"color:#27ae60\"><span style=\"font-size:18px\">3</span></span></li>\r\n <li><span style=\"color:#27ae60\"><span style=\"font-size:18px\">4</span></span></li>\r\n</ul>\r\n',1,'2018-07-18 15:33:00','2018-07-18 15:33:00'),(2,'Страница 1','stranica-1','<h1 style=\"text-align:center\"><span style=\"color:#27ae60\"><span style=\"font-size:18px\">Страница 1</span></span></h1>\r\n\r\n<ul>\r\n <li><span style=\"color:#27ae60\"><span style=\"font-size:18px\">1</span></span></li>\r\n <li><span style=\"color:#27ae60\"><span style=\"font-size:18px\">2</span></span></li>\r\n <li><span style=\"color:#27ae60\"><span style=\"font-size:18px\">3</span></span></li>\r\n <li><span style=\"color:#27ae60\"><span style=\"font-size:18px\">4</span></span></li>\r\n</ul>\r\n\r\n<p><img alt=\"\" src=\"http://phalcon-blog/assets/img/categories/default.png\" style=\"float:right; height:50px; width:50px\" /></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>{{&quot;id&quot;:&quot;3&quot;, &quot;title&quot;:&quot;Image Gallery&quot;, &quot;class&quot;:&quot;gallery-3&quot;}}</p>\r\n',1,'2018-07-18 15:34:00','2018-07-26 17:25:00');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_has_tag`
--

DROP TABLE IF EXISTS `post_has_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_has_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_has_tag`
--

LOCK TABLES `post_has_tag` WRITE;
/*!40000 ALTER TABLE `post_has_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_has_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `short_description` text,
  `active` int(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,8,2,'default.png','nazvanie','название','полное описание','описание',1,'2018-07-17 14:55:00','2018-07-17 15:16:00');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slider_item`
--

DROP TABLE IF EXISTS `slider_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slider_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slider_id` int(11) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=ucs2;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider_item`
--

LOCK TABLES `slider_item` WRITE;
/*!40000 ALTER TABLE `slider_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `slider_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET ucs2 NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=ucs2;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (10,1,'1','1',1,'2018-07-17 15:16:00','2018-07-17 15:16:00'),(11,1,'2','2',1,'2018-07-17 15:16:00','2018-07-17 15:16:00'),(12,1,'teg','тег',1,'2018-07-17 15:16:00','2018-07-17 15:16:00');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
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
  `surname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `second_email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `company_position` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `second_phone` varchar(255) DEFAULT NULL,
  `gender` int(2) DEFAULT NULL,
  `active` int(2) NOT NULL DEFAULT '1',
  `banned` int(2) NOT NULL DEFAULT '0',
  `email_confirmation` int(2) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL DEFAULT '0',
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'412',NULL,NULL,'admin@admin.admin',NULL,'$2y$12$WmsyL0N3a3VPc1ZERHdLU.SAr6GPSK3syMvGzp9xHx21dAB7pKvyO',NULL,NULL,NULL,NULL,NULL,1,0,0,1,'5ad75399c1bcdd57d5deaed6d53f4033','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'4122',NULL,NULL,'admin2@admin.admin',NULL,'$2y$12$WmsyL0N3a3VPc1ZERHdLU.SAr6GPSK3syMvGzp9xHx21dAB7pKvyO',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'Anton','Litvinov',NULL,'antonlitvino1v14@gmail.com',NULL,'$2y$08$RU1MYVA0bDNsYWR1T0hUUOOEDohz8qh2n7n2u2V8hJqO0kFZdSg5m',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:02:00','2018-06-06 17:02:00'),(5,'2','12',NULL,'antonlitvinov114@gmail.com',NULL,'$2y$08$Z0s1WWxudzltNTBHSWpFV.fkW2CRU8/buC/IuXameSGexmg7UkjB6',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:40:00','2018-06-06 17:40:00'),(6,'werwe','ew',NULL,'antonlitvinov124@gmail.com',NULL,'$2y$08$UXFieUdWYS9zamFyL2dydeB6alYaAqkUZrY8XdJWrBVHUpjZ995Jm',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:40:00','2018-06-06 17:40:00'),(7,'a','a',NULL,'antonlitvino2v14@gmail.com',NULL,'$2y$08$QjhsY1p1OHJWeklPL2I0bOJXzyhwZUiF6N9y6kGddLi6cpvNQqdqa',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:41:00','2018-06-06 17:41:00'),(8,'Anton','a',NULL,'antonlitvinov12144@gmail.com',NULL,'$2y$08$eHplWmpaU0hoRnh6K21zS.KuzHo8NAF.iWgeYpIDL2cE4oe4.saLW',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:42:00','2018-06-06 17:42:00'),(9,'Anton','a',NULL,'antonlitvin1ov14@gmail.com',NULL,'$2y$08$em03M01MT3BmajVqRUZvdO.UjNoqBUIFRHZnl3ARe83nUEvdQlbaO',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:45:00','2018-06-06 17:45:00'),(10,'a','a',NULL,'antonlitvin1ov114@gmail.com',NULL,'$2y$08$Wi9Ub1ZXSXp2ME1kZHFmd.Xp0d8ayjK/YC.7BLy0IUsIVplftSBn6',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:48:00','2018-06-06 17:48:00'),(11,'3','a',NULL,'antonlit12vinov14@gmail.com',NULL,'$2y$08$WXNUQW9hYlpuM1dRSTRWVOCP.AerIdLlIM5s5UHoIAih0FKIGBAN.',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:49:00','2018-06-06 17:49:00'),(12,'Anton','aw',NULL,'antonlitvino12v14@gmail.com',NULL,'$2y$08$bW1pSzBmL2xscEZHaENtYuFEq.uBtUHTg0WS/qH8szmH0ucy6NyXC',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:53:00','2018-06-06 17:53:00'),(13,'Anton','124',NULL,'admin124@admin.admin',NULL,'$2y$08$ZGhLczRHbWJKS2NkMXIzNeljY19QdHlDMkPOTA/lP7/Lpa36yji6.',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:55:00','2018-06-06 17:55:00'),(14,'Видео Психология','a',NULL,'antonlitvi124nov14@gmail.com',NULL,'$2y$08$M3l3dlF2RkpldGJsZlRHM.mlbVZ.NpP0PbUop8zVQqdNkMxpoLNhO',NULL,NULL,NULL,NULL,NULL,1,0,0,0,NULL,'2018-06-06 17:56:00','2018-06-06 17:56:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'phalcon_blog'
--

--
-- Dumping routines for database 'phalcon_blog'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-27 17:26:53
