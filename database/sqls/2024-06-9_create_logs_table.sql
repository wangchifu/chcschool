CREATE TABLE `logs` (
  `id` int(10) UNSIGNED NOT NULL,  
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `this_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text NULL DEFAULT NULL,
  `power` tinyint NULL DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `logs`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `logs`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;