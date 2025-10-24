-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 03:26 AM
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
-- Database: `employee_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_history`
--

CREATE TABLE `attendance_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `reset_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_history`
--

INSERT INTO `attendance_history` (`id`, `user_id`, `full_name`, `date`, `time_in`, `time_out`, `reset_at`) VALUES
(1, 0, 'Honey Jane Labiano', '2025-10-08', '11:03:51', '11:03:54', '2025-10-08 11:22:36'),
(2, 0, 'Honey Jane Labiano', '2025-10-08', '11:23:15', '11:23:39', '2025-10-08 11:27:21'),
(3, 5, 'Emanuel Borreros', '2025-10-08', '14:53:36', '22:55:20', '2025-10-09 12:46:03'),
(4, 4, 'Jasper Andam', '2025-10-08', '14:53:53', NULL, '2025-10-09 12:46:03'),
(5, 4, 'Jasper Andam', '2025-10-09', '07:44:57', NULL, '2025-10-09 12:46:03'),
(6, 2, 'Honey Jane Labiano', '2025-10-09', '12:59:44', '12:59:46', '2025-10-09 13:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_logs`
--

CREATE TABLE `attendance_logs` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `date` date DEFAULT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_logs`
--

INSERT INTO `attendance_logs` (`id`, `user_id`, `date`, `time_in`, `time_out`) VALUES
(1, '2', '2025-10-21', '2025-10-21 08:00:00', '2025-10-21 16:00:00'),
(2, '2', '2025-10-20', '2025-10-20 08:00:00', '2025-10-20 16:00:00'),
(3, '2', '2025-10-19', '2025-10-19 08:00:00', '2025-10-19 16:00:00'),
(4, '2', '2025-10-18', '2025-10-18 08:00:00', '2025-10-18 16:00:00'),
(5, '2', '2025-10-17', '2025-10-17 08:00:00', '2025-10-17 16:00:00'),
(6, '5', '2025-10-21', '2025-10-21 08:00:00', '2025-10-21 16:00:00'),
(7, '5', '2025-10-20', '2025-10-20 08:00:00', '2025-10-20 16:00:00'),
(8, '5', '2025-10-19', '2025-10-19 08:00:00', '2025-10-19 16:00:00'),
(9, '5', '2025-10-18', '2025-10-18 08:00:00', '2025-10-18 16:00:00'),
(10, '5', '2025-10-17', '2025-10-17 08:00:00', '2025-10-17 16:00:00'),
(11, '11', '2025-10-21', '2025-10-21 08:00:00', '2025-10-21 16:00:00'),
(12, '11', '2025-10-20', '2025-10-20 08:00:00', '2025-10-20 16:00:00'),
(13, '11', '2025-10-19', '2025-10-19 08:00:00', '2025-10-19 16:00:00'),
(14, '11', '2025-10-18', '2025-10-18 08:00:00', '2025-10-18 16:00:00'),
(15, '11', '2025-10-17', '2025-10-17 08:00:00', '2025-10-17 16:00:00'),
(16, '12', '2025-10-21', '2025-10-21 08:00:00', '2025-10-21 16:00:00'),
(17, '12', '2025-10-20', '2025-10-20 08:00:00', '2025-10-20 16:00:00'),
(18, '12', '2025-10-19', '2025-10-19 08:00:00', '2025-10-19 16:00:00'),
(19, '12', '2025-10-18', '2025-10-18 08:00:00', '2025-10-18 16:00:00'),
(20, '12', '2025-10-17', '2025-10-17 08:00:00', '2025-10-17 16:00:00'),
(21, '13', '2025-10-21', '2025-10-21 08:00:00', '2025-10-21 16:00:00'),
(22, '13', '2025-10-20', '2025-10-20 08:00:00', '2025-10-20 16:00:00'),
(23, '13', '2025-10-19', '2025-10-19 08:00:00', '2025-10-19 16:00:00'),
(24, '13', '2025-10-18', '2025-10-18 08:00:00', '2025-10-18 16:00:00'),
(25, '13', '2025-10-17', '2025-10-17 08:00:00', '2025-10-17 16:00:00'),
(26, '14', '2025-10-21', '2025-10-21 08:00:00', '2025-10-21 16:00:00'),
(27, '14', '2025-10-20', '2025-10-20 08:00:00', '2025-10-20 16:00:00'),
(28, '14', '2025-10-19', '2025-10-19 08:00:00', '2025-10-19 16:00:00'),
(29, '14', '2025-10-18', '2025-10-18 08:00:00', '2025-10-18 16:00:00'),
(30, '14', '2025-10-17', '2025-10-17 08:00:00', '2025-10-17 16:00:00'),
(31, '16', '2025-10-21', '2025-10-21 08:00:00', '2025-10-21 16:00:00'),
(32, '16', '2025-10-20', '2025-10-20 08:00:00', '2025-10-20 16:00:00'),
(33, '16', '2025-10-19', '2025-10-19 08:00:00', '2025-10-19 16:00:00'),
(34, '16', '2025-10-18', '2025-10-18 08:00:00', '2025-10-18 16:00:00'),
(35, '16', '2025-10-17', '2025-10-17 08:00:00', '2025-10-17 16:00:00'),
(36, '17', '2025-10-21', '2025-10-21 09:53:56', '2025-10-21 09:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `cash_advance`
--

CREATE TABLE `cash_advance` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `ca_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Applied') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cash_advance`
--

