-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 20, 2017 at 12:58 PM
-- Server version: 5.7.11
-- PHP Version: 7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lzdssystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(10) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `system_log` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_log` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fee_items`
--

CREATE TABLE `fee_items` (
  `id` int(11) NOT NULL,
  `fee_id` int(11) DEFAULT NULL,
  `school_year` varchar(7) DEFAULT NULL,
  `level` int(10) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `system_log` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_log` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grade_levels`
--

CREATE TABLE `grade_levels` (
  `id` int(10) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `system_log` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_log` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grade_levels`
--

INSERT INTO `grade_levels` (`id`, `description`, `system_log`, `update_log`) VALUES
(1, 'Nursery', '2017-05-15 22:09:52', NULL),
(2, 'Kindergarten', '2017-05-15 22:09:52', NULL),
(3, 'Grade 1', '2017-05-15 22:10:11', NULL),
(4, 'Grade 2', '2017-05-15 22:10:11', NULL),
(5, 'Grade 3', '2017-05-15 22:10:22', NULL),
(6, 'Grade 4', '2017-05-15 22:10:22', NULL),
(7, 'Grade 5', '2017-05-15 22:10:33', NULL),
(8, 'Grade 6', '2017-05-15 22:10:33', NULL),
(9, 'Grade 7', '2017-05-15 22:10:47', NULL),
(10, 'Grade 8', '2017-05-15 22:10:47', NULL),
(11, 'Grade 9', '2017-05-15 22:11:01', NULL),
(12, 'Grade 10', '2017-05-15 22:11:01', NULL),
(13, 'Grade 11', '2017-05-15 22:11:11', NULL),
(14, 'Grade 12', '2017-05-15 22:11:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE `school_years` (
  `id` int(10) NOT NULL,
  `school_year` varchar(7) DEFAULT NULL,
  `system_log` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `school_years`
--

INSERT INTO `school_years` (`id`, `school_year`, `system_log`) VALUES
(1, '2017-18', '2017-05-20 12:51:00');

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `id` int(11) NOT NULL,
  `school_id` varchar(20) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `civil_status` varchar(50) DEFAULT NULL,
  `address` varchar(1000) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `basic_pay` float(10,2) NOT NULL DEFAULT '0.00',
  `cola` float(10,2) NOT NULL DEFAULT '0.00',
  `gross_pay` float(10,2) NOT NULL DEFAULT '0.00',
  `sss` varchar(20) DEFAULT NULL,
  `hdmf` varchar(20) DEFAULT NULL,
  `phic` varchar(20) DEFAULT NULL,
  `tin` varchar(20) DEFAULT NULL,
  `st` varchar(20) DEFAULT NULL,
  `sss_amount` float(10,2) NOT NULL DEFAULT '0.00',
  `hdmf_amount` float(10,2) NOT NULL DEFAULT '0.00',
  `phic_amount` float(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` float(10,2) NOT NULL DEFAULT '0.00',
  `position` varchar(100) DEFAULT NULL,
  `employment_status` varchar(100) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `staff_account_group` int(10) DEFAULT NULL,
  `is_built_in` tinyint(4) DEFAULT '0',
  `system_log` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_log` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`id`, `school_id`, `firstname`, `middlename`, `lastname`, `nickname`, `gender`, `birthday`, `civil_status`, `address`, `contact`, `email`, `basic_pay`, `cola`, `gross_pay`, `sss`, `hdmf`, `phic`, `tin`, `st`, `sss_amount`, `hdmf_amount`, `phic_amount`, `tax_amount`, `position`, `employment_status`, `username`, `password`, `staff_account_group`, `is_built_in`, `system_log`, `update_log`) VALUES
(1, NULL, 'Sylvester', 'Bulilan', 'Flores', 'Sly', NULL, '0000-00-00', NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, '', 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'sly', 'legend', NULL, 1, '2017-04-16 17:38:06', NULL),
(2, '3001', 'Chastine Ann', 'Alminiana', 'Acosta', NULL, 'Female', '2017-05-11', '2', 'Sitio Paratong, Poblacion, Bacnotan, La Union ', '09194800684', 'yashine_ann05@yahoo.com', 7000.00, 0.00, 7000.00, '0121964821', '', '050502034364', '423-184-527', '', 233.30, 100.00, 87.50, 0.00, '', 'EOC', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(3, '10105', 'Aladin', 'Romero', 'Almirol', NULL, NULL, '0000-00-00', NULL, 'Burayoc, Bacnotan, La Union', '09289073685', 'aladin.almirol@yahoo.com', 13000.00, 0.00, 13000.00, '0117039595', '', '050251246537', '939-202-905', '', 400.00, 100.00, 87.50, 990.83, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(4, '3002', 'Maribel ', 'Cariaso', 'Almoite', NULL, NULL, '0000-00-00', NULL, 'Pang-Pang, Bacnotan, La Union', '09129793286', 'delfinado.bel@yahoo.com', 7500.00, 0.00, 7500.00, '0120911998', '0', '050501788594', '407-383-646', '', 250.00, 100.00, 50.00, 106.25, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(5, '3003', 'Harvey', 'Rulloda', 'Asis', NULL, NULL, '0000-00-00', NULL, 'Paraoir, Balaoan, La Union', '09123414194', 'asisharvey@yahoo.com', 7000.00, 0.00, 7000.00, '012105471', '912304040116', '050253278606', '428-498-161', '', 233.30, 100.00, 87.50, 107.92, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(6, '3004', 'Arnel', 'Aromin', 'Calado', NULL, NULL, '0000-00-00', NULL, 'Poblacion, Bacnotan, La Union', '09399152464', 'calado.arnel@yahoo.com', 7500.00, 0.00, 7500.00, '0118398510', '', '050501886561', '255-053-248', '', 250.00, 100.00, 87.50, 56.25, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(7, '3005', 'Harry', 'Sagudang', 'Dagdag', NULL, NULL, '0000-00-00', NULL, 'Salcedo, Luna, La Union', '09193656497', 'josh_ferrero_king22@yahoo.com', 7800.00, 0.00, 7800.00, '0121964782', '', '050502034372', '423-182-998', '', 250.00, 100.00, 87.50, 124.58, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(8, '10118', 'Manuel', 'Suyat', 'Domondon', NULL, NULL, '0000-00-00', NULL, 'Paratong, Poblacion, Bacnotan, LU', '09092870051', 'many_kaulitz89@yahoo.co', 7500.00, 0.00, 7500.00, '0113321391', '', '050501661713', '294-926-201', '', 250.00, 100.00, 87.50, 106.25, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(9, '10104', 'Fernan', 'Calip', 'Flores', NULL, NULL, '0000-00-00', NULL, 'Urbiztondo, San Juan, La Union', '09204752739', 'fernanflores30@yahoo.com', 8500.00, 0.00, 8500.00, '0116582470', '128001165648', '050501282089', '933-125-580', '', 283.30, 100.00, 87.50, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(10, '10117', 'Joel', 'Mapaet', 'Ganotisi', NULL, NULL, '0000-00-00', NULL, 'Ilocano Sur, San Fernando City, La Union', '09199446532', 'jr.ganotisi@yahoo.com', 7800.00, 0.00, 7800.00, '0117538520', '128000712079', '050500978411', '939-203-260', '', 266.70, 100.00, 87.50, 134.58, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(11, '10108', 'Jennyfer', 'Almoite', 'Macadenden', NULL, NULL, '0000-00-00', NULL, 'Cabarsican, Bacnotan, La Union', '09129130131', 'jennyferalmoite@yahoo.com', 7500.00, 0.00, 7500.00, '0118327853', '', '050252163821', '261-730-490', '', 250.00, 100.00, 87.50, 156.25, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(12, '10109', 'Jen Jen ', 'Gaudia', 'Obra', NULL, NULL, '0000-00-00', NULL, 'Tammocalao, Bacnotan, La Union', '09105777553', 'jeangeance@yahoo.com', 7000.00, 0.00, 7000.00, '0121909336', '912142048799', '050253273442', '', '', 233.30, 100.00, 87.50, 57.92, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(13, '10115', 'Almajoy', 'Sagun', 'Panit', NULL, NULL, '0000-00-00', NULL, 'San Martin, Bacnotan, La Union', '09107201582', 'almajoypanit@yahoo.com', 7500.00, 0.00, 7500.00, '0118041003', '', '040500849195', '261-198-980', '', 250.00, 100.00, 87.50, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(14, '10107', 'Darren', 'Depdepen', 'Peralta', NULL, NULL, '0000-00-00', NULL, 'Duplas, Sudipen, La Union', '09279098353', 'darrenperalta77@yahoo.com', 8000.00, 0.00, 8000.00, '0121047874', '', '050501798743', '409-200-099', '', 266.70, 100.00, 87.50, 154.58, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(15, '3006', 'Jowel ', 'Daigan', 'Ramos', NULL, NULL, '0000-00-00', NULL, 'Bangar, La Union', '09097281232', 'jowel_ramos10@yahoo.com', 7500.00, 0.00, 0.00, '0121355155', '010169561903', '050501577976', '422-099-195', '', 250.00, 100.00, 87.50, 76.25, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(16, '10113', 'Arnold', 'Flora', 'San Miguel', NULL, NULL, '0000-00-00', NULL, 'San Pablo, Balaoan, La Union', '09278656234', 'arnoldsanmiguel22@yahoo.com', 8000.00, 0.00, 8000.00, '0121018395', '', '050501798778', '408-856-514', '', 266.70, 100.00, 87.50, 154.58, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(17, '3007', 'Joey ', 'Fontanilla', 'Valdriz', NULL, NULL, '0000-00-00', NULL, 'Casilogan, San Juan, La Union', '09098466585', 'jv.bel.homme@gmail.com', 8500.00, 500.00, 9000.00, '0119488157', '010166169002', '050252739642', '409-236-573', '', 283.30, 100.00, 87.50, 202.92, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:01', NULL),
(18, '3008', 'Riza', 'Valmores', 'Sibayan', NULL, NULL, '0000-00-00', NULL, 'Lisqueb, Bacnotan, La Union', '09468890576', 'rhyegu_13@yahoo.com', 8800.00, 0.00, 8800.00, '0119373877', '010162190803', '050252075620', '278-421-492', '', 300.00, 100.00, 87.50, 410.21, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(19, '10114', 'Divine Grace', 'Depdepen', 'Peralta', NULL, NULL, '0000-00-00', NULL, 'Duplas, Sudipen, La Union', '09103733166', '', 0.00, 0.00, 0.00, '0122801899', '121118190101', '050502350800', '454-456-954', '', 0.00, 0.00, 0.00, 0.00, '', 'Regular', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(20, '10112', 'Kryssa', 'Suniega', 'Ominga', NULL, NULL, '0000-00-00', NULL, 'Guinabang, Bacnotan, La Union', '09109754939', '', 0.00, 0.00, 0.00, '01-2477468-1', '1211-3511-0644', '05-025505-1315', '468-410-773', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(21, '10110', 'Aurora', 'Avecilla', 'Murao', NULL, NULL, '0000-00-00', NULL, 'Sitio Paratong Poblacion, Bacnotan, La Union', '09298115818', '', 0.00, 0.00, 0.00, '0115813137', '', '05025364963', '467429315', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(22, '0', 'Jane', 'OrdoÃ±o', 'Conde', NULL, NULL, '0000-00-00', NULL, 'Sengngat, Sudipen, La Union', '09309769141', '', 0.00, 0.00, 0.00, '0122589645', '913749072006', '050502231712', '436-620-948', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(23, '10119', 'Jessa Lyn', 'Brutas', 'Andidew', NULL, NULL, '0000-00-00', NULL, 'Naguituban, San Juan, La Union', '09465025029', '', 0.00, 0.00, 0.00, '0124793808', '915125566485', '050255176479', '', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(24, '10111', 'Jenny Lyn', 'Gaudia', 'Gaudia', NULL, NULL, '0000-00-00', NULL, '85 Tamocalao, Bacnotan, La Union', '09773480879', '', 0.00, 0.00, 0.00, '0124654769', '121142920054', '', '', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(25, '10116', 'Xyryl ', 'Camero', 'Dacanay', NULL, NULL, '0000-00-00', NULL, 'Umingan, Pangasinan', '09105572742', '', 0.00, 0.00, 0.00, '0124475809', '121143860142', '', '468-875-830', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(26, '10122', 'Eleanor', 'Rivera', 'Cardinez', NULL, NULL, '0000-00-00', NULL, '593 Sitio Paratong, Poblacion, Bacnotan, La Union', '09198535901', '', 0.00, 0.00, 0.00, '01-1021517-0', '', '19-090241228-3', '184-546-476', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(27, '10106', 'Clar Reneith', 'Militar', 'Balimbin', NULL, NULL, '0000-00-00', NULL, 'Sitio Paratong, Poblacion, Bacnotan, La Union', '09109099925', 'clar1231231@gmail.com', 7000.00, 1000.00, 8000.00, '0726861879', '121011212365', '110505240189', '414-528-437', '', 254.30, 100.00, 100.00, 0.00, 'Registrar', 'Contractual', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(28, '10121', 'Wilbert', 'Rapin', 'Fabros', NULL, NULL, '0000-00-00', NULL, 'Paratong, Bacnotan, La Union', '09074358094', '', 0.00, 0.00, 0.00, '0112175292', '010102015801', '190902417013', '930-903-722', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(29, '10101', 'Normita', 'Quimpo', 'Tria', NULL, NULL, '0000-00-00', NULL, 'Conleaf Compound, Bacnotan, La Union', '6074007', 'normita.tria@yahoo.com', 0.00, 0.00, 0.00, '', '', '', '', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(30, '10102', 'Jonabel', 'Aguilar', 'Tria', NULL, NULL, '0000-00-00', NULL, 'Sitio Paratong, Poblacion, Bacnotan, La Union', '09088650702', 'jonabel.tria@yahoo.com', 0.00, 0.00, 0.00, '', '', '', '', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(31, '10120', 'Paul', 'Ramos', 'Rodriguez', NULL, NULL, '0000-00-00', NULL, 'Paratong, Poblacion, Bacnotan, La Union', '09994073940', '', 0.00, 0.00, 0.00, '0110598680', '010151477603', '050500407538', '270-100-310', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(32, '10103', 'Frederick ', 'Quimpo', 'Tria', NULL, NULL, '0000-00-00', NULL, 'Sitio Paratong, Poblacion, Bacnotan, La Union', '09209097818', 'erick3ya@gmail.com', 0.00, 0.00, 0.00, '0109194189', '010138545305', '050500407570', '911-735-292', '', 0.00, 0.00, 0.00, 0.00, '', '', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(33, '10124', 'Dexter ', 'F', 'Tagumpay', NULL, NULL, '0000-00-00', NULL, 'Poblacion, Bacnotan, La Union', '09393649003', '', 2000.00, 0.00, 2000.00, '01-1581328-9', '', '19-090242238-6', '406325550', '', 0.00, 0.00, 0.00, 0.00, 'Maintenance Personnel', 'Contractual', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(34, '10123', 'Marjorie', 'Pasamonte', 'Gallebo', NULL, NULL, '0000-00-00', NULL, 'San Juan, La Union', '-', '', 7000.00, 0.00, 7000.00, '', '915321318884', '050502765687	', '438-810-361', '', 0.00, 0.00, 0.00, 0.00, '', 'Regular', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(35, '10129', 'Richelle', 'Idigpio', 'Nieva', NULL, NULL, '0000-00-00', NULL, 'Paagan, Bacnotan, La Union', '09102858111', '', 7000.00, 0.00, 7000.00, '', '', '', '', '', 254.30, 1000.00, 100.00, 0.00, 'Teacher Librarian', 'Contractual', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(36, '10127', 'Rizaldo', 'Agtarap', 'Valdez', NULL, NULL, '0000-00-00', NULL, 'Butubut, Balaoan, La Union', '09282532885', '', 7500.00, 500.00, 8000.00, '0118883533', '01016169711', '050251189029', '262-235-078', '', 272.50, 100.00, 100.00, 0.00, 'Teacher', 'Contractual', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(37, '10126', 'Mariane Joy', 'Cabrera', 'Neri', NULL, NULL, '0000-00-00', NULL, '517 Sitio Paratong, Poblacion\nBacnotan', '09468395604', 'marianejoyneri@yahoo.com', 7000.00, 0.00, 7000.00, '0125482150', '121170410631', '050502853187', '484-542-263', '', 254.30, 100.00, 100.00, 0.00, 'Teacher', 'Contractual', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(38, '10128', 'Myla', 'Carrera', 'Valdez', NULL, NULL, '0000-00-00', NULL, 'Bulala, Bacnotan, La Union', '09097233140', 'myel148491@gmail.com', 7000.00, 0.00, 7000.00, '0119137550', '121005700459', '01057745720', '273-815-855', '', 254.30, 100.00, 100.00, 0.00, 'Teacher', 'Contractual', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(39, '10125', 'Gracelyn', 'Macinas', 'Logenio', NULL, NULL, '0000-00-00', NULL, 'Cabaroan, Bacnotan, La Union', '09099281651', 'gracelyn_logenio@yahoo.com', 7000.00, 0.00, 7000.00, '0125669931', '121167335507', '050502850927', '453-343-171', '', 254.30, 100.00, 100.00, 0.00, 'Teacher', 'Contractual', NULL, NULL, NULL, 0, '2017-05-08 22:08:02', NULL),
(40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 0, '2017-05-14 20:51:08', '2017-05-14 20:51:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_items`
--
ALTER TABLE `fee_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fee_id` (`fee_id`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `grade_levels`
--
ALTER TABLE `grade_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fee_items`
--
ALTER TABLE `fee_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `grade_levels`
--
ALTER TABLE `grade_levels`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `fee_items`
--
ALTER TABLE `fee_items`
  ADD CONSTRAINT `fee_items_ibfk_1` FOREIGN KEY (`fee_id`) REFERENCES `fees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fee_items_ibfk_2` FOREIGN KEY (`level`) REFERENCES `grade_levels` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `shool_year` ON SCHEDULE EVERY 1 YEAR STARTS '2018-05-20 00:00:00' ENDS '2028-05-20 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO school_years (school_year) VALUES (CONCAT(DATE_FORMAT(now(),"%Y-"),DATE_FORMAT((now()+INTERVAL 1 YEAR),"%y")))$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
