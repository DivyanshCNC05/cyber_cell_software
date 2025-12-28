-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 27, 2025 at 11:12 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u868614356_cybercell_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bagli_cyber`
--

CREATE TABLE `bagli_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_note_press_cyber`
--

CREATE TABLE `bank_note_press_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barotha_cyber`
--

CREATE TABLE `barotha_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bhaurasa_cyber`
--

CREATE TABLE `bhaurasa_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_bagli`
--

CREATE TABLE `ceir_bagli` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_bank_note_press`
--

CREATE TABLE `ceir_bank_note_press` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_barotha`
--

CREATE TABLE `ceir_barotha` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_bhaurasa`
--

CREATE TABLE `ceir_bhaurasa` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_civil_line`
--

CREATE TABLE `ceir_civil_line` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_harangaon`
--

CREATE TABLE `ceir_harangaon` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_hatpiplya`
--

CREATE TABLE `ceir_hatpiplya` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_industrial_area`
--

CREATE TABLE `ceir_industrial_area` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_kamlapur`
--

CREATE TABLE `ceir_kamlapur` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_kannod`
--

CREATE TABLE `ceir_kannod` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_kantaphod`
--

CREATE TABLE `ceir_kantaphod` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_khategaon`
--

CREATE TABLE `ceir_khategaon` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_kotwali`
--

CREATE TABLE `ceir_kotwali` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_nahar_darwaja`
--

CREATE TABLE `ceir_nahar_darwaja` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_nemawar`
--

CREATE TABLE `ceir_nemawar` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_pipalrawan`
--

CREATE TABLE `ceir_pipalrawan` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_satwas`
--

CREATE TABLE `ceir_satwas` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_sonkatch`
--

CREATE TABLE `ceir_sonkatch` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_tonkkhurd`
--

CREATE TABLE `ceir_tonkkhurd` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_udai_nagar`
--

CREATE TABLE `ceir_udai_nagar` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceir_vijayganj_mandi`
--

CREATE TABLE `ceir_vijayganj_mandi` (
  `ceir_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_complaint` date NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `imei` varchar(15) NOT NULL,
  `lost_found` enum('LOST','FOUND') NOT NULL,
  `block_unblock` enum('BLOCK','UNBLOCK') NOT NULL,
  `pdf_attach` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `civil_line_cyber`
--

CREATE TABLE `civil_line_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `harangaon_cyber`
--

CREATE TABLE `harangaon_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hatpiplya_cyber`
--

CREATE TABLE `hatpiplya_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industrial_area_cyber`
--

CREATE TABLE `industrial_area_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kamlapur_cyber`
--

CREATE TABLE `kamlapur_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kannod_cyber`
--

CREATE TABLE `kannod_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kantaphod_cyber`
--

CREATE TABLE `kantaphod_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khategaon_cyber`
--

CREATE TABLE `khategaon_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kotwali_cyber`
--

CREATE TABLE `kotwali_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_credentials`
--

CREATE TABLE `login_credentials` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('ADMIN','CYBER_USER','CEIR_USER') NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `user_number` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_credentials`
--

INSERT INTO `login_credentials` (`user_id`, `email`, `password_hash`, `role`, `full_name`, `is_active`, `created_at`, `user_number`) VALUES
(4, 'user1@cyber.in', 'user1@1234', 'CYBER_USER', 'User1 Officer', 1, '2025-12-27 09:25:40', 1),
(5, 'user2@cyber.in', 'user2@1234', 'CYBER_USER', 'User2 Officer', 1, '2025-12-27 09:25:40', 2),
(6, 'user3@cyber.in', 'user3@1234', 'CYBER_USER', 'User3 Officer', 1, '2025-12-27 09:25:40', 3),
(7, 'ceir@cyber.in', 'ceir@1234', 'CEIR_USER', 'CEIR Officer', 1, '2025-12-27 09:25:40', NULL),
(8, 'admin@cyber.in', 'admin@1234', 'ADMIN', 'Admin', 1, '2025-12-27 09:25:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nahar_darwaja_cyber`
--

CREATE TABLE `nahar_darwaja_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nemawar_cyber`
--

