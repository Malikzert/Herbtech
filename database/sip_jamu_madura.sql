-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Bulan Mei 2026 pada 15.42
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `sip_jamu_madura`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `defect_categories`
--

CREATE TABLE `defect_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `defect_categories`
--

INSERT INTO `defect_categories` (`id`, `name`, `severity`, `created_at`, `updated_at`) VALUES
(1, 'Botol Penyok', 'low', '2026-03-01 01:00:00', '2026-03-01 01:00:00'),
(2, 'Label Miring / Rusak', 'medium', '2026-03-01 01:00:00', '2026-03-01 01:00:00'),
(3, 'Segel Bocor', 'critical', '2026-03-01 01:00:00', '2026-03-01 01:00:00'),
(4, 'Warna Tidak Standar', 'high', '2026-03-01 01:00:00', '2026-03-01 01:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `finished_goods_inventories`
--

CREATE TABLE `finished_goods_inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_added` int(11) NOT NULL,
  `expired_date` date DEFAULT NULL,
  `storage_location` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `finished_goods_inventories`
--

INSERT INTO `finished_goods_inventories` (`id`, `production_id`, `product_id`, `quantity_added`, `expired_date`, `storage_location`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 98, '2027-03-20', 'Gudang A - Rak 1', '2026-03-20 08:00:00', '2026-03-20 08:00:00'),
(2, 2, 2, 120, '2026-10-05', 'Gudang A - Rak 2', '2026-04-05 09:30:00', '2026-04-05 09:30:00'),
(3, 14, 3, 1, '2026-11-09', 'warehouse', '2026-05-09 05:35:33', '2026-05-09 05:35:33'),
(4, 18, 13, 10000, '2026-11-20', 'warehouse', '2026-05-20 09:45:30', '2026-05-20 09:45:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_add_google_fields_to_users_table', 1),
(5, '2026_04_17_100001_create_defect_categories_table', 1),
(6, '2026_04_17_100002_create_products_table', 1),
(7, '2026_04_17_100003_create_raw_materials_table', 1),
(8, '2026_04_17_100004_create_productions_table', 1),
(9, '2026_04_17_100005_create_production_materials_table', 1),
(10, '2026_04_17_100006_create_quality_controls_table', 1),
(11, '2026_04_17_100007_create_qc_defects_table', 1),
(12, '2026_04_17_100008_create_finished_goods_inventories_table', 1),
(13, '2026_04_17_100009_add_user_and_rework_to_productions_table', 1),
(14, '2026_04_17_100010_add_action_to_quality_controls_table', 1),
(15, '2026_04_22_100001_add_missing_columns', 1),
(16, '2026_04_22_143710_add_category_to_products_table', 2),
(17, '2026_04_23_000000_add_notes_to_quality_controls_table', 2),
(18, '2026_04_24_100000_create_recipes_table', 3),
(19, '2026_04_24_100001_add_unit_to_recipes_table', 3),
(20, '2026_04_24_100002_add_unit_to_production_materials_table', 4),
(21, '2026_05_09_000001_add_scheduling_columns_to_productions', 5),
(22, '2026_05_09_000002_add_target_fields_and_pending_status', 6),
(23, '2026_05_09_112955_add_rework_status_to_productions_table', 7),
(24, '2026_05_14_000001_add_image_to_raw_materials_and_products', 8),
(25, '2026_05_20_000001_create_schedulings_table', 9),
(26, '2026_05_20_000040_add_recom_date_to_schedulings_table', 10),
(27, '2026_05_21_000001_rename_category_to_jeniss_in_products_table', 11),
(28, '2026_05_21_000002_create_raw_material_qcs_table', 12),
(29, '2026_05_21_000003_add_qc_status_to_raw_materials_table', 12),
(30, '2026_05_24_000001_add_is_active_to_raw_materials_table', 13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('dwirsk6@gmail.com', '$2y$12$gpEckNvXryHJ4qafB6QStuQ8B7gVI3OlNtqsJntCurxqqBgdL6rhi', '2026-05-11 05:06:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `productions`
--

CREATE TABLE `productions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `batch_number` varchar(100) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `target_quantity` int(11) NOT NULL DEFAULT 0,
  `actual_quantity` int(10) UNSIGNED DEFAULT 0,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `target_date` datetime DEFAULT NULL,
  `status` enum('draft','pending','in_progress','qc_check','rework','completed','cancelled') NOT NULL DEFAULT 'draft',
  `priority_level` tinyint(3) UNSIGNED NOT NULL DEFAULT 50,
  `estimated_duration` int(10) UNSIGNED DEFAULT NULL,
  `algorithm_generated` tinyint(1) NOT NULL DEFAULT 0,
  `scheduled_start` datetime DEFAULT NULL,
  `scheduled_end` datetime DEFAULT NULL,
  `schedule_notes` text DEFAULT NULL,
  `fitness_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fitness_data`)),
  `pic_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rework_of` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `productions`
--

