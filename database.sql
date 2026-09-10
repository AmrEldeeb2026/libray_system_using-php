-- ============================================
-- The Reading Room — Library Management System
-- Database schema
-- ============================================

CREATE DATABASE IF NOT EXISTS `library_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `library_db`;

-- Admin users
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Books catalog
CREATE TABLE IF NOT EXISTS `books` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `author` VARCHAR(255) NOT NULL,
    `isbn` VARCHAR(50) NOT NULL,
    `status` ENUM('available', 'borrowed') DEFAULT 'available',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Borrow records (one row per borrow request)
CREATE TABLE IF NOT EXISTS `borrow_records` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `book_id` INT NOT NULL,
    `borrower_name` VARCHAR(150) NOT NULL,
    `borrower_phone` VARCHAR(20) NOT NULL,
    `borrow_date` DATE NOT NULL,
    `return_date` DATE NULL,
    `status` ENUM('borrowed', 'returned') DEFAULT 'borrowed',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample books to start with
INSERT INTO `books` (`title`, `author`, `isbn`, `status`) VALUES
('Clean Code', 'Robert C. Martin', '9780132350884', 'available'),
('The Pragmatic Programmer', 'Andrew Hunt, David Thomas', '9780201616224', 'available'),
('Introduction to Algorithms', 'Thomas H. Cormen', '9780262033848', 'borrowed');

-- Note: the default admin account (admin@library.com / admin123) is created
-- automatically the first time db.php runs, using PHP's password_hash()
-- so the hash always matches your PHP version. No need to insert it here.
