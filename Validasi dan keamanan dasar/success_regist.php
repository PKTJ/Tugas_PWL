<?php
// Start session
session_start();

// Check if user was redirected properly
if (!isset($_SESSION['registration_success']) || $_SESSION['registration_success'] !== true) {
    header("Location: index.php");
    exit();
}

// Clear the success flag
unset($_SESSION['registration_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="success-message">
            <h1>Registration Successful!</h1>
            <p>Thank you for registering. Your account has been created successfully.</p>
            <a href="index.php" class="btn">Return to Home</a>
        </div>
    </div>
</body>
</html>