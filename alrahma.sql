-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2024 at 08:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alrahma`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

-- --------------------------------------------------------

--
-- Table structure for table `benefactor`
--

CREATE TABLE `benefactor` (
  `id` int(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(150) NOT NULL,
  `goal_amount` int(150) NOT NULL,
  `image` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `benefactor`
--

INSERT INTO `benefactor` (`id`, `name`, `description`, `goal_amount`, `image`) VALUES
(12, 'Benefactor', 'Empowering communities through the generosity of our benefactors. Together, we create brighter futures.', 1, '64.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `discharge`
--

CREATE TABLE `discharge` (
  `id` int(255) NOT NULL,
  `user_id` int(150) NOT NULL,
  `pid` int(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `goal_amount` int(150) NOT NULL,
  `user_donation` int(150) NOT NULL,
  `image` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discharge`
--

INSERT INTO `discharge` (`id`, `user_id`, `pid`, `name`, `goal_amount`, `user_donation`, `image`) VALUES
(218, 0, 24, 'School ', 5000, 12, '../uploaded_img/1716794963_053.png'),
(221, 0, 24, 'School ', 5000, 12, '../uploaded_img/1716794963_053.png');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(255) NOT NULL,
  `user_id` int(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `number` int(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `method` varchar(150) NOT NULL,
  `total_projects` varchar(150) NOT NULL,
  `total_amounts` int(150) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `name`, `number`, `email`, `method`, `total_projects`, `total_amounts`, `placed_on`) VALUES
(70, 4, 'aaysha', 9876543, 'aaysha@gmail.com', 'Visa Card', 'Water Well (488 units)', 488, '2024-05-27'),
(71, 4, 'aaysha', 98765432, 'aaysha@gmail.com', 'Visa Card', 'Kafala (12 units)', 12, '2024-06-02'),
(72, 4, 'aaysha', 3711, 'aaysha@gmail.com', 'Visa Card', 'School  (12 units), School  (12 units), Kafala (12 units), Masjid Omar (23 units), School  (34 units)', 93, '2024-06-02'),
(73, 4, 'aaysha', 3711, 'aaysha@gmail.com', 'Visa Card', 'Kafala (12 units)', 12, '2024-06-02'),
(74, 4, 'sumaya', 3711, 'aays@gmail.com', 'Visa Card', 'Kafala (12 units)', 12, '2024-06-02'),
(75, 4, 'sumaya', 3711, 'aays@gmail.com', 'Visa Card', 'Kafala (23 units)', 23, '2024-06-02'),
(76, 4, 'admin', 3711, 'aaysha@gmail.com', 'Visa Card', 'Kafala (10 units)', 10, '2024-06-02'),
(77, 4, 'Mariam', 3711, 'mariam@gmail.com', 'Master Card', 'Kafala (13 units), School  (34 units), Tailor (43 units), Tailor (43 units), School  (23 units), Zakat (36 units), Sadaqah (34 units)', 226, '2024-06-02'),
(78, 4, 'aaysha', 3711, 'aaysha@gmail.com', 'Visa Card', 'Kafala (12 units)', 12, '2024-06-02'),
(79, 4, 'sumaya', 3711, 'aaysha@gmail.com', 'Visa Card', 'School  (10 units)', 10, '2024-06-02'),
(80, 4, 'aaysha', 1111134557, 'aaysha@gmail.com', 'Visa Card', 'School  (67 units)', 67, '2024-06-03');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(50) NOT NULL,
  `user_id` int(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `number` varchar(150) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(2, 4, 'aaysha', 'aaysha@gmail.com', '0987654', 'hi');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(255) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(150) NOT NULL,
  `image` varchar(150) NOT NULL,
  `goal_amount` int(11) NOT NULL DEFAULT 0,
  `current_amount` int(11) NOT NULL DEFAULT 0,
  `status` enum('ongoing','completed') NOT NULL DEFAULT 'ongoing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `image`, `goal_amount`, `current_amount`, `status`) VALUES
(20, 'Masjid Omar', 'Imagine a serene village where the call to prayer resonates through the air, bringing the community together in faith and worship.', '../uploaded_img/1716746148_87.jpg', 1000, 95, 'ongoing'),
(21, 'Kafala', 'Transforming Lives, One Kafala at a Time: Join Us in Providing Hope and Support to Those in Need.', '../uploaded_img/1716794792_834.jpg', 10000, 85, 'ongoing'),
(22, 'Water Well', 'Quenching Thirst, Nourishing Communities: Building Sustainable Futures Drop by Drop.', '09.jpg', 500, 500, 'completed'),
(24, 'School ', 'Empowering Minds, Building Dreams: Creating Brighter Tomorrows Through Education.', '../uploaded_img/1716794963_053.png', 5000, 183, 'ongoing'),
(25, 'Tailor', 'Stitching Hope, Crafting Opportunities: Empowering Lives One Thread at a Time.', '../uploaded_img/1716819686_984.png', 10000, 86, 'ongoing');

-- --------------------------------------------------------

--
-- Table structure for table `sadaqa`
--

CREATE TABLE `sadaqa` (
  `id` int(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(290) NOT NULL,
  `image` varchar(150) NOT NULL,
  `goal_amount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sadaqa`
--

INSERT INTO `sadaqa` (`id`, `name`, `description`, `image`, `goal_amount`) VALUES
(8, 'Sadaqah', 'Spreading kindness and compassion, one act at a time.', '23.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(4, 'aaysha', 'aaysha@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(255) NOT NULL,
  `user_id` int(150) NOT NULL,
  `pid` int(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `goal_amount` int(150) NOT NULL,
  `user_donation` int(150) NOT NULL,
  `image` varchar(150) NOT NULL,
  `table_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zaka`
--

CREATE TABLE `zaka` (
  `id` int(255) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(150) NOT NULL,
  `image` varchar(150) NOT NULL,
  `goal_amount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zaka`
--

INSERT INTO `zaka` (`id`, `name`, `description`, `image`, `goal_amount`) VALUES
(9, 'Zakat', 'Purifying wealth, uplifting lives, and fostering equality.', '24.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `benefactor`
--
ALTER TABLE `benefactor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discharge`
--
ALTER TABLE `discharge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sadaqa`
--
ALTER TABLE `sadaqa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zaka`
--
ALTER TABLE `zaka`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `benefactor`
--
ALTER TABLE `benefactor`
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `discharge`
--
ALTER TABLE `discharge`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sadaqa`
--
ALTER TABLE `sadaqa`
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `zaka`
--
ALTER TABLE `zaka`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
