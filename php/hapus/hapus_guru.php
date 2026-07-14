<?php
require '../functions.php';

$id = $_GET["id"];

if(!$id) {
    return header("Location: ../guru.php");
}

if ( hapus_guru($id) > 0 ) {
    echo "
        <script>
            alert('Data berhasil dihapus!')
            // redirect versi javascript
            document.location.href = '../guru.php';
            </script>
            ";
        } else {
            echo "
            <script>
            alert('Data gagal dihapus!')
            // redirect versi javascript
            document.location.href = '../guru.php';
        </script>
        ";
}
?>