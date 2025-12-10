-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 07:36 PM
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
-- Database: `certificate_mgt_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_record_id` int(11) DEFAULT NULL,
  `type` enum('new','reissue') NOT NULL,
  `details` text DEFAULT NULL,
  `proof_document_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_id` int(11) DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reason` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `user_id`, `student_record_id`, `type`, `details`, `proof_document_path`, `status`, `admin_id`, `applied_at`, `updated_at`, `reason`) VALUES
(6, 2, 1, 'new', 'yo', 'uploads/doc_68e3a9e7d725d7.95883476.png', 'approved', 1, '2025-10-06 11:37:11', '2025-10-06 11:37:25', ''),
(7, 2, 2, 'new', 'hi', 'uploads/doc_68e3aab13f66e8.40901220.jpg', 'approved', 1, '2025-10-06 11:40:33', '2025-10-06 11:40:44', ''),
(8, 2, 1, 'new', 'I need my certificate right now', 'uploads/doc_68e3c2a99046e4.43918906.png', 'rejected', 1, '2025-10-06 13:22:49', '2025-10-06 13:31:34', ''),
(9, 2, 1, 'new', 'hello', 'uploads/doc_68e3c63f11d8c9.58163156.png', 'rejected', 1, '2025-10-06 13:38:07', '2025-10-06 13:39:23', ''),
(10, 2, 1, 'reissue', 'lost', 'uploads/reissue_68e5f575e49aa1.47821259.png', 'approved', 1, '2025-10-08 05:24:05', '2025-10-08 06:12:28', ''),
(11, 2, 1, 'reissue', 'lost lost', 'uploads/reissue_68e5f9155c95f9.08905652.png', 'approved', 1, '2025-10-08 05:39:33', '2025-10-08 06:09:22', ''),
(12, 2, 1, 'reissue', 'test 3', 'uploads/reissue_68e601773382e5.84595245.jpg', 'approved', 1, '2025-10-08 06:15:19', '2025-10-08 06:15:31', ''),
(13, 2, 1, 'reissue', 'test 4', 'uploads/reissue_68e6022a6e1cc5.74849302.png', 'approved', 1, '2025-10-08 06:18:18', '2025-10-08 06:18:29', ''),
(14, 2, 1, 'new', 'hi', 'uploads/doc_68e6440189b067.28507612.png', 'pending', NULL, '2025-10-08 10:59:13', '2025-10-08 10:59:13', ''),
(15, 2, 2, 'reissue', 'test inf', 'uploads/reissue_68e6441032a835.96323361.png', 'pending', NULL, '2025-10-08 10:59:28', '2025-10-08 10:59:28', ''),
(16, 2, 1, 'new', 'test 6', 'uploads/doc_68e7890f8d76c7.86273619.png', 'pending', NULL, '2025-10-09 10:06:07', '2025-10-09 10:06:07', ''),
(17, 2, 1, 'new', 'test 6', 'uploads/doc_68e7893a8210d5.99574005.png', 'approved', 1, '2025-10-09 10:06:50', '2025-11-23 20:28:22', ''),
(18, 2, 2, 'reissue', 'test 6', 'uploads/reissue_68e7894c099632.74769770.png', 'pending', NULL, '2025-10-09 10:07:08', '2025-10-09 10:07:08', ''),
(20, 2, 1, 'reissue', 'test 8', 'uploads/reissue_68e7a8152adcf0.86004021.jpg', 'approved', 1, '2025-10-09 12:18:29', '2025-11-23 20:28:18', ''),
(21, 2, 1, 'reissue', 'Test 10', 'uploads/reissue_68ecaa5e7870f0.99850073.pdf', 'approved', 1, '2025-10-13 07:29:34', '2025-10-16 12:03:50', ''),
(22, 2, 2, 'new', 'isudgiso', 'uploads/doc_68f0ddafdb7351.82182153.png', 'approved', 1, '2025-10-16 11:57:35', '2025-10-16 12:02:38', ''),
(23, 2, 1, 'new', 'uoyfd9o', 'uploads/doc_68f0ddbba36147.98583165.pdf', 'rejected', 1, '2025-10-16 11:57:47', '2025-10-16 12:01:08', ''),
(24, 2, 1, 'new', 'hello', 'uploads/doc_69236ea918ac85.23687726.jpeg', 'approved', 1, '2025-11-23 20:29:29', '2025-11-23 20:31:35', ''),
(25, 2, 2, 'reissue', 'hi', 'uploads/reissue_69236ec75d8542.25226845.pdf', 'rejected', 1, '2025-11-23 20:29:59', '2025-11-23 20:31:49', 'Give proper Application');

-- --------------------------------------------------------

--
-- Table structure for table `correction_items`
--

CREATE TABLE `correction_items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `new_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `correction_items`
--

INSERT INTO `correction_items` (`id`, `request_id`, `field_name`, `new_value`) VALUES
(3, 7, 'student_name', 'sajid'),
(4, 8, 'date_of_birth', '2025-10-01'),
(5, 9, 'student_name', 'Sajid Ul Islam'),
(6, 9, 'father_name', 'MD Sirajul Islam'),
(7, 10, 'student_name', 'Sajid Ul Islam'),
(8, 10, 'father_name', 'Md. Sirajul Islam');

-- --------------------------------------------------------

--
-- Table structure for table `correction_requests`
--

CREATE TABLE `correction_requests` (
  `id` int(11) NOT NULL,
  `certificate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `proof_document` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_id` int(11) DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reason` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `correction_requests`
