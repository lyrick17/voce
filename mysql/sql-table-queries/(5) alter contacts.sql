ALTER TABLE contacts ADD user_id int(11)

ALTER TABLE contacts
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);