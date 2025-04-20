<?php
// Start session for CSRF protection
session_start();

// Generate a new CSRF token
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM REGISTRASI</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Sistem registrasi keamanan</h1>
        <p>Selamat datang di sistem registrasi yang aman. Silakan mendaftar untuk melanjutkan.</p>
        
        <div class="button-container">
            <a href="register.php" class="btn">Resgistrasi sekarang</a>
        </div>
    </div>
</body>
</html>