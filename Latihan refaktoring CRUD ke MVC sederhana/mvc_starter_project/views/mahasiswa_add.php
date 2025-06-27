<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Tambah Mahasiswa</title></head>
<body>
    <h1>Tambah Mahasiswa</h1>
    <form action="index.php?action=store" method="post">
        <label>Nama: <input type="text" name="nama" required></label><br>
        <label>NIM:  <input type="text" name="nim"  required></label><br>
        <button type="submit">Simpan</button>
    </form>
    <a href="index.php">Batal</a>
</body>
</html>
