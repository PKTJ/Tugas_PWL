<?php
require_once 'vendor/autoload.php';

// Ganti dengan key Anda dari dashboard Midtrans
\Midtrans\Config::$serverKey  = 'SB-Mid-server-xxxxxxxxxxxxxxxxxxxx'; // Ganti dengan key Anda dari dashboard Midtrans
\Midtrans\Config::$clientKey  = 'SB-Mid-client-xxxxxxxxxxxxxxxxxxxx'; // Ganti dengan key Anda dari dashboard Midtrans

// Mode sandbox/testing
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized  = true;
\Midtrans\Config::$is3ds        = true;
