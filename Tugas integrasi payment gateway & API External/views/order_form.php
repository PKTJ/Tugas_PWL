<!DOCTYPE html>
<html>
<head>
    <title>Form Pemesanan</title>
</head>
<body>
    <h2>Form Pemesanan Barang</h2>
    <p><a href="../transactions.php">Lihat Status Transaksi</a></p>
    
    <form action="../calculate_shipping.php" method="post">
        Nama Barang: <input type="text" name="nama_barang"><br>
        Berat (gram): <input type="number" name="berat"><br>
        Provinsi Tujuan: <select name="provinsi" id="provinsi"></select><br>
        Kota Tujuan: <select name="kota" id="kota"></select><br>
        <input type="submit" value="Hitung Ongkir & Bayar">
    </form>

    <script>
    fetch("../api/get_provinces.php")
        .then(res => res.json())
        .then(data => {
            const provinsiSelect = document.getElementById("provinsi");
            data.rajaongkir.results.forEach(prov => {
                const option = document.createElement("option");
                option.value = prov.province_id;
                option.textContent = prov.province;
                provinsiSelect.appendChild(option);
            });

            provinsiSelect.addEventListener("change", () => {
                const province_id = provinsiSelect.value;
                fetch("../api/get_cities.php?province_id=" + province_id)
                    .then(res => res.json())
                    .then(data => {
                        const kotaSelect = document.getElementById("kota");
                        kotaSelect.innerHTML = "";
                        data.rajaongkir.results.forEach(city => {
                            const option = document.createElement("option");
                            option.value = city.city_id;
                            option.textContent = city.city_name;
                            kotaSelect.appendChild(option);
                        });
                    });
            });
        });
    </script>
</body>
</html>
