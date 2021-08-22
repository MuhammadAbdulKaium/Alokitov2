-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 24, 2017 at 05:37 PM
-- Server version: 5.7.19-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `venus_ems_version_one`
--
CREATE DATABASE IF NOT EXISTS `venus_ems_version_one` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `venus_ems_version_one`;

-- --------------------------------------------------------

--
-- Table structure for table `academices_student_attendances`
--

CREATE TABLE `academices_student_attendances` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(11) NOT NULL DEFAULT '0',
  `attendacnce_type` int(11) NOT NULL DEFAULT '0',
  `teacher_id` int(11) NOT NULL DEFAULT '0',
  `attendance_date` date NOT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academices_student_attendances_details`
--

CREATE TABLE `academices_student_attendances_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_attendace_id` int(11) NOT NULL DEFAULT '0',
  `class_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `section_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `subject_id` int(10) UNSIGNED DEFAULT '0',
  `session_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_admissionyear`
--

CREATE TABLE `academics_admissionyear` (
  `id` int(10) UNSIGNED NOT NULL,
  `year_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academics_admissionyear`
--

INSERT INTO `academics_admissionyear` (`id`, `year_name`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2017', 1, NULL, '2017-09-24 03:45:19', '2017-09-24 03:45:19');

-- --------------------------------------------------------

--
-- Table structure for table `academics_assessments`
--

CREATE TABLE `academics_assessments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `grading_category_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `grade_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `points` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `passing_points` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `counts_overall_score` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `applied_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_attendance_sessions`
--

