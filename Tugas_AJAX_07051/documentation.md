# Tugas AJAX - Live Search Mahasiswa dengan Export

## 📋 Deskripsi Project
Project ini merupakan implementasi sistem pencarian real-time menggunakan AJAX dengan fitur export data ke format Excel dan PDF. Sistem ini memungkinkan pengguna untuk mencari data mahasiswa secara real-time tanpa reload halaman dan mengexport hasil pencarian.

## ✨ Fitur Utama

### 1. Live Search dengan AJAX
- Pencarian real-time tanpa reload halaman
- Debouncing untuk optimasi performa (delay 300ms)
- Loading indicator saat proses pencarian
- Animasi fade-in untuk hasil pencarian
- Pencarian berdasarkan NIM, Nama, atau Jurusan

### 2. Export Data
- **Export Excel (.xlsx):** Menggunakan PhpSpreadsheet
- **Export PDF:** Menggunakan DomPDF
- Export data sesuai filter pencarian
- Timestamp otomatis pada nama file
- Header styling dan formatting

### 3. User Interface
- Responsive design dengan Bootstrap 5
- Modern UI dengan hover effects
- Bootstrap Icons untuk visual appeal
- Loading spinner dengan animasi
- Clean table layout

## 🛠️ Teknologi yang Digunakan

### Backend:
- **PHP 7.4+** - Server-side scripting
- **MySQL/MariaDB** - Database management
- **PhpSpreadsheet** - Excel file generation
- **DomPDF** - PDF file generation

### Frontend:
- **HTML5** - Markup structure
- **CSS3** - Styling dan animations
- **JavaScript (Vanilla)** - AJAX functionality
- **Bootstrap 5** - UI framework
- **Bootstrap Icons** - Icon library

### Dependencies:
- PhpSpreadsheet untuk Excel export
- DomPDF untuk PDF export

## 📦 Instalasi

### 1. Install Dependencies
```bash
# Pastikan Composer sudah terinstall
composer require phpoffice/phpspreadsheet
composer require dompdf/dompdf
```

### 2. Setup Database
```sql
-- Import file database
mysql -u root -p kampus < db/mahasiswa.sql
```

### 3. Konfigurasi Database
Edit file `koneksi.php`:
```php
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "kampus";
$koneksi = new mysqli($host, $user, $pass, $db);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
```

### 4. Setup Web Server
- Place project di htdocs (XAMPP) atau www (WAMP)
- Akses via: `http://localhost/Tugas_AJAX_07051/`

## 📁 Struktur File

```
Tugas_AJAX_07051/
├── index.php              # Main interface dengan AJAX search
├── search.php              # AJAX search endpoint
├── get_all.php             # Endpoint untuk load semua data
├── export_excel.php        # Excel export functionality
├── export_pdf.php          # PDF export functionality
├── koneksi.php             # Database connection
├── documentation.md        # Dokumentasi project
├── composer.json           # Composer dependencies
├── vendor/                 # Composer packages
└── db/
    └── mahasiswa.sql       # Database structure & sample data
```

## 🎯 Cara Penggunaan

### 1. Pencarian Data
- Buka halaman utama
- Ketik keyword di search box (NIM, Nama, atau Jurusan)
- Data akan terfilter secara real-time
- Kosongkan search box untuk menampilkan semua data

### 2. Export Data
- **Export Excel:** Klik tombol hijau "Export Excel"
- **Export PDF:** Klik tombol merah "Export PDF"
- File akan didownload dengan timestamp

### 3. Format Export
- **Excel:** Format .xlsx dengan styling header
- **PDF:** Format A4 portrait dengan styling table

## 💾 Database Schema

### Tabel: mahasiswa
```sql
CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nim` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Sample Data
```sql
INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `jurusan`) VALUES
(1, '23001', 'Budi Santoso', 'Teknik Informatika'),
(2, '23002', 'Dewi Anggraini', 'Sistem Informasi'),
(3, '23003', 'Adi Wijaya', 'Teknik Komputer'),
(4, '23004', 'Putri Lestari', 'Manajemen'),
(5, '23005', 'Rizky Hidayat', 'Sistem Informasi');
```

