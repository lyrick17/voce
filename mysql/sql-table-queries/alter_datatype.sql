/* change column data type into text */
ALTER TABLE text_translations MODIFY COLUMN translate_from TEXT;
ALTER TABLE text_translations MODIFY COLUMN translate_to TEXT;

/* limit number of characters */
ALTER TABLE users MODIFY COLUMN username VARCHAR(50);
ALTER TABLE text_translations MODIFY COLUMN email VARCHAR(100);
