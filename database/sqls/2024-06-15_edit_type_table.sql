ALTER TABLE `types` ADD `type_id` int(10) NULL AFTER `name`; 
ALTER TABLE `contents` ADD `tags` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `views`; 
ALTER TABLE `blocks` ADD `disable_block_line` tinyint NULL AFTER `block_position`; 
ALTER TABLE `setups` ADD `bg_color` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `nav_color`; 