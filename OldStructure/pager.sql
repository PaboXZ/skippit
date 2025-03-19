-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 15 Lut 2023, 11:27
-- Wersja serwera: 10.4.25-MariaDB
-- Wersja PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `pager`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `connection_user_thread`
--

CREATE TABLE `connection_user_thread` (
  `connection_id` int(11) NOT NULL,
  `connection_user_id` int(11) NOT NULL,
  `connection_thread_id` int(11) NOT NULL,
  `connection_view_power` int(4) NOT NULL DEFAULT 5,
  `connection_is_owner` tinyint(1) NOT NULL DEFAULT 0,
  `connection_edit_permission` tinyint(1) NOT NULL DEFAULT 0,
  `connection_delete_permission` tinyint(1) NOT NULL DEFAULT 0,
  `connection_create_power` int(4) NOT NULL DEFAULT 0,
  `connection_complete_permission` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `task_data`
--

CREATE TABLE `task_data` (
  `task_id` int(11) NOT NULL,
  `task_thread_id` int(11) NOT NULL,
  `task_user_id` int(11) NOT NULL,
  `task_title` varchar(127) NOT NULL,
  `task_content` varchar(2024) NOT NULL,
  `task_create_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `task_edit_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `task_power` int(4) NOT NULL DEFAULT 1,
  `task_is_complete` tinyint(1) NOT NULL DEFAULT 0,
  `task_is_pinned` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `thread_data`
--

CREATE TABLE `thread_data` (
  `thread_id` int(11) NOT NULL,
  `thread_owner_id` int(11) NOT NULL,
  `thread_name` varchar(32) NOT NULL,
  `thread_version` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_data`
--

CREATE TABLE `user_data` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(64) NOT NULL,
  `user_password` varchar(256) NOT NULL,
  `user_name` varchar(64) NOT NULL,
  `user_is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `user_last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `user_data`
--

INSERT INTO `user_data` (`user_id`, `user_email`, `user_password`, `user_name`, `user_is_admin`, `user_last_active`) VALUES
(1, 'test@test.test', '$2y$10$vCMIUVP24zCofbcSal8Jt.zbuS20h/wTEAg1chcUaNy3krESzQvn6', 'Test', 0, '2023-02-15 10:26:20');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `connection_user_thread`
--
ALTER TABLE `connection_user_thread`
  ADD PRIMARY KEY (`connection_id`);

--
-- Indeksy dla tabeli `task_data`
--
ALTER TABLE `task_data`
  ADD PRIMARY KEY (`task_id`);

--
-- Indeksy dla tabeli `thread_data`
--
ALTER TABLE `thread_data`
  ADD PRIMARY KEY (`thread_id`);

--
-- Indeksy dla tabeli `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `connection_user_thread`
--
ALTER TABLE `connection_user_thread`
  MODIFY `connection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `task_data`
--
ALTER TABLE `task_data`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `thread_data`
--
ALTER TABLE `thread_data`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `user_data`
--
ALTER TABLE `user_data`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
