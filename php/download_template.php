<?php
session_start();
if (!isset($_SESSION["login"])) {
    header('Location: ./login.php');
    exit;
}

require 'SimpleXLSXGen.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type === 'siswa') {
    $data = [
        ['ID Kelas', 'Kode Jurusan', 'NIS', 'Nama Siswa', 'Email', 'Role', 'Nama Orang Tua', 'Nomor WA'],
        ['1', 'RPL', '12345678', 'Contoh Siswa', 'siswa@contoh.com', 'siswa', 'Contoh Orang Tua', '081234567890']
    ];
    $filename = 'Template_Import_Siswa.xlsx';
} elseif ($type === 'guru') {
    $data = [
        ['NIP / NUPTK', 'Nama Guru', 'Email', 'Role'],
        ['198001012005011001', 'Contoh Guru', 'guru@contoh.com', 'guru']
    ];
    $filename = 'Template_Import_Guru.xlsx';
} elseif ($type === 'pelanggaran') {
    $data = [
        ['Jenis Pelanggaran', 'Detail Pelanggaran', 'Poin'],
        ['ringan', 'Contoh Pelanggaran Terlambat', '10']
    ];
    $filename = 'Template_Import_Pelanggaran.xlsx';
} else {
    die("Tipe template tidak valid.");
}

$xlsx = \Shuchkin\SimpleXLSXGen::fromArray( $data );
$xlsx->downloadAs($filename);
exit;
?>
