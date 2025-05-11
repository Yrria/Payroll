-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 08:22 AM
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
CREATE DATABASE IF NOT EXISTS `payroll` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `payroll`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_acc`
--

CREATE TABLE `tbl_admin_acc` (
  `admin_id` int(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `otp` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin_acc`
--

INSERT INTO `tbl_admin_acc` (`admin_id`, `lastname`, `firstname`, `middlename`, `email`, `password`, `otp`) VALUES
(1, 'santos', 'rodney', 'galario', 'ic.rodney.santos@cvsu.edu.ph', 'pass', 47033);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance`
--

CREATE TABLE `tbl_attendance` (
  `emp_id` int(100) NOT NULL,
  `attendance_id` int(100) NOT NULL,
  `present_days` int(100) NOT NULL,
  `absent_days` int(100) DEFAULT NULL,
  `hours_present` double NOT NULL,
  `hours_late` double DEFAULT NULL,
  `hours_overtime` double DEFAULT NULL,
  `holiday` int(100) DEFAULT NULL,
  `attendance_today` varchar(55) NOT NULL,
  `attendance_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_attendance`
--

INSERT INTO `tbl_attendance` (`emp_id`, `attendance_id`, `present_days`, `absent_days`, `hours_present`, `hours_late`, `hours_overtime`, `holiday`, `attendance_today`, `attendance_date`) VALUES
(1, 1, 11, 0, 120, 0, 0, 2, '', NULL),
(2, 2, 12, 0, 120, 0, 0, 2, 'Late', '2025-05-11'),
(3, 3, 11, 5, 80, 0, 0, 1, '', NULL),
(4, 4, 11, 4, 88, 0, 0, 1, '', NULL),
(5, 5, 12, 5, 80, 0, 0, 1, 'Late', '2025-05-11'),
(6, 6, 12, 4, 88, 0, 0, 1, 'Late', '2025-05-11'),
(7, 7, 11, 3, 96, 0, 0, 2, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_deduction`
--

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

--
-- Dumping data for table `tbl_deduction`
--

