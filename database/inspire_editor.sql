-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 03, 2026 at 08:05 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inspire_editor`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_02_121929_create_projects_table', 2),
(5, '2026_04_02_121946_create_project_files_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Untitled Project',
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_slug_unique` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Week 5', 'week-5-tutorial', '2026-04-02 11:37:57', '2026-04-02 11:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `project_files`
--

DROP TABLE IF EXISTS `project_files`;
CREATE TABLE IF NOT EXISTS `project_files` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'html',
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_files_project_id_filename_unique` (`project_id`,`filename`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_files`
--

INSERT INTO `project_files` (`id`, `project_id`, `filename`, `type`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'index.html', 'html', '<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n  <link rel=\"stylesheet\" href=\"style.css\">\n</head>\n<body>   \n  <nav>\n    <a href=\"index.html\" class=\"brand\"> SOJI CAR DEALER</a>\n    <div class=\"nav-links\">\n      <a href=\"index.html\" class=\"active\"> Home </a>  \n      <a href=\"about-us.html\" class=\"\"> About Us </a>     \n      <a href=\"contact-us.html\" class=\"\"> Contact Us </a>        \n   </div>    \n  </nav>\n  \n  <header>\n    <h1> WELCOME TO SOJI CAR DEALER</h1>\n    <p>We provides Strong And Affordable Vehicles </p>\n  </header>\n  \n  <main>\n    \n   <div class=\"container row\">\n    <div class=\"col-lg-4 col-md-6 col box-1\">\n      <img src=\"data:image/jpeg;base64,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\"> \n     </div>\n    <div class=\"col-lg-4 col-md-6 col box-2\">\n     <img src=\"data:image/jpeg;base64,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\">\n     </div>\n     \n     <div class=\"col-lg-4 col-md-6 col box-1\">\n     <img src=\"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQF46s4EkYdf1xpjK6QYjLYT9iIQmzTm_8KQ&s\"> \n     </div> \n     \n     <div class=\"col-lg-4 col-md-6 col box-2\">\n       <img src=\"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSmBafsP7aVmrkyGVXisU7S4HJQJkA8oNFO8Q&s\" />\n     </div>\n     \n      <div class=\"col-lg-4 col-md-6 col box-2\">\n       <img src=\"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTrigQRES3M6xKhgJjysubrecr1WIq0qUlATw&s\" />\n     </div>\n     \n     <div class=\"col-lg-4 col-md-6 col box-2\">\n       <img src=\"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRg36fRyfblWnG9yoYU4E5L0HVHbqBiFNZ5Uw&s\" />\n     </div>\n     \n     \n</div>\n \n    \n  </main>\n  <footer>\n    <a class=\"brand\">&copy; Copyright: 2026 - <span class=\"bolden text-uppercase\"> Soji Motor Dealer </span></a>\n    <div class=\"footlinks\">\n      <a href=\"#\"><b> Contact Address :</b> ALong Sobi Barack Ilorin </a>\n      <a href=\"#\"><b> Telephone :</b> 0803 0001 111 </a>\n      <a href=\"#\"><b> Email :</b> 0803 0001 111 </a>\n    </div>\n  </footer>\n</body>\n</html>', '2026-04-02 11:37:57', '2026-04-03 18:53:29'),
(2, 1, 'style.css', 'css', 'body {\n  font-family: \'Segoe UI\', sans-serif;\n  background: #fff;\n  color: #000;  \n}\nnav {\n  display:flex;\n  flex-direction: row;\n  padding:0px 32px;\n  justify-content:space-between;\n  background:linear-gradient(135deg, green,blue,pink); \n  height:auto; /*border-bottom:1px solid #ccc;*/ \n  }\n\nnav .brand{\n  color:#fff; font-weight:bold; text-decoration:none;\n  font-size:1.5rem; padding: 20px; \n}\n\nnav .nav-links {\n  padding:20px; \n}\n\nnav .nav-links a{\n  color:#fff; padding:20px; font-size:1.2rem; text-decoration:none;\n}\n\nnav .nav-links a.active{\n  color:#fff;  background:rgba(120,90,20,0.5);\n}\n\nheader{\n  display:flex; flex-direction:column; \n  height:30vh; background:linear-gradient(240deg, pink,blue,green);\n  align-items:center; padding:40px 0;\n}\nheader h1, header p {\n  color:#fff; \n}\n /* =========================================\n   MINI CSS GRID FRAMEWORK (BOOTSTRAP-LIKE)\n   Author: Custom Build\n   ========================================= */\n\n/* RESET */\n* {\n  box-sizing: border-box;\n  margin: 0;\n  padding: 0;\n}\n\n/* CONTAINER */\n.container {\n  width: 90%;\n  margin: 20px auto;\n}\n\n.container-fluid {\n  width: 100%;\n  padding: 0 15px;\n}\n\n/* ROW */\n.row {\n  display: grid;\n  grid-template-columns: repeat(12, 1fr);\n  grid-auto-rows: minmax(150px, auto);\n  gap: 15px;\n  grid-auto-flow: dense;\n}\n\n/* DEFAULT COLUMN (MOBILE FIRST) */\n[class*=\"col-\"] {\n  grid-column: span 12;\n}\n\n/* COLUMN SPANS */\n.col-1 { grid-column: span 1; }\n.col-2 { grid-column: span 2; }\n.col-3 { grid-column: span 3; }\n.col-4 { grid-column: span 4; }\n.col-5 { grid-column: span 5; }\n.col-6 { grid-column: span 6; }\n.col-7 { grid-column: span 7; }\n.col-8 { grid-column: span 8; }\n.col-9 { grid-column: span 9; }\n.col-10 { grid-column: span 10; }\n.col-11 { grid-column: span 11; }\n.col-12 { grid-column: span 12; }\n\n/* ROW SPANS */\n.row-1 { grid-row: span 1; }\n.row-2 { grid-row: span 2; }\n.row-3 { grid-row: span 3; }\n.row-4 { grid-row: span 4; }\n.row-5 { grid-row: span 5; }\n.row-6 { grid-row: span 6; }\n\n/* SMALL DEVICES (≥576px) */\n@media (min-width: 576px) {\n  .col-sm-1 { grid-column: span 1; }\n  .col-sm-2 { grid-column: span 2; }\n  .col-sm-3 { grid-column: span 3; }\n  .col-sm-4 { grid-column: span 4; }\n  .col-sm-5 { grid-column: span 5; }\n  .col-sm-6 { grid-column: span 6; }\n  .col-sm-7 { grid-column: span 7; }\n  .col-sm-8 { grid-column: span 8; }\n  .col-sm-9 { grid-column: span 9; }\n  .col-sm-10 { grid-column: span 10; }\n  .col-sm-11 { grid-column: span 11; }\n  .col-sm-12 { grid-column: span 12; }\n\n  .row-sm-1 { grid-row: span 1; }\n  .row-sm-2 { grid-row: span 2; }\n  .row-sm-3 { grid-row: span 3; }\n}\n\n/* MEDIUM DEVICES (≥768px) */\n@media (min-width: 768px) {\n  .col-md-1 { grid-column: span 1; }\n  .col-md-2 { grid-column: span 2; }\n  .col-md-3 { grid-column: span 3; }\n  .col-md-4 { grid-column: span 4; }\n  .col-md-5 { grid-column: span 5; }\n  .col-md-6 { grid-column: span 6; }\n  .col-md-7 { grid-column: span 7; }\n  .col-md-8 { grid-column: span 8; }\n  .col-md-9 { grid-column: span 9; }\n  .col-md-10 { grid-column: span 10; }\n  .col-md-11 { grid-column: span 11; }\n  .col-md-12 { grid-column: span 12; }\n\n  .row-md-1 { grid-row: span 1; }\n  .row-md-2 { grid-row: span 2; }\n  .row-md-3 { grid-row: span 3; }\n}\n\n/* LARGE DEVICES (≥992px) */\n@media (min-width: 992px) {\n  .col-lg-1 { grid-column: span 1; }\n  .col-lg-2 { grid-column: span 2; }\n  .col-lg-3 { grid-column: span 3; }\n  .col-lg-4 { grid-column: span 4; }\n  .col-lg-5 { grid-column: span 5; }\n  .col-lg-6 { grid-column: span 6; }\n  .col-lg-7 { grid-column: span 7; }\n  .col-lg-8 { grid-column: span 8; }\n  .col-lg-9 { grid-column: span 9; }\n  .col-lg-10 { grid-column: span 10; }\n  .col-lg-11 { grid-column: span 11; }\n  .col-lg-12 { grid-column: span 12; }\n\n  .row-lg-1 { grid-row: span 1; }\n  .row-lg-2 { grid-row: span 2; }\n  .row-lg-3 { grid-row: span 3; }\n}\n\n/* AUTO GRID (OPTIONAL) */\n.auto-grid {\n  display: grid;\n  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));\n  gap: 15px;\n}\n\n/* IMAGE HANDLING */\nimg {\n  width: 98%;\n  height: 98%;\n  object-fit: contain;\n}\n\n/* UTILITY BOXES (OPTIONAL) */\n.box-1 {\n  border: 1px solid #ccc;\n  padding: 10px; border-radius:15px;\n}\n\n.box-2 {\n  border: 1px solid #ccc;\n  padding: 10px; border-radius:15px;\n}\n\nfooter {\n  height:100px;\n  background:#000; color:#fff; \n  display:flex; \n  justify-content:space-between; \n  padding:20px; \n}\nfooter .brand{\n  font-size:18px; margin:25px 0;\n    justify-content:space-evenly; \n}\nfooter .footlinks{\n  display:flex;\n  flex-direction:column;\n  gap:15px;\n}\nfooter .footlinks a{\n  color:#ccc; \n  text-decoration:none; \n}\n.bolden {\n  font-weight:bold; \n} \n.text-uppercase {\n  text-transform:uppercase;\n}\n.', '2026-04-02 11:37:57', '2026-04-03 18:55:48'),
(3, 1, 'script.js', 'js', 'function greet() {\n  const name = prompt(\'What is your name?\');\n  if (name) {\n    document.querySelector(\'h1\').textContent = `Hello, ${name}! 🎉`;\n  }\n}', '2026-04-02 11:37:57', '2026-04-02 11:37:57'),
(4, 1, 'about-us.html', 'html', '<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n  <meta charset=\"UTF-8\">\n  <title>New Page</title>\n  <link rel=\"stylesheet\" href=\"style.css\">\n</head>\n<body>\n\n  <nav>\n    <a href=\"index.html\" class=\"brand\"> SOJI CAR DEALER</a>\n    <div class=\"nav-links\">\n      <a href=\"index.html\" class=\"\"> Home </a>  \n      <a href=\"about-us.html\" class=\"active\"> About Us </a>     \n      <a href=\"contact-us.html\" class=\"\"> Contact Us </a>        \n   </div>  \n  </nav> \n\n  <script src=\"script.js\"></script>\n</body>\n</html>', '2026-04-02 11:39:16', '2026-04-02 12:02:20'),
(5, 1, 'contact-us.html', 'html', '<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n  <meta charset=\"UTF-8\">\n  <title>New Page</title>\n  <link rel=\"stylesheet\" href=\"style.css\">\n</head>\n<body>\n\n  <h1>New Page</h1>\n\n  <script src=\"script.js\"></script>\n</body>\n</html>', '2026-04-02 12:17:48', '2026-04-02 12:17:48'),
(6, 2, 'index.html', 'html', '<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n</head>\n<body>   \n  <nav>\n    <a href=\"index.html\" class=\"brand\"> SOJI CAR DEALER</a>\n    <div class=\"nav-links\">\n      <a href=\"index.html\" class=\"active\"> Home </a>  \n      <a href=\"about-us.html\" class=\"\"> About Us </a>     \n      <a href=\"contact-us.html\" class=\"\"> Contact Us </a>        \n   </div>    \n  </nav>\n  \n  <header>\n    <h1> WELCOME TO SOJI CAR DEALER</h1>\n    <p>We provides Strong And Affordable Vehicles </p>\n  </header>\n  \n  <main>\n    <h1>\n      Vehicles \n    </h1>\n   <div class=\"container flex-row\">\n    <div class=\"item\">1</div>\n    <div class=\"item\">2</div>\n    <div class=\"item\">3</div>\n</div>\n\n<div class=\"container grid-basic\">\n  <div class=\"item\">1</div>\n  <div class=\"item\">2</div>\n  <div class=\"item\">3</div>\n</div>\n    \n  </main>\n\n</body>\n</html>', '2026-04-02 19:44:18', '2026-04-02 19:44:18'),
(7, 2, 'style.css', 'css', 'body {\n  font-family: \'Segoe UI\', sans-serif;\n  background: #fff;\n  color: #000;  \n}\nnav {\n  display:flex;\n  flex-direction: row;\n  padding:0px 32px;\n  justify-content:space-between;\n  background:linear-gradient(135deg, green,blue,pink); \n  height:auto; border-bottom:1px solid #ccc; \n  }\n\nnav .brand{\n  color:#fff; font-weight:bold; text-decoration:none;\n  font-size:24px; padding: 20px; \n}\n\nnav .nav-links {\n  padding:20px; \n}\n\nnav .nav-links a{\n  color:#fff; padding:20px; font-size:20px; text-decoration:none;\n}\n\nnav .nav-links a.active{\n  color:#fff;  background:rgba(120,90,20,0.5);\n}\n\nheader{\n  display:flex; flex-direction:column; \n  height:30vh; background:linear-gradient(240deg, pink,blue,green);\n  align-items:center; \n}\nheader h1, header p {\n  color:#fff; \n}\n\n.container {\n  margin: 20px;\n}\n\n.item {\n  background: #4f8ef7;\n  color: #fff;\n  padding: 20px;\n  text-align: center;\n  border-radius: 6px;\n}\n\n\n/* 1. FLEX ROW (Navbar style) */\n.flex-row {\n  display: flex;\n  justify-content: space-between;\n  gap: 10px;\n}\n\n/* 2. FLEX COLUMN (Vertical layout) */\n.flex-column {\n  display: flex;\n  flex-direction: column;\n  align-items: center;\n  gap: 10px;\n}\n\n/* 3. FLEX WRAP (Card layout) */\n.flex-wrap {\n  display: flex;\n  flex-wrap: wrap;\n  gap: 10px;\n}\n\n.flex-wrap .item {\n  flex: 1 1 150px;\n}\n\n/* 4. FLEX CENTER (Perfect centering) */\n.flex-center {\n  display: flex;\n  justify-content: center;\n  align-items: center;\n  height: 200px;\n  background: #f5f5f5;\n}\n\n/* ===============================\n   GRID EXAMPLES\n================================ */\n\n/* 5. BASIC GRID (3 columns) */\n.grid-basic {\n  display: grid;\n  grid-template-columns: repeat(3, 1fr);\n  gap: 10px;\n}\n\n/* 6. GRID SPANNING */\n.grid-span {\n  display: grid;\n  grid-template-columns: repeat(3, 1fr);\n  gap: 10px;\n}\n\n.grid-span .item1 {\n  grid-column: span 2;\n}\n\n/* 7. DASHBOARD GRID */\n.grid-dashboard {\n  display: grid;\n  grid-template-areas:\n    \"header header\"\n    \"sidebar content\";\n  grid-template-columns: 200px 1fr;\n  gap: 10px;\n}\n\n.header {\n  grid-area: header;\n  background: #e74c3c;\n  color: white;\n  padding: 20px;\n}\n\n.sidebar {\n  grid-area: sidebar;\n  background: #2c3e50;\n  color: white;\n  padding: 20px;\n}\n\n.content {\n  grid-area: content;\n  background: #27ae60;\n  color: white;\n  padding: 20px;\n}\n\n/* 8. COMPLEX / MAGAZINE GRID */\n.grid-complex {\n  display: grid;\n  grid-template-columns: repeat(3, 1fr);\n  grid-auto-rows: 100px;\n  gap: 10px;\n}\n\n.grid-complex .item1 {\n  grid-column: span 2;\n}\n\n.grid-complex .item2 {\n  grid-row: span 2;\n}\n\n/* ===============================\n   OPTIONAL COLORS FOR VARIETY\n================================ */\n.item:nth-child(2) { background: #e67e22; }\n.item:nth-child(3) { background: #27ae60; }\n.item:nth-child(4) { background: #8e44ad; }\n.item:nth-child(5) { background: #16a085; }\n.item:nth-child(6) { background: #c0392b; }\n\n\n\n', '2026-04-02 19:44:18', '2026-04-02 19:44:18'),
(8, 2, 'script.js', 'js', 'function greet() {\n  const name = prompt(\'What is your name?\');\n  if (name) {\n    document.querySelector(\'h1\').textContent = `Hello, ${name}! 🎉`;\n  }\n}', '2026-04-02 19:44:18', '2026-04-02 19:44:18'),
(10, 3, 'index.html', 'html', '<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n  <meta charset=\"UTF-8\">\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n  <title>My Project</title>\n  <link rel=\"stylesheet\" href=\"style.css\">\n</head>\n<body>\n\n  <nav>\n    <a href=\"index.html\">Home</a>\n    <a href=\"about.html\">About</a>\n  </nav>\n\n  <main>\n    <h1>Hello, INSPIRE! 👋</h1>\n    <p>Edit the files in the tabs above.</p>\n    <button onclick=\"greet()\">Click me</button>\n  </main>\n\n  <script src=\"script.js\"></script>\n</body>\n</html>', '2026-04-03 18:21:28', '2026-04-03 18:21:28'),
(11, 3, 'style.css', 'css', '* { box-sizing: border-box; margin: 0; padding: 0; }\n\nbody {\n  font-family: \'Segoe UI\', sans-serif;\n  background: linear-gradient(135deg, #0D1B2A, #1E3A5F);\n  color: white;\n  min-height: 100vh;\n}\n\nnav {\n  display: flex;\n  gap: 12px;\n  padding: 16px 32px;\n  background: rgba(0,0,0,0.3);\n}\n\nnav a {\n  color: #00B4D8;\n  text-decoration: none;\n  font-weight: 600;\n  padding: 6px 14px;\n  border-radius: 6px;\n  transition: background 0.2s;\n}\n\nnav a:hover { background: rgba(255,255,255,0.1); }\n\nmain {\n  display: flex;\n  flex-direction: column;\n  align-items: center;\n  justify-content: center;\n  min-height: 80vh;\n  gap: 20px;\n  text-align: center;\n  padding: 40px;\n}\n\nh1 { font-size: 2.8rem; }\np  { opacity: 0.8; font-size: 1.1rem; }\n\nbutton {\n  background: linear-gradient(135deg, #06D6A0, #0077B6);\n  color: white;\n  border: none;\n  padding: 12px 32px;\n  border-radius: 8px;\n  font-size: 16px;\n  font-weight: 600;\n  cursor: pointer;\n  transition: transform 0.2s;\n}\nbutton:hover { transform: translateY(-3px); }', '2026-04-03 18:21:28', '2026-04-03 18:21:28'),
(12, 3, 'script.js', 'js', 'function greet() {\n  const name = prompt(\'What is your name?\');\n  if (name) {\n    document.querySelector(\'h1\').textContent = `Hello, ${name}! 🎉`;\n  }\n}', '2026-04-03 18:21:28', '2026-04-03 18:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9g9GwzPYqrN6C07JMIqnWUXDCzrh7D3ixQyuzyIM', NULL, '10.55.226.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJUWk9mVzFRZlB0TWJPM0M4YWdONFROaHhuVm1iRTNSVnBsZEVJMWdIIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEwLjU1LjIyNi43OjgwMDEiLCJyb3V0ZSI6InByb2plY3RzIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1775246368);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
