CREATE TABLE `contacts` (
  `contact_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text
);

CREATE TABLE `admin_users` (
  `user_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pword` varchar(255) NOT NULL,
  `registration_date` timestamp NOT NULL
);

CREATE TABLE `activity_logs` (
  `log_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `translation_id` int,
  `admin_id` int,
  `activity_description` varchar(255) NOT NULL,
  `activity_date` timestamp NOT NULL
);

CREATE TABLE `audio_files` (
  `file_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  `file_format` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL
);

CREATE TABLE `text_translations` (
  `text_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `file_id` int,
  `from_audio_file` tinyint,
  `translation_type` varchar(255),
  `original_language` varchar(255) NOT NULL,
  `translated_language` varchar(255) NOT NULL,
  `translate_from` text NOT NULL,
  `translate_to` text NOT NULL,
  `translation_date` timestamp NOT NULL
);

ALTER TABLE `text_translations` ADD FOREIGN KEY (`file_id`) REFERENCES `audio_files` (`file_id`);

ALTER TABLE `activity_logs` ADD FOREIGN KEY (`translation_id`) REFERENCES `text_translations` (`text_id`);

ALTER TABLE `activity_logs` ADD FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`user_id`);

/* username: admin */
/* password: 12345678 */
INSERT INTO `admin_users` (`user_id`, `username`, `email`, `pword`, `registration_date`) VALUES
(1, 'admin', 'voceteam.contact@gmail.com', '$2y$10$vuJ.5f3erY/tY8MWZmeAzO30LZT1cVRGemy74nHQ0b3F73kUOi0Ji', '2024-02-19 06:48:37');

ALTER TABLE `audio_files` CHANGE `file_size` `file_size` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

ALTER TABLE `audio_files` ADD `is_recorded` TINYINT NULL DEFAULT NULL AFTER `file_id`;

ALTER TABLE activity_logs DROP CONSTRAINT activity_logs_ibfk_1;

ALTER TABLE `contacts` ADD `contact_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `message`;