<?php
session_start();

// Cek session atau cookie
if (!isset($_SESSION["user_id"])) {
    if (isset($_COOKIE["remember_user"])) {
        $_SESSION["user_id"] = $_COOKIE["remember_user"];
    } else {
        header("Location: login.php");
        exit();
    }
}

include "koneksi.php";
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$_SESSION["role"] = $user["role"]; // Update role dari database

// Otorisasi berdasarkan role
if ($_SESSION["role"] == "admin") {
    echo "<h1>Welcome Admin!</h1>";
} else {
    echo "<h1>Welcome User!</h1>";
}
?>

<h2>Selamat datang di Dashboard!</h2>
<a href="logout.php">Logout</a>