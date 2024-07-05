CREATE TABLE `lunch_stu` (
                                   `id` int(10) UNSIGNED NOT NULL,
                                   `semester` int(10) UNSIGNED NOT NULL,
                                   `no` int(10) UNSIGNED NOT NULL,
                                   `class_num` int(10) UNSIGNED NOT NULL,
                                   `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `sex` int(10) UNSIGNED NOT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `lunch_stu`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `lunch_stu`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `lunch_class` (
                             `id` int(10) UNSIGNED NOT NULL,
                             `semester` int(10) UNSIGNED NOT NULL,
                             `class_id` int(10) UNSIGNED NOT NULL,
                             `class_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                             `created_at` timestamp NULL DEFAULT NULL,
                             `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `lunch_class`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `lunch_class`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `lunch_stu_dates` (
                               `id` int(10) UNSIGNED NOT NULL,
                               `lunch_stu_id` int(10) UNSIGNED NOT NULL,
                               `order_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `enable` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `semester` int(10) UNSIGNED NOT NULL,
                               `lunch_order_id` int(10) UNSIGNED NOT NULL,
                               `lunch_factory_id` int(10) UNSIGNED NOT NULL,
                               `eat_style` int(10) UNSIGNED NOT NULL,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `lunch_stu_dates`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `lunch_stu_dates`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `lunch_class_dates` (
                                   `id` int(10) UNSIGNED NOT NULL,
                                   `lunch_class_id` int(10) UNSIGNED NOT NULL,
                                   `order_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `semester` int(10) UNSIGNED NOT NULL,
                                   `lunch_order_id` int(10) UNSIGNED NOT NULL,
                                   `lunch_factory_id` int(10) UNSIGNED NOT NULL,
                                   `eat_style1` int(10) UNSIGNED NULL DEFAULT NULL,
                                   `eat_style2` int(10) UNSIGNED NULL DEFAULT NULL,
                                   `eat_style3` int(10) UNSIGNED NULL DEFAULT NULL,
                                   `eat_style4` int(10) UNSIGNED NULL DEFAULT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `lunch_class_dates`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `lunch_class_dates`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE lunch_orders ADD order_ps_ps text NULL DEFAULT NULL;
