-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 21 jun 2022 om 09:05
-- Serverversie: 10.4.21-MariaDB
-- PHP-versie: 8.0.12

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
-- Tabelstructuur voor tabel `aanmeldingen`
--

CREATE TABLE `aanmeldingen` (
  `aanmelding_id` int(10) NOT NULL,
  `speler_id` int(10) NOT NULL,
  `toernooi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `aanmeldingen`
--

INSERT INTO `aanmeldingen` (`aanmelding_id`, `speler_id`, `toernooi_id`) VALUES
(79, 24, 125),
(80, 25, 125),
(81, 26, 125),
(82, 27, 125),
(83, 28, 125),
(84, 29, 125),
(85, 31, 125),
(86, 33, 125);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `banen`
--

CREATE TABLE `banen` (
  `baan_id` int(10) NOT NULL,
  `baannaam` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `banen`
--

INSERT INTO `banen` (`baan_id`, `baannaam`) VALUES
(8, 'Baan 1'),
(9, 'Baan 2'),
(10, 'Baan 3');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scholen`
--

CREATE TABLE `scholen` (
  `school_id` int(10) NOT NULL,
  `schoolnaam` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `scholen`
--

INSERT INTO `scholen` (`school_id`, `schoolnaam`) VALUES
(2, 'ROCvA'),
(3, 'ROC TOP'),
(4, 'Da Vinci College'),
(5, 'Drenthe College'),
(6, 'Graafschap College'),
(7, 'Katholiek Onderwijs Vlaanderen'),
(8, 'Rijn Ijssel College'),
(9, 'ROC de Leijgraaf'),
(10, 'ROC Honzon College'),
(11, 'ROC ID College'),
(12, 'ROC Midden Nederland');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `spelers`
--

CREATE TABLE `spelers` (
  `speler_id` int(10) NOT NULL,
  `voornaam` varchar(50) NOT NULL,
  `tussenvoegsel` varchar(20) DEFAULT NULL,
  `achternaam` varchar(50) NOT NULL,
  `school_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `spelers`
--

INSERT INTO `spelers` (`speler_id`, `voornaam`, `tussenvoegsel`, `achternaam`, `school_id`) VALUES
(24, 'Kemal ', 'Faruk', 'Yildiz', 2),
(25, 'Estaban', '', 'Frans', 2),
(26, 'Michael ', '', 'Buitenweg', 2),
(27, 'kyle', '', 'Morales', 2),
(28, 'Kevin ', '', 'Martins', 3),
(29, 'Carmen', '', 'Moreno', 3),
(31, 'Marc', 'de', 'Jong', 3),
(33, 'Moreno', '', 'Martins', 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `toernooi`
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
-- Gegevens worden geëxporteerd voor tabel `toernooi`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wedstrijd`
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
-- Gegevens worden geëxporteerd voor tabel `wedstrijd`
--

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  ADD PRIMARY KEY (`aanmelding_id`),
  ADD KEY `speler_id` (`speler_id`),
  ADD KEY `toernooi_id` (`toernooi_id`);

--
-- Indexen voor tabel `banen`
--
ALTER TABLE `banen`
  ADD PRIMARY KEY (`baan_id`);

--
-- Indexen voor tabel `scholen`
--
ALTER TABLE `scholen`
  ADD PRIMARY KEY (`school_id`);

--
-- Indexen voor tabel `spelers`
--
ALTER TABLE `spelers`
  ADD PRIMARY KEY (`speler_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexen voor tabel `toernooi`
--
ALTER TABLE `toernooi`
  ADD PRIMARY KEY (`toernooi_id`),
  ADD KEY `winnaar_id` (`winnaar_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexen voor tabel `wedstrijd`
--
ALTER TABLE `wedstrijd`
  ADD PRIMARY KEY (`wedstrijd_id`),
  ADD KEY `toernooi_id` (`toernooi_id`),
  ADD KEY `speler1_id` (`speler1_id`),
  ADD KEY `speler2_id` (`speler2_id`),
  ADD KEY `winnaar_id` (`winnaar_id`),
  ADD KEY `baan` (`baan`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  MODIFY `aanmelding_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT voor een tabel `banen`
--
ALTER TABLE `banen`
  MODIFY `baan_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `scholen`
--
ALTER TABLE `scholen`
  MODIFY `school_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT voor een tabel `spelers`
--
ALTER TABLE `spelers`
  MODIFY `speler_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT voor een tabel `toernooi`
--
ALTER TABLE `toernooi`
  MODIFY `toernooi_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `wedstrijd`
--
ALTER TABLE `wedstrijd`
  MODIFY `wedstrijd_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  ADD CONSTRAINT `aanmeldingen_ibfk_1` FOREIGN KEY (`toernooi_id`) REFERENCES `toernooi` (`toernooi_id`),
  ADD CONSTRAINT `aanmeldingen_ibfk_2` FOREIGN KEY (`speler_id`) REFERENCES `spelers` (`speler_id`);

--
-- Beperkingen voor tabel `spelers`
--
ALTER TABLE `spelers`
  ADD CONSTRAINT `spelers_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `scholen` (`school_id`);

--
-- Beperkingen voor tabel `toernooi`
--
ALTER TABLE `toernooi`
  ADD CONSTRAINT `toernooi_ibfk_1` FOREIGN KEY (`winnaar_id`) REFERENCES `spelers` (`speler_id`);

--
-- Beperkingen voor tabel `wedstrijd`
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
