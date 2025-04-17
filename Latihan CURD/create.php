<?php
    include 'config.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $passw = $_POST['passw'];
    $conn->query("INSERT INTO users (name, email, passw) VALUES ('$name',
    '$email', '$passw')");
    header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
<body>
 <h2>Tambah Pengguna</h2>
    <form method="POST">
        Nama: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="passw" required><br>
    <button type="submit">Simpan</button>
 </form>
</body>
</html>