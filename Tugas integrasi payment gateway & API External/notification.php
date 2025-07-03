<?php
require_once 'config.php';
require_once 'database.php';

// Ambil raw POST data
$json_result = file_get_contents('php://input');
$result = json_decode($json_result, true);

// Verifikasi signature key
$signature_key = hash("sha512", $result['order_id'] . $result['status_code'] . $result['gross_amount'] . \Midtrans\Config::$serverKey);

if ($signature_key != $result['signature_key']) {
    http_response_code(400);
    echo "Invalid signature";
    exit;
}

// Update status transaksi di database
try {
    $order_id = $result['order_id'];
    $status = '';
    
    if ($result['transaction_status'] == 'capture') {
        if ($result['fraud_status'] == 'challenge') {
            $status = 'challenge';
        } else if ($result['fraud_status'] == 'accept') {
            $status = 'success';
        }
    } else if ($result['transaction_status'] == 'settlement') {
        $status = 'success';
    } else if ($result['transaction_status'] == 'pending') {
        $status = 'pending';
    } else if ($result['transaction_status'] == 'deny') {
        $status = 'failed';
    } else if ($result['transaction_status'] == 'expire') {
        $status = 'expired';
    } else if ($result['transaction_status'] == 'cancel') {
        $status = 'cancelled';
    }
    
    // Update status di database
    $stmt = $pdo->prepare("UPDATE transactions SET status = ?, payment_type = ?, updated_at = CURRENT_TIMESTAMP WHERE order_id = ?");
    $stmt->execute([$status, $result['payment_type'] ?? '', $order_id]);
    
    // Log callback
    $stmt = $pdo->prepare("INSERT INTO api_logs (api_type, endpoint, request_data, response_data, status_code) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['midtrans', 'notification', $json_result, 'OK', 200]);
    
    echo "OK";
    
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
?>
