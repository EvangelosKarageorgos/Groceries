-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2014 at 12:22 PM
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `cancel_order`(IN `iorder_no` INT)
    NO SQL
begin
	if exists (select * from orders where order_no-iorder_no and is_valid=1 and is_completed=0) then
    begin
    	update order_details od inner join products p on p.prod_code=od.prod_code set p.qty_on_hand = p.qty_on_hand + od.order_qty where od.order_no=iorder_no;
        update orders o inner join users u on u.cust_no=o.cust_no set u.curr_bal = u.curr_bal - o.total_cost, o.is_valid=0 where o.order_no=iorder_no;
    end;
    end if;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_transaction`(IN `iorder_no` INT, IN `icreditcard_no` VARCHAR(50))
    NO SQL
begin
	insert into transactions(order_no, creditcard_no, transaction_sum) values (iorder_no, icreditcard_no, (select total_cost from orders where order_no=iorder_no));
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_cart`(IN `icartid` INT, IN `icust_no` INT)
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
    
	update cart_details cd inner join products p on p.prod_code = cd.prod_code set cd.qty = if(p.qty_on_hand<cd.qty, p.qty_on_hand, cd.qty) where cd.cartid=@cartid;
	delete from cart_details where cartid=@cartid and qty<=0;
	
	select c.cartid as cart_id, c.cust_no, cd.* from carts c left join cart_details cd on cd.cartid=c.cartid where c.cartid = @cartid;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `supply_product`(IN `isupplier_id` INT, IN `iprod_code` VARCHAR(50))
    MODIFIES SQL DATA
begin
	if not exists (select * from suppliers where supplier_id=isupplier_id and prod_code=iprod_code) then
    begin
    	insert into suppliers (supplier_id, prod_code, supplier_name, email, quant_sofar) (select supplier_id, iprod_code as prod_code, supplier_name, email, 0 as quant_sofar from suppliers where supplier_id=isupplier_id limit 1);
    end;
    end if;
	set @qty = (select procur_qty from products where prod_code=iprod_code);
	update suppliers set quant_sofar = quant_sofar + @qty where supplier_id=isupplier_id and prod_code=iprod_code;
    update products set qty_on_hand = qty_on_hand + @qty where prod_code=iprod_code;
    
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_login`(IN `ilogin` VARCHAR(50), IN `ipassword` VARCHAR(50))
    DETERMINISTIC
begin
    SET @isAuth = 0;
    SET @isAdmin = 0;
	SET @custNo = -1;
	
	SET @isAuth = (select count(*) from Users where login=ilogin and password=ipassword);
	if @isAuth > 0 then
		SET @custNo = (select cust_no from Users where login=ilogin and password=ipassword limit 1);
		SET @isAdmin = (select IsAdmin from Users where cust_no=@custNo limit 1);
	end if;
	
	select @isAuth as IsAuth, @isAdmin as IsAdmin, @custNo as custNum;
    
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_register`(IN `ilogin` VARCHAR(50), IN `ipass` VARCHAR(50), IN `ifullname` VARCHAR(50) CHARSET utf8, IN `iemail` VARCHAR(50), IN `istreet` VARCHAR(50) CHARSET utf8, IN `itown` VARCHAR(20) CHARSET utf8, IN `ipostcode` VARCHAR(10))
    NO SQL
begin
    SET @existingLogin = 0;
	SET @custNo = -1;
	
	SET @existingLogin = (select count(*) from Users where login=ilogin);
	if (@existingLogin = 0) then 
    begin
		INSERT INTO Users (login, password, cust_name, email, street, town, post_code, cr_limit, curr_bal)
		VALUES (ilogin, ipass, ifullname, iemail, istreet, itown, ipostcode, 200, 0);
		SET @custNo = LAST_INSERT_ID();		
	end;
	end if;
	
	select @isAuth as IsAuth, @custNo as custNum;
    
end$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `add_to_cart`(`icartid` INT, `iprod_code` VARCHAR(20), `iqty` INT) RETURNS int(10)
    MODIFIES SQL DATA