CREATE TABLE `nemawar_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pipalrawan_cyber`
--

CREATE TABLE `pipalrawan_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `satwas_cyber`
--

CREATE TABLE `satwas_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sonkatch_cyber`
--

CREATE TABLE `sonkatch_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonkkhurd_cyber`
--

CREATE TABLE `tonkkhurd_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `udai_nagar_cyber`
--

CREATE TABLE `udai_nagar_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vijayganj_mandi_cyber`
--

CREATE TABLE `vijayganj_mandi_cyber` (
  `sno` int(11) NOT NULL,
  `complaint_number` varchar(100) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `acknowledgement_number` varchar(100) DEFAULT NULL,
  `nature_of_fraud` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_date` date NOT NULL,
  `total_fraud` decimal(15,2) DEFAULT 0.00,
  `hold_date` date DEFAULT NULL,
  `hold_amount` decimal(15,2) DEFAULT 0.00,
  `refund_amount` decimal(15,2) DEFAULT 0.00,
  `fraud_mobile_number` varchar(15) DEFAULT NULL,
  `fraud_imei_number` varchar(15) DEFAULT NULL,
  `block_or_unblock` enum('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
  `digital_arrest` int(11) DEFAULT 0,
  `digital_amount` decimal(15,2) DEFAULT 0.00,
  `mobile_number` varchar(15) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bagli_cyber`
--
ALTER TABLE `bagli_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `bank_note_press_cyber`
--
ALTER TABLE `bank_note_press_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `barotha_cyber`
--
ALTER TABLE `barotha_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `bhaurasa_cyber`
--
ALTER TABLE `bhaurasa_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_bagli`
--
ALTER TABLE `ceir_bagli`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_bank_note_press`
--
ALTER TABLE `ceir_bank_note_press`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_barotha`
--
ALTER TABLE `ceir_barotha`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_bhaurasa`
--
ALTER TABLE `ceir_bhaurasa`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_civil_line`
--
ALTER TABLE `ceir_civil_line`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_harangaon`
--
ALTER TABLE `ceir_harangaon`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_hatpiplya`
--
ALTER TABLE `ceir_hatpiplya`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_industrial_area`
--
ALTER TABLE `ceir_industrial_area`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_kamlapur`
--
ALTER TABLE `ceir_kamlapur`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_kannod`
--
ALTER TABLE `ceir_kannod`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_kantaphod`
--
ALTER TABLE `ceir_kantaphod`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_khategaon`
--
ALTER TABLE `ceir_khategaon`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_kotwali`
--
ALTER TABLE `ceir_kotwali`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_nahar_darwaja`
--
ALTER TABLE `ceir_nahar_darwaja`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_nemawar`
--
ALTER TABLE `ceir_nemawar`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_pipalrawan`
--
ALTER TABLE `ceir_pipalrawan`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_satwas`
--
ALTER TABLE `ceir_satwas`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_sonkatch`
--
ALTER TABLE `ceir_sonkatch`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_tonkkhurd`
--
ALTER TABLE `ceir_tonkkhurd`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_udai_nagar`
--
ALTER TABLE `ceir_udai_nagar`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ceir_vijayganj_mandi`
--
ALTER TABLE `ceir_vijayganj_mandi`
  ADD PRIMARY KEY (`ceir_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `civil_line_cyber`
--
ALTER TABLE `civil_line_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `harangaon_cyber`
--
ALTER TABLE `harangaon_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `hatpiplya_cyber`
--
ALTER TABLE `hatpiplya_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `industrial_area_cyber`
--
ALTER TABLE `industrial_area_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `kamlapur_cyber`
--
ALTER TABLE `kamlapur_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `kannod_cyber`
--
ALTER TABLE `kannod_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `kantaphod_cyber`
--
ALTER TABLE `kantaphod_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `khategaon_cyber`
--
ALTER TABLE `khategaon_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `kotwali_cyber`
--
ALTER TABLE `kotwali_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `login_credentials`
--
ALTER TABLE `login_credentials`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `nahar_darwaja_cyber`
--
ALTER TABLE `nahar_darwaja_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `nemawar_cyber`
--
ALTER TABLE `nemawar_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `pipalrawan_cyber`
--
ALTER TABLE `pipalrawan_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `satwas_cyber`
--
ALTER TABLE `satwas_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `sonkatch_cyber`
--
ALTER TABLE `sonkatch_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `tonkkhurd_cyber`
--
ALTER TABLE `tonkkhurd_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `udai_nagar_cyber`
--
ALTER TABLE `udai_nagar_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `vijayganj_mandi_cyber`
--
ALTER TABLE `vijayganj_mandi_cyber`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bagli_cyber`
--
ALTER TABLE `bagli_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_note_press_cyber`
--
ALTER TABLE `bank_note_press_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barotha_cyber`
--
ALTER TABLE `barotha_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bhaurasa_cyber`
--
ALTER TABLE `bhaurasa_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_bagli`
--
ALTER TABLE `ceir_bagli`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_bank_note_press`
--
ALTER TABLE `ceir_bank_note_press`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_barotha`
--
ALTER TABLE `ceir_barotha`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_bhaurasa`
--
ALTER TABLE `ceir_bhaurasa`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_civil_line`
--
ALTER TABLE `ceir_civil_line`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_harangaon`
--
ALTER TABLE `ceir_harangaon`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_hatpiplya`
--
ALTER TABLE `ceir_hatpiplya`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_industrial_area`
--
ALTER TABLE `ceir_industrial_area`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_kamlapur`
--
ALTER TABLE `ceir_kamlapur`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_kannod`
--
ALTER TABLE `ceir_kannod`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_kantaphod`
--
ALTER TABLE `ceir_kantaphod`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_khategaon`
--
ALTER TABLE `ceir_khategaon`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_kotwali`
--
ALTER TABLE `ceir_kotwali`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_nahar_darwaja`
--
ALTER TABLE `ceir_nahar_darwaja`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_nemawar`
--
ALTER TABLE `ceir_nemawar`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_pipalrawan`
--
ALTER TABLE `ceir_pipalrawan`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_satwas`
--
ALTER TABLE `ceir_satwas`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_sonkatch`
--
ALTER TABLE `ceir_sonkatch`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_tonkkhurd`
--
ALTER TABLE `ceir_tonkkhurd`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_udai_nagar`
--
ALTER TABLE `ceir_udai_nagar`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceir_vijayganj_mandi`
--
ALTER TABLE `ceir_vijayganj_mandi`
  MODIFY `ceir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `civil_line_cyber`
--
ALTER TABLE `civil_line_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `harangaon_cyber`
--
ALTER TABLE `harangaon_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hatpiplya_cyber`
--
ALTER TABLE `hatpiplya_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `industrial_area_cyber`
--
ALTER TABLE `industrial_area_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kamlapur_cyber`
--
ALTER TABLE `kamlapur_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kannod_cyber`
--
ALTER TABLE `kannod_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kantaphod_cyber`
--
ALTER TABLE `kantaphod_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `khategaon_cyber`
--
ALTER TABLE `khategaon_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kotwali_cyber`
--
ALTER TABLE `kotwali_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_credentials`
--
ALTER TABLE `login_credentials`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nahar_darwaja_cyber`
--
ALTER TABLE `nahar_darwaja_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nemawar_cyber`
--
ALTER TABLE `nemawar_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pipalrawan_cyber`
--
ALTER TABLE `pipalrawan_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `satwas_cyber`
--
ALTER TABLE `satwas_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sonkatch_cyber`
--
ALTER TABLE `sonkatch_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonkkhurd_cyber`
--
ALTER TABLE `tonkkhurd_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `udai_nagar_cyber`
--
ALTER TABLE `udai_nagar_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vijayganj_mandi_cyber`
--
ALTER TABLE `vijayganj_mandi_cyber`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ceir_kotwali`
--
ALTER TABLE `ceir_kotwali`
  ADD CONSTRAINT `ceir_kotwali_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `login_credentials` (`user_id`);

--
-- Constraints for table `kotwali_cyber`
--
ALTER TABLE `kotwali_cyber`
  ADD CONSTRAINT `kotwali_cyber_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `login_credentials` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
