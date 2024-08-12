INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('樹狀目錄(系統區塊)','2019-05-15 11:00:00','2019-05-15 11:00:00');

CREATE TABLE `trees` (
  `id` int(10) UNSIGNED NOT NULL,
  `folder_id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `trees`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `trees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
