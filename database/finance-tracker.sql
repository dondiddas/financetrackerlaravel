-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table finance_tracker.bills: ~12 rows (approximately)
INSERT INTO `bills` (`id`, `bill_name`, `description`, `is_paid`, `is_recurring`, `amount`, `due_date`, `status`, `created_at`, `updated_at`, `deleted_at`, `recurrence_type_id`, `user_id`) VALUES
	(1, 'Electric Bill', 'bill nasad', 0, 0, 2500.00, '2025-11-15', 'unpaid', '2025-11-10 03:52:21', '2025-11-13 09:40:50', NULL, NULL, 1),
	(2, 'Water Bill', 'wa nakoy kwarta', 1, 0, 800.00, '2025-11-20', 'unpaid', '2025-11-10 03:52:21', '2025-12-02 04:19:22', NULL, NULL, 1),
	(3, 'Internet Bill', NULL, 1, 0, 1500.00, '2025-11-25', 'paid', '2025-11-10 03:52:21', '2025-11-10 03:52:21', NULL, NULL, 1),
	(4, 'monthly starlight', NULL, 0, 0, 300.00, '2025-11-30', 'unpaid', '2025-11-21 01:51:40', '2025-11-21 01:51:40', NULL, NULL, 1),
	(5, 'water bill', NULL, 0, 0, 300.00, '2025-11-26', 'unpaid', '2025-11-21 04:23:12', '2025-11-21 04:23:12', NULL, NULL, 1),
	(6, 'monthly starlight', NULL, 0, 0, 320.00, '2025-12-24', 'unpaid', '2025-12-01 23:20:20', '2025-12-01 23:20:20', NULL, NULL, 1),
	(7, 'monthly starlight', 'fredrinn starlight this month', 1, 1, 320.00, '2025-12-24', 'unpaid', '2025-12-02 21:08:55', '2025-12-04 00:43:31', NULL, 2, 4),
	(8, 'monthly starlight', 'fredrinn starlight this month', 1, 1, 320.00, '2026-01-24', 'unpaid', '2025-12-03 19:29:55', '2025-12-04 00:43:39', NULL, 2, 4),
	(9, 'monthly starlight', 'fredrinn starlight this month', 0, 1, 320.00, '2026-02-24', 'unpaid', '2025-12-03 19:47:11', '2025-12-03 23:51:13', '2025-12-03 23:51:13', NULL, 4),
	(10, 'water bill', NULL, 0, 1, 399.00, '2025-12-31', 'unpaid', '2025-12-03 23:26:20', '2025-12-03 23:26:20', NULL, 3, 4),
	(11, 'Electricty Bill', NULL, 1, 1, 1542.00, '2025-12-04', 'unpaid', '2025-12-03 23:31:33', '2025-12-04 00:09:00', NULL, 3, 4),
	(12, 'Rent', NULL, 0, 1, 1500.00, '2025-12-07', 'unpaid', '2025-12-03 23:33:32', '2025-12-03 23:33:32', NULL, 3, 4);

-- Dumping data for table finance_tracker.budgets: ~2 rows (approximately)
INSERT INTO `budgets` (`id`, `amount`, `note`, `created_at`, `updated_at`, `user_id`, `category_id`) VALUES
	(2, 150.00, 'karon na month', '2025-12-03 18:32:25', '2025-12-03 18:32:25', 4, 32),
	(3, 800.00, NULL, '2025-12-03 18:53:36', '2025-12-03 19:03:19', 4, 31);

-- Dumping data for table finance_tracker.cache: ~0 rows (approximately)

-- Dumping data for table finance_tracker.cache_locks: ~0 rows (approximately)

