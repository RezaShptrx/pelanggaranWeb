<?php
session_start();
if (!isset($_SESSION["login"])) {
    header('Location: ./login.php');
    exit;
}

require 'functions.php';
require 'SimpleXLSX.php';

if (isset($_POST["upload"])) {
    if (isset($_FILES['file_excel']['name']) && $_FILES['file_excel']['name'] != '') {
        $allowed_ext = ['xlsx'];
        $file_name = $_FILES['file_excel']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext)) {
            if ($xlsx = SimpleXLSX::parse($_FILES['file_excel']['tmp_name'])) {
                $rows = $xlsx->rows();
                $berhasil = 0;
                $gagal = 0;
                
                foreach ($rows as $index => $row) {
                    // Skip header row
                    if ($index === 0) continue;

                    if(empty($row[0]) && empty($row[1])) continue;

                    $jenis_plgr = htmlspecialchars(strtolower($row[0]));
                    $det_plgr = htmlspecialchars(ucfirst($row[1]));
                    $poin_plgr = htmlspecialchars($row[2]);
                    
                    if(empty($jenis_plgr) || empty($det_plgr)) {
                        $gagal++;
                        continue;
                    }

                    $query = "INSERT INTO ket_pelanggaran (`jenis_pelanggaran`, `det_pelanggaran`, `poin_pelanggaran`) VALUES ('$jenis_plgr', '$det_plgr', '$poin_plgr')";
                    
                    if(mysqli_query($conn, $query)) {
                        $berhasil++;
                    } else {
                        $gagal++;
                    }
                }
                
                echo "<script>
                      alert('Import Selesai! Berhasil: $berhasil baris. Gagal/Dilewati: $gagal baris.');
                      document.location.href = './ktnpelanggaran.php';
                      </script>";
            } else {
                $error = SimpleXLSX::parseError();
                echo "<script>
                      alert('Gagal membaca file Excel: $error');
                      document.location.href = './ktnpelanggaran.php';
                      </script>";
            }
        } else {
            echo "<script>
                  alert('Format file tidak didukung! Pastikan file berformat .xlsx');
                  document.location.href = './ktnpelanggaran.php';
                  </script>";
        }
    } else {
        echo "<script>
              alert('Tidak ada file yang diunggah!');
              document.location.href = './ktnpelanggaran.php';
              </script>";
    }
} else {
    header("Location: ./ktnpelanggaran.php");
}
?>
