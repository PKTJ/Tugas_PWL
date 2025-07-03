## IKHISAR
Program ini memungkinkan pengguna untuk mengunggah foto profil, menyimpannya di server, merekam metadata ke database, dan menampilkan foto profil tersebut di halaman dashboard.

## 📁 STRUKTUR FILE

```
project_root/
├─ includes/
│   ├─ koneksi.php         # Konfigurasi koneksi database
│   └─ function.php        # Fungsi utama upload dan ambil foto profil
├─ assets/
│   └─ style.css           # Styling antarmuka pengguna fileciteturn0file0
├─ dashboard.php           # Halaman untuk menampilkan profil pengguna
├─ upload_profile.php      # Halaman dan logika untuk upload foto
└─ db_web.sql              # Skrip SQL untuk membuat tabel gambar_profile
```

## DATABASE
- **id (INT, PK, AUTO_INCREMENT)**
- **user_id (INT, unik)** - menghubungkan ke pengguna
- **filename (VARCHAR)** - nama file asli
- **filepath (VARCHAR)** - path penyimpanan di server
- **uploaded_at (TIMESTAMP)** - waktu unggah


## 🤝 KONTIBUTOR

**Developer:** ARBINANDRI  
**NIM:** 07051  
**Mata Kuliah:** Pemrograman Web Lanjut  
**Universitas:** Universitas Dian Nuswantoro  