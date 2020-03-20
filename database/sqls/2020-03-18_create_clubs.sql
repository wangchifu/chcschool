CREATE TABLE `clubs` (
  `id` int(10) UNSIGNED NOT NULL,
  `no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone_num` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `money` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `people` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_info` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ps` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taking` int(10) UNSIGNED NOT NULL,
  `prepare` int(10) UNSIGNED NOT NULL,
  `year_limit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `clubs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `clubs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `club_students` (
  `id` int(10) UNSIGNED NOT NULL,
  `semester` int(10) UNSIGNED NOT NULL,
  `no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_num` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pwd` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parents_telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `club_students`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `club_students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `club_semesters` (
  `id` int(10) UNSIGNED NOT NULL,
  `semester` int(10) UNSIGNED NOT NULL,
  `start_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stop_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_limit` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `club_semesters`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `club_semesters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `club_registers` (
  `id` int(10) UNSIGNED NOT NULL,
  `semester` int(10) UNSIGNED NOT NULL,
  `club_id` int(10) UNSIGNED NOT NULL,
  `club_student_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `club_registers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `club_registers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
