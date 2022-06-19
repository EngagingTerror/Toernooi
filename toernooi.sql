-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2022 at 11:14 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toernooi`
--

-- --------------------------------------------------------

--
-- Table structure for table `aanmeldingen`
--

CREATE TABLE `aanmeldingen` (
  `aanmelding_id` int(10) NOT NULL,
  `speler_id` int(10) NOT NULL,
  `toernooi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aanmeldingen`
--

INSERT INTO `aanmeldingen` (`aanmelding_id`, `speler_id`, `toernooi_id`) VALUES
(23, 23, 118),
(24, 24, 118),
(25, 25, 118),
(26, 26, 118),
(27, 27, 118),
(28, 28, 118),
(29, 29, 118),
(30, 31, 118),
(31, 23, 119),
(32, 24, 119),
(33, 25, 119),
(34, 26, 119),
(35, 27, 119),
(36, 28, 119),
(37, 29, 119),
(38, 31, 119),
(39, 23, 120),
(40, 24, 120),
(41, 25, 120),
(42, 26, 120),
(43, 27, 120),
(44, 28, 120),
(45, 29, 120),
(46, 31, 120);

-- --------------------------------------------------------

--
-- Table structure for table `banen`
--

CREATE TABLE `banen` (
  `baan_id` int(10) NOT NULL,
  `baannaam` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `banen`
--

INSERT INTO `banen` (`baan_id`, `baannaam`) VALUES
(6, 'Baan 1'),
(7, 'Baan 2');

-- --------------------------------------------------------

--
-- Table structure for table `medewerkers`
--

CREATE TABLE `medewerkers` (
  `medewerker_id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medewerkers`
--

INSERT INTO `medewerkers` (`medewerker_id`, `user`, `email`, `pwd`) VALUES
(1, 'user', 'user@hotmail.com', '$2y$10$cejfIU/W2tcdeKOjbx0CHeyosuWgrROSnBFvmzYD2I6s2V.KfHxsW'),
(2, 'test', 'test@gmail.com', '$2y$10$aH89IUgEdO5pw7XntVlvo.u0slPtlGlYmA6Ej2UN0CGV8c.hvcy7S');

-- --------------------------------------------------------

--
-- Table structure for table `scholen`
--

CREATE TABLE `scholen` (
  `school_id` int(10) NOT NULL,
  `schoolnaam` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `scholen`
--

INSERT INTO `scholen` (`school_id`, `schoolnaam`) VALUES
(2, 'ROCvA'),
(3, 'ROC TOP');

-- --------------------------------------------------------

--
-- Table structure for table `spelers`
--

CREATE TABLE `spelers` (
  `speler_id` int(10) NOT NULL,
  `voornaam` varchar(50) NOT NULL,
  `tussenvoegsel` varchar(20) DEFAULT NULL,
  `achternaam` varchar(50) NOT NULL,
  `school_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `spelers`
--

INSERT INTO `spelers` (`speler_id`, `voornaam`, `tussenvoegsel`, `achternaam`, `school_id`) VALUES
(23, 'Moreno', '', 'Martins', 2),
(24, 'Kemal ', 'Faruk', 'Yildiz', 2),
(25, 'Estaban', '', 'Frans', 2),
(26, 'Michael ', '', 'Buitenweg', 2),
(27, 'kyle', '', 'Morales', 2),
(28, 'Kevin ', '', 'Martins', 3),
(29, 'Carmen', '', 'Moreno', 3),
(31, 'Marc', 'de', 'Jong', 3);

-- --------------------------------------------------------

--
-- Table structure for table `toernooi`
--

