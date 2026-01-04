-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2025 at 04:57 AM
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
-- Database: `alphacycles_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessories`
--

CREATE TABLE `accessories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accessories`
--

INSERT INTO `accessories` (`id`, `name`, `price`, `stock`, `description`, `image`) VALUES
(25, 'Cycling Helmet', 3500.00, 8, '', '6898266bf1a3d0.93636945.jpg');

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
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ch`
--

INSERT INTO `ch` (`id`, `name`, `price`, `stock`, `description`, `image`) VALUES
(3, 'TJR25.5D', 16000.00, 3, '', '689744fbd1d9a6.15933129.jpg'),
(4, 'FXA 23D', 18000.00, 3, '', '689745c72bbb94.90498048.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `clothing`
--

CREATE TABLE `clothing` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ebikes`
--

CREATE TABLE `ebikes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gravel`
--

CREATE TABLE `gravel` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kids`
--

CREATE TABLE `kids` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mountain`
--

CREATE TABLE `mountain` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mountain`
--

INSERT INTO `mountain` (`id`, `name`, `price`, `stock`, `description`, `image`) VALUES
(5, 'Tremor X 24D', 25000.00, 9, '', '689744016027c5.38267981.png'),
(6, 'TJR25.5D', 15000.00, 10, '', '68976f6ab53d19.88621433.webp'),
(7, 'MTB PRO I', 19000.00, 9, 'The MTB PRO I is engineered for precision and endurance. Featuring a lightweight alloy frame, advanced suspension, and 21-speed gear system, this mountain bike ensures optimal performance on any terrain.', '68981e858953a0.98969609.webp'),
(8, 'MTB PRO III', 22000.00, 10, '', '68981ef3c65aa3.51923415.webp'),
(9, 'MTB PRO V', 25000.00, 9, '', '68981f34da8324.16910373.webp'),
(10, 'MTB ROCKMASTER I', 23000.00, 9, '', '68981f968d9821.27962223.webp'),
(11, 'MTB ROCKMASTER III', 24000.00, 9, '', '689820632c5e70.29431317.webp'),
(12, 'MTB XTR III', 14000.00, 10, '', '68982090870997.24341660.webp'),
(13, 'MTB PRO ELITE', 27000.00, 10, '', '689820ad60c8b3.08924691.webp'),
(14, 'MTB XLR III', 18000.00, 10, '', '689820f86394d0.79626921.webp'),
(15, 'MTB XLR V', 29000.00, 10, '', '68982437bbf9d8.79565453.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `total_amount`, `order_date`, `status`, `created_at`, `updated_at`, `order_time`) VALUES
(1, 'ORD_68962c398ed25', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 2000.00, '2025-08-08 16:56:25', 'confirmed', '2025-08-08 16:56:25', '2025-08-08 16:56:25', '2025-08-09 22:05:57'),
(2, 'ORD_68962cf8e36e1', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 100.00, '2025-08-08 16:59:36', 'confirmed', '2025-08-08 16:59:36', '2025-08-08 16:59:36', '2025-08-09 22:05:57'),
(3, 'ORD_68962d12e7674', 'sayali thakar', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 100.00, '2025-08-08 17:00:02', 'confirmed', '2025-08-08 17:00:02', '2025-08-08 17:00:02', '2025-08-09 22:05:57'),
(4, 'ORD_68962e37cfb8c', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 10000.00, '2025-08-08 17:04:55', 'confirmed', '2025-08-08 17:04:55', '2025-08-08 17:04:55', '2025-08-09 22:05:57'),
(5, 'ORD_68962e538b57d', 'sayali thakar', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 1000.00, '2025-08-08 17:05:23', 'confirmed', '2025-08-08 17:05:23', '2025-08-08 17:05:23', '2025-08-09 22:05:57'),
(6, 'ORD_6896314dd99fb', 'harsh kesti', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 21300.00, '2025-08-08 17:18:05', 'confirmed', '2025-08-08 17:18:05', '2025-08-08 17:18:05', '2025-08-09 22:05:57'),
(7, 'ORD_6896327be3d05', 'Tremor X 24D', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 15000.00, '2025-08-08 17:23:07', 'confirmed', '2025-08-08 17:23:07', '2025-08-08 17:23:07', '2025-08-09 22:05:57'),
(8, 'ORD_68963c8c5208c', 'TJR25.5D', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 30000.00, '2025-08-08 18:06:04', 'confirmed', '2025-08-08 18:06:04', '2025-08-08 18:06:04', '2025-08-09 22:05:57'),
(9, 'ORD_6896429e29d89', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 15000.00, '2025-08-08 18:31:58', 'confirmed', '2025-08-08 18:31:58', '2025-08-08 18:31:58', '2025-08-09 22:05:57'),
(10, 'ORD_6896433dad897', 'TJR25.5D', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 15000.00, '2025-08-08 18:34:37', 'confirmed', '2025-08-08 18:34:37', '2025-08-08 18:34:37', '2025-08-09 22:05:57'),
(11, 'ORD_689644025066a', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 43000.00, '2025-08-08 18:37:54', 'confirmed', '2025-08-08 18:37:54', '2025-08-08 18:37:54', '2025-08-09 22:05:57'),
(12, 'ORD_6896bc4370335', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 11000.00, '2025-08-09 03:10:59', 'confirmed', '2025-08-09 03:10:59', '2025-08-09 03:10:59', '2025-08-09 22:05:57'),
(13, 'ORD_6896bcddef31a', 'TJR25.5D', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 6100.00, '2025-08-09 03:13:33', 'confirmed', '2025-08-09 03:13:33', '2025-08-09 03:13:33', '2025-08-09 22:05:57'),
(14, 'ORD_6896c613ee2b1', 'Tremor X 24D', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 3000.00, '2025-08-09 03:52:51', 'confirmed', '2025-08-09 03:52:51', '2025-08-09 03:52:51', '2025-08-09 22:05:57'),
(15, 'ORD_6896c769d7381', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 19000.00, '2025-08-09 03:58:33', 'confirmed', '2025-08-09 03:58:33', '2025-08-09 03:58:33', '2025-08-09 22:05:57'),
(16, 'ORD_6896c789dd9c6', 'harsh kesti', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 14500.00, '2025-08-09 03:59:05', 'confirmed', '2025-08-09 03:59:05', '2025-08-09 03:59:05', '2025-08-09 22:05:57'),
(17, 'ORD_6897184495678', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 16500.00, '2025-08-09 09:43:32', 'confirmed', '2025-08-09 09:43:32', '2025-08-09 09:43:32', '2025-08-09 22:05:57'),
(18, 'ORD_689750e49308b', 'harsh kesti', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 25000.00, '2025-08-09 13:45:08', 'confirmed', '2025-08-09 13:45:08', '2025-08-09 13:45:08', '2025-08-09 22:05:57'),
(19, 'ORD_689750f93dc19', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 25000.00, '2025-08-09 13:45:29', 'confirmed', '2025-08-09 13:45:29', '2025-08-09 13:45:29', '2025-08-09 22:05:57'),
(20, 'ORD_689752fb32872', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 3600.00, '2025-08-09 13:54:03', 'confirmed', '2025-08-09 13:54:03', '2025-08-09 13:54:03', '2025-08-09 22:05:57'),
(21, 'ORD_689753794d66b', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 2400.00, '2025-08-09 13:56:09', 'confirmed', '2025-08-09 13:56:09', '2025-08-09 13:56:09', '2025-08-09 22:05:57'),
(22, 'ORD_689754db6c937', 'sayali thakar', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 186000.00, '2025-08-09 14:02:03', 'confirmed', '2025-08-09 14:02:03', '2025-08-09 14:02:03', '2025-08-09 22:05:57'),
(23, 'ORD_68975c1b032fb', 'jayvardhan kesti', 'kestijay@gmail.com', '7757056688', 'B2 17 laxmivrindhavan\npimple saudhagar', 359000.00, '2025-08-09 14:32:59', 'confirmed', '2025-08-09 14:32:59', '2025-08-09 14:32:59', '2025-08-09 22:05:57'),
(24, 'ORD_68975c3dd30ea', 'arya ghardale', 'jayvardhankesti@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 10000.00, '2025-08-09 14:33:33', 'confirmed', '2025-08-09 14:33:33', '2025-08-09 14:33:33', '2025-08-09 22:05:57'),
(25, 'ORD_68975cc19e677', 'harsh kesti', 'jayvardhankesti@gmail.com', '7757056688', 'B2 17 laxmivrindhavan\npimple saudhagar', 70000.00, '2025-08-09 14:35:45', 'confirmed', '2025-08-09 14:35:45', '2025-08-09 14:35:45', '2025-08-09 22:05:57'),
(26, 'ORD_68977de321a64', 'harsh kesti', 'harshkesti@gmail.com', '7757056688', 'B2 17 laxmivrindhavan\npimple saudhagar', 45000.00, '2025-08-09 16:57:07', 'confirmed', '2025-08-09 16:57:07', '2025-08-09 16:57:07', '2025-08-09 22:27:07'),
(27, 'ORD_68977eefc8d3a', 'harsh kesti', 'harshkesti@gmail.com', '9595939006', 'B2 17 laxmivrindhavan\npimple saudhagar', 2400.00, '2025-08-09 17:01:35', 'confirmed', '2025-08-09 17:01:35', '2025-08-09 17:01:35', '2025-08-09 22:31:35'),
(28, 'ORD_689780df1b415', 'harsh kesti', 'harshkesti@gmail.com', '9595939006', 'B2 17 laxmivrindhavan\npimple saudhagar', 1800.00, '2025-08-09 17:09:51', 'delivered', '2025-08-09 17:09:51', '2025-08-10 12:29:41', '2025-08-09 22:39:51'),
(29, 'ORD_689782e3e93a9', 'harsh kesti', 'harshkesti@gmail.com', '9595939006', 'B2 17 laxmivrindhavan\npimple saudhagar', 3600.00, '2025-08-09 17:18:27', 'delivered', '2025-08-09 17:18:27', '2025-08-09 17:41:15', '2025-08-09 22:48:27'),
(30, 'ORD_68981d13c957c', 'arya ghardale', 'asghardale@gmail.com', '9595939006', 'B2 17 laxmivrindhavan\npimple saudhagar', 25000.00, '2025-08-10 04:16:19', 'delivered', '2025-08-10 04:16:19', '2025-08-10 12:29:39', '2025-08-10 09:46:19'),
(31, 'ORD_689823eb14918', 'jitendra kesti', 'jskesti@gmail.com', '9595939006', 'B2 17 laxmivrindhavan\npimple saudhagar', 146000.00, '2025-08-10 04:45:31', 'delivered', '2025-08-10 04:45:31', '2025-08-10 12:29:38', '2025-08-10 10:15:31'),
(32, 'ORD_68982bf21924c', 'jitendra kesti', 'jskesti@gmail.com', '9595939006', 'B2 17 laxmivrindhavan\npimple saudhagar', 57000.00, '2025-08-10 05:19:46', 'delivered', '2025-08-10 05:19:46', '2025-08-10 12:29:38', '2025-08-10 10:49:46'),
(33, 'ORD_6898387c45c23', 'jitendra kesti', 'jskesti@gmail.com', '9595939006', 'B2 17 laxmivrindhavan\npimple saudhagar', 50000.00, '2025-08-10 06:13:16', 'delivered', '2025-08-10 06:13:16', '2025-08-10 12:29:37', '2025-08-10 11:43:16'),
(34, 'ORD_6898933ef10fd', 'om kesti', 'omkesti@gmail.com', '9512365478', 'A-502 nisarg shrusti, kaspati vasti, Pune-24', 116000.00, '2025-08-10 12:40:30', 'confirmed', '2025-08-10 12:40:30', '2025-08-10 12:40:30', '2025-08-10 18:10:30'),
(35, 'ORD_6898c652d4f20', 'sayali thakar', 'thakarsayali78@gmail.com', '8329048659', 'B2 17 laxmivrindhavan\npimple saudhagar', 7000.00, '2025-08-10 16:18:26', 'confirmed', '2025-08-10 16:18:26', '2025-08-10 16:18:26', '2025-08-10 21:48:26'),
(36, 'ORD_6898d600b5bf5', 'om kesti', 'omkesti@gmail.com', '9512365478', 'A-502 nisarg shrushti kaspte wasti pune ', 46000.00, '2025-08-10 17:25:20', 'confirmed', '2025-08-10 17:25:20', '2025-08-10 17:25:20', '2025-08-10 22:55:20'),
(37, 'ORD_6898d639174eb', 'om kesti', 'omkesti@gmail.com', '9512365478', 'A-502 Nisarg Shrushti kaspte wasti pune', 115000.00, '2025-08-10 17:26:17', 'confirmed', '2025-08-10 17:26:17', '2025-08-10 17:26:17', '2025-08-10 22:56:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_table` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_table`, `product_name`, `quantity`, `price`, `subtotal`, `created_at`) VALUES
(1, 'ORD_68962c398ed25', 21, 'accessories', 'arya ghardale', 2, 1000.00, 2000.00, '2025-08-08 16:56:25'),
(2, 'ORD_68962cf8e36e1', 23, 'accessories', 'sayali thakar', 1, 100.00, 100.00, '2025-08-08 16:59:36'),
(3, 'ORD_68962d12e7674', 23, 'accessories', 'sayali thakar', 1, 100.00, 100.00, '2025-08-08 17:00:02'),
(4, 'ORD_68962e37cfb8c', 22, 'accessories', 'harsh kesti', 1, 10000.00, 10000.00, '2025-08-08 17:04:55'),
(5, 'ORD_68962e538b57d', 21, 'accessories', 'arya ghardale', 1, 1000.00, 1000.00, '2025-08-08 17:05:23'),
(6, 'ORD_6896314dd99fb', 21, 'accessories', 'arya ghardale', 1, 1000.00, 1000.00, '2025-08-08 17:18:05'),
(7, 'ORD_6896314dd99fb', 23, 'accessories', 'sayali thakar', 3, 100.00, 300.00, '2025-08-08 17:18:05'),
(8, 'ORD_6896314dd99fb', 22, 'accessories', 'harsh kesti', 2, 10000.00, 20000.00, '2025-08-08 17:18:05'),
(9, 'ORD_6896327be3d05', 24, 'accessories', 'Tremor X 24D', 3, 5000.00, 15000.00, '2025-08-08 17:23:07'),
(10, 'ORD_68963c8c5208c', 22, 'accessories', 'harsh kesti', 3, 10000.00, 30000.00, '2025-08-08 18:06:04'),
(11, 'ORD_6896429e29d89', 1, 'mountain', 'harsh kesti', 1, 15000.00, 15000.00, '2025-08-08 18:31:58'),
(12, 'ORD_6896433dad897', 1, 'mountain', 'harsh kesti', 1, 15000.00, 15000.00, '2025-08-08 18:34:37'),
(13, 'ORD_689644025066a', 1, 'mountain', 'harsh kesti', 1, 15000.00, 15000.00, '2025-08-08 18:37:54'),
(14, 'ORD_689644025066a', 1, 'kids', 'arya ghardale', 2, 14000.00, 28000.00, '2025-08-08 18:37:54'),
(15, 'ORD_6896bc4370335', 21, 'accessories', 'arya ghardale', 1, 1000.00, 1000.00, '2025-08-09 03:10:59'),
(16, 'ORD_6896bc4370335', 22, 'accessories', 'harsh kesti', 1, 10000.00, 10000.00, '2025-08-09 03:10:59'),
(17, 'ORD_6896bcddef31a', 21, 'accessories', 'arya ghardale', 1, 1000.00, 1000.00, '2025-08-09 03:13:33'),
(18, 'ORD_6896bcddef31a', 24, 'accessories', 'Tremor X 24D', 1, 5000.00, 5000.00, '2025-08-09 03:13:33'),
(19, 'ORD_6896bcddef31a', 23, 'accessories', 'sayali thakar', 1, 100.00, 100.00, '2025-08-09 03:13:33'),
(20, 'ORD_6896c613ee2b1', 21, 'accessories', 'arya ghardale', 3, 1000.00, 3000.00, '2025-08-09 03:52:51'),
(21, 'ORD_6896c769d7381', 2, 'ch', 'sayali thakar', 2, 4500.00, 9000.00, '2025-08-09 03:58:33'),
(22, 'ORD_6896c769d7381', 1, 'ch', 'arya ghardale', 1, 10000.00, 10000.00, '2025-08-09 03:58:33'),
(23, 'ORD_6896c789dd9c6', 2, 'ch', 'sayali thakar', 1, 4500.00, 4500.00, '2025-08-09 03:59:05'),
(24, 'ORD_6896c789dd9c6', 1, 'ch', 'arya ghardale', 1, 10000.00, 10000.00, '2025-08-09 03:59:05'),
(25, 'ORD_6897184495678', 1, 'mountain', 'harsh kesti', 1, 15000.00, 15000.00, '2025-08-09 09:43:32'),
(26, 'ORD_6897184495678', 2, 'mountain', 'arya ghardale', 1, 1500.00, 1500.00, '2025-08-09 09:43:32'),
(27, 'ORD_689750e49308b', 5, 'mountain', 'Tremor X 24D', 1, 25000.00, 25000.00, '2025-08-09 13:45:08'),
(28, 'ORD_689750f93dc19', 5, 'mountain', 'Tremor X 24D', 1, 25000.00, 25000.00, '2025-08-09 13:45:29'),
(29, 'ORD_689752fb32872', 1, 'ebikes', 'Athlete Suits Pro', 2, 1800.00, 3600.00, '2025-08-09 13:54:03'),
(30, 'ORD_689753794d66b', 1, 'clothing', 'athletic suit pro', 1, 2400.00, 2400.00, '2025-08-09 13:56:09'),
(31, 'ORD_689754db6c937', 4, 'ch', 'FXA 23D', 5, 18000.00, 90000.00, '2025-08-09 14:02:03'),
(32, 'ORD_689754db6c937', 3, 'ch', 'TJR25.5D', 6, 16000.00, 96000.00, '2025-08-09 14:02:03'),
(33, 'ORD_68975c1b032fb', 1, 'ebikes', 'Athlete Suits Pro', 3, 1800.00, 5400.00, '2025-08-09 14:32:59'),
(34, 'ORD_68975c1b032fb', 21, 'accessories', 'arya ghardale', 3, 1000.00, 3000.00, '2025-08-09 14:32:59'),
(35, 'ORD_68975c1b032fb', 22, 'accessories', 'harsh kesti', 9, 10000.00, 90000.00, '2025-08-09 14:32:59'),
(36, 'ORD_68975c1b032fb', 5, 'mountain', 'Tremor X 24D', 6, 25000.00, 150000.00, '2025-08-09 14:32:59'),
(37, 'ORD_68975c1b032fb', 4, 'ch', 'FXA 23D', 1, 18000.00, 18000.00, '2025-08-09 14:32:59'),
(38, 'ORD_68975c1b032fb', 1, 'clothing', 'athletic suit pro', 4, 2400.00, 9600.00, '2025-08-09 14:32:59'),
(39, 'ORD_68975c1b032fb', 2, 'kids', 'FXA 23D', 1, 10000.00, 10000.00, '2025-08-09 14:32:59'),
(40, 'ORD_68975c1b032fb', 1, 'road', 'FXA 23D', 2, 23000.00, 46000.00, '2025-08-09 14:32:59'),
(41, 'ORD_68975c1b032fb', 1, 'gravel', 'FXA 23D', 1, 27000.00, 27000.00, '2025-08-09 14:32:59'),
(42, 'ORD_68975c3dd30ea', 22, 'accessories', 'harsh kesti', 1, 10000.00, 10000.00, '2025-08-09 14:33:33'),
(43, 'ORD_68975cc19e677', 5, 'mountain', 'Tremor X 24D', 2, 25000.00, 50000.00, '2025-08-09 14:35:45'),
(44, 'ORD_68975cc19e677', 2, 'kids', 'FXA 23D', 2, 10000.00, 20000.00, '2025-08-09 14:35:45'),
(45, 'ORD_68977de321a64', 6, 'mountain', 'TJR25.5D', 3, 15000.00, 45000.00, '2025-08-09 16:57:07'),
(46, 'ORD_68977eefc8d3a', 1, 'clothing', 'athletic suit pro', 1, 2400.00, 2400.00, '2025-08-09 17:01:35'),
(47, 'ORD_689780df1b415', 1, 'ebikes', 'Athlete Suits Pro', 1, 1800.00, 1800.00, '2025-08-09 17:09:51'),
(48, 'ORD_689782e3e93a9', 1, 'ebikes', 'Athlete Suits Pro', 2, 1800.00, 3600.00, '2025-08-09 17:18:27'),
(49, 'ORD_68981d13c957c', 5, 'mountain', 'Tremor X 24D', 1, 25000.00, 25000.00, '2025-08-10 04:16:19'),
(50, 'ORD_689823eb14918', 11, 'mountain', 'MTB ROCKMASTER III', 1, 24000.00, 24000.00, '2025-08-10 04:45:31'),
(51, 'ORD_689823eb14918', 5, 'mountain', 'Tremor X 24D', 1, 25000.00, 25000.00, '2025-08-10 04:45:31'),
(52, 'ORD_689823eb14918', 6, 'mountain', 'TJR25.5D', 2, 15000.00, 30000.00, '2025-08-10 04:45:31'),
(53, 'ORD_689823eb14918', 7, 'mountain', 'MTB PRO I', 1, 19000.00, 19000.00, '2025-08-10 04:45:31'),
(54, 'ORD_689823eb14918', 10, 'mountain', 'MTB ROCKMASTER I', 1, 23000.00, 23000.00, '2025-08-10 04:45:31'),
(55, 'ORD_689823eb14918', 9, 'mountain', 'MTB PRO V', 1, 25000.00, 25000.00, '2025-08-10 04:45:31'),
(56, 'ORD_68982bf21924c', 1, 'road', 'FXA 23D', 1, 23000.00, 23000.00, '2025-08-10 05:19:46'),
(57, 'ORD_68982bf21924c', 4, 'ch', 'FXA 23D', 1, 18000.00, 18000.00, '2025-08-10 05:19:46'),
(58, 'ORD_68982bf21924c', 3, 'ch', 'TJR25.5D', 1, 16000.00, 16000.00, '2025-08-10 05:19:46'),
(59, 'ORD_6898387c45c23', 5, 'mountain', 'Tremor X 24D', 2, 25000.00, 50000.00, '2025-08-10 06:13:16'),
(60, 'ORD_6898933ef10fd', 11, 'mountain', 'MTB ROCKMASTER III', 1, 24000.00, 24000.00, '2025-08-10 12:40:30'),
(61, 'ORD_6898933ef10fd', 9, 'mountain', 'MTB PRO V', 1, 25000.00, 25000.00, '2025-08-10 12:40:30'),
(62, 'ORD_6898933ef10fd', 10, 'mountain', 'MTB ROCKMASTER I', 1, 23000.00, 23000.00, '2025-08-10 12:40:30'),
(63, 'ORD_6898933ef10fd', 7, 'mountain', 'MTB PRO I', 1, 19000.00, 19000.00, '2025-08-10 12:40:30'),
(64, 'ORD_6898933ef10fd', 5, 'mountain', 'Tremor X 24D', 1, 25000.00, 25000.00, '2025-08-10 12:40:31'),
(65, 'ORD_6898c652d4f20', 25, 'accessories', 'Cycling Helmet', 2, 3500.00, 7000.00, '2025-08-10 16:18:26'),
(66, 'ORD_6898d600b5bf5', 1, 'road', 'FXA 23D', 2, 23000.00, 46000.00, '2025-08-10 17:25:20'),
(67, 'ORD_6898d639174eb', 1, 'road', 'FXA 23D', 5, 23000.00, 115000.00, '2025-08-10 17:26:17');

-- --------------------------------------------------------

--
-- Table structure for table `road`
--

CREATE TABLE `road` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `road`
--

INSERT INTO `road` (`id`, `name`, `price`, `stock`, `description`, `image`) VALUES
(1, 'FXA 23D', 23000.00, 0, '', '689744d4885f84.69843408.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `top_products`
--

CREATE TABLE `top_products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `top_products`
--