INSERT INTO `tbl_deduction` (`emp_id`, `deduction_id`, `year`, `month`, `pagibig_deduction`, `philhealth_deduction`, `sss_deduction`, `other_deduction`) VALUES
(1, 1, 2025, 'May', 500, 500, 500, 0),
(2, 2, 2025, 'May', 500, 500, 500, 0),
(3, 3, 2025, 'May', 500, 500, 500, 0),
(4, 4, 2025, 'May', 500, 500, 500, 0),
(5, 5, 2025, 'May', 500, 500, 500, 0),
(6, 6, 2025, 'May', 500, 500, 500, 0),
(7, 7, 2025, 'May', 500, 500, 500, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_acc`
--

CREATE TABLE `tbl_emp_acc` (
  `emp_id` int(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(55) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone_no` bigint(100) DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `otp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_acc`
--

INSERT INTO `tbl_emp_acc` (`emp_id`, `lastname`, `firstname`, `middlename`, `email`, `password`, `address`, `phone_no`, `gender`, `status`, `otp`) VALUES
(1, 'Toledo', 'Marc Andrei', 'Andrei', 'ic.marcandrei.toledo@cvsu.edu.ph', 'pass', 'habay, bacoor', 99123456789, 'male', 'active', 0),
(2, 'Lofamia', 'Dhaniel', 'Dela Cruz', 'dhanieeel0907@gmail.com', 'pass', 'jan lang', 937383717, 'Male', 'active', 0),
(3, 'Valentino', 'Martin', 'Malapo', 'ic.martinlouis.valentino@cvsu.edu.ph', 'pass', 'malumot,panapaan bacolor city', 9924210601, 'Male', 'active', 0),
(4, 'Fidelis', 'Alen', 'Nicanor', 'ic.alen.fidelis@cvsu.edu.ph', 'pass', 'dexterville sabang', 9924210601, 'Male', 'active', 0),
(5, 'Carlos', 'Nicol', 'Obes', 'ic.carlosjr.nicol@cvsu.edu.ph', 'pass', 'panapaan', 9924210601, 'Male', 'active', 0),
(6, 'Macaspac', 'Patrick', 'Pitalco', 'ic.johnpatrick.macaspac@cvsu.edu.ph', 'pass', 'carbag', 9924210601, 'Male', 'active', 0),
(7, 'Javier', 'Harvey', 'Camacho', 'ic.harvey.havier@cvsu.edu.ph', 'pass', 'binakayan', 9924210601, 'Male', 'active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_info`
--

CREATE TABLE `tbl_emp_info` (
  `emp_info_id` int(11) NOT NULL,
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

INSERT INTO `tbl_emp_info` (`emp_info_id`, `emp_id`, `shift`, `position`, `bank_acc`, `pay_type`, `rate`) VALUES
(1, 1, 'Night', 'manager', 123456789, '?', 1000),
(2, 2, 'Morning', 'Crew', 98765432123, 'Cash', 520),
(3, 3, 'Night', 'Server Crew', 98765432123, 'Cash', 520),
(4, 4, 'Night', 'Manager', 98765432123, 'Cash', 720),
(5, 5, 'Morning', 'Crew', 98765432123, 'Cash', 520),
(6, 6, 'Morning', 'Crew', 98765432123, 'Cash', 520),
(7, 7, 'Morning', 'Server Crew', 98765432123, 'Cash', 520);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leave`
--

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
  `remaining_leave` int(100) NOT NULL,
  `no_of_leave` int(11) DEFAULT NULL,
  `total_leaves` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_leave`
--

INSERT INTO `tbl_leave` (`emp_id`, `leave_id`, `subject`, `date_applied`, `start_date`, `end_date`, `leave_type`, `message`, `status`, `rejection_reason`, `remaining_leave`, `no_of_leave`, `total_leaves`) VALUES
(1, 50, 'Sick Leave', '2025-05-01', '2025-05-02', '2025-05-03', 'Sick Leave', 'aasdasdsafhfdhdf34353', 'Approved', 'n/a', 2, 0, 5),
(2, 51, 'Maternity Leave', '2025-05-04', '2025-05-05', '2025-05-06', 'Sick Leave', 'adsdasdwad22', 'Pending', 'n/a', 0, 0, 6),
(3, 52, 'Sick Leave', '2025-05-07', '2025-05-08', '2025-05-09', 'Maternity', 'dkkdsmkdmfksmf', 'Approved', 'n/a', 3, 2, 5),
(4, 53, 'Maternity Leave', '2025-05-10', '2025-05-11', '2025-05-12', 'Sick Leave', 'mmkemtkrmtkrmtk', 'Pending', 'n/a', 2, 3, 5),
(5, 54, 'Casual Leave', '2025-05-14', '2025-05-15', '2025-05-16', 'Casual Leave', 'asdsadsadlkn2kenj3', 'Approved', 'n/a', 5, 6, 11),
(6, 55, 'Paternity Leave', '2025-05-19', '2025-05-20', '2025-05-21', 'Paternity Leave', 'fdfgrdgsrlewk', 'Pending', 'n/a', 7, 6, 15),
(7, 56, 'Parental Leave', '2025-05-22', '2025-05-23', '2025-05-24', 'Parental Leave', 'wrqwrqqweqw', 'Pending', 'n/a', 9, 5, 14);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `emp_id` int(100) NOT NULL,
  `payment_id` int(100) NOT NULL,
  `year` int(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `cutoff` varchar(1) NOT NULL COMMENT '1 or 2',
  `status` varchar(100) NOT NULL COMMENT 'paid or unpaid',
  `basic_pay` double NOT NULL,
  `holiday_pay` double DEFAULT NULL,
  `ot_pay` double DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_payment`
--

INSERT INTO `tbl_payment` (`emp_id`, `payment_id`, `year`, `month`, `cutoff`, `status`, `basic_pay`, `holiday_pay`, `ot_pay`, `total`) VALUES
(0, 0, 2022, 'january', '5', 'active', 20000, 20000, 20000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_salary`
--

CREATE TABLE `tbl_salary` (
  `salary_id` int(100) NOT NULL,
  `emp_id` int(100) NOT NULL,
  `l_name` varchar(55) NOT NULL,
  `f_name` varchar(55) NOT NULL,
  `m_name` varchar(55) NOT NULL,
  `position_name` varchar(55) NOT NULL,
  `emp_shift` varchar(55) NOT NULL,
  `year` int(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `cutoff` varchar(22) NOT NULL,
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
-- Dumping data for table `tbl_salary`
--

INSERT INTO `tbl_salary` (`salary_id`, `emp_id`, `l_name`, `f_name`, `m_name`, `position_name`, `emp_shift`, `year`, `month`, `cutoff`, `status`, `basic_pay`, `holiday_pay`, `ot_pay`, `pagibig_deduction`, `philhealth_deduction`, `sss_deduction`, `other_dedution`) VALUES
(1, 1, 'Toledo', 'Marc Andrei', 'Andrei', 'Service Crew', 'Night', 2024, 'January', 'First Cutoff', 'Unpaid', 30000, 500, 500, 500, 500, 500, 500),
(2, 2, 'Lofamia', 'Dhaniel', 'Dela Cruz', 'Service Crew', 'Day', 2024, 'January', 'Second Cutoff', 'Unpaid', 30000, 500, 500, 500, 500, 500, 500);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin_acc`
--
ALTER TABLE `tbl_admin_acc`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `fk_attendance_emp` (`emp_id`);

--
-- Indexes for table `tbl_deduction`
--
ALTER TABLE `tbl_deduction`
  ADD PRIMARY KEY (`deduction_id`);

--
-- Indexes for table `tbl_emp_acc`
--
ALTER TABLE `tbl_emp_acc`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `tbl_emp_info`
--
ALTER TABLE `tbl_emp_info`
  ADD PRIMARY KEY (`emp_info_id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `tbl_leave`
--
ALTER TABLE `tbl_leave`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `tbl_salary`
--
ALTER TABLE `tbl_salary`
  ADD PRIMARY KEY (`salary_id`),
  ADD KEY `fk_salary_emp` (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin_acc`
--
ALTER TABLE `tbl_admin_acc`
  MODIFY `admin_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  MODIFY `attendance_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_emp_acc`
--
ALTER TABLE `tbl_emp_acc`
  MODIFY `emp_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_salary`
--
ALTER TABLE `tbl_salary`
  MODIFY `salary_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD CONSTRAINT `fk_attendance_emp` FOREIGN KEY (`emp_id`) REFERENCES `tbl_emp_acc` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_info`
--
ALTER TABLE `tbl_emp_info`
  ADD CONSTRAINT `tbl_emp_info_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `tbl_emp_acc` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_salary`
--
ALTER TABLE `tbl_salary`
  ADD CONSTRAINT `fk_salary_emp` FOREIGN KEY (`emp_id`) REFERENCES `tbl_emp_acc` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
