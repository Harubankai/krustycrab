-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 03:24 PM
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
-- Database: `qtieeyons`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_02_10_153429_create_users_table', 1),
(2, '2026_03_06_100533_create_userss_table', 1),
(3, '2026_02_10_155128_create_sessions_table', 2),
(4, '2026_03_06_102943_create_user_table', 2),
(5, '2026_03_06_103410_create_user_table', 3),
(6, '2026_03_06_115809_create_users_table', 4),
(7, '2026_03_06_123958_add_password_reset_to_users_table', 5),
(8, '2026_03_06_124829_add_password_reset_to_user_table', 6),
(9, '2026_03_06_125322_add_password_reset_to_user_table', 7),
(12, '2026_03_06_125726_add_password_reset_to_user_table', 8),
(13, '2026_03_10_000001_insert_default_admin_user', 9),
(14, '2026_03_10_120000_add_contact_fields_to_user_table', 10),
(15, '2026_03_12_000001_insert_default_rider_user', 10);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `total_items` int(11) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Preparing',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `customer_id`, `total_items`, `total_price`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ORD-1773836245345', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:28', '2026-03-18 04:17:28'),
(2, 'ORD-1773836246993', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:30', '2026-03-18 04:17:30'),
(3, 'ORD-1773836248233', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:36', '2026-03-18 04:17:36'),
(4, 'ORD-1773836248425', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:39', '2026-03-18 04:17:39'),
(5, 'ORD-1773836248601', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:42', '2026-03-18 04:17:42'),
(6, 'ORD-1773836249345', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:44', '2026-03-18 04:17:44'),
(7, 'ORD-1773836249521', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:47', '2026-03-18 04:17:47'),
(8, 'ORD-1773836249697', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:49', '2026-03-18 04:17:49'),
(9, 'ORD-1773836249865', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:53', '2026-03-18 04:17:53'),
(10, 'ORD-1773836250033', 4, 2, 100.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:17:56', '2026-03-18 04:17:56'),
(11, 'ORD-1773836365048', 4, 1, 45.00, 'Cash on Delivery', 'Preparing', '2026-03-18 04:19:27', '2026-03-18 04:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'customer',
  `password_reset_token` varchar(255) DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `phone`, `address`, `password`, `role`, `password_reset_token`, `token_expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Yona Pandakkk', 'akatsukiharold25@gmail.com', NULL, 'qwqee', '$2y$12$YV9pqIQNzYaXp28gd5pYVezc30mSa/zfhFPvXQ3h0ku12lS61VOaO', 'customer', 'lStkhrLyKeI6P81OR38a5LiG4xDqH3pJZ7rdRNYK1EaaMGZkv71G8yIWDAQN', '2026-03-06 15:12:04', '2026-03-06 05:20:08', '2026-03-12 06:46:39'),
(2, 'Yona Ningz', 'yonaayawon@gmail.com', NULL, NULL, '$2y$12$RCpmyvJ/AvXf.LMVTajde.FXE9.ZOHamolqWi8PUE8mAAFQapMX1q', 'customer', NULL, NULL, '2026-03-09 07:23:11', '2026-03-12 04:05:04'),
(4, 'Admin', 'admin@gmail.com', NULL, NULL, '$2y$12$cKwtgD2t7nVyJqdMPr7cIedNsbbuKwVt8p5WTMuBwLR/kgEIUvKz2', 'admin', NULL, NULL, '2026-03-09 08:03:51', '2026-03-09 08:03:51'),
(5, 'Riderr', 'rider@gmail.com', NULL, 'wwwww', '$2y$12$hS2bCBlC59noOobotnw1EuKxsLOnWzFUoJzttADz38Np78f3H.tFe', 'rider', NULL, NULL, '2026-03-12 03:49:48', '2026-03-12 06:47:44'),
(6, 'Leona', 'ayawonleona@gmail.com', NULL, NULL, '$2y$12$E97I.Dnw9did3tDwl9.v8u.CeJEeCn7p21hGMnCNdLpfTChF3NvN6', 'customer', 'QA08XlkJ8EvE6Ts0FL0vrcYENR2rg9Bl8gWxl2OBFwlKd60clMdf343weqdH', '2026-03-12 12:54:03', '2026-03-12 03:53:42', '2026-03-12 03:54:03'),
(7, 'mang elyot', 'aasas@gmail.com', '092222222121212', NULL, '$2y$12$GNhWyYBzzAqy3Yn08NO8KO3lwszGde1sMPF4/yMhtFwCwDikBxV4G', 'rider', NULL, NULL, '2026-03-12 07:47:24', '2026-03-12 07:47:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
