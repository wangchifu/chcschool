CREATE TABLE `photo_types` (
  `id` int(10) UNSIGNED NOT NULL,  
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_by` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `photo_types`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `photo_types`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `photo_links` ADD `photo_type_id` int UNSIGNED NULL AFTER `image`; 
ALTER TABLE `setups` ADD `photo_link_number` tinyint NULL AFTER `nav_color`; 