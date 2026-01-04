-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2025 at 04:00 PM
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
-- Database: `cycle_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `mountain`
--

CREATE TABLE `mountain` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mountain`
--

INSERT INTO `mountain` (`id`, `name`, `price`, `stock`, `category`, `image`, `created_at`) VALUES
(501, 'pump', 5000.00, 100, '', 'A1.jpg', '2025-07-30 05:37:15'),
(701, 'helmet', 3000.00, 100, '', 'A3.jpg', '2025-07-29 15:40:04'),
(702, 'Tire', 800.00, 100, '', 'A6.webp', '2025-07-29 15:43:38');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9');

-- --------------------------------------------------------

--
-- Table structure for table `ch`
--

CREATE TABLE `ch` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ebikes`
--

CREATE TABLE `ebikes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebikes`
--

INSERT INTO `ebikes` (`id`, `name`, `price`, `stock`, `category`, `image`, `created_at`) VALUES
(801, 'Alpha Cycles Aerodynamics Suit max', 5000.00, 50, '', 'C1.webp', '2025-07-28 17:54:16'),
(802, ' Alpha Cycles Aerodynamic Suit (adidas edition)', 4500.00, 50, '', 'C6.webp', '2025-07-28 17:55:09'),
(803, 'Alpha Cycles Athlete Suits Pro', 3000.00, 50, '', 'C2.webp', '2025-07-28 17:59:57'),
(804, 'Alpha Cycles Athlete Suit Combo', 6000.00, 50, '', 'C4.webp', '2025-07-28 18:00:47'),
(805, 'Alphacyles Aerodynamic jacket', 3750.00, 25, '', 'C5.webp', '2025-07-29 04:41:35');

-- --------------------------------------------------------

--
-- Table structure for table `ebikes`
--

CREATE TABLE `ebikes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebikes`
--

INSERT INTO `ebikes` (`id`, `name`, `price`, `stock`, `category`, `image`, `created_at`) VALUES
(501, 'EBIKE PRO MAXV', 40000.00, 100, '', 'E6.jpg', '2025-08-01 16:30:54'),
(601, 'Alpha Cycles Electric Bike Pro', 40000.00, 100, '', 'E1.jpg', '2025-07-29 05:13:52'),
(602, 'Alpha Cycles Electric Pro III', 45000.00, 100, '', 'E4.jpg', '2025-07-29 05:14:36'),
(603, 'Alpha Cycles Electric Pro I', 50000.00, 100, '', 'E2.jpg', '2025-07-29 05:16:41'),
(604, 'Alpha Cycles Electric Pro V', 53000.00, 100, '', 'E4.jpg', '2025-07-29 05:17:13'),
(608, 'EBIKE PRO MAX III', 45000.00, 100, '', 'E3.jpg', '2025-08-01 16:31:54');

-- --------------------------------------------------------

--
-- Table structure for table `ebikes`
--

CREATE TABLE `ebikes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ebikes`
--

CREATE TABLE `ebikes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebikes`
--

INSERT INTO `ebikes` (`id`, `name`, `price`, `stock`, `category`, `image`, `created_at`) VALUES
(501, 'ebikes Cycle I', 15000.00, 100, '', 'G5.jpg', '2025-07-29 06:34:14');

-- --------------------------------------------------------

--
-- Table structure for table `mountain`
--

CREATE TABLE `mountain` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mountain`
--

