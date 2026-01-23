CREATE TABLE `report_students` (
 `id` int UNSIGNED NOT NULL,
 `semester` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `started_at` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
 `stopped_at` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, 
 `user_id` int UNSIGNED NOT NULL, 
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `report_students`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `report_students`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `report_student_items` (
 `id` int UNSIGNED NOT NULL, 
 `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `report_student_id` int UNSIGNED NOT NULL, 
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `report_student_items`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `report_student_items`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `report_student_answers` ( 
 `id` int UNSIGNED NOT NULL, 
 `student_id` int UNSIGNED NOT NULL, 
 `user_id` int UNSIGNED NOT NULL,  
 `report_student_item_id` int UNSIGNED NOT NULL, 
 `report_student_id` int UNSIGNED NOT NULL, 
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `report_student_answers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `report_student_answers`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;