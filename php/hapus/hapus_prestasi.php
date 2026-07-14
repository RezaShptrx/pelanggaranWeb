<?php
require '../functions.php';

$id = $_GET["id"];

if (!$id) {
    return header("Location: ../ktnpelanggaran.php");
}

if (hapus_ketprestasi($id) > 0) {
    echo "
        <script>
            alert('Data berhasil dihapus!')
            // redirect versi javascript
            document.location.href = './ktnpelanggaran.php';
            </script>
            ";
} else {
    echo "
            <script>
            alert('Data gagal dihapus!')
            // redirect versi javascript
            document.location.href = './ktnpelanggaran.php';
        </script>
        ";
}
