<?php
header("Content-Type: application/json");
require 'database.php';
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
  case 'GET':
    $sql = "SELECT * FROM buku";
    $result = $koneksi->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
    echo json_encode($data);
    break;
  case 'POST':
    $input = json_decode(file_get_contents("php://input"), true);
    $judul = $input['judul'];
    $penulis = $input['penulis'];
    $tahun_terbit = $input['tahun_terbit'];
    $sql = "INSERT INTO buku (judul, penulis, tahun_terbit) VALUES 
    ('$judul', '$penulis','$tahun_terbit')";
    echo json_encode(['status' => $koneksi->query($sql)]);
    break;
  case 'PUT':
    $input = json_decode(file_get_contents("php://input"), true);
    $id = $input['id'];
    $judul = $input['judul'];
    $penulis = $input['penulis'];
    $tahun_terbit = $input['tahun_terbit'];
    $sql = "UPDATE buku SET judul='$judul', penulis='$penulis',
    tahun_terbit='$tahun_terbit' WHEREid=$id";
    echo json_encode(['status' => $koneksi->query(query: $sql)]);
    break;
  case 'DELETE':
    $input = json_decode(file_get_contents("php://input"), true);
    $id = $input['id'];
    $sql = "DELETE FROM buku WHERE id=$id";
    echo json_encode(['status' => $koneksi->query($sql)]);
    break;
  default:
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    break;
}
?>