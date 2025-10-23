-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2025 at 09:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `primary_oaks`
--

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `code` varchar(50) NOT NULL,
  `long_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audits`
--

INSERT INTO `audits` (`audit_id`, `user_id`, `date`, `code`, `long_desc`) VALUES
(23, 0, '2025-10-21 11:06:33', 'REG_ATTEMPT', 'Registration attempt initiated from IP: 127.0.0.1'),
(24, 0, '2025-10-21 11:06:33', 'REG_FAILED', 'Registration failed for email aaa@k.com - Validation errors: Password must be greater than 8 characters.<br>Password must contain at least one uppercase letter.<br>Password must contain at least one number.<br>Password must contain at least one special character.<br>. IP: 127.0.0.1'),
(25, 0, '2025-10-21 11:07:37', 'REG_ATTEMPT', 'Registration attempt initiated from IP: 127.0.0.1'),
(26, 0, '2025-10-21 11:07:37', 'REG_FAILED', 'Registration failed due to a database error for email aaa@k.com. Error: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'full_name\' in \'field list\'. IP: 127.0.0.1'),
(27, 0, '2025-10-21 11:07:59', 'REG_ATTEMPT', 'Registration attempt initiated from IP: 127.0.0.1'),
(28, 0, '2025-10-21 11:07:59', 'REG_FAILED', 'Registration failed due to a database error for email abdallah92713@gmail.com. Error: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'full_name\' in \'field list\'. IP: 127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `role` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `room` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_audits`
--

CREATE TABLE `staff_audits` (
  `audit_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `code` varchar(50) NOT NULL,
  `long_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `address_line_1` varchar(255) NOT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `postcode` varchar(10) NOT NULL,
  `county` varchar(100) NOT NULL,
  `registration_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `staff_audits`
--
ALTER TABLE `staff_audits`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_audits`
--
ALTER TABLE `staff_audits`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
