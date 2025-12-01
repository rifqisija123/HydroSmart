-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 25, 2025 at 10:04 PM
-- Server version: 10.5.27-MariaDB-cll-lve
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smknkot1_wadah_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `drink_prices`
--

CREATE TABLE `drink_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `drink` varchar(255) NOT NULL,
  `ml` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drink_prices`
--

INSERT INTO `drink_prices` (`id`, `drink`, `ml`, `price`, `created_at`, `updated_at`) VALUES
(1, 'kopi', 150, 8000, '2025-11-24 23:56:31', '2025-11-25 02:26:37'),
(2, 'kopi', 200, 10000, '2025-11-24 23:59:11', '2025-11-25 00:16:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drink_prices`
--
ALTER TABLE `drink_prices`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drink_prices`
--
ALTER TABLE `drink_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
