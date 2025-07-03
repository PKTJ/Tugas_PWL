<?php
require_once 'database.php';

// Ambil semua transaksi
try {
    $stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC");
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Status Transaksi</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-pending { color: orange; }
        .status-success { color: green; }
        .status-failed { color: red; }
    </style>
</head>
<body>
    <h2>Status Transaksi</h2>
    <a href="views/order_form.php">← Kembali ke Form Pemesanan</a>
    
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Nama Barang</th>
                <th>Berat (g)</th>
                <th>Kota Tujuan</th>
                <th>Ongkir</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada transaksi</td>
                </tr>
            <?php else: ?>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['order_id']) ?></td>
                        <td><?= htmlspecialchars($transaction['nama_barang']) ?></td>
                        <td><?= number_format($transaction['berat']) ?></td>
                        <td><?= htmlspecialchars($transaction['kota_nama']) ?></td>
                        <td>Rp <?= number_format($transaction['ongkir']) ?></td>
                        <td>Rp <?= number_format($transaction['total_amount']) ?></td>
                        <td class="status-<?= $transaction['status'] ?>">
                            <?= ucfirst($transaction['status']) ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
