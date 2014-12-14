-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2014 at 10:41 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_cart`(IN `icartid` int, IN `icust_no` int)
    DETERMINISTIC
begin
	SET @cartid = icartid;
	SET @cust_no = icust_no;
	if @cust_no < 0 then
		SET @cust_no = NULL;
	end if;
	
	if icartid<0 then
		insert into carts (cust_no) values (@cust_no);
		SET @cartid = LAST_INSERT_ID();
	else
		if (select sum(IsActive) from carts where cartid=@cartid)=0 then
			insert into carts (cust_no) values (@cust_no);
			SET @cartid = LAST_INSERT_ID();
        else
			update carts set cust_no = @cust_no where cartid=@cartid;
		end if;
	end if;
	
	select * from carts where cartid = @cartid;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_login`(IN `ilogin` varchar(50), IN `ipassword` varchar(50))
    DETERMINISTIC
begin
    SET @isAuth = 0;
	SET @custNo = -1;
	
	SET @isAuth = (select count(*) from Users where login=ilogin and password=ipassword);
	if @isAuth > 0 then
		SET @custNo = (select cust_no from Users where login=ilogin and password=ipassword limit 1);
	end if;
	
	select @isAuth as IsAuth, @custNo as custNum;
    
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE IF NOT EXISTS `carts` (
  `cartid` int(11) NOT NULL AUTO_INCREMENT,
  `cust_no` int(11) DEFAULT NULL,
  `CreatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IsActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cartid`),
  KEY `cust_no` (`cust_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cartid`, `cust_no`, `CreatedOn`, `IsActive`) VALUES
(1, 1, '2014-12-14 21:34:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE IF NOT EXISTS `cart_details` (
  `cartid` int(11) NOT NULL,
  `prod_code` varchar(20) COLLATE utf8_bin NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cartid`,`prod_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
  `prod_group` char(1) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `list_price` decimal(10,2) NOT NULL,
  `qty_on_hand` int(11) NOT NULL,
  `procur_level` int(11) NOT NULL,
  `procur_qty` int(11) NOT NULL,
  `imageUrl` varchar(100) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  PRIMARY KEY (`prod_code`),
  KEY `prod_group` (`prod_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`prod_code`, `name`, `description`, `prod_group`, `list_price`, `qty_on_hand`, `procur_level`, `procur_qty`, `imageUrl`) VALUES
('S_001_TUN', 'Tuna', 'Tuna fish', 'S', '151.00', 6, 3, 20, NULL),
('S_002_OCT', 'Octopus', 'Fresh octopus fished at Calymnos', 'S', '30.50', 3, 2, 10, NULL),
('V_001_TOM', 'Tomato', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'V', '3.00', 0, 10, 10, '/images/products/tomato.jpg'),
('V_002_CUC', 'Cucumber', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem\r\nTrtrwe sdfs dfwe wer sdsvsdfsd', 'V', '1.00', 0, 5, 5, '/images/products/cucumber.jpg'),
('V_003_CRT', 'Carrot', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem\r\n\r\nCarrots', 'V', '1.50', 0, 10, 15, '/images/products/carrots.jpg'),
('V_004_CLF', 'Cauliflower', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'V', '3.00', 5, 10, 5, '/images/products/cauliflower.jpg'),
('V_005_LTC', 'Lettuce', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'V', '2.00', 10, 10, 10, '/images/products/lettuce.jpg'),
('V_006_RCB', 'Red Cabbage', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'V', '5.00', 10, 7, 3, '/images/products/cabbage_red.jpg'),
('V_007_WCB', 'Cabbage', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum.', 'V', '2.00', 20, 5, 10, '/images/products/cabbage_white.jpg'),
('V_008_BRC', 'Broccoli', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem\r\n\r\nTRwewrsfdfs sdf werrtt dfg dfg wewe weerdfg', 'V', '7.50', 5, 10, 5, '/images/products/broccoli.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_groups`
--

CREATE TABLE IF NOT EXISTS `product_groups` (
  `group_code` char(1) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `group_name` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`group_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `product_groups`
--

INSERT INTO `product_groups` (`group_code`, `group_name`) VALUES
('C', 'Cerial'),
('D', 'Dairy'),
('F', 'Fruit'),
('M', 'Meat'),
('P', 'Pet'),
('S', 'Seafood'),
('V', 'Vegetables');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`cust_no`, `login`, `password`, `cust_name`, `email`, `street`, `town`, `post_code`, `cr_limit`, `curr_bal`) VALUES
(1, 'angello', '1234', 'Angello Karageorgos', 'ibz-angello@hotmail.com', 'Ksenofronos 9A, Zwgrafou', 'Athens', '15773', '200', '0');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`cust_no`) REFERENCES `users` (`cust_no`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cust_no`) REFERENCES `users` (`cust_no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`prod_group`) REFERENCES `product_groups` (`group_code`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
