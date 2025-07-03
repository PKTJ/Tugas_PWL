<?php
require_once 'config.php';
require_once 'database.php';

session_start();

// Ambil data dari session
$order_data = $_SESSION['order_data'] ?? null;

if (!$order_data) {
    http_response_code(400);
    echo json_encode(['error' => 'Data pesanan tidak ditemukan']);
    exit;
}

$order_id = 'ORDER-' . time();
$harga_barang = 50000; // Default harga barang
$total_amount = $harga_barang + $order_data['ongkir'];

$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' => $total_amount,
);

$item_details = array(
    array(
        'id' => 'barang1',
        'price' => $harga_barang,
        'quantity' => 1,
        'name' => $order_data['nama_barang']
    ),
    array(
        'id' => 'ongkir',
        'price' => $order_data['ongkir'],
        'quantity' => 1,
        'name' => "Ongkos Kirim"
    )
);

$customer_details = array(
    'first_name' => "Customer",
    'email' => "customer@example.com",
    'phone' => "08123456789",
);

$transaction = array(
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
    'customer_details' => $customer_details
);

try {
    $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    
    // Simpan transaksi ke database
    $stmt = $pdo->prepare("INSERT INTO transactions (order_id, nama_barang, berat, provinsi_id, kota_id, ongkir, total_amount, snap_token, customer_name, customer_email, customer_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $order_id,
        $order_data['nama_barang'],
        $order_data['berat'],
        $order_data['provinsi_id'],
        $order_data['kota_id'],
        $order_data['ongkir'],
        $total_amount,
        $snapToken,
        $customer_details['first_name'],
        $customer_details['email'],
        $customer_details['phone']
    ]);
    
    echo json_encode(['token' => $snapToken]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal membuat token pembayaran: ' . $e->getMessage()]);
}
?>
