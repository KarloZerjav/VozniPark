-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 05, 2024 at 06:07 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zerjav`
--

-- --------------------------------------------------------

--
-- Table structure for table `prikolica`
--

CREATE TABLE `prikolica` (
  `id` int(11) NOT NULL,
  `sasija_prikolica` varchar(255) NOT NULL,
  `proizvodac_prikolica` varchar(255) NOT NULL,
  `model_prikolica` varchar(255) NOT NULL,
  `godina_prikolica` varchar(255) NOT NULL,
  `visina_prikolica` varchar(255) NOT NULL,
  `nosivost` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prikolica`
--

INSERT INTO `prikolica` (`id`, `sasija_prikolica`, `proizvodac_prikolica`, `model_prikolica`, `godina_prikolica`, `visina_prikolica`, `nosivost`) VALUES
(1, 'WSM00000003240928', 'Schmitz Cargobull', 'Varios', '2016', '2.90m', '24000kg'),
(2, 'WSM00000003175672', 'Schmitz Cargobull', 'Varios', '2013', '2.85m', '24000kg'),
(3, 'WSM00000003419004', 'Schmitz Cargobull', 'Varios', '2023', '2.85m', '24000kg'),
(4, 'WSM00000003419005', 'Schmitz Cargobull', 'Varios', '2023', '2.85m', '24000kg'),
(5, 'WSM00000003310177', 'Schmitz Cargobull', 'Varios', '2018', '2.90m', '24000kg'),
(6, 'WSM00000003205919', 'Schmitz Cargobull', 'Varios', '2014', '2.85m', '24000kg'),
(7, 'WSM00000003240928', 'Schmitz Cargobull', 'Varios', '2016', '2.85m', '24000kg'),
(8, 'WSM00000003182397', 'Schmitz Cargobull', 'Varios', '2013', '2.90m', '24000kg'),
(9, 'WSM00000003420599', 'Schmitz Cargobull', 'Varios', '2023', '2.90m', '24000kg'),
(10, 'WSM00000003422361', 'Schmitz Cargobull', 'Varios', '2023', '2.90m', '24000kg'),
(11, 'WSM00000003206441', 'Schmitz Cargobull', 'Varios', '2014', '2.90m', '24000kg'),
(12, 'WSM00000003347232', 'Schmitz Cargobull', 'Varios', '2020', '2.85m', '24000kg'),
(13, 'WSM00000003339763', 'Schmitz Cargobull', 'Varios', '2019', '2.85m', '24000kg'),
(14, 'WSM00000003200352', 'Schmitz Cargobull', 'Varios', '2014', '2.90m', '24000kg'),
(15, 'WSM00000003273226', 'Schmitz Cargobull', 'Varios', '2017', '2.90m', '24000kg'),
(16, 'WSM00000003389747', 'Schmitz Cargobull', 'Varios', '2019', '2.90m', '24000kg'),
(17, 'WSM00000003389746', 'Schmitz Cargobull', 'Varios', '2019', '2.90m', '24000kg'),
(18, 'WSM00000003331109', 'Schmitz Cargobull', 'Mulda', '2019', '2.85m', '23000kg'),
(19, 'WSM00000003235809', 'Schmitz Cargobull', 'Varios', '2016', '2.90m', '24000kg'),
(20, 'WSM00000003300737', 'Schmitz Cargobull', 'Varios', '2018', '2.90m', '24000kg'),
(21, 'WSM00000003287745', 'Schmitz Cargobull', 'Varios', '2017', '2.90m', '24000kg'),
(22, 'WSM00000003193229', 'Schmitz Cargobull', 'Varios', '2014', '2.85m', '24000kg'),
(23, 'WSM00000003237418', 'Schmitz Cargobull', 'Varios', '2016', '2.85m', '24000kg'),
(24, 'WSM00000003246891', 'Schmitz Cargobull', 'Varios', '2016', '2.90m', '24000kg'),
(25, 'WSM00000003311811', 'Schmitz Cargobull', 'Varios', '2018', '2.85m', '24000kg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prikolica`
--
ALTER TABLE `prikolica`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prikolica`
--
ALTER TABLE `prikolica`
  ADD CONSTRAINT `Prikolica` FOREIGN KEY (`id`) REFERENCES `popis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
