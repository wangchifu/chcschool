ALTER TABLE clubs ADD class_id int(10) UNSIGNED NOT NULL;
ALTER TABLE club_registers ADD class_id int(10) UNSIGNED NOT NULL;
ALTER TABLE club_semesters ADD start_date2 varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE club_semesters ADD stop_date2 varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL;
