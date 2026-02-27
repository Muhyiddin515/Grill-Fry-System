-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 08:11 PM
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
-- Database: `grill_fry`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `user_id`, `user_type`, `address`) VALUES
(1, 9, 'employee', 'khyara'),
(2, 8, 'employee', 'akroum'),
(3, 7, 'employee', 'halba'),
(4, 6, 'employee', 'kobayat'),
(5, 5, 'employee', 'Saudi Arabia'),
(6, 4, 'employee', 'tripoli'),
(7, 5, 'chef', 'Egypt'),
(8, 4, 'chef', 'Lebanon'),
(9, 3, 'chef', 'Saudi Arabia'),
(10, 1, 'chef', 'Morocco'),
(11, 2, 'chef', 'Palestine'),
(12, 4, 'delivery', 'Indya'),
(13, 3, 'delivery', 'Lebanon'),
(14, 2, 'delivery', 'Sweid'),
(15, 1, 'delivery', 'Indya'),
(16, 28, 'customer', 'Country: Lebanon, City: hamra, Area: , Street: 200, Building: , Floor: , Apartment: '),
(17, 28, 'customer', 'Country: wadi khaled, City: trip, Area: akkar, Street: , Building: , Floor: , Apartment: '),
(18, 28, 'customer', 'Country: Lebanon, City: tripoli, Area: , Street: rejm khalaf, Building: , Floor: , Apartment: '),
(19, 28, 'customer', 'Country: sheikh mohamad, City: halba, Area: , Street: main street, Building: , Floor: , Apartment: '),
(20, 30, 'customer', 'Country: Lebanon, City: Akkar, Area: Wadi Khaled, Street: Rejem Khalaf, Building: Hamad House, Floor: 2, Apartment: 1');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `special_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `type`, `item_name`, `quantity`, `price`, `unit_price`, `special_note`, `created_at`) VALUES
(2, 1, 'Sushi', 'Sushi', 1, 12.00, 0.00, '', '2025-11-28 01:41:37'),
(3, 1, 'Sushi', 'Sushi Roll', 1, 10.00, 0.00, '', '2025-11-28 01:41:40'),
(4, 1, 'Cold Drink', 'Coca Cola', 1, 3.00, 0.00, '', '2025-11-28 01:42:17'),
(5, 1, 'Burger', 'Classic Burger', 1, 9.00, 0.00, '', '2025-11-28 01:52:52'),
(6, 1, 'Burger', 'Classic Burger', 2, 18.00, 0.00, '', '2025-11-28 02:04:00'),
(7, 1, 'Pizza', 'Margherita Pizza', 1, 12.00, 0.00, '', '2025-11-28 02:13:02'),
(8, 1, 'Meat', 'Grilled Steak', 1, 20.00, 0.00, '', '2025-11-28 02:14:03'),
(9, 1, 'Cold Drink', 'Coca Cola', 1, 3.00, 0.00, '', '2025-11-28 02:14:53'),
(10, 1, 'Sushi', 'Sushi', 1, 12.00, 0.00, '', '2025-11-28 02:21:07'),
(11, 1, 'Cold Drink', 'Coca Cola', 1, 3.00, 0.00, '', '2025-11-28 08:27:35'),
(12, 1, 'Sushi', 'Sushi', 2, 24.00, 0.00, 'bala toum', '2025-11-28 15:52:40'),
(13, 5, 'Sushi', 'Sushi Roll', 2, 20.00, 0.00, 'with onion', '2025-11-28 15:55:51'),
(14, 5, 'Sushi', 'Sushi Roll', 2, 20.00, 0.00, 'with onion', '2025-11-28 15:59:07'),
(15, 1, 'Sushi', 'Mini Sushi', 2, 16.00, 0.00, 'grilleed', '2025-11-28 20:49:21'),
(16, 0, 'Sushi', 'Sushi', 2, 24.00, 0.00, 'Bigg', '2025-11-28 21:47:57'),
(17, 0, 'Burger', 'Chicken Burger', 5, 50.00, 0.00, 'small', '2025-11-28 21:48:24'),
(18, 0, 'Sushi', 'Sushi', 1, 12.00, 0.00, '', '2025-11-28 21:56:21'),
(19, 26, 'Burger', 'Cheese Burger', 2, 22.00, 0.00, '', '2025-11-30 14:24:26'),
(21, 26, 'sandwich', 'chawerma', 2, 10.00, 0.00, '', '2025-12-01 13:33:08'),
(43, 27, 'Pizza', 'Pepperoni Pizza', 4, 48.00, 0.00, 'BBBB', '2025-12-03 09:17:38'),
(44, 27, 'Pizza', 'Margherita Pizza', 10, 100.00, 0.00, 'AAAAA', '2025-12-03 09:18:44'),
(46, 5, 'Drinks', 'Coca Cola', 1, 3.00, 0.00, '', '2025-12-10 00:10:25'),
(47, 22, 'Meats', 'BBQ Ribs', 4, 80.00, 0.00, '', '2025-12-10 09:23:46'),
(48, 22, 'Meats', 'Mixed Grill', 3, 66.00, 0.00, '', '2025-12-10 09:23:57'),
(49, 22, 'Burgers', 'Cheese Burger', 2, 22.00, 0.00, '', '2025-12-10 09:24:38'),
(58, 27, 'Drinks', 'Mineral water', 1, 3.00, 0.00, '', '2025-12-11 17:49:58'),
(60, 27, 'offer', 'Pepsi', 1, 3.00, 0.00, '', '2025-12-11 17:57:30'),
(61, 27, 'offer', 'Mineral water', 1, 3.00, 0.00, '', '2025-12-11 17:57:33'),
(62, 27, 'offer', 'Lemonade', 1, 4.00, 0.00, '', '2025-12-11 17:57:39'),
(63, 27, 'offer', 'Pepsi', 3, 3.00, 0.00, '', '2025-12-11 17:57:45'),
(64, 27, 'offer', 'Mineral water', 2, 3.00, 0.00, '', '2025-12-11 17:57:49'),
(65, 27, 'Drinks', 'Coca Cola', 1, 3.00, 0.00, NULL, '2025-12-11 18:04:14'),
(66, 27, 'Drinks', 'Pepsi', 1, 3.00, 0.00, NULL, '2025-12-11 18:04:18'),
(67, 27, 'Drinks', 'Mineral water', 1, 3.00, 0.00, NULL, '2025-12-11 18:04:20'),
(68, 27, 'Drinks', 'Pepsi', 2, 6.00, 0.00, NULL, '2025-12-11 18:06:52'),
(69, 27, 'Drinks', 'Mineral water', 2, 6.00, 0.00, NULL, '2025-12-11 18:06:58'),
(70, 27, 'Burgers', 'Cheese Burger', 1, 11.00, 0.00, NULL, '2025-12-11 18:08:16'),
(71, 27, 'Burgers', 'Chicken Burger', 1, 10.00, 0.00, NULL, '2025-12-11 18:08:19'),
(72, 27, 'Burgers', 'Cheese Burger', 2, 22.00, 0.00, NULL, '2025-12-11 18:08:23'),
(73, 27, 'Burgers', 'Classic Burger', 1, 9.00, 0.00, NULL, '2025-12-11 18:08:27'),
(74, 27, 'Drinks', 'Pepsi', 1, 3.00, 0.00, NULL, '2025-12-11 18:08:32'),
(75, 27, 'Drinks', 'Mineral water', 1, 3.00, 0.00, NULL, '2025-12-11 18:08:52'),
(76, 27, 'Drinks', 'Coca Cola', 1, 3.00, 0.00, NULL, '2025-12-11 18:10:04'),
(77, 27, 'Drinks', 'Pepsi', 1, 3.00, 0.00, NULL, '2025-12-11 18:10:08'),
(78, 27, 'Drinks', 'Pepsi', 1, 3.00, 0.00, NULL, '2025-12-11 18:12:53'),
(79, 27, 'Drinks', 'Pepsi', 1, 3.00, 0.00, NULL, '2025-12-11 18:12:55'),
(80, 27, 'Drinks', 'Mineral water', 1, 3.00, 0.00, NULL, '2025-12-11 18:12:58'),
(81, 27, 'Burgers', 'Cheese Burger', 1, 11.00, 0.00, NULL, '2025-12-11 18:13:02'),
(135, 31, 'Burgers', 'Classic Burger', 2, 18.00, 0.00, NULL, '2025-12-16 14:21:42'),
(205, 28, 'Burgers', 'Classic Burger', 2, 18.00, 0.00, NULL, '2026-01-06 13:52:45'),
(206, 28, 'Drinks', 'Pepsi', 2, 6.00, 0.00, NULL, '2026-01-06 13:52:45'),
(207, 28, 'Burgers', 'Classic Burger', 2, 18.00, 0.00, NULL, '2026-01-06 13:53:28'),
(208, 28, 'pasta', 'Spaghetti Bolognese', 4, 134.40, 33.60, '2 with debes remmen', '2026-01-06 18:28:49'),
(209, 28, 'Sushi', 'Sushi', 1, 12.00, 0.00, NULL, '2026-01-06 18:31:22'),
(210, 28, 'Sushi', 'Sushi Roll', 1, 10.00, 0.00, NULL, '2026-01-06 18:31:22');

-- --------------------------------------------------------

--
-- Table structure for table `chefs`
--

CREATE TABLE `chefs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `salary` double NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `working_time` varchar(50) DEFAULT 'Full Time',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chefs`
--

INSERT INTO `chefs` (`id`, `name`, `email`, `specialty`, `salary`, `image`, `working_time`, `is_active`) VALUES
(1, 'Chef Marco', 'marco@example.com', 'Pastry Chef', 1500, 'marco.jpg', 'Full Time', 1),
(2, 'Chef Layla', 'layla@example.com', 'Grilled chicken', 1300, 'layla.jpg', 'Part Time', 1),
(3, 'Chef Harris', 'harris@example.com', 'Seafood Specialist', 1600, 'harris.jpg', 'Night Shift', 1),
(4, 'Chef Sami', 'sami@example.com', 'Tabbouleh', 1400, 'sami.jpg', 'Full Time', 0),
(5, 'Chef Tayma', 'tayma@example.com', 'Grill Master', 1200, 'tayma.jpg', 'Full Time', 0);

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `vehicle` varchar(100) NOT NULL,
  `salary` double NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `working_time` varchar(50) DEFAULT 'Full Time',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`id`, `name`, `phone`, `vehicle`, `salary`, `image`, `working_time`, `is_active`) VALUES
(1, 'Ali Mansour', '70988766', 'Motorcycle', 600, 'ali.jpg', 'Full Time', 1),
(2, 'Rami Haddad', '76220445', 'Scooter', 550, 'rami.jpg', 'Part Time', 1),
(3, 'Karim Youssef', '71332099', 'Motorcycle', 580, 'karim.jpg', 'Night Shift', 1),
(4, 'Noor Saad', '76559988', 'Car', 700, 'noor.jpg', 'Full Time', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `working_time` varchar(50) DEFAULT 'Full Time',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `position`, `salary`, `image`, `working_time`, `is_active`) VALUES
(4, 'Sara Khalil', 'sara.khalil@example.com', 'Waitress', 650.00, 'sara.jpg', 'Full Time', 1),
(5, 'Mohamad Ali', 'mohamad.ali@example.com', 'Assistant', 750.00, 'mohamad.jpg', 'Full Time', 1),
(6, 'Rana Nasser', 'rana.nasser@example.com', 'Cashier', 700.00, 'rana.jpg', 'Full Time', 1),
(7, 'Jad Hamdan', 'jad.hamdan@example.com', 'employee', 900.00, 'jad.jpg', 'Full Time', 1),
(8, 'Maya fares', 'maya.fares@gmail.com', 'Manager', 1200.00, 'maya.jpg', 'Part Time', 1),
(9, 'Omar Rustom', 'omar.rustom@example.com', 'waiters', 500.00, 'omar.jpg', 'Full Time', 0);

-- --------------------------------------------------------

--
-- Table structure for table `global_discounts`
--

CREATE TABLE `global_discounts` (
  `id` int(11) NOT NULL,
  `discount_name` varchar(100) NOT NULL,
  `discount_percent` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `global_discounts`
--

INSERT INTO `global_discounts` (`id`, `discount_name`, `discount_percent`, `start_date`, `end_date`, `is_active`, `created_at`) VALUES
(7, 'new year', 30, '2026-01-03', '2026-02-28', 1, '2026-01-03 19:32:40');

-- --------------------------------------------------------

--
-- Table structure for table `grill_fry_bookings`
--

CREATE TABLE `grill_fry_bookings` (
  `id` int(11) NOT NULL,
  `booking_number` varchar(20) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `people` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `canceled` tinyint(1) DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_type` varchar(20) NOT NULL DEFAULT 'cash',
  `table_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grill_fry_bookings`
