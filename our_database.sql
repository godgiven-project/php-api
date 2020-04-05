-- GodGiven Project Version 1.0
--
-- Host: localhost:3306
-- Generation Time: Aug 31, 2019 at 08:52 AM
-- Server version: 10.3.17-MariaDB-log-cll-lve
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+04:30";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `DataBaseName`
--

-- --------------------------------------------------------

--
-- Table structure for table `persons_ggn`
--

CREATE TABLE `persons_ggn` (
  `per_id` bigint(20) NOT NULL,
  `per_personality` enum('single','company') CHARACTER SET utf8 NOT NULL,
  `per_Lname` varchar(60) CHARACTER SET utf8 NOT NULL,
  `per_Fname` varchar(60) CHARACTER SET utf8 NOT NULL,
  `per_email` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `per_tell` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `per_address` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `per_mobile` varchar(12) COLLATE utf8_persian_ci DEFAULT NULL,
  `per_email_active` enum('true','false') CHARACTER SET utf8 DEFAULT 'false',
  `per_tell_active` enum('true','false') CHARACTER SET utf8 DEFAULT 'false',
  `per_address_active` enum('true','false') CHARACTER SET utf8 DEFAULT 'false',
  `per_mobile_active` enum('true','false') CHARACTER SET utf8 DEFAULT 'false',
  `per_state` varchar(35) COLLATE utf8_persian_ci DEFAULT NULL,
  `per_city` varchar(35) COLLATE utf8_persian_ci DEFAULT NULL,
  `per_national_id` varchar(12) COLLATE utf8_persian_ci DEFAULT NULL,
  `per_job` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL,
  `per_education` enum('No formal education','Primary education','Secondary education or high school','GED or Vocational qualification','Bachelor''s degree','Master''s degree','Doctorate','Doctorate and Higher') COLLATE utf8_persian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `persons_ggn`
--

INSERT INTO `persons_ggn` (`per_id`, `per_personality`, `per_Lname`, `per_Fname`, `per_email`, `per_tell`, `per_address`, `per_mobile`, `per_email_active`, `per_tell_active`, `per_address_active`, `per_mobile_active`, `per_state`, `per_city`, `per_national_id`, `per_job`, `per_education`) VALUES
(1, 'single', 'Bagheri', 'Mahdi', 'mahdifox2000@gmail.com', '09376028183', '', '0912345678', 'true', 'true', 'true', 'true', '0', '0', '0923563334', 'supervisor', 'Master\'s degree'),
(2, 'single', 'خدادادی', 'محمد', 'asmanp2012@gmail.com', '09382318206', '', '', 'false', 'false', 'false', 'false', NULL, NULL, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_ggn`
--

CREATE TABLE `users_ggn` (
  `user_id` bigint(20) NOT NULL,
  `user_person_id` bigint(20) NOT NULL,
  `user_login` varchar(32) CHARACTER SET utf8 NOT NULL,
  `user_pass` varchar(32) CHARACTER SET utf8 NOT NULL,
  `user_alias` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL,
  `user_permission` smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  `user_active` enum('true','false') COLLATE utf8_persian_ci NOT NULL DEFAULT 'false',
  `user_block` enum('true','false') COLLATE utf8_persian_ci DEFAULT 'false',
  `user_pic` longtext CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `users_ggn`
--

INSERT INTO `users_ggn` (`user_id`, `user_person_id`, `user_login`, `user_pass`, `user_alias`, `user_permission`, `user_active`, `user_block`, `user_pic`) VALUES
(1, 1, 'mo', 'c4ca4238a0b923820dcc509a6f75849b', 'مهدی', 1, 'true', 'false', NULL),
(2, 2, 'asmanp2012', 'c4ca4238a0b923820dcc509a6f75849b', NULL, 7, 'true', 'false', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `persons_ggn`
--
ALTER TABLE `persons_ggn`
  ADD PRIMARY KEY (`per_id`);

--
-- Indexes for table `users_ggn`
--
ALTER TABLE `users_ggn`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_login` (`user_login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `persons_ggn`
--
ALTER TABLE `persons_ggn`
  MODIFY `per_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users_ggn`
--
ALTER TABLE `users_ggn`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
