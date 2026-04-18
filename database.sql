-- VibeFlow Database Schema & Seed
-- Target: MySQL 8.x

CREATE DATABASE IF NOT EXISTS `vibeflow_db`;
USE `vibeflow_db`;

-- 1. Table `users`
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('super_admin', 'pm', 'programmer', 'client') NOT NULL,
    `avatar` VARCHAR(255) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `last_login` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table `projects`
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `client_id` INT NOT NULL,
    `pm_id` INT NOT NULL,
    `status` ENUM('planning', 'active', 'completed', 'archived') DEFAULT 'planning',
    `deadline` DATE NOT NULL,
    `progress` TINYINT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_project_client` FOREIGN KEY (`client_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_project_pm` FOREIGN KEY (`pm_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_client_id ON `projects` (`client_id`);
CREATE INDEX idx_pm_id ON `projects` (`pm_id`);

-- 3. Table `tasks`
CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT NOT NULL,
    `programmer_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium',
    `status` ENUM('todo', 'in_progress', 'done') DEFAULT 'todo',
    `due_date` DATE NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_task_project` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_task_programmer` FOREIGN KEY (`programmer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_project_id ON `tasks` (`project_id`);
CREATE INDEX idx_programmer_id ON `tasks` (`programmer_id`);

-- 4. Table `documents`
CREATE TABLE IF NOT EXISTS `documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT NOT NULL,
    `uploader_id` INT NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_size` INT NULL,
    `file_type` VARCHAR(50) NULL,
    `uploaded_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_doc_project` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_doc_uploader` FOREIGN KEY (`uploader_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Table `activity_logs`
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `action` VARCHAR(100) NOT NULL,
    `target_table` VARCHAR(50) NOT NULL,
    `target_id` INT NOT NULL,
    `old_value` TEXT NULL,
    `new_value` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Data
-- Passwords are 'password123' hashed with bcrypt: $2y$10$qBLwNN2e3QHdKl8c6HLUmObz2QtFW0W3sEb7PiN4ioaJh1CUmYdlG
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Super Admin', 'admin@vibeflow.com', '$2y$10$qBLwNN2e3QHdKl8c6HLUmObz2QtFW0W3sEb7PiN4ioaJh1CUmYdlG', 'super_admin'),
('Project Manager One', 'pm@vibeflow.com', '$2y$10$qBLwNN2e3QHdKl8c6HLUmObz2QtFW0W3sEb7PiN4ioaJh1CUmYdlG', 'pm'),
('Programmer One', 'dev@vibeflow.com', '$2y$10$qBLwNN2e3QHdKl8c6HLUmObz2QtFW0W3sEb7PiN4ioaJh1CUmYdlG', 'programmer'),
('Client One', 'client@vibeflow.com', '$2y$10$qBLwNN2e3QHdKl8c6HLUmObz2QtFW0W3sEb7PiN4ioaJh1CUmYdlG', 'client');