INSERT INTO `cash_advance` (`id`, `user_id`, `ca_date`, `amount`, `status`) VALUES
(1, '17', '2025-10-21', 500.00, 'Applied');

-- --------------------------------------------------------

--
-- Table structure for table `deductions`
--

CREATE TABLE `deductions` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(20) NOT NULL,
  `type` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Applied') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deductions`
--

INSERT INTO `deductions` (`id`, `employee_id`, `type`, `amount`, `date_created`, `created_at`, `status`) VALUES
(2, '11', 'PAGIBIG', 300.00, '2025-10-21 01:51:41', '2025-10-21 01:51:41', 'Pending'),
(3, '12', 'PAGIBIG', 300.00, '2025-10-21 01:51:41', '2025-10-21 01:51:41', 'Pending'),
(4, '13', 'PAGIBIG', 300.00, '2025-10-21 01:51:41', '2025-10-21 01:51:41', 'Pending'),
(5, '17', 'PAGIBIG', 300.00, '2025-10-21 01:51:41', '2025-10-21 01:51:41', 'Applied');

-- --------------------------------------------------------

--
-- Table structure for table `holiday_pay`
--

CREATE TABLE `holiday_pay` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `holiday_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Applied') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `holiday_pay`
--

INSERT INTO `holiday_pay` (`id`, `user_id`, `amount`, `holiday_date`, `created_at`, `status`) VALUES
(1, 11, 800.00, '2025-10-21', '2025-10-21 01:52:39', 'Pending'),
(2, 12, 800.00, '2025-10-21', '2025-10-21 01:52:39', 'Pending'),
(3, 13, 800.00, '2025-10-21', '2025-10-21 01:52:39', 'Pending'),
(4, 17, 800.00, '2025-10-21', '2025-10-21 01:52:39', 'Applied');

-- --------------------------------------------------------

--
-- Table structure for table `leave_pay`
--

CREATE TABLE `leave_pay` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `leave_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Applied') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE `overtime` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `ot_date` date NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(20) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `base_salary` decimal(10,2) NOT NULL,
  `overtime_pay` decimal(10,2) NOT NULL,
  `holiday_pay` decimal(10,2) DEFAULT 0.00,
  `special_pay` decimal(10,2) DEFAULT 0.00,
  `leave_pay` decimal(10,2) DEFAULT 0.00,
  `deductions` decimal(10,2) NOT NULL,
  `cash_advance` decimal(10,2) NOT NULL,
  `net_pay` decimal(10,2) NOT NULL,
  `pay_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `employee_id`, `employee_name`, `base_salary`, `overtime_pay`, `holiday_pay`, `special_pay`, `leave_pay`, `deductions`, `cash_advance`, `net_pay`, `pay_date`) VALUES
(266, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(267, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(268, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(283, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(284, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(285, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(286, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(287, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(288, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(289, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(290, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(291, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(292, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(293, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(294, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(295, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(296, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(297, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(298, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(299, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(300, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(301, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(302, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(303, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(304, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(305, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(306, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(307, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(308, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(309, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(310, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(311, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(312, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(313, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(314, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(315, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(316, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(317, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(318, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(319, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(320, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(321, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 0.00, 500.00, 2000.00, '2025-10-09'),
(322, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(323, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(324, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(325, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 500.00, 500.00, 1500.00, '2025-10-09'),
(326, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(327, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(328, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(329, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 820.00, 500.00, 1180.00, '2025-10-09'),
(330, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(331, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09'),
(332, '5', 'Emanuel Borreros', 0.00, 0.00, -953.00, 6040.00, 4769.00, 0.00, 0.00, 9856.00, '2025-10-09'),
(333, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 1400.00, 1100.00, 820.00, 500.00, 1180.00, '2025-10-09'),
(334, '4', 'Jasper Andam', 0.00, 0.00, -253.00, 0.00, 0.00, 0.00, 0.00, -253.00, '2025-10-09'),
(335, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_history`
--