INSERT INTO `productions` (`id`, `batch_number`, `product_id`, `target_quantity`, `actual_quantity`, `start_date`, `end_date`, `target_date`, `status`, `priority_level`, `estimated_duration`, `algorithm_generated`, `scheduled_start`, `scheduled_end`, `schedule_notes`, `fitness_data`, `pic_name`, `created_at`, `updated_at`, `user_id`, `rework_of`) VALUES
(1, 'BATCH-260320-001', 1, 100, 100, '2026-03-20 07:00:00', '2026-03-20 14:00:00', NULL, 'completed', 50, NULL, 0, NULL, NULL, NULL, NULL, 'Operator Produksi', '2026-03-19 23:50:00', '2026-03-20 07:05:00', 2, NULL),
(2, 'BATCH-260405-002', 2, 120, 120, '2026-04-05 08:00:00', '2026-04-05 15:30:00', NULL, 'completed', 50, NULL, 0, NULL, NULL, NULL, NULL, 'Operator Produksi', '2026-04-05 00:45:00', '2026-04-05 08:40:00', 2, NULL),
(3, 'BATCH-260418-003', 3, 150, 150, '2026-04-18 07:30:00', '2026-04-18 16:00:00', NULL, 'qc_check', 50, NULL, 0, NULL, NULL, NULL, NULL, 'Operator Produksi', '2026-04-18 00:20:00', '2026-04-18 09:05:00', 2, NULL),
(4, 'BATCH-260422-004', 1, 200, NULL, '2026-04-22 08:00:00', NULL, NULL, 'qc_check', 50, NULL, 0, NULL, NULL, NULL, NULL, 'Operator Produksi', '2026-04-22 00:55:00', '2026-04-22 09:28:11', 2, NULL),
(5, 'BATCH-260422-010', 2, 0, NULL, '2026-04-23 23:13:00', '2026-04-22 16:23:35', NULL, 'cancelled', 50, NULL, 0, NULL, NULL, NULL, NULL, 'apa?', '2026-04-22 09:13:36', '2026-04-22 09:23:35', 2, NULL),
(6, 'BATCH-260422-011', 2, 0, NULL, '2026-04-22 16:22:07', '2026-04-22 16:25:45', NULL, 'completed', 50, NULL, 0, NULL, NULL, NULL, NULL, 'Hakim', '2026-04-22 09:22:01', '2026-04-22 09:25:45', 2, NULL),
(7, 'BATCH-230426-687', 1, 0, NULL, '2026-04-23 01:32:00', NULL, NULL, 'in_progress', 50, NULL, 0, NULL, NULL, NULL, NULL, 'Operator Produksi', '2026-04-22 18:33:20', '2026-04-22 18:33:20', 2, NULL),
(8, 'BATCH-230426-911', 1, 0, NULL, '2026-04-23 02:05:00', '2026-04-23 02:13:04', NULL, 'completed', 50, NULL, 0, NULL, NULL, NULL, NULL, 'Operator Produksi', '2026-04-22 19:05:27', '2026-04-22 19:13:04', 2, NULL),
(9, 'BATCH-230426-254', 1, 0, NULL, '2026-04-23 02:29:00', '2026-04-24 11:05:02', NULL, 'completed', 50, NULL, 0, NULL, NULL, NULL, NULL, 'Operator Produksi', '2026-04-22 19:29:26', '2026-04-24 04:05:02', 2, NULL),
(10, 'BATCH-090526-012', 2, 1, 0, '2026-05-09 11:08:00', '2026-05-09 11:13:46', NULL, 'cancelled', 50, NULL, 0, NULL, NULL, NULL, NULL, 'sip jamumadura', '2026-05-09 04:08:53', '2026-05-09 04:13:46', 4, NULL),
(11, 'BATCH-090526-362', 3, 1, 0, '2026-05-09 11:11:00', NULL, NULL, 'in_progress', 50, NULL, 0, NULL, NULL, NULL, NULL, 'sip jamumadura', '2026-05-09 04:12:10', '2026-05-09 04:12:10', 4, NULL),
(12, 'BATCH-090526-056', 3, 5, 4, '2026-05-09 11:46:50', '2026-05-09 11:48:22', NULL, 'rework', 50, NULL, 0, NULL, NULL, NULL, NULL, 'sip jamumadura', '2026-05-09 04:40:32', '2026-05-09 04:48:22', 4, NULL),
(13, 'BATCH-090526-644', 10, 50, 0, '2026-05-09 08:00:00', '2026-05-09 12:24:00', NULL, 'in_progress', 50, 264, 1, '2026-05-09 08:00:00', '2026-05-09 12:24:00', 'Dijadwalkan oleh algoritma genetika. Prioritas: 50', '{\"deadline_score\":0,\"material_score\":0,\"machine_score\":0}', 'sip jamumadura', '2026-05-09 04:44:28', '2026-05-09 05:29:55', 4, NULL),
(14, 'BATCH-090526-056-R', 3, 1, 1, '2026-05-09 14:24:00', '2026-05-09 12:35:33', NULL, 'completed', 50, 6, 1, '2026-05-09 14:24:00', '2026-05-09 14:30:00', 'Dijadwalkan oleh algoritma genetika. Prioritas: 50', '{\"deadline_score\":0,\"material_score\":0,\"machine_score\":0}', 'sip jamumadura', '2026-05-09 04:48:22', '2026-05-09 05:35:33', 4, 12),
(17, 'BATCH-200526-868', 13, 10000, 1, '2026-05-20 16:38:00', '2026-05-20 16:40:30', NULL, 'cancelled', 50, NULL, 0, NULL, NULL, NULL, NULL, 'abd malik', '2026-05-20 09:39:02', '2026-05-20 09:40:30', 4, NULL),
(18, 'BATCH-200526-027', 13, 10000, 10000, '2026-05-20 16:43:00', '2026-05-20 16:45:30', NULL, 'completed', 50, NULL, 0, NULL, NULL, NULL, NULL, 'abd malik', '2026-05-20 09:43:51', '2026-05-20 09:45:30', 4, NULL),
(19, 'BATCH-200526-905', 13, 10000, 0, '2026-05-20 16:53:40', NULL, NULL, 'in_progress', 50, NULL, 0, NULL, NULL, NULL, NULL, 'abd malik', '2026-05-20 09:52:43', '2026-05-20 09:53:40', 4, NULL),
(20, 'BATCH-210526-554', 54, 50, 0, '2026-05-24 15:22:06', NULL, NULL, 'in_progress', 50, NULL, 0, NULL, NULL, NULL, NULL, 'abd malik', '2026-05-20 19:28:30', '2026-05-24 08:22:06', 4, NULL),
(21, 'BATCH-210526-020', 54, 5, 0, '2026-05-24 14:47:46', NULL, NULL, 'in_progress', 50, NULL, 0, NULL, NULL, NULL, NULL, 'abd malik', '2026-05-20 19:30:26', '2026-05-24 07:47:46', 4, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `production_materials`
--

CREATE TABLE `production_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `raw_material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_used` decimal(15,2) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'gram',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `production_materials`
--

INSERT INTO `production_materials` (`id`, `production_id`, `raw_material_id`, `quantity_used`, `unit`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5.00, 'gram', '2026-03-20 00:15:00', '2026-03-20 00:15:00'),
(2, 1, 4, 100.00, 'gram', '2026-03-20 00:15:00', '2026-03-20 00:15:00'),
(3, 2, 3, 4.50, 'gram', '2026-04-05 01:15:00', '2026-04-05 01:15:00'),
(4, 2, 5, 2.00, 'gram', '2026-04-05 01:15:00', '2026-04-05 01:15:00'),
(5, 2, 4, 120.00, 'gram', '2026-04-05 01:15:00', '2026-04-05 01:15:00'),
(6, 3, 2, 6.00, 'gram', '2026-04-18 00:45:00', '2026-04-18 00:45:00'),
(7, 3, 4, 150.00, 'gram', '2026-04-18 00:45:00', '2026-04-18 00:45:00'),
(8, 7, 1, 90.00, 'gram', '2026-04-22 18:33:20', '2026-04-22 18:33:20'),
(9, 7, 3, 50.00, 'gram', '2026-04-22 18:33:20', '2026-04-22 18:33:20'),
(10, 8, 1, 20.00, 'gram', '2026-04-22 19:05:27', '2026-04-22 19:05:27'),
(11, 9, 1, 90.00, 'gram', '2026-04-22 19:29:26', '2026-04-22 19:29:26'),
(12, 10, 3, 0.40, 'gram', '2026-05-09 04:08:53', '2026-05-09 04:08:53'),
(13, 10, 14, 0.50, 'gram', '2026-05-09 04:08:53', '2026-05-09 04:08:53'),
(14, 10, 5, 0.20, 'gram', '2026-05-09 04:08:53', '2026-05-09 04:08:53'),
(15, 10, 4, 1.00, 'gram', '2026-05-09 04:08:53', '2026-05-09 04:08:53'),
(16, 11, 2, 0.60, 'gram', '2026-05-09 04:12:10', '2026-05-09 04:12:10'),
(17, 11, 12, 0.25, 'gram', '2026-05-09 04:12:10', '2026-05-09 04:12:10'),
(18, 11, 4, 1.00, 'gram', '2026-05-09 04:12:10', '2026-05-09 04:12:10'),
(19, 12, 2, 3.00, 'gram', '2026-05-09 04:40:32', '2026-05-09 04:40:32'),
(20, 12, 12, 1.25, 'gram', '2026-05-09 04:40:32', '2026-05-09 04:40:32'),
(21, 12, 4, 5.00, 'gram', '2026-05-09 04:40:32', '2026-05-09 04:40:32'),
(22, 13, 18, 20.00, 'gram', '2026-05-09 04:44:28', '2026-05-09 04:44:28'),
(25, 17, 13, 2000.00, 'gram', '2026-05-20 09:39:02', '2026-05-20 09:39:02'),
(26, 18, 13, 2000.00, 'gram', '2026-05-20 09:43:51', '2026-05-20 09:43:51'),
(27, 19, 13, 2000.00, 'gram', '2026-05-20 09:52:43', '2026-05-20 09:52:43'),
(28, 20, 1, 100.00, 'gram', '2026-05-20 19:28:30', '2026-05-20 19:28:30'),
(29, 20, 2, 250.00, 'gram', '2026-05-20 19:28:30', '2026-05-20 19:28:30'),
(30, 21, 1, 10.00, 'gram', '2026-05-20 19:30:26', '2026-05-20 19:30:26'),
(31, 21, 2, 25.00, 'gram', '2026-05-20 19:30:26', '2026-05-20 19:30:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sku_code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `jeniss` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `sku_code`, `name`, `jeniss`, `description`, `image`, `unit`, `created_at`, `updated_at`) VALUES
(1, 'PRD-PL01', 'Jamu Pegal Linu', 'Cair', 'Meredakan pegal linu dan nyeri otot', NULL, 'Botol', '2026-03-01 01:00:00', '2026-03-01 01:00:00'),
(2, 'PRD-BK02', 'Beras Kencur Madura', 'Cair', 'Menyegarkan badan dan menambah nafsu makan', NULL, 'Botol', '2026-03-05 02:15:00', '2026-03-05 02:15:00'),
(3, 'PRD-KM03', 'Kunyit Asam', 'Cair', 'Melancarkan peredaran darah dan menyegarkan', NULL, 'Botol', '2026-03-10 03:30:00', '2026-03-10 03:30:00'),
(4, 'PRD-BS01', 'Beras Kencur Super', 'Cair', 'Jamu tradisional beras kencur untuk stamina', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(5, 'PRD-KA01', 'Kunyit Asam Segar', 'Cair', 'Minuman pelancar haid dan penyegar tubuh', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(6, 'PRD-TJ01', 'Temulawak Madu', 'Cair', 'Membantu menambah nafsu makan anak', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(7, 'PRD-GP01', 'Gula Jahe Instan', 'Bubuk', 'Minuman hangat jahe merah dalam bentuk bubuk', NULL, 'Sachet', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(8, 'PRD-SK01', 'Sari Kunyit Bubuk', 'Bubuk', 'Bubuk kunyit murni tanpa ampas', NULL, 'Sachet', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(9, 'PRD-MK01', 'Madu Kurma Stamina', 'Cair', 'Suplemen energi dari madu dan kurma', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(10, 'PRD-DS01', 'Kapsul Daun Sirih', 'Kapsul', 'Antiseptik alami dalam bentuk kapsul', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(11, 'PRD-SL01', 'Sambiloto Bitter', 'Cair', 'Jamu pahitan untuk membersihkan darah', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(12, 'PRD-KM01', 'Kayu Manis Serbuk', 'Bubuk', 'Bubuk kayu manis untuk aromatik dan kesehatan', NULL, 'Pouch', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(13, 'PRD-SB01', 'Sirup Brotowali', 'Cair', 'Sirup herbal untuk membantu diabetes', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(14, 'PRD-JK01', 'Jamu Kuat Lelaki', 'Kapsul', 'Meningkatkan vitalitas pria dewasa', NULL, 'Kotak', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(15, 'PRD-WC01', 'Wanita Cantik (Galian Singset)', 'Bubuk', 'Menjaga bentuk tubuh dan kesehatan kulit', NULL, 'Pouch', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(16, 'PRD-BT01', 'Batuk Herbal Kids', 'Cair', 'Obat batuk alami khusus anak-anak', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(17, 'PRD-NS01', 'Nafsu Makan Plus', 'Cair', 'Multivitamin herbal dengan sari temulawak', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(18, 'PRD-TG01', 'Teh Gaharu Madura', 'Bubuk', 'Teh herbal relaksasi dari daun gaharu', NULL, 'Kotak', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(19, 'PRD-KL01', 'Kapsul Kelor Pahit', 'Kapsul', 'Suplemen nutrisi tinggi dari daun kelor', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(20, 'PRD-SN01', 'Sari Najis (Sirih Pinang)', 'Cair', 'Ramuan tradisional untuk kesehatan mulut', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(21, 'PRD-KK01', 'Kumis Kucing Diuretik', 'Kapsul', 'Membantu melancarkan buang air kecil', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(22, 'PRD-SJ01', 'Susu Jahe Merah', 'Bubuk', 'Minuman susu dengan ekstrak jahe merah', NULL, 'Sachet', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(23, 'PRD-AB01', 'Asam Lambung Relief', 'Cair', 'Meredakan mual dan perih di ulu hati', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(24, 'PRD-PC01', 'Pahit Campur Madura', 'Cair', 'Jamu pahitan khas Madura untuk gatal-gatal', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(25, 'PRD-LJ01', 'Lulur Jawa Herbal', 'Bubuk', 'Lulur tradisional untuk perawatan kulit', NULL, 'Pouch', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(26, 'PRD-TM01', 'Teh Mahkota Dewa', 'Bubuk', 'Teh herbal untuk detoksifikasi tubuh', NULL, 'Kotak', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(27, 'PRD-KP01', 'Kapsul Pasak Bumi', 'Kapsul', 'Ekstrak pasak bumi untuk stamina pria', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(28, 'PRD-ID01', 'Imunitas Dewasa', 'Cair', 'Meningkatkan daya tahan tubuh sehari-hari', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(29, 'PRD-JR01', 'Jamu Reumatik Otot', 'Cair', 'Meredakan nyeri sendi dan asam urat', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(30, 'PRD-KC01', 'Kencur Wangi Powder', 'Bubuk', 'Kencur bubuk premium untuk bumbu dan jamu', NULL, 'Sachet', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(31, 'PRD-SA01', 'Sari Alang-alang', 'Cair', 'Pereda panas dalam dan sariawan', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(32, 'PRD-MZ01', 'Minyak Zaitun Herbal', 'Cair', 'Minyak zaitun murni untuk konsumsi', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(33, 'PRD-BP01', 'Biji Pinang Bubuk', 'Bubuk', 'Bubuk biji pinang untuk kesehatan gigi', NULL, 'Pouch', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(34, 'PRD-HS01', 'Habbatussauda Oil', 'Cair', 'Minyak jintan hitam murni dalam botol', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(35, 'PRD-LB01', 'Lidah Buaya Jelly', 'Cair', 'Minuman jelly lidah buaya segar', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(36, 'PRD-MG01', 'Manggis Kulit Kapsul', 'Kapsul', 'Antioksidan tinggi dari ekstrak kulit manggis', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(37, 'PRD-DS02', 'Daun Salam Kolesterol', 'Kapsul', 'Membantu menurunkan kadar kolesterol', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(38, 'PRD-JG01', 'Jamu Gatal Madura', 'Cair', 'Ramuan khusus untuk penyakit kulit', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(39, 'PRD-SM01', 'Sari Manggis Cair', 'Cair', 'Minuman kesehatan kaya antioksidan', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(40, 'PRD-TS01', 'Teh Secang Merah', 'Bubuk', 'Minuman kayu secang khas keraton', NULL, 'Kotak', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(41, 'PRD-AK01', 'Akar Alang-alang Kapsul', 'Kapsul', 'Meredakan infeksi saluran kemih', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(42, 'PRD-BL01', 'Bunga Lawang Powder', 'Bubuk', 'Rempah bubuk bunga lawang kualitas ekspor', NULL, 'Sachet', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(43, 'PRD-LH01', 'Lada Hitam Kasar', 'Bubuk', 'Bumbu lada hitam untuk stamina', NULL, 'Pouch', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(44, 'PRD-MN01', 'Meniran Imun', 'Cair', 'Sirup meniran untuk kekebalan tubuh', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(45, 'PRD-PG01', 'Pegagan Brain Booster', 'Kapsul', 'Meningkatkan konsentrasi dan daya ingat', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(46, 'PRD-BL02', 'Bidara Laut Kapsul', 'Kapsul', 'Obat tradisional untuk demam dan malaria', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(47, 'PRD-SC01', 'Sirup Secang Wangi', 'Cair', 'Sirup penyegar tubuh kayu secang', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(48, 'PRD-KD01', 'Kunyit Dewasa Plus', 'Cair', 'Kunyit asam dengan tambahan madu', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(49, 'PRD-JB01', 'Jati Belanda Pelangsing', 'Kapsul', 'Membantu menurunkan berat badan alami', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(50, 'PRD-GP02', 'Gula Palem Jahe', 'Bubuk', 'Gula sehat dengan aroma jahe', NULL, 'Pouch', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(51, 'PRD-MD01', 'Madu Herbal Anak', 'Cair', 'Madu dengan campuran ekstrak sayuran', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(52, 'PRD-ST01', 'Stevia Drop Liquid', 'Cair', 'Pemanis alami nol kalori', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(53, 'PRD-CJ01', 'Cabe Jamu Stamina', 'Kapsul', 'Kapsul cabe jawa untuk penghangat tubuh', NULL, 'Botol', '2026-05-09 11:00:15', '2026-05-09 11:00:15'),
(54, 'PRD-BS01222', 'jamu jerawat-', 'Cair', 'untuk membereskan jerawat', NULL, 'Botol', '2026-05-20 19:24:36', '2026-05-20 19:24:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `qc_defects`
--

CREATE TABLE `qc_defects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qc_id` bigint(20) UNSIGNED NOT NULL,
  `defect_cat_id` bigint(20) UNSIGNED NOT NULL,
  `defect_quantity` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `qc_defects`
--

INSERT INTO `qc_defects` (`id`, `qc_id`, `defect_cat_id`, `defect_quantity`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2, 'Label terpasang miring pada batch pertama', '2026-03-20 07:35:00', '2026-03-20 07:35:00'),
(2, 3, 3, 5, 'Segel tutup botol tidak rapat (bocor halus)', '2026-04-18 09:45:00', '2026-04-18 09:45:00'),
(3, 3, 4, 5, 'Warna terlalu pekat dari standar', '2026-04-18 09:45:00', '2026-04-18 09:45:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `quality_controls`
--

CREATE TABLE `quality_controls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `inspector_name` varchar(255) NOT NULL,
  `inspected_at` datetime NOT NULL DEFAULT current_timestamp(),
  `total_inspected` int(11) NOT NULL,
  `total_passed` int(11) NOT NULL,
  `total_rejected` int(11) NOT NULL,
  `status` enum('passed','partial_reject','full_reject') NOT NULL,
  `action` enum('release','rework','reject') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `quality_controls`
--

INSERT INTO `quality_controls` (`id`, `production_id`, `inspector_name`, `inspected_at`, `total_inspected`, `total_passed`, `total_rejected`, `status`, `action`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dwi Rizky', '2026-03-20 14:30:00', 100, 98, 2, 'partial_reject', 'release', NULL, '2026-03-20 07:35:00', '2026-03-20 07:35:00'),
(2, 2, 'Dwi Rizky', '2026-04-05 16:00:00', 120, 120, 0, 'passed', 'release', NULL, '2026-04-05 09:10:00', '2026-04-05 09:10:00'),
(3, 3, 'Dwi Rizky', '2026-04-18 16:30:00', 150, 140, 10, 'partial_reject', 'rework', NULL, '2026-04-18 09:45:00', '2026-04-18 09:45:00'),
(4, 10, 'sip jamumadura', '2026-05-09 11:13:46', 1, 0, 1, 'full_reject', 'reject', NULL, '2026-05-09 04:13:46', '2026-05-09 04:13:46'),
(5, 12, 'sip jamumadura', '2026-05-09 11:48:22', 5, 4, 1, 'partial_reject', 'rework', NULL, '2026-05-09 04:48:22', '2026-05-09 04:48:22'),
(6, 14, 'sip jamumadura', '2026-05-09 12:35:33', 1, 1, 0, 'passed', 'release', NULL, '2026-05-09 05:35:33', '2026-05-09 05:35:33'),
(7, 17, 'abd malik', '2026-05-20 16:40:30', 10000, 1, 9999, 'partial_reject', 'reject', NULL, '2026-05-20 09:40:30', '2026-05-20 09:40:30'),
(8, 18, 'abd malik', '2026-05-20 16:45:30', 10000, 10000, 0, 'passed', 'release', NULL, '2026-05-20 09:45:30', '2026-05-20 09:45:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `raw_materials`
--

CREATE TABLE `raw_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `type` enum('herbal','packaging','additive') NOT NULL,
  `unit` varchar(50) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `qc_status` varchar(255) NOT NULL DEFAULT 'waiting',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `price_per_unit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `current_stock` decimal(15,2) NOT NULL DEFAULT 0.00,
  `min_stock_level` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `raw_materials`
--

INSERT INTO `raw_materials` (`id`, `name`, `sku`, `type`, `unit`, `supplier`, `qc_status`, `is_active`, `image`, `expired_date`, `price_per_unit`, `current_stock`, `min_stock_level`, `created_at`, `updated_at`) VALUES
(1, 'Jahe Merah', 'RM-JH01', 'herbal', 'Kg', 'Petani Lokal Pamekasan', 'accept', 1, NULL, NULL, 0.00, 3800.00, 10.00, '2026-03-01 01:00:00', '2026-05-24 08:22:06'),
(2, 'Kunyit', 'RM-KY01', 'herbal', 'Kg', 'Petani Lokal Sumenep', 'accept', 1, NULL, NULL, 0.00, 19749.00, 15.00, '2026-03-02 01:30:00', '2026-05-24 08:22:06'),
(3, 'Kencur', 'RM-KC01', 'herbal', 'Kg', 'Pengepul Bangkalan', 'accept', 1, NULL, NULL, 0.00, 30.00, 10.00, '2026-03-03 02:00:00', '2026-05-24 08:39:48'),
(4, 'Botol Plastik 250ml', 'RM-BT250', 'packaging', 'Pcs', 'PT Kemasan Indo', 'accept', 1, NULL, NULL, 0.00, 1500.00, 500.00, '2026-03-04 03:00:00', '2026-05-24 08:39:48'),
(5, 'Gula Aren Asli', 'RM-GA01', 'additive', 'Kg', 'Koperasi Tani Madura', 'accept', 1, NULL, NULL, 0.00, 25.00, 5.00, '2026-03-05 04:00:00', '2026-05-24 08:39:48'),
(6, 'Temulawak', 'RM-TL01', 'herbal', 'Kg', 'Petani Lokal Bangkalan', 'accept', 1, NULL, '2027-05-20', 15000.00, 40.00, 10.00, '2026-05-09 10:58:27', '2026-05-20 22:38:12'),
(7, 'Sambiloto', 'RM-SB01', 'herbal', 'Kg', 'Petani Lokal Sumenep', 'accept', 1, NULL, '2027-04-15', 22000.00, 12.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 07:26:40'),
(8, 'Daun Sirih', 'RM-DS01', 'herbal', 'Kg', 'Petani Lokal Pamekasan', 'accept', 1, NULL, '2026-12-10', 12000.00, 25.50, 8.00, '2026-05-09 10:58:27', '2026-05-24 08:37:22'),
(9, 'Kayu Manis', 'RM-KM01', 'herbal', 'Kg', 'Supplier Rempah Surabaya', 'accept', 1, NULL, '2028-01-01', 45000.00, 15.00, 3.00, '2026-05-09 10:58:27', '2026-05-24 08:37:29'),
(10, 'Cengkeh', 'RM-CG01', 'herbal', 'Kg', 'Supplier Rempah Surabaya', 'accept', 1, NULL, '2028-06-15', 95000.00, 8.00, 2.00, '2026-05-09 10:58:27', '2026-05-24 08:37:36'),
(11, 'Kapulaga', 'RM-KP01', 'herbal', 'Kg', 'Petani Lokal Sampang', 'accept', 1, NULL, '2027-11-20', 150000.00, 5.00, 1.00, '2026-05-09 10:58:27', '2026-05-24 08:37:41'),
(12, 'Lempuyang', 'RM-LP01', 'herbal', 'Kg', 'Petani Lokal Bangkalan', 'accept', 1, NULL, '2027-03-05', 18000.00, 30.00, 10.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(13, 'Botol Plastik 500ml', 'RM-BT500', 'packaging', 'Pcs', 'PT Kemasan Indo', 'accept', 1, NULL, NULL, 1200.00, 1000.00, 200.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(14, 'Stiker Label Kunyit', 'RM-ST01', 'packaging', 'Pcs', 'Percetakan Madura', 'accept', 1, NULL, NULL, 150.00, 5000.00, 500.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(15, 'Gula Pasir', 'RM-GP01', 'additive', 'Kg', 'Distributor Sembako', 'accept', 1, NULL, '2027-12-31', 17000.00, 100.00, 20.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(16, 'Madu Hutan', 'RM-MD01', 'additive', 'Ltr', 'Pengepul Madu Madura', 'accept', 1, NULL, '2028-05-10', 120000.00, 20.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(17, 'Asam Jawa', 'RM-AJ01', 'herbal', 'Kg', 'Pengepul Bangkalan', 'accept', 1, NULL, '2027-08-22', 25000.00, 18.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(18, 'Jahe Emprit', 'RM-JE01', 'herbal', 'Kg', 'Petani Lokal Pamekasan', 'accept', 1, NULL, '2027-02-28', 20000.00, 40.00, 15.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(19, 'Beras Merah', 'RM-BM01', 'herbal', 'Kg', 'Kelompok Tani Madura', 'accept', 1, NULL, '2027-01-15', 18000.00, 45.00, 10.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(20, 'Kardus Packing Besar', 'RM-KD01', 'packaging', 'Pcs', 'PT Kemasan Indo', 'accept', 1, NULL, NULL, 4500.00, 150.00, 30.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(21, 'Lakban Bening', 'RM-LK01', 'packaging', 'Roll', 'Toko ATK Grosir', 'accept', 1, NULL, NULL, 12000.00, 24.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(22, 'Garam Industri', 'RM-GR01', 'additive', 'Kg', 'PT Garam Madura', 'accept', 1, NULL, '2029-01-01', 5000.00, 200.00, 50.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(23, 'Brotowali', 'RM-BR01', 'herbal', 'Kg', 'Petani Lokal Sampang', 'accept', 1, NULL, '2026-10-15', 15000.00, 10.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(24, 'Kumis Kucing', 'RM-KK01', 'herbal', 'Kg', 'Petani Lokal Sumenep', 'accept', 1, NULL, '2026-11-20', 30000.00, 7.50, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(25, 'Daun Kelor Bubuk', 'RM-DK01', 'herbal', 'Kg', 'UKM Kelor Madura', 'accept', 1, NULL, '2027-09-05', 85000.00, 12.00, 3.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(26, 'Botol Kaca 250ml', 'RM-BK250', 'packaging', 'Pcs', 'PT Kaca Abadi', 'accept', 1, NULL, NULL, 3500.00, 300.00, 50.00, '2026-05-09 10:58:27', '2026-05-24 08:33:00'),
(27, 'Tutup Botol Gold', 'RM-TB01', 'packaging', 'Pcs', 'PT Kemasan Indo', 'accept', 1, NULL, NULL, 200.00, 3000.00, 300.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(28, 'Pandan Wangi', 'RM-PW01', 'herbal', 'Kg', 'Petani Lokal Bangkalan', 'accept', 1, NULL, '2026-08-10', 10000.00, 15.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(29, 'Secang', 'RM-SC01', 'herbal', 'Kg', 'Supplier Rempah Surabaya', 'accept', 1, NULL, '2028-02-14', 35000.00, 20.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(30, 'Adas Pulo', 'RM-AP01', 'herbal', 'Kg', 'Supplier Rempah Surabaya', 'accept', 1, NULL, '2027-12-01', 65000.00, 5.50, 2.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(31, 'Pulasari', 'RM-PS01', 'herbal', 'Kg', 'Supplier Rempah Surabaya', 'accept', 1, NULL, '2027-11-10', 70000.00, 4.00, 2.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(32, 'Cabe Jamu', 'RM-CJ01', 'herbal', 'Kg', 'Petani Lokal Pamekasan', 'accept', 1, NULL, '2027-05-12', 40000.00, 35.00, 10.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(33, 'Daun Jati Belanda', 'RM-JB01', 'herbal', 'Kg', 'Petani Lokal Sumenep', 'accept', 1, NULL, '2026-12-25', 28000.00, 10.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(34, 'Jeruk Nipis', 'RM-JN01', 'herbal', 'Kg', 'Petani Lokal Bangkalan', 'accept', 1, NULL, '2026-06-30', 15000.00, 22.00, 10.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(35, 'Alang-alang', 'RM-AA01', 'herbal', 'Kg', 'Petani Lokal Sampang', 'accept', 1, NULL, '2027-04-20', 12000.00, 30.00, 10.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(36, 'Keling', 'RM-KL01', 'herbal', 'Kg', 'Pengepul Bangkalan', 'accept', 1, NULL, '2027-07-07', 22000.00, 15.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(37, 'Plastik Shrink', 'RM-SK01', 'packaging', 'Roll', 'PT Kemasan Indo', 'accept', 1, NULL, NULL, 85000.00, 10.00, 2.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(38, 'Gula Singkong', 'RM-GS01', 'additive', 'Ltr', 'Koperasi Tani Madura', 'accept', 1, NULL, '2027-10-10', 45000.00, 15.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(39, 'Stevia Cair', 'RM-SV01', 'additive', 'Ltr', 'Distributor Bahan Kimia', 'accept', 1, NULL, '2027-01-20', 250000.00, 5.00, 1.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(40, 'Biji Pinang', 'RM-BP01', 'herbal', 'Kg', 'Petani Lokal Pamekasan', 'accept', 1, NULL, '2028-03-15', 30000.00, 25.00, 10.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(41, 'Habbatussauda', 'RM-HB01', 'herbal', 'Kg', 'Importir Herbal', 'accept', 1, NULL, '2028-09-30', 180000.00, 10.00, 2.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(42, 'Minyak Zaitun', 'RM-MZ01', 'additive', 'Ltr', 'Importir Herbal', 'accept', 1, NULL, '2028-11-12', 150000.00, 12.00, 3.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(43, 'Jerawat Daun', 'RM-JD01', 'herbal', 'Kg', 'Petani Lokal Sumenep', 'accept', 1, NULL, '2027-02-02', 17000.00, 20.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(44, 'Lidah Buaya', 'RM-LB01', 'herbal', 'Kg', 'Petani Lokal Bangkalan', 'accept', 1, NULL, '2026-07-15', 8000.00, 50.00, 15.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(45, 'Kardus Packing Kecil', 'RM-KD02', 'packaging', 'Pcs', 'PT Kemasan Indo', 'accept', 1, NULL, NULL, 2500.00, 500.00, 100.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(46, 'Bubble Wrap', 'RM-BW01', 'packaging', 'Roll', 'Toko ATK Grosir', 'accept', 1, NULL, NULL, 95000.00, 5.00, 1.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(47, 'Pewarna Alami Hijau', 'RM-PAH1', 'additive', 'Ltr', 'Distributor Bahan Alam', 'accept', 1, NULL, '2027-04-01', 60000.00, 10.00, 2.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(48, 'Pengawet Organik', 'RM-PO01', 'additive', 'Ltr', 'Distributor Bahan Alam', 'accept', 1, NULL, '2027-05-05', 120000.00, 8.00, 2.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(49, 'Akar Alang-alang', 'RM-AK01', 'herbal', 'Kg', 'Petani Lokal Pamekasan', 'accept', 1, NULL, '2027-08-18', 15000.00, 40.00, 10.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(50, 'Bunga Lawang', 'RM-BL01', 'herbal', 'Kg', 'Supplier Rempah Surabaya', 'accept', 1, NULL, '2028-10-10', 85000.00, 6.00, 2.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(51, 'Ketumbar', 'RM-KT01', 'herbal', 'Kg', 'Supplier Rempah Surabaya', 'accept', 1, NULL, '2028-12-01', 35000.00, 20.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(52, 'Lada Hitam', 'RM-LH01', 'herbal', 'Kg', 'Supplier Rempah Surabaya', 'accept', 1, NULL, '2028-11-20', 110000.00, 10.00, 3.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(53, 'Meniran', 'RM-MN01', 'herbal', 'Kg', 'Petani Lokal Bangkalan', 'accept', 1, NULL, '2026-09-12', 25000.00, 15.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(54, 'Bidara Laut', 'RM-BL02', 'herbal', 'Kg', 'Pengepul Bangkalan', 'accept', 1, NULL, '2027-11-11', 45000.00, 5.00, 2.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(55, 'Pegagan', 'RM-PG01', 'herbal', 'Kg', 'Petani Lokal Sampang', 'accept', 1, NULL, '2026-10-30', 32000.00, 12.00, 5.00, '2026-05-09 10:58:27', '2026-05-24 08:39:48'),
(57, 'Temulawak A', 'RM-000057-01', 'herbal', 'Kg', 'pt aeminareka', 'accept', 1, NULL, NULL, 0.00, 999.00, 50.00, '2026-05-20 21:33:04', '2026-05-20 22:28:24'),
(58, 'jerebu', 'RM-000058-01', 'additive', 'Kg', 'PT KP%', 'accept', 1, NULL, NULL, 0.00, 8000.00, 40.00, '2026-05-24 06:47:24', '2026-05-24 08:36:53'),
(59, 'ink', 'RM-000059-01', 'herbal', 'Botol', 'ptmk', 'accept', 1, NULL, NULL, 0.00, 700.00, 80.00, '2026-05-24 07:38:56', '2026-05-24 07:39:36'),
(60, 'qwerty', 'RM-000060-01', 'packaging', 'sa', 'pr tama', 'accept', 1, NULL, NULL, 0.00, 399.00, 4.00, '2026-05-24 07:54:21', '2026-05-24 08:36:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `raw_material_qcs`
--

CREATE TABLE `raw_material_qcs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `raw_material_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_qty_checked` int(11) NOT NULL DEFAULT 0,
  `good_qty` int(11) NOT NULL DEFAULT 0,
  `bad_qty` int(11) NOT NULL DEFAULT 0,
  `qc_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'waiting',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `raw_material_qcs`
--

INSERT INTO `raw_material_qcs` (`id`, `raw_material_id`, `user_id`, `total_qty_checked`, `good_qty`, `bad_qty`, `qc_percentage`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 57, 4, 999, 888, 111, 88.89, 'passed', NULL, '2026-05-20 22:28:24', '2026-05-20 22:28:24'),
(2, 6, 4, 40, 40, 0, 100.00, 'passed', NULL, '2026-05-20 22:38:12', '2026-05-20 22:38:12'),
(3, 6, 4, 40, 40, 0, 100.00, 'passed', NULL, '2026-05-20 22:38:13', '2026-05-20 22:38:13'),
(4, 58, 3, 0, 0, 0, 0.00, 'waiting', NULL, '2026-05-24 06:47:25', '2026-05-24 06:47:25'),
(5, 7, 4, 12, 12, 0, 100.00, 'passed', NULL, '2026-05-24 07:26:40', '2026-05-24 07:26:40'),
(6, 58, 4, 8000, 0, 8000, 0.00, 'waiting', NULL, '2026-05-24 07:28:48', '2026-05-24 07:28:48'),
(7, 58, 4, 8000, 6000, 2000, 75.00, 'rework', NULL, '2026-05-24 07:33:25', '2026-05-24 07:33:25'),
(8, 58, 4, 8000, 7000, 1000, 0.13, 'waiting', NULL, '2026-05-24 07:33:43', '2026-05-24 07:33:43'),
(9, 59, 3, 0, 0, 0, 0.00, 'waiting', NULL, '2026-05-24 07:38:56', '2026-05-24 07:38:56'),
(10, 59, 4, 800, 700, 100, 87.50, 'passed', NULL, '2026-05-24 07:39:36', '2026-05-24 07:39:36'),
(11, 60, 3, 0, 0, 0, 0.00, 'waiting', NULL, '2026-05-24 07:54:21', '2026-05-24 07:54:21'),
(12, 1, 4, 4000, 3900, 100, 97.50, 'passed', NULL, '2026-05-24 08:21:02', '2026-05-24 08:21:02'),
(13, 2, 4, 20000, 19999, 1, 100.00, 'passed', NULL, '2026-05-24 08:21:27', '2026-05-24 08:21:27'),
(14, 26, 4, 300, 300, 0, 100.00, 'passed', NULL, '2026-05-24 08:33:00', '2026-05-24 08:33:00'),
(15, 60, 4, 400, 399, 1, 99.75, 'passed', NULL, '2026-05-24 08:36:09', '2026-05-24 08:36:09'),
(16, 58, 4, 8000, 8000, 0, 100.00, 'passed', NULL, '2026-05-24 08:36:53', '2026-05-24 08:36:53'),
(17, 8, 4, 25, 25, 0, 100.00, 'passed', NULL, '2026-05-24 08:37:22', '2026-05-24 08:37:22'),
(18, 9, 4, 15, 15, 0, 100.00, 'passed', NULL, '2026-05-24 08:37:29', '2026-05-24 08:37:29'),
(19, 10, 4, 8, 8, 0, 100.00, 'passed', NULL, '2026-05-24 08:37:36', '2026-05-24 08:37:36'),
(20, 11, 4, 5, 5, 0, 100.00, 'passed', NULL, '2026-05-24 08:37:41', '2026-05-24 08:37:41'),
(21, 3, 4, 30, 30, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(22, 4, 4, 1500, 1500, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(23, 5, 4, 25, 25, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(24, 12, 4, 30, 30, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(25, 13, 4, 1000, 1000, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(26, 14, 4, 5000, 5000, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(27, 15, 4, 100, 100, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(28, 16, 4, 20, 20, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(29, 17, 4, 18, 18, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(30, 18, 4, 40, 40, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(31, 19, 4, 45, 45, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(32, 20, 4, 150, 150, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(33, 21, 4, 24, 24, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(34, 22, 4, 200, 200, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(35, 23, 4, 10, 10, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(36, 24, 4, 7, 7, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(37, 25, 4, 12, 12, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(38, 27, 4, 3000, 3000, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(39, 28, 4, 15, 15, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(40, 29, 4, 20, 20, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(41, 30, 4, 5, 5, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(42, 31, 4, 4, 4, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(43, 32, 4, 35, 35, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(44, 33, 4, 10, 10, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(45, 34, 4, 22, 22, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(46, 35, 4, 30, 30, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(47, 36, 4, 15, 15, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(48, 37, 4, 10, 10, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(49, 38, 4, 15, 15, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(50, 39, 4, 5, 5, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(51, 40, 4, 25, 25, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(52, 41, 4, 10, 10, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(53, 42, 4, 12, 12, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(54, 43, 4, 20, 20, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(55, 44, 4, 50, 50, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(56, 45, 4, 500, 500, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(57, 46, 4, 5, 5, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(58, 47, 4, 10, 10, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(59, 48, 4, 8, 8, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(60, 49, 4, 40, 40, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(61, 50, 4, 6, 6, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(62, 51, 4, 20, 20, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(63, 52, 4, 10, 10, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(64, 53, 4, 15, 15, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(65, 54, 4, 5, 5, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48'),
(66, 55, 4, 12, 12, 0, 100.00, 'passed', 'Bulk pass otomatis.', '2026-05-24 08:39:48', '2026-05-24 08:39:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `recipes`
--

CREATE TABLE `recipes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `raw_material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_needed` decimal(15,2) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'gram',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `recipes`
--

INSERT INTO `recipes` (`id`, `product_id`, `raw_material_id`, `quantity_needed`, `unit`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0.40, 'Kg', '2026-04-24 03:55:53', '2026-04-24 03:55:53'),
(2, 1, 2, 0.20, 'Kg', '2026-04-24 03:55:53', '2026-04-24 03:55:53'),
(3, 1, 1, 0.50, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(4, 1, 2, 0.30, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(5, 2, 3, 0.40, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(6, 2, 14, 0.50, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(7, 2, 5, 0.20, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(8, 3, 2, 0.60, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(9, 3, 12, 0.25, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(10, 4, 6, 0.45, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(11, 4, 11, 0.10, 'Ltr', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(12, 5, 13, 0.70, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(13, 5, 10, 0.50, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(14, 8, 8, 0.80, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(15, 9, 7, 0.30, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(16, 7, 11, 0.50, 'Ltr', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(17, 1, 4, 1.00, 'Pcs', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(18, 2, 4, 1.00, 'Pcs', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(19, 3, 4, 1.00, 'Pcs', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(20, 4, 4, 1.00, 'Pcs', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(21, 5, 9, 1.00, 'Pcs', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(22, 10, 18, 0.40, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(23, 11, 27, 0.20, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(24, 12, 12, 0.30, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(25, 13, 13, 0.20, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(26, 14, 6, 0.40, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(27, 15, 23, 0.10, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(28, 16, 20, 0.50, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(29, 17, 17, 0.05, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(30, 18, 19, 0.30, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(31, 19, 13, 0.40, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(32, 20, 12, 0.40, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(33, 21, 18, 0.60, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(34, 22, 2, 0.20, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(35, 23, 24, 0.15, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(36, 24, 30, 0.25, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(37, 25, 11, 0.05, 'Ltr', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(38, 26, 1, 0.30, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(39, 27, 3, 0.50, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(40, 28, 30, 0.40, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(41, 29, 37, 1.00, 'Ltr', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(42, 30, 35, 0.50, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(43, 31, 36, 0.40, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(44, 32, 39, 0.60, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(45, 33, 31, 0.30, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(46, 34, 28, 0.40, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(47, 35, 18, 0.50, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(48, 36, 31, 0.40, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(49, 37, 24, 0.30, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(50, 38, 44, 0.25, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(51, 39, 45, 0.20, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(52, 40, 47, 0.30, 'Kg', '2026-05-09 11:01:42', '2026-05-09 11:01:42'),
(53, 54, 1, 2.00, 'Kg', '2026-05-20 19:26:42', '2026-05-20 19:26:42'),
(54, 54, 2, 5.00, 'Kg', '2026-05-20 19:26:42', '2026-05-20 19:26:42'),
(55, 6, 58, 50.00, 'Kg', '2026-05-24 07:30:05', '2026-05-24 07:30:05'),
(56, 6, 57, 90.00, 'Kg', '2026-05-24 07:35:08', '2026-05-24 07:35:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedulings`
--

CREATE TABLE `schedulings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `batch_number_recommendation` varchar(100) DEFAULT NULL,
  `recommended_quantity` int(11) NOT NULL DEFAULT 1,
  `priority_order` int(11) DEFAULT NULL,
  `is_recommended` tinyint(1) NOT NULL DEFAULT 1,
  `critical_material_name` varchar(255) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `status` enum('draft','approved','converted_to_production') NOT NULL DEFAULT 'draft',
  `recom_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `schedulings`
--

INSERT INTO `schedulings` (`id`, `product_id`, `user_id`, `batch_number_recommendation`, `recommended_quantity`, `priority_order`, `is_recommended`, `critical_material_name`, `rejection_reason`, `status`, `recom_date`, `created_at`, `updated_at`) VALUES
(1, 13, 3, NULL, 10000, 1, 1, 'Botol Plastik 500ml', NULL, 'converted_to_production', '2026-05-21', '2026-05-20 02:35:19', '2026-05-20 09:46:31'),
(2, 26, 3, NULL, 166, 2, 1, 'Jahe Merah', NULL, 'approved', '2026-05-21', '2026-05-20 02:35:19', '2026-05-20 02:35:19'),
(3, 1, 3, NULL, 98, 3, 1, 'Jahe Merah', NULL, 'approved', '2026-05-21', '2026-05-20 02:35:19', '2026-05-20 18:43:51'),
(4, 22, 3, NULL, 222, 4, 1, 'Kunyit', NULL, 'draft', '2026-05-25', '2026-05-20 02:35:19', '2026-05-20 02:35:19'),
(5, 39, 3, NULL, 2500, 5, 1, 'Kardus Packing Kecil', NULL, 'draft', '2026-05-26', '2026-05-20 02:35:19', '2026-05-20 02:35:19'),
(6, 19, 3, NULL, 4999, 6, 1, 'Botol Plastik 500ml', NULL, 'draft', '2026-05-28', '2026-05-20 02:35:19', '2026-05-20 02:35:19'),
(7, 16, 3, NULL, 300, 7, 1, 'Kardus Packing Besar', NULL, 'draft', '2026-05-28', '2026-05-20 02:35:19', '2026-05-20 02:35:19'),
(8, 27, 3, NULL, 60, 8, 1, 'Kencur', NULL, 'draft', '2026-05-25', '2026-05-20 02:35:19', '2026-05-20 02:35:19'),
(9, 2, 3, NULL, 73, 9, 1, 'Kencur', NULL, 'draft', '2026-05-25', '2026-05-20 02:35:19', '2026-05-20 02:35:19'),
(10, 11, 3, NULL, 15000, 10, 1, 'Tutup Botol Gold', NULL, 'approved', '2026-05-21', '2026-05-20 02:35:19', '2026-05-20 18:43:51'),
(11, 26, 3, NULL, 12666, 1, 1, 'Jahe Merah', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09'),
(12, 16, 3, NULL, 300, 2, 1, 'Kardus Packing Besar', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09'),
(13, 54, 3, NULL, 1899, 3, 1, 'Jahe Merah', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09'),
(14, 6, 3, NULL, 11, 4, 1, 'Temulawak A', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09'),
(15, 22, 3, NULL, 98720, 5, 1, 'Kunyit', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09'),
(16, 13, 3, NULL, 5000, 6, 1, 'Botol Plastik 500ml', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09'),
(17, 19, 3, NULL, 2499, 7, 1, 'Botol Plastik 500ml', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09'),
(18, 27, 3, NULL, 60, 8, 1, 'Kencur', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09'),
(19, 1, 3, NULL, 1500, 9, 1, 'Botol Plastik 250ml', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09'),
(20, 29, 3, NULL, 10, 10, 1, 'Plastik Shrink', NULL, 'draft', '2026-05-24', '2026-05-24 08:41:09', '2026-05-24 08:41:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('nFRjQVVlJ7VpL02KwTivgkMxpO2I0PBIMJEACWde', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJsem1RcmtBUmVSd0pwcHZLNGNpdFNsbkZmZmU0OGMxZXJkUnBFTWlsIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9zY2hlZHVsaW5nIiwicm91dGUiOiJhZG1pbi5zY2hlZHVsaW5nLmluZGV4In0sInN0YXRlIjoidUh3N3A2S2N2dEpmemlGa0JzWTdvMWZ6dk13cnk2TnFCaURoQjZlMCIsImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjozfQ==', 1779637271),
('yS8S4dMp0tfBiS0sFrNE0kjDHcJiilx4qS1YnQbF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI1WkdtTFZKWG5MUVF4MDY2OWVkSGUydUpTa1BoMUEwZDFRbThGU0pXIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1779637246);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','operator') NOT NULL DEFAULT 'operator',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `avatar`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ABDUL MALIK', 'malikdark17@gmail.com', NULL, NULL, '2026-04-22 08:23:17', '$2y$12$19.1WbMEWYDb7iAg9pjm7uofkD4T2aq4Sf4CPa.tK9ckJ4fVolw4a', 'admin', NULL, '2026-04-22 08:23:17', '2026-05-13 03:20:56'),
(2, 'irna khalda', 'irnakhalda@gmail.com', '117635335087900429412', 'https://lh3.googleusercontent.com/a/ACg8ocL-HGIw7m31sVjfRXcjP2STkE_umOTy4hsfEyP41ml2hphCliRU=s96-c', '2026-04-22 08:23:17', '$2y$12$weqIFuFYchC.tQP.Cj6xw.PdUn.t3568Feg2I1D8/nwCF9/6Z3X/y', 'operator', NULL, '2026-04-22 08:23:17', '2026-05-13 03:36:14'),
(3, 'Dwi Rizky', 'dwirsk6@gmail.com', '106501761167972218698', 'https://lh3.googleusercontent.com/a/ACg8ocJdIuTejbBzGcDM774mSICj5zJSZyfyeA-ImBv6O7SQu5D9ODs=s96-c', '2026-04-22 08:23:17', '$2y$12$qB9OijCFWt2oyTN6IZkE5eDzq.Y4tjkqzp/lUGZlFdN4u4lvFHINq', 'admin', NULL, '2026-04-22 08:23:17', '2026-04-27 06:44:06'),
(4, 'abd malik', 'sipjamumadura@gmail.com', '103204997807971475553', 'https://lh3.googleusercontent.com/a/ACg8ocLDNnHVcSPOoFTU6AgpfHTvER9sXBgvQq5xsDzoYc8gxYu7-w=s96-c', '2026-05-09 02:51:24', '$2y$12$zv1zaWTM8vVw1CZYPh0FGuZdVHxOr5lH/bJlbBU0ihSPoUHD3vnjy', 'operator', NULL, '2026-05-09 02:51:24', '2026-05-13 03:27:32'),
(5, 'Ali Ridwan', 'aliridwan15@gmail.com', NULL, NULL, NULL, '$2y$12$iEThiLaE0M4OyP.5bekN5O386oJx9h1DR/nAINmHz22fGGlFWpati', 'admin', NULL, '2026-05-13 03:12:16', '2026-05-13 03:12:16'),
(6, 'Ali Ridwan', 'shafaryu35@gmail.com', NULL, NULL, NULL, '$2y$12$QQrrJQARzMAblIeevJH1/evB4PXhLuAbzbbibCVi8OlRFLWU3AwZ6', 'operator', NULL, '2026-05-13 03:14:13', '2026-05-13 03:14:13');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `defect_categories`
--
ALTER TABLE `defect_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `finished_goods_inventories`
--
ALTER TABLE `finished_goods_inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `finished_goods_inventories_production_id_foreign` (`production_id`),
  ADD KEY `finished_goods_inventories_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productions_batch_number_unique` (`batch_number`),
  ADD KEY `productions_product_id_foreign` (`product_id`),
  ADD KEY `productions_user_id_foreign` (`user_id`),
  ADD KEY `productions_rework_of_foreign` (`rework_of`);

--
-- Indeks untuk tabel `production_materials`
--
ALTER TABLE `production_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_materials_production_id_foreign` (`production_id`),
  ADD KEY `production_materials_raw_material_id_foreign` (`raw_material_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_code_unique` (`sku_code`);

--
-- Indeks untuk tabel `qc_defects`
--
ALTER TABLE `qc_defects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qc_defects_qc_id_foreign` (`qc_id`),
  ADD KEY `qc_defects_defect_cat_id_foreign` (`defect_cat_id`);

--
-- Indeks untuk tabel `quality_controls`
--
ALTER TABLE `quality_controls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quality_controls_production_id_foreign` (`production_id`);

--
-- Indeks untuk tabel `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `raw_material_qcs`
--
ALTER TABLE `raw_material_qcs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raw_material_qcs_raw_material_id_foreign` (`raw_material_id`),
  ADD KEY `raw_material_qcs_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `raw_material_id` (`raw_material_id`);

--
-- Indeks untuk tabel `schedulings`
--
ALTER TABLE `schedulings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedulings_product_id_index` (`product_id`),
  ADD KEY `schedulings_user_id_index` (`user_id`),
  ADD KEY `schedulings_status_index` (`status`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `defect_categories`
--
ALTER TABLE `defect_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `finished_goods_inventories`
--
ALTER TABLE `finished_goods_inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `productions`
--
ALTER TABLE `productions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `production_materials`
--
ALTER TABLE `production_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `qc_defects`
--
ALTER TABLE `qc_defects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `quality_controls`
--
ALTER TABLE `quality_controls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `raw_material_qcs`
--
ALTER TABLE `raw_material_qcs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT untuk tabel `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `schedulings`
--
ALTER TABLE `schedulings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `finished_goods_inventories`
--
ALTER TABLE `finished_goods_inventories`
  ADD CONSTRAINT `finished_goods_inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `finished_goods_inventories_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `productions`
--
ALTER TABLE `productions`
  ADD CONSTRAINT `productions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_rework_of_foreign` FOREIGN KEY (`rework_of`) REFERENCES `productions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `productions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `production_materials`
--
ALTER TABLE `production_materials`
  ADD CONSTRAINT `production_materials_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_materials_raw_material_id_foreign` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `qc_defects`
--
ALTER TABLE `qc_defects`
  ADD CONSTRAINT `qc_defects_defect_cat_id_foreign` FOREIGN KEY (`defect_cat_id`) REFERENCES `defect_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `qc_defects_qc_id_foreign` FOREIGN KEY (`qc_id`) REFERENCES `quality_controls` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `quality_controls`
--
ALTER TABLE `quality_controls`
  ADD CONSTRAINT `quality_controls_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `raw_material_qcs`
--
ALTER TABLE `raw_material_qcs`
  ADD CONSTRAINT `raw_material_qcs_raw_material_id_foreign` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `raw_material_qcs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipes_ibfk_2` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `schedulings`
--
ALTER TABLE `schedulings`
  ADD CONSTRAINT `schedulings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedulings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
