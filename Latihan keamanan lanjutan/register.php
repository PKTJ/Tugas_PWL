<?php
require_once 'config.php';
require_once 'db.php';

// Check if user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

$error_messages = [];
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !SecurityUtils::validateCSRFToken($_POST['csrf_token'])) {
        SecurityUtils::logSecurityEvent('CSRF_ATTACK', 'Invalid CSRF token on registration');
        $error_messages[] = 'Security validation failed. Please try again.';
    } else {
        // Get and sanitize input
        $full_name = SecurityUtils::sanitizeInput($_POST['full_name'] ?? '');
        $email = SecurityUtils::sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $terms_accepted = isset($_POST['terms_accepted']);
        
        // Validate input
        if (empty($full_name)) {
            $error_messages[] = 'Full name is required.';
        }
        
        if (empty($email)) {
            $error_messages[] = 'Email is required.';
        } elseif (!SecurityUtils::validateEmail($email)) {
            $error_messages[] = 'Please enter a valid email address.';
        }
        
        if (empty($password)) {
            $error_messages[] = 'Password is required.';
        } else {
            $password_errors = SecurityUtils::validatePasswordStrength($password);
            $error_messages = array_merge($error_messages, $password_errors);
        }
        
        if ($password !== $confirm_password) {
            $error_messages[] = 'Passwords do not match.';
        }
        
        if (!$terms_accepted) {
            $error_messages[] = 'You must accept the terms and conditions.';
        }
        
        // Check rate limiting
        $rate_limit_identifier = $_SERVER['REMOTE_ADDR'] . '_register';
        if (!SecurityUtils::checkRateLimit($rate_limit_identifier, 5, 3600)) { // 5 attempts per hour
            $error_messages[] = 'Too many registration attempts. Please try again later.';
        }
        
        // If no errors, proceed with registration
        if (empty($error_messages)) {
            try {
                $db = DatabaseConnection::getInstance()->getConnection();
                
                // Check if email already exists
                $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error_messages[] = 'An account with this email already exists.';
                } else {
                    // Hash password
                    $hashed_password = SecurityUtils::hashPassword($password);
                    
                    // Generate verification token
                    $verification_token = bin2hex(random_bytes(32));
                    
                    // Insert user
                    $stmt = $db->prepare("INSERT INTO users (full_name, email, password, verification_token, created_at) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->execute([$full_name, $email, $hashed_password, $verification_token]);
                    
                    SecurityUtils::logSecurityEvent('USER_REGISTERED', "New user registered: $email");
                    
                    $success_message = 'Registration successful! You can now log in with your credentials.';
                    
                    // In a real application, you would send a verification email here
                    // For this demo, we'll auto-activate the account
                    $user_id = $db->lastInsertId();
                    $stmt = $db->prepare("UPDATE users SET is_active = 1, email_verified = 1 WHERE id = ?");
                    $stmt->execute([$user_id]);
                }
            } catch (Exception $e) {
                error_log("Registration error: " . $e->getMessage());
                $error_messages[] = 'An error occurred during registration. Please try again.';
            }
        }
    }
}

$csrf_token = SecurityUtils::generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Advanced Security Practice</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="register-form">
            <div class="form-header">
                <i class="fas fa-user-plus"></i>
                <h2>Create Account</h2>
                <p>Join our secure platform</p>
            </div>

            <?php if (!empty($error_messages)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul>
                        <?php foreach ($error_messages as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                    <p><a href="index.php">Click here to login</a></p>
                </div>
            <?php endif; ?>

            <form method="POST" class="form" id="registerForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="full_name">
                        <i class="fas fa-user"></i>
                        Full Name
                    </label>
                    <input type="text" id="full_name" name="full_name" required 
                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password', 'password-toggle-icon')">
                            <i class="fas fa-eye" id="password-toggle-icon"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="password-strength"></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i>
                        Confirm Password
                    </label>
                    <div class="password-input">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', 'confirm-toggle-icon')">
                            <i class="fas fa-eye" id="confirm-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms_accepted" value="1" required>
                        <span class="checkmark"></span>
                        I agree to the <a href="#" onclick="showTerms()">Terms and Conditions</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </button>
            </form>

            <div class="form-footer">
                <p>Already have an account? <a href="index.php">Sign in here</a></p>
            </div>

            <!-- Password Requirements -->
            <div class="password-requirements">
                <h3><i class="fas fa-info-circle"></i> Password Requirements</h3>
                <ul>
                    <li id="req-length"><i class="fas fa-times"></i> At least <?php echo MIN_PASSWORD_LENGTH; ?> characters</li>
                    <li id="req-uppercase"><i class="fas fa-times"></i> At least one uppercase letter</li>
                    <li id="req-lowercase"><i class="fas fa-times"></i> At least one lowercase letter</li>
                    <li id="req-number"><i class="fas fa-times"></i> At least one number</li>
                    <li id="req-special"><i class="fas fa-times"></i> At least one special character</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function showTerms() {
            alert('Terms and Conditions:\n\n1. Use this system responsibly\n2. Do not share your credentials\n3. Report security issues immediately\n4. Follow all security policies');
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('password-strength');
            const requirements = {
                'req-length': password.length >= <?php echo MIN_PASSWORD_LENGTH; ?>,
                'req-uppercase': /[A-Z]/.test(password),
                'req-lowercase': /[a-z]/.test(password),
                'req-number': /[0-9]/.test(password),
                'req-special': /[^A-Za-z0-9]/.test(password)
            };
            
            let strength = 0;
            let strengthText = '';
            let strengthClass = '';
            
            // Update requirement indicators
            for (const [reqId, met] of Object.entries(requirements)) {
                const reqElement = document.getElementById(reqId);
                const icon = reqElement.querySelector('i');
                
                if (met) {
                    icon.className = 'fas fa-check';
                    reqElement.style.color = '#4CAF50';
                    strength++;
                } else {
                    icon.className = 'fas fa-times';
                    reqElement.style.color = '#f44336';
                }
            }
            
            // Calculate strength
            if (strength === 0) {
                strengthText = '';
            } else if (strength <= 2) {
                strengthText = 'Weak';
                strengthClass = 'weak';
            } else if (strength <= 4) {
                strengthText = 'Medium';
                strengthClass = 'medium';
            } else {
                strengthText = 'Strong';
                strengthClass = 'strong';
            }
            
            strengthDiv.textContent = strengthText;
            strengthDiv.className = 'password-strength ' + strengthClass;
        });

        // Password confirmation checker
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword === '') {
                this.style.borderColor = '';
                return;
            }
            
            if (password === confirmPassword) {
                this.style.borderColor = '#4CAF50';
            } else {
                this.style.borderColor = '#f44336';
            }
        });
    </script>
</body>
</html>