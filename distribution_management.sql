-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 06:29 PM
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
-- Database: `distribution_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'create_user', 'branch_user', 2, '{\"username\":\"test12345\",\"branch_id\":4,\"role\":\"cashier\",\"created_by\":\"sgpriyom\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-12 07:40:02'),
(2, 3, 'login', 'branch_user', 3, '{\"branch_id\":4,\"branch_name\":\"South Branch\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-12 08:24:26'),
(3, 3, 'login', 'branch_user', 3, '{\"branch_id\":4,\"branch_name\":\"South Branch\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-12 08:24:26'),
(4, 3, 'login', 'branch_user', 3, '{\"branch_id\":4,\"branch_name\":\"South Branch\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-03-12 08:24:26'),
(5, 4, 'login', 'branch_user', 4, '{\"branch_id\":3,\"branch_name\":\"Barasat\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-03-12 08:24:26'),
(6, 4, 'login', 'branch_user', 4, '{\"branch_id\":3,\"branch_name\":\"Barasat\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-03-12 08:24:26'),
(7, 5, 'login', 'branch_user', 5, '{\"branch_id\":3,\"branch_name\":\"Barasat\",\"role\":\"manager\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-03-12 08:24:26'),
(8, 4, 'login', 'branch_user', 4, '{\"branch_id\":3,\"branch_name\":\"Barasat\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-03-12 08:24:26'),
(9, 4, 'login', 'branch_user', 4, '{\"branch_id\":3,\"branch_name\":\"Barasat\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-03-12 08:24:26');

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_attempts`
--

CREATE TABLE `admin_login_attempts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `attempt_time` datetime DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'failed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login_attempts`
--

