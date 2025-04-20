<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Gambar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #dropArea {
            border: 2px dashed #ccc;
            transition: all 0.3s;
            cursor: pointer;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        #dropArea:hover {
            border-color: #0d6efd;
        }
        .preview-image {
            max-width: 100%;
            max-height: 300px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="m-0">Upload Gambar</h3>
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" action="upload_process.php" method="POST" enctype="multipart/form-data">
                            <div id="dropArea" class="p-5 text-center rounded">
                                <p class="mb-1">Tarik file ke sini atau klik untuk memilih</p>
                                <p id="fileName" class="text-muted small mb-0"></p>
                                <img id="imagePreview" class="preview-image d-none" alt="Preview">
                            </div>
                            <input type="file" name="gambar" id="gambar" class="d-none" accept="image/jpeg,image/png,image/gif">
                            
                            <div class="mt-4">
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">Upload Gambar</button>
                                    <a href="galeri.php" class="btn btn-secondary">Lihat Galeri</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('gambar');
        const fileName = document.getElementById('fileName');
        const imagePreview = document.getElementById('imagePreview');
        
        // Click to select file
        dropArea.addEventListener('click', () => fileInput.click());
        
        // Drag over event
        dropArea.addEventListener('dragover', e => {
            e.preventDefault();
            dropArea.classList.add('border-primary');
        });
        
        // Drag leave event
        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('border-primary');
        });
        
        // Drop event
        dropArea.addEventListener('drop', e => {
            e.preventDefault();
            dropArea.classList.remove('border-primary');
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect();
            }
        });
        
        // File input change event
        fileInput.addEventListener('change', handleFileSelect);
        
        function handleFileSelect() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                fileName.textContent = file.name;
                
                // Show image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>