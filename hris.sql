-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2025 at 05:36 PM
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
-- Database: `hris`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `control_number` varchar(13) NOT NULL,
  `absent` float NOT NULL,
  `late` float NOT NULL,
  `undertime` float NOT NULL,
  `month` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_year`
--

CREATE TABLE `attendance_year` (
  `year_id` int(11) NOT NULL,
  `school_year` varchar(10) NOT NULL,
  `months` varchar(250) NOT NULL,
  `from_month` varchar(50) NOT NULL,
  `to_month` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_year`
--

INSERT INTO `attendance_year` (`year_id`, `school_year`, `months`, `from_month`, `to_month`) VALUES
(19, '2023-2024', 'November, December, January, February, March, April, May, June, July', 'November', 'July');

-- --------------------------------------------------------

--
-- Table structure for table `data_values`
--

CREATE TABLE `data_values` (
  `value_id` int(11) NOT NULL,
  `data_type` enum('emp_status','classification','department') NOT NULL,
  `data_value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_values`
--

INSERT INTO `data_values` (`value_id`, `data_type`, `data_value`) VALUES
(8, 'emp_status', 'Permanent'),
(9, 'emp_status', 'Probationary'),
(10, 'emp_status', 'Substitute'),
(11, 'emp_status', 'Part-time'),
(12, 'classification', 'Rank and File (Faculty)'),
(13, 'classification', 'Rank and File (Staff)'),
(14, 'classification', 'Academic Middle-Level Administrator'),
(24, 'department', 'Pre-School'),
(25, 'department', 'Araling Panlipunan'),
(26, 'department', 'Christian Living Education'),
(27, 'department', 'Com. Arts English'),
(28, 'department', 'Com. Arts Filipino'),
(29, 'department', 'Mathematics'),
(30, 'department', 'Music Arts Physical Education and Health'),
(31, 'department', 'Science'),
(32, 'department', 'Technology and Livelihood'),
(33, 'department', 'Core Group'),
(34, 'department', 'Non - Teaching Staff'),
(35, 'department', 'Student Services'),
(36, 'classification', 'Non-Academic Middle-Level Administrator'),
(37, 'classification', 'Top Management'),
(38, 'classification', 'Auxiliary'),
(39, 'classification', 'Religious of the Assumption'),
(50, 'department', 'Auxilliary'),
(51, 'department', 'Religious of the Assumption');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `control_number` varchar(13) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `birthday` date NOT NULL,
  `age` int(3) NOT NULL,
  `civil_status` enum('Single','Married','Widowed') DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `employment_status` varchar(100) NOT NULL,
  `classification` varchar(100) NOT NULL,
  `date_hired` date NOT NULL,
  `salary` float NOT NULL,
  `years_in_service` int(2) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `course_taken` varchar(250) DEFAULT NULL,
  `further_studies` varchar(250) DEFAULT NULL,
  `number_of_units` float DEFAULT NULL,
  `prc_number` varchar(7) DEFAULT '',
  `prc_exp` date DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `tin` varchar(11) DEFAULT NULL,
  `sss` varchar(12) DEFAULT NULL,
  `philhealth` varchar(14) DEFAULT NULL,
  `pag_ibig` varchar(14) DEFAULT NULL,
  `status` enum('active','resigned') NOT NULL DEFAULT 'active',
  `image` varchar(250) NOT NULL DEFAULT 'default.jpg',
  `resignation_date` date DEFAULT NULL,
  `department` varchar(100) NOT NULL,
  `vl` int(2) NOT NULL,
  `sl` int(2) NOT NULL,
  `remaining_leave` float NOT NULL,
  `less_yis` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`control_number`, `surname`, `name`, `middle_name`, `suffix`, `birthday`, `age`, `civil_status`, `gender`, `employment_status`, `classification`, `date_hired`, `salary`, `years_in_service`, `address`, `contact`, `email`, `course_taken`, `further_studies`, `number_of_units`, `prc_number`, `prc_exp`, `position`, `tin`, `sss`, `philhealth`, `pag_ibig`, `status`, `image`, `resignation_date`, `department`, `vl`, `sl`, `remaining_leave`, `less_yis`) VALUES
