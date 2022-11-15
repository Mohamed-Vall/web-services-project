-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2022 at 06:20 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ufc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `liveId` int(11) NOT NULL,
  `fightId` int(11) NOT NULL,
  `stats` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `fighters`
--

CREATE TABLE `fighters` (
  `fighterId` int(11) NOT NULL,
  `name` varchar(52) NOT NULL,
  `records` double NOT NULL,
  `weightClass` varchar(52) NOT NULL,
  `country` varchar(52) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `fights`
--

CREATE TABLE `fights` (
  `fightID` int(11) NOT NULL,
  `fighter1ID` int(11) NOT NULL,
  `fighter2ID` int(11) NOT NULL,
  `odds` double NOT NULL,
  `schedule` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `finalresults`
--

CREATE TABLE `finalresults` (
  `resultID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `finalStats` longtext NOT NULL,
  `result` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`liveId`),
  ADD KEY `events_FK_1` (`fightId`);

--
-- Indexes for table `fighters`
--
ALTER TABLE `fighters`
  ADD PRIMARY KEY (`fighterId`);

--
-- Indexes for table `fights`
--
ALTER TABLE `fights`
  ADD PRIMARY KEY (`fightID`),
  ADD KEY `fights_FK_1` (`fighter1ID`),
  ADD KEY `fights_FK_2` (`fighter2ID`);

--
-- Indexes for table `finalresults`
--
ALTER TABLE `finalresults`
  ADD PRIMARY KEY (`resultID`),
  ADD KEY `finalresults_FK_1` (`eventID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `liveId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fighters`
--
ALTER TABLE `fighters`
  MODIFY `fighterId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fights`
--
ALTER TABLE `fights`
  MODIFY `fightID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finalresults`
--
ALTER TABLE `finalresults`
  MODIFY `resultID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_FK_1` FOREIGN KEY (`fightId`) REFERENCES `fights` (`fightID`);

--
-- Constraints for table `fights`
--
ALTER TABLE `fights`
  ADD CONSTRAINT `fights_FK_1` FOREIGN KEY (`fighter1ID`) REFERENCES `fighters` (`fighterId`),
  ADD CONSTRAINT `fights_FK_2` FOREIGN KEY (`fighter2ID`) REFERENCES `fighters` (`fighterId`);

--
-- Constraints for table `finalresults`
--
ALTER TABLE `finalresults`
  ADD CONSTRAINT `finalresults_FK_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`liveId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
