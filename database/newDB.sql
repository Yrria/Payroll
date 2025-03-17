-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 06:33 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `admin_acc`
--

CREATE TABLE `admin_acc` (
  `id` int(10) NOT NULL,
  `admin_id` varchar(250) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `otp` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_acc`
--

INSERT INTO `admin_acc` (`id`, `admin_id`, `password`, `name`, `email`, `otp`) VALUES
(1, 'admin01', 'admin01', 'Admin01', 'sillywilly.cr.00@gmail.com', 2331);

-- --------------------------------------------------------

--
-- Table structure for table `emp_acc`
--

CREATE TABLE `emp_acc` (
  `id` int(100) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `otp` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_acc`
--

INSERT INTO `emp_acc` (`id`, `emp_id`, `password`, `email`, `fname`, `mname`, `lname`, `otp`) VALUES
(1, 'emp0001', 'emp0001', 'sillywilly.cr.00@gmail.com', 'Marc', 'A', 'Toledo', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report_leave`
--

CREATE TABLE `report_leave` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `no_of_leave` int(11) NOT NULL,
  `remaining_leave` int(11) NOT NULL,
  `total_leave` int(11) NOT NULL,
  `date_filed` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_leave`
--

INSERT INTO `report_leave` (`id`, `emp_id`, `subject`, `name`, `leave_type`, `no_of_leave`, `remaining_leave`, `total_leave`, `date_filed`) VALUES
(1, '001', 'Manager', 'Wonka', 'Emergency Leave', 8, 12, 12, '2025-03-18'),
(2, '002', 'Crew', 'Willy', 'Emergency', 20, 20, 20, '2025-03-16'),
(3, '003', 'Crew', 'rod', 'LBM', 10, 10, 10, '2025-03-18'),
(4, '004', 'Crew', 'Nix', 'LBM', 10, 10, 10, '2025-03-18'),
(5, '005', 'Crew', 'Jpits', 'LBM', 10, 10, 10, '2025-03-18'),
(6, '006', 'Manager', 'Jayjay', 'LBM', 10, 10, 10, '2025-03-17'),
(7, '007', 'Manager', 'Dhaniel', 'LBM', 10, 10, 10, '2025-03-17'),
(8, '008', 'Manager', 'Alen', 'LBM', 10, 10, 10, '2025-03-16'),
(9, '009', 'Crew', 'Rhanel', 'LBM', 10, 10, 10, '2025-03-14'),
(10, '010', 'Crew', 'Lance', 'LBM', 10, 10, 10, '2025-03-09'),
(11, '011', 'Crew', 'Marc', 'LBM', 10, 10, 10, '2025-03-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_acc`
--
ALTER TABLE `admin_acc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_acc`
--
ALTER TABLE `emp_acc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_leave`
--
ALTER TABLE `report_leave`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emp_acc`
--
ALTER TABLE `emp_acc`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `report_leave`
--
ALTER TABLE `report_leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
