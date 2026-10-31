ALTER TABLE user_passwords
    MODIFY COLUMN username VARCHAR(50) NOT NULL,
    MODIFY COLUMN password VARCHAR(255) NOT NULL,
    ADD UNIQUE KEY uq_user_passwords_username (username);

ALTER TABLE game_project
    ADD COLUMN added_by INT NULL,
    ADD COLUMN updated_by INT NULL,
    ADD COLUMN last_updated DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP;

UPDATE game_project
SET last_updated = date_added
WHERE last_updated IS NULL;

ALTER TABLE game_project
    ADD CONSTRAINT fk_game_project_added_by
        FOREIGN KEY (added_by) REFERENCES user_passwords (user_id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    ADD CONSTRAINT fk_game_project_updated_by
        FOREIGN KEY (updated_by) REFERENCES user_passwords (user_id)
        ON UPDATE CASCADE ON DELETE SET NULL;
