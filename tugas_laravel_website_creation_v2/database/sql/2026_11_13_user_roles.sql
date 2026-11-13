-- User roles update (13 Nov 2026): user / admin / system_admin, for hosting without "php artisan migrate".
-- Run once in phpMyAdmin AFTER 2026_11_13_cart_checkout.sql: pick the database -> SQL tab -> paste -> Go.
-- Same as database/migrations/2026_11_13_000002_update_user_roles.php.

ALTER TABLE `users` MODIFY `role` varchar(20) NOT NULL DEFAULT 'user';

UPDATE `users` SET `role` = 'user' WHERE `role` = 'customer';

-- The oldest admin account becomes the first System Admin (only if there is none yet)
UPDATE `users` SET `role` = 'system_admin'
WHERE `id` = (SELECT `id` FROM (SELECT MIN(`id`) AS `id` FROM `users` WHERE `role` = 'admin') AS first_admin)
  AND NOT EXISTS (SELECT 1 FROM (SELECT `id` FROM `users` WHERE `role` = 'system_admin') AS has_system_admin);

-- Tell Laravel this migration has run
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_13_000002_update_user_roles', COALESCE(MAX(`batch`), 0) + 1 FROM `migrations`;