INSERT INTO `top_products` (`id`, `name`, `type`, `short_description`, `description`, `price`, `stock`, `image_url`) VALUES
(1, 'TerraClimb X9', 'mountain', 'Conquer rugged terrains with the TerraClimb X9 mountain bike, built with a lightweight aluminum frame and hydraulic disc brakes for superior control.', 'Engineered for off-road adventures. 27.5\" wheels, 12-speed SRAM gearing, and advanced suspension make it ideal for serious mountain bikers.', 2499.00, 10, '1.jpg'),
(2, 'VelociX Aero', 'road', 'Built for speed, the VelociX Aero is a carbon-frame road bike with aerodynamic design and Shimano Ultegra drivetrain for peak performance.', 'Professional-grade road bike offering seamless speed and precision. Ideal for competitive cyclists and long-distance racers.', 3999.00, 10, '2.jpg'),
(3, 'UrbanFlow E-Bike', 'ebikes', 'Make commuting effortless with UrbanFlow, a lightweight e-bike with pedal assist, built-in lights, and up to 60km of range.', 'Daily commute redefined with electric assistance and a sleek, compact design. Foldable frame available in select models.', 1799.00, 10, '3.jpg'),
(4, 'MiniRider 16', 'kids', 'Safe, colorful, and lightweight — the perfect first bike for ebikes aged 4–7. Includes training wheels and adjustable seat.', 'Designed for young riders with safety in mind. Puncture-proof tires, vibrant colors, and full chain guard for protection.', 199.00, 10, '4.jpg');

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
  `created_at` datetime DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `dob`, `city`, `country`, `created_at`, `address`) VALUES
