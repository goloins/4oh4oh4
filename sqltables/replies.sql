CREATE TABLE `replies` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `content` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `attached_media` VARCHAR(500) DEFAULT NULL,
    `source` VARCHAR(50) NOT NULL,
    `reply_to` INT NOT NULL,

    INDEX `idx_replies_reply_to_created` (`reply_to`, `created_at`),
    INDEX `idx_replies_user_created` (`user_id`, `created_at`)

    -- Optional (recommended if your DB has matching PKs):
    -- ,CONSTRAINT `fk_replies_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    -- ,CONSTRAINT `fk_replies_post` FOREIGN KEY (`reply_to`) REFERENCES `posts` (`id`) ON DELETE CASCADE
);