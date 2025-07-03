<?php
// Database configuration with security best practices
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'security_advanced_db');

// Security configurations
define('SITE_KEY', 'your_secret_site_key_here_change_this');
define('ENCRYPTION_KEY', 'your_encryption_key_here_32_chars');
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_TIME', 900); // 15 minutes

// Password policy
define('MIN_PASSWORD_LENGTH', 8);
define('REQUIRE_UPPERCASE', true);
define('REQUIRE_LOWERCASE', true);
define('REQUIRE_NUMBERS', true);
define('REQUIRE_SPECIAL_CHARS', true);

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

// Start secure session
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Regenerate session ID periodically
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Check session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
    session_unset();
    session_destroy();
    header('Location: index.php?timeout=1');
    exit();
}
$_SESSION['last_activity'] = time();
?>