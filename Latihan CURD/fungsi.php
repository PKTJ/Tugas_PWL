<?php
//membuat koneksi ke database mysql
 //   $koneksi=mysqli_connect('localhost','root','','pwlgenap2019-akademik');
 // koneksi bisa seperti dibari atas ini atau dibawah 
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
?>
