-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2023 at 10:04 AM
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
(2, 'Jane Doe', '62215713', 'College Faculty', '2023-11-09', '2023-11-23', 'Repair', 'Consumable', 123131, 'asdasdas', ''),
(4, 'Jane Doe', '62215713', 'College Faculty', '2023-11-09', '2023-11-23', 'Repair', 'Consumable', 123, '', ''),
(5, 'Jane Doe', '62215713', 'College Faculty', '2023-11-09', '2023-11-23', 'Borrow', 'Equipment', 3131, '', '');

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
('REQNO1', 'Pedro Silang', '47841552', 'High School Academics', '2023-11-11', '2023-11-28', 'Transfer', 'Equipment', 44323, '', '', '', '', 'Sarah Bearing(22558296)', 'Declined', '', 'yes'),
('REQNO2', 'Pedro Silang', '47841552', 'High School Academics', '2023-11-11', '2023-11-28', 'Repair', 'Consumable', 6564, '', '', 'Sarah Bearing(22558296)', '', '', 'Forwarded', 'Budget and Control', 'yes'),
('REQNO3', 'Pedro Silang', '47841552', 'High School Academics', '2023-11-11', '2023-11-16', 'Purchase', 'Consumable', 0, '', '', '', '', 'Sarah Bearing(22558296)', 'Declined', '', 'yes');

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
('REQNO1', NULL, '', NULL, NULL, '', '2023-11-11', 'Declined', NULL, NULL),
('REQNO2', '2023-11-11', 'Budget and Control', NULL, NULL, '', NULL, '', NULL, NULL),
('REQNO3', NULL, '', NULL, NULL, '', '2023-11-11', 'Declined', NULL, NULL);

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
(16928636, 'finance', 'finance@email.com', 'Finance', 'Department Head', '$2y$15$KUcbYM5YMyf1qiHHtIL34utJe1CPj0VzFvX/IE7OLhRM..u14l76K', 'Active'),
(18214812, 'Budcon', 'budget@email.com', 'Budget and Control', 'Department Head', '$2y$15$2Z73T8dHNSvyjpfzNlqxP.rBG2KDIJWx/v3QDwpU1I4VYQ/XcqsOq', 'Active'),
(22558296, 'Sarah Bearing', 'shs.dept_head@email.com', 'Senior High School Principal', 'Department Head', '$2y$15$FkQcPQXvm.UNXV.UyxWElO7M/lcbVYtb2Cpy5OcOl1H3IYeGKuqUu', 'Active'),
(25622837, 'gsu', 'gsu@email.com', 'GSU', 'Department Head', '$2y$15$bTn0ExEud9ArMDfJ4qpomeXBoXLlSrTAajZcNkiWykYkUFD7R5TXC', 'Active'),
(47841552, 'Pedro Silang', 'shs@email.com', 'High School Academics', 'Teaching Personnel', '$2y$15$OmKu/q0jPObMHZ4a6lBN0eH00Swbn4gyLfMLs5aj1A4iFQ4cJGgvq', 'Active'),
(54413949, 'John Doe', 'col.dept_head@email.com', 'College Dean', 'Department Head', '$2y$15$Xo8wUSfkHIwPQVVvwOtHfOE4bTB50kYmmaQnnhR/UcKDI2UErSClu', 'Active'),
(62215713, 'Jane Doe', 'college@email.com', 'College Faculty', 'Teaching Personnel', '$2y$15$K1XrBslg6.A9wi6nLropPu/sLLEHl69cUaHaGY5B7.bWEGLl/ufOS', 'Active'),
(72111764, 'Nimda', 'admin@email.com', 'Aula', 'Admin', '$2y$15$46lvkdbRdRZwYO9.9MvBFe3JOptrQMuggU2aA6T.Ad8ovE/fww7DC', 'Active'),
(74273766, 'ictc', 'ictc@email.com', 'ICTC', 'Department Head', '$2y$15$6Sisu3oOjM3VM3mP38xgyeNG1I6hCOcLtIF/wcZo8Sh9XtTqcvxde', 'Active'),
(78461397, 'Doe John', 'johndoe@email.com', 'College Faculty', 'Non-Teaching Personnel', '$2y$15$QiDq9yzKUwmcVGasP4n4M.bE47mdfykb8jBFhen.1kle3YlPYCL4W', 'Active'),
(98831893, 'VPAA', 'vpaa@email.com', 'VPAA', 'Department Head', '$2y$15$a/AD0Gp2nxSTfJRfWNy4T.TE2oHwXmFRhdgefNe24coEF35XOqZBC', 'Active');

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
  MODIFY `Requisition_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98831894;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
