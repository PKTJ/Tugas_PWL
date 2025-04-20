<?php
//membuat koneksi ke database mysql
$host = "localhost";
$user = "root";
$password = "";
$database = "users";

// Create connection
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>