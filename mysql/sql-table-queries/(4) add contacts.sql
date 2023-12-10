CREATE TABLE `contacts` (
  `contact_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text
);