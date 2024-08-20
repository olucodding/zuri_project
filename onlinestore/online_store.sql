-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2024 at 12:57 AM
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
-- Database: `online_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `cart_items` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `product_name`, `product_image`, `total_price`, `cart_items`, `created_at`, `product_price`) VALUES
(62, 4, 2, 7, 'black grey double monkstraps', 'black-grey-double-monkstraps-PARSONS.jpg', 1890, NULL, '2024-08-20 18:47:47', 270),
(63, 4, 1, 8, 'black grey double monkstraps', 'black-grey-double-monkstraps-PARSONS.jpg', 2120, NULL, '2024-08-20 18:47:51', 265),
(64, 4, 3, 2, 'black grey double monkstraps', 'black-grey-double-monkstraps-PARSONS.jpg', 510, NULL, '2024-08-20 18:47:55', 255),
(65, 4, 4, 4, 'brown croco moc-toe shoes', 'brown-croco-moc-toe-EAVES.jpg', 1100, NULL, '2024-08-20 18:48:01', 275);

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `username`, `password`, `full_name`, `email`, `phone_number`, `created_at`, `updated_at`, `status`) VALUES
(1, 'manager1', '$2y$10$KJ4qBYruHvK8usqYg3cdQ.xrU3FcX1RjOBk88Ro7rZqMj1OEul2xC', 'Mager Testing', 'manager@example.com', '2348000000001', '2024-08-11 18:16:16', '2024-08-11 18:16:16', 'active'),
(3, 'manager2', '$2y$10$p5cKQnvePbZPhxTehDx/lOHyP1dSNohE/f7VpSLLTWk2DYu7ao97O', 'manager2 teasting2', 'manager2@example.com', '2348000000002', '2024-08-11 18:25:53', '2024-08-11 18:25:53', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_name` varchar(255) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `product_status` enum('pending','shipped','delivered','canceled') DEFAULT 'pending',
  `product_name` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `carrier` varchar(255) DEFAULT NULL,
  `shipping_status` enum('Pending','Shipped','Delivered','Returned') DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT 'Pending',
  `order_status` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total`, `created_at`, `customer_name`, `order_date`, `product_status`, `product_name`, `product_image`, `product_id`, `tracking_number`, `carrier`, `shipping_status`, `shipping_address`, `payment_method`, `payment_status`, `order_status`) VALUES
(10, 1, 7573.38, '2024-08-20 09:36:05', NULL, NULL, 'pending', NULL, NULL, NULL, '0000000001', 'DHL', 'Shipped', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(11, 2, 14292.13, '2024-08-20 10:04:30', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(12, 2, 1144.88, '2024-08-20 11:54:36', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(13, 2, 0.00, '2024-08-20 11:54:36', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', 'pending'),
(14, 3, 1144.88, '2024-08-20 11:57:48', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(15, 3, 854.63, '2024-08-20 12:03:59', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(16, 3, 12195.88, '2024-08-20 12:29:19', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(17, 1, 5154.63, '2024-08-20 12:50:58', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(18, 1, 1144.88, '2024-08-20 12:53:38', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(19, 1, 0.00, '2024-08-20 12:53:39', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(20, 1, 849.25, '2024-08-20 13:06:49', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(21, 1, 1472.75, '2024-08-20 13:12:04', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(22, 4, 8600.00, '2024-08-20 18:33:07', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', ''),
(23, 4, 5724.38, '2024-08-20 18:34:45', NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'Pending', '112 LATEEF JAKANDE CRESCENT\r\nOFF ALAKIJA EXTENSION\r\nLAGOS,\r\nNIGERIA', 'Bank Transfer', 'Pending', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `order_id` int(11) NOT NULL,
  `order_name` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `order_status` enum('pending','shipped','delivered','canceled') DEFAULT 'pending',
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `product_image` varchar(255) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `product_image`, `shipping_address`, `user_id`) VALUES
(8, 10, 2, 4, 270.00, '2024-08-20 09:36:05', NULL, NULL, 0),
(9, 10, 1, 10, 265.00, '2024-08-20 09:36:05', NULL, NULL, 0),
(10, 10, 3, 13, 255.00, '2024-08-20 09:36:05', NULL, NULL, 0),
(11, 11, 1, 11, 265.00, '2024-08-20 10:04:30', NULL, NULL, 0),
(12, 11, 2, 12, 270.00, '2024-08-20 10:04:30', NULL, NULL, 0),
(13, 11, 3, 28, 255.00, '2024-08-20 10:04:30', NULL, NULL, 0),
(14, 12, 4, 1, 275.00, '2024-08-20 11:54:36', NULL, NULL, 0),
(15, 12, 2, 1, 270.00, '2024-08-20 11:54:36', NULL, NULL, 0),
(16, 12, 1, 1, 265.00, '2024-08-20 11:54:36', NULL, NULL, 0),
(17, 12, 3, 1, 255.00, '2024-08-20 11:54:36', NULL, NULL, 0),
(18, 14, 2, 1, 270.00, '2024-08-20 11:57:48', NULL, NULL, 0),
(19, 14, 3, 1, 255.00, '2024-08-20 11:57:48', NULL, NULL, 0),
(20, 14, 1, 1, 265.00, '2024-08-20 11:57:48', NULL, NULL, 0),
(21, 14, 4, 1, 275.00, '2024-08-20 11:57:48', NULL, NULL, 0),
(22, 15, 3, 1, 255.00, '2024-08-20 12:03:59', NULL, NULL, 0),
(23, 15, 1, 1, 265.00, '2024-08-20 12:03:59', NULL, NULL, 0),
(24, 15, 4, 1, 275.00, '2024-08-20 12:03:59', NULL, NULL, 0),
(25, 16, 1, 12, 265.00, '2024-08-20 12:29:19', NULL, NULL, 0),
(26, 16, 4, 13, 275.00, '2024-08-20 12:29:19', NULL, NULL, 0),
(27, 16, 2, 17, 270.00, '2024-08-20 12:29:19', NULL, NULL, 0),
(28, 17, 3, 5, 255.00, '2024-08-20 12:50:58', NULL, NULL, 0),
(29, 17, 2, 3, 270.00, '2024-08-20 12:50:58', NULL, NULL, 0),
(30, 17, 1, 4, 265.00, '2024-08-20 12:50:58', NULL, NULL, 0),
(31, 17, 4, 6, 275.00, '2024-08-20 12:50:58', NULL, NULL, 0),
(32, 18, 2, 1, 270.00, '2024-08-20 12:53:38', NULL, NULL, 0),
(33, 18, 4, 1, 275.00, '2024-08-20 12:53:38', NULL, NULL, 0),
(34, 18, 3, 1, 255.00, '2024-08-20 12:53:38', NULL, NULL, 0),
(35, 18, 1, 1, 265.00, '2024-08-20 12:53:38', NULL, NULL, 0),
(36, 20, 2, 1, 270.00, '2024-08-20 13:06:49', NULL, NULL, 0),
(37, 20, 3, 1, 255.00, '2024-08-20 13:06:49', NULL, NULL, 0),
(38, 20, 1, 1, 265.00, '2024-08-20 13:06:49', NULL, NULL, 0),
(39, 21, 2, 1, 270.00, '2024-08-20 13:12:04', NULL, NULL, 0),
(40, 21, 4, 4, 275.00, '2024-08-20 13:12:04', NULL, NULL, 0),
(41, 22, 1, 8, 265.00, '2024-08-20 18:33:07', NULL, NULL, 0),
(42, 22, 2, 6, 270.00, '2024-08-20 18:33:07', NULL, NULL, 0),
(43, 22, 3, 7, 255.00, '2024-08-20 18:33:07', NULL, NULL, 0),
(44, 22, 4, 9, 275.00, '2024-08-20 18:33:07', NULL, NULL, 0),
(45, 23, 2, 7, 270.00, '2024-08-20 18:34:45', NULL, NULL, 0),
(46, 23, 1, 4, 265.00, '2024-08-20 18:34:45', NULL, NULL, 0),
(47, 23, 3, 5, 255.00, '2024-08-20 18:34:45', NULL, NULL, 0),
(48, 23, 4, 4, 275.00, '2024-08-20 18:34:45', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_payment`
--

