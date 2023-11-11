-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2023 at 01:52 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
  `Requisition_No` int(11) NOT NULL,
  `User_Name` varchar(50) NOT NULL,
  `User_ID` varchar(50) NOT NULL,
  `Department` varchar(50) NOT NULL,
  `Date_Requested` varchar(50) NOT NULL,
  `Date_Needed` varchar(50) NOT NULL,
  `Request_Type` varchar(50) NOT NULL,
  `Product/Service` varchar(50) NOT NULL,
  `Quantity` int(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Additional_Notes` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `User_ID` int(11) NOT NULL,
  `User_Picture` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `Requisition_No` varchar(11) NOT NULL,
  `User_Name` varchar(50) NOT NULL,
  `User_ID` varchar(50) NOT NULL,
  `Department` varchar(50) NOT NULL,
  `Date_Requested` date NOT NULL,
  `Date_Needed` date NOT NULL,
  `Request_Type` varchar(50) NOT NULL,
  `Product/Service` varchar(50) NOT NULL,
  `Quantity` int(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Additional_Notes` mediumtext NOT NULL,
  `Noted_By` varchar(50) NOT NULL,
  `Noted_By_Budget` varchar(50) NOT NULL,
  `Approved_By` varchar(50) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Forward_To` varchar(50) NOT NULL,
  `Active` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE `track` (
  `Request_No` varchar(11) NOT NULL,
  `Forward_Head` date DEFAULT NULL,
  `Forward_Head_To` varchar(50) NOT NULL,
  `Forward_EVP/VPAA` date DEFAULT NULL,
  `Forward_Budget` date DEFAULT NULL,
  `Forward_Budget_To` varchar(50) NOT NULL,
  `Handled_Date` date DEFAULT NULL,
  `Request_Status` varchar(50) NOT NULL,
  `Purchase_Date` date DEFAULT NULL,
  `Deliver_Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `Full_Name` varchar(25) NOT NULL,
  `Email` varchar(25) NOT NULL,
  `Department` varchar(60) NOT NULL,
  `Role` varchar(25) NOT NULL,
  `Password` varchar(70) NOT NULL,
  `Status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `Full_Name`, `Email`, `Department`, `Role`, `Password`, `Status`) VALUES
(58153965, 'Charles Baker', 'b_charles@email.com', 'Admin', 'Admin', '$2y$15$1pIGFn0bzRE622wxA1NydemTDZB0Fx.hPRI6D2rQrbZAIUTh4/Bmm', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`Requisition_No`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`Requisition_No`);

--
-- Indexes for table `track`
--
ALTER TABLE `track`
  ADD PRIMARY KEY (`Request_No`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `Requisition_No` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58153966;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
