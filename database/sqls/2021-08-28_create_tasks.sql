CREATE TABLE `tasks` (
                                   `id` int(10) UNSIGNED NOT NULL,
                                   `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `user_id` int(10) UNSIGNED NOT NULL,
                                   `disable` tinyint NULL DEFAULT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tasks`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `tasks`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `user_tasks` (
                             `id` int(10) UNSIGNED NOT NULL,
                             `user_id` int(10) UNSIGNED NOT NULL,
                             `task_id` int(10) UNSIGNED NOT NULL,
                             `condition` tinyint NULL DEFAULT NULL,
                             `created_at` timestamp NULL DEFAULT NULL,
                             `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `user_tasks`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `user_tasks`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
