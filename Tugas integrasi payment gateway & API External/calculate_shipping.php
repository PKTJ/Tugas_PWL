<?php
require_once 'config/rajaongkir.php';
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = $_POST['nama_barang'] ?? '';
    $berat = (int)($_POST['berat'] ?? 0);
    $provinsi_id = $_POST['provinsi'] ?? '';
    $kota_id = $_POST['kota'] ?? '';
    
    if (empty($nama_barang) || $berat <= 0 || empty($provinsi_id) || empty($kota_id)) {
        die("Data tidak lengkap!");
    }
    
    // Hitung ongkir menggunakan RajaOngkir API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => RAJAONGKIR_BASE_URL . "cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'origin' => '501', // Yogyakarta (default)
            'destination' => $kota_id,
            'weight' => $berat,
            'courier' => 'jne'
        ]),
        CURLOPT_HTTPHEADER => [
            "key: " . RAJAONGKIR_API_KEY,
            "content-type: application/x-www-form-urlencoded"
        ]
    ]);
    
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if (isset($data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'])) {
            $ongkir = $data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'];
            
            // Simpan data ke session untuk checkout
            session_start();
            $_SESSION['order_data'] = [
                'nama_barang' => $nama_barang,
                'berat' => $berat,
                'provinsi_id' => $provinsi_id,
                'kota_id' => $kota_id,
                'ongkir' => $ongkir
            ];
            
            // Redirect ke halaman checkout
            header("Location: checkout.php");
            exit;
        }
    }
    
    die("Gagal menghitung ongkir. Silakan coba lagi.");
}
?>
