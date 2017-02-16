-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2016 at 08:49 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `spons`
--

-- --------------------------------------------------------

--
-- Table structure for table `AccountLog`
--

CREATE TABLE IF NOT EXISTS `AccountLog` (
  `ID` varchar(15) NOT NULL,
  `Organization` varchar(50) NOT NULL,
  `EventName` varchar(80) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `SponsID` int(9) unsigned NOT NULL,
  `Amount` int(8) unsigned NOT NULL,
  `TransType` enum('Deposit','Withdraw') NOT NULL,
  `Date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `AccountLog`
--

INSERT INTO `AccountLog` (`ID`, `Organization`, `EventName`, `Title`, `SponsID`, `Amount`, `TransType`, `Date`) VALUES
('1', 'Pratibimb', 'Pratibimb-2016', 'Armada Records & Co.', 131040002, 50000, 'Deposit', '2015-02-08'),
('2', 'Pratibimb', 'Pratibimb-2016', 'Chandan Steel Industries', 131080007, 10000, 'Deposit', '2015-02-23'),
('3', 'Pratibimb', 'Pratibimb-2016', 'Louis Philippe', 141020003, 20000, 'Deposit', '2015-03-07'),
('4', 'Pratibimb', 'Pratibimb-2016', 'Fabindia', 131080008, 12000, 'Deposit', '2015-03-11'),
('5', 'Pratibimb', 'Pratibimb-2016', 'Webmax Technologies', 141060007, 12800, 'Deposit', '2015-03-17'),
('6', 'Pratibimb', 'Pratibimb-2016', 'CEAT', 131090005, 17500, 'Deposit', '2015-03-28'),
('7', 'Pratibimb', 'Pratibimb-2016', 'Cognizant India', 141080006, 19000, 'Deposit', '2015-04-26'),
('8', 'Pratibimb', 'Pratibimb-2016', 'BIG FM 92.7', 131010004, 35000, 'Deposit', '2015-04-28'),
('9', 'Pratibimb', 'Pratibimb-2016', 'Siemens', 131090006, 29000, 'Deposit', '2015-05-03'),
('10', 'Pratibimb', 'Pratibimb-2016', 'PepsiCo', 141020004, 43000, 'Deposit', '2015-05-07'),
('11', 'Pratibimb', 'Pratibimb-2016', 'Metro Travels', 131050004, 21000, 'Deposit', '2015-05-09'),
('12', 'Pratibimb', 'Pratibimb-2016', 'The Park', 141080010, 41000, 'Deposit', '2015-05-17'),
('13', 'Pratibimb', 'Pratibimb-2016', 'Ganraj Construction', 141020009, 32000, 'Deposit', '2015-05-14'),
('14', 'Pratibimb', 'Pratibimb-2016', 'Heinz Co', 141030005, 18000, 'Deposit', '2015-05-20'),
('15', 'Pratibimb', 'Pratibimb-2016', 'Bollywood Music Company', 131040002, 100000, 'Deposit', '2016-04-07'),
('18', 'Pratibimb', 'Pratibimb-2016', 'Food', 3, 5000, 'Withdraw', '2015-09-25'),
('17', 'Pratibimb', 'Pratibimb-2016', 'Fabindia', 131080055, 100000, 'Deposit', '2016-05-01'),
('19', 'Pratibimb', 'Pratibimb-2016', 'Stationery', 3, 7000, 'Withdraw', '2016-02-08'),
('20', 'Pratibimb', 'Pratibimb-2016', 'Stationery', 2, 9000, 'Withdraw', '2015-11-17'),
('21', 'Pratibimb', 'Pratibimb-2016', 'Food', 131080055, 10000, 'Withdraw', '2016-04-03'),
('23', 'Pratibimb', 'Pratibimb-2016', 'Adidas', 131080051, 500, 'Deposit', '2016-06-11'),
('ACC-7C0P4', 'Pratibimb', 'Pratibimb-2016', 'Adidas', 121080001, 10000, 'Deposit', '2016-06-11');

-- --------------------------------------------------------

--
-- Table structure for table `CommitteeMember`
--

CREATE TABLE IF NOT EXISTS `CommitteeMember` (
  `ID` int(9) unsigned NOT NULL,
  `Organization` varchar(50) DEFAULT NULL,
  `EventName` varchar(80) DEFAULT NULL,
  `Name` varchar(80) NOT NULL,
  `Department` varchar(15) NOT NULL,
  `Role` varchar(40) NOT NULL,
  `Mobile` varchar(15) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Year` int(1) DEFAULT NULL,
  `Branch` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CommitteeMember`
--

INSERT INTO `CommitteeMember` (`ID`, `Organization`, `EventName`, `Name`, `Department`, `Role`, `Mobile`, `Email`, `Year`, `Branch`) VALUES
(121010001, 'Pratibimb', 'Pratibimb-2016', 'Parshva Shah ', 'Sponsorship', 'Sector Head', '8767121355', 'parshvashah2310@gmail.com', 3, 'Civil'),
(121020001, 'Pratibimb', 'Pratibimb-2016', 'Prajakta Kulkarni ', 'Sponsorship', 'Sector Head', '9757349844', 'kprajakta29@gmail.com', 3, 'Mechanical'),
(121020002, 'Pratibimb', 'Pratibimb-2016', 'Virkar Ashvini ', 'Sponsorship', 'Sector Head', '9594280472', 'ashwinivirkargpm@gmail.com', 3, 'Mechanical'),
(121030001, 'Pratibimb', 'Pratibimb-2016', 'Pranay Patil ', 'Sponsorship', 'Sector Head', NULL, NULL, 3, 'Electrical'),
(121030002, 'Pratibimb', 'Pratibimb-2016', 'Ganesh Kadarwal ', 'Sponsorship', 'Sector Head', '9673828538', 'ganeshkadarwad@gmail.com', 3, 'Electrical'),
(121050001, 'Pratibimb', 'Pratibimb-2016', 'Rahul Rohra ', 'Sponsorship', 'Sector Head', '8097960273', 'rahulrohra01@gmail.com', 3, 'Textile'),
(121060001, 'Pratibimb', 'Pratibimb-2016', 'Mohil Mehta ', 'Sponsorship', 'Sector Head', '9619642420', 'mohil95@hotmail.com', 3, 'Electronics'),
(121060002, 'Pratibimb', 'Pratibimb-2016', 'Rohit Patil ', 'Sponsorship', 'Sector Head', '9405183330', 'rohitpatilvjti@gmail.com', 3, 'Electronics'),
(121060003, 'Pratibimb', 'Pratibimb-2016', 'Pavan Birajdar ', 'Sponsorship', 'Sector Head', '9921440645', 'pavanbirajdar22@gmail.com', 3, 'Electronics'),
(121060004, 'Pratibimb', 'Pratibimb-2016', 'Shaswat Desai ', 'Sponsorship', 'Sector Head', '9987331723', 'shasvat.desai@gmail.com', 3, 'Electronics'),
(121060005, 'Pratibimb', 'Pratibimb-2016', 'Mulla Samin ', 'Sponsorship', 'Sector Head', '8452969901', 'samirraw@gmail.com', 3, 'Electronics'),
(121070001, 'Pratibimb', 'Pratibimb-2016', 'Chaitanya Mahajan ', 'Sponsorship', 'Sector Head', '8793884806', 'chaitanya.mahajan13@gmail.com', 3, 'Comps'),
(121080001, 'Pratibimb', 'Pratibimb-2016', 'Rishabd Dhoke ', 'Sponsorship', 'Sector Head', '7506137891', 'rishabhdhoke44@gmail.com', 3, 'IT'),
(121080002, 'Pratibimb', 'Pratibimb-2016', 'Rahul Jeswani ', 'Sponsorship', 'Sector Head', '8007414276', 'rahuljeswani1995@gmail.com', 3, 'IT'),
(121080003, 'Pratibimb', 'Pratibimb-2016', 'Soham Gandhi ', 'Sponsorship', 'Sector Head', '8805176637', 'sohamgandhi95@gmail.com', 3, 'IT'),
(121081001, NULL, NULL, 'Nicki Minaj', 'Sponsorship', 'SectorHead', '3', 'nickiminaj@gmail.com', 4, 'Textile'),
(121081002, NULL, NULL, 'Nikhil Minaj', 'Sponsorship', 'SectorHead', '13081101', 'nikhilminaj@gmail.com', 4, 'Textile'),
(121081003, NULL, NULL, 'Brenda Cooper', 'Sponsorship', 'SectorHead', '1234-555-009', 'bc@gmail.com', 2, 'IT'),
(121090001, 'Pratibimb', 'Pratibimb-2016', 'Akash Janjal ', 'Sponsorship', 'Sector Head', '7506307407', 'akashjanjal7@gmail.com', 3, 'EXTC'),
(131010003, 'Pratibimb', 'Pratibimb-2016', 'Mohan Sharma ', 'Sponsorship', 'Sponsorship Representative', '9022319471', 'mohan.sharma1092@gmail.com', 2, 'Civil'),
(131010004, 'Pratibimb', 'Pratibimb-2016', 'Parth Parekh ', 'Sponsorship', 'Sponsorship Representative', '7506374795', 'parekhp1995@gmail.com', 2, 'Civil'),
(131010006, 'Pratibimb', 'Pratibimb-2016', 'Ansari Khalid ', 'Sponsorship', 'Sponsorship Representative', '9967190263', 'ansarimohammadkhalid02@gmail.com', 2, 'Civil'),
(131020006, 'Pratibimb', 'Pratibimb-2016', 'Riddhish Shah ', 'Sponsorship', 'Sponsorship Representative', '9664541489', 'shahriddhish1995@gmail.com', 2, 'Mechanical'),
(131020007, 'Pratibimb', 'Pratibimb-2016', 'Siddhant Shah ', 'Sponsorship', 'Sponsorship Representative', '9930811934', 'siddhantnexus@gmail.com', 2, 'Mechanical'),
(131020008, 'Pratibimb', 'Pratibimb-2016', 'Aayush Shah ', 'Sponsorship', 'Sponsorship Representative', '9769802616', NULL, 2, 'Mechanical'),
(131020012, 'Pratibimb', 'Pratibimb-2016', 'Sandip Pawar ', 'Sponsorship', 'Sponsorship Representative', '8888312768', 'sandipspawar88@gmail.com', 2, 'Mechanical'),
(131030004, 'Pratibimb', 'Pratibimb-2016', 'Mehul Jain ', 'Sponsorship', 'Sponsorship Representative', '9168288742', 'mehuljain53@gmail.com', 2, 'Electrical'),
(131040002, 'Pratibimb', 'Pratibimb-2016', 'Dhoomil Sheta ', 'Sponsorship', 'Sponsorship Representative', '9821363504', 'dbsheta@gmail.com', 2, 'Production'),
(131050003, 'Pratibimb', 'Pratibimb-2016', 'Yash Mehta ', 'Sponsorship', 'Sponsorship Representative', NULL, NULL, 2, 'Textile'),
(131050004, 'Pratibimb', 'Pratibimb-2016', 'Prathamesh Mhatre ', 'Sponsorship', 'Sponsorship Representative', '9920322970', 'prathameshmhatre48@gmail.com', 2, 'Textile'),
(131050006, 'Pratibimb', 'Pratibimb-2016', 'Sushant Gaikwad ', 'Sponsorship', 'Sponsorship Representative', '9029416775', 'sushantgaikwad95@gmail.com', 2, 'Textile'),
(131050007, 'Pratibimb', 'Pratibimb-2016', 'Saurabh Bhoy ', 'Sponsorship', 'Sponsorship Representative', '8793269530', 'saurabh.bhoy910@gmail.com', 2, 'Textile'),
(131070002, 'Pratibimb', 'Pratibimb-2016', 'Anisha Motwani ', 'Sponsorship', 'Sponsorship Representative', '9757392237', 'anishamotwani16@gmail.com', 2, 'Comps'),
(131070003, 'Pratibimb', 'Pratibimb-2016', 'Rushabh Patel ', 'Sponsorship', 'Sponsorship Representative', '9029312056', 'rushabhsp95@gmail.com', 2, 'Comps'),
(131070004, 'Pratibimb', 'Pratibimb-2016', 'Parag Pachute ', 'Sponsorship', 'Sponsorship Representative', '8698030488', 'paragpachpute3@gmail.com', 2, 'Comps'),
(131070006, 'Pratibimb', 'Pratibimb-2016', 'Isaivani Mathiyalagan', 'Sponsorship', 'Sponsorship Representative', '8898605263', 'isaimathiyalagan@gmail.com', 2, 'Comps'),
(131080007, 'Pratibimb', 'Pratibimb-2016', 'Chetali Mahore ', 'Sponsorship', 'Sponsorship Representative', '9969586242', NULL, 2, 'IT'),
(131080008, 'Pratibimb', 'Pratibimb-2016', 'Kajal Janghale ', 'Sponsorship', 'Sponsorship Representative', '8652112696', 'rajput.kajal1@gmail.com', 2, 'IT'),
(131080012, 'Pratibimb', 'Pratibimb-2016', 'Madhura Tote ', 'Sponsorship', 'Sponsorship Representative', '9870712261', 'madhura201990@gmail.com', 2, 'IT'),
(131080013, 'Pratibimb', 'Pratibimb-2016', 'Samvanshi Shital ', 'Sponsorship', 'Sponsorship Representative', '8691882532', 'somvanshi.shital19@gmail.com', 2, 'IT'),
(131080022, 'Pratibimb', 'Pratibimb-2016', 'Harshita Bhosle', 'Management', 'Event Organizer', '9819712390', 'harshitabhosle@gmail.com', 3, 'IT'),
(131080051, 'Pratibimb', 'Pratibimb-2016', 'Abhishek Divekar', 'Sponsorship', 'CSO', '9819712190', 'abhishek.r.divekar@gmail.com', 3, 'IT'),
(131080052, 'Pratibimb', 'Pratibimb-2016', 'Shivani Shinde', 'Management', 'Event Organizer', '9819712290', 'shivanirulez@gmail.com', 3, 'IT'),
(131080053, 'Pratibimb', 'Pratibimb-2016', 'Advik Shetty', 'Sponsorship', 'CSO', '9967240818', 'adviksshetty@gmail.com', 3, 'IT'),
(131080055, 'Pratibimb', 'Pratibimb-2016', 'Janit Mehta', 'Sponsorship', 'CSO', '9920059045', 'janithmehta@gmail.com', 3, 'IT'),
(131090002, 'Pratibimb', 'Pratibimb-2016', 'Priyanka Rajpal ', 'Sponsorship', 'Sponsorship Representative', '9769184355', 'pyka.rjpl@gmail.com', 2, 'EXTC'),
(131090003, 'Pratibimb', 'Pratibimb-2016', 'Darshil Gada ', 'Sponsorship', 'Sponsorship Representative', '9702882767', 'darshilgada@yahoo.in', 2, 'EXTC'),
(131090004, 'Pratibimb', 'Pratibimb-2016', 'Abhijit Gupta ', 'Sponsorship', 'Sponsorship Representative', '8879433235', 'guptaabhijit31@gmail.com', 2, 'EXTC'),
(131090005, 'Pratibimb', 'Pratibimb-2016', 'Sarup Dhalwani ', 'Sponsorship', 'Sponsorship Representative', '8652122697', 'dalwanisarup@gmail.com', 2, 'EXTC'),
(131090006, 'Pratibimb', 'Pratibimb-2016', 'Nishant Shah ', 'Sponsorship', 'Sponsorship Representative', '9930784543', 'shahnishant95@gmail.com', 2, 'EXTC'),
(131090007, 'Pratibimb', 'Pratibimb-2016', 'Kaveri Kothe ', 'Sponsorship', 'Sponsorship Representative', '9619254013', 'kaverikothe1995@gmail.com', 2, 'EXTC'),
(131090008, 'Pratibimb', 'Pratibimb-2016', 'Jayesh Bolke ', 'Sponsorship', 'Sponsorship Representative', '7776998441', 'jayesh.bolke@gmail.com', 2, 'EXTC'),
(141010002, 'Pratibimb', 'Pratibimb-2016', 'Supriya Kakade ', 'Sponsorship', 'Sponsorship Representative', '9870079790', 'rgkakade@gmail.com', 1, 'Civil'),
(141010005, 'Pratibimb', 'Pratibimb-2016', 'Apeksha Kirdat ', 'Sponsorship', 'Sponsorship Representative', NULL, 'apekshakirdat@gmail.com', 1, 'Civil'),
(141020003, 'Pratibimb', 'Pratibimb-2016', 'Shailee Vora ', 'Sponsorship', 'Sponsorship Representative', '9967137615', 'shaileevora25@gmail.com', 1, 'Mechanical'),
(141020004, 'Pratibimb', 'Pratibimb-2016', 'Komal Pingle ', 'Sponsorship', 'Sponsorship Representative', '9930531638', 'pinglejayant091@gmail.com', 1, 'Mechanical'),
(141020005, 'Pratibimb', 'Pratibimb-2016', 'Rupali Gawali ', 'Sponsorship', 'Sponsorship Representative', '8082025103', 'rupaligawali95@gmail.com', 1, 'Mechanical'),
(141020009, 'Pratibimb', 'Pratibimb-2016', 'Nidhit Pimple ', 'Sponsorship', 'Sponsorship Representative', '8149865655', 'iamnidhit1994pimple@gmail.com', 1, 'Mechanical'),
(141020010, 'Pratibimb', 'Pratibimb-2016', 'Mridang Agarwal ', 'Sponsorship', 'Sponsorship Representative', '9930126019', 'mridang1611@gmail.com', 1, 'Mechanical'),
(141020011, 'Pratibimb', 'Pratibimb-2016', 'Manan Shah ', 'Sponsorship', 'Sponsorship Representative', '9820657980', 'mananshah18111995@gmail.com', 1, 'Mechanical'),
(141030003, 'Pratibimb', 'Pratibimb-2016', 'Chetana Patel ', 'Sponsorship', 'Sponsorship Representative', '9769761964', 'chetanapatel_26@yahoo.com', 1, 'Electrical'),
(141030005, 'Pratibimb', 'Pratibimb-2016', 'Yash Rathi ', 'Sponsorship', 'Sponsorship Representative', '8087569097', 'yashrathi2511@gmail.com', 1, 'Electrical'),
(141040001, 'Pratibimb', 'Pratibimb-2016', 'Saima Memon ', 'Sponsorship', 'Sponsorship Representative', '9819849201', 'saimamemon26@gmail.com', 1, 'Production'),
(141040003, 'Pratibimb', 'Pratibimb-2016', 'Abhay Kamath ', 'Sponsorship', 'Sponsorship Representative', '9920622593', 'abhkamath@gmail.com', 1, 'Production'),
(141050002, 'Pratibimb', 'Pratibimb-2016', 'Shama Kamat ', 'Sponsorship', 'Sponsorship Representative', '9930799011', 'shamak22430@gmail.com', 1, 'Textile'),
(141050005, 'Pratibimb', 'Pratibimb-2016', 'Shubham Boob ', 'Sponsorship', 'Sponsorship Representative', '9833247892', 'shubhamboob1995@gmail.com', 1, 'Textile'),
(141060006, 'Pratibimb', 'Pratibimb-2016', 'Sonia Martis ', 'Sponsorship', 'Sponsorship Representative', '9920179561', 'sonia_martis@yahoo.co.in', 1, 'Electronics'),
(141060007, 'Pratibimb', 'Pratibimb-2016', 'Mayuri Thakare ', 'Sponsorship', 'Sponsorship Representative', '9004105075', 'mayuri30.vjti@gmail.com', 1, 'Electronics'),
(141060008, 'Pratibimb', 'Pratibimb-2016', 'Kirti Narbag ', 'Sponsorship', 'Sponsorship Representative', '8082145319', 'narbagkirti@gmail.com', 1, 'Electronics'),
(141060009, 'Pratibimb', 'Pratibimb-2016', 'Sandesh Shetty ', 'Sponsorship', 'Sponsorship Representative', '9004971452', 'sandeshshetty95@gmail.com', 1, 'Electronics'),
(141060010, 'Pratibimb', 'Pratibimb-2016', 'Ajitesh Chandak ', 'Sponsorship', 'Sponsorship Representative', '8655495970', 'ajiteshchandak@gmail.com', 1, 'Electronics'),
(141060011, 'Pratibimb', 'Pratibimb-2016', 'Paras Avkirkar ', 'Sponsorship', 'Sponsorship Representative', '7506055572', 'dhavalavkirkar6699@gmail.com', 1, 'Electronics'),
(141070005, 'Pratibimb', 'Pratibimb-2016', 'Rutvik Parekh ', 'Sponsorship', 'Sponsorship Representative', '9833477415', 'rutvik_parekh87@yahoo.in', 1, 'Comps'),
(141080004, 'Pratibimb', 'Pratibimb-2016', 'Vritti Rohira ', 'Sponsorship', 'Sponsorship Representative', '9821594514', 'vritti.rohira@gmail.com', 1, 'IT'),
(141080005, 'Pratibimb', 'Pratibimb-2016', 'Richa Deshmukh ', 'Sponsorship', 'Sponsorship Representative', '9930539242', 'richa.deshmukh@yahoo.co.in', 1, 'IT'),
(141080006, 'Pratibimb', 'Pratibimb-2016', 'Pratiksha Tipre ', 'Sponsorship', 'Sponsorship Representative', '8655721794', 'pratiksha.0073@gmail.com', 1, 'IT'),
(141080009, 'Pratibimb', 'Pratibimb-2016', 'Chinmay Karmarkar ', 'Sponsorship', 'Sponsorship Representative', '9820855892', 'karmalkarchinmay@gmail.com', 1, 'IT'),
(141080010, 'Pratibimb', 'Pratibimb-2016', 'Prathmesh Dahale ', 'Sponsorship', 'Sponsorship Representative', '7588706288', 'prathameshdahale@gmail.com', 1, 'IT'),
(141080011, 'Pratibimb', 'Pratibimb-2016', 'Swachand Lokhande ', 'Sponsorship', 'Sponsorship Representative', '9930616962', 'swachhand95@gmail.com', 1, 'IT'),
(141081001, 'Pratibimb', 'Pratibimb-2016', 'Nicki Minaj', 'Sponsorship', 'Sponsorship Representative', '2', 'nickiminaj@gmail.com', 3, 'EXTC'),
(141081002, 'Pratibimb', 'Pratibimb-2016', 'Darryl Jackson', 'Sponsorship', 'Sponsorship Representative', '123', 'derrylj@gmail.com', 3, 'EXTC'),
(2134456, 'Pratibimb', 'Pratibimb-2016', 'Barack Obama', 'Sponsorship', 'Sponsorship Representative', '999999999', 'potus@wh.gov', 4, 'Comps'),
(1233, 'Pratibimb', 'Pratibimb-2016', 'Janet Kimberley', 'Sponsorship', 'Sponsorship Representative', NULL, NULL, 2, 'Comps'),
(804, 'Pratibimb', 'Pratibimb-2016', 'HaH', 'LOL', 'CSO', '445', NULL, 2, 'Comps'),
(466, 'Pratibimb', 'Pratibimb-2016', 'HaH', 'LOL', 'CSO', '445', NULL, 2, 'Comps'),
(621, 'Pratibimb', 'Pratibimb-2016', 'HaH', 'LOL', 'CSO', '445', NULL, 2, 'Comps'),
(474, 'Pratibimb', 'Pratibimb-2016', 'HaH', 'LOL', 'CSO', '445', NULL, 2, 'Comps'),
(459, 'Pratibimb', 'Pratibimb-2016', 'HaH', 'LOL', 'CSO', '445', NULL, 2, 'Comps'),
(654, 'Pratibimb', 'Pratibimb-2016', 'Lol McLol', 'Sponsorship', 'Sponsorship Representative', '134', 'a@gmail.com', 3, 'Textile'),
(555, 'Pratibimb', 'Pratibimb-2016', 'Dolly Smith', 'Sponsorship', 'Sponsorship Representative', NULL, 'hi@lol.com', NULL, NULL),
(777, 'Pratibimb', 'Pratibimb-2016', 'Simple Ton', 'Sponsorship', 'Sector Head', NULL, NULL, NULL, 'Textile');

-- --------------------------------------------------------

--
-- Table structure for table `Company`
--

CREATE TABLE IF NOT EXISTS `Company` (
  `CMPName` varchar(100) NOT NULL,
  `CMPStatus` varchar(15) DEFAULT NULL,
  `Sector` varchar(15) NOT NULL,
  `CMPAddress` text,
  `PreviouslySponsoredYear` int(4) DEFAULT NULL,
  `SponsoredOtherOrganization` varchar(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Company`
--

INSERT INTO `Company` (`CMPName`, `CMPStatus`, `Sector`, `CMPAddress`, `PreviouslySponsoredYear`, `SponsoredOtherOrganization`) VALUES
('3i Infotech', 'Not called', 'IT', NULL, 2014, 'Yes'),
('ABB', 'Not interested', 'Electricals', 'Shah Industrial Estate; Janaki Centre, Mumbai', 2014, 'Yes'),
('Adidas', 'Not interested', 'Clothes Retail', 'Lake Boulevard Road, Hiranandani Gardens, Mumbai', 2015, 'Yes'),
('Afreen Music Company', NULL, 'Music Stores', NULL, NULL, 'Yes'),
('Aftek', 'Not called', 'IT', '216/A, Second Floor, Prabhadevi Industrial Estate, The Enterprises Co-operative Society Ltd, 408, Veer Saverkar Marg, Prabhadevi, Mumbai - 400 025, India', NULL, 'Yes'),
('Alstom T & D', 'Not called', 'Electricals', '1st Floor, Vedant Delux,Plot No. 38A, Ram Krishna Co-op Society,Narendra Nagar, Nagpur-440010', NULL, 'No'),
('American Megtrends India', 'Not called', 'IT', 'Kumaran Nagar, Semmenchery, Chennai - 600 119 India', 2014, 'Yes'),
('Apollo Tyres', 'Not called', 'Tyres', 'Apollo House, 7 Institutional Area, Sector 32, Gurgaon 122001', 2015, 'Yes'),
('Armada Records & Co.', 'Sponsored', 'Music Stores', 'Carter Road, Bandra West,Mumbai', NULL, 'No'),
('Attari Travels', 'Not interested', 'Travel', 'Plot No 70, Room No 23, Gate No 7, Malwani Colony-Malad', NULL, 'Yes'),
('Bajaj Electricals', 'Not interested', 'Electricals', '45/47, Veer Nariman Road, Mumbai-400001', 2014, 'Yes'),
('Balkrishna Industries', 'Not called', 'Tyres', 'BKT House, C/15, Trade World, Kamala Mills Compound, Senapati Bapat Marg, Lower Parel (W), Mumbai ? 400013', NULL, 'Yes'),
('Bharat Heavy Electricals', 'Not called', 'Electricals', 'BHEL House, Siri Fort, New Delhi - 110049, India.', NULL, 'Yes'),
('BIG FM 92.7', 'Sponsored', 'Radio', '401, 4th Floor, Infiniti Mall, Oshiwara, New Link Road, Andheri West, Mumbai ? 400053', NULL, 'No'),
('Biggaddi.com', 'Not interested', 'Ecommerce', 'NS phadke mg, Andheri East', NULL, 'Yes'),
('Bollywood Music Company', 'Not called', 'Music Stores', 'Grant Road (E), Tribhuwan Marg, Girgaon, Mumbai', NULL, 'No'),
('CEAT', 'Sponsored', 'Tyres', '509-510 Fifth Floor, Shop Zone, M.G.Road, Ghatkopar (W), Mumbai - 400 086', NULL, 'No'),
('Chandan Steel Industries', 'Sponsored', 'Steel', 'N. S Patkar Marg, Grantroad West, Mumbai', NULL, 'No'),
('Coca Cola', NULL, 'FMCG', 'One Coca-Cola Plaza, Atlanta, GA 30313', 2014, 'Yes'),
('Cognizant India', 'Sponsored', 'IT', '12th & 13th Floor, A wing, Kensington Building, Hiranandani Business Park, Powai, Mumbai - 400 076   ', NULL, 'No'),
('Cotton Cottage', 'Not interested', 'Clothes Retail', 'Khar Dadar RD, Khar(W), Mumbai', NULL, 'Yes'),
('Crompton Greaves', 'Not called', 'Electricals', 'CG House, 6th Floor, Dr. Annie Besant Road, Worli, Mumbai - 400 030    ', NULL, 'No'),
('Dim Mak Records', 'Not called', 'Music Stores', 'KL Walawalkar Marg, Phase D, Andheri West, Mumbai', 2015, 'Yes'),
('DRJ Records & Music Co.', 'Not called', 'Music Stores', 'Link Road, Andheri (West) Mumbai, Mumbai', NULL, 'Yes'),
('Eason Reyrolle', 'Not called', 'Electricals', '6th Floor, Temple Tower, 672, Anna Salai, Nandanam, Chennai - 600 035, INDIA', 2014, 'No'),
('Essar Steel Ltd', 'Not interested', 'Steel', 'Keshavrao Khadye Marg, Mahalaxmi, Mumbai', NULL, 'No'),
('Fabindia', 'Sponsored', 'Clothes Retail', 'Lal Bahadur Shastri Marg, Kurla', NULL, 'Yes'),
('Falcon Tyres', 'Not interested', 'Tyres', 'K.R.S. Road, Metagalli, Mysore ? 570016, India', NULL, 'No'),
('Fever 104', 'Not called', 'Radio', 'Tower 2, 7th Floor, Senapati Bapat Marg, Elphinstone Road, Mumbai - 400 013', 2015, 'No'),
('Flipkart', 'Not called', 'Ecommerce', 'Suren Road, Andheri East', NULL, 'Yes'),
('Friendship Sarees', 'Not called', 'Clothes Retail', 'Station Road, Santacruz West, Mumbai', NULL, 'No'),
('Gajra Home makers Pvt Ltd', 'Not called', 'Builders', 'Dana Bazar, Apmc Market', 2015, 'No'),
('Galazy Tours And Travels', 'Not called', 'Travel', 'Rabodi Apartment, Thane West, Thane - 400601', 2014, 'Yes'),
('Ganraj Construction', 'Sponsored', 'Builders', 'Kalyan Road, Dombivli East', NULL, 'Yes'),
('Goel Steel', 'Not interested', 'Steel', '89A, M.T.H Road, Ambattur Industrial Estate,Mumbai', 2015, 'No'),
('Goodyear', 'Not called', 'Tyres', '1st Floor, ABW Elegance Tower, Jasola, New Delhi ? 110025', NULL, 'No'),
('Gozoop Online Pvt. Ltd.', 'Not called', 'Ecommerce', 'Vakola, Santacruz East, Mumbai', NULL, 'No'),
('GRL Tires', 'Not called', 'Tyres', '418, Creative Industrial Estate, Sitaram Mill Compound, 72-N. M. Joshi Marg, Lower Parel, Mumbai - 400 011 (India).', NULL, 'No'),
('Gunjan Music Company', 'Not called', 'Music Stores', 'Dreamland Cinema, Grant Road (E), Girgaon, Mumbai', 2015, 'No'),
('Heinz Co', 'Sponsored', 'FMCG', 'One PPG Place, Pittsburgh, PA 15219', NULL, 'No'),
('Hershys Co', 'Not called', 'FMCG', '100 Crystal A Drive, Hershey, PA 17033', 2015, 'Yes'),
('Hotel Sea Princess', 'Not called', 'Hotel', NULL, NULL, 'Yes'),
('IGATE', 'Not called', 'IT', 'IGATE Global Solutions Limited, Plot No: 158-162P & 165-170P, EPIP Phase II, Whitefield,  Bengaluru, 560066', NULL, 'No'),
('ITSY', 'Not called', 'Ecommerce', 'Aarey Colony, Goregaon East, Mumbai,', 2015, 'No'),
('J.D.Steel Corporation', 'Not called', 'Steel', 'Vertex Vikas Building, Near Railway Station, M V Road', NULL, 'No'),
('Jvw', 'Not called', 'Ecommerce', 'Lt Dilip Gupte Marg, Shivaji Park, Mumbai', 2015, 'No'),
('JW Marriott Hotel', NULL, 'Hotel', NULL, NULL, 'No'),
('Kalki', 'Not called', 'Clothes Retail', 'Swami Vivekanand Road, Santacruz West', NULL, 'No'),
('Kellogs', 'Not called', 'FMCG', 'One Kellogg Square, Battle Creek, MI 49016-3599', NULL, 'No'),
('Kesoram', 'Not called', 'Tyres', '9/1 R. N. Mukherjee Road, Kolkata ? 700 001', NULL, 'No'),
('Krafts Co', 'Not called', 'FMCG', 'Three Lakes Drive, Northfield, IL 60093', 2015, 'No'),
('Lloyds Steel Industries Limited', 'Not called', 'Steel', 'Mahalaxmi, Jacob Circle, Mumbai', NULL, 'No'),
('Louis Philippe', 'Sponsored', 'Clothes Retail', 'SENAPATI BAPAT MARG, LOWER PAREL', NULL, 'No'),
('Mahavir Steel Industries Ltd', 'Not called', 'Steel', 'Steel Centre, Ahmedabad Street,Mumbai', NULL, 'No'),
('Mahindra Ugine Steel Company', 'Not interested', 'Steel', 'Jagdish Nagar, Khopoli - 410216, District - Raigad', 2014, 'No'),
('Mars Inc.', 'Not interested', 'FMCG', '6885 Elm St., McLean, VA 22101-3810', 2015, 'No'),
('McCain Co', 'Not called', 'FMCG', '359 King Street West, Fifth Floor, Toronto, Ontario, Canada', NULL, 'No'),
('Metro Travels', 'Sponsored', 'Travel', '2/5, 1st Rabodi, Thane West, Thane - 400601', NULL, 'Yes'),
('Michelin', 'Not interested', 'Tyres', 'No. 114, Greams Road, Chennai- 600006', NULL, 'Yes'),
('Millionaire', NULL, 'Clothes Retail', 'Juhu Tara Road, Santacruz West, Mumbai,', NULL, 'No'),
('Mphasis', 'Not called', 'IT', 'Bagmane World Technology Center, Marathahalli Outer Ring Road, Doddanakundi Village, Mahadevapura, Bangalore - 560 048.', NULL, 'No'),
('Naitik Tours & Travels', 'Not called', 'Travel', 'Station Road, Bhandup West |', 2015, 'No'),
('Nestle', 'Not interested', 'FMCG', '800 N. Brand Blvd., Glendale, CA 91203', NULL, 'No'),
('Novotel Mumbai Juhu Beach', 'Not called', 'Hotel', 'Balraj Sahani Marg, Juhu', 2015, 'Yes'),
('Onward', 'Not called', 'IT', '2nd Floor, Sterling Centre, Dr A.B. Road, Worli, Mumbai ? 400018', NULL, 'No'),
('Oye 104.8 FM', 'Not called', 'Radio', '4TH Floor, Trade Avenue Building, Dr Suren Road, Opp. Land Mark Building, Chakala, Andheri-East,', NULL, 'No'),
('Pancheel Tours', 'Not called', 'Travel', 'Shop No 16, Aashirwad Chs Ltd, Mrrda Marg, Andheri East', NULL, 'No'),
('PepsiCo', 'Sponsored', 'FMCG', '700 Anderson Hill Road, Purchase, NY 10577-1444', NULL, 'No'),
('Polaris', 'Not interested', 'IT', 'Polaris House, 244, Anna Salai Chennai - 600006, India.', NULL, 'No'),
('PTL Enterprises', 'Not called', 'Tyres', 'Apollo House, 7, Institutional Area, Sector-32, Gurgaon - 122001 (Haryana)', NULL, 'No'),
('Radio Mrichi 98.3', 'Not called', 'Radio', 'ENIL, Radio Mirchi, Matulya Centre, 4th floor, Senapati Bapat Marg, Lower Parel (W), Mumbai 400013', 2015, 'No'),
('Radiocity 91.1', 'Not called', 'Radio', '5th Floor,RNA Corporate Park,Near Chetana College,Bandra East, Mumbai', NULL, 'No'),
('RadioOne 94.3', 'Not called', 'Radio', '2nd Floor, Peninsula Centre, Dr. S.S Rao Road, Parel East, Mumbai - 400 012', NULL, 'No'),
('Ralson', 'Not called', 'Tyres', 'Ralson Nagar, G.T. Road, Ludhiana - 141003, Punjab (INDIA)', NULL, 'No'),
('Ram Travels And Tours', 'Not called', 'Travel', 'Nagar Bus Stop, Goregaon East', 2015, 'No'),
('Red & Yellow Music', 'Not interested', 'Music Stores', 'Opp. Mega Mall, New Link Road, Andheri (West)', NULL, 'No'),
('Red FM 93.5', 'Not called', 'Radio', 'B'' Wing, 3rd Floor, Sun Mill Compound, Lower Parel, Mumbai - 400013  ', NULL, 'No'),
('Ritu Kumar', 'Not interested', 'Clothes Retail', 'uhu Tara Road, Juhu, Mumbai, Maharashtra', 2014, 'No'),
('Sahil Tours And Travels', 'Not called', 'Travel', 'Shop No 18, Western Express Highway, Vile Parle East', NULL, 'No'),
('Schneider Electric', 'Not called', 'Electricals', '9th Floor, DLF Building No. 10, Tower C, DLF Cyber City, Phase II, Gurgaon ? 122002', NULL, 'No'),
('Seasons', 'Not called', 'Clothes Retail', 'Danabhai Jewellers, Santacruz West, Mumbai', NULL, 'No'),
('Siemens', 'sponsored', 'Electricals', '130, Pandurang Budhkar Marg, Worli, Maharashtra, Mumbai 400 018.', NULL, 'No'),
('Snapdeal', 'Not called', 'Ecommerce', 'Lake Boulevard Road, Hiranandani Gardens, Mumbai', NULL, 'No'),
('Steel Mart', 'Not called', 'Steel', 'Near Veena Killedar Industrial Estate, Mumbai', 2014, 'No'),
('Stratedgy', 'Not called', 'Ecommerce', 'Hill road, Bandra West, Mumbai', NULL, 'No'),
('Tejas Networks', 'Not interested', 'IT', '301, Sai Plaza, Junction of Jawahar Road, R.B. Mehta Marg, Opp. Ghatkopar Railway Station,?Ghatkopar (E), Mumbai - 400 077', NULL, 'No'),
('The Lalit Hotel', 'Not interested', 'Hotel', 'Sahar Airport Road, Andheri East', 2014, 'No'),
('The Park', 'Sponsored', 'Hotel', 'Sector 10, Cbd Belapur', 2015, 'No'),
('The Raymond Shop', 'Not called', 'Clothes Retail', 'Ambedkar Garden, Mumbai, Maharashtra', NULL, 'No'),
('The Regenza By Tunga', 'Not interested', 'Hotel', 'Sector 30 A, Vashi', NULL, 'No'),
('Theme Music Co.', 'Not called', 'Music Stores', 'Jawahar Nagar, Road No. 12, Goreagon West, Mumbai', NULL, 'No'),
('TransPacific Software Pvt. Ltd', 'Not called', 'Ecommerce', 'Behind MCA,Bandra East, Mumbai', NULL, 'No'),
('TVS Tyres', 'Not called', 'Tyres', 'TVS SRICHAKRA LIMITED, VELLARIPATTI, MELUR TALUK, MADURAI - 625 122', NULL, 'No'),
('Uttam Galva Steels Limited.', 'Not called', 'Steel', 'P D Mello Road, Carnac Bunder, Mumbai, Maharashtra', NULL, 'No'),
('Vardhaman Group Of Company', 'Not interested', 'Builders', 'Viva College Road, Virar West', NULL, 'Yes'),
('Varun Travels', 'Not called', 'Travel', 'Dadamiya bldg, Kurla West, Mumbai - 400070', NULL, 'No'),
('Waterstones Hotel', 'Not called', 'Hotel', 'Sahar Road, Andheri East', NULL, 'No'),
('Webmax Technologies', 'Sponsored', 'Ecommerce', 'Behind R city mall, Vikhroli (West), Mumbai', NULL, 'No'),
('WebOne Creation', 'Not interested', 'Ecommerce', 'Aarey Colony, Goregaon East, Mumbai', NULL, 'Yes'),
('Wipro Lightings', 'Not called', 'Electricals', 'Godrej Eternia, ''C'' Wing, 5th Floor, Old Pune-Mumbai Road, Wakadewadi, Pune ? 411005', NULL, 'No'),
('WNS', 'Not called', 'IT', 'Plant No. 10 / 11, Gate No. 4, Godrej & Boyce Complex, Pirojshanagar, Vikhroli (West), Mumbai ? 400 079', NULL, 'Yes'),
('Zenith Pcs', 'Not called', 'IT', 'Zenith Computers Ltd. Zenith House, 29 MIDC, Central Road,Andheri (E), Mumbai ? 400 093', 2014, 'No'),
('Harley', 'Not Called', 'All', NULL, NULL, 'Yes'),
('Lotus', 'Not Called', 'Clothes Retail', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `CompanyExec`
--

CREATE TABLE IF NOT EXISTS `CompanyExec` (
  `CEName` varchar(50) NOT NULL DEFAULT '',
  `CMPName` varchar(100) NOT NULL DEFAULT '',
  `CEMobile` varchar(15) DEFAULT NULL,
  `CEEmail` varchar(75) DEFAULT NULL,
  `CEPosition` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CompanyExec`
--

INSERT INTO `CompanyExec` (`CEName`, `CMPName`, `CEMobile`, `CEEmail`, `CEPosition`) VALUES
('Abhay Kamath ', 'Friendship Sarees', '022 2605 7801', 'abhkamath@gmail.com', 'Marketing Executive'),
('Abhijeet Nachane ', 'Wipro Lightings', '020-66098700', 'Abhijeet.n@wipro.com', 'Marketing Officer'),
('Abhijit Waje ', 'Lloyds Steel Industries Limited', '022 2308 0097', 'wajeabhijit66@gmail.com', 'Marketing Executive'),
('Abhishek Sharma ', 'The Raymond Shop', '022 2521 1921', 'abhisharma95aes@gmail.com', 'Marketing Executive'),
('Aditya Kale ', 'Kalki', '022 3267 8003', 'adityaskale@gmail.com', 'Marketing'),
('Ajit Gulabchand ', 'Bharat Heavy Electricals', '+91 11 26493021', 'Ajit.gulabchand@bhel.com', 'Event Manager'),
('Ajitesh Chandak ', 'TransPacific Software Pvt. Ltd', '022 3252 2901', 'ajiteshchandak@gmail.com', 'Marketing Manager'),
('Akshay Bhambhani ', 'JW Marriott Hotel', '+(91)9861639304', 'Akshay_BB75@hotmail.com', 'Advertising Manager'),
('Alka Agarwal ', 'Vardhaman Group Of Company', '+(91)9845106619', 'AlkaAgarwal@yahoo.com', 'Advertising Manager'),
('Amruta Kamble ', 'Gunjan Music Company', '+(91)9961621651', 'amrutaprakash35@gmail.com', 'Marketing Officer'),
('Ankita D Mali ', 'Mahavir Steel Industries Ltd', '022 2683 7310', 'mshrikant361@gmail.com', 'Advertising Manager'),
('Ankita Malaney ', 'Gajra Home makers Pvt Ltd', '+(91)9861621651', 'MalaneyAnkita@gmail.com', 'Marketing'),
('Ansari Khalid ', 'Essar Steel Ltd', '022 2495 0606', 'ansarimohammadkhalid02@gmail.com', 'Event Manager'),
('Anuj Dharap ', 'Metro Travels', '+(91)9849173629', 'AnujDharap@gmail.com', 'Marketing'),
('Apeksha Kirdat ', 'Flipkart', '022 2660 7492', 'apekshakirdat@gmail.com', 'Marketing Officer'),
('Arnob Roy ', 'WNS', '+91 22 4095 210', 'arnob_roy@wns.in', 'Advertising Manager'),
('Arun Rattan ', 'Naitik Tours & Travels', '+(91)9849180399', NULL, NULL),
('Ashok Jalan ', 'Siemens', '+91 22 3967 700', 'a_jalan@siemens.co.in', 'Marketing Executive'),
('Ashok Vemuri ', 'IGATE', '+91 80 4104 000', 'avemuri@igatesolutions.com', 'Marketing Officer'),
('Ayush Awasthi ', 'Attari Travels', '+(91)9849177405', 'AyushAwasthi@yahoo.com', 'Marketing Executive'),
('Ayush Shah ', 'PepsiCo', '914-253-2000', ' Ayush_Shah @hotmail.com', 'Marketing Executive'),
('Balakrishnan ', 'TVS Tyres', '0452 - 2420461', 'kbalakrishnan@tvsltd.com', 'Marketing Executive'),
('Balu Ganesh Ayyar ', 'Mphasis', '+91 80 3352 500', 'bgayyar@mphasis.co.in', 'Marketing Executive'),
('Bharat Jaitley ', 'Galazy Tours And Travels', '+(91)9845112534', 'Bharat.Jaitley79@gmail.com', 'Marketing'),
('Bijoy Ghandhi ', 'Varun Travels', '+(91)9861636631', 'Bijoy_Ghandhi@gmail.com', 'Advertising Manager'),
('C Dasgupta ', 'Goodyear', '0129-6611000', 'cdasgupta1@goodyear.co.in', 'Marketing Executive'),
('Dalpat G Jain ', '3i Infotech', '022 6792 8000', 'dalpatgjain@3i-infotech.com', 'Marketing Executive'),
('Deepa Shetty ', 'Sahil Tours And Travels', '+(91)9861620622', 'Deepa_Shetty@yahoo.com', 'Marketing Executive'),
('Dhanshree Kolage ', 'Armada Records & Co.', '+(91)9949187582', 'dhanshreekolage@gmail.com', 'Marketing Executive'),
('Drishti Lekhrajani ', 'The Park', '+(91)9866933000', 'Drishti_Lekhrajani_1981@gmail.com', 'Marketing Executive'),
('Francisco D''souza ', 'Cognizant India', '+ 91-22-4422 80', 'francisco.dsouza@cognizantindia.com', 'Event Manager'),
('Gayatri Mavani ', 'Hotel Sea Princess', '+(91)9849180309', 'GayatriDMavani@yahoo.com', 'Event Manager'),
('Hardik Rana ', 'Fabindia', '022 6180 1456', 'hardikrana27@gmail.com', 'Marketing Executive'),
('Harish Mehta ', 'Onward', '+91 22 6695 994', 'harish@onwards.com', 'Marketing Manager'),
('Harish Patel ', 'Nestle', '818-549-6000', ' HarishPatel@gmail.com', 'Advertising Manager'),
('Indu Shahani ', 'Crompton Greaves', '+91 22 2423 777', 'indu.sahani@cg-india.com', 'Marketing Executive'),
('Isaivani ', 'Theme Music Co.', '+(91)9961621374', 'isaimathiyalagan@gmail.com', 'Marketing Executive'),
('Jackie Matai ', 'McCain Co', '416-955-1700', 'JMatai1977@gmail.com', 'Marketing Manager'),
('Janhavi Patole ', 'Dim Mak Records', '+(91)9961617144', 'janhavi.patole@rediffmail.com', 'Marketing'),
('Jay Singha ', 'Polaris', '+91-44-3987 400', 'Jay.singha@polaris.com', 'Marketing Executive'),
('Jyoti Kumar ', 'ABB', '+91 22 6671 727', 'jyoti.kumar99@abb.com', 'Marketing Executive'),
('Kajol Galande ', 'Louis Philippe', '022 6617 2045', 'galandekajol55@gmail.com', 'Marketing Executive'),
('Kaveri Kothe ', 'Afreen Music Company', '+(91)9945106619', 'kaverikothe1995@gmail.com', 'Marketing Executive'),
('Kumar Sivrajan ', 'Zenith Pcs', '022-28377300', 'marketing@zenithpcs.com', 'Marketing Officer'),
('Madhura Tote ', 'Biggaddi.com', '022 2495 7364', 'madhura201990@gmail.com', 'Marketing Manager'),
('Mahaveer Bhansali ', 'Falcon Tyres', '+91-821-2582055', 'mahaveer.bhansali@falcontyres.com', 'Event Manager'),
('Mahendra Jha ', 'Eason Reyrolle', '+91-44-24346425', 'sales@easonreyrolle.co.in', 'Marketing Executive'),
('Mahesh Divekar ', 'Krafts Co', '847-646-2000', 'Mahesh_theboss_Divekar@yahoo.com', 'Marketing Executive'),
('Manan Shah ', 'ITSY', '092213 00321', 'mananshah18111995@gmail.com', 'Marketing Executive'),
('Mehul Shah ', 'GRL Tires', '+91-22-2309 564', 'mshah@grltires.com', 'Marketing Manager'),
('Mitesh Swar ', 'Oye 104.8 FM', '+(91)9820215508', 'mitesh.swar@fortviewgroup.com', 'Marketing Executive'),
('Mridang Agarwal ', 'WebOne Creation', '092213 00321', 'mridang1611@gmail.com', 'Event Manager'),
('Murali R ', 'American Megtrends India', '[91] 44-6654092', 'muralir@ami.co.in', 'Marketing Executive'),
('N Subramanian ', 'Radiocity 91.1', '91 22 6696 9100', 'nsubramanian@radiocity.com', 'Advertising Manager'),
('Narendra Shinde ', 'Waterstones Hotel', '+(91)9849187582', 'NarendraPShinde@yahoo.com', 'Marketing Manager'),
('Naveen Saxena ', 'BIG FM 92.7', '+(91)-22-306894', 'naveen.saxena@reliance-broadcasting.com', 'Advertising Manager'),
('Neeta Khatib ', 'Heinz Co', '412-456-5700', 'Neeta.Khatib@hotmail.com', 'Marketing'),
('Neetu Karnani ', 'Mars Inc.', '703-821-4900', 'NeetuKarnani1980@yahoo.com', 'Marketing'),
('Neha Devdas ', 'Bollywood Music Company', '+(91)9967731586', 'nehadevdas238@gmail.com', 'Advertising Manager'),
('Nidhit Pimple ', 'Seasons', '022 6145 9999', 'iamnidhit1994pimple@gmail.com', 'Marketing Executive'),
('Nimesh Kulkanri ', 'Hershys Co', '717-534-4200', 'Nimesh_Kulkanri@gmail.com', 'Marketing Executive'),
('Ojaswini Thakur ', 'RadioOne 94.3', '022 - 67015700', 'ojaswini.thakur@radioone.in', 'Marketing Executive'),
('Onkar Chichkar ', 'Adidas', '098336 63322', 'onkarchichkar@yahoo.in', 'Event Manager'),
('Paras Avkirkar ', 'Stratedgy', '099202 22098', 'dhavalavkirkar6699@gmail.com', 'Marketing Executive'),
('Parul Mehta ', 'Coca Cola', '404-676-2121', 'ParulAMehta@hotmail.com', 'Marketing Officer'),
('Paul Fernandes ', 'Apollo Tyres', '+91 124 2721000', 'paulf@apollotyres.com', 'Marketing Executive'),
('Poonam Danse ', 'Chandan Steel Industries', NULL, 'poonam.danse9@gmail.com', 'Marketing Executive'),
('Poonam Jadhav ', 'Millionaire', '022 2660 4243', 'nandinijadhav75@gmail.com', 'Marketing'),
('Prachi Sagwekar ', 'Goel Steel', '91-44-42914848', 'sagwekarp@gmail.com', 'Marketing Manager'),
('Prakash Shiram ', 'Ganraj Construction', '+(91)9861615049', 'Prakash_Shiram@yahoo.com', 'Marketing'),
('Pranay Jain ', 'Pancheel Tours', ' 22-49179714', 'JainPranay76@hotmail.com', 'Marketing'),
('Pranoy Kapoor ', 'Fever 104', '022-33104104', 'Pranoy.kapoor@fever.fm', 'Marketing Officer'),
('R P Singh ', 'Alstom T & D', '+91 674 2596439', 'rpsingh@alstomindia.com', 'Marketing Manager'),
('R V Gupta ', 'Kesoram', '+ 91 33 2243 54', 'Raghavgupta@gmail.com', 'Marketing Executive'),
('Rajeev Anand ', 'Michelin', '044-28292777', 'mrfshare@mrfmail.com', 'Advertising Manager'),
('Ranjit Dhuru ', 'Aftek', '91-22-24211706', 'ranjit_dhuru@aftek.co.in', 'Marketing Manager'),
('Ravina Vhalekar ', 'Uttam Galva Steels Limited.', '022 6656 3500', 'rvhalekar@gmail.com', 'Marketing Officer'),
('Ray Fernandes ', 'The Lalit Hotel', '+(91)9861635346', 'RSFernandes@hotmail.com', 'Marketing Executive'),
('Sammek Ovhal ', 'Cotton Cottage', '022 2605 4858', 'samikovhal@gmail.com', 'Marketing Officer'),
('Samvanshi Shital ', 'Red & Yellow Music', '+(91)9961620622', 'ashwinivirkar1@gmail.com', 'Marketing Executive'),
('Sandesh Shetty ', 'Webmax Technologies', '022 2517 3966', 'sandeshshetty95@gmail.com', 'Advertising Manager'),
('Sandip Pawar ', 'Ritu Kumar', '022 6697 6932', 'sandipspawar88@gmail.com', 'Advertising Manager'),
('Sanjay Nayak ', 'Tejas Networks', '+91 22 42498600', 'info@tejanetworks.com', 'Marketing'),
('Sarvmeet Oberoi ', 'Radio Mrichi 98.3', '022 - 66620600', 'Sarvmeet.Oberoi@timesgroup.com', 'Advertising Manager'),
('Saurabh Sehgal ', 'Red FM 93.5', '022 - 30935700', 'saurabh_sehgal@redfm.com', 'Marketing Officer'),
('Seema Thappar ', 'PTL Enterprises', '(0124) ? 238300', 'seema.thappar@ptlenterprises.com', 'Marketing Executive'),
('Sheeza Waghmare ', 'CEAT', '022-25100837', 'Sheeza.waghmare@ceat.in', 'Marketing Officer'),
('Shital Bhangare ', 'Steel Mart', '022 2308 0096', 'shitalha.sb@gmail.com', 'Marketing Executive'),
('Shreesha Ketkar ', 'Kellogs', '269-961-2000', 'ShreeshaCKetkar@hotmail.com', 'Marketing'),
('Shubham Bob ', 'Gozoop Online Pvt. Ltd.', '022 6127 0416', 'shubhamboob1995@gmail.com', 'Advertising Manager'),
('Sonali Gadkari ', 'DRJ Records & Music Co.', NULL, NULL, 'Marketing Executive'),
('Soumya Doshi ', 'Novotel Mumbai Juhu Beach', '+(91)9861634479', 'SoumyaDoshi@yahoo.com', 'Marketing Manager'),
('Sudha Ravi ', 'Balkrishna Industries', '66663800', 'sudhar@balkrishnaltd.com', 'Marketing Officer'),
('Suresh Chhada ', 'Schneider Electric', '+91 124 3940 40', 'suresh.chhada@schneider-electric.com', 'Event Manager'),
('Sushant Gaikwad ', 'Mahindra Ugine Steel Company', '+91 9881124640', 'sushantgaikwad95@gmail.com', 'Marketing Executive'),
('Sushma Prasad ', 'The Regenza By Tunga', '+(91)9861617144', 'Sushma_Prasad@yahoo.com', 'Marketing Executive'),
('Swachand Lokhande ', 'Jvw', NULL, NULL, NULL),
('Trisha Gupta ', 'Ram Travels And Tours', '+(91)9849180160', 'Trisha.G@hotmail.com', 'Marketing'),
('V B Haribhakti ', 'Bajaj Electricals', '022-22043780', 'vb.haribhakti@bajaj_electricals.com', 'Marketing Manager'),
('Vijaya Chintala ', 'J.D.Steel Corporation', '022 2683 7310', 'vijayachin15@gmail.com', 'Marketing'),
('Yash Rathi ', 'Snapdeal', '092213 83728', 'yashrathi2511@gmail.com', 'Marketing'),
('Yashwant Singh Yadav ', 'Ralson', '+91-161-2511501', 'marketing@ralson.com', 'Advertising Manager');

-- --------------------------------------------------------

--
-- Table structure for table `Event`
--

CREATE TABLE IF NOT EXISTS `Event` (
`EventID` int(7) unsigned NOT NULL,
  `Organization` varchar(50) NOT NULL,
  `EventName` varchar(80) NOT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Event`
--

INSERT INTO `Event` (`EventID`, `Organization`, `EventName`, `StartDate`, `EndDate`) VALUES
(1, 'Pratibimb', 'Pratibimb-2016', '2016-12-28', '2017-01-10');

-- --------------------------------------------------------

--
-- Table structure for table `Meeting`
--

CREATE TABLE IF NOT EXISTS `Meeting` (
  `ID` varchar(15) NOT NULL,
  `Date` date DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `SponsID` int(9) unsigned DEFAULT NULL,
  `Organization` varchar(50) DEFAULT NULL,
  `EventName` varchar(80) DEFAULT NULL,
  `MeetingType` enum('Email','Call','Face-To-Face Meeting') NOT NULL,
  `CEName` varchar(50) DEFAULT NULL,
  `CMPName` varchar(100) DEFAULT NULL,
  `Outcome` text,
  `Address` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Meeting`
--

INSERT INTO `Meeting` (`ID`, `Date`, `Time`, `SponsID`, `Organization`, `EventName`, `MeetingType`, `CEName`, `CMPName`, `Outcome`, `Address`) VALUES
('1', '2015-01-01', '16:49:00', 141020003, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Kajol Galande', 'Louis Philippe', 'Followup Required Giving only 10,000', 'VJTI'),
('2', '2015-01-03', '14:11:00', 131040002, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Dhanshree Kolage', 'Armada Records & Co.', 'Followup Required after a few days sponsor interested', 'VJTI'),
('3', '2015-01-05', '11:34:00', 141060007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required sponsor needs 2 banners', 'VJTI'),
('4', '2015-01-06', '13:27:00', 131090005, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sheeza Waghmare ', 'CEAT', 'Followup Required', 'VJTI'),
('5', '2015-01-07', '13:30:00', 131040002, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Dhanshree Kolage', 'Armada Records & Co.', 'Very Interested Company', 'Carter Road, Bandra West,Mumbai'),
('6', '2015-01-08', '13:14:00', 141010002, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Samvanshi Shital ', 'Red & Yellow Music', 'Not Interested', 'VJTI'),
('7', '2015-01-09', '12:00:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Hardik Rana', 'Fabindia', 'Followup Required, wants branding of tshirt Event ', 'VJTI'),
('8', '2015-01-11', '12:28:00', 131080007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Poonam Danse', 'Chandan Steel Industries', 'Followup Required sponsor giving only 8000', 'VJTI'),
('9', '2015-01-14', '13:30:00', 141020003, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Kajol Galande', 'Louis Philippe', 'Followup Required', 'SENAPATI BAPAT MARG, LOWER PAREL'),
('10', '2015-01-17', '10:36:00', 141010002, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Kaveri Kothe ', 'Afreen Music Company', 'Not Interested', 'VJTI'),
('11', '2015-01-18', '14:00:00', 131040002, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Dhanshree Kolage', 'Armada Records & Co.', 'Followup Required want 4 banners', 'Carter Road, Bandra West,Mumbai'),
('12', '2015-01-20', '12:00:00', 141060007, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required', 'Behind R city mall, Vikhroli (West), Mumbai'),
('13', '2015-01-21', '12:39:00', 131030004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Ansari Khalid ', 'Essar Steel Ltd', 'Not Interested', 'VJTI'),
('14', '2015-01-23', '14:38:00', 131080007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Poonam Danse', 'Chandan Steel Industries', 'Followup Required ', 'VJTI'),
('15', '2015-01-24', NULL, 141060007, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Sandesh Shetty', 'Webmax Technologies', 'Unvailable For Meeting', NULL),
('16', '2015-01-25', '14:38:00', 141020003, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Kajol Galande', 'Louis Philippe', 'Followup Required , wants to be sole sponsor in sector', 'VJTI'),
('17', '2015-01-26', '13:30:00', 131090005, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Sheeza Waghmare ', 'CEAT', 'Followup Required', '509-510 Fifth Floor, Shop Zone, M.G.Road, Ghatkopar (W), Mumbai - 400 086'),
('18', '2015-01-27', '10:14:00', 131040002, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Dhanshree Kolage', 'Armada Records & Co.', 'Unvailable For Meeting', 'VJTI'),
('19', '2015-01-28', '13:41:00', 131030004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sushant Gaikwad ', 'Mahindra Ugine Steel Company', 'Not Interested', 'VJTI'),
('20', '2015-01-30', '14:30:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Hardik Rana', 'Fabindia', 'Followup Required', 'Lal Bahadur Shastri Marg, Kurla'),
('21', '2015-02-01', '11:54:00', 141060007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required , wants 3 banners', 'VJTI'),
('22', '2015-02-03', '10:00:00', 131080007, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Poonam Danse', 'Chandan Steel Industries', 'Very Interested Company', 'N. S Patkar Marg, Grantroad West, Mumbai'),
('23', '2015-02-05', '15:16:00', 131070003, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sammek Ovhal ', 'Cotton Cottage', 'Not Interested', 'VJTI'),
('24', '2015-02-06', '14:00:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Hardik Rana', 'Fabindia', 'Followup Required', 'Lal Bahadur Shastri Marg, Kurla'),
('25', '2015-02-07', '12:58:00', 141020003, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Kajol Galande', 'Louis Philippe', 'Followup Required', 'VJTI'),
('26', '2015-02-08', '16:11:00', 131040002, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Dhanshree Kolage', 'Armada Records & Co.', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
('27', NULL, NULL, 131080008, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Onkar Chichkar ', 'Adidas', 'Not Interested', NULL),
('28', '2015-02-10', '14:00:00', 131090005, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Sheeza Waghmare ', 'CEAT', 'Followup Required , wants to title sponsor for IC engine Event', '509-510 Fifth Floor, Shop Zone, M.G.Road, Ghatkopar (W), Mumbai - 400 086'),
('29', '2015-02-11', '12:00:00', 131080007, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Poonam Danse', 'Chandan Steel Industries', 'Followup Required', 'N. S Patkar Marg, Grantroad West, Mumbai'),
('30', '2015-02-14', '14:12:00', 131070003, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sandip Pawar ', 'Ritu Kumar', 'Not Interested', NULL),
('31', '2015-02-17', '14:53:00', 141060007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required', 'VJTI'),
('32', '2015-02-18', '15:13:00', 141060007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Mridang Agarwal ', 'WebOne Creation', 'Not Interested', 'VJTI'),
('33', '2015-02-20', '13:30:00', 141020003, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Kajol Galande', 'Louis Philippe', 'Followup Required , Company giving only 12500', 'SENAPATI BAPAT MARG, LOWER PAREL'),
('34', '2015-02-21', '15:16:00', 141080006, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Francisco D''souza ', 'Cognizant India', 'Followup Required , Sponsor wants a banner', 'VJTI'),
('35', '2015-02-23', '11:51:00', 131080007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Poonam Danse', 'Chandan Steel Industries', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
('36', '2015-02-24', '15:59:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Hardik Rana', 'Fabindia', 'Followup Required', 'VJTI'),
('37', '2015-02-25', '15:39:00', 131090006, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Madhura Tote ', 'Biggaddi.com', 'Not Interested', 'VJTI'),
('38', '2015-02-26', '12:37:00', 141020003, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Kajol Galande', 'Louis Philippe', 'Followup Required', 'VJTI'),
('39', '2015-02-27', '15:59:00', 131090002, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Rajeev Anand ', 'Michelin', 'Not Interested', 'VJTI'),
('40', '2015-02-28', '11:15:00', 131090002, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Jay Singha ', 'Polaris', 'Not Interested', 'VJTI'),
('41', '2015-02-28', '13:36:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Hardik Rana', 'Fabindia', 'Followup Required', 'VJTI'),
('42', '2015-03-01', '11:19:00', 131010004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Followup Required', 'VJTI'),
('43', '2015-03-03', '13:30:00', 141020003, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Kajol Galande', 'Louis Philippe', 'Followup Required , Sponsor wants a shop', 'SENAPATI BAPAT MARG, LOWER PAREL'),
('44', '2015-03-05', '16:49:00', 131020007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Alka Agarwal ', 'Vardhaman Group Of Company', 'Not Interested', 'VJTI'),
('45', '2015-03-06', '12:00:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Hardik Rana', NULL, 'Followup Required', 'Lal Bahasdur Shastri Marg, Kurla'),
('46', '2015-03-07', '14:00:00', 141020003, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Kajol Galande', 'Louis Philippe', 'Final Meeting , Sponsorship Confirmed', 'SENAPATI BAPAT MARG, LOWER PAREL'),
('47', '2015-03-08', '13:31:00', 131080012, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sushma Prasad ', 'The Regenza By Tunga', 'Not Interested', 'VJTI'),
('48', '2015-03-09', '17:30:00', 141060007, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required', 'Behind R city mall, Vikhroli (West), Mumbai'),
('49', '2015-03-10', '13:12:00', 131020007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Ray Fernandes ', 'The Lalit Hotel', 'Not Interested', 'VJTI'),
('50', '2015-03-11', '18:00:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Hardik Rana', 'Fabindia', 'Final Meeting , Sponsorship Confirmed', 'Lal Bahadur Shastri Marg, Kurla'),
('51', '2015-03-14', '16:28:00', 131080012, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Ayush Awasthi ', 'Attari Travels', 'Not Interested', 'VJTI'),
('52', '2015-03-17', '13:30:00', 141060007, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sandesh Shetty', 'Webmax Technologies', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
('53', '2015-03-18', '12:32:00', 131010004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Unvailable For Meeting , Family Emergency', 'VJTI'),
('54', '2015-03-20', '13:23:00', 131090006, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Ashok Jalan ', 'Siemens', 'Followup Required', 'VJTI'),
('55', '2015-03-21', '15:42:00', 131010004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Followup Required , Company wants to become title sponsor', 'VJTI'),
('56', '2015-03-23', '13:30:00', 141020004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Ayush Shah ', 'PepsiCo', 'Unvailable For Meeting', 'VJTI'),
('57', '2015-03-24', '10:00:00', 141080006, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Francisco D''souza ', 'Cognizant India', 'Followup Required', '12th & 13th Floor, A wing, Kensington Building, Hiranandani Business Park, Powai, Mumbai - 400 076   '),
('58', '2015-03-25', '13:13:00', 141030005, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Neeta Khatib ', 'Heinz Co', 'Followup Required , comapny wants a stall', 'VJTI'),
('59', '2015-03-26', '12:30:00', 141030005, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Neeta Khatib ', 'Heinz Co', 'Unvailable For Meeting', 'One PPG Place, Pittsburgh, PA 15219'),
('60', '2015-03-27', '12:34:00', 131050004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Anuj Dharap ', 'Metro Travels', 'Followup Required', 'VJTI'),
('61', '2015-03-28', '13:30:00', 131090005, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Sheeza Waghmare ', 'CEAT', 'Final Meeting , Sponsorship Confirmed', '509-510 Fifth Floor, Shop Zone, M.G.Road, Ghatkopar (W), Mumbai - 400 086'),
('62', '2015-03-30', '15:21:00', 141020004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Ayush Shah ', 'PepsiCo', 'Followup Required', 'VJTI'),
('63', '2015-04-01', '14:45:00', 131010004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Unvailable For Meeting, setup another Meeting', 'VJTI'),
('64', '2015-04-03', '14:29:00', 141020009, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Prakash Shiram ', 'Ganraj Construction', 'Followup Required , wants 2 banners', 'VJTI'),
('65', '2015-04-05', '13:30:00', 131090006, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Ashok Jalan ', 'Siemens', 'Followup Required', '130, Pandurang Budhkar Marg, Worli, Maharashtra, Mumbai 400 018.'),
('66', '2015-04-06', '12:52:00', 131010004, 'Pratibimb', 'Pratibimb-2016', 'Call', NULL, 'BIG FM 92.7', NULL, 'VJTI'),
('67', '2015-04-07', '12:00:00', 141030005, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Neeta Khatib ', 'Heinz Co', 'Followup Required', 'One PPG Place, Pittsburgh, PA 15219'),
('68', '2015-04-08', '13:30:00', 141080010, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Drishti Lekhrajani ', 'The Park', 'Unvailable For Meeting', 'Sector 10, Cbd Belapur'),
('69', '2015-04-09', '14:59:00', 141080006, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Francisco D''souza ', 'Cognizant India', 'Followup Required', 'VJTI'),
('70', '2015-04-10', '11:37:00', 131010004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Very Interested Company', 'VJTI'),
('71', '2015-04-11', '12:34:00', 131050004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Anuj Dharap ', 'Metro Travels', 'Unvailable For Meeting', 'VJTI'),
('72', '2015-04-14', '12:00:00', 141080010, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Drishti Lekhrajani ', 'The Park', 'Followup Required, wants to be lone sponsor in hotel sector', 'Sector 10, Cbd Belapur'),
('73', '2015-04-17', '16:38:00', 141030005, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Neeta Khatib ', 'Heinz Co', 'Unvailable For Meeting', 'VJTI'),
('74', '2015-04-18', '13:27:00', 141080006, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Francisco D''souza ', 'Cognizant India', 'Very Interested Company', 'VJTI'),
('75', '2015-04-20', '14:17:00', 141030005, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Neeta Khatib ', 'Heinz Co', 'Followup Required', 'VJTI'),
('76', '2015-04-21', '13:30:00', 131050004, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Anuj Dharap ', 'Metro Travels', 'Very Interested Company , Company wants to become title sponsor', '2/5, 1st Rabodi, Thane West, Thane - 400601'),
('77', '2015-04-23', '15:45:00', 141020004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Ayush Shah ', 'PepsiCo', 'Very Interested Company', 'VJTI'),
('78', '2015-04-24', '13:26:00', 141020009, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Prakash Shiram ', 'Ganraj Construction', 'Followup Required', 'VJTI'),
('79', '2015-04-25', '16:48:00', 131010004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Unvailable For Meeting', 'VJTI'),
('80', '2015-04-26', '14:34:00', 141080006, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Francisco D''souza ', 'Cognizant India', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
('81', '2015-04-27', '12:56:00', 141030005, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Neeta Khatib ', 'Heinz Co', 'Followup Required', 'VJTI'),
('82', '2015-04-28', '13:30:00', 131010004, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Naveen Saxena ', 'BIG FM 92.7', 'Final Meeting , Sponsorship Confirmed', '401, 4th Floor, Infiniti Mall, Oshiwara, New Link Road, Andheri West, Mumbai ? 400053'),
('83', '2015-04-30', '10:19:00', 141080010, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Drishti Lekhrajani ', 'The Park', 'Followup Required want 2 banners', 'VJTI'),
('84', '2015-05-01', '14:43:00', 141030005, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Neeta Khatib ', 'Heinz Co', 'Followup Required', 'VJTI'),
('85', '2015-05-03', '12:00:00', 131090006, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Ashok Jalan ', 'Siemens', 'Final Meeting , Sponsorship Confirmed', '130, Pandurang Budhkar Marg, Worli, Maharashtra, Mumbai 400 018.'),
('86', '2015-05-05', '15:44:00', 141020009, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Prakash Shiram ', 'Ganraj Construction', 'Followup Required , wants 3000 for painting off quad wall', 'VJTI'),
('87', '2015-05-06', '11:55:00', 131070004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Jyoti Kumar ', 'ABB', 'Not Interested', 'VJTI'),
('88', '2015-05-07', '16:00:00', 141020004, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Ayush Shah ', 'PepsiCo', 'Final Meeting , Sponsorship Confirmed', '700 Anderson Hill Road, Purchase, NY 10577-1444'),
('89', '2015-05-08', '16:28:00', 131070004, 'Pratibimb', 'Pratibimb-2016', 'Call', 'V B Haribhakti ', 'Bajaj Electricals', 'Not Interested', 'VJTI'),
('90', '2015-05-09', '14:00:00', 131050004, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Anuj Dharap ', 'Metro Travels', 'Final Meeting , Sponsorship Confirmed', '2/5, 1st Rabodi, Thane West, Thane - 400601'),
('91', '2015-05-10', '12:22:00', 141080009, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Harish Patel ', 'Nestle', NULL, NULL),
('92', '2015-05-11', '14:21:00', 141080009, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Neetu Karnani ', 'Mars Inc.', 'Not Interested', 'VJTI'),
('93', '2015-05-14', '13:30:00', 141020009, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Prakash Shiram ', 'Ganraj Construction', 'Final Meeting , Sponsorship Confirmed', 'Kalyan Road, Dombivli East'),
('94', '2015-05-18', '12:34:00', 141020010, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Sanjay Nayak ', 'Tejas Networks', 'Not Interested', 'VJTI'),
('95', '2015-05-20', '14:22:00', 141030005, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Neeta Khatib ', 'Heinz Co', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
('96', '2015-05-21', '13:55:00', 141060009, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Prachi Sagwekar ', 'Goel Steel', 'Not Interested', 'VJTI'),
('97', '2015-05-23', '14:46:00', 141080011, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Mahaveer Bhansali ', 'Falcon Tyres', 'Not Interested', 'VJTI'),
('98', '2015-05-24', '13:30:00', 141080010, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Drishti Lekhrajani ', 'The Park', 'Final Meeting , Sponsorship Confirmed', 'Sector 10, Cbd Belapur'),
('99', '2016-06-07', '00:00:00', 131080051, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Abhijeet Nachane', 'ABB', 'Another Meeting has been scheduled', NULL),
('100', '2016-06-11', '17:30:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Aditya Kale', 'Fabindia', '(Update after Meeting)', NULL),
('101', '2016-06-11', '17:30:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Face-To-Face Meeting', 'Hardik Rana', 'Fabindia', '(Update after Meeting)', NULL),
('MEET-5O0US', '2016-06-12', '13:00:00', 131080008, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Hardik Rana', 'Fabindia', '(Update after Meeting)', NULL),
('MEET-VWJ5A', '2016-06-13', '15:57:00', 121080001, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Onkar Chichkar', 'Adidas', '(Update after Meeting)', 'VJTI'),
('MEET-ILVOI', '2016-06-13', '17:00:00', 121080001, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Onkar Chichkar', 'Adidas', '(Update after Meeting)', 'vjti'),
('MEET-HV3VV', '2016-06-13', '18:00:00', 121080001, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Onkar Chichkar', 'Adidas', '(Update after Meeting)', 'vjti'),
('MEET-I70FK', '2016-06-13', '19:00:00', 121080001, 'Pratibimb', 'Pratibimb-2016', 'Call', 'Onkar Chichkar', 'Adidas', '(Update after Meeting)', 'vjti');

-- --------------------------------------------------------

--
-- Table structure for table `SectorHead`
--

CREATE TABLE IF NOT EXISTS `SectorHead` (
  `SponsID` int(9) unsigned NOT NULL,
  `Sector` varchar(15) NOT NULL,
  `DateAssigned` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SectorHead`
--

INSERT INTO `SectorHead` (`SponsID`, `Sector`, `DateAssigned`) VALUES
(121010001, 'Radio', '2016-03-21'),
(121020001, 'Music Stores', NULL),
(121020002, 'FMCG', '2016-03-21'),
(121030001, 'Tyres', NULL),
(121030002, 'Hotel', NULL),
(121050001, 'Radio', NULL),
(121060001, 'Steel', NULL),
(121060002, 'IT', NULL),
(121060003, 'Electricals', NULL),
(121060004, 'FMCG', NULL),
(121060005, 'Clothes Retail', NULL),
(121070001, 'IT', NULL),
(121080001, 'Clothes Retail', NULL),
(121080002, 'Ecommerce', NULL),
(121080003, 'Travel', NULL),
(121081003, 'Steel', '2016-03-20'),
(121090001, 'Builders', NULL),
(777, 'Hotel', '2016-06-13');

-- --------------------------------------------------------

--
-- Table structure for table `SponsLogin`
--

CREATE TABLE IF NOT EXISTS `SponsLogin` (
  `SponsID` int(9) unsigned NOT NULL,
  `Password` varchar(255) NOT NULL,
  `AccessLevel` enum('Sponsorship Representative','Sector Head','CSO') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SponsLogin`
--

INSERT INTO `SponsLogin` (`SponsID`, `Password`, `AccessLevel`) VALUES
(121010001, '49f68a5c8493ec2c0bf489821c21fc3b', 'Sector Head'),
(121020001, '121020001test', 'Sector Head'),
(121020002, '49f68a5c8493ec2c0bf489821c21fc3b', 'Sector Head'),
(121030001, '121030001test', 'Sector Head'),
(121030002, '121030002test', 'Sector Head'),
(121050001, '121050001test', 'Sector Head'),
(121060001, '121060001test', 'Sector Head'),
(121060002, '121060002test', 'Sector Head'),
(121060003, '121060003test', 'Sector Head'),
(121060004, '121060004test', 'Sector Head'),
(121060005, '121060005test', 'Sector Head'),
(121070001, '121070001test', 'Sector Head'),
(121080001, '121080001test', 'Sector Head'),
(121080002, '121080002test', 'Sector Head'),
(121080003, '121080003test', 'Sector Head'),
(121081003, '49f68a5c8493ec2c0bf489821c21fc3b', 'Sector Head'),
(121090001, '121090001test', 'Sector Head'),
(131010003, '9cdfb439c7876e703e307864c9167a15', 'Sponsorship Representative'),
(131010004, '131010004testing', 'Sponsorship Representative'),
(131010006, '131010006testing', 'Sponsorship Representative'),
(131020006, '131020006testing', 'Sponsorship Representative'),
(131020007, '131020007testing', 'Sponsorship Representative'),
(131020008, '131020008testing', 'Sponsorship Representative'),
(131020012, '131020012testing', 'Sponsorship Representative'),
(131030004, '131030004testing', 'Sponsorship Representative'),
(131040002, '131040002testing', 'Sponsorship Representative'),
(131050003, '131050003testing', 'Sponsorship Representative'),
(131050004, '131050004testing', 'Sponsorship Representative'),
(131050006, '131050006testing', 'Sponsorship Representative'),
(131050007, '131050007testing', 'Sponsorship Representative'),
(131070002, '131070002testing', 'Sponsorship Representative'),
(131070003, '131070003testing', 'Sponsorship Representative'),
(131070004, '131070004testing', 'Sponsorship Representative'),
(131070006, '131070006testing', 'Sponsorship Representative'),
(131080007, '131080007testing', 'Sponsorship Representative'),
(131080008, '131080008testing', 'Sponsorship Representative'),
(131080012, '131080012testing', 'Sponsorship Representative'),
(131080013, '131080013testing', 'Sponsorship Representative'),
(131080051, 'abhishekdivekar', 'CSO'),
(131080053, 'advikshetty', 'CSO'),
(131080055, 'janitmehta', 'CSO'),
(131090002, '131090002testing', 'Sponsorship Representative'),
(131090003, '131090003testing', 'Sponsorship Representative'),
(131090004, '131090004testing', 'Sponsorship Representative'),
(131090005, '131090005testing', 'Sponsorship Representative'),
(131090006, '131090006testing', 'Sponsorship Representative'),
(131090007, '131090007testing', 'Sponsorship Representative'),
(131090008, '131090008testing', 'Sponsorship Representative'),
(141010002, '141010002testing', 'Sponsorship Representative'),
(141010005, '141010005testing', 'Sponsorship Representative'),
(141020003, '141020003testing', 'Sponsorship Representative'),
(141020004, '141020004testing', 'Sponsorship Representative'),
(141020005, '141020005testing', 'Sponsorship Representative'),
(141020009, '141020009testing', 'Sponsorship Representative'),
(141020010, '141020010testing', 'Sponsorship Representative'),
(141020011, '141020011testing', 'Sponsorship Representative'),
(141030003, '141030003testing', 'Sponsorship Representative'),
(141030005, '141030005testing', 'Sponsorship Representative'),
(141040001, '141040001testing', 'Sponsorship Representative'),
(141040003, '141040003testing', 'Sponsorship Representative'),
(141050002, '141050002testing', 'Sponsorship Representative'),
(141050005, '141050005testing', 'Sponsorship Representative'),
(141060006, '141060006testing', 'Sponsorship Representative'),
(141060007, '141060007testing', 'Sponsorship Representative'),
(141060008, '141060008testing', 'Sponsorship Representative'),
(141060009, '141060009testing', 'Sponsorship Representative'),
(141060010, '141060010testing', 'Sponsorship Representative'),
(141060011, '141060011testing', 'Sponsorship Representative'),
(141070005, '141070005testing', 'Sponsorship Representative'),
(141080004, '141080004testing', 'Sponsorship Representative'),
(141080005, '141080005testing', 'Sponsorship Representative'),
(141080006, '141080006testing', 'Sponsorship Representative'),
(141080009, '141080009testing', 'Sponsorship Representative'),
(141080010, '141080010testing', 'Sponsorship Representative'),
(141080011, '141080011testing', 'Sponsorship Representative'),
(141081001, '49f68a5c8493ec2c0bf489821c21fc3b', 'Sponsorship Representative'),
(141081002, '49f68a5c8493ec2c0bf489821c21fc3b', 'Sponsorship Representative'),
(654, '341561212d59a5e81798e595ae6a7a7cb01c39f7', 'Sponsorship Representative'),
(555, '$2y$11$CyAkNPUWSVaCT3gtMiDiKuZhazAzAdScvOR8Hos9o9vvgBOxESbX2', 'Sponsorship Representative'),
(8888, '$2y$11$FRzdhjOE5ph3B608Y/Y3lubMldNQRK5q5Q7e/CG4yHtaeaQSevWmW', 'Sponsorship Representative'),
(777, '$2y$11$dvNZG3JD9vr80EPOtdWNCO92OK7x32oxklwxkwRzT6VRKW409HsFy', 'Sector Head');

-- --------------------------------------------------------

--
-- Table structure for table `SponsRep`
--

CREATE TABLE IF NOT EXISTS `SponsRep` (
  `SponsID` int(9) unsigned NOT NULL,
  `Sector` varchar(15) NOT NULL,
  `DateAssigned` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SponsRep`
--

INSERT INTO `SponsRep` (`SponsID`, `Sector`, `DateAssigned`) VALUES
(131010003, 'Radio', '2016-03-21'),
(131010004, 'Radio', '2015-01-01'),
(131010006, 'Electricals', '2015-01-01'),
(131020006, 'FMCG', '2015-01-01'),
(131020007, 'Builders', '2015-01-01'),
(131020008, 'Ecommerce', '2015-01-01'),
(131020012, 'FMCG', '2015-01-01'),
(131030004, 'Steel', '2015-01-01'),
(131040002, 'Music Stores', '2015-01-01'),
(131050003, 'Radio', '2015-01-01'),
(131050004, 'Travel', '2015-01-01'),
(131050006, 'Radio', '2015-01-01'),
(131050007, 'Music Stores', '2015-01-01'),
(131070002, 'Ecommerce', '2015-01-01'),
(131070003, 'Clothes Retail', '2015-01-01'),
(131070004, 'Electricals', '2015-01-01'),
(131070006, 'Tyres', '2015-01-01'),
(131080007, 'Steel', '2015-01-01'),
(131080008, 'Clothes Retail', '2015-01-01'),
(131080012, 'Hotel', '2015-01-01'),
(131080013, 'Builders', '2015-01-01'),
(131090002, 'Tyres', '2015-01-01'),
(131090003, 'Electricals', '2015-01-01'),
(131090004, 'Hotel', '2015-01-01'),
(131090005, 'Tyres', '2015-01-01'),
(131090006, 'IT', '2015-01-01'),
(131090007, 'Hotel', '2015-01-01'),
(131090008, 'IT', '2015-01-01'),
(141010002, 'Music Stores', '2015-01-01'),
(141010005, 'Travel', '2015-01-01'),
(141020003, 'Clothes Retail', '2015-01-01'),
(141020004, 'FMCG', '2015-01-01'),
(141020005, 'Travel', '2015-01-01'),
(141020009, 'Builders', '2015-01-01'),
(141020010, 'IT', '2015-01-01'),
(141020011, 'Radio', '2015-01-01'),
(141030003, 'Builders', '2015-01-01'),
(141030005, 'FMCG', '2015-01-01'),
(141040001, 'Ecommerce', '2015-01-01'),
(141040003, 'Music Stores', '2015-01-01'),
(141050002, 'Hotel', '2015-01-01'),
(141050005, 'Electricals', '2015-01-01'),
(141060006, 'Tyres', '2015-01-01'),
(141060007, 'IT', '2015-01-01'),
(141060008, 'Radio', '2015-01-01'),
(141060009, 'Steel', '2015-01-01'),
(141060010, 'Clothes Retail', '2015-01-01'),
(141060011, 'Ecommerce', '2015-01-01'),
(141070005, 'FMCG', '2015-01-01'),
(141080004, 'Music Stores', '2015-01-01'),
(141080005, 'Steel', '2015-01-01'),
(141080006, 'Electricals', '2015-01-01'),
(141080009, 'Travel', '2015-01-01'),
(141080010, 'Hotel', '2015-01-01'),
(141080011, 'Tyres', '2015-01-01'),
(141081001, 'Steel', '2016-03-20'),
(654, 'Shipping', '2016-06-11'),
(555, 'AA', '2016-06-11');

-- --------------------------------------------------------


DROP VIEW IF EXISTS `SponsWorker`;

CREATE VIEW SponsWorker AS
(SELECT SponsID, Sector, DateAssigned, "Sponsorship Representative" AS Role FROM SponsRep )
UNION
(SELECT SponsID, Sector, DateAssigned, "Sector Head" AS Role FROM SectorHead )
UNION
(SELECT ID AS SponsID, "All" as Sector, NULL AS DateAssigned, Role FROM CommitteeMember WHERE Role = "CSO" ) ;




DROP VIEW IF EXISTS `SponsOfficer`;

CREATE VIEW SponsOfficer AS
SELECT Organization, EventName, SponsID, Name, CM.Role as Role, Sector, DateAssigned, Mobile, Email, Year, Branch
FROM
  SponsWorker
  INNER JOIN
  CommitteeMember AS CM
  ON CM.ID = SponsWorker.SponsID

  Where CM.Department = "Sponsorship" ;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `AccountLog`
--
ALTER TABLE `AccountLog`
 ADD PRIMARY KEY (`ID`), ADD KEY `SponsID` (`SponsID`), ADD KEY `Organization` (`Organization`,`EventName`);

--
-- Indexes for table `CommitteeMember`
--
ALTER TABLE `CommitteeMember`
 ADD PRIMARY KEY (`ID`), ADD KEY `Organization` (`Organization`,`EventName`);

--
-- Indexes for table `Company`
--
ALTER TABLE `Company`
 ADD PRIMARY KEY (`CMPName`);

--
-- Indexes for table `CompanyExec`
--
ALTER TABLE `CompanyExec`
 ADD PRIMARY KEY (`CEName`,`CMPName`), ADD KEY `CMPName` (`CMPName`);

--
-- Indexes for table `Event`
--
ALTER TABLE `Event`
 ADD PRIMARY KEY (`EventID`), ADD UNIQUE KEY `Organization` (`Organization`,`EventName`);

--
-- Indexes for table `Meeting`
--
ALTER TABLE `Meeting`
 ADD PRIMARY KEY (`ID`), ADD KEY `SponsID` (`SponsID`), ADD KEY `CMPName` (`CMPName`), ADD KEY `CEName` (`CEName`,`CMPName`), ADD KEY `Organization` (`Organization`,`EventName`);

--
-- Indexes for table `SectorHead`
--
ALTER TABLE `SectorHead`
 ADD PRIMARY KEY (`SponsID`);

--
-- Indexes for table `SponsLogin`
--
ALTER TABLE `SponsLogin`
 ADD PRIMARY KEY (`SponsID`);

--
-- Indexes for table `SponsRep`
--
ALTER TABLE `SponsRep`
 ADD PRIMARY KEY (`SponsID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `AccountLog`
--
ALTER TABLE `AccountLog`
AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `Event`
--
ALTER TABLE `Event`
MODIFY `EventID` int(7) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Meeting`
--
ALTER TABLE `Meeting`
AUTO_INCREMENT=102;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
