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
            if ($xlsx = \Shuchkin\SimpleXLSX::parse($_FILES['file_excel']['tmp_name'])) {
                $rows = $xlsx->rows();
                $berhasil = 0;
                $gagal = 0;
                
                foreach ($rows as $index => $row) {
                    // Skip header row
                    if ($index === 0) continue;
                    
                    // Cek jika baris kosong
                    if(empty($row[0]) && empty($row[1]) && empty($row[2])) continue;

                    $kelas = $row[0];
                    $kode_jurusan = htmlspecialchars($row[1]);
                    $nis = htmlspecialchars($row[2]);
                    $nama = htmlspecialchars(ucwords($row[3]));
                    $email = htmlspecialchars($row[4]);
                    $role = htmlspecialchars(strtolower($row[5]));
                    $nama_orang_tua = isset($row[6]) ? htmlspecialchars(ucwords($row[6])) : '';
                    $nomor_whatsapp = isset($row[7]) ? htmlspecialchars($row[7]) : '';
                    
                    // Validasi minimal
                    if(empty($nis) || empty($nama) || empty($kode_jurusan) || empty($kelas)) {
                        $gagal++;
                        continue;
                    }

                    // Cari id_jurusan berdasarkan kode_jurusan dan id_kelas
                    $cek_jurusan = mysqli_query($conn, "SELECT id_jurusan FROM jurusan WHERE kode_jurusan = '$kode_jurusan' AND id_kelas = '$kelas'");
                    if (mysqli_num_rows($cek_jurusan) > 0) {
                        $dt_jurusan = mysqli_fetch_assoc($cek_jurusan);
                        $jurusan = $dt_jurusan['id_jurusan'];
                    } else {
                        // Coba cari tanpa id_kelas jika tidak ketemu
                        $cek_jurusan2 = mysqli_query($conn, "SELECT id_jurusan FROM jurusan WHERE kode_jurusan = '$kode_jurusan'");
                        if (mysqli_num_rows($cek_jurusan2) > 0) {
                            $dt_jurusan2 = mysqli_fetch_assoc($cek_jurusan2);
                            $jurusan = $dt_jurusan2['id_jurusan'];
                        } else {
                            $gagal++;
                            continue; // Lewati jika kode jurusan tidak ditemukan
                        }
                    }

                    // Cek NIS sudah ada atau belum
                    $cek_nis = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = '$nis'");
                    if (mysqli_num_rows($cek_nis) > 0) {
                        $gagal++;
                        continue; // Lewati jika NIS sudah ada
                    }

                    $query = "INSERT INTO siswa (`id_kelas`, `id_jurusan`, `nis`, `nama_siswa`, `email`, `jmlh_poin`, `role`, `foto`, `password`) VALUES ('$kelas', '$jurusan', '$nis', '$nama', '$email', '100', '$role', NULL, '$nis')";
                    
                    if(mysqli_query($conn, $query)) {
                        $berhasil++;
                        $inserted_id_siswa = mysqli_insert_id($conn);
                        
                        if(!empty($nama_orang_tua) || !empty($nomor_whatsapp)) {
                            mysqli_query($conn, "INSERT INTO orang_tua (id_siswa, nama_orang_tua, nomor_whatsapp) VALUES ('$inserted_id_siswa', '$nama_orang_tua', '$nomor_whatsapp')");
                        }
                    } else {
                        $gagal++;
                    }
                }
                
                echo "<script>
                      alert('Import Selesai! Berhasil: $berhasil baris. Gagal/Dilewati: $gagal baris.');
                      document.location.href = './siswa.php';
                      </script>";
            } else {
                $error = \Shuchkin\SimpleXLSX::parseError();
                echo "<script>
                      alert('Gagal membaca file Excel: $error');
                      document.location.href = './siswa.php';
                      </script>";
            }
        } else {
            echo "<script>
                  alert('Format file tidak didukung! Pastikan file berformat .xlsx');
                  document.location.href = './siswa.php';
                  </script>";
        }
    } else {
        echo "<script>
              alert('Tidak ada file yang diunggah!');
              document.location.href = './siswa.php';
              </script>";
    }
} else {
    header("Location: ./siswa.php");
}
?>
