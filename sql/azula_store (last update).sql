-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2026 at 04:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `azula_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'azula', '2026-02-10 00:46:24');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `qty`, `created_at`, `updated_at`) VALUES
(1, 0, 0, 1, '2026-04-07 06:35:32', '2026-04-07 06:35:32'),
(3, 0, 0, 1, '2026-04-07 06:37:25', '2026-04-07 06:37:25'),
(4, 0, 0, 1, '2026-04-07 06:37:26', '2026-04-07 06:37:26'),
(5, 0, 0, 1, '2026-04-07 06:37:26', '2026-04-07 06:37:26'),
(6, 0, 0, 1, '2026-04-07 06:37:26', '2026-04-07 06:37:26'),
(7, 5, 28, 14, '2026-04-08 03:40:29', '2026-04-09 03:01:43'),
(8, 3, 28, 1, '2026-04-08 04:10:02', '2026-04-08 04:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`id`, `name`, `username`, `email`, `password`, `status`, `created_at`) VALUES
(6, '', 'Fauzan', 'fauzanris@gmail.com', 'fauzan12', 'active', '2026-04-08 23:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `proof` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('pending','processing','shipped','delivered') DEFAULT 'pending',
  `expedition_name` varchar(50) DEFAULT NULL,
  `shipping_type` varchar(50) DEFAULT NULL,
  `shipping_cost` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `address`, `payment_method`, `total`, `proof`, `created_at`, `user_id`, `status`, `expedition_name`, `shipping_type`, `shipping_cost`) VALUES
(25, 'Jl. Ikhlas RT005 RW011 Tanah Baru Beji Depok', 'transfer', 910000, '1775700590_ChatGPT Image 5 Apr 2026, 20.37.29.png', '2026-04-09 09:09:50', 5, 'pending', 'J&T Express', 'Ekspres', 30000),
(26, 'Jl. Ikhlas RT005 RW011 Tanah Baru Beji Depok', 'transfer', 95000, '1775701862_WhatsApp Image 2026-04-07 at 10.54.58 AM.jpeg', '2026-04-09 09:31:02', 5, '', 'SiCepat', 'Reguler', 15000),
(27, 'Jl. Ikhlas RT005 RW011 Tanah Baru Beji Depok', 'transfer', 95000, '1775702012_Sign up-cuate.png', '2026-04-09 09:33:32', 5, 'pending', 'JNE', 'Reguler', 15000);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `price`) VALUES
(6, 6, 28, 5, 200000),
(7, 7, 28, 1, 80000),
(8, 8, 28, 1, 80000),
(9, 9, 28, 1, 80000),
(10, 10, 28, 1, 80000),
(11, 11, 28, 1, 80000),
(12, 12, 28, 1, 80000),
(13, 13, 28, 1, 80000),
(14, 14, 28, 1, 80000),
(15, 15, 28, 1, 80000),
(16, 16, 28, 1, 80000),
(17, 17, 28, 2, 80000),
(18, 18, 28, 1, 80000),
(19, 19, 28, 1, 80000),
(20, 20, 28, 3, 80000),
(21, 21, 28, 1, 80000),
(22, 22, 28, 4, 80000),
(23, 23, 28, 10, 80000),
(24, 24, 28, 1, 80000),
(25, 25, 28, 11, 80000),
(26, 26, 28, 1, 80000),
(27, 27, 28, 1, 80000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `stock`, `price`, `picture`, `created_at`, `description`) VALUES
(28, 'Mens Premium Quick Dry Polo Collar Shirt Short Sleeve Moisture Wicking Fabric', 'shirt', 86, 80000, '1775524970_id-11134207-7ra0g-mdelgydwpxfw7e.png', '2026-02-25', 'Rugby Polo Shirt Pria Wanita Black Gold adalah pilihan sempurna untuk gaya santai yang keren.\r\n\r\nDesain Modern: Kombinasi warna hitam dan cream yang menarik.\r\n\r\nKenyamanan: Terbuat dari bahan berkualitas untuk kenyamanan sehari-hari.\r\n\r\nKesesuaian: Cocok untuk berbagai kesempatan, baik formal maupun informal.\r\n\r\nPadu padan yang ideal untuk tampilan stylish dan nyaman di setiap kesempatan!\r\n\r\nProduk baru Kaos Polo \r\n\r\nSlamat Datang di ARC Apparel\r\n\r\nMaterial yang Kita gunakan\r\n\r\n100% Cotton Premium Anti Bakteri\r\n\r\nKriteria matrial yang tidak menerawang, sangat halus dan lembut tidak menyebabkan iritasi kepada kulit kita, material yang sejuk saat digunakan, menyerap keringat tidak membuat gerah.\r\n\r\nSablon DTF premium\r\n\r\nTekstur yang lembut tidak mudah retak, warna yang solid, gambar yang tajam,warna sablon tidak luntur. finishing sablon doff .\r\n\r\nJahitan rantai pada pundak\r\n\r\noverdeck di tepian tangan dan badan\r\n\r\ndouble stick di bagian Kerah \r\n\r\ndengan demikian membuat pakaian lebih kokoh.\r\n\r\nSize chart\r\n\r\nharap lihat dalam bentuk ukuran centimeter karena setiap postur tubuh berbeda beda walaupun umur sama.\r\n\r\nHarap ukur kembali agar tidak ada kesalahan pemilihan size\r\n\r\nTabel Size ada di Foto\r\n\r\nNB : Jika ada stock yang kosong pasti kita konfirmasi , jadi tidak usah ragu untuk order ya .');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `total_price` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `proof_of_payment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `avatar`, `address`) VALUES
(5, 'acit', 'iniayuma.a@gmail.com', '$2y$10$dNRQSb.YwYWyRnhr2UUNdOxNxnpbfzX2VNhIVmUG4lrfjVyx5BV9i', '2026-04-07 10:52:35', 'uploads/avatars/1775615917_ChatGPT Image 6 Apr 2026, 08.32.13.png', 'Jl. Ikhlas RT005 RW011 Tanah Baru Beji Depok');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
