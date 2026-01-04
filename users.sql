-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2025 at 08:18 PM
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
(5, 'sayali thakar', 'thakarsayali78@gmail.com', '$2y$10$TjgdhYZK/lMPe3Eycn3Dv.CTVK.6X2XS3G6S.kHn28LdnmEvrIiFG', '8329048659', '2006-05-19', 'Pune', 'India', '2025-08-05 10:17:15', NULL);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
