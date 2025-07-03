<?php
require '../config/rajaongkir.php';

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => RAJAONGKIR_BASE_URL . "province",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "key: " . RAJAONGKIR_API_KEY
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

header('Content-Type: application/json');
echo $response;
