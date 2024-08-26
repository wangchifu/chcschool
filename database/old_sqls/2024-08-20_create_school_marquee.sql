CREATE TABLE `school_marquees` (
 `id` int UNSIGNED NOT NULL,
 `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `start_date` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `stop_date` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `user_id` int UNSIGNED NOT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `school_marquees`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `school_marquees`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `setups` ADD `school_marquee_behavior` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `setup_name`; 
ALTER TABLE `setups` ADD `school_marquee_direction` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `setup_name`; 
ALTER TABLE `setups` ADD `school_marquee_scrollamount` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `setup_name`; 