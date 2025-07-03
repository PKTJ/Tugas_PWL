<?php
// File untuk checking konfigurasi
?>
<!DOCTYPE html>
<html>
<head>
    <title>Konfigurasi Setup</title>
    <style>
        .config-box { 
            border: 1px solid #ddd; 
            padding: 15px; 
            margin: 10px 0; 
            border-radius: 5px; 
            background: #f9f9f9; 
        }
        .error { color: red; }
        .success { color: green; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <h2>Setup & Konfigurasi</h2>
    
    <div class="config-box">
        <h3>1. Database Setup</h3>
        <p>Buat database MySQL dan import file: <code>db/payment_gateway.sql</code></p>
        <p>Update konfigurasi di: <code>database.php</code></p>
    </div>
    
    <div class="config-box">
        <h3>2. Composer Dependencies</h3>
        <p>Jalankan command: <code>composer install</code></p>
        <p>Untuk menginstall Midtrans PHP SDK</p>
    </div>
    
    <div class="config-box">
        <h3>3. API Keys yang Diperlukan</h3>
        
        <h4>RajaOngkir</h4>
        <ul>
            <li>Daftar di: <a href="https://rajaongkir.com" target="_blank">rajaongkir.com</a></li>
            <li>Dapatkan API Key</li>
            <li>Update di: <code>config/rajaongkir.php</code></li>
            <li>Ganti: <code>ISI_DENGAN_API_KEY_ANDA</code></li>
        </ul>
        
        <h4>Midtrans</h4>
        <ul>
            <li>Daftar di: <a href="https://midtrans.com" target="_blank">midtrans.com</a></li>
            <li>Pilih environment: Sandbox (testing) atau Production</li>
            <li>Dapatkan Server Key dan Client Key</li>
            <li>Update di: <code>config.php</code></li>
            <li>Update client key di: <code>checkout.php</code></li>
        </ul>
    </div>
    
    <div class="config-box">
        <h3>4. File yang Perlu Dikonfigurasi</h3>
        <ul>
            <li><code>database.php</code> - Konfigurasi database MySQL</li>
            <li><code>config/rajaongkir.php</code> - API Key RajaOngkir</li>
            <li><code>config.php</code> - Server Key & Client Key Midtrans</li>
            <li><code>checkout.php</code> - Client Key Midtrans (line 5)</li>
        </ul>
    </div>
    
    <div class="config-box">
        <h3>5. Testing</h3>
        <p>Setelah semua dikonfigurasi:</p>
        <ol>
            <li>Akses <a href="index.php">index.php</a></li>
            <li>Isi form pemesanan</li>
            <li>Test perhitungan ongkir</li>
            <li>Test proses pembayaran</li>
            <li>Cek <a href="transactions.php">status transaksi</a></li>
        </ol>
    </div>
    
    <div class="config-box">
        <h3>6. Catatan Penting</h3>
        <ul>
            <li>Mode default: Sandbox/Testing</li>
            <li>Untuk production: ubah <code>$isProduction = true</code> di config.php</li>
            <li>Pastikan server mendukung PHP 7.4+ dan extension cURL</li>
            <li>Untuk notification URL (webhook): set di dashboard Midtrans ke <code>notification.php</code></li>
        </ul>
    </div>
</body>
</html>
