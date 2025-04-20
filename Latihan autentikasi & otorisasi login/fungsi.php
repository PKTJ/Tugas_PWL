<?php
//membuat koneksi ke database mysql
$servername = "localhost";
$username = "root";
$password = "";
$database = "pwlgenap2019-akademik";

// Create connection
$koneksi = mysqli_connect($servername, $username, $password, $database);
 
// Check connection 
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";
//mysqli_close($conn);
?>
