<?php
$id = $_GET["id"];

if(!$id) {
    return header("Location: ../../index.php");
}

require '../functions.php';

if(isset($_POST["ganti"])) {
    // var_dump($_POST);
    // var_dump(ubah_foto($_POST, $id));
    if(ubah_foto($_POST, $id) > 0) {
        echo "<script>
                alert('Foto berhasil diubah!')
                // redirect versi javascript
                document.location.href = '../../index.php';
            </script>
            ";
    }
    else {
        echo mysqli_error($conn);
        echo "<script>
                alert('Gagal diubah!')
                // redirect versi javascript
                document.location.href = '../../index.php';
            </script>
            ";
    }
}
?>