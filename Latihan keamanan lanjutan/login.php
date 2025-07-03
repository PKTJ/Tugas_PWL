<?php
require_once 'config.php';
require_once 'db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !SecurityUtils::validateCSRFToken($_POST['csrf_token'])) {
        SecurityUtils::logSecurityEvent('CSRF_ATTACK', 'Invalid CSRF token on login');
        header('Location: index.php?error=csrf');
        exit();
    }
    
    // Get and sanitize input
    $email = SecurityUtils::sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);
    
    // Validate input
    if (empty($email) || empty($password)) {
        $error_message = 'Email and password are required.';
    } elseif (!SecurityUtils::validateEmail($email)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // Check rate limiting
        $rate_limit_identifier = $_SERVER['REMOTE_ADDR'] . '_login';
        if (!SecurityUtils::checkRateLimit($rate_limit_identifier, MAX_LOGIN_ATTEMPTS, LOCKOUT_TIME)) {
            SecurityUtils::logSecurityEvent('RATE_LIMIT_EXCEEDED', "Login attempts from: $email");
            $error_message = 'Too many login attempts. Please try again in 15 minutes.';
        } else {
            try {
                $db = DatabaseConnection::getInstance()->getConnection();
                
                // Get user data
                $stmt = $db->prepare("SELECT id, email, password, is_active, failed_attempts, last_failed_attempt, two_factor_enabled FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if (!$user) {
                    SecurityUtils::logSecurityEvent('LOGIN_FAILED', "Invalid email: $email");
                    $error_message = 'Invalid email or password.';
                } elseif (!$user['is_active']) {
                    SecurityUtils::logSecurityEvent('LOGIN_FAILED', "Inactive account: $email");
                    $error_message = 'Your account has been deactivated. Please contact support.';
                } elseif ($user['failed_attempts'] >= MAX_LOGIN_ATTEMPTS && 
                         $user['last_failed_attempt'] && 
                         (time() - strtotime($user['last_failed_attempt'])) < LOCKOUT_TIME) {
                    SecurityUtils::logSecurityEvent('LOGIN_FAILED', "Account locked: $email");
                    $error_message = 'Account temporarily locked due to multiple failed attempts.';
                } elseif (!SecurityUtils::verifyPassword($password, $user['password'])) {
                    // Update failed attempts
                    $stmt = $db->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_attempt = NOW() WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    
                    SecurityUtils::logSecurityEvent('LOGIN_FAILED', "Invalid password for: $email");
                    $error_message = 'Invalid email or password.';
                } else {
                    // Successful login - reset failed attempts
                    $stmt = $db->prepare("UPDATE users SET failed_attempts = 0, last_failed_attempt = NULL, last_login = NOW() WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    
                    // Set session variables
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['login_time'] = time();
                    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
                    
                    // Handle remember me
                    if ($remember_me) {
                        $token = bin2hex(random_bytes(32));
                        $expires = time() + (30 * 24 * 60 * 60); // 30 days
                        
                        // Store remember token in database
                        $stmt = $db->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, FROM_UNIXTIME(?))");
                        $stmt->execute([$user['id'], hash('sha256', $token), $expires]);
                        
                        // Set secure cookie
                        setcookie('remember_token', $token, [
                            'expires' => $expires,
                            'path' => '/',
                            'secure' => true,
                            'httponly' => true,
                            'samesite' => 'Strict'
                        ]);
                    }
                    
                    SecurityUtils::logSecurityEvent('LOGIN_SUCCESS', "User logged in: $email");
                    
                    // Check for 2FA
                    if ($user['two_factor_enabled']) {
                        $_SESSION['2fa_required'] = true;
                        header('Location: two_factor.php');
                    } else {
                        header('Location: dashboard.php');
                    }
                    exit();
                }
            } catch (Exception $e) {
                error_log("Login error: " . $e->getMessage());
                $error_message = 'An error occurred. Please try again.';
            }
        }
    }
}

// Redirect back to login with error
if (!empty($error_message)) {
    header('Location: index.php?error=' . urlencode($error_message));
    exit();
}

// If accessed directly, redirect to index
header('Location: index.php');
exit();
?>