<?php include 'koneksi_07051.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="header mb-4">
            <h1 class="text-center">Manajemen Produk</h1>
            <div class="d-flex justify-content-between mb-3">
                <a href="tambah_07051.php" class="btn btn-success">Tambah Produk</a>
                <input type="text" id="liveSearch" class="form-control w-25" placeholder="Cari produk...">
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $data = mysqli_query($koneksi, "SELECT * FROM produk");
                while($d = mysqli_fetch_array($data)) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $d['nama_produk']; ?></td>
                    <td><?php echo $d['kategori']; ?></td>
                    <td>Rp<?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $d['stok']; ?></td>
                    <td>
                        <a href="edit_07051.php?id=<?php echo $d['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus_07051.php?id=<?php echo $d['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <footer class="footer mt-5 py-3 bg-light fixed-bottom">
            <div class="container text-center">
                <span class="fw-bold">Nama: ARBINAND ROFFI ILMI | NIM: A12.2023.07051</span>
            </div>
        </footer>
    </div>

    <script>
        // Live Search
        document.getElementById('liveSearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>