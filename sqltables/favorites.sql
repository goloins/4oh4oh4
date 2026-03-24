CREATE TABLE `favorites` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `post_id` INT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX `idx_favorites_user_created` (`user_id`, `created_at`),
    INDEX `idx_favorites_post` (`post_id`),

    UNIQUE KEY `uniq_user_post` (`user_id`, `post_id`)
);