-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2025 at 10:41 AM
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
-- Database: `gconsole`
--

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `code` text NOT NULL,
  `long_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consoles`
--

CREATE TABLE `consoles` (
  `console_id` int(11) NOT NULL,
  `manufacturer` text NOT NULL,
  `console_name` text NOT NULL,
  `release_date` text NOT NULL,
  `controller_number` int(11) NOT NULL,
  `bit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consoles`
--

INSERT INTO `consoles` (`console_id`, `manufacturer`, `console_name`, `release_date`, `controller_number`, `bit`) VALUES
(1, 'Nintendo', 'Nintendo Switch', '2017/03/03', 8, 64),
(2, 'Nintendo', 'Game Boy Advance', '2011/03/21', 0, 32),
(3, 'Nintendo', 'DS', '2004/11/21', 0, 32),
(4, 'Nintendo', '3DS', '2011/02/26', 0, 32),
(5, 'Sony', 'PS2', '2000/03/04', 2, 64),
(6, 'Sony', 'PS3', '2006/11/11', 7, 64);

-- --------------------------------------------------------

--
-- Table structure for table `owns`
--

CREATE TABLE `owns` (
  `owns_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `console_id` int(11) NOT NULL,
  `buy_date` text NOT NULL,
  `link_date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owns`
--

INSERT INTO `owns` (`owns_id`, `user_id`, `console_id`, `buy_date`, `link_date`) VALUES
(1, 2, 1, '2025/09/26', '2025/09/26'),
(2, 2, 2, '2025/09/26', '2025/09/26'),
(3, 2, 3, '2025/09/26', '2025/09/26'),
(4, 2, 4, '2025/09/26', '2025/09/26'),
(5, 2, 5, '2025/09/26', '2025/09/26'),
(6, 2, 6, '2025/09/26', '2025/09/26'),
(8, 1, 1, '2025/09/26', '2025/09/26'),
(9, 1, 2, '2025/09/26', '2025/09/26'),
(10, 3, 3, '2025/09/26', '2025/09/26'),
(11, 3, 4, '2025/09/26', '2025/09/26'),
(12, 4, 5, '2025/09/26', '2025/09/26'),
(13, 4, 6, '2025/09/26', '2025/09/26'),
(14, 5, 1, '2025/09/26', '2025/09/26'),
(15, 5, 3, '2025/09/26', '2025/09/26'),
(16, 6, 2, '2025/09/26', '2025/09/26'),
(17, 6, 6, '2025/09/26', '2025/09/26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` text NOT NULL,
  `password` text NOT NULL,
  `sign_up_date` text NOT NULL,
  `date_of_birth` text NOT NULL,
  `country` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `password`, `sign_up_date`, `date_of_birth`, `country`) VALUES
(1, 'abdallah', 'password1G', '2024/12/24', '2007/09/02', 'Ajman'),
(2, 'layla', 'password1G', '1729/02/09', '1279/12/24', 'Belize'),
(3, 'soleone', 'password1G', '2011/03/03', '1111/01/01', 'Khorasan'),
(4, 'mimi', 'password1G', '2032/12/24', '2024/12/24', 'Belize'),
(5, 'cehalest', 'password1G', '2022/02/02', '1022/02/02', 'Sharjah'),
(6, 'uhu', 'password1G', '3033/03/03', '2033/02/03', 'Zamfara');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`audit_id`),
  ADD UNIQUE KEY `user-id` (`user_id`),
  ADD KEY `user-id_2` (`user_id`),
  ADD KEY `user-id_3` (`user_id`);

--
-- Indexes for table `consoles`
--
ALTER TABLE `consoles`
  ADD PRIMARY KEY (`console_id`);

--
-- Indexes for table `owns`
--
ALTER TABLE `owns`
  ADD PRIMARY KEY (`owns_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`console_id`),
  ADD KEY `console_id` (`console_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consoles`
--
ALTER TABLE `consoles`
  MODIFY `console_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `owns`
--
ALTER TABLE `owns`
  MODIFY `owns_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audits`
--
ALTER TABLE `audits`
  ADD CONSTRAINT `audits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `owns`
--
ALTER TABLE `owns`
  ADD CONSTRAINT `owns_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `owns_ibfk_2` FOREIGN KEY (`console_id`) REFERENCES `consoles` (`console_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
