<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Edit Mahasiswa</title></head>
<body>
    <h1>Edit Mahasiswa</h1>
    <form action="index.php?action=update&id=<?= $mhs['id']; ?>" method="post">
        <label>Nama: <input type="text" name="nama" value="<?= htmlspecialchars($mhs['nama']); ?>" required></label><br>
        <label>NIM:  <input type="text" name="nim"  value="<?= htmlspecialchars($mhs['nim']); ?>"  required></label><br>
        <button type="submit">Update</button>
    </form>
    <a href="index.php">Batal</a>
</body>
</html>