## 🔧 API Endpoints

### 1. GET `/search.php`
**Purpose:** Mencari data mahasiswa berdasarkan keyword
**Parameters:**
- `keyword` (string): Kata kunci pencarian

**Response:**
```json
[
  {
    "nim": "23001",
    "nama": "Budi Santoso",
    "jurusan": "Teknik Informatika"
  }
]
```

### 2. GET `/get_all.php`
**Purpose:** Mengambil semua data mahasiswa
**Parameters:** Tidak ada
**Response:** Array JSON semua data mahasiswa

### 3. GET `/export_excel.php`
**Purpose:** Export data ke Excel
**Parameters:**
- `keyword` (optional): Filter data

### 4. GET `/export_pdf.php`
**Purpose:** Export data ke PDF
**Parameters:**
- `keyword` (optional): Filter data

## 🎨 Fitur UI/UX

### 1. Loading States
- Spinner loading saat search
- Posisi loading di dalam search box
- Text indicator "Mencari..."

### 2. Animations
- Fade-in animation untuk hasil search
- Hover effects pada tombol export
- Smooth transitions

### 3. Responsive Design
- Mobile-friendly layout
- Responsive table
- Stacked buttons pada mobile

## 🔒 Security Features

### 1. Input Sanitization
```php
$keyword = $koneksi->real_escape_string($_GET['keyword']);
```

### 2. SQL Injection Prevention
- Menggunakan real_escape_string
- Prepared statements ready

### 3. XSS Prevention
```php
htmlspecialchars($data['nama'])
```

## ⚡ Performance Optimization

### 1. AJAX Debouncing
- Delay 300ms sebelum search
- Mencegah request berlebihan
- Smooth user experience

### 2. Efficient Database Queries
- Index pada kolom pencarian
- LIKE queries optimized
- Minimal data transfer

## 🐛 Error Handling

### 1. Database Errors
- Connection error handling
- Query error handling
- Graceful degradation

### 2. AJAX Errors
- Network error handling
- JSON parsing errors
- User-friendly error messages

## 🚀 Future Enhancements

### Possible Improvements:
1. **Pagination** untuk large datasets
2. **Advanced Filters** berdasarkan jurusan
3. **Sorting** functionality
4. **Data Caching** untuk performa
5. **Real-time Notifications**
6. **Advanced Export Options** (CSV, XML)
7. **Search History**
8. **Auto-complete** suggestions

## 📊 Performance Metrics

### Target Metrics:
- Search response time: < 200ms
- Page load time: < 1s
- Export generation: < 5s
- Mobile responsiveness: 100%

## 🤝 Kontributor

**Developer:** ARBINANDRI  
**NIM:** 07051  
**Mata Kuliah:** Pemrograman Web Lanjut  
**Universitas:** Universitas Dian Nuswantoro  

## 📄 Changelog

### Version 1.0.0 (Current)
- ✅ Live search implementation
- ✅ Excel export functionality
- ✅ PDF export functionality
- ✅ Responsive design
- ✅ Error handling

## 🆘 Troubleshooting

### Common Issues:

1. **Composer dependencies tidak terinstall**
   ```bash
   composer install
   ```

2. **Database connection error**
   - Check database credentials
   - Ensure MySQL service is running
   - Import database file

3. **Export tidak berfungsi**
   - Check file permissions
   - Verify Composer dependencies
   - Check PHP memory limit

4. **AJAX tidak bekerja**
   - Check JavaScript console
   - Verify file paths
   - Check server response

## 📞 Support

Untuk bantuan atau pertanyaan:
- Email: [your-email]
- GitHub Issues: [repository-link]
- Documentation: README.md

---

**Last Updated:** Juni 27, 2025  
**Version:** 1.0.0
