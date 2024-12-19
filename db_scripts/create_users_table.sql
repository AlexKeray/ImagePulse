-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 01:53 PM
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
-- Database: `imagepulse`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'q', '$argon2i$v=19$m=65536,t=4,p=1$cTBFeGZaeWJYSldWNGF5OQ$qdiu01ymLR8xcC7A1U4EHwHhjPbl8AWBxWz3Dy0etbQ'),
(2, 'w', '$argon2id$v=19$m=65536,t=3,p=4$VxOYM0yDO2vnYE8LZXh2nA$wfku2Lf630umGlDD6TWmO9sVwSaPJxLiquanFxz2jH8'),
(3, 'e', '$argon2id$v=19$m=65536,t=3,p=4$aNwQtZ4Wk6W+yoEkt5HeKQ$CueEfRYRdpo/1JxdC76AyFl6t0KelEMHiEULuprvZuY'),
(4, 'r', '$argon2id$v=19$m=65536,t=3,p=4$HqZDGLBZkndUrlaB2V0HkQ$rTrJOr6/Pqcd9InN3E/Y7QDfhM7Q42hdXe0DJY8GfnQ'),
(5, 't', '$argon2id$v=19$m=65536,t=3,p=4$29SbSPt6ORZg1y2Su6BCUg$O1QEZaE4vt4UlvfCGWTjDXBMe6CauZkALiQbiU5gkmo'),
(6, 'y', '$argon2id$v=19$m=65536,t=3,p=4$dOKqLjBfBIOWS7xOXHItmw$DzaBcFTIgnaxn9rVeb11wEF6LU5IWDWHO9/+IT3Dz2k'),
(9, 'z', '$argon2i$v=19$m=65536,t=4,p=1$NUFQY2RMTkRrUGJiZXJMYg$J6WP7SSZBuPVVxx05UFRkha+v0bblsIbqfSedK4MHuY');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
