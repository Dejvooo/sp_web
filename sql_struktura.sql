-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Stř 10. pro 2014, 16:56
-- Verze serveru: 5.6.20
-- Verze PHP: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `nba`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `hrac`
--

CREATE TABLE IF NOT EXISTS `hrac` (
`id_hrac` int(5) NOT NULL,
  `jmeno` varchar(25) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(25) COLLATE utf8_czech_ci NOT NULL,
  `cislo` int(2) NOT NULL,
  `pozice` varchar(5) COLLATE utf8_czech_ci NOT NULL,
  `datum_narozeni` date NOT NULL,
  `vyska` varchar(5) COLLATE utf8_czech_ci NOT NULL,
  `vaha` varchar(5) COLLATE utf8_czech_ci NOT NULL,
  `draft_tym` int(2) NOT NULL,
  `draft_rok` year(4) NOT NULL,
  `draft_kolo` int(1) NOT NULL,
  `draft_pozice` int(3) NOT NULL,
  `univerzita` varchar(100) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=163 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `komentar`
--

CREATE TABLE IF NOT EXISTS `komentar` (
`id_komentar` int(10) NOT NULL,
  `uzivatel` int(5) NOT NULL,
  `hrac` int(5) NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `datum` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=141 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `prestup`
--

CREATE TABLE IF NOT EXISTS `prestup` (
`id_prestup` int(10) NOT NULL,
  `hrac` int(5) NOT NULL,
  `tym` int(2) NOT NULL,
  `sezona` varchar(9) COLLATE utf8_czech_ci NOT NULL,
  `aktualni` int(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=169 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `tym`
--

CREATE TABLE IF NOT EXISTS `tym` (
`id_tym` int(2) NOT NULL,
  `mesto` varchar(25) COLLATE utf8_czech_ci NOT NULL,
  `nazev` varchar(25) COLLATE utf8_czech_ci NOT NULL,
  `rok_zalozeni` year(4) NOT NULL,
  `stadion` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `kapacita` int(5) NOT NULL,
  `trener` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `web` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `konference` varchar(8) COLLATE utf8_czech_ci NOT NULL,
  `divize` varchar(13) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `uzivatel`
--

CREATE TABLE IF NOT EXISTS `uzivatel` (
`id_uzivatel` int(5) NOT NULL,
  `nick` varchar(25) COLLATE utf8_czech_ci NOT NULL,
  `heslo` varchar(25) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `datum_registrace` date NOT NULL,
  `prava` int(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=20 ;

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `hrac`
--
ALTER TABLE `hrac`
 ADD PRIMARY KEY (`id_hrac`), ADD KEY `draft_tym` (`draft_tym`);

--
-- Klíče pro tabulku `komentar`
--
ALTER TABLE `komentar`
 ADD PRIMARY KEY (`id_komentar`), ADD KEY `uzivatel` (`uzivatel`), ADD KEY `hrac` (`hrac`);

--
-- Klíče pro tabulku `prestup`
--
ALTER TABLE `prestup`
 ADD PRIMARY KEY (`id_prestup`), ADD KEY `tym` (`tym`), ADD KEY `hrac` (`hrac`);

--
-- Klíče pro tabulku `tym`
--
ALTER TABLE `tym`
 ADD PRIMARY KEY (`id_tym`);

--
-- Klíče pro tabulku `uzivatel`
--
ALTER TABLE `uzivatel`
 ADD PRIMARY KEY (`id_uzivatel`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `hrac`
--
ALTER TABLE `hrac`
MODIFY `id_hrac` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=163;
--
-- AUTO_INCREMENT pro tabulku `komentar`
--
ALTER TABLE `komentar`
MODIFY `id_komentar` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=141;
--
-- AUTO_INCREMENT pro tabulku `prestup`
--
ALTER TABLE `prestup`
MODIFY `id_prestup` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=169;
--
-- AUTO_INCREMENT pro tabulku `tym`
--
ALTER TABLE `tym`
MODIFY `id_tym` int(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT pro tabulku `uzivatel`
--
ALTER TABLE `uzivatel`
MODIFY `id_uzivatel` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `hrac`
--
ALTER TABLE `hrac`
ADD CONSTRAINT `hrac_ibfk_1` FOREIGN KEY (`draft_tym`) REFERENCES `tym` (`id_tym`);

--
-- Omezení pro tabulku `komentar`
--
ALTER TABLE `komentar`
ADD CONSTRAINT `komentar_ibfk_1` FOREIGN KEY (`uzivatel`) REFERENCES `uzivatel` (`id_uzivatel`),
ADD CONSTRAINT `komentar_ibfk_2` FOREIGN KEY (`hrac`) REFERENCES `hrac` (`id_hrac`);

--
-- Omezení pro tabulku `prestup`
--
ALTER TABLE `prestup`
ADD CONSTRAINT `prestup_ibfk_2` FOREIGN KEY (`hrac`) REFERENCES `hrac` (`id_hrac`),
ADD CONSTRAINT `prestup_ibfk_3` FOREIGN KEY (`tym`) REFERENCES `tym` (`id_tym`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
