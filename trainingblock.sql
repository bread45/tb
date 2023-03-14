-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2023 at 02:24 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tb_source_review`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `locations` text DEFAULT NULL,
  `amount` double(15,2) DEFAULT NULL,
  `click_count` int(11) DEFAULT NULL,
  `view` varchar(50) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `pageview` varchar(50) DEFAULT NULL,
  `typeview` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advertisements_details`
--

CREATE TABLE `advertisements_details` (
  `id` int(11) NOT NULL,
  `advertisement_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `price` double(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `athlete_help_requests`
--

CREATE TABLE `athlete_help_requests` (
  `id` int(11) NOT NULL,
  `athelete_first_name` varchar(250) NOT NULL,
  `athelete_last_name` varchar(250) NOT NULL,
  `athelete_email` varchar(100) NOT NULL,
  `athelete_phone` varchar(100) DEFAULT NULL,
  `athelete_subject` varchar(250) DEFAULT NULL,
  `email_content` text DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `blog_category_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `meta_title` varchar(191) DEFAULT NULL,
  `meta_keywords` varchar(191) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `sub_title` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `order_by` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `is_featured` enum('0','1') NOT NULL DEFAULT '0',
  `created_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `image` varchar(191) DEFAULT NULL,
  `is_popular` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `sub_title_text` varchar(191) DEFAULT NULL,
  `meta_title` varchar(191) DEFAULT NULL,
  `meta_keywords` varchar(191) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `banner_image` varchar(191) DEFAULT NULL,
  `order_by` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `is_read` int(11) NOT NULL DEFAULT 0,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `coupon_code` varchar(100) NOT NULL,
  `unit` varchar(100) NOT NULL COMMENT '1 => Percentage  2=> Dollars ',
  `percentage` varchar(50) NOT NULL,
  `fromdate` date NOT NULL,
  `todate` date NOT NULL,
  `status` int(2) NOT NULL COMMENT '1=>Active,2=>Inactive',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_comments`
--

CREATE TABLE `event_comments` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `comments` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_count`
--

CREATE TABLE `event_count` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `likes` int(2) NOT NULL,
  `dislike` int(2) NOT NULL,
  `saved` int(2) NOT NULL,
  `unsaved` int(2) NOT NULL,
  `comments` text DEFAULT NULL,
  `like_count` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_registration`
--

CREATE TABLE `event_registration` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `attender_id` int(11) NOT NULL,
  `attender_first` varchar(250) NOT NULL,
  `attender_last` varchar(250) DEFAULT NULL,
  `attender_email` varchar(250) NOT NULL,
  `is_payment` int(11) DEFAULT NULL,
  `rsvp` varchar(255) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `original_price` int(11) DEFAULT NULL,
  `stripe_payment_id` text DEFAULT NULL,
  `stripe_response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event_type` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `explore_menu_items`
--

CREATE TABLE `explore_menu_items` (
  `id` int(11) NOT NULL,
  `city` varchar(250) NOT NULL,
  `state` varchar(250) NOT NULL,
  `created_at` varchar(250) NOT NULL,
  `updated_at` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `friend_id` int(11) DEFAULT NULL,
  `accept` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `front_users`
--

CREATE TABLE `front_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_name` varchar(191) DEFAULT NULL,
  `service_location` varchar(191) DEFAULT NULL,
  `coverage_area` varchar(191) DEFAULT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `trainer_service_id` text DEFAULT NULL,
  `day1` varchar(255) DEFAULT NULL COMMENT 'Monday',
  `day2` varchar(255) DEFAULT NULL COMMENT 'Tuesday',
  `day3` varchar(255) DEFAULT NULL COMMENT 'Wednesday',
  `day4` varchar(255) DEFAULT NULL COMMENT 'Thursday',
  `day5` varchar(255) DEFAULT NULL COMMENT 'Friday',
  `day6` varchar(255) DEFAULT NULL COMMENT 'Saturday',
  `day7` varchar(255) DEFAULT NULL COMMENT 'Sunday',
  `day1_check` int(2) NOT NULL,
  `day2_check` int(2) NOT NULL,
  `day3_check` int(2) NOT NULL,
  `day4_check` int(2) NOT NULL,
  `day5_check` int(2) NOT NULL,
  `day6_check` int(2) NOT NULL,
  `day7_check` int(2) NOT NULL,
  `address_1` varchar(255) DEFAULT NULL,
  `address1_virtual` int(2) NOT NULL,
  `address_2` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `state_code` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `zip_code` varchar(50) DEFAULT NULL,
  `map_latitude` varchar(100) NOT NULL,
  `map_longitude` varchar(100) NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `user_role` enum('customer','trainer') DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `headline` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `is_feature` int(11) NOT NULL DEFAULT 0,
  `is_sponsored` int(1) NOT NULL DEFAULT 0,
  `is_verfied` int(11) NOT NULL DEFAULT 0,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(191) DEFAULT NULL,
  `spot_description` text DEFAULT NULL,
  `affiliate_id` varchar(191) NOT NULL,
  `referred_by` bigint(20) DEFAULT NULL,
  `referral_wallet` int(11) DEFAULT NULL,
  `stripe_customer_id` varchar(191) DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `confirmation_code` varchar(255) DEFAULT NULL,
  `google_id` varchar(250) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `is_subscription` int(2) NOT NULL COMMENT '0=>Waived,1=>Regular',
  `is_payment` int(2) NOT NULL COMMENT '1 => Active, 0 => Inactive',
  `ppc_user` tinyint(1) NOT NULL COMMENT '0 => No , 1 => Yes',
  `ppc_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 => active, 0 => inactive',
  `trainer_email_contact` tinyint(1) NOT NULL COMMENT '0 => Yes  , 1 => No ',
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `attr_key` varchar(191) NOT NULL,
  `attr_value` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_users`
--

CREATE TABLE `group_users` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invite_friend`
--

CREATE TABLE `invite_friend` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keyword_explore`
--

CREATE TABLE `keyword_explore` (
  `id` int(11) NOT NULL,
  `keywords` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `keyword` varchar(191) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nextsections`
--

CREATE TABLE `nextsections` (
  `id` int(11) NOT NULL,
  `section_title` varchar(250) NOT NULL,
  `slider_title` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `next_steps`
--

CREATE TABLE `next_steps` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `icon` mediumblob NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `button_1` varchar(250) NOT NULL,
  `button_1_link` text NOT NULL,
  `button_2` varchar(250) NOT NULL,
  `modal_title` text NOT NULL,
  `modal_content` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_user_id` int(10) UNSIGNED NOT NULL,
  `to_user_id` int(10) UNSIGNED NOT NULL,
  `notification_type` varchar(191) DEFAULT NULL,
  `title` varchar(191) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `url` varchar(191) DEFAULT NULL,
  `is_read` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `secret` varchar(100) NOT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` text NOT NULL,
  `apartment_no` varchar(255) DEFAULT NULL,
  `amount` double(15,2) NOT NULL,
  `admin_fees` double(15,2) NOT NULL,
  `ref_discount` double(15,2) DEFAULT NULL,
  `service_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `status` int(50) DEFAULT NULL,
  `service_date` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `service_time` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `zip_code` varchar(50) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `customer_id` varchar(200) DEFAULT NULL,
  `stripe_payment_id` varchar(255) DEFAULT NULL,
  `stripe_refund_id` varchar(255) DEFAULT NULL,
  `refund_amount` double(15,2) DEFAULT NULL,
  `stripe_subscription_id` varchar(191) DEFAULT NULL,
  `plan_type` varchar(255) DEFAULT NULL,
  `days` int(11) NOT NULL,
  `subscription_status` varchar(255) DEFAULT NULL,
  `json_response` text DEFAULT NULL,
  `order_status` varchar(255) DEFAULT NULL,
  `order_note` text DEFAULT NULL,
  `chat_message` int(2) NOT NULL COMMENT '1 => Enable, 2 => disable',
  `stripeToken` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_request`
--

CREATE TABLE `order_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `reuest_date_time` datetime NOT NULL,
  `customer_id` varchar(200) DEFAULT NULL,
  `comments` text NOT NULL,
  `status` int(3) NOT NULL COMMENT '1=>New,2=>Accept,3=>Decline',
  `stripeToken` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_manager`
--

CREATE TABLE `permission_manager` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `route_id` int(10) UNSIGNED NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ppc_payment_history`
--

CREATE TABLE `ppc_payment_history` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) DEFAULT NULL,
  `bill_date` varchar(250) DEFAULT NULL,
  `amount` varchar(250) DEFAULT NULL,
  `stripe_payment_id` text DEFAULT NULL,
  `stripe_response` text DEFAULT NULL,
  `payment_status` tinyint(1) NOT NULL COMMENT '1 => success, 0 => failed',
  `created_at` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ppc_user_clicks`
--

CREATE TABLE `ppc_user_clicks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `user_type` tinyint(1) DEFAULT NULL COMMENT '0 => anonymous , 1 => authorized',
  `click_element` text DEFAULT NULL,
  `ip` text DEFAULT NULL,
  `device` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `created_at` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_locations`
--

CREATE TABLE `provider_locations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_orders`
--

CREATE TABLE `provider_orders` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `plan_type` varchar(100) NOT NULL,
  `amount` double(15,2) NOT NULL,
  `start_date` date NOT NULL,
  `stripe_subscription_id` varchar(150) NOT NULL,
  `subscription_status` varchar(100) NOT NULL,
  `json_response` text NOT NULL,
  `stripeToken` varchar(100) NOT NULL,
  `status` int(2) NOT NULL,
  `cancel_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_scheduling`
--

CREATE TABLE `provider_scheduling` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `day1` varchar(255) NOT NULL,
  `day2` varchar(255) NOT NULL,
  `day3` varchar(255) NOT NULL,
  `day4` varchar(255) NOT NULL,
  `day5` varchar(255) NOT NULL,
  `day6` varchar(255) NOT NULL,
  `day7` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_scheduling_date`
--

CREATE TABLE `provider_scheduling_date` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `day` varchar(255) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(255) NOT NULL,
  `created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_scheduling_service`
--

CREATE TABLE `provider_scheduling_service` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `day1` varchar(255) NOT NULL,
  `day2` varchar(255) NOT NULL,
  `day3` varchar(255) NOT NULL,
  `day4` varchar(255) NOT NULL,
  `day5` varchar(255) NOT NULL,
  `day6` varchar(255) NOT NULL,
  `day7` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_scheduling_service_date`
--

CREATE TABLE `provider_scheduling_service_date` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `day` varchar(255) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(255) NOT NULL,
  `created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_scheduling_temp`
--

CREATE TABLE `provider_scheduling_temp` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `day1` varchar(255) NOT NULL,
  `day2` varchar(255) NOT NULL,
  `day3` varchar(255) NOT NULL,
  `day4` varchar(255) NOT NULL,
  `day5` varchar(255) NOT NULL,
  `day6` varchar(255) NOT NULL,
  `day7` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_service_book`
--

CREATE TABLE `provider_service_book` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `rating` int(2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recommended_providers`
--

CREATE TABLE `recommended_providers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `role` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_bonus`
--

CREATE TABLE `referral_bonus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referred_by` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `discount` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE `resource` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `subtitle` varchar(200) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `format` varchar(100) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `format_name` varchar(100) DEFAULT NULL,
  `image_name` varchar(200) NOT NULL,
  `big_image_name` varchar(200) NOT NULL,
  `tags` varchar(150) DEFAULT NULL,
  `like_count` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `status` int(3) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource_category`
--

CREATE TABLE `resource_category` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` int(2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource_comments`
--

CREATE TABLE `resource_comments` (
  `id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `comments` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource_count`
--

CREATE TABLE `resource_count` (
  `id` int(11) NOT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `likes` int(2) NOT NULL,
  `dislike` int(2) NOT NULL,
  `saved` int(2) NOT NULL,
  `unsaved` int(2) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `white_icon` varchar(255) NOT NULL,
  `home_icon` varchar(150) NOT NULL,
  `home_color` varchar(150) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `postal_code` varchar(100) NOT NULL,
  `country_code` varchar(30) NOT NULL DEFAULT 'US'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stripe_accounts`
--

CREATE TABLE `stripe_accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `access_token` varchar(100) NOT NULL,
  `refresh_token` varchar(100) NOT NULL,
  `token_type` varchar(100) NOT NULL,
  `stripe_publishable_key` varchar(100) NOT NULL,
  `stripe_user_id` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribe`
--

CREATE TABLE `subscribe` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptionplan`
--

CREATE TABLE `subscriptionplan` (
  `id` int(10) UNSIGNED NOT NULL,
  `subcription_plan` varchar(191) NOT NULL,
  `price` varchar(30) DEFAULT NULL,
  `product_id` varchar(191) NOT NULL,
  `plan_id` varchar(191) NOT NULL,
  `free_trial_months` int(4) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptionplan`
--

INSERT INTO `subscriptionplan` (`id`, `subcription_plan`, `price`, `product_id`, `plan_id`, `free_trial_months`, `status`, `created_at`, `updated_at`) VALUES
(1, 'monthly', '101', 'prod_NV57vLmd8yam4k', 'plan_NV57ZCvBRjAdwh', 0, 'inactive', '2022-01-15 19:24:55', '2023-03-10 06:51:52'),
(2, 'yearly', '1001', 'prod_NV58oWZxwz7Znu', 'plan_NV58p8zyK4s5px', 0, 'inactive', '2022-01-15 19:31:21', '2023-03-10 06:51:52');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `user_image` text NOT NULL,
  `description` text DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE `tips` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainer_email_requests`
--

CREATE TABLE `trainer_email_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `receiver_email` varchar(250) NOT NULL,
  `athelete_first_name` varchar(250) NOT NULL,
  `athelete_last_name` varchar(250) NOT NULL,
  `athelete_email` varchar(100) NOT NULL,
  `athelete_phone` varchar(100) NOT NULL,
  `athelete_subject` varchar(250) DEFAULT NULL,
  `email_content` text DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainer_events`
--

CREATE TABLE `trainer_events` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `category` varchar(250) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `format` varchar(50) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `venue` varchar(250) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `start_date` varchar(250) DEFAULT NULL,
  `end_date` varchar(250) DEFAULT NULL,
  `start_time` varchar(50) DEFAULT NULL,
  `end_time` varchar(50) DEFAULT NULL,
  `recurrence_id` int(11) DEFAULT NULL COMMENT 'Recurring Event ID ',
  `accept_promo` tinyint(1) NOT NULL COMMENT '1=>Promo Code Accepted, 0=> Not Accepted',
  `description` text DEFAULT NULL,
  `members_allowed` int(11) DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL COMMENT '1=>Yes, 0=> No',
  `recurring_type` varchar(150) DEFAULT NULL,
  `recurring_day` text DEFAULT NULL,
  `recurring_end` varchar(250) DEFAULT NULL,
  `recurring_end_date` varchar(250) DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 => Inactive  , 1 => Active',
  `like_count` int(11) DEFAULT NULL,
  `comment_count` int(11) DEFAULT NULL,
  `event_start_datetime` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainer_photo`
--

CREATE TABLE `trainer_photo` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `position` int(11) NOT NULL DEFAULT -1,
  `is_featured` int(1) NOT NULL DEFAULT 0,
  `is_video` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainer_resource_photo`
--

CREATE TABLE `trainer_resource_photo` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `position` int(11) NOT NULL DEFAULT -1,
  `is_featured` int(1) NOT NULL DEFAULT 0,
  `is_video` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainer_services`
--

CREATE TABLE `trainer_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `is_featured` varchar(255) NOT NULL,
  `is_recurring` varchar(255) DEFAULT NULL,
  `max_bookings` int(11) DEFAULT NULL,
  `price` varchar(191) DEFAULT NULL,
  `price_weekly` varchar(191) DEFAULT NULL,
  `price_monthly` varchar(191) DEFAULT NULL,
  `format` varchar(191) NOT NULL,
  `message` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `fromDay` varchar(100) DEFAULT NULL COMMENT 'Hour of Operation (From Date)',
  `toDay` varchar(100) DEFAULT NULL COMMENT 'Hour of Operation (To Date)',
  `fromTime` varchar(100) DEFAULT NULL COMMENT 'Hour of Operation (From Time)',
  `toTime` varchar(100) DEFAULT NULL COMMENT 'Hour of Operation (To Time)',
  `location_id` int(11) DEFAULT NULL,
  `product_id` varchar(191) DEFAULT NULL,
  `weekly_plan_id` varchar(191) DEFAULT NULL,
  `monthly_plan_id` varchar(191) DEFAULT NULL,
  `yearly_plan_id` varchar(191) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `duration_mins` varchar(55) NOT NULL,
  `buffer_time` varchar(50) DEFAULT NULL,
  `promo_code` varchar(50) DEFAULT NULL,
  `book_type` int(2) DEFAULT NULL COMMENT '1=>Automatic Booking,2=>Request to Book',
  `desc` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `lockout_time` int(11) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_number` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `image` varchar(191) DEFAULT NULL,
  `login_ip` varchar(191) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `username`, `email`, `lockout_time`, `email_verified_at`, `phone_number`, `password`, `address`, `status`, `image`, `login_ip`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 1, 'Training', 'admin', 'trainingblockusa@gmail.com', 1, NULL, '(407) 864-2606', '$2y$10$99R3k7dB5cBf.GwMLM21xOd0u0qPwiGWfZt8HZph2Roul5kjoy8YO', '12620 Beach Blvd. Suite #3,\r\nJacksonville, Florida,\r\nUnited States, 32246', 'active', '1581405783.jpg', NULL, 'PCG7TiCEp9712gtyQKgzyTwVDKuWCq78GjTag02wHY7JCXwAFh2CaX507G9A', NULL, '2020-04-17 20:04:07');

-- --------------------------------------------------------

--
-- Table structure for table `us_cities`
--

CREATE TABLE `us_cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_state` int(10) UNSIGNED NOT NULL,
  `city_title` varchar(100) NOT NULL,
  `city_county` varchar(100) DEFAULT NULL,
  `city_latitude` varchar(100) DEFAULT NULL,
  `city_longitude` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertisements_details`
--
ALTER TABLE `advertisements_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `athlete_help_requests`
--
ALTER TABLE `athlete_help_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogs_blog_category_id_foreign` (`blog_category_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_comments`
--
ALTER TABLE `event_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_count`
--
ALTER TABLE `event_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_registration`
--
ALTER TABLE `event_registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `explore_menu_items`
--
ALTER TABLE `explore_menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_users`
--
ALTER TABLE `front_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `front_users_email_unique` (`email`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_users`
--
ALTER TABLE `group_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invite_friend`
--
ALTER TABLE `invite_friend`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keyword_explore`
--
ALTER TABLE `keyword_explore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nextsections`
--
ALTER TABLE `nextsections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `next_steps`
--
ALTER TABLE `next_steps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_from_user_id_foreign` (`from_user_id`),
  ADD KEY `notifications_to_user_id_foreign` (`to_user_id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_request`
--
ALTER TABLE `order_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permission_manager`
--
ALTER TABLE `permission_manager`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_manager_role_id_foreign` (`role_id`),
  ADD KEY `permission_manager_route_id_foreign` (`route_id`);

--
-- Indexes for table `ppc_payment_history`
--
ALTER TABLE `ppc_payment_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppc_user_clicks`
--
ALTER TABLE `ppc_user_clicks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_locations`
--
ALTER TABLE `provider_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_orders`
--
ALTER TABLE `provider_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_scheduling`
--
ALTER TABLE `provider_scheduling`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_scheduling_date`
--
ALTER TABLE `provider_scheduling_date`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_scheduling_service`
--
ALTER TABLE `provider_scheduling_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_scheduling_service_date`
--
ALTER TABLE `provider_scheduling_service_date`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_scheduling_temp`
--
ALTER TABLE `provider_scheduling_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_service_book`
--
ALTER TABLE `provider_service_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recommended_providers`
--
ALTER TABLE `recommended_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource_category`
--
ALTER TABLE `resource_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource_comments`
--
ALTER TABLE `resource_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource_count`
--
ALTER TABLE `resource_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stripe_accounts`
--
ALTER TABLE `stripe_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribe`
--
ALTER TABLE `subscribe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptionplan`
--
ALTER TABLE `subscriptionplan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainer_email_requests`
--
ALTER TABLE `trainer_email_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainer_events`
--
ALTER TABLE `trainer_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainer_photo`
--
ALTER TABLE `trainer_photo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainer_resource_photo`
--
ALTER TABLE `trainer_resource_photo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainer_services`
--
ALTER TABLE `trainer_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `us_cities`
--
ALTER TABLE `us_cities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advertisements_details`
--
ALTER TABLE `advertisements_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `athlete_help_requests`
--
ALTER TABLE `athlete_help_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_comments`
--
ALTER TABLE `event_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_count`
--
ALTER TABLE `event_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_registration`
--
ALTER TABLE `event_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `explore_menu_items`
--
ALTER TABLE `explore_menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_users`
--
ALTER TABLE `front_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_users`
--
ALTER TABLE `group_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invite_friend`
--
ALTER TABLE `invite_friend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keyword_explore`
--
ALTER TABLE `keyword_explore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nextsections`
--
ALTER TABLE `nextsections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `next_steps`
--
ALTER TABLE `next_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_request`
--
ALTER TABLE `order_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission_manager`
--
ALTER TABLE `permission_manager`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppc_payment_history`
--
ALTER TABLE `ppc_payment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppc_user_clicks`
--
ALTER TABLE `ppc_user_clicks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_locations`
--
ALTER TABLE `provider_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_orders`
--
ALTER TABLE `provider_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_scheduling`
--
ALTER TABLE `provider_scheduling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_scheduling_date`
--
ALTER TABLE `provider_scheduling_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_scheduling_service`
--
ALTER TABLE `provider_scheduling_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_scheduling_service_date`
--
ALTER TABLE `provider_scheduling_service_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_scheduling_temp`
--
ALTER TABLE `provider_scheduling_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_service_book`
--
ALTER TABLE `provider_service_book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recommended_providers`
--
ALTER TABLE `recommended_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource`
--
ALTER TABLE `resource`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource_category`
--
ALTER TABLE `resource_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource_comments`
--
ALTER TABLE `resource_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource_count`
--
ALTER TABLE `resource_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stripe_accounts`
--
ALTER TABLE `stripe_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribe`
--
ALTER TABLE `subscribe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptionplan`
--
ALTER TABLE `subscriptionplan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tips`
--
ALTER TABLE `tips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_email_requests`
--
ALTER TABLE `trainer_email_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_events`
--
ALTER TABLE `trainer_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_photo`
--
ALTER TABLE `trainer_photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_resource_photo`
--
ALTER TABLE `trainer_resource_photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_services`
--
ALTER TABLE `trainer_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `us_cities`
--
ALTER TABLE `us_cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
