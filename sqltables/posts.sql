CREATE TABLE `posts` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `content` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL,
    `attached_media` VARCHAR(500) DEFAULT NULL,
    `source` VARCHAR(50) NOT NULL
);