INSERT INTO `admin_login_attempts` (`id`, `username`, `ip_address`, `attempt_time`, `status`, `created_at`) VALUES
(1, 'test', '::1', '2025-03-12 04:35:18', 'failed', '2025-03-11 23:05:18'),
(2, 'test', '::1', '2025-04-07 18:04:29', 'failed', '2025-04-07 12:34:29'),
(3, 'subho', '::1', '2025-04-22 10:45:38', 'failed', '2025-04-22 05:15:38'),
(4, 'admin', '::1', '2025-04-22 11:26:42', 'failed', '2025-04-22 05:56:42'),
(5, 'admin', '::1', '2025-06-07 15:24:46', 'failed', '2025-06-07 09:54:46'),
(6, 'admin', '::1', '2025-06-07 15:24:55', 'failed', '2025-06-07 09:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_logs`
--

CREATE TABLE `admin_login_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login_logs`
--

INSERT INTO `admin_login_logs` (`id`, `admin_id`, `login_time`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, '2025-03-12 02:43:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', '2025-03-11 21:13:46'),
(2, 1, '2025-03-12 04:35:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', '2025-03-11 23:05:43'),
(3, 1, '2025-03-12 04:43:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', '2025-03-11 23:13:52'),
(4, 1, '2025-03-12 05:02:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', '2025-03-11 23:32:54'),
(5, 1, '2025-03-13 00:41:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-12 19:11:28'),
(6, 1, '2025-04-07 18:27:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-07 12:57:23'),
(7, 1, '2025-04-09 14:29:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-09 08:59:21'),
(8, 1, '2025-04-09 15:24:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-09 09:54:01'),
(9, 1, '2025-04-09 16:39:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-09 11:09:37'),
(10, 1, '2025-04-09 16:49:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-09 11:19:02'),
(11, 1, '2025-04-09 18:20:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-09 12:50:25'),
(12, 1, '2025-04-17 01:36:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-16 20:06:02'),
(13, 1, '2025-04-22 10:45:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-22 05:15:50'),
(14, 1, '2025-04-22 11:26:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-22 05:56:50'),
(15, 1, '2025-04-22 12:32:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-22 07:02:32'),
(16, 1, '2025-06-07 15:25:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-07 09:55:04'),
(17, 1, '2025-06-07 15:27:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-07 09:57:24'),
(18, 1, '2025-06-07 16:32:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-07 11:02:45'),
(19, 1, '2025-06-13 21:58:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-13 16:28:02');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','manager','staff') DEFAULT 'staff',
  `status` enum('active','inactive') DEFAULT 'active',
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `login_count` int(11) DEFAULT 0,
  `password_changed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `role`, `status`, `email`, `full_name`, `mobile`, `last_login`, `login_count`, `password_changed_at`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$K5rPUJesJc37Ec9pBvwf/.bW9dO/C/Yu0RNq3a9NFjmYp45KYrv/K', 'super_admin', 'active', NULL, NULL, NULL, '2025-06-13 21:58:02', 19, NULL, '2025-03-11 21:13:41', '2025-06-13 16:28:02');

-- --------------------------------------------------------

--
-- Table structure for table `apb`
--

CREATE TABLE `apb` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `quantity_received` int(11) DEFAULT 0,
  `opening_stock` int(11) DEFAULT 0,
  `total_available` int(11) DEFAULT 0,
  `total_sold` int(11) DEFAULT 0,
  `closing_stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apb`
--

INSERT INTO `apb` (`id`, `branch_id`, `staff_id`, `transaction_date`, `quantity_received`, `opening_stock`, `total_available`, `total_sold`, `closing_stock`, `created_at`) VALUES
(1, 3, NULL, '2025-04-09', 3, 3, 6, 2, 4, '2025-04-09 10:35:38');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `opening_balance` decimal(10,2) DEFAULT 0.00,
  `current_balance` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `branch_id`, `bank_name`, `account_number`, `opening_balance`, `current_balance`, `created_at`) VALUES
(1, 3, 'bandhan', '868779834354544', 100.00, 100.00, '2025-04-09 11:18:03'),
(2, 1, 'HDFC', '8687798566', 1000.00, 1000.00, '2025-04-22 06:59:49');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_code` varchar(10) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `branch_type` enum('main','sub','satellite') DEFAULT 'sub',
  `opening_date` date NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `manager_name` varchar(100) DEFAULT NULL,
  `working_hours` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_code`, `branch_name`, `branch_type`, `opening_date`, `address1`, `address2`, `city`, `postal_code`, `contact_number`, `email`, `manager_name`, `working_hours`, `notes`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'BR001', 'Main Office', 'main', '2024-01-01', '123 Main Street', NULL, 'New York', '10001', '555-0123', 'main@example.com', 'John Doe', '9:00 AM - 5:00 PM', NULL, 'active', 1, '2025-03-11 21:37:44', NULL, '2025-03-11 21:37:44'),
(2, 'BR002', 'Downtown Branch', 'sub', '2024-01-15', '456 Park Avenue', NULL, 'New York', '10002', '555-0124', 'downtown@example.com', 'Jane Smith', '9:00 AM - 6:00 PM', NULL, 'active', 1, '2025-03-11 21:37:44', NULL, '2025-03-11 21:37:44'),
(3, '1', 'Barasat', 'main', '2025-03-12', 'Rabindra Road, Noapara, Barasat', 'Near Sabuj Sagha Club', 'Kolkata', '700125', '09007030532', 'hitnstudy@gmail.com', 'sourav', '10:00 AM-6:00 PM', 'dasd', 'active', 1, '2025-03-11 21:41:47', NULL, '2025-03-11 21:41:47'),
(4, 'BR003', 'South Branch', 'sub', '0000-00-00', '', NULL, '', '', '', '', NULL, NULL, NULL, 'active', NULL, '2025-03-11 23:11:41', NULL, '2025-03-11 23:11:41');

-- --------------------------------------------------------

--
-- Table structure for table `branch_activity_logs`
--

CREATE TABLE `branch_activity_logs` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_login_logs`
--

CREATE TABLE `branch_login_logs` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_users`
--

CREATE TABLE `branch_users` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `role` enum('manager','staff') DEFAULT 'staff',
  `permissions` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `address` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_users`
--

INSERT INTO `branch_users` (`id`, `branch_id`, `username`, `password`, `full_name`, `email`, `mobile`, `role`, `permissions`, `status`, `address`, `last_login`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 3, '1', '$2y$10$16GqRAcrpFFoenk/gLwmrOh0wV8FZSnWJoxOQv8z5Pj80Ss1JgluS', NULL, NULL, NULL, 'manager', NULL, 'active', NULL, NULL, 1, '2025-03-11 21:41:47', NULL, '2025-03-11 21:41:47'),
(2, 4, 'test12345', '$2y$10$VV6LQ.JICxtr46/0fD70peE7R/rk7Gjz1ly/ztyJF4XDlfz5C4Hw2', 'Subhajit Ghosh', 'hitnstudy@gmail.com', '9007030532', '', NULL, 'active', NULL, NULL, 1, '2025-03-12 02:10:02', NULL, '2025-03-12 07:41:50'),
(3, 4, 'santu123', '$2y$10$fC2.RNEEGlcdhqTE2iRcDevH5FFe0QihCpy45bM5fBgP1jYRuNhDG', 'santu dey', 'jahahsa@hh.com', '7987790902', 'staff', NULL, 'active', 'asaas', '2025-03-12 08:24:26', 1, '2025-03-12 02:37:31', NULL, '2025-03-12 08:26:11'),
(4, 3, 'saurav', '$2y$10$8tNDMyFp8CMxBKDhUdRcBewsBcRXP0Ij7g.vGver2eNjimTMcy.FW', 'saurav', 'ghj@gmail.com', '6989800077', 'staff', NULL, 'active', 'sxsxs', '2025-03-12 08:24:26', 1, '2025-03-12 02:37:31', NULL, '2025-04-09 09:55:32'),
(5, 3, 'test', '$2y$10$Eaoosn8iE3bg2eZNCP1owusaQs.jvBFfj.ZJ5Y.fasn8vNCnRqn4.', 'test test', 'hkhhdajd@gmail.com', '8988808090', 'manager', NULL, 'active', 'Rabindra Road, Noapara, Barasat\r\nNear Sabuj Sagha Club', '2025-03-12 08:24:26', 1, '2025-03-12 02:37:31', NULL, '2025-04-22 07:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `cash_deposits`
--

CREATE TABLE `cash_deposits` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `bank_account_id` int(11) DEFAULT NULL,
  `deposit_date` date DEFAULT NULL,
  `notes_2000` int(11) DEFAULT 0,
  `notes_500` int(11) DEFAULT 0,
  `notes_200` int(11) DEFAULT 0,
  `notes_100` int(11) DEFAULT 0,
  `notes_50` int(11) DEFAULT 0,
  `notes_20` int(11) DEFAULT 0,
  `notes_10` int(11) DEFAULT 0,
  `notes_5` int(11) DEFAULT 0,
  `notes_2` int(11) DEFAULT 0,
  `notes_1` int(11) DEFAULT 0,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `distributors`
--

CREATE TABLE `distributors` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dth`
--

CREATE TABLE `dth` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `amount_received` decimal(10,2) DEFAULT 0.00,
  `opening_balance` decimal(10,2) DEFAULT 0.00,
  `auto_amount` decimal(10,2) DEFAULT 0.00,
  `total_available_fund` decimal(10,2) DEFAULT 0.00,
  `total_spent` decimal(10,2) DEFAULT 0.00,
  `closing_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dth`
--

INSERT INTO `dth` (`id`, `branch_id`, `staff_id`, `transaction_date`, `amount_received`, `opening_balance`, `auto_amount`, `total_available_fund`, `total_spent`, `closing_amount`, `created_at`) VALUES
(1, 3, NULL, '2025-04-09', 100.00, 100.00, 100.00, 300.00, 100.00, 200.00, '2025-04-09 10:38:59');

-- --------------------------------------------------------

--
-- Table structure for table `lapu`
--

CREATE TABLE `lapu` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `cash_received` decimal(10,2) NOT NULL,
  `opening_balance` decimal(10,2) NOT NULL,
  `auto_amount` decimal(10,2) NOT NULL,
  `total_spent` decimal(10,2) NOT NULL,
  `total_available_fund` decimal(10,2) NOT NULL,
  `closing_amount` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lapu`
--

INSERT INTO `lapu` (`id`, `branch_id`, `transaction_date`, `cash_received`, `opening_balance`, `auto_amount`, `total_spent`, `total_available_fund`, `closing_amount`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, '2025-04-09 15:43:03', 33.00, 33.00, 33.00, 3.00, 99.00, 96.00, '', '2025-04-09 15:43:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sim_cards`
--

CREATE TABLE `sim_cards` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `quantity_received` int(11) DEFAULT 0,
  `opening_stock` int(11) DEFAULT 0,
  `auto_quantity` int(11) DEFAULT 0,
  `total_available` int(11) DEFAULT 0,
  `total_sold` int(11) DEFAULT 0,
  `closing_stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sim_cards`
--

INSERT INTO `sim_cards` (`id`, `branch_id`, `staff_id`, `transaction_date`, `quantity_received`, `opening_stock`, `auto_quantity`, `total_available`, `total_sold`, `closing_stock`, `created_at`) VALUES
(1, 3, NULL, '2025-04-09', 22, 2, 2, 26, 4, 22, '2025-04-09 10:31:36');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('manager','supervisor','staff') NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `dob` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `joining_date` date NOT NULL,
  `address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_id`, `branch_id`, `full_name`, `email`, `mobile`, `username`, `password`, `role`, `department`, `dob`, `gender`, `joining_date`, `address`, `profile_photo`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, '12345', 3, 'Subhajit Ghosh', 'hitnstudy@gmail.com', '09007030532', 'test', '$2y$10$OpTUGJxb7n5NR.BvAJ0qZu8NZvPlNrmJdy0Kt/w2MTjB8X0z3X7Hu', 'supervisor', 'sales', '2025-03-06', 'male', '2025-03-28', 'Rabindra Road, Noapara, Barasat\r\nNear Sabuj Sagha Club', 'STAFF_1_1741733701.jpg', 'active', 1, '2025-03-11 22:55:01', NULL, '2025-03-11 22:55:01'),
(3, '3232', 3, 'mithun', 'jjjjaj@gmail.com', '09007030532', 'test11', '$2y$10$RZBwJFxDIsU2t4zZAXIjIOcwK4IDcOIeJrtdz7FonFr0IoQFWwXAe', 'staff', 'sales', '2017-03-02', 'male', '2025-03-10', 'Rabindra Road, Noapara, Barasat\r\nNear Sabuj Sagha Club', 'STAFF_3_1741762402.jpg', 'active', 1, '2025-03-12 06:53:22', NULL, '2025-03-12 06:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `staff_activity_logs`
--

CREATE TABLE `staff_activity_logs` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_login_logs`
--

CREATE TABLE `staff_login_logs` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `bank_account_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `credit` decimal(10,2) DEFAULT 0.00,
  `debit` decimal(10,2) DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logout_logs`
--

CREATE TABLE `user_logout_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `logout_time` datetime NOT NULL,
  `logout_type` enum('manual','auto','inactivity') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_logout_logs`
--

INSERT INTO `user_logout_logs` (`id`, `user_id`, `logout_time`, `logout_type`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, '2025-03-12 04:37:05', 'manual', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', '2025-03-11 23:07:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin_login_attempts`
--
ALTER TABLE `admin_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_login_logs`
--
ALTER TABLE `admin_login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `apb`
--
ALTER TABLE `apb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch_code` (`branch_code`),
  ADD KEY `idx_branch_code` (`branch_code`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_city` (`city`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `branch_activity_logs`
--
ALTER TABLE `branch_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `branch_login_logs`
--
ALTER TABLE `branch_login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `branch_users`
--
ALTER TABLE `branch_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `cash_deposits`
--
ALTER TABLE `cash_deposits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `bank_account_id` (`bank_account_id`);

--
-- Indexes for table `distributors`
--
ALTER TABLE `distributors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `dth`
--
ALTER TABLE `dth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `lapu`
--
ALTER TABLE `lapu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `sim_cards`
--
ALTER TABLE `sim_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_staff_id` (`staff_id`),
  ADD KEY `idx_branch` (`branch_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `staff_activity_logs`
--
ALTER TABLE `staff_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_staff_activity` (`staff_id`,`activity_type`);

--
-- Indexes for table `staff_login_logs`
--
ALTER TABLE `staff_login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_staff_login` (`staff_id`,`login_time`),
  ADD KEY `idx_login_status` (`status`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `bank_account_id` (`bank_account_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `user_logout_logs`
--
ALTER TABLE `user_logout_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_logout` (`user_id`,`logout_time`),
  ADD KEY `idx_logout_type` (`logout_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_login_attempts`
--
ALTER TABLE `admin_login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admin_login_logs`
--
ALTER TABLE `admin_login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `apb`
--
ALTER TABLE `apb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branch_activity_logs`
--
ALTER TABLE `branch_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch_login_logs`
--
ALTER TABLE `branch_login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch_users`
--
ALTER TABLE `branch_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cash_deposits`
--
ALTER TABLE `cash_deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `distributors`
--
ALTER TABLE `distributors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dth`
--
ALTER TABLE `dth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lapu`
--
ALTER TABLE `lapu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sim_cards`
--
ALTER TABLE `sim_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff_activity_logs`
--
ALTER TABLE `staff_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_login_logs`
--
ALTER TABLE `staff_login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logout_logs`
--
ALTER TABLE `user_logout_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `branch_users` (`id`);

--
-- Constraints for table `admin_login_logs`
--
ALTER TABLE `admin_login_logs`
  ADD CONSTRAINT `admin_login_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `apb`
--
ALTER TABLE `apb`
  ADD CONSTRAINT `apb_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `apb_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);

--
-- Constraints for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD CONSTRAINT `bank_accounts_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admin_users` (`id`),
  ADD CONSTRAINT `branches_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `branch_activity_logs`
--
ALTER TABLE `branch_activity_logs`
  ADD CONSTRAINT `branch_activity_logs_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `branch_activity_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `branch_users` (`id`);

--
-- Constraints for table `branch_login_logs`
--
ALTER TABLE `branch_login_logs`
  ADD CONSTRAINT `branch_login_logs_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `branch_login_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `branch_users` (`id`);

--
-- Constraints for table `branch_users`
--
ALTER TABLE `branch_users`
  ADD CONSTRAINT `branch_users_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `branch_users_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `admin_users` (`id`),
  ADD CONSTRAINT `branch_users_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `cash_deposits`
--
ALTER TABLE `cash_deposits`
  ADD CONSTRAINT `cash_deposits_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `cash_deposits_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`),
  ADD CONSTRAINT `cash_deposits_ibfk_3` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`);

--
-- Constraints for table `dth`
--
ALTER TABLE `dth`
  ADD CONSTRAINT `dth_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `dth_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);

--
-- Constraints for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `branch_users` (`id`),
  ADD CONSTRAINT `login_logs_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `sim_cards`
--
ALTER TABLE `sim_cards`
  ADD CONSTRAINT `sim_cards_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `sim_cards_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);

--
-- Constraints for table `staff_activity_logs`
--
ALTER TABLE `staff_activity_logs`
  ADD CONSTRAINT `staff_activity_logs_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_login_logs`
--
ALTER TABLE `staff_login_logs`
  ADD CONSTRAINT `staff_login_logs_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
