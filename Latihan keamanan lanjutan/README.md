# Sistem Praktik Keamanan Lanjutan

Aplikasi web berbasis PHP yang komprehensif untuk mendemonstrasikan praktik dan implementasi keamanan tingkat lanjut.

## 🚀 Fitur

### Fitur Keamanan
- **Perlindungan CSRF** – Melindungi semua form dari Cross-Site Request Forgery  
- **Pembatasan Laju (Rate Limiting)** – Mencegah serangan brute force dan penyalahgunaan  
- **Hashing Kata Sandi Aman** – Menggunakan algoritma Argon2ID  
- **Keamanan Sesi** – Penanganan sesi yang aman dengan batas waktu  
- **Validasi & Sanitasi Input** – Validasi input yang menyeluruh  
- **Header Keamanan** – Implementasi header HTTP untuk keamanan  
- **Pencegahan SQL Injection** – Menggunakan prepared statements  
- **Perlindungan XSS** – Encoding dan validasi output  
- **Pencatatan Keamanan** – Jejak audit komprehensif  
- **Fungsi “Remember Me”** – Login persisten yang aman  
- **Validasi Kekuatan Kata Sandi** – Pengecekan kekuatan kata sandi secara real-time  

### Manajemen Pengguna
- **Registrasi Pengguna** – Sistem registrasi aman dengan validasi  
- **Autentikasi Pengguna** – Sistem login aman  
- **Kebijakan Kata Sandi** – Penerapan persyaratan kata sandi kuat  
- **Penguncian Akun** – Penguncian sementara setelah beberapa gagal login  
- **Verifikasi Email** – Sistem verifikasi email (placeholder)  
- **Autentikasi Dua Faktor** – Siap untuk implementasi 2FA  

### Fitur Dasbor
- **Ringkasan Keamanan** – Menampilkan status keamanan  
- **Pemantauan Aktivitas** – Peristiwa keamanan terbaru  
- **Manajemen Sesi** – Melihat dan mengelola sesi aktif  
- **Aksi Cepat** – Akses mudah ke fungsi keamanan  
- **Tips Keamanan** – Edukasi keamanan bawaan  

## 📋 Persyaratan

- PHP 7.4+ (disarankan PHP 8.0+)  
- MySQL 5.7+ atau MariaDB 10.2+  
- Server web (Apache/Nginx)  
- Ekstensi OpenSSL  
- Ekstensi PDO MySQL  

## 🛠️ Instalasi

1. **Buat database**  
   - Impor `database_schema.sql` ke server MySQL Anda  
   - Ini akan membuat database dan semua tabel yang diperlukan  

2. **Konfigurasi koneksi database**  
   - Edit `config.php`  
   - Perbarui kredensial database:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USERNAME', 'your_username');
   define('DB_PASSWORD', 'your_password');
   define('DB_NAME', 'security_advanced_db');
