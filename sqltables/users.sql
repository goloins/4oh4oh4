CREATE DATABASE IF NOT EXISTS `4oh4oh4`;
USE `4oh4oh4`;

CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username`      VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `email`         VARCHAR(255) NOT NULL,
    `name`          VARCHAR(255) NOT NULL,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `isbanned`      TINYINT(1) NOT NULL DEFAULT 0,
    `displayname`   VARCHAR(255) DEFAULT NULL,
    `bio`           TEXT DEFAULT NULL,
    `avatar_url`    VARCHAR(500) DEFAULT NULL,
    `follows`         TEXT DEFAULT NULL
);