CREATE TABLE `academics_attendance_sessions` (
  `id` int(10) UNSIGNED NOT NULL,
  `session_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `institution_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `campus_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_attendance_settings`
--

CREATE TABLE `academics_attendance_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `institution_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `campus_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `multiple_sessions` tinyint(1) NOT NULL DEFAULT '0',
  `subject_wise` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_attendance_types`
--

CREATE TABLE `academics_attendance_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `short_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_class_grade_scales`
--

CREATE TABLE `academics_class_grade_scales` (
  `id` int(10) UNSIGNED NOT NULL,
  `scale_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `section_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `batch_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `academic_year_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_division`
--

CREATE TABLE `academics_division` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academics_division`
--

INSERT INTO `academics_division` (`id`, `name`, `short_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Science', 'science', NULL, NULL, NULL),
(2, 'Arts', 'arts', NULL, NULL, NULL),
(3, 'Commerce', 'commerce', NULL, NULL, NULL),
(4, 'Junior', 'junior', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `academics_grades`
--

CREATE TABLE `academics_grades` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `grade_scale_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_grade_categories`
--

CREATE TABLE `academics_grade_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_grade_details`
--

CREATE TABLE `academics_grade_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `grade_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `min_per` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `max_per` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `points` float DEFAULT '0',
  `sorting_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_grade_scales`
--

CREATE TABLE `academics_grade_scales` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no set',
  `loader_route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no set',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_level`
--

CREATE TABLE `academics_level` (
  `id` int(10) UNSIGNED NOT NULL,
  `level_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `academics_year_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academics_level`
--

INSERT INTO `academics_level` (`id`, `level_name`, `level_code`, `is_active`, `academics_year_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Primary', 'PR', 1, 1, '2017-09-24 03:45:55', '2017-09-24 03:45:55', NULL),
(2, 'Secondary', 'SR', 1, 1, '2017-09-24 03:46:06', '2017-09-24 03:46:06', NULL),
(3, 'Higher Secondary', 'HR', 1, 1, '2017-09-24 03:46:18', '2017-09-24 03:46:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `academics_syllabus`
--

CREATE TABLE `academics_syllabus` (
  `id` int(10) UNSIGNED NOT NULL,
  `syllabus_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `academic_year` int(10) UNSIGNED DEFAULT NULL,
  `academic_level` int(10) UNSIGNED DEFAULT NULL,
  `batch` int(10) UNSIGNED DEFAULT NULL,
  `section` int(10) UNSIGNED DEFAULT NULL,
  `institute_id` int(10) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academics_year`
--

CREATE TABLE `academics_year` (
  `id` int(10) UNSIGNED NOT NULL,
  `year_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `institute_id` int(10) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academics_year`
--

INSERT INTO `academics_year` (`id`, `year_name`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `deleted_at`, `institute_id`, `campus_id`) VALUES
(1, '2017-18', '2017-01-01', '2017-12-31', 1, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `academics_year_semesters`
--

CREATE TABLE `academics_year_semesters` (
  `id` int(10) UNSIGNED NOT NULL,
  `academic_year_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academics_year_semesters`
--

INSERT INTO `academics_year_semesters` (`id`, `academic_year_id`, `name`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '1st Semester', '2017-01-01', '2017-04-30', 1, '2017-09-24 03:46:54', '2017-09-24 03:46:54', NULL),
(2, 1, '2nd Semester', '2017-05-01', '2017-08-31', 1, '2017-09-24 03:47:07', '2017-09-24 03:47:07', NULL),
(3, 1, '3rd Semester', '2017-09-01', '2017-12-31', 1, '2017-09-24 03:47:19', '2017-09-24 03:47:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `acc_bank`
--

CREATE TABLE `acc_bank` (
  `id` int(10) UNSIGNED NOT NULL,
  `bank_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_acc_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_acc_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chart_parent` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `notes` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_charts`
--

CREATE TABLE `acc_charts` (
  `id` int(10) UNSIGNED NOT NULL,
  `chart_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chart_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chart_parent` int(11) DEFAULT NULL,
  `chart_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `cash_acc` tinyint(4) DEFAULT NULL,
  `reconciliation` tinyint(4) DEFAULT NULL,
  `notes` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_closing_balance`
--

CREATE TABLE `acc_closing_balance` (
  `id` int(10) UNSIGNED NOT NULL,
  `acc_f_year_id` int(11) NOT NULL,
  `acc_charts_id` int(11) NOT NULL,
  `balance` double(8,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_f_year`
--

CREATE TABLE `acc_f_year` (
  `id` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `acc_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acc_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_subledger`
--

CREATE TABLE `acc_subledger` (
  `id` int(10) UNSIGNED NOT NULL,
  `chart_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chart_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chart_parent` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `notes` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_tran`
--

CREATE TABLE `acc_tran` (
  `id` int(10) UNSIGNED NOT NULL,
  `tran_seq` int(11) NOT NULL,
  `tran_serial` int(11) NOT NULL,
  `acc_voucher_type_id` int(11) NOT NULL,
  `tran_date` date NOT NULL,
  `acc_charts_id` int(11) NOT NULL,
  `tran_amt_dr` double NOT NULL DEFAULT '0',
  `tran_amt_cr` double NOT NULL DEFAULT '0',
  `tran_details` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '2',
  `company_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_voucher_type`
--

CREATE TABLE `acc_voucher_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_type_id` int(11) NOT NULL,
  `acc_charts_id` int(11) DEFAULT NULL,
  `notes` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `house` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `city_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `state_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `country_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `zip` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_address`
--

CREATE TABLE `applicant_address` (
  `id` int(10) UNSIGNED NOT NULL,
  `applicant_id` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('PRESENT','PERMANENT') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not set',
  `house` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not set',
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not set',
  `city_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `state_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `country_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `zip` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_document`
--

CREATE TABLE `applicant_document` (
  `id` int(10) UNSIGNED NOT NULL,
  `applicant_id` int(10) UNSIGNED DEFAULT NULL,
  `doc_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc_mime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc_details` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not set',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_enrollment`
--

CREATE TABLE `applicant_enrollment` (
  `id` int(10) UNSIGNED NOT NULL,
  `applicant_id` int(10) UNSIGNED DEFAULT NULL,
  `academic_year` int(10) UNSIGNED DEFAULT NULL,
  `academic_level` int(10) UNSIGNED DEFAULT NULL,
  `batch` int(10) UNSIGNED DEFAULT NULL,
  `section` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_exam_settings`
--

CREATE TABLE `applicant_exam_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `exam_fees` int(11) NOT NULL,
  `merit_list_std_no` int(11) UNSIGNED DEFAULT NULL,
  `waiting_list_std_no` int(11) UNSIGNED DEFAULT NULL,
  `exam_marks` int(11) UNSIGNED DEFAULT NULL,
  `exam_passing_marks` int(11) UNSIGNED DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `exam_start_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exam_end_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exam_venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exam_taken` tinyint(1) DEFAULT '0',
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `institute_id` int(10) UNSIGNED DEFAULT NULL,
  `academic_year` int(10) UNSIGNED DEFAULT NULL,
  `academic_level` int(10) UNSIGNED DEFAULT NULL,
  `batch` int(10) UNSIGNED DEFAULT NULL,
  `section` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_fees`
--

CREATE TABLE `applicant_fees` (
  `id` int(10) UNSIGNED NOT NULL,
  `applicant_id` int(10) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `institute_id` int(10) UNSIGNED DEFAULT NULL,
  `fees_amount` int(11) DEFAULT NULL,
  `invoice_no` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_follow_ups`
--

CREATE TABLE `applicant_follow_ups` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_grades`
--

CREATE TABLE `applicant_grades` (
  `id` int(10) UNSIGNED NOT NULL,
  `applicant_id` int(10) UNSIGNED DEFAULT NULL,
  `applicant_grade` int(10) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `institute_id` int(10) UNSIGNED DEFAULT NULL,
  `academic_year` int(10) UNSIGNED DEFAULT NULL,
  `academic_level` int(10) UNSIGNED DEFAULT NULL,
  `batch` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_information`
--

CREATE TABLE `applicant_information` (
  `id` int(10) UNSIGNED NOT NULL,
  `applicant_id` int(10) UNSIGNED DEFAULT NULL,
  `title` enum('Mr.','Mrs.','Ms.') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `gender` tinyint(1) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `blood_group` enum('Unknown','A+','A-','B+','B-','AB+','AB-','O+','O-') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `religion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `applicant_manage_view`
--
CREATE TABLE `applicant_manage_view` (
`applicant_id` int(10) unsigned
,`application_no` int(10) unsigned
,`payment_status` tinyint(1)
,`application_status` int(10) unsigned
,`first_name` varchar(255)
,`middle_name` varchar(255)
,`last_name` varchar(255)
,`email` varchar(255)
,`gender` tinyint(1)
,`birth_date` date
,`academic_year` int(10) unsigned
,`academic_level` int(10) unsigned
,`batch` int(10) unsigned
,`section` int(10) unsigned
,`campus_id` int(10) unsigned
,`institute_id` int(10) unsigned
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `applicant_merit_batch`
--

CREATE TABLE `applicant_merit_batch` (
  `id` int(10) UNSIGNED NOT NULL,
  `merit_batch` int(10) UNSIGNED DEFAULT '1',
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `institute_id` int(10) UNSIGNED DEFAULT NULL,
  `academic_year` int(10) UNSIGNED DEFAULT NULL,
  `academic_level` int(10) UNSIGNED DEFAULT NULL,
  `batch` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_results`
--

CREATE TABLE `applicant_results` (
  `id` int(10) UNSIGNED NOT NULL,
  `applicant_id` int(10) UNSIGNED DEFAULT NULL,
  `applicant_exam_result` tinyint(1) DEFAULT NULL,
  `applicant_merit_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `applicant_merit_position` int(10) UNSIGNED DEFAULT NULL,
  `applicant_merit_batch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admission_status` tinyint(1) DEFAULT '0',
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `institute_id` int(10) UNSIGNED DEFAULT NULL,
  `academic_year` int(10) UNSIGNED DEFAULT NULL,
  `academic_level` int(10) UNSIGNED DEFAULT NULL,
  `batch` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_user`
--

CREATE TABLE `applicant_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `application_no` int(10) UNSIGNED DEFAULT NULL,
  `application_status` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `institute_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` int(10) UNSIGNED NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci,
  `new_values` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_sms_module`
--

CREATE TABLE `auto_sms_module` (
  `id` int(10) UNSIGNED NOT NULL,
  `status_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `sms_temp_id` int(11) NOT NULL,
  `ins_id` int(11) NOT NULL,
  `campus_id` int(11) NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auto_sms_module`
--

INSERT INTO `auto_sms_module` (`id`, `status_code`, `status`, `sms_temp_id`, `ins_id`, `campus_id`, `description`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`) VALUES
(1, 'ATTENDANCE', 1, 1, 1, 1, 'Attendance Module', '2017-09-23 00:27:31', '2017-09-23 00:27:31', NULL, NULL, NULL),
(2, 'RESULT', 1, 2, 1, 1, 'Result Moudle', '2017-09-23 00:28:08', '2017-09-23 00:28:08', NULL, NULL, NULL),
(3, 'FEES', 1, 3, 1, 1, 'fees modules', '2017-09-23 00:28:26', '2017-09-23 00:28:26', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auto_sms_settings`
--

CREATE TABLE `auto_sms_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_type` json NOT NULL,
  `auto_sms_module_id` int(11) NOT NULL,
  `ins_id` int(11) NOT NULL,
  `campus_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auto_sms_settings`
--

INSERT INTO `auto_sms_settings` (`id`, `user_type`, `auto_sms_module_id`, `ins_id`, `campus_id`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`) VALUES
(1, '["4", "2"]', 1, 1, 1, '2017-09-23 00:47:05', '2017-09-23 00:47:05', NULL, NULL, NULL),
(2, '["4", "2"]', 2, 1, 1, '2017-09-23 00:47:05', '2017-09-23 00:47:05', NULL, NULL, NULL),
(3, '["4", "2"]', 3, 1, 1, '2017-09-23 00:47:05', '2017-09-23 00:47:05', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `id` int(10) UNSIGNED NOT NULL,
  `academics_year_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `academics_level_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `batch_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batch`
--

INSERT INTO `batch` (`id`, `academics_year_id`, `academics_level_id`, `division_id`, `batch_name`, `batch_alias`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, 1, 'Class 9', 'C_9', '2017-01-01', '2017-12-31', 1, '2017-09-24 03:48:42', '2017-09-24 03:48:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_category_id` int(11) NOT NULL,
  `book_type` int(11) NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_shelf_id` int(11) NOT NULL,
  `cup_board_shelf_id` int(11) NOT NULL,
  `edition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publisher` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_vendor_id` int(11) NOT NULL,
  `book_cost` double NOT NULL,
  `copy` int(11) NOT NULL,
  `remark` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_category`
--

CREATE TABLE `book_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_shelf`
--

CREATE TABLE `book_shelf` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_vendor`
--

CREATE TABLE `book_vendor` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_periods`
--

CREATE TABLE `class_periods` (
  `id` int(10) UNSIGNED NOT NULL,
  `period_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `campus` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `institute` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `academic_year` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `period_shift` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `period_category` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `period_start_hour` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `period_start_min` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `period_start_meridiem` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `period_end_hour` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `period_end_min` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `period_end_meridiem` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `is_break` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_period_categories`
--

CREATE TABLE `class_period_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'not set',
  `institute` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `campus` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `academic_year` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_section_period`
--

CREATE TABLE `class_section_period` (
  `id` int(10) UNSIGNED NOT NULL,
  `cs_shift` int(11) UNSIGNED DEFAULT NULL,
  `cs_period_category` int(10) UNSIGNED DEFAULT NULL,
  `academic_year` int(10) UNSIGNED DEFAULT NULL,
  `academic_level` int(10) UNSIGNED DEFAULT NULL,
  `batch` int(10) UNSIGNED DEFAULT NULL,
  `section` int(10) UNSIGNED DEFAULT NULL,
  `institute_id` int(10) UNSIGNED DEFAULT NULL,
  `campus_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_subjects`
--

CREATE TABLE `class_subjects` (
  `id` int(10) UNSIGNED NOT NULL,
  `class_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `section_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `subject_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `subject_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '(not set)',
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '(not set)',
  `subject_credit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '(not set)',
  `sorting_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_subject_teachers`
--

CREATE TABLE `class_subject_teachers` (
  `id` int(10) UNSIGNED NOT NULL,
  `class_subject_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `employee_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '(not set)',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_subject_teacher_timetables`
--

CREATE TABLE `class_subject_teacher_timetables` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_timetables`
--

CREATE TABLE `class_timetables` (
  `id` int(10) UNSIGNED NOT NULL,
  `day` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `period` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `shift` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `room` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `batch` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `section` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `subject` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `teacher` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `campus` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `institute` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `academic_year` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `mime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cup_board_shelf`
--

CREATE TABLE `cup_board_shelf` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_shelf_id` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `details` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_attachments`
--

CREATE TABLE `employee_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `doc_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `doc_details` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `doc_submited_at` date DEFAULT NULL,
  `doc_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_departments`
--

CREATE TABLE `employee_departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `alias` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_designations`
--

CREATE TABLE `employee_designations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `alias` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_guardians`
--

CREATE TABLE `employee_guardians` (
  `id` int(10) UNSIGNED NOT NULL,
  `emp_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` enum('Mr.','Mrs.','Ms.','Prof.','Dr.') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `relation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `income` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `home_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `office_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `is_emergency` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_informations`
--

CREATE TABLE `employee_informations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` enum('Mr.','Mrs.','Ms.','Prof.','Dr.') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not avaliable',
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not avaliable',
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not avaliable',
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not avaliable',
  `gender` enum('Male','Female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `doj` date DEFAULT NULL,
  `department` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `designation` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `category` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not avaliable',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not avaliable',
  `blood_group` enum('Unknown','A+','A-','B+','B-','AB+','AB-','O+','O-') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unknown',
  `birth_place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not avaliable',
  `religion` int(10) UNSIGNED DEFAULT NULL,
  `marital_status` enum('MARRIED','UNMARRIED','DIVORCED') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` int(10) UNSIGNED DEFAULT NULL,
  `experience_year` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `experience_month` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave_entitlements`
--

CREATE TABLE `employee_leave_entitlements` (
  `id` int(10) UNSIGNED NOT NULL,
  `category` int(11) DEFAULT NULL,
  `employee` int(11) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `structure` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave_structures`
--

CREATE TABLE `employee_leave_structures` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(10) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave_structure_type`
--

CREATE TABLE `employee_leave_structure_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `structure_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `type_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `leave_days` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `parent` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave_types`
--

CREATE TABLE `employee_leave_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `proportinate_on_joined_date` tinyint(1) NOT NULL,
  `carray_forward` tinyint(1) NOT NULL DEFAULT '0',
  `percentage_of_cf` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `max_cf_amount` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `cf_availability_period` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(10) UNSIGNED NOT NULL,
  `fee_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `due_date` date NOT NULL,
  `fee_type` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `fee_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `partial_allowed` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_collection`
--

CREATE TABLE `fees_collection` (
  `id` int(10) UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `fees_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_discount`
--

CREATE TABLE `fees_discount` (
  `id` int(10) UNSIGNED NOT NULL,
  `fees_id` int(11) NOT NULL,
  `discount_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percent` double(15,8) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_invoices`
--

CREATE TABLE `fees_invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `fees_id` int(11) NOT NULL,
  `payer_id` int(11) NOT NULL,
  `payer_type` int(11) DEFAULT NULL,
  `payer_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inform_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_item`
--

CREATE TABLE `fees_item` (
  `id` int(10) UNSIGNED NOT NULL,
  `fees_id` int(11) NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `rate` double(15,8) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_type`
--

CREATE TABLE `fee_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `fee_type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `institute_language`
--

CREATE TABLE `institute_language` (
  `id` int(10) UNSIGNED NOT NULL,
  `institute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payment`
--

CREATE TABLE `invoice_payment` (
  `id` int(10) UNSIGNED NOT NULL,
  `fees_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_payment_amount` double NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issue_book`
--

CREATE TABLE `issue_book` (
  `id` int(10) UNSIGNED NOT NULL,
  `asn_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `holder_type` int(11) NOT NULL,
  `holder_name` int(11) NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(10) UNSIGNED NOT NULL,
  `language_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2013_01_12_084934_create_setting_institute_table', 1),
(2, '2013_01_15_044834_create_setting_country_table', 1),
(3, '2013_01_20_060051_create_setting_state_table', 1),
(4, '2014_01_15_061640_create_setting_city_table', 1),
(5, '2014_10_12_000000_create_users_table', 1),
(6, '2014_10_12_100000_create_password_resets_table', 1),
(7, '2017_01_15_090921_create_academics_year_table', 1),
(8, '2017_01_15_093737_create_academics_level_table', 1),
(9, '2017_01_23_102406_create_academics_division_table', 1),
(10, '2017_02_23_062426_create_contents_table', 1),
(11, '2017_02_23_075055_create_addresses_table', 1),
(13, '2017_02_23_110028_crate_student_attachments_table', 1),
(14, '2017_02_26_084005_create_student_subject', 1),
(15, '2017_02_28_065901_create_academicyear_table', 1),
(16, '2017_03_02_065319_create_batch_table', 1),
(18, '2017_03_07_050132_create_section_table', 1),
(19, '2017_03_09_102239_create_student_guardians_table', 1),
(21, '2017_03_14_061025_create_student_extends_table', 1),
(23, '2017_03_15_090306_create_academics_admissionyear_table', 1),
(24, '2017_03_21_053333_create_extends_table', 2),
(25, '2017_03_27_064645_create_employee_designations_table', 2),
(26, '2017_03_27_064659_create_employee_departments_table', 2),
(27, '2017_03_27_064665_create_employee_informations_table', 2),
(28, '2017_03_28_060015_create_employee_attachments_table', 2),
(29, '2017_03_29_060319_create_employee_guardians_table', 2),
(30, '2014_01_15_061640_create_setting_city_table', 1),
(31, '2017_03_14_064152_create_setting_city_table', 1),
(32, '2013_01_15_061640_create_setting_city_table', 3),
(33, '2017_02_23_105859_crate_student_informations_table', 3),
(37, '2017_04_09_050758_create_academics_class_subjects_table', 4),
(38, '2017_04_09_050924_create_academics_class_subject_teachers_table', 4),
(39, '2017_04_11_064408_create_audits_table', 5),
(44, '2017_04_13_054256_academics_grade_categories_table', 6),
(45, '2017_04_13_054315_academics_grade_scales_table', 6),
(46, '2017_04_13_054333_academics_grades_table', 6),
(47, '2017_04_13_054413_academics_grades_details_table', 7),
(48, '2017_03_20_054155_create_room_category_table', 8),
(50, '2017_04_20_094041_create_room_master_table', 8),
(53, '2017_04_14_050833_create_academics_assessments_table', 9),
(59, '2017_04_18_052841_create_academics_attendance_settings_table', 1),
(60, '2017_04_18_052731_create_academics_attendance_types_table', 1),
(61, '2017_04_18_052911_create_academics_attendance_sessions_table', 1),
(62, '2017_04_19_043914_create_academices_student_attendances_table', 10),
(63, '2017_04_19_043937_create_academices_student_attendances_details_table', 11),
(69, '2017_05_06_102854_create_employee_leave_structures_table', 12),
(70, '2017_05_04_114905_create_employee_leave_types_table', 13),
(71, '2017_05_07_050509_create_employee_leave_structure_type_table', 14),
(74, '2017_05_23_052749_create_student_grades_table', 16),
(76, '2017_05_23_052938_create_student_marks_table', 17),
(78, '2017_06_13_075243_create_academics_class_grade_scales_table', 18),
(95, '2017_03_14_064152_create_setting_campus_table', 26),
(103, '2017_05_16_110528_create_academics_module_views', 29),
(107, '2017_07_12_083537_create_language_table', 31),
(108, '2017_07_13_090145_create_institute_language_table', 31),
(109, '2017_07_20_055706_create_sms_institution_getway_table', 31),
(120, '2017_07_04_073825_create_class_period_categories_table', 33),
(121, '2017_07_04_073826_create_class_periods_table', 33),
(122, '2017_07_04_074117_create_class_timetables_table', 33),
(123, '2017_07_04_074158_create_teacher_timetables_table', 33),
(124, '2017_07_17_070757_create_sms_credit_table', 34),
(125, '2017_07_17_110357_create_sms_log_table', 34),
(126, '2017_07_18_044729_create_sms_message_table', 34),
(127, '2017_05_08_090758_create_employee_leave_entitlements_table', 35),
(129, '2017_06_07_090511_create_fees_table', 36),
(130, '2017_06_07_090825_create_frees_item_table', 36),
(131, '2017_06_15_065648_create_fees_invoices_table', 36),
(132, '2017_06_20_050718_create_payment_method_table', 36),
(133, '2017_06_20_061557_create_invoice_payment_table', 36),
(134, '2017_07_06_052909_create_fees_discount_table', 36),
(138, '2017_03_17_075612_create_student_enrollments_table', 38),
(143, '2017_07_24_070120_create_modules_table', 39),
(144, '2017_07_24_070213_create_menus_table', 39),
(145, '2017_07_26_092441_create_institute_modules_table', 39),
(146, '2017_08_01_060457_create_setting_menu_permission_table', 39),
(147, '2017_07_23_065506_entrust_setup_tables', 40),
(149, '2017_08_07_084233_create_academics_year_semesters_table', 41),
(154, '2017_08_03_060844_create_fee_type_table', 43),
(174, '2017_08_08_105332_create_applicant_user_table', 44),
(175, '2017_08_08_105532_create_applicant_information_table', 44),
(177, '2017_08_08_105710_create_applicant_address_table', 44),
(178, '2017_08_09_052831_create_applicant_document_table', 44),
(180, '2017_08_03_094732_creat_notice_table', 45),
(182, '2017_08_13_054202_create__payment_extra_table', 47),
(183, '2017_08_20_045752_create_user_institution_campus', 48),
(184, '2017_03_06_095251_extend_batch_table', 49),
(185, '2017_08_22_093535_create_student_enrollment_history_table', 50),
(186, '2017_08_12_085114_create_applicant_fees_table', 51),
(188, '2017_08_08_105605_create_applicant_enrollment_table', 52),
(190, '2017_08_20_055531_create_setting_inst_prop', 54),
(191, '2017_08_12_102743_create_applicant_exam_settings_table', 55),
(193, '2017_08_28_065007_create_applicant_grades_table', 56),
(194, '2017_08_29_095642_create_applicant_follow_ups_table', 57),
(195, '2017_09_10_073807_create_applicant_results_table', 57),
(196, '2017_08_29_050044_create_book_category_table', 58),
(197, '2017_08_29_060602_create_book_shelf_table', 58),
(198, '2017_08_29_062543_create_cup_board_shelf_table', 58),
(199, '2017_08_29_065128_create_book_vendor_table', 58),
(200, '2017_08_29_071314_create_book_table', 58),
(201, '2017_09_10_101704_create_issue_book_table', 59),
(203, '2017_09_12_121359_create_applicant_merit_batch_table', 60),
(204, '2017_09_16_081627_create_academics_syllabus_table', 61),
(207, '2017_09_17_053840_create_student_parents_table', 62),
(208, '2017_09_18_073335_create_class_section_period_table', 63),
(209, '2017_08_16_050626_create_sms_batch_table', 64),
(210, '2017_09_13_105030_create_sms_status_table', 64),
(211, '2017_09_14_091307_create_sms_template_table', 64),
(212, '2017_09_17_063829_create_auto_sms_module_table', 65),
(213, '2017_09_17_095158_create_auto_sms_settings_table', 65),
(214, '2017_07_11_094824_create_schedule_log', 66),
(215, '2017_08_16_095321_create_jobs_table', 67),
(216, '2017_03_22_101528_create_acc_charts', 68),
(217, '2017_03_29_071603_create_acc_subledger', 68),
(218, '2017_03_30_060309_create_acc_bank', 68),
(219, '2017_03_30_120415_create_acc_voucher_type', 68),
(220, '2017_04_05_101328_create_acc_tran', 68),
(221, '2017_05_23_064633_create_acc_fyear', 68),
(222, '2017_06_07_061233_create_acc_closing_balance', 68),
(223, '2017_09_17_095724_create_acc_fees_collection', 68);

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_date` date NOT NULL,
  `desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` int(11) NOT NULL,
  `notice_file` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_extra`
--

CREATE TABLE `payment_extra` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(11) NOT NULL,
  `extra_amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(10) UNSIGNED NOT NULL,
  `method_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sub_module_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `module_id`, `sub_module_id`, `display_name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'manage-academics-module', 1, 0, 'Manage Academics (Module)', 'can manage academics module', 1, '2017-08-05 03:31:35', '2017-08-05 23:18:27'),
(2, 'manage-timetable-management', 1, 0, 'Manage Timetable  Management', 'can manage timetable management', 1, '2017-08-05 03:32:21', '2017-08-06 01:34:04'),
(3, 'manage-std-attendance-management', 1, 0, 'Student Attendance Management', 'can manage student attendance manangement', 1, '2017-08-05 03:33:12', '2017-08-06 01:31:00'),
(4, 'manage-course-management', 1, 0, 'Manage Course Management', 'can manage course management', 1, '2017-08-05 03:34:30', '2017-08-06 01:32:35'),
(5, 'manage-assessment-management', 1, 0, 'Manage Assessment Management', 'can manage assessment management', 1, '2017-08-05 03:35:08', '2017-08-06 01:33:10'),
(6, 'manage-academic-year', 1, 12, 'Manage Academic Year', 'can manage academic year', 1, '2017-08-05 03:44:18', '2017-08-05 10:14:53'),
(7, 'add-academic-year', 1, 12, 'Add Academic Year', 'can add academic year', 1, '2017-08-05 03:45:45', '2017-08-05 03:45:45'),
(8, 'edit-academic-year', 1, 12, 'Edit Academic Year', 'can edit academic year', 1, '2017-08-05 03:46:42', '2017-08-05 03:46:42'),
(9, 'delete-academic-year', 1, 12, 'Delete Academic Year', 'can delete academic year', 1, '2017-08-05 03:47:31', '2017-08-05 03:47:31'),
(10, 'manage-academic-level', 1, 12, 'Manage Academic Level', 'can manage academic level', 1, '2017-08-05 03:57:45', '2017-08-05 04:02:49'),
(11, 'add-academic-level', 1, 12, 'Add Academic Level', 'can add academic level', 1, '2017-08-05 03:58:39', '2017-08-05 03:58:39'),
(12, 'edit-academic-level', 1, 12, 'Edit Academic level', 'can edit academic level', 1, '2017-08-05 03:59:22', '2017-08-05 10:14:48'),
(13, 'delete-academic-level', 1, 12, 'Delete Academic Level', 'can delete academic level', 1, '2017-08-05 04:00:03', '2017-08-05 04:00:03'),
(14, 'manage-batch', 1, 12, 'Manage Batch', 'can manage batch', 1, '2017-08-05 04:04:04', '2017-08-05 04:04:04'),
(15, 'add-batch', 1, 12, 'Add Batch', 'can add batch', 1, '2017-08-05 04:04:33', '2017-08-05 10:15:45'),
(16, 'edit-batch', 1, 12, 'Edit Batch', 'can edit batch', 1, '2017-08-05 04:05:16', '2017-08-05 04:05:16'),
(17, 'delete-batch', 1, 12, 'Delete Batch', 'can delete batch', 1, '2017-08-05 04:08:15', '2017-08-05 04:08:15'),
(18, 'manage-subject', 1, 12, 'Manage Subject', 'can manage subject', 1, '2017-08-05 04:11:13', '2017-08-05 04:11:13'),
(19, 'add-subject', 1, 12, 'Add Subject', 'can add subject', 1, '2017-08-05 04:12:48', '2017-08-05 04:12:48'),
(20, 'edit-subject', 1, 12, 'Edit Subject', 'can edit subject', 1, '2017-08-05 04:14:13', '2017-08-05 04:14:13'),
(21, 'delete-subject', 1, 12, 'Delete Subject', 'can delete subject', 1, '2017-08-05 04:14:44', '2017-08-05 04:14:44'),
(22, 'manage-section', 1, 12, 'Manage Section', 'can manage section', 1, '2017-08-05 04:15:53', '2017-08-05 04:15:53'),
(23, 'add-section', 1, 12, 'Add Section', 'can add section', 1, '2017-08-05 04:16:32', '2017-08-05 04:16:32'),
(24, 'edit-section', 1, 12, 'Edit Section', 'can edit section', 1, '2017-08-05 04:17:56', '2017-08-05 04:17:56'),
(26, 'manage-grade-setup', 1, 14, 'Manage Grade Setup', 'can manage grade setup', 1, '2017-08-05 04:29:28', '2017-08-05 04:34:12'),
(27, 'add-grade-category', 1, 14, 'Add Grade Category', 'can add assessment grade category', 1, '2017-08-05 04:30:25', '2017-08-05 04:33:45'),
(28, 'edit-grade-category', 1, 14, 'Edit Grade Category', 'can edit assessment grade category', 1, '2017-08-05 04:31:24', '2017-08-05 04:31:24'),
(29, 'delete-grade-category', 1, 14, 'Delete Grade Category', 'can delete assessment grade category', 1, '2017-08-05 04:35:15', '2017-08-05 04:35:15'),
(30, 'add-assessment', 1, 14, 'Add Assessment', 'can add new assessment', 1, '2017-08-05 04:37:04', '2017-08-05 04:37:04'),
(31, 'edit-assessment', 1, 14, 'Edit Assessment', 'can edit assessment', 1, '2017-08-05 04:37:57', '2017-08-05 04:37:57'),
(32, 'delete-assessment', 1, 14, 'Delete Assessment', 'can delete assessment', 1, '2017-08-05 04:38:48', '2017-08-05 04:38:48'),
(33, 'manage-report-card', 1, 14, 'Manage Report Card', 'can manage report card', 1, '2017-08-05 04:40:54', '2017-08-05 04:40:54'),
(34, 'download-student-report-card', 1, 14, 'Download Student Report Card', 'can download single student report card', 1, '2017-08-05 04:43:22', '2017-08-05 04:43:22'),
(35, 'manage-grade-book', 1, 14, 'Manage Grade Book', 'can manage grade book', 1, '2017-08-05 04:44:27', '2017-08-05 04:44:27'),
(36, 'export-grade-book', 1, 14, 'Export GradeBook', 'Can export grade book', 1, '2017-08-05 04:46:02', '2017-08-05 04:47:32'),
(37, 'import-grade-book', 1, 14, 'Import GradeBook', 'can import grade book', 1, '2017-08-05 04:47:19', '2017-08-05 04:47:19'),
(38, 'manage-result', 1, 14, 'Manage Result', 'can manage result', 1, '2017-08-05 04:49:59', '2017-08-05 04:49:59'),
(39, 'manage-student-module', 3, 0, 'Manage Student Module', 'Has access to Student module', 1, '2017-08-05 04:58:52', '2017-08-05 05:03:03'),
(40, 'manage-student-management', 3, 0, 'Manage Student Management', 'Has access and can manage student management', 1, '2017-08-05 05:09:16', '2017-08-06 01:22:52'),
(41, 'manage-enquiry-management', 3, 0, 'Manage Enquiry Management', 'Has access and can manage student enquiry management', 1, '2017-08-05 05:11:10', '2017-08-06 01:23:40'),
(42, 'add-student', 3, 17, 'Add Student', 'can add a student', 1, '2017-08-05 05:12:34', '2017-08-05 05:12:34'),
(43, 'edit-student', 3, 17, 'Edit Student', 'can edit a student', 1, '2017-08-05 05:13:18', '2017-08-05 05:13:18'),
(44, 'delete-student', 3, 17, 'Delete Student', 'can delete a student', 1, '2017-08-05 05:14:05', '2017-08-05 05:14:05'),
(45, 'import-student', 3, 17, 'Import Student', 'can import student', 1, '2017-08-05 05:14:59', '2017-08-05 05:14:59'),
(46, 'promote-student', 3, 17, 'Promote Student', 'can promote a student', 1, '2017-08-05 05:15:58', '2017-08-05 05:15:58'),
(47, 'delete-section', 1, 12, 'Delete Section', 'can delete section', 1, '2017-08-05 09:59:17', '2017-08-05 09:59:17'),
(48, 'manage-setting-module', 10, 0, 'Manage Settiing(Module)', 'can manage setting module', 1, '2017-08-05 23:54:01', '2017-08-05 23:54:01'),
(49, 'manage-configure-sub-module', 10, 0, 'Manage Configure (Sub Module)', 'can manage configure sub module', 1, '2017-08-06 00:03:40', '2017-08-06 00:03:50'),
(50, 'manage-sms-sub-module', 10, 0, 'Manage SMS (Sub Module)', 'can manage sms sub module', 1, '2017-08-06 00:04:57', '2017-08-06 00:04:57'),
(51, 'manage-users-sub-module', 10, 0, 'Manage Users (Sub Module)', 'can manage users sub module', 1, '2017-08-06 00:06:15', '2017-08-06 00:06:15'),
(52, 'manage-user-rights-sub-module', 10, 0, 'Manage User Rights (Sub Module)', 'can manage user rights sub module', 1, '2017-08-06 00:08:28', '2017-08-06 00:08:28'),
(53, 'manage-additional-sub-module', 10, 0, 'Manage Additional (Sub Module)', 'can manage additional sub module', 1, '2017-08-06 00:09:45', '2017-08-06 00:09:45'),
(54, 'manage-country', 10, 29, 'Manage Country (Menu)', 'can manage country', 1, '2017-08-06 00:12:50', '2017-08-06 00:15:17'),
(55, 'manage-state', 10, 29, 'Manage State (Menu)', 'can manage state', 1, '2017-08-06 00:14:08', '2017-08-06 00:15:22'),
(56, 'manage-city', 10, 29, 'Manage City (Menu)', 'can manage city', 1, '2017-08-06 00:16:20', '2017-08-06 00:16:20'),
(57, 'manage-institue', 10, 29, 'Manage Institute (Menu)', 'can manage institute', 1, '2017-08-06 00:17:22', '2017-08-06 00:17:22'),
(58, 'manange-language', 10, 29, 'Manage Language (Menu)', 'can manage language', 1, '2017-08-06 00:18:23', '2017-08-06 00:18:23'),
(59, 'manage-nationality', 10, 29, 'Manage Nationality (Menu)', 'can manage nationality', 1, '2017-08-06 00:19:04', '2017-08-06 00:19:13'),
(60, 'manage-sms-settings', 10, 30, 'Manage SMS Settings (Menu)', 'can manage sms settings', 1, '2017-08-06 00:20:31', '2017-08-06 00:20:31'),
(61, 'manage-sms-getway', 10, 30, 'Manage SMS Getway (Menu)', 'can manage sms getway', 1, '2017-08-06 00:21:49', '2017-08-06 00:21:49'),
(62, 'manage-roles', 10, 32, 'Manage Roles (Menu)', 'can manage roles', 1, '2017-08-06 00:24:01', '2017-08-06 00:24:01'),
(63, 'manage-permission', 10, 32, 'Manage Permission (Menu)', 'can manage permissions', 1, '2017-08-06 00:24:54', '2017-08-06 00:27:14'),
(64, 'add-role', 10, 32, 'Add Role (Menu)', 'can add role', 1, '2017-08-06 00:25:33', '2017-08-06 00:25:33'),
(65, 'edit-role', 10, 32, 'Edit Role (Menu)', 'can edit role', 1, '2017-08-06 00:26:06', '2017-08-06 00:26:06'),
(66, 'delete-role', 10, 32, 'Delete Role (Menu)', 'can delete role', 1, '2017-08-06 00:27:03', '2017-08-06 00:27:03'),
(67, 'add-permission', 10, 32, 'Add Permission', 'can add permission', 1, '2017-08-06 00:27:55', '2017-08-06 00:27:55'),
(68, 'edit-permission', 10, 32, 'Edit Permission', 'can edit permission', 1, '2017-08-06 00:28:25', '2017-08-06 00:28:25'),
(69, 'delete-permission', 10, 32, 'Delete Permission (Menu)', 'can delete permission', 1, '2017-08-06 00:29:08', '2017-08-06 00:29:08'),
(70, 'manage-hr-module', 2, 0, 'Manage HR (Module)', 'Has access to  human resources (HR) module and routes', 1, '2017-08-06 00:34:54', '2017-08-06 00:47:05'),
(71, 'manage-employee-management', 2, 0, 'Manage Employee Management (Sub Module)', 'can manage employee management sub module and routes', 1, '2017-08-06 00:37:45', '2017-08-06 00:46:56'),
(72, 'manage-employee-congfiguation', 2, 0, 'Manage Employee  Configuration (Sub Module)', 'can manage employee configuration sub module', 1, '2017-08-06 00:40:03', '2017-08-06 00:40:03'),
(73, 'manage-leave-management', 2, 0, 'Manage Leave Management (Sub Module)', 'can manage employee leave management sub module', 1, '2017-08-06 00:42:26', '2017-08-06 00:42:26'),
(74, 'manage-attendance-management', 2, 0, 'Manage Attendance Management (Sub Module))', 'can manage employee attendance sub module', 1, '2017-08-06 00:44:24', '2017-08-06 01:16:37'),
(75, 'manage-payroll', 2, 0, 'Manage Payroll (Sub Module)', 'can manage payroll sub module', 1, '2017-08-06 00:46:04', '2017-08-06 00:46:04'),
(76, 'manage-department', 2, 19, 'Manage Department (Menu)', 'has access to  manage department and its routes', 1, '2017-08-06 00:52:43', '2017-08-06 00:52:43'),
(77, 'add-department', 2, 19, 'Add Department', 'can add department', 1, '2017-08-06 00:54:49', '2017-08-06 00:54:49'),
(78, 'edit-department', 2, 19, 'Edit Department (Menu)', 'has access and can edit department', 1, '2017-08-06 00:56:17', '2017-08-06 00:56:17'),
(79, 'delete-department', 2, 19, 'Delete Department (Menu)', 'has access and can delete department', 1, '2017-08-06 00:57:09', '2017-08-06 00:57:09'),
(80, 'manage-designation', 2, 19, 'Manage Designation (Menu)', 'has access and can manage designtion', 1, '2017-08-06 00:58:12', '2017-08-06 00:58:12'),
(81, 'add-designation', 2, 19, 'Add Designation (Menu)', 'has access and can add designtion', 1, '2017-08-06 00:59:59', '2017-08-06 00:59:59'),
(82, 'edit-designation', 2, 19, 'Edit Designation', 'has access and can edit designtion', 1, '2017-08-06 01:00:37', '2017-08-06 01:00:37'),
(83, 'delete-designation', 2, 19, 'Delete Designation (Menu)', 'has access and can delete designtion', 1, '2017-08-06 01:01:27', '2017-08-06 01:01:27'),
(84, 'manage-employees', 2, 19, 'Manage Employee (Menu)', 'has access and can manage employee', 1, '2017-08-06 01:04:37', '2017-08-06 01:04:37'),
(85, 'add-employee', 2, 19, 'Add Employee (Menu)', 'Has access and can add employee', 1, '2017-08-06 01:05:23', '2017-08-06 01:05:23'),
(86, 'edit-employee', 2, 19, 'Edit Employee (Menu)', 'Has access and can edit employee', 1, '2017-08-06 01:05:55', '2017-08-06 01:05:55'),
(87, 'delete-employee', 2, 19, 'Delete Employee (Menu)', 'Has access and can delete employee', 1, '2017-08-06 01:06:58', '2017-08-06 01:07:34'),
(88, 'import-employee', 2, 19, 'Import Employee (Menu)', 'Has access and can import employee', 1, '2017-08-06 01:09:17', '2017-08-06 01:09:17'),
(89, 'manage-shift-allocation', 2, 19, 'Manage Shift Allocation (Menu)', 'Has access and can allocate shift to the employee', 1, '2017-08-06 01:12:25', '2017-08-06 01:12:45'),
(90, 'manage-employee-settings', 2, 19, 'Manage Employee Settings', 'Has access and can manage employee settings', 1, '2017-08-06 01:13:59', '2017-08-06 01:13:59'),
(91, 'manage-employee-attendance', 2, 22, 'Manange Employee Attendance', 'Has access and can manage employee attendacne', 1, '2017-08-06 01:17:55', '2017-08-06 01:17:55'),
(92, 'take-employee-attendance', 2, 22, 'Take Employee Attendance', 'Has access and can take employee attenadance', 1, '2017-08-06 01:18:56', '2017-08-06 01:18:56'),
(93, 'import-employee-attendance', 2, 22, 'Import Employee Attendance', 'Has access and can import employee attendance', 1, '2017-08-06 01:19:49', '2017-08-06 01:20:05'),
(94, 'manage-student', 3, 17, 'Manage Student', 'has access and can manage student', 1, '2017-08-06 01:24:39', '2017-08-06 01:24:47'),
(95, 'manage-student-attendance', 1, 15, 'Manage Student Attendance', 'has access and can manage student attendance', 1, '2017-08-06 01:35:53', '2017-08-06 01:35:53'),
(96, 'take-student-attendance', 1, 15, 'Take Student Attendance', 'Has access and can take student attenadance', 1, '2017-08-06 01:36:46', '2017-08-06 01:36:52'),
(97, 'import-student-attendance', 1, 15, 'Import Student Attendance', 'Has access and can import student attendance', 1, '2017-08-06 01:38:02', '2017-08-06 01:38:02'),
(98, 'manage-fees-module', 4, 0, 'Manage Fees (Module)', 'Has access to the fees module and routes', 1, '2017-08-06 01:44:44', '2017-08-06 01:47:15'),
(99, 'manage-fees-management', 4, 0, 'Manage Fees Management', 'Has access and can manage fees management', 1, '2017-08-06 01:46:21', '2017-08-06 01:46:21'),
(100, 'manage-fees', 4, 37, 'Manage Fees', 'Has access and manage fees', 1, '2017-08-06 01:48:56', '2017-08-06 01:48:56'),
(101, 'add-fees', 4, 37, 'Add Fees', 'Has access and add fees', 1, '2017-08-06 01:53:36', '2017-08-06 01:53:36'),
(102, 'delete-fees', 4, 37, 'Delete Fees', 'has access and can delete fees', 1, '2017-08-06 01:54:51', '2017-08-06 01:54:51'),
(103, 'manage-payment-transaction', 4, 37, 'Manage Payment Transacation', 'has access and can manage payment transaction', 1, '2017-08-06 01:57:05', '2017-08-06 01:57:05'),
(104, 'invoice-management', 4, 37, 'Invoice Management', 'has access and can manage invoice', 1, '2017-08-06 01:59:13', '2017-08-06 01:59:13'),
(105, 'invoice-generate', 4, 37, 'Invoice Generation', 'has access and generate invoice', 1, '2017-08-06 02:01:23', '2017-08-06 02:01:23'),
(106, 'invoice-cancel', 4, 37, 'Cancel Invoice', 'can cancel a invoice', 1, '2017-08-06 03:03:06', '2017-08-06 03:03:06'),
(107, 'download-result', 1, 14, 'Download Result', 'has access and can download result', 1, '2017-08-06 03:05:46', '2017-08-06 03:05:46'),
(108, 'manage-timetable', 1, 13, 'Manage Timetable', 'has access and can manage timetable', 1, '2017-08-06 03:07:44', '2017-08-06 03:23:19'),
(109, 'manage-timetable-settings', 1, 13, 'Manage Time Settings', 'has access and can manage timetable settings', 1, '2017-08-06 03:09:21', '2017-08-06 03:09:21'),
(110, 'add-period-category', 1, 13, 'Add Timetable Period Category', 'has access and can add timetable period category', 1, '2017-08-06 03:11:44', '2017-08-06 03:11:44'),
(111, 'edit-period-category', 1, 13, 'Edit Timetable period category', 'has access and can edit timetable period category', 1, '2017-08-06 03:13:07', '2017-08-06 03:13:07'),
(112, 'delete-period-category', 1, 13, 'Delete Timetable Period Category', 'has access and can delete timetable period category', 1, '2017-08-06 03:15:22', '2017-08-06 03:15:22'),
(113, 'manage-period', 1, 13, 'Manage Timetable Period', 'hass access and can manage period', 1, '2017-08-06 03:16:57', '2017-08-06 03:16:57'),
(114, 'add-period', 1, 13, 'Add Period', 'has access and can add timetable period', 1, '2017-08-06 03:18:28', '2017-08-06 03:18:28'),
(115, 'edit-period', 1, 13, 'Edit Timetable period', 'has access and can edit timetable period', 1, '2017-08-06 03:19:12', '2017-08-06 03:19:12'),
(116, 'delete-period', 1, 13, 'Delete Timetable Period', 'has access and can delete timetable period', 1, '2017-08-06 03:24:37', '2017-08-06 03:24:37'),
(117, 'manage-parents', 10, 31, 'Manage Parents', 'Has access and can manage parents', 1, '2017-08-06 03:28:30', '2017-08-06 03:28:30'),
(118, 'manage-student-password', 10, 31, 'Manage Student Password', 'has access and can manage student password', 1, '2017-08-06 03:29:35', '2017-08-06 03:29:35'),
(119, 'manage-parent-password', 10, 31, 'Manage Parent Password', 'has access and can manage parent password', 1, '2017-08-06 03:30:42', '2017-08-06 03:30:42'),
(120, 'manage-employee-password', 10, 31, 'Manage Employee Password', 'has access and can manage employee password', 1, '2017-08-06 03:49:54', '2017-08-06 03:49:54'),
(121, 'view-admin-dashboard', 11, 0, 'view-admin-dashboard', 'has access to the admin dashoard', 1, '2017-08-06 04:55:09', '2017-08-06 04:55:39'),
(122, 'view-student-dashboard', 11, 0, 'View Student Dashboard', 'has access to the student dashboaard', 1, '2017-08-06 04:56:32', '2017-08-06 04:56:32'),
(123, 'view-parent-dashboard', 11, 0, 'View Parent Dashboard', 'has access to the parent dashboard', 1, '2017-08-06 04:58:03', '2017-08-06 04:58:03'),
(124, 'view-teacher-dashboard', 11, 0, 'View Teacher Dashboard', 'has access to the teacher dashboard', 1, '2017-08-06 05:00:29', '2017-09-17 06:41:26'),
(125, 'view-hr-dashboard', 11, 0, 'View HR Dashboard', 'has access hr dashoard', 1, '2017-08-06 05:01:57', '2017-08-06 05:01:57');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(121, 1),
(123, 2),
(122, 3),
(125, 4),
(124, 5);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', 'Has access to all modules and routes', 1, '2017-08-05 02:09:28', '2017-08-05 02:09:28'),
(2, 'parent', 'Parent', 'Has access to Student modules and parent routes', 1, '2017-08-05 02:10:13', '2017-08-05 02:10:13'),
(3, 'student', 'Student', 'Has access to Student modules and student routes', 1, '2017-08-05 02:11:12', '2017-08-05 02:11:12'),
(4, 'hrms', 'HR', 'Has access to hr module and routes', 1, '2017-08-05 02:12:19', '2017-08-17 03:08:20'),
(5, 'teacher', 'Teacher', 'Has access to Employee modules and teacher routes', 1, '2017-08-05 02:13:11', '2017-08-05 02:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `room_category`
--

CREATE TABLE `room_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `categoryname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_master`
--

CREATE TABLE `room_master` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `seat_capacity` int(11) NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_log`
--

CREATE TABLE `schedule_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(10) UNSIGNED NOT NULL,
  `academics_year_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `batch_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `section_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `intake` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `academics_year_id`, `batch_id`, `section_name`, `intake`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'A', '60', 1, '2017-09-24 03:48:42', '2017-09-24 03:48:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `setting_campus`
--

CREATE TABLE `setting_campus` (
  `id` int(10) UNSIGNED NOT NULL,
  `institute_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `address_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campus_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting_campus`
--

INSERT INTO `setting_campus` (`id`, `institute_id`, `address_id`, `name`, `campus_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Campus 01', 'VS01', NULL, '2017-09-10 19:21:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `setting_city`
--

CREATE TABLE `setting_city` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `state_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting_country`
--

CREATE TABLE `setting_country` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting_country`
--

INSERT INTO `setting_country` (`id`, `name`, `nationality`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Bangladesh', 'Bangladeshi', '2017-04-05 17:30:56', '2017-04-05 17:30:56', NULL),
(2, 'India', 'Indian', '2017-04-05 17:31:03', '2017-04-05 17:31:03', NULL),
(3, 'Pakistan', 'Pakistani', '2017-04-05 17:31:10', '2017-04-05 17:31:10', NULL),
(4, 'Nepal', 'Nepali', '2017-04-05 17:31:14', '2017-04-05 17:31:14', NULL),
(5, 'Bhutan', 'Bhutani', '2017-04-05 17:31:19', '2017-04-05 17:31:19', NULL),
(6, 'Unitd States of America', 'American', '2017-04-05 17:31:51', '2017-04-05 17:31:51', NULL),
(7, 'United Kingdom', 'British', '2017-04-05 17:32:11', '2017-04-05 17:32:11', NULL),
(8, 'Japan', 'Japane', '2017-04-05 17:32:26', '2017-04-05 17:32:26', NULL),
(9, 'China', 'Chinese', '2017-04-05 17:32:33', '2017-04-05 17:32:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `setting_institute`
--

CREATE TABLE `setting_institute` (
  `id` int(10) UNSIGNED NOT NULL,
  `institute_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `institute_alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting_institute`
--

INSERT INTO `setting_institute` (`id`, `institute_name`, `institute_alias`, `address1`, `address2`, `phone`, `email`, `website`, `logo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Venus School', 'Venus School', 'Badda, Dhaka', 'Badda', '0123456745324', 'venus@gmail.com', 'venusschool.com', 'thankyou4.png', '2017-04-05 22:15:18', '2017-09-24 03:53:21', NULL),
(2, 'asdfasd', 'fasdfasd', 'fasdfasdf', 'sdfasdf', '24523452345234', 'adfasamirul.ete@gmail.com', 'asdfasd', 'configure-report-card.jpg', '2017-06-11 20:21:33', '2017-06-11 20:21:33', NULL),
(3, 'asdfadf', 'asdfasd', 'fasdfasdf', 'asdfasdf', '0987654321', 'amirul.ete@gmail.com', 'asdfasd', 'lt10351621.png', '2017-06-11 20:23:06', '2017-06-11 20:23:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `setting_institute_modules`
--

CREATE TABLE `setting_institute_modules` (
  `institute_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `module_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting_inst_prop`
--

CREATE TABLE `setting_inst_prop` (
  `id` int(10) UNSIGNED NOT NULL,
  `institution_id` int(11) NOT NULL,
  `campus_id` int(11) NOT NULL,
  `attribute_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting_menus`
--

CREATE TABLE `setting_menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sub_module_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting_menu_permission`
--

CREATE TABLE `setting_menu_permission` (
  `menu_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `permission_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting_modules`
--

CREATE TABLE `setting_modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not set',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting_state`
--

CREATE TABLE `setting_state` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_batch`
--

CREATE TABLE `sms_batch` (
  `id` int(10) UNSIGNED NOT NULL,
  `institution_id` int(11) NOT NULL,
  `campus_id` int(11) NOT NULL,
  `batch_count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_batch`
--

INSERT INTO `sms_batch` (`id`, `institution_id`, `campus_id`, `batch_count`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`) VALUES
(1, 1, 1, 1, NULL, '2017-09-23 03:26:16', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sms_credit`
--

CREATE TABLE `sms_credit` (
  `id` int(10) UNSIGNED NOT NULL,
  `institution_id` int(11) NOT NULL,
  `sms_amount` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_type` int(11) DEFAULT NULL,
  `submitted_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submission_date` date DEFAULT NULL,
  `accepted_date` date DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_credit`
--

INSERT INTO `sms_credit` (`id`, `institution_id`, `sms_amount`, `status`, `sms_type`, `submitted_by`, `submission_date`, `accepted_date`, `comment`, `remark`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`) VALUES
(1, 1, 1000, '1', 0, 'Session User', '2017-09-24', NULL, NULL, NULL, '2017-09-23 22:20:09', '2017-09-23 22:20:09', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sms_institution_getway`
--

CREATE TABLE `sms_institution_getway` (
  `id` int(10) UNSIGNED NOT NULL,
  `institution_id` int(11) NOT NULL,
  `api_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activated_upto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_institution_getway`
--

INSERT INTO `sms_institution_getway` (`id`, `institution_id`, `api_path`, `remark`, `status`, `activated_upto`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'https://api.mobireach.com.bd/SendTextMultiMessage?Username=raqib&Password=Abcd@1234&From=ALOKITO&To=', 'Mobirech', '1', '2017-12-31', NULL, NULL, NULL, '2017-09-23 00:13:21', '2017-09-23 00:13:21'),
(2, 1, 'https://api.mobireach.com.bd/SendTextMessage?Username=raqib&Password=Abcd@1234&From=ALOKITO&To=', 'Mobirech', '1', '2017-12-31', NULL, NULL, NULL, '2017-09-23 00:13:39', '2017-09-23 00:13:39'),
(3, 1, 'Hi Romesh How Are You', 'Mobirech', '1', '2017-09-01', NULL, NULL, '2017-09-24 04:25:39', '2017-09-23 06:57:13', '2017-09-24 04:25:39');

-- --------------------------------------------------------

--
-- Table structure for table `sms_log`
--

CREATE TABLE `sms_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_group` int(11) NOT NULL,
  `user_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sms_batch_id` int(11) DEFAULT NULL,
  `delivery_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_message`
--

CREATE TABLE `sms_message` (
  `id` int(10) UNSIGNED NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_status`
--

CREATE TABLE `sms_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `message_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_count` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_credit` double DEFAULT NULL,
  `sms_logid` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_template`
--

CREATE TABLE `sms_template` (
  `id` int(10) UNSIGNED NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_template`
--

INSERT INTO `sms_template` (`id`, `message`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`) VALUES
(1, 'Attendance Notification!\r\nName: {name}\r\nClass: {batch}\r\nSection: {section}\r\nAttendance: {attendance}\r\nDate: {date}', '1', NULL, NULL, NULL, NULL, NULL),
(2, 'Result Notification!\r\nName: {name}\r\nClass: {batch}\r\nSection: {section}\r\n{result}\r\nDate:{date}', '1', NULL, NULL, NULL, NULL, NULL),
(3, 'Fees Notification!\r\nFees Name: {fees}\r\nName: {name}\r\nClass: {batch}\r\nSection: {section}\r\nPaid: {amount}\r\nDate:{date}', '1', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_assessment_view`
--
CREATE TABLE `student_assessment_view` (
`std_id` int(10) unsigned
,`academic_level` int(10) unsigned
,`batch` int(10) unsigned
,`section` int(10) unsigned
,`subject` int(10) unsigned
,`deleted_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `student_attachments`
--

CREATE TABLE `student_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `std_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `doc_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `doc_details` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `doc_submited_at` date DEFAULT NULL,
  `doc_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_attendance_view_one`
--
CREATE TABLE `student_attendance_view_one` (
`academic_year` int(10) unsigned
,`att_id` int(10) unsigned
,`student_id` int(11)
,`std_gender` enum('Male','Female')
,`attendacnce_type` int(11)
,`class_id` int(10) unsigned
,`section_id` int(10) unsigned
,`session_id` int(10) unsigned
,`attendance_date` date
,`subject_id` int(10) unsigned
,`deleted_at` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_attendance_view_two`
--
CREATE TABLE `student_attendance_view_two` (
`academic_year` int(10) unsigned
,`att_id` int(10) unsigned
,`student_id` int(11)
,`std_gender` enum('Male','Female')
,`attendacnce_type` int(11)
,`class_id` int(10) unsigned
,`section_id` int(10) unsigned
,`session_id` int(10) unsigned
,`subject_id` int(10) unsigned
,`sorting_order` int(10) unsigned
,`attendance_date` date
,`deleted_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `student_enrollments`
--

CREATE TABLE `student_enrollments` (
  `id` int(10) UNSIGNED NOT NULL,
  `std_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `gr_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `academic_level` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `batch` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `section` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `academic_year` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `admission_year` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `enrolled_at` date NOT NULL,
  `enroll_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `batch_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `remark` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_enrollment_history`
--

CREATE TABLE `student_enrollment_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `enroll_id` int(10) UNSIGNED NOT NULL,
  `gr_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section` int(10) UNSIGNED NOT NULL,
  `batch` int(10) UNSIGNED NOT NULL,
  `academic_level` int(10) UNSIGNED NOT NULL,
  `academic_year` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `enrolled_at` date NOT NULL,
  `batch_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Not available',
  `remark` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_grades`
--

CREATE TABLE `student_grades` (
  `id` int(10) UNSIGNED NOT NULL,
  `std_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `mark_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `scale_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `class_sub_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `semester` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `academic_year` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_guardians`
--

CREATE TABLE `student_guardians` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `mobile` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `income` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `home_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `office_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_informations`
--

CREATE TABLE `student_informations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `type` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` enum('Mr.','Mrs.','Ms.') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('Male','Female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `blood_group` enum('Unknown','A+','A-','B+','B-','AB+','AB-','O+','O-') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `religion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `residency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institute` int(10) UNSIGNED DEFAULT NULL,
  `campus` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_manage_view`
--
CREATE TABLE `student_manage_view` (
`std_id` int(10) unsigned
,`user_id` int(10) unsigned
,`first_name` varchar(255)
,`middle_name` varchar(255)
,`last_name` varchar(255)
,`email` varchar(255)
,`enroll_id` int(10) unsigned
,`gr_no` varchar(255)
,`academic_year` int(10) unsigned
,`academic_level` int(10) unsigned
,`batch` int(10) unsigned
,`section` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Table structure for table `student_marks`
--

CREATE TABLE `student_marks` (
  `id` int(10) UNSIGNED NOT NULL,
  `marks` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_parents`
--

CREATE TABLE `student_parents` (
  `id` int(10) UNSIGNED NOT NULL,
  `gud_id` int(10) UNSIGNED DEFAULT NULL,
  `std_id` int(10) UNSIGNED DEFAULT NULL,
  `is_emergency` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_payment_view`
--
CREATE TABLE `student_payment_view` (
`std_id` int(10) unsigned
,`academic_year` int(10) unsigned
,`batch` int(10) unsigned
,`section` int(10) unsigned
,`payable_amount` double(25,8)
,`payed_amount` double
);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@alokito.com', '$2y$10$bNlbwymEiXe9dpDSDlsYpeWE4OMRSGB2ECPpeAWVpKYeZCchafWdq', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_institution_campus`
--

CREATE TABLE `user_institution_campus` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `institute_id` int(10) UNSIGNED NOT NULL,
  `campus_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_institution_campus`
--

INSERT INTO `user_institution_campus` (`user_id`, `institute_id`, `campus_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure for view `applicant_manage_view`
--
DROP TABLE IF EXISTS `applicant_manage_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `applicant_manage_view`  AS  select `a`.`id` AS `applicant_id`,`a`.`application_no` AS `application_no`,`a`.`payment_status` AS `payment_status`,`a`.`application_status` AS `application_status`,`i`.`first_name` AS `first_name`,`i`.`middle_name` AS `middle_name`,`i`.`last_name` AS `last_name`,`a`.`email` AS `email`,`i`.`gender` AS `gender`,`i`.`birth_date` AS `birth_date`,`e`.`academic_year` AS `academic_year`,`e`.`academic_level` AS `academic_level`,`e`.`batch` AS `batch`,`e`.`section` AS `section`,`a`.`campus_id` AS `campus_id`,`a`.`institute_id` AS `institute_id`,`a`.`created_at` AS `created_at` from ((`applicant_user` `a` join `applicant_information` `i` on((`i`.`applicant_id` = `a`.`id`))) join `applicant_enrollment` `e` on((`e`.`applicant_id` = `a`.`id`))) where isnull(`a`.`deleted_at`) ;

-- --------------------------------------------------------

--
-- Structure for view `student_assessment_view`
--
DROP TABLE IF EXISTS `student_assessment_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_assessment_view`  AS  select `a`.`std_id` AS `std_id`,`a`.`academic_level` AS `academic_level`,`a`.`batch` AS `batch`,`a`.`section` AS `section`,`b`.`subject_id` AS `subject`,`a`.`deleted_at` AS `deleted_at` from (`student_enrollments` `a` join `class_subjects` `b` on(((`b`.`class_id` = `a`.`batch`) and (`b`.`section_id` = `a`.`section`)))) ;

-- --------------------------------------------------------

--
-- Structure for view `student_attendance_view_one`
--
DROP TABLE IF EXISTS `student_attendance_view_one`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_attendance_view_one`  AS  select `e`.`academic_year` AS `academic_year`,`a`.`id` AS `att_id`,`a`.`student_id` AS `student_id`,`s`.`gender` AS `std_gender`,`a`.`attendacnce_type` AS `attendacnce_type`,`b`.`class_id` AS `class_id`,`b`.`section_id` AS `section_id`,`b`.`session_id` AS `session_id`,`a`.`attendance_date` AS `attendance_date`,`b`.`subject_id` AS `subject_id`,`a`.`deleted_at` AS `deleted_at` from (((`academices_student_attendances` `a` join `academices_student_attendances_details` `b` on((`b`.`student_attendace_id` = `a`.`id`))) join `student_enrollments` `e` on((`e`.`std_id` = `a`.`student_id`))) join `student_informations` `s` on((`s`.`id` = `a`.`student_id`))) where (`b`.`subject_id` = 0) ;

-- --------------------------------------------------------

--
-- Structure for view `student_attendance_view_two`
--
DROP TABLE IF EXISTS `student_attendance_view_two`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_attendance_view_two`  AS  select `e`.`academic_year` AS `academic_year`,`a`.`id` AS `att_id`,`a`.`student_id` AS `student_id`,`s`.`gender` AS `std_gender`,`a`.`attendacnce_type` AS `attendacnce_type`,`b`.`class_id` AS `class_id`,`b`.`section_id` AS `section_id`,`b`.`session_id` AS `session_id`,`b`.`subject_id` AS `subject_id`,`c`.`sorting_order` AS `sorting_order`,`a`.`attendance_date` AS `attendance_date`,`a`.`deleted_at` AS `deleted_at` from ((((`academices_student_attendances` `a` join `academices_student_attendances_details` `b` on((`b`.`student_attendace_id` = `a`.`id`))) join `class_subjects` `c` on(((`c`.`id` = `b`.`subject_id`) and (`c`.`class_id` = `b`.`class_id`) and (`c`.`section_id` = `b`.`section_id`)))) join `student_enrollments` `e` on((`e`.`std_id` = `a`.`student_id`))) join `student_informations` `s` on((`s`.`id` = `a`.`student_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `student_manage_view`
--
DROP TABLE IF EXISTS `student_manage_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_manage_view`  AS  select `i`.`id` AS `std_id`,`i`.`user_id` AS `user_id`,`i`.`first_name` AS `first_name`,`i`.`middle_name` AS `middle_name`,`i`.`last_name` AS `last_name`,`i`.`email` AS `email`,`e`.`id` AS `enroll_id`,`e`.`gr_no` AS `gr_no`,`e`.`academic_year` AS `academic_year`,`e`.`academic_level` AS `academic_level`,`e`.`batch` AS `batch`,`e`.`section` AS `section` from (`student_enrollments` `e` join `student_informations` `i` on((`i`.`id` = `e`.`std_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `student_payment_view`
--
DROP TABLE IF EXISTS `student_payment_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_payment_view`  AS  select `e`.`std_id` AS `std_id`,`e`.`academic_year` AS `academic_year`,`e`.`batch` AS `batch`,`e`.`section` AS `section`,sum(`virtualtable`.`amount`) AS `payable_amount`,sum(ifnull(`virtualtable2`.`payed_amount`,0)) AS `payed_amount` from (((`student_enrollments` `e` join `fees_invoices` `i` on((`i`.`payer_id` = `e`.`id`))) join (select sum((`fees_item`.`rate` * `fees_item`.`qty`)) AS `amount`,`fees_item`.`fees_id` AS `fees_id` from `fees_item` group by `fees_item`.`fees_id`) `virtualtable` on((`virtualtable`.`fees_id` = `i`.`fees_id`))) left join (select sum(`invoice_payment`.`payment_amount`) AS `payed_amount`,`invoice_payment`.`invoice_id` AS `invoice_id` from `invoice_payment` group by `invoice_payment`.`invoice_id`) `virtualtable2` on((`i`.`id` = `virtualtable2`.`invoice_id`))) group by `e`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academices_student_attendances`
--
ALTER TABLE `academices_student_attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academices_student_attendances_details`
--
ALTER TABLE `academices_student_attendances_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academics_admissionyear`
--
ALTER TABLE `academics_admissionyear`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academics_assessments`
--
ALTER TABLE `academics_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academics_assessments_grading_category_id_foreign` (`grading_category_id`),
  ADD KEY `academics_assessments_grade_id_foreign` (`grade_id`);

--
-- Indexes for table `academics_attendance_sessions`
--
ALTER TABLE `academics_attendance_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academics_attendance_settings`
--
ALTER TABLE `academics_attendance_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academics_attendance_types`
--
ALTER TABLE `academics_attendance_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academics_class_grade_scales`
--
ALTER TABLE `academics_class_grade_scales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academics_division`
--
ALTER TABLE `academics_division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academics_grades`
--
ALTER TABLE `academics_grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academics_grades_grade_scale_id_foreign` (`grade_scale_id`);

--
-- Indexes for table `academics_grade_categories`
--
ALTER TABLE `academics_grade_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academics_grade_details`
--
ALTER TABLE `academics_grade_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academics_grade_details_grade_id_foreign` (`grade_id`);

--
-- Indexes for table `academics_grade_scales`
--
ALTER TABLE `academics_grade_scales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academics_level`
--
ALTER TABLE `academics_level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academics_level_academics_year_id_foreign` (`academics_year_id`);

--
-- Indexes for table `academics_syllabus`
--
ALTER TABLE `academics_syllabus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academics_syllabus_academic_year_foreign` (`academic_year`),
  ADD KEY `academics_syllabus_academic_level_foreign` (`academic_level`),
  ADD KEY `academics_syllabus_batch_foreign` (`batch`),
  ADD KEY `academics_syllabus_section_foreign` (`section`),
  ADD KEY `academics_syllabus_institute_id_foreign` (`institute_id`),
  ADD KEY `academics_syllabus_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `academics_year`
--
ALTER TABLE `academics_year`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academics_year_institute_id_foreign` (`institute_id`),
  ADD KEY `academics_year_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `academics_year_semesters`
--
ALTER TABLE `academics_year_semesters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_bank`
--
ALTER TABLE `acc_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_charts`
--
ALTER TABLE `acc_charts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_closing_balance`
--
ALTER TABLE `acc_closing_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_f_year`
--
ALTER TABLE `acc_f_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_subledger`
--
ALTER TABLE `acc_subledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_tran`
--
ALTER TABLE `acc_tran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_voucher_type`
--
ALTER TABLE `acc_voucher_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`),
  ADD KEY `addresses_city_id_foreign` (`city_id`),
  ADD KEY `addresses_state_id_foreign` (`state_id`),
  ADD KEY `addresses_country_id_foreign` (`country_id`);

--
-- Indexes for table `applicant_address`
--
ALTER TABLE `applicant_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_address_applicant_id_foreign` (`applicant_id`),
  ADD KEY `applicant_address_city_id_foreign` (`city_id`),
  ADD KEY `applicant_address_state_id_foreign` (`state_id`),
  ADD KEY `applicant_address_country_id_foreign` (`country_id`);

--
-- Indexes for table `applicant_document`
--
ALTER TABLE `applicant_document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_document_applicant_id_foreign` (`applicant_id`);

--
-- Indexes for table `applicant_enrollment`
--
ALTER TABLE `applicant_enrollment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_enrollment_applicant_id_foreign` (`applicant_id`),
  ADD KEY `applicant_enrollment_academic_year_foreign` (`academic_year`),
  ADD KEY `applicant_enrollment_academic_level_foreign` (`academic_level`),
  ADD KEY `applicant_enrollment_batch_foreign` (`batch`);

--
-- Indexes for table `applicant_exam_settings`
--
ALTER TABLE `applicant_exam_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_fees_settings_campus_id_foreign` (`campus_id`),
  ADD KEY `applicant_fees_settings_institute_id_foreign` (`institute_id`),
  ADD KEY `applicant_fees_settings_academic_year_foreign` (`academic_year`),
  ADD KEY `applicant_fees_settings_academic_level_foreign` (`academic_level`),
  ADD KEY `applicant_fees_settings_batch_foreign` (`batch`),
  ADD KEY `applicant_fees_settings_section_foreign` (`section`);

--
-- Indexes for table `applicant_fees`
--
ALTER TABLE `applicant_fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_fees_applicant_id_foreign` (`applicant_id`),
  ADD KEY `applicant_fees_campus_id_foreign` (`campus_id`),
  ADD KEY `applicant_fees_institute_id_foreign` (`institute_id`);

--
-- Indexes for table `applicant_follow_ups`
--
ALTER TABLE `applicant_follow_ups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applicant_grades`
--
ALTER TABLE `applicant_grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_grades_applicant_id_foreign` (`applicant_id`),
  ADD KEY `applicant_grades_campus_id_foreign` (`campus_id`),
  ADD KEY `applicant_grades_institute_id_foreign` (`institute_id`),
  ADD KEY `applicant_grades_academic_year_foreign` (`academic_year`),
  ADD KEY `applicant_grades_academic_level_foreign` (`academic_level`),
  ADD KEY `applicant_grades_batch_foreign` (`batch`);

--
-- Indexes for table `applicant_information`
--
ALTER TABLE `applicant_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_information_applicant_id_foreign` (`applicant_id`);

--
-- Indexes for table `applicant_merit_batch`
--
ALTER TABLE `applicant_merit_batch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_merit_batch_campus_id_foreign` (`campus_id`),
  ADD KEY `applicant_merit_batch_institute_id_foreign` (`institute_id`),
  ADD KEY `applicant_merit_batch_academic_year_foreign` (`academic_year`),
  ADD KEY `applicant_merit_batch_academic_level_foreign` (`academic_level`),
  ADD KEY `applicant_merit_batch_academic_batch_foreign` (`batch`);

--
-- Indexes for table `applicant_results`
--
ALTER TABLE `applicant_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_results_applicant_id_foreign` (`applicant_id`),
  ADD KEY `applicant_results_campus_id_foreign` (`campus_id`),
  ADD KEY `applicant_results_institute_id_foreign` (`institute_id`),
  ADD KEY `applicant_results_academic_year_foreign` (`academic_year`),
  ADD KEY `applicant_results_academic_level_foreign` (`academic_level`),
  ADD KEY `applicant_results_batch_foreign` (`batch`);

--
-- Indexes for table `applicant_user`
--
ALTER TABLE `applicant_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applicant_user_email_unique` (`email`),
  ADD UNIQUE KEY `applicant_user_application_no_unique` (`application_no`),
  ADD KEY `applicant_user_campus_id_foreign` (`campus_id`),
  ADD KEY `applicant_user_institute_id_foreign` (`institute_id`);

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audits_auditable_id_auditable_type_index` (`auditable_id`,`auditable_type`);

--
-- Indexes for table `auto_sms_module`
--
ALTER TABLE `auto_sms_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auto_sms_settings`
--
ALTER TABLE `auto_sms_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_academics_year_id_foreign` (`academics_year_id`),
  ADD KEY `batch_academics_level_id_foreign` (`academics_level_id`),
  ADD KEY `batch_division_id_foreign` (`division_id`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_category`
--
ALTER TABLE `book_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_shelf`
--
ALTER TABLE `book_shelf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_vendor`
--
ALTER TABLE `book_vendor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_periods`
--
ALTER TABLE `class_periods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_period_categories`
--
ALTER TABLE `class_period_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_section_period`
--
ALTER TABLE `class_section_period`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_section_period_cs_period_category_foreign` (`cs_period_category`),
  ADD KEY `class_section_period_academic_year_foreign` (`academic_year`),
  ADD KEY `class_section_period_academic_level_foreign` (`academic_level`),
  ADD KEY `class_section_period_batch_foreign` (`batch`),
  ADD KEY `class_section_period_section_foreign` (`section`),
  ADD KEY `class_section_period_institute_id_foreign` (`institute_id`),
  ADD KEY `class_section_period_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_subjects_class_id_foreign` (`class_id`),
  ADD KEY `class_subjects_section_id_foreign` (`section_id`),
  ADD KEY `class_subjects_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `class_subject_teachers`
--
ALTER TABLE `class_subject_teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_subject_teachers_class_subject_id_foreign` (`class_subject_id`),
  ADD KEY `class_subject_teachers_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `class_subject_teacher_timetables`
--
ALTER TABLE `class_subject_teacher_timetables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_timetables`
--
ALTER TABLE `class_timetables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cup_board_shelf`
--
ALTER TABLE `cup_board_shelf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_attachments`
--
ALTER TABLE `employee_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_attachments_emp_id_foreign` (`emp_id`),
  ADD KEY `employee_attachments_doc_id_foreign` (`doc_id`);

--
-- Indexes for table `employee_departments`
--
ALTER TABLE `employee_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_designations`
--
ALTER TABLE `employee_designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_guardians`
--
ALTER TABLE `employee_guardians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_guardians_emp_id_foreign` (`emp_id`);

--
-- Indexes for table `employee_informations`
--
ALTER TABLE `employee_informations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_informations_user_id_foreign` (`user_id`),
  ADD KEY `employee_informations_department_foreign` (`department`),
  ADD KEY `employee_informations_designation_foreign` (`designation`);

--
-- Indexes for table `employee_leave_entitlements`
--
ALTER TABLE `employee_leave_entitlements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_leave_structures`
--
ALTER TABLE `employee_leave_structures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_leave_structure_type`
--
ALTER TABLE `employee_leave_structure_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_leave_types`
--
ALTER TABLE `employee_leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees_collection`
--
ALTER TABLE `fees_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees_discount`
--
ALTER TABLE `fees_discount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees_invoices`
--
ALTER TABLE `fees_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees_item`
--
ALTER TABLE `fees_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_type`
--
ALTER TABLE `fee_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institute_language`
--
ALTER TABLE `institute_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_payment`
--
ALTER TABLE `invoice_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_book`
--
ALTER TABLE `issue_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `payment_extra`
--
ALTER TABLE `payment_extra`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `room_category`
--
ALTER TABLE `room_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_master`
--
ALTER TABLE `room_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_master_category_id_foreign` (`category_id`);

--
-- Indexes for table `schedule_log`
--
ALTER TABLE `schedule_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_academics_year_id_foreign` (`academics_year_id`),
  ADD KEY `section_batch_id_foreign` (`batch_id`);

--
-- Indexes for table `setting_campus`
--
ALTER TABLE `setting_campus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setting_campus_institute_id_foreign` (`institute_id`),
  ADD KEY `setting_campus_address_id_foreign` (`address_id`);

--
-- Indexes for table `setting_city`
--
ALTER TABLE `setting_city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setting_city_country_id_foreign` (`country_id`),
  ADD KEY `setting_city_state_id_foreign` (`state_id`);

--
-- Indexes for table `setting_country`
--
ALTER TABLE `setting_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting_institute`
--
ALTER TABLE `setting_institute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting_institute_modules`
--
ALTER TABLE `setting_institute_modules`
  ADD PRIMARY KEY (`institute_id`,`module_id`),
  ADD KEY `setting_institute_modules_module_id_foreign` (`module_id`);

--
-- Indexes for table `setting_inst_prop`
--
ALTER TABLE `setting_inst_prop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting_menus`
--
ALTER TABLE `setting_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting_menu_permission`
--
ALTER TABLE `setting_menu_permission`
  ADD PRIMARY KEY (`menu_id`,`permission_id`),
  ADD KEY `setting_menu_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `setting_modules`
--
ALTER TABLE `setting_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting_state`
--
ALTER TABLE `setting_state`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setting_state_country_id_foreign` (`country_id`);

--
-- Indexes for table `sms_batch`
--
ALTER TABLE `sms_batch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_credit`
--
ALTER TABLE `sms_credit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_institution_getway`
--
ALTER TABLE `sms_institution_getway`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_log`
--
ALTER TABLE `sms_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_message`
--
ALTER TABLE `sms_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_status`
--
ALTER TABLE `sms_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_template`
--
ALTER TABLE `sms_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_attachments`
--
ALTER TABLE `student_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_attachments_std_id_foreign` (`std_id`),
  ADD KEY `student_attachments_doc_id_foreign` (`doc_id`);

--
-- Indexes for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_enrollments_std_id_foreign` (`std_id`),
  ADD KEY `student_enrollments_academic_level_foreign` (`academic_level`),
  ADD KEY `student_enrollments_batch_foreign` (`batch`),
  ADD KEY `student_enrollments_section_foreign` (`section`),
  ADD KEY `student_enrollments_academic_year_foreign` (`academic_year`),
  ADD KEY `student_enrollments_admission_year_foreign` (`admission_year`);

--
-- Indexes for table `student_enrollment_history`
--
ALTER TABLE `student_enrollment_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_enrollment_history_enroll_id_foreign` (`enroll_id`),
  ADD KEY `student_enrollment_history_section_foreign` (`section`),
  ADD KEY `student_enrollment_history_batch_foreign` (`batch`),
  ADD KEY `student_enrollment_history_academic_level_foreign` (`academic_level`),
  ADD KEY `student_enrollment_history_academic_year_foreign` (`academic_year`);

--
-- Indexes for table `student_grades`
--
ALTER TABLE `student_grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_guardians`
--
ALTER TABLE `student_guardians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_informations`
--
ALTER TABLE `student_informations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_informations_user_id_foreign` (`user_id`);

--
-- Indexes for table `student_marks`
--
ALTER TABLE `student_marks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_parents`
--
ALTER TABLE `student_parents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_parents_gud_id_foreign` (`gud_id`),
  ADD KEY `student_parents_std_id_foreign` (`std_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_institution_campus`
--
ALTER TABLE `user_institution_campus`
  ADD PRIMARY KEY (`user_id`,`institute_id`,`campus_id`),
  ADD KEY `user_institution_campus_institute_id_foreign` (`institute_id`),
  ADD KEY `user_institution_campus_campus_id_foreign` (`campus_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academices_student_attendances`
--
ALTER TABLE `academices_student_attendances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academices_student_attendances_details`
--
ALTER TABLE `academices_student_attendances_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_admissionyear`
--
ALTER TABLE `academics_admissionyear`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `academics_assessments`
--
ALTER TABLE `academics_assessments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_attendance_sessions`
--
ALTER TABLE `academics_attendance_sessions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_attendance_settings`
--
ALTER TABLE `academics_attendance_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_attendance_types`
--
ALTER TABLE `academics_attendance_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_class_grade_scales`
--
ALTER TABLE `academics_class_grade_scales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_division`
--
ALTER TABLE `academics_division`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `academics_grades`
--
ALTER TABLE `academics_grades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_grade_categories`
--
ALTER TABLE `academics_grade_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_grade_details`
--
ALTER TABLE `academics_grade_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_grade_scales`
--
ALTER TABLE `academics_grade_scales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_level`
--
ALTER TABLE `academics_level`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `academics_syllabus`
--
ALTER TABLE `academics_syllabus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `academics_year`
--
ALTER TABLE `academics_year`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `academics_year_semesters`
--
ALTER TABLE `academics_year_semesters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `acc_bank`
--
ALTER TABLE `acc_bank`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `acc_charts`
--
ALTER TABLE `acc_charts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `acc_closing_balance`
--
ALTER TABLE `acc_closing_balance`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `acc_f_year`
--
ALTER TABLE `acc_f_year`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `acc_subledger`
--
ALTER TABLE `acc_subledger`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `acc_tran`
--
ALTER TABLE `acc_tran`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `acc_voucher_type`
--
ALTER TABLE `acc_voucher_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_address`
--
ALTER TABLE `applicant_address`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_document`
--
ALTER TABLE `applicant_document`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_enrollment`
--
ALTER TABLE `applicant_enrollment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_exam_settings`
--
ALTER TABLE `applicant_exam_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_fees`
--
ALTER TABLE `applicant_fees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_follow_ups`
--
ALTER TABLE `applicant_follow_ups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_grades`
--
ALTER TABLE `applicant_grades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_information`
--
ALTER TABLE `applicant_information`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_merit_batch`
--
ALTER TABLE `applicant_merit_batch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_results`
--
ALTER TABLE `applicant_results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `applicant_user`
--
ALTER TABLE `applicant_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `auto_sms_module`
--
ALTER TABLE `auto_sms_module`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `auto_sms_settings`
--
ALTER TABLE `auto_sms_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `batch`
--
ALTER TABLE `batch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `book_category`
--
ALTER TABLE `book_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `book_shelf`
--
ALTER TABLE `book_shelf`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `book_vendor`
--
ALTER TABLE `book_vendor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class_periods`
--
ALTER TABLE `class_periods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class_period_categories`
--
ALTER TABLE `class_period_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class_section_period`
--
ALTER TABLE `class_section_period`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class_subjects`
--
ALTER TABLE `class_subjects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class_subject_teachers`
--
ALTER TABLE `class_subject_teachers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class_subject_teacher_timetables`
--
ALTER TABLE `class_subject_teacher_timetables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class_timetables`
--
ALTER TABLE `class_timetables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cup_board_shelf`
--
ALTER TABLE `cup_board_shelf`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_attachments`
--
ALTER TABLE `employee_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_departments`
--
ALTER TABLE `employee_departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_designations`
--
ALTER TABLE `employee_designations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_guardians`
--
ALTER TABLE `employee_guardians`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_informations`
--
ALTER TABLE `employee_informations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_leave_entitlements`
--
ALTER TABLE `employee_leave_entitlements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_leave_structures`
--
ALTER TABLE `employee_leave_structures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_leave_structure_type`
--
ALTER TABLE `employee_leave_structure_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_leave_types`
--
ALTER TABLE `employee_leave_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fees_collection`
--
ALTER TABLE `fees_collection`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fees_discount`
--
ALTER TABLE `fees_discount`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fees_invoices`
--
ALTER TABLE `fees_invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fees_item`
--
ALTER TABLE `fees_item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fee_type`
--
ALTER TABLE `fee_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `institute_language`
--
ALTER TABLE `institute_language`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invoice_payment`
--
ALTER TABLE `invoice_payment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issue_book`
--
ALTER TABLE `issue_book`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;
--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_extra`
--
ALTER TABLE `payment_extra`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `room_category`
--
ALTER TABLE `room_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `room_master`
--
ALTER TABLE `room_master`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `schedule_log`
--
ALTER TABLE `schedule_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `setting_campus`
--
ALTER TABLE `setting_campus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `setting_city`
--
ALTER TABLE `setting_city`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `setting_country`
--
ALTER TABLE `setting_country`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `setting_institute`
--
ALTER TABLE `setting_institute`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `setting_inst_prop`
--
ALTER TABLE `setting_inst_prop`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `setting_menus`
--
ALTER TABLE `setting_menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `setting_modules`
--
ALTER TABLE `setting_modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `setting_state`
--
ALTER TABLE `setting_state`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_batch`
--
ALTER TABLE `sms_batch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sms_credit`
--
ALTER TABLE `sms_credit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sms_institution_getway`
--
ALTER TABLE `sms_institution_getway`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sms_log`
--
ALTER TABLE `sms_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_message`
--
ALTER TABLE `sms_message`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_status`
--
ALTER TABLE `sms_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_template`
--
ALTER TABLE `sms_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `student_attachments`
--
ALTER TABLE `student_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_enrollment_history`
--
ALTER TABLE `student_enrollment_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_grades`
--
ALTER TABLE `student_grades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_guardians`
--
ALTER TABLE `student_guardians`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_informations`
--
ALTER TABLE `student_informations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_marks`
--
ALTER TABLE `student_marks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_parents`
--
ALTER TABLE `student_parents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `academics_assessments`
--
ALTER TABLE `academics_assessments`
  ADD CONSTRAINT `academics_assessments_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `academics_grades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `academics_assessments_grading_category_id_foreign` FOREIGN KEY (`grading_category_id`) REFERENCES `academics_grade_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `academics_grades`
--
ALTER TABLE `academics_grades`
  ADD CONSTRAINT `academics_grades_grade_scale_id_foreign` FOREIGN KEY (`grade_scale_id`) REFERENCES `academics_grade_scales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `academics_grade_details`
--
ALTER TABLE `academics_grade_details`
  ADD CONSTRAINT `academics_grade_details_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `academics_grades` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `academics_level`
--
ALTER TABLE `academics_level`
  ADD CONSTRAINT `academics_level_academics_year_id_foreign` FOREIGN KEY (`academics_year_id`) REFERENCES `academics_year` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `academics_syllabus`
--
ALTER TABLE `academics_syllabus`
  ADD CONSTRAINT `academics_syllabus_academic_level_foreign` FOREIGN KEY (`academic_level`) REFERENCES `academics_level` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `academics_syllabus_academic_year_foreign` FOREIGN KEY (`academic_year`) REFERENCES `academics_year` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `academics_syllabus_batch_foreign` FOREIGN KEY (`batch`) REFERENCES `batch` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `academics_syllabus_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `setting_campus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `academics_syllabus_institute_id_foreign` FOREIGN KEY (`institute_id`) REFERENCES `setting_institute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `academics_syllabus_section_foreign` FOREIGN KEY (`section`) REFERENCES `section` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `academics_year`
--
ALTER TABLE `academics_year`
  ADD CONSTRAINT `academics_year_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `setting_campus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `academics_year_institute_id_foreign` FOREIGN KEY (`institute_id`) REFERENCES `setting_institute` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `setting_city` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `setting_country` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `addresses_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `setting_state` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_address`
--
ALTER TABLE `applicant_address`
  ADD CONSTRAINT `applicant_address_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicant_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_address_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `setting_city` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_address_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `setting_country` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_address_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `setting_state` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_document`
--
ALTER TABLE `applicant_document`
  ADD CONSTRAINT `applicant_document_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicant_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_enrollment`
--
ALTER TABLE `applicant_enrollment`
  ADD CONSTRAINT `applicant_enrollment_academic_level_foreign` FOREIGN KEY (`academic_level`) REFERENCES `academics_level` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_enrollment_academic_year_foreign` FOREIGN KEY (`academic_year`) REFERENCES `academics_year` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_enrollment_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicant_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_enrollment_batch_foreign` FOREIGN KEY (`batch`) REFERENCES `batch` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_exam_settings`
--
ALTER TABLE `applicant_exam_settings`
  ADD CONSTRAINT `applicant_fees_settings_academic_level_foreign` FOREIGN KEY (`academic_level`) REFERENCES `academics_level` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_fees_settings_academic_year_foreign` FOREIGN KEY (`academic_year`) REFERENCES `academics_year` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_fees_settings_batch_foreign` FOREIGN KEY (`batch`) REFERENCES `batch` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_fees_settings_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `setting_campus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_fees_settings_institute_id_foreign` FOREIGN KEY (`institute_id`) REFERENCES `setting_institute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_fees_settings_section_foreign` FOREIGN KEY (`section`) REFERENCES `section` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_fees`
--
ALTER TABLE `applicant_fees`
  ADD CONSTRAINT `applicant_fees_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicant_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_fees_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `setting_campus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_fees_institute_id_foreign` FOREIGN KEY (`institute_id`) REFERENCES `setting_institute` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_grades`
--
ALTER TABLE `applicant_grades`
  ADD CONSTRAINT `applicant_grades_academic_level_foreign` FOREIGN KEY (`academic_level`) REFERENCES `academics_level` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_grades_academic_year_foreign` FOREIGN KEY (`academic_year`) REFERENCES `academics_year` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_grades_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicant_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_grades_batch_foreign` FOREIGN KEY (`batch`) REFERENCES `batch` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_grades_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `setting_campus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_grades_institute_id_foreign` FOREIGN KEY (`institute_id`) REFERENCES `setting_institute` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_information`
--
ALTER TABLE `applicant_information`
  ADD CONSTRAINT `applicant_information_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicant_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_merit_batch`
--
ALTER TABLE `applicant_merit_batch`
  ADD CONSTRAINT `applicant_merit_batch_academic_batch_foreign` FOREIGN KEY (`batch`) REFERENCES `batch` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_merit_batch_academic_level_foreign` FOREIGN KEY (`academic_level`) REFERENCES `academics_level` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_merit_batch_academic_year_foreign` FOREIGN KEY (`academic_year`) REFERENCES `academics_year` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_merit_batch_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `setting_campus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_merit_batch_institute_id_foreign` FOREIGN KEY (`institute_id`) REFERENCES `setting_institute` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_results`
--
ALTER TABLE `applicant_results`
  ADD CONSTRAINT `applicant_results_academic_level_foreign` FOREIGN KEY (`academic_level`) REFERENCES `academics_level` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_results_academic_year_foreign` FOREIGN KEY (`academic_year`) REFERENCES `academics_year` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_results_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicant_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_results_batch_foreign` FOREIGN KEY (`batch`) REFERENCES `batch` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_results_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `setting_campus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_results_institute_id_foreign` FOREIGN KEY (`institute_id`) REFERENCES `setting_institute` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicant_user`
--
ALTER TABLE `applicant_user`
  ADD CONSTRAINT `applicant_user_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `setting_campus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_user_institute_id_foreign` FOREIGN KEY (`institute_id`) REFERENCES `setting_institute` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `batch`
--
ALTER TABLE `batch`
  ADD CONSTRAINT `batch_academics_level_id_foreign` FOREIGN KEY (`academics_level_id`) REFERENCES `academics_level` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `batch_academics_year_id_foreign` FOREIGN KEY (`academics_year_id`) REFERENCES `academics_year` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `batch_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `academics_division` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
