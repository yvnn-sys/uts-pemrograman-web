-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 27, 2026 at 01:20 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `nilaimhs`
--

CREATE TABLE `nilaimhs` (
  `nim` varchar(20) NOT NULL,
  `nama_mhs` varchar(100) NOT NULL,
  `nilai_akhir` decimal(5,2) NOT NULL,
  `nilai_huruf` varchar(2) NOT NULL,
  `predikat` varchar(20) NOT NULL,
  `preg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilaimhs`
--

INSERT INTO `nilaimhs` (`nim`, `nama_mhs`, `nilai_akhir`, `nilai_huruf`, `predikat`, `preg_date`, `foto`) VALUES
('3202416015', 'Willie', 81.00, 'B', 'Memuaskan', '2026-10-15 03:26:59', NULL),
('3202416027', 'Jordi', 81.00, 'A', 'Sangat Memuaskan', '2026-10-27 11:24:22', '6ae089e66395f.jpg'),
('3202416029', 'Bobon', 69.00, 'C', 'Cukup Memuaskan', '2026-10-27 11:23:32', '6ae089b41bea6.jpg'),
('3202416067', 'Sasuke', 78.00, 'B', 'Memuaskan', '2026-10-27 13:08:47', '6ae0a25ff2dfc.png'),
('3202416103', 'Jojo', 68.00, 'C', 'Cukup Memuaskan', '2026-10-15 04:08:45', NULL),
('3202416107', 'Jono', 60.00, 'C', 'Cukup Memuaskan', '2026-10-15 04:12:14', NULL),
('3202416129', 'Uni', 86.00, 'A', 'Sangat Memuaskan', '2026-10-15 04:20:23', NULL),
('3202416130', 'Lala', 58.00, 'D', 'Kurang Memuaskan', '2026-10-15 04:21:36', NULL),
('3202416167', 'Odan', 79.00, 'B', 'Memuaskan', '2026-10-15 04:21:08', NULL),
('3202416173', 'Witan', 93.00, 'A', 'Sangat Memuaskan', '2026-10-15 04:22:13', NULL),
('3202416199', 'Surya', 99.00, 'A', 'Sangat Memuaskan', '2026-10-24 08:42:01', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nilaimhs`
--
ALTER TABLE `nilaimhs`
  ADD PRIMARY KEY (`nim`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
