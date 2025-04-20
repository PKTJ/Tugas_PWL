<?php
require 'vendor/autoload.php';
require 'db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil keyword dari URL
$keyword = isset($_GET['keyword']) ? $koneksi->real_escape_string($_GET['keyword']) : '';

$sql = "SELECT nim, nama, jurusan FROM mahasiswa 
        WHERE nim LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR jurusan LIKE '%$keyword%'";
$result = $koneksi->query($sql);

// CSS untuk tabel
$style = "
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    h3 {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>";

// Bangun HTML
$html = $style . '<h3>Data Mahasiswa</h3>';
if ($keyword) {
    $html .= '<p>Hasil pencarian untuk: <b>' . htmlspecialchars($keyword) . '</b></p>';
}

$html .= '<table>
<thead>
    <tr>
        <th>NIM</th>
        <th>Nama</th>
        <th>Jurusan</th>
    </tr>
</thead>
<tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>' . htmlspecialchars($row['nim']) . '</td>
            <td>' . htmlspecialchars($row['nama']) . '</td>
            <td>' . htmlspecialchars($row['jurusan']) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="3" style="text-align:center;">Tidak ada data yang ditemukan</td></tr>';
}

$html .= '</tbody></table>';
$html .= '<p style="font-size: 12px; text-align: right;">Diekspor pada: ' . date('d-m-Y H:i:s') . '</p>';

// Set opsi Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

// Generate PDF
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Kirim output ke browser
$filename = "data_mahasiswa_" . date('YmdHis') . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
exit;