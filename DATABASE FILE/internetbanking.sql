-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2024 at 08:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tekoncoz_internetbanking`
--

-- --------------------------------------------------------

--
-- Table structure for table `ib_acc_types`
--

CREATE TABLE `ib_acc_types` (
  `acctype_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` longtext NOT NULL,
  `rate` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_acc_types`
--

INSERT INTO `ib_acc_types` (`acctype_id`, `name`, `description`, `rate`, `code`) VALUES
(1, 'Savings', '<p>Savings accounts&nbsp;are typically the first official bank account anybody opens. Children may open an account with a parent to begin a pattern of saving. Teenagers open accounts to stash cash earned&nbsp;from a first job&nbsp;or household chores.</p><p>Savings accounts are an excellent place to park&nbsp;emergency cash. Opening a savings account also marks the beginning of your relationship with a financial institution. For example, when joining a credit union, your &ldquo;share&rdquo; or savings account establishes your membership.</p>', '20', 'ACC-CAT-4EZFO'),
(2, ' Retirement', '<p>Retirement accounts&nbsp;offer&nbsp;tax advantages. In very general terms, you get to&nbsp;avoid paying income tax on interest&nbsp;you earn from a savings account or CD each year. But you may have to pay taxes on those earnings at a later date. Still, keeping your money sheltered from taxes may help you over the long term. Most banks offer IRAs (both&nbsp;Traditional IRAs&nbsp;and&nbsp;Roth IRAs), and they may also provide&nbsp;retirement accounts for small businesses</p>', '10', 'ACC-CAT-1QYDV'),
(4, 'Recurring deposit', '<p><strong>Recurring deposit account or RD account</strong> is opened by those who want to save certain amount of money regularly for a certain period of time and earn a higher interest rate.&nbsp;In RD&nbsp;account a&nbsp;fixed amount is deposited&nbsp;every month for a specified period and the total amount is repaid with interest at the end of the particular fixed period.&nbsp;</p><p>The period of deposit is minimum six months and maximum ten years.&nbsp;The interest rates vary&nbsp;for different plans based on the amount one saves and the period of time and also on banks. No withdrawals are allowed from the RD account. However, the bank may allow to close the account before the maturity period.</p><p>These accounts can be opened in single or joint names. Banks are also providing the Nomination facility to the RD account holders.&nbsp;</p>', '15', 'ACC-CAT-VBQLE'),
(5, 'Fixed Deposit Account', '<p>In <strong>Fixed Deposit Account</strong> (also known as <strong>FD Account</strong>), a particular sum of money is deposited in a bank for specific&nbsp;period of time. It&rsquo;s one time deposit and one time take away (withdraw) account.&nbsp;The money deposited in this account can not be withdrawn before the expiry of period.&nbsp;</p><p>However, in case of need,&nbsp; the depositor can ask for closing the fixed deposit prematurely by paying a penalty. The penalty amount varies with banks.</p><p>A high interest rate is paid on fixed deposits. The rate of interest paid for fixed deposit vary according to amount, period and also from bank to bank.</p>', '40', 'ACC-CAT-A86GO'),
(7, 'Current account', '<p><strong>Current account</strong> is mainly for business persons, firms, companies, public enterprises etc and are never used for the purpose of investment or savings.These deposits are the most liquid deposits and there are no limits for number of transactions or the amount of transactions in a day. While, there is no interest paid on amount held in the account, banks charges certain &nbsp;service charges, on such accounts. The current accounts do not have any fixed maturity as these are on continuous basis accounts.</p>', '20', 'ACC-CAT-4O8QW');

-- --------------------------------------------------------

--
-- Table structure for table `ib_admin`
--

