ALTER TABLE `student_signs` ADD `run_num` int UNSIGNED NULL AFTER `order`; 
ALTER TABLE `student_signs` ADD `section_num` int UNSIGNED NULL AFTER `order`; 
ALTER TABLE `items` ADD `scoring` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `reward`; 