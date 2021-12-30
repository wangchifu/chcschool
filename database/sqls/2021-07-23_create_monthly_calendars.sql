CREATE TABLE `monthly_calendars` (
                               `id` int(10) UNSIGNED NOT NULL,
                               `item_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `item` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `user_id` int(10) UNSIGNED,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `monthly_calendars`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `monthly_calendars`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('校務月曆(系統區塊)','2021-07-19 11:00:00','2021-07-23 11:00:00');
