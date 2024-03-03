ALTER TABLE `audio_files` CHANGE `file_size` `file_size` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

ALTER TABLE `audio_files` ADD `is_recorded` TINYINT NULL DEFAULT NULL AFTER `file_id`;