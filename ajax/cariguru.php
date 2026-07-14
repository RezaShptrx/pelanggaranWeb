<?php
require '../php/functions.php';

$jmlh_guru = query("SELECT * FROM guru_pembina");

$batas = 10;
$halaman = isset($_GET["halaman"])?(int)$_GET["halaman"] : 1;
$halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
$previous = $halaman - 1;
$next = $halaman + 1;

$keyword = $_GET["keyword"];

$query = "SELECT id_guru, nip, nama_guru, email FROM guru_pembina WHERE
          nip LIKE '$keyword%' OR
          nama_guru LIKE '%$keyword%'
          LIMIT 10
          ";

$guru_sekolah = query($query);

if(empty($_GET["keyword"])) {
    $jumlah_data = count($jmlh_guru);
} else {
    $jumlah_data = count($guru_sekolah);
}

$total_halaman = ceil($jumlah_data/$batas);
$nomor = $halaman_awal+1;
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru | SMKN 12 JAKARTA</title>
</head>
<body>  
    <section style="margin: 0 -12px 0 -12px">
        <div class="container-lg" id="container_guru">
            <div class="table-responsive-sm">
                <table border="1" cellpadding="10" cellspacing="0" class="table table-bordered table-hover text-center">
                    <thead class="table-light">
                        <th class="align-middle">No.</th>
                        <th class="align-middle">Nama</th>
                        <th class="align-middle">NIP / NUPTK</th>
                    </thead>
                    <?php foreach( $guru_sekolah as $guru) : ?>
                    <tbody>
                        <th><?= $nomor++; ?></th>
                        <td class="text-start"><a href="../php/data_guru.php?id=<?= $guru["id_guru"]; ?>"><?= $guru["nama_guru"]; ?></a></td>
                        <td><?= $guru["nip"]; ?></td>
                        <td style="width:3rem;">
                            <div class="dropdown">
                                <button class="btn" type="button" id="actionMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionMenu">
                                    <li><a class="dropdown-item" href="../php/hapus/hapus_guru.php?id=<?= $guru["id_guru"]; ?>" onclick="return confirm('Hapus data?')">Hapus</a></li>
                                    <li><a class="dropdown-item" href="../php/ubah/ubah_guru.php?id=<?= $guru["id_guru"]; ?>">Ubah</a></li>
                                </ul>
                            </div>
                        </td>
                    </tbody>
                    <?php endforeach; ?>
                </table>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a  class="page-link text-dark" <?php if($halaman > 1){echo "href='?halaman=$previous'";} ?>><span aria-hidden="true">&laquo;</span></a>
                        </li>
                        <?php for($i=1;$i<=$total_halaman;$i++) : ?>
                        <li class="page-item">
                            <a href="?halaman=<?= $i; ?>" class="page-link text-dark"><?= $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item">
                            <a class="page-link text-dark" <?php if($halaman < $total_halaman){echo "href='?halaman=$next'";} ?>><span aria-hidden="true">&raquo;</span></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

</body>
</html>