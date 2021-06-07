-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 07, 2021 at 03:56 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pruts_normal`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `OpenTickets`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `OpenTickets` ()  BEGIN
	SELECT ticket.ticketId, customer.fname, customer.mname, customer.lname, ticket.description , ticket.date, ticket.status FROM customer, ticket WHERE customer.customerId = ticket.customerId AND status='Open' ORDER BY DATE DESC;
END$$

DROP PROCEDURE IF EXISTS `Orders`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Orders` (IN `param1` VARCHAR(50))  BEGIN
SELECT * FROM `order` WHERE 1  AND status = param1 ORDER BY date DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `customerId` int(10) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `contactno` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`customerId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerId`, `fname`, `mname`, `lname`, `contactno`, `address`, `username`, `password`) VALUES
(2, 'Jan Blessica', 'G.', 'Bagiuo', '09912345612', 'Matina Aplaya', 'jan1', '1234'),
(6, 'Ashley Faith', 'Santos', 'Chan', '09123456789', 'Obrero', 'ash1', '1234');

-- --------------------------------------------------------

--
-- Stand-in structure for view `customers`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
`fname` varchar(50)
,`mname` varchar(50)
,`lname` varchar(50)
,`contactno` varchar(15)
,`address` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `fruit`
--

DROP TABLE IF EXISTS `fruit`;
CREATE TABLE IF NOT EXISTS `fruit` (
  `fruitId` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `pricePerKilo` int(10) NOT NULL,
  PRIMARY KEY (`fruitId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fruit`
--

INSERT INTO `fruit` (`fruitId`, `name`, `pricePerKilo`) VALUES
(1, 'Banana', 30),
(2, 'Apple', 130),
(3, 'Mango', 190),
(5, 'Durian', 50),
(8, 'Lansones', 25),
(9, 'Santol', 120);

-- --------------------------------------------------------

--
-- Stand-in structure for view `fruits`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `fruits`;
CREATE TABLE IF NOT EXISTS `fruits` (
`name` varchar(50)
,`pricePerKilo` int(10)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `monthearnings`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `monthearnings`;
CREATE TABLE IF NOT EXISTS `monthearnings` (
`monthEarnings` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `orderId` int(10) NOT NULL AUTO_INCREMENT,
  `customerId` int(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `total` int(10) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) NOT NULL DEFAULT 'Not Paid',
  PRIMARY KEY (`orderId`),
  KEY `customerId` (`customerId`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderId`, `customerId`, `address`, `description`, `total`, `date`, `status`) VALUES
(1, 2, 'Matina Aplaya', 'Ordered 3 Kilo(s) of Banana', 90, '2021-06-04', 'Paid'),
(2, 2, 'Matina Aplaya', 'Ordered 1 Kilo(s) of Apple', 130, '2021-06-14', 'Not Paid'),
(21, 6, 'Sasa', 'Ordered 2 Kilo(s) of Mango', 380, '2021-06-20', 'Paid'),
(22, 6, 'Sasa', 'Ordered 3 Kilo(s) of Durian', 150, '2021-06-20', 'Not Paid');

--
-- Triggers `order`
--
DROP TRIGGER IF EXISTS `order_delete_log`;
DELIMITER $$
CREATE TRIGGER `order_delete_log` AFTER DELETE ON `order` FOR EACH ROW BEGIN
 INSERT INTO order_log
 SET action = 'Delete',
 orderId = OLD.orderId,
 customerId = OLD.customerId,
 address = OLD.address,
 description = OLD.description,
 total = OLD.total,
 date = OLD.date,
 status = OLD.status,
 actionDate = NOW();
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `order_update_log`;
DELIMITER $$
CREATE TRIGGER `order_update_log` AFTER UPDATE ON `order` FOR EACH ROW BEGIN
INSERT INTO order_log
 SET action = 'Update',
 orderId = OLD.orderId,
 customerId = OLD.customerId,
 address = OLD.address,
 description = OLD.description,
 total = OLD.total,
 date = OLD.date,
 status = OLD.status,
 actionDate = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `orders`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
`name` varchar(50)
,`address` varchar(255)
,`description` varchar(255)
,`total` int(10)
,`date` date
,`status` varchar(10)
);

-- --------------------------------------------------------

--
-- Table structure for table `order_fruit`
--

DROP TABLE IF EXISTS `order_fruit`;
CREATE TABLE IF NOT EXISTS `order_fruit` (
  `ofruitId` int(10) NOT NULL AUTO_INCREMENT,
  `orderId` int(10) NOT NULL,
  `fruitId` int(10) DEFAULT NULL,
  `fruitName` varchar(50) NOT NULL,
  `pricePerKilo` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  PRIMARY KEY (`ofruitId`),
  KEY `orderId` (`orderId`,`fruitId`),
  KEY `fruitId` (`fruitId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_fruit`
--

INSERT INTO `order_fruit` (`ofruitId`, `orderId`, `fruitId`, `fruitName`, `pricePerKilo`, `quantity`) VALUES
(1, 1, 1, 'Banana', 30, 3),
(2, 2, 2, 'Apple', 130, 1),
(4, 21, 3, 'Mango', 190, 2),
(5, 22, 5, 'Durian', 50, 3);

-- --------------------------------------------------------

--
-- Table structure for table `order_log`
--

DROP TABLE IF EXISTS `order_log`;
CREATE TABLE IF NOT EXISTS `order_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `orderId` int(10) NOT NULL,
  `customerId` int(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `total` int(10) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(10) NOT NULL,
  `actionDate` date NOT NULL,
  `action` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_log`
--

INSERT INTO `order_log` (`id`, `orderId`, `customerId`, `address`, `description`, `total`, `date`, `status`, `actionDate`, `action`) VALUES
(1, 20, 6, 'Sasa', 'hello', 260, '2021-06-20', 'Paid', '2021-06-04', 'Update'),
(2, 23, 2, 'Matina Aplaya', 'paki container salamat', 410, '2021-06-24', 'Paid', '2021-06-04', 'Update'),
(3, 23, 2, 'Matina Aplaya', 'Hello please put into container', 410, '2021-06-24', 'Paid', '2021-06-04', 'Update'),
(4, 23, 2, 'Matina Aplaya', 'Please put into container', 410, '2021-06-24', 'Paid', '2021-06-07', 'Delete'),
(5, 20, 6, 'Sasa', 'im happy to purchase fruits', 260, '2021-06-20', 'Paid', '2021-06-07', 'Delete'),
(6, 2, 2, 'Matina Aplaya', 'Ordered 1 Kilo(s) of Apple', 130, '2021-06-14', 'Canceled', '2021-06-07', 'Update'),
(7, 2, 2, 'Matina Aplaya', 'Ordered 1 Kilo(s) of Apple', 130, '2021-06-14', 'Not Paid', '2021-06-07', 'Update'),
(8, 2, 2, 'Matina Aplaya', 'Ordered 1 Kilo(s) of Apple', 130, '2021-06-14', 'Canceled', '2021-06-07', 'Update'),
(9, 2, 2, 'Matina Aplaya', 'Ordered 1 Kilo(s) of Apple', 130, '2021-06-14', 'Not Paid', '2021-06-07', 'Update'),
(10, 2, 2, 'Matina Aplaya', 'Ordered 1 Kilo(s) of Apple', 130, '2021-06-14', 'Canceled', '2021-06-07', 'Update'),
(11, 25, 6, 'Obrero', 'Ordered 2 Kilo(s) of Banana', 60, '2021-06-07', 'Not Paid', '2021-06-07', 'Delete'),
(12, 26, 6, 'Obrero', 'Ordered 2 Kilo(s) of Banana', 60, '2021-06-07', 'Not Paid', '2021-06-07', 'Delete'),
(13, 27, 6, 'Obrero', 'Ordered 2 Kilo(s) of Banana', 60, '2021-06-07', 'Not Paid', '2021-06-07', 'Delete'),
(14, 28, 6, 'Obrero', 'Ordered 2 Kilo(s) of Apple', 640, '2021-06-07', 'Not Paid', '2021-06-07', 'Delete');

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

DROP TABLE IF EXISTS `owner`;
CREATE TABLE IF NOT EXISTS `owner` (
  `ownerId` int(10) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`ownerId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`ownerId`, `fname`, `mname`, `lname`, `username`, `password`) VALUES
(1, 'Jephthah Ruel', 'Gonzales', 'Millan', 'admin', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
CREATE TABLE IF NOT EXISTS `ticket` (
  `ticketId` int(10) NOT NULL AUTO_INCREMENT,
  `customerId` int(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`ticketId`),
  KEY `customerId` (`customerId`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`ticketId`, `customerId`, `description`, `date`, `status`) VALUES
(1, 2, 'Hi your mango is overripe may i ask for refund', '2021-05-14 15:19:35', 'Closed'),
(2, 2, 'Hello where is your physical store located?', '2021-05-14 15:19:45', 'Open'),
(10, 2, 'Pwede mag pa reserve??', '2021-05-21 14:25:25', 'Open'),
(12, 2, 'Test', '2021-06-07 20:13:55', 'Open'),
(13, 6, 'Test', '2021-06-07 20:43:20', 'Open');

--
-- Triggers `ticket`
--
DROP TRIGGER IF EXISTS `ticket_delete_log`;
DELIMITER $$
CREATE TRIGGER `ticket_delete_log` AFTER DELETE ON `ticket` FOR EACH ROW BEGIN
 INSERT INTO ticket_log
 SET action = 'Delete',
 ticketId = OLD.ticketId,
 customerId = OLD.customerId,
 description = OLD.description,
 date = OLD.date,
 status = OLD.status,
 actionDate = NOW();
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `ticket_update_log`;
DELIMITER $$
CREATE TRIGGER `ticket_update_log` AFTER UPDATE ON `ticket` FOR EACH ROW BEGIN
 INSERT INTO ticket_log
 SET action = 'Update',
 ticketId = OLD.ticketId,
 customerId = OLD.customerId,
 description = OLD.description,
 date = OLD.date,
 status = OLD.status,
 actionDate = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `tickets`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
`ticketId` int(10)
,`fname` varchar(50)
,`mname` varchar(50)
,`lname` varchar(50)
,`description` varchar(255)
,`date` datetime
,`status` varchar(10)
);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_log`
--

DROP TABLE IF EXISTS `ticket_log`;
CREATE TABLE IF NOT EXISTS `ticket_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticketId` int(10) NOT NULL,
  `customerId` int(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(10) NOT NULL,
  `actionDate` date NOT NULL,
  `action` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket_log`
--

INSERT INTO `ticket_log` (`id`, `ticketId`, `customerId`, `description`, `date`, `status`, `actionDate`, `action`) VALUES
(9, 6, 6, 'Hello why the delivery take so long?', '2021-05-18', 'Closed', '2021-06-07', 'Update'),
(8, 10, 2, 'Pwede mag pa reserve?', '2021-05-21', 'Open', '2021-06-04', 'Update'),
(7, 10, 2, 'pwede mag pa reserve??', '2021-05-21', 'Open', '2021-06-04', 'Update'),
(10, 6, 6, 'Hello why the delivery take so long?', '2021-05-18', 'Open', '2021-06-07', 'Update'),
(11, 6, 6, 'Hello why the delivery take so long?', '2021-05-18', 'Closed', '2021-06-07', 'Update'),
(12, 6, 6, 'Hello why the delivery take so long?', '2021-05-18', 'Open', '2021-06-07', 'Update'),
(13, 6, 6, 'Hello why the delivery take so long?', '2021-05-18', 'Closed', '2021-06-07', 'Update'),
(14, 6, 6, 'Hello why the delivery take so long?', '2021-05-18', 'Open', '2021-06-07', 'Update'),
(15, 10, 2, 'Pwede mag pa reserve??', '2021-05-21', 'Open', '2021-06-07', 'Update'),
(16, 10, 2, 'Pwede mag pa reserve??', '2021-05-21', 'Closed', '2021-06-07', 'Update'),
(17, 10, 2, 'Pwede mag pa reserve??', '2021-05-21', 'Open', '2021-06-07', 'Update'),
(18, 10, 2, 'Pwede mag pa reserve??', '2021-05-21', 'Closed', '2021-06-07', 'Update'),
(19, 10, 2, 'Pwede mag pa reserve??', '2021-05-21', 'Open', '2021-06-07', 'Update'),
(20, 10, 2, 'Pwede mag pa reserve??', '2021-05-21', 'Closed', '2021-06-07', 'Update'),
(21, 10, 2, 'Pwede mag pa reserve??', '2021-05-21', 'Open', '2021-06-07', 'Update'),
(22, 10, 2, 'Pwede mag pa reserve??', '2021-05-21', 'Closed', '2021-06-07', 'Update'),
(23, 6, 6, 'Hello why the delivery take so long?', '2021-05-18', 'Closed', '2021-06-07', 'Update'),
(24, 6, 6, 'Hello why the delivery take so long?', '2021-05-18', 'Open', '2021-06-07', 'Delete'),
(25, 11, 2, 'Test', '2021-06-07', 'Open', '2021-06-07', 'Delete'),
(26, 13, 6, 'Test', '2021-06-07', 'Open', '2021-06-07', 'Update'),
(27, 13, 6, 'Test', '2021-06-07', 'Closed', '2021-06-07', 'Update');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_message`
--

DROP TABLE IF EXISTS `ticket_message`;
CREATE TABLE IF NOT EXISTS `ticket_message` (
  `tmessageId` int(10) NOT NULL AUTO_INCREMENT,
  `ticketId` int(10) NOT NULL,
  `senderName` varchar(50) NOT NULL,
  `body` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tmessageId`),
  KEY `ticketId` (`ticketId`,`senderName`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket_message`
--

INSERT INTO `ticket_message` (`tmessageId`, `ticketId`, `senderName`, `body`, `date`) VALUES
(1, 1, 'Jan Blessica', 'Hi your mango is overripe may i ask for refund', '2021-05-14 15:16:13'),
(2, 1, 'Jephthah Ruel', 'Or maybe we can give you new stock what you think?', '2021-05-14 15:22:12'),
(3, 1, 'Jan Blessica', 'That would be great! thanks!', '2021-05-14 15:22:31'),
(4, 2, 'Jan Blessica', 'Hello where is your physical store located?', '2021-05-14 15:23:56'),
(8, 2, 'Jephthah Ruel', 'Here at Toril', '2021-05-15 18:55:11'),
(9, 2, 'Jephthah Ruel', 'Come we have newly harvested fruits!', '2021-05-15 18:55:42'),
(16, 1, 'Jephthah Ruel', 'You are welcome!', '2021-05-15 19:26:05'),
(25, 10, 'Jan Blessica', 'pwede mag pa reserve?', '2021-05-21 14:25:25'),
(26, 10, 'Jephthah Ruel', 'yes po', '2021-06-07 15:50:53'),
(27, 10, 'Jephthah Ruel', 'you can come here or text me', '2021-06-07 15:51:05'),
(28, 10, 'Jephthah Ruel', 'okay?', '2021-06-07 16:06:04'),
(30, 12, 'Jan Blessica', 'Test', '2021-06-07 20:13:55'),
(31, 12, 'Jan Blessica', 'Test2', '2021-06-07 20:22:06'),
(32, 12, 'Jan Blessica', 'Test3', '2021-06-07 20:22:40'),
(33, 12, 'Jephthah Ruel', 'Test4', '2021-06-07 20:24:32'),
(34, 12, 'Jephthah Ruel', 'All good!', '2021-06-07 20:25:26'),
(35, 13, 'Ashley Faith', 'Test', '2021-06-07 20:43:20'),
(36, 13, 'Ashley Faith', 'Test2', '2021-06-07 20:43:25'),
(37, 13, 'Jephthah Ruel', 'Test 3', '2021-06-07 20:44:36'),
(38, 13, 'Jephthah Ruel', 'All good!', '2021-06-07 20:44:47');

-- --------------------------------------------------------

--
-- Stand-in structure for view `todayearnings`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `todayearnings`;
CREATE TABLE IF NOT EXISTS `todayearnings` (
`todaysEarnings` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `yearearnings`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `yearearnings`;
CREATE TABLE IF NOT EXISTS `yearearnings` (
`yearEarnings` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Structure for view `customers`
--
DROP TABLE IF EXISTS `customers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `customers`  AS  select `customer`.`fname` AS `fname`,`customer`.`mname` AS `mname`,`customer`.`lname` AS `lname`,`customer`.`contactno` AS `contactno`,`customer`.`address` AS `address` from `customer` where 1 ;

-- --------------------------------------------------------

--
-- Structure for view `fruits`
--
DROP TABLE IF EXISTS `fruits`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `fruits`  AS  select `fruit`.`name` AS `name`,`fruit`.`pricePerKilo` AS `pricePerKilo` from `fruit` where 1 ;

-- --------------------------------------------------------

--
-- Structure for view `monthearnings`
--
DROP TABLE IF EXISTS `monthearnings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `monthearnings`  AS  select sum(`revenue`.`total`) AS `monthEarnings` from (select `order`.`orderId` AS `orderId`,`order`.`customerId` AS `customerId`,`order`.`address` AS `address`,`order`.`description` AS `description`,`order`.`total` AS `total`,`order`.`date` AS `date`,`order`.`status` AS `status` from `order` where `order`.`status` = 'Paid' and month(`order`.`date`) = month(current_timestamp())) `revenue` where 1 ;

-- --------------------------------------------------------

--
-- Structure for view `orders`
--
DROP TABLE IF EXISTS `orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `orders`  AS  select `c`.`fname` AS `name`,`o`.`address` AS `address`,`o`.`description` AS `description`,`o`.`total` AS `total`,`o`.`date` AS `date`,`o`.`status` AS `status` from (`order` `o` join `customer` `c`) where `o`.`customerId` = `c`.`customerId` ;

-- --------------------------------------------------------

--
-- Structure for view `tickets`
--
DROP TABLE IF EXISTS `tickets`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tickets`  AS  select `ticket`.`ticketId` AS `ticketId`,`customer`.`fname` AS `fname`,`customer`.`mname` AS `mname`,`customer`.`lname` AS `lname`,`ticket`.`description` AS `description`,`ticket`.`date` AS `date`,`ticket`.`status` AS `status` from (`customer` join `ticket`) where `customer`.`customerId` = `ticket`.`customerId` order by `ticket`.`date` desc ;

-- --------------------------------------------------------

--
-- Structure for view `todayearnings`
--
DROP TABLE IF EXISTS `todayearnings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `todayearnings`  AS  select sum(`revenue`.`total`) AS `todaysEarnings` from (select `order`.`orderId` AS `orderId`,`order`.`customerId` AS `customerId`,`order`.`address` AS `address`,`order`.`description` AS `description`,`order`.`total` AS `total`,`order`.`date` AS `date`,`order`.`status` AS `status` from `order` where `order`.`status` = 'Paid' and `order`.`date` = curdate()) `revenue` where 1 ;

-- --------------------------------------------------------

--
-- Structure for view `yearearnings`
--
DROP TABLE IF EXISTS `yearearnings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `yearearnings`  AS  select sum(`revenue`.`total`) AS `yearEarnings` from (select `order`.`orderId` AS `orderId`,`order`.`customerId` AS `customerId`,`order`.`address` AS `address`,`order`.`description` AS `description`,`order`.`total` AS `total`,`order`.`date` AS `date`,`order`.`status` AS `status` from `order` where `order`.`status` = 'Paid' and year(`order`.`date`) = year(current_timestamp())) `revenue` where 1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_fruit`
--
ALTER TABLE `order_fruit`
  ADD CONSTRAINT `order_fruit_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `order` (`orderId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_fruit_ibfk_2` FOREIGN KEY (`fruitId`) REFERENCES `fruit` (`fruitId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_message`
--
ALTER TABLE `ticket_message`
  ADD CONSTRAINT `ticket_message_ibfk_1` FOREIGN KEY (`ticketId`) REFERENCES `ticket` (`ticketId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
