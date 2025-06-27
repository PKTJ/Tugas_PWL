<?php 
include 'koneksi_07051.php';

$nama_produk = $_POST['nama_produk'];
$kategori = $_POST['kategori'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];

mysqli_query($koneksi, "INSERT INTO produk VALUES('', '$nama_produk', '$kategori', '$harga', '$stok')");

header("location:index_07051.php");
?>