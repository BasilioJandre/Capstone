-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2023 at 03:05 AM
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
(2, 'testuser', '62215713', 'College Faculty', '2023-10-23', '2023-11-02', 'Repair', 'Consumable', 1613213, 'test1', ''),
(3, 'testuser', '62215713', 'College Faculty', '2023-10-23', '2023-11-02', 'Borrow', 'Furnishing/Appliance', 21681, 'test2', 'I believe these remarks work'),
(4, 'testuser', '62215713', 'College Faculty', '2023-10-23', '2023-10-09', 'Purchase', 'Consumable', 8421, 'test3', '');

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
  `Additional_Notes` mediumtext NOT NULL,
  `Noted_By` varchar(50) NOT NULL,
  `Approved_By` varchar(50) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Forward_To` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`Requisition_No`, `User_Name`, `User_ID`, `Department`, `Date_Requested`, `Date_Needed`, `Request_Type`, `Product/Service`, `Quantity`, `Description`, `Additional_Notes`, `Noted_By`, `Approved_By`, `Status`, `Forward_To`) VALUES
(1, 'Pedro Silang', '47841552', 'High School Academics', '2023-10-30', '2023-10-18', 'Transfer', 'Consumable', 13, 'dasdadw', 'yes', 'Sarah Bearing(22558296)', 'John Doe(54413949)', 'Approved', 'College'),
(2, 'Pedro Silang', '47841552', 'High School Academics', '2023-10-30', '2023-11-09', 'Purchase', 'Consumable', 123, 'test notes again', '', '', '', 'New', '');

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE `track` (
  `Request_No` int(11) NOT NULL,
  `Forward_Date` varchar(50) NOT NULL,
  `Step2` varchar(50) NOT NULL,
  `Step3` varchar(50) NOT NULL,
  `Step4` varchar(50) NOT NULL,
  `Step5` varchar(50) NOT NULL,
  `Step6` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `track`
--

INSERT INTO `track` (`Request_No`, `Forward_Date`, `Step2`, `Step3`, `Step4`, `Step5`, `Step6`) VALUES
(1, '2023-10-30', '2023-10-31', '', '', '', '');

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
(22558296, 'Sarah Bearing', 'shs.dept_head@email.com', 'Senior High School Principal', 'Department Head', '$2y$15$FkQcPQXvm.UNXV.UyxWElO7M/lcbVYtb2Cpy5OcOl1H3IYeGKuqUu', 'Active'),
(47841552, 'Pedro Silang', 'shs@email.com', 'High School Academics', 'Teaching Personnel', '$2y$15$OmKu/q0jPObMHZ4a6lBN0eH00Swbn4gyLfMLs5aj1A4iFQ4cJGgvq', 'Active'),
(54413949, 'John Doe', 'col.dept_head@email.com', 'College Dean', 'Department Head', '$2y$15$Xo8wUSfkHIwPQVVvwOtHfOE4bTB50kYmmaQnnhR/UcKDI2UErSClu', 'Active'),
(62215713, 'Jane Doe', 'college@email.com', 'College Faculty', 'Teaching Personnel', '$2y$15$K1XrBslg6.A9wi6nLropPu/sLLEHl69cUaHaGY5B7.bWEGLl/ufOS', 'Active'),
(72111764, 'Admin', 'admin@email.com', 'Aula', 'Admin', '$2y$15$46lvkdbRdRZwYO9.9MvBFe3JOptrQMuggU2aA6T.Ad8ovE/fww7DC', 'Active');

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
  MODIFY `Requisition_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `Requisition_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `track`
--
ALTER TABLE `track`
  MODIFY `Request_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72111765;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
