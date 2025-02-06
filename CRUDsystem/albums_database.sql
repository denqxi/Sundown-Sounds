-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 02:39 PM
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
-- Database: `albums_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `album_id` int(11) NOT NULL,
  `album_name` varchar(255) NOT NULL,
  `artist_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `albmQty` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `album_name`, `artist_id`, `genre_id`, `release_date`, `price`, `image_url`, `albmQty`) VALUES
(1, 'Off The Wall', 1, 1, '1979-08-10', 2437.00, '/CRUDsystem/images/album covers/offthewall.jpg', 13),
(2, 'SOS (Deluxe)', 4, 2, '2024-12-20', 4341.00, '/CRUDsystem/images/album covers/SOS-Deluxe.jpg\r\n', 13),
(3, 'Blonde', 2, 2, '2016-08-20', 8755.00, '/CRUDsystem/images/album covers/blonde.jpeg\r\n', 14),
(4, 'IGOR', 3, 3, '2019-05-17', 2541.00, '/CRUDsystem/images/album covers/igor.jpg\r\n', 15),
(5, 'Positions', 5, 4, '2020-10-30', 2300.00, '/CRUDsystem/images/album covers/Positions.png\r\n', 15),
(6, 'Chromakopia', 3, 5, '2024-10-28', 2316.00, '/CRUDsystem/images/album covers/chromakopia.jpg', 14),
(7, 'GNX', 6, 5, '2024-11-22', 1969.00, '/CRUDsystem/images/album covers/gnx.jpg', 15),
(8, 'Alligator Bites Never Heal', 7, 5, '2024-08-30', 1737.00, '/CRUDsystem/images/album covers/abnh.jpg', 15);

-- --------------------------------------------------------

--
-- Table structure for table `album_genres`
--

CREATE TABLE `album_genres` (
  `album_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `album_genres`
--

INSERT INTO `album_genres` (`album_id`, `genre_id`) VALUES
(1, 1),
(2, 2),
(3, 2),
(4, 3),
(5, 4),
(6, 5),
(7, 5),
(8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `artist_id` int(11) NOT NULL,
  `artist_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`artist_id`, `artist_name`) VALUES
(1, 'Micheal Jackson'),
(2, 'Frank Ocean'),
(3, 'Tyler, The Creator'),
(4, 'SZA'),
(5, 'Ariana Grande'),
(6, 'Kendrick Lamar'),
(7, 'Doechii');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `album_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `genre_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `genre_name`) VALUES
(1, 'Funk/Disco'),
(2, 'R&B/Soul'),
(3, 'Indie/Underground Hip-Hop'),
(4, 'Pop'),
(5, 'Hip-Hop/Rap');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `order_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending',
  `cancellation_reason` text DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_name`, `address`, `order_date`, `total_amount`, `status`, `cancellation_reason`, `payment_method`) VALUES
(1, 'Jane Doe', '123 Street', '2025-02-06', 2437.00, 'Cancelled', 'I\'ve changed my mind.', 'Credit Card'),
(2, 'John Smith', '123 Avenue', '2025-02-06', 13096.00, 'Processing', NULL, 'Cash on Delivery'),
(3, 'Senku', '3213 St. Street', '2025-02-06', 2316.00, 'Processing', NULL, 'Cash on Delivery'),
(4, 'Marianne Nicole Berida Partoza', 'Matina, Balusong', '2025-02-06', 4341.00, 'Processing', NULL, 'Gcash'),
(5, 'Solana', '123 Blvd, Pogi Street', '2025-02-06', 4874.00, 'Processing', NULL, 'Gcash');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `album_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 2437.00),
(2, 2, 2, 1, 4341.00),
(3, 2, 3, 1, 8755.00),
(5, 3, 6, 1, 2316.00),
(6, 4, 2, 1, 4341.00),
(7, 5, 1, 2, 2437.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `album_genres`
--
ALTER TABLE `album_genres`
  ADD PRIMARY KEY (`album_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`artist_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `album_id` (`album_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `artist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`),
  ADD CONSTRAINT `albums_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`);

--
-- Constraints for table `album_genres`
--
ALTER TABLE `album_genres`
  ADD CONSTRAINT `album_genres_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `album_genres_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
