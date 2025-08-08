-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 08:42 AM
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
-- Database: `transport`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_tbl`
--

CREATE TABLE `category_tbl` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_tbl`
--

INSERT INTO `category_tbl` (`cat_id`, `cat_name`) VALUES
(2, 'Car'),
(3, 'Mini Truck'),
(4, 'bike');

-- --------------------------------------------------------

--
-- Table structure for table `customer_tbl`
--

CREATE TABLE `customer_tbl` (
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_tbl`
--

INSERT INTO `customer_tbl` (`username`, `email`, `password`, `age`, `gender`) VALUES
('first', 'first@gmail.com', '$2y$10$7arq0bbpQsstQjQAHkSi4.fegKcGN7.u.6wPw5rc14IjS7MT8j39O', 20, 'male');

-- --------------------------------------------------------

--
-- Table structure for table `renter_tbl`
--

CREATE TABLE `renter_tbl` (
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `renter_tbl`
--

INSERT INTO `renter_tbl` (`username`, `email`, `password`, `age`, `gender`) VALUES
('second', 'second@gmail.com', '$2y$10$IvkD6bkFYaTu2ucsB9JIdeSkK9rtSROK59Q3dbLXcVW/cY95vjAoC', 12, 'male'),
('weqw', 'qwe', '$2y$10$nA7ENXn1yPPBQc6pkUH83uVOAduQN2//2v1HWS6MnwxN9ea0OpJmS', 0, 'male');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_tbl`
--

CREATE TABLE `vehicle_tbl` (
  `veh_id` int(11) NOT NULL,
  `veh_name` varchar(30) DEFAULT NULL,
  `veh_model` varchar(30) DEFAULT NULL,
  `veh_reginfo` varchar(30) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `contact_no` bigint(20) NOT NULL,
  `veh_loc` varchar(30) DEFAULT NULL,
  `veh_img` varchar(500) DEFAULT NULL,
  `veh_des` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_tbl`
--

INSERT INTO `vehicle_tbl` (`veh_id`, `veh_name`, `veh_model`, `veh_reginfo`, `cat_id`, `contact_no`, `veh_loc`, `veh_img`, `veh_des`) VALUES
(1, 'sfnsdjf', 'm mmbm,n,mnm,n', 'mmnbnbbm', 3, 0, 'vnmnnbnbmnbnmm', 'uploads/688a5653f3c78_textlo.png', 'fesafefwef');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_tbl`
--
ALTER TABLE `category_tbl`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `customer_tbl`
--
ALTER TABLE `customer_tbl`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `renter_tbl`
--
ALTER TABLE `renter_tbl`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicle_tbl`
--
ALTER TABLE `vehicle_tbl`
  ADD PRIMARY KEY (`veh_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_tbl`
--
ALTER TABLE `category_tbl`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vehicle_tbl`
--
ALTER TABLE `vehicle_tbl`
  MODIFY `veh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vehicle_tbl`
--
ALTER TABLE `vehicle_tbl`
  ADD CONSTRAINT `vehicle_tbl_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category_tbl` (`cat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
