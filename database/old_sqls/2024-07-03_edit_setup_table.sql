ALTER TABLE `setups` ADD `navbar_width` tinyint NULL AFTER `post_show_number`; 
ALTER TABLE `setups` ADD `disable_right` tinyint NULL AFTER `post_show_number`; 
ALTER TABLE `posts` ADD `inbox` tinyint NULL AFTER `insite`; 