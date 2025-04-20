<?php
// Start session for CSRF protection
session_start();

// Generate a new CSRF token if not exists
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Token that will be used in the form
$token = $_SESSION['token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Form Registrasi</h1>
        
        <div id="error-container" class="error-message"></div>
        
        <form id="registration-form" action="process_regist.php" method="POST" onsubmit="return validateForm()">
            <!-- CSRF Protection -->
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Masukan username (alphanumeric only)" required>
                <span class="error-text" id="username-error"></span>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Masukan alamat email" required>
                <span class="error-text" id="email-error"></span>
            </div>
            
            <div class="form-group">
                <label for="full_name">NAma lengkap:</label>
                <input type="text" id="full_name" name="full_name" placeholder="Masukan nama lengkap anda" required>
                <span class="error-text" id="full-name-error"></span>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Masukan password (minimum 8 characters)" required>
                <span class="error-text" id="password-error"></span>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">konfirmasi Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password" required>
                <span class="error-text" id="confirm-password-error"></span>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
        </form>
    </div>
    
    <script src="js/validation.js"></script>
</body>
</html>