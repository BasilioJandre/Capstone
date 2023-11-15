-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2023 at 05:09 AM
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

--
-- Dumping data for table `archive`
--

INSERT INTO `archive` (`Requisition_No`, `User_Name`, `User_ID`, `Department`, `Date_Requested`, `Date_Needed`, `Request_Type`, `Product/Service`, `Quantity`, `Description`, `Additional_Notes`) VALUES
(1, 'Sarah Doe', '32364651', 'College Faculty', '2023-11-11', '2023-11-12', 'Repair', 'Equipment', 2, 'broken keyboards', ''),
(2, 'Sarah Doe', '32364651', 'College Faculty', '2023-11-12', '2023-11-22', 'Borrow', 'Equipment', 10, 'PC set ups for Esports event', '');

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
('REQNO10', 'Juan Dela Cruz', '26948221', 'High School Academics', '2023-11-15', '2023-11-20', 'Borrow', 'Furnishing/Appliance', 2, '2 large fans for gymnasium event', '', '', '', '', 'Pending', '', 'yes'),
('REQNO3', 'Juan Dela Cruz', '26948221', 'High School Academics', '2023-11-13', '2023-12-05', 'Purchase', 'Consumable', 2, '2 reams of bond paper for school supplies', '', 'Sarah Magpantay(25163716)', '', '', 'Item Delivered', 'Budget and Control', 'no'),
('REQNO4', 'Juan Dela Cruz', '26948221', 'High School Academics', '2023-11-13', '2023-12-18', 'Purchase', 'Equipment', 1, 'Projector for classroom', '', '', '', '', 'Pending', '', 'yes'),
('REQNO5', 'Juan Dela Cruz', '26948221', 'High School Academics', '2023-11-13', '2023-11-21', 'Repair', 'Furnishing/Appliance', 1, 'broken armchair', '', '', '', '', 'Pending', '', 'yes'),
('REQNO6', 'Juan Dela Cruz', '26948221', 'High School Academics', '2023-11-13', '2023-11-16', 'Transfer', 'Equipment', 1, 'transfer projector from room 1 to room 2 please :)', '', '', '', '', 'Pending', '', 'yes'),
('REQNO7', 'Sarah Doe', '32364651', 'College Faculty', '2023-11-13', '2023-11-14', 'Repair', 'Equipment', 1, 'Broken monitor on lab 401', '', 'Jenny Smith(95328448)', '', '', 'Forwarded', 'Budget and Control', 'yes'),
('REQNO8', 'Sarah Doe', '32364651', 'College Faculty', '2023-11-13', '2023-11-15', 'Borrow', 'Furnishing/Appliance', 15, 'chairs for grounds event', '', 'Jenny Smith(95328448)', '', '', 'Forwarded', 'Budget and Control', 'yes'),
('REQNO9', 'Sarah Doe', '32364651', 'College Faculty', '2023-11-13', '2023-11-22', 'Borrow', 'Equipment', 2, 'borrow 2 monitors', '', 'Jenny Smith(95328448)', 'Jane Doe(23859356)', '', 'Forwarded', 'ICTC', 'yes');

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
('REQNO1', '2023-11-12', 'VPAA Office', '2023-11-12', '2023-11-12', 'VPAA Office', '2023-11-12', 'Repaired', NULL, NULL),
('REQNO10', NULL, '', NULL, NULL, '', NULL, '', NULL, NULL),
('REQNO2', NULL, '', NULL, NULL, '', NULL, '', NULL, NULL),
('REQNO3', '2023-11-13', 'Budget and Control', NULL, NULL, '', NULL, '', '2023-11-13', '2023-11-13'),
('REQNO4', NULL, '', NULL, NULL, '', NULL, '', NULL, NULL),
('REQNO5', NULL, '', NULL, NULL, '', NULL, '', NULL, NULL),
('REQNO6', NULL, '', NULL, NULL, '', NULL, '', NULL, NULL),
('REQNO7', '2023-11-13', 'Budget and Control', NULL, NULL, '', NULL, '', NULL, NULL),
('REQNO8', '2023-11-13', 'Budget and Control', NULL, NULL, '', NULL, '', NULL, NULL),
('REQNO9', '2023-11-13', 'Budget and Control', NULL, '2023-11-13', 'ICTC', NULL, '', NULL, NULL);

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
(15778165, 'Ping Guevarra', 'guevarra_p@email.com', 'ICTC', 'Department Head', '$2y$15$GbHyMnA/9B3OKmg7i3X/yegwjgM63waQNLhl6VJVwYowxIqPUMPx6', 'Active'),
(23859356, 'Jane Doe', 'doejane@email.com', 'Budget and Control', 'Department Head', '$2y$15$GoMUdBnLHmro8OPh/wIeV.tMGcGk0D6kiqtiXuiW4twQeFy2VK.RO', 'Active'),
(25163716, 'Sarah Magpantay', 'magpantay.sarah@email.com', 'Senior High School Principal', 'Department Head', '$2y$15$DkpO/gpr4TSvMtxR4Tt4O.4mqazZAjg5q67rB0kcL2SU54im0sUOK', 'Active'),
(26948221, 'Juan Dela Cruz', 'jd.cruz@email.com', 'High School Academics', 'Teaching Personnel', '$2y$15$sD/uMR5XK.xAp3LmknRHjutJMqpQaOGVKhXTfYsGTJRD06Le1jd9W', 'Active'),
(32364651, 'Sarah Doe', 'd_sarah@email.com', 'College Faculty', 'Teaching Personnel', '$2y$15$zf9M9hO8Yo7PXMSACJ3PKOrh9qrAddOZO2pVn4Fl7F6R2H.gLV2ua', 'Active'),
(32669997, 'John Deere', 'jd@email.com', 'ICTC', 'Admin', '$2y$15$47kW6PvGy5q/UTYXjPVOEewHog88.SxBQ.nmRzz.WbQwRqcn0dOG.', 'Active'),
(58153965, 'Charles Baker', 'b_charles@email.com', 'Admin', 'Admin', '$2y$15$1pIGFn0bzRE622wxA1NydemTDZB0Fx.hPRI6D2rQrbZAIUTh4/Bmm', 'Active'),
(95328448, 'Jenny Smith', 'jsmith@email.com', 'College Dean', 'Department Head', '$2y$15$NDUFCTOQK3A6j5XiGF2WSOMHom9if4IWhAqypIAqL.zzVCgxXbLB.', 'Active'),
(97737112, 'Carla Crisostomo', 'carlacris@email.com', 'VPAA', 'Department Head', '$2y$15$Dj92X/JOJn0gWgxr2qbRwOFIs0lQ5CsnCAQ4xaRGOmp4XLvfbiFk.', 'Active');

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
  MODIFY `Requisition_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
