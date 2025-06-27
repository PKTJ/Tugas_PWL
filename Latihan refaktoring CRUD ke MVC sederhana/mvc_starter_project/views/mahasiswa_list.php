<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Daftar Mahasiswa</title></head>
<body>
    <h1>Daftar Mahasiswa</h1>
    <a href="index.php?action=add">Tambah Mahasiswa</a>
    <table border="1" cellpadding="10">
        <tr><th>ID</th><th>Nama</th><th>NIM</th><th>Aksi</th></tr>
        <?php foreach($data as $mhs): ?>
        <tr>
            <td><?= $mhs['id']; ?></td>
            <td><?= htmlspecialchars($mhs['nama']); ?></td>
            <td><?= htmlspecialchars($mhs['nim']); ?></td>
            <td>
                <a href="index.php?action=edit&id=<?= $mhs['id']; ?>">Edit</a> |
                <a href="index.php?action=delete&id=<?= $mhs['id']; ?>"
                   onclick="return confirm('Hapus data?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
