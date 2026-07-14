<?php
require '../functions.php';

$id_prestasi = $_GET["id_prestasi"];
$id_siswa = $_GET["id_siswa"];

if (!$id_prestasi) {
    return header("Location: ../data_siswa.php?id=" . $id_siswa);
}

if (hapus_prestasi($id_prestasi, $id_siswa) > 0) {
    echo "
        <script>
            alert('Data berhasil dihapus!')
            // redirect versi javascript
            document.location.href = '../data_siswa.php?id=' + $id_siswa;
            </script>
            ";
} else {
    echo "
            <script>
            alert('Data gagal dihapus!')
            // redirect versi javascript
            document.location.href = '../data_siswa.php?id=' + $id_siswa;
        </script>
        ";
}
