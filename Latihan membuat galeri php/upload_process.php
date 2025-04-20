<?php
include 'koneksi.php';

// Create directories if they don't exist
$dirs = ["uploads", "uploads/original", "uploads/resized", "uploads/thumbs", "uploads/crops"];
foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Configuration
$max_file_size = 1024 * 1024; // 1MB
$max_width = 1024;
$max_height = 1024;
$thumb_width = 150;
$thumb_height = 150;
$crop_size = 300;
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

// Check if file is uploaded
if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
    die("Error: File tidak ditemukan atau gagal diupload");
}

$file = $_FILES['gambar'];

// Validate file size
if ($file['size'] > $max_file_size) {
    die("Error: Ukuran file tidak boleh lebih dari 1MB");
}

// Validate mime type
$mime_type = mime_content_type($file['tmp_name']);
if (!in_array($mime_type, $allowed_types)) {
    die("Error: Hanya file JPG, PNG, dan GIF yang diperbolehkan");
}

// Get original dimensions
list($width, $height) = getimagesize($file['tmp_name']);

// Create unique filename
$timestamp = time();
$filename = pathinfo($file['name'], PATHINFO_FILENAME);
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$unique_filename = $filename . '_' . $timestamp . '.' . $extension;

// Set all paths
$original_path = "uploads/original/" . $unique_filename;
$resized_path = "uploads/resized/" . $unique_filename;
$thumb_path = "uploads/thumbs/" . $unique_filename;
$crop_path = "uploads/crops/" . $unique_filename;

// Save original image
move_uploaded_file($file['tmp_name'], $original_path);

// Create source image based on mime type
switch ($mime_type) {
    case 'image/jpeg':
        $source_image = imagecreatefromjpeg($original_path);
        break;
    case 'image/png':
        $source_image = imagecreatefrompng($original_path);
        break;
    case 'image/gif':
        $source_image = imagecreatefromgif($original_path);
        break;
    default:
        die("Format gambar tidak didukung");
}

// Check if resize is needed
$new_width = $width;
$new_height = $height;
if ($width > $max_width || $height > $max_height) {
    $scale = min($max_width / $width, $max_height / $height);
    $new_width = floor($width * $scale);
    $new_height = floor($height * $scale);
    
    // Resize image
    $resized_image = imagecreatetruecolor($new_width, $new_height);
    
    // Preserve transparency for PNG and GIF
    if ($mime_type == 'image/png' || $mime_type == 'image/gif') {
        imagealphablending($resized_image, false);
        imagesavealpha($resized_image, true);
        $transparent = imagecolorallocatealpha($resized_image, 255, 255, 255, 127);
        imagefilledrectangle($resized_image, 0, 0, $new_width, $new_height, $transparent);
    }
    
    imagecopyresampled(
        $resized_image, $source_image, 
        0, 0, 0, 0, 
        $new_width, $new_height, 
        $width, $height
    );
    
    // Save resized image
    switch ($mime_type) {
        case 'image/jpeg':
            imagejpeg($resized_image, $resized_path, 90);
            break;
        case 'image/png':
            imagepng($resized_image, $resized_path, 9);
            break;
        case 'image/gif':
            imagegif($resized_image, $resized_path);
            break;
    }
    
    // Use the resized image for further processing
    imagedestroy($source_image);
    $source_image = $resized_image;
    $width = $new_width;
    $height = $new_height;
} else {
    // If no resize needed, the resized path is the same as original
    copy($original_path, $resized_path);
}

// Create and save thumbnail (square)
$thumbnail = imagecreatetruecolor($thumb_width, $thumb_height);

// Preserve transparency for PNG and GIF
if ($mime_type == 'image/png' || $mime_type == 'image/gif') {
    imagealphablending($thumbnail, false);
    imagesavealpha($thumbnail, true);
    $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
    imagefilledrectangle($thumbnail, 0, 0, $thumb_width, $thumb_height, $transparent);
}

imagecopyresampled(
    $thumbnail, $source_image,
    0, 0, 0, 0,
    $thumb_width, $thumb_height,
    $width, $height
);

switch ($mime_type) {
    case 'image/jpeg':
        imagejpeg($thumbnail, $thumb_path, 90);
        break;
    case 'image/png':
        imagepng($thumbnail, $thumb_path, 9);
        break;
    case 'image/gif':
        imagegif($thumbnail, $thumb_path);
        break;
}

// Create and save center crop (300x300)
$crop = imagecreatetruecolor($crop_size, $crop_size);

// Preserve transparency for PNG and GIF
if ($mime_type == 'image/png' || $mime_type == 'image/gif') {
    imagealphablending($crop, false);
    imagesavealpha($crop, true);
    $transparent = imagecolorallocatealpha($crop, 255, 255, 255, 127);
    imagefilledrectangle($crop, 0, 0, $crop_size, $crop_size, $transparent);
}

// Calculate crop start points (from center)
$crop_x = max(0, ($width - $crop_size) / 2);
$crop_y = max(0, ($height - $crop_size) / 2);

// If image is smaller than crop size, adjust crop dimensions
$crop_w = min($width, $crop_size);
$crop_h = min($height, $crop_size);

imagecopyresampled(
    $crop, $source_image,
    0, 0, $crop_x, $crop_y,
    $crop_size, $crop_size, $crop_w, $crop_h
);

switch ($mime_type) {
    case 'image/jpeg':
        imagejpeg($crop, $crop_path, 90);
        break;
    case 'image/png':
        imagepng($crop, $crop_path, 9);
        break;
    case 'image/gif':
        imagegif($crop, $crop_path);
        break;
}

// Free memory
imagedestroy($source_image);
imagedestroy($thumbnail);
imagedestroy($crop);

// Save to database
$stmt = $conn->prepare("INSERT INTO gambar (filename, filepath, thumbpath, croppath, width, height, size) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssiis", $unique_filename, $resized_path, $thumb_path, $crop_path, $new_width, $new_height, $file['size']);
$stmt->execute();
$stmt->close();

// Redirect to gallery
header("Location: galeri.php?status=success");
exit();
?>