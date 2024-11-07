CREATE TABLE `actions` (
 `id` int UNSIGNED NOT NULL,
 `semester` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `track` tinyint(4) NOT NULL,
 `field` tinyint(4) NOT NULL,
 `frequency` tinyint(4) NOT NULL,
 `numbers` tinyint(4) NOT NULL,
 `disable` tinyint(4) NULL DEFAULT NULL,
 `open` tinyint(4) NULL DEFAULT NULL,
 `started_at` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
 `stopped_at` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, 
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `actions`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `items` (
 `id` int UNSIGNED NOT NULL,
 `action_id` int(10) UNSIGNED NOT NULL,
 `order` int(10) UNSIGNED NULL DEFAULT NULL,
 `game_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
 `official` tinyint(4) NULL DEFAULT NULL,
 `reserve` tinyint(4) NULL DEFAULT NULL,
 `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `group` tinyint(4) NOT NULL,
 `type` tinyint(4) NOT NULL,
 `years` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `limit` tinyint(4) NULL DEFAULT NULL,
 `people` tinyint(4) NULL DEFAULT NULL,
 `reward` tinyint(4) NULL DEFAULT NULL,
 `disable` tinyint(4) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `items`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `student_signs` (
 `id` int UNSIGNED NOT NULL,
 `item_id` int(10) UNSIGNED NOT NULL,
 `item_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `game_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
 `is_official` tinyint(4) NULL DEFAULT NULL,
 `group_num` tinyint(4) NULL DEFAULT NULL,
 `student_id` int(10) UNSIGNED NOT NULL,
 `action_id` int(10) UNSIGNED NOT NULL,
 `student_year` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `student_class` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `num` tinyint(4) NULL DEFAULT NULL,
 `sex` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `achievement` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
 `ranking` int(10) UNSIGNED NULL DEFAULT NULL,
 `order` int(10) UNSIGNED NULL DEFAULT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `student_signs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `student_signs`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `club_students` ADD `number` VARCHAR(191) NULL AFTER `class_num`; 