begin
	
    if not exists (select * from products where prod_code=iprod_code) then
    begin
    	return 0;
    end;
    end if;
    
    SET @stock = (select qty_on_hand from products where prod_code=iprod_code);
	SET @quantityAlready = ifnull((select qty from cart_details where cartid=icartid and prod_code=iprod_code), 0);

    IF @quantityAlready = 0 and iqty<0 THEN
    BEGIN
    	return 0;
    END;
    ELSE
    BEGIN
        IF @stock < iqty + @quantityAlready THEN
            SET @qty = @stock - @quantityAlready;
        ELSE
            SET @qty = iqty;
        END IF;
        
        IF @qty + @quantityAlready >0 THEN
            BEGIN
                IF NOT EXISTS (select * from cart_details where cartid=icartid and prod_code=iprod_code) THEN
                    BEGIN
                        insert into cart_details (cartid, prod_code, qty) values(icartid, iprod_code, @qty);
                    END;
                ELSE
                    BEGIN
                        update cart_details set qty=(qty+@qty) where cartid=icartid and prod_code=iprod_code;
                    END;
                END IF;
            END;
        ELSE
            BEGIN
				SET @qty = -@quantityAlready;
                delete from cart_details where cartid=icartid and prod_code=iprod_code;
            END;
        END IF;
        return @qty;
    END;
    END iF;
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `complete_order`(`iorder_no` INT) RETURNS tinyint(1)
    NO SQL
begin
	if exists (select * from orders o inner join transactions t on t.order_no=o.order_no where o.order_no=iorder_no and o.is_valid=1 and o.is_completed=0) then
    begin
		update orders o inner join transactions t on t.order_no=o.order_no inner join users u on u.cust_no=o.cust_no set t.is_validated=1, o.is_completed=1, u.cr_limit = u.cr_limit + t.transaction_sum * 0.1, u.curr_bal = u.curr_bal - t.transaction_sum where o.order_no=iorder_no;
        return 1;
    end;
    else
    begin
    	return 0;
    end;
    end if;
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `convert_cart_to_order`(`icartid` INT) RETURNS int(11)
    MODIFIES SQL DATA
begin
	if exists (select * from carts where cartid=icartid) then
	begin
		set @cust_no = (select cust_no from carts where cartid=icartid);
        
        update cart_details cd inner join products p on p.prod_code = cd.prod_code set cd.qty = if(p.qty_on_hand<cd.qty, p.qty_on_hand, cd.qty) where cd.cartid=@cartid;
        delete from cart_details where cartid=@cartid and qty<=0;
        
		set @total_cost = (select sum(p.list_price*cd.qty) from cart_details cd inner join products p on p.prod_code=cd.prod_code where cd.cartid=icartid);
        
        if (select cr_limit - curr_bal from users where cust_no=@cust_no) < @total_cost then
        begin
            return -2;
        end;
        else
        begin
            update users set curr_bal = curr_bal + @total_cost where cust_no=@cust_no;
        end;
        end if;
        
		insert into orders (cust_no, total_cost) values (@cust_no, @total_cost);
		set @order_no = LAST_INSERT_ID();
		insert into order_details select (@order_no) as order_no, p.prod_code, cd.qty as order_qty, (cd.qty*p.list_price) as order_sum from cart_details cd inner join products p on p.prod_code=cd.prod_code where cd.cartid=icartid;
        
		update products p inner join order_details od on od.prod_code=p.prod_code set p.qty_on_hand = (p.qty_on_hand - od.order_qty) where od.order_no=@order_no;
        update carts set IsActive=0 where cartid=icartid;
        return @order_no;
	end;
    else
    begin
    	return -1;
    end;
	end if;
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `supliers_seperation`(`supplier_id_from` INT, `supplier_id_to` INT) RETURNS int(11)
    NO SQL
begin
	set @sup_from = supplier_id_from;
    set @sup_to = supplier_id_to;
    set @connected = (select * from suppliers s1 inner join suppliers s2 on s1.prod_code=s2.prod_code where s1.supplier_id=s2.supplier_id);
    
    return 1;
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `supply_product_new_supplier`(`iprod_code` VARCHAR(50), `isupplier_name` VARCHAR(50) CHARSET utf8, `isupplier_email` VARCHAR(50)) RETURNS int(11)
    MODIFIES SQL DATA
