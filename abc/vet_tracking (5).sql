-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 14 Oca 2025, 01:01:49
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `vet_tracking`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `examinations`
--

CREATE TABLE `examinations` (
  `pet_id` int(11) NOT NULL,
  `exam_date` date NOT NULL,
  `medication` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `examinations`
--

INSERT INTO `examinations` (`pet_id`, `exam_date`, `medication`, `start_date`, `end_date`) VALUES
(0, '0000-00-00', '0', '0000-00-00', '2025-01-16'),
(0, '0000-00-00', '0', '0000-00-00', '2025-01-09'),
(0, '0000-00-00', '0', '0000-00-00', '2025-01-13'),
(0, '2025-01-18', 'abc', '2025-01-19', '2025-01-26'),
(7, '2025-01-19', 'iğne', '2025-01-20', '2025-01-25');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `p`
--

CREATE TABLE `p` (
  `pet_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pets`
--

CREATE TABLE `pets` (
  `pet_id` int(255) NOT NULL,
  `exam_date` varchar(255) NOT NULL,
  `medication` varchar(255) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_name` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `species` varchar(255) NOT NULL,
  `breed` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `treatment_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `pets`
--

INSERT INTO `pets` (`pet_id`, `exam_date`, `medication`, `start_date`, `end_date`, `user_id`, `pet_name`, `id`, `species`, `breed`, `age`, `weight`, `treatment_status`) VALUES
(3, '', '', '', '', 2, 'mehmet', 0, 'türk', 'türk', 20, 100, 'Completed'),
(4, '', '', '', '', 2, 'ramazan', 0, 'türk', 'we', 56, 102, 'Pending'),
(6, '', '', '', '', 2, 'berkay', 0, 'türk', 'türk', 20, 85, 'Completed'),
(7, '', '', '', '', 5, 'bulut', 0, 'sibirya kurdu', 'male', 2, 30, 'Pending');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `treatment_status`
--

CREATE TABLE `treatment_status` (
  `treatment` varchar(255) NOT NULL,
  `pet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `is_admin`) VALUES
(2, 'ahmet1', '$2y$10$n480CZaumxaW2FUcwnLxVuqjjlLExrERunj4K.ztueSWxgrOQN4d.', 0),
(3, 'admin', '$2y$10$rLz486h7YHdgwt92asdm.eEitpdZBBMOQvbxycgaqxWGHtl.b2NC6', 1),
(5, 'berkay', '$2y$10$HyRpmpjbI3wouNTDCwvg4ejRIeEZ2RoLhZKTcmS3pAlMpvUHRh062', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`pet_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `pets`
--
ALTER TABLE `pets`
  MODIFY `pet_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
