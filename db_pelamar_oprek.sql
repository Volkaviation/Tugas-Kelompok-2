-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 10, 2026 at 05:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pelamar_oprek`
--
CREATE DATABASE IF NOT EXISTS `db_pelamar_oprek` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `db_pelamar_oprek`;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_interview`
--

DROP TABLE IF EXISTS `jadwal_interview`;
CREATE TABLE `jadwal_interview` (
  `id_jadwal` int NOT NULL,
  `id_pelamar` int DEFAULT NULL,
  `nama_pelamar` varchar(100) NOT NULL,
  `posisi` varchar(50) NOT NULL,
  `tanggal_interview` date NOT NULL,
  `waktu_interview` time NOT NULL,
  `link_interview` varchar(255) DEFAULT NULL,
  `catatan` text,
  `status_interview` enum('DIJADWALKAN','BELUM HADIR','SELESAI','DIBATALKAN') DEFAULT 'DIJADWALKAN',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal_interview`
--

INSERT INTO `jadwal_interview` (`id_jadwal`, `id_pelamar`, `nama_pelamar`, `posisi`, `tanggal_interview`, `waktu_interview`, `link_interview`, `catatan`, `status_interview`, `created_at`) VALUES
(1, NULL, 'BUDI TESTING', 'MARKETING', '2026-01-11', '16:00:00', 'https://zoom.us/test123', 'Test setelah fix', 'DIJADWALKAN', '2026-01-10 04:13:18'),
(2, 22, 'Reza Firmansyah B', 'SUPERVISOR', '2026-01-11', '10:20:00', 'https://zoom.us/ghigj/1234d', 'Skill basic', 'SELESAI', '2026-01-10 04:15:32'),
(3, 153, 'el jhon wick', 'SUPERVISOR', '2026-01-12', '12:12:00', 'https://zoom.us/test123', '', 'DIJADWALKAN', '2026-01-10 05:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `pelamar`
--

DROP TABLE IF EXISTS `pelamar`;
CREATE TABLE `pelamar` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pendidikan` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `posisi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pengalaman_kerja` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cv` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_melamar` date DEFAULT NULL,
  `status_akhir` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelamar`
--

INSERT INTO `pelamar` (`id`, `nama`, `pendidikan`, `posisi`, `pengalaman_kerja`, `cv`, `tanggal_melamar`, `status_akhir`) VALUES
(1, 'Reza Firmansyah B', 'D3', 'Staff Admin', '1 tahun', 'Ada', '2024-03-23', 'Wawancara'),
(2, 'Yoga Aditya C', 'D3', 'Operator Mesin', '8 bulan', 'Ada', '2024-03-14', 'Wawancara'),
(3, 'Dinda Putri E', 'S2', 'Supervisor', '1 tahun', 'Ada', '2024-02-25', 'Tes Tulis'),
(4, 'Nina Sari D', 'SMK', 'Staff Akutansi', '2 tahun', 'Ada', '2024-11-26', 'Tidak Lolos'),
(5, 'Dewi Kania B', 'D3', 'Staff Akutansi', '0 tahun', 'Ada', '2024-01-17', 'Wawancara'),
(6, 'Rizka Amelia B', 'D3', 'Staff Admin', '1 tahun', 'Ada', '2024-05-04', 'Diterima'),
(7, 'Budi Santoso B', 'SMK', 'Customer Service', '1 tahun', 'Ada', '2024-08-09', 'Wawancara'),
(8, 'Sinta Lestari D', 'D3', 'Programmer', '1 tahun', 'Ada', '2024-10-10', 'Wawancara'),
(9, 'Dion Prasetyo E', 'S1', 'Customer Service', '2 tahun', 'Ada', '2024-08-11', 'Wawancara'),
(10, 'Ahmad Rizky A', 'SMK', 'Programmer', '0 tahun', 'Ada', '2024-05-17', 'Wawancara'),
(11, 'Agus Permana B', 'SMK', 'Supervisor', '1 tahun', 'Ada', '2024-08-08', 'Wawancara'),
(12, 'Reza Firmansyah C', 'S1', 'Data Analyst', '1 tahun', 'Ada', '2024-06-03', 'Tes Tulis'),
(13, 'Rama Wijaya D', 'SMK', 'Staff Admin', '2 tahun', 'Ada', '2024-07-10', 'Wawancara'),
(14, 'Tina Marlina B', 'S2', 'Supervisor', '1 tahun', 'Ada', '2024-09-04', 'Tidak Lolos'),
(15, 'Rizka Amelia E', 'SMK', 'Staff Admin', '0 tahun', 'Ada', '2024-06-24', 'Tidak Lolos'),
(16, 'Salsa Putri D', 'S2', 'Operator', '1 tahun', 'Ada', '2024-12-01', 'Wawancara'),
(17, 'Rafi Pratama B', 'S2', 'Supervisor', '1 tahun', 'Ada', '2024-11-05', 'Wawancara'),
(18, 'Ayu Wulandari C', 'SMK', 'Digital Marketing', '1 tahun', 'Ada', '2024-11-12', 'Diterima'),
(19, 'Budi Santoso B', 'S1', 'Programmer', '1 tahun', 'Ada', '2024-08-03', 'Tes Tulis'),
(20, 'Ahmad Rizky C', 'SMK', 'Operator Mesin', '2 tahun', 'Ada', '2024-09-29', 'Tidak Lolos'),
(21, 'Budi Santoso D', 'SMK', 'Designer', '2 tahun', 'Ada', '2024-08-31', 'Wawancara'),
(22, 'Reza Firmansyah B', 'S2', 'Supervisor', '1 tahun', 'Ada', '2024-11-10', 'Wawancara'),
(23, 'Lina Wati D', 'D3', 'Digital Marketing', '0 tahun', 'Ada', '2024-03-30', 'Tidak Lolos'),
(24, 'Nina Sari C', 'D3', 'Digital Marketing', '0 tahun', 'Ada', '2024-11-17', 'Diterima'),
(25, 'Rama Wijaya C', 'S2', 'Digital Marketing', '0 tahun', 'Ada', '2024-04-25', 'Tes Tulis'),
(26, 'Reza Firmansyah A', 'SMK', 'Staff Admin', '1 tahun', 'Ada', '2024-09-16', 'Wawancara'),
(27, 'Mira Septiani D', 'S1', 'Staff Admin', '0 tahun', 'Ada', '2024-01-04', 'Diterima'),
(28, 'Rizka Amelia B', 'S2', 'Customer Service', '1 tahun', 'Ada', '2024-03-26', 'Tes Tulis'),
(29, 'Reza Firmansyah B', 'SMK', 'Supervisor', '1 tahun', 'Ada', '2024-12-21', 'Tes Tulis'),
(30, 'Rama Wijaya D', 'SMK', 'Operator Mesin', '1 tahun', 'Ada', '2024-12-28', 'Diterima'),
(31, 'Dinda Putri E', 'SMK', 'Designer', '1 tahun', 'Ada', '2024-02-27', 'Diterima'),
(32, 'Ayu Wulandari E', 'SMK', 'Customer Service', '1 tahun', 'Ada', '2024-06-23', 'Diterima'),
(33, 'Ayu Wulandari B', 'SMK', 'Digital Marketing', '2 tahun', 'Ada', '2024-04-16', 'Tidak Lolos'),
(34, 'Dewi Kania B', 'SMK', 'Programmer', '7 bulan', 'Ada', '2024-11-17', 'Wawancara'),
(35, 'Reza Firmansyah A', 'SMK', 'Supervisor', '1 tahun', 'Ada', '2024-10-24', 'Diterima'),
(36, 'Agus Permana A', 'SMK', 'Staff Admin', '8 bulan', 'Ada', '2024-10-11', 'Wawancara'),
(37, 'Reza Firmansyah C', 'S2', 'Supervisor', '1 tahun', 'Ada', '2024-06-03', 'Tidak Lolos'),
(38, 'Rizka Amelia C', 'S2', 'Programmer', '0 tahun', 'Ada', '2024-09-22', 'Wawancara'),
(39, 'Mira Septiani E', 'SMK', 'Supervisor', '1 tahun', 'Ada', '2024-02-20', 'Diterima'),
(40, 'Tina Marlina B', 'SMK', 'Operator', '2 tahun', 'Ada', '2024-11-15', 'Tes Tulis'),
(41, 'Tina Marlina E', 'SMK', 'Designer', '1 tahun', 'Ada', '2024-05-11', 'Tidak Lolos'),
(42, 'Nina Sari A', 'SMK', 'Customer Service', '8 bulan', 'Ada', '2024-03-06', 'Diterima'),
(43, 'Rizka Amelia A', 'S2', 'Operator Mesin', '8 bulan', 'Ada', '2024-10-14', 'Tes Tulis'),
(44, 'Lina Wati A', 'SMK', 'Staff Admin', '7 bulan', 'Ada', '2024-02-27', 'Wawancara'),
(45, 'Fajar Ramdhan C', 'SMK', 'Operator Mesin', '2 tahun', 'Ada', '2024-02-16', 'Tes Tulis'),
(46, 'Yoga Aditya A', 'D3', 'Operator', '2 tahun', 'Ada', '2024-05-25', 'Diterima'),
(47, 'Agus Permana A', 'D3', 'Supervisor', '1 tahun', 'Ada', '2024-08-02', 'Tidak Lolos'),
(48, 'Clara Anjani A', 'D3', 'Customer Service', '0 tahun', 'Ada', '2024-09-18', 'Tes Tulis'),
(49, 'Tina Marlina C', 'SMK', 'Operator', '0 tahun', 'Ada', '2024-07-19', 'Diterima'),
(50, 'Clara Anjani B', 'S1', 'Supervisor', '1 tahun', 'Ada', '2024-03-22', 'Diterima'),
(51, 'Dewi Kania B', 'D3', 'Data Analyst', '1 tahun', 'Ada', '2024-09-09', 'Tidak Lolos'),
(52, 'Budi Santoso A', 'D3', 'Data Analyst', '1 tahun', 'Ada', '2024-09-22', 'Tes Tulis'),
(53, 'Rafi Pratama D', 'SMK', 'Operator', '0 tahun', 'Ada', '2024-08-23', 'Tes Tulis'),
(54, 'Rama Wijaya C', 'S1', 'Operator', '1 tahun', 'Ada', '2024-09-05', 'Diterima'),
(55, 'Ahmad Rizky E', 'SMK', 'Designer', '0 tahun', 'Ada', '2024-12-18', 'Wawancara'),
(56, 'Clara Anjani D', 'D3', 'Digital Marketing', '1 tahun', 'Ada', '2024-06-06', 'Wawancara'),
(58, 'Dewi Kania C', 'SMK', 'Customer Service', '1 tahun', 'Ada', '2024-02-22', 'Diterima'),
(59, 'Dewi Kania B', 'SMK', 'Customer Service', '1 tahun', 'Ada', '2024-02-14', 'Tes Tulis'),
(60, 'Fajar Ramdhan A', 'S2', 'Staff Admin', '0 tahun', 'Ada', '2024-10-18', 'Diterima'),
(61, 'Tina Marlina B', 'S2', 'Operator Mesin', '0 tahun', 'Ada', '2024-07-28', 'Wawancara'),
(62, 'Rafi Pratama A', 'D3', 'Operator', '1 tahun', 'Ada', '2024-06-05', 'Diterima'),
(63, 'Sinta Lestari B', 'D3', 'Programmer', '2 tahun', 'Ada', '2024-09-27', 'Tidak Lolos'),
(64, 'Rama Wijaya B', 'D3', 'Customer Service', '2 tahun', 'Ada', '2024-08-15', 'Tes Tulis'),
(65, 'Mira Septiani D', 'S2', 'Operator', '2 tahun', 'Ada', '2024-06-16', 'Tidak Lolos'),
(66, 'Fajar Ramdhan D', 'D3', 'Supervisor', '2 tahun', 'Ada', '2024-06-16', 'Wawancara'),
(67, 'Lina Wati A', 'SMK', 'Supervisor', '2 tahun', 'Ada', '2024-09-18', 'Wawancara'),
(68, 'Nina Sari E', 'S1', 'Supervisor', '2 tahun', 'Ada', '2024-07-27', 'Tes Tulis'),
(69, 'Budi Santoso C', 'S1', 'Operator Mesin', '1 tahun', 'Ada', '2024-06-14', 'Tidak Lolos'),
(70, 'Dion Prasetyo E', 'SMK', 'Staff Admin', '1 tahun', 'Ada', '2024-07-29', 'Tidak Lolos'),
(71, 'Rama Wijaya E', 'SMK', 'Staff Admin', '1 tahun', 'Ada', '2024-12-17', 'Wawancara'),
(72, 'Reza Firmansyah D', 'D3', 'Digital Marketing', '1 tahun', 'Ada', '2024-04-19', 'Tes Tulis'),
(73, 'Sinta Lestari E', 'S2', 'Operator', '1 tahun', 'Ada', '2024-11-21', 'Tes Tulis'),
(74, 'Tina Marlina C', 'D3', 'Staff Akutansi', '1 tahun', 'Ada', '2024-11-16', 'Tidak Lolos'),
(75, 'Dewi Kania E', 'SMK', 'Designer', '1 tahun', 'Ada', '2024-11-03', 'Tidak Lolos'),
(76, 'Reza Firmansyah D', 'S2', 'Supervisor', '1 tahun', 'Ada', '2024-12-08', 'Diterima'),
(77, 'Rizka Amelia C', 'S1', 'Customer Service', '0 tahun', 'Ada', '2024-09-07', 'Wawancara'),
(78, 'Tina Marlina E', 'S2', 'Staff Akutansi', '1 tahun', 'Ada', '2024-07-28', 'Tes Tulis'),
(79, 'Agus Permana B', 'SMK', 'Data Analyst', '1 tahun', 'Ada', '2024-06-11', 'Diterima'),
(80, 'Ayu Wulandari E', 'S2', 'Operator Mesin', '0 tahun', 'Ada', '2024-08-25', 'Diterima'),
(81, 'Clara Anjani A', 'S1', 'Programmer', '0 tahun', 'Ada', '2024-11-07', 'Wawancara'),
(82, 'Dinda Putri D', 'SMK', 'Staff Akutansi', '1 tahun', 'Ada', '2024-01-12', 'Diterima'),
(83, 'Dion Prasetyo E', 'D3', 'Operator', '1 tahun', 'Ada', '2024-05-21', 'Wawancara'),
(84, 'Tina Marlina C', 'S1', 'Programmer', '8 bulan', 'Ada', '2024-02-23', 'Wawancara'),
(85, 'Clara Anjani B', 'S1', 'Digital Marketing', '5 bulan', 'Ada', '2024-01-24', 'Wawancara'),
(86, 'Dinda Putri B', 'S2', 'Digital Marketing', '5 bulan', 'Ada', '2024-04-01', 'Tidak Lolos'),
(87, 'Rafi Pratama E', 'S2', 'Programmer', '8 bulan', 'Ada', '2024-07-10', 'Tidak Lolos'),
(88, 'Agus Permana A', 'D3', 'Operator Mesin', '8 bulan', 'Ada', '2024-05-11', 'Tes Tulis'),
(89, 'Nina Sari C', 'S1', 'Customer Service', '5 bulan', 'Ada', '2024-08-29', 'Wawancara'),
(90, 'Ayu Wulandari A', 'D3', 'Operator Mesin', '8 bulan', 'Ada', '2024-02-14', 'Diterima'),
(91, 'Lina Wati A', 'SMK', 'Operator Mesin', '5 bulan', 'Ada', '2024-12-05', 'Wawancara'),
(92, 'Tina Marlina A', 'SMK', 'Staff Admin', '1 tahun', 'Ada', '2024-06-28', 'Wawancara'),
(93, 'Yoga Aditya B', 'SMK', 'Staff Admin', '0 tahun', 'Ada', '2024-11-15', 'Tes Tulis'),
(94, 'Agus Permana D', 'S2', 'Customer Service', '0 tahun', 'Ada', '2024-08-06', 'Wawancara'),
(95, 'Rama Wijaya D', 'S1', 'Supervisor', '0 tahun', 'Ada', '2024-09-22', 'Diterima'),
(96, 'Rama Wijaya E', 'S2', 'Programmer', '0 tahun', 'Ada', '2024-12-05', 'Diterima'),
(97, 'Agus Permana D', 'S1', 'Data Analyst', '0 tahun', 'Ada', '2024-12-06', 'Diterima'),
(98, 'Rafi Pratama A', 'S2', 'Staff Admin', '1 tahun', 'Ada', '2024-05-12', 'Tes Tulis'),
(99, 'Rizka Amelia C', 'S1', 'Digital Marketing', '0 tahun', 'Ada', '2024-08-26', 'Wawancara'),
(100, 'Reza Firmansyah D', 'S2', 'Customer Service', '0 tahun', 'Ada', '2024-11-02', 'Diterima'),
(101, 'Ahmad Rizky A', 'S1', 'Operator', '0 tahun', 'Ada', '2024-10-05', 'Wawancara'),
(102, 'Agus Permana D', 'D3', 'Staff Admin', '0 tahun', 'Ada', '2024-02-10', 'Tidak Lolos'),
(103, 'Agus Permana D', 'SMK', 'Staff Akutansi', '1 tahun', 'Ada', '2024-09-20', 'Wawancara'),
(104, 'Dinda Putri C', 'S1', 'Designer', '0 tahun', 'Ada', '2024-12-10', 'Tes Tulis'),
(105, 'Mira Septiani A', 'D3', 'Supervisor', '1 tahun', 'Ada', '2024-01-24', 'Tidak Lolos'),
(106, 'Rizka Amelia A', 'S1', 'Operator', '0 tahun', 'Ada', '2024-01-03', 'Tes Tulis'),
(107, 'Yoga Aditya E', 'S1', 'Data Analyst', '1 tahun', 'Ada', '2024-08-10', 'Diterima'),
(108, 'Nina Sari A', 'SMK', 'Supervisor', '0 tahun', 'Ada', '2024-02-03', 'Tidak Lolos'),
(109, 'Mira Septiani B', 'S1', 'Customer Service', '1 tahun', 'Ada', '2024-06-03', 'Wawancara'),
(110, 'Fajar Ramdhan C', 'S2', 'Data Analyst', '1 tahun', 'Ada', '2024-03-23', 'Diterima'),
(111, 'Mira Septiani C', 'SMK', 'Designer', '1 tahun', 'Ada', '2024-08-08', 'Tidak Lolos'),
(112, 'Agus Permana A', 'D3', 'Programmer', '1 tahun', 'Ada', '2024-06-29', 'Tes Tulis'),
(113, 'Rizka Amelia B', 'D3', 'Staff Akutansi', '0 tahun', 'Ada', '2024-05-15', 'Tes Tulis'),
(114, 'Nina Sari C', 'D3', 'Operator Mesin', '1 tahun', 'Ada', '2024-07-19', 'Wawancara'),
(115, 'Rizka Amelia E', 'S2', 'Designer', '0 tahun', 'Ada', '2024-02-07', 'Wawancara'),
(116, 'Yoga Aditya E', 'SMK', 'Supervisor', '8 bulan', 'Ada', '2024-09-01', 'Diterima'),
(117, 'Lina Wati B', 'S2', 'Operator', '7 bulan', 'Ada', '2024-09-09', 'Tes Tulis'),
(118, 'Fajar Ramdhan D', 'D3', 'Staff Admin', '0 tahun', 'Ada', '2024-01-18', 'Diterima'),
(119, 'Budi Santoso E', 'S2', 'Staff Admin', '1 tahun', 'Ada', '2024-12-08', 'Tes Tulis'),
(120, 'Budi Santoso A', 'S2', 'Designer', '0 tahun', 'Ada', '2024-08-19', 'Wawancara'),
(121, 'Sinta Lestari C', 'D3', 'Programmer', '0 tahun', 'Ada', '2024-02-28', 'Diterima'),
(122, 'Yoga Aditya A', 'SMK', 'Digital Marketing', '1 tahun', 'Ada', '2024-11-27', 'Tidak Lolos'),
(123, 'Dewi Kania E', 'S1', 'Customer Service', '0 tahun', 'Ada', '2024-04-04', 'Wawancara'),
(124, 'Nina Sari B', 'S1', 'Operator', '0 tahun', 'Ada', '2024-11-02', 'Diterima'),
(125, 'Reza Firmansyah E', 'S2', 'Programmer', '1 tahun', 'Ada', '2024-06-21', 'Tes Tulis'),
(126, 'Clara Anjani D', 'S2', 'Digital Marketing', '1 tahun', 'Ada', '2024-01-03', 'Tes Tulis'),
(127, 'Rafi Pratama D', 'S2', 'Customer Service', '1 tahun', 'Ada', '2024-12-05', 'Tes Tulis'),
(128, 'Fajar Ramdhan E', 'SMK', 'Digital Marketing', '0 tahun', 'Ada', '2024-01-07', 'Tidak Lolos'),
(129, 'Sinta Lestari D', 'S1', 'Digital Marketing', '1 tahun', 'Ada', '2024-07-11', 'Wawancara'),
(130, 'Ayu Wulandari C', 'D3', 'Supervisor', '0 tahun', 'Ada', '2024-09-26', 'Tidak Lolos'),
(131, 'Rizka Amelia D', 'D3', 'Staff Akutansi', '0 tahun', 'Ada', '2024-06-22', 'Tes Tulis'),
(132, 'Agus Permana C', 'S2', 'Operator', '1 tahun', 'Ada', '2024-04-30', 'Wawancara'),
(133, 'Tina Marlina E', 'S2', 'Programmer', '1 tahun', 'Ada', '2024-05-09', 'Tes Tulis'),
(134, 'Reza Firmansyah A', 'S2', 'Customer Service', '1 tahun', 'Ada', '2024-07-25', 'Diterima'),
(135, 'Mira Septiani C', 'SMK', 'Operator Mesin', '1 tahun', 'Ada', '2024-05-09', 'Tidak Lolos'),
(136, 'Rizka Amelia E', 'SMK', 'Operator Mesin', '1 tahun', 'Ada', '2024-07-05', 'Tidak Lolos'),
(137, 'Reza Firmansyah E', 'S2', 'Programmer', '1 tahun', 'Ada', '2024-03-19', 'Tidak Lolos'),
(138, 'Ahmad Rizky E', 'D3', 'Digital Marketing', '0 tahun', 'Ada', '2024-01-02', 'Diterima'),
(139, 'Dion Prasetyo D', 'SMK', 'Supervisor', '1 tahun', 'Ada', '2024-06-30', 'Tes Tulis'),
(140, 'Agus Permana C', 'SMK', 'Operator Mesin', '2 tahun', 'Ada', '2024-09-08', 'Wawancara'),
(141, 'Mira Septiani B', 'S1', 'Staff Akutansi', '2 tahun', 'Ada', '2024-05-24', 'Diterima'),
(142, 'Sinta Lestari A', 'S1', 'Staff Admin', '2 tahun', 'Ada', '2024-08-14', 'Wawancara'),
(143, 'Dion Prasetyo E', 'S1', 'Staff Admin', '1 tahun', 'Ada', '2024-06-14', 'Tes Tulis'),
(144, 'Ahmad Rizky B', 'S1', 'Operator', '0 tahun', 'Ada', '2024-10-23', 'Diterima'),
(145, 'Rama Wijaya B', 'S1', 'Operator', '1 tahun', 'Ada', '2024-04-21', 'Diterima'),
(146, 'Agus Permana E', 'S1', 'Data Analyst', '1 tahun', 'Ada', '2024-11-26', 'Wawancara'),
(147, 'Fajar Ramdhan B', 'S1', 'Staff Akutansi', '2 tahun', 'Ada', '2024-09-27', 'Diterima'),
(148, 'Fajar Ramdhan C', 'D3', 'Data Analyst', '0 tahun', 'Ada', '2024-11-17', 'Wawancara'),
(149, 'Clara Anjani C', 'S1', 'Data Analyst', '1 tahun', 'Ada', '2024-07-13', 'Wawancara'),
(150, 'Rama Wijaya E', 'S2', 'Data Analyst', '0 tahun', 'Ada', '2024-11-14', 'Tidak Lolos'),
(151, 'TEST USER AUTO', 'S1', 'SUPERVISOR', '2 tahun', 'Ada', '2026-01-10', 'Tes Tulis'),
(152, 'Test Pelamar Baru', 'S1', 'MARKETING', '3 tahun+', 'Ada', '2026-01-10', 'Wawancara'),
(153, 'el jhon wick', 'S1', 'SUPERVISOR', '5 bulan', 'Ada', '2026-01-10', 'Wawancara'),
(154, 'Siwa', 'S2', 'SUPERVISOR', '3 tahun+', 'Ada', '2026-01-10', 'Wawancara');

-- --------------------------------------------------------

--
-- Table structure for table `posisi_lowongan`
--

DROP TABLE IF EXISTS `posisi_lowongan`;
CREATE TABLE `posisi_lowongan` (
  `id_posisi` int NOT NULL,
  `nama_posisi` varchar(50) NOT NULL,
  `deskripsi` text,
  `requirements` text,
  `jumlah_dibutuhkan` int DEFAULT '1',
  `status` enum('AKTIF','TIDAK AKTIF') DEFAULT 'AKTIF',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `email`, `password`) VALUES
(1, 'rafiqy', 'rafiqy@gmail.com', 'newpassword123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadwal_interview`
--
ALTER TABLE `jadwal_interview`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_pelamar` (`id_pelamar`);

--
-- Indexes for table `pelamar`
--
ALTER TABLE `pelamar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posisi_lowongan`
--
ALTER TABLE `posisi_lowongan`
  ADD PRIMARY KEY (`id_posisi`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jadwal_interview`
--
ALTER TABLE `jadwal_interview`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pelamar`
--
ALTER TABLE `pelamar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `posisi_lowongan`
--
ALTER TABLE `posisi_lowongan`
  MODIFY `id_posisi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_interview`
--
ALTER TABLE `jadwal_interview`
  ADD CONSTRAINT `jadwal_interview_ibfk_1` FOREIGN KEY (`id_pelamar`) REFERENCES `pelamar` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
