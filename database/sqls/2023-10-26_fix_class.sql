CREATE TABLE `fix_classes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_by` int(10) UNSIGNED NULL DEFAULT NULL,
  `disable` tinyint NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `fix_classes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `fix_classes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `fix_classes` (`id`, `name`, `order_by`, `created_at`, `updated_at`) VALUES (NULL, '設備或資訊', '1', NULL, NULL); 
INSERT INTO `fix_classes` (`id`, `name`, `order_by`, `created_at`, `updated_at`) VALUES (NULL, '總務', '2', NULL, NULL); 
