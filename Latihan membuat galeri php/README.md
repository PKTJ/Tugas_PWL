# Dokumentasi Program: Latihan Membuat Galeri PHP

## Deskripsi
Program ini adalah aplikasi galeri gambar berbasis PHP yang memungkinkan pengguna mengunggah gambar, melihat galeri, dan menghapus gambar. Setiap gambar yang diunggah akan otomatis diproses menjadi beberapa versi: original, resize, thumbnail, dan crop.

## Struktur Folder & File
- `index.php` : Halaman utama upload gambar (drag & drop, preview, validasi ukuran/tipe).
- `upload_process.php` : Proses backend upload, resize, crop, thumbnail, dan simpan data ke database.
- `galeri.php` : Menampilkan galeri gambar dengan pagination dan filter tanggal.
- `hapus.php` : Menghapus gambar dari server dan database.
- `koneksi.php` : Koneksi ke database MySQL.
- `db/gambar.sql` : Skrip SQL pembuatan tabel `gambar`.
- `uploads/` : Folder penyimpanan gambar (original, resized, thumbs, crops).

## Fitur Utama
- **Upload Gambar**: Mendukung drag & drop, validasi tipe (JPG, PNG, GIF), dan ukuran maksimal 1MB.
- **Otomatis Resize**: Gambar diresize maksimal 1024x1024px.
- **Thumbnail & Crop**: Dibuat thumbnail 150x150px dan crop tengah 300x300px.
- **Galeri**: Menampilkan gambar dengan pagination, filter tanggal, dan preview.
- **Hapus Gambar**: Menghapus file di server dan data di database.

## Struktur Database (`gambar`)
| Field       | Type         | Keterangan                |
|-------------|--------------|---------------------------|
| id          | int (AI, PK) | ID gambar                 |
| filename    | varchar(255) | Nama file unik            |
| filepath    | varchar(255) | Path gambar hasil resize  |
| thumbpath   | varchar(255) | Path thumbnail            |
| croppath    | varchar(255) | Path crop                 |
| width       | int          | Lebar gambar (px)         |
| height      | int          | Tinggi gambar (px)        |
| size        | int          | Ukuran file (byte)        |
| uploaded_at | timestamp    | Waktu upload              |

## Cara Menjalankan
1. Import `db/gambar.sql` ke MySQL untuk membuat tabel `gambar`.
2. Pastikan konfigurasi di `koneksi.php` sesuai environment Anda.
3. Jalankan aplikasi di server lokal (XAMPP/Laragon/dll).
4. Akses `index.php` untuk upload, `galeri.php` untuk melihat galeri.

## Catatan
- Pastikan folder `uploads/` dan subfoldernya dapat ditulis oleh server.
- Untuk produksi, tambahkan validasi keamanan lanjutan (CSRF, autentikasi, dsb).
- Gunakan browser modern untuk tampilan optimal.