CREATE TABLE `toernooi` (
  `toernooi_id` int(10) NOT NULL,
  `toernooi_naam` varchar(50) NOT NULL,
  `omschrijving` varchar(100) DEFAULT NULL,
  `datum` datetime DEFAULT current_timestamp(),
  `winnaar_id` int(10) DEFAULT NULL,
  `afgesloten` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `toernooi`
--

INSERT INTO `toernooi` (`toernooi_id`, `toernooi_naam`, `omschrijving`, `datum`, `winnaar_id`, `afgesloten`) VALUES
(118, 'Test', 'test', '2022-06-19 10:41:23', 23, 1),
(119, 'Test2', '', '2022-06-19 10:42:49', 23, 1),
(120, 'Tennis King', '', '2022-06-19 11:09:25', 23, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wedstrijd`
--

CREATE TABLE `wedstrijd` (
  `wedstrijd_id` int(10) NOT NULL,
  `toernooi_id` int(10) NOT NULL,
  `baan` int(10) DEFAULT NULL,
  `ronde` int(11) NOT NULL,
  `speler1_id` int(10) DEFAULT NULL,
  `speler2_id` int(10) DEFAULT NULL,
  `score1` int(1) DEFAULT NULL,
  `score2` int(1) DEFAULT NULL,
  `winnaar_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wedstrijd`
--

INSERT INTO `wedstrijd` (`wedstrijd_id`, `toernooi_id`, `baan`, `ronde`, `speler1_id`, `speler2_id`, `score1`, `score2`, `winnaar_id`) VALUES
(25, 118, NULL, 1, 23, 24, 2, 0, 23),
(26, 118, NULL, 1, 25, 26, 1, 2, 26),
(27, 118, NULL, 1, 27, 28, 2, 1, 27),
(28, 118, NULL, 1, 29, 31, 2, 0, 29),
(29, 118, NULL, 2, 23, 26, 1, 1, 23),
(30, 118, NULL, 2, 27, 29, 2, 1, 27),
(31, 118, NULL, 3, 23, 27, 2, 0, 23),
(32, 119, NULL, 1, 23, 24, 3, 0, 23),
(33, 119, NULL, 1, 25, 26, 3, 0, 25),
(34, 119, NULL, 1, 27, 28, 3, 0, 27),
(35, 119, NULL, 1, 29, 31, 3, 0, 29),
(36, 119, NULL, 2, 23, 25, 3, 0, 23),
(37, 119, NULL, 2, 27, 29, 3, 0, 27),
(38, 119, NULL, 3, 23, 27, 3, 0, 23),
(39, 120, 6, 1, 23, 24, 3, 0, 23),
(40, 120, 7, 1, 25, 26, 3, 0, 25),
(41, 120, 6, 1, 27, 28, 3, 0, 27),
(42, 120, 7, 1, 29, 31, 3, 0, 29),
(43, 120, 6, 2, 23, 25, 3, 0, 23),
(44, 120, 7, 2, 27, 29, 3, 0, 27),
(45, 120, 6, 3, 23, 27, 3, 0, 23);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  ADD PRIMARY KEY (`aanmelding_id`),
  ADD KEY `speler_id` (`speler_id`),
  ADD KEY `toernooi_id` (`toernooi_id`);

--
-- Indexes for table `banen`
--
ALTER TABLE `banen`
  ADD PRIMARY KEY (`baan_id`);

--
-- Indexes for table `medewerkers`
--
ALTER TABLE `medewerkers`
  ADD PRIMARY KEY (`medewerker_id`);

--
-- Indexes for table `scholen`
--
ALTER TABLE `scholen`
  ADD PRIMARY KEY (`school_id`);

--
-- Indexes for table `spelers`
--
ALTER TABLE `spelers`
  ADD PRIMARY KEY (`speler_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `toernooi`
--
ALTER TABLE `toernooi`
  ADD PRIMARY KEY (`toernooi_id`),
  ADD KEY `winnaar_id` (`winnaar_id`);

--
-- Indexes for table `wedstrijd`
--
ALTER TABLE `wedstrijd`
  ADD PRIMARY KEY (`wedstrijd_id`),
  ADD KEY `toernooi_id` (`toernooi_id`),
  ADD KEY `speler1_id` (`speler1_id`),
  ADD KEY `speler2_id` (`speler2_id`),
  ADD KEY `winnaar_id` (`winnaar_id`),
  ADD KEY `baan` (`baan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  MODIFY `aanmelding_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `banen`
--
ALTER TABLE `banen`
  MODIFY `baan_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `medewerkers`
--
ALTER TABLE `medewerkers`
  MODIFY `medewerker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `scholen`
--
ALTER TABLE `scholen`
  MODIFY `school_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `spelers`
--
ALTER TABLE `spelers`
  MODIFY `speler_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `toernooi`
--
ALTER TABLE `toernooi`
  MODIFY `toernooi_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `wedstrijd`
--
ALTER TABLE `wedstrijd`
  MODIFY `wedstrijd_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  ADD CONSTRAINT `aanmeldingen_ibfk_1` FOREIGN KEY (`toernooi_id`) REFERENCES `toernooi` (`toernooi_id`),
  ADD CONSTRAINT `aanmeldingen_ibfk_2` FOREIGN KEY (`speler_id`) REFERENCES `spelers` (`speler_id`);

--
-- Constraints for table `spelers`
--
ALTER TABLE `spelers`
  ADD CONSTRAINT `spelers_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `scholen` (`school_id`);

--
-- Constraints for table `toernooi`
--
ALTER TABLE `toernooi`
  ADD CONSTRAINT `toernooi_ibfk_1` FOREIGN KEY (`winnaar_id`) REFERENCES `spelers` (`speler_id`);

--
-- Constraints for table `wedstrijd`
--
ALTER TABLE `wedstrijd`
  ADD CONSTRAINT `wedstrijd_ibfk_1` FOREIGN KEY (`toernooi_id`) REFERENCES `toernooi` (`toernooi_id`),
  ADD CONSTRAINT `wedstrijd_ibfk_2` FOREIGN KEY (`speler1_id`) REFERENCES `spelers` (`speler_id`),
  ADD CONSTRAINT `wedstrijd_ibfk_3` FOREIGN KEY (`speler2_id`) REFERENCES `spelers` (`speler_id`),
  ADD CONSTRAINT `wedstrijd_ibfk_4` FOREIGN KEY (`winnaar_id`) REFERENCES `spelers` (`speler_id`),
  ADD CONSTRAINT `wedstrijd_ibfk_5` FOREIGN KEY (`baan`) REFERENCES `banen` (`baan_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
