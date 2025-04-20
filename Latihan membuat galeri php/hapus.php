<?php
include 'koneksi.php';

// Check if ID is set
if (!isset($_POST['id'])) {
    header("Location: galeri.php");
    exit();
}

$id = $_POST['id'];
$filepath = $_POST['filepath'];
$thumbpath = $_POST['thumbpath'];
$croppath = $_POST['croppath'] ?? '';
$originalpath = str_replace('resized', 'original', $filepath);

// Delete files from server
if (file_exists($filepath)) unlink($filepath);
if (file_exists($thumbpath)) unlink($thumbpath);
if (file_exists($croppath)) unlink($croppath);
if (file_exists($originalpath)) unlink($originalpath);

// Delete record from database
$sql = "DELETE FROM gambar WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Redirect back to gallery
header("Location: galeri.php");
exit();
?>