CREATE TABLE `order_payment` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` enum('Credit Card','Debit Card','PayPal','Bank Transfer','Cash On Delivery') NOT NULL,
  `payment_status` enum('Pending','Completed','Failed','Refunded') NOT NULL,
  `payment_date` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_tracking`
--

CREATE TABLE `order_tracking` (
  `tracking_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` enum('pending','cancelled','out of stock','shipped','delivered') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_status` enum('active','inactive','hidden') DEFAULT 'active',
  `hidden` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `created_at`, `category`, `brand`, `supplier`, `quantity`, `product_image`, `product_status`, `hidden`) VALUES
(1, 'black grey double monkstraps', 'black grey double monkstraps', 265.99, '2024-08-19 21:34:58', 'CUSTOM SHOES', 'PARSONS', '', 130, 'black-grey-double-monkstraps-PARSONS.jpg', 'active', 1),
(2, 'black grey double monkstraps', 'black grey double monkstraps', 270.00, '2024-08-18 19:45:50', 'CUSTOM SHOES', 'PARSONS', '', 68, 'black-grey-double-monkstraps-PARSONS.jpg', 'active', 1),
(3, 'black grey double monkstraps', 'black grey double monkstraps', 255.99, '2024-08-18 19:57:18', 'CUSTOM SHOES', 'PARSONS', '', 105, 'black-grey-double-monkstraps-PARSONS.jpg', 'active', 0),
(4, 'brown croco moc-toe shoes', '', 275.99, '2024-08-18 20:27:00', 'CUSTOM SHOES', 'EAVES', '', 121, 'brown-croco-moc-toe-EAVES.jpg', 'active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`) VALUES
(1, 'user', 'Standard user with limited access'),
(2, 'manager', 'Manager with additional privileges'),
(3, 'admin', 'Administrator with full access');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `shipping_apt_no` varchar(50) DEFAULT NULL,
  `shipping_street_address` varchar(255) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_country` varchar(100) DEFAULT NULL,
  `billing_apt_no` varchar(50) DEFAULT NULL,
  `billing_street_address` varchar(255) DEFAULT NULL,
  `billing_state` varchar(100) DEFAULT NULL,
  `billing_country` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `profile_picture`, `shipping_apt_no`, `shipping_street_address`, `shipping_state`, `shipping_country`, `billing_apt_no`, `billing_street_address`, `billing_state`, `billing_country`, `phone_number`, `name`, `role_id`, `full_name`) VALUES
(1, 'user1', 'user1@sample.com', '$2y$10$h3lqjbpyYIWK6337nIRmeea/bLB00EZDQSHi6z3r/SFcTWvpynIaC', '2024-08-10 12:26:21', NULL, '65746', '6476', '647', '46756', '65756', '657456', '6576', '65746', '65y56', NULL, 1, 'yu56'),
(2, 'user2', 'user2@example.com', '$2y$10$eNRnUF13jQ5nQo0t4.hAb.zMzYT8cDcpVJL1zn.gX3OiYnGjWCIuC', '2024-08-10 13:00:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(3, 'user3', 'user3@example.com', '$2y$10$a3bDMisf9r73ezZzDhD.v.2a.iCQWxRoqWhwLt3oF3qrzqM9RCusm', '2024-08-10 13:05:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(4, 'user4', 'user4@example.com', '$2y$10$1fFBzTCb2eOUz/pnonzNheoSXKGHFtDxzNIgGJLHdp2RtYARJ68qG', '2024-08-10 18:19:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(5, 'user5', 'user5@example.com', '$2y$10$MO4NjLVJ8OtNWtOcmEo0KeHgeY0g.E5iSK55xc8la5TvUQjWx4pV.', '2024-08-15 06:49:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'user5 testing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_id` (`order_name`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_order_id` (`order_id`),
  ADD KEY `fk_product_id` (`product_id`);

--
-- Indexes for table `order_payment`
--
ALTER TABLE `order_payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD PRIMARY KEY (`tracking_id`),
  ADD KEY `fk_order_tracking` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `order_payment`
--
ALTER TABLE `order_payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_tracking`
--
ALTER TABLE `order_tracking`
  MODIFY `tracking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `order_history_ibfk_1` FOREIGN KEY (`order_name`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_history_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `order_history_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_payment`
--
ALTER TABLE `order_payment`
  ADD CONSTRAINT `order_payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_payment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD CONSTRAINT `fk_order_tracking` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
