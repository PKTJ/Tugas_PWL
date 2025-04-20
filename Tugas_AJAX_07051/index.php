<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Search Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        #loading {
            display: none;
        }
        .search-result {
            transition: opacity 0.3s ease-in-out;
        }
        .fade-in {
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Live Search Mahasiswa (AJAX + MySQL)</h2>
        
        <div class="row mb-3">
            <div class="col-md-8">
                <input type="text" id="search" class="form-control" placeholder="Ketik nama, NIM, atau jurusan...">
            </div>
            <div class="col-md-4">
                <div id="loading" class="d-flex align-items-center">
                    <div class="spinner-border text-primary spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span>Mencari...</span>
                </div>
            </div>
        </div>
        
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                </tr>
            </thead>
            <tbody id="result"></tbody>
        </table>
    </div>

    <script>
        const searchBox = document.getElementById("search");
        const result = document.getElementById("result");
        const loading = document.getElementById("loading");
        
        // tampilkan semua data saat halaman pertama kali dibuka
        function loadAllData() {
            loading.style.display = "flex";
            
            fetch("get_all.php")
                .then(res => res.json())
                .then(data => {
                    loading.style.display = "none";
                    result.innerHTML = "";
                    
                    if (data.length === 0) {
                        result.innerHTML = "<tr><td colspan='3' class='text-center'>Tidak ada data</td></tr>";
                    } else {
                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.className = 'search-result fade-in';
                            tr.innerHTML = `
                                <td>${row.nim}</td>
                                <td>${row.nama}</td>
                                <td>${row.jurusan}</td>
                            `;
                            result.appendChild(tr);
                        });
                    }
                })
                .catch(error => {
                    loading.style.display = "none";
                    console.error("Error:", error);
                    result.innerHTML = "<tr><td colspan='3' class='text-center text-danger'>Terjadi kesalahan saat memuat data</td></tr>";
                });
        }
        
        // Fungsi untuk mencari data
        function searchData() {
            const keyword = searchBox.value.trim();
            
            // Jika keyword kosong, tampilkan semua data
            if (keyword.length === 0) {
                loadAllData();
                return;
            }
            
            // Tampilkan loading spinner
            loading.style.display = "flex";
            
            // Kirim request ke search.php
            fetch("search.php?keyword=" + encodeURIComponent(keyword))
                .then(res => res.json())
                .then(data => {
                    // Sembunyikan loading spinner
                    loading.style.display = "none";
                    
                    // Kosongkan hasil sebelumnya
                    result.innerHTML = "";
                    
                    // Tampilkan hasil pencarian
                    if (data.length === 0) {
                        result.innerHTML = "<tr><td colspan='3' class='text-center'>Data tidak ditemukan</td></tr>";
                    } else {
                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.className = 'search-result fade-in';
                            tr.innerHTML = `
                                <td>${row.nim}</td>
                                <td>${row.nama}</td>
                                <td>${row.jurusan}</td>
                            `;
                            result.appendChild(tr);
                        });
                    }
                })
                .catch(error => {
                    loading.style.display = "none";
                    console.error("Error:", error);
                    result.innerHTML = "<tr><td colspan='3' class='text-center text-danger'>Terjadi kesalahan saat mencari data</td></tr>";
                });
        }
        
        // Event listener untuk input search
        searchBox.addEventListener("keyup", function() {
            // Tunda pencarian untuk mengurangi request berlebihan
            clearTimeout(this.timer);
            this.timer = setTimeout(searchData, 300);
        });
        
        // Load semua data saat halaman pertama kali dibuka
        window.addEventListener("load", function() {
            searchBox.value = "";
            loadAllData();
        });
    </script>
</body>
</html>