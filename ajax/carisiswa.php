<?php
session_start();

if (isset($_SESSION["osis"])) {
    $osis = 'hidden';
} else {
    $osis = "";
}

require '../php/functions.php';

$jmlh_siswa = query("SELECT * FROM siswa");

$batas = 50;
$halaman = isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;
$previous = $halaman - 1;
$next = $halaman + 1;

if (isset($_GET["keyword"])) {
    $keyword = $_GET["keyword"];
    $keyword = explode(" ", $keyword);
    if (count($keyword) == 2) {
        $query = "SELECT siswa.id_siswa, siswa.id_kelas, siswa.id_jurusan, siswa.nis, 
              siswa.nama_siswa, siswa.jmlh_poin, kelas.nama_kelas, jurusan.kode_jurusan FROM siswa 
              INNER JOIN kelas ON siswa.id_kelas=kelas.id_kelas 
              INNER JOIN jurusan ON siswa.id_jurusan=jurusan.id_jurusan WHERE
              nama_kelas LIKE '%$keyword[0]' AND kode_jurusan LIKE '%$keyword[1]%' ORDER BY jmlh_poin, nama_kelas LIMIT $halaman_awal, $batas
              ";
    } else {
        $query = "SELECT siswa.id_siswa, siswa.id_kelas, siswa.id_jurusan, siswa.nis, 
              siswa.nama_siswa, siswa.jmlh_poin, kelas.nama_kelas, jurusan.kode_jurusan FROM siswa 
              INNER JOIN kelas ON siswa.id_kelas=kelas.id_kelas 
              INNER JOIN jurusan ON siswa.id_jurusan=jurusan.id_jurusan WHERE
              id_siswa LIKE '%$keyword[0]%' OR
              nis LIKE '%$keyword[0]%' OR
              nama_siswa LIKE '%$keyword[0]%' OR
              nama_kelas LIKE '$keyword[0]%' OR
              kode_jurusan LIKE '$keyword[0]%' ORDER BY jmlh_poin, nama_kelas LIMIT $halaman_awal, $batas
              ";
    }

    $siswa_sekolah = query($query);
}

if (empty($_GET["keyword"])) {
    $jumlah_data = count($jmlh_siswa);
} else {
    $jumlah_data = count($siswa_sekolah);
}

$total_halaman = ceil($jumlah_data / $batas);
$nomor = $halaman_awal + 1;

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa | SMKN 12 JAKARTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/umum.css">
</head>

<body>
    <section style="margin: 0 -12px 0 -12px">
        <div class="container-lg " id="container_siswa">
            <div class="table-responsive-sm">
                <table border="1" cellpadding="10" cellspacing="0" class="table table-bordered table-hover text-center">
                    <thead class="table-light">
                        <th class="align-middle">No.</th>
                        <th class="align-middle" <?= $osis; ?>>NIS</th>
                        <th class="align-middle">Nama</th>
                        <th class="align-middle">Poin</th>
                        <th class="align-middle">Kelas</th>
                    </thead>
                    <?php foreach ($siswa_sekolah as $siswa) : ?>
                        <?php $jurusan = query("SELECT kode_jurusan FROM jurusan WHERE id_jurusan=" . $siswa['id_jurusan'])[0];
                        $jmlh_poin = intval($siswa["jmlh_poin"]);
                        ?>
                        <tbody>
                            <th><?= $nomor++; ?></th>
                            <td <?= $osis; ?>><?= $siswa["nis"]; ?></td>
                            <td class="text-start ps-3"><a href="./data_siswa.php?id=<?= $siswa["id_siswa"]; ?>" class=""><?= $siswa["nama_siswa"]; ?></a></td>
                            <td>
                                <?php if ($jmlh_poin > 0) : ?>
                                    <?= $siswa["jmlh_poin"]; ?>
                                <?php else : ?>
                                    Drop Out
                                <?php endif; ?>
                            </td>
                            <td><?= $siswa["nama_kelas"]; ?> <?= $jurusan["kode_jurusan"]; ?></td>
                        </tbody>
                    <?php endforeach; ?>
                </table>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link text-dark" <?php if ($halaman > 1) {
                                                                echo "href='?halaman=$previous'";
                                                            } ?>><span aria-hidden="true">&laquo;</span></a>
                        </li>
                        <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                            <li class="page-item">
                                <a href="?halaman=<?= $i; ?>" class="page-link text-dark"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item">
                            <a class="page-link text-dark" <?php if ($halaman < $total_halaman) {
                                                                echo "href='?halaman=$next'";
                                                            } ?>><span aria-hidden="true">&raquo;</span></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>