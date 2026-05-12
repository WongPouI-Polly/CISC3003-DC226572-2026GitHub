-- =====================================================
-- CISC3003-FinalExam-Paper02A
-- Scenario A: Database and Table Creation
-- Student: Wong Pou I (DC226572)
-- =====================================================
-- A.09: Create a database and table using phpMyAdmin
-- (Execute this in phpMyAdmin or MySQL CLI)
-- =====================================================

-- Create the database
CREATE DATABASE IF NOT EXISTS cisc3003_paper02a
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Select the database
USE cisc3003_paper02a;

-- A.09 & A.10: Create table for registrations
-- A.10: This table will receive INSERT INTO statements
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    course VARCHAR(20) NOT NULL,
    academic_year TINYINT NOT NULL,
    learning_mode VARCHAR(100) DEFAULT NULL,
    remarks TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_course (course)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verify table structure
-- DESCRIBE registrations;