-- Dumping data for table finance_tracker.categories: ~17 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `user_id`, `type`, `created_at`, `updated_at`) VALUES
	(1, 'Pocket Money', 1, 'allowance', '2025-11-10 03:44:59', '2025-11-10 03:44:59'),
	(2, 'Salary', 1, 'income', '2025-11-10 03:44:59', '2025-11-10 03:44:59'),
	(3, 'Groceries', 1, 'expense', '2025-11-10 03:44:59', '2025-11-10 03:44:59'),
	(4, 'Electricity Bill', 1, 'expense', '2025-11-10 03:44:59', '2025-11-10 03:44:59'),
	(5, 'Allowance', 1, 'allowance', '2025-11-10 05:52:18', '2025-11-10 05:52:18'),
	(8, 'Transportation', 1, 'expense', '2025-11-10 07:11:41', '2025-11-10 07:11:41'),
	(9, 'Entertainment', 1, 'expense', '2025-11-10 07:15:24', '2025-11-10 07:15:24'),
	(10, 'Gym', 1, 'expense', '2025-11-10 07:21:50', '2025-11-10 07:21:50'),
	(11, 'cosmetics', 1, 'expense', '2025-11-11 22:28:19', '2025-11-11 22:28:19'),
	(15, 'Income', 1, 'income', '2025-11-13 05:34:14', '2025-11-13 05:34:14'),
	(21, 'Food', 1, 'expense', '2025-11-17 01:18:16', '2025-11-17 01:18:16'),
	(28, 'Transportation', 4, 'expense', '2025-12-02 21:08:38', '2025-12-02 21:08:38'),
	(30, 'Income', 4, 'income', '2025-12-02 21:09:49', '2025-12-02 21:09:49'),
	(31, 'Groceries', 4, 'expense', '2025-12-02 21:34:05', '2025-12-02 21:34:05'),
	(32, 'Entertainment', 4, 'expense', '2025-12-02 21:34:19', '2025-12-02 21:34:19'),
	(33, 'cosmetics', 4, 'expense', '2025-12-03 18:56:48', '2025-12-03 18:56:48'),
	(34, 'Allowance', 4, 'allowance', '2025-12-04 00:25:03', '2025-12-04 00:25:03');

-- Dumping data for table finance_tracker.daily_limits: ~1 rows (approximately)
INSERT INTO `daily_limits` (`id`, `user_id`, `expense_limit`, `limit_date`, `created_at`, `updated_at`) VALUES
	(1, 4, 300.00, '2025-12-04', '2025-12-03 18:50:13', '2025-12-03 18:50:13');

-- Dumping data for table finance_tracker.migrations: ~16 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '2025_11_10_065629_create_categories_table', 1),
	(4, '2025_11_10_065631_create_goals_table', 1),
	(5, '2025_11_10_065631_create_transactions_table', 1),
	(6, '2025_11_10_065808_create_bills_table', 1),
	(7, '2025_11_10_065827_create_budgets_table', 1),
	(8, '2025_11_10_065839_create_reminders_table', 1),
	(9, '2025_12_02_000000_add_is_recurring_to_bills_table', 2),
	(10, '2025_12_02_000001_add_recurrence_interval_to_bills_table', 3),
	(11, '2025_12_02_000002_add_is_paid_to_bills_table', 3),
	(12, '2025_12_03_000001_add_dark_mode_to_users_table', 4),
	(13, '2025_12_04_000000_create_daily_limits_table', 5),
	(14, '2025_12_04_000001_create_recurrence_types_table', 6),
	(15, '2025_12_04_000002_add_recurrence_type_id_to_bills_table', 6),
	(16, '2025_12_04_000003_add_deleted_at_to_bills_table', 7);

-- Dumping data for table finance_tracker.password_reset_tokens: ~0 rows (approximately)

