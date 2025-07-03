<?php
require_once 'config.php';
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    $email = $_SESSION['email'] ?? 'unknown';
    
    // Log the logout event
    SecurityUtils::logSecurityEvent('LOGOUT', "User logged out: $email");
    
    // Remove remember me token if exists
    if (isset($_COOKIE['remember_token'])) {
        $db = DatabaseConnection::getInstance()->getConnection();
        $token_hash = hash('sha256', $_COOKIE['remember_token']);
        $stmt = $db->prepare("DELETE FROM remember_tokens WHERE user_id = ? AND token = ?");
        $stmt->execute([$user_id, $token_hash]);
        
        // Clear the cookie
        setcookie('remember_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }
    
    // Update user's last logout time
    $db = DatabaseConnection::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE users SET last_logout = NOW() WHERE id = ?");
    $stmt->execute([$user_id]);
    
} catch (Exception $e) {
    error_log("Logout error: " . $e->getMessage());
}

// Clear all session data
session_unset();
session_destroy();

// Start a new session for flash messages
session_start();
session_regenerate_id(true);

// Redirect to login page with logout message
header('Location: index.php?logout=1');
exit();
?>