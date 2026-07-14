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

                    $nip = htmlspecialchars($row[0]);
                    $nama = htmlspecialchars(ucwords($row[1]));
                    $email = htmlspecialchars($row[2]);
                    $role = htmlspecialchars(strtolower($row[3]));
                    
                    if(empty($nip) || empty($nama)) {
                        $gagal++;
                        continue;
                    }

                    $cek_nip = mysqli_query($conn, "SELECT * FROM guru_pembina WHERE nip = '$nip'");
                    if (mysqli_num_rows($cek_nip) > 0) {
                        $gagal++;
                        continue; 
                    }

                    $query = "INSERT INTO guru_pembina (`nip`, `nama_guru`, `email`, `role`, `password`) VALUES ('$nip', '$nama', '$email', '$role', '$nip')";
                    
                    if(mysqli_query($conn, $query)) {
                        $berhasil++;
                    } else {
                        $gagal++;
                    }
                }
                
                echo "<script>
                      alert('Import Selesai! Berhasil: $berhasil baris. Gagal/Dilewati: $gagal baris.');
                      document.location.href = './guru.php';
                      </script>";
            } else {
                $error = SimpleXLSX::parseError();
                echo "<script>
                      alert('Gagal membaca file Excel: $error');
                      document.location.href = './guru.php';
                      </script>";
            }
        } else {
            echo "<script>
                  alert('Format file tidak didukung! Pastikan file berformat .xlsx');
                  document.location.href = './guru.php';
                  </script>";
        }
    } else {
        echo "<script>
              alert('Tidak ada file yang diunggah!');
              document.location.href = './guru.php';
              </script>";
    }
} else {
    header("Location: ./guru.php");
}
?>
