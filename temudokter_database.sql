-- MySQL dump 10.13  Distrib 8.0.44, for macos11.7 (x86_64)
--
-- Host: 127.0.0.1    Database: temudokter
-- ------------------------------------------------------
-- Server version	8.0.44

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Administrator','admin@konsulku.id','$2y$12$8Njt/UawIwfzRBJ0HAinIOX0XRA6Geqh.QYH.6P6ZzGx50D6/qIHa','admins/UrFoX2mfqlO7PpeEMiuUF1qBge8cSYe4qNdglgbn.png',NULL,'2026-06-12 11:31:23','2026-06-16 17:53:12');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultations`
--

DROP TABLE IF EXISTS `consultations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `history_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consultation_status` enum('waiting_payment','waiting_upload','waiting_admin_confirmation','payment_rejected','waiting_assignment','active','completed','cancelled','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting_payment',
  `duration_minutes` smallint unsigned NOT NULL DEFAULT '15',
  `started_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `rating` tinyint DEFAULT NULL,
  `review` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` enum('telemedicine','homecare') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'telemedicine',
  `address` text COLLATE utf8mb4_unicode_ci,
  `homecare_schedule_date` date DEFAULT NULL,
  `homecare_schedule_time` time DEFAULT NULL,
  `price` int unsigned DEFAULT NULL,
  `homecare_report` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `consultations_invoice_number_unique` (`invoice_number`),
  UNIQUE KEY `consultations_history_code_unique` (`history_code`),
  KEY `consultations_patient_id_foreign` (`patient_id`),
  KEY `consultations_doctor_id_foreign` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultations`
--

