<?php
require '../config/rajaongkir.php';

$province_id = $_GET['province_id'] ?? '';
if (!$province_id) {
    echo json_encode(["error" => "province_id required"]);
    exit;
}

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => RAJAONGKIR_BASE_URL . "city?province=$province_id",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "key: " . RAJAONGKIR_API_KEY
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

header('Content-Type: application/json');
echo $response;
