-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2014 at 01:17 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `groceries`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_no` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cust_no` int(11) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_no`),
  KEY `cust_no` (`cust_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `order_no` int(11) NOT NULL,
  `prod_code` varchar(20) COLLATE utf8_bin NOT NULL,
  `order_qty` int(11) NOT NULL,
  `order_sum` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_no`,`prod_code`),
  UNIQUE KEY `prod_code` (`prod_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `prod_code` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` varchar(500) COLLATE utf8_bin NOT NULL,
  `prod_group` char(1) COLLATE utf8_bin NOT NULL,
  `list_price` decimal(10,2) NOT NULL,
  `qty_on_hand` int(11) NOT NULL,
  `procur_level` int(11) NOT NULL,
  `procur_qty` int(11) NOT NULL,
  PRIMARY KEY (`prod_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`prod_code`, `name`, `description`, `prod_group`, `list_price`, `qty_on_hand`, `procur_level`, `procur_qty`) VALUES
('001_TOM', 'Tomato', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'M', '3.00', 0, 10, 10),
('002_CUC', 'Cucumber', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem\r\nTrtrwe sdfs dfwe wer sdsvsdfsd', 'M', '1.00', 0, 5, 5),
('003_CRT', 'Carrot', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem\r\n\r\nCarrots', 'M', '1.50', 0, 10, 15),
('004_CLF', 'Cauliflower', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'L', '3.00', 5, 10, 5),
('005_LTC', 'Lettuce', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'L', '2.00', 10, 10, 10),
('006_RCB', 'Red Cabbage', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'L', '5.00', 10, 7, 3),
('007_WCB', 'Cabbage', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum.', 'L', '2.00', 20, 5, 10),
('008_BRC', 'Broccoli', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem\r\n\r\nTRwewrsfdfs sdf werrtt dfg dfg wewe weerdfg', 'L', '7.50', 5, 10, 5),
('p00', 'test', 'description', 'I', '151.00', 6, 3, 20),
('p001', 'product name', 'product description', 'A', '30.50', 3, 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `prod_code` varchar(20) COLLATE utf8_bin NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `email` varchar(50) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `quant_sofar` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`prod_code`,`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `cust_no` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `password` varchar(50) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `cust_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `email` varchar(50) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `street` varchar(50) COLLATE utf8_bin NOT NULL,
  `town` varchar(20) COLLATE utf8_bin NOT NULL,
  `post_code` varchar(10) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `cr_limit` decimal(10,0) NOT NULL,
  `curr_bal` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cust_no`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cust_no`) REFERENCES `users` (`cust_no`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
