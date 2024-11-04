-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2024 at 01:46 PM
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
-- Database: `login-register`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_on`) VALUES
(160, 8, 26, 1, '2024-09-29 16:02:49'),
(161, 8, 23, 1, '2024-09-29 16:03:30');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Triumph'),
(2, 'Kawasaki'),
(3, 'KTM'),
(4, 'Royal Enfield'),
(5, 'Yamaha'),
(7, 'Bajaj'),
(8, 'Honda'),
(9, 'Hero'),
(10, 'Suzuki'),
(11, 'BMW'),
(12, 'TVS');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(1, 'Aldrin', 'fsgsheje@gmail.com', 'sfbdsfbbdzbs', '2024-09-03 22:14:04'),
(2, 'Aldrin', 'fsgsheje@gmail.com', 'sdgszbsdfb', '2024-09-03 22:15:49'),
(3, 'Aldrin', 'fsgsheje@gmail.com', 'il;u;hfyuo', '2024-09-03 22:16:04'),
(4, 'Aldrin', 'aldrinsony20@gmail.com', 'fehdgsdbsd', '2024-09-03 22:16:25'),
(5, 'Aldrin', 'fsgsheje@gmail.com', 'dfsb', '2024-09-03 22:16:36'),
(6, 'Aldrin', 'fsgsheje@gmail.com', 'sdvsvsdv', '2024-09-03 22:16:49'),
(7, 'Aldrin', 'fsgsheje@gmail.com', 'sfva', '2024-09-03 22:25:53'),
(8, 'Aldrin', 'aldrinsony20@gmail.com', 'fdhgfhsdh', '2024-09-03 22:26:06'),
(9, 'Aldrin', 'aldrinsony20@gmail.com', 'g', '2024-09-03 22:26:17'),
(10, 'Aldrin', 'fsgsheje@gmail.com', '&#39;', '2024-09-03 22:26:30'),
(11, 'Aldrin', 'fsgsheje@gmail.com', '&#39;', '2024-09-03 22:30:57'),
(12, 'Aldrin', 'fsgsheje@gmail.com', '&#39;', '2024-09-03 22:31:05'),
(13, 'Aldrin', 'fsgsheje@gmail.com', 'f', '2024-09-03 22:31:18'),
(14, 'Aldrin', 'fsgsheje@gmail.com', 'f', '2024-09-03 22:41:21'),
(15, 'Aldrin', 'fsgsheje@gmail.com', 'f', '2024-09-03 22:42:48'),
(16, 'Aldrin', 'aldrinsony20@gmail.com', 'dfdbf', '2024-09-03 22:43:01'),
(17, 'Aldrin', 'aldrinsony20@gmail.com', 'dfdbf', '2024-09-03 22:43:16'),
(18, 'Aldrin', 'fsgsheje@gmail.com', 'xhfj', '2024-09-03 22:45:36'),
(19, 'Aldrin', 'fsgsheje@gmail.com', 'xhfj', '2024-09-03 22:46:31'),
(20, 'Aldrin', 'aldrinsony20@gmail.com', 'twergh', '2024-09-05 04:14:42'),
(21, 'Aldrin', 'aldrinsony20@gmail.com', 'sgsfdbv', '2024-09-05 06:16:47'),
(22, 'Aldrin', 'fsgsheje@gmail.com', 'errgweghwer', '2024-09-05 06:18:13'),
(23, 'Aldrin', 'aldrinsony20@gmail.com', 'HI', '2024-09-26 18:18:41'),
(24, 'Dave', 'davenevin2004@gmail.com', 'Nice website...', '2024-09-27 02:49:14');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `feedback_date` datetime DEFAULT current_timestamp(),
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 10),
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `message`, `feedback_date`, `rating`, `product_id`) VALUES
(20, 1, 'Good Product...!', '2024-09-01 19:51:44', 5, 22),
(25, 1, 'Nice...', '2024-09-06 02:46:11', 3, 21),
(26, 1, 'Nice', '2024-09-06 02:46:53', 3, 22),
(27, 1, 'It was ok...', '2024-09-08 02:05:53', 2, 22),
(28, 1, 'Nice', '2024-09-08 02:06:08', 5, 20),
(29, 1, 'Nyc Product', '2024-09-28 18:52:32', 5, 21),
(31, 1, 'A good product...', '2024-09-29 04:32:19', 4, 27),
(33, 1, 'ii', '2024-09-29 21:14:43', 2, 23),
(34, 1, 'Good', '2024-09-30 12:51:31', 4, 26);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'processing',
  `total` decimal(10,2) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `status`, `total`, `shipping_address`, `phone_number`, `payment_method`) VALUES
(1, 1, '2024-08-29 13:02:03', 'processing', 1499.00, NULL, NULL, NULL),
(2, 0, '2024-08-31 16:31:50', '', 2499.00, NULL, NULL, NULL),
(3, 1, '2024-09-02 18:06:31', 'delivered', 999.00, NULL, NULL, NULL),
(4, 1, '2024-09-05 09:50:13', 'shipped', 2997.00, NULL, NULL, NULL),
(5, 1, '2024-09-09 22:18:12', 'processing', 15189.00, NULL, NULL, NULL),
(6, 1, '2024-09-10 21:06:38', 'shipped', 7495.00, NULL, NULL, NULL),
(7, 1, '2024-09-10 21:17:59', '', 299.00, NULL, NULL, NULL),
(8, 1, '2024-09-21 19:55:29', '', 7094.00, NULL, NULL, NULL),
(9, 1, '2024-09-23 12:35:38', '', 15089.00, NULL, NULL, NULL),
(10, 1, '2024-09-23 13:09:47', '', 13293.00, NULL, NULL, NULL),
(11, 1, '2024-09-26 19:11:06', '', 15690.00, NULL, NULL, NULL),
(12, 1, '2024-09-26 19:11:55', '', 999.00, NULL, NULL, NULL),
(13, 1, '2024-09-26 19:12:29', '', 2499.00, NULL, NULL, NULL),
(14, 1, '2024-09-26 19:15:49', '', 4998.00, NULL, NULL, NULL),
(15, 1, '2024-09-26 19:16:40', '', 2797.00, NULL, NULL, NULL),
(16, 1, '2024-09-26 20:40:43', '', 21489.00, NULL, NULL, NULL),
(17, 1, '2024-09-26 20:41:28', '', 999.00, NULL, NULL, NULL),
(18, 1, '2024-09-26 23:06:19', '', 5997.00, NULL, NULL, NULL),
(19, 1, '2024-09-26 23:07:17', '', 2499.00, NULL, NULL, NULL),
(20, 1, '2024-09-26 23:19:31', '', 5997.00, NULL, NULL, NULL),
(21, 1, '2024-09-26 23:22:35', '', 4998.00, NULL, NULL, NULL),
(22, 1, '2024-09-26 23:28:47', '', 2499.00, NULL, NULL, NULL),
(23, 1, '2024-09-26 23:30:28', '', 2499.00, NULL, NULL, NULL),
(24, 1, '2024-09-26 23:40:47', '', 2499.00, NULL, NULL, NULL),
(25, 1, '2024-09-27 11:51:00', '', 2499.00, NULL, NULL, NULL),
(26, 1, '2024-09-27 11:58:27', '', 0.00, NULL, NULL, NULL),
(27, 1, '2024-09-27 12:00:45', '', 0.00, NULL, NULL, NULL),
(28, 1, '2024-09-27 12:02:07', '', 0.00, NULL, NULL, NULL),
(29, 1, '2024-09-27 12:03:23', '', 0.00, NULL, NULL, NULL),
(30, 1, '2024-09-27 12:11:17', '', 0.00, NULL, NULL, NULL),
(31, 1, '2024-09-27 16:59:41', '', 0.00, NULL, NULL, NULL),
(32, 1, '2024-09-27 17:04:28', '', 3498.00, NULL, NULL, NULL),
(33, 1, '2024-09-27 17:04:36', '', 0.00, NULL, NULL, NULL),
(34, 1, '2024-09-27 17:04:40', '', 0.00, NULL, NULL, NULL),
(35, 1, '2024-09-27 18:14:20', '', 999.00, NULL, NULL, NULL),
(36, 1, '2024-09-28 19:01:06', '', 1798.00, NULL, NULL, NULL),
(37, 1, '2024-09-28 19:06:47', '', 999.00, NULL, NULL, NULL),
(38, 1, '2024-09-28 19:07:05', '', 0.00, NULL, NULL, NULL),
(39, 1, '2024-09-28 19:07:19', '', 0.00, NULL, NULL, NULL),
(40, 1, '2024-09-28 19:09:33', '', 0.00, NULL, NULL, NULL),
(41, 1, '2024-09-28 19:10:47', '', 0.00, NULL, NULL, NULL),
(42, 1, '2024-09-28 19:11:17', '', 2499.00, NULL, NULL, NULL),
(43, 1, '2024-09-28 19:13:43', '', 1499.00, NULL, NULL, NULL),
(44, 1, '2024-09-28 19:15:23', '', 799.00, NULL, NULL, NULL),
(45, 1, '2024-09-28 19:15:31', '', 0.00, NULL, NULL, NULL),
(46, 1, '2024-09-28 19:17:18', '', 0.00, NULL, NULL, NULL),
(47, 1, '2024-09-28 19:17:39', '', 299.00, NULL, NULL, NULL),
(48, 1, '2024-09-28 19:17:46', '', 299.00, NULL, NULL, NULL),
(49, 1, '2024-09-28 19:17:52', '', 299.00, NULL, NULL, NULL),
(50, 1, '2024-09-28 19:17:59', '', 299.00, NULL, NULL, NULL),
(51, 1, '2024-09-28 19:23:22', '', 299.00, NULL, NULL, NULL),
(52, 1, '2024-09-28 19:23:31', '', 299.00, NULL, NULL, NULL),
(53, 1, '2024-09-28 19:24:38', '', 299.00, NULL, NULL, NULL),
(54, 1, '2024-09-28 19:24:50', '', 299.00, NULL, NULL, NULL),
(55, 1, '2024-09-28 19:25:54', '', 299.00, NULL, NULL, NULL),
(56, 1, '2024-09-28 19:26:07', '', 299.00, NULL, NULL, NULL),
(57, 1, '2024-09-28 19:28:35', '', 299.00, NULL, NULL, NULL),
(58, 1, '2024-09-28 19:34:35', '', 299.00, NULL, NULL, NULL),
(59, 1, '2024-09-28 19:34:54', '', 299.00, NULL, NULL, NULL),
(60, 1, '2024-09-28 19:46:19', '', 999.00, NULL, NULL, NULL),
(61, 1, '2024-09-28 19:46:25', '', 0.00, NULL, NULL, NULL),
(62, 1, '2024-09-28 19:46:38', '', 0.00, NULL, NULL, NULL),
(63, 1, '2024-09-28 20:05:42', '', 2499.00, NULL, NULL, NULL),
(64, 1, '2024-09-28 20:08:39', '', 2499.00, NULL, NULL, NULL),
(65, 1, '2024-09-28 20:19:19', '', 2499.00, NULL, NULL, NULL),
(66, 1, '2024-09-28 20:25:01', '', 2499.00, NULL, NULL, NULL),
(67, 1, '2024-09-28 20:25:43', '', 1699.00, NULL, NULL, NULL),
(68, 1, '2024-09-28 20:26:08', '', 2499.00, NULL, NULL, NULL),
(69, 1, '2024-09-28 20:26:46', '', 2499.00, NULL, NULL, NULL),
(70, 1, '2024-09-28 23:53:12', '', 0.00, NULL, NULL, NULL),
(71, 1, '2024-09-28 23:53:30', '', 0.00, NULL, NULL, NULL),
(72, 1, '2024-09-29 01:44:11', '', 0.00, NULL, NULL, NULL),
(73, 1, '2024-09-29 01:44:30', '', 0.00, NULL, NULL, NULL),
(74, 1, '2024-09-29 01:58:23', '', 299.00, NULL, NULL, NULL),
(75, 1, '2024-09-29 02:00:05', '', 598.00, NULL, NULL, NULL),
(76, 1, '2024-09-29 02:00:44', '', 1998.00, NULL, NULL, NULL),
(77, 1, '2024-09-29 02:00:48', '', 1998.00, NULL, NULL, NULL),
(78, 1, '2024-09-29 02:00:52', '', 1998.00, NULL, NULL, NULL),
(79, 1, '2024-09-29 02:00:54', '', 1998.00, NULL, NULL, NULL),
(80, 1, '2024-09-29 02:01:23', '', 1998.00, NULL, NULL, NULL),
(81, 1, '2024-09-29 02:07:08', '', 6646.20, NULL, NULL, NULL),
(82, 1, '2024-09-29 02:19:23', '', 0.00, NULL, NULL, NULL),
(83, 1, '2024-09-29 04:24:53', '', 2499.00, NULL, NULL, NULL),
(84, 1, '2024-09-29 04:46:09', '', 2499.00, NULL, NULL, NULL),
(85, 1, '2024-09-29 15:13:33', '', 0.00, NULL, NULL, NULL),
(86, 1, '2024-09-29 15:13:43', '', 0.00, NULL, NULL, NULL),
(87, 1, '2024-09-29 15:19:00', '', 999.00, NULL, NULL, NULL),
(88, 1, '2024-09-29 15:22:30', '', 8091.90, NULL, NULL, NULL),
(89, 1, '2024-09-29 15:24:34', '', 2997.00, NULL, NULL, NULL),
(90, 1, '2024-09-29 15:28:14', '', 2997.00, NULL, NULL, NULL),
(91, 1, '2024-09-29 17:10:05', '', 14385.60, NULL, NULL, NULL),
(92, 1, '2024-09-29 18:31:55', '', 999.00, NULL, NULL, NULL),
(93, 1, '2024-09-29 18:34:31', '', 8091.90, NULL, NULL, NULL),
(94, 1, '2024-09-29 18:34:52', '', 999.00, NULL, NULL, NULL),
(95, 1, '2024-09-29 18:35:52', '', 999.00, NULL, NULL, NULL),
(96, 1, '2024-09-29 18:37:18', '', 2499.00, NULL, NULL, NULL),
(97, 1, '2024-09-29 19:08:27', '', 5697.15, NULL, NULL, NULL),
(98, 1, '2024-09-29 19:11:49', '', 5697.15, NULL, NULL, NULL),
(99, 1, '2024-09-29 19:26:49', '', 5697.15, NULL, NULL, NULL),
(100, 1, '2024-09-29 19:28:40', '', 0.00, NULL, NULL, NULL),
(101, 1, '2024-09-29 19:31:23', '', 0.00, NULL, NULL, NULL),
(102, 1, '2024-09-29 19:41:38', '', 1499.00, NULL, NULL, NULL),
(103, 1, '2024-09-29 20:28:33', 'shipped', 1198.00, 'Vattamakal House', '7306024781', 'cod'),
(104, 1, '2024-09-29 20:29:36', 'processing', 5696.20, 'Karuvelipady', '7034537942', 'online'),
(105, 1, '2024-09-29 20:31:36', 'processing', 1499.00, 'Vattamakal House', '7306024781', 'cod'),
(106, 1, '2024-09-29 21:15:52', 'processing', 6925.50, 'wsdawe', '7306024781', 'online'),
(107, 1, '2024-09-30 02:33:28', 'processing', 1794.00, 'Thoppumpady', '7306024781', 'cod'),
(108, 1, '2024-09-30 12:44:36', 'processing', 1499.00, 'ABC Villa ', '7306024781', 'online');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 23, 1, 1499.00),
(2, 2, 20, 1, 2499.00),
(3, 3, 21, 1, 999.00),
(4, 4, 22, 3, 999.00),
(5, 5, 22, 4, 999.00),
(6, 5, 23, 1, 1499.00),
(7, 5, 21, 2, 999.00),
(8, 5, 25, 1, 199.00),
(9, 5, 20, 3, 2499.00),
(10, 6, 20, 1, 2499.00),
(11, 6, 22, 1, 999.00),
(12, 6, 21, 1, 999.00),
(13, 6, 23, 2, 1499.00),
(14, 7, 26, 1, 299.00),
(15, 8, 20, 1, 2499.00),
(16, 8, 21, 1, 999.00),
(17, 8, 23, 2, 1499.00),
(18, 8, 26, 1, 299.00),
(19, 8, 31, 1, 299.00),
(20, 9, 20, 3, 2499.00),
(21, 9, 21, 4, 999.00),
(22, 9, 23, 2, 1499.00),
(23, 9, 26, 1, 299.00),
(24, 9, 31, 1, 299.00),
(25, 10, 20, 4, 2499.00),
(26, 10, 23, 2, 1499.00),
(27, 10, 26, 1, 299.00),
(28, 11, 20, 5, 2499.00),
(29, 11, 22, 1, 999.00),
(30, 11, 30, 2, 99.00),
(31, 11, 21, 2, 999.00),
(32, 12, 21, 1, 999.00),
(33, 13, 20, 1, 2499.00),
(34, 14, 20, 2, 2499.00),
(35, 15, 21, 2, 999.00),
(36, 15, 27, 1, 799.00),
(37, 16, 21, 3, 999.00),
(38, 16, 20, 7, 2499.00),
(39, 16, 22, 1, 999.00),
(40, 17, 21, 1, 999.00),
(41, 18, 20, 2, 2499.00),
(42, 18, 22, 1, 999.00),
(43, 19, 20, 1, 2499.00),
(44, 20, 22, 1, 999.00),
(45, 20, 20, 2, 2499.00),
(46, 21, 20, 2, 2499.00),
(47, 22, 20, 1, 2499.00),
(48, 23, 20, 1, 2499.00),
(49, 24, 20, 1, 2499.00),
(50, 25, 20, 1, 2499.00),
(51, 32, 21, 1, 999.00),
(52, 32, 20, 1, 2499.00),
(53, 35, 21, 1, 999.00),
(54, 36, 27, 1, 799.00),
(55, 36, 21, 1, 999.00),
(56, 37, 21, 1, 999.00),
(57, 42, 20, 1, 2499.00),
(58, 43, 23, 1, 1499.00),
(59, 44, 27, 1, 799.00),
(60, 47, 31, 1, 299.00),
(61, 48, 31, 1, 299.00),
(62, 49, 31, 1, 299.00),
(63, 50, 31, 1, 299.00),
(64, 51, 31, 1, 299.00),
(65, 52, 31, 1, 299.00),
(66, 53, 31, 1, 299.00),
(67, 54, 31, 1, 299.00),
(68, 55, 31, 1, 299.00),
(69, 56, 31, 1, 299.00),
(70, 57, 31, 1, 299.00),
(71, 58, 31, 1, 299.00),
(72, 59, 31, 1, 299.00),
(73, 60, 22, 1, 999.00),
(74, 63, 20, 1, 2499.00),
(75, 64, 20, 1, 2499.00),
(76, 65, 20, 1, 2499.00),
(77, 66, 20, 1, 2499.00),
(78, 67, 29, 1, 1699.00),
(79, 68, 20, 1, 2499.00),
(80, 69, 20, 1, 2499.00),
(81, 74, 31, 1, 299.00),
(82, 75, 31, 1, 299.00),
(83, 76, 21, 1, 999.00),
(84, 77, 21, 1, 999.00),
(85, 78, 21, 1, 999.00),
(86, 79, 21, 1, 999.00),
(87, 80, 21, 1, 999.00),
(88, 81, 21, 1, 999.00),
(89, 81, 20, 1, 2499.00),
(90, 83, 20, 1, 2499.00),
(91, 84, 20, 1, 2499.00),
(92, 87, 21, 1, 999.00),
(93, 88, 21, 9, 999.00),
(94, 89, 21, 3, 999.00),
(95, 90, 22, 3, 999.00),
(96, 91, 21, 16, 999.00),
(97, 92, 21, 1, 999.00),
(98, 93, 22, 9, 999.00),
(99, 94, 22, 1, 999.00),
(100, 95, 22, 1, 999.00),
(101, 96, 20, 1, 2499.00),
(102, 97, 20, 2, 2499.00),
(103, 97, 21, 1, 999.00),
(104, 98, 20, 2, 2499.00),
(105, 98, 21, 1, 999.00),
(106, 99, 20, 2, 2499.00),
(107, 99, 21, 1, 999.00),
(108, 102, 23, 1, 1499.00),
(109, 103, 22, 1, 999.00),
(110, 103, 25, 1, 199.00),
(111, 104, 23, 4, 1499.00),
(112, 105, 23, 1, 1499.00),
(113, 106, 23, 2, 1499.00),
(114, 106, 27, 5, 799.00),
(115, 106, 30, 3, 99.00),
(116, 107, 26, 5, 299.00),
(117, 107, 31, 1, 299.00),
(118, 108, 23, 1, 1499.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock_alert_level` int(11) DEFAULT 0,
  `additional_images` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `category_id`, `stock`, `image`, `stock_alert_level`, `additional_images`) VALUES
