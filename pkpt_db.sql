-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 23, 2026 at 07:49 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pkpt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-01-08-020000', 'App\\Database\\Migrations\\BuatTabelProgramKerja', 'default', 'App', 1768201627, 1),
(2, '2026-01-08-030000', 'App\\Database\\Migrations\\UpdateTabelProgramKerja', 'default', 'App', 1768201627, 1),
(3, '2026-01-08-045907', 'App\\Database\\Migrations\\CreateDetailAnggaranTable', 'default', 'App', 1768201627, 1),
(4, '2026-01-08-084025', 'App\\Database\\Migrations\\CreateProgramKerjaDokumenTable', 'default', 'App', 1768201627, 1);

-- --------------------------------------------------------

--
-- Table structure for table `program_kerja`
--

CREATE TABLE `program_kerja` (
  `id` int UNSIGNED NOT NULL,
  `tahun` int NOT NULL DEFAULT '2026' COMMENT 'Tahun program kerja',
  `nama_kegiatan` varchar(500) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nama kegiatan program kerja',
  `tanggal_mulai` date DEFAULT NULL COMMENT 'Tanggal mulai pelaksanaan',
  `tanggal_selesai` date DEFAULT NULL COMMENT 'Tanggal selesai pelaksanaan',
  `unit_kerja` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Unit kerja pelaksana',
  `rencana_kegiatan` text COLLATE utf8mb4_general_ci COMMENT 'Deskripsi rencana kegiatan',
  `anggaran` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Anggaran yang dialokasikan (dalam Rupiah)',
  `realisasi_kegiatan` text COLLATE utf8mb4_general_ci COMMENT 'Deskripsi realisasi kegiatan',
  `pelaksana` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Nama pelaksana/PIC kegiatan',
  `dokumen_output` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Path file dokumen output',
  `realisasi_anggaran` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Realisasi anggaran yang terpakai (dalam Rupiah)',
  `sasaran_strategis` text COLLATE utf8mb4_general_ci COMMENT 'Sasaran strategis kegiatan',
  `status` enum('Terlaksana','Tidak Terlaksana','Penugasan Tambahan') COLLATE utf8mb4_general_ci DEFAULT 'Terlaksana' COMMENT 'Status pelaksanaan kegiatan',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_kerja`
--

INSERT INTO `program_kerja` (`id`, `tahun`, `nama_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `unit_kerja`, `rencana_kegiatan`, `anggaran`, `realisasi_kegiatan`, `pelaksana`, `dokumen_output`, `realisasi_anggaran`, `sasaran_strategis`, `status`, `created_at`, `updated_at`) VALUES
(1, 2026, 'Hakordia', '2026-01-05', '2026-01-12', 'Biro Digi', '', '240000000.00', '', 'Jeki', NULL, '230000000.00', '', 'Terlaksana', '2026-01-13 01:18:17', '2026-01-13 07:03:19'),
(2, 2026, 'Senam', '2026-01-06', '2026-01-09', 'Biro Digi', '', '240000000.00', '', 'Jeki', NULL, '198000000.00', '', 'Penugasan Tambahan', '2026-01-13 01:19:29', '2026-01-22 08:37:19'),
(3, 2026, 'Running', '2026-01-15', '2026-01-20', 'Biro Digi', '', '240000000.00', '', 'Jeki', NULL, '210000000.00', '', 'Tidak Terlaksana', '2026-01-13 01:33:48', '2026-01-13 07:01:57'),
(4, 2026, 'FunFair', '2026-01-19', '2026-02-09', 'Hukor', '', '500000000.00', '', 'Alya', NULL, '550000000.00', '', 'Tidak Terlaksana', '2026-01-13 02:05:56', '2026-01-13 02:05:56'),
(5, 2026, 'Lomba', '2026-02-16', '2026-02-19', 'Inspektorat', '', '12000000.00', '', 'Rehan', NULL, '13000000.00', '', 'Penugasan Tambahan', '2026-01-13 02:07:12', '2026-01-13 02:07:12'),
(6, 2026, '17 agustus', '2026-08-18', '2026-08-19', 'Biro Digi', '', '5000000.00', '', 'Taufik', NULL, '4500000.00', '', 'Terlaksana', '2026-01-13 02:11:12', '2026-01-13 02:12:03'),
(7, 2026, 'Sosialisasi ', '2026-01-15', '2026-01-16', 'Inspektorat', '', '1000000.00', '', 'Budi', NULL, '0.00', '', 'Terlaksana', '2026-01-13 02:37:43', '2026-01-13 07:19:33'),
(8, 2025, 'P.Perjanjian Kinerja 2025', '2025-01-01', '2025-01-01', 'Biro Perencanaan dan Kerjasama', '', '0.00', '', 'Kabag TU', NULL, '0.00', '', 'Terlaksana', '2026-01-14 04:05:06', '2026-01-14 04:05:06'),
(9, 2026, 'Senergi', '2026-02-16', '2026-02-17', 'Deputi 5', '', '12000000.00', '', 'vira', NULL, '10000000.00', '', 'Tidak Terlaksana', '2026-01-19 08:11:49', '2026-01-22 08:37:07'),
(10, 2026, 'CKG', '2026-01-20', '2026-01-20', 'Biro Digi', '', '67000000.00', '', 'Rehan', NULL, '45000000.00', '', 'Tidak Terlaksana', '2026-01-19 08:22:59', '2026-01-22 08:36:52'),
(11, 2026, 'Pancasila', '2026-01-21', '2026-01-21', 'Biro Digi', '', '0.00', '', 'Budi', NULL, '0.00', '', 'Penugasan Tambahan', '2026-01-20 03:37:42', '2026-01-22 08:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `program_kerja_detail_anggaran`
--

CREATE TABLE `program_kerja_detail_anggaran` (
  `id` int UNSIGNED NOT NULL,
  `program_kerja_id` int UNSIGNED NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `jumlah` decimal(15,2) NOT NULL DEFAULT '0.00',
  `bukti_dukung` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_kerja_dokumen`
--

CREATE TABLE `program_kerja_dokumen` (
  `id` int UNSIGNED NOT NULL,
  `program_kerja_id` int UNSIGNED NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tipe_dokumen` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_kerja`
--
ALTER TABLE `program_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_kerja_detail_anggaran`
--
ALTER TABLE `program_kerja_detail_anggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_kerja_detail_anggaran_program_kerja_id_foreign` (`program_kerja_id`);

--
-- Indexes for table `program_kerja_dokumen`
--
ALTER TABLE `program_kerja_dokumen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_kerja_dokumen_program_kerja_id_foreign` (`program_kerja_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `program_kerja`
--
ALTER TABLE `program_kerja`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `program_kerja_detail_anggaran`
--
ALTER TABLE `program_kerja_detail_anggaran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_kerja_dokumen`
--
ALTER TABLE `program_kerja_dokumen`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `program_kerja_detail_anggaran`
--
ALTER TABLE `program_kerja_detail_anggaran`
  ADD CONSTRAINT `program_kerja_detail_anggaran_program_kerja_id_foreign` FOREIGN KEY (`program_kerja_id`) REFERENCES `program_kerja` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `program_kerja_dokumen`
--
ALTER TABLE `program_kerja_dokumen`
  ADD CONSTRAINT `program_kerja_dokumen_program_kerja_id_foreign` FOREIGN KEY (`program_kerja_id`) REFERENCES `program_kerja` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
