ALTER TABLE club_students ADD sex varchar(255) DEFAULT NULL;
ALTER TABLE club_students ADD disable tinyint UNSIGNED DEFAULT NULL;

CREATE TABLE `student_classes` (
                               `id` int(10) UNSIGNED NOT NULL,
                               `semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `student_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `student_class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `user_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `user_names` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `student_classes`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `student_classes`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

DROP TABLE lunch_class;
DROP TABLE lunch_stu;