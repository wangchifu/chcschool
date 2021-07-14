CREATE TABLE `blogs` (
 `id` int UNSIGNED NOT NULL,
 `title_image` tinyint DEFAULT NULL,
 `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `user_id` int UNSIGNED NOT NULL,
 `views` int UNSIGNED NOT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `blogs`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('校園部落格(系統區塊)','2021-07-14 11:00:00','2021-07-14 11:00:00');
