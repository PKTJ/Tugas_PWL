<?php
header('Content-Type: application/json');
include 'koneksi.php';

$sql = "SELECT nim, nama, jurusan FROM mahasiswa ORDER BY nim";
$result = $koneksi->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>