('00-00000', 'Simbulan', 'Adrian', 'Meneses', '', '1999-04-28', 25, 'Single', 'Male', 'Permanent', 'Academic Middle-Level Administrator', '2022-08-22', 10000, 2, 'Lot 1, Block 1, Phase 2', '09123456789', 'example.email@example.com', 'Bachelor of Science in Mechanical Engineering', '', 20, 'Prc###', '2023-09-22', 'Position', '12345678910', '', '', '', 'active', 'default.jpg', NULL, 'Pre-School', 0, 0, 0, 0),
('10-6169', 'Surname44', 'Name44', '', '', '2023-09-18', 1, 'Single', 'Male', 'Part-time', 'Rank and File (Faculty)', '1998-07-12', 180000, 22, 'Address44', 'Contact44', 'email44@example.com', 'Course44', 'Studies44', 188, 'PRC44', '2014-04-10', 'Position44', 'TIN44', '', '', 'PagIbig44', 'active', '1695038555_download (1).jpg', '1977-09-07', 'asdf', 5, 5, 10, 4),
('15-1055', 'Surname30', 'Name30', 'MiddleName30', 'III', '2018-09-05', 6, 'Single', 'Female', 'Permanent', 'Non-Academic Middle-Level Administrator', '1988-05-20', 0, 32, 'Address30', 'Contact30', 'email30@example.com', 'Course30', '', 12, 'PRC30', '1987-11-05', 'Position30', 'TIN30', '', '', 'PagIbig30', 'active', 'default.jpg', NULL, 'Com. Arts English', 5, 5, 10, 4),
('15-4268', 'Surname10', 'Name10', '', 'Sr.', '1987-07-01', 37, 'Married', 'Female', 'Probationary', 'Religious of the Assumption', '2004-03-29', 0, 17, 'Address10', 'Contact10', 'email10@example.com', 'Course10', '', 166, 'PRC10', '2016-01-08', 'Position10', '', 'SSS10', 'Philhealth10', 'PagIbig10', 'active', 'default.jpg', '2021-12-24', 'Non - Teaching Staff', 0, 5, 5, 3),
('16-3427', 'Surname6', 'Name6', '', '', '1985-07-07', 39, 'Single', 'Female', 'Part-time', 'Academic Middle-Level Administrator', '2010-11-22', 0, 10, 'Address6', 'Contact6', 'email6@example.com', 'Course6', 'Studies6', 88, 'PRC6', '1993-01-09', 'Position6', 'TIN6', 'SSS6', '', '', 'active', 'default.jpg', NULL, 'Com. Arts Filipino', 5, 10, 15, 4),
('16-8346', 'Surname39', 'Name39', '', 'III', '2022-11-30', 2, 'Single', 'Male', 'Part-time', 'Non-Academic Middle-Level Administrator', '2005-07-27', 0, 19, 'Address39', 'Contact39', 'email39@example.com', 'Course39', '', 11, 'PRC39', '1981-08-01', 'Position39', '', '', 'Philhealth39', 'PagIbig39', 'active', 'default.jpg', '1973-06-27', 'Auxilliary', 10, 0, 10, 0),
('18-6022', 'Simbulann', 'Ian Angelo', 'Meneses', '', '2002-09-16', 22, 'Single', 'Male', 'Permanent', 'Rank and File (Faculty)', '1987-09-19', 0, 35, 'Address34', 'Contact34', 'email34@example.com', 'Course34', 'Studies34', 82, 'PRC34', '1986-10-31', 'Position34', '', 'SSS34', 'Philhealth34', 'PagIbig34', 'active', '1695038577_download (1).jpg', '1974-08-07', 'Com. Arts Filipino', 5, 5, 10, 2),
('18-8048', 'Surname36', 'Name36', 'MiddleName36', '', '2009-03-29', 15, 'Widowed', 'Female', 'Part-time', 'Non-Academic Middle-Level Administrator', '1981-11-25', 0, 43, 'Address36', 'Contact36', 'email36@example.com', 'Course36', '', 168, 'PRC36', '1978-04-22', 'Position36', 'TIN36', '', 'Philhealth36', '', 'active', 'default.jpg', '1982-02-14', 'Technology and Livelihood', 5, 10, 15, 0),
('18-9155', 'Surname17', 'Name17', '', '', '1976-09-10', 48, 'Married', 'Female', 'Probationary', 'Rank and File (Staff)', '1981-07-31', 0, 41, 'Address17', 'Contact17', 'email17@example.com', 'Course17', '', 20, 'PRC17', '1977-01-22', 'Position17', 'TIN17', 'SSS17', 'Philhealth17', 'PagIbig17', 'active', 'default.jpg', '2009-11-27', 'Student Services', 5, 10, 15, 2),
('19-117', 'Cortez', 'Andrea Lois ', 'Aguilar ', '', '1996-08-23', 28, 'Single', 'Female', 'Permanent', 'Non-Academic Middle-Level Administrator', '2019-07-19', 0, 5, '138 San Juan, San Simon, Pampanga', '09954376261', 'andrealoiscortez@gmail.com', 'BS PSYCHOLOGY', 'NA', 0, '', '0000-00-00', 'HR OFFICER', '', '34-6772201-2', '07-025871651-3', '1212-0678-23', 'active', 'default.jpg', NULL, 'Non - Teaching Staff', 0, 0, 0, 0),
('20-4241', 'Surname3', 'Name3', '', '', '2009-05-29', 15, 'Married', 'Male', 'Permanent', 'Academic Middle-Level Administrator', '1973-08-16', 0, 51, 'Address3', 'Contact3', 'email3@example.com', 'Course3', 'Studies3', 153, 'PRC3', '1999-02-24', 'Position3', '', 'SSS3', '', '', 'active', 'default.jpg', '1974-09-18', 'Com. Arts Filipino', 10, 10, 20, 0),
('22-3491', 'Surname48', 'Name48', '', '', '2015-01-01', 10, 'Widowed', 'Female', 'Substitute', 'Auxiliary', '1978-03-06', 0, 42, 'Address48', 'Contact48', 'email48@example.com', 'Course48', 'Studies48', 159, 'PRC48', '2006-08-17', 'Position48', '', '', '', 'PagIbig48', 'active', 'default.jpg', '1989-08-02', 'Pre-School', 5, 10, 15, 5),
('22-4659', 'Surname10', 'Name10', 'MiddleName10', 'Jr.', '2018-07-04', 6, 'Widowed', 'Male', 'Substitute', 'Rank and File (Faculty)', '1970-07-28', 0, 54, 'Address10', 'Contact10', 'email10@example.com', 'Course10', 'Studies10', 42, 'PRC10', '2019-05-30', 'Position10', '', 'SSS10', 'Philhealth10', 'PagIbig10', 'active', 'default.jpg', '2020-10-02', 'Pre-School', 5, 0, 5, 0),
('22-6731', 'Surname32', 'Name32', 'MiddleName32', 'Jr.', '1978-02-16', 47, 'Single', 'Female', 'Substitute', 'Top Management', '2009-03-21', 0, 14, 'Address32', 'Contact32', 'email32@example.com', 'Course32', 'Studies32', 172, 'PRC32', '1993-11-27', 'Position32', '', 'SSS32', '', 'PagIbig32', 'active', 'default.jpg', '2001-01-29', 'Non - Teaching Staff', 5, 5, 10, 1),
('23-1964', 'Surname13', 'Name13', '', '', '2011-10-17', 13, 'Single', 'Female', 'Permanent', 'Rank and File (Faculty)', '1975-12-13', 0, 47, 'Address13', 'Contact13', 'email13@example.com', 'Course13', 'Studies13', 147, 'PRC13', '2019-02-12', 'Position13', 'TIN13', '', '', 'PagIbig13', 'active', 'default.jpg', '1996-02-19', 'Com. Arts English', 0, 5, 5, 2),
('23-4161', 'Surname11', 'Name11', '', '', '1987-03-28', 37, 'Widowed', 'Female', 'Part-time', 'Academic Middle-Level Administrator', '1971-03-23', 0, 50, 'Address11', 'Contact11', 'email11@example.com', 'Course11', 'Studies11', 101, 'PRC11', '1978-12-30', 'Position11', '', '', '', 'PagIbig11', 'active', 'default.jpg', '2021-10-01', 'Core Group', 0, 5, 5, 3),
('23-6668', 'Surname4', 'Name4', 'MiddleName4', '', '2002-12-24', 22, 'Married', 'Female', 'Permanent', 'Academic Middle-Level Administrator', '2013-08-01', 0, 9, 'Address4', 'Contact4', 'email4@example.com', 'Course4', 'Studies4', 21, 'PRC4', '2016-01-26', 'Position4', 'TIN4', 'SSS4', 'Philhealth4', '', 'active', 'default.jpg', NULL, 'Music Arts Physical Education and Health', 5, 0, 5, 2),
('25-6626', 'Surname44', 'Name44', '', 'III', '1987-07-13', 37, 'Married', 'Female', 'Substitute', 'Non-Academic Middle-Level Administrator', '1980-04-19', 0, 44, 'Address44', 'Contact44', 'email44@example.com', 'Course44', 'Studies44', 44, 'PRC44', '2003-11-08', 'Position44', 'TIN44', '', '', 'PagIbig44', 'active', 'default.jpg', '1971-10-28', 'Mathematics', 10, 5, 15, 0),
('27-8593', 'Surname45', 'Name45', 'MiddleName45', 'III', '2010-03-05', 15, 'Married', 'Male', 'Part-time', 'Academic Middle-Level Administrator', '1993-11-06', 0, 29, 'Address45', 'Contact45', 'email45@example.com', 'Course45', '', 112, 'PRC45', '1983-11-21', 'Position45', 'TIN45', '', '', 'PagIbig45', 'active', 'default.jpg', '1984-04-04', 'Auxilliary', 5, 10, 15, 2),
('29-6722', 'Surname42', 'Name42', '', 'III', '2009-05-28', 15, 'Married', 'Female', 'Probationary', 'Rank and File (Staff)', '1978-01-04', 0, 44, 'Address42', 'Contact42', 'email42@example.com', 'Course42', 'Studies42', 13, 'PRC42', '2022-12-25', 'Position42', '', '', 'Philhealth42', '', 'active', 'default.jpg', '1982-06-01', 'Christian Living Education', 5, 5, 10, 3),
('29-8647', 'Surname42', 'Name42', 'MiddleName42', 'Jr.', '1993-06-26', 31, 'Widowed', 'Female', 'Probationary', 'Academic Middle-Level Administrator', '2021-01-24', 0, 2, 'Address42', 'Contact42', 'email42@example.com', 'Course42', '', 59, 'PRC42', '1974-07-25', 'Position42', 'TIN42', 'SSS42', 'Philhealth42', 'PagIbig42', 'active', 'default.jpg', NULL, 'Christian Living Education', 10, 5, 15, 2),
('29-9162', 'Surname25', 'Name25', 'MiddleName25', '', '1971-03-14', 53, 'Widowed', 'Male', 'Part-time', 'Rank and File (Staff)', '2010-11-07', 0, 13, 'Address25', 'Contact25', 'email25@example.com', 'Course25', 'Studies25', 8, 'PRC25', '1991-11-15', 'Position25', 'TIN25', 'SSS25', 'Philhealth25', '', 'active', 'default.jpg', '1974-06-28', 'Christian Living Education', 5, 5, 10, 1),
('30-2921', 'Surname40', 'Name40', '', '', '2020-12-21', 4, 'Single', 'Male', 'Substitute', 'Rank and File (Faculty)', '1988-03-15', 0, 34, 'Address40', 'Contact40', 'email40@example.com', 'Course40', 'Studies40', 132, 'PRC40', '1993-05-07', 'Position40', 'TIN40', '', '', '', 'active', 'default.jpg', '1980-04-11', 'Music Arts Physical Education and Health', 10, 5, 15, 2),
('30-3597', 'Surname31', 'Name31', '', '', '1981-07-11', 43, 'Widowed', 'Male', 'Part-time', 'Top Management', '1977-03-05', 0, 43, 'Address31', 'Contact31', 'email31@example.com', 'Course31', 'Studies31', 120, 'PRC31', '2011-06-19', 'Position31', '', '', 'Philhealth31', '', 'active', 'default.jpg', '2020-08-13', 'Science', 0, 10, 10, 5),
('31-5823', 'Surname35', 'Name35', '', 'Jr.', '2007-03-13', 17, 'Single', 'Female', 'Part-time', 'Top Management', '2012-04-24', 0, 11, 'Address35', 'Contact35', 'email35@example.com', 'Course35', '', 20, 'PRC35', '1989-11-23', 'Position35', '', 'SSS35', 'Philhealth35', 'PagIbig35', 'active', 'default.jpg', '2022-10-18', 'Mathematics', 5, 10, 15, 1),
('32-1894', 'Surname28', 'Name28', 'MiddleName28', '', '1972-02-12', 53, 'Single', 'Female', 'Part-time', 'Rank and File (Staff)', '1992-08-17', 0, 27, 'Address28', 'Contact28', 'email28@example.com', 'Course28', '', 103, 'PRC28', '1970-12-06', 'Position28', 'TIN28', '', '', '', 'active', 'default.jpg', '2021-02-01', 'Auxilliary', 0, 5, 5, 5),
('32-2425', 'Surname12', 'Name12', 'MiddleName12', '', '1982-12-26', 42, 'Married', 'Male', 'Substitute', 'Non-Academic Middle-Level Administrator', '1997-07-05', 0, 24, 'Address12', 'Contact12', 'email12@example.com', 'Course12', '', 47, 'PRC12', '2001-07-01', 'Position12', '', '', '', 'PagIbig12', 'active', 'default.jpg', '1993-08-28', 'Araling Panlipunan', 5, 5, 10, 3),
('32-6289', 'Surname2', 'Name2', 'MiddleName2', 'Sr.', '2021-12-27', 3, 'Single', 'Female', 'Permanent', 'Rank and File (Faculty)', '1975-02-18', 0, 50, 'Address2', 'Contact2', 'email2@example.com', 'Course2', 'Studies2', 19, 'PRC2', '2019-07-05', 'Position2', '', 'SSS2', 'Philhealth2', '', 'active', 'default.jpg', '2007-02-21', 'Araling Panlipunan', 0, 0, 0, 0),
('34-3116', 'Surname24', 'Name24', 'MiddleName24', '', '1989-07-12', 35, 'Widowed', 'Male', 'Substitute', 'Academic Middle-Level Administrator', '2018-06-06', 0, 6, 'Address24', 'Contact24', 'email24@example.com', 'Course24', '', 73, 'PRC24', '2002-01-27', 'Position24', 'TIN24', '', '', 'PagIbig24', 'resigned', 'default.jpg', '1997-04-02', 'Pre-School', 5, 10, 15, 2),
('35-8332', 'Surname22', 'Name22', 'MiddleName22', 'II', '1974-08-14', 50, 'Widowed', 'Female', 'Part-time', 'Academic Middle-Level Administrator', '1979-10-12', 0, 30, 'Address22', 'Contact22', 'email22@example.com', 'Course22', 'Studies22', 129, 'PRC22', '2002-03-03', 'Position22', 'TIN22', '', 'Philhealth22', 'PagIbig22', 'resigned', 'default.jpg', '2006-07-01', 'Music Arts Physical Education and Health', 5, 0, 5, 0),
('39-7739', 'Surname19', 'Name19', '', 'Jr.', '2006-01-08', 19, 'Married', 'Female', 'Permanent', 'Top Management', '1982-01-16', 0, 40, 'Address19', 'Contact19', 'email19@example.com', 'Course19', '', 151, 'PRC19', '1981-02-04', 'Position19', 'TIN19', 'SSS19', '', '', 'active', 'default.jpg', '1978-09-15', 'Science', 5, 5, 10, 3),
('40-1484', 'Surname13', 'Name13', '', '', '1977-12-06', 47, 'Married', 'Female', 'Substitute', 'Religious of the Assumption', '2007-03-05', 0, 22, 'Address13', 'Contact13', 'email13@example.com', 'Course13', '', 5, 'PRC13', '2022-02-13', 'Position13', 'TIN13', '', '', '', 'resigned', 'default.jpg', '1999-05-14', 'Religious of the Assumption', 5, 0, 5, 5),
('41-8270', 'Surname6', 'Name6', '', '', '1998-05-17', 26, 'Single', 'Female', 'Probationary', 'Rank and File (Staff)', '2017-03-22', 0, 3, 'Address6', 'Contact6', 'email6@example.com', 'Course6', 'Studies6', 47, 'PRC6', '1993-02-15', 'Position6', '', 'SSS6', 'Philhealth6', '', 'active', 'default.jpg', '1986-11-13', 'Com. Arts Filipino', 5, 5, 10, 4),
('41-8710', 'Surname14', 'Name14', '', 'III', '2006-01-04', 19, 'Widowed', 'Female', 'Substitute', 'Rank and File (Faculty)', '2003-12-27', 0, 18, 'Address14', 'Contact14', 'email14@example.com', 'Course14', '', 94, 'PRC14', '2002-06-02', 'Position14', 'TIN14', 'SSS14', '', '', 'active', 'default.jpg', '2001-04-26', 'Mathematics', 0, 10, 10, 3),
('42-4663', 'Surname9', 'Name9', '', 'III', '2019-08-16', 5, 'Married', 'Female', 'Part-time', 'Non-Academic Middle-Level Administrator', '2011-04-19', 0, 10, 'Address9', 'Contact9', 'email9@example.com', 'Course9', 'Studies9', 145, 'PRC9', '2005-11-20', 'Position9', '', '', '', '', 'active', 'default.jpg', '2019-08-14', 'Science', 10, 0, 10, 3),
('43-2043', 'Surname17', 'Name17', 'MiddleName17', '', '1981-01-30', 44, 'Married', 'Female', 'Part-time', 'Religious of the Assumption', '2014-02-06', 0, 9, 'Address17', 'Contact17', 'email17@example.com', 'Course17', 'Studies17', 142, 'PRC17', '1979-01-28', 'Position17', 'TIN17', '', '', '', 'active', 'default.jpg', '2011-07-26', 'Auxilliary', 5, 0, 5, 2),
('43-8714', 'Surname46', 'Name46', '', '', '1993-07-09', 31, 'Married', 'Male', 'Probationary', 'Religious of the Assumption', '1973-03-24', 0, 51, 'Address46', 'Contact46', 'email46@example.com', 'Course46', '', 24, 'PRC46', '2009-09-20', 'Position46', 'TIN46', 'SSS46', '', '', 'active', 'default.jpg', '1980-05-19', 'Technology and Livelihood', 10, 5, 15, 0),
('44-7879', 'Surname26', 'Name26', '', '', '1982-09-12', 42, 'Single', 'Male', 'Probationary', 'Non-Academic Middle-Level Administrator', '2016-10-20', 0, 4, 'Address26', 'Contact26', 'email26@example.com', 'Course26', '', 5, 'PRC26', '2021-06-04', 'Position26', 'TIN26', '', '', 'PagIbig26', 'active', 'default.jpg', '1976-05-13', 'Araling Panlipunan', 5, 0, 5, 4),
('45-3825', 'Surname27', 'Name27', 'MiddleName27', '', '1997-01-14', 28, 'Married', 'Female', 'Probationary', 'Rank and File (Faculty)', '1973-05-10', 0, 47, 'Address27', 'Contact27', 'email27@example.com', 'Course27', 'Studies27', 20, 'PRC27', '2006-07-30', 'Position27', '', '', '', 'PagIbig27', 'active', 'default.jpg', '2019-06-08', 'Auxilliary', 5, 5, 10, 4),
('45-9978', 'Surname37', 'Name37', '', 'Jr.', '2014-12-20', 10, 'Single', 'Male', 'Probationary', 'Rank and File (Staff)', '1992-08-23', 0, 0, 'Address37', 'Contact37', 'email37@example.com', 'Course37', 'Studies37', 176, 'PRC37', '2016-02-26', 'Position37', 'TIN37', 'SSS37', 'Philhealth37', '', 'resigned', 'default.jpg', '1978-01-23', 'Com. Arts English', 5, 5, 10, 1),
('46-4427', 'Surname9', 'Name9', '', '', '2010-01-04', 15, 'Married', 'Female', 'Probationary', 'Non-Academic Middle-Level Administrator', '1998-06-30', 0, 26, 'Address9', 'Contact9', 'email9@example.com', 'Course9', 'Studies9', 197, 'PRC9', '2022-08-27', 'Position9', '', 'SSS9', '', '', 'active', 'default.jpg', '1970-12-07', 'Non - Teaching Staff', 0, 0, 0, 0),
('46-4768', 'Surname49', 'Name49', '', '', '2021-06-11', 3, 'Married', 'Female', 'Substitute', 'Academic Middle-Level Administrator', '1988-10-01', 0, 33, 'Address49', 'Contact49', 'email49@example.com', 'Course49', 'Studies49', 109, 'PRC49', '1970-02-14', 'Position49', 'TIN49', 'SSS49', '', 'PagIbig49', 'active', 'default.jpg', '1984-08-15', 'Com. Arts English', 0, 5, 5, 3),
('46-8424', 'Surname37', 'Name37', 'MiddleName37', '', '1983-09-07', 41, 'Widowed', 'Male', 'Part-time', 'Non-Academic Middle-Level Administrator', '2011-01-24', 0, 11, 'Address37', 'Contact37', 'email37@example.com', 'Course37', 'Studies37', 98, 'PRC37', '2019-05-20', 'Position37', '', '', '', 'PagIbig37', 'active', 'default.jpg', '2018-04-28', 'Mathematics', 0, 0, 0, 3),
('48-5178', 'Surname16', 'Name16', 'MiddleName16', '', '1970-03-22', 54, 'Widowed', 'Female', 'Permanent', 'Auxiliary', '1992-04-27', 0, 27, 'Address16', 'Contact16', 'email16@example.com', 'Course16', '', 47, 'PRC16', '1970-05-25', 'Position16', '', 'SSS16', '', '', 'active', 'default.jpg', '2020-04-20', 'Core Group', 10, 5, 15, 5),
('48-8949', 'Surname19', 'Name19', '', '', '1970-05-31', 54, 'Married', 'Female', 'Part-time', 'Top Management', '1989-09-25', 0, 28, 'Address19', 'Contact19', 'email19@example.com', 'Course19', '', 73, 'PRC19', '2010-08-03', 'Position19', 'TIN19', 'SSS19', 'Philhealth19', '', 'resigned', 'default.jpg', '2011-05-31', 'Non - Teaching Staff', 5, 0, 5, 2),
('49-5476', 'Surname29', 'Name29', 'MiddleName29', '', '1975-08-14', 49, 'Single', 'Female', 'Substitute', 'Non-Academic Middle-Level Administrator', '2021-11-19', 0, -2, 'Address29', 'Contact29', 'email29@example.com', 'Course29', 'Studies29', 153, 'PRC29', '1973-05-16', 'Position29', '', '', '', '', 'active', 'default.jpg', '2001-04-26', 'Science', 10, 5, 15, 5),
('51-5129', 'Surname38', 'Name38', '', '', '2009-09-17', 15, 'Single', 'Male', 'Probationary', 'Auxiliary', '1973-07-05', 0, 46, 'Address38', 'Contact38', 'email38@example.com', 'Course38', '', 80, 'PRC38', '1999-09-10', 'Position38', 'TIN38', '', '', 'PagIbig38', 'active', 'default.jpg', '1992-10-02', 'Mathematics', 0, 5, 5, 5),
('54-4973', 'Surname18', 'Name18', '', '', '2005-08-10', 19, 'Widowed', 'Male', 'Permanent', 'Rank and File (Faculty)', '1996-12-24', 0, 25, 'Address18', 'Contact18', 'email18@example.com', 'Course18', 'Studies18', 185, 'PRC18', '2002-07-21', 'Position18', 'TIN18', 'SSS18', 'Philhealth18', '', 'active', 'default.jpg', '2011-03-25', 'Com. Arts Filipino', 10, 5, 15, 3),
('56-3440', 'Surname30', 'Name30', 'MiddleName30', '', '1999-05-10', 25, 'Widowed', 'Male', 'Permanent', 'Non-Academic Middle-Level Administrator', '1977-09-24', 0, 42, 'Address30', 'Contact30', 'email30@example.com', 'Course30', 'Studies30', 55, 'PRC30', '2020-09-11', 'Position30', 'TIN30', '', '', 'PagIbig30', 'active', 'default.jpg', '1974-03-12', 'Mathematics', 10, 5, 15, 5),
('57-5580', 'Surname29', 'Name29', 'MiddleName29', '', '1974-05-19', 50, 'Single', 'Female', 'Substitute', 'Non-Academic Middle-Level Administrator', '1999-03-26', 0, 25, 'Address29', 'Contact29', 'email29@example.com', 'Course29', 'Studies29', 200, 'PRC29', '2005-05-06', 'Position29', '', 'SSS29', 'Philhealth29', '', 'active', 'default.jpg', '1997-09-28', 'Christian Living Education', 10, 0, 10, 0),
('57-8112', 'Surname22', 'Name22', '', '', '2008-05-07', 16, 'Single', 'Female', 'Probationary', 'Religious of the Assumption', '1970-11-25', 0, 54, 'Address22', 'Contact22', 'email22@example.com', 'Course22', 'Studies22', 162, 'PRC22', '2011-05-06', 'Position22', '', 'SSS22', '', 'PagIbig22', 'active', 'default.jpg', '1982-07-18', 'Science', 5, 5, 10, 0),
('58-3278', 'Surname38', 'Name38', '', '', '2009-07-07', 15, 'Married', 'Female', 'Permanent', 'Top Management', '2018-09-08', 0, 3, 'Address38', 'Contact38', 'email38@example.com', 'Course38', '', 47, 'PRC38', '1990-09-10', 'Position38', '', '', 'Philhealth38', 'PagIbig38', 'active', 'default.jpg', '1980-02-07', 'Christian Living Education', 0, 5, 5, 3),
('58-5994', 'Surname21', 'Name21', '', '', '2010-12-17', 14, 'Single', 'Male', 'Substitute', 'Auxiliary', '1985-05-27', 0, 37, 'Address21', 'Contact21', 'email21@example.com', 'Course21', 'Studies21', 175, 'PRC21', '2019-04-25', 'Position21', 'TIN21', 'SSS21', '', '', 'active', 'default.jpg', '1996-10-22', 'Com. Arts Filipino', 0, 0, 0, 2),
('58-6641', 'Surname33', 'Name33', 'MiddleName33', '', '1991-08-15', 33, 'Single', 'Male', 'Permanent', 'Religious of the Assumption', '2008-04-02', 0, 11, 'Address33', 'Contact33', 'email33@example.com', 'Course33', 'Studies33', 200, 'PRC33', '2013-02-13', 'Position33', '', 'SSS33', '', 'PagIbig33', 'active', 'default.jpg', '1972-08-06', 'Religious of the Assumption', 5, 0, 5, 5),
('58-6643', 'Surname21', 'Name21', 'MiddleName21', '', '1983-03-26', 41, 'Widowed', 'Female', 'Permanent', 'Rank and File (Faculty)', '1999-10-19', 0, 24, 'Address21', 'Contact21', 'email21@example.com', 'Course21', 'Studies21', 171, 'PRC21', '1974-02-17', 'Position21', '', '', 'Philhealth21', '', 'active', 'default.jpg', '1972-03-25', 'Religious of the Assumption', 0, 5, 5, 1),
('58-7740', 'Surname7', 'Name7', '', '', '1982-06-24', 42, 'Widowed', 'Female', 'Substitute', 'Academic Middle-Level Administrator', '1989-10-30', 0, 31, 'Address7', 'Contact7', 'email7@example.com', 'Course7', 'Studies7', 29, 'PRC7', '2008-03-08', 'Position7', '', '', 'Philhealth7', 'PagIbig7', 'active', 'default.jpg', '2022-02-12', 'Mathematics', 5, 10, 15, 4),
('61-4496', 'Surname16', 'Name16', 'MiddleName16', 'Sr.', '1971-09-18', 53, 'Widowed', 'Female', 'Substitute', 'Rank and File (Staff)', '2021-10-31', 0, 1, 'Address16', 'Contact16', 'email16@example.com', 'Course16', 'Studies16', 183, 'PRC16', '1986-02-15', 'Position16', '', 'SSS16', '', 'PagIbig16', 'active', 'default.jpg', '1997-03-02', 'Mathematics', 10, 10, 20, 2),
('61-8067', 'Surname2', 'Name2', 'MiddleName2', '', '1980-11-02', 44, 'Widowed', 'Male', 'Permanent', 'Rank and File (Faculty)', '1973-07-23', 0, 47, 'Address2', 'Contact2', 'email2@example.com', 'Course2', '', 96, 'PRC2', '1990-11-11', 'Position2', 'TIN2', '', 'Philhealth2', '', 'active', 'default.jpg', '2005-08-18', 'Christian Living Education', 5, 10, 15, 4),
('61-9358', 'Surname46', 'Name46', '', '', '1978-02-17', 47, 'Widowed', 'Male', 'Part-time', 'Non-Academic Middle-Level Administrator', '2009-07-12', 0, 14, 'Address46', 'Contact46', 'email46@example.com', 'Course46', '', 162, 'PRC46', '2020-03-25', 'Position46', 'TIN46', 'SSS46', 'Philhealth46', '', 'active', 'default.jpg', '1976-02-27', 'Religious of the Assumption', 5, 5, 10, 1),
('62-2523', 'Surname8', 'Name8', 'MiddleName8', '', '1980-08-02', 44, 'Married', 'Male', 'Probationary', 'Religious of the Assumption', '2016-11-14', 0, 3, 'Address8', 'Contact8', 'email8@example.com', 'Course8', 'Studies8', 45, 'PRC8', '2006-01-20', 'Position8', '', 'SSS8', '', '', 'active', 'default.jpg', '1973-11-27', 'Religious of the Assumption', 5, 5, 10, 5),
('62-3758', 'Surname3', 'Name3', 'MiddleName3', '', '1994-03-17', 30, 'Married', 'Female', 'Part-time', 'Rank and File (Faculty)', '1970-10-29', 0, 53, 'Address3', 'Contact3', 'email3@example.com', 'Course3', '', 96, 'PRC3', '1999-03-04', 'Position3', 'TIN3', 'SSS3', 'Philhealth3', 'PagIbig3', 'active', 'default.jpg', '2006-03-12', 'Non - Teaching Staff', 0, 0, 0, 1),
('63-6258', 'Surname26', 'Name26', '', 'III', '1971-10-01', 53, 'Married', 'Female', 'Part-time', 'Religious of the Assumption', '1986-03-10', 0, 34, 'Address26', 'Contact26', 'email26@example.com', 'Course26', '', 147, 'PRC26', '1970-11-03', 'Position26', 'TIN26', 'SSS26', 'Philhealth26', 'PagIbig26', 'active', 'default.jpg', '2022-03-10', 'Religious of the Assumption', 10, 0, 10, 4),
('63-8223', 'Surname35', 'Name35', 'MiddleName35', '', '2001-11-22', 23, 'Married', 'Male', 'Substitute', 'Auxiliary', '1988-01-10', 0, 0, 'Address35', 'Contact35', 'email35@example.com', 'Course35', 'Studies35', 55, 'PRC35', '1984-03-07', 'Position35', '', '', '', '', 'resigned', 'default.jpg', '2014-05-26', 'Religious of the Assumption', 5, 0, 5, 3),
('63-8490', 'Surname43', 'Name43', 'MiddleName43', '', '2009-04-11', 15, 'Widowed', 'Female', 'Permanent', 'Academic Middle-Level Administrator', '2001-10-16', 0, 19, 'Address43', 'Contact43', 'email43@example.com', 'Course43', '', 102, 'PRC43', '2021-06-20', 'Position43', 'TIN43', 'SSS43', 'Philhealth43', '', 'active', 'default.jpg', '2000-11-12', 'Com. Arts English', 0, 10, 10, 4),
('63-9689', 'Surname48', 'Name48', 'MiddleName48', '', '2008-12-28', 16, 'Widowed', 'Male', 'Part-time', 'Academic Middle-Level Administrator', '1977-12-07', 0, 44, 'Address48', 'Contact48', 'email48@example.com', 'Course48', 'Studies48', 192, 'PRC48', '1978-06-26', 'Position48', '', 'SSS48', '', 'PagIbig48', 'active', 'default.jpg', '2005-09-20', 'Music Arts Physical Education and Health', 5, 0, 5, 3),
('63-9741', 'Surname11', 'Name11', 'MiddleName11', '', '1990-09-07', 34, 'Widowed', 'Male', 'Part-time', 'Rank and File (Staff)', '1993-06-30', 0, 29, 'Address11', 'Contact11', 'email11@example.com', 'Course11', '', 26, 'PRC11', '1983-02-08', 'Position11', '', '', 'Philhealth11', '', 'active', 'default.jpg', '1974-09-21', 'Pre-School', 5, 10, 15, 2),
('64-7774', 'Surname15', 'Name15', '', '', '1974-11-07', 50, 'Widowed', 'Female', 'Permanent', 'Auxiliary', '1998-03-14', 0, 21, 'Address15', 'Contact15', 'email15@example.com', 'Course15', '', 157, 'PRC15', '2020-04-07', 'Position15', 'TIN15', '', 'Philhealth15', '', 'active', 'default.jpg', '1995-03-14', 'Core Group', 0, 5, 5, 5),
('64-8019', 'Surname12', 'Name12', 'MiddleName12', '', '1970-05-29', 54, 'Widowed', 'Female', 'Substitute', 'Rank and File (Faculty)', '1970-11-14', 0, 50, 'Address12', 'Contact12', 'email12@example.com', 'Course12', '', 101, 'PRC12', '1977-07-05', 'Position12', '', '', 'Philhealth12', 'PagIbig12', 'active', 'default.jpg', '2001-11-04', 'Pre-School', 5, 0, 5, 4),
('66-1358', 'Surname45', 'Name45', '', 'II', '1985-11-07', 39, 'Widowed', 'Male', 'Probationary', 'Top Management', '2007-05-16', 0, 17, 'Address45', 'Contact45', 'email45@example.com', 'Course45', 'Studies45', 31, 'PRC45', '2007-04-16', 'Position45', 'TIN45', '', 'Philhealth45', 'PagIbig45', 'active', 'default.jpg', '2004-05-25', 'Science', 10, 5, 15, 0),
('66-4968', 'Surname33', 'Name33', 'MiddleName33', '', '1994-02-14', 31, 'Married', 'Male', 'Permanent', 'Rank and File (Staff)', '2013-01-24', 0, 0, 'Address33', 'Contact33', 'email33@example.com', 'Course33', 'Studies33', 68, 'PRC33', '1976-03-09', 'Position33', 'TIN33', 'SSS33', 'Philhealth33', '', 'resigned', 'default.jpg', '1972-08-18', 'Com. Arts Filipino', 5, 10, 15, 2),
('67-6933', 'Surname27', 'Name27', 'MiddleName27', '', '2007-06-05', 17, 'Widowed', 'Female', 'Part-time', 'Rank and File (Faculty)', '1974-03-04', 0, 49, 'Address27', 'Contact27', 'email27@example.com', 'Course27', 'Studies27', 187, 'PRC27', '1983-01-09', 'Position27', '', '', '', 'PagIbig27', 'active', 'default.jpg', '2005-10-07', 'Pre-School', 10, 5, 15, 2),
('69-9227', 'Surname15', 'Name15', '', 'Sr.', '1982-01-08', 43, 'Married', 'Male', 'Substitute', 'Non-Academic Middle-Level Administrator', '1977-04-23', 0, 47, 'Address15', 'Contact15', 'email15@example.com', 'Course15', '', 92, 'PRC15', '1986-04-04', 'Position15', '', '', '', 'PagIbig15', 'active', 'default.jpg', '2006-05-04', 'Com. Arts Filipino', 5, 10, 15, 0),
('70-2279', 'Surname18', 'Name18', 'MiddleName18', '', '1987-06-06', 37, 'Widowed', 'Male', 'Substitute', 'Rank and File (Faculty)', '1993-08-25', 0, 31, 'Address18', 'Contact18', 'email18@example.com', 'Course18', '', 94, 'PRC18', '2013-12-19', 'Position18', '', '', 'Philhealth18', 'PagIbig18', 'active', 'default.jpg', '1979-04-20', 'Com. Arts Filipino', 10, 5, 15, 0),
('72-2513', 'Surname36', 'Name36', 'MiddleName36', '', '1977-10-13', 47, 'Single', 'Male', 'Substitute', 'Religious of the Assumption', '1998-03-04', 0, 23, 'Address36', 'Contact36', 'email36@example.com', 'Course36', 'Studies36', 78, 'PRC36', '2014-08-31', 'Position36', 'TIN36', 'SSS36', '', 'PagIbig36', 'active', 'default.jpg', '1997-08-29', 'Technology and Livelihood', 0, 5, 5, 4),
('72-5487', 'Surname7', 'Name7', '', '', '1987-01-22', 38, 'Single', 'Male', 'Substitute', 'Religious of the Assumption', '1970-02-01', 0, 53, 'Address7', 'Contact7', 'email7@example.com', 'Course7', '', 171, 'PRC7', '2001-03-08', 'Position7', '', '', 'Philhealth7', '', 'active', 'default.jpg', '1984-04-12', 'Religious of the Assumption', 0, 5, 5, 2),
('73-6586', 'Surname31', 'Name31', '', '', '2019-12-31', 5, 'Widowed', 'Male', 'Permanent', 'Rank and File (Faculty)', '2001-05-09', 0, 21, 'Address31', 'Contact31', 'email31@example.com', 'Course31', 'Studies31', 82, 'PRC31', '1984-01-03', 'Position31', 'TIN31', 'SSS31', '', '', 'resigned', 'default.jpg', '1995-09-20', 'Auxilliary', 5, 5, 10, 4),
('75-1083', 'Surname20', 'Name20', '', '', '1996-03-30', 28, 'Widowed', 'Male', 'Part-time', 'Non-Academic Middle-Level Administrator', '1983-11-14', 0, 39, 'Address20', 'Contact20', 'email20@example.com', 'Course20', '', 171, 'PRC20', '2018-06-06', 'Position20', '', 'SSS20', '', 'PagIbig20', 'active', 'default.jpg', '1971-06-19', 'Christian Living Education', 5, 5, 10, 2),
('75-2952', 'Surname23', 'Name23', 'MiddleName23', 'Sr.', '1974-09-03', 50, 'Single', 'Female', 'Permanent', 'Rank and File (Staff)', '2013-03-24', 0, 8, 'Address23', 'Contact23', 'email23@example.com', 'Course23', '', 133, 'PRC23', '2018-04-24', 'Position23', '', '', 'Philhealth23', '', 'active', 'default.jpg', '1972-02-06', 'Technology and Livelihood', 5, 0, 5, 3),
('75-7730', 'Surname28', 'Name28', '', '', '1996-05-30', 28, 'Married', 'Male', 'Part-time', 'Rank and File (Staff)', '1997-07-06', 0, 27, 'Address28', 'Contact28', 'email28@example.com', 'Course28', '', 62, 'PRC28', '2004-08-16', 'Position28', '', '', '', '', 'active', 'default.jpg', '1979-12-15', 'Non - Teaching Staff', 5, 0, 5, 0),
('76-2557', 'Surname14', 'Name14', '', 'Sr.', '1995-04-12', 29, 'Single', 'Male', 'Permanent', 'Academic Middle-Level Administrator', '1978-04-24', 0, 43, 'Address14', 'Contact14', 'email14@example.com', 'Course14', 'Studies14', 69, 'PRC14', '2021-01-05', 'Position14', 'TIN14', 'SSS14', 'Philhealth14', '', 'active', 'default.jpg', '2009-09-05', 'Music Arts Physical Education and Health', 10, 10, 20, 3),
('77-6399', 'Surname24', 'Name24', 'MiddleName24', 'II', '2012-05-31', 12, 'Widowed', 'Male', 'Part-time', 'Academic Middle-Level Administrator', '1997-02-05', 0, 28, 'Address24', 'Contact24', 'email24@example.com', 'Course24', 'Studies24', 92, 'PRC24', '1973-03-16', 'Position24', '', '', 'Philhealth24', 'PagIbig24', 'active', 'default.jpg', '1977-12-18', 'Araling Panlipunan', 5, 0, 5, 0),
('79-4036', 'Surname5', 'Name5', '', '', '1970-09-17', 54, 'Single', 'Male', 'Probationary', 'Religious of the Assumption', '1987-11-23', 0, 13, 'Address5', 'Contact5', 'email5@example.com', 'Course5', '', 63, 'PRC5', '1986-05-17', 'Position5', 'TIN5', '', '', '', 'resigned', 'default.jpg', '2000-10-10', 'Religious of the Assumption', 10, 10, 20, 1),
('79-4147', 'Surname34', 'Name34', '', '', '2007-08-04', 17, 'Married', 'Male', 'Part-time', 'Top Management', '1986-12-14', 0, 33, 'Address34', 'Contact34', 'email34@example.com', 'Course34', '', 13, 'PRC34', '1983-09-05', 'Position34', 'TIN34', '', '', '', 'active', 'default.jpg', '2021-10-16', 'Auxilliary', 5, 10, 15, 5),
('79-6416', 'Surname47', 'Name47', 'MiddleName47', '', '2015-01-09', 10, 'Widowed', 'Male', 'Substitute', 'Auxiliary', '1977-11-12', 0, 42, 'Address47', 'Contact47', 'email47@example.com', 'Course47', 'Studies47', 159, 'PRC47', '2014-10-19', 'Position47', '', '', '', 'PagIbig47', 'active', 'default.jpg', '2012-09-06', 'Religious of the Assumption', 5, 0, 5, 5),
('81-7219', 'Surname5', 'Name5', '', '', '1974-01-03', 51, 'Single', 'Female', 'Substitute', 'Rank and File (Faculty)', '2010-10-12', 0, 12, 'Address5', 'Contact5', 'email5@example.com', 'Course5', '', 154, 'PRC5', '1980-09-28', 'Position5', '', 'SSS5', '', 'PagIbig5', 'active', 'default.jpg', '1982-09-29', 'Technology and Livelihood', 5, 5, 10, 2),
('82-3242', 'Surname50', 'Name50', '', '', '2016-03-06', 9, 'Single', 'Male', 'Part-time', 'Top Management', '2010-01-29', 0, 11, 'Address50', 'Contact50', 'email50@example.com', 'Course50', '', 124, 'PRC50', '1995-10-28', 'Position50', 'TIN50', '', '', '', 'active', 'default.jpg', '2006-09-15', 'Religious of the Assumption', 5, 5, 10, 4),
('83-6659', 'Surname39', 'Name39', '', 'Jr.', '2015-08-25', 9, 'Single', 'Female', 'Permanent', 'Auxiliary', '1983-09-08', 0, 37, 'Address39', 'Contact39', 'email39@example.com', 'Course39', '', 191, 'PRC39', '1978-04-06', 'Position39', '', '', 'Philhealth39', 'PagIbig39', 'active', 'default.jpg', '2016-05-11', 'Christian Living Education', 10, 10, 20, 4),
('84-1166', 'Surname40', 'Name40', 'MiddleName40', '', '1973-12-18', 51, 'Single', 'Male', 'Permanent', 'Auxiliary', '2021-12-18', 0, 8, 'Address40', 'Contact40', 'email40@example.com', 'Course40', '', 56, 'PRC40', '1994-10-27', 'Position40', '', 'SSS40', '', '', 'resigned', 'default.jpg', '2022-09-20', 'Religious of the Assumption', 0, 5, 5, 0),
('84-4560', 'Surname50', 'Name50', 'MiddleName50', '', '1989-06-06', 35, 'Single', 'Female', 'Part-time', 'Rank and File (Faculty)', '1989-01-23', 0, 15, 'Address50', 'Contact50', 'email50@example.com', 'Course50', '', 193, 'PRC50', '2014-08-17', 'Position50', 'TIN50', '', 'Philhealth50', '', 'resigned', 'default.jpg', '1985-03-05', 'Com. Arts Filipino', 10, 0, 10, 0),
('86-1097', 'Surname32', 'Name32', 'MiddleName32', '', '1974-10-22', 50, 'Married', 'Male', 'Part-time', 'Religious of the Assumption', '2023-02-13', 0, -2, 'Address32', 'Contact32', 'email32@example.com', 'Course32', '', 149, 'PRC32', '2009-11-24', 'Position32', '', 'SSS32', '', '', 'active', 'default.jpg', '1996-03-09', 'Religious of the Assumption', 10, 10, 20, 4),
('88-8069', 'Surname1', 'Name1', 'MiddleName1', 'II', '1986-07-30', 38, 'Married', 'Female', 'Substitute', 'Academic Middle-Level Administrator', '1989-09-29', 0, 31, 'Address1', 'Contact1', 'email1@example.com', 'Course1', 'Studies1', 147, 'PRC1', '1985-01-06', 'Position1', 'TIN1', 'SSS1', '', '', 'active', 'default.jpg', '1972-04-30', 'Technology and Livelihood', 5, 5, 10, 4),
('89-2020', 'Surname25', 'Name25', 'MiddleName25', '', '1991-11-11', 33, 'Single', 'Female', 'Probationary', 'Rank and File (Staff)', '1991-10-16', 0, 32, 'Address25', 'Contact25', 'email25@example.com', 'Course25', '', 22, 'PRC25', '1989-04-06', 'Position25', '', '', '', '', 'active', 'default.jpg', '1971-08-18', 'Science', 0, 0, 0, 1),
('89-8514', 'Surname4', 'Name4', '', '', '2006-08-15', 18, 'Married', 'Male', 'Substitute', 'Rank and File (Staff)', '2005-08-05', 0, 19, 'Address4', 'Contact4', 'email4@example.com', 'Course4', '', 130, 'PRC4', '2002-08-08', 'Position4', '', '', '', 'PagIbig4', 'active', 'default.jpg', '1994-09-30', 'Core Group', 5, 5, 10, 0),
('90-8882', 'Surname49', 'Name49', 'MiddleName49', 'Sr.', '1970-06-22', 54, 'Single', 'Male', 'Probationary', 'Religious of the Assumption', '1978-11-13', 0, 42, 'Address49', 'Contact49', 'email49@example.com', 'Course49', '', 64, 'PRC49', '1975-07-24', 'Position49', '', '', 'Philhealth49', '', 'active', 'default.jpg', '1987-04-12', 'Religious of the Assumption', 5, 5, 10, 4),
('93-5034', 'Surname1', 'Name1', '', '', '1988-01-25', 37, 'Widowed', 'Male', 'Part-time', 'Academic Middle-Level Administrator', '2004-12-21', 0, 19, 'Address1', 'Contact1', 'email1@example.com', 'Course1', '', 66, 'PRC1', '2009-12-19', 'Position1', 'TIN1', '', 'Philhealth1', '', 'active', 'default.jpg', '2003-04-10', 'Technology and Livelihood', 5, 5, 10, 1),
('93-5615', 'Surname41', 'Name41', 'MiddleName41', '', '2003-07-12', 21, 'Single', 'Female', 'Substitute', 'Rank and File (Staff)', '1999-03-22', 0, 22, 'Address41', 'Contact41', 'email41@example.com', 'Course41', 'Studies41', 43, 'PRC41', '1987-01-09', 'Position41', 'TIN41', '', 'Philhealth41', 'PagIbig41', 'resigned', 'default.jpg', '1978-10-24', 'Science', 0, 10, 10, 0),
('93-8161', 'Surname8', 'Name8', 'MiddleName8', '', '1974-07-12', 50, 'Single', 'Female', 'Part-time', 'Religious of the Assumption', '2007-10-31', 0, 16, 'Address8', 'Contact8', 'email8@example.com', 'Course8', 'Studies8', 190, 'PRC8', '1984-04-01', 'Position8', 'TIN8', '', '', 'PagIbig8', 'active', 'default.jpg', '1980-03-14', 'Pre-School', 5, 0, 5, 1),
('93-9774', 'Surname23', 'Name23', '', '', '2020-10-16', 4, 'Widowed', 'Male', 'Permanent', 'Rank and File (Staff)', '2004-07-21', 0, 18, 'Address23', 'Contact23', 'email23@example.com', 'Course23', 'Studies23', 50, 'PRC23', '1973-01-13', 'Position23', 'TIN23', '', 'Philhealth23', 'PagIbig23', 'active', 'default.jpg', '1987-03-21', 'Com. Arts Filipino', 10, 5, 15, 2),
('95-1132', 'Surname20', 'Name20', '', '', '1995-04-04', 29, 'Widowed', 'Female', 'Probationary', 'Non-Academic Middle-Level Administrator', '2001-01-28', 0, 21, 'Address20', 'Contact20', 'email20@example.com', 'Course20', 'Studies20', 116, 'PRC20', '2014-06-15', 'Position20', 'TIN20', '', '', '', 'active', 'default.jpg', '2011-08-26', 'Araling Panlipunan', 5, 0, 5, 3),
('95-7316', 'Surname43', 'Name43', '', '', '2015-06-10', 9, 'Single', 'Male', 'Substitute', 'Academic Middle-Level Administrator', '1987-08-04', 0, 32, 'Address43', 'Contact43', 'email43@example.com', 'Course43', 'Studies43', 1, 'PRC43', '2007-03-28', 'Position43', 'TIN43', '', 'Philhealth43', 'PagIbig43', 'active', 'default.jpg', '2017-07-01', 'Christian Living Education', 0, 10, 10, 5),
('95-8797', 'Surname47', 'Name47', 'MiddleName47', '', '1980-02-23', 45, 'Single', 'Female', 'Probationary', 'Auxiliary', '2001-07-20', 0, 20, 'Address47', 'Contact47', 'email47@example.com', 'Course47', '', 83, 'PRC47', '2005-06-15', 'Position47', 'TIN47', 'SSS47', '', 'PagIbig47', 'active', 'default.jpg', '1988-02-18', 'Mathematics', 5, 5, 10, 3),
('99-8526', 'Surname41', 'Name41', '', '', '2011-05-04', 13, 'Single', 'Male', 'Permanent', 'Academic Middle-Level Administrator', '2004-01-10', 0, 8, 'Address41', 'Contact41', 'email41@example.com', 'Course41', '', 97, 'PRC41', '1992-11-16', 'Position41', '', '', '', 'PagIbig41', 'resigned', 'default.jpg', '2017-02-10', 'Non - Teaching Staff', 0, 10, 10, 4),
('RA-2021-1', 'asdf', 'asdf', 'asd', 'as', '2023-09-14', 1, 'Married', 'Male', 'Permanent', 'Religious of the Assumption', '2021-06-18', 0, 3, '', '', '', '', '', 0, '', '0000-00-00', '', '', '123412341234', '12341234123412', '123412341234', 'active', '1695024183_download.jpg', NULL, 'Religious of the Assumption', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `history_id` int(50) NOT NULL,
  `admin_id` varchar(50) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `type` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_name` varchar(150) NOT NULL,
  `employee_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`history_id`, `admin_id`, `employee_id`, `type`, `timestamp`, `admin_name`, `employee_name`) VALUES
