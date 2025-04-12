-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 10:47 AM
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
-- Database: `library_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `message`, `created_at`, `photo_url`) VALUES
(4, 'CAPSTONE DEFENDED CUTIE✨✨✨🤞🏻🤞🏻🤞🏻', '2025-04-11 05:54:29', 'uploads/announcements/images.png');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `tags` varchar(13) NOT NULL,
  `image` varchar(100) NOT NULL,
  `books_count` int(100) NOT NULL,
  `pages` int(255) NOT NULL,
  `format` varchar(100) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `dewey_decimal` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `name`, `author`, `tags`, `image`, `books_count`, `pages`, `format`, `genre`, `description`, `dewey_decimal`) VALUES
(561, 'A Gentle Reminder', 'Bianca Sparacino', '9781949759297', 'a gentle reminder.jpg', 30, 121, 'Paperback', 'Poetry', 'A gentle reminder, for the days you feel light in this world, and for the days in which the sun rises a little slower. A gentle reminder for when your heart is full of hope, and for when you are learning how to heal it. A gentle reminder for when you finally begin to trust in the goodness, and for when you need the kind of words that hug your broken pieces back together. A gentle reminder for when growth hangs heavy in the air, for when you need to tuck your strength into your bones just to make it to tomorrow. A gentle reminder for when you are balancing the messiness, and the beauty, of what it means to be human, when you are teaching yourself that it is okay to be both happy and sad, that you are real, not perfect. A gentle reminder for when you seek the words you needed when you were younger. A gentle reminder for when you need to hear that you deserve to be loved the way you love others. A gentle reminder for when you need to recognize that you are not your past, that you are not your faults. A gentle reminder for when you need to believe in staying soft, in continuing to be the kind of person who cares. A gentle reminder for when you need to believe in loving deeply in a world that sometimes fails to do so. A gentle reminder to keep going. A gentle reminder to hope.', '25.1'),
(567, 'El Fili', 'Jose Rizal', '978-123456789', 'el fili.jpg', 15, 400, 'Paperback', 'Historical Fiction', 'The second novel of Rizal...', '895');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `book_name` varchar(255) DEFAULT NULL,
  `book_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `pickup_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Collected','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `phone_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `age` int(11) NOT NULL,
  `valid_id` varchar(255) NOT NULL,
  `status` enum('not-verified','verified') DEFAULT 'not-verified',
  `last_login` timestamp NOT NULL DEFAULT current_timestamp(),
  `reservation_history` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_verified` tinyint(1) DEFAULT 0,
  `id_type` varchar(255) NOT NULL,
  `first_login` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `email`, `created_at`, `role`, `phone_number`, `address`, `age`, `valid_id`, `status`, `last_login`, `reservation_history`, `updated_at`, `is_verified`, `id_type`, `first_login`) VALUES
(1, 'admin_use', '$2y$10$4sQfzHmph4SM2G2yYGJweuow9OlMioIR3r9qCX.S27buTIQ.XsoOW', 'James Christian P. Laag', 'jameschristianlaag0718@gmail.com', '2025-04-07 13:25:26', 'admin', '09932681714', 'Blk 6 Lot 32 Tierra Benita Subd. Brgy. Muzon, CSJDM, Bulacan', 21, '', 'verified', '2025-04-08 03:57:31', NULL, '2025-04-11 04:14:31', 1, '', 0),
(11, 'james_user5', '$2y$10$whv595Bv0Y1Itg/cTSoGAesRxJFy470zcijxrHJ5ay.Qah5XDxcc2', 'Christian James', 'kaizokuninaru01@gmail.com', '2025-04-11 03:27:21', 'user', '09583781324', 'Tierra Benita Brgy. Muzon, CSJDM, Bulacan', 21, 'uploads/qc id.jpg', 'not-verified', '2025-04-11 03:27:21', NULL, '2025-04-11 04:16:21', 1, 'qc_id', 0),
(13, 'ej_user3', '$2y$10$geOQIPbq6vxNEP8mckExhO36fiizL/M./YYqDdeAQilRvS63YWzrG', 'Ej Alberto', 'ej.alberto.015@gmail.com', '2025-04-11 06:12:32', 'user', '09457428002', '9 Payte St. Masambong, Quezon City', 22, 'uploads/sample school id.jpg', 'not-verified', '2025-04-11 06:12:32', NULL, '2025-04-11 06:16:58', 1, 'school_id', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_borrowed`
--

CREATE TABLE `user_borrowed` (
  `id` int(11) NOT NULL,
  `borrow_id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `total_books` int(11) NOT NULL,
  `book_names` text NOT NULL,
  `date_placed` date NOT NULL,
  `status` enum('Pending','Approved','Declined') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_borrowed`
--

INSERT INTO `user_borrowed` (`id`, `borrow_id`, `user_id`, `name`, `number`, `email`, `address`, `total_books`, `book_names`, `date_placed`, `status`) VALUES
(11, 'BORROW-67f8cdd0d7b61', 1, 'James Christian P. Laag', '09932681714', 'jameschristianlaag0718@gmail.com', 'Blk 6 Lot 32 Tierra Benita Subd. Brgy. Muzon, CSJDM, Bulacan', 1, 'El Fili', '2025-04-11', 'Pending'),
(12, 'BORROW-67f8d466eb2d1', 11, 'Christian James', '09583781324', 'kaizokuninaru01@gmail.com', 'Tierra Benita Brgy. Muzon, CSJDM, Bulacan', 1, 'A Gentle Reminder', '2025-04-11', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `user_inventory`
--

CREATE TABLE `user_inventory` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `book_image` varchar(255) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_borrowed`
--
ALTER TABLE `user_borrowed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_inventory`
--
ALTER TABLE `user_inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`book_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=582;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_borrowed`
--
ALTER TABLE `user_borrowed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_inventory`
--
ALTER TABLE `user_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_borrowed`
--
ALTER TABLE `user_borrowed`
  ADD CONSTRAINT `user_borrowed_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
