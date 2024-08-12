INSERT INTO `post_types` (`name`, `created_at`, `updated_at`) VALUES ('一般公告','2024-06-06 11:00:00','2024-06-06 11:00:00');
ALTER TABLE `post_types` ADD `disable` tinyint NULL AFTER `order_by`; 
update `post_types` set `id`='0' where `name`="一般公告";