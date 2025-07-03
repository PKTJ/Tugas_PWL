<?php
require_once 'config.php';
require_once 'db.php';

// Check if user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

$error_message = '';
$success_message = '';

// Handle session timeout
if (isset($_GET['timeout'])) {
    $error_message = 'Your session has expired. Please log in again.';
}

// Handle logout
if (isset($_GET['logout'])) {
    $success_message = 'You have been successfully logged out.';
}

$csrf_token = SecurityUtils::generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Security Practice - Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <div class="form-header">
                <i class="fas fa-shield-alt"></i>
                <h2>Secure Login</h2>
                <p>Advanced Security Practice System</p>
            </div>

            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="password-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember_me" value="1">
                        <span class="checkmark"></span>
                        Remember me for 30 days
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

            <div class="form-footer">
                <p>Don't have an account? <a href="register.php">Create one here</a></p>
                <p><a href="#" onclick="showForgotPassword()">Forgot your password?</a></p>
            </div>

            <!-- Security Features Info -->
            <div class="security-info">
                <h3><i class="fas fa-info-circle"></i> Security Features</h3>
                <ul>
                    <li><i class="fas fa-check"></i> CSRF Protection</li>
                    <li><i class="fas fa-check"></i> Rate Limiting</li>
                    <li><i class="fas fa-check"></i> Session Security</li>
                    <li><i class="fas fa-check"></i> Password Hashing</li>
                    <li><i class="fas fa-check"></i> Input Validation</li>
                    <li><i class="fas fa-check"></i> Security Logging</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            
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

        function showForgotPassword() {
            alert('Forgot password functionality would be implemented here with secure password reset via email.');
        }

        // Clear any error messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>