CREATE TABLE `payroll_history` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(10) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `regular_pay` decimal(10,2) NOT NULL,
  `overtime_pay` decimal(10,2) NOT NULL,
  `holiday_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `special_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `leave_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_deductions` decimal(10,2) NOT NULL,
  `cash_advance` decimal(10,2) NOT NULL,
  `net_pay` decimal(10,2) NOT NULL,
  `date_archived` datetime DEFAULT NULL,
  `snapshot_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_history`
--

INSERT INTO `payroll_history` (`id`, `employee_id`, `full_name`, `regular_pay`, `overtime_pay`, `holiday_pay`, `special_pay`, `leave_pay`, `total_deductions`, `cash_advance`, `net_pay`, `date_archived`, `snapshot_date`) VALUES
(278, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 0.00, 0.00, 820.00, 0.00, -820.00, '2025-10-09 13:32:14', '2025-10-09 13:32:14'),
(279, '4', 'Jasper Andam', 0.00, 0.00, 0.00, 0.00, 0.00, 250.00, 0.00, -250.00, '2025-10-09 13:32:14', '2025-10-09 13:32:14'),
(280, '1', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 300.00, 0.00, -300.00, '2025-10-09 13:32:14', '2025-10-09 13:32:14'),
(281, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-10 19:44:29'),
(282, '5', 'Emanuel Borreros', 0.00, 0.00, 500.00, 600.00, 700.00, 133.00, 0.00, 1667.00, NULL, '2025-10-10 19:44:29'),
(283, '2', 'Honey Jane Labiano', 0.00, 0.00, 500.00, 0.00, 0.00, 3000.00, 0.00, -2500.00, NULL, '2025-10-11 09:01:50'),
(284, '5', 'Emanuel Borreros', 0.00, 0.00, 800.00, 600.00, 600.00, 500.00, 100.00, 1400.00, NULL, '2025-10-11 09:01:50'),
(285, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-11 11:47:19'),
(286, '5', 'Emanuel Borreros', 0.00, 0.00, 0.00, 500.00, 0.00, 0.00, 0.00, 500.00, NULL, '2025-10-11 11:47:19'),
(287, '2', 'Honey Jane Labiano', 3600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3600.00, NULL, '2025-10-12 13:06:13'),
(288, '5', 'Emanuel Borreros', 0.00, 0.00, 0.00, 0.00, 111.00, 0.00, 0.00, 111.00, NULL, '2025-10-12 13:06:13'),
(289, '10', 'test', 0.00, 0.00, 0.00, 0.00, 0.00, 500.00, 0.00, -500.00, NULL, '2025-10-12 13:06:13'),
(290, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-13 22:33:15'),
(291, '5', 'Emanuel Borreros', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-13 22:33:15'),
(292, '10', 'test', 0.00, 0.00, 0.00, 500.00, 500.00, 0.00, 0.00, 1000.00, NULL, '2025-10-13 22:33:15'),
(293, '11', 'Emanuel Borreros', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-13 22:33:15'),
(294, '12', 'cal', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-13 22:33:15'),
(295, '2', 'Honey Jane Labiano', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-14 09:48:49'),
(296, '5', 'Emanuel Borreros', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-14 09:48:49'),
(297, '10', 'test', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-14 09:48:49'),
(298, '11', 'Emanuel Borreros', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-14 09:48:49'),
(299, '12', 'cal', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-14 09:48:49'),
(300, '2', 'Honey Jane Labiano', 240.00, 0.00, 500.00, 600.00, 600.00, 2000.00, 0.00, -60.00, NULL, '2025-10-20 21:36:48'),
(301, '5', 'Emanuel Borreros', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-20 21:36:48'),
(302, '11', 'Emanuel Borreros', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-20 21:36:48'),
(303, '12', 'cal', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-20 21:36:48'),
(304, '13', 'Andrea Andres', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-20 21:36:48'),
(305, '14', 'Ryan Duclayan', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-20 21:36:48'),
(306, '16', 'Keshen', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-20 21:36:48'),
(307, '2', 'Honey Jane Labiano', 3600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3600.00, NULL, '2025-10-21 06:25:58'),
(308, '5', 'Emanuel Borreros', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2025-10-21 06:25:58'),
(309, '11', 'Emanuel Borreros', 2800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2800.00, NULL, '2025-10-21 06:25:58'),
(310, '12', 'cal', 2800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2800.00, NULL, '2025-10-21 06:25:58'),
(311, '13', 'Andrea Andres', 2800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2800.00, NULL, '2025-10-21 06:25:58'),
(312, '14', 'Ryan Duclayan', 3200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3200.00, NULL, '2025-10-21 06:25:58'),
(313, '16', 'Keshen', 3200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3200.00, NULL, '2025-10-21 06:25:58');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_master`
--

CREATE TABLE `payroll_master` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `regular_pay` decimal(10,2) DEFAULT 0.00,
  `overtime_pay` decimal(10,2) DEFAULT 0.00,
  `holiday_pay` decimal(10,2) DEFAULT 0.00,
  `special_pay` decimal(10,2) DEFAULT 0.00,
  `leave_pay` decimal(10,2) DEFAULT 0.00,
  `cash_advance` decimal(10,2) DEFAULT 0.00,
  `deductions` decimal(10,2) DEFAULT 0.00,
  `net_pay` decimal(10,2) DEFAULT 0.00,
  `status` enum('Pending','Applied') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_master`
--

INSERT INTO `payroll_master` (`id`, `user_id`, `period_start`, `period_end`, `regular_pay`, `overtime_pay`, `holiday_pay`, `special_pay`, `leave_pay`, `cash_advance`, `deductions`, `net_pay`, `status`, `created_at`) VALUES
(1, 5, '0000-00-00', '0000-00-00', 0.00, 0.00, 500.00, 0.00, 0.00, 0.00, 0.00, 500.00, 'Applied', '2025-10-11 15:59:50'),
(2, 5, '0000-00-00', '0000-00-00', 2800.00, 0.00, 0.00, 123.00, 0.00, 0.00, 0.00, 2923.00, 'Applied', '2025-10-11 16:01:34'),
(3, 5, '0000-00-00', '0000-00-00', 2800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 560.00, 2240.00, 'Applied', '2025-10-11 16:10:57'),
(4, 5, '0000-00-00', '0000-00-00', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 560.00, -560.00, 'Applied', '2025-10-11 16:12:18'),
(5, 5, '0000-00-00', '0000-00-00', 2800.00, 0.00, 0.00, 0.00, 300.00, 0.00, 0.00, 3100.00, 'Applied', '2025-10-11 16:31:08'),
(6, 5, '0000-00-00', '0000-00-00', 2800.00, 0.00, 0.00, 0.00, 111.00, 0.00, 0.00, 2911.00, 'Applied', '2025-10-11 16:38:30'),
(7, 2, '0000-00-00', '0000-00-00', 0.00, 0.00, 500.00, 600.00, 600.00, 0.00, 2000.00, -300.00, 'Applied', '2025-10-14 04:32:35'),
(8, 11, '0000-00-00', '0000-00-00', 2800.00, 0.00, 1500.00, 500.00, 600.00, 400.00, 2200.00, 2800.00, 'Applied', '2025-10-20 13:44:08'),
(9, 17, '0000-00-00', '0000-00-00', 0.00, 0.00, 800.00, 0.00, 0.00, 500.00, 300.00, 0.00, 'Applied', '2025-10-21 01:56:22');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `rate_per_hour` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `title`, `rate_per_hour`) VALUES
(1, 'Manager', 90.00),
(2, 'Assistant Manager', 80.00),
(4, 'CEO', 150.00),
(5, 'Employee', 70.00);

-- --------------------------------------------------------

--
-- Table structure for table `special_pay`
--

CREATE TABLE `special_pay` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `special_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Applied') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) DEFAULT NULL,
  `setting_value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'last_attendance_reset', '2025-10-09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(4) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','employee') DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `role`, `password`, `profile_pic`, `position_id`, `archived`) VALUES
(2, 'Honey Jane Labiano', 'labiano@gmail.com', 'employee', '$2y$10$b9oD33CHFlcGFU/T/pFuUue5bW50jg6xyYs7jahBFvPvI5RiFRzuO', 'default-user.png', 1, 0),
(5, 'Emanuel Borreros', 'borreros@gmail.com', 'employee', '$2y$10$o77mOJS3/sMETiuuyzBiDOtoMvcM//azzFojP2/YGONIBjOnmZAMu', 'default-user.png', 3, 1),
(9, 'admin1', 'admin1@gmail.com', 'admin', '$2y$10$zpyp3gnUpDDzJMDu0EM.BuuoLbldx2E0F1RexjR913XKgRW1Dg3/u', 'default-user.png', NULL, 1),
(11, 'Emanuel Borreros', 'eman@gmail.com', 'employee', '$2y$10$QWMx1uS0tFaZST2ZoldieORWZTWdJZkGIfxLjTqXAuTVhu0irU7UG', 'default-user.png', 5, 0),
(12, 'cal', 'cheese@gmail.com', 'employee', '$2y$10$rR0FCripFVRgG64tTQS91OlIuiGO8uAuvHOruC6A8.PY0HcDmUmEq', '68eb7b3374a0c_3645443.jpg', 5, 1),
(13, 'Andrea Andres', 'andres@gmail.com', 'employee', '$2y$10$aohRadCJUfpiKMgqdUr6nO.G24ryibI0mK9NJUn.FkVMtpqMTWzuu', 'default-user.png', 5, 0),
(14, 'Ryan Duclayan', 'duclayan@gmail.com', 'employee', '$2y$10$ssngfJk3O9KkyRIcGd7nD.bb5172UzsEyfyio8rAP7z9Cr3d9qf6i', 'default-user.png', 2, 0),
(16, 'Keshen', 'keshem@gmail.com', 'employee', '$2y$10$DUQxUjaUKGpRnFB0SG3D9Oe8PtJnYdRVo0xoejwt9RPlxV1SC6ivO', 'default-user.png', 2, 0),
(17, 'jarce', 'jarce@gmail.com', 'employee', '$2y$10$AwzTvBw.p51frg3Tak4hf.hAGaCPz2HnQ0ZrWcF5xlIamKF1T/iVu', '68f6e59a98ffd_3645443.jpg', 5, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_history`
--
ALTER TABLE `attendance_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_logs`
--
ALTER TABLE `attendance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cash_advance`
--
ALTER TABLE `cash_advance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `holiday_pay`
--
ALTER TABLE `holiday_pay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `leave_pay`
--
ALTER TABLE `leave_pay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `overtime`
--
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_history`
--
ALTER TABLE `payroll_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_master`
--
ALTER TABLE `payroll_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `special_pay`
--
ALTER TABLE `special_pay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `position_id` (`position_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_history`
--
ALTER TABLE `attendance_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `attendance_logs`
--
ALTER TABLE `attendance_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `cash_advance`
--
ALTER TABLE `cash_advance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deductions`
--
ALTER TABLE `deductions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `holiday_pay`
--
ALTER TABLE `holiday_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_pay`
--
ALTER TABLE `leave_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `overtime`
--
ALTER TABLE `overtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT for table `payroll_history`
--
ALTER TABLE `payroll_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=314;

--
-- AUTO_INCREMENT for table `payroll_master`
--
ALTER TABLE `payroll_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `special_pay`
--
ALTER TABLE `special_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `holiday_pay`
--
ALTER TABLE `holiday_pay`
  ADD CONSTRAINT `holiday_pay_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `leave_pay`
--
ALTER TABLE `leave_pay`
  ADD CONSTRAINT `leave_pay_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `special_pay`
--
ALTER TABLE `special_pay`
  ADD CONSTRAINT `special_pay_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
