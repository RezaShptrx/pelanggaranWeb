<?php
$id = $_GET["id"];

if(!$id) {
    return header("Location: ../../index.php");
}

require '../functions.php';

if(isset($_POST["ganti_email"])) {
    if ( ubah_email($_POST, $id) > 0 ) {
        echo "<script>
                alert('Email berhasil diubah!')
                // redirect versi javascript
                document.location.href = '../halaman_siswa.php';
            </script>
            ";
            } else {
                echo "
                <script>
                alert('Email gagal diubah!')
                // redirect versi javascript
                document.location.href = '../halaman_siswa.php';
            </script>
            ";
    }
}
?>