--

INSERT INTO `correction_requests` (`id`, `certificate_id`, `user_id`, `proof_document`, `status`, `admin_id`, `requested_at`, `reason`) VALUES
(7, 6, 2, 'uploads/proofs/proof_68e628071f4422.74502481.png', 'approved', 1, '2025-10-08 08:59:51', ''),
(8, 7, 2, 'uploads/proofs/proof_68e789629e1281.84892586.png', 'rejected', 1, '2025-10-09 10:07:30', 'not sufficient proof'),
(9, 6, 2, 'uploads/proofs/proof_68f0ddf0b0bc90.11243831.png', 'approved', 1, '2025-10-16 11:58:40', ''),
(10, 7, 2, 'uploads/proofs/proof_69236ed7693fb8.18267856.png', 'approved', 1, '2025-11-23 20:30:15', '');

-- --------------------------------------------------------

--
-- Table structure for table `student_records`
--

CREATE TABLE `student_records` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_type` enum('SSC','HSC') NOT NULL,
  `roll_number` int(11) NOT NULL,
  `registration_number` varchar(50) NOT NULL,
  `exam_year` int(11) NOT NULL,
  `board` varchar(100) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `mother_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gpa` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_records`
--

INSERT INTO `student_records` (`id`, `user_id`, `exam_type`, `roll_number`, `registration_number`, `exam_year`, `board`, `student_name`, `father_name`, `mother_name`, `date_of_birth`, `gpa`) VALUES
(1, 2, 'SSC', 1234, '123456', 2018, 'Rajshahi', 'Sajid-Ul Islam', 'Md. Sirajul Islam', 'MST. Shahnaz parveen', '2000-12-01', 4.94),
(2, 2, 'HSC', 123456, '12345678', 2020, 'Rajshahi', 'Sajid-Ul Islam', 'Md. Sirajul Islam', 'MST. Shahnaz parveen', '2000-12-01', 5.00),
(5, 5, 'SSC', 235484, '5421532054', 2022, 'Dhaka', 'MR X', 'MR Y', 'MR Z', '2003-06-20', 5.00),
(6, 5, 'HSC', 25484121, '3264204510', 2024, 'Rajshahi', 'MR X', 'MR Y', 'MR Z', '2003-06-20', 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@gmail.com', 'admin', 'admin', '2025-10-01 04:24:16'),
(2, 'Sajid Ul Islam', 'sajid@gmail.com', 'sajid', 'student', '2025-10-01 04:57:33'),
(5, 'MR X', 'hello@gmail.com', 'mrx', 'student', '2025-12-10 18:06:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_admin` (`admin_id`),
  ADD KEY `student_id` (`student_record_id`);

--
-- Indexes for table `correction_items`
--
ALTER TABLE `correction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `correction_requests`
--
ALTER TABLE `correction_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificate_id` (`certificate_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `student_records`
--
ALTER TABLE `student_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_record` (`exam_type`,`roll_number`,`exam_year`,`board`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `correction_items`
--
ALTER TABLE `correction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `correction_requests`
--
ALTER TABLE `correction_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student_records`
--
ALTER TABLE `student_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`student_record_id`) REFERENCES `student_records` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `correction_items`
--
ALTER TABLE `correction_items`
  ADD CONSTRAINT `correction_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `correction_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `correction_requests`
--
ALTER TABLE `correction_requests`
  ADD CONSTRAINT `correction_requests_ibfk_1` FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `correction_requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `correction_requests_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `student_records`
--
ALTER TABLE `student_records`
  ADD CONSTRAINT `student_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