begin
	if exists (select * from suppliers where supplier_name=isupplier_name) then
    begin
    	return -1;
    end;
    end if;
		set @qty = (select procur_qty from products where prod_code=iprod_code);
        set @newid = (select (max(supplier_id)+1) as supplier_id from suppliers);
    	insert into suppliers (supplier_id, prod_code, supplier_name, email, quant_sofar) values (@newid, iprod_code, isupplier_name, isupplier_email, @qty);
    update products set qty_on_hand = qty_on_hand + @qty where prod_code=iprod_code;
	return LAST_INSERT_ID();    
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=44 ;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cartid`, `cust_no`, `CreatedOn`, `IsActive`) VALUES
(1, NULL, '2014-12-14 21:34:50', 1),
(2, NULL, '2014-12-21 13:13:48', 1),
(3, NULL, '2014-12-21 13:14:05', 1),
(4, NULL, '2014-12-21 13:14:09', 1),
(5, NULL, '2014-12-21 13:14:09', 1),
(6, NULL, '2014-12-21 13:14:10', 1),
(7, NULL, '2014-12-21 13:14:12', 1),
(8, NULL, '2014-12-21 13:14:12', 1),
(9, NULL, '2014-12-21 13:14:40', 1),
(10, NULL, '2014-12-21 13:14:45', 1),
(11, NULL, '2014-12-21 13:14:45', 1),
(12, NULL, '2014-12-21 13:17:08', 1),
(13, NULL, '2014-12-21 13:17:32', 1),
(14, NULL, '2014-12-21 13:17:39', 1),
(15, NULL, '2014-12-21 13:17:40', 1),
(16, NULL, '2014-12-21 13:18:14', 1),
(17, 1, '2014-12-21 13:18:16', 0),
(18, 1, '2014-12-21 22:13:38', 0),
(19, 1, '2014-12-21 22:18:56', 0),
(20, 1, '2014-12-21 23:01:05', 0),
(21, 1, '2014-12-22 22:53:06', 0),
(22, NULL, '2014-12-23 17:36:38', 1),
(23, 2, '2014-12-23 20:53:27', 0),
(24, 2, '2014-12-23 20:54:24', 0),
(25, 2, '2014-12-23 20:56:17', 0),
(26, 2, '2014-12-23 20:58:36', 0),
(27, 2, '2014-12-23 20:59:41', 0),
(28, 2, '2014-12-23 21:23:08', 0),
(29, 2, '2014-12-23 21:26:13', 0),
(30, 2, '2014-12-23 21:28:45', 0),
(31, 2, '2014-12-23 22:52:19', 0),
(32, 2, '2014-12-23 22:53:37', 0),
(33, 2, '2014-12-23 22:54:31', 0),
(34, 2, '2014-12-23 22:55:33', 0),
(35, 2, '2014-12-23 22:56:34', 0),
(36, 1, '2014-12-23 23:56:51', 0),
(37, 2, '2014-12-24 00:29:50', 0),
(38, 2, '2014-12-24 00:41:28', 0),
(39, 2, '2014-12-24 01:42:55', 0),
(40, NULL, '2014-12-24 02:26:31', 1),
(41, 1, '2014-12-24 10:04:36', 0),
(42, 1, '2014-12-24 10:07:26', 1),
(43, 2, '2014-12-24 12:08:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE IF NOT EXISTS `cart_details` (
  `cartid` int(11) NOT NULL,
  `prod_code` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cartid`,`prod_code`),
  KEY `prod_code` (`prod_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cart_details`
--

INSERT INTO `cart_details` (`cartid`, `prod_code`, `qty`) VALUES
(17, 'V_004_CLF', 5),
(17, 'V_008_BRC', 5),
(18, 'S_002_OCT', 2),
(18, 'V_008_BRC', 2),
(19, 'S_002_OCT', 1),
(19, 'V_008_BRC', 2),
(20, 'V_001_TOM', 1),
(20, 'V_002_CUC', 1),
(20, 'V_003_CRT', 2),
(20, 'V_004_CLF', 1),
(21, 'V_001_TOM', 1),
(21, 'V_005_LTC', 1),
(22, 'V_002_CUC', 1),
(23, 'V_002_CUC', 1),
(23, 'V_003_CRT', 1),
(24, 'S_001_TUN', 1),
(24, 'S_002_OCT', 1),
(25, 'V_003_CRT', 1),
(25, 'V_005_LTC', 1),
(26, 'S_002_OCT', 1),
(27, 'V_003_CRT', 1),
(28, 'S_001_TUN', 1),
(29, 'V_003_CRT', 4),
(30, 'S_001_TUN', 1),
(31, 'V_005_LTC', 1),
(32, 'V_001_TOM', 1),
(33, 'V_001_TOM', 1),
(34, 'V_001_TOM', 1),
(35, 'S_001_TUN', 1),
(36, 'V_001_TOM', 1),
(36, 'V_002_CUC', 1),
(37, 'V_002_CUC', 1),
(37, 'V_003_CRT', 1),
(37, 'V_005_LTC', 1),
(38, 'S_002_OCT', 1),
(39, 'S_002_OCT', 1),
(39, 'V_003_CRT', 1),
(39, 'V_005_LTC', 1),
(41, 'S_002_OCT', 1),
(43, 'S_002_OCT', 1),
(43, 'V_007_WCB', 13);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_no` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cust_no` int(11) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `is_valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`order_no`),
  KEY `cust_no` (`cust_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=30 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_no`, `order_date`, `cust_no`, `total_cost`, `is_completed`, `is_valid`) VALUES
(23, '2014-12-23 22:56:50', 2, '151.00', 1, 0),
(24, '2014-12-23 23:57:11', 1, '4.00', 1, 1),
(25, '2014-12-24 00:41:21', 2, '4.50', 0, 0),
(26, '2014-12-24 00:41:38', 2, '30.50', 1, 1),
(27, '2014-12-24 01:43:28', 2, '34.00', 0, 1),
(28, '2014-12-24 10:05:45', 1, '30.50', 0, 0),
(29, '2014-12-24 12:15:38', 2, '56.50', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `order_no` int(11) NOT NULL,
  `prod_code` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `order_qty` int(11) NOT NULL,
  `order_sum` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_no`,`prod_code`),
  KEY `prod_code` (`prod_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_no`, `prod_code`, `order_qty`, `order_sum`) VALUES
