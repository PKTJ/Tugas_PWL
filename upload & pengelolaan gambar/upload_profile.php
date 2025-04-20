<?php
// upload_profile.php
require_once 'includes/koneksi.php';
require_once 'includes/function.php';

session_start();

// Contoh user ID (dalam aplikasi nyata ini bisa dari session login)
$user_id = 1; // Ganti dengan $_SESSION['user_id'] jika sudah ada sistem login

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $result = uploadProfilePicture($_FILES['profile_pic'], $user_id);
    
    if (isset($result['error'])) {
        $message = '<div class="alert error">' . $result['error'] . '</div>';
    } else {
        $message = '<div class="alert success">' . $result['success'] . '</div>';
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Upload Gambar Profil</h1>
        
        <?php echo $message; ?>
        
        <form action="upload_profile.php" method="post" enctype="multipart/form-data" class="upload-form">
            <div class="form-group">
                <label for="profile_pic">Pilih Gambar Profil (Max 1MB, JPG/PNG):</label>
                <input type="file" name="profile_pic" id="profile_pic" accept="image/jpeg, image/png" required>
            </div>
            
            <button type="submit" class="btn">Upload</button>
        </form>
        
        <div class="preview">
            <h3>Preview:</h3>
            <img id="previewImage" src="<?php echo getProfilePicture($user_id); ?>" alt="Preview Profil">
        </div>
    </div>

    <script>
        // JavaScript untuk menampilkan preview gambar sebelum upload
        document.getElementById('profile_pic').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>