(744, '000000', '000000', 'Updated Access Level', '2023-09-03 10:23:55', 'Simbulan, Ian Angelo M.', 'Unknown'),
(745, '000000', '000000', 'Updated Status', '2023-09-03 10:23:55', 'Simbulan, Ian Angelo M.', 'Unknown'),
(746, '000000', '011111', 'Updated Access Level', '2023-09-03 10:23:55', 'Simbulan, Ian Angelo M.', 'Unknown'),
(747, '000000', '011111', 'Updated Status', '2023-09-03 10:23:55', 'Simbulan, Ian Angelo M.', 'Unknown'),
(748, '000000', '011111', 'Updated Access Level', '2023-09-03 10:26:24', 'Simbulan, Ian Angelo M.', 'Unknown'),
(749, '000000', '011111', 'Updated Status', '2023-09-03 10:26:33', 'Simbulan, Ian Angelo M.', 'Unknown'),
(750, '000000', '011111', 'Updated Access Level', '2023-09-03 10:30:25', 'Simbulan, Ian Angelo M.', 'Unknown'),
(751, '000000', '011111', 'Updated Status', '2023-09-03 10:30:25', 'Simbulan, Ian Angelo M.', 'Unknown'),
(752, '000000', '000000', 'Updated Access Level', '2023-09-03 10:30:37', 'Simbulan, Ian Angelo M.', 'Unknown'),
(753, '000000', '000000', 'Updated Status', '2023-09-03 10:30:37', 'Simbulan, Ian Angelo M.', 'Unknown'),
(754, '000000', '011111', 'Updated Access Level', '2023-09-03 10:30:37', 'Simbulan, Ian Angelo M.', 'Unknown'),
(755, '000000', '011111', 'Updated Status', '2023-09-03 10:30:37', 'Simbulan, Ian Angelo M.', 'Unknown'),
(756, '000000', '011111', 'Updated Access Level', '2023-09-03 10:30:50', 'Simbulan, Ian Angelo M.', 'Unknown'),
(757, '000000', '011111', 'Updated Status', '2023-09-03 10:30:50', 'Simbulan, Ian Angelo M.', 'Unknown'),
(758, '000000', '000000', 'Updated Access Level', '2023-09-03 10:30:50', 'Simbulan, Ian Angelo M.', 'Unknown'),
(759, '000000', '000000', 'Updated Status', '2023-09-03 10:30:50', 'Simbulan, Ian Angelo M.', 'Unknown'),
(760, '000000', '71', 'Employee Added', '2023-09-03 10:48:45', 'Simbulan, Ian Angelo M.', '71, 71 7.'),
(761, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 10:59:51', 'Simbulan, Ian Angelo M.', 'Unknown'),
(762, '000000', '000000', 'Information Updated', '2023-09-03 11:00:31', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(763, '000000', '011111', 'Information Updated', '2023-09-03 11:01:15', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(764, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:01:22', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(765, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:04:06', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(766, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:07:41', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(767, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:08:50', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(768, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:08:54', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(769, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:09:13', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(770, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:09:29', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(771, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:13:41', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(772, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:14:17', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(773, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:15:47', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(774, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:16:28', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(775, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:18:28', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(776, '000000', '011111', 'Reset Employee Username & Password', '2023-09-03 11:19:03', 'Simbulan, Ian Angelo M.', 'Sample, Sample S.'),
(777, '000000', '70', 'Information Updated', '2023-09-03 11:28:14', 'Simbulan, Ian Angelo M.', '70, '),
(778, '000000', '71', 'Information Updated', '2023-09-03 11:28:22', 'Simbulan, Ian Angelo M.', '71, 71 7.'),
(779, '000000', '70', 'Updated Status', '2023-09-03 11:32:35', 'Simbulan, Ian Angelo M.', '70, '),
(780, '000000', '71', 'Reset Employee Username & Password', '2023-09-03 11:35:58', 'Simbulan, Ian Angelo M.', '71, 71 7.'),
(781, '000000', '70', 'Reset Employee Username & Password', '2023-09-03 11:35:58', 'Simbulan, Ian Angelo M.', '70, '),
(782, '000000', '70', 'Information Updated', '2023-09-03 11:50:26', 'Simbulan, Ian Angelo M.', '70, '),
(783, '000000', '72', 'Employee Added', '2023-09-03 11:51:11', 'Simbulan, Ian Angelo M.', '72, 72 7.'),
(784, '000000', '722', 'Information Updated', '2023-09-03 11:51:29', 'Simbulan, Ian Angelo M.', '72, 72 7.'),
(785, '000000', 'asdf', 'Information Updated', '2023-09-03 11:51:43', 'Simbulan, Ian Angelo M.', '72, 72 7.'),
(786, '000000', '722', 'Information Updated', '2023-09-03 11:52:33', 'Simbulan, Ian Angelo M.', '72, 72 7.'),
(787, '000000', '7613', 'Information Updated', '2023-09-03 11:53:01', 'Simbulan, Ian Angelo M.', '72, 72 7.'),
(788, '000000', '7613', 'Employee Deleted', '2023-09-03 11:54:33', 'Simbulan, Ian Angelo M.', 'Unknown'),
(789, '000000', '72', 'Employee Added', '2023-09-03 11:57:24', 'Simbulan, Ian Angelo M.', 's, Sample Sample s.'),
(790, '000000', '722', 'Information Updated', '2023-09-03 11:57:36', 'Simbulan, Ian Angelo M.', 's, Sample Sample s.'),
(791, '000000', 'asdf', 'Information Updated', '2023-09-03 11:57:52', 'Simbulan, Ian Angelo M.', 's, Sample Sample s.'),
(792, '000000', '722', 'Information Updated', '2023-09-03 12:00:03', 'Simbulan, Ian Angelo M.', 's, Sample Sample s.'),
(793, '000000', '722', 'Employee Deleted', '2023-09-03 12:01:35', 'Simbulan, Ian Angelo M.', 'Unknown'),
(794, '000000', '72', 'Employee Added', '2023-09-03 12:01:51', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(795, '000000', 'asdf', 'Information Updated', '2023-09-03 12:02:00', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(796, '000000', '72', 'Information Updated', '2023-09-03 12:02:16', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(797, '000000', '72', 'Information Updated', '2023-09-03 12:05:27', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(798, '000000', '72', 'Information Updated', '2023-09-03 12:05:37', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(799, '000000', '722', 'Information Updated', '2023-09-03 12:05:45', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(800, '000000', '722', 'Employee Deleted', '2023-09-03 12:07:07', 'Simbulan, Ian Angelo M.', 'Unknown'),
(801, '000000', '72', 'Employee Added', '2023-09-03 12:07:22', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(802, '000000', '72', 'Information Updated', '2023-09-03 12:07:31', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(803, '000000', '72', 'Information Updated', '2023-09-03 12:07:41', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(804, '000000', '72', 'Information Updated', '2023-09-03 12:08:23', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(805, '000000', '72', 'Information Updated', '2023-09-03 12:08:30', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(806, '000000', '72', 'Updated Access Level', '2023-09-03 13:36:57', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(807, '000000', '72', 'Updated Access Level', '2023-09-03 13:38:04', 'Simbulan, Ian Angelo M.', '72, Sample Sample'),
(808, '000000', '1234', 'Information Updated', '2023-09-03 15:51:07', 'Simbulan, Ian Angelo M.', 'Simbulan, Ian Angelo M.'),
(809, '000000', '72', 'Employee Deleted', '2023-09-03 15:51:20', 'Simbulan, Ian Angelo M.', 'Unknown'),
(810, '1234', 'Settings', 'Username Update', '2023-09-03 18:42:21', 'Simbulan, Ian Angelo M.', 'Unknown'),
(811, '1234', 'Settings', 'Username Update', '2023-09-03 18:43:09', 'Simbulan, Ian Angelo M.', 'Unknown'),
(812, '1234', 'Settings', 'Username Update', '2023-09-03 18:50:09', 'Simbulan, Ian Angelo M.', 'Unknown'),
(813, '1234', 'Settings', 'Username Update', '2023-09-03 18:50:27', 'Simbulan, Ian Angelo M.', 'Unknown'),
(814, '1234', 'Settings', 'Username Update', '2023-09-03 18:53:38', 'Simbulan, Ian Angelo M.', 'Unknown'),
(815, '1234', 'Settings', 'Username Update', '2023-09-03 18:55:09', 'Simbulan, Ian Angelo M.', 'Unknown'),
(816, '1234', 'Settings', 'Username Update', '2023-09-03 19:02:19', 'Simbulan, Ian Angelo M.', 'Unknown'),
(817, '1234', 'Settings', 'Username Update', '2023-09-03 19:03:28', 'Simbulan, Ian Angelo M.', 'Unknown'),
(818, '1234', 'Settings', 'Username Update', '2023-09-03 19:04:11', 'Simbulan, Ian Angelo M.', 'Unknown'),
(819, '1234', 'Settings', 'Username Update', '2023-09-03 19:06:40', 'Simbulan, Ian Angelo M.', 'Unknown'),
(820, '1234', 'Settings', 'Username Update', '2023-09-03 19:07:55', 'Simbulan, Ian Angelo M.', 'Unknown'),
(821, '1234', 'Settings', 'Username Update', '2023-09-03 19:10:02', 'Simbulan, Ian Angelo M.', 'Unknown'),
(822, '1234', 'Settings', 'Username Update', '2023-09-03 19:14:42', 'Simbulan, Ian Angelo M.', 'Unknown'),
(823, '1234', 'Settings', 'Username Update', '2023-09-03 19:16:30', 'Simbulan, Ian Angelo M.', 'Unknown'),
(824, '1234', 'Settings', 'Password Update', '2023-09-03 19:17:14', 'Simbulan, Ian Angelo M.', 'Unknown'),
(825, '1234', 'Settings', 'Password Update', '2023-09-03 19:18:13', 'Simbulan, Ian Angelo M.', 'Unknown'),
(826, '1234', 'Settings', 'Password Update', '2023-09-03 19:18:31', 'Simbulan, Ian Angelo M.', 'Unknown'),
(827, '1234', 'Settings', 'Password Update', '2023-09-03 19:19:09', 'Simbulan, Ian Angelo M.', 'Unknown'),
(828, '1234', 'Settings', 'Password Update', '2023-09-03 19:19:25', 'Simbulan, Ian Angelo M.', 'Unknown'),
(829, '1234', 'Settings', 'Password Update', '2023-09-03 19:20:09', 'Simbulan, Ian Angelo M.', 'Unknown'),
(830, '1234', 'Settings', 'Password Update', '2023-09-03 19:20:35', 'Simbulan, Ian Angelo M.', 'Unknown'),
(831, '1234', 'Settings', 'Password Update', '2023-09-03 19:21:18', 'Simbulan, Ian Angelo M.', 'Unknown'),
(832, '1234', 'Settings', 'Password Update', '2023-09-03 19:21:49', 'Simbulan, Ian Angelo M.', 'Unknown'),
(833, '1234', 'Settings', 'Password Update', '2023-09-03 19:22:39', 'Simbulan, Ian Angelo M.', 'Unknown'),
(834, '1234', 'Settings', 'Password Update', '2023-09-03 19:22:56', 'Simbulan, Ian Angelo M.', 'Unknown'),
(835, '1234', 'Settings', 'Username Update', '2023-09-03 19:27:00', 'Simbulan, Ian Angelo M.', 'Unknown'),
(836, '1234', 'Settings', 'Username Update', '2023-09-03 19:27:33', 'Simbulan, Ian Angelo M.', 'Unknown'),
(837, '1234', 'Settings', 'Password Update', '2023-09-03 19:28:04', 'Simbulan, Ian Angelo M.', 'Unknown'),
(838, '1234', 'Settings', 'Username Update', '2023-09-03 19:29:16', 'Simbulan, Ian Angelo M.', 'Unknown'),
(839, '1234', 'Settings', 'Password Update', '2023-09-03 19:29:37', 'Simbulan, Ian Angelo M.', 'Unknown'),
(840, '1234', 'Settings', 'Password Update', '2023-09-03 19:37:14', 'Simbulan, Ian Angelo M.', 'Unknown'),
(841, '1234', 'Settings', 'Password Update', '2023-09-03 19:37:45', 'Simbulan, Ian Angelo M.', 'Unknown'),
(842, '000000', '1234', 'Service Record Added', '2023-09-05 14:41:34', 'Simbulan, Ian Angelo M.', 'Simbulan, Ian Angelo M.'),
(843, '000000', '000000', 'Image Uploaded', '2023-09-05 16:50:12', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(844, '000000', '000000', 'Information Updated', '2023-09-12 08:47:32', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(845, '000000', '000000', 'Information Updated', '2023-09-12 08:49:23', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(846, '000000', '000000', 'Information Updated', '2023-09-12 08:49:31', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(847, '000000', '000000', 'Information Updated', '2023-09-12 08:52:04', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(848, '000000', '000000', 'Information Updated', '2023-09-12 08:52:10', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(849, '000000', '000000', 'Information Updated', '2023-09-12 08:52:15', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(850, '000000', '000000', 'Information Updated', '2023-09-12 08:52:18', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(851, '000000', '000000', 'Information Updated', '2023-09-12 08:55:07', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(852, '000000', '000000', 'Information Updated', '2023-09-12 08:55:11', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(853, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 08:56:52', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(854, '000000', '11', 'Information Updated', '2023-09-12 08:56:59', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(855, '000000', '11', 'Information Updated', '2023-09-12 08:57:04', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(856, '000000', '11', 'Unresigned Employee', '2023-09-12 08:57:28', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(857, '000000', '11', 'Information Updated', '2023-09-12 08:57:59', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(858, '000000', '11', 'Information Updated, Resigned Employee', '2023-09-12 08:58:12', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(859, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 08:58:25', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(860, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 08:59:00', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(861, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 08:59:10', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(862, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 08:59:48', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(863, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 09:01:09', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(864, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 09:01:24', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(865, '000000', '11', 'Resignation Updated', '2023-09-12 09:02:48', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(866, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 09:07:09', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(867, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 09:07:32', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(868, '000000', '11', 'Resignation Updated', '2023-09-12 09:07:45', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(869, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 09:07:51', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(870, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 09:07:56', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(871, '000000', '11', 'Information Updated', '2023-09-12 09:08:02', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(872, '000000', '11', 'Information Updated', '2023-09-12 09:08:07', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(873, '000000', '11', 'Information Updated, Resignation Updated', '2023-09-12 09:08:30', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(874, '000000', '000000', 'Information Updated', '2023-09-12 09:23:49', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(875, '000000', '000000', 'Information Updated', '2023-09-12 09:23:55', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(876, '000000', '000000', 'Information Updated', '2023-09-12 09:24:06', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(877, '000000', '000000', 'Information Updated', '2023-09-12 09:24:15', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(878, '000000', '000000', 'Information Updated', '2023-09-12 09:24:43', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(879, '000000', '000000', 'Information Updated', '2023-09-12 09:25:46', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(880, '000000', '000000', 'Information Updated', '2023-09-12 09:49:22', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(881, '000000', '000000', 'Information Updated', '2023-09-12 09:49:29', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(882, '000000', '000000', 'Information Updated', '2023-09-12 09:49:35', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(883, '000000', '000000', 'Information Updated', '2023-09-12 09:50:04', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(884, '000000', '000000', 'Information Updated', '2023-09-12 09:50:09', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(885, '000000', '000000', 'Information Updated', '2023-09-12 09:50:24', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(886, '000000', '000000', 'Information Updated', '2023-09-12 09:50:27', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(887, '000000', '000000', 'Information Updated', '2023-09-12 09:50:39', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(888, '000000', '000000', 'Information Updated', '2023-09-12 09:50:44', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(889, '000000', '000000', 'Information Updated', '2023-09-12 09:54:06', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(890, '000000', '000000', 'Information Updated', '2023-09-12 09:54:16', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(891, '000000', '11', 'Information Updated', '2023-09-12 09:54:25', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(892, '000000', '11', 'Information Updated', '2023-09-12 09:54:29', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(893, '000000', '11', 'Information Updated', '2023-09-12 09:54:36', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(894, '000000', '11', 'Information Updated', '2023-09-12 09:54:40', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(895, '000000', '000000', 'Information Updated', '2023-09-12 09:56:19', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(896, '000000', '000000', 'Information Updated', '2023-09-12 09:56:24', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(897, '000000', '000000', 'Information Updated', '2023-09-12 10:00:01', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(898, '000000', '000000', 'Information Updated', '2023-09-12 10:00:05', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(899, '000000', '000000', 'Information Updated', '2023-09-12 10:02:03', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(900, '000000', '000000', 'Information Updated', '2023-09-12 10:02:11', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(901, '000000', '11', 'Information Updated', '2023-09-12 10:02:51', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(902, '000000', '11', 'Information Updated', '2023-09-12 10:02:58', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(903, '000000', '11', 'Information Updated', '2023-09-12 10:03:03', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(904, '000000', '11', 'Information Updated', '2023-09-12 10:05:34', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(905, '000000', '11', 'Information Updated', '2023-09-12 10:05:38', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(906, '000000', '000000', 'Information Updated', '2023-09-12 10:05:51', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(907, '000000', '000000', 'Information Updated', '2023-09-12 10:05:55', 'Simbulan, Ian Angelo M.', 'Surname, First Name M.'),
(908, '000000', '000000', 'Information Updated', '2023-09-12 10:06:59', 'Simbulan, Ian Angelo M.', 'Sample 1, First Name M.'),
(909, '000000', '000000', 'Information Updated', '2023-09-12 10:09:45', 'Simbulan, Ian Angelo M.', 'Sample 1, First Name M.'),
(910, '000000', '011111', 'Information Updated', '2023-09-12 10:11:02', 'Simbulan, Ian Angelo M.', 'Sample2, Sample S.'),
(911, '000000', '011111', 'Information Updated', '2023-09-12 10:11:34', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(912, '000000', '011111', 'Information Updated', '2023-09-12 10:11:52', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample2 S.'),
(913, '000000', '011111', 'Information Updated', '2023-09-12 10:12:36', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(914, '000000', '011111', 'Information Updated', '2023-09-12 10:12:42', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(915, '000000', '011111', 'Information Updated', '2023-09-12 10:12:45', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(916, '000000', '011111', 'Information Updated', '2023-09-12 10:12:51', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample2 S.'),
(917, '000000', '011111', 'Information Updated', '2023-09-12 10:12:57', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(918, '000000', '1234', 'Information Updated', '2023-09-12 10:14:21', 'Simbulan, Ian Angelo M.', 'Simbulan, Ian Angelo M.'),
(919, '000000', '11', 'Information Updated', '2023-09-12 10:14:58', 'Simbulan, Ian Angelo M.', 'Sample 3, One O.'),
(920, '000000', '11', 'Information Updated', '2023-09-12 10:15:10', 'Simbulan, Ian Angelo M.', 'Sample 3, One O.'),
(921, '000000', '2', 'Information Updated', '2023-09-12 10:15:48', 'Simbulan, Ian Angelo M.', 'Sample 4, One O.'),
(922, '000000', '2', 'Information Updated, Resignation Updated', '2023-09-12 10:16:33', 'Simbulan, Ian Angelo M.', 'Sample 4, One O.'),
(923, '000000', '2', 'Information Updated', '2023-09-12 10:18:34', 'Simbulan, Ian Angelo M.', 'Sample 4, One O.'),
(924, '000000', '2', 'Information Updated', '2023-09-12 10:18:39', 'Simbulan, Ian Angelo M.', 'Sample 4, One O.'),
(925, '000000', '2', 'Information Updated', '2023-09-12 10:19:13', 'Simbulan, Ian Angelo M.', 'Sample 4, One O.'),
(926, '000000', '2', 'Information Updated', '2023-09-12 10:19:19', 'Simbulan, Ian Angelo M.', 'Sample 4, One O.'),
(927, '000000', '000000', 'Information Updated', '2023-09-12 13:22:06', 'Simbulan, Ian Angelo M.', 'Sample 1, First Name M.'),
(928, '000000', '13', 'Information Updated', '2023-09-12 16:45:57', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(929, '000000', '13', 'Information Updated', '2023-09-12 16:46:08', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(930, '000000', '13', 'Information Updated', '2023-09-12 16:46:13', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(931, '000000', '13', 'Information Updated', '2023-09-12 16:50:50', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(932, '000000', '13', 'Information Updated', '2023-09-12 16:50:56', 'Simbulan, Ian Angelo M.', 'One, One O.'),
(933, '000000', 'test1', 'Employee Added', '2023-09-14 17:29:01', 'Simbulan, Ian Angelo M.', 'test, test'),
(934, '000000', 'test1', 'Information Updated', '2023-09-14 17:29:16', 'Simbulan, Ian Angelo M.', 'test, test'),
(935, '000000', 'test1', 'Employee Deleted', '2023-09-14 17:29:27', 'Simbulan, Ian Angelo M.', 'Unknown'),
(936, '000000', 'fdsa', 'Employee Added', '2023-09-14 17:31:12', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(937, '000000', 'fdsaasd', 'Employee Added', '2023-09-14 17:33:15', 'Simbulan, Ian Angelo M.', 'Unknown'),
(938, '000000', 'fdsaas', 'Employee Deleted', '2023-09-14 17:34:01', 'Simbulan, Ian Angelo M.', 'Unknown'),
(939, '000000', '12asdre', 'Employee Added', '2023-09-14 17:34:27', 'Simbulan, Ian Angelo M.', 'Unknown'),
(940, '000000', '12asdr', 'Employee Deleted', '2023-09-14 17:35:00', 'Simbulan, Ian Angelo M.', 'Unknown'),
(941, '000000', 'zx', 'Employee Added', '2023-09-14 17:36:16', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(942, '000000', 'zxc', 'Information Updated', '2023-09-14 17:36:26', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(943, '000000', 'zxc', 'Information Updated', '2023-09-14 17:38:02', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(944, '000000', 'zxc', 'Information Updated', '2023-09-14 17:38:05', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(945, '000000', 'zxc', 'Information Updated', '2023-09-14 17:41:01', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(946, '000000', 'zxc', 'Information Updated', '2023-09-14 17:42:25', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(947, '000000', 'zxc', 'Information Updated', '2023-09-14 17:42:28', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(948, '000000', 'zxc', 'Information Updated', '2023-09-14 17:42:39', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(949, '000000', 'zxc', 'Information Updated', '2023-09-14 17:42:42', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(950, '000000', 'zxc', 'Information Updated', '2023-09-14 17:42:49', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(951, '000000', 'zxc', 'Information Updated', '2023-09-14 17:42:52', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(952, '000000', 'zxc', 'Information Updated', '2023-09-14 17:42:57', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(953, '000000', 'zxc', 'Information Updated', '2023-09-14 17:42:59', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(954, '000000', 'zxc', 'Information Updated', '2023-09-14 17:43:04', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(955, '000000', 'zxc', 'Information Updated', '2023-09-14 17:43:08', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(956, '000000', 'zxc', 'Information Updated', '2023-09-14 17:43:11', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(957, '000000', 'zxc', 'Information Updated', '2023-09-14 17:43:15', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(958, '000000', 'zxc', 'Information Updated', '2023-09-14 17:43:18', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(959, '000000', 'zxc', 'Information Updated', '2023-09-14 17:43:24', 'Simbulan, Ian Angelo M.', 'asdf, asdf asdf asdf'),
(960, '000000', 'z', 'Information Updated', '2023-09-14 17:45:16', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(961, '000000', 'z', 'Information Updated', '2023-09-14 17:45:23', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(962, '000000', 'z', 'Information Updated', '2023-09-14 17:45:33', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(963, '000000', 'z', 'Information Updated', '2023-09-14 17:45:40', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(964, '000000', 'z', 'Information Updated', '2023-09-14 17:48:10', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(965, '000000', 'z', 'Information Updated', '2023-09-14 17:48:17', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(966, '000000', 'z', 'Information Updated', '2023-09-14 17:48:19', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(967, '000000', 'z', 'Information Updated', '2023-09-14 17:48:43', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(968, '000000', 'z', 'Information Updated', '2023-09-14 17:48:46', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(969, '000000', 'z', 'Information Updated', '2023-09-14 17:48:53', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(970, '000000', 'z', 'Information Updated', '2023-09-14 17:48:57', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(971, '000000', 'z', 'Information Updated', '2023-09-14 17:49:00', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(972, '000000', 'z', 'Information Updated', '2023-09-14 17:49:06', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(973, '000000', 'z', 'Information Updated', '2023-09-14 17:49:44', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(974, '000000', 'z', 'Information Updated', '2023-09-14 17:51:48', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(975, '000000', 'z', 'Information Updated', '2023-09-14 17:51:59', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(976, '000000', 'z', 'Information Updated', '2023-09-14 17:52:12', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(977, '000000', 'z', 'Information Updated', '2023-09-14 17:52:14', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(978, '000000', 'z', 'Information Updated', '2023-09-14 17:55:10', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(979, '000000', 'z', 'Information Updated', '2023-09-14 17:55:15', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(980, '000000', 'z', 'Information Updated', '2023-09-14 17:55:22', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(981, '000000', 'z', 'Information Updated', '2023-09-14 17:55:25', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(982, '000000', 'y', 'Employee Added', '2023-09-14 17:56:36', 'Simbulan, Ian Angelo M.', 'y, y'),
(983, '000000', 'y', 'Information Updated', '2023-09-14 17:58:23', 'Simbulan, Ian Angelo M.', 'y, y'),
(984, '000000', 'y', 'Information Updated', '2023-09-14 17:58:26', 'Simbulan, Ian Angelo M.', 'y, y'),
(985, '000000', 'y', 'Information Updated', '2023-09-14 17:58:32', 'Simbulan, Ian Angelo M.', 'y, y'),
(986, '000000', 'y', 'Information Updated', '2023-09-14 17:58:38', 'Simbulan, Ian Angelo M.', 'y, y'),
(987, '000000', 'y', 'Information Updated', '2023-09-14 17:58:40', 'Simbulan, Ian Angelo M.', 'y, y'),
(988, '000000', 'z', 'Information Updated', '2023-09-14 17:59:21', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(989, '000000', 'z', 'Information Updated', '2023-09-14 18:01:13', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(990, '000000', 'z', 'Information Updated', '2023-09-14 18:01:20', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(991, '000000', 'z', 'Information Updated', '2023-09-14 18:01:34', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(992, '000000', 'z', 'Information Updated', '2023-09-14 18:01:40', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(993, '000000', 'y', 'Information Updated', '2023-09-14 18:03:17', 'Simbulan, Ian Angelo M.', 'y, y'),
(994, '000000', 'y', 'Information Updated', '2023-09-14 18:03:31', 'Simbulan, Ian Angelo M.', 'y, y'),
(995, '000000', 'y', 'Information Updated', '2023-09-14 18:03:35', 'Simbulan, Ian Angelo M.', 'y, y'),
(996, '000000', 'y', 'Information Updated', '2023-09-14 18:03:45', 'Simbulan, Ian Angelo M.', 'y, y'),
(997, '000000', 'y', 'Information Updated', '2023-09-14 18:03:50', 'Simbulan, Ian Angelo M.', 'y, y'),
(998, '000000', 'y', 'Information Updated', '2023-09-14 18:03:54', 'Simbulan, Ian Angelo M.', 'y, y'),
(999, '000000', 'y', 'Information Updated', '2023-09-14 18:03:59', 'Simbulan, Ian Angelo M.', 'y, y'),
(1000, '000000', 'y', 'Information Updated', '2023-09-14 18:04:08', 'Simbulan, Ian Angelo M.', 'y, y'),
(1001, '000000', 'y', 'Information Updated', '2023-09-14 18:16:40', 'Simbulan, Ian Angelo M.', 'y, y'),
(1002, '000000', 'Settings', 'Deleted Attendance Record', '2023-09-15 10:26:39', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1003, '000000', 'RA-20232', 'Employee Added', '2023-09-15 13:09:23', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1004, '000000', 'RA-2023-2', 'Information Updated', '2023-09-15 13:09:43', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1005, '000000', 'RA-2023-1', 'Information Updated', '2023-09-15 13:09:54', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1006, '000000', 'RA-2023-2', 'Information Updated', '2023-09-15 13:10:00', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1007, '000000', 'RA-2023-2', 'Information Updated', '2023-09-15 13:10:50', 'Simbulan, Ian Angelo M.', 'Simbulann, asdf'),
(1008, '000000', 'RA-2023-2', 'Information Updated', '2023-09-15 13:11:06', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1009, '000000', 'RA-2023-3', 'Information Updated', '2023-09-15 13:11:14', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1010, '000000', 'RA-2023-2', 'Information Updated', '2023-09-15 13:11:21', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1011, '000000', 'RA-2023-22', 'Information Updated', '2023-09-15 13:14:10', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1012, '000000', 'RA-2023-2', 'Information Updated', '2023-09-15 13:14:26', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1013, '000000', 'RA-2023-22', 'Information Updated', '2023-09-15 13:14:39', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1014, '000000', 'RA-2023-2', 'Information Updated', '2023-09-15 13:18:23', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1015, '000000', 'RA-2023-22', 'Information Updated', '2023-09-15 13:20:51', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1016, '000000', 'RA-2023-2', 'Information Updated', '2023-09-15 13:20:56', 'Simbulan, Ian Angelo M.', 'Simbulan, asdf'),
(1017, '000000', 'Settings', 'Password Update', '2023-09-15 18:36:10', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1018, '000000', 'Settings', 'Password Update', '2023-09-15 18:36:27', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1019, '000000', 'Settings', 'Password Update', '2023-09-15 19:09:16', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1020, '000000', 'Settings', 'Username Update', '2023-09-15 19:09:51', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1021, '000000', 'Settings', 'Username Update', '2023-09-15 19:09:53', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1022, '000000', 'Settings', 'Department Value Added', '2023-09-15 20:07:22', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1023, '000000', 'RA-2023-3', 'Employee Added', '2023-09-15 20:07:55', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(1024, '000000', 'Settings', 'Department Value Deleted', '2023-09-15 20:23:03', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1025, '000000', 'RA-2023-3', 'Information Updated', '2023-09-15 20:23:41', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(1026, '000000', 'Settings', 'Department Value Added', '2023-09-15 20:27:52', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1027, '000000', 'RA-2023-3', 'Information Updated', '2023-09-15 20:27:58', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(1028, '000000', 'RA-2023-3', 'Information Updated', '2023-09-15 20:30:52', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(1029, '000000', 'Settings', 'Department Value Deleted', '2023-09-15 20:30:58', 'Simbulan, Ian Angelo M.', 'Unknown'),
(1030, '000000', '011111', 'Information Updated', '2023-09-18 02:24:31', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(1031, '000000', 'samps', 'Information Updated', '2023-09-18 03:06:16', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(1032, '000000', 'samps', 'Information Updated', '2023-09-18 03:06:29', 'Simbulan, Ian Angelo M.', 'asdf, asdff'),
(1033, '000000', 'samps', 'Information Updated', '2023-09-18 03:17:26', 'Simbulan, Ian Angelo M.', 'asdf, asdff'),
(1034, '000000', 'samps', 'Information Updated', '2023-09-18 03:17:41', 'Simbulan, Ian Angelo M.', 'asdf, asdff'),
(1035, '000000', '011111', 'Information Updated', '2023-09-18 03:18:32', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(1036, '000000', '011111', 'Information Updated', '2023-09-18 03:19:00', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(1037, '000000', '011111', 'Information Updated', '2023-09-18 03:19:51', 'Simbulan, Ian Angelo M.', 'Sample 22, Sample S.'),
(1038, '000000', '011111', 'Information Updated', '2023-09-18 03:20:43', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(1039, '000000', '011111', 'Information Updated', '2023-09-18 03:21:08', 'Simbulan, Ian Angelo M.', 'Sample 22, Sample S.'),
(1040, '000000', '011111', 'Information Updated', '2023-09-18 03:23:26', 'Simbulan, Ian Angelo M.', 'Sample 22, Sample S.'),
(1041, '000000', '011111', 'Information Updated', '2023-09-18 03:23:34', 'Simbulan, Ian Angelo M.', 'Sample 2, Sample S.'),
(1042, '000000', 'y', 'Information Updated', '2023-09-18 04:23:12', 'Simbulan, Ian Angelo M.', 'y, y'),
(1043, '000000', 'y', 'Information Updated', '2023-09-18 04:23:30', 'Simbulan, Ian Angelo M.', 'y, y'),
(1044, '000000', 'y', 'Information Updated', '2023-09-18 04:23:52', 'Simbulan, Ian Angelo M.', 'y, y'),
(1045, '000000', 'fdsa', 'Information Updated', '2023-09-18 04:45:35', 'Simbulan, Ian Angelo M.', 'asdf, asdf'),
(1046, '000000', '18-6022', 'Information Updated', '2023-09-18 07:11:30', 'Unknown', 'Simbulan, Ian Angelo M.'),
(1047, '000000', '18-6022', 'Information Updated', '2023-09-18 07:12:40', 'Unknown', 'Simbulan, Ian Angelo M.'),
(1048, '000000', '22-4659', 'Information Updated', '2023-09-18 07:16:34', 'Unknown', 'Surname10, Name10 M. Jr.'),
(1049, '000000', '18-6022', 'Information Updated', '2023-09-18 07:31:28', 'Unknown', 'Simbulann, Ian Angelo M.'),
(1050, '000000', '18-6022', 'Profile Updated', '2023-09-18 07:34:26', 'Unknown', 'Simbulann, Ian Angelo M.'),
(1051, '18-6022', 'Settings', 'Username Update', '2023-09-18 07:40:17', 'Simbulann, Ian Angelo M.', 'Unknown'),
(1052, '18-6022', 'Settings', 'Password Update', '2023-09-18 07:40:26', 'Simbulann, Ian Angelo M.', 'Unknown'),
(1053, '18-6022', '18-6022', 'Profile Updated', '2023-09-18 07:41:50', 'Simbulann, Ian Angelo M.', 'Simbulann, Ian Angelo M.'),
(1054, '18-6022', '18-6022', 'Information Updated', '2023-09-18 07:54:33', 'Simbulann, Ian Angelo M.', 'Simbulann, Ian Angelo M.'),
(1055, '18-6022', '18-6022', 'Profile Updated', '2023-09-18 07:56:52', 'Simbulann, Ian Angelo M.', 'Simbulann, Ian Angelo M.'),
(1056, '18-6022', '18-6022', 'Service Record Added', '2023-09-18 07:57:28', 'Simbulann, Ian Angelo M.', 'Simbulann, Ian Angelo M.'),
(1057, '18-6022', '16-3427', 'Information Updated, Unresigned Employee', '2023-09-18 07:58:29', 'Simbulann, Ian Angelo M.', 'Surname6, Name6'),
(1058, '18-6022', '29-8647', 'Information Updated, Unresigned Employee', '2023-09-18 07:59:29', 'Simbulann, Ian Angelo M.', 'Surname42, Name42 M. Jr.'),
(1059, '18-6022', 'RA-2021-1', 'Employee Added', '2023-09-18 08:02:09', 'Simbulann, Ian Angelo M.', 'asdf, asdf a. as'),
(1060, '18-6022', 'RA-2021-1', 'Information Updated, Profile Updated', '2023-09-18 08:03:03', 'Simbulann, Ian Angelo M.', 'asdf, asdf a. as'),
(1061, '18-6022', 'Settings', 'School Year Added', '2023-09-18 08:04:06', 'Simbulann, Ian Angelo M.', 'Unknown'),
(1062, '18-6022', 'Settings', 'School Year Added', '2023-09-18 08:04:16', 'Simbulann, Ian Angelo M.', 'Unknown'),
(1063, '18-6022', 'RA-2021-1', 'Reset Employee Username & Password', '2023-09-18 08:09:31', 'Simbulann, Ian Angelo M.', 'asdf, asdf a. as'),
(1064, '18-6022', 'Settings', 'Department Value Added', '2023-09-18 08:21:06', 'Simbulann, Ian Angelo M.', 'Unknown'),
(1065, '18-6022', '10-6169', 'Information Updated', '2023-09-18 08:21:14', 'Simbulann, Ian Angelo M.', 'Surname44, Name44'),
(1066, '18-6022', '19-117', 'Employee Added', '2023-09-18 08:26:36', 'Simbulann, Ian Angelo M.', 'Cortez, Andrea Lois  A.'),
(1067, '19-117', 'Settings', 'Username Update', '2023-09-18 08:27:53', 'Cortez, Andrea Lois  A.', 'Unknown'),
(1068, '19-117', 'Settings', 'Password Update', '2023-09-18 08:28:23', 'Cortez, Andrea Lois  A.', 'Unknown'),
(1069, '18-6022', '19-117', 'Updated Access Level', '2023-09-18 08:29:20', 'Simbulann, Ian Angelo M.', 'Cortez, Andrea Lois  A.'),
(1070, '19-117', '10-6169', 'Information Updated', '2023-09-18 08:33:59', 'Cortez, Andrea Lois  A.', 'Surname44, Name44'),
(1071, '10-6169', 'Settings', 'Username Update', '2023-09-18 08:34:35', 'Surname44, Name44', 'Unknown'),
(1072, '10-6169', 'Settings', 'Password Update', '2023-09-18 08:34:46', 'Surname44, Name44', 'Unknown'),
(1073, '18-6022', '15-1055', 'Information Updated, Resigned Employee', '2023-09-18 12:00:35', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1074, '18-6022', '15-1055', 'Unresigned Employee', '2023-09-18 12:00:47', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1075, '18-6022', '23-6668', 'Information Updated, Unresigned Employee', '2023-09-18 12:01:01', 'Simbulann, Ian Angelo M.', 'Surname4, Name4 M.'),
(1076, '18-6022', '10-6169', 'Profile Updated', '2023-09-18 12:02:36', 'Simbulann, Ian Angelo M.', 'Surname44, Name44'),
(1077, '18-6022', '18-6022', 'Profile Updated', '2023-09-18 12:02:57', 'Simbulann, Ian Angelo M.', 'Simbulann, Ian Angelo M.'),
(1078, '000000', '10-6169', 'Information Updated', '2023-09-21 18:20:30', 'Unknown', 'Surname44, Name44'),
(1079, '000000', '10-6169', 'Information Updated', '2023-09-21 18:24:54', 'Unknown', 'Surname44, Name44'),
(1080, '000000', '18-9155', 'Information Updated', '2023-09-21 18:35:18', 'Unknown', 'Surname17, Name17'),
(1081, '000000', 'Settings', 'Deleted Attendance Record', '2023-09-21 18:38:25', 'Unknown', 'Unknown'),
(1082, '000000', '93-5034', 'Updated Access Level', '2023-09-21 18:49:46', 'Unknown', 'Surname1, Name1'),
(1083, '93-5034', 'Settings', 'Username Update', '2023-09-21 18:58:43', 'Surname1, Name1', 'Unknown'),
(1084, '93-5034', 'Settings', 'Password Update', '2023-09-21 18:59:00', 'Surname1, Name1', 'Unknown'),
(1085, '15-1055', 'Settings', 'Username Update', '2023-09-22 05:53:46', 'Surname30, Name30 M. III', 'Unknown'),
(1086, '15-1055', 'Settings', 'Password Update', '2023-09-22 05:53:53', 'Surname30, Name30 M. III', 'Unknown'),
(1087, '18-6022', '15-1055', 'Service Record Added', '2023-09-22 07:36:52', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1088, '18-6022', '00-00000', 'Employee Added', '2023-09-22 09:05:24', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1089, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:12:23', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1090, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:14:15', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1091, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:15:00', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1092, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:16:18', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1093, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:18:59', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1094, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:19:15', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1095, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:22:07', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1096, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:22:42', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1097, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:23:58', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1098, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:24:11', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1099, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:24:17', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1100, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:25:11', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1101, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:25:48', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1102, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:30:19', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1103, '18-6022', '00-00000', 'Information Updated', '2023-09-22 09:33:07', 'Simbulann, Ian Angelo M.', 'Simbulan, Adrian M.'),
(1104, '18-6022', 'asdf', 'Employee Added', '2023-09-22 09:34:11', 'Simbulann, Ian Angelo M.', 'asdf, asdf a.'),
(1105, '18-6022', 'asdf', 'Employee Deleted', '2023-09-22 09:34:24', 'Simbulann, Ian Angelo M.', 'Unknown'),
(1106, '18-6022', '111111111111', 'Employee Added', '2025-03-09 09:11:10', 'Simbulann, Ian Angelo M.', 'asdf, asdf a.'),
(1107, '18-6022', '111111111111', 'Employee Deleted', '2025-03-09 09:11:25', 'Simbulann, Ian Angelo M.', 'Unknown');

-- --------------------------------------------------------

--
-- Table structure for table `recent_request`
--

CREATE TABLE `recent_request` (
  `history_id` int(50) NOT NULL,
  `admin_id` varchar(13) NOT NULL,
  `employee_id` varchar(13) NOT NULL,
  `type` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_name` varchar(150) NOT NULL,
  `employee_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recent_request`
--

INSERT INTO `recent_request` (`history_id`, `admin_id`, `employee_id`, `type`, `timestamp`, `admin_name`, `employee_name`) VALUES
(1090, '18-6022', '15-1055', 'Decline Request', '2023-09-22 06:40:37', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1091, '18-6022', '15-1055', 'Decline Request', '2023-09-22 06:40:38', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1092, '18-6022', '15-1055', 'Decline Request', '2023-09-22 06:40:40', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1093, '18-6022', '15-1055', 'Decline Request', '2023-09-22 06:45:05', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1094, '18-6022', '15-1055', 'Decline Request', '2023-09-22 06:45:06', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1095, '18-6022', '15-1055', 'Accept Request', '2023-09-22 06:45:09', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1096, 'Not Applicabl', '15-1055', 'Pending Request', '2023-09-22 06:53:54', 'Not Applicable', 'Surname30, Name30 M. III'),
(1097, '18-6022', '15-1055', 'Accept Request', '2023-09-22 07:45:37', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1098, '18-6022', '15-1055', 'Decline Request', '2023-09-22 07:45:38', 'Simbulann, Ian Angelo M.', 'Surname30, Name30 M. III'),
(1099, 'Not Applicabl', '15-1055', 'Pending Request', '2023-09-22 08:12:59', 'Not Applicable', 'Surname30, Name30 M. III');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `control_number` varchar(13) NOT NULL,
  `name` varchar(300) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `req` varchar(100) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_needed` date DEFAULT NULL,
  `status` enum('pending','accept','decline') NOT NULL DEFAULT 'pending',
  `process_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `control_number`, `name`, `request_date`, `req`, `description`, `date_needed`, `status`, `process_date`) VALUES
(23, '15-1055', 'Name30 MiddleName30 Surname30', '2023-09-22 06:34:24', 'sample', '', '2023-09-22', 'decline', '2023-09-22 00:40:40'),
(24, '15-1055', 'Name30 MiddleName30 Surname30', '2023-09-22 06:38:21', 'Bobo', '', '2023-09-22', 'decline', '2023-09-22 00:40:38'),
(25, '15-1055', 'Name30 MiddleName30 Surname30', '2023-09-22 06:38:26', 'Bobo', '', '2023-09-22', 'decline', '2023-09-22 00:40:37'),
(26, '15-1055', 'Name30 MiddleName30 Surname30', '2023-09-22 06:43:38', 'sample', 'asdf', '2023-09-13', 'decline', '2023-09-22 00:45:05'),
(27, '15-1055', 'Name30 MiddleName30 Surname30', '2023-09-22 06:44:44', 'Bobo', '', '2023-09-22', 'decline', '2023-09-22 00:45:06'),
(28, '15-1055', 'Name30 MiddleName30 Surname30', '2023-09-22 06:44:52', 'sample', '', '2023-09-21', 'accept', '2023-09-22 00:45:09'),
(29, '15-1055', 'Name30 MiddleName30 Surname30', '2023-09-22 06:53:02', 'sample', 'asdf', '2023-09-12', 'accept', '2023-09-22 01:45:37'),
(30, '15-1055', 'Name30 MiddleName30 Surname30', '2023-09-22 06:53:54', 'sample', 'asdf', '2023-09-12', 'decline', '2023-09-22 01:45:38'),
(31, '15-1055', 'Surname30, Name30 MiddleName30', '2023-09-22 08:12:59', 'sample', 'asdf', '2023-09-23', 'accept', '2025-03-09 02:11:42');

-- --------------------------------------------------------

--
-- Table structure for table `resigned_employees`
--

CREATE TABLE `resigned_employees` (
  `control_number` varchar(6) NOT NULL,
  `resignation_date` date NOT NULL,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `civil_status` enum('Single','Married','Widowed') NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `employment_status` enum('Permanent','Probationary','Substitute','Part-time') NOT NULL,
  `classification` varchar(100) NOT NULL,
  `date_hired` date NOT NULL,
  `years_in_service` int(2) NOT NULL,
  `address` varchar(250) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course_taken` varchar(100) NOT NULL,
  `further_studies` varchar(100) NOT NULL,
  `number_of_units` float NOT NULL,
  `prc_number` varchar(7) NOT NULL,
  `prc_exp` date NOT NULL,
  `position` varchar(100) NOT NULL,
  `tin` varchar(11) NOT NULL,
  `sss` varchar(12) NOT NULL,
  `philhealth` varchar(14) NOT NULL,
  `pag_ibig` varchar(14) NOT NULL,
  `status` enum('resigned') NOT NULL DEFAULT 'resigned',
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resigned_employees`
--

INSERT INTO `resigned_employees` (`control_number`, `resignation_date`, `surname`, `name`, `middle_name`, `birthday`, `civil_status`, `gender`, `employment_status`, `classification`, `date_hired`, `years_in_service`, `address`, `contact`, `email`, `course_taken`, `further_studies`, `number_of_units`, `prc_number`, `prc_exp`, `position`, `tin`, `sss`, `philhealth`, `pag_ibig`, `status`, `image`) VALUES
('02', '2023-08-17', 'Sample', 'Sample', 'Sample', '2023-07-08', 'Married', 'Male', 'Probationary', 'Middle Level Administrator', '2023-07-13', 5, 'Add', '12345678910', 'sample@sample.com', 'Sample', 'Sample', 5, '000000', '2023-07-21', '3, 4', '0000', '00000', '000', '00000', 'resigned', '1692632203_Simbulan.png'),
('1', '2010-08-02', 'asdf', 'asdf asdf asdf', 'asdf', '2023-08-16', 'Single', 'Male', 'Permanent', '', '2023-08-21', 0, 'Lot 1, Block 1, Phase 2, La Trevi Estate, Sta. Monica,', 'asdf', 'asdf@asdf.com', 'asdf', '', 0, '', '0000-00-00', 'asdf', '', '', '', '', 'resigned', ''),
('10', '2022-02-16', 'asdf', 'asdf asdf asdf', 'asdf', '2023-08-26', 'Single', 'Male', 'Permanent', '', '2023-07-30', 0, 'Lot 1, Block 1, Phase 2, La Trevi Estate, Sta. Monica,', 'asdf', 'asdf@asdf.com', 'asdf', '', 0, '', '0000-00-00', 'asdf', '', '', '', '', 'resigned', ''),
('101010', '2023-07-30', 'Sample', 'Sample', 'Sample', '2023-06-26', 'Single', 'Male', 'Permanent', 'Rank and File', '2023-07-13', 20, 'Sample', '12345678910', 'sample@sample.com', 'Sample', 'Sample', 20, '000123', '2023-07-04', '1, 2', '123', '321', '123', '123', 'resigned', ''),
('111111', '2000-10-10', 'Sample', 'Sample', 'Sample', '2023-07-06', 'Widowed', 'Female', 'Part-time', 'Department Head', '2023-07-18', 25, 'Sample Sample Sample', '12345678910', 'sample@sample.com', 'Sample', ' Sample', 15, '0123120', '0000-00-00', 'position 1', '123', '123', '123', '123', 'resigned', ''),
('123457', '2023-08-14', 'asdf', 'asdf asdf asdf', 'asdf', '2023-08-10', 'Married', 'Male', 'Permanent', 'Rank and File', '2023-08-08', 0, 'Lot 1, Block 1, Phase 2, La Trevi Estate, Sta. Monica,', 'asdf', 'asdf@asdf.com', 'asdf', '', 0, '', '0000-00-00', 'asdf', '', '', '', '', 'resigned', ''),
('14', '2023-08-29', '12', '', '', '0000-00-00', 'Single', 'Male', 'Permanent', '', '0000-00-00', 0, '', '', '', '', '', 0, '', '0000-00-00', '', '', '', '', '', 'resigned', '');

-- --------------------------------------------------------

--
-- Table structure for table `service_record`
--

CREATE TABLE `service_record` (
  `service_id` int(11) NOT NULL,
  `control_number` varchar(13) NOT NULL,
  `school_year` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `information` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_record`
--

INSERT INTO `service_record` (`service_id`, `control_number`, `school_year`, `status`, `information`) VALUES
(1, '123456', '2015-2016', 'Probationary', 'Mathematics\r\nasdfdsasdfa\r\nasdfasdasdf\r\nasdfasdfasdf'),
(7, '01', '2015-2016', 'Permanent', 'Mathematics, AP'),
(33, '01', '2019-2020', '', 'a, b, c, b, c, b, c, b, c, b, c, b, c, b, c, b, c, b, c, b, c, b, c, b, c, b, c, b, c, b, c,'),
(34, '01', '', '', ''),
(35, '01', '', '', ''),
(46, '01', '2019-2020', 'Permanent', 'asdfasdf, asdfasdf, asdfasd'),
(51, '04', '2019-2020', 'asdf', 'asdf'),
(52, '00011', '2019-2020', 'asd', 'asd'),
(53, '10', '2019-2020', '1', '1, 1, 1, 1, 1, 1, ,1 ,1 , , , , 1'),
(54, '10', '2000', '2', 'Eample 1, Example2,Example3'),
(55, '1234', '2020-2021', '', 'Test 1, Test 2, Test 3'),
(56, '18-6022', '2019-2020', 'asdf', 'asdf'),
(57, '15-1055', '2020-2021', 'asdf', 'asdfasdfasdfasdfasdf, asdfasdfasdf');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `control_number` varchar(13) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `suffix` varchar(20) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `access_level` enum('super admin','admin','employee') NOT NULL,
  `status` set('active','disabled') NOT NULL DEFAULT 'active',
  `image` varchar(250) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`control_number`, `surname`, `name`, `middle_name`, `suffix`, `username`, `password`, `access_level`, `status`, `image`) VALUES
('00-00000', 'Simbulan', 'Adrian', 'Meneses', '', '8fba715e7a5fb390fab381324b294d9e96b55391', 'c147e68d95345c6a0de85e7c541042efb42f55ea', 'employee', 'active', 'default.jpg'),
('10-6169', 'Surname44', 'Name44', '', '', '92429d82a41e930486c6de5ebda9602d55c39986', '92429d82a41e930486c6de5ebda9602d55c39986', 'employee', 'active', '1695038555_download (1).jpg'),
('15-1055', 'Surname30', 'Name30', 'MiddleName30', 'III', 'caf322f0bbed721eac4a36bf7aff1103079faf25', '92429d82a41e930486c6de5ebda9602d55c39986', 'employee', 'active', 'default.jpg'),
('15-4268', 'Surname10', 'Name10', '', 'Sr.', 'b08d713c1b960579111ba003a22d2424d0db068b', '6e2a62f1990f4148d42731d8511f252c9ed97bbe', 'employee', 'active', 'default.jpg'),
('16-3427', 'Surname6', 'Name6', '', '', '2af24ee1303aba2ff6fe75cfe6bd8ffa3b597bd5', 'cc8b9792101ccf365ea090788eb9febc67c246aa', 'employee', 'active', 'default.jpg'),
('16-8346', 'Surname39', 'Name39', '', 'III', '7161698d65b2d37631fee77d75d16e5f1c150422', '45baeee846a4f02b51ca5b7a6048284ae8bd0e32', 'employee', 'active', 'default.jpg'),
('18-6022', 'Simbulann', 'Ian Angelo', 'Meneses', '', '3da541559918a808c2402bba5012f6c60b27661c', '3da541559918a808c2402bba5012f6c60b27661c', 'super admin', 'active', '1695038577_download (1).jpg'),
('18-8048', 'Surname36', 'Name36', 'MiddleName36', '', 'b9f867ca96d8b12eda21fd5c6e5cb993f274fc16', 'de35fe389663ca364d2b13ac3d7b9ed278c8f158', 'employee', 'active', 'default.jpg'),
('18-9155', 'Surname17', 'Name17', '', '', '99426598930b679d74287d8d4f939846efdc49cd', 'd9902fdc4b4124be5cae3a5b3b99afba41f0641c', 'employee', 'active', 'default.jpg'),
('19-117', 'Cortez', 'Andrea Lois ', 'Aguilar ', '', 'ff85e2e1fed55deaf8d5fb2a49b31684de71c7a0', 'd6460747e1436910ac4dc7c197ce7a3b4a22a033', 'super admin', 'active', 'default.jpg'),
('20-4241', 'Surname3', 'Name3', '', '', '868c7b0b37828b9e1afc61926465ba2f8699e75b', 'b254493043b379b08cd33183d43a67c8fc28485c', 'employee', 'active', 'default.jpg'),
('22-3491', 'Surname48', 'Name48', '', '', 'f79aeaacec24603dd75b277f7d470ec0c2c21e60', '492e39ecf0e6fdbebba4f0838c0c2045ccd265fc', 'employee', 'active', 'default.jpg'),
('22-4659', 'Surname10', 'Name10', 'MiddleName10', 'Jr.', '36eb429c34441be934a90233e6479fa73a9935bc', '7938ad140572c14c2259cc3bf6dae256e7cc6919', 'employee', 'active', 'default.jpg'),
('22-6731', 'Surname32', 'Name32', 'MiddleName32', 'Jr.', '485b7a765580c1596c69fb0d9281a96013a98876', 'b89de9fc48a891051f964778c20d068d734606f4', 'employee', 'active', 'default.jpg'),
('23-1964', 'Surname13', 'Name13', '', '', '92d7a0d04c0f3133eea804610c634238e7214a9d', '7955da5412dd27fbd88216fb8463b98dfb7130a0', 'employee', 'active', 'default.jpg'),
('23-4161', 'Surname11', 'Name11', '', '', '5951f35fc353c50e83248c87d41c004404547ebd', 'd7e7e1d3430e9b1d9475e807b56d910f91c899b8', 'employee', 'active', 'default.jpg'),
('23-6668', 'Surname4', 'Name4', 'MiddleName4', '', '5acadfdb7cc9bc35f2a06c4746b9658b83755cc6', 'd287671d5fde78ea523c0f9ed54657ea78933ea8', 'employee', 'active', 'default.jpg'),
('25-6626', 'Surname44', 'Name44', '', 'III', 'c1d8eb484e5f827e6264bff9e5df84191d0afb0d', '2b8b25f0e1e64abc218e1cb772b097a0641de8ac', 'employee', 'active', 'default.jpg'),
('27-8593', 'Surname45', 'Name45', 'MiddleName45', 'III', '2635de9a7ca5a27167587b688ab5a8574625071e', 'e054cac484c16b3d3b4fbe009f43ec14273c74a8', 'employee', 'active', 'default.jpg'),
('29-6722', 'Surname42', 'Name42', '', 'III', 'f191edc5a58dd70554f6d5b6ea1e4b90797b9506', '6a8a7f850c294e78b9a4ba7c3f85fd4fdf9c53da', 'employee', 'active', 'default.jpg'),
('29-8647', 'Surname42', 'Name42', 'MiddleName42', 'Jr.', 'a6078a54da53a6a7557e4cb6ceb7baa54334e8e4', '1921584de160ced2007237d1486da5208669bb54', 'employee', 'active', 'default.jpg'),
('29-9162', 'Surname25', 'Name25', 'MiddleName25', '', '1afbae0c151995f992a72d70beab29a1873d8b4b', '0e95c43124566efa43ca694e506713411f30daf4', 'employee', 'active', 'default.jpg'),
('30-2921', 'Surname40', 'Name40', '', '', '8a062afa421fb3709507a02a7ac6e4d96ccc1671', '73d7e55bcbd6be9c7c5a348eec71c3865f1b3959', 'employee', 'active', 'default.jpg'),
('30-3597', 'Surname31', 'Name31', '', '', '9d9aa8d50b9298a5af059d2d95120e15a6cade46', '48ba42c29cc77d440d871ebeb86e242296ee6bae', 'employee', 'active', 'default.jpg'),
('31-5823', 'Surname35', 'Name35', '', 'Jr.', 'bd753dcc1cc16704976f7eb86247988409f21985', '11fa8cb77b4522d63bf6e1321c6ce7f3006a326b', 'employee', 'active', 'default.jpg'),
('32-1894', 'Surname28', 'Name28', 'MiddleName28', '', 'cce372816ec64894a4606baab83e9e5e132d3631', '3027ead2074827b887f599df824e7fda5243a9ef', 'employee', 'active', 'default.jpg'),
('32-2425', 'Surname12', 'Name12', 'MiddleName12', '', '222bcd903fb6cd6ab3bc5c9532c98b9e00d13ad7', '9cc62275735504d2bd1a768d203d9d2a1495b9ee', 'employee', 'active', 'default.jpg'),
('32-6289', 'Surname2', 'Name2', 'MiddleName2', 'Sr.', '9746f7d8e95733ed121b6b00def8499abe10a94d', '8220a01a1a6dfaf3740893004becf3f47364cfb9', 'employee', 'active', 'default.jpg'),
('34-3116', 'Surname24', 'Name24', 'MiddleName24', '', '7fcb00b553beb7bbee85ba0cdf3af4db15223201', 'b9605ec03cc5fa6821bce4e28c76bf6762324858', 'employee', 'disabled', 'default.jpg'),
('35-8332', 'Surname22', 'Name22', 'MiddleName22', 'II', 'e9afe507ef64c5a64da001e2edea3e9fbcec5006', '15c62d675ef65f3d79adb54ed097dfe2b87778ea', 'employee', 'disabled', 'default.jpg'),
('39-7739', 'Surname19', 'Name19', '', 'Jr.', '9dcc2c0fd75dbf9a659d234992797ab84a601b6b', 'af6bb35804d2714fdf331d895a5723f32aa09143', 'employee', 'active', 'default.jpg'),
('40-1484', 'Surname13', 'Name13', '', '', 'af502b2c0c2ecefe5252a731dc2d9521eaecb62e', '4e13b09842bcd83ae3d141a580559ad474a89d5b', 'employee', 'disabled', 'default.jpg'),
('41-8270', 'Surname6', 'Name6', '', '', '9759a7d206a0944b743e198b5df8f77f742547a6', '322404f62b6aaabde99a63c13c260ef5cf72f6fc', 'employee', 'active', 'default.jpg'),
('41-8710', 'Surname14', 'Name14', '', 'III', '30d5928fb4a8683f2053107772fb22d643291fa1', '89b2d9a5c61e190ead90d599aa42e2aa813a36e9', 'employee', 'active', 'default.jpg'),
('42-4663', 'Surname9', 'Name9', '', 'III', 'c7efaa876e40a8bce72ffdc743b58b50760798bc', '6bebc354456fcf24031aa8c2a40ffae8aa33067b', 'employee', 'active', 'default.jpg'),
('43-2043', 'Surname17', 'Name17', 'MiddleName17', '', 'c00bcb458d191eaeebb0b8090258efc8fd4ee317', '86a14ab5feb338de14924a5caa8da9eb226c8a47', 'employee', 'active', 'default.jpg'),
('43-8714', 'Surname46', 'Name46', '', '', 'f94e9133c64dc107793eb7507a5376f83f4dfe61', 'a4250131604fdd8fe14857f73613747b9aaa935e', 'employee', 'active', 'default.jpg'),
('44-7879', 'Surname26', 'Name26', '', '', 'f2b7778cca1d76f1b9be4e8a12b2a96405d80c73', 'a4cd8a51471d9df1913cac4ea6702cde023b36e0', 'employee', 'active', 'default.jpg'),
('45-3825', 'Surname27', 'Name27', 'MiddleName27', '', '2634383c67b6b47dbe3768aafca7526fce11f7a2', 'c1bb690296cbe28571052596ba2cee05f653201a', 'employee', 'active', 'default.jpg'),
('45-9978', 'Surname37', 'Name37', '', 'Jr.', 'dceeff2b33935a333755c9c8ed9c4449e152f0b6', '7dbb8d91f32949eb2180b6c192ac0eee89842727', 'employee', 'disabled', 'default.jpg'),
('46-4427', 'Surname9', 'Name9', '', '', '455d3bdd195ed6151cc4d1959e14ec197b19ea64', 'e4d9583fe5df41f4ec2f76574c64d24860ed6d85', 'employee', 'active', 'default.jpg'),
('46-4768', 'Surname49', 'Name49', '', '', '378508c921f186b3341fd2353455c4d6c14351f4', 'ea76a9a5301b2e0f10ae395f4a5501699023c7e7', 'employee', 'active', 'default.jpg'),
('46-8424', 'Surname37', 'Name37', 'MiddleName37', '', 'e6f74d1c9215956d23923c83ca5a6c3084fe4822', '1e758a8c0e28a4939f4f0249640aca1470eceb8f', 'employee', 'active', 'default.jpg'),
('48-5178', 'Surname16', 'Name16', 'MiddleName16', '', 'ad6a00deb0c7df413df23460b12a2206159210fb', 'a807d4e8035e95bd4d147d167b90b9ca23f67eb4', 'employee', 'active', 'default.jpg'),
('48-8949', 'Surname19', 'Name19', '', '', '5f98723ad9940fa614836d234d95017753df8be1', '9b746153623ca3a1ba9a9f29e29bb3bace23b44a', 'employee', 'disabled', 'default.jpg'),
('49-5476', 'Surname29', 'Name29', 'MiddleName29', '', 'bb5c2a12ecf012243c2fc458e9d565bf7bfa45b7', '0139f6e9e04521d7b2d0aa27bb4cc7d06447eb55', 'employee', 'active', 'default.jpg'),
('51-5129', 'Surname38', 'Name38', '', '', 'c9ad02ec2d91ba6a46651e939e72cdc438e538a7', '1bb8435d3b1aad0b4f43656c91f8c52bc455dd63', 'employee', 'active', 'default.jpg'),
('54-4973', 'Surname18', 'Name18', '', '', 'a95389652d6198ae5fa47161a09342f717520ddb', '672b08673b7a1d8e48fd26a4ffeef850844f619d', 'employee', 'active', 'default.jpg'),
('56-3440', 'Surname30', 'Name30', 'MiddleName30', '', 'f3b2dd7fb194b72cd0e9cfc2c4c03d3a40117bf1', 'c5f54e50c7544adce1233e2a2b890d60aa4bf7f5', 'employee', 'active', 'default.jpg'),
('57-5580', 'Surname29', 'Name29', 'MiddleName29', '', 'ac7bf2693a352b432c32f1084e37fd54dbed8cd3', 'dc23fa8e4eee235159433e28c805d86350652c11', 'employee', 'active', 'default.jpg'),
('57-8112', 'Surname22', 'Name22', '', '', '19311dcd0d6e9c05e7bb5567e71c7a4d8bb5cb8f', 'd0bf3080b471285ea8b7ab8c23c09e71e2603c5a', 'employee', 'active', 'default.jpg'),
('58-3278', 'Surname38', 'Name38', '', '', 'ee6e96151d1abb481995a7837bb3af9d4ebf10f7', '181b695a7b960544f9ed35e9c04e0020a8b2db56', 'employee', 'active', 'default.jpg'),
('58-5994', 'Surname21', 'Name21', '', '', 'd9cbcb8b29a35396f65f23c15066a33f31621d02', '3471287b22424e170539f87b2e1d6e80f84e36b4', 'employee', 'active', 'default.jpg'),
('58-6641', 'Surname33', 'Name33', 'MiddleName33', '', '3ecd1e9afd43104296e9c113955847978a59b4e8', 'db722be6a6ecac733906a69d4127e13dda75517a', 'employee', 'active', 'default.jpg'),
('58-6643', 'Surname21', 'Name21', 'MiddleName21', '', '16fbf93a508e9e5fbad35c433fd685881acdcc04', '6203d632abead31dd6090e34391e7ecb3a9fb437', 'employee', 'active', 'default.jpg'),
('58-7740', 'Surname7', 'Name7', '', '', 'cd0fe52105db3f7ab667cdf063c667803ad11e6a', 'ca272582769ecdae880a55a17aa36b6202eb096c', 'employee', 'active', 'default.jpg'),
('61-4496', 'Surname16', 'Name16', 'MiddleName16', 'Sr.', '7245f0bfe1797045685df4f33765251eda6c6d3a', 'a43fa91192454e899545f3dbafb76549f11c1f0f', 'employee', 'active', 'default.jpg'),
('61-8067', 'Surname2', 'Name2', 'MiddleName2', '', 'bcee8c89387838ec4256247cf7f4c4443be1fd91', '4b6329829b0b6f0827d0032ff6bb0882e37978e7', 'employee', 'active', 'default.jpg'),
('61-9358', 'Surname46', 'Name46', '', '', 'a40a2b98e9578e33a7529e7177f3be096c291703', '49da91ed5c7f5f227388615f4b640119db925b92', 'employee', 'active', 'default.jpg'),
('62-2523', 'Surname8', 'Name8', 'MiddleName8', '', 'b090dd1be747305ff479da4fd1025112e7c81b35', '16e23e6daab396eb18d9f2b902e7f35ba6374a06', 'employee', 'active', 'default.jpg'),
('62-3758', 'Surname3', 'Name3', 'MiddleName3', '', '566f21a7d2d4c54174fa782088b68fa9bd3dcc5c', 'd789af146c961e355ab33923f9bb00d9196c636f', 'employee', 'active', 'default.jpg'),
('63-6258', 'Surname26', 'Name26', '', 'III', '9aa5284a55e2069be58acdc3dc09b6aa4c3b84c7', 'cd1a4301c65447636a1d0b2421b49835f6878425', 'employee', 'active', 'default.jpg'),
('63-8223', 'Surname35', 'Name35', 'MiddleName35', '', 'f4af4923fd7c2eb612723c47b264fdc825cae7c6', '89a502a81a7f80f83f8a9a682e9541dd8c3ffed5', 'employee', 'disabled', 'default.jpg'),
('63-8490', 'Surname43', 'Name43', 'MiddleName43', '', '407d285d3e53cd76d3a812d97a58c66c1e6d701e', '55533cad75119506d56d5fd119f71e77377ff91f', 'employee', 'active', 'default.jpg'),
('63-9689', 'Surname48', 'Name48', 'MiddleName48', '', 'd5b01076ce3ef1394b2b4d7c71451417c8cbdfb3', 'b4d9d8ab6048b8601ee5e86ff0e9f2675d0c854b', 'employee', 'active', 'default.jpg'),
('63-9741', 'Surname11', 'Name11', 'MiddleName11', '', '8449b1858311cc9337f1b03117a0ca456edd7438', 'd6e163ba7c2f3843111f488bfbb6b4d72bf9abd8', 'employee', 'active', 'default.jpg'),
('64-7774', 'Surname15', 'Name15', '', '', '80472ff14ad632553d6b8f0e68ef5d92feb6d7de', '67f2c55000737c58cc9a04af79b187f600ce0e11', 'employee', 'active', 'default.jpg'),
('64-8019', 'Surname12', 'Name12', 'MiddleName12', '', '1ef2286ce9eeee7f6bbc2228fe7ba627ff351be2', '9b1b796c11c610255726cdf545e682c23e3b0fa9', 'employee', 'active', 'default.jpg'),
('66-1358', 'Surname45', 'Name45', '', 'II', 'c482c9b01b281bb742896a85c34880ef1710df52', 'c37b9b64f73686b04869ecdb8fdbb9307c12c8eb', 'employee', 'active', 'default.jpg'),
('66-4968', 'Surname33', 'Name33', 'MiddleName33', '', '943674001b99cd56b035cefedeb544b1d61e3a5c', '799396e519ee41dacdc7076a2c0f7c46e14d7b0e', 'employee', 'disabled', 'default.jpg'),
('67-6933', 'Surname27', 'Name27', 'MiddleName27', '', 'c2f99ed856e409cb511ef883621945be8d9e2f34', '3351957445ffbb5cef2eab53ac749a4cd179db15', 'employee', 'active', 'default.jpg'),
('69-9227', 'Surname15', 'Name15', '', 'Sr.', 'e70c14fc1587dc1237cf74373ab34f1274b5dba7', '522282bf04ce05a620a93006faa5f2070c46155f', 'employee', 'active', 'default.jpg'),
('70-2279', 'Surname18', 'Name18', 'MiddleName18', '', 'abdfa6011f77365b11e5d9b77fd644e129913bd5', 'cd7fb3a9e8d4386f2a9aa64b703e11db263f1536', 'employee', 'active', 'default.jpg'),
('72-2513', 'Surname36', 'Name36', 'MiddleName36', '', '338813695fda0ea943991722ede990c2ce0bf09f', '313709d53c8cb2140972feecb058777419088273', 'employee', 'active', 'default.jpg'),
('72-5487', 'Surname7', 'Name7', '', '', 'b88a32f213fed14ed5546d042501c317ae424a40', 'd756f8588f1e1743bf0d1792ad4327f1debdce69', 'employee', 'active', 'default.jpg'),
('73-6586', 'Surname31', 'Name31', '', '', '849b0738e3c9981824ff7400f145de7042272c33', 'c6ad37081264f3ae95daf91124bd446ad68c64ac', 'employee', 'disabled', 'default.jpg'),
('75-1083', 'Surname20', 'Name20', '', '', '002cc592d3545d049e47c42e69a59e7edf998395', '721a5de4fffda1fa3287bf3d8b42f8b375262582', 'employee', 'active', 'default.jpg'),
('75-2952', 'Surname23', 'Name23', 'MiddleName23', 'Sr.', '2fbfd7c8c5d78b2ddd83f03434369712671dd253', 'a815eba1d5d5091428d995ef27601883cef0350f', 'employee', 'active', 'default.jpg'),
('75-7730', 'Surname28', 'Name28', '', '', '9fd6cfeaec80e25e01cf83e237980115fa08f908', '07e233d9fa0a15543744eda14829cfc08e0bab06', 'employee', 'active', 'default.jpg'),
('76-2557', 'Surname14', 'Name14', '', 'Sr.', '2ab29511024d9200bbdd0e61f7fdaca9522635e1', 'b928c0cc4dd82e40b3320cc17ee21d492c229a69', 'employee', 'active', 'default.jpg'),
('77-6399', 'Surname24', 'Name24', 'MiddleName24', 'II', '4718ca4cf9ae5d7d97d0e5ca0bb7a5f42a286c14', '661260eabb23223bd2389785a615a408b15b2d97', 'employee', 'active', 'default.jpg'),
('79-4036', 'Surname5', 'Name5', '', '', 'ecb859c0288a66c2b5f0b5bc8bdb2369792750ea', '91db7769a3e892ba9e435d6505eef90466b0a554', 'employee', 'disabled', 'default.jpg'),
('79-4147', 'Surname34', 'Name34', '', '', 'fde00c42fd2d4877b86f372ec0c4563df3383298', '7e9ea3d03d81cf144862fcbf998253841f5ee94c', 'employee', 'active', 'default.jpg'),
('79-6416', 'Surname47', 'Name47', 'MiddleName47', '', '832eda7aeb40d9bba1506287df2283848129290d', '1b8a4bb2b05b2253e38a63fe6bcac2159f16b984', 'employee', 'active', 'default.jpg'),
('81-7219', 'Surname5', 'Name5', '', '', 'ef46fbec40ab488118a073e39093dfc9683bde45', '129a0ce73c7a398b32a206eccf1e2620bd9b16b5', 'employee', 'active', 'default.jpg'),
('82-3242', 'Surname50', 'Name50', '', '', '3789a1df8df52c803ec5f63747427c482b5e50f9', '793dd543bef608317049df4c71addac2c7c1255d', 'employee', 'active', 'default.jpg'),
('83-6659', 'Surname39', 'Name39', '', 'Jr.', 'f89657a9b372c8356d27fc29ea17d7df4122eb41', 'd0c697c1f2590ff1343c6e1291a282e6f0d326c0', 'employee', 'active', 'default.jpg'),
('84-1166', 'Surname40', 'Name40', 'MiddleName40', '', '3d885cfcae629f5bc49efc75c6f3a034a265cc6e', '3f9931ac09f81929c9bc22f869d22d7868585db1', 'employee', 'disabled', 'default.jpg'),
('84-4560', 'Surname50', 'Name50', 'MiddleName50', '', '6144e3bef0061fe20105dfd698dab8d4f78bcd90', 'ee8b97860dbdc37bb345d5883602a41c75234d62', 'employee', 'disabled', 'default.jpg'),
('86-1097', 'Surname32', 'Name32', 'MiddleName32', '', 'e0ea962aeadd0047152e172325c5a7e4d3d215cb', 'f00800995acd48a01952018b842bc25dc83afd5e', 'employee', 'active', 'default.jpg'),
('88-8069', 'Surname1', 'Name1', 'MiddleName1', 'II', 'f2402e3178254348f17858629eaea7fb56f5be19', '18a7ae389c440829929429561d2930044052f6f9', 'employee', 'active', 'default.jpg'),
('89-2020', 'Surname25', 'Name25', 'MiddleName25', '', '7733c7395bd86e1ecd212c86930100c7846e9e1c', '4657442740631487821f061330444f4107d2b7f7', 'employee', 'active', 'default.jpg'),
('89-8514', 'Surname4', 'Name4', '', '', '4eb30c4d590a23476415ebe74ac7e7d2113ff5a0', 'b4f6f4a65654bdf7ed5b9d84fa06ba603bd31ff8', 'employee', 'active', 'default.jpg'),
('90-8882', 'Surname49', 'Name49', 'MiddleName49', 'Sr.', 'ebfa975200fe59fa056b943e3b05016ae610216f', '1a1024f2cc71d23596fdaea3964bc41706037f23', 'employee', 'active', 'default.jpg'),
('93-5034', 'Surname1', 'Name1', '', '', '79437f5edda13f9c0669b978dd7a9066dd2059f1', '92429d82a41e930486c6de5ebda9602d55c39986', 'admin', 'active', 'default.jpg'),
('93-5615', 'Surname41', 'Name41', 'MiddleName41', '', '73b2cf221c04e3985f5497d58cabec14d0443ed2', 'fd73eb9948f0a8d2a0e3050763ec6692e8fb32cf', 'employee', 'disabled', 'default.jpg'),
('93-8161', 'Surname8', 'Name8', 'MiddleName8', '', '85f543a604d80f35482692ce17c2811155bbb4e1', 'c3c95d0949b5e752a2fbb40cb8224f6345cfb874', 'employee', 'active', 'default.jpg'),
('93-9774', 'Surname23', 'Name23', '', '', 'b2fecef86a349cd94bd53572dc00bec86bcd49ef', '78d12d5509982a302e1252594a9a8362fe43c45c', 'employee', 'active', 'default.jpg'),
('95-1132', 'Surname20', 'Name20', '', '', '9a028460e2563af29c40ea2f8868bd60b43f75c5', '7368ae00ebbd59b4cf2d816bf9038ed9e57d2396', 'employee', 'active', 'default.jpg'),
('95-7316', 'Surname43', 'Name43', '', '', 'c706abec5a94cbed4de405d197e81f140885c7eb', 'a922a3ac635637429fb1cb1daa52b1f1469318fe', 'employee', 'active', 'default.jpg'),
('95-8797', 'Surname47', 'Name47', 'MiddleName47', '', '81d37c4a662596160a4977b1d66a7b0c1808388e', '9c8c0e64a26a8e0b7725b04e9e56de375fe2c0b2', 'employee', 'active', 'default.jpg'),
('99-8526', 'Surname41', 'Name41', '', '', '8d485121036b0f38d6375eae0fbd18d0b35c4bc2', 'd5855dd3c74ddba96e4e0c3116957aa2e322442d', 'employee', 'disabled', 'default.jpg'),
('RA-2021-1', 'asdf', 'asdf', 'asd', 'as', '42f121b40d0b0349b4e2c2df3f1b2550deed3bec', '6fafa136345af511874301eec0ca63eef601de69', 'employee', 'active', '1695024183_download.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `attendance_year`
--
ALTER TABLE `attendance_year`
  ADD PRIMARY KEY (`year_id`);

--
-- Indexes for table `data_values`
--
ALTER TABLE `data_values`
  ADD PRIMARY KEY (`value_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`control_number`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `recent_request`
--
ALTER TABLE `recent_request`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `resigned_employees`
--
ALTER TABLE `resigned_employees`
  ADD PRIMARY KEY (`control_number`);

--
-- Indexes for table `service_record`
--
ALTER TABLE `service_record`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`control_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `attendance_year`
--
ALTER TABLE `attendance_year`
  MODIFY `year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `data_values`
--
ALTER TABLE `data_values`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1108;

--
-- AUTO_INCREMENT for table `recent_request`
--
ALTER TABLE `recent_request`
  MODIFY `history_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1100;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `service_record`
--
ALTER TABLE `service_record`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
