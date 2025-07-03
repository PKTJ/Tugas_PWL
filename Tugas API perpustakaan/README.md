# Dokumentasi Tugas API Perpustakaan

## Deskripsi
Tugas API Perpustakaan adalah sebuah aplikasi sederhana berbasis PHP yang menyediakan RESTful API untuk mengelola data buku pada sebuah perpustakaan. API ini memungkinkan pengguna untuk melakukan operasi CRUD (Create, Read, Update, Delete) pada data buku yang tersimpan di database MySQL.

## Struktur Folder & File
- `database.php` : File konfigurasi koneksi ke database MySQL.
- `rest-api.php` : Endpoint utama REST API untuk operasi CRUD pada data buku.
- `db/perpus.sql` : Skrip SQL untuk membuat database dan tabel beserta data awal.

## Instalasi & Setup
1. **Import Database**
   - Jalankan file `db/perpus.sql` di MySQL untuk membuat database `perpus` dan tabel `buku` beserta data contoh.
2. **Konfigurasi Koneksi**
   - Pastikan konfigurasi di `database.php` sesuai dengan environment MySQL Anda (host, user, password, db).
3. **Jalankan API**
   - Letakkan file di server lokal (XAMPP/Laragon/dll) dan akses `rest-api.php` melalui HTTP.

## Endpoint REST API
Semua endpoint menerima dan mengembalikan data dalam format JSON.

### 1. GET `/rest-api.php`
Mengambil seluruh data buku.
- **Response:**
```json
[
  {"id":1, "judul":"Laut Bercerita", ...},
  ...
]
```

### 2. POST `/rest-api.php`
Menambah data buku baru.
- **Request Body:**
```json
{
  "judul": "Judul Buku",
  "penulis": "Nama Penulis",
  "tahun_terbit": "Tahun"
}
```
- **Response:**
```json
{"status": true}
```

### 3. PUT `/rest-api.php`
Mengubah data buku berdasarkan ID.
- **Request Body:**
```json
{
  "id": 1,
  "judul": "Judul Baru",
  "penulis": "Penulis Baru",
  "tahun_terbit": "Tahun Baru"
}
```
- **Response:**
```json
{"status": true}
```

### 4. DELETE `/rest-api.php`
Menghapus data buku berdasarkan ID.
- **Request Body:**
```json
{
  "id": 1
}
```
- **Response:**
```json
{"status": true}
```

## Struktur Tabel Buku
| Field        | Tipe         | Keterangan         |
|--------------|--------------|--------------------|
| id           | INT (AI, PK) | ID Buku            |
| judul        | VARCHAR(15)  | Judul Buku         |
| penulis      | VARCHAR(100) | Nama Penulis       |
| tahun_terbit | VARCHAR(100) | Tahun Terbit       |

## Contoh Data Awal
- Laut Bercerita, Leila S. Chudori, 2017
- Bumi Manusia, Pramoedya Ananta Toer, 1980
- Filosofi Teras, Henry Menampiring, 2018
- The Midnight Library, Matt Haig, 2020
- Atomic Habits, James Clear, 2018

## Catatan
- Pastikan server mendukung PHP dan MySQL.
- Gunakan aplikasi seperti Postman untuk menguji endpoint API.
- Untuk keamanan produksi, lakukan validasi dan sanitasi input lebih lanjut.

## 🤝 KONTIBUTOR

**Developer:** ARBINANDRI  
**NIM:** 07051  
**Mata Kuliah:** Pemrograman Web Lanjut  
**Universitas:** Universitas Dian Nuswantoro  