(1, 'jay kesti', 'jayvardhankesti@gmail.com', '$2y$10$V.kGZIChY47CkT.cempR0OiN0G5OPYxcHBrTe1wo98KH9ozFRQDYa', '7757056688', '2003-05-23', 'PCMC PUNE', 'India', '2025-08-03 20:08:47', NULL),
(2, 'harsh kesti', 'harshkesti@gmail.com', '$2y$10$nj38fMNdKfADxoEi/dNcbOiZBh3cwv9fcnB42RC5tAVd4WW2d2QcW', '9595939006', '2008-03-23', 'Pune', 'India', '2025-08-03 20:38:12', NULL),
(4, 'arya ghardale', 'asghardale@gmail.com', '$2y$10$LxT53Ii55cBGh/v/YELFyeSp2qOlzMaIvUrKc6Q4PZp82pxKa78Zq', '2392382938', '2005-10-24', 'Pune', 'India', '2025-08-04 20:36:49', NULL),
(5, 'sayali thakar', 'thakarsayali78@gmail.com', '$2y$10$TjgdhYZK/lMPe3Eycn3Dv.CTVK.6X2XS3G6S.kHn28LdnmEvrIiFG', '8329048659', '2006-05-19', 'Pune', 'India', '2025-08-05 10:17:15', NULL),
(6, 'jitendra kesti', 'jskesti@gmail.com', '$2y$10$2HLMkzd91x6btZ8/4A00KusVZKMYGXDzLK0LPCDOus/iMi0cUsLHq', '9595939006', '1975-04-21', 'PCMC PUNE', 'India', '2025-08-10 11:42:00', NULL),
(7, 'om kesti', 'omkesti@gmail.com', '$2y$10$Ki4foydvwipj7dYgAYNUWuuQSpd8bPlKVdiEFL3OWdpDan90twGlO', '9512365478', '2007-08-16', 'PCMC PUNE', 'India', '2025-08-10 18:08:29', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accessories`
--
ALTER TABLE `accessories`
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
-- Indexes for table `clothing`
--
ALTER TABLE `clothing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebikes`
--
ALTER TABLE `ebikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gravel`
--
ALTER TABLE `gravel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kids`
--
ALTER TABLE `kids`
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
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `idx_orders_order_id` (`order_id`),
  ADD KEY `idx_orders_status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order_id` (`order_id`),
  ADD KEY `idx_order_items_product` (`product_id`,`product_table`);

--
-- Indexes for table `road`
--
ALTER TABLE `road`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `top_products`
--
ALTER TABLE `top_products`
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
-- AUTO_INCREMENT for table `accessories`
--
ALTER TABLE `accessories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ch`
--
ALTER TABLE `ch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `clothing`
--
ALTER TABLE `clothing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ebikes`
--
ALTER TABLE `ebikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gravel`
--
ALTER TABLE `gravel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kids`
--
ALTER TABLE `kids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mountain`
--
ALTER TABLE `mountain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `road`
--
ALTER TABLE `road`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `top_products`
--
ALTER TABLE `top_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
