<?php
$id = $_GET["id"];

if(!$id) {
    return header("Location: ../../index.php");
}

require '../functions.php';

if(isset($_POST["ganti"])) {
    if ( ubah_password($_POST, $id) > 0 ) {
        echo "<script>
                alert('Password berhasil diubah!')
                // redirect versi javascript
                document.location.href = '../../index.php';
            </script>
            ";
            } else {
                echo "
                <script>
                alert('Password gagal diubah!')
                // redirect versi javascript
                document.location.href = '../../index.php';
            </script>
            ";
    }
}
?>