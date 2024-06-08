ALTER TABLE `setups` ADD `fixed_nav` tinyint NULL AFTER `nav_color`; 
ALTER TABLE `links` ADD `icon` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `id`;
ALTER TABLE `links` ADD `target` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `order_by`; 
ALTER TABLE `contents` ADD `power` tinyint NULL AFTER `content`; 