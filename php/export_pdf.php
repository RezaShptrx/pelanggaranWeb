<?php
session_start();

if (!isset($_SESSION["login"])) {
    header('Location: ./login.php');
    exit;
}

// Redirect if siswa accesses
if (isset($_SESSION["siswa"])) {
    header("Location: ./data_siswa.php?id=" . $_SESSION["id_siswa"]);
    exit;
}

if (!isset($_POST["tanggal"])) {
    echo "Pilih tanggal terlebih dahulu.";
    exit;
}

require './functions.php';

// Include DomPDF autoloader
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$tgl_plgr = $_POST["tanggal"];
$plgr_siswa = query("SELECT * FROM pelanggaran_siswa WHERE waktu_pelanggaran = '$tgl_plgr'");

if (empty($plgr_siswa)) {
    echo "Tidak ada data pelanggaran pada tanggal tersebut.";
    exit;
}

$tanggal_format = date("d M Y", strtotime($tgl_plgr));

// Initialize HTML
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pelanggaran - ' . $tanggal_format . '</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
        }
        @page {
            margin: 100px 50px 50px 50px;
        }
        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            border-bottom: 2px solid #333;
            text-align: center;
        }
        footer {
            position: fixed; 
            bottom: -30px; 
            left: 0px; 
            right: 0px;
            height: 30px; 
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        footer .pagenum:before {
            content: counter(page);
        }
        .text-center { text-align: center; }
        .title { margin: 0; font-size: 18px; font-weight: bold; text-transform: uppercase; }
        .subtitle { margin: 5px 0 0 0; font-size: 12px; color: #555; }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ccc;
            padding: 8px 5px;
            word-wrap: break-word;
        }
        table.data-table th {
            background-color: #f9fafb;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        table.data-table td {
            font-size: 11px;
            vertical-align: top;
        }
        .col-no { width: 4%; text-align: center; }
        .col-tgl { width: 12%; text-align: center; }
        .col-nama { width: 23%; }
        .col-plgr { width: 40%; }
        .col-poin { width: 6%; text-align: center; font-weight: bold; color: #b91c1c; }
        .col-petugas { width: 15%; text-align: center; }
        
        ul { margin: 0; padding-left: 15px; }
        li { margin-bottom: 3px; }
    </style>
</head>
<body>

    <header>
        <h2 class="title">Laporan Pelanggaran Siswa</h2>
        <p class="subtitle">Tanggal Laporan: ' . $tanggal_format . ' | OSIS SMKN 12 JAKARTA</p>
    </header>

    <footer>
        <table width="100%" style="border: none;">
            <tr>
                <td style="border: none; padding: 0; text-align: left;">Dicetak pada: ' . date('d M Y H:i') . '</td>
                <td style="border: none; padding: 0; text-align: right;">Halaman <span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>

    <main>
        <table class="data-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-tgl">Tanggal</th>
                    <th class="col-nama">Nama Pelanggar</th>
                    <th class="col-plgr">Detail Pelanggaran</th>
                    <th class="col-poin">Poin</th>
                    <th class="col-petugas">Petugas</th>
                </tr>
            </thead>
            <tbody>';

$nomor = 1;
foreach ($plgr_siswa as $plgr) {
    // Get student name
    $id_pelanggar = $plgr["id_pelanggar"];
    $nama_query = mysqli_query($conn, "SELECT nama_siswa FROM siswa WHERE id_siswa = $id_pelanggar");
    $nama = $nama_query ? $nama_query->fetch_assoc()["nama_siswa"] : "Tidak diketahui";
    
    // Get violations
    $id_pelanggaran = $plgr["id_pelanggaran"];
    $pelanggaran = query("SELECT det_pelanggaran FROM ket_pelanggaran WHERE id_pelanggaran in ($id_pelanggaran)");
    
    $plgr_list = "<ul>";
    foreach ($pelanggaran as $data) {
        $plgr_list .= "<li>" . htmlspecialchars($data["det_pelanggaran"]) . "</li>";
    }
    $plgr_list .= "</ul>";
    
    // Get reporter
    $id_pelapor = $plgr["id_pelapor"];
    if (strlen($id_pelapor) === 5) {
        $pelapor_query = mysqli_query($conn, "SELECT nama_siswa FROM siswa WHERE nis = $id_pelapor");
        $pelapor = $pelapor_query ? $pelapor_query->fetch_assoc()["nama_siswa"] : "Tidak diketahui";
    } else {
        $pelapor_query = mysqli_query($conn, "SELECT nama_guru FROM guru_pembina WHERE nip = $id_pelapor");
        $pelapor = $pelapor_query ? $pelapor_query->fetch_assoc()["nama_guru"] : "Tidak diketahui";
    }
    
    $html .= '<tr>
        <td class="col-no">' . $nomor++ . '</td>
        <td class="col-tgl">' . $tanggal_format . '</td>
        <td class="col-nama">' . htmlspecialchars($nama) . '</td>
        <td class="col-plgr">' . $plgr_list . '</td>
        <td class="col-poin">-' . $plgr["poin_berkurang"] . '</td>
        <td class="col-petugas">' . htmlspecialchars($pelapor) . '</td>
    </tr>';
}

$html .= '
            </tbody>
        </table>
    </main>

</body>
</html>';

// Setup dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Stream PDF to browser
$dompdf->stream("Laporan_Pelanggaran_" . str_replace(" ", "_", $tanggal_format) . ".pdf", array("Attachment" => true));
exit;
?>
