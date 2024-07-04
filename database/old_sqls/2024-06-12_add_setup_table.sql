ALTER TABLE `blocks` ADD `block_position` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `block_color`; 
ALTER TABLE `setups` ADD `all_post` tinyint NULL AFTER `ipv6`; 