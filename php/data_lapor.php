<?php
require './functions.php';


if(isset($_POST["kelas"])) {
    $kelas = $_POST["kelas"];

    $query = query("SELECT id_jurusan, kode_jurusan FROM jurusan WHERE id_kelas=$kelas");
    ?>
    <option>Pilih jurusan</option>
    <?php 
    foreach($query as $hasil) {
        ?>
        <option value="<?= $hasil["id_jurusan"]; ?>"><?= $hasil["kode_jurusan"]; ?></option>
    
    <?php 
    }
}

if(isset($_POST["jurusan"])) {
    $jurusan = $_POST["jurusan"];

    $query = query("SELECT id_siswa, nama_siswa FROM siswa WHERE id_jurusan=$jurusan");
    ?>

    <option>Pilih nama siswa</option>
    
    <?php 
    foreach($query as $hasil) {
        ?>
        <option value="<?= $hasil["id_siswa"]; ?>"><?= ucwords($hasil["nama_siswa"]); ?></option>

    <?php 
    }
}
?>