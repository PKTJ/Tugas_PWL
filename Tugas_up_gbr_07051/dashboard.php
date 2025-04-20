<?php
// dashboard.php
require_once 'includes/koneksi.php';
require_once 'includes/function.php';

session_start();

// Contoh user ID
$user_id = 1; // Ganti dengan $_SESSION['user_id'] jika sudah ada sistem login

$profile_pic = getProfilePicture($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Dashboard Pengguna</h1>
            <nav>
                <a href="upload_profile.php">Ubah Foto Profil</a>
            </nav>
        </header>
        
        <div class="profile-section">
            <div class="profile-card">
                <div class="profile-pic">
                    <img src="<?php echo $profile_pic; ?>" alt="Foto Profil">
                </div>
                
                <div class="profile-info">
                    <h2>Nama Pengguna</h2>
                    <p>Email: user@example.com</p>
                    <p>Bergabung sejak: Januari 2023</p>
                </div>
            </div>
            
            <div class="profile-actions">
                <a href="upload_profile.php" class="btn">Ubah Foto Profil</a>
            </div>
        </div>
    </div>
</body>
</html>