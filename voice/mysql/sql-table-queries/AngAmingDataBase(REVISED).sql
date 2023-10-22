
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    email VARCHAR(255),
    pword VARCHAR(255),
    registration_date TIMESTAMP,
    type VARCHAR(255)
);

CREATE TABLE user_activity_log (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    activity_description VARCHAR(255),
    activity_date TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TABLE audio_files (
    file_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    file_name VARCHAR(255),
    file_size VARCHAR(255),
    file_format VARCHAR(255),
    upload_date TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TABLE transcriptions (
    transcription_id INT PRIMARY KEY AUTO_INCREMENT,
    file_id INT,
    user_id INT,
    translation_language VARCHAR(255),
    transcription_text VARCHAR(255),
    transcription_date TIMESTAMP,
    FOREIGN KEY (file_id) REFERENCES audio_files (file_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TABLE translations (
    translation_id INT AUTO_INCREMENT,
    transcription_id INT,
    file_id INT,
    user_id INT,
    translate_to VARCHAR(255),
    translation_text VARCHAR(255),
    translation_date TIMESTAMP,
    PRIMARY KEY (translation_id),
    FOREIGN KEY (file_id) REFERENCES audio_files(file_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (transcription_id) REFERENCES transcriptions(transcription_id)
);

Create TABLE text_only_translations (
text_only_id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT,
original_language VARCHAR(255),
translated_language VARCHAR(255),
translate_from VARCHAR(255),
translate_to VARCHAR(255),
translation_text VARCHAR(255),
translation_date timestamp,
FOREIGN KEY (user_id) REFERENCES users (user_id)

);