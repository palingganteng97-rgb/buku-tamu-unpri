-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.46 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.17.0.7270
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_buku_tamu
CREATE DATABASE IF NOT EXISTS `db_buku_tamu` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_buku_tamu`;

-- Dumping structure for table db_buku_tamu.data_instansi
CREATE TABLE IF NOT EXISTS `data_instansi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kategori` varchar(100) NOT NULL,
  `nama_instansi` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_buku_tamu.data_instansi: ~42 rows (approximately)
REPLACE INTO `data_instansi` (`id`, `kategori`, `nama_instansi`) VALUES
	(1, 'Kementerian Negara', 'Kementerian Dalam Negeri (Kemendagri)'),
	(2, 'Kementerian Negara', 'Kementerian Luar Negeri (Kemenlu)'),
	(3, 'Kementerian Negara', 'Kementerian Pertahanan (Kemenhan)'),
	(4, 'Kementerian Negara', 'Kementerian Keuangan (Kemenkeu)'),
	(5, 'Kementerian Negara', 'Kementerian Kesehatan (Kemenkes)'),
	(6, 'Kementerian Negara', 'Kementerian Sosial (Kemensos)'),
	(7, 'Kementerian Negara', 'Kementerian Komunikasi dan Informatika (Kemenkominfo)'),
	(8, 'Kementerian Negara', 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi (Kemendikbudristek)'),
	(9, 'Kementerian Negara', 'Kementerian Agama (Kemenag)'),
	(10, 'Kementerian Negara', 'Kementerian Badan Usaha Milik Negara (KemenBUMN)'),
	(11, 'Lembaga Tinggi Negara', 'Dewan Perwakilan Rakyat (DPR-RI)'),
	(12, 'Lembaga Tinggi Negara', 'Majelis Permusyawaratan Rakyat (MPR-RI)'),
	(13, 'Lembaga Tinggi Negara', 'Mahkamah Agung (MA)'),
	(14, 'Lembaga Tinggi Negara', 'Mahkamah Konstitusi (MK)'),
	(15, 'Pemerintah Daerah', 'Pemerintah Provinsi (Pemprov) Seluruh Indonesia'),
	(16, 'Pemerintah Daerah', 'Pemerintah Kabupaten / Kota (Pemkab / Pemkot) Seluruh Indonesia'),
	(17, 'Keamanan & Pertahanan', 'Tentara Nasional Indonesia (TNI)'),
	(18, 'Keamanan & Pertahanan', 'Kepolisian Republik Indonesia (POLRI)'),
	(19, 'Korporasi Negara', 'Badan Usaha Milik Negara (BUMN) / BUMD'),
	(20, 'Pendidikan & Umum', 'Universitas / Sekolah / Institusi Pendidikan'),
	(21, 'Umum', 'Masyarakat Umum / Perorangan / Swasta Lainnya'),
	(22, 'Kementerian Negara', 'Kementerian Dalam Negeri (Kemendagri)'),
	(23, 'Kementerian Negara', 'Kementerian Luar Negeri (Kemenlu)'),
	(24, 'Kementerian Negara', 'Kementerian Pertahanan (Kemenhan)'),
	(25, 'Kementerian Negara', 'Kementerian Keuangan (Kemenkeu)'),
	(26, 'Kementerian Negara', 'Kementerian Kesehatan (Kemenkes)'),
	(27, 'Kementerian Negara', 'Kementerian Sosial (Kemensos)'),
	(28, 'Kementerian Negara', 'Kementerian Komunikasi dan Informatika (Kemenkominfo)'),
	(29, 'Kementerian Negara', 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi (Kemendikbudristek)'),
	(30, 'Kementerian Negara', 'Kementerian Agama (Kemenag)'),
	(31, 'Kementerian Negara', 'Kementerian Badan Usaha Milik Negara (KemenBUMN)'),
	(32, 'Lembaga Tinggi Negara', 'Dewan Perwakilan Rakyat (DPR-RI)'),
	(33, 'Lembaga Tinggi Negara', 'Majelis Permusyawaratan Rakyat (MPR-RI)'),
	(34, 'Lembaga Tinggi Negara', 'Mahkamah Agung (MA)'),
	(35, 'Lembaga Tinggi Negara', 'Mahkamah Konstitusi (MK)'),
	(36, 'Pemerintah Daerah', 'Pemerintah Provinsi (Pemprov) Seluruh Indonesia'),
	(37, 'Pemerintah Daerah', 'Pemerintah Kabupaten / Kota (Pemkab / Pemkot) Seluruh Indonesia'),
	(38, 'Keamanan & Pertahanan', 'Tentara Nasional Indonesia (TNI)'),
	(39, 'Keamanan & Pertahanan', 'Kepolisian Republik Indonesia (POLRI)'),
	(40, 'Korporasi Negara', 'Badan Usaha Milik Negara (BUMN) / BUMD'),
	(41, 'Pendidikan & Umum', 'Universitas / Sekolah / Institusi Pendidikan'),
	(42, 'Umum', 'Masyarakat Umum / Perorangan / Swasta Lainnya');

-- Dumping structure for table db_buku_tamu.master_instansi
CREATE TABLE IF NOT EXISTS `master_instansi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_instansi` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_instansi` (`nama_instansi`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_buku_tamu.master_instansi: ~73 rows (approximately)
REPLACE INTO `master_instansi` (`id`, `nama_instansi`) VALUES
	(1, 'Majelis Permusyawaratan Rakyat (MPR)'),
	(2, 'Dewan Perwakilan Rakyat (DPR)'),
	(3, 'Dewan Perwakilan Daerah (DPD)'),
	(4, 'Mahkamah Agung (MA)'),
	(5, 'Mahkamah Konstitusi (MK)'),
	(6, 'Komisi Yudisial (KY)'),
	(7, 'Badan Pemeriksa Keuangan (BPK)'),
	(8, 'Kejaksaan Agung RI'),
	(9, 'Sekretariat Negara (Setneg)'),
	(10, 'Sekretariat Kabinet (Setkab)'),
	(11, 'Kementerian Koordinator Bidang Politik dan Keamanan'),
	(12, 'Kementerian Koordinator Bidang Hukum, HAM, Imigrasi, dan Pemasyarakatan'),
	(13, 'Kementerian Koordinator Bidang Perekonomian'),
	(14, 'Kementerian Koordinator Bidang Pembangunan Manusia dan Kebudayaan'),
	(15, 'Kementerian Koordinator Bidang Infrastruktur dan Pembangunan Kewilayahan'),
	(16, 'Kementerian Koordinator Bidang Pemberdayaan Masyarakat'),
	(17, 'Kementerian Koordinator Bidang Pangan'),
	(18, 'Kementerian Dalam Negeri (Kemendagri)'),
	(19, 'Kementerian Luar Negeri (Kemenlu)'),
	(20, 'Kementerian Pertahanan (Kemenhan)'),
	(21, 'Kementerian Agama (Kemenag)'),
	(22, 'Kementerian Hukum'),
	(23, 'Kementerian Hak Asasi Manusia'),
	(24, 'Kementerian Imigrasi dan Pemasyarakatan'),
	(25, 'Kementerian Keuangan (Kemenkeu)'),
	(26, 'Kementerian Pendidikan Dasar dan Menengah'),
	(27, 'Kementerian Pendidikan Tinggi, Sains, dan Teknologi'),
	(28, 'Kementerian Kebudayaan'),
	(29, 'Kementerian Kesehatan (Kemenkes)'),
	(30, 'Kementerian Sosial (Kemensos)'),
	(31, 'Kementerian Ketenagakerjaan (Kemnaker)'),
	(32, 'Kementerian Perindustrian (Kemenperin)'),
	(33, 'Kementerian Perdagangan (Kemendag)'),
	(34, 'Kementerian Energi dan Sumber Daya Mineral (ESDM)'),
	(35, 'Kementerian Pekerjaan Umum (PU)'),
	(36, 'Kementerian Perumahan dan Kawasan Permukiman'),
	(37, 'Kementerian Desa dan Pembangunan Daerah Tertinggal'),
	(38, 'Kementerian Transmigrasi'),
	(39, 'Kementerian Perhubungan (Kemenhub)'),
	(40, 'Kementerian Komunikasi dan Digital (Komdigi)'),
	(41, 'Kementerian Pertanian (Kementan)'),
	(42, 'Kementerian Kehutanan'),
	(43, 'Kementerian Lingkungan Hidup / Badan Pengendalian Lingkungan Hidup'),
	(44, 'Kementerian Kelautan dan Perikanan (KKP)'),
	(45, 'Kementerian Agraria dan Tata Ruang / Badan Pertanahan Nasional'),
	(46, 'Kementerian Perencanaan Pembangunan Nasional / Bappenas'),
	(47, 'Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi'),
	(48, 'Kementerian Badan Usaha Milik Negara (BUMN)'),
	(49, 'Kementerian Kependudukan dan Pembangunan Keluarga / BKKBN'),
	(50, 'Kementerian Investasi dan Hilirisasi / BKPM'),
	(51, 'Kementerian Koperasi'),
	(52, 'Kementerian Usaha Mikro, Kecil, dan Menengah (UMKM)'),
	(53, 'Kementerian Pariwisata'),
	(54, 'Kementerian Ekonomi Kreatif / Badan Ekonomi Kreatif'),
	(55, 'Kementerian Pemberdayaan Perempuan dan Perlindungan Anak'),
	(56, 'Kementerian Pemuda dan Olahraga (Kemenpora)'),
	(57, 'Kementerian Pelindungan Pekerja Migran Indonesia'),
	(58, 'Badan Riset dan Inovasi Nasional (BRIN)'),
	(59, 'Badan Pusat Statistik (BPS)'),
	(60, 'Badan Siber dan Sandi Negara (BSSN)'),
	(61, 'Badan Intelijen Negara (BIN)'),
	(62, 'Badan Kepegawaian Negara (BKN)'),
	(63, 'Badan Meteorologi, Klimatologi, dan Geofisika (BMKG)'),
	(64, 'Badan Narkotika Nasional (BNN)'),
	(65, 'Badan Nasional Penanggulangan Bencana (BNPB)'),
	(66, 'Badan Pengawas Obat dan Makanan (BPOM)'),
	(67, 'Komisi Pemilihan Umum (KPU)'),
	(68, 'Komisi Pemberantasan Korupsi (KPK)'),
	(69, 'Kepolisian Negara Republik Indonesia (POLRI)'),
	(70, 'Tentara Nasional Indonesia (TNI)'),
	(71, 'Pemerintah Provinsi (Pemprov)'),
	(72, 'Pemerintah Kabupaten (Pemkab)'),
	(73, 'Pemerintah Kota (Pemkot)');

-- Dumping structure for table db_buku_tamu.tamu
CREATE TABLE IF NOT EXISTS `tamu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `instansi` varchar(150) DEFAULT NULL,
  `keperluan` text NOT NULL,
  `tanggal_kunjungan` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_buku_tamu.tamu: ~2 rows (approximately)
REPLACE INTO `tamu` (`id`, `nama`, `email`, `no_telepon`, `instansi`, `keperluan`, `tanggal_kunjungan`) VALUES
	(1, 'RamKun', 'palingganteng97@gmail.com', '08145', 'Pemerintah Kabupaten/Kota (Pemkab/Pemkot)', 'Data', '2026-06-10 10:12:46'),
	(9, 'RamKun', 'palingganteng97@gmail.com', '08167', 'Tentara Nasional Indonesia (TNI)', 'FR', '2026-06-11 03:09:51');

-- Dumping structure for table db_buku_tamu.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_buku_tamu.users: ~1 rows (approximately)
REPLACE INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
	(1, 'RestuAjiM', '$2y$10$U65WQKUh7wyXEOxrGcNlwuyT1rJm3gLJA9QTzVzjKxaVdRD.rS1GS', '2026-06-10 11:13:39');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
