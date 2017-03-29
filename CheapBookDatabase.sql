SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: CheapBook
--

-- --------------------------------------------------------

--
-- Table structure for table book
--
DROP TABLE IF EXISTS book;
CREATE TABLE IF NOT EXISTS book (
  ISBN varchar(13) NOT NULL,
  Title varchar(50) DEFAULT NULL,
  Year int(10) DEFAULT '0',
  Price decimal(10,2) DEFAULT '0.00',
  Publisher varchar(50) DEFAULT NULL,
  PRIMARY KEY (ISBN)
);

--
-- Dumping data for table book
--
INSERT INTO book (ISBN, Title, Year, Price, Publisher) VALUES
('1455586455', 'The Last Mile', 2016, 20.28, 'Grand Central Publishing'),
('1250079020', 'Mightier Than The Sword', 2015, 16.43, 'St. Martins Griffin'),
('1476766606', 'Hush', 2015, 17.68, 'Pocket Books'),
('0345506944', 'Shadow Woman', 2013, 7.99, 'Ballantine Books'),
('0061785695', 'The Sixth Extinsion', 2015, 19.84, 'Reissue edition'),
('0373617623', 'Inferno', 2007, 6.00, 'Silhouette'),
('1400079152', 'Inferno', 2014, 20.49, 'Anchor'),
('1250040779', 'Best Kept Secret', 2013, 16.99, 'St. Martins Griffin');


--
-- Table structure for table author
--
DROP TABLE IF EXISTS author;
CREATE TABLE IF NOT EXISTS author (
  SSN int(11) NOT NULL,
  AuthorName varchar(50) DEFAULT NULL,
  Address varchar(60) DEFAULT NULL,
  Phone int(10),
  PRIMARY KEY (SSN)
);

--
-- Dumping data for table author
--
INSERT INTO author (SSN, AuthorName, Address, Phone) VALUES
(761527866, 'David Baldacci', 'Virginia, United States', 4126221761),
(264552781, 'Jeffry Archer', 'New York, United States', 6142236712),
(345178112, 'Karen Robards', ' Louisville, Kentucky, USA', 8715253445),
(561294161, 'Linda Howard', 'Gadsden, Alabama, USA', 2345612270),
(981256781, 'James Rollins', 'Sacramento, California, USA', 7467812340),
(563442165, 'Dan Brown', 'London, United Kingdom', 4342176673);

--
-- Table structure for table wrritenby
--
DROP TABLE IF EXISTS writtenby;
CREATE TABLE IF NOT EXISTS writtenby (
  SSN int(11) REFERENCES author (SSN),
  ISBN varchar(13) REFERENCES book (ISBN),
  PRIMARY KEY (SSN, ISBN)
);
  
--
-- Dumping data for table writtenby
--
INSERT INTO writtenby (SSN, ISBN) VALUES
(761527866, '1455586455'),
(264552781, '1250079020'),
(345178112, '1476766606'),
(561294161, '0345506944'),
(981256781, '0061785695'),
(561294161, '0373617623'),
(563442165, '1400079152'),
(264552781, '1250040779');

--
-- Table structure for table warehouse
--
DROP TABLE IF EXISTS warehouse;
CREATE TABLE IF NOT EXISTS warehouse (
  WarehouseCode int(10) NOT NULL,
  WarehouseName varchar(20) DEFAULT NULL,
  WarehouseAddress varchar(50) DEFAULT NULL,
  Phone varchar(10),
  PRIMARY KEY (WarehouseCode)
);

--
-- Dumping data for table warehouse
--
INSERT INTO warehouse (WarehouseCode, WarehouseName, WarehouseAddress, Phone) VALUES 
(101, 'Amazon', 'California, USA', '5613245655'),
(102, 'Ingram', 'Chicago, Illinois, USA', '6821544237'),
(103, 'Thrift', 'Dallas, Texas, USA', '7156736671'),
(104, 'Scholastic', 'New Jersey, USA', '2334536718'),
(105, 'Bertrams', 'Detroit, Michigan, USA', '5625477910');

--
-- Table structure for table stocks
--
DROP TABLE IF EXISTS stocks;
CREATE TABLE IF NOT EXISTS stocks (
  ISBN varchar(13) REFERENCES book (ISBN),
  WarehouseCode int(10) REFERENCES warehouse (WarehouseCode),
  Number int(10) DEFAULT '0',
  PRIMARY KEY (ISBN, WarehouseCode)
);

--
-- Dumping data for table stocks
--
INSERT INTO stocks (ISBN, WarehouseCode, Number) VALUES 
('1455586455', 101, 30),
('1455586455', 103, 10),
('1250079020', 101, 40),
('1250079020', 102, 35),
('1476766606', 104, 47),
('1476766606', 101, 62),
('0345506944', 105, 23),
('0345506944', 104, 17),
('0061785695', 103, 12),
('0061785695', 105, 20),
('0373617623', 103, 33),
('0373617623', 105, 21),
('1400079152', 102, 19),
('1400079152', 101, 5),
('1250040779', 104, 53);

--
-- Table structure for table customer
--
DROP TABLE IF EXISTS customer;
CREATE TABLE IF NOT EXISTS customer (
  UserName varchar(10) PRIMARY KEY,
  Password varchar(32) NOT NULL,
  Address varchar(50),
  Phone int(10),
  Email varchar(20)
);

--
-- Dumping data for table customer
--
INSERT INTO customer (UserName, Password, Address, Phone, Email) VALUES 
('smith', 'Password1', 'Houston, USA', 5621617682, 'smith@gmail.com'),
('joey', 'Password2', 'New York, USA', 2235618907, 'joe@hotmail.com'),
('james', 'Password3', 'Washington, USA', 5456334521, 'james@yahoo.com'),
('annie', 'Password4', 'California, USA', 6533126381, 'annie@ymail.com'),
('teena', 'Password5', 'Indiana, USA', 5428159931, 'teena@gmail.co.in');

--
-- Table structure for table shoppingbasket
--
DROP TABLE IF EXISTS shoppingbasket;
CREATE TABLE IF NOT EXISTS shoppingbasket (
  BasketID varchar(13),
  UserName varchar(10) REFERENCES customer (UserName),
  PRIMARY KEY (BasketID)
);

--
-- Table structure for table contains
--
DROP TABLE IF EXISTS contains;
CREATE TABLE IF NOT EXISTS contains (
  ISBN varchar(13) REFERENCES book (ISBN),
  BasketID varchar(13) REFERENCES shoppingbasket (BasketID),
  Number int(10),
  PRIMARY KEY (ISBN, BasketID)
);

--
-- Table structure for table shippingorder
--
DROP TABLE IF EXISTS shippingorder;
CREATE TABLE IF NOT EXISTS shippingorder (
  ISBN varchar(13) REFERENCES book (ISBN),
  WarehouseCode int(10) REFERENCES warehouse (WarehouseCode),
  UserName varchar(10) REFERENCES customer (UserName),
  Number int(10),
  PRIMARY KEY (ISBN, WarehouseCode, UserName)
);
