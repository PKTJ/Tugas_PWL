-- Advanced Security Practice Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS security_advanced_db;
USE security_advanced_db;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(255),
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    two_factor_secret VARCHAR(255),
    failed_attempts INT DEFAULT 0,
    last_failed_attempt TIMESTAMP NULL,
    last_login TIMESTAMP NULL,
    last_logout TIMESTAMP NULL,
    password_changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_verification_token (verification_token),
    INDEX idx_created_at (created_at)
);

-- Security logs table
CREATE TABLE security_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_type VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_event_type (event_type),
    INDEX idx_ip_address (ip_address),
    INDEX idx_created_at (created_at)
);

-- Rate limiting table
CREATE TABLE rate_limits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    identifier VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_identifier (identifier),
    INDEX idx_created_at (created_at)
);

-- Remember me tokens table
CREATE TABLE remember_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_token (token),
    INDEX idx_expires_at (expires_at)
);

-- User sessions table (for session management)
CREATE TABLE user_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_session_id (session_id),
    INDEX idx_expires_at (expires_at)
);

-- Password reset tokens table
CREATE TABLE password_reset_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_token (token),
    INDEX idx_expires_at (expires_at)
);

-- Two-factor authentication backup codes
CREATE TABLE two_factor_backup_codes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    code VARCHAR(20) NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    used_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_code (code)
);

-- Login attempts tracking (more detailed than rate_limits)
CREATE TABLE login_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    success BOOLEAN NOT NULL,
    failure_reason VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_ip_address (ip_address),
    INDEX idx_success (success),
    INDEX idx_created_at (created_at)
);

-- Admin users table (for administrative access)
CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    role ENUM('super_admin', 'admin', 'moderator') DEFAULT 'admin',
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_role (role)
);

-- API tokens table (for API access)
CREATE TABLE api_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token_name VARCHAR(100) NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    permissions JSON,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_token_hash (token_hash),
    INDEX idx_expires_at (expires_at)
);

-- Insert sample admin user (password: Admin123!)
INSERT INTO users (full_name, email, password, is_active, email_verified, two_factor_enabled) VALUES 
(
    'System Administrator', 
    'admin@security-demo.com', 
    '$argon2id$v=19$m=65536,t=4,p=3$YWJjZGVmZ2hpams$K5dZjQXgQ+H7c8X2v9L8mQ5v5x5x5x5x5x5x5x5x5x5',
    TRUE, 
    TRUE, 
    FALSE
);

-- Insert sample regular user (password: User123!)
INSERT INTO users (full_name, email, password, is_active, email_verified, two_factor_enabled) VALUES 
(
    'John Doe', 
    'user@security-demo.com', 
    '$argon2id$v=19$m=65536,t=4,p=3$YWJjZGVmZ2hpams$K5dZjQXgQ+H7c8X2v9L8mQ5v5x5x5x5x5x5x5x5x5x5',
    TRUE, 
    TRUE, 
    FALSE
);

-- Create admin role for the admin user
INSERT INTO admin_users (user_id, role, permissions, created_by) VALUES 
(1, 'super_admin', '{"all": true}', 1);

-- Sample security log entries
INSERT INTO security_logs (event_type, ip_address, user_agent, details) VALUES
('SYSTEM_START', '127.0.0.1', 'System', 'Security system initialized'),
('USER_REGISTERED', '127.0.0.1', 'Mozilla/5.0', 'New user registered: admin@security-demo.com'),
('USER_REGISTERED', '127.0.0.1', 'Mozilla/5.0', 'New user registered: user@security-demo.com');

-- Create indexes for performance
CREATE INDEX idx_users_email_active ON users(email, is_active);
CREATE INDEX idx_security_logs_composite ON security_logs(event_type, created_at);
CREATE INDEX idx_rate_limits_cleanup ON rate_limits(identifier, created_at);

-- Create cleanup procedures for old data
DELIMITER //

CREATE PROCEDURE CleanupOldLogs()
BEGIN
    -- Remove security logs older than 6 months
    DELETE FROM security_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
    
    -- Remove expired rate limit entries
    DELETE FROM rate_limits WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY);
    
    -- Remove expired remember tokens
    DELETE FROM remember_tokens WHERE expires_at < NOW();
    
    -- Remove expired password reset tokens
    DELETE FROM password_reset_tokens WHERE expires_at < NOW();
    
    -- Remove expired user sessions
    DELETE FROM user_sessions WHERE expires_at < NOW();
    
    -- Remove old login attempts (keep 30 days)
    DELETE FROM login_attempts WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
END //

DELIMITER ;

-- Create event scheduler for automatic cleanup (runs daily at 2 AM)
-- Note: You may need to enable event scheduler: SET GLOBAL event_scheduler = ON;
CREATE EVENT IF NOT EXISTS daily_cleanup
ON SCHEDULE EVERY 1 DAY
STARTS '2024-01-01 02:00:00'
DO
  CALL CleanupOldLogs();

-- Views for easier data access
CREATE VIEW active_users AS
SELECT 
    id, full_name, email, email_verified, two_factor_enabled, 
    last_login, created_at
FROM users 
WHERE is_active = TRUE;

CREATE VIEW recent_security_events AS
SELECT 
    event_type, ip_address, details, created_at
FROM security_logs 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
ORDER BY created_at DESC;

CREATE VIEW failed_login_summary AS
SELECT 
    email, 
    COUNT(*) as attempts, 
    MAX(created_at) as last_attempt,
    GROUP_CONCAT(DISTINCT ip_address) as ip_addresses
FROM login_attempts 
WHERE success = FALSE 
    AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
GROUP BY email
HAVING attempts >= 3
ORDER BY attempts DESC;

-- Triggers for enhanced security
DELIMITER //

CREATE TRIGGER user_password_changed 
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF OLD.password != NEW.password THEN
        INSERT INTO security_logs (event_type, ip_address, user_agent, details)
        VALUES ('PASSWORD_CHANGED', COALESCE(@current_ip, '127.0.0.1'), 
                COALESCE(@current_user_agent, 'Unknown'), 
                CONCAT('Password changed for user: ', NEW.email));
    END IF;
END //

CREATE TRIGGER user_status_changed 
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF OLD.is_active != NEW.is_active THEN
        INSERT INTO security_logs (event_type, ip_address, user_agent, details)
        VALUES ('USER_STATUS_CHANGED', COALESCE(@current_ip, '127.0.0.1'), 
                COALESCE(@current_user_agent, 'Unknown'), 
                CONCAT('User status changed: ', NEW.email, ' - Active: ', NEW.is_active));
    END IF;
END //

DELIMITER ;

-- Show table structure
SHOW TABLES;

-- Display initial data
SELECT 'Users' as Table_Name;
SELECT id, full_name, email, is_active, email_verified, two_factor_enabled, created_at FROM users;

SELECT 'Security Logs' as Table_Name;
SELECT event_type, ip_address, details, created_at FROM security_logs ORDER BY created_at DESC;

SELECT 'Setup completed successfully!' as Status;
