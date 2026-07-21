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

$keyword_input = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$filter_kelas = isset($_GET["kelas"]) ? $_GET["kelas"] : "";
$filter_jurusan = isset($_GET["jurusan"]) ? $_GET["jurusan"] : "";

$where_clauses = [];
if ($keyword_input != "") {
    $where_clauses[] = "(siswa.nis LIKE '%$keyword_input%' OR siswa.nama_siswa LIKE '%$keyword_input%')";
}
if ($filter_kelas != "") {
    $where_clauses[] = "siswa.id_kelas = '$filter_kelas'";
}
if ($filter_jurusan != "") {
    $where_clauses[] = "jurusan.kode_jurusan = '$filter_jurusan'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
}

$query = "SELECT siswa.id_siswa, siswa.id_kelas, siswa.id_jurusan, siswa.nis, 
      siswa.nama_siswa, siswa.jmlh_poin, kelas.nama_kelas, jurusan.kode_jurusan FROM siswa 
      INNER JOIN kelas ON siswa.id_kelas=kelas.id_kelas 
      INNER JOIN jurusan ON siswa.id_jurusan=jurusan.id_jurusan" . $where_sql . " ORDER BY jmlh_poin, nama_kelas LIMIT $halaman_awal, $batas";

$siswa_sekolah = query($query);

$query_count = "SELECT * FROM siswa INNER JOIN kelas ON siswa.id_kelas=kelas.id_kelas INNER JOIN jurusan ON siswa.id_jurusan=jurusan.id_jurusan" . $where_sql;
$jumlah_data = count(query($query_count));

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
                        <?php 
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
                            <td><?= $siswa["nama_kelas"]; ?> <?= $siswa["kode_jurusan"]; ?></td>
                        </tbody>
                    <?php endforeach; ?>
                </table>
                <nav class="mt-4">
                    <?php 
                        $qs = $_GET;
                        unset($qs['halaman']);
                        $query_string = http_build_query($qs);
                        $query_string = $query_string ? '&' . $query_string : '';
                    ?>
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link text-dark" <?php if ($halaman > 1) {
                                                                echo "href='?halaman=$previous$query_string'";
                                                            } ?>><span aria-hidden="true">&laquo;</span></a>
                        </li>
                        <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                            <li class="page-item">
                                <a href="?halaman=<?= $i; ?><?= $query_string ?>" class="page-link text-dark"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item">
                            <a class="page-link text-dark" <?php if ($halaman < $total_halaman) {
                                                                echo "href='?halaman=$next$query_string'";
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