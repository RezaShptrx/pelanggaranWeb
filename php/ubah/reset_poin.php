<?php
if(isset($_SESSION["guru"])) {
    echo "<script>
            alert('Hanya admin yang punya hak reset poin!');
            document.location.href = '../logout.php';
        </script>";
}

if(isset($_SESSION["osis"])) {
    echo "<script>
            alert('Hanya admin yang punya hak reset poin!');
            document.location.href = '../logout.php';
        </script>";
}

if(isset($_SESSION["siswa"])) {
    echo "<script>
            alert('Hanya admin yang punya hak reset poin!');
            document.location.href = '../logout.php';
        </script>";
}

require "../functions.php";

if(reset_poin() > 0) {
    echo "<script>
              alert('Poin berhasil di reset!');
              document.location.href = '../siswa.php';
              </script>";
    } else {
        echo "<script>
              alert('Poin gagal di reset!');
              document.location.href = '../siswa.php';
              </script>";
    }
?>