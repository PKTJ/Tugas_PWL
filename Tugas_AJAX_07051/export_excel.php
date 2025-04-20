<?php
require 'vendor/autoload.php'; // autoload dari Composer
require 'koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$keyword = isset($_GET['keyword']) ? $koneksi->real_escape_string($_GET['keyword']) : '';

$sql = "SELECT nim, nama, jurusan FROM mahasiswa 
        WHERE nim LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR jurusan LIKE '%$keyword%'";
$result = $koneksi->query($sql);

// Buat spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'NIM');
$sheet->setCellValue('B1', 'Nama');
$sheet->setCellValue('C1', 'Jurusan');

// Style header
$headerStyle = [
    'font' => [
        'bold' => true,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'D9D9D9',
        ],
    ],
];
$sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

// Isi data
$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['nim']);
    $sheet->setCellValue('B' . $row, $data['nama']);
    $sheet->setCellValue('C' . $row, $data['jurusan']);
    $row++;
}

// Auto size kolom
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);

// Set nama file
$filename = "data_mahasiswa_" . date('YmdHis') . ".xlsx";

// Output ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;