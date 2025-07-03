# Advanced Security Practice System

A comprehensive PHP-based web application demonstrating advanced security practices and implementations.

## 🚀 Features

### Security Features
- **CSRF Protection** - Cross-Site Request Forgery protection on all forms
- **Rate Limiting** - Prevents brute force attacks and abuse
- **Secure Password Hashing** - Using Argon2ID algorithm
- **Session Security** - Secure session handling with timeout
- **Input Validation & Sanitization** - Comprehensive input validation
- **Security Headers** - Implemented security HTTP headers
- **SQL Injection Prevention** - Using prepared statements
- **XSS Protection** - Output encoding and validation
- **Security Logging** - Comprehensive audit trail
- **Remember Me Functionality** - Secure persistent login
- **Password Strength Validation** - Real-time password strength checking

### User Management
- **User Registration** - Secure user registration with validation
- **User Authentication** - Secure login system
- **Password Policy** - Enforced strong password requirements
- **Account Lockout** - Temporary lockout after failed attempts
- **Email Verification** - Email verification system (placeholder)
- **Two-Factor Authentication** - Ready for 2FA implementation

### Dashboard Features
- **Security Overview** - Display of security status
- **Activity Monitoring** - Recent security events
- **Session Management** - View and manage active sessions
- **Quick Actions** - Easy access to security functions
- **Security Tips** - Built-in security education

## 📋 Requirements

- PHP 7.4+ (PHP 8.0+ recommended)
- MySQL 5.7+ or MariaDB 10.2+
- Web server (Apache/Nginx)
- OpenSSL extension
- PDO MySQL extension

## 🛠️ Installation

1. **Create the database**
   - Import `database_schema.sql` into your MySQL server
   - This will create the database and all required tables

2. **Configure database connection**
   - Edit `config.php`
   - Update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USERNAME', 'your_username');
     define('DB_PASSWORD', 'your_password');
     define('DB_NAME', 'security_advanced_db');
     ```

3. **Update security keys**
   - Change the security keys in `config.php`:
     ```php
     define('SITE_KEY', 'your_unique_site_key_here');
     define('ENCRYPTION_KEY', 'your_32_character_encryption_key');
     ```

## 🔑 Default Accounts

The system comes with two pre-configured accounts for testing:

### Administrator Account
- **Email:** admin@security-demo.com
- **Password:** Admin123!

### Regular User Account
- **Email:** user@security-demo.com
- **Password:** User123!

⚠️ **Important:** Change these credentials immediately in production!

## 🏗️ File Structure

```
Latihan keamanan lanjutan/
├── index.php              # Main login page
├── login.php              # Login processing
├── register.php           # User registration
├── dashboard.php          # User dashboard
├── logout.php             # Logout functionality
├── config.php             # Configuration settings
├── db.php                 # Database connection & utilities
├── database_schema.sql    # Database structure
├── assets/
│   └── style.css         # CSS styles
└── README.md             # This file
```

## 🔒 Security Implementations

### 1. CSRF Protection
- Unique tokens generated for each session
- Validated on all form submissions
- Prevents cross-site request forgery attacks

### 2. Rate Limiting
- IP-based rate limiting
- Configurable attempt limits and timeouts
- Prevents brute force attacks

### 3. Password Security
- Argon2ID hashing algorithm
- Configurable password strength requirements
- Password change tracking

### 4. Session Security
- Secure session configuration
- Session timeout handling
- Session regeneration on login
- HttpOnly and Secure cookie flags

### 5. Input Validation
- Comprehensive server-side validation
- HTML encoding to prevent XSS
- Email format validation
- SQL injection prevention via prepared statements

### 6. Security Headers
- X-Content-Type-Options: nosniff
- X-Frame-Options: DENY
- X-XSS-Protection: 1; mode=block
- Strict-Transport-Security (HSTS)

### 7. Audit Logging
- All security events logged
- IP address and user agent tracking
- Configurable log retention

## 🚨 Security Best Practices

1. **Always use HTTPS** in production
2. **Update passwords** regularly
3. **Monitor security logs** for suspicious activity
4. **Keep software updated**
5. **Use strong database passwords**
6. **Regular security audits**
7. **Backup database** regularly

## 📄 License

This project is for educational purposes. Use responsibly and ensure compliance with applicable laws and regulations.

## ⚠️ Disclaimer

This system is designed for educational and demonstration purposes. While it implements many security best practices, additional security measures may be required for production use.

---

**Security Note:** This application demonstrates various security practices but should not be considered production-ready without additional security audits and testing.
