# Dokumentasi Program: Latihan Keamanan Dasar & Validasi

## Deskripsi
Program ini merupakan latihan pembuatan sistem registrasi pengguna berbasis PHP dengan penerapan keamanan dasar dan validasi, baik di sisi server maupun client. Fitur utama meliputi proteksi CSRF, validasi input, hash password, dan penggunaan prepared statement untuk mencegah SQL Injection.

## Struktur Folder & File
- `index.php` : Halaman utama, pengantar dan link ke form registrasi.
- `register.php` : Form registrasi dengan proteksi CSRF dan validasi client-side.
- `process_regist.php` : Proses backend untuk validasi, proteksi, dan penyimpanan data ke database.
- `success_regist.php` : Halaman notifikasi registrasi berhasil.
- `koneksi.php` : Konfigurasi koneksi ke database MySQL.
- `db/secure_registration.sql` : Skrip SQL pembuatan database dan tabel `users`.
- `css/style.css` : Styling halaman dan form.
- `js/validation.js` : Validasi form di sisi client (JavaScript).

## Alur Registrasi & Keamanan
1. **Proteksi CSRF**
   - Setiap form registrasi menyertakan token CSRF unik yang diverifikasi saat submit.
2. **Validasi Input**
   - Validasi dilakukan di sisi client (JavaScript) dan server (PHP):
     - Username: hanya huruf, angka, dan underscore.
     - Email: format email valid.
     - Nama lengkap: wajib diisi.
     - Password: minimal 8 karakter, konfirmasi password harus sama.
3. **Hash Password**
   - Password disimpan dalam database menggunakan hash (`password_hash`).
4. **Prepared Statement**
   - Query penyimpanan data menggunakan prepared statement untuk mencegah SQL Injection.
5. **Unique Constraint**
   - Username dan email tidak boleh sama dengan yang sudah terdaftar.

## Struktur Database (`users`)
| Field       | Type          | Keterangan         |
|-------------|---------------|--------------------|
| id          | int (AI, PK)  | ID user            |
| username    | varchar(50)   | Username unik      |
| email       | varchar(100)  | Email unik         |
| password    | varchar(255)  | Password hash      |
| full_name   | varchar(100)  | Nama lengkap       |
| created_at  | timestamp     | Waktu registrasi   |

## Cara Menjalankan
1. Import `db/secure_registration.sql` ke MySQL untuk membuat database dan tabel.
2. Pastikan konfigurasi di `koneksi.php` sesuai dengan environment Anda.
3. Jalankan aplikasi di server lokal (XAMPP/Laragon/dll).
4. Akses `index.php` untuk memulai proses registrasi.

## Catatan
- Validasi client-side membantu user experience, namun validasi server-side tetap wajib untuk keamanan.
- Gunakan browser modern untuk tampilan dan fungsi optimal.
- Untuk produksi, tambahkan fitur keamanan lanjutan seperti rate limiting, email verification, dsb.
