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
-- Table structure for table `kamion`
--

CREATE TABLE `kamion` (
  `id` int(11) NOT NULL,
  `sasija_kamion` varchar(255) NOT NULL,
  `proizvodac_kamion` varchar(255) NOT NULL,
  `model_kamion` varchar(255) NOT NULL,
  `godina_kamion` varchar(255) NOT NULL,
  `visina_kamion` varchar(255) NOT NULL,
  `rezervar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kamion`
--

INSERT INTO `kamion` (`id`, `sasija_kamion`, `proizvodac_kamion`, `model_kamion`, `godina_kamion`, `visina_kamion`, `rezervar`) VALUES
(1, 'WMA13XZZ5JM781553', 'MAN', '18.500', '2018', 'Mega', '960L'),
(2, 'WMA06XZZ2JM785708', 'MAN', '18.500', '2018', 'Standard', '1160L'),
(3, 'WMA06X779JM758800', 'MAN', '18.500', '2018', 'Standard', '1160L'),
(4, 'WMA06XZZ8KM829423', 'MAN', '18.500', '2019', 'Standard', '870L'),
(5, 'WMA13XZZ3KM803034', 'MAN', '18.500', '2018', 'Mega', '960L'),
(6, 'WMA10XZZ5DM627406', 'MAN', '18.440', '2013', 'Standard', '870L'),
(7, 'WMA06XZZ8KM829423', 'MAN', '18.640', '2008', 'Standard', '870L'),
(8, 'WMA13XZZ5JM775204', 'MAN', '18.500', '2017', 'Mega', '960L'),
(9, 'WMA13XZZ1LP137582', 'MAN', '18.510', '2020', 'Mega', '960L'),
(10, 'WMA13XZZ2LP137557', 'MAN', '18.510', '2020', 'Mega', '960L'),
(11, 'WMA13XZZ1HM744221', 'MAN', '18.469', '2017', 'Mega', '960L'),
(12, 'WMA06XZZ5HM743690', 'MAN', '18.500', '2018', 'Standard', '1160L'),
(13, 'WMA06XZZ0LM854818', 'MAN', '18.510', '2019', 'Standard', '1160L'),
(14, 'WMA13XZZ3JM766226', 'MAN', '18.500', '2018', 'Mega', '960L'),
(15, 'WMA13XZZ0JM798695', 'MAN', '18.500', '2018', 'Mega', '960L'),
(16, 'WMA13XZZXLP144966', 'MAN', '18.510', '2019', 'Mega', '960L'),
(17, 'WMA13XZZ5LP142400', 'MAN', '18.510', '2019', 'Mega', '960L'),
(18, 'WMA06XZZ4JM758915', 'MAN', '18.500', '2017', 'Standard', '870L'),
(19, 'WMA13XZZ6HM723087', 'MAN', '18.480', '2016', 'Mega', '960L'),
(20, 'WMA13XZZ8JM787184', 'MAN', '18.500', '2018', 'Mega', '960L'),
(21, 'WMA13XZZ8HM744314', 'MAN', '18.460', '2016', 'Mega', '960L'),
(22, 'WMA06XZZ6JM772878', 'MAN', '18.500', '2017', 'Standard', '1160L'),
(23, 'WMA10XZZ4GM698102', 'MAN', '18.480', '2015', 'Standard', '1160L'),
(24, 'WMA13XZZ8GP075958', 'MAN', '18.480', '2016', 'Mega', '960L'),
(25, 'WMA06XZZ2JM759545', 'MAN', '18.500', '2018', 'Standard', '870L');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kamion`
--
ALTER TABLE `kamion`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kamion`
--
ALTER TABLE `kamion`
  ADD CONSTRAINT `Kamion` FOREIGN KEY (`id`) REFERENCES `popis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