-- Dumping data for table finance_tracker.recurrence_types: ~4 rows (approximately)
INSERT INTO `recurrence_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'daily', '2025-12-03 23:08:47', '2025-12-03 23:08:47'),
	(2, 'weekly', '2025-12-03 23:08:47', '2025-12-03 23:08:47'),
	(3, 'monthly', '2025-12-03 23:08:47', '2025-12-03 23:08:47'),
	(4, 'yearly', '2025-12-03 23:08:47', '2025-12-03 23:08:47');

-- Dumping data for table finance_tracker.reminders: ~0 rows (approximately)

-- Dumping data for table finance_tracker.sessions: ~2 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('BDG9KovXZKd5RUKoQWaiBWJCWXNvAgcG4Dmd8tuD', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYWdkdG84QVFDR2NSZnJWMFlRZlVPRTZhb3RlNGZnZEx6b0VKbXdPcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWNlbnQtdHJhbnNhY3Rpb25zIjtzOjU6InJvdXRlIjtzOjE5OiJyZWNlbnQudHJhbnNhY3Rpb25zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1764849013),
	('yvUYPfoQSS54Vs5sADcY5ObOh8hFr6COWm2Eq392', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQm5zUGJtUm1JWlNNbmtpdmFORVJrbEZNMndzOVVGT1lSVHlmNWNOQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9idWRnZXRzIjtzOjU6InJvdXRlIjtzOjEzOiJidWRnZXRzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1764838073);

-- Dumping data for table finance_tracker.transactions: ~47 rows (approximately)
INSERT INTO `transactions` (`id`, `amount`, `note`, `transaction_date`, `user_id`, `category_id`, `created_at`, `updated_at`) VALUES
	(1, 100.00, 'Weekly allowance', '2025-11-09 16:00:00', 1, 1, '2025-11-10 03:44:59', '2025-11-10 03:44:59'),
	(2, 50.00, 'Supermarket', '2025-11-09 16:00:00', 1, 3, '2025-11-10 03:44:59', '2025-11-10 03:44:59'),
	(3, 75.00, 'Monthly bill', '2025-11-07 16:00:00', 1, 4, '2025-11-10 03:44:59', '2025-11-10 03:44:59'),
	(4, 1500.00, 'Monthly salary', '2025-10-09 16:00:00', 1, 2, '2025-11-10 03:44:59', '2025-11-10 03:44:59'),
	(5, 500.00, 'allowance this week', '2025-11-10 05:52:18', 1, 5, '2025-11-10 05:52:18', '2025-11-10 05:52:18'),
	(9, 50.00, 'roxas to mintal', '2025-11-10 15:11:41', 1, 8, '2025-11-10 07:11:41', '2025-11-10 07:11:41'),
	(10, 200.00, 'sine', '2025-11-10 15:15:24', 1, 9, '2025-11-10 07:15:24', '2025-11-10 07:15:24'),
	(11, 150.00, 'Fuel', '2025-11-10 15:17:57', 1, 8, '2025-11-10 07:17:57', '2025-11-10 07:17:57'),
	(12, 100.00, 'padako lawas', '2025-11-10 15:21:50', 1, 10, '2025-11-10 07:21:50', '2025-11-10 07:21:50'),
	(13, 1000.00, 'Added allowance', '2025-11-10 07:22:38', 1, 5, '2025-11-10 07:22:38', '2025-11-10 07:22:38'),
	(14, 100.00, 'padako lawas', '2025-11-12 06:27:10', 1, 10, '2025-11-11 22:27:10', '2025-11-11 22:27:10'),
	(15, 500.00, 'blk', '2025-11-12 06:28:19', 1, 11, '2025-11-11 22:28:19', '2025-11-11 22:28:19'),
	(16, 50.00, 'sardinas', '2025-11-12 16:31:19', 1, 3, '2025-11-12 08:31:19', '2025-11-12 08:31:19'),
	(17, 1999.00, 'davao light', '2025-11-13 04:41:50', 1, 4, '2025-11-12 20:41:50', '2025-11-12 20:41:50'),
	(18, 10000.00, 'kinsenas', '2025-11-13 05:34:14', 1, 15, '2025-11-13 05:34:14', '2025-11-13 05:34:14'),
	(20, 100.00, 'padako lawas', '2025-11-13 17:30:37', 1, 10, '2025-11-13 09:30:37', '2025-11-13 09:30:37'),
	(21, 150.00, 'batong with chicken', '2025-11-15 08:16:30', 1, 3, '2025-11-15 00:16:30', '2025-11-15 00:16:30'),
	(22, 150.00, 'lipstick', '2025-11-15 08:29:17', 1, 11, '2025-11-15 00:29:17', '2025-11-15 00:29:17'),
	(23, 150.00, 'eyeliner', '2025-11-16 09:54:18', 1, 11, '2025-11-16 01:54:18', '2025-11-16 01:54:18'),
	(24, 40.00, 'pastil, siomai, coke', '2025-11-17 09:18:16', 1, 21, '2025-11-17 01:18:16', '2025-11-17 01:18:16'),
	(25, 244.00, 'Fuel', '2025-11-19 08:47:23', 1, 8, '2025-11-19 00:47:23', '2025-11-19 00:47:23'),
	(26, 93.00, 'penoy with softdrink', '2025-11-19 09:36:44', 1, 21, '2025-11-19 01:36:44', '2025-11-19 01:36:44'),
	(27, 124.00, 'Fuel', '2025-11-19 15:51:17', 1, 8, '2025-11-19 07:51:17', '2025-11-19 07:51:17'),
	(28, 88.00, 'tamarind candy/chuckie', '2025-11-19 15:51:35', 1, 21, '2025-11-19 07:51:35', '2025-11-19 07:51:35'),
	(29, 6000.00, 'Added allowance', '2025-11-19 08:09:45', 1, 5, '2025-11-19 08:09:45', '2025-11-19 08:09:45'),
	(30, 100.00, 'padako lawas', '2025-11-20 05:27:10', 1, 10, '2025-11-19 21:27:10', '2025-11-19 21:27:10'),
	(32, 500.00, 'Added income', '2025-11-21 04:19:28', 1, 15, '2025-11-21 04:19:28', '2025-11-21 04:19:28'),
	(33, 20.00, 'Added allowance', '2025-11-21 04:19:50', 1, 5, '2025-11-21 04:19:50', '2025-11-21 04:19:50'),
	(34, 140.00, 'unleaded', '2025-11-21 12:20:42', 1, 8, '2025-11-21 04:20:42', '2025-11-21 04:20:42'),
	(35, 100.00, 'padako lawas', '2025-11-21 12:21:02', 1, 10, '2025-11-21 04:21:02', '2025-11-21 04:21:02'),
	(36, 100.00, 'padako lawas', '2025-12-01 12:05:57', 1, 10, '2025-12-01 04:05:57', '2025-12-01 04:05:57'),
	(37, 15000.00, 'Added income', '2025-12-01 04:06:48', 1, 15, '2025-12-01 04:06:48', '2025-12-01 04:06:48'),
	(38, 15000.00, 'Added allowance', '2025-12-01 04:07:16', 1, 5, '2025-12-01 04:07:16', '2025-12-01 04:07:16'),
	(39, 250.00, 'de lata', '2025-12-01 13:58:46', 1, 3, '2025-12-01 05:58:46', '2025-12-01 05:58:46'),
	(40, 250.00, 'Added income', '2025-12-01 06:39:36', 1, 15, '2025-12-01 06:39:36', '2025-12-01 06:39:36'),
	(41, 125.00, 'fuel', '2025-12-02 07:22:37', 1, 8, '2025-12-01 23:22:37', '2025-12-01 23:22:37'),
	(42, 75.00, 'toppings with mineral water', '2025-12-02 07:32:31', 1, 21, '2025-12-01 23:32:31', '2025-12-01 23:32:31'),
	(43, 75.00, 'toppings with mineral water', '2025-12-02 07:52:39', 1, 21, '2025-12-01 23:52:39', '2025-12-01 23:52:39'),
	(44, 200.00, 'sakay taxi mintal to downtown', '2025-12-03 05:08:38', 4, 28, '2025-12-02 21:08:38', '2025-12-02 21:08:38'),
	(45, 800.00, 'Added income', '2025-12-02 21:09:49', 4, 30, '2025-12-02 21:09:49', '2025-12-02 21:09:49'),
	(46, 20.00, 'tinapa', '2025-12-03 05:34:05', 4, 31, '2025-12-02 21:34:05', '2025-12-02 21:34:05'),
	(47, 100.00, 'paload', '2025-12-03 05:34:19', 4, 32, '2025-12-02 21:34:19', '2025-12-02 21:34:19'),
	(48, 100.00, 'patubil', '2025-12-04 02:52:51', 4, 28, '2025-12-03 18:52:51', '2025-12-03 18:52:51'),
	(49, 300.00, 'gulays', '2025-12-04 02:54:05', 4, 31, '2025-12-03 18:54:05', '2025-12-03 18:54:05'),
	(50, 300.00, 'lippy', '2025-12-04 02:56:48', 4, 33, '2025-12-03 18:56:48', '2025-12-03 18:56:48'),
	(51, 500.00, 'Added allowance', '2025-12-04 00:25:03', 4, 34, '2025-12-04 00:25:03', '2025-12-04 00:25:03'),
	(52, 2000.00, 'Added allowance', '2025-12-04 00:25:54', 4, 34, '2025-12-04 00:25:54', '2025-12-04 00:25:54');

-- Dumping data for table finance_tracker.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Prince Randy', 'M.', 'Gonzales', 'princerandygonzales@example.com', NULL, '$2y$12$GZCv5iYR8W2JhF.t2AoMpej.UdHS8LY0wipfeV3jkK49serUArEu2', NULL, NULL, NULL),
	(3, 'Mara', 'M', 'Gonzales', 'dummydumbbed@gmail.com', NULL, '$2y$12$UkDfezwgBilZKHG.f7KoeetDiSNbDKUI/LL4zlNZQDL.pWccdcE.i', NULL, '2025-12-02 20:43:03', '2025-12-02 20:43:03'),
	(4, 'Cueshe', 'S', 'Stay', 'cueshe@gmail.com', NULL, '$2y$12$sTHVunFNPzt6n4pXz.c1V.l6z8QoQf/wIb4K5bbpM4nyUy94O3KAK', NULL, '2025-12-02 20:46:32', '2025-12-02 20:46:32');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
