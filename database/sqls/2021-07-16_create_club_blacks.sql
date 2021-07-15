CREATE TABLE `club_blacks` (
  `id` int(10) UNSIGNED NOT NULL,
  `semester` int(10) UNSIGNED NOT NULL,
  `no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `club_blacks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `club_blacks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
