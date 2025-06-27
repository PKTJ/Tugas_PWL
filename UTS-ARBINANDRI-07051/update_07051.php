<?php 
include 'koneksi_07051.php';

$id = $_POST['id'];
$nama_produk = $_POST['nama_produk'];
$kategori = $_POST['kategori'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];

mysqli_query($koneksi, "UPDATE produk SET 
    nama_produk='$nama_produk',
    kategori='$kategori',
    harga='$harga',
    stok='$stok'
    WHERE id='$id'");

header("location:index_07051.php");
?>