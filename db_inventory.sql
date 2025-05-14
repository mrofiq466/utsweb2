-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 14, 2025 at 06:05 AM
-- Server version: 8.0.39-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_inventory`
--

CREATE TABLE `tb_inventory` (
  `id_barang` int NOT NULL,
  `kode_barang` varchar(20) DEFAULT NULL,
  `nama_barang` varchar(50) DEFAULT NULL,
  `jumlah_barang` int DEFAULT NULL,
  `satuan_barang` varchar(20) DEFAULT NULL,
  `harga_beli` double(20,2) DEFAULT NULL,
  `status_barang` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_inventory`
--

INSERT INTO `tb_inventory` (`id_barang`, `kode_barang`, `nama_barang`, `jumlah_barang`, `satuan_barang`, `harga_beli`, `status_barang`) VALUES
(1, 'BRG001', 'Laptop Lenovo', 10, 'pcs', 7500000.00, 1),
(2, 'BRG002', 'Mouse Logitech', 25, 'pcs', 150000.00, 1),
(3, 'BRG003', 'Printer Canon', 5, 'pcs', 2000000.00, 1),
(4, 'BRG004', 'Kertas A4', 100, 'pcs', 45000.00, 1),
(5, 'BRG005', 'Flashdisk 32GB', 40, 'pcs', 75000.00, 1),
(6, 'BRG006', 'Tinta Printer', 20, 'liter', 120000.00, 1),
(7, 'BRG007', 'Kabel LAN 10m', 15, 'meter', 35000.00, 1),
(8, 'BRG008', 'Meja Kantor', 8, 'pcs', 950000.00, 1),
(9, 'BRG009', 'Kursi Kantor', 12, 'pcs', 650000.00, 1),
(10, 'BRG010', 'Monitor Samsung', 6, 'pcs', 1800000.00, 1),
(11, 'BRG011', 'Scanner Epson', 3, 'pcs', 2500000.00, 1),
(12, 'BRG012', 'Rak Arsip', 10, 'pcs', 400000.00, 1),
(13, 'BRG013', 'Stabilo Warna', 60, 'pcs', 5000.00, 1),
(14, 'BRG014', 'Harddisk Eksternal 1TB', 7, 'pcs', 850000.00, 1),
(15, 'BRG015', 'Kalkulator Scientific', 18, 'pcs', 125000.00, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  ADD PRIMARY KEY (`id_barang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
