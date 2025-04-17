<?php
 
$servername = "localhost";
$username = "root";
$password = "";
$database = "curd_db";
 
// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);
 
// Check connection
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
   }
   
//echo "Connected successfully";
//mysqli_close($conn);
?>