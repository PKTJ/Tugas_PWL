<?php
// includes/functions.php
require_once 'koneksi.php';

function uploadProfilePicture($file, $user_id) {
    global $conn;
    
    // Validasi file
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 1 * 1024 * 1024; // 1MB
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    
    // Validasi MIME type
    if (!in_array($mime, $allowed_types)) {
        return ['error' => 'Hanya file JPG/JPEG dan PNG yang diperbolehkan.'];
    }
    
    // Validasi ukuran
    if ($file['size'] > $max_size) {
        return ['error' => 'Ukuran file maksimal 1MB.'];
    }
    
    // Buat folder jika belum ada
    if (!file_exists('uploads/profile_pics')) {
        mkdir('uploads/profile_pics', 0777, true);
    }
    
    // Generate nama file baru
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = $user_id . '_' . time() . '.' . $ext;
    $target_path = 'uploads/profile_pics/' . $new_filename;
    
    // Pindahkan file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Simpan ke database
        $sql = "INSERT INTO gambar_profil (user_id, filename, filepath) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE filename = ?, filepath = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $new_filename, $target_path, $new_filename, $target_path);
        
        if ($stmt->execute()) {
            return ['success' => 'Gambar profil berhasil diupload.'];
        } else {
            return ['error' => 'Gagal menyimpan ke database.'];
        }
    } else {
        return ['error' => 'Gagal mengupload file.'];
    }
}

function getProfilePicture($user_id) {
    global $conn;
    
    $sql = "SELECT filepath FROM gambar_profil WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['filepath'];
    }
    
    return 'assets/default-profile.png'; // Gambar default jika tidak ada
}
?>