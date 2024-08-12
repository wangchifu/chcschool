DELETE FROM `blocks` WHERE `title` like "今日餐點%(系統區塊)";
INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('RSS訊息(系統區塊)','2022-08-20 11:00:00','2022-08-20 11:00:00');

CREATE TABLE `rss_feeds` (
                            `id` int(10) UNSIGNED NOT NULL,
                            `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `type` tinyint NOT NULL,
                            `num` int(10) UNSIGNED NOT NULL,
                            `created_at` timestamp NULL,
                            `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `rss_feeds`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `rss_feeds`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;