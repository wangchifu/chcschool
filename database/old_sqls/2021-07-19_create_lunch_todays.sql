CREATE TABLE `lunch_todays` (
                               `id` int(10) UNSIGNED NOT NULL,
                               `school_id` int(10) UNSIGNED,
                               `school_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `lunch_todays`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `lunch_todays`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `lunch_todays` (`created_at`, `updated_at`) VALUES ('2021-07-19 11:00:00','2021-07-19 11:00:00');
INSERT INTO `lunch_todays` (`created_at`, `updated_at`) VALUES ('2021-07-19 11:00:00','2021-07-19 11:00:00');
INSERT INTO `lunch_todays` (`created_at`, `updated_at`) VALUES ('2021-07-19 11:00:00','2021-07-19 11:00:00');
INSERT INTO `lunch_todays` (`created_at`, `updated_at`) VALUES ('2021-07-19 11:00:00','2021-07-19 11:00:00');
INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('今日餐點1(系統區塊)','2021-07-19 11:00:00','2021-07-19 11:00:00');
INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('今日餐點2(系統區塊)','2021-07-19 11:00:00','2021-07-19 11:00:00');
INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('今日餐點3(系統區塊)','2021-07-19 11:00:00','2021-07-19 11:00:00');
INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('今日餐點4(系統區塊)','2021-07-19 11:00:00','2021-07-19 11:00:00');
