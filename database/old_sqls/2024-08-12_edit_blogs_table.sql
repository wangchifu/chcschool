ALTER TABLE `blogs` ADD `job_title` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `user_id`; 
ALTER TABLE `uploads` ADD `job_title` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `user_id`; 