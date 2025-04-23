<?php 
include 'koneksi_07051.php';

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM produk WHERE id='$id'");

header("location:index_07051.php");
?>