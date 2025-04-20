<?php 
include 'koneksi.php'; 

// Pagination setup
$limit = 8; // jumlah gambar per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter tanggal
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : null;

// Hitung total gambar
$count_sql = "SELECT COUNT(*) FROM gambar";
if ($tanggal) {
    $count_sql .= " WHERE DATE(uploaded_at) = '$tanggal'";
}
$total_result = $conn->query($count_sql)->fetch_row()[0];
$total_pages = ceil($total_result / $limit);

// Query dengan LIMIT dan OFFSET
$sql = "SELECT * FROM gambar";
if ($tanggal) {
    $sql .= " WHERE DATE(uploaded_at) = '$tanggal'";
}
$sql .= " ORDER BY uploaded_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Gambar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-img-top {
            height: 180px;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Galeri Gambar</h2>
            <a href="index.php" class="btn btn-primary">Upload Gambar Baru</a>
        </div>
        
        <!-- Status message -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Gambar berhasil diunggah!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Filter berdasarkan tanggal -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="date" name="tanggal" class="form-control" value="<?= $tanggal ?? '' ?>">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-success">Filter Tanggal</button>
                <a href="galeri.php" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Gallery -->
        <div class="row g-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card shadow-sm h-100">
                            <img src="<?= $row['thumbpath'] ?>" class="card-img-top" alt="Thumbnail" 
                                 data-bs-toggle="modal" data-bs-target="#modal<?= $row['id'] ?>">
                            <div class="card-body">
                                <p class="card-text"><strong>Ukuran:</strong> <?= $row['width'] ?>x<?= $row['height'] ?></p>
                                <p class="card-text"><small class="text-muted">Upload: <?= date('d-m-Y', strtotime($row['uploaded_at'])) ?></small></p>
                                <div class="d-flex justify-content-between">
                                    <a href="<?= $row['filepath'] ?>" class="btn btn-sm btn-primary" target="_blank">Lihat Asli</a>
                                    <form action="hapus.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus gambar ini?')">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="filepath" value="<?= $row['filepath'] ?>">
                                        <input type="hidden" name="thumbpath" value="<?= $row['thumbpath'] ?>">
                                        <input type="hidden" name="croppath" value="<?= $row['croppath'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Preview -->
                    <div class="modal fade" id="modal<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Preview Gambar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="<?= $row['filepath'] ?>" class="img-fluid" alt="Preview">
                                </div>
                                <div class="modal-footer">
                                    <a href="<?= $row['croppath'] ?>" class="btn btn-info" target="_blank">Lihat Crop</a>
                                    <a href="<?= $row['filepath'] ?>" class="btn btn-primary" target="_blank">Lihat Asli</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-12'><p class='text-center py-5'>Belum ada gambar diunggah.</p></div>";
            }
            ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mt-4">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>