CREATE TABLE `classrooms` (
                               `id` int(10) UNSIGNED NOT NULL,
                               `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `close_sections` text COLLATE utf8mb4_unicode_ci NULL,
                               `disable` int(10) UNSIGNED DEFAULT NULL,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `classrooms`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `classrooms`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `classroom_orders` (
                              `id` int(10) UNSIGNED NOT NULL,
                              `classroom_id` int(10) UNSIGNED NOT NULL,
                              `order_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `section` int(10) UNSIGNED NOT NULL,
                              `user_id` int(10) UNSIGNED NOT NULL,
                              `created_at` timestamp NULL DEFAULT NULL,
                              `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `classroom_orders`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `classroom_orders`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('教室預約(系統區塊)','2021-08-06 11:00:00','2021-08-06 11:00:00');