--

INSERT INTO `grill_fry_bookings` (`id`, `booking_number`, `name`, `phone`, `people`, `date`, `time`, `user_id`, `canceled`, `status`, `payment_type`, `table_number`) VALUES
(7, NULL, 'mehyedin', '03384525', 3, '2025-07-29', '08:37:00', 13, 1, 'pending', 'cash', 0),
(8, NULL, 'Dalal', '03384525', 3, '2025-07-29', '23:50:00', 13, 0, 'pending', 'cash', 0),
(9, NULL, 'Nawal', '76123456', 2, '2025-07-15', '01:50:00', 12, 0, 'accepted', 'cash', 0),
(10, NULL, 'Ahmad', '03456789', 5, '2025-07-25', '13:50:00', 13, 1, 'pending', 'cash', 0),
(11, NULL, 'Kamal', '03384525', 5, '2025-07-29', '10:54:00', 12, 0, 'pending', 'cash', 0),
(12, NULL, 'aliiii', '76543267', 6, '2025-07-29', '11:19:00', 13, 0, 'accepted', 'cash', 0),
(13, NULL, 'mohye', '12345678', 1, '2025-07-30', '00:29:00', 13, 0, 'pending', 'cash', 0),
(14, NULL, 'mohye', '12345678', 1, '2025-07-30', '00:29:00', 13, 1, 'pending', 'cash', 0),
(15, NULL, 'Imaddddd', '03313299', 4, '2025-07-31', '01:50:00', 14, 0, 'pending', 'cash', 0),
(16, NULL, 'isdiia', '71675749', 3, '2025-07-29', '18:00:00', 13, 0, 'pending', 'cash', 0),
(20, NULL, 'test', '71675748', 9, '2025-07-29', '19:46:00', 13, 0, 'pending', 'cash', 0),
(21, NULL, 'ahmad', '71675748', 9, '2025-07-29', '19:46:00', 13, 0, 'pending', 'cash', 0),
(22, NULL, 'ahmad', '03456789', 4, '2025-07-30', '21:33:00', 13, 0, 'pending', 'cash', 0),
(23, NULL, 'alii', '12345678', 2, '2025-07-30', '12:01:00', 13, 0, 'pending', 'cash', 0),
(26, NULL, 'hani', '12345678', 4, '2025-09-09', '07:59:00', 18, 0, 'pending', 'cash', 0),
(27, NULL, 'muhyii', '09876543', 4, '2025-10-17', '21:05:00', 20, 1, 'accepted', 'cash', 0),
(28, NULL, 'muhyii', '09876543', 7, '2025-11-09', '22:44:00', 20, 0, 'pending', 'cash', 0),
(29, NULL, 'hadi', '71608790', 4, '2026-12-23', '17:55:00', 23, 0, 'pending', 'cash', 0),
(41, NULL, 'khaled', '70485543', 7, '2025-11-28', '13:31:00', 1, 1, 'pending', 'cash', 0),
(43, NULL, 'khaled', '70485543', 7, '2025-11-28', '13:31:00', 1, 1, 'pending', 'cash', 0),
(45, NULL, 'malaka', '03139057', 2, '2025-11-05', '13:04:00', 1, 0, 'pending', 'cash', 0),
(47, NULL, 'hamad', '03139057', 4, '2025-12-01', '12:14:00', 28, 1, 'pending', 'cash', 0),
(48, NULL, 'mehyedin', '71675749', 1, '2025-12-24', '20:13:00', 28, 1, 'pending', 'online', 0),
(49, NULL, 'mehyedin', '71675749', 1, '2025-12-24', '20:13:00', 28, 1, 'pending', 'online', 0),
(50, NULL, 'Imad', '03384525', 5, '0007-05-06', '15:42:00', 28, 1, 'pending', 'online', 0),
(51, NULL, 'khaled', '71675749', 3, '2025-12-01', '09:56:00', 28, 0, 'pending', 'cash', 0),
(52, NULL, 'hamad', '03313299', 12, '2022-12-23', '12:22:00', 28, 0, 'pending', 'card', 0),
(53, NULL, 'hamad', '03384525', 11, '1212-12-12', '00:12:00', 27, 1, 'pending', 'cash', 0),
(54, NULL, 'mehyedin', '03384525', 3, '2025-12-02', '07:27:00', 27, 1, 'pending', 'cash', 0),
(55, NULL, 'hamad', '03384525', 5, '2025-12-26', '07:33:00', 27, 1, 'pending', 'cash', 0),
(56, NULL, 'hamad', '03384525', 3, '2025-12-03', '21:41:00', 28, 0, 'pending', 'cash', 0),
(57, NULL, 'hamad', '03384525', 4, '2025-12-03', '23:02:00', 28, 0, 'pending', 'card', 0),
(58, NULL, 'hamad', '03384525', 5, '2025-12-03', '13:25:00', 27, 1, 'pending', 'cash', 0),
(59, NULL, 'dana', '03384525', 3, '2025-12-03', '11:57:00', 27, 1, 'pending', 'card', 0),
(60, NULL, 'mehyedin', '87654321', 3, '2026-02-04', '06:43:00', 5, 0, 'accepted', 'cash', 0),
(61, NULL, 'samiii', '03384525', 4, '2025-12-11', '21:36:00', 28, 0, 'pending', 'cash', 0),
(62, NULL, 'sara', '031390057', 5, '2025-12-24', '18:54:00', 27, 1, 'pending', 'cash', 4),
(63, NULL, 'sara', '031390057', 5, '2025-12-11', '12:08:00', 27, 0, 'accepted', 'online', 5),
(64, NULL, 'hamad', '81822823', 20, '2025-12-01', '00:30:00', 28, 0, 'pending', 'card', 32),
(65, NULL, 'hamad', '81822823', 12, '2025-12-16', '20:00:00', 28, 0, 'pending', 'card', 13),
(66, NULL, 'hamad', '81822823', 20, '2025-12-16', '20:00:00', 28, 0, 'pending', 'cash', 32),
(67, NULL, 'hamad', '81822823', 11, '2025-12-16', '23:14:00', 28, 0, 'pending', 'card', 2),
(68, NULL, 'hamad', '81822823', 11, '2025-12-16', '23:14:00', 28, 0, 'pending', 'cash', 3),
(69, NULL, 'malak', '81822827', 5, '2025-12-17', '07:50:00', 30, 0, 'pending', 'cash', 5),
(70, NULL, 'hamad', '81822823', 10, '2025-12-21', '15:59:00', 28, 0, 'pending', 'cash', 12),
(71, NULL, 'sara', '03139057', 10, '2025-12-21', '15:07:00', 28, 0, 'pending', 'cash', 12),
(72, 'BK-00072', 'Sarah', '03139057', 20, '2025-12-30', '14:30:00', 28, 0, 'pending', 'cash', 2),
(73, 'BK-00073', 'muhyii', '70433255', 5, '2026-01-02', '22:53:00', 30, 0, 'pending', 'cash', 6),
(74, 'BK-00074', 'sara', '03139057', 10, '2026-01-04', '03:51:00', 30, 0, 'pending', 'cash', 12),
(75, 'BK-00075', 'Sara', '03139057', 3, '2026-01-07', '20:50:00', 28, 0, 'pending', 'card', 4),
(76, 'BK-00076', 'mehye', '03384525', 2, '2026-01-13', '22:00:00', 28, 0, 'pending', 'online', 2),
(77, 'BK-00077', 'malaka', '70433255', 3, '2026-01-13', '22:00:00', 28, 0, 'pending', 'cash', 4);

