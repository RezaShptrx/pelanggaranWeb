<?php
require '../functions.php';

$id = $_GET["id"];

if(!$id) {
    return header("Location: ../siswa.php");
}

if ( hapus_siswa($id) > 0 ) {
    echo "
        <script>
            alert('Data berhasil dihapus!')
            // redirect versi javascript
            document.location.href = '../siswa.php';
            </script>
            ";
        } else {
            echo "
            <script>
            alert('Data gagal dihapus!')
            // redirect versi javascript
            document.location.href = '../siswa.php';
        </script>
        ";
}
?>