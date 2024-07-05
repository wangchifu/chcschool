CREATE TABLE `title_image_descs` (
                               `id` int(10) UNSIGNED NOT NULL,
                               `image_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `desc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `title_image_descs`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `title_image_descs`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;