-- --------------------------------------------------------

--
-- Table structure for table `grill_fry_users`
--

CREATE TABLE `grill_fry_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `phone` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birthday` date NOT NULL DEFAULT '2000-01-01',
  `profile_image` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grill_fry_users`
--

INSERT INTO `grill_fry_users` (`id`, `name`, `email`, `password`, `admin`, `phone`, `gender`, `birthday`, `profile_image`, `avatar`, `created_at`) VALUES
(1, 'Admin Grill & Fry', 'admin@grillfry.com', '$2y$10$wFJSP50bpvS/OHvSrmV7pexR7fDhKKdneiDlEpGfbBNrX3gDg.u7m', 0, '70123456', 'female', '2004-12-27', NULL, NULL, '2025-12-31 21:37:38'),
(4, 'malaka', 'malaka@mail', '$2y$10$Hbd.RjbIRfXD1opKvYzimuvWbMs73CW6ZMJem./OFKsysWVqIsqTi', 0, '236842374', 'female', '2025-11-18', NULL, NULL, '2025-12-31 21:37:38'),
(5, 'mohyii', 'mohyii@gmail.com', '$2y$10$nLnk0/RcssxS1NlZ8Rdi4./3BSNGyW0npTpO2vei2JAjOInYUmjYS', 0, '03139057', 'male', '2025-11-28', NULL, NULL, '2025-12-31 21:37:38'),
(11, 'Fadiii', 'Fadiii@gmail.com', '$2y$10$45ej2.QymPqmVWuGpsABMONElfAFpuqXxplY2JpkeApWXolFF8Hb6', 0, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(12, 'Sami', 'Sami@hamad.123', '$2y$10$HgSgjfdL4Qpk7aNRTXYJuOzDdN/aCEUOTRBTgQzTExi0hqVk409Za', 0, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(13, 'mehyedin', 'mehyedin@hmd.123', '$2y$10$4jlV2zinE9HnY.WsP04D0eH155OVgbb03cFmJ/q4ofKqk0tybyUzm', 0, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(14, 'Imad', 'imad@hamad.123', '$2y$10$.3ZLi5mx5BGYUdlgR8hQt.4EY5DN4urD7UYsexOoVAKcjyEC7aTGG', 0, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(16, 'admin', 'admin@gmail.com', '$2y$10$C8lQyyyEdVYsfOl2rK2EZ.0NlZAhnU9LGeNxxojQ70ovAo08jAbDe', 1, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(17, 'yasin', 'yasin@gmail.com', '$2y$10$.E8yXLdZWpi5/9xY0WPcKemxOFfbsdHp4TGPxwhCTpwBfqsz5u4/C', 0, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(18, 'muhyiddin', 'muhyii@gmail.123', '$2y$10$4KR3d9AxaQFy8ZMNusIry.BnxXvf/ZEIP5lStHbxU4TjlRan8C3ae', 0, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(19, 'sarah', 'sara@hamad.12', '$2y$10$arx6IY46XNjYXIs23ozjbOKqCA1yAFMGbNyrHNp2mpzpszz0CDbWK', 1, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(20, 'muhyii', 'mohyii.hmd@gmail.com', '$2y$10$QU9RiPyByXAuU/VyrjEhzOM5E.hSMuv6acnVYFlmaIw2HlKMDNWGS', 0, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(21, 'khaled', 'khaled@hamad', '$2y$10$kl0rUp8XBmU2txgCQ8rmIuqarbChptel0Aw2Poi4biicyhwTOTfBe', 1, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(22, 'Admen', 'admen@gmail.com', '$2y$10$ir1sav6Jx.8jECr7iVxTv.LpD7wzw/4uviAvQV1Yh1RrBouTN3yG2', 1, '76543267', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(23, 'hadi', 'khoderhadi90@gmail.com', '$2y$10$3YbPwVgORpndkOa0TUtrT.Jork0bMt/5lAmIyQB989sgVnigVaY4u', 0, '', '', '2000-01-01', NULL, NULL, '2025-12-31 21:37:38'),
(24, 'mohyii', 'mohyiddin@gmail.com', '$2y$10$fs5sU4co54rStXLQW/5fOOM7n4ZZFmHcwjt.0vzp9ZkO8cqSYVQoq', 1, '03384525', 'Male', '2025-11-30', NULL, NULL, '2025-12-31 21:37:38'),
(25, 'AHMAD', 'ahmad@gmail.com', '$2y$10$OdooEwRRdqhp0Htfes9Tw.wjGorI13lA2igM6maSwVaTwILIG5B46', 1, '46738334', 'Male', '2025-11-30', NULL, NULL, '2025-12-31 21:37:38'),
(26, 'hajj', 'hajj@gmail.com', '$2y$10$OO/6MqJx9Dx0G59KDetUg.UUeOJPbB6.hoewguyrAzlv/UZyyiTN2', 0, '12345678', 'Male', '2025-12-06', NULL, NULL, '2025-12-31 21:37:38'),
(27, 'sarah', 'sara@gmail.com', '$2y$10$iphaaoCIV/p5U2yV2uWe2.iYhp8xjSf6mRpUB8qfmGJNrKoQ2FBNG', 1, '03139057', 'Female', '2004-12-08', 'uploads/admins/admin_27_1766348662.png', NULL, '2025-12-31 21:37:38'),
(28, 'hamad', 'hamad@sara', '$2y$10$E1hCTaeul1wa4dlCJk6FlORTNxHwPxLebbezTE1U7BJXsd80LqM.G', 0, '81822823', 'Female', '2025-12-01', NULL, '1767209751_default_avatar.png', '2025-12-31 21:37:38'),
(29, 'khaled', 'user@gmail.com', '$2y$10$6Ndqb0jGhwEJ4YMk7ybBAO6PpYw5p0HXIpXYK9R5dBHlo8qPSgKjG', 0, '76532198', 'Male', '2025-12-10', NULL, NULL, '2025-12-31 21:37:38'),
(30, 'malak', 'malak@gmail.com', '$2y$10$qy1QUHnk5HLlHFT2nFwjqO/Wd7UhLuGhNnLnZWm7q3Rs7oCt7P4wq', 0, '81822827', 'Female', '2025-12-15', NULL, '1767368295_default_avatar.png', '2025-12-31 21:37:38'),
(31, 'Dr. Maher', 'dr.maher@gmail.com', '$2y$10$Zb5nBYi7IHrEO0zMpDPpTuDZ/Ku7ymlnQbtTud8Ic7HT2pCDvdoEC', 1, '03444555', 'Male', '2025-12-15', 'uploads/admins/admin_31_1767210603.png', NULL, '2025-12-31 21:37:38'),
(32, 'kamil', 'kamil@gmail.com', '$2y$10$x1O6n4S4.jmhYiyr0dO3WunXbKiAWP3gt3JbIfG01.CvVAAGHKfMi', 1, '70809070', 'Male', '2002-09-03', NULL, NULL, '2026-01-04 01:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `position` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `title`, `image_path`, `section`, `is_active`, `position`) VALUES
(1, 'Shrimpy Pizza', 'image/f1.png', 'our_menu', 1, 5),
(2, 'Vegi Pizza', 'image/f3.png', 'our_menu', 1, 4),
(3, 'Multi Pizza', 'image/f6.png', 'our_menu', 1, 2),
(4, 'Mushroom Pizza', 'image/mushroompizza.png', 'our_menu', 1, 3),
(5, 'Classic Burger', 'image/f7.png', 'our_menu', 1, 9),
(6, 'Grilled Chicken Burger', 'image/f8.png', 'our_menu', 1, 6),
(7, 'Beef Burger', 'image/f2.png', 'our_menu', 1, 8),
(8, 'Fillet Burger', 'image/filletburger.png', 'our_menu', 1, 7),
(11, 'Customer Image', 'uploads/gallery/1766316875_5070.jpg', 'gallery', 1, 9),
(13, 'Customer Image', 'uploads/gallery/1766316949_1180.jpg', 'gallery', 1, 10),
(21, 'Customer Image', 'uploads/gallery/1766319833_6324.webp', 'gallery', 0, 13),
(22, 'Customer Image', 'uploads/gallery/1767368435_3058.png', 'gallery', 0, 14),
(23, 'Spaghetti Bolognese', 'uploads/items/1767554610_1765803654_lasagna (1).webp', 'our_menu', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `discount_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `type_id`, `item_name`, `description`, `price`, `image`, `created_at`, `is_active`, `discount_id`) VALUES
(4, 1, 'Sushi', 'Rice, Nori, Fresh fish (salmon/tuna), Soy sauce, Wasabi, Pickled ginger', 12.00, 'image/sushi1.jpg', '2025-12-01 14:13:43', 1, 7),
(5, 1, 'Sushi Roll', 'Rice, Nori, Cucumber, Avocado, Fresh fish, Sesame seeds', 10.00, 'image/sushiRoll.jpg', '2025-12-01 14:13:43', 1, 7),
(6, 1, 'Mini Sushi', 'Mini rice balls, Fresh salmon/tuna, Nori strips, Soy sauce', 8.00, 'image/miniSushi.jpg', '2025-12-01 14:13:43', 1, 7),
(7, 1, 'Dragon Roll', 'Tempura shrimp, Rice, Nori, Avocado, Cucumber, Eel sauce, Sesame seeds', 15.00, 'image/dragonroll.jpg', '2025-12-01 14:13:43', 1, 7),
(8, 1, 'California Roll', 'Crab stick, Avocado, Cucumber, Rice, Nori, Sesame seeds', 14.00, 'image/californiaroll.jpg', '2025-12-01 14:13:43', 1, 7),
(9, 1, 'Salmon Sushi', 'Rice, Fresh salmon, Soy sauce, Wasabi, Pickled ginger', 11.00, 'image/salmonSushi.jpg', '2025-12-01 14:13:43', 1, 7),
(10, 2, 'Classic Burger', 'Beef patty, Lettuce, Tomato, Onion, Pickles, Cheese', 9.00, 'image/classicburger.jpg', '2025-12-01 14:17:12', 1, 7),
(11, 2, 'Cheese Burger', 'Beef patty, Double cheese, Onion, Tomato, Sauce', 11.00, 'image/cheeseburger.jpg', '2025-12-01 14:17:12', 1, 7),
(12, 2, 'Chicken Burger', 'Crispy chicken fillet, Lettuce, Mayo, Pickles', 10.00, 'image/chickenburger.jpg', '2025-12-01 14:17:12', 1, 7),
(13, 2, 'BBQ Burger', 'Beef patty, BBQ sauce, Onion rings, Cheese, Lettuce', 13.00, 'image/bbqburger.jpg', '2025-12-01 14:17:12', 1, 7),
(14, 2, 'Spicy Burger', 'Beef patty, Jalapenos, Spicy sauce, Cheese, Onion', 12.00, 'image/spicyburger.jpg', '2025-12-01 14:17:12', 1, 7),
(15, 2, 'Double Beef Burger', 'Two beef patties, Cheese, Pickles, Lettuce, Tomato', 15.00, 'image/doubleburger.jpg', '2025-12-01 14:17:12', 1, 7),
(16, 3, 'Margherita Pizza', 'Tomato sauce, Mozzarella, Basil, Olive oil', 10.00, 'image/margheritapizza.jpg', '2025-12-01 14:21:48', 1, 7),
(17, 3, 'Pepperoni Pizza', 'Tomato sauce, Mozzarella, Pepperoni slices', 12.00, 'image/pepperonipizza.jpg', '2025-12-01 14:21:48', 1, 7),
(18, 3, 'BBQ Chicken Pizza', 'Chicken, BBQ sauce, Mozzarella, Onions', 14.00, 'image/bbqchickenpizza.jpg', '2025-12-01 14:21:48', 1, 7),
(19, 3, 'Veggie Pizza', 'Bell peppers, Olives, Onions, Mushrooms, Mozzarella', 11.00, 'image/vegetarianpizza.jpg', '2025-12-01 14:21:48', 1, 7),
(20, 3, 'Four Cheese Pizza', 'Mozzarella, Parmesan, Gorgonzola, Cheddar', 15.00, 'image/fourcheesepizza.jpg', '2025-12-01 14:21:48', 1, 7),
(21, 3, 'Hawaiian Pizza', 'Pineapple, Ham, Tomato sauce, Mozzarella', 13.00, 'image/hawaiian.jpg', '2025-12-01 14:21:48', 1, 7),
(22, 4, 'Grilled Steak', 'Beef steak, Grilled, Served with herbs and fries', 18.00, 'image/grilledsteak.jpg', '2025-12-01 14:24:56', 1, 7),
(23, 4, 'BBQ Ribs', 'Slow-cooked ribs, BBQ sauce, Served with coleslaw', 20.00, 'image/beefribs.jpg', '2025-12-01 14:24:56', 1, 7),
(24, 4, 'Mixed Grill', 'Beef, Chicken, Kebab, Sausage, Grilled vegetables', 22.00, 'image/mixedgrill.jpg', '2025-12-01 14:24:56', 1, 7),
(25, 4, 'Lamb Chops', 'Grilled lamb chops, Herbs, Potatoes', 21.00, 'image/lambchops.jpg', '2025-12-01 14:24:56', 1, 7),
(26, 4, 'Grilled Sausage', 'Grilled sausage breast, Vegetables, Light sauce', 15.00, 'image/grilledsausage.jpg', '2025-12-01 14:24:56', 1, 7),
(27, 4, 'Beef Kebab', 'Grilled kebab skewers, Onions, Parsley, Bread', 16.00, 'image/beefkebab.jpg', '2025-12-01 14:24:56', 1, 7),
(28, 5, 'Coca Cola', 'Cold soft drink served chilled', 3.00, 'image/cocacola.jpg', '2025-12-01 14:26:06', 1, 7),
(29, 5, 'Pepsi', 'Refreshing cold drink', 3.00, 'image/pepsi.jpg', '2025-12-01 14:26:06', 1, 7),
(30, 5, 'Mineral water', 'Mineral cold water', 3.00, 'image/mineralwater.jpg', '2025-12-01 14:26:06', 1, 7),
(31, 5, 'Orange Juice', 'Fresh orange juice, no sugar added', 5.00, 'image/orange.jpg', '2025-12-01 14:26:06', 1, 7),
(32, 5, 'Lemonade', 'Cold lemonade with mint', 4.00, 'image/lemonade.jpg', '2025-12-01 14:26:06', 1, 7),
(33, 5, 'Iced Tea', 'Cold black tea with lemon', 4.00, 'image/icedtea.jpg', '2025-12-01 14:26:06', 1, 7),
(37, 7, 'Spaghetti Bolognese', 'Spaghetti pasta with rich tomato sauce, minced beef, onion, garlic, and Italian herbs.', 12.00, 'uploads/items/1765803654_lasagna (1).webp', '2025-12-15 12:39:45', 1, 7),
(38, 7, 'Chicken Alfredo', 'Fettuccine pasta with grilled chicken breast, creamy Alfredo sauce, and Parmesan cheese.', 13.00, 'uploads/items/1765803672_lasagna (4).jpg', '2025-12-15 12:39:45', 1, 7),
(39, 7, 'Penne Arrabbiata', 'Penne pasta with spicy tomato sauce, garlic, chili flakes, and olive oil.', 11.00, 'uploads/items/1765803687_Penne-Arrabbiata-1-2.jpg', '2025-12-15 12:39:45', 1, 7),
(40, 7, 'Seafood Pasta', 'Pasta with shrimp, calamari, mussels, and light tomato or creamy sauce.', 15.00, 'uploads/items/1765803708_lasagna (3).jpg', '2025-12-15 12:39:45', 1, 7),
(41, 7, 'Lasagna', 'Layers of pasta with minced meat, béchamel sauce, tomato sauce, and mozzarella cheese.', 14.00, 'uploads/items/1765803623_lasagna (1).jpg', '2025-12-15 12:39:45', 1, 7),
(42, 7, 'Pesto Pasta', 'Pasta tossed in fresh basil pesto sauce with garlic, pine nuts, olive oil, and Parmesan.', 12.00, 'uploads/items/1765803791_Bt3Fm3icu5mVUVJSUGylr5-al1xUWVCSmqJbkpRnoJdeXJJYkpmsl5yfq5-Zm5ieWmxfaAuUsXL0S7F0Tw4JL8ov808qcDRIdfRytiwvCff0cPQKCvM0KI2PT630rkoPyfUI9ixwd88qC3TNCXYpUSsGAIrFJtM', '2025-12-15 12:39:45', 1, 7),
(43, 7, 'Carbonara', 'Spaghetti with eggs, cream, smoked beef, black pepper, and Parmesan cheese.', 13.00, 'uploads/items/1765804036_lasagna (5).jpg', '2025-12-15 12:39:45', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `money`
--

CREATE TABLE `money` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Wish Money','Card','Cash on Delivery') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `money`
--

INSERT INTO `money` (`id`, `order_id`, `user_id`, `amount`, `payment_method`, `created_at`) VALUES
(1, 10, 28, 6.00, 'Card', '2025-12-14 14:08:03'),
(2, 9, 28, 20.00, 'Card', '2025-12-14 14:17:39'),
(3, 20, 28, 44.00, 'Cash on Delivery', '2025-12-15 11:50:49'),
(4, 24, 30, 153.00, 'Cash on Delivery', '2025-12-16 14:14:47');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `old_price` double NOT NULL,
  `new_price` double NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `show_day` varchar(20) DEFAULT 'all',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `title`, `description`, `old_price`, `new_price`, `image`, `show_day`, `is_active`) VALUES