INSERT INTO `mountain` (`id`, `name`, `price`, `stock`, `category`, `image`, `created_at`) VALUES
(101, 'NUKE 24D', 15600.00, 100, '', 'E1.jpg', '2025-07-28 18:05:31'),
(102, 'mtbII', 10000.00, 100, '', 'CH1.webp', '2025-07-31 18:37:32'),
(108, 'MTB III', 45000.00, 120, '', '_SPL9942_.webp', '2025-08-01 16:49:48');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `order_date` date DEFAULT NULL,
  `order_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `customer_name`, `customer_email`, `customer_address`, `customer_phone`, `total_amount`, `status`, `order_date`, `order_time`) VALUES
(1, 'ORD688F6D27B6927', 'jay kesti', 'jayvardhankesti@gmail.com', 'B2 17 laxmivrindhavan\r\npimple saudhagar', '7757056688', 83308.00, 'Shipped', '0000-00-00', '16:07:35'),
(2, 'ORD688F6DE34D953', 'jay kesti', 'jayvardhankesti@gmail.com', 'B2 17 laxmivrindhavan\r\npimple saudhagar', '7757056688', 83308.00, 'Cancelled', '0000-00-00', '16:10:43');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `gst` decimal(10,2) DEFAULT NULL,
  `total_with_gst` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `product_price`, `quantity`, `gst`, `total_with_gst`) VALUES
(1, 'ORD688F6D27B6927', 'NUKE 24D', 15600.00, 1, 2808.00, 18408.00),
(2, 'ORD688F6D27B6927', 'mtbII', 10000.00, 1, 1800.00, 11800.00),
(3, 'ORD688F6D27B6927', 'MTB III', 45000.00, 1, 8100.00, 53100.00),
(4, 'ORD688F6DE34D953', 'NUKE 24D', 15600.00, 1, 2808.00, 18408.00),
(5, 'ORD688F6DE34D953', 'mtbII', 10000.00, 1, 1800.00, 11800.00),
(6, 'ORD688F6DE34D953', 'MTB III', 45000.00, 1, 8100.00, 53100.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image_url`, `category`, `brand`) VALUES
(101, 'Tremor X 24D', NULL, 16900.00, 100, '', 'mtb', 'firefox'),
(102, 'Charger 29D', NULL, 48300.00, 100, '', 'mtb', 'firefox'),
(103, 'Charger 27.5D', NULL, 47800.00, 100, NULL, 'mtb', 'firefox'),
(104, 'Combact 27.5D', NULL, 29300.00, 100, NULL, 'mtb', 'firefox'),
(105, 'TJR25.5D', NULL, 27090.00, 100, NULL, 'mtb', 'firefox'),
(106, 'FXA 23D', NULL, 34600.00, 100, NULL, 'mtb', 'firefox'),
(107, 'Typhoon 25.5D', NULL, 25400.00, 100, NULL, 'mtb', 'firefox'),
(108, 'TJR 35.5D', NULL, 32300.00, 100, NULL, 'mtb', 'firefox'),
(109, 'Combact 32.5D', NULL, 32300.00, 100, NULL, 'mtb', ''),
(110, 'Nuke 26D', NULL, 16090.00, 100, NULL, 'mtb', 'firefox'),
(111, 'Charger 26D', NULL, 34500.00, 100, NULL, 'mtb', 'firefox'),
(112, 'NUKE 24D', NULL, 19900.00, 100, NULL, 'mtb', 'firefox');

-- --------------------------------------------------------

--
-- Table structure for table `road`
--

CREATE TABLE `road` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `road`
--

INSERT INTO `road` (`id`, `name`, `price`, `stock`, `category`, `image`, `created_at`) VALUES
(301, 'ROAD BIKE III', 15000.00, 100, '', 'G6.jpg', '2025-08-01 15:36:24'),
(302, 'ROAD BIKE I', 20000.00, 100, '', 'E4.jpg', '2025-08-01 15:39:47'),
(303, 'ROAD BIKE V', 32000.00, 100, '', 'E1.jpg', '2025-08-01 15:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `dob`, `city`, `country`, `created_at`) VALUES
(1, 'jay kesti', 'jayvardhankesti@gmail.com', '$2y$10$V.kGZIChY47CkT.cempR0OiN0G5OPYxcHBrTe1wo98KH9ozFRQDYa', '7757056688', '2003-05-23', 'PCMC PUNE', 'India', '2025-08-03 20:08:47'),
(2, 'harsh kesti', 'harshkesti@gmail.com', '$2y$10$nj38fMNdKfADxoEi/dNcbOiZBh3cwv9fcnB42RC5tAVd4WW2d2QcW', '9595939006', '2008-03-23', 'Pune', 'India', '2025-08-03 20:38:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mountain`
--
ALTER TABLE `mountain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `ch`
--
ALTER TABLE `ch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebikes`
--
ALTER TABLE `ebikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebikes`
--
ALTER TABLE `ebikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebikes`
--
ALTER TABLE `ebikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebikes`
--
ALTER TABLE `ebikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mountain`
--
ALTER TABLE `mountain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `road`
--
ALTER TABLE `road`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1058;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
