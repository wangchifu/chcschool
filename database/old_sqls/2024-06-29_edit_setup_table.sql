ALTER TABLE `setups` ADD `homepage_name` varchar(255) COLLATE utf8mb4_unicode_ci NULL; 
ALTER TABLE `setups` ADD `post_name` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `setups` ADD `openfile_name` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `setups` ADD `department_name` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `setups` ADD `schoolexec_name` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `setups` ADD `setup_name` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `blocks` ADD `new_title` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `title`; 
ALTER TABLE `posts` ADD `top_date` DATE NULL AFTER `top`; 