-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2023 at 05:13 AM
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

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`Requisition_No`, `User_Name`, `User_ID`, `Department`, `Date_Requested`, `Date_Needed`, `Request_Type`, `Product/Service`, `Quantity`, `Description`, `Additional_Notes`, `Noted_By`, `Noted_By_Budget`, `Approved_By`, `Status`, `Forward_To`, `Active`) VALUES
('REQNO1', 'Sarah Doe', '32364651', 'College Faculty', '2023-11-11', '2023-11-12', 'Repair', 'Equipment', 2, 'broken keyboards', '', 'Carla Crisostomo(97737112) / Jenny Smith(95328448)', 'Jane Doe(23859356)', 'Carla Crisostomo(97737112)', 'Repaired', 'VPAA Office', 'no');

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

--
-- Dumping data for table `track`
--

INSERT INTO `track` (`Request_No`, `Forward_Head`, `Forward_Head_To`, `Forward_EVP/VPAA`, `Forward_Budget`, `Forward_Budget_To`, `Handled_Date`, `Request_Status`, `Purchase_Date`, `Deliver_Date`) VALUES
('REQNO1', '2023-11-12', 'VPAA Office', '2023-11-12', '2023-11-12', 'VPAA Office', '2023-11-12', 'Repaired', NULL, NULL);

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
(23859356, 'Jane Doe', 'doejane@email.com', 'Budget and Control', 'Department Head', '$2y$15$GoMUdBnLHmro8OPh/wIeV.tMGcGk0D6kiqtiXuiW4twQeFy2VK.RO', 'Active'),
(32364651, 'Sarah Doe', 'd_sarah@email.com', 'College Faculty', 'Teaching Personnel', '$2y$15$zf9M9hO8Yo7PXMSACJ3PKOrh9qrAddOZO2pVn4Fl7F6R2H.gLV2ua', 'Active'),
(32669997, 'John Deere', 'jd@email.com', 'ICTC', 'Admin', '$2y$15$47kW6PvGy5q/UTYXjPVOEewHog88.SxBQ.nmRzz.WbQwRqcn0dOG.', 'Active'),
(49113465, 'Pedro Calungsod', 'calungsod@email.com', 'College Faculty', 'Teaching Personnel', '$2y$15$K8ThNXxcReUiHpO0xQP5h.AiupeykFkmlOgqPwlsRD7ZPRksFP6nG', 'Pending'),
(58153965, 'Charles Baker', 'b_charles@email.com', 'Admin', 'Admin', '$2y$15$1pIGFn0bzRE622wxA1NydemTDZB0Fx.hPRI6D2rQrbZAIUTh4/Bmm', 'Active'),
(95328448, 'Jenny Smith', 'jsmith@email.com', 'College Dean', 'Department Head', '$2y$15$NDUFCTOQK3A6j5XiGF2WSOMHom9if4IWhAqypIAqL.zzVCgxXbLB.', 'Active'),
(97737112, 'Carla Crisostomo', 'CarlaCris@email.com', 'VPAA', 'Department Head', '$2y$15$Dj92X/JOJn0gWgxr2qbRwOFIs0lQ5CsnCAQ4xaRGOmp4XLvfbiFk.', 'Active');

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
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97737113;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
