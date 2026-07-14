<?php
require '../functions.php';

$id_plgr = $_GET["id_plgr"];
$id_siswa = $_GET["id_siswa"];
$poin = $_GET["poin"];

if (!$id_plgr) {
    return header("Location: ../data_siswa.php?id=" . $id_siswa);
}

if (hapus_plgrSiswa($id_plgr, $id_siswa, $poin) > 0) {
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
