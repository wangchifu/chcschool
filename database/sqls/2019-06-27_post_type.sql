CREATE TABLE `post_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `post_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `post_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `post_types` (`id`,`name`, `created_at`, `updated_at`) VALUES ('1','內部公告','2019-06-27 11:00:00','2019-06-27 11:00:00');
INSERT INTO `post_types` (`id`,`name`, `created_at`, `updated_at`) VALUES ('2','榮譽榜','2019-06-27 11:00:00','2019-06-27 11:00:00');
INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('分類公告(系統區塊)','2019-06-20 11:00:00','2019-06-20 11:00:00');
