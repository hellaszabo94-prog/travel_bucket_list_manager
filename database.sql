-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 02. Sep 2026 um 15:51
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `db_travel_bucket_list`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_city`
--

CREATE TABLE `tbl_city` (
  `IDCity` int(11) NOT NULL,
  `CityName` varchar(100) NOT NULL,
  `FIDCountry` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_country`
--

CREATE TABLE `tbl_country` (
  `IDCountry` int(11) NOT NULL,
  `CountryName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_destination`
--

CREATE TABLE `tbl_destination` (
  `IDDestination` int(11) NOT NULL,
  `DestinationName` varchar(150) NOT NULL,
  `Description` text DEFAULT NULL,
  `FIDCity` int(11) NOT NULL,
  `FIDStatus` int(11) NOT NULL,
  `FIDUser` int(10) UNSIGNED NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_image`
--

CREATE TABLE `tbl_image` (
  `IDImage` int(10) UNSIGNED NOT NULL,
  `FIDDestination` int(11) NOT NULL,
  `ImagePath` varchar(255) NOT NULL,
  `IsCover` tinyint(1) NOT NULL DEFAULT 0,
  `UploadedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_status`
--

CREATE TABLE `tbl_status` (
  `IDStatus` int(11) NOT NULL,
  `StatusName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `tbl_status`
--

INSERT INTO `tbl_status` (`IDStatus`, `StatusName`) VALUES
(2, 'Planning'),
(3, 'Visited'),
(1, 'Wishlist');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_user`
--

CREATE TABLE `tbl_user` (
  `IDUser` int(10) UNSIGNED NOT NULL,
  `Emailaddress` varchar(64) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
  `Firstname` varchar(32) NOT NULL,
  `Lastname` varchar(32) NOT NULL,
  `Birthdate` date NOT NULL,
  `Regdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tbl_city`
--
ALTER TABLE `tbl_city`
  ADD PRIMARY KEY (`IDCity`),
  ADD KEY `FIDCountry` (`FIDCountry`);

--
-- Indizes für die Tabelle `tbl_country`
--
ALTER TABLE `tbl_country`
  ADD PRIMARY KEY (`IDCountry`);

--
-- Indizes für die Tabelle `tbl_destination`
--
ALTER TABLE `tbl_destination`
  ADD PRIMARY KEY (`IDDestination`),
  ADD KEY `FIDCity` (`FIDCity`),
  ADD KEY `FIDStatus` (`FIDStatus`),
  ADD KEY `FIDUser` (`FIDUser`);

--
-- Indizes für die Tabelle `tbl_image`
--
ALTER TABLE `tbl_image`
  ADD PRIMARY KEY (`IDImage`),
  ADD UNIQUE KEY `fk_image_destination` (`FIDDestination`) USING BTREE;

--
-- Indizes für die Tabelle `tbl_status`
--
ALTER TABLE `tbl_status`
  ADD PRIMARY KEY (`IDStatus`),
  ADD UNIQUE KEY `StatusName` (`StatusName`);

--
-- Indizes für die Tabelle `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`IDUser`),
  ADD UNIQUE KEY `Emailaddress` (`Emailaddress`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tbl_city`
--
ALTER TABLE `tbl_city`
  MODIFY `IDCity` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tbl_country`
--
ALTER TABLE `tbl_country`
  MODIFY `IDCountry` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tbl_destination`
--
ALTER TABLE `tbl_destination`
  MODIFY `IDDestination` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tbl_image`
--
ALTER TABLE `tbl_image`
  MODIFY `IDImage` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tbl_status`
--
ALTER TABLE `tbl_status`
  MODIFY `IDStatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `IDUser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `tbl_city`
--
ALTER TABLE `tbl_city`
  ADD CONSTRAINT `tbl_city_ibfk_1` FOREIGN KEY (`FIDCountry`) REFERENCES `tbl_country` (`IDCountry`);

--
-- Constraints der Tabelle `tbl_destination`
--
ALTER TABLE `tbl_destination`
  ADD CONSTRAINT `tbl_destination_ibfk_1` FOREIGN KEY (`FIDCity`) REFERENCES `tbl_city` (`IDCity`),
  ADD CONSTRAINT `tbl_destination_ibfk_2` FOREIGN KEY (`FIDStatus`) REFERENCES `tbl_status` (`IDStatus`),
  ADD CONSTRAINT `tbl_destination_ibfk_3` FOREIGN KEY (`FIDUser`) REFERENCES `tbl_user` (`IDUser`);

--
-- Constraints der Tabelle `tbl_image`
--
ALTER TABLE `tbl_image`
  ADD CONSTRAINT `fk_image_destination` FOREIGN KEY (`FIDDestination`) REFERENCES `tbl_destination` (`IDDestination`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