(1, 'Pizza Family Box', 'Large Family Pizza + Free 1.25L Drink', 25, 19.99, 'pizzaoffer.jpg', 'monday', 1),
(2, 'Burger Combo', '2 Beef Burgers + Fries + Soft Drink', 18, 12.5, 'burgercombo.jpg', 'tuesday', 1),
(3, 'Sushi Mix Offer', '12 Pieces Mixed Sushi (Fresh Salmon + Crab)', 30, 22, 'sushioffer.jpg', 'saturday', 1),
(4, 'Chicken Bucket', '8 Pieces Crispy Chicken + Fries + Sauce', 20, 14.99, 'fridayoffer.jpg', 'wednesday', 1),
(5, 'Offer-3', 'Classic ', 10, 9, '1765966099_1765803623_lasagna (1).jpg', 'all', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL,
  `voucher_image` varchar(255) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `delivery_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`, `payment_method`, `voucher_image`, `address_id`, `delivery_id`) VALUES
(1, 28, 526.50, 'done', '2025-12-12 14:00:53', NULL, NULL, NULL, NULL),
(2, 28, 526.50, 'pending', '2025-12-12 14:02:37', NULL, NULL, NULL, NULL),
(3, 28, 62.00, 'pending', '2025-12-12 14:03:25', NULL, NULL, NULL, NULL),
(4, 28, 6.00, 'pending', '2025-12-12 14:20:32', NULL, NULL, NULL, NULL),
(5, 28, 44.00, 'pending', '2025-12-12 14:24:14', NULL, NULL, NULL, NULL),
(6, 28, 36.00, 'pending', '2025-12-12 14:37:08', NULL, NULL, NULL, NULL),
(7, 28, 6.00, 'pending', '2025-12-12 14:45:42', 'Wish Money', NULL, NULL, NULL),
(8, 28, 6.00, 'done', '2025-12-12 14:50:05', 'Wish Money', NULL, NULL, NULL),
(9, 28, 20.00, 'done', '2025-12-12 14:56:33', 'Card', NULL, NULL, NULL),
(10, 28, 6.00, 'done', '2025-12-12 15:02:46', 'Card', NULL, NULL, NULL),
(11, 28, 6.00, 'pending', '2025-12-12 15:03:50', 'Wish Money', NULL, NULL, NULL),
(12, 28, 6.00, 'pending', '2025-12-12 15:31:12', 'Wish Money', NULL, NULL, NULL),
(13, 28, 20.00, 'done', '2025-12-12 15:32:08', 'Wish Money', NULL, NULL, NULL),
(14, 28, 62.00, 'done', '2025-12-12 15:50:05', 'Wish Money', '1765547405_Screenshot 2025-12-09 221425.jpg', NULL, NULL),
(15, 28, 6.00, 'done', '2025-12-12 16:15:00', 'Wish Money', '1765548900_index.png', NULL, NULL),
(16, 28, 19.00, 'done', '2025-12-13 16:15:14', 'Cash on Delivery', NULL, NULL, NULL),
(17, 28, 12.00, 'done', '2025-12-13 16:15:41', 'Cash on Delivery', NULL, NULL, NULL),
(18, 28, 53.00, 'done', '2025-12-13 20:36:02', 'Cash on Delivery', NULL, 17, NULL),
(19, 28, 55.00, 'done', '2025-12-14 15:48:53', 'Cash on Delivery', NULL, 16, NULL),
(20, 28, 44.00, 'done', '2025-12-15 13:50:07', 'Cash on Delivery', NULL, 19, NULL),
(21, 28, 10.00, 'pending', '2025-12-15 14:05:12', 'Card', NULL, 17, NULL),
(22, 30, 18.00, 'pending', '2025-12-15 18:15:41', 'Wish Money', NULL, 20, NULL),
(23, 28, 13.00, 'pending', '2025-12-16 16:00:44', 'Cash on Delivery', NULL, 19, NULL),
(24, 30, 153.00, 'done', '2025-12-16 16:09:52', 'Cash on Delivery', NULL, 20, NULL),
(25, 30, 12.00, 'pending', '2025-12-16 16:25:13', 'Cash on Delivery', NULL, 20, NULL),
(26, 28, 9.00, 'pending', '2025-12-17 09:56:17', 'Cash on Delivery', NULL, 19, NULL),
(27, 28, 57.00, 'pending', '2025-12-17 10:17:30', 'Cash on Delivery', NULL, 19, NULL),
(28, 28, 12.00, 'pending', '2025-12-17 17:48:10', 'Cash on Delivery', NULL, 18, NULL),
(29, 28, 58.00, 'pending', '2025-12-19 16:57:40', 'Cash on Delivery', NULL, 19, NULL),
(30, 28, 72.00, 'pending', '2025-12-19 23:09:21', 'Cash on Delivery', NULL, 19, NULL),
(31, 28, 1.80, 'pending', '2025-12-21 22:09:17', 'Cash on Delivery', NULL, 17, NULL),
(32, 30, 143.00, 'pending', '2026-01-04 00:50:49', 'Cash on Delivery', NULL, 20, NULL),
(33, 30, 108.60, 'pending', '2026-01-04 19:54:33', 'Cash on Delivery', NULL, 20, NULL),
(34, 28, 24.00, 'pending', '2026-01-05 22:36:20', 'Cash on Delivery', NULL, 19, NULL),
(35, 28, 12.60, 'pending', '2026-01-06 12:29:49', 'Cash on Delivery', NULL, 19, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `food_item` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id`, `user_id`, `booking_id`, `food_item`) VALUES
(4, 13, 21, 'Shrimpy pizza'),
(5, 13, 22, 'Shrimpy pizza'),
(6, 13, 22, 'Vegi pizza'),
(7, 13, 22, 'Multi pizza'),
(8, 13, 23, 'Mushroom pizza'),
(11, 18, 26, 'Classic Burger'),
(12, 20, 27, 'Vegi pizza'),
(13, 20, 27, 'Multi pizza'),
(14, 20, 27, 'Mushroom pizza'),
(15, 20, 28, 'Mushroom pizza'),
(16, 20, 28, 'Classic Burger'),
(17, 20, 28, 'Grilled Chicken Burger'),
(18, 23, 29, 'Grilled Chicken Burger');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_name`, `quantity`, `price`, `created_at`) VALUES
(1, 2, 'Sushi', 1, 12.00, '2025-12-12 14:02:37'),
(2, 2, 'Dragon Roll', 1, 15.00, '2025-12-12 14:02:37'),
(3, 2, 'Coca Cola', 2, 6.00, '2025-12-12 14:02:37'),
(4, 2, 'Pepsi', 1, 3.00, '2025-12-12 14:02:37'),
(5, 2, 'Grilled Steak', 2, 36.00, '2025-12-12 14:02:37'),
(6, 2, 'BBQ Ribs', 1, 20.00, '2025-12-12 14:02:37'),
(7, 2, 'Burger Combo', 1, 12.50, '2025-12-12 14:02:37'),
(8, 2, 'Sushi', 34, 408.00, '2025-12-12 14:02:37'),
(9, 2, 'Coca Cola', 2, 6.00, '2025-12-12 14:02:37'),
(10, 2, 'Iced Tea', 2, 8.00, '2025-12-12 14:02:37'),
(11, 3, 'Cheese Burger', 2, 22.00, '2025-12-12 14:03:25'),
(12, 3, 'BBQ Ribs', 2, 40.00, '2025-12-12 14:03:25'),
(13, 4, 'Coca Cola', 2, 6.00, '2025-12-12 14:20:32'),
(14, 5, 'Mixed Grill', 2, 44.00, '2025-12-12 14:24:14'),
(15, 6, 'Grilled Steak', 2, 36.00, '2025-12-12 14:37:08'),
(16, 7, 'Pepsi', 2, 6.00, '2025-12-12 14:45:42'),
(17, 8, 'Mineral water', 2, 6.00, '2025-12-12 14:50:05'),
(18, 9, 'pink pasta', 2, 20.00, '2025-12-12 14:56:33'),
(19, 10, 'Coca Cola', 2, 6.00, '2025-12-12 15:02:46'),
(20, 11, 'Pepsi', 2, 6.00, '2025-12-12 15:03:50'),
(21, 12, 'Pepsi', 2, 6.00, '2025-12-12 15:31:12'),
(22, 13, 'BBQ Ribs', 1, 20.00, '2025-12-12 15:32:08'),
(23, 14, 'Cheese Burger', 2, 22.00, '2025-12-12 15:50:05'),
(24, 14, 'Grilled Steak', 1, 18.00, '2025-12-12 15:50:05'),
(25, 14, 'Mixed Grill', 1, 22.00, '2025-12-12 15:50:05'),
(26, 15, 'Mineral water', 2, 6.00, '2025-12-12 16:15:00'),
(27, 16, 'Classic Burger', 1, 9.00, '2025-12-13 16:15:14'),
(28, 16, 'Chicken Burger', 1, 10.00, '2025-12-13 16:15:14'),
(29, 17, 'Pepsi', 2, 6.00, '2025-12-13 16:15:41'),
(30, 17, 'Mineral water', 1, 3.00, '2025-12-13 16:15:41'),
(31, 17, 'Coca Cola', 1, 3.00, '2025-12-13 16:15:41'),
(32, 18, 'BBQ Ribs', 1, 20.00, '2025-12-13 20:36:02'),
(33, 18, 'Mixed Grill', 1, 22.00, '2025-12-13 20:36:02'),
(34, 18, 'Cheese Burger', 1, 11.00, '2025-12-13 20:36:02'),
(35, 19, 'Pepsi', 2, 6.00, '2025-12-14 15:48:53'),
(36, 19, 'Mineral water', 1, 3.00, '2025-12-14 15:48:53'),
(37, 19, 'Iced Tea', 1, 4.00, '2025-12-14 15:48:53'),
(38, 19, 'BBQ Ribs', 1, 20.00, '2025-12-14 15:48:53'),
(39, 19, 'Mixed Grill', 1, 22.00, '2025-12-14 15:48:53'),
(40, 20, 'Mixed Grill', 2, 44.00, '2025-12-15 13:50:07'),
(41, 21, 'pink pasta', 1, 10.00, '2025-12-15 14:05:12'),
(42, 22, 'Classic Burger', 1, 9.00, '2025-12-15 18:15:41'),
(43, 22, 'Classic Burger', 1, 9.00, '2025-12-15 18:15:41'),
(44, 23, 'Chicken Alfredo', 1, 13.00, '2025-12-16 16:00:44'),
(45, 24, 'Classic Burger', 2, 18.00, '2025-12-16 16:09:52'),
(46, 24, 'Pepperoni Pizza', 2, 24.00, '2025-12-16 16:09:52'),
(47, 24, 'Pepsi', 1, 3.00, '2025-12-16 16:09:52'),
(48, 24, 'Lasagna', 1, 14.00, '2025-12-16 16:09:52'),
(49, 24, 'Classic Burger', 3, 27.00, '2025-12-16 16:09:52'),
(50, 24, 'Pepsi', 3, 9.00, '2025-12-16 16:09:52'),
(51, 24, 'Pepsi', 1, 3.00, '2025-12-16 16:09:52'),
(52, 24, 'Lasagna', 1, 14.00, '2025-12-16 16:09:52'),
(53, 24, 'Classic Burger', 1, 9.00, '2025-12-16 16:09:52'),
(54, 24, 'Chicken Burger', 2, 20.00, '2025-12-16 16:09:52'),
(55, 24, 'Classic Burger', 1, 9.00, '2025-12-16 16:09:52'),
(56, 24, 'Pepsi', 1, 3.00, '2025-12-16 16:09:52'),
(57, 25, 'Spaghetti Bolognese', 1, 12.00, '2025-12-16 16:25:13'),
(58, 26, 'Classic Burger', 1, 9.00, '2025-12-17 09:56:17'),
(59, 27, 'Classic Burger', 1, 9.00, '2025-12-17 10:17:30'),
(60, 27, 'Double Beef Burger', 2, 30.00, '2025-12-17 10:17:30'),
(61, 27, 'Classic Burger', 2, 18.00, '2025-12-17 10:17:30'),
(62, 28, 'Classic Burger', 1, 9.00, '2025-12-17 17:48:10'),
(63, 28, 'Pepsi', 1, 3.00, '2025-12-17 17:48:10'),
(64, 29, 'Spaghetti Bolognese', 2, 24.00, '2025-12-19 16:57:40'),
(65, 29, 'Cheese Burger', 2, 22.00, '2025-12-19 16:57:40'),
(66, 29, 'Spaghetti Bolognese', 1, 12.00, '2025-12-19 16:57:40'),
(67, 30, 'Classic Burger', 1, 9.00, '2025-12-19 23:09:21'),
(68, 30, 'Pepsi', 1, 3.00, '2025-12-19 23:09:21'),
(69, 30, 'Mixed Grill', 1, 22.00, '2025-12-19 23:09:21'),
(70, 30, 'Chicken Alfredo', 1, 13.00, '2025-12-19 23:09:21'),
(71, 30, 'BBQ Chicken Pizza', 1, 14.00, '2025-12-19 23:09:21'),
(72, 30, 'Salmon Sushi', 1, 11.00, '2025-12-19 23:09:21'),
(73, 31, 'Mineral water', 1, 1.80, '2025-12-21 22:09:17'),
(74, 32, 'Classic Burger', 1, 9.00, '2026-01-04 00:50:49'),
(75, 32, 'Classic Burger', 1, 9.00, '2026-01-04 00:50:49'),
(76, 32, 'Pepsi', 1, 3.00, '2026-01-04 00:50:49'),
(77, 32, 'Classic Burger', 1, 9.00, '2026-01-04 00:50:49'),
(78, 32, 'Cheese Burger', 1, 6.60, '2026-01-04 00:50:49'),
(79, 32, 'Spaghetti Bolognese', 3, 75.60, '2026-01-04 00:50:49'),
(80, 32, 'Cheese Burger', 2, 30.80, '2026-01-04 00:50:49'),
(81, 33, 'Pepsi', 1, 3.00, '2026-01-04 19:54:33'),
(82, 33, 'Pepsi', 2, 6.00, '2026-01-04 19:54:33'),
(83, 33, 'Classic Burger', 2, 18.00, '2026-01-04 19:54:33'),
(84, 33, 'Pepsi', 2, 6.00, '2026-01-04 19:54:33'),
(85, 33, 'Spaghetti Bolognese', 3, 75.60, '2026-01-04 19:54:33'),
(86, 34, 'Classic Burger', 1, 9.00, '2026-01-05 22:36:20'),
(87, 34, 'Pepsi', 1, 3.00, '2026-01-05 22:36:20'),
(88, 34, 'Classic Burger', 1, 9.00, '2026-01-05 22:36:20'),
(89, 34, 'Pepsi', 1, 3.00, '2026-01-05 22:36:20'),
(90, 35, 'Classic Burger', 1, 6.30, '2026-01-06 12:29:49'),
(91, 35, 'Classic Burger', 1, 6.30, '2026-01-06 12:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `rating`, `comment`, `status`, `created_at`) VALUES
(1, 28, 5, 'very very good the items are very deliciouse ', 'approved', '2025-12-12 14:31:22'),
(2, 28, 4, 'i like the process', 'approved', '2025-12-12 14:40:07'),
(3, 28, 5, 'i love grill and fryyyy', 'approved', '2025-12-12 15:21:30'),
(4, 31, 5, 'Your restaurant is my favv', 'approved', '2025-12-15 16:53:59'),
(6, 31, 5, 'Your restaurant is my favv', '', '2025-12-15 16:55:19'),
(7, 30, 3, 'GOOOOOD', 'pending', '2026-01-03 22:52:22'),
(8, 30, 4, 'I LOVE YOUR RESTAURANT!!!', 'pending', '2026-01-03 22:53:08'),
(9, 30, 4, 'I LOVE YOUR RESTAURANT!!!', 'pending', '2026-01-03 23:13:30'),
(10, 30, 4, 'I LOVE YOUR RESTAURANT!!!', 'pending', '2026-01-03 23:14:21'),
(11, 30, 4, 'I LOVE YOUR RESTAURANT!!!', 'approved', '2026-01-03 23:15:22'),
(12, 30, 4, 'I LOVE YOUR RESTAURANT!!!', 'approved', '2026-01-03 23:15:31'),
(13, 30, 4, 'I LOVE YOUR RESTAURANT!!!', '', '2026-01-03 23:15:41'),
(14, 30, 4, 'I LOVE YOUR RESTAURANT!!!', 'pending', '2026-01-03 23:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `people` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `user_id`, `service_type`, `event_date`, `time_from`, `time_to`, `people`, `notes`, `status`, `created_at`, `is_deleted`) VALUES
(2, 28, 'Dine-In', '2026-01-06', '14:26:00', '15:30:00', 2, 'love theme', 'approved', '2026-01-06 10:53:32', 0),
(3, 28, 'Special Event', '2026-01-14', '16:33:00', '18:33:00', 20, 'theme for fam', 'approved', '2026-01-06 12:20:34', 0),
(4, 28, 'Special Event', '2026-01-29', '15:35:00', '16:35:00', 20, 'meeting for doctors', 'rejected', '2026-01-06 12:32:56', 0),
(5, 28, 'Dine-In', '2026-01-30', '16:45:00', '17:45:00', 2, 'theme work', 'pending', '2026-01-06 13:46:13', 0),
(6, 28, 'Special Event', '2026-01-31', '21:00:00', '22:00:00', 4, 'birthday theme', 'pending', '2026-01-06 17:59:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `type_name`, `image`, `is_active`) VALUES
(1, 'Sushi', 'image/sushi.jpg', 1),
(2, 'Burgers', 'image/burgerr.jpg', 1),
(3, 'Pizza', 'image/f6.png', 1),
(4, 'Meats', 'image/g2.jpg', 1),
(5, 'Drinks', 'image/colddronk.jpg', 1),
(7, 'pasta', 'uploads/types/1765802551_Skinny-Chicken-Broccoli-Alfredo-1.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chefs`
--
ALTER TABLE `chefs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `global_discounts`
--
ALTER TABLE `global_discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grill_fry_bookings`
--
ALTER TABLE `grill_fry_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_number` (`booking_number`),
  ADD KEY `fk_user_booking` (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `grill_fry_users`
--
ALTER TABLE `grill_fry_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_type` (`type_id`),
  ADD KEY `fk_discount` (`discount_id`);

--
-- Indexes for table `money`
--
ALTER TABLE `money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `chefs`
--
ALTER TABLE `chefs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `global_discounts`
--
ALTER TABLE `global_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `grill_fry_bookings`
--
ALTER TABLE `grill_fry_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `grill_fry_users`
--
ALTER TABLE `grill_fry_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `money`
--
ALTER TABLE `money`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grill_fry_bookings`
--
ALTER TABLE `grill_fry_bookings`
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`user_id`) REFERENCES `grill_fry_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_discount` FOREIGN KEY (`discount_id`) REFERENCES `global_discounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_item_type` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_order_booking` FOREIGN KEY (`booking_id`) REFERENCES `grill_fry_bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `grill_fry_bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