LOCK TABLES `consultations` WRITE;
/*!40000 ALTER TABLE `consultations` DISABLE KEYS */;
INSERT INTO `consultations` VALUES (3,3,1,'DK1406260001','1A6ABKFD','completed',60,'2026-06-14 11:01:27','2026-06-14 11:16:27','2026-06-14 11:15:17',5,NULL,NULL,'2026-06-14 11:00:45','2026-06-14 11:18:02','homecare','fdsghardfgfhs','2026-06-28','11:00:00',150000,NULL),(4,4,1,'DK1406260002','FQPHWAMZ','completed',15,'2026-06-14 14:52:39','2026-06-14 15:07:39','2026-06-14 15:06:56',5,NULL,NULL,'2026-06-14 14:52:08','2026-06-15 07:08:24','telemedicine',NULL,NULL,NULL,25000,NULL),(5,5,1,'DK1506260001','K4TOQNPQ','completed',15,'2026-06-15 07:15:24','2026-06-15 07:30:24','2026-06-15 07:33:01',5,NULL,NULL,'2026-06-15 07:14:42','2026-06-15 07:33:01','telemedicine',NULL,NULL,NULL,25000,NULL),(6,6,1,'DK1506260002','GTVYP8JI','completed',60,'2026-06-15 07:33:13','2026-06-15 07:48:13','2026-06-15 07:47:39',5,NULL,NULL,'2026-06-15 07:32:33','2026-06-15 08:07:33','homecare','fdsghardfgfhs','2026-06-21','13:00:00',150000,NULL),(7,7,1,'DK1606260001','VMLK9JNG','completed',15,'2026-06-16 08:28:40','2026-06-16 08:43:40','2026-06-16 08:35:57',5,'bagus',NULL,'2026-06-16 08:27:38','2026-06-16 08:35:57','telemedicine',NULL,NULL,NULL,25000,NULL),(8,8,1,'DK1606260002','K28CHGGS','completed',60,'2026-06-16 08:36:50','2026-06-16 08:51:50','2026-06-16 08:51:50',5,NULL,NULL,'2026-06-16 08:34:58','2026-06-16 09:29:01','homecare','fdsghardfgfhs','2026-07-04','11:00:00',150000,NULL),(9,9,1,'DK1606260003','JODN4OVR','completed',60,'2026-06-16 09:30:15','2026-06-16 09:45:15','2026-06-16 10:23:54',NULL,NULL,NULL,'2026-06-16 09:29:44','2026-06-16 10:23:54','homecare','fdsghardfgfhs','2026-06-20','17:00:00',150000,NULL),(10,10,1,'DK1606260004','NIVKVRDU','completed',15,'2026-06-16 16:53:42','2026-06-16 17:08:42','2026-06-16 16:53:54',5,NULL,NULL,'2026-06-16 16:53:03','2026-06-16 16:53:58','telemedicine',NULL,NULL,NULL,25000,NULL),(11,11,NULL,'DK2206260001','2SBFAB6R','payment_rejected',15,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-22 05:29:21','2026-06-22 05:41:07','telemedicine',NULL,NULL,NULL,25000,NULL),(12,12,2,'DK2206260002','P2OBDJXV','completed',15,'2026-06-22 06:06:23','2026-06-22 06:21:23','2026-06-22 06:13:22',5,NULL,NULL,'2026-06-22 05:56:44','2026-06-22 06:13:47','telemedicine',NULL,NULL,NULL,25000,NULL),(13,13,2,'DK2206260003','IUGJO7LG','completed',60,'2026-06-22 06:31:48','2026-06-22 06:46:48','2026-06-22 06:44:56',5,NULL,NULL,'2026-06-22 06:30:25','2026-06-22 06:45:04','homecare','fdsghardfgfhs','2026-07-11','11:00:00',150000,'kacau dah parahhh');
/*!40000 ALTER TABLE `consultations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dokter Umum',
  `experience_years` int NOT NULL DEFAULT '0',
  `practice_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `str_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sip_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('online','offline','busy','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'offline',
  `bio` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doctors_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctors`
--

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` VALUES (1,'Dr. Andi Wijaya, Sp.PD','andi@konsulku.id','$2y$12$HT8f4FKH6hL4htjSJNHjaeE3YqH7IkoTVn0jE7.CI4JLNAv1HqHq6','Dokter Spesialis Penyakit Dalam',0,'RS Harapan Sehat, Bekasi Barat',NULL,NULL,'12345/SIP/DPMPTSP/2026','08121234001',NULL,'online','Dokter spesialis penyakit dalam dengan pengalaman 10 tahun di wilayah Bekasi.',NULL,'2026-06-12 11:31:23','2026-06-16 16:53:54',NULL),(2,'Dr. Sari Putri, Sp.A','sari@konsulku.id','$2y$12$ma77aPaEIUH3Kt0duaMsQOCCC7GA09zrCPwFqmp.vKxOQwm6eUIJ6','Dokter Spesialis Anak',0,'Praktek dr Umum','Fk upn veteran jakarta',NULL,'503/A.1/ 0044 DU/35.09.325/2024','08121234002','doctors/oN4RhKxIlA645PZuyt0l6dnMYVgUOCu9CAVI2JjR.jpg','online','Dokter spesialis anak dengan pengalaman 8 tahun dan fokus pada kesehatan pediatrik.',NULL,'2026-06-12 11:31:23','2026-06-22 06:44:56',NULL),(3,'Dr. Budi Santoso','budi@konsulku.id','$2y$12$4LVVRIItl/3G7CpIlpbk5.bg.gTpK307JI/qoVMIE2qgPnBWGJ/7i','Dokter Umum',0,NULL,NULL,NULL,NULL,'08121234003',NULL,'offline','Dokter umum dengan pendekatan holistik dan berpengalaman di Bekasi Timur.',NULL,'2026-06-12 11:31:24','2026-06-12 11:31:24',NULL),(4,'Dr. Rini Kusuma, Sp.OG','rini@konsulku.id','$2y$12$CbD6qjAZ3PvMy22iST0sTeK9oGm6X93wNsJzb6RkXG1ThzbnsyGVm','Dokter Spesialis Kandungan',0,NULL,NULL,NULL,NULL,'08121234004',NULL,'offline','Dokter spesialis obstetri dan ginekologi untuk kesehatan perempuan.',NULL,'2026-06-12 11:31:24','2026-06-12 11:31:24',NULL);
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `homecare_blocks`
--

DROP TABLE IF EXISTS `homecare_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `homecare_blocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('block','open') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'block',
  `date` date NOT NULL,
  `time` time DEFAULT NULL COMMENT 'If null, the entire day is blocked',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `homecare_blocks`
--

LOCK TABLES `homecare_blocks` WRITE;
/*!40000 ALTER TABLE `homecare_blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `homecare_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicines`
--

DROP TABLE IF EXISTS `medicines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicines`
--

LOCK TABLES `medicines` WRITE;
/*!40000 ALTER TABLE `medicines` DISABLE KEYS */;
INSERT INTO `medicines` VALUES (1,'sanmol',NULL,'2026-06-14 08:48:59','2026-06-14 08:48:59',NULL),(2,'sangobion',NULL,'2026-06-14 08:49:06','2026-06-14 08:49:06',NULL),(3,'amoxilin',NULL,'2026-06-14 08:49:13','2026-06-14 08:49:13',NULL),(4,'ethanol',NULL,'2026-06-14 08:49:19','2026-06-14 08:49:19',NULL);
/*!40000 ALTER TABLE `medicines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `sender_type` enum('patient','doctor','admin','system') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint unsigned DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_type` enum('image','pdf','none') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_consultation_id_foreign` (`consultation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (13,3,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-14 11:01:20','2026-06-14 11:01:20'),(14,3,'system',NULL,'Dr. Dr. Andi Wijaya, Sp.PD telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-14 11:01:27','2026-06-14 11:01:27'),(15,3,'doctor',1,'Halo, saya dr. Dr. Andi Wijaya, Sp.PD yang ditugaskan. Saya sedang bersiap menuju rumah Anda.',NULL,'none',0,'2026-06-14 11:01:41','2026-06-14 11:01:41'),(16,3,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260001.pdf','pdf',0,'2026-06-14 11:02:13','2026-06-14 11:02:13'),(17,3,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260001.pdf','pdf',0,'2026-06-14 11:08:43','2026-06-14 11:08:43'),(18,3,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260001.pdf','pdf',0,'2026-06-14 11:11:41','2026-06-14 11:11:41'),(19,3,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260001.pdf','pdf',0,'2026-06-14 11:14:56','2026-06-14 11:14:56'),(20,3,'system',NULL,'Konsultasi telah diakhiri oleh dokter.',NULL,'none',0,'2026-06-14 11:15:17','2026-06-14 11:15:17'),(21,4,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-14 14:52:31','2026-06-14 14:52:31'),(22,4,'system',NULL,'Dr. Dr. Andi Wijaya, Sp.PD telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-14 14:52:39','2026-06-14 14:52:39'),(23,4,'doctor',1,'Halo, saya dr. Dr. Andi Wijaya, Sp.PD. Terima kasih telah bergabung dalam konsultasi online ini. Silakan ceritakan keluhan atau pertanyaan yang ingin Anda konsultasikan hari ini. Saya siap membantu dan memberikan informasi medis sesuai dengan kondisi yang Anda sampaikan.',NULL,'none',0,'2026-06-14 14:52:39','2026-06-14 14:52:39'),(24,4,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260002.pdf','pdf',0,'2026-06-14 14:52:57','2026-06-14 14:52:57'),(25,4,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260002.pdf','pdf',0,'2026-06-14 14:56:09','2026-06-14 14:56:09'),(26,4,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260002.pdf','pdf',0,'2026-06-14 14:58:15','2026-06-14 14:58:15'),(27,4,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260002.pdf','pdf',0,'2026-06-14 15:00:44','2026-06-14 15:00:44'),(28,4,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260002.pdf','pdf',0,'2026-06-14 15:05:05','2026-06-14 15:05:05'),(29,4,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1406260002.pdf','pdf',0,'2026-06-14 15:06:39','2026-06-14 15:06:39'),(30,4,'system',NULL,'Konsultasi telah diakhiri oleh dokter.',NULL,'none',0,'2026-06-14 15:06:56','2026-06-14 15:06:56'),(31,5,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-15 07:15:19','2026-06-15 07:15:19'),(32,5,'system',NULL,'Dr. Dr. Andi Wijaya, Sp.PD telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-15 07:15:24','2026-06-15 07:15:24'),(33,5,'doctor',1,'Halo, saya dr. Dr. Andi Wijaya, Sp.PD. Terima kasih telah bergabung dalam konsultasi online ini. Silakan ceritakan keluhan atau pertanyaan yang ingin Anda konsultasikan hari ini. Saya siap membantu dan memberikan informasi medis sesuai dengan kondisi yang Anda sampaikan.',NULL,'none',0,'2026-06-15 07:15:24','2026-06-15 07:15:24'),(34,5,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260001.pdf','pdf',0,'2026-06-15 07:15:40','2026-06-15 07:15:40'),(35,5,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260001.pdf','pdf',0,'2026-06-15 07:20:16','2026-06-15 07:20:16'),(36,5,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260001.pdf','pdf',0,'2026-06-15 07:21:16','2026-06-15 07:21:16'),(37,5,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260001.pdf','pdf',0,'2026-06-15 07:26:41','2026-06-15 07:26:41'),(38,5,'system',NULL,'Konsultasi telah diakhiri oleh dokter.',NULL,'none',0,'2026-06-15 07:28:14','2026-06-15 07:28:14'),(39,5,'system',NULL,'Konsultasi telah diakhiri oleh admin.',NULL,'none',0,'2026-06-15 07:33:01','2026-06-15 07:33:01'),(40,6,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-15 07:33:08','2026-06-15 07:33:08'),(41,6,'system',NULL,'Dr. Dr. Andi Wijaya, Sp.PD telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-15 07:33:13','2026-06-15 07:33:13'),(42,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 07:33:34','2026-06-15 07:33:34'),(43,6,'system',NULL,'Konsultasi telah diakhiri oleh dokter.',NULL,'none',0,'2026-06-15 07:47:39','2026-06-15 07:47:39'),(44,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 07:47:49','2026-06-15 07:47:49'),(45,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 07:50:42','2026-06-15 07:50:42'),(46,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 07:51:22','2026-06-15 07:51:22'),(47,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 07:57:23','2026-06-15 07:57:23'),(48,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 10:24:01','2026-06-15 10:24:01'),(49,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 10:50:07','2026-06-15 10:50:07'),(50,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 10:58:16','2026-06-15 10:58:16'),(51,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 11:00:12','2026-06-15 11:00:12'),(52,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 11:01:57','2026-06-15 11:01:57'),(53,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 11:04:38','2026-06-15 11:04:38'),(54,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 11:10:13','2026-06-15 11:10:13'),(55,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 11:12:22','2026-06-15 11:12:22'),(56,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 11:17:45','2026-06-15 11:17:45'),(57,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 11:19:11','2026-06-15 11:19:11'),(58,6,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1506260002.pdf','pdf',0,'2026-06-15 11:24:42','2026-06-15 11:24:42'),(59,6,'doctor',1,'Resep obat telah diterbitkan. Silakan unduh resep obat Anda.','prescriptions/resep_DK1506260002.pdf','pdf',0,'2026-06-15 14:49:17','2026-06-15 14:49:17'),(60,6,'doctor',1,'Resep obat telah diterbitkan. Silakan unduh resep obat Anda.','prescriptions/resep_DK1506260002.pdf','pdf',0,'2026-06-15 14:53:48','2026-06-15 14:53:48'),(61,6,'doctor',1,'Resep obat telah diterbitkan. Silakan unduh resep obat Anda.','prescriptions/resep_DK1506260002.pdf','pdf',0,'2026-06-15 15:00:50','2026-06-15 15:00:50'),(62,7,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-16 08:28:30','2026-06-16 08:28:30'),(63,7,'system',NULL,'Dr. Dr. Andi Wijaya, Sp.PD telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-16 08:28:40','2026-06-16 08:28:40'),(64,7,'doctor',1,'Halo, saya dr. Dr. Andi Wijaya, Sp.PD. Terima kasih telah bergabung dalam konsultasi online ini. Silakan ceritakan keluhan atau pertanyaan yang ingin Anda konsultasikan hari ini. Saya siap membantu dan memberikan informasi medis sesuai dengan kondisi yang Anda sampaikan.',NULL,'none',0,'2026-06-16 08:28:40','2026-06-16 08:28:40'),(65,7,'doctor',1,'Resep obat telah diterbitkan. Silakan unduh resep obat Anda.','prescriptions/resep_DK1606260001.pdf','pdf',0,'2026-06-16 08:29:42','2026-06-16 08:29:42'),(66,7,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1606260001.pdf','pdf',0,'2026-06-16 08:31:01','2026-06-16 08:31:01'),(67,7,'system',NULL,'Konsultasi telah diakhiri oleh dokter.',NULL,'none',0,'2026-06-16 08:31:44','2026-06-16 08:31:44'),(68,7,'system',NULL,'Konsultasi telah diakhiri oleh admin.',NULL,'none',0,'2026-06-16 08:35:57','2026-06-16 08:35:57'),(69,8,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-16 08:36:10','2026-06-16 08:36:10'),(70,8,'system',NULL,'Dr. Dr. Andi Wijaya, Sp.PD telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-16 08:36:50','2026-06-16 08:36:50'),(71,8,'doctor',1,'Halo, saya dr. Dr. Andi Wijaya, Sp.PD yang ditugaskan. Saya sedang bersiap menuju rumah Anda.',NULL,'none',0,'2026-06-16 08:37:09','2026-06-16 08:37:09'),(72,8,'doctor',1,'Saya dr. Dr. Andi Wijaya, Sp.PD sudah sampai di lokasi rumah Anda, mohon konfirmasinya.',NULL,'none',0,'2026-06-16 08:37:25','2026-06-16 08:37:25'),(73,8,'doctor',1,'Resep obat telah diterbitkan. Silakan unduh resep obat Anda.','prescriptions/resep_DK1606260002.pdf','pdf',0,'2026-06-16 08:38:30','2026-06-16 08:38:30'),(74,8,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1606260002.pdf','pdf',0,'2026-06-16 08:40:12','2026-06-16 08:40:12'),(75,8,'system',NULL,'Konsultasi telah diakhiri oleh dokter.',NULL,'none',0,'2026-06-16 08:51:50','2026-06-16 08:51:50'),(76,9,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-16 09:30:08','2026-06-16 09:30:08'),(77,9,'system',NULL,'Dr. Dr. Andi Wijaya, Sp.PD telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-16 09:30:16','2026-06-16 09:30:16'),(78,9,'doctor',1,'🚗 Halo, saya dr. Dr. Andi Wijaya, Sp.PD yang ditugaskan. Saya sedang bersiap menuju rumah Anda.',NULL,'none',0,'2026-06-16 09:30:27','2026-06-16 09:30:27'),(79,9,'doctor',1,'📍 Saya Sedang Ditindak lanjuti di lokasi rumah Anda.',NULL,'none',0,'2026-06-16 09:32:20','2026-06-16 09:32:20'),(80,9,'doctor',1,'📍 Pasien sedang di tindak lanjuti oleh dokter.',NULL,'none',0,'2026-06-16 10:07:17','2026-06-16 10:07:17'),(81,9,'doctor',1,'Resep obat telah diterbitkan. Silakan unduh resep obat Anda.','prescriptions/resep_DK1606260003.pdf','pdf',0,'2026-06-16 10:07:39','2026-06-16 10:07:39'),(82,9,'system',NULL,'Konsultasi telah diakhiri oleh admin.',NULL,'none',0,'2026-06-16 10:23:54','2026-06-16 10:23:54'),(83,10,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-16 16:53:31','2026-06-16 16:53:31'),(84,10,'system',NULL,'Dr. Dr. Andi Wijaya, Sp.PD telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-16 16:53:42','2026-06-16 16:53:42'),(85,10,'doctor',1,'Halo, saya dr. Dr. Andi Wijaya, Sp.PD. Terima kasih telah bergabung dalam konsultasi online ini. Silakan ceritakan keluhan atau pertanyaan yang ingin Anda konsultasikan hari ini. Saya siap membantu dan memberikan informasi medis sesuai dengan kondisi yang Anda sampaikan.',NULL,'none',0,'2026-06-16 16:53:42','2026-06-16 16:53:42'),(86,10,'system',NULL,'Konsultasi telah diakhiri oleh dokter.',NULL,'none',0,'2026-06-16 16:53:54','2026-06-16 16:53:54'),(87,10,'doctor',1,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK1606260004.pdf','pdf',0,'2026-06-16 17:07:26','2026-06-16 17:07:26'),(88,12,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-22 06:03:42','2026-06-22 06:03:42'),(89,12,'system',NULL,'Dr. Dr. Sari Putri, Sp.A telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-22 06:06:23','2026-06-22 06:06:23'),(90,12,'doctor',2,'Halo, saya dr. Dr. Sari Putri, Sp.A. Terima kasih telah bergabung dalam konsultasi online ini. Silakan ceritakan keluhan atau pertanyaan yang ingin Anda konsultasikan hari ini. Saya siap membantu dan memberikan informasi medis sesuai dengan kondisi yang Anda sampaikan.',NULL,'none',0,'2026-06-22 06:06:23','2026-06-22 06:06:23'),(91,12,'doctor',2,'Resep obat telah diterbitkan. Silakan unduh resep obat Anda.','prescriptions/resep_DK2206260002.pdf','pdf',0,'2026-06-22 06:10:47','2026-06-22 06:10:47'),(92,12,'doctor',2,'Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.','sick_leaves/surat_sakit_DK2206260002.pdf','pdf',0,'2026-06-22 06:12:43','2026-06-22 06:12:43'),(93,12,'doctor',2,'halo saya dokter',NULL,'none',0,'2026-06-22 06:13:12','2026-06-22 06:13:12'),(94,12,'system',NULL,'Konsultasi telah diakhiri oleh dokter.',NULL,'none',0,'2026-06-22 06:13:22','2026-06-22 06:13:22'),(95,13,'system',NULL,'Pembayaran telah diverifikasi. Menunggu penugasan dokter.',NULL,'none',0,'2026-06-22 06:31:37','2026-06-22 06:31:37'),(96,13,'system',NULL,'Dr. Dr. Sari Putri, Sp.A telah bergabung. Konsultasi dimulai. Waktu konsultasi 15 menit.',NULL,'none',0,'2026-06-22 06:31:48','2026-06-22 06:31:48'),(97,13,'doctor',2,'🚗 Halo, saya dr. Dr. Sari Putri, Sp.A yang ditugaskan. Saya sedang bersiap menuju rumah Anda.',NULL,'none',0,'2026-06-22 06:31:58','2026-06-22 06:31:58'),(98,13,'doctor',2,'📍 Pasien sedang di tindak lanjuti oleh dokter.',NULL,'none',0,'2026-06-22 06:32:08','2026-06-22 06:32:08'),(99,13,'patient',NULL,'alert(\'Hacked\'). Si',NULL,'none',0,'2026-06-22 06:34:54','2026-06-22 06:34:54'),(100,13,'doctor',2,'alert(\'Hacked\')',NULL,'none',0,'2026-06-22 06:35:26','2026-06-22 06:35:26'),(101,13,'doctor',2,'Laporan Kunjungan Homecare telah ditambahkan oleh dokter.',NULL,'none',0,'2026-06-22 06:44:45','2026-06-22 06:44:45'),(102,13,'doctor',2,'✅ Tindakan medis telah selesai dilakukan. Terima kasih.',NULL,'none',0,'2026-06-22 06:44:55','2026-06-22 06:44:55'),(103,13,'system',NULL,'Konsultasi telah diakhiri oleh dokter.',NULL,'none',0,'2026-06-22 06:44:56','2026-06-22 06:44:56');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_01_000001_create_admins_table',1),(5,'2026_01_01_000002_create_doctors_table',1),(6,'2026_01_01_000003_create_patients_table',1),(7,'2026_01_01_000004_create_consultations_table',1),(8,'2026_01_01_000005_create_transactions_table',1),(9,'2026_01_01_000006_create_payment_sessions_table',1),(10,'2026_01_01_000007_create_payment_proofs_table',1),(11,'2026_01_01_000008_create_messages_table',1),(12,'2026_01_01_000009_create_prescriptions_table',1),(13,'2026_05_28_183220_add_drug_allergies_to_patients_table',1),(14,'2026_05_29_190203_add_homecare_fields_to_consultations_table',1),(15,'2026_05_29_201749_create_homecare_blocks_table',1),(16,'2026_06_01_172113_add_address_fields_to_patients_table',1),(17,'2026_06_05_162844_create_medicines_table',1),(18,'2026_06_05_162844_create_prescription_items_table',1),(19,'2026_06_05_162845_create_sick_leaves_table',1),(20,'2026_06_05_173618_alter_prescription_items_columns',1),(21,'2026_06_05_181617_add_email_to_patients_table',1),(22,'2026_06_06_131849_add_type_to_homecare_blocks_table',1),(23,'2026_06_06_152551_add_survey_to_consultations_table',1),(24,'2026_06_06_155521_alter_payment_method_on_transactions_table',1),(25,'2026_06_08_163714_add_profile_fields_to_doctors_table',1),(26,'2026_06_08_172023_create_settings_table',1),(27,'2026_06_08_221654_fix_prescription_items_columns',1),(28,'2026_06_08_222846_add_occupation_to_patients_table',1),(29,'2026_06_08_231725_add_history_code_to_consultations_table',1),(30,'2026_06_12_184156_add_photo_to_admins_table',2),(31,'2026_06_15_215702_add_kegunaan_to_prescription_items_table',3),(32,'2026_06_17_011103_add_deleted_at_to_master_tables',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` tinyint unsigned NOT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('laki-laki','perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `village` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rt_rw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bekasi_area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complaint_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `drug_allergies` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tidak diketahui',
  `medical_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_session_token_unique` (`session_token`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,'alif wardana','alifwrdhh111@gmail.com','85892501302',23,'Pegawai Negeri','laki-laki','Jawa Barat','Kota Bekasi','Bekasi Selatan','Kayuringin Jaya','-','Bekasi Selatan','iwh1uihmzdgifgsiuzfiufs fdsiNSFDZPYSTDUIZNSTXyifgudnysfotdsiyftsauif','Tidak ada alergi obat',NULL,NULL,'61eabaaf-3914-4791-b749-031571ebce62','2026-06-14 08:47:07','2026-06-14 08:47:07',NULL),(2,'alif wardana','alifwrdhh111@gmail.com','85892501302',23,'Pegawai Negeri','laki-laki','JAWA BARAT','KABUPATEN BEKASI','TAMBUN SELATAN','TAMBUN','fdsghardfgfhs',NULL,'hviuhniudsiuvguidgmiciugh,fioucfhdgmoihdgcuhmigohoifghiugmuhgioghvmiuhgiuvndfypgi','Tidak ada alergi obat',NULL,NULL,'d79b600d-1694-4278-aca3-b99a9ecc25af','2026-06-14 09:32:05','2026-06-14 09:32:05',NULL),(3,'alif wardana','alifwrdhh111@gmail.com','85892501302',23,'Pegawai Negeri','perempuan','Jawa Barat','Kota Bekasi','Bekasi Barat','Jakasampurna','-','Bekasi Barat','hefdimfxhui gdnuivgyidgmiuggdcugmvcjhgvgvnjfhvmcghvhgvkahjvgjhg','Tidak ada alergi obat',NULL,NULL,'218d3a33-bb74-4447-a06a-97f0db7e63ba','2026-06-14 11:00:45','2026-06-14 11:00:45',NULL),(4,'alif wardana','alifwrdhh111@gmail.com','85892501302',23,'-','laki-laki','DKI JAKARTA','KOTA JAKARTA TIMUR','CIRACAS','CIRACAS','fdsghardfgfhs',NULL,'hiufgsadyoufgagmfisgmdiugfibygdnjkgjshfdg','Tidak ada alergi obat',NULL,NULL,'65e07148-b621-4783-aa86-bf4261d7db16','2026-06-14 14:52:08','2026-06-14 14:52:08',NULL),(5,'alif wardana','alifwrdhh111@gmail.com','85892501302',23,'-','laki-laki','JAWA BARAT','KOTA BEKASI','PONDOKMELATI','JATIMELATI','fdsghardfgfhs',NULL,'ygnfcdgsyivgydigfmdfidgfynsdyfvouxvufmydfgirydsinusgufid','Tidak ada alergi obat',NULL,NULL,'b0455449-0386-4345-a864-8df5a9e6b104','2026-06-15 07:14:42','2026-06-15 07:14:42',NULL),(6,'alif wardana','alifwrdhh111@gmail.com','85892501302',25,'-','laki-laki','Jawa Barat','Kota Bekasi','Bekasi Selatan','Pekayon Jaya','-','Bekasi Selatan','shgfnysdgouifgdsbfgndsfgdhjsfvdhsjlgbfudsgnfdhksbf','Tidak ada alergi obat',NULL,NULL,'40a13fc8-7985-4307-ad2d-66659a637ad6','2026-06-15 07:32:33','2026-06-15 07:32:33',NULL),(7,'alif wardana','alifwrdhh111@gmail.com','85892501302',23,'PNS','laki-laki','JAWA BARAT','KOTA BEKASI','PONDOKMELATI','JATIWARNA','fdsghardfgfhs',NULL,'sakit perut dan iritasi kulit','Tidak ada alergi obat',NULL,NULL,'5fbc96dc-996a-4afd-a47b-ac19a0d90710','2026-06-16 08:27:38','2026-06-16 08:27:38',NULL),(8,'alif wardana','alifwrdhh111@gmail.com','85892501302',24,'-','laki-laki','Jawa Barat','Kota Bekasi','Bekasi Barat','Bintara','-','Bekasi Barat','sakit lumpu total dan sakit kepala','Tidak ada alergi obat',NULL,NULL,'04a8dd84-de92-4897-bec5-a0df63d26a56','2026-06-16 08:34:58','2026-06-16 08:34:58',NULL),(9,'alif wardana','alifwrdhh111@gmail.com','85892501302',25,'-','perempuan','Jawa Barat','Kota Bekasi','Bekasi Timur','Margahayu','-','Bekasi Timur','uigfiucgnfgomdgsifcgdnsfgvidgfniuscofmcg','Tidak ada alergi obat',NULL,NULL,'06ad4cb8-6a56-4276-928b-0e68571908f6','2026-06-16 09:29:44','2026-06-16 09:29:44',NULL),(10,'alif wardana','alifwrdhh111@gmail.com','85892501302',27,'-','laki-laki','JAWA BARAT','KOTA BEKASI','JATIASIH','JATIKRAMAT','fdsghardfgfhs',NULL,'vdbyfnyfbxcyrdbiyftiyfbtfyunfyvghvcdxturdytbghnjkm','Tidak ada alergi obat',NULL,NULL,'9b958778-b689-4b31-a1ad-7c81c7d2d4aa','2026-06-16 16:53:03','2026-06-16 16:53:03',NULL),(11,'alif wardana','alifwrdhh111@gmail.com','85892501302',23,'-','laki-laki','JAWA BARAT','KOTA BEKASI','JATISAMPURNA','JATIKARYA','fdsghardfgfhs',NULL,'sakit hati ga bisa di ubaran sampe nyesek','gatel gatel','medical-documents/65ebc490-9a27-499f-9aba-d6db3e9c607c.png','medical-documents/fe6be4a2-d5bb-4363-aa13-52261b1894dd.png','f4ea8d2c-f340-4e3f-acc2-dbb9a27d7d04','2026-06-22 05:29:21','2026-06-22 05:29:21',NULL),(12,'alif wardana','alifwrdhh111@gmail.com','85892501302',23,'-','laki-laki','JAWA BARAT','KOTA DEPOK','BEJI','KEMIRIMUKA','fdsghardfgfhs',NULL,'sakit banget parah nyesek asli','gatel gatel banget','medical-documents/96b15802-67f6-448b-b929-f94fe301258e.jpg','medical-documents/38c650b7-a561-459a-aefc-c452c9c36320.png','c7d67123-718b-4edb-8b3d-1a1e8c9f91ef','2026-06-22 05:56:44','2026-06-22 05:56:44',NULL),(13,'alif wardana','alifwrdhh111@gmail.com','81292501703',24,'Pegawai Negeri','laki-laki','Jawa Barat','Kota Bekasi','Bekasi Selatan','Jakasetia','-','Bekasi Selatan','sakit hati ga bisa di ubaran euy','gatel gatel wae','medical-documents/1349f905-4a22-4874-9bf3-f729b431bfe4.png',NULL,'1a57cae9-53a3-40b8-b1a4-569f482fa84e','2026-06-22 06:30:25','2026-06-22 06:30:25',NULL);
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_proofs`
--

DROP TABLE IF EXISTS `payment_proofs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_proofs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','request_reupload') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_proofs_transaction_id_foreign` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_proofs`
--

LOCK TABLES `payment_proofs` WRITE;
/*!40000 ALTER TABLE `payment_proofs` DISABLE KEYS */;
INSERT INTO `payment_proofs` VALUES (3,3,'payment-proofs/7366ab42-5e32-4178-b0e1-271159f89e99.png','image',NULL,'approved',NULL,'2026-06-14 11:01:02','2026-06-14 11:01:20'),(4,4,'payment-proofs/0ab994e2-fdc7-44c8-85ad-8a88fadfc9df.png','image',NULL,'approved',NULL,'2026-06-14 14:52:18','2026-06-14 14:52:31'),(5,5,'payment-proofs/70cccb13-42b9-4539-b861-c7f4a1eb2b1a.png','image',NULL,'approved',NULL,'2026-06-15 07:14:51','2026-06-15 07:15:19'),(6,6,'payment-proofs/79696977-bc2c-4559-bece-35e7d4a8a974.png','image',NULL,'approved',NULL,'2026-06-15 07:32:50','2026-06-15 07:33:08'),(7,7,'payment-proofs/1688b237-5189-47ce-b38f-22175d5601c1.png','image',NULL,'approved',NULL,'2026-06-16 08:27:55','2026-06-16 08:28:30'),(8,8,'payment-proofs/671fd379-c642-4d8a-af41-0cd214f7466e.png','image',NULL,'approved',NULL,'2026-06-16 08:35:36','2026-06-16 08:36:10'),(9,9,'payment-proofs/03c71c08-498a-47a1-9e01-25c2ce096f0f.png','image',NULL,'approved',NULL,'2026-06-16 09:30:00','2026-06-16 09:30:08'),(10,10,'payment-proofs/279af342-0454-428b-a9c7-75c70a444b30.png','image',NULL,'approved',NULL,'2026-06-16 16:53:12','2026-06-16 16:53:31'),(11,11,'payment-proofs/3ea581e6-6130-478d-9808-d489fc19ca24.jpg','image','transfer Pak budi','rejected','kurang jelas','2026-06-22 05:32:22','2026-06-22 05:41:07'),(12,12,'payment-proofs/1abfd6ca-19f4-4168-990c-1b0fec578c56.jpg','image','Pak hartono','approved',NULL,'2026-06-22 05:57:08','2026-06-22 06:03:42'),(13,13,'payment-proofs/50128bd5-13a1-4560-85ec-17ebf74c3b82.png','image',NULL,'approved',NULL,'2026-06-22 06:31:22','2026-06-22 06:31:37');
/*!40000 ALTER TABLE `payment_proofs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_sessions`
--

DROP TABLE IF EXISTS `payment_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `method` enum('qris','virtual_account','ewallet') COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `simulated_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` timestamp NOT NULL,
  `status` enum('active','expired','used') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_sessions_transaction_id_foreign` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_sessions`
--

LOCK TABLES `payment_sessions` WRITE;
/*!40000 ALTER TABLE `payment_sessions` DISABLE KEYS */;
INSERT INTO `payment_sessions` VALUES (1,12,'qris','QRIS','QRIS-7520FFC2','2026-06-22 06:56:52','expired','2026-06-22 05:56:52','2026-06-22 06:00:33'),(2,13,'qris','QRIS','QRIS-4667B95C','2026-06-22 07:31:12','active','2026-06-22 06:31:12','2026-06-22 06:31:12');
/*!40000 ALTER TABLE `payment_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescription_items`
--

DROP TABLE IF EXISTS `prescription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prescription_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned DEFAULT NULL,
  `medicine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kegunaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `instructions` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prescription_items_prescription_id_foreign` (`prescription_id`),
  KEY `prescription_items_medicine_id_foreign` (`medicine_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescription_items`
--

LOCK TABLES `prescription_items` WRITE;
/*!40000 ALTER TABLE `prescription_items` DISABLE KEYS */;
INSERT INTO `prescription_items` VALUES (15,2,1,'sanmol','sakit kepala',NULL,2,'3x1','habiskan','2026-06-15 15:00:48','2026-06-15 15:00:48'),(16,2,2,'sangobion','penambah darah',NULL,2,'4x1','habiskan','2026-06-15 15:00:48','2026-06-15 15:00:48'),(17,3,1,'sanmol','sakit kepala',NULL,10,'3x1','habiskan','2026-06-16 08:29:40','2026-06-16 08:29:40'),(18,4,3,'amoxilin','meredakan sakit kepala',NULL,10,'3x1','habiskan','2026-06-16 08:38:28','2026-06-16 08:38:28'),(19,5,1,'sanmol','4',NULL,10,'54',NULL,'2026-06-16 10:07:37','2026-06-16 10:07:37'),(20,6,1,'sanmol','sakit kepala',NULL,10,'3x1','habiskan','2026-06-22 06:10:45','2026-06-22 06:10:45'),(21,6,4,'ethanol','meredakan gila',NULL,2,'2x1','habiskan','2026-06-22 06:10:45','2026-06-22 06:10:45'),(22,6,3,'amoxilin','meredakan duit',NULL,10,'5x1','habiskan','2026-06-22 06:10:45','2026-06-22 06:10:45');
/*!40000 ALTER TABLE `prescription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prescriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prescriptions_consultation_id_foreign` (`consultation_id`),
  KEY `prescriptions_doctor_id_foreign` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescriptions`
--

LOCK TABLES `prescriptions` WRITE;
/*!40000 ALTER TABLE `prescriptions` DISABLE KEYS */;
INSERT INTO `prescriptions` VALUES (2,6,1,NULL,'prescriptions/resep_DK1506260002.pdf','pdf','2026-06-15 14:49:16','2026-06-15 14:49:17'),(3,7,1,NULL,'prescriptions/resep_DK1606260001.pdf','pdf','2026-06-16 08:29:40','2026-06-16 08:29:42'),(4,8,1,NULL,'prescriptions/resep_DK1606260002.pdf','pdf','2026-06-16 08:38:28','2026-06-16 08:38:30'),(5,9,1,NULL,'prescriptions/resep_DK1606260003.pdf','pdf','2026-06-16 10:07:37','2026-06-16 10:07:39'),(6,12,2,NULL,'prescriptions/resep_DK2206260002.pdf','pdf','2026-06-22 06:10:45','2026-06-22 06:10:47');
/*!40000 ALTER TABLE `prescriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sick_leaves`
--

DROP TABLE IF EXISTS `sick_leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sick_leaves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sick_leaves_consultation_id_foreign` (`consultation_id`),
  KEY `sick_leaves_doctor_id_foreign` (`doctor_id`),
  KEY `sick_leaves_patient_id_foreign` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sick_leaves`
--

LOCK TABLES `sick_leaves` WRITE;
/*!40000 ALTER TABLE `sick_leaves` DISABLE KEYS */;
INSERT INTO `sick_leaves` VALUES (2,3,1,3,'2026-06-16','2026-06-16','Mati total','sick_leaves/surat_sakit_DK1406260001.pdf','2026-06-14 11:02:13','2026-06-14 11:02:13'),(3,3,1,3,'2026-06-16','2026-06-16','sakit total','sick_leaves/surat_sakit_DK1406260001.pdf','2026-06-14 11:08:43','2026-06-14 11:08:43'),(4,3,1,3,'2026-06-16','2026-06-16','mati total','sick_leaves/surat_sakit_DK1406260001.pdf','2026-06-14 11:11:41','2026-06-14 11:11:41'),(5,3,1,3,'2026-06-16','2026-06-16','mati total','sick_leaves/surat_sakit_DK1406260001.pdf','2026-06-14 11:14:56','2026-06-14 11:14:56'),(6,4,1,4,'2026-06-17','2026-06-17','sakit parah','sick_leaves/surat_sakit_DK1406260002.pdf','2026-06-14 14:52:57','2026-06-14 14:52:57'),(7,4,1,4,'2026-06-17','2026-06-23','sakit mati','sick_leaves/surat_sakit_DK1406260002.pdf','2026-06-14 14:56:09','2026-06-14 14:56:09'),(8,4,1,4,'2026-06-17','2026-06-29','sakit','sick_leaves/surat_sakit_DK1406260002.pdf','2026-06-14 14:58:15','2026-06-14 14:58:15'),(9,4,1,4,'2026-06-17','2026-06-19','sakit','sick_leaves/surat_sakit_DK1406260002.pdf','2026-06-14 15:00:44','2026-06-14 15:00:44'),(10,4,1,4,'2026-06-17','2026-06-18','sakit','sick_leaves/surat_sakit_DK1406260002.pdf','2026-06-14 15:05:05','2026-06-14 15:05:05'),(11,4,1,4,'2026-06-24','2026-06-24','ski','sick_leaves/surat_sakit_DK1406260002.pdf','2026-06-14 15:06:39','2026-06-14 15:06:39'),(12,5,1,5,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260001.pdf','2026-06-15 07:15:40','2026-06-15 07:15:40'),(13,5,1,5,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260001.pdf','2026-06-15 07:20:16','2026-06-15 07:20:16'),(14,5,1,5,'2026-06-24','2026-06-25','sakit','sick_leaves/surat_sakit_DK1506260001.pdf','2026-06-15 07:21:16','2026-06-15 07:21:16'),(15,5,1,5,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260001.pdf','2026-06-15 07:26:41','2026-06-15 07:26:41'),(16,6,1,6,'2026-06-17','2026-06-17','sait','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 07:33:34','2026-06-15 07:33:34'),(17,6,1,6,'2026-06-16','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 07:47:49','2026-06-15 07:47:49'),(18,6,1,6,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 07:50:42','2026-06-15 07:50:42'),(19,6,1,6,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 07:51:22','2026-06-15 07:51:22'),(20,6,1,6,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 07:57:23','2026-06-15 07:57:23'),(21,6,1,6,'2026-06-16','2026-06-16','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 10:24:01','2026-06-15 10:24:01'),(22,6,1,6,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 10:50:07','2026-06-15 10:50:07'),(23,6,1,6,'2026-06-16','2026-06-16','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 10:58:16','2026-06-15 10:58:16'),(24,6,1,6,'2026-06-16','2026-06-16','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 11:00:12','2026-06-15 11:00:12'),(25,6,1,6,'2026-06-16','2026-06-16','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 11:01:57','2026-06-15 11:01:57'),(26,6,1,6,'2026-06-16','2026-06-16','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 11:04:38','2026-06-15 11:04:38'),(27,6,1,6,'2026-06-16','2026-06-16','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 11:10:13','2026-06-15 11:10:13'),(28,6,1,6,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 11:12:22','2026-06-15 11:12:22'),(29,6,1,6,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 11:17:45','2026-06-15 11:17:45'),(30,6,1,6,'2026-06-16','2026-06-16','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 11:19:11','2026-06-15 11:19:11'),(31,6,1,6,'2026-06-16','2026-06-16','sakit','sick_leaves/surat_sakit_DK1506260002.pdf','2026-06-15 11:24:42','2026-06-15 11:24:42'),(32,7,1,7,'2026-06-17','2026-06-17','lumpuh','sick_leaves/surat_sakit_DK1606260001.pdf','2026-06-16 08:31:01','2026-06-16 08:31:01'),(33,8,1,8,'2026-06-17','2026-06-17','sakit','sick_leaves/surat_sakit_DK1606260002.pdf','2026-06-16 08:40:12','2026-06-16 08:40:12'),(34,10,1,10,'2026-06-18','2026-06-30','sakit','sick_leaves/surat_sakit_DK1606260004.pdf','2026-06-16 17:07:26','2026-06-16 17:07:26'),(35,12,2,12,'2026-06-23','2026-06-23','sakit banget asli','sick_leaves/surat_sakit_DK2206260002.pdf','2026-06-22 06:12:43','2026-06-22 06:12:43');
/*!40000 ALTER TABLE `sick_leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int unsigned NOT NULL,
  `payment_status` enum('pending_payment','waiting_upload','waiting_admin_confirmation','approved','rejected','request_reupload','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_consultation_id_foreign` (`consultation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (3,3,'DK1406260001','manual_transfer','manual',150000,'approved','2026-06-14 11:01:02','2026-06-14 11:01:20'),(4,4,'DK1406260002','manual_transfer','manual',25000,'approved','2026-06-14 14:52:18','2026-06-14 14:52:31'),(5,5,'DK1506260001','manual_transfer','manual',25000,'approved','2026-06-15 07:14:51','2026-06-15 07:15:19'),(6,6,'DK1506260002','bank_transfer','Transfer Bank',150000,'approved','2026-06-15 07:32:38','2026-06-15 07:33:08'),(7,7,'DK1606260001','bank_transfer','Transfer Bank',25000,'approved','2026-06-16 08:27:41','2026-06-16 08:28:30'),(8,8,'DK1606260002','manual_transfer','manual',150000,'approved','2026-06-16 08:35:35','2026-06-16 08:36:10'),(9,9,'DK1606260003','manual_transfer','manual',150000,'approved','2026-06-16 09:30:00','2026-06-16 09:30:08'),(10,10,'DK1606260004','manual_transfer','manual',25000,'approved','2026-06-16 16:53:12','2026-06-16 16:53:31'),(11,11,'DK2206260001','bank_transfer','Transfer Bank',25000,'rejected','2026-06-22 05:29:28','2026-06-22 05:41:07'),(12,12,'DK2206260002','bank_transfer','Transfer Bank',25000,'approved','2026-06-22 05:56:50','2026-06-22 06:03:42'),(13,13,'DK2206260003','qris','QRIS',150000,'approved','2026-06-22 06:31:10','2026-06-22 06:31:37');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
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

-- Dump completed on 2026-06-22 15:11:57