(20, 'Yamaha MT 15 Trident Crash Guard Mat Slider', 'This MT 15 crash guard can protect your motorcycle during an event of an accident or crash. This Crash guard is strongly built with additional sliders on the sides and gives added protection to your motorcycle. The stylish and sporty design of the product adds an aesthetic touch to your motorcycle.', 2499.00, 5, 8, 'MT Slider.png', 5, NULL),
(21, 'Yamaha MT 15 Expedition Carrier', 'Presenting specially designed back rack for Yamaha MT15. This is a simple and stylish rear carrier that lets you carry and mount additional luggage when commuting or when riding long distances. The design makes all the difference. It neither disturbs your pillion rider nor feels any inconvenience in riding. The product featured here is made with high-grade mild steel.', 999.00, 5, 0, 'MT Expedition.png', 5, NULL),
(22, 'Yamaha MT Trail Carrier', 'Carrying extra luggage on motorcycles is a hassle but the Yamaha MT 15 trail carrier can solve this for you. With a sleek finish and quality build, this carrier is designed to last. When you decide to take your naked street fighter to the open roads, our trail carrier is your trusted sidekick.', 999.00, 5, 0, 'MT Trail.png', 5, NULL),
(23, 'Yamaha MT 15 Frame Slider', 'Frame sliders minimize the damage to the frame (around the engine) and the fairing of the bike. Even a fall so minor could damage your fairing.  Replacing the fiber (or plastic) parts after a crash is expensive. So, keep your motorcycle as good as new after a fall, with these frame sliders! These Frame Sliders are sturdy and lightweight at the same time. It also has the added advantage of being highly abrasive. Therefore, if you fall off while riding, it sacrifices itself to a certain extent and absorbs the crash impact.', 1499.00, 5, 3, 'MT Frame Slider.png', 5, NULL),
(25, 'Yamaha MT 15 Short Visor Matt Black', 'The short visor is designed to make your bike look sleeker than what Yamaha intended to do. With the short build and the matte or glossy black finish, the visor slots in easily onto the vehicle accenting its look. The visor is made from corrosion-resistant material and built to last the seasons and the elements. This short visor is custom-built for MT-15. You don’t have to worry about adaptors, tapes and nuts and bolts. The visor is precision cut to slot in easily onto your MT-15.', 199.00, 5, 24, 'MT Short Visor.png', 5, NULL),
(26, 'Yamaha MT 15 Metal Visor', 'Give your Yamaha bike an edgy makeover! Get your MT 15 a cool upgrade with our sleek and stylish metal visor. With a metallic finish and elegant design, these visors are just what your two-wheeled buddy needs to turn everyone’s eyes. They are durable and built to last longer and, above all, make your bike the centre of everyone’s attention as you enter a parking area. Our metal visor can ensure you double the functionality of a normal windscreen by blocking more wind than usual shielding. Being one of the popular options for comfort riding, you will be availed with a lasting design along with your safe ride. Enjoy your ride with the cold wind with protection against bugs and frost.', 299.00, 5, 20, 'MT Metal Visor.png', 5, '[]'),
(27, 'Yamaha MT 15 Radiator Grill', 'The radiator grill delivers a powerful performance. It keeps your radiator safe from debris and additional damage.', 799.00, 5, 20, 'MT Radiator Grill.png', 5, NULL),
(28, 'Yamaha MT 15 Tail Tidy', 'The Yamaha MT 15 Tail Tidy makes your bike more naked and minimal. It gives the tail light a stylish new abode. Made with lightweight mild steel, this product reduces the clutter, improves the aesthetics and ensures performance.', 799.00, 5, 25, 'MT Tail Tidy.png', 5, NULL),
(29, 'Yamaha MT 15 Bend Pipe', 'If you have always wanted better sound and performance from your Yamaha, then look no further. Our aftermarket bend pipe is designed to give out optimal airflow through the exhaust thus generating better performance output. Pair this with a good quality slip-on exhaust to make your motorcycle the best sounding and performing motorcycle. The product featured here is designed for Yamaha MT15. The material used in manufacturing is stainless steel and the material makes it resistant towards rusting. This performance oriented product is a direct fit product and comes with easy installation.', 1699.00, 5, 25, 'MT Bend Pipe.png', 5, NULL),
(30, 'Yamaha MT 15 Winglet', 'Upgrade your Yamaha MT 15 with this winglet to set yourself apart. It is sleek, stylish and sporty. Built to withstand wind blast even at high speeds! It comes with an elongated reflective surface giving you a better rear view. New-age sporty aesthetic meets durability in this winglet.', 99.00, 5, 22, 'MT Winglet.png', 5, NULL),
(31, 'Yamaha MT 15 Carbon Metal Lever Guard', 'The Mission is to protect the rider’s hands, avoid accidental breakage of the lever in the event of impact and reduction of air pressure against the brake control even at high speed. The device ensures the brake lever system offers additional protection for the little finger. The outer part features a curved shape, making it easy to remove the hand quickly in the event of a high side.', 299.00, 5, 24, 'MT Metal Lever Guard.png', 5, NULL),
(32, 'Yamaha MT 15 Tail Light', 'On dark and foggy nights, increase your visibility for other riders on road with the Integrated LED Tail Light 1.0 For Yamaha MT15. With a durable design, you can be assured that the light is your companion for many rides in the future.', 2999.00, 5, 25, 'MT Tail Light.png', 5, NULL),
(33, 'Yamaha MT 15 Rolon Chain Sprocket', 'Rolon Brass Chain Sprocket Kit For MT 15', 2499.00, 5, 25, 'MT Chain Sprocket.png', 5, NULL),
(34, 'Yamaha R15 V2 Bubble Visor', 'Visors are necessary accessories for your motorcycle, making it easy to ride through windy areas, which is why we have the perfect visor for your YAMAHA R15 V2. This bubble visor is durable and simply gives your motorcycle a facelift. The aerodynamic bubble design ensures proper airflow which results in better wind protection and ride quality.', 299.00, 5, 25, 'V2 Bubble Visor.png', 5, NULL),
(35, 'Yamaha R15 V2 Metal Lever Guard', 'The Mission is to protect the rider’s hands, avoid accidental breakage of the lever in the event of impact and reduction of air pressure against the brake control even at high speed. The device ensures the brake lever system offers additional protection for the little finger. The outer part features a curved shape, making it easy to remove the hand quickly in the event of a high side.', 299.00, 5, 25, 'V2 Metal Lever Guard.png', 5, NULL),
(36, 'Yamaha R15 V2 Carbon Mirror with Led', 'Enhance your Yamaha R15 V2 with this sleek and stylish Carbon Mirror featuring integrated LED indicators. Crafted from high-quality carbon fiber, these mirrors are designed to provide a lightweight and durable solution for your motorcycle. The carbon fiber finish adds a touch of sophistication and a sporty edge to your bike, perfectly complementing its aggressive design.', 999.00, 5, 25, 'V2 Mirror with Led.png', 5, NULL),
(40, 'Yamaha MT Flash X Hazard Module', 'Flash X is a cool hazard flash module for your motorcycle which is a plug and play device that allows you to choose different combination of blink patterns on your motorcycle. Stop worrying about cutting and splicing of wires as this one just plugs into the stock socket of your motorcycle. Too many switches on your handle bar and no more space to mount another? problem solved !! We have integrated it with stock indicator switches of your motorcycle with just the combination of right, left and center clicks.', 1000.00, 5, 25, '125 Hazard.png', 0, NULL),
(41, 'Triumph Hazard', 'hazard module', 999.00, 1, 10, '200 Hazard.png', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `site_users`
--

CREATE TABLE `site_users` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` datetime DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_users`
--

INSERT INTO `site_users` (`id`, `username`, `email`, `password`, `registration_date`, `profile_pic`, `phone_number`) VALUES
(1, 'aldrinsony', 'aldrinsony20@gmail.com', '$2y$10$H1sC9ggnBBkd5eq7rBYaDOmVJOd56Tqk4ZsIhVVzE8QDsW5Xde7Sq', '2024-08-26 00:00:00', 'p3.png', '7306024781'),
(7, 'dave', 'dave123@gmail.com', '$2y$10$d516OtJCFDHRONWi5GJB8u6QcsIKVcfLVSVLx0bhYh5IF1VdQaLg.', '2024-09-29 21:08:37', NULL, NULL),
(8, 'hari', 'hari123@gmail.com', '$2y$10$yDOaZZAIUbFtdP3t8bdvHOlMWYyiwJbTS7CQ.u3O4lGVMtN2D63aa', '2024-09-29 21:21:26', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`) VALUES
(1, 'Aldrin Sony', 'fsgsheje@gmail.com', '$2y$10$q9XQv6OeF9oZGSMqaZd6XuXKc3rfjYHWzxpLmPNAxgar711O..pTC'),
(2, 'Dave', 'davenevin2004@gmail.com', '$2y$10$VMNwMrWFZseq499LWFuhgeHR4VThaZjNjAjOE25zAY7NZxpaiNTKW');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id`, `action`, `action_date`) VALUES
(9, 1, 'User Logged Out', '2024-09-28 19:30:05'),
(10, 1, 'User Logged In', '2024-09-28 19:30:31'),
(11, 1, 'User Submitted Feedback for Product ID 27', '2024-09-28 19:32:19'),
(12, 1, 'User added Product ID 21 to Cart', '2024-09-28 19:35:02'),
(24, 1, 'User added Product ID 23 to Cart', '2024-09-28 19:43:38'),
(25, 1, 'User added Product ID 20 to Cart', '2024-09-28 19:45:55'),
(27, 1, 'User Logged In', '2024-09-29 06:05:30'),
(28, 1, 'User Logged Out', '2024-09-29 06:07:55'),
(29, 1, 'User Logged In', '2024-09-29 09:38:55'),
(30, 1, 'User Logged Out', '2024-09-29 06:09:09'),
(31, 1, 'User Logged In', '2024-09-29 09:39:50'),
(32, 1, 'User Logged Out', '2024-09-29 06:09:58'),
(33, 1, 'User Logged In', '2024-09-29 09:40:23'),
(34, 1, 'User Logged Out', '2024-09-29 09:40:25'),
(35, 1, 'User Logged In', '2024-09-29 09:43:25'),
(36, 1, 'User added Product ID 21 to Cart', '2024-09-29 09:48:44'),
(37, 1, 'User Placed an Order, Order ID 87', '2024-09-29 09:49:00'),
(38, 1, 'User Logged In', '2024-09-29 09:52:05'),
(39, 1, 'User added Product ID 21 to Cart', '2024-09-29 09:52:13'),
(40, 1, 'User Placed an Order, Order ID 88', '2024-09-29 09:52:30'),
(41, 1, 'User Logged In', '2024-09-29 09:54:10'),
(42, 1, 'User added Product ID 21 to Cart', '2024-09-29 09:54:19'),
(43, 1, 'User Placed an Order, Order ID 89', '2024-09-29 09:54:34'),
(44, 1, 'User added Product ID 22 to Cart', '2024-09-29 09:57:48'),
(45, 1, 'User Placed an Order, Order ID 90', '2024-09-29 09:58:15'),
(46, 1, 'User added Product ID 22 to Cart', '2024-09-29 10:02:08'),
(47, 1, 'User added Product ID 22 to Cart', '2024-09-29 10:05:18'),
(48, 1, 'User Logged In', '2024-09-29 11:32:32'),
(49, 1, 'User added Product ID 21 to Cart', '2024-09-29 11:39:46'),
(50, 1, 'User Placed an Order, Order ID 91', '2024-09-29 11:40:05'),
(51, 1, 'User Logged In', '2024-09-29 12:51:18'),
(52, 1, 'User added Product ID 21 (Yamaha MT 15 Expedition Carrier) to Cart', '2024-09-29 13:01:17'),
(53, 1, 'User Placed an Order, Order ID 92', '2024-09-29 13:01:55'),
(54, 1, 'User added Product ID 22 (Yamaha MT Trail Carrier) to Cart', '2024-09-29 13:04:19'),
(55, 1, 'User Placed an Order, Order ID 93', '2024-09-29 13:04:31'),
(56, 1, 'User added Product ID 22 (Yamaha MT Trail Carrier) to Cart', '2024-09-29 13:04:40'),
(57, 1, 'User Placed an Order, Order ID 94', '2024-09-29 13:04:52'),
(58, 1, 'User added Product ID 22 (Yamaha MT Trail Carrier) to Cart', '2024-09-29 13:05:40'),
(59, 1, 'User Placed an Order, Order ID 95', '2024-09-29 13:05:52'),
(60, 1, 'User added Product ID 20 (Yamaha MT 15 Trident Crash Guard Mat Slider) to Cart', '2024-09-29 13:07:05'),
(61, 1, 'User Placed an Order, Order ID 96', '2024-09-29 13:07:18'),
(62, 1, 'User Placed an Order, Order ID 96', '2024-09-29 13:09:31'),
(63, 1, 'User added Product ID 20 (Yamaha MT 15 Trident Crash Guard Mat Slider) to Cart', '2024-09-29 13:24:43'),
(64, 1, 'User added Product ID 20 (Yamaha MT 15 Trident Crash Guard Mat Slider) to Cart', '2024-09-29 13:28:28'),
(65, 1, 'User Logged Out', '2024-09-29 13:33:34'),
(66, 1, 'User Logged In', '2024-09-29 13:34:14'),
(67, 1, 'User added Product ID 21 (Yamaha MT 15 Expedition Carrier) to Cart', '2024-09-29 10:05:08'),
(68, 1, 'User Placed an Order, Order ID 99', '2024-09-29 13:56:49'),
(69, 1, 'User Placed an Order, Order ID 100', '2024-09-29 13:58:40'),
(70, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 10:31:29'),
(71, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 10:40:13'),
(72, 1, 'User Placed an Order, Order ID 102', '2024-09-29 14:11:38'),
(73, 1, 'User Placed an Order, Order ID 103', '2024-09-29 14:58:33'),
(74, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 11:29:15'),
(75, 1, 'User Placed an Order, Order ID 104', '2024-09-29 14:59:36'),
(76, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 11:31:22'),
(77, 1, 'User Placed an Order, Order ID 105', '2024-09-29 15:01:37'),
(78, 1, 'User Placed an Order, Order ID 105', '2024-09-29 15:01:57'),
(79, 1, 'User Placed an Order, Order ID 105', '2024-09-29 15:02:56'),
(80, 1, 'User Logged In', '2024-09-29 15:33:39'),
(81, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 12:05:55'),
(82, 1, 'User Logged Out', '2024-09-29 15:37:30'),
(83, 7, 'User Logged In', '2024-09-29 15:38:49'),
(84, 7, 'User Logged Out', '2024-09-29 15:39:54'),
(85, 1, 'User Logged In', '2024-09-29 15:40:04'),
(86, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 12:10:10'),
(87, 1, 'User Logged In', '2024-09-29 15:44:11'),
(88, 1, 'User Submitted Feedback for Product ID 23', '2024-09-29 15:44:43'),
(89, 1, 'User added Product ID 27 (Yamaha MT 15 Radiator Grill) to Cart', '2024-09-29 12:15:10'),
(90, 1, 'User added Product ID 30 (Yamaha MT 15 Winglet) to Cart', '2024-09-29 12:15:15'),
(91, 1, 'User Placed an Order, Order ID 106', '2024-09-29 15:45:52'),
(92, 1, 'User Logged Out', '2024-09-29 15:50:21'),
(93, 8, 'User Logged In', '2024-09-29 15:51:38'),
(94, 8, 'User added Product ID 26 (Yamaha MT 15 Metal Visor) to Cart', '2024-09-29 12:32:49'),
(95, 8, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 12:33:30'),
(96, 8, 'User Logged Out', '2024-09-29 16:03:34'),
(97, 7, 'User Logged In', '2024-09-29 16:04:12'),
(98, 7, 'User added Product ID 27 (Yamaha MT 15 Radiator Grill) to Cart', '2024-09-29 12:34:21'),
(99, 7, 'User Logged Out', '2024-09-29 16:08:00'),
(100, 1, 'User Logged In', '2024-09-29 19:47:55'),
(101, 1, 'User added Product ID 26 (Yamaha MT 15 Metal Visor) to Cart', '2024-09-29 17:31:40'),
(102, 1, 'User added Product ID 31 (Yamaha MT 15 Carbon Metal Lever Guard) to Cart', '2024-09-29 17:31:45'),
(103, 1, 'User added Product ID 33 (Yamaha MT 15 Rolon Chain Sprocket) to Cart', '2024-09-29 17:31:52'),
(104, 1, 'User Placed an Order, Order ID 107', '2024-09-29 21:03:28'),
(105, 1, 'User Logged In', '2024-09-30 03:08:51'),
(106, 1, 'User added Product ID 27 (Yamaha MT 15 Radiator Grill) to Cart', '2024-09-29 23:39:04'),
(107, 1, 'User added Product ID 27 (Yamaha MT 15 Radiator Grill) to Cart', '2024-09-29 23:39:16'),
(108, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 23:39:37'),
(109, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 23:44:12'),
(110, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 23:47:43'),
(111, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 23:47:46'),
(112, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 23:48:53'),
(113, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-29 23:49:44'),
(114, 1, 'User Logged In', '2024-09-30 06:43:17'),
(115, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-30 03:16:02'),
(116, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-30 03:17:26'),
(117, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-30 03:20:03'),
(118, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-30 03:25:25'),
(119, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-30 03:27:33'),
(120, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-30 03:27:38'),
(121, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-30 03:28:03'),
(122, 1, 'User Logged Out', '2024-09-30 07:11:46'),
(123, 1, 'User Logged In', '2024-09-30 07:12:58'),
(124, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-30 03:43:24'),
(125, 1, 'User added Product ID 23 (Yamaha MT 15 Frame Slider) to Cart', '2024-09-30 03:43:44'),
(126, 1, 'User Placed an Order, Order ID 108', '2024-09-30 07:14:36'),
(127, 1, 'User Logged In', '2024-09-30 07:21:16'),
(128, 1, 'User Submitted Feedback for Product ID 26', '2024-09-30 07:21:31');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(1265, 8, 26, 1),
(1266, 1, 23, 1),
(1267, 1, 27, 1);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `site_users`
--
ALTER TABLE `site_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `wishlist_ibfk_1` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `site_users`
--
ALTER TABLE `site_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1268;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `site_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `site_users` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `site_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
