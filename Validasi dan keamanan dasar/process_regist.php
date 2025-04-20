<?php
// Start session
session_start();

// Include database configuration
require_once 'fungsi.php';

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Periksa apakah form telah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // verify CSRF token
    if (!isset($_POST['token']) || !isset($_SESSION['token']) || $_POST['token'] !== $_SESSION['token']) {
        die("CSRF token validation failed. Access denied!");
    }
    
    // Reset token untuk menhindari reuse
    $_SESSION['token'] = bin2hex(random_bytes(32));
    
    // Initialize error array
    $errors = [];
    
    // validasi input
    $username = isset($_POST['username']) ? sanitizeInput($_POST['username']) : '';
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $full_name = isset($_POST['full_name']) ? sanitizeInput($_POST['full_name']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Username validation
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores";
    }
    
    // Email validation
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Full name validation
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    // Password validation
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    
    // Password confirmation
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // jika tidak ada eror, proses registration
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // gunakan prepared statement untuk menghindari SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $full_name);
        
        // Try to execute the statement
        if ($stmt->execute()) {
            // Registration successful
            $_SESSION['registration_success'] = true;
            header("Location: success_regist.php");
            exit();
        } else {
            // Periksa apakah kesalahan disebabkan oleh unique constraint
            if ($conn->errno === 1062) {
                if (strpos($conn->error, 'username') !== false) {
                    $errors[] = "Username already exists";
                } elseif (strpos($conn->error, 'email') !== false) {
                    $errors[] = "Email already exists";
                } else {
                    $errors[] = "Registration failed: " . $conn->error;
                }
            } else {
                $errors[] = "Registration failed: " . $conn->error;
            }
        }
        
        $stmt->close();
    }
    
    // Jika ada kesalahan, arahkan kembali ke formulir pendaftaran
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        header("Location: register.php");
        exit();
    }
} else {
    // Jika seseorang mencoba mengakses halaman ini secara langsung tanpa mengirimkan formulir
    header("Location: register.php");
    exit();
}