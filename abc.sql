/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 8.0.30 : Database - abc
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`abc` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `abc`;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

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

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_19_020341_create_hibah_tables',1),(5,'2026_02_19_020549_create_submissions_table',1),(6,'2026_02_19_032310_create_steps_table',1);

/*Table structure for table `pakets` */

DROP TABLE IF EXISTS `pakets`;

CREATE TABLE `pakets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_paket` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pakets` */

insert  into `pakets`(`id`,`nama_paket`,`created_at`,`updated_at`) values (1,'Pembangunan Jaringan Irigasi DI. Cipancuh','2026-02-23 03:28:26','2026-02-23 03:28:26'),(2,'Normalisasi Sungai Cimanuk Hilir','2026-02-23 03:28:26','2026-02-23 03:28:26'),(3,'Rehabilitasi Bendung Rentang','2026-02-23 03:28:26','2026-02-23 03:28:26');

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

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

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values ('0rlXTlN3X2PB1Ia1tn8MQzfg1XbL5qWtQCVsoUKf',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicmdCeXhUV2lKeWd0cGo4TG1JelNJZEx2ZU5seUtOdHlka1MyWjhpbiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC93aXphcmQvcGFrZXQvMS9zdGVwLzYiO3M6NToicm91dGUiO3M6OToidXNlci5zdGVwIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1771822937),('wzX3uOBgN5p6BHOH8cC2umwXouF54bbcIOL5ibXC',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiV0phREJtWFFXdG5QbGhJRG1DcFVOVkY4ZTg4VncwOWNEYnVpRVVYMSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZC9wcm9ncmVzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wYWtldC8xL2RldGFpbC8yIjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi5wYWtldC5kZXRhaWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1771822947);

/*Table structure for table `steps` */

DROP TABLE IF EXISTS `steps`;

CREATE TABLE `steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `paket_id` bigint unsigned NOT NULL,
  `nama_step` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `steps_paket_id_foreign` (`paket_id`),
  CONSTRAINT `steps_paket_id_foreign` FOREIGN KEY (`paket_id`) REFERENCES `pakets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `steps` */

/*Table structure for table `submissions` */

DROP TABLE IF EXISTS `submissions`;

CREATE TABLE `submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `paket_id` bigint unsigned NOT NULL,
  `step_number` int NOT NULL,
  `file_path` json NOT NULL,
  `status` enum('pending','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `submissions_user_id_foreign` (`user_id`),
  KEY `submissions_paket_id_foreign` (`paket_id`),
  CONSTRAINT `submissions_paket_id_foreign` FOREIGN KEY (`paket_id`) REFERENCES `pakets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `submissions` */

insert  into `submissions`(`id`,`user_id`,`paket_id`,`step_number`,`file_path`,`status`,`catatan_admin`,`created_at`,`updated_at`) values (1,2,1,1,'\"submissions/wa2-4.jpeg\"','pending','salah bangg','2026-02-23 03:29:16','2026-02-23 04:46:09'),(2,2,1,2,'\"submissions/wa3-1.jpeg\"','disetujui',NULL,'2026-02-23 03:30:07','2026-02-23 03:42:35'),(3,2,1,3,'\"submissions/wa3-3.jpeg\"','pending',NULL,'2026-02-23 03:30:12','2026-02-23 04:37:03'),(4,2,1,4,'\"submissions/wa3-4.jpeg\"','pending','masih salah bang','2026-02-23 03:30:18','2026-02-23 04:38:39'),(5,2,1,5,'\"submissions/beritabalai1.png\"','pending','ttm aja bang','2026-02-23 03:30:24','2026-02-23 04:55:53'),(6,2,1,6,'\"submissions/SKRIPSI CECILIO STIKOM 2025.pdf\"','pending',NULL,'2026-02-23 03:30:31','2026-02-23 05:02:14'),(7,2,1,7,'\"submissions/beritabalai3.png\"','pending',NULL,'2026-02-23 03:30:37','2026-02-23 04:55:35'),(8,2,1,8,'[\"submissions/step8/wa1-4.jpeg\", \"submissions/step8/beritabalai1.png\", \"submissions/step8/beritabalai1-1.png\"]','pending',NULL,'2026-02-23 03:30:47','2026-02-23 04:55:28'),(9,2,3,1,'\"submissions/wa2.jpeg\"','pending',NULL,'2026-02-23 04:21:18','2026-02-23 04:21:18'),(10,2,3,2,'\"submissions/wa2-1.jpeg\"','pending',NULL,'2026-02-23 04:22:57','2026-02-23 04:22:57'),(11,2,3,3,'\"submissions/wa3-2.jpeg\"','pending',NULL,'2026-02-23 04:23:07','2026-02-23 04:23:07'),(12,2,3,4,'\"submissions/wa1.jpeg\"','pending',NULL,'2026-02-23 04:23:36','2026-02-23 04:23:36'),(13,2,3,5,'\"submissions/wa1-1.jpeg\"','pending',NULL,'2026-02-23 04:24:12','2026-02-23 04:24:12'),(14,2,3,6,'\"submissions/wa2-2.jpeg\"','pending',NULL,'2026-02-23 04:24:18','2026-02-23 04:24:18'),(15,2,3,7,'\"submissions/166.png\"','pending',NULL,'2026-02-23 04:24:22','2026-02-23 04:24:22'),(16,2,3,8,'[\"submissions/step8/wa4.jpeg\", \"submissions/step8/wa3-2.jpeg\", \"submissions/step8/wa2-3.jpeg\", \"submissions/step8/beritabalai3.png\", \"submissions/step8/wa4-1.jpeg\"]','pending',NULL,'2026-02-23 04:24:46','2026-02-23 04:24:46'),(17,2,1,9,'\"submissions/wa3-5.jpeg\"','pending',NULL,'2026-02-23 04:51:50','2026-02-23 04:51:50'),(18,2,1,10,'\"submissions/wa2-3.jpeg\"','pending',NULL,'2026-02-23 04:53:52','2026-02-23 04:53:52');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_satker` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Internal BBWS',
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`username`,`password`,`nama_satker`,`role`,`remember_token`,`created_at`,`updated_at`) values (1,'Administrator Utama','admin','$2y$12$xsX/Pq6wiJaezNblNoUTROqM/zV.n07jVkVxW7S4FXKbhvSqHndqq','Internal BBWS','admin',NULL,'2026-02-23 03:28:26','2026-02-23 03:28:26'),(2,'Satker Wilayah I','satker01','$2y$12$F0XK9BOljtdzk05/Vd6KLeZQ4e6iqvwA6yrWW1trnIZpjeCHUL6ry','Satker Wilayah Cirebon','user',NULL,'2026-02-23 03:28:26','2026-02-23 03:28:26');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
