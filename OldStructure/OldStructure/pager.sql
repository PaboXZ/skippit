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

CREATE TABLE IF NOT EXISTS `connection_user_thread` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `view_power` int(4) NOT NULL DEFAULT 5,
  `is_owner` tinyint(1) NOT NULL DEFAULT 0,
  `edit_permission` tinyint(1) NOT NULL DEFAULT 0,
  `delete_permission` tinyint(1) NOT NULL DEFAULT 0,
  `create_power` int(4) NOT NULL DEFAULT 0,
  `complete_permission` tinyint(1) NOT NULL DEFAULT 0
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `task_data`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(127) NOT NULL,
  `content` varchar(2024) NOT NULL,
  `create_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `edit_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `power` int(4) NOT NULL DEFAULT 1,
  `is_complete` tinyint(1) NOT NULL DEFAULT 0,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `thread_data`
--

CREATE TABLE `threads` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_data`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(256) NOT NULL,
  `name` varchar(64) NOT NULL,
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
