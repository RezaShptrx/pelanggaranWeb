<?php 
require "../functions.php";

if(!isset($_GET["role"])) {
    header("Location: ../data_siswa.php?id=". $_GET["sk"]);
}

$role = $_GET["role"];
$id = $_GET["sk"];

if($role === "siswa") {
    mysqli_query($conn, "UPDATE siswa SET `role` = 'osis' WHERE id_siswa = $id");

    if(mysqli_affected_rows($conn) > 0) {
        echo "<script>
                alert('Siswa berhasil dijadikan OSIS!')
                document.location.href = '../data_siswa.php?id=$id';
                </script>
                ";
    } else {
        echo "<script>
                alert('Siswa gagal dijadikan OSIS!')
                document.location.href = '../data_siswa.php?id=$id';
                </script>
                ";
    }
}
elseif($role === "osis") {
    mysqli_query($conn, "UPDATE siswa SET `role` = 'siswa' WHERE id_siswa = $id");

    if(mysqli_affected_rows($conn) > 0) {
        echo "<script>
                alert('Berhasil dijadikan siswa!')
                document.location.href = '../data_siswa.php?id=$id';
                </script>
                ";
    } else {
        echo "<script>
                alert('Gagal dijadikan siswa!')
                document.location.href = '../data_siswa.php?id=$id';
                </script>
                ";
    }
}




