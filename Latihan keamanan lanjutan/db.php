<?php
require_once 'config.php';

class DatabaseConnection {
    private $connection;
    private static $instance = null;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USERNAME,
                DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true
                ]
            );
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    private function __wakeup() {}
}

// Security utility functions
class SecurityUtils {
    
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64 MB
            'time_cost' => 4,       // 4 iterations
            'threads' => 3          // 3 threads
        ]);
    }
    
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public static function validatePasswordStrength($password) {
        $errors = [];
        
        if (strlen($password) < MIN_PASSWORD_LENGTH) {
            $errors[] = "Password must be at least " . MIN_PASSWORD_LENGTH . " characters long";
        }
        
        if (REQUIRE_UPPERCASE && !preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }
        
        if (REQUIRE_LOWERCASE && !preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }
        
        if (REQUIRE_NUMBERS && !preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }
        
        if (REQUIRE_SPECIAL_CHARS && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }
        
        return $errors;
    }
    
    public static function logSecurityEvent($event, $details = '') {
        $db = DatabaseConnection::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO security_logs (event_type, ip_address, user_agent, details, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([
            $event,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            $details
        ]);
    }
    
    public static function checkRateLimit($identifier, $max_attempts, $time_window) {
        $db = DatabaseConnection::getInstance()->getConnection();
        
        // Clean old attempts
        $stmt = $db->prepare("DELETE FROM rate_limits WHERE identifier = ? AND created_at < DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->execute([$identifier, $time_window]);
        
        // Count recent attempts
        $stmt = $db->prepare("SELECT COUNT(*) as attempts FROM rate_limits WHERE identifier = ?");
        $stmt->execute([$identifier]);
        $result = $stmt->fetch();
        
        if ($result['attempts'] >= $max_attempts) {
            return false;
        }
        
        // Record this attempt
        $stmt = $db->prepare("INSERT INTO rate_limits (identifier, created_at) VALUES (?, NOW())");
        $stmt->execute([$identifier]);
        
        return true;
    }
}
?>