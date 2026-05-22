-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: lerd-mysql
-- Generation Time: May 22, 2026 at 03:04 AM
-- Server version: 8.4.9
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pelayanan`
--

-- --------------------------------------------------------

--
-- Table structure for table `arrival_records`
--

CREATE TABLE `arrival_records` (
  `id` bigint UNSIGNED NOT NULL,
  `citizen_nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','PENDING','REJECTED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `previous_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `arrival_date` date NOT NULL,
  `recorded_by` bigint UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `arrival_records`
--

INSERT INTO `arrival_records` (`id`, `citizen_nik`, `status`, `previous_address`, `arrival_date`, `recorded_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, '1231231231231231', 'ACTIVE', 'eyJpdiI6IjNhaTIrenR5aWh6SUpYZGw2VGhHR1E9PSIsInZhbHVlIjoiRVB2OWpBV3VscFRFMHAycWk3UXUydz09IiwibWFjIjoiMDUxOGZlYWFjNWI3NjdlZTdmNGNjMmMxMDVlMzljMzI0ZmQ3M2NiODRjMjhkZDNhMjFlNTQ0ZGUyNjU2ZGExYiIsInRhZyI6IiJ9', '2026-05-11', 2, NULL, '2026-05-11 15:05:26', '2026-05-11 15:05:26'),
(2, '1231231231231232', 'ACTIVE', 'eyJpdiI6IkovODFLbDYvVG1kQ0FzVnkrcFpTOUE9PSIsInZhbHVlIjoieWJrTlhLODBrTlNCYjVJS25UMUIyUT09IiwibWFjIjoiYzhmZDBkOWUwZDQ1NjE1YjA0ZjQyZTA0MTNhOTQzMDM0YTc1MWViY2M0ZjBkZjQyZGFmNDIyMGQ2NjRhN2FlNSIsInRhZyI6IiJ9', '2026-05-11', 2, NULL, '2026-05-11 15:12:19', '2026-05-11 15:12:19');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_table` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_value` json DEFAULT NULL,
  `new_value` json DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `target_table`, `target_id`, `old_value`, `new_value`, `timestamp`) VALUES
(1, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-04 05:34:35'),
(2, NULL, 'CREATE_USER', 'users', '5', NULL, '{\"id\": 5, \"name\": \"Elian Hoeger\", \"email\": \"elian-hoeger@desa.id\", \"desa_id\": 1, \"role_id\": 2, \"password\": \"$2y$12$BqBmoCIFcxD1KyDa6YlnC.axnIPE/uKgLItK/CduQ0VQhcum56Kua\", \"is_active\": true, \"created_at\": \"2026-05-04 05:46:27\", \"updated_at\": \"2026-05-04 05:46:27\"}', '2026-05-04 05:46:27'),
(3, NULL, 'CREATE_USER', 'users', '6', NULL, '{\"id\": 6, \"name\": \"Milford Ruecker\", \"email\": \"milford-ruecker@desa.id\", \"desa_id\": 2, \"role_id\": 2, \"password\": \"$2y$12$EhiQLDsU3VgZVou7ZW8jt.qRMrELR5Z5pkTCcSkRTbujBcyxwq08K\", \"is_active\": true, \"created_at\": \"2026-05-04 05:46:27\", \"updated_at\": \"2026-05-04 05:46:27\"}', '2026-05-04 05:46:27'),
(4, NULL, 'CREATE_USER', 'users', '7', NULL, '{\"id\": 7, \"name\": \"Richmond Champlin\", \"email\": \"richmond-champlin@desa.id\", \"desa_id\": 3, \"role_id\": 2, \"password\": \"$2y$12$9h9gQYpBJ0rmlYJ1x8YRFeCYWJLgnk0DpWPyOhay63TM523er/Fzm\", \"is_active\": true, \"created_at\": \"2026-05-04 05:46:27\", \"updated_at\": \"2026-05-04 05:46:27\"}', '2026-05-04 05:46:27'),
(5, NULL, 'CREATE_USER', 'users', '8', NULL, '{\"id\": 8, \"name\": \"Kadin Simonis DVM\", \"email\": \"kadin-simonis-dvm@kecamatan.id\", \"role_id\": 2, \"password\": \"$2y$12$99bflV3YsJiWU0sBZhj.hOYqjSPrsuOG5b4uwImZVdibQClK9xoK2\", \"is_active\": true, \"created_at\": \"2026-05-04 05:46:27\", \"updated_at\": \"2026-05-04 05:46:27\", \"district_id\": 1}', '2026-05-04 05:46:27'),
(6, NULL, 'CREATE_USER', 'users', '9', NULL, '{\"id\": 9, \"name\": \"Mr. Robb Bins\", \"email\": \"mr-robb-bins@kecamatan.id\", \"role_id\": 2, \"password\": \"$2y$12$gv2Apg4VbcWXip8WgEuxfeEaiv3kvOPZKGBF0f52MvFvWle/JkY8O\", \"is_active\": true, \"created_at\": \"2026-05-04 05:46:27\", \"updated_at\": \"2026-05-04 05:46:27\", \"district_id\": 2}', '2026-05-04 05:46:27'),
(7, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:00:56'),
(8, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:06:21'),
(9, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:06:59'),
(10, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:12:09'),
(11, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:18:01'),
(12, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:23:01'),
(13, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:23:05'),
(14, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:23:59'),
(15, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:27:11'),
(16, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:27:21'),
(17, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 06:28:38'),
(18, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 07:07:45'),
(19, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 07:08:38'),
(20, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-04 07:11:07'),
(21, NULL, 'CREATE_CITIZEN', 'citizens', '9642371409431637', NULL, '{\"nik\": \"9642371409431637\", \"kontak\": \"082247994859\", \"desa_id\": 3, \"tgl_lahir\": \"1997-05-25 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IlFLZStHcWlPaTFockI5SmdObDR5TlE9PSIsInZhbHVlIjoiK3kxZm0wVmNSWVE5NmFldXhjb01sQ1ViSnhQTzZLbzhsUGFxK25ObUdkeFhNNW1VSnVJWDJXZVRIdXV5MkFFRiIsIm1hYyI6ImIwMzlmOTk5ZDA1MzM3YWQxNWVhZjQ0NGEwNGJkNGVmNmFmNmRhODYyMTg4NWZhNDdlNDZkNDMyZDUwODFjOGEiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Madge Strosin\", \"__display_name\": \"Madge Strosin\", \"household_card_id\": \"4118538042647206\"}', '2026-05-04 07:36:39'),
(22, NULL, 'CREATE_CITIZEN', 'citizens', '7602237318489366', NULL, '{\"nik\": \"7602237318489366\", \"kontak\": \"082188009747\", \"desa_id\": 3, \"tgl_lahir\": \"2017-03-22 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6ImVsRlZUc2NLckNYdXhha2FMWERENkE9PSIsInZhbHVlIjoiRnB0UlVCUnBIWmNNVGk2S0phZERYMWRHMzUwdEJJZjNGZWtWQmg4N1pQaGRaUlhvcVAzcSs1NGtRZ01lUEx4UCIsIm1hYyI6IjM5ZmViYzU0ODE5NWNkMDMwYjdiOTMyMzhjN2UwMjA1MDQyNTc4ZWM1NDljMTJhNTY0MzdlYzM3MGExNDk4MzgiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Kenny Heidenreich IV\", \"__display_name\": \"Kenny Heidenreich IV\", \"household_card_id\": \"4118538042647206\"}', '2026-05-04 07:36:39'),
(23, NULL, 'CREATE_CITIZEN', 'citizens', '4187465571124897', NULL, '{\"nik\": \"4187465571124897\", \"kontak\": \"086313853078\", \"desa_id\": 3, \"tgl_lahir\": \"2013-11-19 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6ImYrbFNjT2c4WFBOOFArNTFWMGp3V2c9PSIsInZhbHVlIjoiMzF6UUxFNWd4Q3pySzBIelNBL0VOVU1RQTJQN21OVW1NZkRncWtSQWIvamE5SElidlNNYzhzT0VFeXNmaW1USCIsIm1hYyI6IjE3NjZmNjUwZDI2NDUwMmYxNTBiMWNkNDNhNmFhM2EwNzgwYTgxZGU3ZTgzZTdmZDgyNWRmMzM3NmY4YWE3ODUiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Aubrey Weimann\", \"__display_name\": \"Aubrey Weimann\", \"household_card_id\": \"4118538042647206\"}', '2026-05-04 07:36:39'),
(24, NULL, 'CREATE_CITIZEN', 'citizens', '0548397596571350', NULL, '{\"nik\": \"0548397596571350\", \"kontak\": \"083113437500\", \"desa_id\": 3, \"tgl_lahir\": \"1985-03-05 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IkY3aHVnZHBKMXJqVFdXc1RXblI5K3c9PSIsInZhbHVlIjoibVI3RFQ1MEJETmpUdU5rdkQ2cG9lSTI1bk9FQVRGNTNCdWlvOTAxb280eUlDS0xHSGw5eHVFZFZ5amVvbGpuMiIsIm1hYyI6ImY4MDBhODViZGEzYWQ5ZTYwODMxYmNiOTQ0NTEwYzU5MmJmMWQ1MTA4MTZiYjU4MDU2YTY0MDJjYmQ4ZWIyYmEiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Mrs. Connie Murray III\", \"__display_name\": \"Mrs. Connie Murray III\", \"household_card_id\": \"4118538042647206\"}', '2026-05-04 07:36:39'),
(25, NULL, 'CREATE_CITIZEN', 'citizens', '1926958198088365', NULL, '{\"nik\": \"1926958198088365\", \"kontak\": \"088714477879\", \"desa_id\": 3, \"tgl_lahir\": \"2005-10-01 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IlZMc0FyalorbEJ3VnJUU2ZGM3J2aHc9PSIsInZhbHVlIjoiU2J2TjVDV0NybjBmd1diNmg2WWtuUFpFYnZDejdVbTJGY25ZWVJrRHVDUkh1RVZBKzIxYXhESTZDUnovb3JxLyIsIm1hYyI6ImNiNTgzMjI1ZmQ4ZDQzMzg3ZDhjODI0ZDU2NTNlNzEyNWRkYzIzZGFjN2U4NWY1YmE2MjViNzllOWI0YjdkMjEiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Dr. Hailey Nicolas DVM\", \"__display_name\": \"Dr. Hailey Nicolas DVM\", \"household_card_id\": \"4118538042647206\"}', '2026-05-04 07:36:39'),
(26, NULL, 'UPDATE_CITIZEN', 'citizens', '0548397596571350', '{\"nama_lengkap\": \"Mrs. Connie Murray III\"}', '{\"nama_lengkap\": \"Prof. Juvenal Smitham\", \"__display_name\": \"Prof. Juvenal Smitham\"}', '2026-05-04 07:36:39'),
(27, NULL, 'CREATE_CITIZEN', 'citizens', '5967910942197430', NULL, '{\"nik\": \"5967910942197430\", \"kontak\": \"080886862108\", \"desa_id\": 1, \"tgl_lahir\": \"2009-10-06 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IjljcXJrd2JiMzNCa0hKUzZES0wrN1E9PSIsInZhbHVlIjoiUEJtN3FyN1VCdWN5cW9MUkxYMTc3YkdXaFE1bkx4bnVCYjFDbXNyVDc4eHM5OUlJeWlwVXBveWJ6YlpJdWQrdCIsIm1hYyI6ImNkYjAwYTJlYjliMDIwMWY3NWRkYThhY2QwMzc4ZjgzYmU4MTRmMTAyYWMzMThkZjViMDhiM2MwNjk4OGUxZGEiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Mariela Kulas\", \"__display_name\": \"Mariela Kulas\", \"household_card_id\": \"7873743606870620\"}', '2026-05-04 07:36:39'),
(28, NULL, 'CREATE_CITIZEN', 'citizens', '7180364968353838', NULL, '{\"nik\": \"7180364968353838\", \"kontak\": \"088363522837\", \"desa_id\": 1, \"tgl_lahir\": \"1977-07-30 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6Im5oVE05SFUvRXNrQTdkZm1wSTFIYkE9PSIsInZhbHVlIjoiazBJcFV2UCtKWWtUY0VGUFFTUGRjSUVPTytQYndlWFZQK0FSdGpuMkE0MFYrd3hORHV1dFVpTEliNm5JdHZ6WCIsIm1hYyI6IjZhMjI3ODVjNmU0MjQ3YmY4NjIwNTAzOWU3ZjUzMGZmMmJkOTBlNzQ0MzdiZjg3MzQ5YWFhZTJiZjhlNzdlYmEiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Joesph Swift I\", \"__display_name\": \"Joesph Swift I\", \"household_card_id\": \"7873743606870620\"}', '2026-05-04 07:36:39'),
(29, NULL, 'UPDATE_CITIZEN', 'citizens', '5967910942197430', '{\"nama_lengkap\": \"Mariela Kulas\"}', '{\"nama_lengkap\": \"Jabari Reilly\", \"__display_name\": \"Jabari Reilly\"}', '2026-05-04 07:36:39'),
(30, NULL, 'CREATE_CITIZEN', 'citizens', '4505213597384420', NULL, '{\"nik\": \"4505213597384420\", \"kontak\": \"080525938081\", \"desa_id\": 3, \"tgl_lahir\": \"2014-02-08 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6ImcvV2xKc3ZEK1BwL0REb3JhY0thUWc9PSIsInZhbHVlIjoieFVWeWk3Qi9oSnh5MFhteEkydUJsMmdaWDh6N0REZVZTVzc2bzVyYVozTFFrbVZGWHZPTlZhd3pzUFJralYraCIsIm1hYyI6Ijk1N2MzZjY0NjdkMWNmMjEzMjc4M2Y0YjBiYjVjNjIwNmI5YzdmNWU1OTcxOGY0ZDAwMzE2ZjJmMDc0YmUyOGQiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Kari Barrows\", \"__display_name\": \"Kari Barrows\", \"household_card_id\": \"1660961957535760\"}', '2026-05-04 07:36:39'),
(31, NULL, 'CREATE_CITIZEN', 'citizens', '0237772649659152', NULL, '{\"nik\": \"0237772649659152\", \"kontak\": \"084727385306\", \"desa_id\": 3, \"tgl_lahir\": \"2003-08-17 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6Ijd3d0liN0djdkRRY3g1ZXFBKzdIQ3c9PSIsInZhbHVlIjoiTHp6NEdoeTJkU2lmOVhlejdtS2FHTVZJT3d3TEU0QlRHaXVRdTJvaDJQYnN4ckxMUHEweFE5Z1cxUlF6eU1qWSIsIm1hYyI6IjExOTNlNzFkY2U1OTM2ODU1M2EzODRlOGZmZDhlZTYwMWZjOTBkMzFlMTcxNDM5YWY0NTczNTNhYTI4OGU5YmQiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Tatum Price DVM\", \"__display_name\": \"Tatum Price DVM\", \"household_card_id\": \"1660961957535760\"}', '2026-05-04 07:36:39'),
(32, NULL, 'UPDATE_CITIZEN', 'citizens', '0237772649659152', '{\"nama_lengkap\": \"Tatum Price DVM\"}', '{\"nama_lengkap\": \"Adriel Heller\", \"__display_name\": \"Adriel Heller\"}', '2026-05-04 07:36:39'),
(33, NULL, 'CREATE_CITIZEN', 'citizens', '7437696059229065', NULL, '{\"nik\": \"7437696059229065\", \"kontak\": \"086354533635\", \"desa_id\": 3, \"tgl_lahir\": \"1990-06-27 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6InltWTRVOGUvckNaV2FudEd2eTZWcWc9PSIsInZhbHVlIjoiOEIyUGtIMVdHcER2M2pKZnErQ2w4WlVJMzBJdmtTb3BPanZzMTh4T1lpWEpEOUp1eU9xVjZneUxXd2tvWEl0SSIsIm1hYyI6IjA0NmE1NTY4YmNmNjhlNTkwN2IzYTU2M2Y3OWUyMGM1OTgwNTJlMTliYmU0OWQ1YWIzNGU4YmQ2NzRkOTI2NWQiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Harold Gerlach\", \"__display_name\": \"Harold Gerlach\", \"household_card_id\": \"3619504393732364\"}', '2026-05-04 07:36:39'),
(34, NULL, 'CREATE_CITIZEN', 'citizens', '5701813829625777', NULL, '{\"nik\": \"5701813829625777\", \"kontak\": \"089650228753\", \"desa_id\": 3, \"tgl_lahir\": \"1973-08-31 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IjgrbzdqMWpoY25HbjE3aG8vdGVsZEE9PSIsInZhbHVlIjoiM0dIdTZlaTRmQll6V0xiU1NqWUhFTmpaVkRiV2JZa3RGdDdrYVZHdEpmT0hSajVZQlBrRHVDNzNzK2RYVGY4VyIsIm1hYyI6IjRkZDdkZTIxM2M5NDM3YmM3MGJkMzNlNTk3MzYyMmQ1MjFmMDk1YzQ4YjYxMTY5YzhkZjczYjcwOWI1YjhjYzgiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Erling Ledner II\", \"__display_name\": \"Erling Ledner II\", \"household_card_id\": \"3619504393732364\"}', '2026-05-04 07:36:39'),
(35, NULL, 'UPDATE_CITIZEN', 'citizens', '5701813829625777', '{\"nama_lengkap\": \"Erling Ledner II\"}', '{\"nama_lengkap\": \"Jarrell Harvey\", \"__display_name\": \"Jarrell Harvey\"}', '2026-05-04 07:36:39'),
(36, NULL, 'CREATE_CITIZEN', 'citizens', '7393602997992159', NULL, '{\"nik\": \"7393602997992159\", \"kontak\": \"088947532674\", \"desa_id\": 3, \"tgl_lahir\": \"2024-12-17 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IklyQitJUytlTlpzQW9zZkljZFR5Snc9PSIsInZhbHVlIjoiUXNScnB6dUlndTYwNk1nc0pEcjc5Y2luWnBUb0Z6NEZHMXh5V05HMjJsVUVLSW5hQ3IvNGVkMnRHMGRwSnlPciIsIm1hYyI6IjU4MWQzZTJmMDJjOTgyOGE3MDM1NzgxYjQ4ZjcwNzVhMWJkYjgwODU1YjQ5ZThiNDUxMGE0M2FiMWNjMjk3ZDMiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Arno Marks\", \"__display_name\": \"Arno Marks\", \"household_card_id\": \"6988051632499646\"}', '2026-05-04 07:36:39'),
(37, NULL, 'CREATE_CITIZEN', 'citizens', '3377694025765557', NULL, '{\"nik\": \"3377694025765557\", \"kontak\": \"087484658623\", \"desa_id\": 3, \"tgl_lahir\": \"1978-11-23 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6Im4rT0xKa01oQ000V2FqelIyODdKaHc9PSIsInZhbHVlIjoiVHR2Z3BWWUw4ZDlFcnVacU5kQkkvLzFIcmdKRkIwejdWQTgvajFCUmEzNlNBWUJ3ZEhlNWdaSTYzNVRCVDNvTSIsIm1hYyI6ImMwOGIyOTM5Y2E0ZWIwYjFiYTZmZmM3OWQ0ODE2MTFmMDdiNjU2ODJmNzNkMGRhMjlmOTA5NzUxMTJlZThjZjkiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Mrs. Treva Becker I\", \"__display_name\": \"Mrs. Treva Becker I\", \"household_card_id\": \"6988051632499646\"}', '2026-05-04 07:36:39'),
(38, NULL, 'CREATE_CITIZEN', 'citizens', '6520016491495352', NULL, '{\"nik\": \"6520016491495352\", \"kontak\": \"088302346024\", \"desa_id\": 3, \"tgl_lahir\": \"1988-05-20 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IkFUdzJUU1JlRDhkT1BrNFN5cG5ZdlE9PSIsInZhbHVlIjoibzlFeFZyMUZuVXk1WERqdEJ6MUZEaklUbXZRazdoY3JmYzJtS3JkandLdmEvSVFqdlh1U1lmZHVWT3J4UHF1cyIsIm1hYyI6ImE2YTRjMjU3NWQ0ZGMxMzFjYTBkZjM1NzA2MDg2MTcwZjdjNmZlN2U5OWU2MDZkZDExNTAwMzZmMTdhMDNhNDkiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Prof. Lilian Haley\", \"__display_name\": \"Prof. Lilian Haley\", \"household_card_id\": \"6988051632499646\"}', '2026-05-04 07:36:39'),
(39, NULL, 'UPDATE_CITIZEN', 'citizens', '3377694025765557', '{\"nama_lengkap\": \"Mrs. Treva Becker I\"}', '{\"nama_lengkap\": \"Dr. Theo Schultz PhD\", \"__display_name\": \"Dr. Theo Schultz PhD\"}', '2026-05-04 07:36:39'),
(40, NULL, 'CREATE_CITIZEN', 'citizens', '6892704104239204', NULL, '{\"nik\": \"6892704104239204\", \"kontak\": \"088006154736\", \"desa_id\": 2, \"tgl_lahir\": \"2021-09-06 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6ImZiNzVKekZMa3pPUWJhSkFtc2J3MEE9PSIsInZhbHVlIjoiSEJXQkIwSlhGbEY1Q093d2ozRWRvdE4vd0NxRlVZVHh0YUhWaSswaXRJQktCSmhUcUpJK3cyYnExWHhEdGVvNCIsIm1hYyI6ImZlNWExZDExNzZkNzhjMTdhOWRlYjhlYmRkNjM5MGM1YjY5ZWVmZTE0ZTg5ZjEzNmFhNjgwOTg4MThkMzg3N2EiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Jennings Bechtelar\", \"__display_name\": \"Jennings Bechtelar\", \"household_card_id\": \"3790303525496946\"}', '2026-05-04 07:36:39'),
(41, NULL, 'CREATE_CITIZEN', 'citizens', '2764672858582329', NULL, '{\"nik\": \"2764672858582329\", \"kontak\": \"089153459751\", \"desa_id\": 2, \"tgl_lahir\": \"1998-09-22 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IkdjaDdzVHJFNUVlN3VTbXpIVzR3S3c9PSIsInZhbHVlIjoicVM3aG45aWJjMmZKa1JxQTR1U0xqNWRjaTR4Ym5DSkdxbzdzNTArdjh0Q2V5TGFGRUNJcHpmbkZZcGt3NHFYcSIsIm1hYyI6IjQ3ZTNiODE1NmQyMTgzYWY3NDZjNzc1ZTE2YTk1YWVhMTg5NWM0YjI1ZDYyYzJiNzZiZWVmNjEzMGNmODA0NmQiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Shannon Ankunding\", \"__display_name\": \"Shannon Ankunding\", \"household_card_id\": \"3790303525496946\"}', '2026-05-04 07:36:39'),
(42, NULL, 'CREATE_CITIZEN', 'citizens', '7774394540040179', NULL, '{\"nik\": \"7774394540040179\", \"kontak\": \"089927623265\", \"desa_id\": 2, \"tgl_lahir\": \"2008-08-14 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6Ilcwb2hpR1FVblloUFRTeElLZHRvcXc9PSIsInZhbHVlIjoiYlZ0YjM0TGtOR0JIYnBnZlZZL2J5TTg3TUdlNmpDTDZQT3FCcWZUNkwraGVUdnJ1N1h5K1lCSUZGZ01rMkRYMyIsIm1hYyI6IjQyNGFlYzgwYzU2MTI2ZTMxNmI4MjQ2NDBhODIwOTFhMzU0NTFkNWZjY2I5NjBkZTczMTZhOTkxNGVmYzE2M2YiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Miracle Veum\", \"__display_name\": \"Miracle Veum\", \"household_card_id\": \"3790303525496946\"}', '2026-05-04 07:36:39'),
(43, NULL, 'CREATE_CITIZEN', 'citizens', '5756004488963627', NULL, '{\"nik\": \"5756004488963627\", \"kontak\": \"083451207463\", \"desa_id\": 2, \"tgl_lahir\": \"1972-04-10 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IlBtMk5hT1RjdUZJTVV5RXRFOHI4OUE9PSIsInZhbHVlIjoieHdhR09GK0xOc0VOOWZJcnoxckhPMEI1V0ZQZEpJZWFNc2tkZmxFSkNHT0FPTkE1cVg1ekxaOGFiRENhRnNPcyIsIm1hYyI6IjM3ZWY5ZWJjODQxMTYzMGJmMzFjNmNlZGYwNWFiZDI0ODkwODczMDA5Mzk4Y2UxNmYxZmIyY2RkMGY0ZmQwMWEiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Aniya Grady Jr.\", \"__display_name\": \"Aniya Grady Jr.\", \"household_card_id\": \"3790303525496946\"}', '2026-05-04 07:36:39'),
(44, NULL, 'UPDATE_CITIZEN', 'citizens', '2764672858582329', '{\"nama_lengkap\": \"Shannon Ankunding\"}', '{\"nama_lengkap\": \"Prof. Angus Keeling V\", \"__display_name\": \"Prof. Angus Keeling V\"}', '2026-05-04 07:36:39'),
(45, NULL, 'CREATE_CITIZEN', 'citizens', '3596469487045524', NULL, '{\"nik\": \"3596469487045524\", \"kontak\": \"081122779069\", \"desa_id\": 2, \"tgl_lahir\": \"2004-05-27 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IjJ3SXptbGtTbXlNejZLRjlmZWtKWHc9PSIsInZhbHVlIjoiemMrUWhGU2JEU2IybWNkSXBSQWswazZEWW1URzBhMFkvK1BmcFBuSWM1Z1c0MkhyTUczalN6U0pxQW1ZamNSMSIsIm1hYyI6IjBiOTRmMDU1MDI3OGFkYWZiZWJmMTVhMTBlMDM4OGRkNWE0YzM4MTQ3YzdhNjY5MWEyZTY5NmVlZjQyNWU2ZTAiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Jamison Jerde\", \"__display_name\": \"Jamison Jerde\", \"household_card_id\": \"4878045916240772\"}', '2026-05-04 07:36:39'),
(46, NULL, 'CREATE_CITIZEN', 'citizens', '4282567203957615', NULL, '{\"nik\": \"4282567203957615\", \"kontak\": \"087121572202\", \"desa_id\": 2, \"tgl_lahir\": \"2003-03-27 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IlJrUkVUSk5JeDRQMWRuV2p3YVpmSlE9PSIsInZhbHVlIjoieVp2MVVSRW5EL2F5eFZvWTVKV0gwNzRFbHU5c3FzMDdhQ2IxNTRoeVRQWEJTa3IrV2RWOUdWRUpaaTQrNzJRdSIsIm1hYyI6ImE4NTM5ZTMxMjY4ZGI1YmNhNjcxYWRhNjJiMjk2MDQxYWM5OWVjOGFmYjQ1ODFlOWFiMzI1MDEzMWNkMDI3NzciLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Christop Lockman\", \"__display_name\": \"Christop Lockman\", \"household_card_id\": \"4878045916240772\"}', '2026-05-04 07:36:39'),
(47, NULL, 'CREATE_CITIZEN', 'citizens', '4063556498594918', NULL, '{\"nik\": \"4063556498594918\", \"kontak\": \"089579559618\", \"desa_id\": 2, \"tgl_lahir\": \"2009-10-30 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IlpGZS8yS2ZoWmRKb0c2YVo4bFByQ0E9PSIsInZhbHVlIjoiZE1RZUFIcVo5cVRpeHBzMFVaelY3NGlaR1owRXFIVGtDN1RzRm9TOG0zdllIZ1VZTEJpa1hLZ3BiVkxRM1RMQyIsIm1hYyI6IjM1ODllNDkxMjJmYWIwNjVhYzhlZmY5NDg3NmM4ZDVjY2U4NDM2MGY4NTA1ZDdiMWZiNjM2NjYwNThmMWE0OTEiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Filiberto Nicolas\", \"__display_name\": \"Filiberto Nicolas\", \"household_card_id\": \"4878045916240772\"}', '2026-05-04 07:36:39'),
(48, NULL, 'UPDATE_CITIZEN', 'citizens', '3596469487045524', '{\"nama_lengkap\": \"Jamison Jerde\"}', '{\"nama_lengkap\": \"Mr. Antwan Welch\", \"__display_name\": \"Mr. Antwan Welch\"}', '2026-05-04 07:36:39'),
(49, NULL, 'CREATE_CITIZEN', 'citizens', '3795914626868426', NULL, '{\"nik\": \"3795914626868426\", \"kontak\": \"086484895311\", \"desa_id\": 3, \"tgl_lahir\": \"1972-06-03 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6InBYODVGZlNtZElLUndYbU9Ma1J0U2c9PSIsInZhbHVlIjoiejlIZzNkT1BNWUZyd0c2V1F4ZG5QdzFlYWkxLzdFQ05HcTlzUVJ6NmZZRWkxcGJOVk1sWnZsZDRlVHdDVXBwdyIsIm1hYyI6ImQxYmI1NTg4NzAzODFlN2IyY2U3ODVjMzhmZGMwZTc1OWQyZDZjZGYxNmI2MzVmNDJlNjg3ZGU5YmZkMTRhY2EiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Jeanette Haley\", \"__display_name\": \"Jeanette Haley\", \"household_card_id\": \"3677277571821350\"}', '2026-05-04 07:36:39'),
(50, NULL, 'CREATE_CITIZEN', 'citizens', '4465026426842313', NULL, '{\"nik\": \"4465026426842313\", \"kontak\": \"086912963624\", \"desa_id\": 3, \"tgl_lahir\": \"1982-10-31 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IkRDbDkwY3VyVUw3d1R2RVQvTStDSnc9PSIsInZhbHVlIjoibHZKV2dQZXZ4RkRSWXZiT1pwS3F2SFVRaVU2UlIybm1SejZJcERHWGc0OW4rUXk5dnVCM2MwWFNwdkliVFFpaCIsIm1hYyI6IjE5Zjg2NWJkZGVjNzBlMjBhNWI0M2M2ZGViY2Y4MTBiMDk4MzMxMzljMThkNGJlN2VjZGU2ODkyMDNkNjcwM2IiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Mr. Alfonzo Dooley\", \"__display_name\": \"Mr. Alfonzo Dooley\", \"household_card_id\": \"3677277571821350\"}', '2026-05-04 07:36:39'),
(51, NULL, 'CREATE_CITIZEN', 'citizens', '4019554442191682', NULL, '{\"nik\": \"4019554442191682\", \"kontak\": \"088669967650\", \"desa_id\": 3, \"tgl_lahir\": \"1973-06-01 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IlBNUnE5cG9FUXNUY3ppeGxnRUMwMVE9PSIsInZhbHVlIjoieERLUXdwd2JzYks0NFBUblNpSks4QnJDb3ZXYjl2ZjZzbWFLM09jUXQraytpWnRwNTEyVElteXI0cTFCcUJOQiIsIm1hYyI6IjcxZjRjODQ3NzdiNWRjODc5MTFlMTk5NGQxZDVhZjk3ZTAzYzVkNzNkZDMyOTMzNWU3NmZjMThiNzhmNzEwNmIiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Alverta Rempel\", \"__display_name\": \"Alverta Rempel\", \"household_card_id\": \"3677277571821350\"}', '2026-05-04 07:36:39'),
(52, NULL, 'CREATE_CITIZEN', 'citizens', '3701484035927705', NULL, '{\"nik\": \"3701484035927705\", \"kontak\": \"083281106404\", \"desa_id\": 3, \"tgl_lahir\": \"1976-11-30 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IlgyQ1RRbWxFNENDcFVNdVoyTVNpNGc9PSIsInZhbHVlIjoieU5qWHpMM05YUlFIZlYyREhROGNxMVFRN2lwWVZqNHZ0WXBSWUpUSXNHSXpObldqVVNJb2RRVzBKaEtqZXIxVSIsIm1hYyI6Ijk0ZjA1ZDYwOTVhZWJjM2I5ZWY3N2JjMWVjMjc1MzEzMDJhMDBhYmYyNGMwODAxMTQ0NzA5MGJhZGRmZjU3ZGQiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Eugenia Bruen I\", \"__display_name\": \"Eugenia Bruen I\", \"household_card_id\": \"3677277571821350\"}', '2026-05-04 07:36:39'),
(53, NULL, 'UPDATE_CITIZEN', 'citizens', '3701484035927705', '{\"nama_lengkap\": \"Eugenia Bruen I\"}', '{\"nama_lengkap\": \"Norbert Zulauf\", \"__display_name\": \"Norbert Zulauf\"}', '2026-05-04 07:36:39'),
(54, NULL, 'CREATE_CITIZEN', 'citizens', '3781762212703458', NULL, '{\"nik\": \"3781762212703458\", \"kontak\": \"084725060021\", \"desa_id\": 1, \"tgl_lahir\": \"1977-03-01 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6Ik56QzlPUW50MUhaKzBYSWRwR1B6Rmc9PSIsInZhbHVlIjoic0FjbGZKYjlhUklnVHNyNXd4QWtobGljODlGYkw4YVEranhJL3pKODBHd1cxczFNcFgzMjhIVmI3QXlpMVFNSyIsIm1hYyI6IjY1NTFlODkxMzk2YzFkYTUwOTcwMDFlY2M3MDg3Yzg2MWEzM2M2Y2Y4ZDY1M2Q2OGNmNzg0NTYwMTAyY2Y0MzgiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Remington Dickens\", \"__display_name\": \"Remington Dickens\", \"household_card_id\": \"2352183283459212\"}', '2026-05-04 07:36:39'),
(55, NULL, 'CREATE_CITIZEN', 'citizens', '0299621747194687', NULL, '{\"nik\": \"0299621747194687\", \"kontak\": \"089393846198\", \"desa_id\": 1, \"tgl_lahir\": \"1983-12-23 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6Ii9oZngwYVFJYVRnQUViR3R5aFFreVE9PSIsInZhbHVlIjoiSWV3U0Y1YS84bmFTN2RabDUrWVRzaVhJQVBYb1BRMXcwMUw3bnhxT1JNWFFkZnNHdURZNCtOSnZmS3p1dmdGdiIsIm1hYyI6ImM4ZTllODg5YmY3MTlkMTM0NzIwZTNiNTg2YzJjNzY1MjkwZDNkOTRiYjNlYzhiZTQzODNjYzAwZTkwZWI3ZWIiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Pattie Turcotte\", \"__display_name\": \"Pattie Turcotte\", \"household_card_id\": \"2352183283459212\"}', '2026-05-04 07:36:39'),
(56, NULL, 'UPDATE_CITIZEN', 'citizens', '0299621747194687', '{\"nama_lengkap\": \"Pattie Turcotte\"}', '{\"nama_lengkap\": \"Gaston Schroeder\", \"__display_name\": \"Gaston Schroeder\"}', '2026-05-04 07:36:39'),
(57, NULL, 'CREATE_CITIZEN', 'citizens', '7589394078728639', NULL, '{\"nik\": \"7589394078728639\", \"kontak\": \"080338125960\", \"desa_id\": 2, \"tgl_lahir\": \"1997-02-14 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6ImJuWGUrZmJibG9JQUpEeXhUMGVnbnc9PSIsInZhbHVlIjoiSkRnUkFiUmZjbDdGUFFBdTRqWWdvazR3WUU4cXNhYzV4ZStsaWpQRThBM3dHMDBtZmdaTVZOY0pGalZnQlVmaiIsIm1hYyI6Ijc1NjVkMWM2OGFiY2IxMDNhOGFlOTQ5YWM5ZTM1MWY3MTZiODAzZmUxNDBjZWY2NDliYzU1MjdiZGNkMjU2NzMiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Barbara Dibbert Jr.\", \"__display_name\": \"Barbara Dibbert Jr.\", \"household_card_id\": \"7075753455854325\"}', '2026-05-04 07:36:39'),
(58, NULL, 'CREATE_CITIZEN', 'citizens', '4519282631439486', NULL, '{\"nik\": \"4519282631439486\", \"kontak\": \"086454034550\", \"desa_id\": 2, \"tgl_lahir\": \"2018-03-26 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IlBQZlZRTXUydGM0UGVVSUswNlpmeGc9PSIsInZhbHVlIjoiZGZzMTduYklxVmlwN0tHRUZ5VVYrbElDM01CZGFjM2N2US9yamxFaS9lcnEwUkNvd3RYbWg1dkZHTkFWT1BHbyIsIm1hYyI6ImU3NzNjOTYzZWEyYjcyYjI1ZjYyNTZlOTY4YjFkMTVmNjU0YWIwMTgzZDIxZmM2ZTcyMDA5OTgyYTg5YWNhZDUiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Tanya Johnson\", \"__display_name\": \"Tanya Johnson\", \"household_card_id\": \"7075753455854325\"}', '2026-05-04 07:36:39'),
(59, NULL, 'CREATE_CITIZEN', 'citizens', '3439000341160690', NULL, '{\"nik\": \"3439000341160690\", \"kontak\": \"080963066025\", \"desa_id\": 2, \"tgl_lahir\": \"2007-01-05 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IkpRUDdsL1FLRVljbTJybE9jRVRONXc9PSIsInZhbHVlIjoiRmcrelBFRTZMOVNjWE5XcmxQVnpSNzJuWitaaWVBTGZWVCtSUEYwcEdSaS9VbVBwc0o1c3ZXMWRRUWVUL0treiIsIm1hYyI6IjU3ZjUwNzhlMDkxYTcxMjA5N2NlNTc0YjNkMWZjZWE4ZmJhMDNlMzI5MGUyNWMwODYwOGM0OTY1N2FkMWI0NDUiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Alberta Hahn\", \"__display_name\": \"Alberta Hahn\", \"household_card_id\": \"7075753455854325\"}', '2026-05-04 07:36:39'),
(60, NULL, 'CREATE_CITIZEN', 'citizens', '5879662869832569', NULL, '{\"nik\": \"5879662869832569\", \"kontak\": \"082242462848\", \"desa_id\": 2, \"tgl_lahir\": \"1991-08-16 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6ImlGSHJBNU05SVJxSTJKN2lOUHNCV0E9PSIsInZhbHVlIjoiSVkrdGs1clB1VHRjY0I3Vk1za1hxd2pKRDZ4NnZ4VGRMUmJ0UUFsVkx5ZFBvYnFLVGJ0Qk8wVnhjaU9mQlppSyIsIm1hYyI6ImVjMTg5ZjYwZTU1Yjg2ZWM0MDM1MDY2YjdmNzU1ODFlMWQ2M2YwN2E4Y2I0OWU0NTM3MzI3NzBhMzk5ZmFhYzkiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Orie Konopelski Sr.\", \"__display_name\": \"Orie Konopelski Sr.\", \"household_card_id\": \"7075753455854325\"}', '2026-05-04 07:36:39'),
(61, NULL, 'UPDATE_CITIZEN', 'citizens', '3439000341160690', '{\"nama_lengkap\": \"Alberta Hahn\"}', '{\"nama_lengkap\": \"Emile Prosacco\", \"__display_name\": \"Emile Prosacco\"}', '2026-05-04 07:36:39'),
(62, NULL, 'CREATE_CITIZEN', 'citizens', '3463864787130881', NULL, '{\"nik\": \"3463864787130881\", \"kontak\": \"085246331540\", \"desa_id\": 2, \"tgl_lahir\": \"2017-01-18 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6InpyQ0thSkR3akJ2Z0VzWmFqOCtnY3c9PSIsInZhbHVlIjoiYjk1b1VMZlVEMkNzL3JnMVRTcCtDVE05V3VkREwrOElTZGptN01HREM4L0JjeUxJTGRmTUFWa01BQmNvUlZwWSIsIm1hYyI6ImJjOGMwNGM5ZWIzMGYzYTUyZjc3N2Q3M2Q0ZGRjYjkyY2Y3Y2JjZjFmYzBlZjc5ZWMzMTc5MTkyOTQ3ZDIyYzUiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Mr. Derek Halvorson DVM\", \"__display_name\": \"Mr. Derek Halvorson DVM\", \"household_card_id\": \"5813416891243847\"}', '2026-05-04 07:36:39'),
(63, NULL, 'CREATE_CITIZEN', 'citizens', '5578276384260630', NULL, '{\"nik\": \"5578276384260630\", \"kontak\": \"084782933663\", \"desa_id\": 2, \"tgl_lahir\": \"1991-10-20 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6Ik5HcFltMUVlQ0hpL2ZlOGt3S1ljY3c9PSIsInZhbHVlIjoia3U0YWwxajlHRTlDTUhybGdXeERqSWtxTVhJT2NWVFBXdU0yUTFIc3V1SEpUZGJpU3JMdDJmaTErYzZ6SWFCViIsIm1hYyI6ImMwMDY1ZDdkYmY1ODZhMDc1MTI0OTlkNzYyYjNiOTczMDg4MGM1YTU3NDc4MzE5MjYyMTMzYzczOTRhYjU1N2MiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Wanda Wolf\", \"__display_name\": \"Wanda Wolf\", \"household_card_id\": \"5813416891243847\"}', '2026-05-04 07:36:39'),
(64, NULL, 'CREATE_CITIZEN', 'citizens', '3685315395556932', NULL, '{\"nik\": \"3685315395556932\", \"kontak\": \"087799287675\", \"desa_id\": 2, \"tgl_lahir\": \"2002-05-05 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IkxiWkRRTlV3SDFXbmtZNloyU0tQVmc9PSIsInZhbHVlIjoiekMyQkpBbmNoalJnQUxST2dyVWNYVWhzNkpIZFYxZFg2dUw2QnFwdm5LalJYKzIxaVJ3UDlIZVBsZEhZYk00byIsIm1hYyI6IjU0MzRhMTJjNTk4NDlhNmFhZDM0NzJiMmExMzkzZGZhYTIyZDczMjhhZjFiNjc2YjU4NGMyY2YwNzgyMTBiNTAiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Florence Wehner\", \"__display_name\": \"Florence Wehner\", \"household_card_id\": \"5813416891243847\"}', '2026-05-04 07:36:39'),
(65, NULL, 'CREATE_CITIZEN', 'citizens', '9229045182647549', NULL, '{\"nik\": \"9229045182647549\", \"kontak\": \"080247780558\", \"desa_id\": 2, \"tgl_lahir\": \"2005-03-29 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6ImpKNUl1ak1ObC9yYmt4TTUrcGZIUVE9PSIsInZhbHVlIjoiajdwT2I5UHU0VWFPTTFpMDlkODdpZnZid1BSRDc2N1Q1UTR5akwreHpjVzc5cE1TOHJmakx4K0pRbUMrL1R3RSIsIm1hYyI6IjAwNzg3ZDAzNTgzYzU2ODQwMDY5MDg1ZDFhNGMzZGZhZGJhMTcxZmMyODY5ZmNlZDQzM2FkYjU3Nzc1MWU1OGUiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Amos Borer\", \"__display_name\": \"Amos Borer\", \"household_card_id\": \"5813416891243847\"}', '2026-05-04 07:36:39'),
(66, NULL, 'CREATE_CITIZEN', 'citizens', '4361987573172153', NULL, '{\"nik\": \"4361987573172153\", \"kontak\": \"080356457163\", \"desa_id\": 2, \"tgl_lahir\": \"1986-05-15 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IkE1Rm1rd1o2UzRYTkhZVGt1TUtlbWc9PSIsInZhbHVlIjoiQndBcVNRTzJISlJicjZ5c052NmdKUndQYnBHWmpxVThUL2paeUl6cEJrOGNJZlBlcDNmemtTNkFrY0lTRlpoSSIsIm1hYyI6IjcxNWFmNTBkZTI3ZDY4YjE1ZTRmM2MwMWM3NTBkY2M5YWVhYTYwZWE5MDYxZTJjZmQxM2Q3MDc2YjFjYTVjYjkiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Davonte Hauck I\", \"__display_name\": \"Davonte Hauck I\", \"household_card_id\": \"5813416891243847\"}', '2026-05-04 07:36:39'),
(67, NULL, 'UPDATE_CITIZEN', 'citizens', '3463864787130881', '{\"nama_lengkap\": \"Mr. Derek Halvorson DVM\"}', '{\"nama_lengkap\": \"Dion Hodkiewicz\", \"__display_name\": \"Dion Hodkiewicz\"}', '2026-05-04 07:36:39'),
(68, NULL, 'CREATE_CITIZEN', 'citizens', '9251423393469562', NULL, '{\"nik\": \"9251423393469562\", \"kontak\": \"085232959047\", \"desa_id\": 1, \"tgl_lahir\": \"2018-07-24 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IjNEYmhiUXF4c0UzaW5qTjRTWUhjVEE9PSIsInZhbHVlIjoiekF0Rk1oNmdlNkp6RjE1QjFQK3BCS2Y0Zko5by8xWUltTzFFS013bTZNMFFBeFJjbjVxZkh2U3VYQy94M0tQVCIsIm1hYyI6IjE5NDY4ZTNkZWVjMjRkMWMzMjg5MTJlMDFkM2RjNWMxODRiMjNiNDJmYmY1Y2E2YWZiZDRkODMyNjU3MDgxMTkiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Wellington Osinski\", \"__display_name\": \"Wellington Osinski\", \"household_card_id\": \"4978690671792204\"}', '2026-05-04 07:36:39'),
(69, NULL, 'CREATE_CITIZEN', 'citizens', '7153845424003833', NULL, '{\"nik\": \"7153845424003833\", \"kontak\": \"087885633976\", \"desa_id\": 1, \"tgl_lahir\": \"2009-08-28 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IjZlMFlqdDNkVlIyOTBaSHBNVkF2M3c9PSIsInZhbHVlIjoieXE4cEFxRGtHNGRpR000Ynp6WG83TjBCQ21ZSXdvM3V5Mjk5UC9zVXFnTTV1SHo1cDQ3RFVBSVc2WnFjcEtOMSIsIm1hYyI6IjYyY2IxNzJkNDAxMmIyN2ZjYmQyMTE2MTkyNWEyYTQ0ZGUzNjYwYWI2MjcwODE3OWQxZDFlZmZiOGQwYTFkMWIiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Donna Balistreri III\", \"__display_name\": \"Donna Balistreri III\", \"household_card_id\": \"4978690671792204\"}', '2026-05-04 07:36:39'),
(70, NULL, 'CREATE_CITIZEN', 'citizens', '3572996557041966', NULL, '{\"nik\": \"3572996557041966\", \"kontak\": \"081506932420\", \"desa_id\": 1, \"tgl_lahir\": \"2019-10-09 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IlRqQldDK2hGdUFPT2Q0K0t1QXBWU0E9PSIsInZhbHVlIjoiektTTmJrYmFDZHdOSkR0Znp5MmwwRXdOdWZjNTNRYVVEWEpoVXBFSW1NNXJEYnBLWU9LS2NNUHJFc0h2RlZJZSIsIm1hYyI6IjQ2MjE4OGVhMGM3N2NmZDRlMGI5NTUyZGY0YTkwODAyYjZmMTk2Mzk2ZGNiMzAxY2ZmNDZlNjA1MDk0YTgwZTkiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Ike Weissnat\", \"__display_name\": \"Ike Weissnat\", \"household_card_id\": \"4978690671792204\"}', '2026-05-04 07:36:39'),
(71, NULL, 'CREATE_CITIZEN', 'citizens', '7787281848167325', NULL, '{\"nik\": \"7787281848167325\", \"kontak\": \"082236977572\", \"desa_id\": 1, \"tgl_lahir\": \"2007-08-19 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6Ik5YVldCclJmby9ZNWlucmx3M3kwdWc9PSIsInZhbHVlIjoiU2JTenpYZng5M3ZYd2xMak44emhTazdNK1RnclpWUG9LS0ZTZFh5SjUzclJhU2VlbDY1eUtqdUJhcFFENlNVcyIsIm1hYyI6IjY4MjVhZjM1N2QwNTcyOGQ3NjY3NDUwY2M2OTAyMTNhMGIxNzQ0ZGYyYmEwZmJiZjIyMzMyMTlmMTI0NDJlOTciLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Taurean Runolfsson\", \"__display_name\": \"Taurean Runolfsson\", \"household_card_id\": \"4978690671792204\"}', '2026-05-04 07:36:39'),
(72, NULL, 'UPDATE_CITIZEN', 'citizens', '3572996557041966', '{\"nama_lengkap\": \"Ike Weissnat\"}', '{\"nama_lengkap\": \"Prof. Alexie Borer\", \"__display_name\": \"Prof. Alexie Borer\"}', '2026-05-04 07:36:39'),
(73, NULL, 'CREATE_CITIZEN', 'citizens', '3790669648128166', NULL, '{\"nik\": \"3790669648128166\", \"kontak\": \"084527598009\", \"desa_id\": 3, \"tgl_lahir\": \"2007-12-19 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6ImJNemlYVEFPNmQrOC9selh4MDVGQnc9PSIsInZhbHVlIjoiU1M2NWpoMmdRaUJFRURveDFSeC9BOHpubklYTjVlb1ZiOHdXTTFnV1FSV2YzR1BYNW9sWm44aDNFZHhCdVRncSIsIm1hYyI6ImNmMWRjYWQ0MDljZjc0MTAxZjAyNzNkYTg2YTNkYjI0NGI1MzYyZTc0MmZjNTUxODc1MjIyMWQ2NmZmMGE5MmIiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Eriberto Koepp\", \"__display_name\": \"Eriberto Koepp\", \"household_card_id\": \"5138902707436241\"}', '2026-05-04 07:36:39'),
(74, NULL, 'CREATE_CITIZEN', 'citizens', '9701621342064988', NULL, '{\"nik\": \"9701621342064988\", \"kontak\": \"085802435497\", \"desa_id\": 3, \"tgl_lahir\": \"2026-05-01 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IjQ4UXg0cW1LNHVQRjBweFVVLzM4c0E9PSIsInZhbHVlIjoiRy9RVFJCdkRNV0ZrbnAxdkNtNHNRNitOcFJMRDlFV3N1cGI0RnluNk5GMkM3eEIwQXF1SzFDNFl0WGp3Si9GSyIsIm1hYyI6ImM0MDI3OTQ4NWNjZjRlMGM3OGVjYjhkMWU3MDU1Y2QyMTc4ZjBjYjllYTI2ZTUwNTIyZDE4OWJiN2FjMzdlZDQiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Mallie Mitchell III\", \"__display_name\": \"Mallie Mitchell III\", \"household_card_id\": \"5138902707436241\"}', '2026-05-04 07:36:39'),
(75, NULL, 'UPDATE_CITIZEN', 'citizens', '3790669648128166', '{\"nama_lengkap\": \"Eriberto Koepp\"}', '{\"nama_lengkap\": \"Raymundo McDermott III\", \"__display_name\": \"Raymundo McDermott III\"}', '2026-05-04 07:36:39'),
(76, NULL, 'CREATE_CITIZEN', 'citizens', '0204199306493975', NULL, '{\"nik\": \"0204199306493975\", \"kontak\": \"080739629975\", \"desa_id\": 3, \"tgl_lahir\": \"1997-10-07 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6IjFEZUFRNkRsdThMNlM4NjRkblRXNmc9PSIsInZhbHVlIjoiQU5IRmwrVEtIMlpOZlVCaUtXNm91amhoN25HeXliMVMxMkpzeUlza0ZIdm1PODNobUtkYm9TZU1mdHBxVkRzdyIsIm1hYyI6ImM5Mjc3MjgzNWFhNmNjNjc0ODdlMTllM2JhZTZkMjE5N2VmM2E3M2FkYmMwYzRiZGFhZWE4MmYyMjE4MzFhYzYiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Ebony White\", \"__display_name\": \"Ebony White\", \"household_card_id\": \"1233791839629110\"}', '2026-05-04 07:36:39'),
(77, NULL, 'CREATE_CITIZEN', 'citizens', '3003768389119937', NULL, '{\"nik\": \"3003768389119937\", \"kontak\": \"089664345571\", \"desa_id\": 3, \"tgl_lahir\": \"1972-08-14 00:00:00\", \"created_at\": \"2026-05-04 07:36:39\", \"updated_at\": \"2026-05-04 07:36:39\", \"alamat_desa\": \"eyJpdiI6InJ3QU5DOGMrVEx4UmlTemdkbjJ5NXc9PSIsInZhbHVlIjoieHhEeHNnQlhTVGV0KzQyNUU0SEErbExhejVJTW1teUFWd3V5K2ZqWTQ5YmdEeXhNMWFjTURSVGx2c2JQcGpqeSIsIm1hYyI6IjdhOTE2Y2UwMWZjMDI0NDhlYzE1Y2Y0N2EyZTVhZjUwMzA5ZDNkMmM4NDJlNGQzNTdmMTZjY2VkYTRkZjQwNTQiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Mr. Ethan Will V\", \"__display_name\": \"Mr. Ethan Will V\", \"household_card_id\": \"1233791839629110\"}', '2026-05-04 07:36:39'),
(78, NULL, 'CREATE_CITIZEN', 'citizens', '6929672155228920', NULL, '{\"nik\": \"6929672155228920\", \"kontak\": \"080052006472\", \"desa_id\": 3, \"tgl_lahir\": \"1977-12-23 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IklwWnRPVUtLcmNkRU1Jdi9MMGorQWc9PSIsInZhbHVlIjoiZUFIRmE3Mm93dFBlMi93OFFMbUZaVStjWEZ2T2F5ZWNGWE1lUzNpRk5scDlvVnd0VmlvS21mOUJNL0hDUTRUdiIsIm1hYyI6IjA1NDI1ZjA0NTE2ZDlhODczNDQ4ZjQzMjE5YTE3NTg0NzA0YzNlOTc0NWZhODdkYTUzM2NkNjM2MTAxNzZkZmYiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Karlee Bergnaum\", \"__display_name\": \"Karlee Bergnaum\", \"household_card_id\": \"1233791839629110\"}', '2026-05-04 07:36:40'),
(79, NULL, 'CREATE_CITIZEN', 'citizens', '9115898529275689', NULL, '{\"nik\": \"9115898529275689\", \"kontak\": \"084525256483\", \"desa_id\": 3, \"tgl_lahir\": \"1985-05-31 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IldTbTdiaVZacHFFanR1eHM4YnBheFE9PSIsInZhbHVlIjoicXBUUzVQNUFpQkxaVnlTSTdjV2NPbHRSVHJEdkw5aGZ0MkVGOExEKytXd1kzSXYwT3N6MTRrd0VwT0RoKzFWQyIsIm1hYyI6IjllYzQ3ZTY5NmEwZGJhM2ZjOGU2MTVjNjJiNTUzZDA5NWQwYjgyN2U1NjZhODMzZTg5MTc3YTc4Mjg4ZThkZTQiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Amely Botsford\", \"__display_name\": \"Amely Botsford\", \"household_card_id\": \"1233791839629110\"}', '2026-05-04 07:36:40'),
(80, NULL, 'UPDATE_CITIZEN', 'citizens', '0204199306493975', '{\"updated_at\": \"2026-05-04 07:36:39\", \"nama_lengkap\": \"Ebony White\"}', '{\"updated_at\": \"2026-05-04 07:36:40\", \"nama_lengkap\": \"Green Stoltenberg\", \"__display_name\": \"Green Stoltenberg\"}', '2026-05-04 07:36:40'),
(81, NULL, 'CREATE_CITIZEN', 'citizens', '9157078261747449', NULL, '{\"nik\": \"9157078261747449\", \"kontak\": \"087443130422\", \"desa_id\": 1, \"tgl_lahir\": \"1994-07-01 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IjZld09IZTBTVVNuUEJ5ampRN0ZMT1E9PSIsInZhbHVlIjoiMTNXbnZacWpsdG5LOUV5WXYyQjRVbkx2UzJxa1UxcmNSWEtSY0dBL1NlK1dCcnpoek9aamtjQVdxU21nN09ZcCIsIm1hYyI6ImRjYzRlYTUzNjViNjM5YTM0MWU2YTc4ODljNDgyNGI5ZTQ0MjE2NmU2ZTJkMjk3ZDAxMDM5M2M3MmQxNzIyODYiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Emilie Donnelly\", \"__display_name\": \"Emilie Donnelly\", \"household_card_id\": \"8958937323630079\"}', '2026-05-04 07:36:40'),
(82, NULL, 'CREATE_CITIZEN', 'citizens', '7119734837910704', NULL, '{\"nik\": \"7119734837910704\", \"kontak\": \"084242816724\", \"desa_id\": 1, \"tgl_lahir\": \"1997-02-27 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6Inh2RTkrejQ3Sm81YXdJMzExOWU3MXc9PSIsInZhbHVlIjoiN0JVYWwzWGtEbDQ1M3RBcDJ1KzQ4RExkVDhOZDlydS9sb2FTc2czSEJjM3VQOFJIN3VtWHl6Ymc1VWpoVzVrTiIsIm1hYyI6ImFmNzczZGU5ZTdiMzJkOGEyNmVhY2I2YTFkZDM3ZWVlYmY2NzA5Y2I4MDU0YmExOTQyMDI2ZDQxZWY3OGU1NGMiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Brendon Abernathy\", \"__display_name\": \"Brendon Abernathy\", \"household_card_id\": \"8958937323630079\"}', '2026-05-04 07:36:40'),
(83, NULL, 'CREATE_CITIZEN', 'citizens', '6970788577109346', NULL, '{\"nik\": \"6970788577109346\", \"kontak\": \"086126442531\", \"desa_id\": 1, \"tgl_lahir\": \"1974-08-22 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6InhmRE45dk1hamJPM1l4UnhyQm9zc3c9PSIsInZhbHVlIjoiRzk2SFZTQzFKV2NVWUQrK21zVmFmYklabm9PNkNEcTFlVnpmWG5SSENmVitnL1JhYmhndm1XK0h0ZTRQSU9iWiIsIm1hYyI6IjU5ZGViMTRkMjY3YmYzMzgxOTQzYjQzNDc1ZjE4N2ZlZmI5NDZlODQwMGQzYjYyOWU2MWY4Nzc2ZTNlNDBmNGMiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Zakary Lakin\", \"__display_name\": \"Zakary Lakin\", \"household_card_id\": \"8958937323630079\"}', '2026-05-04 07:36:40'),
(84, NULL, 'CREATE_CITIZEN', 'citizens', '3878775818711995', NULL, '{\"nik\": \"3878775818711995\", \"kontak\": \"083405080386\", \"desa_id\": 1, \"tgl_lahir\": \"2020-10-27 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6Im12Vm9oSWs1VWUweTBKUmZxQTErQlE9PSIsInZhbHVlIjoiMHBZRzNmWVZsTHpyZ0l4cjNkTGRtWERmL2VZL0IzRkd2YXpIOVI2NFEzRktxWmJNUkVFUHJmWGtyblVMemRtbCIsIm1hYyI6IjA0NjE5OTQxMDRlYTMxOWExYmNkOTQyZWU0YTQ5MjdjNWI2ZjE2OGJiMGE0MWZkYzY3NGIyMTM5NjRlYzA0YmYiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Axel Wisoky V\", \"__display_name\": \"Axel Wisoky V\", \"household_card_id\": \"8958937323630079\"}', '2026-05-04 07:36:40'),
(85, NULL, 'CREATE_CITIZEN', 'citizens', '3373377393202273', NULL, '{\"nik\": \"3373377393202273\", \"kontak\": \"084996602617\", \"desa_id\": 1, \"tgl_lahir\": \"1978-08-13 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IktORDFsYlo2V1lvNmhnSlQ3eldxb0E9PSIsInZhbHVlIjoiMjRLYkpKbWllTktPVFh1bU9UZ2pGOXY4aDVDUXhqN3BETFZzSzF2VmZsU0pPSDFtaW1yRzI2NlRzSTlZcFk0ZyIsIm1hYyI6ImNhNWI3NzI3N2Y0ZTAzOGM5ZjQzYmQxZDM2MjA2NTE3N2ZjZGQ1ZTY5YzYyNDFiZmJjN2U2NzZhZTNkNzRhNjkiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Prof. Favian Gibson DVM\", \"__display_name\": \"Prof. Favian Gibson DVM\", \"household_card_id\": \"8958937323630079\"}', '2026-05-04 07:36:40'),
(86, NULL, 'UPDATE_CITIZEN', 'citizens', '3373377393202273', '{\"nama_lengkap\": \"Prof. Favian Gibson DVM\"}', '{\"nama_lengkap\": \"Leonard Lockman\", \"__display_name\": \"Leonard Lockman\"}', '2026-05-04 07:36:40'),
(87, NULL, 'CREATE_CITIZEN', 'citizens', '5538260369323391', NULL, '{\"nik\": \"5538260369323391\", \"kontak\": \"086815749127\", \"desa_id\": 2, \"tgl_lahir\": \"1979-07-14 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6ImJKc1B0NVowQzk0QW9NQnN0dmFBM3c9PSIsInZhbHVlIjoiYWRWU3lIdDE4RDIwU050Y3h0Nk05elgxUGd5RUp3MnFSOWZiWHhDOFJXZjRTWkU3VkpzeEdLSHUyeklFVHQ3TyIsIm1hYyI6IjEyZDIwNGFhZDUyMTlhYWIzMTliMzdlNWRmOTkzNjY0NWQxMDI3Mjg0Nzg4Y2Y4YmY0Yjg3ZDU4ZTRlOTYyYzAiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Amina Hessel\", \"__display_name\": \"Amina Hessel\", \"household_card_id\": \"3421205268285809\"}', '2026-05-04 07:36:40'),
(88, NULL, 'CREATE_CITIZEN', 'citizens', '4278776258111071', NULL, '{\"nik\": \"4278776258111071\", \"kontak\": \"086337743673\", \"desa_id\": 2, \"tgl_lahir\": \"2016-06-11 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6Inp6Zll3NkhrSERBVGVmQkVGU290d0E9PSIsInZhbHVlIjoiWW9Db2I2Q0pCcU5TUVZZb2puSFVEWHpNVzBqUnk3Z2dObFcxK25BKzl5dThxd1R5RjUvZHVVSTdWYnlWMVFtOCIsIm1hYyI6IjgzZmM3NzZlNzNlYWQ0ZDIyMGY1NTg5MDc0MmZjYjhlMTgzNWJmOTdkNzRiODFmOTgzOTE2NDZlMmY5NDAyMWYiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Jaida Stark\", \"__display_name\": \"Jaida Stark\", \"household_card_id\": \"3421205268285809\"}', '2026-05-04 07:36:40'),
(89, NULL, 'CREATE_CITIZEN', 'citizens', '5257629764665805', NULL, '{\"nik\": \"5257629764665805\", \"kontak\": \"083963904241\", \"desa_id\": 2, \"tgl_lahir\": \"1999-02-13 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IkZSQ0tNSkExYkFDUWx2dzBMUEVuYWc9PSIsInZhbHVlIjoic1NwMWU4YW9XcU1qOGFMYkVNVGpnSVUyTSsyMWxDaElTNHBNayt4T3pYRlh3MGg0aTRWZUR5blU1Z3NNbXA1YyIsIm1hYyI6IjgwZDNlYWY0NGQ1OTUwM2YyMGE3M2I5ODc1MjhmZmQ0ZDRjN2EzNmNlOWNmMTQ4Mjk1Y2U5NjExZDZmYTZmNDUiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Arlie Jaskolski\", \"__display_name\": \"Arlie Jaskolski\", \"household_card_id\": \"3421205268285809\"}', '2026-05-04 07:36:40'),
(90, NULL, 'CREATE_CITIZEN', 'citizens', '0911859211473940', NULL, '{\"nik\": \"0911859211473940\", \"kontak\": \"089269536102\", \"desa_id\": 2, \"tgl_lahir\": \"2008-07-24 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IkxnZlpWNzJPOTc2U21KRXU0QjFLM1E9PSIsInZhbHVlIjoiOEVPV2pQa1BOd3JvZHRabURZbUlQc0dtYXdCY2p4WFdpcHp6TjJUeUhIV0tiTEhYYjFKK01DeEZyR1VMRWZodyIsIm1hYyI6ImJhNTkwNWUzZmI3NzFmZDU1YTQ5YWZiYTExN2VkZjJiMjhlZTQ2OGU1NDI0Njk5YTI5NmYxNGQzZGQwYjg0MWYiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Brenden Yundt\", \"__display_name\": \"Brenden Yundt\", \"household_card_id\": \"3421205268285809\"}', '2026-05-04 07:36:40'),
(91, NULL, 'UPDATE_CITIZEN', 'citizens', '0911859211473940', '{\"nama_lengkap\": \"Brenden Yundt\"}', '{\"nama_lengkap\": \"Markus Spinka\", \"__display_name\": \"Markus Spinka\"}', '2026-05-04 07:36:40'),
(92, NULL, 'CREATE_CITIZEN', 'citizens', '8982775245399990', NULL, '{\"nik\": \"8982775245399990\", \"kontak\": \"089753252248\", \"desa_id\": 1, \"tgl_lahir\": \"1984-04-26 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IlFSZ3pMSTR6d1owNVVsUTlKWjlJMGc9PSIsInZhbHVlIjoiUytFcFRJaWlJMkxVNGFlMVNxdmc2UElNYkh3WlUySjBuVjdnNmhIckN2NTBJbUJzQVRDaTVXRHlKUC85cmwxNyIsIm1hYyI6ImU3YTk1MDEwZGI2NGY0OTMyYmNhN2EzNGE2NTFjZDFhOGRmZDg3OWEyMzBhZTc4MThjZGFiNmQ4NTYzMmVjZjIiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Koby Schmidt\", \"__display_name\": \"Koby Schmidt\", \"household_card_id\": \"6969058683899311\"}', '2026-05-04 07:36:40'),
(93, NULL, 'CREATE_CITIZEN', 'citizens', '0010303206953957', NULL, '{\"nik\": \"0010303206953957\", \"kontak\": \"089161802726\", \"desa_id\": 1, \"tgl_lahir\": \"1980-08-04 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6Ii9pbWFmZjJVMXJRZ2xPc1A5amtNb1E9PSIsInZhbHVlIjoidURpYzV1N1RPVTlGcmF3RjhiK1k5MFVpaVI4dkNydGo5VkdnR01qWUdhcTZTZHo0SjdZMDVKV21OMXoxNWgwTCIsIm1hYyI6ImVlZjZkMGRhMTA4MGY0YzY1ZDNlOTRlNWNlYzk3YTgzZGRkYTljYzgzMGQzNWNjMGIzNDhmZDM1YTMyZDVjYzkiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Lilyan Von\", \"__display_name\": \"Lilyan Von\", \"household_card_id\": \"6969058683899311\"}', '2026-05-04 07:36:40'),
(94, NULL, 'UPDATE_CITIZEN', 'citizens', '0010303206953957', '{\"nama_lengkap\": \"Lilyan Von\"}', '{\"nama_lengkap\": \"Guiseppe Kuphal\", \"__display_name\": \"Guiseppe Kuphal\"}', '2026-05-04 07:36:40'),
(95, NULL, 'CREATE_CITIZEN', 'citizens', '7995457385909268', NULL, '{\"nik\": \"7995457385909268\", \"kontak\": \"081770643548\", \"desa_id\": 2, \"tgl_lahir\": \"1971-01-11 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IklyaEE2dVliMHgzaFhCT0wvSkVLMWc9PSIsInZhbHVlIjoicW5hTDdub1Z4U2paTWhRVkN2dTNZMVVGSEwram1SKzM1dGkyTS8wWTVLbit2djlnSkNoTHZtekdIK3JtbUVCQSIsIm1hYyI6ImQ1Y2Q1YTZhMzI5YzJlYjgzMDYxOGU3YWUxZmVjODhiOTIwOWY0ZmFmNWM3ZjIyMmZiNTBhMzEzYTMxYjVhYzgiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Raymond Bechtelar\", \"__display_name\": \"Raymond Bechtelar\", \"household_card_id\": \"5001207486395518\"}', '2026-05-04 07:36:40'),
(96, NULL, 'CREATE_CITIZEN', 'citizens', '0066935789043262', NULL, '{\"nik\": \"0066935789043262\", \"kontak\": \"081764578565\", \"desa_id\": 2, \"tgl_lahir\": \"1979-07-13 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6Ik1vVmZYK1Q3QzM3YkZWOUNvbjc4NUE9PSIsInZhbHVlIjoiMnBQaEQwZTdnRzZqYlRtZ3hBWDBOVGlHbUtyMUYvbW50SXowWFN5eGkxSGhDTWUvQUhCakZrZmZuSjk4SXpNOSIsIm1hYyI6IjhlNmIzYzczMTc1YzM0OWE5NTdhODM3ODI0ZDdhZWMzOGI1YjZiYTFjZGQ3ODZkZDMzNWEyZmUyNWEzMmJiZWIiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Mr. Sam Bins III\", \"__display_name\": \"Mr. Sam Bins III\", \"household_card_id\": \"5001207486395518\"}', '2026-05-04 07:36:40'),
(97, NULL, 'CREATE_CITIZEN', 'citizens', '4301550074633703', NULL, '{\"nik\": \"4301550074633703\", \"kontak\": \"080401793212\", \"desa_id\": 2, \"tgl_lahir\": \"1998-01-23 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6ImxvNEo3d2dPeFk1bCtlcjZEYkwzbEE9PSIsInZhbHVlIjoidm5zTkY3REE1ZlRNcXpsMHYxVWp4eVhxcnBhWGFSWjhBc1pFZ1pjY2Y0Rno2eFJXcDd2LzUvZGxBRGpmSWkybCIsIm1hYyI6Ijg0ZGM2OTZjYTA3MTRiZWQxZGViNWIyNzQxMTU0MWQ0ZjBhNWViMDQwZDYxYWY1ZDg2Y2Q5MTliNmEyNGZkZTEiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Gordon Schmeler\", \"__display_name\": \"Gordon Schmeler\", \"household_card_id\": \"5001207486395518\"}', '2026-05-04 07:36:40'),
(98, NULL, 'CREATE_CITIZEN', 'citizens', '2684091943511482', NULL, '{\"nik\": \"2684091943511482\", \"kontak\": \"084093211177\", \"desa_id\": 2, \"tgl_lahir\": \"1970-08-19 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6InNCZEdpdTNMZis5cStaM0lrb3lvUmc9PSIsInZhbHVlIjoiRUZMK1dIcVo0NHl0cFMrNjJpSXFMbS9weUFTWXlYU0RkRHpHeDRxa3cyOEVRbDB2OU1MaUZlRW1vc0piSnlvaCIsIm1hYyI6ImIyNmYyZGMwNzJhMGJlOWJiYjk4NjM5NDdmYjY5ZmUzZTJlZDYyNDFhNGY2MWJkNjZhOTkxMzEwYWFmNGM4ZTUiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Sharon Thiel V\", \"__display_name\": \"Sharon Thiel V\", \"household_card_id\": \"5001207486395518\"}', '2026-05-04 07:36:40'),
(99, NULL, 'UPDATE_CITIZEN', 'citizens', '0066935789043262', '{\"nama_lengkap\": \"Mr. Sam Bins III\"}', '{\"nama_lengkap\": \"Dr. Jo Lemke\", \"__display_name\": \"Dr. Jo Lemke\"}', '2026-05-04 07:36:40');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `target_table`, `target_id`, `old_value`, `new_value`, `timestamp`) VALUES
(100, NULL, 'CREATE_CITIZEN', 'citizens', '7890993187085161', NULL, '{\"nik\": \"7890993187085161\", \"kontak\": \"084449612133\", \"desa_id\": 3, \"tgl_lahir\": \"2018-10-12 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6Ilg0dHlsbkxZVk01cGtDNlZwcEljeFE9PSIsInZhbHVlIjoiVkRneE5XaEsyN25PbGNSNnI5ZExERTdQSEx6MXZVT0dGZi9MNHdDR0duYWE1aVM4dlpaditkMXlRMmFnSTBsRiIsIm1hYyI6IjdlMWMzYTM2NTYwMWJiZDM1Y2Q3NzI3Y2Y0OTBlZWIzNzBmMTRhMDRmMWJkOTY0N2M0MjUxMTE0YTA0NTVkYTAiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Miss Eliza Ullrich III\", \"__display_name\": \"Miss Eliza Ullrich III\", \"household_card_id\": \"8252925377204770\"}', '2026-05-04 07:36:40'),
(101, NULL, 'CREATE_CITIZEN', 'citizens', '3444106539063721', NULL, '{\"nik\": \"3444106539063721\", \"kontak\": \"085073814566\", \"desa_id\": 3, \"tgl_lahir\": \"1998-09-06 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6InEyb0UzdmQzS0dVMFNqM3EvY1dJWXc9PSIsInZhbHVlIjoiRm9PL080Q00vaDROTGhxUXhYaWNsSnh2MkhHTVpWWVZwQWVGTWE2WW1ML0lVMktqK0pQNytOeHR2c0NkbmlOUSIsIm1hYyI6ImQ5ZTQzMGU0MWRiMDFlOWFlM2Y4OWFlYTgyMTE2ZjJiY2I3ODQyOTA0ODA0MDVjMGYxYjdmZmVmMWMxNjZlODYiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Quinton Wehner\", \"__display_name\": \"Quinton Wehner\", \"household_card_id\": \"8252925377204770\"}', '2026-05-04 07:36:40'),
(102, NULL, 'CREATE_CITIZEN', 'citizens', '0064622836742991', NULL, '{\"nik\": \"0064622836742991\", \"kontak\": \"080373697426\", \"desa_id\": 3, \"tgl_lahir\": \"2012-01-19 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6ImpUWFRFcjNuaUp4aGNybEdEbHlLc2c9PSIsInZhbHVlIjoicEc2RjBiaXRaTkRidll1aE1YWndPMlp4d3dLUmgwOUZOcVQrTDF3dURUdEVyTFZYb0NoYkNvSHZ4cC83N0p0LyIsIm1hYyI6IjY1YjY0NjgyMmFhMDkwZDEwM2VhMzA1NTQ1ZjNjNGRhNGIwNjhmYjE0YTZmNzk2ODVhNDc1OTczOWJiNDllOGYiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Grayce Mraz\", \"__display_name\": \"Grayce Mraz\", \"household_card_id\": \"8252925377204770\"}', '2026-05-04 07:36:40'),
(103, NULL, 'CREATE_CITIZEN', 'citizens', '7109700345988517', NULL, '{\"nik\": \"7109700345988517\", \"kontak\": \"082475814946\", \"desa_id\": 3, \"tgl_lahir\": \"1997-05-06 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IndlTkxCTVNEb21qZnZEa1lHOGxCSGc9PSIsInZhbHVlIjoiZTVSa1VYcFdMMnVHUUhlZWw0Y1dxZ05MZXkwSXlZQUdDU251aEpEUTdZdTZMb29sSmxFU2hranAreVVOMk5iWSIsIm1hYyI6IjM4ZGU2Yzk0ZDE5NjIwODgwYmM1OGRmNzQ1MjQzMDEyNWNkNjkzMTY1OWVhNDVjNDIzYjU0MDljMmI0NTA1ZTgiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Jerrold Hilpert\", \"__display_name\": \"Jerrold Hilpert\", \"household_card_id\": \"8252925377204770\"}', '2026-05-04 07:36:40'),
(104, NULL, 'CREATE_CITIZEN', 'citizens', '3193862811099563', NULL, '{\"nik\": \"3193862811099563\", \"kontak\": \"081921615729\", \"desa_id\": 3, \"tgl_lahir\": \"1998-02-16 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IkxNS1JpY2RiRVBaaXFQN1NJbVRyK3c9PSIsInZhbHVlIjoiVWs1emRxc1RpR3hFYlBhZ0o0eEtNeXk5VlhzbFJlZjdxWjU5aStZT0VkdFc2Um9rNWtmU1JLRDFXVlBxQW16eiIsIm1hYyI6IjEwMzgwMjdiY2E2NTljM2RlMGE1ZjIzZDZmMjY2ZTZiOTViMGQ2YjkxOGQyZjE0NzdiMjY0MTFkZjNhOTU1OWEiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Annamae Ortiz\", \"__display_name\": \"Annamae Ortiz\", \"household_card_id\": \"8252925377204770\"}', '2026-05-04 07:36:40'),
(105, NULL, 'UPDATE_CITIZEN', 'citizens', '0064622836742991', '{\"nama_lengkap\": \"Grayce Mraz\"}', '{\"nama_lengkap\": \"Erling Howe\", \"__display_name\": \"Erling Howe\"}', '2026-05-04 07:36:40'),
(106, NULL, 'CREATE_CITIZEN', 'citizens', '2563815713882304', NULL, '{\"nik\": \"2563815713882304\", \"kontak\": \"080942976444\", \"desa_id\": 3, \"tgl_lahir\": \"2014-08-12 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6IjZ6RzdGWFZhbitCS21YT2JwVWpKNXc9PSIsInZhbHVlIjoid3RZZW94dDFWT3ZjZlRsV2h6YWg4QmJhQTJEL3ZXQUNpYm1UM2lpMWcyMEFqVjBzNnFRRFVFeWp4WGhhSGF4YyIsIm1hYyI6ImYxY2U4MGY4ZmEwMDFkOTdlMmFhNTU2NGQwNWVjZDZiNGU3Mzk5NTc2ZGZjZDMzYWMyMzVjYzU3ZGMyOTgxOGUiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Alfreda Schuster\", \"__display_name\": \"Alfreda Schuster\", \"household_card_id\": \"5612773451825704\"}', '2026-05-04 07:36:40'),
(107, NULL, 'CREATE_CITIZEN', 'citizens', '7567233677789817', NULL, '{\"nik\": \"7567233677789817\", \"kontak\": \"080008780155\", \"desa_id\": 3, \"tgl_lahir\": \"2015-10-18 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6ImwyWURzaUdTNEhwQU5oNlNCakxLbUE9PSIsInZhbHVlIjoiMS9CNkczZnBkc09TR1lTTDhBNERqSmVBb2lNMkJST1N2Yk9yVmNKOERHNEN2VVNwZ3prZll1dXdCcUlRcHJZYiIsIm1hYyI6ImM4YjkxZmQwYjE0ZDk0MzljODdlYzFlY2QzNzBiOWUyM2Q5ZjFjZGQxNDBkMTI3YTZiN2RiN2E3ZjBhYTUyYjMiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Bernita Koss\", \"__display_name\": \"Bernita Koss\", \"household_card_id\": \"5612773451825704\"}', '2026-05-04 07:36:40'),
(108, NULL, 'CREATE_CITIZEN', 'citizens', '7004729095594613', NULL, '{\"nik\": \"7004729095594613\", \"kontak\": \"083554881696\", \"desa_id\": 3, \"tgl_lahir\": \"2023-01-02 00:00:00\", \"created_at\": \"2026-05-04 07:36:40\", \"updated_at\": \"2026-05-04 07:36:40\", \"alamat_desa\": \"eyJpdiI6ImMzMi9reGcvSFJSc3pDb2h0dnBxMlE9PSIsInZhbHVlIjoidENCWStXU1lYNGlHRzA0akI2bnVGWHpPK0NMQXlQWEo1RDhNR1I3SEdyU2VQWk1GYjZDbjZRUnZVZE5LOHRwUiIsIm1hYyI6IjRlZDQzZGY2NjAzOGY3ZjBkNzIyYWYzMmE2MmZkZGJkZDExYzUwZDAxNTg0NTQ3NTNlODQ3NDUwMGI0ZjFjNmQiLCJ0YWciOiIifQ==\", \"nama_lengkap\": \"Alexandre Berge\", \"__display_name\": \"Alexandre Berge\", \"household_card_id\": \"5612773451825704\"}', '2026-05-04 07:36:40'),
(109, NULL, 'UPDATE_CITIZEN', 'citizens', '2563815713882304', '{\"nama_lengkap\": \"Alfreda Schuster\"}', '{\"nama_lengkap\": \"Jamil Nikolaus\", \"__display_name\": \"Jamil Nikolaus\"}', '2026-05-04 07:36:40'),
(110, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 08:02:26'),
(111, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 08:11:25'),
(112, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 08:15:21'),
(113, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 08:16:28'),
(114, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 08:24:40'),
(115, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 08:34:43'),
(116, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 08:59:43'),
(117, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:02:49'),
(118, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:03:47'),
(119, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:08:23'),
(120, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:10:00'),
(121, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:15:48'),
(122, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:18:50'),
(123, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:19:36'),
(124, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:21:26'),
(125, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:21:29'),
(126, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:22:37'),
(127, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:30:38'),
(128, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:31:09'),
(129, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:31:53'),
(130, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:33:18'),
(131, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:33:59'),
(132, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:34:30'),
(133, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:35:14'),
(134, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:35:18'),
(135, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:35:22'),
(136, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:36:45'),
(137, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:37:40'),
(138, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:37:44'),
(139, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 09:38:38'),
(140, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-04 13:04:54'),
(141, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:04:54'),
(142, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:04:58'),
(143, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:07:31'),
(144, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:09:27'),
(145, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:14:09'),
(146, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:15:56'),
(147, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:16:59'),
(148, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:19:38'),
(149, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:21:29'),
(150, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:21:37'),
(151, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:22:24'),
(152, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:23:58'),
(153, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:24:31'),
(154, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:26:43'),
(155, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:29:47'),
(156, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:32:14'),
(157, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:33:09'),
(158, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:35:03'),
(159, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:36:04'),
(160, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:36:54'),
(161, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:38:50'),
(162, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:41:09'),
(163, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:44:34'),
(164, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:46:57'),
(165, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:50:39'),
(166, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:51:25'),
(167, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:59:27'),
(168, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 13:59:36'),
(169, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 15:31:52'),
(170, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"8677645003235905\"}', '2026-05-04 15:36:43'),
(171, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-05 06:07:20'),
(172, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-06 03:03:18'),
(173, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-06 04:04:44'),
(174, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-06 04:16:36'),
(175, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-06 15:09:01'),
(176, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-06 15:09:01'),
(177, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-06 15:22:25'),
(178, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\"}', '2026-05-06 15:35:31'),
(179, 1, 'UPDATE_USER', 'users', '6', '{\"desa_id\": 2, \"updated_at\": \"2026-05-04 05:46:27\"}', '{\"desa_id\": \"3\", \"updated_at\": \"2026-05-06 16:18:31\"}', '2026-05-06 16:18:31'),
(180, 1, 'UPDATE_USER', 'users', '6', '{\"id\": 6, \"name\": \"Milford Ruecker\", \"email\": \"milford-ruecker@desa.id\", \"desa_id\": 2, \"role_id\": 2, \"is_active\": true, \"created_at\": \"2026-05-04T05:46:27.000000Z\", \"updated_at\": \"2026-05-04T05:46:27.000000Z\", \"district_id\": null, \"email_verified_at\": null}', '{\"id\": 6, \"name\": \"Milford Ruecker\", \"email\": \"milford-ruecker@desa.id\", \"desa_id\": 3, \"role_id\": 2, \"is_active\": true, \"created_at\": \"2026-05-04T05:46:27.000000Z\", \"updated_at\": \"2026-05-06T16:18:31.000000Z\", \"district_id\": null, \"email_verified_at\": null}', '2026-05-06 16:18:31'),
(181, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-06 16:18:50'),
(182, 1, 'UPDATE_ROLE', 'roles', '2', '{\"id\": 2, \"name\": \"OperatorDesa\", \"slug\": \"operatordesa\", \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null, \"permissions\": [{\"id\": 1, \"name\": \"Verify Poverty Status\", \"slug\": \"poverty.verify\", \"pivot\": {\"role_id\": 2, \"permission_id\": 1}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 3, \"name\": \"Report Service Given\", \"slug\": \"service.report\", \"pivot\": {\"role_id\": 2, \"permission_id\": 3}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}]}', '{\"id\": 2, \"name\": \"OperatorDesa\", \"slug\": \"operatordesa\", \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null, \"permissions\": [{\"id\": 1, \"name\": \"Verify Poverty Status\", \"slug\": \"poverty.verify\", \"pivot\": {\"role_id\": 2, \"permission_id\": 1}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 2, \"name\": \"Print Verification Proof\", \"slug\": \"poverty.print_proof\", \"pivot\": {\"role_id\": 2, \"permission_id\": 2}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 3, \"name\": \"Report Service Given\", \"slug\": \"service.report\", \"pivot\": {\"role_id\": 2, \"permission_id\": 3}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 4, \"name\": \"Management Citizens\", \"slug\": \"citizens.manage\", \"pivot\": {\"role_id\": 2, \"permission_id\": 4}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 5, \"name\": \"View Audit Logs\", \"slug\": \"audit.view\", \"pivot\": {\"role_id\": 2, \"permission_id\": 5}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 6, \"name\": \"System Management\", \"slug\": \"system.manage\", \"pivot\": {\"role_id\": 2, \"permission_id\": 6}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 9, \"name\": \"Manage Villages\", \"slug\": \"villages.manage\", \"pivot\": {\"role_id\": 2, \"permission_id\": 9}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}]}', '2026-05-06 16:19:37'),
(183, 1, 'UPDATE_USER', 'users', '1', '{\"remember_token\": \"FeOWBW71kg\"}', '{\"remember_token\": \"NT0GpxJUanzsDQf20AFCaS4KdlxpXMKMsRBLMbNCQlOpVdb3QY77oM7XPIu3\"}', '2026-05-06 16:22:35'),
(184, NULL, 'LOGOUT', 'users', '1', NULL, NULL, '2026-05-06 16:22:35'),
(185, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-06 16:22:38'),
(186, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"IqZg3Opx9e\"}', '{\"remember_token\": \"LHyBbIsQ9o3q4QnCApCL7YnAsFbp4BAVwZIq6HCjQrMiL2kd2tm6FXNgXdkx\"}', '2026-05-06 16:24:12'),
(187, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-06 16:24:12'),
(188, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-06 16:24:16'),
(189, 1, 'UPDATE_USER', 'users', '1', '{\"remember_token\": \"NT0GpxJUanzsDQf20AFCaS4KdlxpXMKMsRBLMbNCQlOpVdb3QY77oM7XPIu3\"}', '{\"remember_token\": \"WN5SO8DNRkXEUR8eCGyprvjM092FyoMaVtwhEsosBVkq5qmYfvxhh719RIyi\"}', '2026-05-06 16:36:37'),
(190, NULL, 'LOGOUT', 'users', '1', NULL, NULL, '2026-05-06 16:36:37'),
(191, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-06 16:36:39'),
(192, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0140915127418975\"}', '2026-05-06 16:41:08'),
(193, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0140915127418975\"}', '2026-05-06 16:43:46'),
(194, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-07 03:24:19'),
(195, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-07 07:17:50'),
(196, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-08 05:55:22'),
(197, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-08 05:55:25'),
(198, 2, 'REPORT_SERVICE', 'service_requests', '1', NULL, '{\"id\": 1, \"notes\": \"dadsada\", \"status\": \"PENDING\", \"created_at\": \"2026-05-08T06:03:10.000000Z\", \"updated_at\": \"2026-05-08T06:03:10.000000Z\", \"citizen_nik\": \"0140915127418975\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 2}', '2026-05-08 06:03:10'),
(199, 2, 'REPORT_SERVICE', 'service_requests', '2', NULL, '{\"id\": 2, \"notes\": \"dasddada\", \"status\": \"PENDING\", \"created_at\": \"2026-05-08T06:18:48.000000Z\", \"updated_at\": \"2026-05-08T06:18:48.000000Z\", \"citizen_nik\": \"0140915127418975\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 2}', '2026-05-08 06:18:48'),
(200, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"LHyBbIsQ9o3q4QnCApCL7YnAsFbp4BAVwZIq6HCjQrMiL2kd2tm6FXNgXdkx\"}', '{\"remember_token\": \"oIoYRZnu3QFJNn0NUcNkG4kKrIuAqx1CQ0kFJP2kP5GISYcQJqCUZqYMVNyd\"}', '2026-05-08 06:38:34'),
(201, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-08 06:38:34'),
(202, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-08 06:38:39'),
(203, 1, 'UPDATE_USER', 'users', '1', '{\"remember_token\": \"WN5SO8DNRkXEUR8eCGyprvjM092FyoMaVtwhEsosBVkq5qmYfvxhh719RIyi\"}', '{\"remember_token\": \"8aYAFnFk3brUaicIOsROdyjeBVLi7JYr9jURvPCbjqXpETaTWEER7Zrk4X56\"}', '2026-05-08 06:53:45'),
(204, NULL, 'LOGOUT', 'users', '1', NULL, NULL, '2026-05-08 06:53:45'),
(205, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-08 06:53:51'),
(206, 6, 'APPROVE_SERVICE', 'service_requests', '2', NULL, '{\"citizen_nik\": \"0140915127418975\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-08 07:32:21'),
(207, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0140915127418975\"}', '2026-05-08 07:32:41'),
(208, 2, 'REPORT_SERVICE', 'service_requests', '3', NULL, '{\"id\": 3, \"notes\": null, \"status\": \"PENDING\", \"created_at\": \"2026-05-08T07:53:03.000000Z\", \"updated_at\": \"2026-05-08T07:53:03.000000Z\", \"citizen_nik\": \"0204199306493975\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 2}', '2026-05-08 07:53:03'),
(209, NULL, 'AUTO_EXPIRE_SERVICE', 'service_requests', '3', NULL, '{\"reason\": \"SLA 1 Hari: Tidak ditanggapi oleh desa tepat waktu\", \"citizen_nik\": \"0204199306493975\"}', '2026-05-08 08:00:29'),
(210, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"oIoYRZnu3QFJNn0NUcNkG4kKrIuAqx1CQ0kFJP2kP5GISYcQJqCUZqYMVNyd\"}', '{\"remember_token\": \"EeQWhEPOJtx6KYXgmnBLWrnzqYZpYMDxSq3jKiK3yEp1xnAJfMfGwqDfhLpf\"}', '2026-05-08 08:21:17'),
(211, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-08 08:21:17'),
(212, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-08 08:21:21'),
(213, NULL, 'AUTO_EXPIRE_SERVICE', 'service_requests', '3', NULL, '{\"reason\": \"SLA 1 Hari: Tidak ditanggapi oleh desa tepat waktu\", \"citizen_nik\": \"0204199306493975\"}', '2026-05-08 08:29:28'),
(214, 1, 'REPORT_SERVICE', 'service_requests', '4', NULL, '{\"id\": 4, \"notes\": \"aaaa\", \"status\": \"PENDING\", \"created_at\": \"2026-05-08T08:36:45.000000Z\", \"updated_at\": \"2026-05-08T08:36:45.000000Z\", \"citizen_nik\": \"0204199306493975\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 1}', '2026-05-08 08:36:45'),
(215, 6, 'APPROVE_SERVICE', 'service_requests', '4', NULL, '{\"citizen_nik\": \"0204199306493975\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-08 08:37:38'),
(216, 1, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\"}', '2026-05-08 08:43:33'),
(217, 1, 'REPORT_SERVICE', 'service_requests', '5', NULL, '{\"id\": 5, \"notes\": \"dddd\", \"status\": \"PENDING\", \"created_at\": \"2026-05-08T08:50:06.000000Z\", \"updated_at\": \"2026-05-08T08:50:06.000000Z\", \"citizen_nik\": \"0204199306493975\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 1}', '2026-05-08 08:50:06'),
(218, 1, 'REPORT_SERVICE', 'service_requests', '6', NULL, '{\"id\": 6, \"notes\": \"dddd\", \"status\": \"PENDING\", \"created_at\": \"2026-05-08T08:52:54.000000Z\", \"updated_at\": \"2026-05-08T08:52:54.000000Z\", \"citizen_nik\": \"0204199306493975\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 1}', '2026-05-08 08:52:54'),
(219, 1, 'REPORT_SERVICE', 'service_requests', '7', NULL, '{\"id\": 7, \"notes\": \"ffff\", \"status\": \"PENDING\", \"created_at\": \"2026-05-08T08:53:22.000000Z\", \"updated_at\": \"2026-05-08T08:53:22.000000Z\", \"citizen_nik\": \"0204199306493975\", \"service_type\": \"PENGANTAR PINDAH\", \"front_office_user_id\": 1}', '2026-05-08 08:53:22'),
(220, 6, 'APPROVE_SERVICE', 'service_requests', '6', NULL, '{\"citizen_nik\": \"0204199306493975\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-08 09:02:21'),
(221, 1, 'UPDATE_USER', 'users', '1', '{\"remember_token\": \"8aYAFnFk3brUaicIOsROdyjeBVLi7JYr9jURvPCbjqXpETaTWEER7Zrk4X56\"}', '{\"remember_token\": \"AdDSM50gKFtWuq89Zjj2UTL3Z60cY8PI0l9YrWrOQZ0X1OCoKBugXwM6g7yR\"}', '2026-05-08 09:02:48'),
(222, NULL, 'LOGOUT', 'users', '1', NULL, NULL, '2026-05-08 09:02:48'),
(223, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-08 09:02:53'),
(224, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"EeQWhEPOJtx6KYXgmnBLWrnzqYZpYMDxSq3jKiK3yEp1xnAJfMfGwqDfhLpf\"}', '{\"remember_token\": \"XHE1tp373cQDRDGLieczqc9NWaussZ06LFgIVEfAtrcIiJ5VqL85sSheJyz0\"}', '2026-05-08 09:13:39'),
(225, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-08 09:13:39'),
(226, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-08 09:13:44'),
(227, 1, 'UPDATE_ROLE', 'roles', '3', '{\"id\": 3, \"name\": \"PetugasFrontOffice\", \"slug\": \"petugasfrontoffice\", \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null, \"permissions\": [{\"id\": 1, \"name\": \"Verify Poverty Status\", \"slug\": \"poverty.verify\", \"pivot\": {\"role_id\": 3, \"permission_id\": 1}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 2, \"name\": \"Print Verification Proof\", \"slug\": \"poverty.print_proof\", \"pivot\": {\"role_id\": 3, \"permission_id\": 2}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 3, \"name\": \"Report Service Usage\", \"slug\": \"service.report\", \"pivot\": {\"role_id\": 3, \"permission_id\": 3}, \"created_at\": \"2026-05-08T06:42:03.000000Z\", \"updated_at\": \"2026-05-08T06:42:03.000000Z\", \"description\": \"Akses untuk melaporkan penggunaan layanan atau meminta verifikasi.\"}]}', '{\"id\": 3, \"name\": \"PetugasFrontOffice\", \"slug\": \"petugasfrontoffice\", \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null, \"permissions\": [{\"id\": 1, \"name\": \"Verify Poverty Status\", \"slug\": \"poverty.verify\", \"pivot\": {\"role_id\": 3, \"permission_id\": 1}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 2, \"name\": \"Print Verification Proof\", \"slug\": \"poverty.print_proof\", \"pivot\": {\"role_id\": 3, \"permission_id\": 2}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 3, \"name\": \"Report Service Usage\", \"slug\": \"service.report\", \"pivot\": {\"role_id\": 3, \"permission_id\": 3}, \"created_at\": \"2026-05-08T06:42:03.000000Z\", \"updated_at\": \"2026-05-08T06:42:03.000000Z\", \"description\": \"Akses untuk melaporkan penggunaan layanan atau meminta verifikasi.\"}, {\"id\": 5, \"name\": \"View Audit Logs\", \"slug\": \"audit.view\", \"pivot\": {\"role_id\": 3, \"permission_id\": 5}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}]}', '2026-05-08 09:14:00'),
(228, 1, 'UPDATE_USER', 'users', '1', '{\"remember_token\": \"AdDSM50gKFtWuq89Zjj2UTL3Z60cY8PI0l9YrWrOQZ0X1OCoKBugXwM6g7yR\"}', '{\"remember_token\": \"t7Bb4Iv9fnueQTeT8wVCzv30l3SzSPVxzr3EobkImixKMQAfDNVA3B8W6Mw1\"}', '2026-05-08 09:14:08'),
(229, NULL, 'LOGOUT', 'users', '1', NULL, NULL, '2026-05-08 09:14:08'),
(230, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-08 09:14:11'),
(231, 2, 'REPORT_SERVICE', 'service_requests', '8', NULL, '{\"id\": 8, \"notes\": null, \"status\": \"PENDING\", \"created_at\": \"2026-05-08T09:17:22.000000Z\", \"updated_at\": \"2026-05-08T09:17:22.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 2}', '2026-05-08 09:17:22'),
(232, 6, 'REJECT_SERVICE', 'service_requests', '8', NULL, '{\"reason\": \"dosen\"}', '2026-05-08 09:17:37'),
(233, 2, 'REPORT_SERVICE', 'service_requests', '9', NULL, '{\"id\": 9, \"notes\": \"rrrrr\", \"status\": \"PENDING\", \"created_at\": \"2026-05-08T09:22:39.000000Z\", \"updated_at\": \"2026-05-08T09:22:39.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 2}', '2026-05-08 09:22:39'),
(234, 6, 'REJECT_SERVICE', 'service_requests', '9', NULL, '{\"reason\": \"dosen\"}', '2026-05-08 09:22:52'),
(235, 6, 'REJECT_SERVICE', 'service_requests', '9', NULL, '{\"reason\": \"hjjjh\"}', '2026-05-08 09:28:35'),
(236, 6, 'REJECT_SERVICE', 'service_requests', '9', NULL, '{\"reason\": \"dosen\"}', '2026-05-08 09:30:06'),
(237, 6, 'REJECT_SERVICE', 'service_requests', '9', NULL, '{\"reason\": \"dosen un\"}', '2026-05-08 09:36:59'),
(238, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-08 16:17:57'),
(239, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-08 19:11:31'),
(240, 2, 'REPORT_SERVICE', 'service_requests', '10', NULL, '{\"id\": 10, \"notes\": \"bukan dosen\", \"status\": \"PENDING\", \"created_at\": \"2026-05-08T19:12:37.000000Z\", \"updated_at\": \"2026-05-08T19:12:37.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 2}', '2026-05-08 19:12:37'),
(241, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-08 19:12:49'),
(242, 6, 'APPROVE_SERVICE', 'service_requests', '10', NULL, '{\"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-08 19:12:57'),
(243, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\"}', '2026-05-08 19:13:04'),
(244, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"XHE1tp373cQDRDGLieczqc9NWaussZ06LFgIVEfAtrcIiJ5VqL85sSheJyz0\"}', '{\"remember_token\": \"2K0fohPJ99jtHhqG4A0UI9b7V4z3e7DuWgUG9FKvPnfootpJzwS3Libz66ny\"}', '2026-05-08 19:28:20'),
(245, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-08 19:28:20'),
(246, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-08 19:28:24'),
(247, 1, 'UPDATE_USER', 'users', '1', '{\"remember_token\": \"t7Bb4Iv9fnueQTeT8wVCzv30l3SzSPVxzr3EobkImixKMQAfDNVA3B8W6Mw1\"}', '{\"remember_token\": \"BGFDVsNHv40XDPIprwAGerAU474vMYiSMIAgYhX7XxPhR0a7HR0zVpjbRPYe\"}', '2026-05-08 19:28:40'),
(248, NULL, 'LOGOUT', 'users', '1', NULL, NULL, '2026-05-08 19:28:40'),
(249, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-08 19:28:43'),
(250, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-08 19:40:35'),
(251, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-08 19:52:33'),
(252, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-08 19:55:24'),
(253, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-09 06:15:23'),
(254, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-09 06:17:09'),
(255, 2, 'REPORT_SERVICE', 'service_requests', '11', NULL, '{\"id\": 11, \"notes\": \"domisili\", \"status\": \"PENDING\", \"created_at\": \"2026-05-09T06:17:24.000000Z\", \"updated_at\": \"2026-05-09T06:17:24.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN DOMISILI\", \"front_office_user_id\": 2}', '2026-05-09 06:17:24'),
(256, 6, 'REJECT_SERVICE', 'service_requests', '11', NULL, '{\"reason\": \"bukan warga saya\"}', '2026-05-09 06:40:06'),
(257, 6, 'APPROVE_SERVICE', 'service_requests', '11', NULL, '{\"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN DOMISILI\"}', '2026-05-09 06:56:09'),
(258, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-09 06:56:34'),
(259, 2, 'REPORT_SERVICE', 'service_requests', '12', NULL, '{\"id\": 12, \"notes\": \"keperluan PNS\", \"status\": \"PENDING\", \"created_at\": \"2026-05-09T07:11:19.000000Z\", \"updated_at\": \"2026-05-09T07:11:19.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN DOMISILI\", \"front_office_user_id\": 2}', '2026-05-09 07:11:19'),
(260, 6, 'APPROVE_SERVICE', 'service_requests', '12', NULL, '{\"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN DOMISILI\"}', '2026-05-09 07:11:27'),
(261, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-09 07:31:57'),
(262, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-09 07:49:18'),
(263, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-10 05:41:14'),
(264, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-10 05:48:29'),
(265, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-11 03:33:00'),
(266, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-11 03:34:03'),
(267, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"2K0fohPJ99jtHhqG4A0UI9b7V4z3e7DuWgUG9FKvPnfootpJzwS3Libz66ny\"}', '{\"remember_token\": \"A6G9n54fYJPtDTv5iN9j6sDP2CEOcLl9BG1GBrVDVsTDWq4dmiaaE6sqb7gL\"}', '2026-05-11 03:35:38'),
(268, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-11 03:35:38'),
(269, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-11 03:35:42'),
(270, 1, 'UPDATE_USER', 'users', '1', '{\"remember_token\": \"BGFDVsNHv40XDPIprwAGerAU474vMYiSMIAgYhX7XxPhR0a7HR0zVpjbRPYe\"}', '{\"remember_token\": \"p1aoUNJAsLEJWF1sw59luh4kKcQwT8CKVi176MdGTddoIIiTpZTXvCbD0IK7\"}', '2026-05-11 03:36:19'),
(271, NULL, 'LOGOUT', 'users', '1', NULL, NULL, '2026-05-11 03:36:19'),
(272, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-11 03:36:21'),
(273, 2, 'REPORT_SERVICE', 'service_requests', '13', NULL, '{\"id\": 13, \"notes\": \"dsaasdsdas\", \"status\": \"PENDING\", \"created_at\": \"2026-05-11T04:00:41.000000Z\", \"updated_at\": \"2026-05-11T04:00:41.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"PENGANTAR PINDAH\", \"front_office_user_id\": 2}', '2026-05-11 04:00:41'),
(274, 6, 'REJECT_SERVICE', 'service_requests', '13', NULL, '{\"reason\": \"sjdhagd\"}', '2026-05-11 04:02:52'),
(275, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"A6G9n54fYJPtDTv5iN9j6sDP2CEOcLl9BG1GBrVDVsTDWq4dmiaaE6sqb7gL\"}', '{\"remember_token\": \"tICts4ngbvyo4HhLVuES0itOKnTblG08urJzwxFLNWWD5GMla5nZ7uaYLwmt\"}', '2026-05-11 04:06:49'),
(276, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-11 04:06:49'),
(277, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-11 04:06:52'),
(278, 1, 'UPDATE_ROLE', 'roles', '2', '{\"id\": 2, \"name\": \"OperatorDesa\", \"slug\": \"operatordesa\", \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null, \"permissions\": [{\"id\": 1, \"name\": \"Verify Poverty Status\", \"slug\": \"poverty.verify\", \"pivot\": {\"role_id\": 2, \"permission_id\": 1}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 12, \"name\": \"Manage Service Requests\", \"slug\": \"service.manage\", \"pivot\": {\"role_id\": 2, \"permission_id\": 12}, \"created_at\": \"2026-05-08T06:42:03.000000Z\", \"updated_at\": \"2026-05-08T06:42:03.000000Z\", \"description\": \"Akses untuk mengelola (setuju/tolak) permintaan layanan dari desa.\"}]}', '{\"id\": 2, \"name\": \"OperatorDesa\", \"slug\": \"operatordesa\", \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null, \"permissions\": [{\"id\": 1, \"name\": \"Verify Poverty Status\", \"slug\": \"poverty.verify\", \"pivot\": {\"role_id\": 2, \"permission_id\": 1}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 4, \"name\": \"Management Citizens\", \"slug\": \"citizens.manage\", \"pivot\": {\"role_id\": 2, \"permission_id\": 4}, \"created_at\": \"2026-05-04T05:32:21.000000Z\", \"updated_at\": \"2026-05-04T05:32:21.000000Z\", \"description\": null}, {\"id\": 12, \"name\": \"Manage Service Requests\", \"slug\": \"service.manage\", \"pivot\": {\"role_id\": 2, \"permission_id\": 12}, \"created_at\": \"2026-05-08T06:42:03.000000Z\", \"updated_at\": \"2026-05-08T06:42:03.000000Z\", \"description\": \"Akses untuk mengelola (setuju/tolak) permintaan layanan dari desa.\"}]}', '2026-05-11 04:10:09'),
(279, 1, 'UPDATE_USER', 'users', '1', '{\"remember_token\": \"p1aoUNJAsLEJWF1sw59luh4kKcQwT8CKVi176MdGTddoIIiTpZTXvCbD0IK7\"}', '{\"remember_token\": \"Boj29DgUnyc1ysPrzpTIyz8xjYRhKGb4Y7Ft42bA2Dh7Iudwd8dMqOFeDKLj\"}', '2026-05-11 04:10:15'),
(280, NULL, 'LOGOUT', 'users', '1', NULL, NULL, '2026-05-11 04:10:15'),
(281, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-11 04:10:19'),
(282, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-11 04:12:42'),
(283, 2, 'REPORT_SERVICE', 'service_requests', '14', NULL, '{\"id\": 14, \"notes\": \"das\", \"status\": \"PENDING\", \"created_at\": \"2026-05-11T04:13:57.000000Z\", \"updated_at\": \"2026-05-11T04:13:57.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 2}', '2026-05-11 04:13:57'),
(284, 6, 'APPROVE_SERVICE', 'service_requests', '14', NULL, '{\"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-11 04:14:27'),
(285, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-11 04:14:33'),
(286, 6, 'APPROVE_SERVICE', 'service_requests', '14', NULL, '{\"citizen_nik\": \"0548397596571350\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-11 04:31:43'),
(287, 2, 'REPORT_SERVICE', 'service_requests', '15', NULL, '{\"id\": 15, \"notes\": \"TUJUAN: dasdads\\nCATATAN: aaaa\", \"status\": \"PENDING\", \"created_at\": \"2026-05-11T04:45:18.000000Z\", \"updated_at\": \"2026-05-11T04:45:18.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"PENGANTAR PINDAH\", \"front_office_user_id\": 2}', '2026-05-11 04:45:18'),
(288, 6, 'APPROVE_SERVICE', 'service_requests', '15', NULL, '{\"citizen_nik\": \"0548397596571350\", \"service_type\": \"PENGANTAR PINDAH\"}', '2026-05-11 04:50:36'),
(289, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"move\"}', '2026-05-11 04:51:27'),
(290, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"tICts4ngbvyo4HhLVuES0itOKnTblG08urJzwxFLNWWD5GMla5nZ7uaYLwmt\"}', '{\"remember_token\": \"qyRmE7qWOZhHO47Pfp3JQh5uFygOZBdQ5XAL6D9ELy7ILYX2gQCt0vMaPwfs\"}', '2026-05-11 04:56:39'),
(291, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-11 04:56:39'),
(292, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-11 04:56:45'),
(293, 2, 'REPORT_SERVICE', 'service_requests', '16', NULL, '{\"id\": 16, \"notes\": \"TANGGAL: 12 May 2026\\nLOKASI: sda\\nPENYEBAB: 123\\nCATATAN: dasd\", \"status\": \"PENDING\", \"created_at\": \"2026-05-11T05:35:15.000000Z\", \"updated_at\": \"2026-05-11T05:35:15.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"SURAT KEMATIAN\", \"front_office_user_id\": 2}', '2026-05-11 05:35:15'),
(294, 6, 'REJECT_SERVICE', 'service_requests', '16', NULL, '{\"reason\": \"dasdsa\"}', '2026-05-11 05:35:32'),
(295, 6, 'REJECT_SERVICE', 'service_requests', '16', NULL, '{\"reason\": \"belum meninggal\"}', '2026-05-11 05:41:00'),
(296, 2, 'REPORT_SERVICE', 'service_requests', '17', NULL, '{\"id\": 17, \"notes\": \"TANGGAL: 10 May 2026\\nLOKASI: tubaba\\nPENYEBAB: haru tua\", \"status\": \"PENDING\", \"created_at\": \"2026-05-11T05:45:03.000000Z\", \"updated_at\": \"2026-05-11T05:45:03.000000Z\", \"citizen_nik\": \"0548397596571350\", \"service_type\": \"SURAT KEMATIAN\", \"front_office_user_id\": 2}', '2026-05-11 05:45:03'),
(297, 6, 'APPROVE_SERVICE', 'service_requests', '17', NULL, '{\"citizen_nik\": \"0548397596571350\", \"service_type\": \"SURAT KEMATIAN\"}', '2026-05-11 05:45:17'),
(298, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"death\"}', '2026-05-11 06:20:06'),
(299, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"death\"}', '2026-05-11 06:50:46'),
(300, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"3721700552332154\", \"type\": \"poverty\"}', '2026-05-11 06:56:06'),
(301, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"3721700552332154\", \"type\": \"poverty\"}', '2026-05-11 06:59:46'),
(302, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"3721700552332154\", \"type\": \"poverty\"}', '2026-05-11 07:01:07'),
(303, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"3721700552332154\", \"type\": \"poverty\"}', '2026-05-11 07:01:51'),
(304, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"3721700552332154\", \"type\": \"poverty\"}', '2026-05-11 14:03:57'),
(305, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"3721700552332154\", \"type\": \"poverty\"}', '2026-05-11 14:04:29'),
(306, 2, 'REPORT_SERVICE', 'service_requests', '18', NULL, '{\"id\": 18, \"notes\": \"cetak ktp\", \"status\": \"PENDING\", \"created_at\": \"2026-05-11T07:05:32.000000Z\", \"updated_at\": \"2026-05-11T07:05:32.000000Z\", \"citizen_nik\": \"3721700552332154\", \"service_type\": \"KETERANGAN DOMISILI\", \"front_office_user_id\": 2}', '2026-05-11 14:05:32'),
(307, 6, 'APPROVE_SERVICE', 'service_requests', '18', NULL, '{\"citizen_nik\": \"3721700552332154\", \"service_type\": \"KETERANGAN DOMISILI\"}', '2026-05-11 14:05:43'),
(308, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"3721700552332154\", \"type\": \"domicile\"}', '2026-05-11 14:05:54'),
(309, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"3721700552332154\", \"type\": \"domicile\"}', '2026-05-11 14:29:50'),
(310, 2, 'REPORT_SERVICE', 'service_requests', '19', NULL, '{\"id\": 19, \"notes\": \"TUJUAN: pindah desa dan kab\\nCATATAN: pindah desa\", \"status\": \"PENDING\", \"created_at\": \"2026-05-11T08:01:12.000000Z\", \"updated_at\": \"2026-05-11T08:01:12.000000Z\", \"citizen_nik\": \"3721700552332154\", \"service_type\": \"PENGANTAR PINDAH\", \"front_office_user_id\": 2}', '2026-05-11 15:01:12'),
(311, 6, 'APPROVE_SERVICE', 'service_requests', '19', NULL, '{\"citizen_nik\": \"3721700552332154\", \"service_type\": \"PENGANTAR PINDAH\"}', '2026-05-11 15:01:31'),
(312, 2, 'CREATE_CITIZEN', 'citizens', '1231231231231231', NULL, '{\"nik\": \"1231231231231231\", \"kontak\": \"1231231232\", \"desa_id\": \"3\", \"tgl_lahir\": \"2013-01-11 00:00:00\", \"created_at\": \"2026-05-11 15:05:26\", \"updated_at\": \"2026-05-11 15:05:26\", \"alamat_desa\": \"eyJpdiI6IkN5d0dvdnpFc0M5SU9HdUpaV0Y0alE9PSIsInZhbHVlIjoiNjZrUEhUeTNwT0NxZW96eFRXWTMzZz09IiwibWFjIjoiZmRlNjMyOWFjYWU4NWI3NGNhMTI1YjRhMGEwMmFlYTJkNDRkODUxNTRjZjI1OThmNjE3ODcyYzczZjI3YmZhZSIsInRhZyI6IiJ9\", \"nama_lengkap\": \"aaaa\", \"__display_name\": \"aaaa\"}', '2026-05-11 15:05:26'),
(313, 2, 'REPORT_ARRIVAL', 'arrival_records', '1', NULL, '{\"id\": 1, \"notes\": null, \"status\": \"ACTIVE\", \"created_at\": \"2026-05-11T08:05:26.000000Z\", \"updated_at\": \"2026-05-11T08:05:26.000000Z\", \"citizen_nik\": \"1231231231231231\", \"recorded_by\": 2, \"arrival_date\": \"2026-05-10T17:00:00.000000Z\", \"previous_address\": \"sdasdsa\"}', '2026-05-11 15:05:26'),
(314, 2, 'CREATE_CITIZEN', 'citizens', '1231231231231232', NULL, '{\"nik\": \"1231231231231232\", \"kontak\": \"123\", \"desa_id\": \"3\", \"tgl_lahir\": \"1990-05-11 00:00:00\", \"created_at\": \"2026-05-11 15:12:19\", \"updated_at\": \"2026-05-11 15:12:19\", \"alamat_desa\": \"eyJpdiI6IkVDT1M2K1NBdmg4eWR6TDNyZDNJVFE9PSIsInZhbHVlIjoieHI2U1h2Q2xXb05ZcjdDa2UyNzU2UT09IiwibWFjIjoiYmYzZTE1NjVhNWE3YzEwNWEyNGY5Y2FmMGU1ZWE4MzE5YzNiZjJjM2E4MjFkMjE4MWMzYzdhYTYxNzAwMzZhZiIsInRhZyI6IiJ9\", \"nama_lengkap\": \"bbbbb\", \"__display_name\": \"bbbbb\"}', '2026-05-11 15:12:19'),
(315, 2, 'REPORT_ARRIVAL', 'arrival_records', '2', NULL, '{\"id\": 2, \"notes\": null, \"status\": \"ACTIVE\", \"created_at\": \"2026-05-11T08:12:19.000000Z\", \"updated_at\": \"2026-05-11T08:12:19.000000Z\", \"citizen_nik\": \"1231231231231232\", \"recorded_by\": 2, \"arrival_date\": \"2026-05-10T17:00:00.000000Z\", \"previous_address\": \"dadsa\"}', '2026-05-11 15:12:19'),
(316, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-12 10:50:40'),
(317, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-12 11:45:20'),
(318, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-12 16:40:56'),
(319, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-12 16:42:27'),
(320, 2, 'REPORT_SERVICE', 'service_requests', '22', NULL, '{\"id\": 22, \"notes\": \"fdyughguhfy\", \"status\": \"PENDING\", \"created_at\": \"2026-05-12T09:43:51.000000Z\", \"updated_at\": \"2026-05-12T09:43:51.000000Z\", \"citizen_nik\": \"0064622836742991\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 2}', '2026-05-12 16:43:51'),
(321, 6, 'APPROVE_SERVICE', 'service_requests', '22', NULL, '{\"citizen_nik\": \"0064622836742991\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-12 16:44:14'),
(322, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0064622836742991\", \"type\": \"poverty\"}', '2026-05-12 16:44:23'),
(323, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0064622836742991\", \"type\": \"poverty\"}', '2026-05-12 16:47:00'),
(324, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"qyRmE7qWOZhHO47Pfp3JQh5uFygOZBdQ5XAL6D9ELy7ILYX2gQCt0vMaPwfs\"}', '{\"remember_token\": \"c8JQ0KTR37oqsusAZw8rUcUniXZcK8aeSw2oHKRTFkK3lnVnk9PpondArcTG\"}', '2026-05-12 17:26:21'),
(325, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-12 17:26:21'),
(326, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-12 17:26:26'),
(327, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0064622836742991\", \"type\": \"poverty\"}', '2026-05-12 17:34:07'),
(328, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-18 11:58:00'),
(329, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-18 21:07:25'),
(330, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"c8JQ0KTR37oqsusAZw8rUcUniXZcK8aeSw2oHKRTFkK3lnVnk9PpondArcTG\"}', '{\"remember_token\": \"P9RyN1W0KWBPnkiHLMcwtjX6XZUEbuJsJdNfJKCM6O5yVgtJGeGuGe18OW3x\"}', '2026-05-18 21:07:41'),
(331, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-18 21:07:41'),
(332, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-18 21:08:04'),
(333, 6, 'APPROVE_SERVICE', 'service_requests', '22', NULL, '{\"citizen_nik\": \"0064622836742991\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-18 21:26:56'),
(334, NULL, 'LOGOUT', 'users', '6', NULL, NULL, '2026-05-18 21:27:02'),
(335, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-18 21:27:13'),
(336, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0064622836742991\", \"type\": \"poverty\"}', '2026-05-18 21:27:50'),
(337, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0064622836742991\", \"type\": \"poverty\"}', '2026-05-18 21:28:28'),
(338, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0064622836742991\", \"type\": \"poverty\"}', '2026-05-18 21:33:42'),
(339, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0064622836742991\", \"type\": \"poverty\"}', '2026-05-18 21:36:38'),
(340, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-19 19:25:58'),
(341, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 19:30:27'),
(342, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0064622836742991\", \"type\": \"poverty\"}', '2026-05-19 19:33:01'),
(343, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"P9RyN1W0KWBPnkiHLMcwtjX6XZUEbuJsJdNfJKCM6O5yVgtJGeGuGe18OW3x\"}', '{\"remember_token\": \"nxHhaVG531DH0y4K6OlUxeYK2QGfJ8plUeyiYDOaZa6F1dvF0VNkCxcvLNEp\"}', '2026-05-19 19:43:05'),
(344, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-19 19:43:05'),
(345, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-19 19:43:09'),
(346, 6, 'APPROVE_SERVICE', 'service_requests', '22', NULL, '{\"citizen_nik\": \"0064622836742991\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-19 19:55:18'),
(347, NULL, 'LOGOUT', 'users', '6', NULL, NULL, '2026-05-19 20:06:07'),
(348, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-19 20:06:10'),
(349, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:06:33'),
(350, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:13:51'),
(351, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:21:23'),
(352, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:23:04'),
(353, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:25:56'),
(354, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:28:59'),
(355, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:29:23'),
(356, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:30:53'),
(357, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:31:07'),
(358, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:31:22'),
(359, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:31:33'),
(360, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:31:48'),
(361, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:32:06'),
(362, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 20:32:19'),
(363, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 20:33:02'),
(364, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 20:38:01'),
(365, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 20:44:28'),
(366, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 20:48:12'),
(367, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 20:52:53'),
(368, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 20:56:58'),
(369, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 20:57:20'),
(370, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 20:58:16'),
(371, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:00:36'),
(372, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:09:13');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `target_table`, `target_id`, `old_value`, `new_value`, `timestamp`) VALUES
(373, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:11:46'),
(374, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:45:52'),
(375, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:48:18'),
(376, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:50:22'),
(377, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:52:31'),
(378, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:54:30'),
(379, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:54:43'),
(380, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 21:56:06'),
(381, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 21:57:54'),
(382, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:03:22'),
(383, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:03:30'),
(384, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:06:02'),
(385, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:06:10'),
(386, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:07:16'),
(387, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:07:26'),
(388, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:09:58'),
(389, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:10:05'),
(390, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:10:42'),
(391, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:10:53'),
(392, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:12:09'),
(393, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:12:21'),
(394, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:12:29'),
(395, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:12:48'),
(396, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:13:00'),
(397, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:13:12'),
(398, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:13:20'),
(399, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:16:36'),
(400, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:17:22'),
(401, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:17:29'),
(402, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:24:17'),
(403, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:24:23'),
(404, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:28:41'),
(405, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:28:47'),
(406, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:34:10'),
(407, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:37:02'),
(408, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:37:08'),
(409, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:38:36'),
(410, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:42:05'),
(411, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:42:13'),
(412, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:43:17'),
(413, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:45:12'),
(414, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:45:27'),
(415, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:45:35'),
(416, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:46:50'),
(417, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:46:59'),
(418, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:51:35'),
(419, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:51:41'),
(420, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 22:56:39'),
(421, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:56:47'),
(422, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:57:21'),
(423, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:57:53'),
(424, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 22:58:17'),
(425, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:00:15'),
(426, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:01:17'),
(427, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:02:33'),
(428, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:05:15'),
(429, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:05:22'),
(430, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:08:01'),
(431, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:08:10'),
(432, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:13:37'),
(433, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:14:12'),
(434, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:14:19'),
(435, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:20:49'),
(436, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:21:00'),
(437, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:21:41'),
(438, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:22:32'),
(439, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:23:40'),
(440, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:23:47'),
(441, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:24:00'),
(442, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:24:03'),
(443, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:24:54'),
(444, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:26:32'),
(445, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:26:38'),
(446, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:37:23'),
(447, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:39:53'),
(448, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:41:21'),
(449, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:41:37'),
(450, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:45:37'),
(451, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:45:54'),
(452, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:46:06'),
(453, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-19 23:46:44'),
(454, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-19 23:47:30'),
(455, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:00:12'),
(456, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:00:15'),
(457, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:01:25'),
(458, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:02:47'),
(459, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:03:00'),
(460, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:11:43'),
(461, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:11:48'),
(462, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:13:25'),
(463, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:14:36'),
(464, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:20:52'),
(465, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:20:58'),
(466, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:25:53'),
(467, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:26:04'),
(468, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:26:25'),
(469, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:26:31'),
(470, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:27:21'),
(471, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:27:31'),
(472, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:28:46'),
(473, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:28:59'),
(474, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:29:57'),
(475, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:32:50'),
(476, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:32:52'),
(477, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:33:43'),
(478, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:34:56'),
(479, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:35:05'),
(480, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:35:40'),
(481, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:35:41'),
(482, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:36:08'),
(483, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:36:21'),
(484, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:36:35'),
(485, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:36:48'),
(486, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:39:13'),
(487, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:39:32'),
(488, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:40:30'),
(489, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:41:04'),
(490, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:41:11'),
(491, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:42:02'),
(492, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:42:40'),
(493, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:42:42'),
(494, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:43:34'),
(495, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:44:06'),
(496, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:44:25'),
(497, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:46:08'),
(498, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:49:30'),
(499, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:50:26'),
(500, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:50:45'),
(501, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:50:50'),
(502, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:51:08'),
(503, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:51:35'),
(504, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:52:03'),
(505, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:52:17'),
(506, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:52:22'),
(507, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:53:10'),
(508, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:53:28'),
(509, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:54:23'),
(510, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:54:31'),
(511, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:55:18'),
(512, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:56:12'),
(513, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:56:39'),
(514, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:58:05'),
(515, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 00:58:19'),
(516, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:58:37'),
(517, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 00:59:45'),
(518, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 01:02:31'),
(519, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 01:02:37'),
(520, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 01:02:56'),
(521, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 01:05:35'),
(522, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 01:08:22'),
(523, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 01:08:34'),
(524, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 01:09:48'),
(525, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 01:16:59'),
(526, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 01:18:18'),
(527, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 01:49:05'),
(528, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 01:49:17'),
(529, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:07:13'),
(530, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:07:49'),
(531, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:08:42'),
(532, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 02:08:57'),
(533, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 02:09:26'),
(534, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:09:48'),
(535, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 02:15:32'),
(536, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 02:19:42'),
(537, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 02:26:31'),
(538, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:26:41'),
(539, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 02:28:17'),
(540, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 02:28:47'),
(541, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 02:36:31'),
(542, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:36:39'),
(543, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:42:58'),
(544, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 02:43:10'),
(545, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:54:35'),
(546, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:56:43'),
(547, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:58:29'),
(548, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 02:59:59'),
(549, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 03:03:27'),
(550, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 03:04:57'),
(551, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 03:12:55'),
(552, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 03:13:18'),
(553, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 03:23:18'),
(554, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 03:23:52'),
(555, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 03:25:00'),
(556, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 03:25:14'),
(557, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 03:25:41'),
(558, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 03:26:59'),
(559, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 03:28:17'),
(560, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 03:31:31'),
(561, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 03:32:46'),
(562, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 03:33:54'),
(563, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 03:34:20'),
(564, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 03:34:36'),
(565, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 03:35:27'),
(566, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 03:45:27'),
(567, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 04:01:18'),
(568, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 04:01:35'),
(569, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-20 11:30:53'),
(570, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:30:53'),
(571, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:31:04'),
(572, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:31:23'),
(573, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:31:34'),
(574, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:31:50'),
(575, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:32:05'),
(576, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:32:21'),
(577, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:32:43'),
(578, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:32:57'),
(579, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:33:07'),
(580, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:33:21'),
(581, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:34:07'),
(582, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:34:33'),
(583, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:36:38'),
(584, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:38:54'),
(585, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:41:19'),
(586, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:45:19'),
(587, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:57:05'),
(588, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:57:13'),
(589, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:58:15'),
(590, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:58:27'),
(591, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:58:49'),
(592, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 11:59:00'),
(593, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:59:30'),
(594, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 11:59:54'),
(595, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:00:06'),
(596, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:00:48'),
(597, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:00:57'),
(598, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:01:17'),
(599, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:06:16'),
(600, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:06:26'),
(601, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:12:26'),
(602, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:13:11'),
(603, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:13:30'),
(604, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:13:50'),
(605, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:14:27'),
(606, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:16:47'),
(607, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:24:54'),
(608, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:25:05'),
(609, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:25:44'),
(610, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:27:04'),
(611, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:27:15'),
(612, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:27:53'),
(613, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:29:33'),
(614, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:29:44'),
(615, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:32:02'),
(616, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:32:11'),
(617, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:33:57'),
(618, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:34:30'),
(619, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:37:20'),
(620, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:37:29'),
(621, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:42:21'),
(622, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:43:13'),
(623, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 12:50:25'),
(624, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:57:30'),
(625, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:57:58'),
(626, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 12:59:48'),
(627, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:00:11'),
(628, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:00:31'),
(629, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:00:52'),
(630, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:01:14'),
(631, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:02:03'),
(632, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:03:47'),
(633, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:08:09'),
(634, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:12:22'),
(635, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:13:16'),
(636, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:14:10'),
(637, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:17:13'),
(638, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:18:42'),
(639, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 13:18:45'),
(640, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:19:56'),
(641, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:20:30'),
(642, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:22:22'),
(643, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 13:23:48'),
(644, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:28:57'),
(645, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:30:41'),
(646, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:31:39'),
(647, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:32:05'),
(648, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:32:55'),
(649, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:34:56'),
(650, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:35:32'),
(651, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:36:49'),
(652, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:37:49'),
(653, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:39:28'),
(654, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:42:46'),
(655, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:44:25'),
(656, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:46:09'),
(657, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:47:07'),
(658, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:48:22'),
(659, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:49:12'),
(660, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:49:26'),
(661, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:50:19'),
(662, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:51:10'),
(663, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:55:53'),
(664, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:56:24'),
(665, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:56:47'),
(666, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 13:59:44'),
(667, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:02:55'),
(668, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:03:55'),
(669, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:05:58'),
(670, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:06:51'),
(671, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:07:27'),
(672, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:08:21'),
(673, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:11:27'),
(674, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:11:51'),
(675, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:12:36'),
(676, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:12:49'),
(677, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:14:13'),
(678, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:14:55'),
(679, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:16:53'),
(680, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:19:00'),
(681, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:20:24'),
(682, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:20:37'),
(683, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:21:34'),
(684, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:24:29'),
(685, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:24:49'),
(686, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:26:15'),
(687, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:26:24'),
(688, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:27:31'),
(689, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:30:00'),
(690, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:34:04'),
(691, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:38:56'),
(692, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:41:58'),
(693, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:42:37'),
(694, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:48:55'),
(695, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 14:49:17'),
(696, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 15:08:07'),
(697, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 15:08:13'),
(698, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 15:15:23'),
(699, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 15:15:30'),
(700, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-20 21:18:03'),
(701, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:18:03'),
(702, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:18:06'),
(703, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:18:23'),
(704, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:18:30'),
(705, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:18:39'),
(706, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:18:46'),
(707, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:21:57'),
(708, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:22:59'),
(709, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:23:33'),
(710, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:23:45'),
(711, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:24:18'),
(712, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:24:25'),
(713, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:25:07'),
(714, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:25:16'),
(715, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:26:32'),
(716, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:28:13'),
(717, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:28:23'),
(718, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:28:37'),
(719, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:29:21'),
(720, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:30:18'),
(721, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:31:07'),
(722, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:32:49'),
(723, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:33:48'),
(724, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:34:28'),
(725, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:35:04'),
(726, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:35:23'),
(727, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:36:45'),
(728, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:37:12'),
(729, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:37:35'),
(730, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:37:57'),
(731, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:43:15'),
(732, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:43:23'),
(733, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:43:53'),
(734, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:43:54'),
(735, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:44:39'),
(736, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:45:17'),
(737, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:45:33'),
(738, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:45:57'),
(739, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:46:31'),
(740, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:47:12'),
(741, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:47:40'),
(742, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:49:04'),
(743, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:49:28'),
(744, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:49:34'),
(745, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:50:25'),
(746, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:50:56'),
(747, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:51:24'),
(748, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:51:38'),
(749, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:51:49'),
(750, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:52:04'),
(751, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:52:25'),
(752, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:52:45'),
(753, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:53:15'),
(754, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:53:33'),
(755, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:54:10'),
(756, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:54:40'),
(757, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:54:59'),
(758, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:55:57'),
(759, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0204199306493975\", \"type\": \"poverty\"}', '2026-05-20 21:56:09'),
(760, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"2658156081791587\", \"type\": \"poverty\"}', '2026-05-20 21:57:03');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `target_table`, `target_id`, `old_value`, `new_value`, `timestamp`) VALUES
(761, 2, 'REPORT_SERVICE', 'service_requests', '23', NULL, '{\"id\": 23, \"notes\": \"dasdad\", \"status\": \"PENDING\", \"created_at\": \"2026-05-20T15:00:27.000000Z\", \"updated_at\": \"2026-05-20T15:00:27.000000Z\", \"citizen_nik\": \"3721700552332154\", \"service_type\": \"KETERANGAN DOMISILI\", \"front_office_user_id\": 2}', '2026-05-20 22:00:27'),
(762, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"nxHhaVG531DH0y4K6OlUxeYK2QGfJ8plUeyiYDOaZa6F1dvF0VNkCxcvLNEp\"}', '{\"remember_token\": \"2m1gTU5O655zIA1qk2yYfBE2M1S8kwYBNVFgqhK8Vji6VPKtzoIoixudKpd7\"}', '2026-05-20 22:00:37'),
(763, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-20 22:00:37'),
(764, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-20 22:00:40'),
(765, 6, 'APPROVE_SERVICE', 'service_requests', '23', NULL, '{\"citizen_nik\": \"3721700552332154\", \"service_type\": \"KETERANGAN DOMISILI\"}', '2026-05-20 22:00:57'),
(766, NULL, 'LOGOUT', 'users', '6', NULL, NULL, '2026-05-20 22:01:02'),
(767, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-20 22:01:13'),
(768, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-20 22:02:09'),
(769, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-20 22:03:52'),
(770, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-20 22:04:18'),
(771, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-20 22:04:48'),
(772, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:06:55'),
(773, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:08:05'),
(774, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:08:25'),
(775, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:13:18'),
(776, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:13:24'),
(777, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:14:04'),
(778, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:14:28'),
(779, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:15:44'),
(780, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:17:08'),
(781, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"poverty\"}', '2026-05-20 22:18:51'),
(782, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-20 22:20:32'),
(783, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"move\"}', '2026-05-20 22:20:59'),
(784, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-20 22:22:31'),
(785, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"move\"}', '2026-05-20 22:23:56'),
(786, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0548397596571350\", \"type\": \"domicile\"}', '2026-05-20 22:24:03'),
(787, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"3721700552332154\", \"type\": \"domicile\"}', '2026-05-20 22:24:22'),
(788, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-21 23:03:16'),
(789, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"2m1gTU5O655zIA1qk2yYfBE2M1S8kwYBNVFgqhK8Vji6VPKtzoIoixudKpd7\"}', '{\"remember_token\": \"ZTx8XajANlRAFKZyFCbCPC0oCGeLnDzHUqmopBpLe863FtnnwjVALiCgfRIv\"}', '2026-05-21 23:06:10'),
(790, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-21 23:06:10'),
(791, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-21 23:06:16'),
(792, 2, 'REPORT_SERVICE', 'service_requests', '24', NULL, '{\"id\": 24, \"notes\": \"masyarakat miskin\", \"status\": \"PENDING\", \"created_at\": \"2026-05-21T16:07:04.000000Z\", \"updated_at\": \"2026-05-21T16:07:04.000000Z\", \"citizen_nik\": \"0003485827010970\", \"service_type\": \"KETERANGAN KEMISKINAN\", \"front_office_user_id\": 2}', '2026-05-21 23:07:04'),
(793, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"ZTx8XajANlRAFKZyFCbCPC0oCGeLnDzHUqmopBpLe863FtnnwjVALiCgfRIv\"}', '{\"remember_token\": \"N04WXiPBLn1rY3ARzRA1bQ6hxaoEUFGoIxnuH1a16oy1UzAFzAYQDQTByr0t\"}', '2026-05-21 23:07:09'),
(794, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-21 23:07:09'),
(795, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-21 23:07:15'),
(796, 6, 'APPROVE_SERVICE', 'service_requests', '24', NULL, '{\"citizen_nik\": \"0003485827010970\", \"service_type\": \"KETERANGAN KEMISKINAN\"}', '2026-05-21 23:07:34'),
(797, NULL, 'LOGOUT', 'users', '6', NULL, NULL, '2026-05-21 23:07:42'),
(798, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-21 23:07:48'),
(799, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"N04WXiPBLn1rY3ARzRA1bQ6hxaoEUFGoIxnuH1a16oy1UzAFzAYQDQTByr0t\"}', '{\"remember_token\": \"aijZkdnvz02dqov9ru99K2p8uPSNlBl059IR90zBQqsT4uW50CTltvbNCKTB\"}', '2026-05-21 23:08:17'),
(800, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-21 23:08:17'),
(801, 6, 'LOGIN', 'users', '6', NULL, NULL, '2026-05-21 23:08:21'),
(802, NULL, 'LOGOUT', 'users', '6', NULL, NULL, '2026-05-21 23:08:27'),
(803, 2, 'LOGIN', 'users', '2', NULL, NULL, '2026-05-21 23:08:30'),
(804, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\", \"type\": \"poverty\"}', '2026-05-21 23:08:40'),
(805, 2, 'PRINT_PROOF', 'citizens', NULL, NULL, '{\"nik\": \"0003485827010970\", \"type\": \"poverty\"}', '2026-05-21 23:09:53'),
(806, 2, 'UPDATE_USER', 'users', '2', '{\"remember_token\": \"aijZkdnvz02dqov9ru99K2p8uPSNlBl059IR90zBQqsT4uW50CTltvbNCKTB\"}', '{\"remember_token\": \"Yy64ndslkhBDWcVVGyCstd8vQIxuRkyti94AS7Zt7hoS91QtFeFCufmVJfrW\"}', '2026-05-21 23:10:48'),
(807, NULL, 'LOGOUT', 'users', '2', NULL, NULL, '2026-05-21 23:10:48'),
(808, 1, 'LOGIN', 'users', '1', NULL, NULL, '2026-05-21 23:10:51'),
(809, 1, 'UPDATE_USER', 'users', '1', '{\"remember_token\": \"Boj29DgUnyc1ysPrzpTIyz8xjYRhKGb4Y7Ft42bA2Dh7Iudwd8dMqOFeDKLj\"}', '{\"remember_token\": \"0U4ZteeAlhsw5VSs3XmBktZcSlun8oZdHQkFai91CqvUIiXaWHrgH6lxDGHW\"}', '2026-05-21 23:21:44'),
(810, NULL, 'LOGOUT', 'users', '1', NULL, NULL, '2026-05-21 23:21:44'),
(811, 4, 'LOGIN', 'users', '4', NULL, NULL, '2026-05-21 23:21:49'),
(812, 4, 'UPDATE_USER', 'users', '4', '{\"remember_token\": \"tsVdbQfch8\"}', '{\"remember_token\": \"YHF8gRPVsz6ckjF7oBMdOp49nZODTDA9IwhDusw4KhqRfwjTHhfwjB7n1sc0\"}', '2026-05-21 23:21:59'),
(813, NULL, 'LOGOUT', 'users', '4', NULL, NULL, '2026-05-21 23:21:59'),
(814, 4, 'LOGIN', 'users', '4', NULL, NULL, '2026-05-21 23:32:36');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('sistem-verifikasi-pelayanan-dokumen-cache-citizen_services_0003485827010970', 'a:10:{s:7:\"citizen\";a:15:{s:3:\"nik\";s:16:\"0003485827010970\";s:17:\"household_card_id\";s:16:\"1233791839629110\";s:12:\"nama_lengkap\";s:14:\"Tina Mills DDS\";s:9:\"tgl_lahir\";s:27:\"2003-12-13T17:00:00.000000Z\";s:11:\"alamat_desa\";s:33:\"Kp. Corkery No. 2348, RT 01/RW 03\";s:6:\"kontak\";s:12:\"087734811642\";s:7:\"desa_id\";i:3;s:10:\"created_at\";s:27:\"2026-05-03T22:32:23.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-03T22:32:23.000000Z\";s:7:\"village\";a:6:{s:2:\"id\";i:3;s:11:\"district_id\";i:2;s:4:\"name\";s:12:\" Bojong Gede\";s:4:\"code\";s:10:\"3201020001\";s:10:\"created_at\";s:27:\"2026-05-03T22:32:21.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-03T22:32:21.000000Z\";}s:15:\"poverty_records\";a:1:{i:0;a:12:{s:2:\"id\";i:35;s:11:\"citizen_nik\";s:16:\"0003485827010970\";s:6:\"status\";s:6:\"ACTIVE\";s:13:\"letter_number\";s:3:\"123\";s:15:\"signed_pdf_path\";s:55:\"public/poverty_proofs/1/0003485827010970_1778081733.pdf\";s:12:\"income_range\";s:19:\"DI BAWAH RP 500.000\";s:10:\"valid_from\";s:27:\"2026-05-20T17:00:00.000000Z\";s:11:\"valid_until\";s:27:\"2026-11-20T17:00:00.000000Z\";s:11:\"verified_by\";i:6;s:6:\"source\";s:20:\"VILLAGE_VERIFICATION\";s:10:\"created_at\";s:27:\"2026-05-03T22:32:23.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-21T16:07:34.000000Z\";}}s:16:\"domicile_records\";a:0:{}s:12:\"move_records\";a:0:{}s:13:\"death_records\";a:0:{}s:15:\"arrival_records\";a:0:{}}s:14:\"poverty_record\";a:12:{s:2:\"id\";i:35;s:11:\"citizen_nik\";s:16:\"0003485827010970\";s:6:\"status\";s:6:\"ACTIVE\";s:13:\"letter_number\";s:3:\"123\";s:15:\"signed_pdf_path\";s:55:\"public/poverty_proofs/1/0003485827010970_1778081733.pdf\";s:12:\"income_range\";s:19:\"DI BAWAH RP 500.000\";s:10:\"valid_from\";s:27:\"2026-05-20T17:00:00.000000Z\";s:11:\"valid_until\";s:27:\"2026-11-20T17:00:00.000000Z\";s:11:\"verified_by\";i:6;s:6:\"source\";s:20:\"VILLAGE_VERIFICATION\";s:10:\"created_at\";s:27:\"2026-05-03T22:32:23.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-21T16:07:34.000000Z\";}s:15:\"domicile_record\";N;s:11:\"move_record\";N;s:12:\"death_record\";N;s:14:\"arrival_record\";N;s:18:\"is_poverty_expired\";b:0;s:19:\"is_domicile_expired\";b:0;s:15:\"is_move_expired\";b:0;s:16:\"is_death_expired\";b:0;}', 1779466118),
('sistem-verifikasi-pelayanan-dokumen-cache-citizen_services_0548397596571350', 'a:10:{s:7:\"citizen\";a:15:{s:3:\"nik\";s:16:\"0548397596571350\";s:17:\"household_card_id\";s:16:\"4118538042647206\";s:12:\"nama_lengkap\";s:21:\"Prof. Juvenal Smitham\";s:9:\"tgl_lahir\";s:27:\"1985-03-04T17:00:00.000000Z\";s:11:\"alamat_desa\";s:42:\"Jl. Schmeler Views No. 8166, RT 072/RW 044\";s:6:\"kontak\";s:12:\"083113437500\";s:7:\"desa_id\";i:3;s:10:\"created_at\";s:27:\"2026-05-04T00:36:39.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-04T00:36:39.000000Z\";s:7:\"village\";a:6:{s:2:\"id\";i:3;s:11:\"district_id\";i:2;s:4:\"name\";s:12:\" Bojong Gede\";s:4:\"code\";s:10:\"3201020001\";s:10:\"created_at\";s:27:\"2026-05-03T22:32:21.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-03T22:32:21.000000Z\";}s:15:\"poverty_records\";a:1:{i:0;a:12:{s:2:\"id\";i:81;s:11:\"citizen_nik\";s:16:\"0548397596571350\";s:6:\"status\";s:6:\"ACTIVE\";s:13:\"letter_number\";N;s:15:\"signed_pdf_path\";s:55:\"public/poverty_proofs/3/0548397596571350_1778472876.pdf\";s:12:\"income_range\";s:19:\"DI BAWAH RP 500.000\";s:10:\"valid_from\";s:27:\"2026-05-10T17:00:00.000000Z\";s:11:\"valid_until\";s:27:\"2026-11-10T17:00:00.000000Z\";s:11:\"verified_by\";i:6;s:6:\"source\";s:20:\"VILLAGE_VERIFICATION\";s:10:\"created_at\";s:27:\"2026-05-08T02:17:22.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-10T22:45:17.000000Z\";}}s:16:\"domicile_records\";a:1:{i:0;a:12:{s:2:\"id\";i:2;s:11:\"citizen_nik\";s:16:\"0548397596571350\";s:6:\"status\";s:6:\"ACTIVE\";s:13:\"letter_number\";N;s:15:\"signed_pdf_path\";s:56:\"public/domicile_proofs/3/0548397596571350_1779289491.pdf\";s:7:\"purpose\";s:13:\"keperluan PNS\";s:10:\"valid_from\";s:27:\"2026-05-08T17:00:00.000000Z\";s:11:\"valid_until\";s:27:\"2026-08-30T17:00:00.000000Z\";s:11:\"verified_by\";i:6;s:6:\"source\";s:20:\"VILLAGE_VERIFICATION\";s:10:\"created_at\";s:27:\"2026-05-09T00:11:19.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-20T15:04:51.000000Z\";}}s:12:\"move_records\";a:1:{i:0;a:12:{s:2:\"id\";i:1;s:11:\"citizen_nik\";s:16:\"0548397596571350\";s:6:\"status\";s:6:\"ACTIVE\";s:13:\"letter_number\";N;s:19:\"destination_address\";s:7:\"dasdads\";s:6:\"reason\";s:4:\"aaaa\";s:15:\"signed_pdf_path\";s:52:\"public/move_proofs/3/0548397596571350_1778475090.pdf\";s:11:\"valid_until\";s:27:\"2026-06-10T17:00:00.000000Z\";s:11:\"verified_by\";i:6;s:9:\"issued_at\";s:27:\"2026-05-10T21:50:36.000000Z\";s:10:\"created_at\";s:27:\"2026-05-10T21:00:41.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-10T22:45:17.000000Z\";}}s:13:\"death_records\";a:0:{}s:15:\"arrival_records\";a:0:{}}s:14:\"poverty_record\";a:12:{s:2:\"id\";i:81;s:11:\"citizen_nik\";s:16:\"0548397596571350\";s:6:\"status\";s:6:\"ACTIVE\";s:13:\"letter_number\";N;s:15:\"signed_pdf_path\";s:55:\"public/poverty_proofs/3/0548397596571350_1778472876.pdf\";s:12:\"income_range\";s:19:\"DI BAWAH RP 500.000\";s:10:\"valid_from\";s:27:\"2026-05-10T17:00:00.000000Z\";s:11:\"valid_until\";s:27:\"2026-11-10T17:00:00.000000Z\";s:11:\"verified_by\";i:6;s:6:\"source\";s:20:\"VILLAGE_VERIFICATION\";s:10:\"created_at\";s:27:\"2026-05-08T02:17:22.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-10T22:45:17.000000Z\";}s:15:\"domicile_record\";a:12:{s:2:\"id\";i:2;s:11:\"citizen_nik\";s:16:\"0548397596571350\";s:6:\"status\";s:6:\"ACTIVE\";s:13:\"letter_number\";N;s:15:\"signed_pdf_path\";s:56:\"public/domicile_proofs/3/0548397596571350_1779289491.pdf\";s:7:\"purpose\";s:13:\"keperluan PNS\";s:10:\"valid_from\";s:27:\"2026-05-08T17:00:00.000000Z\";s:11:\"valid_until\";s:27:\"2026-08-30T17:00:00.000000Z\";s:11:\"verified_by\";i:6;s:6:\"source\";s:20:\"VILLAGE_VERIFICATION\";s:10:\"created_at\";s:27:\"2026-05-09T00:11:19.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-20T15:04:51.000000Z\";}s:11:\"move_record\";a:12:{s:2:\"id\";i:1;s:11:\"citizen_nik\";s:16:\"0548397596571350\";s:6:\"status\";s:6:\"ACTIVE\";s:13:\"letter_number\";N;s:19:\"destination_address\";s:7:\"dasdads\";s:6:\"reason\";s:4:\"aaaa\";s:15:\"signed_pdf_path\";s:52:\"public/move_proofs/3/0548397596571350_1778475090.pdf\";s:11:\"valid_until\";s:27:\"2026-06-10T17:00:00.000000Z\";s:11:\"verified_by\";i:6;s:9:\"issued_at\";s:27:\"2026-05-10T21:50:36.000000Z\";s:10:\"created_at\";s:27:\"2026-05-10T21:00:41.000000Z\";s:10:\"updated_at\";s:27:\"2026-05-10T22:45:17.000000Z\";}s:12:\"death_record\";N;s:14:\"arrival_record\";N;s:18:\"is_poverty_expired\";b:0;s:19:\"is_domicile_expired\";b:0;s:15:\"is_move_expired\";b:0;s:16:\"is_death_expired\";b:0;}', 1779376012);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `citizens`
--

CREATE TABLE `citizens` (
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `household_card_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat_desa` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `citizens`
--

INSERT INTO `citizens` (`nik`, `household_card_id`, `nama_lengkap`, `tgl_lahir`, `alamat_desa`, `kontak`, `desa_id`, `created_at`, `updated_at`) VALUES
('0003485827010970', '1233791839629110', 'Tina Mills DDS', '2003-12-14', 'eyJpdiI6IlRBWHc0eWFYZW9oWGMzUnlsR1Jkd2c9PSIsInZhbHVlIjoiUll5QTF6NmtNU3NmUXFodGY2WnZ4clRQbjc1KzlZOHZRNTUvQUdxanc5blMwakc2OVJ6RXhncGswYTBiTGZzVCIsIm1hYyI6ImRiMjNjZjAxZmZmYzRlZTQ3MDY1MGE0OGMwZTQ0Y2ExNjQzYmIzNjg3OGE2ZjlhMjFkMGY1ZDQ1Mjk5ZmNiMDciLCJ0YWciOiIifQ==', '087734811642', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('0010303206953957', '6969058683899311', 'Guiseppe Kuphal', '1980-08-04', 'eyJpdiI6Ii9pbWFmZjJVMXJRZ2xPc1A5amtNb1E9PSIsInZhbHVlIjoidURpYzV1N1RPVTlGcmF3RjhiK1k5MFVpaVI4dkNydGo5VkdnR01qWUdhcTZTZHo0SjdZMDVKV21OMXoxNWgwTCIsIm1hYyI6ImVlZjZkMGRhMTA4MGY0YzY1ZDNlOTRlNWNlYzk3YTgzZGRkYTljYzgzMGQzNWNjMGIzNDhmZDM1YTMyZDVjYzkiLCJ0YWciOiIifQ==', '089161802726', 1, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('0064622836742991', '8252925377204770', 'Erling Howe', '2012-01-19', 'eyJpdiI6ImpUWFRFcjNuaUp4aGNybEdEbHlLc2c9PSIsInZhbHVlIjoicEc2RjBiaXRaTkRidll1aE1YWndPMlp4d3dLUmgwOUZOcVQrTDF3dURUdEVyTFZYb0NoYkNvSHZ4cC83N0p0LyIsIm1hYyI6IjY1YjY0NjgyMmFhMDkwZDEwM2VhMzA1NTQ1ZjNjNGRhNGIwNjhmYjE0YTZmNzk2ODVhNDc1OTczOWJiNDllOGYiLCJ0YWciOiIifQ==', '080373697426', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('0066935789043262', '5001207486395518', 'Dr. Jo Lemke', '1979-07-13', 'eyJpdiI6Ik1vVmZYK1Q3QzM3YkZWOUNvbjc4NUE9PSIsInZhbHVlIjoiMnBQaEQwZTdnRzZqYlRtZ3hBWDBOVGlHbUtyMUYvbW50SXowWFN5eGkxSGhDTWUvQUhCakZrZmZuSjk4SXpNOSIsIm1hYyI6IjhlNmIzYzczMTc1YzM0OWE5NTdhODM3ODI0ZDdhZWMzOGI1YjZiYTFjZGQ3ODZkZDMzNWEyZmUyNWEzMmJiZWIiLCJ0YWciOiIifQ==', '081764578565', 2, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('0125368268471489', NULL, 'Miss Kaitlin Muller', '2024-11-22', 'eyJpdiI6IitWKzlVanNTbjNmV3BnMnZmUXkvUWc9PSIsInZhbHVlIjoia2pWQ2xCUUJWN2p5K3MwdjFBR2xMMUFUYXpxWFZ2TGhJOVl5dCt6cWtVZDMyUVU2QTBTc3VqQ3NmL1ArczA0YiIsIm1hYyI6IjI0YmM4MTFkNjY0OTU5NmI1YzNiZGVjMTJkNGFmYzM3ZDQ1NGI2OTg5MzE2MDY2Y2ZmZmQxODkxZjA4ODg1YWIiLCJ0YWciOiIifQ==', '089719507285', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('0140915127418975', NULL, 'Amelie McDermott', '1980-07-06', 'eyJpdiI6IlRiSmVObmZnQkJBNkJqRXphY0tHMUE9PSIsInZhbHVlIjoia2FFUGk5ZU9ZTHIxV1gydEZxdzdqVzVTYzZicHl6WFVCTmJ6MGllS1Z6NC8wY2g2ZGJ1TElmaFBwQ1o2aldUNiIsIm1hYyI6ImRlOWFmZWQ0YTZkYjI5NDUwMTZlYmE3MDJiMWRjYTNhYmEyNTJlMGJjMjAzYjg5ODE3Y2M3YmZjMTQxZDVlNWUiLCJ0YWciOiIifQ==', '083134608988', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('0204199306493975', '1233791839629110', 'Green Stoltenberg', '1997-10-07', 'eyJpdiI6IjFEZUFRNkRsdThMNlM4NjRkblRXNmc9PSIsInZhbHVlIjoiQU5IRmwrVEtIMlpOZlVCaUtXNm91amhoN25HeXliMVMxMkpzeUlza0ZIdm1PODNobUtkYm9TZU1mdHBxVkRzdyIsIm1hYyI6ImM5Mjc3MjgzNWFhNmNjNjc0ODdlMTllM2JhZTZkMjE5N2VmM2E3M2FkYmMwYzRiZGFhZWE4MmYyMjE4MzFhYzYiLCJ0YWciOiIifQ==', '080739629975', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:40'),
('0221137620576019', NULL, 'Abigail Jaskolski', '2000-03-03', 'eyJpdiI6IlR4WmdMbWZaOUMzVzdiMk5YMGFpdkE9PSIsInZhbHVlIjoiWEcwczhlTHl3V2JpRGg5eG9FY3FXNEtPM2RQd1piVHExUDY5OWxCM1hhOVFtbEltVUpoV2JLODM3ODlpWUdVVyIsIm1hYyI6IjJhYTM2ZTMzNTE0MmY3NGVmOGQyMTNhNzcyYTI3YTA2ZWQ2ZWEyNzY1YzU5M2ZhZjc4ZTVkZjNmZTFmMDAxNjciLCJ0YWciOiIifQ==', '083989964029', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('0237772649659152', '1660961957535760', 'Adriel Heller', '2003-08-17', 'eyJpdiI6Ijd3d0liN0djdkRRY3g1ZXFBKzdIQ3c9PSIsInZhbHVlIjoiTHp6NEdoeTJkU2lmOVhlejdtS2FHTVZJT3d3TEU0QlRHaXVRdTJvaDJQYnN4ckxMUHEweFE5Z1cxUlF6eU1qWSIsIm1hYyI6IjExOTNlNzFkY2U1OTM2ODU1M2EzODRlOGZmZDhlZTYwMWZjOTBkMzFlMTcxNDM5YWY0NTczNTNhYTI4OGU5YmQiLCJ0YWciOiIifQ==', '084727385306', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('0299621747194687', '2352183283459212', 'Gaston Schroeder', '1983-12-23', 'eyJpdiI6Ii9oZngwYVFJYVRnQUViR3R5aFFreVE9PSIsInZhbHVlIjoiSWV3U0Y1YS84bmFTN2RabDUrWVRzaVhJQVBYb1BRMXcwMUw3bnhxT1JNWFFkZnNHdURZNCtOSnZmS3p1dmdGdiIsIm1hYyI6ImM4ZTllODg5YmY3MTlkMTM0NzIwZTNiNTg2YzJjNzY1MjkwZDNkOTRiYjNlYzhiZTQzODNjYzAwZTkwZWI3ZWIiLCJ0YWciOiIifQ==', '089393846198', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('0548397596571350', '4118538042647206', 'Prof. Juvenal Smitham', '1985-03-05', 'eyJpdiI6IkY3aHVnZHBKMXJqVFdXc1RXblI5K3c9PSIsInZhbHVlIjoibVI3RFQ1MEJETmpUdU5rdkQ2cG9lSTI1bk9FQVRGNTNCdWlvOTAxb280eUlDS0xHSGw5eHVFZFZ5amVvbGpuMiIsIm1hYyI6ImY4MDBhODViZGEzYWQ5ZTYwODMxYmNiOTQ0NTEwYzU5MmJmMWQ1MTA4MTZiYjU4MDU2YTY0MDJjYmQ4ZWIyYmEiLCJ0YWciOiIifQ==', '083113437500', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('0594521673981587', NULL, 'Deion Howell', '2024-07-17', 'eyJpdiI6Ik9KTmtWQVdXeEhSaEZJYmFaa2tzd3c9PSIsInZhbHVlIjoiRWd0WFpnblRUNS9UdjVlbmJjWE5GMU1Nci9XUzhTdWpjckR2NVU5WmVZQjhCQVMxZGFJS0VtSllpVFFQY0JrTyIsIm1hYyI6ImRlOGM1YzFkYzZhOTVhNGM5YjVlNDdmMDA3MDE2YzJkMDFhMWRlNzVjZTYyYzViYjEwMmJkY2RlNWZmZDljMzgiLCJ0YWciOiIifQ==', '086881634513', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('0597787384237356', NULL, 'Jammie Bradtke', '1971-11-15', 'eyJpdiI6Im9pTXA0WHloekxpTVVTVjBtNzlWbUE9PSIsInZhbHVlIjoiK1NoSFJyc0hSS1dsRjVjWERXRlZLMXhWVkJERytKUXlmWGJueFBaaUNnd01BeVdhd2ZzOWJMS201cjNUb1VobSIsIm1hYyI6IjIwZmFjMmMwOGIzYWU4NGI0Y2U0MzVhM2ZkMWM0ZWUyYmU5NWQ4NTVjYmEyNjM4Y2ZhNzhhOWUwN2VhODkxOWIiLCJ0YWciOiIifQ==', '084732745668', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('0911859211473940', '3421205268285809', 'Markus Spinka', '2008-07-24', 'eyJpdiI6IkxnZlpWNzJPOTc2U21KRXU0QjFLM1E9PSIsInZhbHVlIjoiOEVPV2pQa1BOd3JvZHRabURZbUlQc0dtYXdCY2p4WFdpcHp6TjJUeUhIV0tiTEhYYjFKK01DeEZyR1VMRWZodyIsIm1hYyI6ImJhNTkwNWUzZmI3NzFmZDU1YTQ5YWZiYTExN2VkZjJiMjhlZTQ2OGU1NDI0Njk5YTI5NmYxNGQzZGQwYjg0MWYiLCJ0YWciOiIifQ==', '089269536102', 2, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('1231231231231231', NULL, 'aaaa', '2013-01-11', 'eyJpdiI6IkN5d0dvdnpFc0M5SU9HdUpaV0Y0alE9PSIsInZhbHVlIjoiNjZrUEhUeTNwT0NxZW96eFRXWTMzZz09IiwibWFjIjoiZmRlNjMyOWFjYWU4NWI3NGNhMTI1YjRhMGEwMmFlYTJkNDRkODUxNTRjZjI1OThmNjE3ODcyYzczZjI3YmZhZSIsInRhZyI6IiJ9', '1231231232', 3, '2026-05-11 15:05:26', '2026-05-11 15:05:26'),
('1231231231231232', NULL, 'bbbbb', '1990-05-11', 'eyJpdiI6IkVDT1M2K1NBdmg4eWR6TDNyZDNJVFE9PSIsInZhbHVlIjoieHI2U1h2Q2xXb05ZcjdDa2UyNzU2UT09IiwibWFjIjoiYmYzZTE1NjVhNWE3YzEwNWEyNGY5Y2FmMGU1ZWE4MzE5YzNiZjJjM2E4MjFkMjE4MWMzYzdhYTYxNzAwMzZhZiIsInRhZyI6IiJ9', '123', 3, '2026-05-11 15:12:19', '2026-05-11 15:12:19'),
('1234567890123456', NULL, 'Budi Sudarsono', '1985-05-20', 'eyJpdiI6Ik0zTCtzM21CWUVkcmpFQXhGUjNIS3c9PSIsInZhbHVlIjoiaGdKRmJ4N0FFUDlxbStRZUNLbnR1ZFVyMW5BSUpqYmRDRkxXeEMyWWNxVE1NWVl6cTFES2pxME5aUkVUTW1BdyIsIm1hYyI6ImQ5ZjhiOTdjOGM2ZmZmZjM2N2JjNTRiYmZhZjdmYTNlYWU5YTg1MDYxNmE2MGMyOTNmMTY2YjJlOTlkNGM4ZGQiLCJ0YWciOiIifQ==', '081234567890', 1, '2026-05-04 05:32:22', '2026-05-04 05:32:22'),
('1711408092293945', NULL, 'Reagan Emmerich', '2009-03-23', 'eyJpdiI6IlUrRStTL0FzR1djVyt1akhlRTBIZXc9PSIsInZhbHVlIjoiSVdwN1V2azhyam40R2V1UHU5SzNVYnY0YkUwcGNUVURSbWJBYUFsb0VNOD0iLCJtYWMiOiJlMTc0MWY4YzVjYTVhMGQ0MTQ4ZmNiMTFlMDY1MTI0ZTRjODExNjk3ZDk3NzExNzJjMDNjZWExYTM5OWNhYTI5IiwidGFnIjoiIn0=', '084719695093', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('1716095046254527', NULL, 'Cristina Boyer Jr.', '2005-01-21', 'eyJpdiI6IlhmVzNFMmsxenhCVEdLaWxKc3dUVUE9PSIsInZhbHVlIjoiQjRHTjhNMWd0QnJpRXh2bFR6RVRueVRvaU5MNERUdkMvUldqTWYzME5URDBST3FiMTNrb3Z6c2ZnKzZWMjdRRCIsIm1hYyI6IjFiZjE0ZDcxYjQwNmM2Y2Q1MGI4OTkxN2NhOTVhNDY4MTA0YTU4ZGE3NDY5NWU5MDI4NzJmZTg3YTA4MDJhZjUiLCJ0YWciOiIifQ==', '086871881786', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('1765211616403087', NULL, 'Zita Spinka', '2009-02-02', 'eyJpdiI6Iks0RDZvSDhJQlhKR21vdVFsbG1KWFE9PSIsInZhbHVlIjoialFURDNtQW1QdmsrUnhNNWJRNWVpdks0RDkrTHlKTk1lRjZVZDgrdTRNNG5YVWpTRGN4dTc5UUtnRDJFMnEzUCIsIm1hYyI6IjU3MjM5NjM0ODBlYTdlMWY2MWRhN2I2OTdjNGFhMTRlOTY1N2U5ZDcyOTUxZjQyNDE4ZTg1NGUyYWNiOWRlZTMiLCJ0YWciOiIifQ==', '083628880620', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('1926958198088365', '4118538042647206', 'Dr. Hailey Nicolas DVM', '2005-10-01', 'eyJpdiI6IlZMc0FyalorbEJ3VnJUU2ZGM3J2aHc9PSIsInZhbHVlIjoiU2J2TjVDV0NybjBmd1diNmg2WWtuUFpFYnZDejdVbTJGY25ZWVJrRHVDUkh1RVZBKzIxYXhESTZDUnovb3JxLyIsIm1hYyI6ImNiNTgzMjI1ZmQ4ZDQzMzg3ZDhjODI0ZDU2NTNlNzEyNWRkYzIzZGFjN2U4NWY1YmE2MjViNzllOWI0YjdkMjEiLCJ0YWciOiIifQ==', '088714477879', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('2065707815076781', NULL, 'Alison Bruen', '1987-07-22', 'eyJpdiI6InpNZFR5aHlzZWh3emQvaThkRG9wZEE9PSIsInZhbHVlIjoiR0RsdXZXOUkwSU0yenRGQ2txT2ZqVlZYK1M5bXhKejNtNTBUMXRKQUxvdVFVdmUwVW0rL3JvWDIrOVZvekMyYyIsIm1hYyI6IjZhNTU3MGVhNTg0MjgwNjdhY2U0YzUwYzVkOGFhNDZiMzU1YmVhMGYxMzY5MTc5ZWU5M2FjNzIwNzE3Mzg2NzMiLCJ0YWciOiIifQ==', '080853901681', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('2425119000510520', NULL, 'Ernie Brakus', '1970-06-16', 'eyJpdiI6IjR1bmphdU02RHRLUEFQQmZXc2w5V2c9PSIsInZhbHVlIjoia1c5cUgvZCtld2lXZE9NeS9YWWc0bEZWZlcwb2EzMVZqNXhXV0p3cmdNWUNKUXp5dzZBS0Q0M3RXb3lINDM1WSIsIm1hYyI6IjRkYTNmZjllOWExMjY0NWJlNWIxZDkzYWJkZTM5Mzk1MGU4NDA1NTQ4ZmRlNjJhZDEzYjBlZWZlMTE1N2VjZWMiLCJ0YWciOiIifQ==', '081463566035', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('2563815713882304', '5612773451825704', 'Jamil Nikolaus', '2014-08-12', 'eyJpdiI6IjZ6RzdGWFZhbitCS21YT2JwVWpKNXc9PSIsInZhbHVlIjoid3RZZW94dDFWT3ZjZlRsV2h6YWg4QmJhQTJEL3ZXQUNpYm1UM2lpMWcyMEFqVjBzNnFRRFVFeWp4WGhhSGF4YyIsIm1hYyI6ImYxY2U4MGY4ZmEwMDFkOTdlMmFhNTU2NGQwNWVjZDZiNGU3Mzk5NTc2ZGZjZDMzYWMyMzVjYzU3ZGMyOTgxOGUiLCJ0YWciOiIifQ==', '080942976444', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('2658156081791587', NULL, 'Alexandrea Gerlach', '2003-08-05', 'eyJpdiI6InBKMjQvclFrcS93Ymh5RGg3SDI3MVE9PSIsInZhbHVlIjoiOGdyS01pOWZnMEROelJ4enBWTmR5eE5IaTkvUDRvbkVETlF6d1ExMnA0SlNLZlBQTjlQTE80eTV4RmRHSnRJNSIsIm1hYyI6IjI3YWYxMjc4NDEwNTk4YzYyYWEzZjIzODk2OTMyZmUyNDc2NGIxNGFlZjdmMTVlZDNhYmM2MWY2NWNlODA3MjYiLCJ0YWciOiIifQ==', '087069691413', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('2684091943511482', '5001207486395518', 'Sharon Thiel V', '1970-08-19', 'eyJpdiI6InNCZEdpdTNMZis5cStaM0lrb3lvUmc9PSIsInZhbHVlIjoiRUZMK1dIcVo0NHl0cFMrNjJpSXFMbS9weUFTWXlYU0RkRHpHeDRxa3cyOEVRbDB2OU1MaUZlRW1vc0piSnlvaCIsIm1hYyI6ImIyNmYyZGMwNzJhMGJlOWJiYjk4NjM5NDdmYjY5ZmUzZTJlZDYyNDFhNGY2MWJkNjZhOTkxMzEwYWFmNGM4ZTUiLCJ0YWciOiIifQ==', '084093211177', 2, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('2764672858582329', '3790303525496946', 'Prof. Angus Keeling V', '1998-09-22', 'eyJpdiI6IkdjaDdzVHJFNUVlN3VTbXpIVzR3S3c9PSIsInZhbHVlIjoicVM3aG45aWJjMmZKa1JxQTR1U0xqNWRjaTR4Ym5DSkdxbzdzNTArdjh0Q2V5TGFGRUNJcHpmbkZZcGt3NHFYcSIsIm1hYyI6IjQ3ZTNiODE1NmQyMTgzYWY3NDZjNzc1ZTE2YTk1YWVhMTg5NWM0YjI1ZDYyYzJiNzZiZWVmNjEzMGNmODA0NmQiLCJ0YWciOiIifQ==', '089153459751', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('2801754690781515', '1233791839629110', 'Vivien Bauch', '2003-05-13', 'eyJpdiI6IlVFekRYQTliVTRINTBaSnlkWnpVbGc9PSIsInZhbHVlIjoiY1RQNUR5WUZDYUF2VmtXY1F3SWV1TytsazBrbEdEM0N2aEtJRFBldWVVRm5yUHFiQkt1TnlNdjJkaVJLaXgxaCIsIm1hYyI6Ijk5MDQyNmEzNmI2OThhZGQxNjVjODIyZmVhZTQ0YWE0NzU5NjdhMjNkMDRjMDQ3MjgxZmQ5MmM3Zjg1ZmZhMDUiLCJ0YWciOiIifQ==', '084523198314', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('2811476912538260', NULL, 'Dr. Hardy Crist', '1997-05-19', 'eyJpdiI6IlRlRkw1UGJiQ1dhMkdHaWhrZWlrRnc9PSIsInZhbHVlIjoid0FPOUpiV1ZnaTlDWk5ISVk4RjR0aC9pRk5qODk0QlhQdU8wVTFMZHVjZz0iLCJtYWMiOiI0OTJjYzMwNTdjODhkNzI2NmU2MTdjMTQyN2I1YTBjZjU0ZjU5Y2JlZGJhY2ZmMzEyZTYwOTI3Y2UwYTEzODlkIiwidGFnIjoiIn0=', '089977301230', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('2826556909042545', NULL, 'Jaqueline Runte Sr.', '1971-05-28', 'eyJpdiI6Ik13Zys4cFJ0enoyQnVlN1ZsaDJLb1E9PSIsInZhbHVlIjoicXRnT1dDWEN1eWd0eUxtUmttbHBqUEFXR2F0Uy9aeVc0MW9iZHA5QndmZFhNbmVEVy9GMGhmNEwvd3pqUEZySiIsIm1hYyI6IjgzMTgzNzBiZjcyZmVlNTc1ZWZmMmY0MDUzZWRlZjRiMWNmZTA2NzkwYWRlNjgwMTg5NzVlNTZkYzJhMzlmNzMiLCJ0YWciOiIifQ==', '083718460648', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('2945329526956887', NULL, 'Melissa Leannon', '1994-07-30', 'eyJpdiI6IjVCYm9vMkhvUTVLOGV3NEZMWGhlRGc9PSIsInZhbHVlIjoicDVQVjdpdTJoZVVCZ2RzTjhyTkd3K3A1c0haeUVRSjN4cjh5dnpZUlNGSi83T0ZXaHVYTHR0MXNGRUJOMy9GQyIsIm1hYyI6ImNiOGU1ZjdmZjQyYTQ0MDE3YzhjMzBlMjBhNTNkMDY1NzEyMDQ2MTE5ZWE0YTViZjRiYTY4ZTIwOTcxYzlmYTkiLCJ0YWciOiIifQ==', '084210892296', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('3003768389119937', '1233791839629110', 'Mr. Ethan Will V', '1972-08-14', 'eyJpdiI6InJ3QU5DOGMrVEx4UmlTemdkbjJ5NXc9PSIsInZhbHVlIjoieHhEeHNnQlhTVGV0KzQyNUU0SEErbExhejVJTW1teUFWd3V5K2ZqWTQ5YmdEeXhNMWFjTURSVGx2c2JQcGpqeSIsIm1hYyI6IjdhOTE2Y2UwMWZjMDI0NDhlYzE1Y2Y0N2EyZTVhZjUwMzA5ZDNkMmM4NDJlNGQzNTdmMTZjY2VkYTRkZjQwNTQiLCJ0YWciOiIifQ==', '089664345571', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3023369112029953', '1233791839629110', 'Braxton Bayer', '2018-10-31', 'eyJpdiI6Ik1uOXY2aWI2MHhNZnduQ1hOYy9VZGc9PSIsInZhbHVlIjoiOWt1dG1lRnk5aDZMUXJZTUd3QkVES1o5ampqRVptVTBvZUI2Q3M1ZTFhST0iLCJtYWMiOiIwOTBlYTViYWUxNTdjZTlmZWQ2YWEwNTRkZDIwZmFhY2U5MTE3ZmZiNDg1MTY4ZGUyOTZkYmFkNmUyZTBjYWU4IiwidGFnIjoiIn0=', '087555855207', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('3156661593848831', NULL, 'Sonia Doyle', '2024-06-15', 'eyJpdiI6ImhiSlp1WmNOc3FuNkY3b2tNajljK3c9PSIsInZhbHVlIjoiNHV2LzVrUzcvVUFzbFFzUFFzOE9TcU5DdmhQdVNFa0tObWdsSC9GVDJ4dz0iLCJtYWMiOiJiN2JjMDc0ZjE1OGE3MWVjMTU5ZGZhZjM0MDIwZjQxZmQ2NDMzMTAyMjZkYTA2NDkxMjBkY2I3NTAwMjViNzYzIiwidGFnIjoiIn0=', '084548067976', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('3193862811099563', '8252925377204770', 'Annamae Ortiz', '1998-02-16', 'eyJpdiI6IkxNS1JpY2RiRVBaaXFQN1NJbVRyK3c9PSIsInZhbHVlIjoiVWs1emRxc1RpR3hFYlBhZ0o0eEtNeXk5VlhzbFJlZjdxWjU5aStZT0VkdFc2Um9rNWtmU1JLRDFXVlBxQW16eiIsIm1hYyI6IjEwMzgwMjdiY2E2NTljM2RlMGE1ZjIzZDZmMjY2ZTZiOTViMGQ2YjkxOGQyZjE0NzdiMjY0MTFkZjNhOTU1OWEiLCJ0YWciOiIifQ==', '081921615729', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('3249031442770406', NULL, 'Halie Ratke', '2014-02-23', 'eyJpdiI6IldMN1B5VWpDUmlIY2c3UzJENXpZSGc9PSIsInZhbHVlIjoiek5zOFhBeUFhalliYWJMOGdFTXRidU90emdHdmlqVGJtOGpyN1hsdCt2dEM2NFF4NytucDhZTGhXZ1JFNWd5YiIsIm1hYyI6ImU0MjYyYzgzZmE4NzQ0OTkyODAwYjQ1MDZkM2I5Yjg0MWYxNWE5MTMyNGQ4NGUzOWRiYjdmMGExNTZjNWQxYzQiLCJ0YWciOiIifQ==', '084077985159', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('3373377393202273', '8958937323630079', 'Leonard Lockman', '1978-08-13', 'eyJpdiI6IktORDFsYlo2V1lvNmhnSlQ3eldxb0E9PSIsInZhbHVlIjoiMjRLYkpKbWllTktPVFh1bU9UZ2pGOXY4aDVDUXhqN3BETFZzSzF2VmZsU0pPSDFtaW1yRzI2NlRzSTlZcFk0ZyIsIm1hYyI6ImNhNWI3NzI3N2Y0ZTAzOGM5ZjQzYmQxZDM2MjA2NTE3N2ZjZGQ1ZTY5YzYyNDFiZmJjN2U2NzZhZTNkNzRhNjkiLCJ0YWciOiIifQ==', '084996602617', 1, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('3377694025765557', '6988051632499646', 'Dr. Theo Schultz PhD', '1978-11-23', 'eyJpdiI6Im4rT0xKa01oQ000V2FqelIyODdKaHc9PSIsInZhbHVlIjoiVHR2Z3BWWUw4ZDlFcnVacU5kQkkvLzFIcmdKRkIwejdWQTgvajFCUmEzNlNBWUJ3ZEhlNWdaSTYzNVRCVDNvTSIsIm1hYyI6ImMwOGIyOTM5Y2E0ZWIwYjFiYTZmZmM3OWQ0ODE2MTFmMDdiNjU2ODJmNzNkMGRhMjlmOTA5NzUxMTJlZThjZjkiLCJ0YWciOiIifQ==', '087484658623', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3439000341160690', '7075753455854325', 'Emile Prosacco', '2007-01-05', 'eyJpdiI6IkpRUDdsL1FLRVljbTJybE9jRVRONXc9PSIsInZhbHVlIjoiRmcrelBFRTZMOVNjWE5XcmxQVnpSNzJuWitaaWVBTGZWVCtSUEYwcEdSaS9VbVBwc0o1c3ZXMWRRUWVUL0treiIsIm1hYyI6IjU3ZjUwNzhlMDkxYTcxMjA5N2NlNTc0YjNkMWZjZWE4ZmJhMDNlMzI5MGUyNWMwODYwOGM0OTY1N2FkMWI0NDUiLCJ0YWciOiIifQ==', '080963066025', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3444106539063721', '8252925377204770', 'Quinton Wehner', '1998-09-06', 'eyJpdiI6InEyb0UzdmQzS0dVMFNqM3EvY1dJWXc9PSIsInZhbHVlIjoiRm9PL080Q00vaDROTGhxUXhYaWNsSnh2MkhHTVpWWVZwQWVGTWE2WW1ML0lVMktqK0pQNytOeHR2c0NkbmlOUSIsIm1hYyI6ImQ5ZTQzMGU0MWRiMDFlOWFlM2Y4OWFlYTgyMTE2ZjJiY2I3ODQyOTA0ODA0MDVjMGYxYjdmZmVmMWMxNjZlODYiLCJ0YWciOiIifQ==', '085073814566', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('3463864787130881', '5813416891243847', 'Dion Hodkiewicz', '2017-01-18', 'eyJpdiI6InpyQ0thSkR3akJ2Z0VzWmFqOCtnY3c9PSIsInZhbHVlIjoiYjk1b1VMZlVEMkNzL3JnMVRTcCtDVE05V3VkREwrOElTZGptN01HREM4L0JjeUxJTGRmTUFWa01BQmNvUlZwWSIsIm1hYyI6ImJjOGMwNGM5ZWIzMGYzYTUyZjc3N2Q3M2Q0ZGRjYjkyY2Y3Y2JjZjFmYzBlZjc5ZWMzMTc5MTkyOTQ3ZDIyYzUiLCJ0YWciOiIifQ==', '085246331540', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3556305576873095', NULL, 'Jammie Ruecker', '2019-12-21', 'eyJpdiI6IkFiV2s0THA3TTliRmErM25iN1hmeGc9PSIsInZhbHVlIjoiZ21BMXVQZ3VUQ1B3Y3FEaWZROXloRzR4ekw4TGp0a0Q4NzZkMkZhNUgzY1NPb2pkVE5RYlAxelNMbFRiRFptZyIsIm1hYyI6Ijg2Zjg0NzhhNTA5NWY5NWYwMjQzYTM5OTZlMWYxOGY5YzhlZGZhYWI0ZGM5NmQ2ODIyN2ZmNGQ1OGYxMDllMjIiLCJ0YWciOiIifQ==', '087049824685', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('3572996557041966', '4978690671792204', 'Prof. Alexie Borer', '2019-10-09', 'eyJpdiI6IlRqQldDK2hGdUFPT2Q0K0t1QXBWU0E9PSIsInZhbHVlIjoiektTTmJrYmFDZHdOSkR0Znp5MmwwRXdOdWZjNTNRYVVEWEpoVXBFSW1NNXJEYnBLWU9LS2NNUHJFc0h2RlZJZSIsIm1hYyI6IjQ2MjE4OGVhMGM3N2NmZDRlMGI5NTUyZGY0YTkwODAyYjZmMTk2Mzk2ZGNiMzAxY2ZmNDZlNjA1MDk0YTgwZTkiLCJ0YWciOiIifQ==', '081506932420', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3596469487045524', '4878045916240772', 'Mr. Antwan Welch', '2004-05-27', 'eyJpdiI6IjJ3SXptbGtTbXlNejZLRjlmZWtKWHc9PSIsInZhbHVlIjoiemMrUWhGU2JEU2IybWNkSXBSQWswazZEWW1URzBhMFkvK1BmcFBuSWM1Z1c0MkhyTUczalN6U0pxQW1ZamNSMSIsIm1hYyI6IjBiOTRmMDU1MDI3OGFkYWZiZWJmMTVhMTBlMDM4OGRkNWE0YzM4MTQ3YzdhNjY5MWEyZTY5NmVlZjQyNWU2ZTAiLCJ0YWciOiIifQ==', '081122779069', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3685315395556932', '5813416891243847', 'Florence Wehner', '2002-05-05', 'eyJpdiI6IkxiWkRRTlV3SDFXbmtZNloyU0tQVmc9PSIsInZhbHVlIjoiekMyQkpBbmNoalJnQUxST2dyVWNYVWhzNkpIZFYxZFg2dUw2QnFwdm5LalJYKzIxaVJ3UDlIZVBsZEhZYk00byIsIm1hYyI6IjU0MzRhMTJjNTk4NDlhNmFhZDM0NzJiMmExMzkzZGZhYTIyZDczMjhhZjFiNjc2YjU4NGMyY2YwNzgyMTBiNTAiLCJ0YWciOiIifQ==', '087799287675', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3701484035927705', '3677277571821350', 'Norbert Zulauf', '1976-11-30', 'eyJpdiI6IlgyQ1RRbWxFNENDcFVNdVoyTVNpNGc9PSIsInZhbHVlIjoieU5qWHpMM05YUlFIZlYyREhROGNxMVFRN2lwWVZqNHZ0WXBSWUpUSXNHSXpObldqVVNJb2RRVzBKaEtqZXIxVSIsIm1hYyI6Ijk0ZjA1ZDYwOTVhZWJjM2I5ZWY3N2JjMWVjMjc1MzEzMDJhMDBhYmYyNGMwODAxMTQ0NzA5MGJhZGRmZjU3ZGQiLCJ0YWciOiIifQ==', '083281106404', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3721700552332154', '1233791839629110', 'Marisol Upton PhD', '2011-08-13', 'eyJpdiI6Ilp5RlZSMGd3MEZiY2hzQlVZdmMzZWc9PSIsInZhbHVlIjoicE1uOFpsMHJYTVFmZlhTN0hYNTZFeVJ5MWthYjNNY2dGeXJMWDBzdnVoQmNNcytveGVzQ3VIN3c0N0JqUWh3NSIsIm1hYyI6IjJlZTEyODgwZDExNjEyZmY2YzE4YmQ4MTU4OWNlMDhiMGRjMmVkZjZhOTEwZmY2ZTM3NThmZDk0NzJjYWI4Y2EiLCJ0YWciOiIifQ==', '088462372226', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('3780862829786626', '1233791839629110', 'Emie Crooks', '1975-05-11', 'eyJpdiI6IkdKbjBUWG9IV210WDVmQWtmWmQwelE9PSIsInZhbHVlIjoiZVpMY05kdjVyc1NVcnhtQXgvVlFiM1UxM3BQa29VT2VzZkNQdXRMTjlDdz0iLCJtYWMiOiIzNTQ1MzgwMzRmMTZkOWUyYzUxYzExM2U0NTk4YjkzZmVlMzQ2NzVlZDgxNjFkYjJmZDUyYTk2NjBlOTFmNDU0IiwidGFnIjoiIn0=', '082937693812', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('3781762212703458', '2352183283459212', 'Remington Dickens', '1977-03-01', 'eyJpdiI6Ik56QzlPUW50MUhaKzBYSWRwR1B6Rmc9PSIsInZhbHVlIjoic0FjbGZKYjlhUklnVHNyNXd4QWtobGljODlGYkw4YVEranhJL3pKODBHd1cxczFNcFgzMjhIVmI3QXlpMVFNSyIsIm1hYyI6IjY1NTFlODkxMzk2YzFkYTUwOTcwMDFlY2M3MDg3Yzg2MWEzM2M2Y2Y4ZDY1M2Q2OGNmNzg0NTYwMTAyY2Y0MzgiLCJ0YWciOiIifQ==', '084725060021', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3790669648128166', '5138902707436241', 'Raymundo McDermott III', '2007-12-19', 'eyJpdiI6ImJNemlYVEFPNmQrOC9selh4MDVGQnc9PSIsInZhbHVlIjoiU1M2NWpoMmdRaUJFRURveDFSeC9BOHpubklYTjVlb1ZiOHdXTTFnV1FSV2YzR1BYNW9sWm44aDNFZHhCdVRncSIsIm1hYyI6ImNmMWRjYWQ0MDljZjc0MTAxZjAyNzNkYTg2YTNkYjI0NGI1MzYyZTc0MmZjNTUxODc1MjIyMWQ2NmZmMGE5MmIiLCJ0YWciOiIifQ==', '084527598009', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3795914626868426', '3677277571821350', 'Jeanette Haley', '1972-06-03', 'eyJpdiI6InBYODVGZlNtZElLUndYbU9Ma1J0U2c9PSIsInZhbHVlIjoiejlIZzNkT1BNWUZyd0c2V1F4ZG5QdzFlYWkxLzdFQ05HcTlzUVJ6NmZZRWkxcGJOVk1sWnZsZDRlVHdDVXBwdyIsIm1hYyI6ImQxYmI1NTg4NzAzODFlN2IyY2U3ODVjMzhmZGMwZTc1OWQyZDZjZGYxNmI2MzVmNDJlNjg3ZGU5YmZkMTRhY2EiLCJ0YWciOiIifQ==', '086484895311', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3878775818711995', '8958937323630079', 'Axel Wisoky V', '2020-10-27', 'eyJpdiI6Im12Vm9oSWs1VWUweTBKUmZxQTErQlE9PSIsInZhbHVlIjoiMHBZRzNmWVZsTHpyZ0l4cjNkTGRtWERmL2VZL0IzRkd2YXpIOVI2NFEzRktxWmJNUkVFUHJmWGtyblVMemRtbCIsIm1hYyI6IjA0NjE5OTQxMDRlYTMxOWExYmNkOTQyZWU0YTQ5MjdjNWI2ZjE2OGJiMGE0MWZkYzY3NGIyMTM5NjRlYzA0YmYiLCJ0YWciOiIifQ==', '083405080386', 1, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('4019554442191682', '3677277571821350', 'Alverta Rempel', '1973-06-01', 'eyJpdiI6IlBNUnE5cG9FUXNUY3ppeGxnRUMwMVE9PSIsInZhbHVlIjoieERLUXdwd2JzYks0NFBUblNpSks4QnJDb3ZXYjl2ZjZzbWFLM09jUXQraytpWnRwNTEyVElteXI0cTFCcUJOQiIsIm1hYyI6IjcxZjRjODQ3NzdiNWRjODc5MTFlMTk5NGQxZDVhZjk3ZTAzYzVkNzNkZDMyOTMzNWU3NmZjMThiNzhmNzEwNmIiLCJ0YWciOiIifQ==', '088669967650', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4063556498594918', '4878045916240772', 'Filiberto Nicolas', '2009-10-30', 'eyJpdiI6IlpGZS8yS2ZoWmRKb0c2YVo4bFByQ0E9PSIsInZhbHVlIjoiZE1RZUFIcVo5cVRpeHBzMFVaelY3NGlaR1owRXFIVGtDN1RzRm9TOG0zdllIZ1VZTEJpa1hLZ3BiVkxRM1RMQyIsIm1hYyI6IjM1ODllNDkxMjJmYWIwNjVhYzhlZmY5NDg3NmM4ZDVjY2U4NDM2MGY4NTA1ZDdiMWZiNjM2NjYwNThmMWE0OTEiLCJ0YWciOiIifQ==', '089579559618', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4096333497694141', NULL, 'Miss Jaida Mitchell I', '1990-11-02', 'eyJpdiI6InAyZW5vMlZLMkdadys3cVpidkNJRWc9PSIsInZhbHVlIjoia0pmSEl4V2lFYzN3NG4zNGtUdlJETkRxZENWYUo3VXJNMzZSSnZhd25xc3RIcTlMejhudVBHU1dMQnF1a01EQyIsIm1hYyI6IjRkZTg1MTIxZWFlYzYyMjI1MDFjYjBiODNkMTJlMTA3NDVhZDIzNmUwMjVjZGYzMGE5NDRiNjhlZGJmMTc1Y2QiLCJ0YWciOiIifQ==', '081803926985', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('4187465571124897', '4118538042647206', 'Aubrey Weimann', '2013-11-19', 'eyJpdiI6ImYrbFNjT2c4WFBOOFArNTFWMGp3V2c9PSIsInZhbHVlIjoiMzF6UUxFNWd4Q3pySzBIelNBL0VOVU1RQTJQN21OVW1NZkRncWtSQWIvamE5SElidlNNYzhzT0VFeXNmaW1USCIsIm1hYyI6IjE3NjZmNjUwZDI2NDUwMmYxNTBiMWNkNDNhNmFhM2EwNzgwYTgxZGU3ZTgzZTdmZDgyNWRmMzM3NmY4YWE3ODUiLCJ0YWciOiIifQ==', '086313853078', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4278776258111071', '3421205268285809', 'Jaida Stark', '2016-06-11', 'eyJpdiI6Inp6Zll3NkhrSERBVGVmQkVGU290d0E9PSIsInZhbHVlIjoiWW9Db2I2Q0pCcU5TUVZZb2puSFVEWHpNVzBqUnk3Z2dObFcxK25BKzl5dThxd1R5RjUvZHVVSTdWYnlWMVFtOCIsIm1hYyI6IjgzZmM3NzZlNzNlYWQ0ZDIyMGY1NTg5MDc0MmZjYjhlMTgzNWJmOTdkNzRiODFmOTgzOTE2NDZlMmY5NDAyMWYiLCJ0YWciOiIifQ==', '086337743673', 2, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('4282567203957615', '4878045916240772', 'Christop Lockman', '2003-03-27', 'eyJpdiI6IlJrUkVUSk5JeDRQMWRuV2p3YVpmSlE9PSIsInZhbHVlIjoieVp2MVVSRW5EL2F5eFZvWTVKV0gwNzRFbHU5c3FzMDdhQ2IxNTRoeVRQWEJTa3IrV2RWOUdWRUpaaTQrNzJRdSIsIm1hYyI6ImE4NTM5ZTMxMjY4ZGI1YmNhNjcxYWRhNjJiMjk2MDQxYWM5OWVjOGFmYjQ1ODFlOWFiMzI1MDEzMWNkMDI3NzciLCJ0YWciOiIifQ==', '087121572202', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4301550074633703', '5001207486395518', 'Gordon Schmeler', '1998-01-23', 'eyJpdiI6ImxvNEo3d2dPeFk1bCtlcjZEYkwzbEE9PSIsInZhbHVlIjoidm5zTkY3REE1ZlRNcXpsMHYxVWp4eVhxcnBhWGFSWjhBc1pFZ1pjY2Y0Rno2eFJXcDd2LzUvZGxBRGpmSWkybCIsIm1hYyI6Ijg0ZGM2OTZjYTA3MTRiZWQxZGViNWIyNzQxMTU0MWQ0ZjBhNWViMDQwZDYxYWY1ZDg2Y2Q5MTliNmEyNGZkZTEiLCJ0YWciOiIifQ==', '080401793212', 2, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('4361987573172153', '5813416891243847', 'Davonte Hauck I', '1986-05-15', 'eyJpdiI6IkE1Rm1rd1o2UzRYTkhZVGt1TUtlbWc9PSIsInZhbHVlIjoiQndBcVNRTzJISlJicjZ5c052NmdKUndQYnBHWmpxVThUL2paeUl6cEJrOGNJZlBlcDNmemtTNkFrY0lTRlpoSSIsIm1hYyI6IjcxNWFmNTBkZTI3ZDY4YjE1ZTRmM2MwMWM3NTBkY2M5YWVhYTYwZWE5MDYxZTJjZmQxM2Q3MDc2YjFjYTVjYjkiLCJ0YWciOiIifQ==', '080356457163', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4381799045319171', NULL, 'Vivien McLaughlin', '2025-04-18', 'eyJpdiI6IjJBSXZiTEdWTmJzZnVyRGFRQ1BwWFE9PSIsInZhbHVlIjoiYWRjTUJQcEg1ckp5S3FRRy92cldWY0lrR3VxNzR6MzJUb0RyckptSEp6cjRKS0xncFZUOGQvd210Y3dzakRuayIsIm1hYyI6ImEyMzBiMzY1NzM2ZGY5ZmI2YWUxZDE0OTk2NzE1ZWM4MTY1MDRjMTgyMmQ0MzYwYmY0YTA1MDAwN2YyZTdjYTkiLCJ0YWciOiIifQ==', '088413971243', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('4465026426842313', '3677277571821350', 'Mr. Alfonzo Dooley', '1982-10-31', 'eyJpdiI6IkRDbDkwY3VyVUw3d1R2RVQvTStDSnc9PSIsInZhbHVlIjoibHZKV2dQZXZ4RkRSWXZiT1pwS3F2SFVRaVU2UlIybm1SejZJcERHWGc0OW4rUXk5dnVCM2MwWFNwdkliVFFpaCIsIm1hYyI6IjE5Zjg2NWJkZGVjNzBlMjBhNWI0M2M2ZGViY2Y4MTBiMDk4MzMxMzljMThkNGJlN2VjZGU2ODkyMDNkNjcwM2IiLCJ0YWciOiIifQ==', '086912963624', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4505213597384420', '1660961957535760', 'Kari Barrows', '2014-02-08', 'eyJpdiI6ImcvV2xKc3ZEK1BwL0REb3JhY0thUWc9PSIsInZhbHVlIjoieFVWeWk3Qi9oSnh5MFhteEkydUJsMmdaWDh6N0REZVZTVzc2bzVyYVozTFFrbVZGWHZPTlZhd3pzUFJralYraCIsIm1hYyI6Ijk1N2MzZjY0NjdkMWNmMjEzMjc4M2Y0YjBiYjVjNjIwNmI5YzdmNWU1OTcxOGY0ZDAwMzE2ZjJmMDc0YmUyOGQiLCJ0YWciOiIifQ==', '080525938081', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4519282631439486', '7075753455854325', 'Tanya Johnson', '2018-03-26', 'eyJpdiI6IlBQZlZRTXUydGM0UGVVSUswNlpmeGc9PSIsInZhbHVlIjoiZGZzMTduYklxVmlwN0tHRUZ5VVYrbElDM01CZGFjM2N2US9yamxFaS9lcnEwUkNvd3RYbWg1dkZHTkFWT1BHbyIsIm1hYyI6ImU3NzNjOTYzZWEyYjcyYjI1ZjYyNTZlOTY4YjFkMTVmNjU0YWIwMTgzZDIxZmM2ZTcyMDA5OTgyYTg5YWNhZDUiLCJ0YWciOiIifQ==', '086454034550', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4741704571918545', NULL, 'Garrison Hammes', '1983-09-27', 'eyJpdiI6ImdvTHJsNWpoZFdKUE92ek1HNjdySGc9PSIsInZhbHVlIjoiTkM4Ry9WNTJVMlJLS0ozdU14bmwyZFQrK2FEc1JXWlVHRGs3MElSREtEdU9lQXl6RUNraEdGLzFOb21iQnV5NyIsIm1hYyI6IjQyZDE5MWJlMDI1NDVkMWRhY2U2N2E5MWU3NjMzOWExMTU4NTRmZjBmZTg3YWMxMDg3M2MwZGNiNWE0NDNiMmUiLCJ0YWciOiIifQ==', '083454269525', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('4854458624290894', NULL, 'Amparo Quigley', '1972-04-02', 'eyJpdiI6Ik1hQ3hSY1VCRVFGZjJnR1VPc1IxaGc9PSIsInZhbHVlIjoiek03S1JHdk9QemNWR1d6L3ViMjk4QW1oRi9CTXk0U0VJR29kWmZLOE5HUnRZUjYyQ0hKeEtuZzFmYkplSk5jNCIsIm1hYyI6IjBhOWIxMzMyZDM4Yjc5M2IzODAzM2I4N2EyNDU0M2UxYTUwNmQwMjEwZGE4NGU1ODQ5ODI5NWYxMDc1YTJkZjIiLCJ0YWciOiIifQ==', '085083077655', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('5015999804741324', '1233791839629110', 'Russel Rempel V', '1975-10-09', 'eyJpdiI6IkNZY3ovVGxPTG1nQ2I2ZlAzeHRLcGc9PSIsInZhbHVlIjoiWUI3S01VSVZ1RkhteXBhVmtPeDdTTnBZUnRqWDRacytmcU9Wbnd5SUl6R2t5TVBBa2RNQjhjcnRWTXZ2b2lXeSIsIm1hYyI6ImM2NTNhZjk1ZDRkNWQxZDA3NDgwMTgxMWIxOGUxZjBiZDhiOWYwNDUxYmE0Y2E4YmU1MjI5Mzc3YWI4OGQyZDEiLCJ0YWciOiIifQ==', '089278188972', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('5242463576709867', '1233791839629110', 'Freddy Mitchell', '1993-05-16', 'eyJpdiI6Ii9jYWFzMi83RWFOOXEvYmU5dUtUdWc9PSIsInZhbHVlIjoidUJFTUNOUE5sazlqRDA1M0NqTzJaSXlVYjh2dWM0aHQzZFJ2aFQyeDZOa3RoMkg3Y3pXbm04MFgxZ2Ezdm5DRyIsIm1hYyI6ImY2YmVkMzU2ODNmMzQ4NDUzZjNkMTFjMWI5NmI0ZGUwNDYxNDI1ZTUyNGRmODNmYmUwMTIxOTUzMjEyOGYyOGQiLCJ0YWciOiIifQ==', '084671094382', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('5257629764665805', '3421205268285809', 'Arlie Jaskolski', '1999-02-13', 'eyJpdiI6IkZSQ0tNSkExYkFDUWx2dzBMUEVuYWc9PSIsInZhbHVlIjoic1NwMWU4YW9XcU1qOGFMYkVNVGpnSVUyTSsyMWxDaElTNHBNayt4T3pYRlh3MGg0aTRWZUR5blU1Z3NNbXA1YyIsIm1hYyI6IjgwZDNlYWY0NGQ1OTUwM2YyMGE3M2I5ODc1MjhmZmQ0ZDRjN2EzNmNlOWNmMTQ4Mjk1Y2U5NjExZDZmYTZmNDUiLCJ0YWciOiIifQ==', '083963904241', 2, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('5262394477764231', NULL, 'Paula Klein', '2022-01-01', 'eyJpdiI6IndTVlRjcWtqWm4wbjFXczBCR3VxQUE9PSIsInZhbHVlIjoia29VTWFoNm5NbmU4dlFFRUEzVkFnaUlMbk1XYVlSSFl1SmJ5d1RGWVpuczVJZi9GRHg3c3RleENtUHY1a2c3TyIsIm1hYyI6IjYxNzk4ZWNiNmEyNDIxNjAyMGVjN2MyNDQwMzljMjQyMDhiYTQ1NjE3OGNlYjVlMjYxZDhiOTRhYzg3MTQ4YWIiLCJ0YWciOiIifQ==', '082821156299', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('5538260369323391', '3421205268285809', 'Amina Hessel', '1979-07-14', 'eyJpdiI6ImJKc1B0NVowQzk0QW9NQnN0dmFBM3c9PSIsInZhbHVlIjoiYWRWU3lIdDE4RDIwU050Y3h0Nk05elgxUGd5RUp3MnFSOWZiWHhDOFJXZjRTWkU3VkpzeEdLSHUyeklFVHQ3TyIsIm1hYyI6IjEyZDIwNGFhZDUyMTlhYWIzMTliMzdlNWRmOTkzNjY0NWQxMDI3Mjg0Nzg4Y2Y4YmY0Yjg3ZDU4ZTRlOTYyYzAiLCJ0YWciOiIifQ==', '086815749127', 2, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('5578276384260630', '5813416891243847', 'Wanda Wolf', '1991-10-20', 'eyJpdiI6Ik5HcFltMUVlQ0hpL2ZlOGt3S1ljY3c9PSIsInZhbHVlIjoia3U0YWwxajlHRTlDTUhybGdXeERqSWtxTVhJT2NWVFBXdU0yUTFIc3V1SEpUZGJpU3JMdDJmaTErYzZ6SWFCViIsIm1hYyI6ImMwMDY1ZDdkYmY1ODZhMDc1MTI0OTlkNzYyYjNiOTczMDg4MGM1YTU3NDc4MzE5MjYyMTMzYzczOTRhYjU1N2MiLCJ0YWciOiIifQ==', '084782933663', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('5701813829625777', '3619504393732364', 'Jarrell Harvey', '1973-08-31', 'eyJpdiI6IjgrbzdqMWpoY25HbjE3aG8vdGVsZEE9PSIsInZhbHVlIjoiM0dIdTZlaTRmQll6V0xiU1NqWUhFTmpaVkRiV2JZa3RGdDdrYVZHdEpmT0hSajVZQlBrRHVDNzNzK2RYVGY4VyIsIm1hYyI6IjRkZDdkZTIxM2M5NDM3YmM3MGJkMzNlNTk3MzYyMmQ1MjFmMDk1YzQ4YjYxMTY5YzhkZjczYjcwOWI1YjhjYzgiLCJ0YWciOiIifQ==', '089650228753', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('5744659211397856', NULL, 'Kianna Hammes', '2014-02-14', 'eyJpdiI6IlNmQUl1bFZBQlFvZzRqQ212VmhvOHc9PSIsInZhbHVlIjoiSFg3SG54Rzkrd1d5Z3hiOGRCd3FXb3plRkx2b3djTUpCQm4reVZ1dWJnL1BjSmxRRzcrTDBLMVQrcWNQSk1rbSIsIm1hYyI6IjQ2MjU5NzdlYWMxODcyZmYwOTk4MWU3OWU5NDM4NTNiOGQ0MGFmYTA1MGU0YTIzMmQ2YzVhZDM4YmEyNzJiZjkiLCJ0YWciOiIifQ==', '084741951603', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('5756004488963627', '3790303525496946', 'Aniya Grady Jr.', '1972-04-10', 'eyJpdiI6IlBtMk5hT1RjdUZJTVV5RXRFOHI4OUE9PSIsInZhbHVlIjoieHdhR09GK0xOc0VOOWZJcnoxckhPMEI1V0ZQZEpJZWFNc2tkZmxFSkNHT0FPTkE1cVg1ekxaOGFiRENhRnNPcyIsIm1hYyI6IjM3ZWY5ZWJjODQxMTYzMGJmMzFjNmNlZGYwNWFiZDI0ODkwODczMDA5Mzk4Y2UxNmYxZmIyY2RkMGY0ZmQwMWEiLCJ0YWciOiIifQ==', '083451207463', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('5879662869832569', '7075753455854325', 'Orie Konopelski Sr.', '1991-08-16', 'eyJpdiI6ImlGSHJBNU05SVJxSTJKN2lOUHNCV0E9PSIsInZhbHVlIjoiSVkrdGs1clB1VHRjY0I3Vk1za1hxd2pKRDZ4NnZ4VGRMUmJ0UUFsVkx5ZFBvYnFLVGJ0Qk8wVnhjaU9mQlppSyIsIm1hYyI6ImVjMTg5ZjYwZTU1Yjg2ZWM0MDM1MDY2YjdmNzU1ODFlMWQ2M2YwN2E4Y2I0OWU0NTM3MzI3NzBhMzk5ZmFhYzkiLCJ0YWciOiIifQ==', '082242462848', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('5879810478829248', NULL, 'Nicola Jaskolski', '2016-12-14', 'eyJpdiI6IjkwMDAxdGZDOXdlc1lBWHdITXJTd2c9PSIsInZhbHVlIjoiUEM5OWMwRFMyZ3pQSHl5OXozOEZidnV6WnVoY2p5THpzbXRoSDRNeXRMTUJQZGpMWDYyakhEVE52YXBEMGlzdiIsIm1hYyI6IjI2NzcwNjc2MzFlNGYxYmJiMDE5ZGJhOWM0ZjM5M2VmYWQ4Mzk2OTkxOGJmNWM3NjQ5MTk5Y2IxZmNiYmU1NDAiLCJ0YWciOiIifQ==', '086060992745', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('5967910942197430', '7873743606870620', 'Jabari Reilly', '2009-10-06', 'eyJpdiI6IjljcXJrd2JiMzNCa0hKUzZES0wrN1E9PSIsInZhbHVlIjoiUEJtN3FyN1VCdWN5cW9MUkxYMTc3YkdXaFE1bkx4bnVCYjFDbXNyVDc4eHM5OUlJeWlwVXBveWJ6YlpJdWQrdCIsIm1hYyI6ImNkYjAwYTJlYjliMDIwMWY3NWRkYThhY2QwMzc4ZjgzYmU4MTRmMTAyYWMzMThkZjViMDhiM2MwNjk4OGUxZGEiLCJ0YWciOiIifQ==', '080886862108', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('6307266807914803', NULL, 'Leonard Hamill', '1974-05-04', 'eyJpdiI6Im9jVVJGQU9yenFDaUsrUmVBWUp5Z3c9PSIsInZhbHVlIjoiMzhxblp0c2F0R2NESHM0UVQ5YkpyUWNJWnBWb2tNUlR2dkJqb1Fya0ZTNkVqTTFVR2M0TDBNNGQyNUF6dnFydSIsIm1hYyI6IjFjZmFjZjZmZGRjYjE5N2QxZDE2NWQ3ZGQ5ZWU4MjZlZmM5MTJjNWVmZTU0ZDcyMWQwN2E4MWVlNDViMWZlNTIiLCJ0YWciOiIifQ==', '081793161550', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('6520016491495352', '6988051632499646', 'Prof. Lilian Haley', '1988-05-20', 'eyJpdiI6IkFUdzJUU1JlRDhkT1BrNFN5cG5ZdlE9PSIsInZhbHVlIjoibzlFeFZyMUZuVXk1WERqdEJ6MUZEaklUbXZRazdoY3JmYzJtS3JkandLdmEvSVFqdlh1U1lmZHVWT3J4UHF1cyIsIm1hYyI6ImE2YTRjMjU3NWQ0ZGMxMzFjYTBkZjM1NzA2MDg2MTcwZjdjNmZlN2U5OWU2MDZkZDExNTAwMzZmMTdhMDNhNDkiLCJ0YWciOiIifQ==', '088302346024', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('6583712080202361', NULL, 'Domenica Ruecker', '1972-12-14', 'eyJpdiI6ImJhWUR2d09KVS85S1kydW1RQkpSdHc9PSIsInZhbHVlIjoiS0JCK2FOME4vT1NnOGZ1VlFUWlpzNUt4NXFDcHU0c3NObDBKTzk4TXRjL0JhcjZQQ0w2R0d2c3BDMEtWN0hwdyIsIm1hYyI6IjhjYTAxY2UzYmM0OGVlM2VhMTcyY2U4NDYzNDRmMGFiYjBlOGQyY2JiYjM1N2Q0NGMzYWU4M2UzMTU2MjFkMjUiLCJ0YWciOiIifQ==', '086242879804', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('6595145129658715', '1233791839629110', 'Ralph Funk', '1983-07-17', 'eyJpdiI6IkZoaGRkZFZ4RkFzYUxRa1NqSVFyVnc9PSIsInZhbHVlIjoidGt0TVlxcUVyMFBGS0ZhaWNmcjBNdmk0MDh3anJqcmZGdjczaFhnbTc2cz0iLCJtYWMiOiIxYzhiMjcwNTFiNjljOWJlYzAxYWY1MTAwNjMwYzdkODIyM2M5MjJjMmY5ZTlkOWRkYjQ0YWZjNTgxMTY2MGQxIiwidGFnIjoiIn0=', '087952460685', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('6824309255018061', NULL, 'Ervin Boyle', '1983-11-19', 'eyJpdiI6IjljU1dWRDZSUk1FZTYzWlQ2OW8xclE9PSIsInZhbHVlIjoiQ0hWcktqeFYyVEVtdGpDYzA5L1BwQ3BEd0xxRS9NbTZ3ejQraE1vNGxLdC9BSkd0c1M4Uy9rRjNXbDZORitxdiIsIm1hYyI6IjFmODE2MDllYzU5YzUyYzNjMjNiZmRiNjI4ODBhMjA0MGY1MzkzYmQyNWJjMWRlMGE2ZmVkN2Q5Mzg5ZWJlM2YiLCJ0YWciOiIifQ==', '082683726005', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('6892704104239204', '3790303525496946', 'Jennings Bechtelar', '2021-09-06', 'eyJpdiI6ImZiNzVKekZMa3pPUWJhSkFtc2J3MEE9PSIsInZhbHVlIjoiSEJXQkIwSlhGbEY1Q093d2ozRWRvdE4vd0NxRlVZVHh0YUhWaSswaXRJQktCSmhUcUpJK3cyYnExWHhEdGVvNCIsIm1hYyI6ImZlNWExZDExNzZkNzhjMTdhOWRlYjhlYmRkNjM5MGM1YjY5ZWVmZTE0ZTg5ZjEzNmFhNjgwOTg4MThkMzg3N2EiLCJ0YWciOiIifQ==', '088006154736', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('6908665213825078', '1233791839629110', 'Katherine Torphy', '1979-08-01', 'eyJpdiI6IlJ4ODZlbUE2cnpUZmoxd0xUVTZCVHc9PSIsInZhbHVlIjoiZCtvbE85dFBIN3FSZjVSMGZEQkpyR2xnVzVGVjREeVZKNzYxaCtDS2JpVGV1b1gxZG13dlpwTnE5TkRaaFA3byIsIm1hYyI6ImU1ZGExNjQxNWRiMDU4MDE3ZjU5YTI4ODUzMTE0OTE5OTg2YjBiNzVjZjc4YTQ4NWJjMGJiOTliYWFlNDA0OTIiLCJ0YWciOiIifQ==', '084981462663', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('6913960499989309', '1233791839629110', 'Verlie Pollich', '1998-05-23', 'eyJpdiI6ImpyQUp2QmJoYkZVdS9sR245bDQ1L0E9PSIsInZhbHVlIjoieDgvM1ZveHZFSTJIQVNsdldSN1lBYXc1RzJYK0d5SDRKTEwxVjFzNVl2OWtYdjJiTDRZNEtUd21ENVJFYmt0UiIsIm1hYyI6IjE4MjY1YzY0YjAwMTE3MDMyZGY5ZGQ2ZGQ4MDJiNTA4ZWY3ODE4Njk4NjI2YmMwODRjN2ZhNWU2ZDVmMmE0MzkiLCJ0YWciOiIifQ==', '086462318011', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('6929672155228920', '1233791839629110', 'Karlee Bergnaum', '1977-12-23', 'eyJpdiI6IklwWnRPVUtLcmNkRU1Jdi9MMGorQWc9PSIsInZhbHVlIjoiZUFIRmE3Mm93dFBlMi93OFFMbUZaVStjWEZ2T2F5ZWNGWE1lUzNpRk5scDlvVnd0VmlvS21mOUJNL0hDUTRUdiIsIm1hYyI6IjA1NDI1ZjA0NTE2ZDlhODczNDQ4ZjQzMjE5YTE3NTg0NzA0YzNlOTc0NWZhODdkYTUzM2NkNjM2MTAxNzZkZmYiLCJ0YWciOiIifQ==', '080052006472', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('6970788577109346', '8958937323630079', 'Zakary Lakin', '1974-08-22', 'eyJpdiI6InhmRE45dk1hamJPM1l4UnhyQm9zc3c9PSIsInZhbHVlIjoiRzk2SFZTQzFKV2NVWUQrK21zVmFmYklabm9PNkNEcTFlVnpmWG5SSENmVitnL1JhYmhndm1XK0h0ZTRQSU9iWiIsIm1hYyI6IjU5ZGViMTRkMjY3YmYzMzgxOTQzYjQzNDc1ZjE4N2ZlZmI5NDZlODQwMGQzYjYyOWU2MWY4Nzc2ZTNlNDBmNGMiLCJ0YWciOiIifQ==', '086126442531', 1, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('7004729095594613', '5612773451825704', 'Alexandre Berge', '2023-01-02', 'eyJpdiI6ImMzMi9reGcvSFJSc3pDb2h0dnBxMlE9PSIsInZhbHVlIjoidENCWStXU1lYNGlHRzA0akI2bnVGWHpPK0NMQXlQWEo1RDhNR1I3SEdyU2VQWk1GYjZDbjZRUnZVZE5LOHRwUiIsIm1hYyI6IjRlZDQzZGY2NjAzOGY3ZjBkNzIyYWYzMmE2MmZkZGJkZDExYzUwZDAxNTg0NTQ3NTNlODQ3NDUwMGI0ZjFjNmQiLCJ0YWciOiIifQ==', '083554881696', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('7012281952710046', '1233791839629110', 'Jayda Fay', '2007-05-15', 'eyJpdiI6ImhaMGhZaGRBRVgwSnF5YWFsUGJhN2c9PSIsInZhbHVlIjoiVk01azR0NFhUVjdrakRhN2xybm8ybXIwRE4yY3lpbXB2bDFXZnVuZHllcz0iLCJtYWMiOiJjNTk1OTRiOTVjNWQ2NGEwZWRlZWViNDNhZDQ2ZjI5ZWNkZDg5MmYxMWY2ZTEyODcwYTYyZmI0NmE2OTllOTcxIiwidGFnIjoiIn0=', '083714813555', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('7109700345988517', '8252925377204770', 'Jerrold Hilpert', '1997-05-06', 'eyJpdiI6IndlTkxCTVNEb21qZnZEa1lHOGxCSGc9PSIsInZhbHVlIjoiZTVSa1VYcFdMMnVHUUhlZWw0Y1dxZ05MZXkwSXlZQUdDU251aEpEUTdZdTZMb29sSmxFU2hranAreVVOMk5iWSIsIm1hYyI6IjM4ZGU2Yzk0ZDE5NjIwODgwYmM1OGRmNzQ1MjQzMDEyNWNkNjkzMTY1OWVhNDVjNDIzYjU0MDljMmI0NTA1ZTgiLCJ0YWciOiIifQ==', '082475814946', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('7119734837910704', '8958937323630079', 'Brendon Abernathy', '1997-02-27', 'eyJpdiI6Inh2RTkrejQ3Sm81YXdJMzExOWU3MXc9PSIsInZhbHVlIjoiN0JVYWwzWGtEbDQ1M3RBcDJ1KzQ4RExkVDhOZDlydS9sb2FTc2czSEJjM3VQOFJIN3VtWHl6Ymc1VWpoVzVrTiIsIm1hYyI6ImFmNzczZGU5ZTdiMzJkOGEyNmVhY2I2YTFkZDM3ZWVlYmY2NzA5Y2I4MDU0YmExOTQyMDI2ZDQxZWY3OGU1NGMiLCJ0YWciOiIifQ==', '084242816724', 1, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('7153845424003833', '4978690671792204', 'Donna Balistreri III', '2009-08-28', 'eyJpdiI6IjZlMFlqdDNkVlIyOTBaSHBNVkF2M3c9PSIsInZhbHVlIjoieXE4cEFxRGtHNGRpR000Ynp6WG83TjBCQ21ZSXdvM3V5Mjk5UC9zVXFnTTV1SHo1cDQ3RFVBSVc2WnFjcEtOMSIsIm1hYyI6IjYyY2IxNzJkNDAxMmIyN2ZjYmQyMTE2MTkyNWEyYTQ0ZGUzNjYwYWI2MjcwODE3OWQxZDFlZmZiOGQwYTFkMWIiLCJ0YWciOiIifQ==', '087885633976', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7180364968353838', '7873743606870620', 'Joesph Swift I', '1977-07-30', 'eyJpdiI6Im5oVE05SFUvRXNrQTdkZm1wSTFIYkE9PSIsInZhbHVlIjoiazBJcFV2UCtKWWtUY0VGUFFTUGRjSUVPTytQYndlWFZQK0FSdGpuMkE0MFYrd3hORHV1dFVpTEliNm5JdHZ6WCIsIm1hYyI6IjZhMjI3ODVjNmU0MjQ3YmY4NjIwNTAzOWU3ZjUzMGZmMmJkOTBlNzQ0MzdiZjg3MzQ5YWFhZTJiZjhlNzdlYmEiLCJ0YWciOiIifQ==', '088363522837', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7203751937254613', '1233791839629110', 'Prof. Kip Fay II', '1984-07-12', 'eyJpdiI6InROOURacDI4TDhrUWp4TmgxVENXeXc9PSIsInZhbHVlIjoiUHB2dTJNRWZ0eEpoZGZWaitvSzYzenF5YXFKRGtnVDlXRWwvRkpwN0ZnZ0NxdXpHRHpCbDVHV2NXUldUdmxjYSIsIm1hYyI6IjBhOGE5YzYzMWFhZGI0ZWZkZWExZjA1OGE2ZGMwNmQzZmEwMTQ5OGVlNDI0OGUwNDM0OGU5NDU2OGQ3YTAwZDAiLCJ0YWciOiIifQ==', '081261060464', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('7393602997992159', '6988051632499646', 'Arno Marks', '2024-12-17', 'eyJpdiI6IklyQitJUytlTlpzQW9zZkljZFR5Snc9PSIsInZhbHVlIjoiUXNScnB6dUlndTYwNk1nc0pEcjc5Y2luWnBUb0Z6NEZHMXh5V05HMjJsVUVLSW5hQ3IvNGVkMnRHMGRwSnlPciIsIm1hYyI6IjU4MWQzZTJmMDJjOTgyOGE3MDM1NzgxYjQ4ZjcwNzVhMWJkYjgwODU1YjQ5ZThiNDUxMGE0M2FiMWNjMjk3ZDMiLCJ0YWciOiIifQ==', '088947532674', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7424022564701548', NULL, 'Vicente Stroman', '2019-09-09', 'eyJpdiI6Im5hUC8wQWd6U3dRT1ZZcEgrRDB4VGc9PSIsInZhbHVlIjoiRnVDRGZIcFRHRlJSd1NxSzFUcW5BNUNsbzU0V2hDMG9zTzRrR1ZFRlJDTE1uQ1J5MmRvT0FYdlI0REZFSlh0eiIsIm1hYyI6IjA2ZTUzY2M2ZjAwNmU0YTc2ZjQ4NmNkNjEyYzllNGY3ZjdhZWRlZGU2M2NjYjE3NzcxNzFiNzRiYTdkY2I4MGIiLCJ0YWciOiIifQ==', '087788389248', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('7437696059229065', '3619504393732364', 'Harold Gerlach', '1990-06-27', 'eyJpdiI6InltWTRVOGUvckNaV2FudEd2eTZWcWc9PSIsInZhbHVlIjoiOEIyUGtIMVdHcER2M2pKZnErQ2w4WlVJMzBJdmtTb3BPanZzMTh4T1lpWEpEOUp1eU9xVjZneUxXd2tvWEl0SSIsIm1hYyI6IjA0NmE1NTY4YmNmNjhlNTkwN2IzYTU2M2Y3OWUyMGM1OTgwNTJlMTliYmU0OWQ1YWIzNGU4YmQ2NzRkOTI2NWQiLCJ0YWciOiIifQ==', '086354533635', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7567233677789817', '5612773451825704', 'Bernita Koss', '2015-10-18', 'eyJpdiI6ImwyWURzaUdTNEhwQU5oNlNCakxLbUE9PSIsInZhbHVlIjoiMS9CNkczZnBkc09TR1lTTDhBNERqSmVBb2lNMkJST1N2Yk9yVmNKOERHNEN2VVNwZ3prZll1dXdCcUlRcHJZYiIsIm1hYyI6ImM4YjkxZmQwYjE0ZDk0MzljODdlYzFlY2QzNzBiOWUyM2Q5ZjFjZGQxNDBkMTI3YTZiN2RiN2E3ZjBhYTUyYjMiLCJ0YWciOiIifQ==', '080008780155', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('7585599281379407', NULL, 'Julia Hand', '2022-12-01', 'eyJpdiI6IkdOb1NyNWFFS2plYVhsWUpNQVZKUGc9PSIsInZhbHVlIjoiNDFvYjFlQjRubmVra0tuVGMvVlhYakRTQ0QrV0lrUkNTei9hb0c0eGQvK0hPa1hFSW9vM1JwR2FreVJCNkN4cSIsIm1hYyI6IjA2OGE5MTc1NTg1OTI5OWU2MmQ4NTk3MTBlZGVhYjA2OWVkNDc2YmNjMTZkZjc4ZjA1YmEwOTQ1YmI5YjcyYzUiLCJ0YWciOiIifQ==', '089816292599', 2, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('7589394078728639', '7075753455854325', 'Barbara Dibbert Jr.', '1997-02-14', 'eyJpdiI6ImJuWGUrZmJibG9JQUpEeXhUMGVnbnc9PSIsInZhbHVlIjoiSkRnUkFiUmZjbDdGUFFBdTRqWWdvazR3WUU4cXNhYzV4ZStsaWpQRThBM3dHMDBtZmdaTVZOY0pGalZnQlVmaiIsIm1hYyI6Ijc1NjVkMWM2OGFiY2IxMDNhOGFlOTQ5YWM5ZTM1MWY3MTZiODAzZmUxNDBjZWY2NDliYzU1MjdiZGNkMjU2NzMiLCJ0YWciOiIifQ==', '080338125960', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7602237318489366', '4118538042647206', 'Kenny Heidenreich IV', '2017-03-22', 'eyJpdiI6ImVsRlZUc2NLckNYdXhha2FMWERENkE9PSIsInZhbHVlIjoiRnB0UlVCUnBIWmNNVGk2S0phZERYMWRHMzUwdEJJZjNGZWtWQmg4N1pQaGRaUlhvcVAzcSs1NGtRZ01lUEx4UCIsIm1hYyI6IjM5ZmViYzU0ODE5NWNkMDMwYjdiOTMyMzhjN2UwMjA1MDQyNTc4ZWM1NDljMTJhNTY0MzdlYzM3MGExNDk4MzgiLCJ0YWciOiIifQ==', '082188009747', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7632292793269675', '1233791839629110', 'Katelin Ferry Jr.', '1971-04-09', 'eyJpdiI6IllqR0laaDBEdHp0WllDRnh5WXFUR1E9PSIsInZhbHVlIjoiVDZTMWhDdGV5bTRZN1gvSk1SYjYySGtWZnpUaHpwcVQ5M0hwdGZGaGpldEFIWlREVFNpYzB4SGVUTlk2TGcrQyIsIm1hYyI6IjU2MjhiNWU3ZjQ3NjBlYjIyMGNmZDM2MjEwNjBlZGY1OWJiOGExNTAwYjc5MzgwOWI0MjJiZmI0NGUyNTlkNGEiLCJ0YWciOiIifQ==', '083601939621', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('7661351748728698', '1233791839629110', 'Mr. Cooper Fadel IV', '2000-10-28', 'eyJpdiI6IlpzWmpKTDNVQ1ZFZUZSZTVxN25qU3c9PSIsInZhbHVlIjoieER5ejNoVjdQSERtZm11anpBd0dVTUhac1VqSUhmeU1FL0F5bmNjWjBOamFQajJHcnlvZDJrVnEwNDgydlJ3KyIsIm1hYyI6IjMwMzIyNWU4ODE2NDExM2YzN2U3Zjk2NDQ0NWZhYzdjMmY1ZjQxYjA0NTk1MDc3OTZkMjcwNGEwYjFiMTIxYmIiLCJ0YWciOiIifQ==', '080579208723', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('7774394540040179', '3790303525496946', 'Miracle Veum', '2008-08-14', 'eyJpdiI6Ilcwb2hpR1FVblloUFRTeElLZHRvcXc9PSIsInZhbHVlIjoiYlZ0YjM0TGtOR0JIYnBnZlZZL2J5TTg3TUdlNmpDTDZQT3FCcWZUNkwraGVUdnJ1N1h5K1lCSUZGZ01rMkRYMyIsIm1hYyI6IjQyNGFlYzgwYzU2MTI2ZTMxNmI4MjQ2NDBhODIwOTFhMzU0NTFkNWZjY2I5NjBkZTczMTZhOTkxNGVmYzE2M2YiLCJ0YWciOiIifQ==', '089927623265', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7787281848167325', '4978690671792204', 'Taurean Runolfsson', '2007-08-19', 'eyJpdiI6Ik5YVldCclJmby9ZNWlucmx3M3kwdWc9PSIsInZhbHVlIjoiU2JTenpYZng5M3ZYd2xMak44emhTazdNK1RnclpWUG9LS0ZTZFh5SjUzclJhU2VlbDY1eUtqdUJhcFFENlNVcyIsIm1hYyI6IjY4MjVhZjM1N2QwNTcyOGQ3NjY3NDUwY2M2OTAyMTNhMGIxNzQ0ZGYyYmEwZmJiZjIyMzMyMTlmMTI0NDJlOTciLCJ0YWciOiIifQ==', '082236977572', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7890993187085161', '8252925377204770', 'Miss Eliza Ullrich III', '2018-10-12', 'eyJpdiI6Ilg0dHlsbkxZVk01cGtDNlZwcEljeFE9PSIsInZhbHVlIjoiVkRneE5XaEsyN25PbGNSNnI5ZExERTdQSEx6MXZVT0dGZi9MNHdDR0duYWE1aVM4dlpaditkMXlRMmFnSTBsRiIsIm1hYyI6IjdlMWMzYTM2NTYwMWJiZDM1Y2Q3NzI3Y2Y0OTBlZWIzNzBmMTRhMDRmMWJkOTY0N2M0MjUxMTE0YTA0NTVkYTAiLCJ0YWciOiIifQ==', '084449612133', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('7995457385909268', '5001207486395518', 'Raymond Bechtelar', '1971-01-11', 'eyJpdiI6IklyaEE2dVliMHgzaFhCT0wvSkVLMWc9PSIsInZhbHVlIjoicW5hTDdub1Z4U2paTWhRVkN2dTNZMVVGSEwram1SKzM1dGkyTS8wWTVLbit2djlnSkNoTHZtekdIK3JtbUVCQSIsIm1hYyI6ImQ1Y2Q1YTZhMzI5YzJlYjgzMDYxOGU3YWUxZmVjODhiOTIwOWY0ZmFmNWM3ZjIyMmZiNTBhMzEzYTMxYjVhYzgiLCJ0YWciOiIifQ==', '081770643548', 2, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('8074387465060152', NULL, 'Mr. Tod Walter', '2003-04-08', 'eyJpdiI6ImZ1aGZkTUczcVRYQ0h0Mzd0L0xiUEE9PSIsInZhbHVlIjoiS2l4SkpHd2NGR3hCaEJZdEFZUkRScmlJSS9yT1lWMzVmZnhHSWNGSWdRMD0iLCJtYWMiOiI0MDhmNmIxMTU2OWY5M2IyOTAzMDgyY2IwNTdlZDYwNDVhNWQzNjc0MzI2YWRlZDFlMmZkYmRhMWZjYmM5ZTJjIiwidGFnIjoiIn0=', '086736340995', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('8177263444040736', NULL, 'Otho Rippin', '2022-01-24', 'eyJpdiI6IksvUDNiVVhMMnRWWUtvTnNOdThpVVE9PSIsInZhbHVlIjoieitkcEo5L1BObzdlY3JNd0tLUEJ6Z1orUkI3ODVCQUg3QTlvVVFnOS9DYz0iLCJtYWMiOiI4NDBlZGQ3YjZhNjc5ZmJkNjVjOTQwZTE0NDNkZjc1M2E5NDE1MzY0MzdkODAxNzYyNmMzZjkwYWE1MTFiY2ZkIiwidGFnIjoiIn0=', '088522325361', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('8677645003235905', '1233791839629110', 'Prince Hyatt', '2018-12-25', 'eyJpdiI6ImYwbHZaeDFBMGg5WDZNUC9LTHB5UlE9PSIsInZhbHVlIjoiVm1KZEhTS3FxQVRBRlpjS0pvenQzS0NERUJ0ZVFwVVA2Y2RjWnFwa2t1TT0iLCJtYWMiOiI3YTg4ZjIzMjliOWI3NjdhNWQwOTE3MDUyZjQ5OTY3NzJmMjc1ZGFkNWNlZjljMzA5YTVmYTNkNTRmMTFmMWM3IiwidGFnIjoiIn0=', '087926283592', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('8924557358624676', NULL, 'Brenda Hills', '2023-07-07', 'eyJpdiI6IjBuQWpZOWhOV2JlYWFRYWxBSmlpYmc9PSIsInZhbHVlIjoiZVFHTWVVZzc5WTNZektTVDVwV3Y2bDVQYThWZzhrZ3ZyTWR1WVBlVU92UT0iLCJtYWMiOiJiNGYzMmI1NjBlZDIwZGFhMDUyYWRlNTY5NWJlZDgyODQ0ZGUyY2MyZmJmYmUxNWQwNWM4NmQ0YjFkN2FjOGEwIiwidGFnIjoiIn0=', '089404393351', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('8982775245399990', '6969058683899311', 'Koby Schmidt', '1984-04-26', 'eyJpdiI6IlFSZ3pMSTR6d1owNVVsUTlKWjlJMGc9PSIsInZhbHVlIjoiUytFcFRJaWlJMkxVNGFlMVNxdmc2UElNYkh3WlUySjBuVjdnNmhIckN2NTBJbUJzQVRDaTVXRHlKUC85cmwxNyIsIm1hYyI6ImU3YTk1MDEwZGI2NGY0OTMyYmNhN2EzNGE2NTFjZDFhOGRmZDg3OWEyMzBhZTc4MThjZGFiNmQ4NTYzMmVjZjIiLCJ0YWciOiIifQ==', '089753252248', 1, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('9051445144286473', NULL, 'Mr. Michale Schuppe V', '2000-11-09', 'eyJpdiI6Ik85b3dOTll5MW81Q0U5RjdSR01aRFE9PSIsInZhbHVlIjoiWU9vUTZpNGtLSUdreTc2ZlNnQUdGSHlZeVN2djV5enhHQnk4Lzg2NGNIckpsOU9XcWYrd2NHTGoyT1dJUWF4aiIsIm1hYyI6IjVkMDZhYzM4NzdlYzhkODM0YTI1YWY5ODZkMThjMmZiOGNlMzJlODMyOTFiNzllMmMwYTE4MTJjODA5OGNlNWIiLCJ0YWciOiIifQ==', '083127177491', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('9064018936887419', NULL, 'Miss Christine Dickinson', '2007-01-15', 'eyJpdiI6IkgwVmR4dk5UQ2ZYUmRnT3dJZUQ1U2c9PSIsInZhbHVlIjoiTXpwMW1rdWtTSzVwZjFodEx1NjREQXFwMkNnL01TZXVMOVdVS2J2ZHQ2NFFLaHM3SXBuaGpTN0NHRTJKbmdLaSIsIm1hYyI6IjkyYTBhODNjNTNmOWQ3ZjEwZjhhZGYxODE0YTM1MWU4NWE2MDE0NjBmNTc0MTk2ZDljZmNmZGRjZGM5Y2ZkNGUiLCJ0YWciOiIifQ==', '080825113014', 3, '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
('9115898529275689', NULL, 'Amely Botsford', '1985-05-31', 'eyJpdiI6IldTbTdiaVZacHFFanR1eHM4YnBheFE9PSIsInZhbHVlIjoicXBUUzVQNUFpQkxaVnlTSTdjV2NPbHRSVHJEdkw5aGZ0MkVGOExEKytXd1kzSXYwT3N6MTRrd0VwT0RoKzFWQyIsIm1hYyI6IjllYzQ3ZTY5NmEwZGJhM2ZjOGU2MTVjNjJiNTUzZDA5NWQwYjgyN2U1NjZhODMzZTg5MTc3YTc4Mjg4ZThkZTQiLCJ0YWciOiIifQ==', '084525256483', 3, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('9157078261747449', '8958937323630079', 'Emilie Donnelly', '1994-07-01', 'eyJpdiI6IjZld09IZTBTVVNuUEJ5ampRN0ZMT1E9PSIsInZhbHVlIjoiMTNXbnZacWpsdG5LOUV5WXYyQjRVbkx2UzJxa1UxcmNSWEtSY0dBL1NlK1dCcnpoek9aamtjQVdxU21nN09ZcCIsIm1hYyI6ImRjYzRlYTUzNjViNjM5YTM0MWU2YTc4ODljNDgyNGI5ZTQ0MjE2NmU2ZTJkMjk3ZDAxMDM5M2M3MmQxNzIyODYiLCJ0YWciOiIifQ==', '087443130422', 1, '2026-05-04 07:36:40', '2026-05-04 07:36:40'),
('9229045182647549', '5813416891243847', 'Amos Borer', '2005-03-29', 'eyJpdiI6ImpKNUl1ak1ObC9yYmt4TTUrcGZIUVE9PSIsInZhbHVlIjoiajdwT2I5UHU0VWFPTTFpMDlkODdpZnZid1BSRDc2N1Q1UTR5akwreHpjVzc5cE1TOHJmakx4K0pRbUMrL1R3RSIsIm1hYyI6IjAwNzg3ZDAzNTgzYzU2ODQwMDY5MDg1ZDFhNGMzZGZhZGJhMTcxZmMyODY5ZmNlZDQzM2FkYjU3Nzc1MWU1OGUiLCJ0YWciOiIifQ==', '080247780558', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('9251423393469562', '4978690671792204', 'Wellington Osinski', '2018-07-24', 'eyJpdiI6IjNEYmhiUXF4c0UzaW5qTjRTWUhjVEE9PSIsInZhbHVlIjoiekF0Rk1oNmdlNkp6RjE1QjFQK3BCS2Y0Zko5by8xWUltTzFFS013bTZNMFFBeFJjbjVxZkh2U3VYQy94M0tQVCIsIm1hYyI6IjE5NDY4ZTNkZWVjMjRkMWMzMjg5MTJlMDFkM2RjNWMxODRiMjNiNDJmYmY1Y2E2YWZiZDRkODMyNjU3MDgxMTkiLCJ0YWciOiIifQ==', '085232959047', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('9642371409431637', '4118538042647206', 'Madge Strosin', '1997-05-25', 'eyJpdiI6IlFLZStHcWlPaTFockI5SmdObDR5TlE9PSIsInZhbHVlIjoiK3kxZm0wVmNSWVE5NmFldXhjb01sQ1ViSnhQTzZLbzhsUGFxK25ObUdkeFhNNW1VSnVJWDJXZVRIdXV5MkFFRiIsIm1hYyI6ImIwMzlmOTk5ZDA1MzM3YWQxNWVhZjQ0NGEwNGJkNGVmNmFmNmRhODYyMTg4NWZhNDdlNDZkNDMyZDUwODFjOGEiLCJ0YWciOiIifQ==', '082247994859', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('9701621342064988', '5138902707436241', 'Mallie Mitchell III', '2026-05-01', 'eyJpdiI6IjQ4UXg0cW1LNHVQRjBweFVVLzM4c0E9PSIsInZhbHVlIjoiRy9RVFJCdkRNV0ZrbnAxdkNtNHNRNitOcFJMRDlFV3N1cGI0RnluNk5GMkM3eEIwQXF1SzFDNFl0WGp3Si9GSyIsIm1hYyI6ImM0MDI3OTQ4NWNjZjRlMGM3OGVjYjhkMWU3MDU1Y2QyMTc4ZjBjYjllYTI2ZTUwNTIyZDE4OWJiN2FjMzdlZDQiLCJ0YWciOiIifQ==', '085802435497', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('9853662668487827', NULL, 'Flavie Hansen III', '1979-02-04', 'eyJpdiI6IjhnSHZ4TVlEL3B0MUFKUXUxKzZjOXc9PSIsInZhbHVlIjoiWmJMTzF4WFUyb3hkdXFTRGhjY0NsYmsvNEx2bTBnTENlbjRNYzZjRFZsQk9OK2tIYllZazhSdjdKTkdLMHBZYSIsIm1hYyI6IjUyODkzOWUzMmI5YmRjZjYxMDcyMjdlNmVhM2E3MjQyNWQwMGExMWI0MGVkOGNjYmFhODY2ZmNhMjU5OWJlN2YiLCJ0YWciOiIifQ==', '082592532922', 1, '2026-05-04 05:32:23', '2026-05-04 05:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `death_records`
--

CREATE TABLE `death_records` (
  `id` bigint UNSIGNED NOT NULL,
  `citizen_nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `letter_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_death` date DEFAULT NULL,
  `cause_of_death` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place_of_death` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FRONT_OFFICE_REQUEST',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regency_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `code`, `regency_name`, `created_at`, `updated_at`) VALUES
(1, 'Kecamatan Makmur', '320101', 'Kabupaten Tulang Bawang Barat', '2026-05-04 05:32:21', '2026-05-04 05:32:21'),
(2, 'Kecamatan Jaya', '320102', 'Kabupaten Tulang Bawang Barat', '2026-05-04 05:32:21', '2026-05-04 05:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `district_leaders`
--

CREATE TABLE `district_leaders` (
  `id` bigint UNSIGNED NOT NULL,
  `district_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `district_leaders`
--

INSERT INTO `district_leaders` (`id`, `district_id`, `user_id`, `name`, `nip`, `rank`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 'Kadin Simonis DVM', '197123225 19915 1 001', 'Pembina / IV.a', 1, '2026-05-04 05:32:21', '2026-05-04 05:46:27'),
(2, 2, 9, 'Mr. Robb Bins', '197899080 19914 1 001', 'Pembina / IV.a', 1, '2026-05-04 05:32:21', '2026-05-04 05:46:27');

-- --------------------------------------------------------

--
-- Table structure for table `domicile_records`
--

CREATE TABLE `domicile_records` (
  `id` bigint UNSIGNED NOT NULL,
  `citizen_nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','EXPIRED','PENDING','REJECTED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `letter_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `domicile_records`
--

INSERT INTO `domicile_records` (`id`, `citizen_nik`, `status`, `letter_number`, `signed_pdf_path`, `purpose`, `valid_from`, `valid_until`, `verified_by`, `source`, `created_at`, `updated_at`) VALUES
(2, '0548397596571350', 'ACTIVE', NULL, 'public/domicile_proofs/3/0548397596571350_1779290647.pdf', 'keperluan PNS', '2026-05-09', '2026-08-31', 6, 'VILLAGE_VERIFICATION', '2026-05-09 07:11:19', '2026-05-20 22:24:07'),
(3, '3721700552332154', 'ACTIVE', '123/232/saddsa/223/2026', 'public/domicile_proofs/3/3721700552332154_1779290666.pdf', 'dasdad', '2026-05-20', '2026-08-20', 6, 'VILLAGE_VERIFICATION', '2026-05-11 14:05:32', '2026-05-20 22:24:26');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `household_cards`
--

CREATE TABLE `household_cards` (
  `no_kk` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rt` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `village_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `household_cards`
--

INSERT INTO `household_cards` (`no_kk`, `head_name`, `address`, `rt`, `rw`, `village_id`, `created_at`, `updated_at`) VALUES
('1233791839629110', 'Green Stoltenberg', 'eyJpdiI6ImtFbEkwd3EvYTZsTlZoRlBOdU1WdWc9PSIsInZhbHVlIjoib1RxM05rNVVjejd2TFBpTDhpSTZPKzRNMFpFYjBBOUN4ZjB1MjZKOVdqMD0iLCJtYWMiOiI1OTg1Yzc1NTkwM2ViYzM1ZWYyZWExN2JkMDc2MDQzMzBiZjdkNzU5YTViODcyNmU4MDI0NmZmMDA1NDk4NjE4IiwidGFnIjoiIn0=', '021', '007', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('1660961957535760', 'Adriel Heller', 'eyJpdiI6ImNLZGVrRDZlaGhwUTNkN2kvbUFaZ1E9PSIsInZhbHVlIjoidVQwRnAyVVhiY1YrTy9zSWxBOXVkcEpUVjJwbXhTdW5LMW5MSTFjQjdSaz0iLCJtYWMiOiI3YTFjYzY1ZTgwYjM4Y2ZlYTQ5MmNiOTBmMjZkNmM5MTg2ZDRiZWY3OTBhOTRmM2FjNzhkZTI4MmQ0ZTBlMzI3IiwidGFnIjoiIn0=', '054', '054', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('2352183283459212', 'Gaston Schroeder', 'eyJpdiI6IlFWaGJMSU5hMnpocm4zQXBWVWZFMFE9PSIsInZhbHVlIjoiRTN0dVZuSFRaWHVSbjRwYjZuM2tSOXU2MDBCQzhQWS9rSlVXWHpnNzAvdz0iLCJtYWMiOiIyNmJmNDhiNmUyOWZiYzUzNjhkZDA2ZjVmOTdkYjgxMDYwYjA3NzFkN2MwMzVjYzJiNjkxODNhNzRkNTAxYjhhIiwidGFnIjoiIn0=', '083', '009', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3421205268285809', 'Markus Spinka', 'eyJpdiI6IlN5YzFWT1ZVODZpMFdKSjBkMFRWbXc9PSIsInZhbHVlIjoiN1dQOUJkNmtzcmw0YkVOQ2FPYTlLa21ac01yRHVrVEFPekk5TmdpeFZsST0iLCJtYWMiOiJiMGI1MjBhMmQ4NGM5YmIwMTUxNDVmMWJkYzZmODdkZDQ4NzhjN2E0YzNlODRkZGUxMjRkYzVlMjIxOGVjMjQwIiwidGFnIjoiIn0=', '098', '026', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3619504393732364', 'Jarrell Harvey', 'eyJpdiI6IjZVa24zZi83TEs0VUx0NDRDVDZPemc9PSIsInZhbHVlIjoiSnpMbSs2NFNlank0UFZGUlJwY0NqaUt4SlNKQWNkdFNCaGxZTU81L3BSUT0iLCJtYWMiOiI2MThhOGQ4NTViNmI4YzJiMTMxNmMxNWQ1ZTM0OWM2ZjY3NDM3OGU4OTMxZTIzZTIxYmI4MjdmZGY4NThlNGZjIiwidGFnIjoiIn0=', '022', '099', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3677277571821350', 'Norbert Zulauf', 'eyJpdiI6ImxTTWNIbFVBUSt4VFBTUDNlV2RDSmc9PSIsInZhbHVlIjoiRTh4OVZvUVlUc25lcDJhR0JFUUpLa1JqMzBXREZrZEZKVUxkWTBLdlFwRT0iLCJtYWMiOiIxYTc3ZTdhYWYxMGZiZGFjODU5NTU0ZjE4M2ZmNGFjZmYyNGM4ZjYzNDZiY2U1NDNlOTk5NGZmZTY5Y2ZhNjVkIiwidGFnIjoiIn0=', '049', '083', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('3790303525496946', 'Prof. Angus Keeling V', 'eyJpdiI6IjFQRG5HYWxiVWFrdTVtTlF0WnJjQkE9PSIsInZhbHVlIjoiZTJ1VjZEY1RBVnhyUTJBSHFIOXF2OG5KRnNiSWNrS2NYWTliTlRyWjFXND0iLCJtYWMiOiJhY2I3MjI4MWMxNGJhZDBiNjU2MGY2ZmQ3ODhlMDY3MDkxZDRkYjJjMzRkMWRiZTlkM2YyYTY4ZmRjYjJlNGI0IiwidGFnIjoiIn0=', '039', '011', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4118538042647206', 'Prof. Juvenal Smitham', 'eyJpdiI6Ik51b3N3anRPZG9GZ0x1VDlYNi9OQ2c9PSIsInZhbHVlIjoiSTBPb01BQXQyR2FHSkwrSklNOE8xNk9PbVlhZW5jZVFCT04wRW1rWDVYQT0iLCJtYWMiOiIzYTExN2Q0NjMzMWI3NzRlMTIxMTFmMGFlYWJhZDE5ZTgzYmY3ZDAyY2Y3YjhhNThlNzc1NTRkOGE4YjlhNWMzIiwidGFnIjoiIn0=', '072', '044', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4878045916240772', 'Mr. Antwan Welch', 'eyJpdiI6IitUaXJteXd0d1g1M003NUgzazkrUFE9PSIsInZhbHVlIjoiNzBRSnlNeVFId2g0QWZuSjRuVUV1ZGthQmNUa3JIRHozUW9vYVBDbWJ1ST0iLCJtYWMiOiI0YmIwZjhmZmQ1YzZmNDM4NGZhYzQ3NTMzOWRmMjEyMmY5Y2IxNzZhYjNmMWQwMzBmNjRkZGQ4ZDc2NjcyZWIwIiwidGFnIjoiIn0=', '091', '048', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('4978690671792204', 'Prof. Alexie Borer', 'eyJpdiI6IjJYVlVQRDJ1Tmc3d2NGNlNYTndHYXc9PSIsInZhbHVlIjoic0ZYL2duWDArbWlML24rOFhGL2xBbGpjT2EydXNrUkw3RHlreWF4eXZpZz0iLCJtYWMiOiJiM2FhNTQxYTZiYzhlY2IyZjgxMTA2NGNmOGM0MTM1NzljMzY0ZGYyMDQyZTgzMmNmMmVjODRlNjgzMjE4NWNkIiwidGFnIjoiIn0=', '089', '038', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('5001207486395518', 'Dr. Jo Lemke', 'eyJpdiI6IlBycU1VaERnVGJiR2tXa1dTdjZrTnc9PSIsInZhbHVlIjoiR2pUblp0Z0gwR0FtNU5GQmk3LzhsUzJ6dUlEbEtuRXBTV0ZYMXRGNGttaz0iLCJtYWMiOiI3YmRmY2I5NTc0ZTExNTM1ZDhjZDdjNTIwZmUxMmJiMGM2ZmUwYmFkMDEwMjk3MDcxODg1Y2E0ZmY4YTVhMDNiIiwidGFnIjoiIn0=', '033', '067', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('5138902707436241', 'Raymundo McDermott III', 'eyJpdiI6IkVWMGhaeFI0U3IycDJHeWdrZXRNelE9PSIsInZhbHVlIjoiOXoyTngvTHcxdVlCTytwWHNnMnRNa3Eya2QyMU5VeVBMd0xqYkhVWWFycz0iLCJtYWMiOiI2NDc0MGJkZjBlOWI2NzBlYWRmODNlNTU0ZWJkNDJhYThiYTc3YjZhZWQ5MjFlZmRhNWYxNDA2NDRhMWM1OGU4IiwidGFnIjoiIn0=', '067', '093', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('5612773451825704', 'Jamil Nikolaus', 'eyJpdiI6IkxqcTY3R3VQbi81eTVsa2J3MDduVFE9PSIsInZhbHVlIjoiWVU3bmxOK0I2UFkrOEcvYzVxRUMvSld0cC9sSFFqbHNZU0E2WCtJNnFsbz0iLCJtYWMiOiJiNjZjZWZlMDViNjE2M2UxOWVhZTYzZTBmOWJmNzRkZGM0NGNlYjg4YTE4NTA2MmU3ZmNmNGRhY2YxODc2OWNmIiwidGFnIjoiIn0=', '055', '001', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('5813416891243847', 'Dion Hodkiewicz', 'eyJpdiI6IkdOWXZXQ2pML21wdG9YcE55bHVKTkE9PSIsInZhbHVlIjoiQmhvZ1R1NHNiOEh0OVVESjRCTjVMdjRIemxpem5NWjRzM2dVVkRoTUU5Yz0iLCJtYWMiOiJjMDU1ZjVmZjFlMzc1OTM2MmJkYjc4ZTY3MDA3MTAyN2IzNTUzZGM3YTNhODFhMTA1ZTE4Njc2MzM1NTM4NmM1IiwidGFnIjoiIn0=', '056', '014', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('6969058683899311', 'Guiseppe Kuphal', 'eyJpdiI6ImR3ZlZGM0hoOWpia3IxRjAyY2xZbFE9PSIsInZhbHVlIjoiRXRSR25XTUhYUFp2QkxOVHRKcWttSDhYRm5HczNhc3VwNE9Oc1FZQjJTQT0iLCJtYWMiOiJlMDQ0NWVlMzUzYzliODZhNTNiMThjOTBmOGY2MWYxN2MyZWUzMGJhMzI0NzAxMWJmNmFlN2ZlOTM0Y2FkNjM2IiwidGFnIjoiIn0=', '015', '033', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('6988051632499646', 'Dr. Theo Schultz PhD', 'eyJpdiI6IlFhclFhUVNzUml5QkY5eEpMK0xUNnc9PSIsInZhbHVlIjoiUERhejRjdU0rWFBtU1NEV0tmeHpjOHpTVjVqWGJOemU3eEZEQ1RvL2NZVT0iLCJtYWMiOiI4ZWIwYmM4NTk5M2M3NjFiYTdiZjIzYzU2YzJlMTU0ZDJlZTg0M2Y2ZmY2ODE2ZmRjM2NmZDFlYzgyOGM1ZjY4IiwidGFnIjoiIn0=', '007', '023', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7075753455854325', 'Emile Prosacco', 'eyJpdiI6Ikc4RXl3UHNuTmlsRTk5UUpFWndQZ1E9PSIsInZhbHVlIjoiOHB1Z242MnBoQTNXQnFnZ2lFNzZtckk0dnBiSVJ2c2FTT3JHOWlkWEVkcz0iLCJtYWMiOiI2M2IxZTFjZmNmNGE4Njg2NGRjODUzZGJhMTk1OWMyMmMyZGZkZmEyNDNmYzdiMzAwM2JjOTEyOWQ3OGUwYjgyIiwidGFnIjoiIn0=', '011', '045', 2, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('7873743606870620', 'Jabari Reilly', 'eyJpdiI6InB0YWVwblR3b0dQSXVZZnNCQjlBbUE9PSIsInZhbHVlIjoiWmdTcVZTOThJamU0djIvbUNlZXBGWk1JMHZMUE5VS0c2Q2FTMVhnM1lJND0iLCJtYWMiOiIyMGExNzE1M2U0YmIzOWFkM2U1NTU5MDBiZGVhY2Y3ZjkwYzBlNWY0ZTdjM2Y1NDdhODY0MmVmNzgxMjVlMmQ3IiwidGFnIjoiIn0=', '052', '024', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('8252925377204770', 'Erling Howe', 'eyJpdiI6IlljWXcraXROa0xtMXlDY21iWm9zclE9PSIsInZhbHVlIjoiNXJ0bEpEcElZQnROYWxzYTBkd0ZJQ3B0Yy93TXpabmZNOHlUSkVwWDYvTT0iLCJtYWMiOiJhZDkyZjFlMDk3ZTFmNzNhYzEzN2UzNzc3OTZjNWNhMzc5OWQ4YzJjZjMxZDc0MjIzZDVlNjY3MjU3ZjdmMzMxIiwidGFnIjoiIn0=', '048', '047', 3, '2026-05-04 07:36:39', '2026-05-04 07:36:39'),
('8958937323630079', 'Leonard Lockman', 'eyJpdiI6IjhtVjFqenB0MUUzVXhrOGE1bm5TbkE9PSIsInZhbHVlIjoiQ3FObmZDQXVkVUczYzlwRzlOdVQyN2ZUZlVYYVpUbFpHeisvdUl5L0dPcz0iLCJtYWMiOiIzNzViZDUwOTY3OWUzZDQwOWZkZjI4N2YzMjk5NDkyZTU0MGRiZTBmZGJmM2FjY2MzZGQzYmQ5ZTkyYWJlMDUzIiwidGFnIjoiIn0=', '080', '058', 1, '2026-05-04 07:36:39', '2026-05-04 07:36:39');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_04_26_075636_create_sessions_table', 1),
(2, '2026_04_27_000000_init_schema', 1),
(3, '2026_04_27_050828_create_districts_table', 1),
(4, '2026_04_27_050828_update_villages_table_add_district_id', 1),
(5, '2026_04_28_130048_change_target_id_to_string_in_audit_logs_table', 1),
(6, '2026_05_04_045230_create_user_certificates_table', 1),
(7, '2026_05_04_052428_add_district_id_to_users_table', 1),
(8, '2026_05_04_052953_create_village_leaders_table', 1),
(9, '2026_05_04_052954_create_district_leaders_table', 1),
(10, '2026_05_04_061055_add_signed_pdf_path_to_poverty_records', 2),
(11, '2026_05_04_073215_create_household_cards_table', 3),
(12, '2026_05_04_073231_add_household_card_id_to_citizens_table', 3),
(13, '2026_05_08_061240_make_poverty_records_columns_nullable', 4),
(14, '2026_05_08_064048_add_service_management_permissions', 5),
(15, '2026_05_08_070139_add_expired_to_service_requests_status', 6),
(16, '2026_05_08_091926_add_rejected_status_to_poverty_records_table', 7),
(17, '2026_05_09_060920_create_domicile_records_table', 8),
(18, '2026_05_11_030535_create_move_records_table', 9),
(19, '2026_05_11_051205_create_death_records_table', 10),
(20, '2026_05_11_141845_create_arrival_records_table', 11),
(21, '2026_05_19_194124_add_letter_number_to_records_tables', 12);

-- --------------------------------------------------------

--
-- Table structure for table `move_records`
--

CREATE TABLE `move_records` (
  `id` bigint UNSIGNED NOT NULL,
  `citizen_nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','EXPIRED','PENDING','REJECTED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `letter_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_address` text COLLATE utf8mb4_unicode_ci,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `move_records`
--

INSERT INTO `move_records` (`id`, `citizen_nik`, `status`, `letter_number`, `destination_address`, `reason`, `signed_pdf_path`, `valid_until`, `verified_by`, `issued_at`, `created_at`, `updated_at`) VALUES
(1, '0548397596571350', 'ACTIVE', NULL, 'dasdads', 'aaaa', 'public/move_proofs/3/0548397596571350_1779290639.pdf', '2026-06-11', 6, '2026-05-11 04:50:36', '2026-05-11 04:00:41', '2026-05-20 22:23:59'),
(2, '3721700552332154', 'ACTIVE', NULL, 'pindah desa dan kab', 'pindah desa', NULL, '2026-06-11', 6, '2026-05-11 15:01:31', '2026-05-11 15:01:12', '2026-05-11 15:01:31');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Verifikasi Status Layanan', 'service.verify', 'Akses untuk memverifikasi status data dan dokumen warga yang mengajukan layanan.', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(2, 'Cetak Bukti Verifikasi', 'service.print_proof', 'Akses untuk mencetak bukti verifikasi layanan sebagai dokumen resmi.', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(3, 'Lapor Penggunaan Layanan', 'service.report', 'Akses untuk melaporkan penggunaan layanan atau mengajukan permintaan verifikasi warga.', '2026-05-08 06:42:03', '2026-05-21 23:17:19'),
(4, 'Kelola Data Warga', 'citizens.manage', 'Akses untuk mengelola data kependudukan warga termasuk tambah, ubah, dan hapus.', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(5, 'Lihat Log Audit', 'audit.view', 'Akses untuk melihat log audit aktivitas sistem termasuk riwayat perubahan data.', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(6, 'Kelola Sistem', 'system.manage', 'Akses penuh untuk mengelola konfigurasi dan pengaturan sistem aplikasi.', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(7, 'Kelola Pengguna', 'users.manage', 'Akses untuk mengelola akun pengguna termasuk tambah, ubah, dan nonaktifkan.', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(8, 'Kelola Role & Hak Akses', 'roles.manage', 'Akses untuk mengelola role dan hak akses pengguna dalam sistem.', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(9, 'Kelola Data Desa', 'villages.manage', 'Akses untuk mengelola data desa termasuk tambah, ubah, dan hapus.', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(10, 'Kelola Data Kecamatan', 'districts.manage', 'Akses untuk mengelola data kecamatan termasuk tambah, ubah, dan hapus.', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(11, 'Ekspor Laporan', 'reports.export', 'Akses untuk mengekspor laporan data dalam format yang tersedia (Excel, PDF, dll).', '2026-05-04 05:32:21', '2026-05-21 23:17:19'),
(12, 'Kelola Permohonan Layanan', 'service.manage', 'Akses untuk mengelola (menyetujui/menolak) permohonan layanan yang masuk dari desa.', '2026-05-08 06:42:03', '2026-05-21 23:17:19');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(1, 2),
(4, 2),
(12, 2),
(1, 3),
(2, 3),
(3, 3),
(5, 3),
(5, 4),
(11, 4);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poverty_records`
--

CREATE TABLE `poverty_records` (
  `id` bigint UNSIGNED NOT NULL,
  `citizen_nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','EXPIRED','PENDING','REJECTED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `letter_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `income_range` text COLLATE utf8mb4_unicode_ci,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `poverty_records`
--

INSERT INTO `poverty_records` (`id`, `citizen_nik`, `status`, `letter_number`, `signed_pdf_path`, `income_range`, `valid_from`, `valid_until`, `verified_by`, `source`, `created_at`, `updated_at`) VALUES
(1, '8677645003235905', 'ACTIVE', NULL, 'public/poverty_proofs/3/8677645003235905_1777909006.pdf', 'eyJpdiI6IjlEODh0ZURvWlBUL3hsOTdmK1JXdkE9PSIsInZhbHVlIjoiMzZFcjJjWVpYR0sxejlzUUtEdzRYdz09IiwibWFjIjoiZmUyYTNiMmI4OGMwYTM1N2IyODVlNGZjNGI5NzEwMjMzZDdiNzgwM2UxMTMxOGI2YjdjNTRhODdhOGFkNTBkZCIsInRhZyI6IiJ9', '2026-02-13', '2027-05-09', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 15:36:46'),
(2, '8677645003235905', 'EXPIRED', NULL, NULL, 'eyJpdiI6IjVzYzZ1QUV3Rmp1MmI5SmJBL2hiOWc9PSIsInZhbHVlIjoiTUkxMVdRRmU3bVhJNGFrajJtOEx1QT09IiwibWFjIjoiMDc1M2NlNzAyNDBiYTYwMzFmODZhZGY0MmFmYTRiNmNmZmZkZDRmM2ZmYWEyNzY4ZDJlZmE3OWE5NzFjODVjZiIsInRhZyI6IiJ9', '2025-07-15', '2027-09-28', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(3, '5242463576709867', 'PENDING', NULL, NULL, 'eyJpdiI6InQzbTRGTVliZ1JRZVlLUy9pd08rQXc9PSIsInZhbHVlIjoickNiMGgzVnZRRStFQmJPRmo1K01VQT09IiwibWFjIjoiZDgzM2MxNDRkZjUzMzI2ZWI1YTY3MmNiZTBhMmM3ZWE0ZGI5MTYyNGJmYjRjNTk0OTY1YTJjMjk3M2E4MWM4ZiIsInRhZyI6IiJ9', '2026-02-08', '2026-09-25', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(4, '5242463576709867', 'ACTIVE', NULL, NULL, 'eyJpdiI6IkEyckdrcUFaM25BRk9wcDU4dUxtanc9PSIsInZhbHVlIjoiRGEwNVgwcFRDZWtYYUYxdHN3NEpTZz09IiwibWFjIjoiM2FmNDJiNmE4YWU4MDM5OTJkMmEyNGY2ZjViN2U0MWIwMDlhN2RjZDIzYjgzNmZlNjQ4OTIwNTI2ZmNhZjgxNyIsInRhZyI6IiJ9', '2025-06-09', '2028-02-16', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(5, '3780862829786626', 'ACTIVE', NULL, NULL, 'eyJpdiI6IkM4Z0VnTWZWMXFzSnd3OUc2anJTYlE9PSIsInZhbHVlIjoiQzdvL3ZsNEhGd1IyT2wzL1RqOEs1dz09IiwibWFjIjoiOGI4MjEzMTAwOTNmN2JjMjZjYTVlMWRkZmUzZjY1Y2UwM2QwZDFlMzkzOWU3ZTAzOTI3NDU5MGJiZmIwY2FiNyIsInRhZyI6IiJ9', '2025-08-30', '2027-10-09', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(6, '3780862829786626', 'EXPIRED', NULL, NULL, 'eyJpdiI6IjFrTXczaWRRV1NYQTM0YjlqZlJGd0E9PSIsInZhbHVlIjoidnVMM1BKWndBSUVaaXZKbmkvVnQwUT09IiwibWFjIjoiYjA2M2NlZDFiYzNjMGY3ZjIxNzIwYzYwMjlhZGQ2YzhmYWJmZTE1MmYwN2FmZGMwNzY3OWM0ODZjNjI5ZGRhNSIsInRhZyI6IiJ9', '2025-05-23', '2027-09-23', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(7, '7424022564701548', 'EXPIRED', NULL, NULL, 'eyJpdiI6IjFEbG1JS3hYZWdjb2NRd2Ezd2VVM0E9PSIsInZhbHVlIjoiZlo4a1hEeVdOYVV0UDRncC9pRzArQT09IiwibWFjIjoiOTAxY2M5OTkwZjFhYTczMTZhZGNkMWVlNTBkYTQ3NGZiOTRkNTU2ODZkMzVjYmNjNmFmMGU2MTRmY2EyMTZjNyIsInRhZyI6IiJ9', '2025-05-07', '2026-09-18', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(8, '6908665213825078', 'EXPIRED', NULL, NULL, 'eyJpdiI6IlBEM0xpZDlpNmhQTmxGZHNaNWY5UUE9PSIsInZhbHVlIjoiZCtuL09UV2QrUThZTlg2VGlCVTdCZz09IiwibWFjIjoiODcxY2QzYzQ0Mzc4ZWZlMDAwMjJhNDVlODRjYmNhMjI4YjRkNTRhMzlmMDQ5ODJiODVlMTZlZGQwYmNkODRiNSIsInRhZyI6IiJ9', '2026-01-31', '2028-03-10', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(9, '4381799045319171', 'PENDING', NULL, NULL, 'eyJpdiI6IjFhOUFtQ05lVFBSek53WTZJM09lUlE9PSIsInZhbHVlIjoiU0NNdzNPTXU5dWdKWlpSNUZIOWs0QT09IiwibWFjIjoiNGQ4YWIxZTM1MWMxODVlYjYzNzA5ZTgzNWU0MmU3NDQwNzE2NWUyNmEyMTJjNWUwMDYzYTM0ZDUwOTk1YzBiYSIsInRhZyI6IiJ9', '2025-11-18', '2026-07-14', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(10, '4381799045319171', 'ACTIVE', NULL, NULL, 'eyJpdiI6ImVUdTA5dFhlWG9DdnRHd3hkUVoycnc9PSIsInZhbHVlIjoiYTM4RXVxR0szcDZRRXdGb0JwZzF6QT09IiwibWFjIjoiYTMwYzRhMWExZjcwNGYxMWQwODA5OTAyMmJhYmZmNWZmYWQyZjNlNzIxNzVkZmIzMzk4NGY3MGYyMjQxMGJjMSIsInRhZyI6IiJ9', '2025-09-27', '2027-06-06', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(11, '5879810478829248', 'PENDING', NULL, NULL, 'eyJpdiI6InBCNy9idUl1dUF3SXU5V2xvT2R3RlE9PSIsInZhbHVlIjoiRVdTL2d6enk4elRsb3p4UFE4enJ4dz09IiwibWFjIjoiMDdhM2I1MTdjMzk2YzllZTY3MWZiZTNiMWU1ZmQxNmFkNjJkMTUyMTg0MTYyYjI1YWY0MDM0ODA4NGFkMjkyMyIsInRhZyI6IiJ9', '2025-12-08', '2027-10-04', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(12, '5262394477764231', 'PENDING', NULL, NULL, 'eyJpdiI6ImYwYWt3UHdKOG5EK1FIenZleFU1T1E9PSIsInZhbHVlIjoiT25aUDl4bXRjUlRFZ1dNTnJSNy9ZUT09IiwibWFjIjoiYTkxNDY4MjUyYjM4OTk2OGVlOTcyOGM5NTdhOTE3NDQwMmJkMDA1MzRiYWJmMGJlYmFmY2JlMjIwYWYwNzQxNSIsInRhZyI6IiJ9', '2026-03-26', '2027-07-21', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(13, '5262394477764231', 'EXPIRED', NULL, NULL, 'eyJpdiI6ImxwMlNpZXBxbFU0MlE5UGYrY1l3S3c9PSIsInZhbHVlIjoiQ0IySGdtd3BaS3BxdkdEdDB0c1Nqdz09IiwibWFjIjoiOThjNzViZjJhYjdhNDgzZGQyMTdmYjJkOGZiOWRkNDA1NzRiMzgyYmEzODE0MzU4ODg4OGNkOGM0Y2M5NDZkZSIsInRhZyI6IiJ9', '2025-05-05', '2027-03-19', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(14, '2945329526956887', 'PENDING', NULL, NULL, 'eyJpdiI6IkVWcmZnVFJySGZNYWFkeWNZRW95T2c9PSIsInZhbHVlIjoia21EMkJoOE81MXllZ0sycEc5d2VGQT09IiwibWFjIjoiZWNiOThmYzFhN2M0M2FkNzBmNGJlMzgxNmJiODY1OWZlNTY2MjE2MWUxMDhkNTMwZmFmZWNlMzk5Mzg2NzEwZSIsInRhZyI6IiJ9', '2026-04-27', '2026-08-30', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(15, '2945329526956887', 'EXPIRED', NULL, NULL, 'eyJpdiI6InNuU3V0VUdEUDBHK2I2KzBBWmRjS2c9PSIsInZhbHVlIjoiNlFiamk0OHRkUDJEb0NZSUlCWjh2Zz09IiwibWFjIjoiYzhmZDg4NmRjZjdhMWYwYTA2NTdiOTg4YTUyNDgyMzJkYzMxOTU3MmJhYTA3ZTEzMjYzOWMyZjZlZDJkYjMzYiIsInRhZyI6IiJ9', '2025-10-18', '2026-11-12', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(16, '6913960499989309', 'PENDING', NULL, NULL, 'eyJpdiI6IlQ0SHEzNzdYREhzUzNHWHR1S0taM1E9PSIsInZhbHVlIjoiK1hKajM3NGRVbjVSZk8vUmNSSGJ5UT09IiwibWFjIjoiYzgwMzFmMGJhYTJhNDQzZjIwZGJiNzEzZDExOTBkMmNhZGM4NDc3Mjk3MDlhOGJmZmMxNjQxZjkyODY3NWU0MiIsInRhZyI6IiJ9', '2025-05-17', '2026-11-30', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(17, '1716095046254527', 'EXPIRED', NULL, NULL, 'eyJpdiI6Im1IdXpzSW5ucnY4djFBQkcrZi9ENkE9PSIsInZhbHVlIjoiRVhZNVFuSDh6eGpSZXVOMnRCQ014Zz09IiwibWFjIjoiNzFmNDM3NWY0MzA4NmZmNjkyNzE4MmI0ODM5N2EyYTExNDkzMDgyNjk5ZjZlNjk4NzM5NjU3ZjU1OTE3NmE2ZCIsInRhZyI6IiJ9', '2026-03-18', '2027-10-16', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(18, '2826556909042545', 'ACTIVE', NULL, NULL, 'eyJpdiI6ImZ0Z2ExQlR4ZklzMjdIODNSQjV3b0E9PSIsInZhbHVlIjoiQnJ0RThGVEdkNTROMVRwb1AzTFZtdz09IiwibWFjIjoiMDQ1MTFiNmNhOWVmZjRjN2FiYzBmNmM4ODQ2NmY0YTE3ODQ5N2JmYWY3YWIzZTk2YjJiODMyMDllZDcxMzg1OCIsInRhZyI6IiJ9', '2025-06-16', '2027-04-22', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(19, '8074387465060152', 'PENDING', NULL, NULL, 'eyJpdiI6InBXcTR1Mm1yWXc5cnh6bjE0VzE2QVE9PSIsInZhbHVlIjoiVEFSeEhoUGZ0blZweUZzeDhsdmpYdz09IiwibWFjIjoiNWQ4ODQxOGViOTVkM2ZiMjBhYzhjM2I5YWUzYTEyOTljMjlhMjI2N2RjZWUzMWU3MTNjZGNjMTY0MmE3NGY4MiIsInRhZyI6IiJ9', '2025-12-13', '2027-02-18', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(20, '8074387465060152', 'EXPIRED', NULL, NULL, 'eyJpdiI6IjBrTkhER1RYeUdkQ1g1ZFcvUDJtSWc9PSIsInZhbHVlIjoiU0YxdkFvZFZlMFpxbVltU2ljSXFhZz09IiwibWFjIjoiNzk3YTdlNTBmZTg5ZTBjOWI0YWRlNzI4NWQ5MjQxNjY2NGE5OTYxMjIxNDI3OGE2OTc4YmU5YWJkZTM1MjExZCIsInRhZyI6IiJ9', '2025-10-24', '2027-02-02', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(21, '4096333497694141', 'PENDING', NULL, NULL, 'eyJpdiI6ImxnbU80cWZqRERzTGtGclVSbm94QkE9PSIsInZhbHVlIjoieGNQV2JuYzRaYzN6cUtPdjMwNHlVUT09IiwibWFjIjoiMDg2NjRkZmMzZjVkN2ExMGJhZWViMzA3YTI1YTY5OWJhNWRhYzQ2NWNiZWEwNjQ2NDBmOTk1NDgzNGQ5YjEwNCIsInRhZyI6IiJ9', '2025-11-19', '2027-08-21', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(22, '4096333497694141', 'ACTIVE', NULL, NULL, 'eyJpdiI6Im15OGxqVmQxQi96Y05qamJJZmNpUnc9PSIsInZhbHVlIjoiRkN0eS9ncnRWYldEeVJyVEZvUzlIUT09IiwibWFjIjoiZjUxZThhMWMwYTIzMGZjNzU5ZTJmMGMyYTMwNTEyZmM0YWM4MzU4NGNmMGU4MzI2ZGU5Yjc2OTAxNmIyYTU2NyIsInRhZyI6IiJ9', '2026-03-05', '2027-07-07', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(23, '0221137620576019', 'ACTIVE', NULL, NULL, 'eyJpdiI6Ik8wM0VpSTZqYzZoVlFtSEFXV096SVE9PSIsInZhbHVlIjoiczROQlIyaHRGSkx3Z3dnWTNaR2NUQT09IiwibWFjIjoiYWI4ZWU2ZjIyMjkxZTUwNGFhZjY0ZmUyYzNlMTFhNTdjODAzZDY4ZmUwMTc4YjZlMWJhOTBiNDFiZDY3MTJkZCIsInRhZyI6IiJ9', '2026-03-03', '2027-01-31', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(24, '0221137620576019', 'ACTIVE', NULL, NULL, 'eyJpdiI6IjI0WnptSy9tUCtFZ2tKSzhKSiticGc9PSIsInZhbHVlIjoiT3ZQa0o4LzhkbDk1TzA0OFFQaDZLZz09IiwibWFjIjoiMTQ4NzM0ZDQ5YzkyZTA5Y2E1YTA5NGVlODJiOTdjM2FhNDFkM2M1ODkyZGMwYjc2NzFlNzI1MmY5YmRlYzc5OCIsInRhZyI6IiJ9', '2025-11-30', '2026-10-06', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(25, '8177263444040736', 'PENDING', NULL, NULL, 'eyJpdiI6ImRTOHdHZmlTWkxsOFJORzRVQjdGQmc9PSIsInZhbHVlIjoiTnRrTGVBSnpvTnBlN2ljbVF5aGowZz09IiwibWFjIjoiYzkxNmI0NDBlYzRjNGYxODkyMzY0ZjgwNzY2NmEyM2QwMmU4ODZhYTE1ZGVhNDVkODA1YzFmNmY0MWU5NWU4ZSIsInRhZyI6IiJ9', '2025-12-22', '2026-12-07', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(26, '6583712080202361', 'PENDING', NULL, NULL, 'eyJpdiI6IkFVNEx6TjdMa3o3OHRUTWdyOVRPS2c9PSIsInZhbHVlIjoiR05mTjRST2dzYjd5RTNJRGJZV1I0Zz09IiwibWFjIjoiODkzOTk3ZjdkNzQ5OGU3M2ZhNzIyODQ2ZjliMTJmZWM0MjBlMWE3N2UxYTFhZWFiNTM3MjRlYzcyMTYxMWJjMyIsInRhZyI6IiJ9', '2025-06-11', '2026-10-13', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(27, '6583712080202361', 'EXPIRED', NULL, NULL, 'eyJpdiI6Im9odloxOUtNMzlSVmF4dVFFOE1wY2c9PSIsInZhbHVlIjoiWm9WMjdIMG4rV2tGNkRsTE1NUkRyQT09IiwibWFjIjoiN2MxMjc5NmQ5ZjNkMGVlNDdhZjZmNDkyMjI5YTVhOTY2OTk1ODZjMjhhZDg1MTc2YWM4YTUzNWFlNzdhMTkzMyIsInRhZyI6IiJ9', '2025-11-23', '2026-12-05', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(28, '3721700552332154', 'ACTIVE', NULL, 'public/poverty_proofs/3/3721700552332154_1778483072.pdf', 'eyJpdiI6Ii9ycmhIWm1LK2VxL3JmNk9EYVE0eXc9PSIsInZhbHVlIjoiOVJVa0IzWTlGMTB0aG1lc3FjZC9uUT09IiwibWFjIjoiNDU0MTg1ZDExNWMzMThhYzJkZWRiMjhjMzg2Zjc5NGIzNjE5MmJmN2ZkMGFkNmViYzdmMjEzNzdlOTcyMzA5NCIsInRhZyI6IiJ9', '2026-03-07', '2027-07-01', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-11 14:04:32'),
(29, '3023369112029953', 'ACTIVE', NULL, NULL, 'eyJpdiI6IjNWdmlreW8vdzd6T3dVbStjc3RyVGc9PSIsInZhbHVlIjoiZGNBL1F4RzE1aFk5NlBiK2l3S2REZz09IiwibWFjIjoiZjFkMzI2NWQzMTg1Njg5MTdlZTgwMTI0NmI3NjEwZWQ0NTk1MWIyOGQ4Y2Y1YjNhZGQ5NDc0Mzc1ZTk4MWQyYSIsInRhZyI6IiJ9', '2025-09-19', '2026-08-19', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(30, '9051445144286473', 'EXPIRED', NULL, NULL, 'eyJpdiI6Ikw4NExZa1pJL1lOREhPMm05WlRVR3c9PSIsInZhbHVlIjoiUWZHOXRjZTFTcDdWa2ZLQkhSYjB6Zz09IiwibWFjIjoiMjBmOWQ5MWRjYjhhY2M2ODhjZWYwYjk5YTI0NDg3NmM2MmNkYjk0YTAyOTUwYjMwOGQ3Mjg3YWQwNTUyNDA2YiIsInRhZyI6IiJ9', '2025-11-24', '2026-06-22', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(31, '4741704571918545', 'ACTIVE', NULL, NULL, 'eyJpdiI6IjRJcHF3SnkwNVhFUng1aWtVcCtOVmc9PSIsInZhbHVlIjoiSDdXYXJCV29NYXozL1d0UG1OOW1VUT09IiwibWFjIjoiOTAyZjU1ZmE5MGY5MWJkNDMxMTNmN2EwNGNjYmY4ZDUwZDU2MmEwNmY4MTIxNThhYTkzMmRjODY1YWE2M2VjOSIsInRhZyI6IiJ9', '2026-01-16', '2027-11-26', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(32, '4741704571918545', 'ACTIVE', NULL, NULL, 'eyJpdiI6InYxSWM4TzdGNXJnQmpEL2RMUS9YZWc9PSIsInZhbHVlIjoiaGlnWDJ5UmFsVFRDOEczdHhIMFRCZz09IiwibWFjIjoiNTAzZTg3ZmFmZWNiMThmMzI4Yjk3NzIyZjcyOThhMDhkNmUyMTJlMGYyMDUyNWM1NGE3ZmJmODQzMjIwOTJlZCIsInRhZyI6IiJ9', '2025-12-21', '2027-09-06', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(33, '2065707815076781', 'PENDING', NULL, NULL, 'eyJpdiI6IlRDTkpkcVVBejhETzlCL05ZaStqblE9PSIsInZhbHVlIjoidnVMVmcwUVppc2h2dlBFc0FycUQ4Zz09IiwibWFjIjoiZDcxMmIxNDM5MTMwZDdhOWYxY2QxMmZiZDgxMDNlZTIxMDU2MzZlOWM4MGQ3NTFjOTc3ZDUyZTY0N2NlM2MzZSIsInRhZyI6IiJ9', '2026-01-01', '2026-12-17', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(34, '2065707815076781', 'PENDING', NULL, NULL, 'eyJpdiI6IlcvSEVtYzlTOUpOUlZxRHo5VTQwc3c9PSIsInZhbHVlIjoiYzUyanREaFpDWTZ2eXRaS0J4WTIrQT09IiwibWFjIjoiZDVlOWM1YWExMGYwNzEzM2RiZDY1NjdiMGI3ZDJiMTg0NGZkNmZkMTAzNjg0ZjgzMmRkMjc2Y2U5MTQ2YzRmYiIsInRhZyI6IiJ9', '2026-03-06', '2026-10-07', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(35, '0003485827010970', 'ACTIVE', '123', 'public/poverty_proofs/3/0003485827010970_1779379796.pdf', 'eyJpdiI6Ijcya056bjFPT3VJMi9IQlVoRVFvSUE9PSIsInZhbHVlIjoieUVwWnlwN2FZaTVtY1FqVjJySllNOTdpVXNvZUcvRlgvVzhyQjFjeXJaTT0iLCJtYWMiOiI4MzhiMDEyMDRhNDYzMzhlZDkyZDE0ZDYzOGM4MDIxYjk3NjAwNzUyMTQ4YTQwOTA4ZDBmNWNlMmM0ZWNkMGIxIiwidGFnIjoiIn0=', '2026-05-21', '2026-11-21', 6, 'VILLAGE_VERIFICATION', '2026-05-04 05:32:23', '2026-05-21 23:09:56'),
(36, '6595145129658715', 'EXPIRED', NULL, NULL, 'eyJpdiI6IldLaXNzWDlKSXZ2T0c1QnlyZG02S0E9PSIsInZhbHVlIjoiTS9CNm1UQlNkUHFRUW05dllVZHJMQT09IiwibWFjIjoiYTcwMDcwMGYxNDhhYjA5MjcxZDJkNWZkZGJlNzMxZTgwNzdiMmQ3ZDkwZjhmMDk2ZTU4NDQzYTk1NGFjZTU3NSIsInRhZyI6IiJ9', '2026-01-23', '2027-06-14', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(37, '2811476912538260', 'PENDING', NULL, NULL, 'eyJpdiI6ImE4cE5Lbi8zUEc3TzNsVHF3c242bkE9PSIsInZhbHVlIjoiK3VQTWx1WWtTdktTOHFnYzZQNDl5UT09IiwibWFjIjoiMmFmY2I0Nzc0NGI4NjdhMzY0M2RhZTUxYjBhOTNhM2Q4NjFkNmZhYjBhN2YxZjMwMjQ2NTFmYzYwM2EzNTBjNCIsInRhZyI6IiJ9', '2025-08-11', '2027-08-20', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(38, '2811476912538260', 'EXPIRED', NULL, NULL, 'eyJpdiI6IjJzVXgvcS9OajJwZ3lzcTZYL3N0VHc9PSIsInZhbHVlIjoiWE5Fd2g3aEJyak5QRlpscGkwTkhpQT09IiwibWFjIjoiMzU3YWRhODkzN2JiZDQ3NWViYmE1NDlmMmRiODFlZDIyZDdiNmVlYTY2ODA1YjI2MjliNzgwMzkxMTU1M2NjNCIsInRhZyI6IiJ9', '2025-11-21', '2027-03-14', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(39, '2801754690781515', 'ACTIVE', NULL, NULL, 'eyJpdiI6IlBpdVNoanFhdTBBS3JFV2xJMEZoVHc9PSIsInZhbHVlIjoiZFdTZnk0bHZwaGlydGpPbDFUanl3Zz09IiwibWFjIjoiZmI4MTlkNjBmOGRlY2FjNzJhNGI3OWJhOGY3NDc0ZWM0NWZmMDNlMzRlY2NkNGVhZGM4ZjhiNThhMzA0ZWE2ZCIsInRhZyI6IiJ9', '2025-10-19', '2026-11-15', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(40, '2801754690781515', 'ACTIVE', NULL, NULL, 'eyJpdiI6InNsN2REbTU2cHA2VWVjQ2JJSytlTVE9PSIsInZhbHVlIjoiMHVGWmpzUGpqa2JzczdYQW4wZDRldz09IiwibWFjIjoiYWIxNzRiNzgxNjc3ZDhiZWFlODA4M2Q3YTQ1MThiODkxYzM3ZGFhNzFlZjYzN2U2MmRkODQwMjMwMDA2MzdiNSIsInRhZyI6IiJ9', '2026-02-13', '2027-12-04', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(41, '0125368268471489', 'ACTIVE', NULL, NULL, 'eyJpdiI6Ik4zcWttTVYrRzJmNjBTaUlQVCtwM3c9PSIsInZhbHVlIjoieVQzc3F2T1NtbEZRZmNqdHF0WS9CZz09IiwibWFjIjoiMDk2NWIyYzYwZTY4NDg4NzFmZWQwZDRmYzAxMTM5ZTFkZDY3NWJhMDk4YzY0ZWMxMzEyM2MzNWI4YTQyNmM4OSIsInRhZyI6IiJ9', '2025-12-11', '2027-05-30', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(42, '0594521673981587', 'PENDING', NULL, NULL, 'eyJpdiI6Ik5KZWdYbU9CTEVJTy8zZ094RCtwanc9PSIsInZhbHVlIjoiL2hFanlaM1V2MHlGQ3pKdDZDQmdVUT09IiwibWFjIjoiYmY4MjE0N2Y2YzQyNWNmMTBlMzY2Yjc1OWMzN2JmOGU0ODViMDM2MWMxMjcxMmMwYjRiMjI2MWE0Y2I5MGM1MSIsInRhZyI6IiJ9', '2025-10-05', '2026-06-06', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(43, '7585599281379407', 'ACTIVE', NULL, NULL, 'eyJpdiI6Ii9PekhlS3JxTmVkRXJHS3BjUm9FNmc9PSIsInZhbHVlIjoiQTk5d0xwQWtPZXpHeWcrV3N6WTdkUT09IiwibWFjIjoiZDg2Yjk4NzE2NzJiNGFlZTczMTU5NTNhOWViMTk0NTk4ZDc1YjE2OGIwYjM5NjI0ODBiZWQ1ZTg3YzRiOTNkYiIsInRhZyI6IiJ9', '2026-01-25', '2026-07-11', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(44, '9064018936887419', 'ACTIVE', NULL, NULL, 'eyJpdiI6IkZzd2QrYlJ5SzhwakhieW5pYnlMakE9PSIsInZhbHVlIjoiYzFRd0wyaVk3TTExVVRGaWN5dytNQT09IiwibWFjIjoiMzM2NzYzNGQ4ZjIxZTE4MDExN2VhMjU0NzFhYmExMTVlNjQ2MTIzY2E2ZGYzZjMyN2YyMWM1NzZhZGNlMmRhYSIsInRhZyI6IiJ9', '2025-10-19', '2027-06-06', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(45, '9064018936887419', 'EXPIRED', NULL, NULL, 'eyJpdiI6IjJnQWM3eXBUb3VsNDJhaDlIem1wcXc9PSIsInZhbHVlIjoiZGYwaHpWQ1d0L3VJMkJRdHF4akI1QT09IiwibWFjIjoiODg5ZGU5NmY3N2M0OTYwODIxZDlhNTVmZmNkMWI5ZTdkYmM5NTk3NWYxZTE4ZTM1MmNhN2ZmMGY5MDVkNmRkYyIsInRhZyI6IiJ9', '2025-11-29', '2027-06-03', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(46, '1711408092293945', 'ACTIVE', NULL, NULL, 'eyJpdiI6IjRZcGRxM012eUtYTE4zdmtQV3o1K1E9PSIsInZhbHVlIjoicGZFUUhmTGhhcFhSd1dXV0p4RkNqQT09IiwibWFjIjoiMGVjOTQ3Y2M2OGNlNzE1ZDhiZDQ3NjkzMGU3YzMzMTI0OWJkM2RiNmJjNzQ5NzNiZTQxMTU2ZmE0MzhjNDFlYSIsInRhZyI6IiJ9', '2026-02-17', '2027-10-26', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(47, '1711408092293945', 'ACTIVE', NULL, NULL, 'eyJpdiI6IlNFUEFzbEowL1RHaVBxV3B6WW5SekE9PSIsInZhbHVlIjoiZU5PNExpQy91OEdPS25vNjN5cmMvQT09IiwibWFjIjoiZDc2YjNmNjMzNmExMGJkYzlhMWI1OGFlOGY5YmEzYzdlNWE2ODQxNjFjYTZkMjA4MzI5MmY0MTQxZDU5YWVlMSIsInRhZyI6IiJ9', '2025-09-16', '2026-08-07', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(48, '7632292793269675', 'EXPIRED', NULL, NULL, 'eyJpdiI6IlhLY25nbjgzN2V6aUovNFE2RHBoL3c9PSIsInZhbHVlIjoieWwrYXNiZzBVYlp3eDdueGlnVllQZz09IiwibWFjIjoiOTgyMjZlYWZmMzA5NTVmNjU1NTAzMDFlYTM2YTMxMjM2OTc3MDYzNTRlNjdkNDBhMGYyZTkzMzVlZTQzMWVjMCIsInRhZyI6IiJ9', '2025-05-11', '2028-01-28', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(49, '6824309255018061', 'EXPIRED', NULL, NULL, 'eyJpdiI6IkJZVVd4VGk1TEE0S3FDS28zbWF3OFE9PSIsInZhbHVlIjoiNDV2WlRzbndVYzlwL1BLbUM3MHM0dz09IiwibWFjIjoiZTM2ZGMxNmExMTI3Zjg2YTdkMTJiNzIzN2E2NTJiMzQ4MWUxODdhMTY3ODkwZWQwN2M5N2JjMDU0MDMwODk4NyIsInRhZyI6IiJ9', '2025-08-24', '2026-06-10', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(50, '6824309255018061', 'EXPIRED', NULL, NULL, 'eyJpdiI6IlJrNHgxV0ZRaFZTSnA5dVgyWU5rVFE9PSIsInZhbHVlIjoiSG9icjMzekw1Ukc1Y3BXelE4anBXdz09IiwibWFjIjoiZDYwMWU5NTU3NmM5YjE0NTJhY2IwY2EzZWNiYmFlNDU0N2MwNWQyZTFkNzVjNzM3NGMwMGZkOWI1YmNlN2QxZCIsInRhZyI6IiJ9', '2026-01-19', '2026-08-09', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(51, '3156661593848831', 'EXPIRED', NULL, NULL, 'eyJpdiI6IlhvRFdSTStNdG9zOXBkT0FDRnlvYkE9PSIsInZhbHVlIjoiWGlzWDc4dDRISHFrN21hbnlDeHAzUT09IiwibWFjIjoiYTUxZTU0MGFhYWFmZDRiZDNlZmMyNDBiMWNhOWE1NTg3MjY5NjMzNjE0ZDVkYzM0ZTA5ODk1NGNkMjU0NDkwNSIsInRhZyI6IiJ9', '2026-03-12', '2027-09-06', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(52, '3156661593848831', 'EXPIRED', NULL, NULL, 'eyJpdiI6ImU1MEJvRG1ZbkRkSUVEVGp0Qk8rNHc9PSIsInZhbHVlIjoiL1B2VHRQekVIdHVyTDh2OUJzWFkxZz09IiwibWFjIjoiODNiOTYyMDY5MGUzZmM3M2M0NmEyNDY5NjZmOWU5NDE2NDk5MTUxOTg4ZDk3M2MzZWJiMDBkMzI3OTNkMjhhNyIsInRhZyI6IiJ9', '2025-12-13', '2027-02-05', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(53, '0597787384237356', 'PENDING', NULL, NULL, 'eyJpdiI6IlkyZzJrcHZKSDRETnZNUllWbDhHTEE9PSIsInZhbHVlIjoiMWQrOTBWQUJaTCttMFVQa2xJNFhvUT09IiwibWFjIjoiOWJhYjI4YjQ1MGI1MDE3N2VmNTIyMTJiMzI4ZDAyMjdiOGQyNThlYjgwOGQzYzRkNjllZGUyYTJiYTM4NzBhYSIsInRhZyI6IiJ9', '2026-03-18', '2027-01-26', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(54, '0597787384237356', 'ACTIVE', NULL, NULL, 'eyJpdiI6Ikt3K1p0VnFTTWpUMy90cWh1OUNZNEE9PSIsInZhbHVlIjoiK3dwSWN5cU0wZDdCR1lLK1JTZ0xhZz09IiwibWFjIjoiMzQ5NTZlNjY0YWRjZWEwMjcxZTcwZmIwYWY4YTJmYmI2MGYxMTdhNGUwZGE5YTFlYThlYjMxNGY5ZTQ3M2QwZiIsInRhZyI6IiJ9', '2026-04-13', '2026-10-29', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(55, '1765211616403087', 'ACTIVE', NULL, NULL, 'eyJpdiI6Imt0Mjk2dHU5a3VJTHlaVGFqMFhib1E9PSIsInZhbHVlIjoibDhzMmJZaXVMeXc5K3IyQ0JBWDBsQT09IiwibWFjIjoiODdlYjQxNjU0OGVlOWFiZjgwMjkxNmU3ZWU1MWIyZGY1MjJkZDk0NGJmMTJiZDAzMGViYWIyZGI2MWNlNTAxOSIsInRhZyI6IiJ9', '2026-03-16', '2027-05-24', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(56, '1765211616403087', 'EXPIRED', NULL, NULL, 'eyJpdiI6IllqMGp6aEFQcDFxTVdtenJseWM1QVE9PSIsInZhbHVlIjoidUJZd1JSMWRuZHZaM0pPME1uL1g3UT09IiwibWFjIjoiZWI4NzQwNTUzMTI0YjQ3Y2MwNWNlNmFjZDAyOTE0ZWM1NTUxMDg1MzY2NDU3MjI0NDAwMDViYjE3Yzc0OGRkYiIsInRhZyI6IiJ9', '2026-04-12', '2026-07-29', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(59, '3556305576873095', 'ACTIVE', NULL, NULL, 'eyJpdiI6Im1uZkdBaHd4NnQxZWRldmIwRi9CbUE9PSIsInZhbHVlIjoiODh3bE5QVzFXUVhEQnhZTGRrdzRtdz09IiwibWFjIjoiMzQzNjg5NDExZTFkYmUzYjEwZTY4MzJmY2E4YmYyZTU4ZjhhYWIxODRkYTc2YzdkM2FjNTRlYjA4N2IzNzFkYiIsInRhZyI6IiJ9', '2026-03-29', '2027-12-03', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(60, '3556305576873095', 'ACTIVE', NULL, NULL, 'eyJpdiI6IjJac2NxY3A4MjEzRHJCSzZKMmx5Qnc9PSIsInZhbHVlIjoiSU5WWHlaMXJqeWNsc0h5UkpnWXUxdz09IiwibWFjIjoiZjA3YjI5Y2UyYjQyYzVjMzEwY2Y2NjNlOTA0MDgzYWU0MjFjODhiOTVkMjc3YjFhYWZjZmE5N2NiN2MzZDQ4YyIsInRhZyI6IiJ9', '2026-01-03', '2027-06-23', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(61, '7203751937254613', 'ACTIVE', NULL, NULL, 'eyJpdiI6InFJanhzMituR1QwVzZtWTBGcHkyWkE9PSIsInZhbHVlIjoibzMwQnNmQlluVDJleXRDUWczdTI5Zz09IiwibWFjIjoiNzU5ZjljN2MxODllMTQ1Mzg2NzA2MWRlOGEwMzdlOTZjZjFlZTk2ZmJkNzkxOTE4YjcwYWQyYWQ5NWQ4NWIyZiIsInRhZyI6IiJ9', '2025-12-21', '2027-04-13', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(62, '2425119000510520', 'EXPIRED', NULL, NULL, 'eyJpdiI6IjJJaFRvdGpRblQxVjBvd0QvUFNoRlE9PSIsInZhbHVlIjoibXcyM1NGZGUvaldJODZuTkFQbzJ4QT09IiwibWFjIjoiZjYxZWFkZDg3NjY1YjFkNzhhMDE2MjI5NzMzNzYyMmI1ZjY4OTE2NThhMzU5Nzk4YjE0MTg2ZjE2ZGNlZWY5NyIsInRhZyI6IiJ9', '2025-10-08', '2027-04-13', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(63, '2425119000510520', 'PENDING', NULL, NULL, 'eyJpdiI6ImRNOWJCa1gvMTBJaTl5T3dGcnJQOEE9PSIsInZhbHVlIjoiQXE1V1F6VmJ4N3Z1ckR5ME1wT2U4Zz09IiwibWFjIjoiMDM5Njg4ZTgzNmFhYjI0ZTQ0OTA2YTYyYzQwMmE1OTk0MWE5ODVjYzA2ODExYmNlNGRkNTQyMjA2MmM0OWI0OCIsInRhZyI6IiJ9', '2025-05-23', '2026-09-16', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(64, '5015999804741324', 'EXPIRED', NULL, NULL, 'eyJpdiI6Im9TZ0xnU08vWGhlUFpvNzJxZ3lNZlE9PSIsInZhbHVlIjoiTTIyMHBCcFBrbjVVbEZGRjgvQTI1UT09IiwibWFjIjoiOTQ5N2M0ZGEyMjNmMDZjNzE3N2I4NTNiZTA3ZWVjZDM5MGFhOTU5YzE5MjNlZTJkYzRhOTUxOWE1OWJjYmE5OCIsInRhZyI6IiJ9', '2025-09-07', '2026-10-24', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(65, '5015999804741324', 'EXPIRED', NULL, NULL, 'eyJpdiI6IjdyZk41TDFSS0s0SitRYXBRK2U2K3c9PSIsInZhbHVlIjoibVlhU1JyM3Fua0E5anhRanVPOVV5dz09IiwibWFjIjoiYTVlYTlhZDRjODc0Y2M5ZWE0M2M1NjJmZjkzMmQ2MTc5ODJjYzdlMmZjNzk2ZTA3Y2I0YWY0YjQ0YWUzZjhhMyIsInRhZyI6IiJ9', '2026-04-15', '2028-02-29', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(66, '4854458624290894', 'EXPIRED', NULL, NULL, 'eyJpdiI6IjBaWkU3Q0tqVk11OVhhdGR1blFZMEE9PSIsInZhbHVlIjoiQzJGajNma0FmV1JQVkh5ckV1OVJoUT09IiwibWFjIjoiMDMxOGZiMTNlYzZiNTJmYjhmZTMyMzc1Y2Q5M2I0ZTc5ODRhMmI3ZjZlZmMxNGIzZWEwOTNlYmJjOTZkM2Y5MiIsInRhZyI6IiJ9', '2025-09-10', '2028-02-03', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(67, '4854458624290894', 'EXPIRED', NULL, NULL, 'eyJpdiI6IlhMVjloYzJJMmowbXh6MkxqRm9jbFE9PSIsInZhbHVlIjoidm45VTV2M01qZnZKcUVoYzJTWndvZz09IiwibWFjIjoiZDFmNzdjMDY4ZWEyYmQ4YzZjNjU4OTU0NmQxODVmMjkyZGY3ODVmZmZlMTA5MWY4YjRiZDkzZTYwZmNmOTMwYyIsInRhZyI6IiJ9', '2025-10-14', '2027-01-05', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(68, '7661351748728698', 'EXPIRED', NULL, NULL, 'eyJpdiI6InphYmtSVVNYdXBIRkYzZGg4Mk1Fa0E9PSIsInZhbHVlIjoiYmJmUzJ2NkloZEU4ODJRdUlrQVkvZz09IiwibWFjIjoiNGQ4MGRjZGJkYjUwMzI0MDc5NmQ3ZDZiZTg3YWI0YjQ2N2ZmM2Y4ZGM3ZTQ1YTVhZDY5NjkxMGYyMTFmNTYwZCIsInRhZyI6IiJ9', '2025-06-21', '2027-01-20', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(69, '5744659211397856', 'PENDING', NULL, NULL, 'eyJpdiI6IjJydEliQ05sOUhiWnFSTkIxcU9qY0E9PSIsInZhbHVlIjoiaS9LQmpycTllbVI3eXlUK3MrV21xQT09IiwibWFjIjoiNTE3NmEyZjUwMDU4Y2JmNTFkMTljYzE0YTg5ZjllM2U0MWZkNDc0MzI4MWIwZDAwYjM2MzllYjFmY2VkODkwOSIsInRhZyI6IiJ9', '2025-10-14', '2027-01-22', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(70, '3249031442770406', 'EXPIRED', NULL, NULL, 'eyJpdiI6InB5b3VIbEhvVWhUWWVJbHFxeHY1VXc9PSIsInZhbHVlIjoiclJjOFAyZ3BuK3pzZ2lQOUtIc0sxQT09IiwibWFjIjoiMzU4ZGI2ZGUzYWYwODU1NWU3MTUwZTBmZTQxNzNmNmNhM2JhZDllZDVjYzk2ZTU5M2ExYzQ2YjBmOTM4NGI2MSIsInRhZyI6IiJ9', '2025-10-26', '2028-01-25', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(71, '9853662668487827', 'PENDING', NULL, NULL, 'eyJpdiI6Im9lc0xEcHFmd2ZadUtoZWIrYWZ5eVE9PSIsInZhbHVlIjoicjNjVUN1MEpBWFpDMFE1L2c2eGlKQT09IiwibWFjIjoiYzI0ODI4NmI4MTczM2UyM2I1NWEwZmQ1OGY1Y2IzZjhmZGU4MWI3ZTRhM2ZhMzc4MjYyMzc1ZjgyNjc4ZGNlZCIsInRhZyI6IiJ9', '2025-07-17', '2027-07-28', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(72, '9853662668487827', 'ACTIVE', NULL, NULL, 'eyJpdiI6IkVhQzRBNVA2dTdLWFkzbWpPM2NmS0E9PSIsInZhbHVlIjoicHVhUktWZHZFd2Z6aHBhbHErOGtMZz09IiwibWFjIjoiOGI5ZGM0ZjQ5NGM4ZTcwYzUxM2ZkYWY0Zjk5NjUxYzM4ZDJiZTU1N2Q3ODhlNGEzNTA0ZGVjYmZiNGU4ZGI5OSIsInRhZyI6IiJ9', '2025-07-16', '2027-07-31', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(73, '6307266807914803', 'ACTIVE', NULL, NULL, 'eyJpdiI6ImJ0YmxFYjBKbW1BZ2RoSStjbHo3NWc9PSIsInZhbHVlIjoianZpTnFMdlAvMmcyWEU3MURPSHk0QT09IiwibWFjIjoiYzRlODE4NzY5ZTlmMmJhOTU5YTIzZWMwMDJiOTU1ZGRlMDNhM2JkMGJlMjBiODcxM2Q2OGI5MmY4MzU2N2M2NCIsInRhZyI6IiJ9', '2026-02-06', '2028-01-31', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(74, '2658156081791587', 'ACTIVE', NULL, 'public/poverty_proofs/2/2658156081791587_1779289026.pdf', 'eyJpdiI6Ik5xRWhuTGhkL29aZXlFcmNLQ0NqcHc9PSIsInZhbHVlIjoicitqL2R2S2Z6cmZFVWhaQXNtNXhLQT09IiwibWFjIjoiODlkYTcxODY4Y2RiYzU1OWY0OGI1ZWU4ZWRjY2I5MmI2NDQxZmIyNjYyZGJiOTRiNWQzMzBlODQ3YmUxNmYxZCIsInRhZyI6IiJ9', '2025-11-18', '2026-12-26', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-20 21:57:06'),
(75, '2658156081791587', 'PENDING', NULL, NULL, 'eyJpdiI6Ik9WZlhNNW82eDBkZGtSV2NyU0N2Umc9PSIsInZhbHVlIjoiem0vbGlkWkpYWHYxSThqaDhZVjh3Zz09IiwibWFjIjoiZTllODkwZWFiZGQxOTdiMTc1ODc5MDgzZjA1NjVlZjkzZGE0YzZmZjAxZGYwYWU1MTE4OTYwNTM2ODJhOTBmNyIsInRhZyI6IiJ9', '2025-05-24', '2027-10-09', 1, 'DTKS', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(76, '7012281952710046', 'EXPIRED', NULL, NULL, 'eyJpdiI6InFKWDc5L3o5N0NaVm1OZVRORlhHUUE9PSIsInZhbHVlIjoiNklWanFLczdrc2RvZW94TStFai84dz09IiwibWFjIjoiOWZhMzkwMmI5Zjk5MTM3Nzg4MzY0MzJjOTYyMmFiOTJjZjkzNTBlZmZlZDljZjRjMjliMWM0NmU0OTMzM2RiNiIsInRhZyI6IiJ9', '2026-01-28', '2027-04-02', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(77, '8924557358624676', 'ACTIVE', NULL, NULL, 'eyJpdiI6InNqY3IzUkRSd3VkVkY2UjhVY3REalE9PSIsInZhbHVlIjoiSGF3bUNjSHMxVmNGcjRVOTdxTjNWUT09IiwibWFjIjoiZTNjMmViNjkxYjczOTFmYWE2M2IzYjgzMDJjYTQxMzY2NmI1ODk4MzFiNjQ3MDM1NWMyYWQ4ZjY5MDdjMTRhYyIsInRhZyI6IiJ9', '2025-10-27', '2026-11-12', 1, 'Data Desa', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(78, '8924557358624676', 'EXPIRED', NULL, NULL, 'eyJpdiI6ImVCOTZodGpWeGtKQkpDbkRvZlFhbGc9PSIsInZhbHVlIjoiSXZwWkI3RHBRUmhKZlIxenoxVjFTUT09IiwibWFjIjoiMDZhMmFlNDI4NjEwZTYzMjY3ZGQ2ZDE2MzU4NTIxYWJiNjQzMzVlNzU2MDI5MzkzZmU2MjEzM2FlMzQ4ZDA5YyIsInRhZyI6IiJ9', '2025-05-11', '2027-01-13', 1, 'Hasil Survey', '2026-05-04 05:32:23', '2026-05-04 05:32:23'),
(79, '0140915127418975', 'ACTIVE', NULL, 'public/poverty_proofs/3/0140915127418975_1778225564.pdf', 'eyJpdiI6IjYzdFE1UkhIdExMZXRJVEFxejhVN3c9PSIsInZhbHVlIjoiSnc1V1NCV25TbGN6ZFdOWDVobzZCMTJxTFJFWE5nbjhNVUUwdExBaXQxUT0iLCJtYWMiOiI0NmY4MWJlZDZmN2IzNTdjODU5OGFmMzIxOTMwMTg2OWIwMDRmNzc0N2FlNDhiZjdmMTY3NmIwNmZhMzZmZGM2IiwidGFnIjoiIn0=', '2026-05-08', '2026-11-08', 6, 'VILLAGE_VERIFICATION', '2026-05-08 06:18:48', '2026-05-08 07:32:44'),
(80, '0204199306493975', 'ACTIVE', NULL, 'public/poverty_proofs/3/0204199306493975_1779288972.pdf', 'eyJpdiI6IlNiSTNYUGxNaGpoRS90VGV1b3pxVVE9PSIsInZhbHVlIjoiQ3hrbHF4T2pFOEtKaWVOSUt6elF3WWg0NGZuRjc2Q3MvUU5BVXphdlNsbz0iLCJtYWMiOiJjMTIxMDdiMDYzZDU0ZjQxZmRhOTY0MzVjNzQyMWY1OGY1MjFiMWZlNTBkYzcxMzkxNWViZGFmNzM0MmZjNzI0IiwidGFnIjoiIn0=', '2026-05-08', '2026-11-08', 6, 'VILLAGE_VERIFICATION', '2026-05-08 07:53:03', '2026-05-20 21:56:12'),
(81, '0548397596571350', 'ACTIVE', NULL, 'public/poverty_proofs/3/0548397596571350_1779290334.pdf', 'eyJpdiI6IlhGcnRRN0NwanJiN2xobVZDWFpoTVE9PSIsInZhbHVlIjoiK2RRTW9mMkltUWJ5Vk9vekkyb1JXU3UxNkRDdmFqRm82ckhoSTJyb0pQND0iLCJtYWMiOiIzYTAyNDE2MTAwNTcwMGVjZDE5NzIyNmJjMWFmMTU3OWE4MTI2ODdlOTA0NTFiOTQyNGNlYTQyZTYzOTFjM2I1IiwidGFnIjoiIn0=', '2026-05-11', '2026-11-11', 6, 'VILLAGE_VERIFICATION', '2026-05-08 09:17:22', '2026-05-20 22:18:54'),
(82, '0064622836742991', 'ACTIVE', '123', 'https://antigravity.google/docs/skills', 'eyJpdiI6IlBuMTlCMnUwRFFsQXE0UlRDTVRmV2c9PSIsInZhbHVlIjoiald2dUxCOG5uYnRoSnJ3T1dTQWJvRXorUTNUOGFHc3p1dFNIakZpSEpidz0iLCJtYWMiOiIwYTE1ZjAyYjkyNWI3N2M4NzNlZWJlNzY5Nzk2NGY5OGE3ODY0ZjI5NjY5N2IzOTlmOGZlOTk4OGU1NWE2ZTZiIiwidGFnIjoiIn0=', '2026-05-19', '2026-11-19', 6, 'VILLAGE_VERIFICATION', '2026-05-12 16:43:51', '2026-05-19 19:55:18');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'SuperAdmin', 'superadmin', NULL, '2026-05-04 05:32:21', '2026-05-04 05:32:21'),
(2, 'OperatorDesa', 'operatordesa', NULL, '2026-05-04 05:32:21', '2026-05-04 05:32:21'),
(3, 'PetugasFrontOffice', 'petugasfrontoffice', NULL, '2026-05-04 05:32:21', '2026-05-04 05:32:21'),
(4, 'Auditor', 'auditor', NULL, '2026-05-04 05:32:21', '2026-05-04 05:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `citizen_nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('PENDING','APPROVED','REJECTED','EXPIRED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `front_office_user_id` bigint UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `citizen_nik`, `service_type`, `status`, `front_office_user_id`, `notes`, `created_at`, `updated_at`) VALUES
(2, '0140915127418975', 'KETERANGAN KEMISKINAN', 'APPROVED', 2, 'dasddada', '2026-05-08 06:18:48', '2026-05-08 07:32:21'),
(3, '0204199306493975', 'KETERANGAN KEMISKINAN', 'EXPIRED', 2, NULL, '2026-05-07 07:58:19', '2026-05-08 08:29:28'),
(4, '0204199306493975', 'KETERANGAN KEMISKINAN', 'APPROVED', 1, 'aaaa', '2026-05-07 08:36:45', '2026-05-07 08:37:38'),
(6, '0204199306493975', 'KETERANGAN KEMISKINAN', 'APPROVED', 1, 'dddd', '2026-05-08 08:52:54', '2026-05-08 09:02:21'),
(9, '0548397596571350', 'KETERANGAN KEMISKINAN', 'REJECTED', 2, 'dosen un', '2026-05-07 09:22:39', '2026-05-07 09:36:59'),
(10, '0548397596571350', 'KETERANGAN KEMISKINAN', 'APPROVED', 2, 'bukan dosen', '2026-05-08 19:12:37', '2026-05-08 19:12:57'),
(12, '0548397596571350', 'KETERANGAN DOMISILI', 'APPROVED', 2, 'keperluan PNS', '2026-05-09 07:11:19', '2026-05-09 07:11:27'),
(13, '0548397596571350', 'PENGANTAR PINDAH', 'REJECTED', 2, 'sjdhagd', '2026-05-11 04:00:41', '2026-05-11 04:02:52'),
(14, '0548397596571350', 'KETERANGAN KEMISKINAN', 'APPROVED', 2, 'das', '2026-05-11 04:13:57', '2026-05-11 04:31:43'),
(15, '0548397596571350', 'PENGANTAR PINDAH', 'APPROVED', 2, 'TUJUAN: dasdads\nCATATAN: aaaa', '2026-05-11 04:45:18', '2026-05-11 04:50:36'),
(18, '3721700552332154', 'KETERANGAN DOMISILI', 'APPROVED', 2, 'cetak ktp', '2026-05-11 14:05:32', '2026-05-11 14:05:43'),
(19, '3721700552332154', 'PENGANTAR PINDAH', 'APPROVED', 2, 'TUJUAN: pindah desa dan kab\nCATATAN: pindah desa', '2026-05-11 15:01:12', '2026-05-11 15:01:31'),
(20, '1231231231231231', 'LAPOR DATANG', 'APPROVED', 2, 'DARI: sdasdsa\nDATANG: 11/05/2026', '2026-05-11 15:05:26', '2026-05-11 15:05:26'),
(21, '1231231231231232', 'LAPOR DATANG', 'APPROVED', 2, 'DARI: dadsa\nDATANG: 11/05/2026', '2026-05-11 15:12:19', '2026-05-11 15:12:19'),
(22, '0064622836742991', 'KETERANGAN KEMISKINAN', 'APPROVED', 2, 'fdyughguhfy', '2026-05-12 16:43:51', '2026-05-19 19:55:18'),
(23, '3721700552332154', 'KETERANGAN DOMISILI', 'APPROVED', 2, 'dasdad', '2026-05-20 22:00:27', '2026-05-20 22:00:57'),
(24, '0003485827010970', 'KETERANGAN KEMISKINAN', 'APPROVED', 2, 'masyarakat miskin', '2026-05-21 23:07:04', '2026-05-21 23:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('iQwNjRQYbR5pdzi23cWmJOjcrij2dANUNzE7zBrL', 4, '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqak03YndUMWNLQnpRZ1JNREJSUUFnZ09YTUt0QUdORWwxSEl6V2xkIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3BlbGF5YW5hbi50ZXN0XC9kZXNhIiwicm91dGUiOiJkYXNoYm9hcmQuZGVzYSJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6NH0=', 1779381156),
('wSxx7TjG9DM9cUorOIomedrWNbIrobt4QQS6PjUB', NULL, '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJPZk1VM2VHWkhaQUVybUZOZDJ5ZXl0Z1I0ajFSMDJycEw5M2ZMNVVZIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3BlbGF5YW5hbi50ZXN0XC9kZXNhIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwczpcL1wvcGVsYXlhbmFuLnRlc3RcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1779392259);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `desa_id` bigint UNSIGNED DEFAULT NULL,
  `district_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `desa_id`, `district_id`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Pusat', 'admin@example.com', '2026-05-04 05:32:21', '$2y$12$vHgUK3rV.m6D.IVMD/5DROuSPf7uA2EQayCONAMK.wBGhM4dr69ta', 1, NULL, NULL, 1, '0U4ZteeAlhsw5VSs3XmBktZcSlun8oZdHQkFai91CqvUIiXaWHrgH6lxDGHW', '2026-05-04 05:32:22', '2026-05-04 05:32:22'),
(2, 'Petugas Front Office', 'fo@example.com', '2026-05-04 05:32:22', '$2y$12$t86tID142gIpPbrpSAk2iO6OqFc6XB0PvVoP4unMq/65pSsis0QZi', 3, NULL, NULL, 1, 'Yy64ndslkhBDWcVVGyCstd8vQIxuRkyti94AS7Zt7hoS91QtFeFCufmVJfrW', '2026-05-04 05:32:22', '2026-05-04 05:32:22'),
(3, 'Operator Desa Sukamaju', 'sukamaju@example.com', '2026-05-04 05:32:22', '$2y$12$P7ODr5bg4PnFOJVp06L.TOG4oKaWOHw8DTj7Gm8sRtWnv/F1tLskm', 2, 1, NULL, 1, 'zH6kbno0t7', '2026-05-04 05:32:22', '2026-05-04 05:32:22'),
(4, 'Operator Desa Sukaraya', 'sukaraya@example.com', '2026-05-04 05:32:22', '$2y$12$WNR0mK7jzrit6JkpktMgduRyS329WzP.dTngrz0ewY5hEXanvZh3G', 2, 2, NULL, 1, 'YHF8gRPVsz6ckjF7oBMdOp49nZODTDA9IwhDusw4KhqRfwjTHhfwjB7n1sc0', '2026-05-04 05:32:22', '2026-05-04 05:32:22'),
(5, 'Elian Hoeger', 'elian-hoeger@desa.id', NULL, '$2y$12$BqBmoCIFcxD1KyDa6YlnC.axnIPE/uKgLItK/CduQ0VQhcum56Kua', 2, 1, NULL, 1, NULL, '2026-05-04 05:46:27', '2026-05-04 05:46:27'),
(6, 'Milford Ruecker', 'milford-ruecker@desa.id', NULL, '$2y$12$EhiQLDsU3VgZVou7ZW8jt.qRMrELR5Z5pkTCcSkRTbujBcyxwq08K', 2, 3, NULL, 1, NULL, '2026-05-04 05:46:27', '2026-05-06 16:18:31'),
(7, 'Richmond Champlin', 'richmond-champlin@desa.id', NULL, '$2y$12$9h9gQYpBJ0rmlYJ1x8YRFeCYWJLgnk0DpWPyOhay63TM523er/Fzm', 2, 3, NULL, 1, NULL, '2026-05-04 05:46:27', '2026-05-04 05:46:27'),
(8, 'Kadin Simonis DVM', 'kadin-simonis-dvm@kecamatan.id', NULL, '$2y$12$99bflV3YsJiWU0sBZhj.hOYqjSPrsuOG5b4uwImZVdibQClK9xoK2', 2, NULL, 1, 1, NULL, '2026-05-04 05:46:27', '2026-05-04 05:46:27'),
(9, 'Mr. Robb Bins', 'mr-robb-bins@kecamatan.id', NULL, '$2y$12$gv2Apg4VbcWXip8WgEuxfeEaiv3kvOPZKGBF0f52MvFvWle/JkY8O', 2, NULL, 2, 1, NULL, '2026-05-04 05:46:27', '2026-05-04 05:46:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_certificates`
--

CREATE TABLE `user_certificates` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `certificate_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passphrase` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `valid_until` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_certificates`
--

INSERT INTO `user_certificates` (`id`, `user_id`, `certificate_path`, `passphrase`, `valid_until`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 5, 'private/certs/cert_user_5_1777874017.p12', 'eyJpdiI6IitkdUZnVFFPWVd2aW1WWUl2Q1BoVWc9PSIsInZhbHVlIjoiQnJtUjRTblZDUEptaGMrWW9aUURybThaTCs5OVJDNEFlR0ZUVXhtcTE1MD0iLCJtYWMiOiIxYzJkMzdhYWY3MmE3MmI1NGNhMTBmNmI4MGQyY2E2MTk0OWNhNGVhNDQ4YmZlZjUzOTQzY2FlODAyZDQxYzY1IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 05:53:37', '2026-05-04 06:17:29'),
(2, 6, 'private/certs/cert_user_6_1777874428.p12', 'eyJpdiI6IkZ4bU1tNU9uQk8yVW9GSjlMVjZQd0E9PSIsInZhbHVlIjoiejFlemliMGtqalJEUHZhZnpwZXFYOFRHVzE4aGxsYmN1VC92QkdFR0tpcz0iLCJtYWMiOiI2N2M4NDM4NDAzMDRhYmE4MjYwYjg1MWI0M2YzNTY3OTczYTk3Mzc3NTI0N2QyN2FlODIwZDU5YTliNDlkYjBiIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 06:00:28', '2026-05-04 07:08:16'),
(3, 7, 'private/certs/cert_user_7_1777874434.p12', 'eyJpdiI6Imp3clRDc3p5Yk1aekxOc2RNOFM5RVE9PSIsInZhbHVlIjoiRHVsWkpEbmxyMUh1L3B0N1VuU1M0aERxbmRibjFGSTIybGYxUVAyQWdWWT0iLCJtYWMiOiIyOTA3NzFkOTE1ZjY4M2FmY2UxZjM3ZGE5YjQ1YjY0NGJiMjFhYTE1MDk5OThlNzQzZDZhNjI1YzNiMjJlYmQyIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 06:00:34', '2026-05-04 09:15:12'),
(4, 5, 'private/certs/cert_user_5_1777875449.pfx', 'eyJpdiI6InFNM1k5bk02Uy9KUHR2Q3ltRHNEbVE9PSIsInZhbHVlIjoiUm5wSzVyWE5UMzJNVlF4SkNwdmxQZ1k1MU45Q2F6SmJUUXBETE5rVGRSND0iLCJtYWMiOiIyNDI4NmYxMTZkN2I5ZGFjYTFjN2Q3YjA5ZjNmYjI4MzdkZGE0NjRmNGQ1NDdkZTA0ZjhjZmFlODBlYzZlZTIyIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 06:17:29', '2026-05-04 07:08:12'),
(5, 5, 'private/certs/cert_user_5_1777878492.pfx', 'eyJpdiI6IjJyUXlWNlNSeEVrT0ZZd213ZlJ4YXc9PSIsInZhbHVlIjoiUlBtMjdMSUdGclVpeFhKU0Rmc0xOYUVQZ3RVVWJONEtDVi94bjRTVUE4UT0iLCJtYWMiOiJmMzRlNjVlMzIzYTc1N2I3Y2RiZWM0MDU4OWQ3NjQxMGRiMTY5ZDkwYmQyMDhhZDFkMTA1MWFmOWY5ZWU1ZWU2IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 07:08:12', '2026-05-04 07:10:55'),
(6, 6, 'private/certs/cert_user_6_1777878496.pfx', 'eyJpdiI6IlRXRXZpK1BBZkZZcDFzYVBBWExONkE9PSIsInZhbHVlIjoiYXprMkIxSWFSczl1TVZhYmlhdEphemYwdHBGR01BK2FlZVQxd2hmblFacz0iLCJtYWMiOiIwODEzZGY3OTI3ZTUyNWZiNDE5ZmJiMWU1OTc4MjI0NTc0NTE3YjdlMGYxMmQ4ZGU0MDc1YjBiNjNkNzg4YmUxIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 07:08:16', '2026-05-04 07:10:59'),
(7, 7, 'private/certs/cert_user_7_1777878503.pfx', 'eyJpdiI6InJkMHUyQUVHR01BbzQ1bW9XYUQzWHc9PSIsInZhbHVlIjoiMnFBNWxJcThTTzlaUWdyaW51K3NpUXFuYmdtQ21TT05ydi9iNmJpMXRaST0iLCJtYWMiOiIyODAwNzFlYjg4ZjFhYTJjNTc4ZThkMzg3NmY4ZjM2OGEzOTQzZWEwNWE4OTlmZTNiNjk1YjAzOWU3NDdmOGIxIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 07:08:23', '2026-05-04 09:15:12'),
(8, 5, 'private/certs/cert_user_5_1777878655.pfx', 'eyJpdiI6Iml5YmdrZFJBRHdpS3hHdlhTSlJRMkE9PSIsInZhbHVlIjoiNm9jTHBmeGk5dHk4YVI0ck9wQ0JnQzlYaEZsMVlLaFB2MnVsNFIvd2hrMD0iLCJtYWMiOiI1YTY3ZjQ0MWFkODJkNTQ1ZmE0MGQyMjA2Mjk3ZDVhYzIxYTc4YmQyZDdkNGE1ZTg1NWYwYzIyZWMyY2U3OTIwIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 07:10:55', '2026-05-04 08:24:16'),
(9, 6, 'private/certs/cert_user_6_1777878659.pfx', 'eyJpdiI6Ijl1K2xPb2kxTXY3Z1h0NUhIUHlQZnc9PSIsInZhbHVlIjoiMVc0MHlCbGI3TXRuMGpiQWovZkFIQVZGQ1pMa3J3QXpxYmRCY3dOdWp6TT0iLCJtYWMiOiI1ODRjYTUyYWY4ZWYxMmExMTRmNjc1OGRjYWE4NmM3ZmI3MjU4MDMwZThiYzA4MjJhZDBiMzYwMjgwYTJkYjQ3IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 07:10:59', '2026-05-04 08:24:20'),
(10, 7, 'private/certs/cert_user_7_1777878663.pfx', 'eyJpdiI6Ilg5eHl0UmZ3NGlrdkhzOVBLWmc5Qmc9PSIsInZhbHVlIjoiQ1Yrcjk5U001elRwSE1CUDcwVmpsZ2VPUGNvUktpS2Exc1VGZWRTMWw3ST0iLCJtYWMiOiI5OTg5ODY5ODliNTRlMTI2YTJhYWE3OWJmOWYxMzljNmUwOTU3MjFiNmZkM2IwMzgxYzE4MGQ3NDk4YWY0MmNjIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 07:11:03', '2026-05-04 09:15:12'),
(11, 7, 'private/certs/cert_user_7_1777882584.pfx', 'eyJpdiI6IlFVbXI4d1hkTmV5Wkp5MVVLektidEE9PSIsInZhbHVlIjoieGxEU29UTkY5RGNMZVI3bERSZDU3TU01SklJczBMOC9IVGl5aXdYTTZBcz0iLCJtYWMiOiIxODM2NjU3ZDg2OTAxM2QyYmZmOWUyMGExOTFjYTdhNzAyYmFkMmZmMTQwZjE0NTY3NTJlNzhiYTlhNWI1NjgyIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:16:24', '2026-05-04 09:15:12'),
(12, 7, 'private/certs/cert_user_7_1777883050.pfx', 'eyJpdiI6Iks5bWwyRGhwaGZZMFlkQzdJbHZZK3c9PSIsInZhbHVlIjoiRE1IK09hWTV2a0JQRW80SVdEdnVBVUkwWnF0UzFyaHBFTUhHR0EvT0V0bz0iLCJtYWMiOiJlM2IyOGM2MmMzMTJhOGY0OGIzOGZhNmRkYTAxNmViYTNhOTI0N2FmMDQ3OTk3OGU5OGMyZTEyMzdiYmNhZjU4IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:24:10', '2026-05-04 09:15:12'),
(13, 5, 'private/certs/cert_user_5_1777883056.pfx', 'eyJpdiI6IlY3bi95OWhpSzBQbmc0VjAxMko0QWc9PSIsInZhbHVlIjoiSEgyZ0p0WU9oclAwT056VHA2ZFhqTVdKbHlFMXNkOUMvOTJlYmJJVTJnTT0iLCJtYWMiOiJiNTJjZWIyYTg2MzE5ZThiNjliM2E4NWQ3ZjZmZmMyZDNiY2Y4NDIzYTNjOTdlMjQ2ODhkN2ZiMWMzODBkYTg5IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:24:16', '2026-05-04 08:34:32'),
(14, 6, 'private/certs/cert_user_6_1777883060.pfx', 'eyJpdiI6ImdrSllCdTVweE5hWENXT0M2THZtTHc9PSIsInZhbHVlIjoiSkdyL0RWb094dC9wSGxjTENEOGE1dGtHRHAyMWw0aUM0TlN2RUIyS25uYz0iLCJtYWMiOiIzM2EzYjJjMTBjYjMzNDFiMTJhMjkxMWUzZjY5NDlhNmMxZWE2YmIyZDk2YWNkYzRhYWMzZGU1NzI2ZTJhNDM1IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:24:20', '2026-05-04 08:34:36'),
(15, 5, 'private/certs/cert_user_5_1777883672.pfx', 'eyJpdiI6IjdaOUlCNzdmeEp0d1VKbFhINGR1Q2c9PSIsInZhbHVlIjoieWhqMlFFUWJkZGZDS1VWMzRFSDBXY2krQWFtRGEvNUtQTkoxb1lVMmhzVT0iLCJtYWMiOiI0YjJmZTAzNGYyNTlhMzk1MmIxODBhOTVkNTc4ZjFlYmUxZGUyMzUwN2M5NDU4YmFiZDg2NzEyODRlZjE5NDM5IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:34:32', '2026-05-04 08:59:33'),
(16, 6, 'private/certs/cert_user_6_1777883676.pfx', 'eyJpdiI6IjdJWDVvRm93WGtFdFJiUkNIbFJxeEE9PSIsInZhbHVlIjoid1NkYTBCUWxtMlpLZStJNktxa1ZpN2w4THg3YTlMVFRYYUp1YVFwVmxNTT0iLCJtYWMiOiI4ZGE1NTg3MDFmMmQ0ZWIxY2MyNThmNWY5MWIzZjE3OWU2NzFhNjVkZTgyNTY5MmMwZmIzNjI4Mjk1NWYxOTkwIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:34:36', '2026-05-04 08:59:37'),
(17, 7, 'private/certs/cert_user_7_1777883679.pfx', 'eyJpdiI6IkR1b2dURjJDYmZzTG5pMUp6Tk1PaUE9PSIsInZhbHVlIjoiMU1BU3RQSnkvRk9MSCtpcG4zZnBvTjV6YVJUeDVMeWNudVZ5UnBjMkE3dz0iLCJtYWMiOiIyMWY3MWE0ZTgyYWYxZGZmMzRhYWUwMWNjMjRjMmUwNmJmNDY1Y2FhOGIzMTg0Zjc3NTNjYjg0ZDU0NWVmMWNmIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:34:40', '2026-05-04 09:15:12'),
(18, 5, 'private/certs/cert_user_5_1777885173.pfx', 'eyJpdiI6IkdKdTV0cTRrY2ppeUJTK1h1R3JYN3c9PSIsInZhbHVlIjoieE5qOVpyQ2J3SWVPek5sejlMNVFuQllhRzdjSXRXbERUQkx1ek4xeXBKZz0iLCJtYWMiOiI0ZTYxMDQzYjZiZDJhMDZiZGJmNzFmMjRiZjJkNzNiNjE3Y2Y3MWUxNjI3YWE2YzE1ZTU0ZTA0MWQ5MWU0NGRiIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:59:34', '2026-05-06 04:01:14'),
(19, 6, 'private/certs/cert_user_6_1777885177.pfx', 'eyJpdiI6ImZNUytQNEVPenJhb1dGcGRpVllac3c9PSIsInZhbHVlIjoiQnVHZ25veHJrNE1VREJjTlowWkxNL1FnZk13UjhvUk91L3Y2N3ZlZnZSUT0iLCJtYWMiOiI4YmM5ZGRkNGNkZTQyNWJmYTYyNjY1ZmYwMWJhYzMxMjc5NWQ2ZjkwZDZmZTE3MjQxNzI2YjI5OTA2YWNiYThiIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:59:37', '2026-05-06 04:01:18'),
(20, 7, 'private/certs/cert_user_7_1777885179.pfx', 'eyJpdiI6Im5iajU2Vm9PQ2ZCKy8rOFg5dStaR3c9PSIsInZhbHVlIjoiRVhPWE5STkFPVFY1aU9hVFZwTHZCTGgvcWlZM3MvcEgvNVB3WU93cjVyQT0iLCJtYWMiOiJkNzgwODMwM2U3NWNmOWY1ZDVlNTVlMmE2ZWYzNjI2Mjk5YzdkZDJiMGEwNzI4YjBiMzg0ZWM5ODhjMzIyYTIyIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 08:59:39', '2026-05-04 09:15:12'),
(21, 7, 'private/certs/cert_user_7_1777885364.pfx', 'eyJpdiI6InVTRDArdnhIb2I1dG9zWHV6WUlseVE9PSIsInZhbHVlIjoidThRR0hOVE5FcjBDV2paaFdxam1RY01TdGNCRXpFZlp2NURiaVl2eHJnaz0iLCJtYWMiOiIwMjQ4NDdiOGI0MGYyODlmMTc0YTdjNjg0MDI3NzkyMGQ3NzJmMTE0NzgxNWE2MjkzNTk2OGI5OWJjY2NmMDk5IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 09:02:44', '2026-05-04 09:15:12'),
(22, 7, 'private/certs/cert_user_7_1777885423.pfx', 'eyJpdiI6InFnM0RMTVkrSUJrODNFblBXb0JPUUE9PSIsInZhbHVlIjoidDNFUnNXVDJwaTg5MVYxKzB5eGdKSjkwaU5YcjJXbSs4L0VURWNGU2t4cz0iLCJtYWMiOiI2ZjE2ZGRjMzkyNzgzOTA3YmZhZWFkNWYwZjJiYmEwNWM1MzQ2OTZlOTk3YmNlMGJhOGJmYjY5ZjZjNTMwM2NmIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 09:03:43', '2026-05-04 09:15:12'),
(23, 7, 'private/certs/cert_user_7_1777885699.pfx', 'eyJpdiI6InM1bXhJWG5Qbk1PT3N3cUc2RTN2S0E9PSIsInZhbHVlIjoiTWF2TWF5eWZ6TDJINEE0ZnBaL1hlTlhLZDF0Mk4rQ0xUMHlqTkhYQ000WT0iLCJtYWMiOiI0MGRmNzZjN2ViZTJlYjE1OTc3NTEyMzM0NTQ5ZDYyMzBhOTcxODE1NDI2NGNlNDZiMzZjYzg2ZTM5NzQzYjlhIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 09:08:19', '2026-05-04 09:15:12'),
(24, 7, 'private/certs/cert_user_7_1777885794.pfx', 'eyJpdiI6IlZwUE0xL2tyemNzb3NIcFhlSUhxY0E9PSIsInZhbHVlIjoiRk1URHZhMHN2aFM5bW5SbTlvN0kyNUtmOEgzQ1pIaG5jOXp2Rk5oNDBvZz0iLCJtYWMiOiI3ZDA3NzZmZDI3NmQ4YWRhMDhlMzY3Njg1M2M4ZmNmMTY2NDZkZjM3ZjQ3ZmE3MDM0ZDk1NmRkZDU3MGFiOTMwIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 09:09:54', '2026-05-04 09:15:12'),
(25, 7, 'private/certs/cert_user_7_1777885999.pfx', 'eyJpdiI6ImNVSS9XeXc5QW92VldicXk2a2ZZVGc9PSIsInZhbHVlIjoiM1UxRGw2VDBhblBVUk5HQ3pEMTNGUVRuejczRFc3RjBvYy9HR2dvUWVkVT0iLCJtYWMiOiI1ZWQxZDMyOTllY2RjYzIzZDYyY2Q0MDBiODZkZmQ2ZjRjZDU4MTg1OTA3MzBiN2YwMmZmN2NkYmQyNmMzZmY1IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 09:13:19', '2026-05-04 09:18:41'),
(26, 7, 'private/certs/cert_user_7_1777886321.pfx', 'eyJpdiI6Iko5NmlSZVAzT3laT0piVDliNjBiZWc9PSIsInZhbHVlIjoiYm1nQzFQVFc3QWhhTGovRkI2d3hMdVpEUjJNVm9BRXFISzZCRzdlMmNOTT0iLCJtYWMiOiIzM2JhMjE5NGU4YzczMDAyYzYzOTFlZGRiNzg0OTU0NzhjMDBlMGJhYTcwNzNmM2ZiZGQ5NTVhM2UwNDBhYTc5IiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 09:18:41', '2026-05-04 09:19:24'),
(27, 7, 'private/certs/cert_user_7_1777886364.pfx', 'eyJpdiI6IkNXZkFPZjNQaCtvVklNbldMYmNFQ3c9PSIsInZhbHVlIjoiY1hyRlRubXNUK0Njd1JJdnk4cUljRnVjNmcrMmNXcGxtaUFCbkV4bm44Zz0iLCJtYWMiOiI3ZDlmOWUxMTdiYTY2ZGUzNGVjOTU4YzhlMGVmYjI5NzBlMWUxYTRjZmEwNDc1ZDJlNjA0OTU4MjA5ZWMxMTEwIiwidGFnIjoiIn0=', '2027-05-04', 0, '2026-05-04 09:19:24', '2026-05-04 09:21:23'),
(28, 7, 'private/certs/cert_user_7_1777886483.pfx', 'eyJpdiI6Im9WZ0hTNlJ5dTVNN3RMUzJKdVhtS0E9PSIsInZhbHVlIjoiN0VvTmlsYnlXM1NNUHRqMDJGWXdvQ2Z6MmdRSHRFMzRLMktUZHZpdEV0ST0iLCJtYWMiOiI4Y2NiYjY3MTUzNzY3OWQ2Mzc5YWMxZjc2Y2M5MDJkMTJlZDUxZDJlYWFlNTI3MmFmYWUyOTgwNTllYmMxNGE3IiwidGFnIjoiIn0=', '2027-05-04', 1, '2026-05-04 09:21:23', '2026-05-04 09:21:23'),
(29, 5, 'private/certs/cert_user_5_1778040074.pfx', 'eyJpdiI6IktFU2Y2Sjd2TVpJRjhJUk5IVDJPaWc9PSIsInZhbHVlIjoiL1dpRHdIYWk2c3RzOGNDRWJkT09PREJOMTQwQ085cGplL3R3aTMxSTJpaz0iLCJtYWMiOiJhZWIzNWFkMDBmMTY2NzE5MmM4NGQ4YWE3YjA3Zjg3ZDEwOTQwMmI3ZmUzMGE4ZWY0ZDE1ZDk1ZTAzNzMxYjQ0IiwidGFnIjoiIn0=', '2027-05-06', 1, '2026-05-06 04:01:14', '2026-05-06 04:01:14'),
(30, 6, 'private/certs/cert_user_6_1778040078.pfx', 'eyJpdiI6Ijl5Q0VUOVJEZDJaZ1Z2VnJoeWtNTFE9PSIsInZhbHVlIjoiOUtDdmJrYmpPclVURFVkWTRPVG50bEl4ZWdqc2tJeUxwemZuZnNKc09NOD0iLCJtYWMiOiI5MmIwNGQxODk4MmI5ZmRhNjEyNDUwYmQ5YjAzN2NhZmQ5YzExYTJlMzg1ODVhMzM5MzY5YjhiNzNmNDFlZTZiIiwidGFnIjoiIn0=', '2027-05-06', 1, '2026-05-06 04:01:18', '2026-05-06 04:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `verification_logs`
--

CREATE TABLE `verification_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `request_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verification_logs`
--

INSERT INTO `verification_logs` (`id`, `request_id`, `user_id`, `nik`, `method`, `result`, `ip_address`, `user_agent`, `timestamp`) VALUES
(1, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.215', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 06:00:53'),
(2, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.215', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 06:17:57'),
(3, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.215', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 06:22:42'),
(4, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.215', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 06:27:19'),
(5, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 06:28:36'),
(6, NULL, 1, '0204199306493975', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 07:40:58'),
(7, NULL, 1, '0204199306493975', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 07:41:13'),
(8, NULL, 1, '0204199306493975', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 07:42:25'),
(9, NULL, 1, '0204199306493975', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 07:46:30'),
(10, NULL, 1, '3003768389119937', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 07:46:43'),
(11, NULL, 1, '0204199306493975', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 07:46:57'),
(12, NULL, 1, '0204199306493975', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 07:48:58'),
(13, NULL, 1, '0204199306493975', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 07:49:14'),
(14, NULL, 1, '0204199306493975', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 08:00:54'),
(15, NULL, 1, '0204199306493975', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 08:01:02'),
(16, NULL, 1, '3003768389119937', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 08:01:07'),
(17, NULL, 1, '8677645003235905', 'NIK', 'ACTIVE', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 08:02:24'),
(18, NULL, 1, '0066935789043262', 'NIK', 'PENDING_REVIEW', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 08:18:15'),
(19, NULL, 1, '8677645003235905', 'NIK', 'ACTIVE', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 08:24:37'),
(20, NULL, 1, '8677645003235905', 'NIK', 'ACTIVE', '10.89.0.3', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 09:09:15'),
(21, NULL, 1, '8677645003235905', 'NIK', 'ACTIVE', '10.89.0.23', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 13:44:13'),
(22, NULL, 1, '8677645003235905', 'NIK', 'ACTIVE', '10.89.0.23', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 13:44:29'),
(23, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 03:57:52'),
(24, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:20:26'),
(25, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:22:19'),
(26, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:22:23'),
(27, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:22:44'),
(28, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:22:55'),
(29, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:23:42'),
(30, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:23:55'),
(31, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:25:05'),
(32, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:25:18'),
(33, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:25:29'),
(34, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:25:31'),
(35, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:25:32'),
(36, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:26:09'),
(37, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:26:47'),
(38, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:26:54'),
(39, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:28:51'),
(40, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:28:58'),
(41, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:30:48'),
(42, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:33:06'),
(43, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:33:11'),
(44, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:35:06'),
(45, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:36:50'),
(46, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:36:55'),
(47, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:36:56'),
(48, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:37:14'),
(49, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:37:16'),
(50, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:38:26'),
(51, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:40:21'),
(52, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:41:00'),
(53, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:41:08'),
(54, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:42:10'),
(55, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:44:00'),
(56, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:46:29'),
(57, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:47:44'),
(58, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:52:09'),
(59, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:53:39'),
(60, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:54:32'),
(61, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:55:01'),
(62, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 15:59:54'),
(63, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:01:35'),
(64, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:08:27'),
(65, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:10:51'),
(66, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:11:02'),
(67, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:12:05'),
(68, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:15:13'),
(69, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:15:39'),
(70, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:15:57'),
(71, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:20:43'),
(72, NULL, 2, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:22:55'),
(73, NULL, 2, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:23:00'),
(74, NULL, 1, '0003485827010970', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:24:20'),
(75, NULL, 1, '0125368268471489', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:24:37'),
(76, NULL, 1, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:24:55'),
(77, NULL, 6, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:25:00'),
(78, NULL, 6, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:25:23'),
(79, NULL, 6, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:34:31'),
(80, NULL, 1, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:35:23'),
(81, NULL, 2, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:36:48'),
(82, NULL, 6, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:37:01'),
(83, NULL, 2, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:40:14'),
(84, NULL, 2, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:42:35'),
(85, NULL, 2, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:42:39'),
(86, NULL, 2, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:43:41'),
(87, NULL, 6, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:45:12'),
(88, NULL, 6, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:46:26'),
(89, NULL, 6, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:47:13'),
(90, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:47:20'),
(91, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:47:43'),
(92, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 16:51:38'),
(93, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 16:56:13'),
(94, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 16:56:17'),
(95, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:00:12'),
(96, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:01:59'),
(97, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:02:02'),
(98, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:02:04'),
(99, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:02:05'),
(100, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:21'),
(101, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:22'),
(102, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:23'),
(103, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:26'),
(104, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:28'),
(105, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:29'),
(106, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:30'),
(107, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:35'),
(108, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:38'),
(109, NULL, 2, '0140915127418975', 'NIK', 'PENDING_REVIEW', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:04:40'),
(110, NULL, 6, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 17:09:42'),
(111, NULL, 6, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 17:09:46'),
(112, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:09:51'),
(113, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:10:04'),
(114, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:13:03'),
(115, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:13:09'),
(116, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 17:18:59'),
(117, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 17:19:01'),
(118, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 17:19:12'),
(119, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-06 17:21:26'),
(120, NULL, 2, '0204199306493975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-07 03:29:53'),
(121, NULL, 2, '0204199306493975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-07 03:32:52'),
(122, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 05:55:26'),
(123, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 05:55:41'),
(124, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 05:57:43'),
(125, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 05:57:49'),
(126, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:03:23'),
(127, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:12:54'),
(128, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:12:57'),
(129, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:18:04'),
(130, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:18:05'),
(131, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:18:23'),
(132, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:18:38'),
(133, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:18:39'),
(134, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:18:40'),
(135, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:18:42'),
(136, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:26:07'),
(137, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:26:08'),
(138, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:26:11'),
(139, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:26:12'),
(140, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:26:50'),
(141, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:33:05'),
(142, NULL, 6, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:33:08'),
(143, NULL, 2, '0140915127418975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:33:10'),
(144, NULL, 6, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 06:33:18'),
(145, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:33:20'),
(146, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:38:29'),
(147, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:54:03'),
(148, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 06:56:02'),
(149, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 07:12:40'),
(150, NULL, 2, '0140915127418975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 07:13:35'),
(151, NULL, 2, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 07:32:33'),
(152, NULL, 2, '0140915127418975', 'NIK', 'ACTIVE', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 07:52:32'),
(153, NULL, 2, '0204199306493975', 'NIK', 'UNREGISTERED', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 07:52:54'),
(154, NULL, 2, '0204199306493975', 'NIK', 'PENDING', '10.89.0.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 07:53:03'),
(155, NULL, 2, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:20:48'),
(156, NULL, 2, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:20:52'),
(157, NULL, 2, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:20:56'),
(158, NULL, 2, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:21:06'),
(159, NULL, 2, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:21:09'),
(160, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:21:41'),
(161, NULL, 1, '0237772649659152', 'NIK', 'UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:21:54'),
(162, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:22:09'),
(163, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:30:50'),
(164, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:30:52'),
(165, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:31:07'),
(166, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:31:08'),
(167, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED_REQUEST', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:31:11'),
(168, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:34:46'),
(169, NULL, 1, '0204199306493975', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:36:45'),
(170, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:37:44'),
(171, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:37:46'),
(172, NULL, 1, '0204199306493975', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:42:35'),
(173, NULL, 1, '0204199306493975', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:43:13'),
(174, NULL, 1, '0204199306493975', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:43:20'),
(175, NULL, 1, '0204199306493975', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:43:31'),
(176, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:49:03'),
(177, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:49:13'),
(178, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:50:06'),
(179, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:50:09'),
(180, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:50:10'),
(181, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:50:11'),
(182, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:50:12'),
(183, NULL, 1, '0204199306493975', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:52:47'),
(184, NULL, 1, '0204199306493975', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:52:54'),
(185, NULL, 1, '0204199306493975', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 08:53:22'),
(186, NULL, 1, '0204199306493975', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 08:57:56'),
(187, NULL, 1, '0204199306493975', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:01:49'),
(188, NULL, 2, '0204199306493975', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:07:44'),
(189, NULL, 2, '0204199306493975', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:07:45'),
(190, NULL, 2, '0204199306493975', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:08:57'),
(191, NULL, 2, '0204199306493975', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:13:25'),
(192, NULL, 2, '0548397596571350', 'NIK', 'UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:17:16'),
(193, NULL, 2, '0548397596571350', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:17:22'),
(194, NULL, 2, '0548397596571350', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:17:44'),
(195, NULL, 2, '0548397596571350', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:22:05'),
(196, NULL, 2, '0548397596571350', 'NIK', 'EXPIRED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:22:13'),
(197, NULL, 2, '0548397596571350', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:22:39'),
(198, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:22:56'),
(199, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:22:59'),
(200, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:26:30'),
(201, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:27:16'),
(202, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:28:16'),
(203, NULL, 2, '0548397596571350', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:28:21'),
(204, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:28:39'),
(205, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:29:22'),
(206, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:30:11'),
(207, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:34:29'),
(208, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:34:30'),
(209, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:35:55'),
(210, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:26'),
(211, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:28'),
(212, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:29'),
(213, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:29'),
(214, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:29'),
(215, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:30'),
(216, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:30'),
(217, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:30'),
(218, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:30'),
(219, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:30'),
(220, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:31'),
(221, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:31'),
(222, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:31'),
(223, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:31'),
(224, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:31'),
(225, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:31'),
(226, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:32'),
(227, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:32'),
(228, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:32'),
(229, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:32'),
(230, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:32'),
(231, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:34'),
(232, NULL, 2, '0548397596571350', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:36:45'),
(233, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:37:05'),
(234, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:50:43'),
(235, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-08 09:50:44'),
(236, NULL, 1, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 16:18:02'),
(237, NULL, 1, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 16:18:08'),
(238, NULL, 2, '0548397596571350', 'NIK', 'REJECTED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 19:11:31'),
(239, NULL, 2, '0548397596571350', 'NIK', 'PENDING', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 19:12:37'),
(240, NULL, 2, '0548397596571350', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 19:13:02'),
(241, NULL, 2, '0548397596571350', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-08 19:30:28');
INSERT INTO `verification_logs` (`id`, `request_id`, `user_id`, `nik`, `method`, `result`, `ip_address`, `user_agent`, `timestamp`) VALUES
(242, NULL, 2, '0548397596571350', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 06:15:35'),
(243, NULL, 2, '0548397596571350', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 06:17:04'),
(244, NULL, 2, '0548397596571350', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 06:17:24'),
(245, NULL, 2, '0548397596571350', 'NIK', 'ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:35:05'),
(246, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:39:52'),
(247, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:40:09'),
(248, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:40:10'),
(249, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:41:44'),
(250, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:43:39'),
(251, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:48:53'),
(252, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:48:55'),
(253, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:49:15'),
(254, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:49:21'),
(255, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:56:32'),
(256, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 06:59:57'),
(257, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:02:46'),
(258, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:02:50'),
(259, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:03:33'),
(260, NULL, 2, '0548397596571350', 'NIK', 'POV: ACTIVE | DOM: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:09:32'),
(261, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:10:19'),
(262, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:10:25'),
(263, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:10:49'),
(264, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:11:19'),
(265, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:11:30'),
(266, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:13:15'),
(267, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:14:16'),
(268, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:14:55'),
(269, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:21:49'),
(270, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:21:54'),
(271, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:31:35'),
(272, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:31:54'),
(273, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:36:10'),
(274, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:36:34'),
(275, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:37:05'),
(276, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:38:35'),
(277, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:39:32'),
(278, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:41:13'),
(279, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:41:42'),
(280, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:42:31'),
(281, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:42:49'),
(282, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:43:11'),
(283, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:43:38'),
(284, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:49:16'),
(285, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:49:27'),
(286, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-09 07:49:29'),
(287, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-10 05:41:15'),
(288, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-10 06:12:42'),
(289, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 03:40:47'),
(290, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 04:00:17'),
(291, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: PENDING', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 04:00:41'),
(292, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: REJECTED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 04:10:51'),
(293, NULL, 2, '0548397596571350', 'NIK', 'SKTM: PENDING | SKD: ACTIVE | PINDAH: REJECTED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 04:13:57'),
(294, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: REJECTED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 04:14:32'),
(295, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: REJECTED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 04:27:32'),
(296, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: REJECTED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 04:32:33'),
(297, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 04:45:18'),
(298, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 04:51:14'),
(299, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 04:56:19'),
(300, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 04:56:57'),
(301, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:33:12'),
(302, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:35:15'),
(303, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:35:36'),
(304, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:35:38'),
(305, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:38:36'),
(306, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:38:39'),
(307, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:38:56'),
(308, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:39:02'),
(309, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:39:04'),
(310, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:41:11'),
(311, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: REJECTED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:44:39'),
(312, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: PENDING', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:45:03'),
(313, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:45:21'),
(314, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:46:13'),
(315, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:46:40'),
(316, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:48:47'),
(317, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:48:59'),
(318, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:52:22'),
(319, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:52:52'),
(320, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:58:14'),
(321, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:58:16'),
(322, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:58:23'),
(323, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:58:49'),
(324, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:58:59'),
(325, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:59:36'),
(326, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:59:50'),
(327, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 05:59:57'),
(328, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:03:13'),
(329, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:03:17'),
(330, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:03:23'),
(331, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:03:48'),
(332, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:04:11'),
(333, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:07:14'),
(334, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:07:40'),
(335, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:11:07'),
(336, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:11:09'),
(337, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:11:14'),
(338, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:14:12'),
(339, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:15:46'),
(340, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:17:03'),
(341, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:20:00'),
(342, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:39:41'),
(343, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:44:09'),
(344, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:45:27'),
(345, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:46:11'),
(346, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:50:37'),
(347, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: EXPIRED | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:55:05'),
(348, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:56:01'),
(349, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: PENDING | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:05:32'),
(350, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:05:47'),
(351, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:06:50'),
(352, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:07:23'),
(353, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:07:24'),
(354, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:23:51'),
(355, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:23:53'),
(356, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:29:46'),
(357, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:33:06'),
(358, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:34:15'),
(359, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:34:23'),
(360, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:35:17'),
(361, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:36:08'),
(362, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 14:38:16'),
(363, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 14:42:09'),
(364, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 15:00:24'),
(365, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: PENDING | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 15:01:12'),
(366, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 15:01:35'),
(367, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 15:02:02'),
(368, NULL, 2, '1231231231231231', 'NIK', 'SKTM: UNREGISTERED | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 15:05:28'),
(369, NULL, 2, '1231231231231231', 'NIK', 'SKTM: UNREGISTERED | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 15:11:38'),
(370, NULL, 2, '1231231231231232', 'NIK', 'SKTM: UNREGISTERED | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 15:12:21'),
(371, NULL, 2, '1231231231231231', 'NIK', 'SKTM: UNREGISTERED | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 10:50:41'),
(372, NULL, 2, '1231231231231231', 'NIK', 'SKTM: UNREGISTERED | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.13', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 16:40:56'),
(373, NULL, 2, '0064622836742991', 'NIK', 'SKTM: UNREGISTERED | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.13', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 16:43:20'),
(374, NULL, 2, '0064622836742991', 'NIK', 'SKTM: PENDING | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.13', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 16:43:51'),
(375, NULL, 2, '0064622836742991', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.13', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 16:44:20'),
(376, NULL, 2, '0064622836742991', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.13', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 17:33:47'),
(377, NULL, 2, '0064622836742991', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.5', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-18 21:27:48'),
(378, NULL, 2, '0064622836742991', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.5', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-18 21:33:41'),
(379, NULL, 2, '0064622836742991', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.5', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-18 21:36:08'),
(380, NULL, 2, '0204199306493975', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-19 19:30:25'),
(381, NULL, 2, '0064622836742991', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-19 19:33:00'),
(382, NULL, 2, '0204199306493975', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-19 20:06:31'),
(383, NULL, 2, '0204199306493975', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-19 21:10:41'),
(384, NULL, 2, '0204199306493975', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-19 21:57:53'),
(385, NULL, 2, '2658156081791587', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 01:04:08'),
(386, NULL, 2, '2658156081791587', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:58:01'),
(387, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:58:05'),
(388, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:58:45'),
(389, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:58:46'),
(390, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:58:48'),
(391, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:58:49'),
(392, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:58:49'),
(393, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:59:03'),
(394, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:59:04'),
(395, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:59:05'),
(396, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 21:59:59'),
(397, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:00:00'),
(398, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: EXPIRED | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:00:18'),
(399, NULL, 2, '3721700552332154', 'NIK', 'SKTM: ACTIVE | SKD: PENDING | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:00:28'),
(400, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: ACTIVE | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:01:23'),
(401, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: ACTIVE | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:01:32'),
(402, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: ACTIVE | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:05:04'),
(403, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: ACTIVE | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:05:06'),
(404, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: ACTIVE | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:05:20'),
(405, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: ACTIVE | PINDAH: EXPIRED | MATI: ACTIVE', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:05:24'),
(406, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: ACTIVE | PINDAH: EXPIRED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:05:30'),
(407, NULL, 2, '0548397596571350', 'NIK', 'SKTM: EXPIRED | SKD: ACTIVE | PINDAH: EXPIRED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:06:46'),
(408, NULL, 2, '0548397596571350', 'NIK', 'SKTM: ACTIVE | SKD: ACTIVE | PINDAH: ACTIVE | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-20 22:06:52'),
(409, NULL, 2, '0003485827010970', 'NIK', 'SKTM: EXPIRED | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 23:06:49'),
(410, NULL, 2, '0003485827010970', 'NIK', 'SKTM: PENDING | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 23:07:04'),
(411, NULL, 2, '0003485827010970', 'NIK', 'SKTM: ACTIVE | SKD: UNREGISTERED | PINDAH: UNREGISTERED | MATI: UNREGISTERED', '10.89.0.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 23:08:39');

-- --------------------------------------------------------

--
-- Table structure for table `villages`
--

CREATE TABLE `villages` (
  `id` bigint UNSIGNED NOT NULL,
  `district_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `villages`
--

INSERT INTO `villages` (`id`, `district_id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sukamaju', '3201010001', '2026-05-04 05:32:21', '2026-05-04 05:32:21'),
(2, 1, 'Sukaraya', '3201010002', '2026-05-04 05:32:21', '2026-05-04 05:32:21'),
(3, 2, ' Bojong Gede', '3201020001', '2026-05-04 05:32:21', '2026-05-04 05:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `village_leaders`
--

CREATE TABLE `village_leaders` (
  `id` bigint UNSIGNED NOT NULL,
  `village_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `village_leaders`
--

INSERT INTO `village_leaders` (`id`, `village_id`, `user_id`, `name`, `nip`, `rank`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'Elian Hoeger', '198773491 20012 1 001', NULL, 1, '2026-05-04 05:32:21', '2026-05-04 05:46:27'),
(2, 2, 6, 'Milford Ruecker', '198495013 20014 1 001', NULL, 1, '2026-05-04 05:32:21', '2026-05-04 05:46:27'),
(3, 3, 7, 'Richmond Champlin', '198996653 20014 1 001', NULL, 1, '2026-05-04 05:32:21', '2026-05-04 05:46:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arrival_records`
--
ALTER TABLE `arrival_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arrival_records_recorded_by_foreign` (`recorded_by`),
  ADD KEY `arrival_records_citizen_nik_foreign` (`citizen_nik`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `citizens`
--
ALTER TABLE `citizens`
  ADD PRIMARY KEY (`nik`),
  ADD KEY `citizens_desa_id_foreign` (`desa_id`),
  ADD KEY `citizens_household_card_id_index` (`household_card_id`);

--
-- Indexes for table `death_records`
--
ALTER TABLE `death_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `death_records_citizen_nik_unique` (`citizen_nik`),
  ADD KEY `death_records_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `districts_code_unique` (`code`);

--
-- Indexes for table `district_leaders`
--
ALTER TABLE `district_leaders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `district_leaders_district_id_foreign` (`district_id`),
  ADD KEY `district_leaders_user_id_foreign` (`user_id`);

--
-- Indexes for table `domicile_records`
--
ALTER TABLE `domicile_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domicile_records_verified_by_foreign` (`verified_by`),
  ADD KEY `domicile_records_citizen_nik_index` (`citizen_nik`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `household_cards`
--
ALTER TABLE `household_cards`
  ADD PRIMARY KEY (`no_kk`),
  ADD KEY `household_cards_village_id_foreign` (`village_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `move_records`
--
ALTER TABLE `move_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `move_records_verified_by_foreign` (`verified_by`),
  ADD KEY `move_records_citizen_nik_index` (`citizen_nik`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `poverty_records`
--
ALTER TABLE `poverty_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poverty_records_verified_by_foreign` (`verified_by`),
  ADD KEY `poverty_records_citizen_nik_foreign` (`citizen_nik`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_requests_front_office_user_id_foreign` (`front_office_user_id`),
  ADD KEY `service_requests_citizen_nik_foreign` (`citizen_nik`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_desa_id_index` (`desa_id`),
  ADD KEY `users_district_id_foreign` (`district_id`);

--
-- Indexes for table `user_certificates`
--
ALTER TABLE `user_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_certificates_user_id_foreign` (`user_id`);

--
-- Indexes for table `verification_logs`
--
ALTER TABLE `verification_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verification_logs_request_id_foreign` (`request_id`),
  ADD KEY `verification_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `villages`
--
ALTER TABLE `villages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `villages_code_unique` (`code`),
  ADD KEY `villages_district_id_foreign` (`district_id`);

--
-- Indexes for table `village_leaders`
--
ALTER TABLE `village_leaders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `village_leaders_village_id_foreign` (`village_id`),
  ADD KEY `village_leaders_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arrival_records`
--
ALTER TABLE `arrival_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=815;

--
-- AUTO_INCREMENT for table `death_records`
--
ALTER TABLE `death_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `district_leaders`
--
ALTER TABLE `district_leaders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `domicile_records`
--
ALTER TABLE `domicile_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `move_records`
--
ALTER TABLE `move_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poverty_records`
--
ALTER TABLE `poverty_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_certificates`
--
ALTER TABLE `user_certificates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `verification_logs`
--
ALTER TABLE `verification_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=412;

--
-- AUTO_INCREMENT for table `villages`
--
ALTER TABLE `villages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `village_leaders`
--
ALTER TABLE `village_leaders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arrival_records`
--
ALTER TABLE `arrival_records`
  ADD CONSTRAINT `arrival_records_citizen_nik_foreign` FOREIGN KEY (`citizen_nik`) REFERENCES `citizens` (`nik`) ON DELETE CASCADE,
  ADD CONSTRAINT `arrival_records_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `citizens`
--
ALTER TABLE `citizens`
  ADD CONSTRAINT `citizens_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `villages` (`id`),
  ADD CONSTRAINT `citizens_household_card_id_foreign` FOREIGN KEY (`household_card_id`) REFERENCES `household_cards` (`no_kk`) ON DELETE SET NULL;

--
-- Constraints for table `death_records`
--
ALTER TABLE `death_records`
  ADD CONSTRAINT `death_records_citizen_nik_foreign` FOREIGN KEY (`citizen_nik`) REFERENCES `citizens` (`nik`) ON DELETE CASCADE,
  ADD CONSTRAINT `death_records_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `district_leaders`
--
ALTER TABLE `district_leaders`
  ADD CONSTRAINT `district_leaders_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `district_leaders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `domicile_records`
--
ALTER TABLE `domicile_records`
  ADD CONSTRAINT `domicile_records_citizen_nik_foreign` FOREIGN KEY (`citizen_nik`) REFERENCES `citizens` (`nik`) ON DELETE CASCADE,
  ADD CONSTRAINT `domicile_records_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `household_cards`
--
ALTER TABLE `household_cards`
  ADD CONSTRAINT `household_cards_village_id_foreign` FOREIGN KEY (`village_id`) REFERENCES `villages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `move_records`
--
ALTER TABLE `move_records`
  ADD CONSTRAINT `move_records_citizen_nik_foreign` FOREIGN KEY (`citizen_nik`) REFERENCES `citizens` (`nik`) ON DELETE CASCADE,
  ADD CONSTRAINT `move_records_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `poverty_records`
--
ALTER TABLE `poverty_records`
  ADD CONSTRAINT `poverty_records_citizen_nik_foreign` FOREIGN KEY (`citizen_nik`) REFERENCES `citizens` (`nik`) ON DELETE CASCADE,
  ADD CONSTRAINT `poverty_records_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_citizen_nik_foreign` FOREIGN KEY (`citizen_nik`) REFERENCES `citizens` (`nik`),
  ADD CONSTRAINT `service_requests_front_office_user_id_foreign` FOREIGN KEY (`front_office_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `user_certificates`
--
ALTER TABLE `user_certificates`
  ADD CONSTRAINT `user_certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `verification_logs`
--
ALTER TABLE `verification_logs`
  ADD CONSTRAINT `verification_logs_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`id`),
  ADD CONSTRAINT `verification_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `villages`
--
ALTER TABLE `villages`
  ADD CONSTRAINT `villages_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `village_leaders`
--
ALTER TABLE `village_leaders`
  ADD CONSTRAINT `village_leaders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `village_leaders_village_id_foreign` FOREIGN KEY (`village_id`) REFERENCES `villages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
