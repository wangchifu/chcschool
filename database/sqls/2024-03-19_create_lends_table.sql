CREATE TABLE `lend_classes` (
  `id` int(10) UNSIGNED NOT NULL,  
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `ps` text NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `lend_classes`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `lend_classes`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `lend_items` (
  `id` int(10) UNSIGNED NOT NULL,  
  `lend_class_id` int(10) UNSIGNED NOT NULL, 
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `enable` int(10) UNSIGNED NULL DEFAULT NULL,
  `ps` text NULL DEFAULT NULL,
  `lend_sections` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `lend_items`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `lend_items`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `lend_orders` (
  `id` int(10) UNSIGNED NOT NULL,  
  `lend_item_id` int(10) UNSIGNED NOT NULL, 
  `num` int(10) UNSIGNED NOT NULL,
  `lend_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lend_section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `back_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `back_section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `owner_user_id` int(10) UNSIGNED NOT NULL,  
  `ps` text NULL DEFAULT NULL,  
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `lend_orders`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `lend_orders`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `users` ADD `line_key` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `title`; 

INSERT INTO `blocks` (`title`, `order_by`, `setup_col_id`, `created_at`, `updated_at`) VALUES ('借用狀態(系統區塊)',null,null,'2024-03-19 11:00:00','2024-03-19 11:00:00');