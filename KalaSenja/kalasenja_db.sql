-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 15, 2024 at 11:44 AM
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
(24, 'makanannn', '20000.00', '0', '../uploads/4699.png', 'Food', 3),
(25, 'Rendang Hikaru', '10000.00', '0', '../uploads/image (8).png', 'Beverage', 10),
(26, 'ak', '20000.00', '0', '../uploads/4699.png', 'Beverage', 0);

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
(91, '2024-12-14 22:54:15', 7, 'Dine-in', 'Open', 2, '10000.00', '10000.00', 'muhammad azdhar'),
(92, '2024-12-14 22:54:45', 7, 'Dine-in', 'Open', 3, '20000.00', '20000.00', 'muhammad azdhar'),
(93, '2024-12-14 23:07:30', 11, 'Dine-in', 'Open', 3, '60000.00', '60000.00', 'bbb'),
(94, '2024-12-14 23:08:21', 11, 'Dine-in', 'Open', 3, '20000.00', '20000.00', 'bbb'),
(95, '2024-12-14 23:10:25', 11, 'Dine-in', 'Open', 3, '30000.00', '30000.00', 'ag'),
(96, '2024-12-14 23:14:00', 12, 'Dine-in', 'Close', 2, '40000.00', '40000.00', 'c'),
(97, '2024-12-15 00:58:13', 14, 'Take Away', 'Open', 1, '10000.00', '10000.00', 'v'),
(98, '2024-12-15 15:47:01', 11, 'Dine-in', 'Open', 9, '0.00', '0.00', 'b'),
(99, '2024-12-15 15:52:46', 11, 'Dine-in', 'Open', 9, '500000.00', '20000.00', 'v'),
(100, '2024-12-15 15:54:36', 11, 'Dine-in', 'Open', 9, '20000.00', '10000.00', 'g'),
(101, '2024-12-15 15:55:16', 11, 'Dine-in', 'Open', 9, '60000.00', '700000.00', 'b');

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
(9, 'anjay'),
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
  `role_tmu` varchar(50) NOT NULL,
  `animal_tmu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_m_user`
--

INSERT INTO `tbl_m_user` (`id_tmu`, `email_tmu`, `name_tmu`, `password_tmu`, `role_tmu`, `animal_tmu`) VALUES
(2, 'admin@example.com', 'admin', 'admin', 'admin', NULL),
(4, 'ajar@gmail.com', 'ajar', 'hikarurendang', 'user', NULL),
(6, 'ajar1@gmail.com', 'ajar1', '$2y$10$d0Di0b6xBTqb0gkgdIFlL.g/0qK81RTEbTya.5HpZRYn0d.o72WSi', 'admin', NULL),
(7, 'ajar2@gmail.com', 'ajar2', '$2y$10$d1llEIGXX91jgmipKeeMxOmU1nk60if6PYKpmv2i1P3bNOatxV6dG', 'user', NULL),
(8, 'ajare@gmail.com', 'ajarrr', '1', 'cashier', NULL),
(9, 'admin1@gmail.com', 'admin1', '$2y$10$Jr6cvo917dVWKclivIRNgu/ayi.d4PJOw0D9ueZD4dxRN3cjzjpGa', 'admin', NULL),
(10, 'admin2@gmail.com', 'admin2', '$2y$10$VrkidVFGvwXhQXNDaTB9kerL6frsQ9uZh05mXTAF5HqRC2NRD3UGi', 'admin', NULL),
(11, 'cashier1@gmail.com', 'cashier1', '$2y$10$sh4J3Z7pnSZp0IuvUypQPe7CNh2KcKqTO/a0nfof/SIfXMvWGsZ6K', 'cashier', NULL),
(12, 'user@gmail.com', 'user', '$2y$10$cjqCIsYmyfpQ4bV/8mpSi.uEo6GzuIQNRqtu8w5zpDKwsk8cYzUBG', 'user', NULL),
(13, 'azdharsyahputra@gmail.com', 'azdhar', '$2y$10$VUTcaiS5sk12uvaQV1SPCOn1XzFUEGxRtQ8vMy/Ufq9T.eVNsPhxi', 'user', NULL),
(14, 'aku@gmail.com', 'aku', '$2y$10$nQjZDinN/slUXjlTUimh0.O0hYFmGNc.QLuW9zYZcp2onK9fuZTNy', 'user', 'gajah duduk');

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
(101, '', 1, '10000.00', 25, 91),
(102, '', 1, '20000.00', 24, 92),
(103, 'b', 3, '60000.00', 24, 93),
(104, 'r', 2, '20000.00', 25, 94),
(105, 'ff', 3, '30000.00', 25, 95),
(106, 'b', 4, '40000.00', 25, 96),
(107, '', 1, '10000.00', 25, 97),
(108, '', 1, '10000.00', 25, 98),
(109, '', 1, '10000.00', 23, 98),
(110, '', 1, '20000.00', 24, 99),
(111, '', 1, '20000.00', 24, 100),
(112, '', 3, '60000.00', 24, 101);

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
  MODIFY `id_tmm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_m_order`
--
ALTER TABLE `tbl_m_order`
  MODIFY `id_tmo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `tbl_m_paymentmethod`
--
ALTER TABLE `tbl_m_paymentmethod`
  MODIFY `id_tmpm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_m_user`
--
ALTER TABLE `tbl_m_user`
  MODIFY `id_tmu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_t_order`
--
ALTER TABLE `tbl_t_order`
  MODIFY `id_tto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

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
