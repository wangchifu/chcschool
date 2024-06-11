ALTER TABLE `photo_links` ADD `user_id` int UNSIGNED NULL AFTER `order_by`; 
ALTER TABLE `photo_types` ADD `user_id` int UNSIGNED NULL AFTER `order_by`; 
ALTER TABLE `trees` ADD `order_by` int UNSIGNED NULL AFTER `url`; 
ALTER TABLE `posts` ADD `die_date` DATE NULL AFTER `top`; 