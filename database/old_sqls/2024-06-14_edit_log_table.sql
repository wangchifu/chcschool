ALTER TABLE `logs` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL; 
ALTER TABLE `contents` ADD `views` int(10) NULL AFTER `power`; 
ALTER TABLE `departments` ADD `views` int(10) NULL AFTER `order_by`; 