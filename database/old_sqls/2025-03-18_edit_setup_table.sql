ALTER TABLE `setups` ADD `post_line_group_id` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `post_line_token`; 
ALTER TABLE `setups` ADD `post_line_bot_token` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `post_line_token`; 
ALTER TABLE `users` ADD `line_user_id` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `line_key`; 
ALTER TABLE `users` ADD `line_bot_token` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `line_key`; 