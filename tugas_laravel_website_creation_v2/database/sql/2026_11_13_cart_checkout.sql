-- Cart & checkout update (13 Nov 2026), for hosting without "php artisan migrate" (InfinityFree).
-- Run once in phpMyAdmin: pick the database -> SQL tab -> paste everything -> Go.
-- Same as database/migrations/2026_11_13_000001_create_checkouts_table.php.

CREATE TABLE `checkouts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `code` varchar(20) NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `note` varchar(255) NULL,
  `subtotal` int NOT NULL,
  `shipping` int NOT NULL,
  `total` int NOT NULL,
  `payment_message` text NULL,
  `payment_sent_at` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  UNIQUE KEY `checkouts_code_unique` (`code`),
  CONSTRAINT `checkouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `orders`
  ADD `checkout_id` bigint unsigned NULL AFTER `id`,
  ADD CONSTRAINT `orders_checkout_id_foreign` FOREIGN KEY (`checkout_id`) REFERENCES `checkouts` (`id`) ON DELETE SET NULL;

-- Tell Laravel this migration has run, so "php artisan migrate" won't try it again later
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_13_000001_create_checkouts_table', COALESCE(MAX(`batch`), 0) + 1 FROM `migrations`;
