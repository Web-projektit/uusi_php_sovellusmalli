-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 02.10.2023 klo 10:03
-- Palvelimen versio: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `neilikka`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `rememberme_tokens`
--

CREATE TABLE `rememberme_tokens` (
  `id` int(11) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashed_validator` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `rememberme_tokens`
--

INSERT INTO `rememberme_tokens` (`id`, `selector`, `hashed_validator`, `user_id`, `expiry`) VALUES
(10, 'b268b008e45b3f7b6a87bd801527d768', '$2y$10$RsVSczK/JuE8OqeR6fSOkuFc3w3nE6EL6LV81Mt3k1K3DXGj4pLZm', 17, '2023-10-25 14:37:20'),
(13, '17e760c40fed4bd1e733ff15a25b7c16', '$2y$10$Hdba/iXyEu8VMdn0ibUh8OMMtlCc.OMQFQBEeJNEIBqZ/O0JX2HZ6', 25, '2023-10-27 14:59:04');

-- --------------------------------------------------------

--
-- Rakenne taululle `resetpassword_tokens`
--

CREATE TABLE `resetpassword_tokens` (
  `users_id` int(9) NOT NULL,
  `token` varchar(255) NOT NULL,
  `voimassa` date NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `resetpassword_tokens`
--

INSERT INTO `resetpassword_tokens` (`users_id`, `token`, `voimassa`, `updated`) VALUES
(17, '98358b4487bd52835c20610c99b34e159190f8a74797fc55805ded6427ceacf7ed38a455a06e10b26c46a7988ab5cd8a89ee', '2023-09-26', '2023-09-26 08:51:20');

-- --------------------------------------------------------

--
-- Rakenne taululle `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NOT NULL,
  `value` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `roles`
--

INSERT INTO `roles` (`id`, `name`, `value`) VALUES
(1, 'user', 1),
(2, 'mainuser', 2),
(3, 'admin', 4);

-- --------------------------------------------------------

--
-- Rakenne taululle `signup_tokens`
--

CREATE TABLE `signup_tokens` (
  `token` varchar(255) NOT NULL,
  `users_id` int(9) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Rakenne taululle `users`
--

CREATE TABLE `users` (
  `id` int(9) NOT NULL,
  `firstname` varchar(25) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `lastname` varchar(50) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobilenumber` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `is_active` enum('0','1') NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `role` int(4) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Vedos taulusta `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `mobilenumber`, `password`, `token`, `is_active`, `image`, `role`, `created`, `updated`) VALUES
(17, 'Jukka', 'Aula', 'jukka.aula@omnia.fi', '358501234567', '$2y$10$JDOHI7AadNlFds84fGQK3ORFZYMQP32dcvbLiFyURSSEntCKWyeOS', '3316307165dc37153a6563cab4acb82f', '1', 'omnia_logo_56bd6d.png', 3, '2023-02-16 18:30:57', '2023-09-27 10:20:13'),
(25, 'Tapio', 'Aula', 'jukka.aula@kolumbus.fi', '', '$2y$10$Xvjd7WqGBCPD6dsRdPyV5OXjp6irmoMd3F2/fBYA9MDs31KGo7TfO', '', '1', NULL, 1, '2023-09-27 13:06:53', '2023-09-27 10:20:37'),
(27, 'Matti', 'Meikäläinen', 'matti.meikalainen@yritys.fi', '', '$2y$10$unG9zucXvyL3WAGyO7F7hO75u2hzfutVgt660KkeQBwHTU82z13M6', '', '1', 'omniavalkea_tausta_bd59a8.png', 1, '2023-09-28 13:02:49', '2023-10-02 06:57:43');

-- --------------------------------------------------------

--
-- Rakenne taululle `yhteydenotot`
--

CREATE TABLE `yhteydenotot` (
  `id` int(9) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NOT NULL,
  `email` varchar(255) NOT NULL,
  `aihe` varchar(25) NOT NULL,
  `viesti` text NOT NULL,
  `tilaus` tinyint(1) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `yhteydenotot`
--

INSERT INTO `yhteydenotot` (`id`, `name`, `email`, `aihe`, `viesti`, `tilaus`, `created`) VALUES
(1, 'Uusi suomi', 'jukka.aula@kolumbus.fi', 'aihe', 'kläklä', 0, '2023-03-13 20:01:55'),
(2, 'Uusi suomi', 'jukka.aula@kolumbus.fi', 'aihe', 'kläklä', 0, '2023-03-13 20:16:53'),
(3, 'Uusi suomi', 'jukka.aula@kolumbus.fi', 'aihe', 'kläklä', 0, '2023-03-13 20:17:58'),
(4, 'Uusi suomi', 'jukka.aula@kolumbus.fi', 'aihe', 'kläklä', 0, '2023-03-13 20:18:27'),
(5, 'Uusi suomi', 'jukka.aula@kolumbus.fi', 'aihe', 'kläklä', 0, '2023-03-13 20:19:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rememberme_tokens`
--
ALTER TABLE `rememberme_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `resetpassword_tokens`
--
ALTER TABLE `resetpassword_tokens`
  ADD PRIMARY KEY (`users_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signup_tokens`
--
ALTER TABLE `signup_tokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `yhteydenotot`
--
ALTER TABLE `yhteydenotot`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rememberme_tokens`
--
ALTER TABLE `rememberme_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `yhteydenotot`
--
ALTER TABLE `yhteydenotot`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `rememberme_tokens`
--
ALTER TABLE `rememberme_tokens`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Rajoitteet taululle `resetpassword_tokens`
--
ALTER TABLE `resetpassword_tokens`
  ADD CONSTRAINT `resetpassword_tokens_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Rajoitteet taululle `signup_tokens`
--
ALTER TABLE `signup_tokens`
  ADD CONSTRAINT `signup_tokens_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