(23, 'S_001_TUN', 1, '151.00'),
(24, 'V_001_TOM', 1, '3.00'),
(24, 'V_002_CUC', 1, '1.00'),
(25, 'V_002_CUC', 1, '1.00'),
(25, 'V_003_CRT', 1, '1.50'),
(25, 'V_005_LTC', 1, '2.00'),
(26, 'S_002_OCT', 1, '30.50'),
(27, 'S_002_OCT', 1, '30.50'),
(27, 'V_003_CRT', 1, '1.50'),
(27, 'V_005_LTC', 1, '2.00'),
(28, 'S_002_OCT', 1, '30.50'),
(29, 'S_002_OCT', 1, '30.50'),
(29, 'V_007_WCB', 13, '26.00');

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
('S_001_TUN', 'Tuna', 'Tuna fish', 'S', '151.00', 21, 3, 20, NULL),
('S_002_OCT', 'Octopus', 'Fresh octopus fished at Calymnos', 'S', '30.50', 6, 2, 10, NULL),
('V_001_TOM', 'Tomato', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'V', '3.00', 8, 10, 10, '/images/products/tomato.jpg'),
('V_002_CUC', 'Cucumber', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem\r\nTrtrwe sdfs dfwe wer sdsvsdfsd', 'V', '1.00', 6, 5, 5, '/images/products/cucumber.jpg'),
('V_003_CRT', 'Carrot', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem\r\n\r\nCarrots', 'V', '1.50', 22, 10, 15, '/images/products/carrots.jpg'),
('V_004_CLF', 'Cauliflower', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'V', '3.00', 11, 10, 5, '/images/products/cauliflower.jpg'),
('V_005_LTC', 'Lettuce', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'V', '2.00', 11, 10, 10, '/images/products/lettuce.jpg'),
('V_006_RCB', 'Red Cabbage', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem', 'V', '5.00', 15, 7, 3, '/images/products/cabbage_red.jpg'),
('V_007_WCB', 'Cabbage', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum.', 'V', '2.00', 12, 5, 10, '/images/products/cabbage_white.jpg'),
('V_008_BRC', 'Broccoli', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean et lacus eget nisi sodales tempus. Sed nec velit in metus porttitor elementum. Quisque vulputate tortor sem\r\n\r\nTRwewrsfdfs sdf werrtt dfg dfg wewe weerdfg', 'V', '7.50', 11, 10, 5, '/images/products/broccoli.jpg');

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

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`prod_code`, `supplier_id`, `supplier_name`, `email`, `quant_sofar`) VALUES
('S_001_TUN', 1, 'supplier1', 'sup1@mail.com', 20),
('S_002_OCT', 3, 'supplier3', 'sup3@email.com', 2),
('V_001_TOM', 1, 'supplier1', 'sup1@mail.com', 2),
('V_001_TOM', 2, 'supplier2', 'sup2@mail.com', 1),
('V_003_CRT', 2, 'supplier2', 'sup2@email.com', 2),
('V_004_CLF', 5, 'supplier5', 'sup5@email.com', 5),
('V_005_LTC', 4, 'supplier4', 'sup4@email.com', 2),
('V_005_LTC', 5, 'supplier5', 'sup5@email.com', 2),
('V_006_RCB', 2, 'supplier2', 'sup2@email.com', 2),
('V_006_RCB', 3, 'supplier3', 'sup3@email.com', 2),
('V_007_WCB', 3, 'supplier3', 'sup3@email.com', 2),
('V_007_WCB', 5, 'supplier5', 'sup5@email.com', 2),
('V_008_BRC', 3, 'supplier3', 'sup3@email.com', 2),
('V_008_BRC', 6, 'supplier6', 'sup6@mail.com', 5);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL,
  `creditcard_no` varchar(50) COLLATE utf8_bin NOT NULL,
  `transaction_sum` decimal(10,0) NOT NULL DEFAULT '0',
  `transaction_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_validated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`transaction_id`),
  KEY `order_no` (`order_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `order_no`, `creditcard_no`, `transaction_sum`, `transaction_date`, `is_validated`) VALUES
(1, 23, '123456', '151', '2014-12-23 22:56:50', 1),
(2, 24, '123456-65', '4', '2014-12-23 23:57:11', 1),
(3, 25, '122233', '5', '2014-12-24 00:41:21', 0),
(4, 26, '545645', '31', '2014-12-24 00:41:38', 1),
(5, 27, '123-456-6789', '34', '2014-12-24 01:43:28', 0),
(6, 28, 'tertert', '31', '2014-12-24 10:05:45', 0),
(7, 29, '1233-665', '57', '2014-12-24 12:15:38', 0);

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
  `IsAdmin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cust_no`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`cust_no`, `login`, `password`, `cust_name`, `email`, `street`, `town`, `post_code`, `cr_limit`, `curr_bal`, `IsAdmin`) VALUES
(1, 'admin', 'admin', 'Store manager', 'admin@groceries.gr', 'Ksenofronos 9A, Zwgrafou', 'Athens', '15773', '200', '1', 1),
(2, 'angello', '1234', 'Angello Karageorgos', 'ibz-angello@hotmail.com', 'Ksenofronos 9A, Zwgrafou', 'Athens', '15773', '218', '91', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`cust_no`) REFERENCES `users` (`cust_no`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD CONSTRAINT `cart_details_ibfk_1` FOREIGN KEY (`cartid`) REFERENCES `carts` (`cartid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_details_ibfk_2` FOREIGN KEY (`prod_code`) REFERENCES `products` (`prod_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cust_no`) REFERENCES `users` (`cust_no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_no`) REFERENCES `orders` (`order_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`prod_code`) REFERENCES `products` (`prod_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`prod_group`) REFERENCES `product_groups` (`group_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`order_no`) REFERENCES `orders` (`order_no`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
