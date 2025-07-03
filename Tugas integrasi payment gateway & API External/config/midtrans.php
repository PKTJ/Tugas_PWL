<?php
require_once '../vendor/autoload.php';
require_once '../config.php';

use Midtrans\Snap;
use Midtrans\Transaction;

// Data transaksi
$params = [
  'transaction_details' => [
    'order_id' => 'ORDER-'.time(),
    'gross_amount' => 100000,
  ],
  'customer_details' => [
    'first_name' => 'Andi',
    'email'      => 'andi@example.com',
  ],
];

// Generate Snap token
$snapToken = Snap::getSnapToken($params);
echo json_encode(['token' => $snapToken]);
