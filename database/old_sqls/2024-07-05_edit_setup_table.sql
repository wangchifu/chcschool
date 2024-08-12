ALTER TABLE `setups` ADD `disable_right` tinyint NULL AFTER `post_show_number`; 
ALTER TABLE `posts` ADD `inbox` tinyint NULL AFTER `insite`; 
INSERT INTO `blocks` (`title`, `created_at`, `updated_at`) VALUES ('常駐公告(系統區塊)','2024-07-05 11:00:00','2024-07-05 11:00:00');
