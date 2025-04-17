<?php
include 'config.php';
$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")-
>fetch_assoc();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $name = $_POST['name'];
 $email = $_POST['email'];
 $passw = $_POST['passw'];
 $conn->query("UPDATE users SET name='$name', email='$email',
passw='$passw' WHERE id=$id");
 header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<body>
 <h2>Edit Pengguna</h2>
 <form method="POST">
 Nama: <input type="text" name="name" value="<?= $user['name'] ?>"
required><br>
 Email: <input type="email" name="email" value="<?= $user['email'] ?>"
required><br>
 Umur: <input type="password" name="passw" value="<?= $user['passw']
?>" required><br>
 <button type="submit">Update</button>
 </form>
</body>
</html>
