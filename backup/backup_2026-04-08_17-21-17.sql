-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: azula_store
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `azula_store`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `azula_store` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `azula_store`;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','azula','2026-02-10 00:46:24');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (1,0,0,1,'2026-04-07 06:35:32','2026-04-07 06:35:32'),(3,0,0,1,'2026-04-07 06:37:25','2026-04-07 06:37:25'),(4,0,0,1,'2026-04-07 06:37:26','2026-04-07 06:37:26'),(5,0,0,1,'2026-04-07 06:37:26','2026-04-07 06:37:26'),(6,0,0,1,'2026-04-07 06:37:26','2026-04-07 06:37:26'),(7,5,28,4,'2026-04-08 03:40:29','2026-04-08 12:20:20'),(8,3,28,1,'2026-04-08 04:10:02','2026-04-08 04:10:02');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `officers`
--

DROP TABLE IF EXISTS `officers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `officers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `officers`
--

LOCK TABLES `officers` WRITE;
/*!40000 ALTER TABLE `officers` DISABLE KEYS */;
INSERT INTO `officers` VALUES (5,'','yazid','yazid@gmail.com','12345','active','2026-02-12 00:28:38');
/*!40000 ALTER TABLE `officers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (6,6,28,5,200000),(7,7,28,1,80000),(8,8,28,1,80000),(9,9,28,1,80000),(10,10,28,1,80000),(11,11,28,1,80000),(12,12,28,1,80000),(13,13,28,1,80000),(14,14,28,1,80000),(15,15,28,1,80000),(16,16,28,1,80000),(17,17,28,2,80000),(18,18,28,1,80000),(19,19,28,1,80000),(20,20,28,3,80000),(21,21,28,1,80000),(22,22,28,4,80000);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` text DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `proof` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('pending','accepted') DEFAULT 'pending',
  `expedition_name` varchar(50) DEFAULT NULL,
  `shipping_type` varchar(50) DEFAULT NULL,
  `shipping_cost` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (6,'asd','transfer',1000000,'1775522157_icon#2.png','2026-04-07 02:35:57',3,'pending',NULL,NULL,0),(7,'21','transfer',80000,'1775525552_icon#1.png','2026-04-07 03:32:32',3,'pending',NULL,NULL,0),(8,'jauh','cod',80000,'','2026-04-07 03:45:19',3,'accepted',NULL,NULL,0),(9,'satuan','transfer',80000,'1775530093_ChatGPT Image 6 Apr 2026, 09.50.58.png','2026-04-07 04:48:13',3,'accepted',NULL,NULL,0),(10,'jalan ikhlas nomor 23 tanah baru beji depok','cod',80000,'','2026-04-07 04:55:13',3,'accepted',NULL,NULL,0),(11,'Jl. Ikhlas RT004 RW011 No.23, Tanah Baru Beji Depok','transfer',80000,'1775534331_WhatsApp Image 2026-04-07 at 10.54.58 AM.jpeg','2026-04-07 05:58:51',5,'accepted',NULL,NULL,0),(12,'asddsa','transfer',80000,'1775534704_WhatsApp Image 2026-04-07 at 10.54.58 AM.jpeg','2026-04-07 06:05:04',5,'accepted',NULL,NULL,0),(13,'sdasd','transfer',80000,'1775535361_icon#2.png','2026-04-07 06:16:01',5,'accepted',NULL,NULL,0),(14,'asdasd','cod',80000,'','2026-04-07 06:24:03',5,'accepted',NULL,NULL,0),(15,'Jl. Ikhlas RT004 RW011 No.23, Tanah Baru Beji Depok','transfer',80000,'1775541962_ChatGPT Image 6 Apr 2026, 09.52.52.png','2026-04-07 08:06:02',5,'accepted',NULL,NULL,0),(16,'sadasdasdasd','cod',80000,'','2026-04-07 08:10:17',5,'accepted',NULL,NULL,0),(17,'blabla','cod',160000,'','2026-04-07 08:39:18',5,'accepted',NULL,NULL,0),(18,'asdasd','cod',80000,'','2026-04-07 13:10:23',5,'pending',NULL,NULL,0),(19,'aaaaa','cod',80000,'','2026-04-07 13:24:41',5,'pending',NULL,NULL,0),(20,'asdad','cod',240000,'','2026-04-07 17:18:10',5,'pending',NULL,NULL,0),(21,'Jl. Ikhlas RT004 RW011 No.23, Tanah Baru Beji Depok','transfer',80000,'1775617742_WhatsApp Image 2026-04-07 at 10.54.58 AM.jpeg','2026-04-08 05:09:02',5,'accepted',NULL,NULL,0),(22,'Jl. Ikhlas RT005 RW011 Tanah Baru Beji Depok','cod',335000,'','2026-04-08 16:47:49',5,'pending','JNE','Reguler',15000);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (28,'Mens Premium Quick Dry Polo Collar Shirt Short Sleeve Moisture Wicking Fabric','shirt',100,80000,'1775524970_id-11134207-7ra0g-mdelgydwpxfw7e.png','2026-02-25','Rugby Polo Shirt Pria Wanita Black Gold adalah pilihan sempurna untuk gaya santai yang keren.\r\n\r\nDesain Modern: Kombinasi warna hitam dan cream yang menarik.\r\n\r\nKenyamanan: Terbuat dari bahan berkualitas untuk kenyamanan sehari-hari.\r\n\r\nKesesuaian: Cocok untuk berbagai kesempatan, baik formal maupun informal.\r\n\r\nPadu padan yang ideal untuk tampilan stylish dan nyaman di setiap kesempatan!\r\n\r\nProduk baru Kaos Polo \r\n\r\nSlamat Datang di ARC Apparel\r\n\r\nMaterial yang Kita gunakan\r\n\r\n100% Cotton Premium Anti Bakteri\r\n\r\nKriteria matrial yang tidak menerawang, sangat halus dan lembut tidak menyebabkan iritasi kepada kulit kita, material yang sejuk saat digunakan, menyerap keringat tidak membuat gerah.\r\n\r\nSablon DTF premium\r\n\r\nTekstur yang lembut tidak mudah retak, warna yang solid, gambar yang tajam,warna sablon tidak luntur. finishing sablon doff .\r\n\r\nJahitan rantai pada pundak\r\n\r\noverdeck di tepian tangan dan badan\r\n\r\ndouble stick di bagian Kerah \r\n\r\ndengan demikian membuat pakaian lebih kokoh.\r\n\r\nSize chart\r\n\r\nharap lihat dalam bentuk ukuran centimeter karena setiap postur tubuh berbeda beda walaupun umur sama.\r\n\r\nHarap ukur kembali agar tidak ada kesalahan pemilihan size\r\n\r\nTabel Size ada di Foto\r\n\r\nNB : Jika ada stock yang kosong pasti kita konfirmasi , jadi tidak usah ragu untuk order ya .');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `total_price` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `proof_of_payment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'acit','iniayuma.a@gmail.com','$2y$10$dNRQSb.YwYWyRnhr2UUNdOxNxnpbfzX2VNhIVmUG4lrfjVyx5BV9i','2026-04-07 10:52:35','uploads/avatars/1775615917_ChatGPT Image 6 Apr 2026, 08.32.13.png','Jl. Ikhlas RT005 RW011 Tanah Baru Beji Depok');
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

-- Dump completed on 2026-04-08 22:21:17
