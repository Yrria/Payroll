-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 09:16 AM
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
-- Database: `payroll`
--
DROP DATABASE IF EXISTS `payroll`;
CREATE DATABASE IF NOT EXISTS `payroll` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `payroll`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_acc`
--

DROP TABLE IF EXISTS `tbl_admin_acc`;
CREATE TABLE `tbl_admin_acc` (
  `admin_id` int(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `otp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin_acc`
--

INSERT INTO `tbl_admin_acc` (`admin_id`, `lastname`, `firstname`, `middlename`, `email`, `password`, `otp`) VALUES
(3, 'santos', 'rodney', 'galario', 'ic.rodney.santos@cvsu.edu.ph', 'rabbit', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance`
--

DROP TABLE IF EXISTS `tbl_attendance`;
CREATE TABLE `tbl_attendance` (
  `emp_id` int(100) NOT NULL,
  `attendance_id` int(100) NOT NULL,
  `present_days` int(100) NOT NULL,
  `absent_days` int(100) DEFAULT NULL,
  `hours_present` double NOT NULL,
  `hours_late` double DEFAULT NULL,
  `hours_overtime` double DEFAULT NULL,
  `holiday` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_deduction`
--

DROP TABLE IF EXISTS `tbl_deduction`;
CREATE TABLE `tbl_deduction` (
  `emp_id` int(100) NOT NULL,
  `deduction_id` int(100) NOT NULL,
  `year` int(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `pagibig_deduction` double DEFAULT NULL,
  `philhealth_deduction` double DEFAULT NULL,
  `sss_deduction` double DEFAULT NULL,
  `other_deduction` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_acc`
--

DROP TABLE IF EXISTS `tbl_emp_acc`;
CREATE TABLE `tbl_emp_acc` (
  `emp_id` int(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone_no` bigint(100) DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `otp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_acc`
--

INSERT INTO `tbl_emp_acc` (`emp_id`, `lastname`, `firstname`, `middlename`, `email`, `password`, `address`, `phone_no`, `gender`, `status`, `otp`) VALUES
(1, 'toledo', 'marc andrei', 'kalapati', 'ic.marcandrei.toledo@cvsu.edu.ph', 'kalapati', 'habay, bacoor', 99123456789, 'male', 'inactive', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_info`
--

DROP TABLE IF EXISTS `tbl_emp_info`;
CREATE TABLE `tbl_emp_info` (
  `emp_id` int(100) NOT NULL,
  `shift` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `bank_acc` bigint(100) NOT NULL,
  `pay_type` varchar(100) NOT NULL,
  `rate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_info`
--

INSERT INTO `tbl_emp_info` (`emp_id`, `shift`, `position`, `bank_acc`, `pay_type`, `rate`) VALUES
(1, 'night', 'manager', 123456789, '?', 500);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leave`
--

DROP TABLE IF EXISTS `tbl_leave`;
CREATE TABLE `tbl_leave` (
  `emp_id` int(100) NOT NULL,
  `leave_id` int(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `date_applied` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `leave_type` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `rejection_reason` text NOT NULL,
  `remaining_leave` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

DROP TABLE IF EXISTS `tbl_payment`;
CREATE TABLE `tbl_payment` (
  `emp_id` int(100) NOT NULL,
  `payment_id` int(100) NOT NULL,
  `year` int(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `cutoff` varchar(1) NOT NULL COMMENT '1 or 2',
  `status` varchar(100) NOT NULL COMMENT 'paid or unpaid',
  `basic_pay` double NOT NULL,
  `holiday_pay` double DEFAULT NULL,
  `ot_pay` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_salary`
--

DROP TABLE IF EXISTS `tbl_salary`;
CREATE TABLE `tbl_salary` (
  `emp_id` int(100) NOT NULL,
  `salary_id` int(100) NOT NULL,
  `year` int(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `cutoff` varchar(1) NOT NULL COMMENT 'either 1 or 2',
  `status` varchar(100) NOT NULL COMMENT 'paid or unpaid',
  `basic_pay` double NOT NULL,
  `holiday_pay` double DEFAULT NULL,
  `ot_pay` double DEFAULT NULL,
  `pagibig_deduction` double DEFAULT NULL,
  `philhealth_deduction` double DEFAULT NULL,
  `sss_deduction` double DEFAULT NULL,
  `other_dedution` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin_acc`
--
ALTER TABLE `tbl_admin_acc`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_emp_acc`
--
ALTER TABLE `tbl_emp_acc`
  ADD PRIMARY KEY (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin_acc`
--
ALTER TABLE `tbl_admin_acc`
  MODIFY `admin_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_emp_acc`
--
ALTER TABLE `tbl_emp_acc`
  MODIFY `emp_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