CREATE TABLE `ib_admin` (
  `admin_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_admin`
--

INSERT INTO `ib_admin` (`admin_id`, `name`, `email`, `number`, `password`, `profile_pic`) VALUES
(2, 'System Administrator', 'admin@mail.com', 'iBank-ADM-0516', 'fe703d258c7ef5f50b71e06565a65aa07194907f', 'admin-icn.png');

-- --------------------------------------------------------

--
-- Table structure for table `ib_bankaccounts`
--

CREATE TABLE `ib_bankaccounts` (
  `account_id` int(20) NOT NULL,
  `acc_name` varchar(200) NOT NULL,
  `account_number` varchar(200) NOT NULL,
  `acc_type` varchar(200) NOT NULL,
  `acc_rates` varchar(200) NOT NULL,
  `acc_status` varchar(200) NOT NULL,
  `acc_amount` varchar(200) NOT NULL,
  `client_id` varchar(200) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_national_id` varchar(200) NOT NULL,
  `client_phone` varchar(200) NOT NULL,
  `client_number` varchar(200) NOT NULL,
  `client_email` varchar(200) NOT NULL,
  `client_adr` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_bankaccounts`
--

INSERT INTO `ib_bankaccounts` (`account_id`, `acc_name`, `account_number`, `acc_type`, `acc_rates`, `acc_status`, `acc_amount`, `client_id`, `client_name`, `client_national_id`, `client_phone`, `client_number`, `client_email`, `client_adr`, `created_at`) VALUES
(13, 'Christine Moore', '421873905', 'Current account ', '20', 'Active', '0', '4', 'Christine Moore', '478545445812', '7785452210', 'iBank-CLIENT-9501', 'christine@mail.com', '445 Bleck Street', '2022-08-30 17:45:18.749496'),
(14, 'Harry M Den', '357146928', 'Savings ', '20', 'Active', '0', '5', 'Harry Den', '100014001000', '7412560000', 'iBank-CLIENT-7014', 'harryden@mail.com', '114 Allace Avenue', '2023-01-10 15:45:16.753509'),
(15, 'Amanda Stiefel', '287359614', 'Savings ', '20', 'Active', '0', '8', 'Amanda Stiefel', '478000001', '7850000014', 'iBank-CLIENT-0423', 'amanda@mail.com', '92 Maple Street', '2023-02-16 16:14:54.629958'),
(16, 'Johnnie Reyes', '705239816', ' Retirement ', '10', 'Active', '0', '6', 'Johnnie J. Reyes', '147455554', '7412545454', 'iBank-CLIENT-1698', 'reyes@mail.com', '23 Hinkle Deegan Lake Road', '2023-02-16 16:19:11.806028'),
(17, 'Liam M. Moore', '719360482', 'Savings ', '20', 'Active', '0', '9', 'Liam Moore', '170014695', '7014569696', 'iBank-CLIENT-4716', 'liamoore@mail.com', '46 Timberbrook Lane', '2023-02-16 16:28:37.437656'),
(18, 'Johnny M. Doen', '724310586', 'Fixed Deposit Account ', '40', 'Active', '0', '3', 'John Doe', '36756481', '9897890089', 'iBank-CLIENT-8127', 'johndoe@gmail.com', '127007 Localhost', '2023-02-16 16:40:15.645285'),
(19, 'Tariro Mavezera', '694302751', 'Savings ', '20', 'Active', '0', '12', 'Tariro', '7299yebs782', '0784884211', 'iBank-CLIENT-3714', 'madlamini94@gmail.com', 'avysasy Mutare', '2024-05-26 18:01:57.543976'),
(20, 'Bright Cheteni', '613804529', 'Savings ', '20', 'Active', '0', '11', 'Lovest', '27 001306 S 15', '0787679375', 'iBank-CLIENT-6139', 'brightcheteni@gmail.com', '14532 Selborne Park4', '2024-05-26 18:03:07.449145'),
(21, 'Bright Cheteni', '059187426', 'Select Any iBank Account types', '', 'Active', '0', '11', 'Lovest', '27 001306 S 15', '0787679375', 'iBank-CLIENT-6139', 'brightcheteni@gmail.com', '14532 Selborne Park4', '2024-05-26 18:06:04.887812');

-- --------------------------------------------------------

--
-- Table structure for table `ib_clients`
--

CREATE TABLE `ib_clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `national_id` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `client_number` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_clients`
--

INSERT INTO `ib_clients` (`client_id`, `name`, `national_id`, `phone`, `address`, `email`, `password`, `profile_pic`, `client_number`) VALUES
(3, 'John Doe', '36756481', '9897890089', '127007 Localhost', 'johndoe@gmail.com', 'a69681bcf334ae130217fea4505fd3c994f5683f', '', 'iBank-CLIENT-8127'),
(4, 'Christine Moore', '478545445812', '7785452210', '445 Bleck Street', 'christine@mail.com', '55c3b5386c486feb662a0785f340938f518d547f', 'defaultimg.jpg', 'iBank-CLIENT-9501'),
(5, 'Harry Den', '100014001000', '7412560000', '114 Allace Avenue', 'harryden@mail.com', '55c3b5386c486feb662a0785f340938f518d547f', '', 'iBank-CLIENT-7014'),
(6, 'Johnnie J. Reyes', '147455554', '7412545454', '23 Hinkle Deegan Lake Road', 'reyes@mail.com', '55c3b5386c486feb662a0785f340938f518d547f', 'user-profile-min.png', 'iBank-CLIENT-1698'),
(8, 'Amanda Stiefel', '478000001', '7850000014', '92 Maple Street', 'amanda@mail.com', '55c3b5386c486feb662a0785f340938f518d547f', 'user-profile-min.png', 'iBank-CLIENT-0423'),
(9, 'Liam Moore', '170014695', '7014569696', '46 Timberbrook Lane', 'liamoore@mail.com', '55c3b5386c486feb662a0785f340938f518d547f', 'user-profile-min.png', 'iBank-CLIENT-4716'),
(10, 'Bright', '27 001306 S 12', '0787679376', '14532 Selborne Park', 'brightcheteni1@gmail.com', 'fe703d258c7ef5f50b71e06565a65aa07194907f', '', 'iBank-CLIENT-8394'),
(11, 'Lovest', '27 001306 S 15', '0787679375', '14532 Selborne Park4', 'brightcheteni@gmail.com', '5f7abce07bc5e1f32426a00dc7272b88bb92b39b', '', 'iBank-CLIENT-6139'),
(12, 'Tariro', '7299yebs782', '0784884211', 'avysasy Mutare', 'madlamini94@gmail.com', 'e691620e2a67018b37b1a5af68f567b5cd167bfa', '', 'iBank-CLIENT-3714');

-- --------------------------------------------------------

--
-- Table structure for table `ib_notifications`
--

CREATE TABLE `ib_notifications` (
  `notification_id` int(20) NOT NULL,
  `notification_details` text NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_notifications`
--

INSERT INTO `ib_notifications` (`notification_id`, `notification_details`, `created_at`) VALUES
(20, 'Amanda Stiefel Has Deposited $ 2658 To Bank Account 287359614', '2023-02-16 16:17:22.592127'),
(21, 'Liam Moore Has Deposited $ 5650 To Bank Account 719360482', '2023-02-16 16:29:14.930350'),
(22, 'Liam Moore Has Withdrawn $ 777 From Bank Account 719360482', '2023-02-16 16:29:38.233567'),
(23, 'Liam Moore Has Transfered $ 1256 From Bank Account 719360482 To Bank Account 287359614', '2023-02-16 16:30:15.575946'),
(24, 'John Doe Has Deposited $ 8550 To Bank Account 724310586', '2023-02-16 16:40:49.513943'),
(25, 'Liam Moore Has Deposited $ 600 To Bank Account 719360482', '2023-02-16 16:40:57.385035'),
(26, 'Liam Moore Has Withdrawn $ 120 From Bank Account 719360482', '2023-02-16 16:41:14.885825'),
(27, 'John Doe Has Transfered $ 100 From Bank Account 724310586 To Bank Account 719360482', '2023-02-16 16:41:38.821974'),
(28, 'Harry Den Has Deposited $ 6800 To Bank Account 357146928', '2023-02-16 16:44:09.250277'),
(29, 'Tariro Has Deposited $ 10500 To Bank Account 694302751', '2024-05-26 18:03:50.042667'),
(30, 'Tariro Has Transfered $ 7000 From Bank Account 694302751 To Bank Account 613804529', '2024-05-26 18:07:28.774114');

-- --------------------------------------------------------

--
-- Table structure for table `ib_staff`
--

CREATE TABLE `ib_staff` (
  `staff_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `staff_number` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `sex` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_staff`
--

INSERT INTO `ib_staff` (`staff_id`, `name`, `staff_number`, `phone`, `email`, `password`, `sex`, `profile_pic`) VALUES
(3, 'Staff ', 'iBank-STAFF-6785', '0704975742', 'staff@mail.com', '7f06c04d59bd83605193621e8d0d693bd30cdc9e', 'Male', 'user-profile-min.png');

-- --------------------------------------------------------

--
-- Table structure for table `ib_systemsettings`
--

CREATE TABLE `ib_systemsettings` (
  `id` int(20) NOT NULL,
  `sys_name` longtext NOT NULL,
  `sys_tagline` longtext NOT NULL,
  `sys_logo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_systemsettings`
--

INSERT INTO `ib_systemsettings` (`id`, `sys_name`, `sys_tagline`, `sys_logo`) VALUES
(1, 'NMB Banking', 'Financial success at every service we offer, exceptionally!', 'ibankinglg.png');

-- --------------------------------------------------------

--
-- Table structure for table `ib_transactions`
--

CREATE TABLE `ib_transactions` (
  `tr_id` int(20) NOT NULL,
  `tr_code` varchar(200) NOT NULL,
  `account_id` varchar(200) NOT NULL,
  `acc_name` varchar(200) NOT NULL,
  `account_number` varchar(200) NOT NULL,
  `acc_type` varchar(200) NOT NULL,
  `acc_amount` varchar(200) NOT NULL,
  `tr_type` varchar(200) NOT NULL,
  `tr_status` varchar(200) NOT NULL,
  `client_id` varchar(200) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_national_id` varchar(200) NOT NULL,
  `transaction_amt` varchar(200) NOT NULL,
  `client_phone` varchar(200) NOT NULL,
  `receiving_acc_no` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `receiving_acc_name` varchar(200) NOT NULL,
  `receiving_acc_holder` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_transactions`
--

INSERT INTO `ib_transactions` (`tr_id`, `tr_code`, `account_id`, `acc_name`, `account_number`, `acc_type`, `acc_amount`, `tr_type`, `tr_status`, `client_id`, `client_name`, `client_national_id`, `transaction_amt`, `client_phone`, `receiving_acc_no`, `created_at`, `receiving_acc_name`, `receiving_acc_holder`) VALUES
(38, '2XsYuvHwMmlEfiTRgD97', '13', 'Christine Moore', '421873905', 'Current account ', '', 'Deposit', 'Success ', '4', 'Christine Moore', '478545445812', '2350', '7785452210', '', '2022-08-30 17:45:33.972970', '', ''),
(39, 'Q6zFbdlINi3Reyu8UPMD', '13', 'Christine Moore', '421873905', 'Current account ', '', 'Deposit', 'Success ', '4', 'Christine Moore', '478545445812', '660', '7785452210', '', '2022-08-30 17:46:45.034964', '', ''),
(40, 'pl1QXD8CgeKon6TRf3Fk', '13', 'Christine Moore', '421873905', 'Current account ', '', 'Withdrawal', 'Success ', '4', 'Christine Moore', '478545445812', '200', '7785452210', '', '2022-08-30 17:46:59.566360', '', ''),
(41, 'RGl1EohqrgS3K4MUAHaf', '14', 'Harry M Den', '357146928', 'Savings ', '', 'Deposit', 'Success ', '5', 'Harry Den', '100014001000', '2660', '7412560000', '', '2023-01-10 15:47:21.233304', '', ''),
(42, 'FfYSvxkq7T1iHs06p2Qa', '13', 'Christine Moore', '421873905', 'Current account ', '', 'Transfer', 'Success ', '4', 'Christine Moore', '478545445812', '665', '7785452210', '357146928', '2023-02-15 16:49:45.731760', 'Harry M Den', 'Harry Den'),
(43, 'wXOyVgizubsp6UnTNfL4', '15', 'Amanda Stiefel', '287359614', 'Savings ', '', 'Deposit', 'Success ', '8', 'Amanda Stiefel', '478000001', '2658', '7850000014', '', '2023-02-16 16:17:22.506549', '', ''),
(44, '1S6wRtU3zP0igpCYyTGF', '17', 'Liam M. Moore', '719360482', 'Savings ', '', 'Deposit', 'Success ', '9', 'Liam Moore', '170014695', '5650', '7014569696', '', '2023-02-16 16:29:14.851707', '', ''),
(45, 'GCNrZ7n3oJyM62SzpKWs', '17', 'Liam M. Moore', '719360482', 'Savings ', '', 'Withdrawal', 'Success ', '9', 'Liam Moore', '170014695', '777', '7014569696', '', '2023-02-16 16:29:38.175952', '', ''),
(46, 'J7cWlTO4hPofHFaAIvx1', '17', 'Liam M. Moore', '719360482', 'Savings ', '', 'Transfer', 'Success ', '9', 'Liam Moore', '170014695', '1256', '7014569696', '287359614', '2023-02-16 16:30:15.509360', 'Amanda Stiefel', 'Amanda Stiefel'),
(47, 'm2OlYZgkQwTPp5VHS9WN', '18', 'Johnny M. Doen', '724310586', 'Fixed Deposit Account ', '', 'Deposit', 'Success ', '3', 'John Doe', '36756481', '8550', '9897890089', '', '2023-02-16 16:40:49.466257', '', ''),
(48, 'P5urU12mcnOBbG0NMVHX', '17', 'Liam M. Moore', '719360482', 'Savings ', '', 'Deposit', 'Success ', '9', 'Liam Moore', '170014695', '600', '7014569696', '', '2023-02-16 16:40:57.306089', '', ''),
(49, 'kQBMaoO42sAeqZtS9lFz', '17', 'Liam M. Moore', '719360482', 'Savings ', '', 'Withdrawal', 'Success ', '9', 'Liam Moore', '170014695', '120', '7014569696', '', '2023-02-16 16:41:14.817821', '', ''),
(50, '9jQsTd0YV6tfqCZzckGW', '18', 'Johnny M. Doen', '724310586', 'Fixed Deposit Account ', '', 'Transfer', 'Success ', '3', 'John Doe', '36756481', '100', '9897890089', '719360482', '2023-02-16 16:41:38.758246', 'Liam M. Moore', 'Liam Moore'),
(51, 'FMyw7YGtnpQPaZXTuWmR', '14', 'Harry M Den', '357146928', 'Savings ', '', 'Deposit', 'Success ', '5', 'Harry Den', '100014001000', '6800', '7412560000', '', '2023-02-16 16:44:09.179146', '', ''),
(52, 'dsoJ9MQpakUj1bLDI6Nt', '19', 'Tariro Mavezera', '694302751', 'Savings ', '', 'Deposit', 'Success ', '12', 'Tariro', '7299yebs782', '10500', '0784884211', '', '2024-05-26 18:03:49.837184', '', ''),
(53, 'alOg9NKnV4MLxiY5Rrpy', '19', 'Tariro Mavezera', '694302751', 'Savings ', '', 'Transfer', 'Success ', '12', 'Tariro', '7299yebs782', '7000', '0784884211', '613804529', '2024-05-26 18:07:17.658991', 'Bright Cheteni', 'Bright Cheteni');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ib_acc_types`
--
ALTER TABLE `ib_acc_types`
  ADD PRIMARY KEY (`acctype_id`);

--
-- Indexes for table `ib_admin`
--
ALTER TABLE `ib_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `ib_clients`
--
ALTER TABLE `ib_clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `ib_notifications`
--
ALTER TABLE `ib_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `ib_staff`
--
ALTER TABLE `ib_staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `ib_systemsettings`
--
ALTER TABLE `ib_systemsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  ADD PRIMARY KEY (`tr_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ib_acc_types`
--
ALTER TABLE `ib_acc_types`
  MODIFY `acctype_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ib_admin`
--
ALTER TABLE `ib_admin`
  MODIFY `admin_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  MODIFY `account_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `ib_clients`
--
ALTER TABLE `ib_clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ib_notifications`
--
ALTER TABLE `ib_notifications`
  MODIFY `notification_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `ib_staff`
--
ALTER TABLE `ib_staff`
  MODIFY `staff_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ib_systemsettings`
--
ALTER TABLE `ib_systemsettings`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  MODIFY `tr_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
