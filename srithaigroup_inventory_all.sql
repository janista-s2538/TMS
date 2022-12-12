-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2022 at 05:23 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `srithaigroup`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory_daily`
--

CREATE TABLE `inventory_daily` (
  `id` int(11) NOT NULL,
  `site_id` int(4) NOT NULL,
  `business_id` int(4) NOT NULL,
  `inventory_items_id` int(11) NOT NULL,
  `bring_forward` float NOT NULL DEFAULT 0,
  `purchase_id` int(11) NOT NULL,
  `received_amount` float NOT NULL DEFAULT 0,
  `used_amount` float NOT NULL DEFAULT 0,
  `balance` float NOT NULL DEFAULT 0,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '0=ปิด,1=ใช้งาน ค่าเริ่มต้น 1',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory_daily`
--

INSERT INTO `inventory_daily` (`id`, `site_id`, `business_id`, `inventory_items_id`, `bring_forward`, `purchase_id`, `received_amount`, `used_amount`, `balance`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 1, 12, 0, 40, 42, 10, '1', 1, '0000-00-00 00:00:00', '2022-11-04 10:58:12'),
(2, 1, 4, 2, 6, 0, 30, 18, 18, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 1, 4, 3, 4, 0, 10, 14, 0, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 1, 4, 4, 0, 0, 48, 48, 0, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 1, 4, 5, 0, 0, 30, 30, 0, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 1, 4, 6, 2, 0, 0, 0, 2, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 1, 4, 7, 18, 0, 15, 0, 15, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 1, 4, 8, 5, 0, 30, 0, 30, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `items_type_id` int(11) NOT NULL,
  `tier_group` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0=ไม่ระบุ,1=ยางใหม่,2=ยางนอกเก่า',
  `tier_frame` enum('0','1','2','') NOT NULL DEFAULT '0' COMMENT '0=ไม่ระบุ,1=โครงยางใหม่,2=โครงยางเก่า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `name`, `items_type_id`, `tier_group`, `tier_frame`) VALUES
(1, 'ยางใหม่ 1000-20 เรเดียล  M 789', 2, '1', '0'),
(2, 'ยางใหม่ 1100-20 เรเดียล', 2, '1', '0'),
(3, 'ยางใหม่ 295 เรเดียล', 2, '1', '0'),
(4, 'ยางหล่อ 1000-20 เรเดียล', 2, '1', '0'),
(5, 'ยางหล่อ 1100-20 ผ้าใบ', 2, '1', '0'),
(6, 'ยางหล่อ 11R;295 -22.5', 2, '1', '0'),
(7, 'ยางใน1000-20 R', 2, '1', '0'),
(8, 'ยางรองคอ', 2, '1', '0'),
(10, 'ทดดสอบ', 2, '2', '2');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_purchase`
--

CREATE TABLE `inventory_purchase` (
  `id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `date` date NOT NULL,
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `site_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `status` enum('1','2','3','9') NOT NULL COMMENT '1=กำลังดำเนินการ,2=รออนุมัติ,3=เสร็จสิ้น,9=ไม่อนุมัติ',
  `created_by` int(11) NOT NULL,
  `checker_by` int(11) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `ref_docket` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory_purchase`
--

INSERT INTO `inventory_purchase` (`id`, `code`, `date`, `sdate`, `edate`, `site_id`, `business_id`, `status`, `created_by`, `checker_by`, `remark`, `ref_docket`, `created_at`, `updated_at`) VALUES
(1, 202211001, '2022-11-04', '2022-11-04', '2023-01-31', 1, 4, '1', 66, 3, 'ทดสอบ', NULL, '2022-11-04 10:36:13', '2022-11-04 10:36:13'),
(2, 202211002, '2022-11-04', '2022-11-01', '2023-03-31', 1, 4, '1', 66, 3, 'ทดสอบขอเบิกยาง', NULL, '2022-11-04 11:02:42', '2022-11-04 11:02:42');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_purchase_items`
--

CREATE TABLE `inventory_purchase_items` (
  `id` int(11) NOT NULL,
  `inventory_purchase_id` int(11) NOT NULL,
  `inventory_items_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory_purchase_items`
--

INSERT INTO `inventory_purchase_items` (`id`, `inventory_purchase_id`, `inventory_items_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100, '2022-11-04 10:36:13', '2022-11-04 10:36:13'),
(2, 1, 2, 200, '2022-11-04 10:36:13', '2022-11-04 10:36:13'),
(3, 2, 1, 10, '2022-11-04 11:02:42', '2022-11-04 11:02:42'),
(4, 2, 2, 20, '2022-11-04 11:02:42', '2022-11-04 11:02:42'),
(5, 2, 3, 30, '2022-11-04 11:02:42', '2022-11-04 11:02:42'),
(6, 2, 4, 40, '2022-11-04 11:02:42', '2022-11-04 11:02:42'),
(7, 2, 5, 50, '2022-11-04 11:02:42', '2022-11-04 11:02:42'),
(8, 2, 6, 60, '2022-11-04 11:02:42', '2022-11-04 11:02:42'),
(9, 2, 7, 70, '2022-11-04 11:02:42', '2022-11-04 11:02:42'),
(10, 2, 8, 80, '2022-11-04 11:02:42', '2022-11-04 11:02:42');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_supply`
--

CREATE TABLE `inventory_supply` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `reg_number` varchar(10) NOT NULL,
  `miles_number` varchar(50) NOT NULL,
  `inventory_items_id` int(11) NOT NULL,
  `used_amount` float NOT NULL DEFAULT 0,
  `remark` text DEFAULT NULL,
  `inventory_daily_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory_supply`
--

INSERT INTO `inventory_supply` (`id`, `date`, `reg_number`, `miles_number`, `inventory_items_id`, `used_amount`, `remark`, `inventory_daily_id`, `created_by`, `created_at`, `updated_at`) VALUES
(2, '2022-11-04', '60-2392', '', 1, 5, 'ทดสอบจ่ายยาง', 1, 66, '2022-11-04 10:58:12', '2022-11-04 10:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `items_type`
--

CREATE TABLE `items_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items_type`
--

INSERT INTO `items_type` (`id`, `name`) VALUES
(1, 'น้ำมันหล่อลื่น/จารบี'),
(2, 'ยาง');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory_daily`
--
ALTER TABLE `inventory_daily`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_purchase`
--
ALTER TABLE `inventory_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_purchase_items`
--
ALTER TABLE `inventory_purchase_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_supply`
--
ALTER TABLE `inventory_supply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_type`
--
ALTER TABLE `items_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory_daily`
--
ALTER TABLE `inventory_daily`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_purchase`
--
ALTER TABLE `inventory_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory_purchase_items`
--
ALTER TABLE `inventory_purchase_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_supply`
--
ALTER TABLE `inventory_supply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items_type`
--
ALTER TABLE `items_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
