# Tugas Integrasi Payment Gateway & API External

Aplikasi web PHP yang mengintegrasikan payment gateway Midtrans dengan API RajaOngkir untuk sistem checkout dengan perhitungan ongkir otomatis.

## Fitur

1. **Form Pemesanan** - Input data barang dan tujuan pengiriman
2. **Integrasi API RajaOngkir** - Perhitungan ongkir otomatis berdasarkan provinsi dan kota tujuan
3. **Payment Gateway Midtrans** - Proses pembayaran menggunakan Snap API
4. **Database Logging** - Penyimpanan data transaksi dan log API calls

## Struktur File

```
├── index.php                 # Redirect ke form pemesanan
├── views/
│   └── order_form.php        # Form pemesanan barang
├── api/
│   ├── get_provinces.php     # API untuk mendapatkan daftar provinsi
│   └── get_cities.php        # API untuk mendapatkan daftar kota
├── config/
│   ├── rajaongkir.php       # Konfigurasi API RajaOngkir
│   └── midtrans.php         # Contoh konfigurasi Midtrans
├── calculate_shipping.php    # Proses perhitungan ongkir
├── checkout.php             # Halaman checkout
├── charge.php              # Generate Snap token Midtrans
├── notification.php        # Handle callback Midtrans
├── config.php              # Konfigurasi Midtrans utama
├── database.php           # Konfigurasi database
├── db/
│   └── payment_gateway.sql # Script database
└── vendor/                # Dependencies Composer
```

## Setup & Instalasi

### 1. Install Dependencies
```bash
composer install
```

### 2. Database Setup
1. Import file `db/payment_gateway.sql` ke MySQL
2. Update konfigurasi database di `database.php`

### 3. API Keys Configuration

#### RajaOngkir
1. Daftar di [RajaOngkir.com](https://rajaongkir.com)
2. Dapatkan API Key
3. Update di `config/rajaongkir.php`:
```php
define("RAJAONGKIR_API_KEY", "your_api_key_here");
```

#### Midtrans
1. Daftar di [Midtrans.com](https://midtrans.com)
2. Dapatkan Server Key dan Client Key dari dashboard
3. Update di `config.php`:
```php
\Midtrans\Config::$serverKey = 'your_server_key_here';
\Midtrans\Config::$clientKey = 'your_client_key_here';
```
4. Update client key di `checkout.php`

### 4. Webhook Notification (Optional)
Untuk production, set notification URL di dashboard Midtrans ke:
```
https://yourdomain.com/notification.php
```

## Penggunaan

1. Akses `index.php` atau langsung ke `views/order_form.php`
2. Isi form pemesanan:
   - Nama barang
   - Berat barang (gram)
   - Pilih provinsi tujuan
   - Pilih kota tujuan
3. Submit form untuk menghitung ongkir
4. Klik "Bayar Sekarang" untuk proses pembayaran
5. Selesaikan pembayaran melalui Snap Midtrans

## Teknologi yang Digunakan

- **PHP 7.4+**
- **MySQL/MariaDB**
- **Midtrans Snap API** - Payment Gateway
- **RajaOngkir API** - Shipping Cost Calculator
- **Composer** - Dependency Management
- **JavaScript** - Frontend interaction

## Notes

- Mode default: Sandbox/Testing
- Untuk production, ubah `\Midtrans\Config::$isProduction = true`
- Pastikan semua API keys sudah dikonfigurasi dengan benar
- Database harus dibuat sebelum menjalankan aplikasi

## Error Handling

Aplikasi dilengkapi dengan:
- Validasi input form
- Error handling untuk API calls
- Database transaction logging
- Callback notification handling
