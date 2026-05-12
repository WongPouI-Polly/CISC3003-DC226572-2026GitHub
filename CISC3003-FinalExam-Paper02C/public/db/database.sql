-- =====================================================
-- CISC3003-FinalExam-Paper02C
-- Scenario C: User Authentication Database
-- Student: Wong Pou I (DC226572)
-- =====================================================

CREATE DATABASE IF NOT EXISTS cisc3003_paper02c
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE cisc3003_paper02c;

-- C.03: Table to save signup data
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,  -- C.08: Email confirmation required
    activation_token VARCHAR(64) DEFAULT NULL,
    activation_token_expiry DATETIME DEFAULT NULL,
    reset_token VARCHAR(64) DEFAULT NULL,      -- C.07: Password reset
    reset_token_expiry DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;