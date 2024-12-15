-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 14, 2024 at 04:56 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kalasenja_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_m_menu`
--

CREATE TABLE `tbl_m_menu` (
  `id_tmm` int NOT NULL,
  `menu_tmm` varchar(50) NOT NULL,
  `price_tmm` decimal(10,2) NOT NULL,
  `desc_tmm` text,
  `photo_tmm` varchar(255) NOT NULL,
  `categoryMenu_tmm` varchar(255) NOT NULL,
  `stock_tmm` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_m_menu`
--

INSERT INTO `tbl_m_menu` (`id_tmm`, `menu_tmm`, `price_tmm`, `desc_tmm`, `photo_tmm`, `categoryMenu_tmm`, `stock_tmm`) VALUES
(23, 'air', '10000.00', '0', '../uploads/download.jpeg', 'Beverage', 5),
(24, 'makanan', '20000.00', '0', '../uploads/4699.png', 'Food', 3),
(25, 'Rendang Hikaru', '10000.00', '0', '../uploads/WhatsApp Image 2024-06-29 at 12.58.43.jpeg', 'Food', 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_m_order`
--

CREATE TABLE `tbl_m_order` (
  `id_tmo` int NOT NULL,
  `dateOrder_tmo` datetime NOT NULL,
  `id_tmu` int NOT NULL,
  `typeOrder_tmo` enum('Take Away','Dine-in') DEFAULT NULL,
  `statusOrder_tmo` varchar(10) DEFAULT NULL,
  `id_tmpm` int NOT NULL,
  `totalOrder_tmo` decimal(10,2) NOT NULL,
  `cashNominal_tmo` decimal(10,2) DEFAULT NULL,
  `customerName_tmo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_m_order`
--

INSERT INTO `tbl_m_order` (`id_tmo`, `dateOrder_tmo`, `id_tmu`, `typeOrder_tmo`, `statusOrder_tmo`, `id_tmpm`, `totalOrder_tmo`, `cashNominal_tmo`, `customerName_tmo`) VALUES
(61, '2024-12-12 19:41:49', 8, 'Dine-in', 'Open', 1, '20.00', '20.00', 'konz'),
(62, '2024-12-12 19:43:57', 8, 'Dine-in', 'Open', 1, '20.00', '20.00', 'k'),
(63, '2024-12-12 19:57:56', 8, 'Dine-in', 'Open', 1, '20.00', '20.00', 'kanjut'),
(64, '2024-12-12 20:00:13', 8, 'Dine-in', 'Open', 1, '0.00', '0.00', 'a'),
(65, '2024-12-12 20:05:57', 8, 'Dine-in', 'Open', 1, '20.00', '20.00', 'c'),
(66, '2024-12-12 20:13:39', 8, 'Dine-in', 'Open', 1, '10000.00', '10000.00', 'kanjut'),
(67, '2024-12-12 20:14:47', 8, 'Dine-in', 'Open', 1, '20000.00', '20000.00', 'ngentot'),
(68, '2024-12-12 20:40:12', 4, 'Dine-in', 'Open', 1, '20000.00', '20000.00', 'o'),
(69, '2024-12-13 23:02:35', 8, 'Dine-in', 'Open', 1, '50000.00', '50000.00', 'kontolodon'),
(70, '2024-12-14 11:38:57', 8, 'Dine-in', 'Open', 3, '20000.00', '20000.00', 'ajarudes');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_m_paymentmethod`
--

CREATE TABLE `tbl_m_paymentmethod` (
  `id_tmpm` int NOT NULL,
  `paymentMethod_tmpm` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_m_paymentmethod`
--

INSERT INTO `tbl_m_paymentmethod` (`id_tmpm`, `paymentMethod_tmpm`) VALUES
(3, 'BCA'),
(1, 'Dana'),
(2, 'QRIS');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_m_user`
--

CREATE TABLE `tbl_m_user` (
  `id_tmu` int NOT NULL,
  `email_tmu` varchar(50) NOT NULL,
  `name_tmu` varchar(50) NOT NULL,
  `password_tmu` varchar(255) DEFAULT NULL,
  `role_tmu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_m_user`
--

INSERT INTO `tbl_m_user` (`id_tmu`, `email_tmu`, `name_tmu`, `password_tmu`, `role_tmu`) VALUES
(2, 'admin@example.com', 'admin', 'admin', 'admin'),
(4, 'ajar@gmail.com', 'ajar', 'hikarurendang', 'user'),
(6, 'ajar1@gmail.com', 'ajar1', '$2y$10$d0Di0b6xBTqb0gkgdIFlL.g/0qK81RTEbTya.5HpZRYn0d.o72WSi', 'admin'),
(7, 'ajar2@gmail.com', 'ajar2', '$2y$10$d1llEIGXX91jgmipKeeMxOmU1nk60if6PYKpmv2i1P3bNOatxV6dG', 'user'),
(8, 'ajare@gmail.com', 'ajarrr', '1', 'cashier');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_t_order`
--

CREATE TABLE `tbl_t_order` (
  `id_tto` int NOT NULL,
  `note_tto` text,
  `quantity_tto` int NOT NULL,
  `subTotal_tto` decimal(10,2) NOT NULL,
  `id_tmm` int DEFAULT NULL,
  `id_tmo` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_t_order`
--

INSERT INTO `tbl_t_order` (`id_tto`, `note_tto`, `quantity_tto`, `subTotal_tto`, `id_tmm`, `id_tmo`) VALUES
(64, '', 2, '20000.00', 23, 61),
(65, '', 1, '20000.00', 24, 62),
(66, '', 1, '20000.00', 24, 63),
(67, '', 1, '10000.00', 23, 64),
(68, '', 1, '20000.00', 24, 65),
(69, '', 1, '10000.00', 23, 66),
(70, '', 1, '20000.00', 24, 67),
(71, '', 1, '20000.00', 24, 68),
(72, '', 3, '30000.00', 23, 69),
(73, '', 1, '20000.00', 24, 69),
(74, '', 1, '20000.00', 24, 70);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_m_menu`
--
ALTER TABLE `tbl_m_menu`
  ADD PRIMARY KEY (`id_tmm`);

--
-- Indexes for table `tbl_m_order`
--
ALTER TABLE `tbl_m_order`
  ADD PRIMARY KEY (`id_tmo`),
  ADD KEY `id_tmu` (`id_tmu`),
  ADD KEY `id_tmpm` (`id_tmpm`);

--
-- Indexes for table `tbl_m_paymentmethod`
--
ALTER TABLE `tbl_m_paymentmethod`
  ADD PRIMARY KEY (`id_tmpm`),
  ADD UNIQUE KEY `paymentMethod_tmpm` (`paymentMethod_tmpm`);

--
-- Indexes for table `tbl_m_user`
--
ALTER TABLE `tbl_m_user`
  ADD PRIMARY KEY (`id_tmu`),
  ADD UNIQUE KEY `email_tmu` (`email_tmu`);

--
-- Indexes for table `tbl_t_order`
--
ALTER TABLE `tbl_t_order`
  ADD PRIMARY KEY (`id_tto`),
  ADD KEY `idx_id_tmm` (`id_tmm`),
  ADD KEY `idx_id_tmo` (`id_tmo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_m_menu`
--
ALTER TABLE `tbl_m_menu`
  MODIFY `id_tmm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_m_order`
--
ALTER TABLE `tbl_m_order`
  MODIFY `id_tmo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `tbl_m_paymentmethod`
--
ALTER TABLE `tbl_m_paymentmethod`
  MODIFY `id_tmpm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_m_user`
--
ALTER TABLE `tbl_m_user`
  MODIFY `id_tmu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_t_order`
--
ALTER TABLE `tbl_t_order`
  MODIFY `id_tto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_m_order`
--
ALTER TABLE `tbl_m_order`
  ADD CONSTRAINT `tbl_m_order_ibfk_1` FOREIGN KEY (`id_tmu`) REFERENCES `tbl_m_user` (`id_tmu`),
  ADD CONSTRAINT `tbl_m_order_ibfk_2` FOREIGN KEY (`id_tmpm`) REFERENCES `tbl_m_paymentmethod` (`id_tmpm`);

--
-- Constraints for table `tbl_t_order`
--
ALTER TABLE `tbl_t_order`
  ADD CONSTRAINT `fk_tbl_t_order_menu` FOREIGN KEY (`id_tmm`) REFERENCES `tbl_m_menu` (`id_tmm`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbl_t_order_tmo` FOREIGN KEY (`id_tmo`) REFERENCES `tbl_m_order` (`id_tmo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
