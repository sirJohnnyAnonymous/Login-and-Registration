ALTER TABLE game_dev
    ADD COLUMN added_by INT NULL,
    ADD COLUMN updated_by INT NULL,
    ADD COLUMN last_updated DATETIME NULL;

UPDATE game_dev
SET last_updated = date_added
WHERE last_updated IS NULL;

ALTER TABLE game_dev
    MODIFY COLUMN last_updated DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    ADD CONSTRAINT fk_game_dev_added_by
        FOREIGN KEY (added_by) REFERENCES user_passwords (user_id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    ADD CONSTRAINT fk_game_dev_updated_by
        FOREIGN KEY (updated_by) REFERENCES user_passwords (user_id)
        ON UPDATE CASCADE ON DELETE SET NULL;
