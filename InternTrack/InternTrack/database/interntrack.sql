/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: interntrack
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

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
-- Table structure for table `absensi`
--

DROP TABLE IF EXISTS `absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `penempatan_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpa') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_absensi` (`siswa_id`,`tanggal`),
  KEY `penempatan_id` (`penempatan_id`),
  CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`penempatan_id`) REFERENCES `penempatan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absensi`
--

LOCK TABLES `absensi` WRITE;
/*!40000 ALTER TABLE `absensi` DISABLE KEYS */;
INSERT INTO `absensi` VALUES
(1,1,1,'2026-09-01','Hadir',NULL,'2026-10-19 02:12:47'),
(2,1,1,'2026-09-02','Hadir',NULL,'2026-10-19 02:12:47'),
(3,1,1,'2026-09-03','Hadir',NULL,'2026-10-19 02:12:47'),
(4,1,1,'2026-09-04','Izin',NULL,'2026-10-19 02:12:47'),
(5,1,1,'2026-09-05','Hadir',NULL,'2026-10-19 02:12:47'),
(6,2,1,'2026-09-01','Hadir',NULL,'2026-10-19 02:12:47'),
(7,2,1,'2026-09-02','Hadir',NULL,'2026-10-19 02:12:47'),
(8,2,1,'2026-09-03','Sakit',NULL,'2026-10-19 02:12:47'),
(9,2,1,'2026-09-04','Hadir',NULL,'2026-10-19 02:12:47'),
(10,2,1,'2026-09-05','Hadir',NULL,'2026-10-19 02:12:47'),
(11,3,2,'2026-09-01','Hadir',NULL,'2026-10-19 02:12:47'),
(12,3,2,'2026-09-02','Hadir',NULL,'2026-10-19 02:12:47'),
(13,3,2,'2026-09-03','Hadir',NULL,'2026-10-19 02:12:47'),
(14,3,2,'2026-09-04','Hadir',NULL,'2026-10-19 02:12:47'),
(15,3,2,'2026-09-05','Alpa',NULL,'2026-10-19 02:12:47'),
(16,4,2,'2026-09-01','Hadir',NULL,'2026-10-19 02:12:47'),
(17,4,2,'2026-09-02','Hadir',NULL,'2026-10-19 02:12:47'),
(18,4,2,'2026-09-03','Hadir',NULL,'2026-10-19 02:12:47'),
(19,4,2,'2026-09-04','Hadir',NULL,'2026-10-19 02:12:47'),
(20,4,2,'2026-09-05','Hadir',NULL,'2026-10-19 02:12:47'),
(21,5,3,'2026-09-01','Hadir',NULL,'2026-10-19 02:12:47'),
(22,5,3,'2026-09-02','Izin',NULL,'2026-10-19 02:12:47'),
(23,5,3,'2026-09-03','Hadir',NULL,'2026-10-19 02:12:47'),
(24,5,3,'2026-09-04','Hadir',NULL,'2026-10-19 02:12:47'),
(25,5,3,'2026-09-05','Hadir',NULL,'2026-10-19 02:12:47'),
(26,6,3,'2026-09-01','Hadir',NULL,'2026-10-19 02:12:47'),
(27,6,3,'2026-09-02','Hadir',NULL,'2026-10-19 02:12:47'),
(28,6,3,'2026-09-03','Hadir',NULL,'2026-10-19 02:12:47'),
(29,6,3,'2026-09-04','Hadir',NULL,'2026-10-19 02:12:47'),
(30,6,3,'2026-09-05','Hadir',NULL,'2026-10-19 02:12:47');
/*!40000 ALTER TABLE `absensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jurnal`
--

DROP TABLE IF EXISTS `jurnal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurnal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `penempatan_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text NOT NULL,
  `hasil` text DEFAULT NULL,
  `kendala` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `siswa_id` (`siswa_id`),
  KEY `penempatan_id` (`penempatan_id`),
  CONSTRAINT `jurnal_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jurnal_ibfk_2` FOREIGN KEY (`penempatan_id`) REFERENCES `penempatan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal`
--

LOCK TABLES `jurnal` WRITE;
/*!40000 ALTER TABLE `jurnal` DISABLE KEYS */;
INSERT INTO `jurnal` VALUES
(1,1,1,'2026-09-01','Orientasi perusahaan','Memahami struktur organisasi','Adaptasi awal','2026-10-19 02:12:47'),
(2,1,1,'2026-09-02','Pelajari stack teknologi','Mengenal Laravel & React','Dokumentasi terbatas','2026-10-19 02:12:47'),
(3,1,1,'2026-09-03','Membuat ERD','ERD selesai','Cardinality','2026-10-19 02:12:47'),
(4,2,1,'2026-09-01','Orientasi tim','Mengenal anggota tim',NULL,'2026-10-19 02:12:47'),
(5,2,1,'2026-09-02','Setup environment','XAMPP & VS Code ready','Port conflict','2026-10-19 02:12:47'),
(6,3,2,'2026-09-01','Orientasi','Memahami workflow',NULL,'2026-10-19 02:12:47'),
(7,3,2,'2026-09-02','Belajar CI4','Controller & model dasar',NULL,'2026-10-19 02:12:47'),
(8,4,2,'2026-09-01','Pengenalan project','Requirement dipahami',NULL,'2026-10-19 02:12:47'),
(9,5,3,'2026-09-01','Orientasi','Sistem existing dikenal',NULL,'2026-10-19 02:12:47'),
(10,6,3,'2026-09-01','Onboarding','Akses diberikan',NULL,'2026-10-19 02:12:47'),
(11,1,1,'2026-09-06','Code review dengan senior','Feedback diterapkan',NULL,'2026-10-19 02:14:42'),
(12,1,1,'2026-09-07','Implementasi validasi form','Validasi client & server',NULL,'2026-10-19 02:14:42'),
(13,1,1,'2026-09-08','Testing fitur CRUD','Semua test lolos',NULL,'2026-10-19 02:14:42'),
(14,2,1,'2026-09-06','Membuat unit test','Coverage meningkat',NULL,'2026-10-19 02:14:42'),
(15,2,1,'2026-09-07','Fix bug session','Bug fixed',NULL,'2026-10-19 02:14:42'),
(16,2,1,'2026-09-08','Dokumentasi API','Swagger updated',NULL,'2026-10-19 02:14:42'),
(17,3,2,'2026-09-06','Integrasi frontend','API terhubung',NULL,'2026-10-19 02:14:42'),
(18,3,2,'2026-09-07','Optimasi query','Query lebih cepat',NULL,'2026-10-19 02:14:42'),
(19,3,2,'2026-09-08','Deploy staging','Deploy sukses',NULL,'2026-10-19 02:14:42'),
(20,4,2,'2026-09-06','Desain UI dashboard','Mockup selesai',NULL,'2026-10-19 02:14:42'),
(21,4,2,'2026-09-07','Implementasi chart','Chart tampil',NULL,'2026-10-19 02:14:42'),
(22,4,2,'2026-09-08','Responsive fix','Mobile OK',NULL,'2026-10-19 02:14:42'),
(23,5,3,'2026-09-06','Meeting requirement','Requirement final',NULL,'2026-10-19 02:14:42'),
(24,5,3,'2026-09-07','Coding backend cuti','API cuti selesai',NULL,'2026-10-19 02:14:42'),
(25,5,3,'2026-09-08','Testing manual','Beberapa edge case',NULL,'2026-10-19 02:14:42'),
(26,6,3,'2026-09-06','Code refactor','Kode lebih bersih',NULL,'2026-10-19 02:14:42'),
(27,6,3,'2026-09-07','Pair programming','Belajar pola baru',NULL,'2026-10-19 02:14:42'),
(28,6,3,'2026-09-08','Presentasi progress','Feedback positif',NULL,'2026-10-19 02:14:42'),
(29,7,4,'2026-09-06','Orientasi sistem','Memahami arsitektur',NULL,'2026-10-19 02:14:42'),
(30,7,4,'2026-09-07','Task minor','Selesai',NULL,'2026-10-19 02:14:42');
/*!40000 ALTER TABLE `jurnal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monitoring`
--

DROP TABLE IF EXISTS `monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `monitoring` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `pembimbing_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `catatan` text DEFAULT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `rekomendasi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `siswa_id` (`siswa_id`),
  KEY `pembimbing_id` (`pembimbing_id`),
  CONSTRAINT `monitoring_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `monitoring_ibfk_2` FOREIGN KEY (`pembimbing_id`) REFERENCES `pembimbing` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monitoring`
--

LOCK TABLES `monitoring` WRITE;
/*!40000 ALTER TABLE `monitoring` DISABLE KEYS */;
INSERT INTO `monitoring` VALUES
(1,1,1,'2026-09-10','Siswa mampu berkomunikasi dengan baik dan dapat menyelesaikan tugas yang diberikan industri.',85.00,'Pertahankan semangat. Tingkatkan debugging.','2026-10-19 02:12:47'),
(2,2,1,'2026-09-10','Cukup baik teknis. Perlu lebih aktif diskusi.',78.00,'Lebih proaktif dalam meeting.','2026-10-19 02:12:47'),
(3,3,2,'2026-09-11','Kemampuan backend bagus. Dokumentasi kurang.',82.00,'Tulis komentar kode lebih baik.','2026-10-19 02:12:47'),
(4,4,2,'2026-09-11','Rajin dan disiplin. Hasil sesuai ekspektasi.',88.00,'Eksplorasi teknologi baru.','2026-10-19 02:12:47'),
(5,5,3,'2026-09-12','Pemahaman requirement perlu ditingkatkan.',75.00,'Lebih teliti baca requirement.','2026-10-19 02:12:47'),
(6,6,3,'2026-09-12','Sangat baik unit testing.',90.00,'Siap tanggung jawab lebih besar.','2026-10-19 02:12:47'),
(7,7,4,'2026-09-13','Masih adaptasi.',70.00,'Perbanyak tanya mentor.','2026-10-19 02:12:47'),
(8,8,5,'2026-09-13','Kreatif UI. Perhatian accessibility.',80.00,'Pelajari WCAG.','2026-10-19 02:12:47'),
(9,9,6,'2026-09-14','PKL selesai. Performa baik.',83.00,'Siap dunia kerja.','2026-10-19 02:12:47'),
(10,10,7,'2026-09-15','Belum mulai PKL.',0.00,'Siapkan diri.','2026-10-19 02:12:47');
/*!40000 ALTER TABLE `monitoring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembimbing`
--

DROP TABLE IF EXISTS `pembimbing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembimbing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `pembimbing_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembimbing`
--

LOCK TABLES `pembimbing` WRITE;
/*!40000 ALTER TABLE `pembimbing` DISABLE KEYS */;
INSERT INTO `pembimbing` VALUES
(1,2,'198501012010011001','Budi Santoso, S.Kom','081234567001','budi@smk.sch.id'),
(2,3,'198602022011012002','Siti Aminah, S.Pd','081234567002','siti@smk.sch.id'),
(3,4,'198703032012013003','Ahmad Fauzi, M.Kom','081234567003','ahmad@smk.sch.id'),
(4,5,'198804042013014004','Dewi Lestari, S.Kom','081234567004','dewi@smk.sch.id'),
(5,6,'198905052014015005','Rudi Hartono, S.T','081234567005','rudi@smk.sch.id'),
(6,7,'199006062015016006','Nina Kartika, S.Pd','081234567006','nina@smk.sch.id'),
(7,8,'199107072016017007','Eko Prasetyo, M.T','081234567007','eko@smk.sch.id'),
(8,9,'199208082017018008','Maya Sari, S.Kom','081234567008','maya@smk.sch.id'),
(9,10,'199309092018019009','Joko Widodo, S.Pd','081234567009','joko@smk.sch.id'),
(10,11,'199410102019011010','Rina Marlina, S.Kom','081234567010','rina@smk.sch.id');
/*!40000 ALTER TABLE `pembimbing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penempatan`
--

DROP TABLE IF EXISTS `penempatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `penempatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `perusahaan_id` int(11) NOT NULL,
  `pembimbing_id` int(11) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `status` enum('Belum Mulai','Aktif','Selesai') DEFAULT 'Belum Mulai',
  PRIMARY KEY (`id`),
  KEY `siswa_id` (`siswa_id`),
  KEY `perusahaan_id` (`perusahaan_id`),
  KEY `pembimbing_id` (`pembimbing_id`),
  CONSTRAINT `penempatan_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penempatan_ibfk_2` FOREIGN KEY (`perusahaan_id`) REFERENCES `perusahaan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penempatan_ibfk_3` FOREIGN KEY (`pembimbing_id`) REFERENCES `pembimbing` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penempatan`
--

LOCK TABLES `penempatan` WRITE;
/*!40000 ALTER TABLE `penempatan` DISABLE KEYS */;
INSERT INTO `penempatan` VALUES
(1,1,1,1,'2026-08-01','2026-11-30','Aktif'),
(2,2,1,1,'2026-08-01','2026-11-30','Aktif'),
(3,3,2,2,'2026-08-01','2026-11-30','Aktif'),
(4,4,2,2,'2026-08-01','2026-11-30','Aktif'),
(5,5,3,3,'2026-08-01','2026-11-30','Aktif'),
(6,6,3,3,'2026-08-01','2026-11-30','Aktif'),
(7,7,4,4,'2026-08-01','2026-11-30','Aktif'),
(8,8,5,5,'2026-08-01','2026-11-30','Aktif'),
(9,9,6,6,'2026-07-01','2026-10-31','Selesai'),
(10,10,7,7,'2026-09-01','2026-12-31','Belum Mulai');
/*!40000 ALTER TABLE `penempatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perusahaan`
--

DROP TABLE IF EXISTS `perusahaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `perusahaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) NOT NULL,
  `alamat` text DEFAULT NULL,
  `bidang` varchar(100) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perusahaan`
--

LOCK TABLES `perusahaan` WRITE;
/*!40000 ALTER TABLE `perusahaan` DISABLE KEYS */;
INSERT INTO `perusahaan` VALUES
(1,'PT Teknologi Maju Indonesia','Jl. Industri Raya No.100, Semarang','Software Development','024-1234567','2026-10-19 02:12:47'),
(2,'CV Digital Solusi','Jl. Teknopolis No.25, Semarang','Web Development','024-2345678','2026-10-19 02:12:47'),
(3,'PT Inovasi Sistem Informasi','Jl. Science Park No.50, Semarang','IT Consulting','024-3456789','2026-10-19 02:12:47'),
(4,'PT Cyber Security Nusantara','Jl. Data Center No.75, Semarang','Cyber Security','024-4567890','2026-10-19 02:12:47'),
(5,'CV Kreatif Media Digital','Jl. Kreatif No.12, Semarang','Digital Marketing & Design','024-5678901','2026-10-19 02:12:47'),
(6,'PT SoftTech Indonesia','Jl. Software No.88, Semarang','Enterprise Software','024-6789012','2026-10-19 02:12:47'),
(7,'PT Data Analytics Pro','Jl. Big Data No.33, Semarang','Data Science','024-7890123','2026-10-19 02:12:47'),
(8,'CV Mobile App Studio','Jl. Aplikasi No.45, Semarang','Mobile Development','024-8901234','2026-10-19 02:12:47'),
(9,'PT Cloud Computing ID','Jl. Cloud No.67, Semarang','Cloud Services','024-9012345','2026-10-19 02:12:47'),
(10,'PT AI Innovation Lab','Jl. Artificial No.99, Semarang','Artificial Intelligence','024-0123456','2026-10-19 02:12:47');
/*!40000 ALTER TABLE `perusahaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siswa`
--

DROP TABLE IF EXISTS `siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nis` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nis` (`nis`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siswa`
--

LOCK TABLES `siswa` WRITE;
/*!40000 ALTER TABLE `siswa` DISABLE KEYS */;
INSERT INTO `siswa` VALUES
(1,12,'2024001','Andi Pratama','XII RPL 1','Rekayasa Perangkat Lunak','081345678001',NULL),
(2,13,'2024002','Bella Putri','XII RPL 1','Rekayasa Perangkat Lunak','081345678002',NULL),
(3,14,'2024003','Candra Wijaya','XII RPL 1','Rekayasa Perangkat Lunak','081345678003',NULL),
(4,15,'2024004','Dina Amelia','XII RPL 1','Rekayasa Perangkat Lunak','081345678004',NULL),
(5,16,'2024005','Eko Saputra','XII RPL 1','Rekayasa Perangkat Lunak','081345678005',NULL),
(6,17,'2024006','Fitri Handayani','XII RPL 2','Rekayasa Perangkat Lunak','081345678006',NULL),
(7,18,'2024007','Gilang Ramadhan','XII RPL 2','Rekayasa Perangkat Lunak','081345678007',NULL),
(8,19,'2024008','Hana Safitri','XII RPL 2','Rekayasa Perangkat Lunak','081345678008',NULL),
(9,20,'2024009','Irfan Maulana','XII RPL 2','Rekayasa Perangkat Lunak','081345678009',NULL),
(10,21,'2024010','Jihan Aulia','XII RPL 2','Rekayasa Perangkat Lunak','081345678010',NULL),
(11,22,'2024011','Kevin Aditya','XII RPL 1','Rekayasa Perangkat Lunak','081345678011',NULL),
(12,23,'2024012','Luna Maharani','XII RPL 1','Rekayasa Perangkat Lunak','081345678012',NULL),
(13,24,'2024013','Miko Fernando','XII RPL 1','Rekayasa Perangkat Lunak','081345678013',NULL),
(14,25,'2024014','Nadia Kusuma','XII RPL 2','Rekayasa Perangkat Lunak','081345678014',NULL),
(15,26,'2024015','Oscar Putra','XII RPL 2','Rekayasa Perangkat Lunak','081345678015',NULL),
(16,27,'2024016','Putri Ayu','XII RPL 2','Rekayasa Perangkat Lunak','081345678016',NULL),
(17,28,'2024017','Rafi Akbar','XII RPL 1','Rekayasa Perangkat Lunak','081345678017',NULL),
(18,29,'2024018','Salsa Bilqis','XII RPL 1','Rekayasa Perangkat Lunak','081345678018',NULL),
(19,30,'2024019','Teguh Santoso','XII RPL 1','Rekayasa Perangkat Lunak','081345678019',NULL),
(20,31,'2024020','Umi Kalsum','XII RPL 2','Rekayasa Perangkat Lunak','081345678020',NULL),
(21,32,'2024021','Vino Bastian','XII RPL 2','Rekayasa Perangkat Lunak','081345678021',NULL),
(22,33,'2024022','Wulan Dari','XII RPL 2','Rekayasa Perangkat Lunak','081345678022',NULL),
(23,34,'2024023','Yoga Pratama','XII RPL 1','Rekayasa Perangkat Lunak','081345678023',NULL),
(24,35,'2024024','Zahra Melati','XII RPL 1','Rekayasa Perangkat Lunak','081345678024',NULL),
(25,36,'2024025','Arief Rahman','XII RPL 2','Rekayasa Perangkat Lunak','081345678025',NULL);
/*!40000 ALTER TABLE `siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pembimbing','siswa') NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'admin','$2y$10$vPpwRVnJXD76BaK0Xf7XkeqPTPyWJVS36ReuBOis.azUwWEZ.LUKG','admin',NULL,'2026-10-19 02:12:47'),
(2,'pembimbing1','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',1,'2026-10-19 02:12:47'),
(3,'pembimbing2','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',2,'2026-10-19 02:12:47'),
(4,'pembimbing3','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',3,'2026-10-19 02:12:47'),
(5,'pembimbing4','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',4,'2026-10-19 02:12:47'),
(6,'pembimbing5','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',5,'2026-10-19 02:12:47'),
(7,'pembimbing6','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',6,'2026-10-19 02:12:47'),
(8,'pembimbing7','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',7,'2026-10-19 02:12:47'),
(9,'pembimbing8','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',8,'2026-10-19 02:12:47'),
(10,'pembimbing9','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',9,'2026-10-19 02:12:47'),
(11,'pembimbing10','$2y$10$kpY5JcFqvdfvOAYo19i.6e/Ii2w4gbUIbA67BY1x.PuorVdQ0v5V6','pembimbing',10,'2026-10-19 02:12:47'),
(12,'siswa01','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',1,'2026-10-19 02:12:47'),
(13,'siswa02','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',2,'2026-10-19 02:12:47'),
(14,'siswa03','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',3,'2026-10-19 02:12:47'),
(15,'siswa04','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',4,'2026-10-19 02:12:47'),
(16,'siswa05','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',5,'2026-10-19 02:12:47'),
(17,'siswa06','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',6,'2026-10-19 02:12:47'),
(18,'siswa07','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',7,'2026-10-19 02:12:47'),
(19,'siswa08','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',8,'2026-10-19 02:12:47'),
(20,'siswa09','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',9,'2026-10-19 02:12:47'),
(21,'siswa10','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',10,'2026-10-19 02:12:47'),
(22,'siswa11','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',11,'2026-10-19 02:12:47'),
(23,'siswa12','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',12,'2026-10-19 02:12:47'),
(24,'siswa13','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',13,'2026-10-19 02:12:47'),
(25,'siswa14','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',14,'2026-10-19 02:12:47'),
(26,'siswa15','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',15,'2026-10-19 02:12:47'),
(27,'siswa16','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',16,'2026-10-19 02:12:47'),
(28,'siswa17','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',17,'2026-10-19 02:12:47'),
(29,'siswa18','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',18,'2026-10-19 02:12:47'),
(30,'siswa19','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',19,'2026-10-19 02:12:47'),
(31,'siswa20','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',20,'2026-10-19 02:12:47'),
(32,'siswa21','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',21,'2026-10-19 02:12:47'),
(33,'siswa22','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',22,'2026-10-19 02:12:47'),
(34,'siswa23','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',23,'2026-10-19 02:12:47'),
(35,'siswa24','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',24,'2026-10-19 02:12:47'),
(36,'siswa25','$2y$10$cfj/oJ0glherG5uWwu6XaOwg8HdWX4QD3NlMiE5JNDqaQeoKbKof6','siswa',25,'2026-10-19 02:12:47');
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

-- Dump completed on 2026-10-19  2:15:02
