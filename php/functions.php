<?php
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "root", "pelanggaran_siswa");

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function lapor($data, $pelapor)
{
    global $conn;

    $plgr = $data["pelanggaran"];
    $id_pelanggar = $data["nama"];
    $waktu_pelanggaran = date("Y-m-d");
    $pelanggaran = implode(",", $plgr);
    $poin = query("SELECT poin_pelanggaran FROM ket_pelanggaran WHERE id_pelanggaran in ($pelanggaran)");
    for ($i = 0; $i < count($poin); $i++) {
        $poin_plgr[] = intval($poin[$i]["poin_pelanggaran"]);
    }
    $jmlh_poin_plgr = array_sum($poin_plgr);
    $jmlh_poin_siswa = query("SELECT jmlh_poin FROM siswa WHERE id_siswa=$id_pelanggar")[0]["jmlh_poin"];
    $poin_akhir = $jmlh_poin_siswa - $jmlh_poin_plgr;

    $query = "INSERT INTO pelanggaran_siswa VALUES ('','$id_pelanggar', '$pelapor', '$pelanggaran', '$jmlh_poin_plgr','$waktu_pelanggaran')";
    mysqli_query($conn, $query);

    mysqli_query($conn, "UPDATE siswa SET jmlh_poin=$poin_akhir WHERE id_siswa=$id_pelanggar");

    return mysqli_affected_rows($conn);
}

function tambah_guru($data)
{
    global $conn;

    $nip = htmlspecialchars($data["nip"]);
    $nama = htmlspecialchars(ucwords($data["nama"]));
    $email = htmlspecialchars($data["email"]);
    $role = htmlspecialchars(($data["role"]));

    $query = "INSERT INTO guru_pembina (`nip`, `nama_guru`, `email`, `role`, `password`) VALUES ('$nip', '$nama', '$email', '$role', '$nip')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function tambah_siswa($data)
{
    global $conn;

    $kelas = $data["kelas"];
    $jurusan = $data["jurusan"];
    $nis = htmlspecialchars($data["nis"]);
    $nama = htmlspecialchars(ucwords($data["nama"]));
    $role = htmlspecialchars(($data["role"]));
    $email = htmlspecialchars($data["email"]);

    $cek_nis = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = $nis");

    if (mysqli_num_rows($cek_nis) > 0) {
        echo "<script>
              alert('NIS sudah dipakai');
              </script>";
        return false;
    }

    $foto = null;

    if (!empty($_FILES["foto"]["tmp_name"])) {
        $foto = upload();
    }


    mysqli_query($conn, "INSERT INTO siswa (`id_kelas`, `id_jurusan`, `nis`, `nama_siswa`, `email`, `jmlh_poin`, `role`, `foto`, `password`) VALUES ('$kelas', '$jurusan', '$nis', '$nama', '$email', '100', '$role', '$foto', '$nis')");

    return mysqli_affected_rows($conn);
}

function tambah_pelanggaran($data)
{
    global $conn;

    $jenis_plgr = htmlspecialchars(strtolower($data["jenis_plgr"]));
    $det_plgr = htmlspecialchars(ucfirst($data["det_plgr"]));
    $poin_plgr = htmlspecialchars($data["poin"]);

    mysqli_query($conn, "INSERT INTO ket_pelanggaran (`jenis_pelanggaran`, `det_pelanggaran`, `poin_pelanggaran`) VALUES ('$jenis_plgr', '$det_plgr', '$poin_plgr')");

    return mysqli_affected_rows($conn);
}

function tambah_ketprestasi($data)
{
    global $conn;
    $det_prestasi = htmlspecialchars(ucfirst($data["det_prestasi"]));
    $poin_prestasi = htmlspecialchars($data["poin"]);

    mysqli_query($conn, "INSERT INTO ket_prestasi (`det_prestasi`, `poin_prestasi`) VALUES ('$det_prestasi', '$poin_prestasi')");

    return mysqli_affected_rows($conn);
}

function hapus_plgr($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM ket_pelanggaran WHERE id_pelanggaran = $id");

    return mysqli_affected_rows($conn);
}

function hapus_ketprestasi($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM ket_prestasi WHERE id_prestasi = $id");

    return mysqli_affected_rows($conn);
}

function hapus_prestasi($id_prestasi, $id_siswa)
{
    global $conn;

    $poin_siswa = mysqli_query($conn, "SELECT jmlh_poin FROM siswa WHERE id_siswa = $id_siswa")->fetch_assoc();
    $poin_prestasi = mysqli_query($conn, "SELECT poin_bertambah FROM prestasi_siswa WHERE id_prestasi_siswa = $id_prestasi")->fetch_assoc();
    $poin_siswa = intval($poin_siswa["jmlh_poin"]);
    $poin_prestasi = intval($poin_prestasi["poin_bertambah"]);

    $poin_akhir = $poin_siswa - $poin_prestasi;

    mysqli_query($conn, "UPDATE siswa SET jmlh_poin = $poin_akhir WHERE id_siswa = $id_siswa");

    mysqli_query($conn, "DELETE FROM prestasi_siswa WHERE id_prestasi_siswa = $id_prestasi");

    return mysqli_affected_rows($conn);
}

function hapus_plgrSiswa($id_plgr, $id_siswa, $poin)
{
    global $conn;

    $poin_siswa = mysqli_query($conn, "SELECT jmlh_poin FROM siswa WHERE id_siswa = $id_siswa")->fetch_assoc();
    $poin_siswa = intval($poin_siswa["jmlh_poin"]);
    $poin = intval($poin);
    $poin_akhir = $poin_siswa + $poin;

    mysqli_query($conn, "DELETE FROM pelanggaran_siswa WHERE id_pelanggaran_siswa = $id_plgr");
    mysqli_query($conn, "UPDATE siswa SET jmlh_poin = $poin_akhir WHERE id_siswa = $id_siswa");

    return mysqli_affected_rows($conn);
}

function hapus_guru($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM guru_pembina WHERE id_guru = $id");

    return mysqli_affected_rows($conn);
}

function hapus_siswa($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM pelanggaran_siswa WHERE id_pelanggar = $id");
    mysqli_query($conn, "DELETE FROM siswa WHERE id_siswa = $id");

    return mysqli_affected_rows($conn);
}

function ubah_plgr($data)
{
    global $conn;

    $id = $data["id"];
    $jenis_plgr = htmlspecialchars(ucwords($data["jenis_plgr"]));
    $det_plgr = htmlspecialchars($data["det_plgr"]);
    $poin = htmlspecialchars($data["poin"]);

    mysqli_query($conn, "UPDATE ket_pelanggaran SET 
                 jenis_pelanggaran = '$jenis_plgr',
                 det_pelanggaran = '$det_plgr', 
                 poin_pelanggaran = '$poin'
                 WHERE id_pelanggaran = $id
                 ");

    return mysqli_affected_rows($conn);
}

function ubah_ketprestasi($data)
{
    global $conn;

    $id = $data["id"];
    $det_prestasi = htmlspecialchars($data["det_prestasi"]);
    $poin = htmlspecialchars($data["poin"]);

    mysqli_query($conn, "UPDATE ket_prestasi SET 
                 det_prestasi = '$det_prestasi', 
                 poin_prestasi = '$poin'
                 WHERE id_prestasi = $id
                 ");

    return mysqli_affected_rows($conn);
}

function ubah_carousel()
{
    global $conn;
    if (!empty($_FILES["foto"]["name"][0])) {
        $result = [];
        for ($i = 0; $i < count($_FILES["foto"]["name"]); $i++) {
            $ekstensiFotoValid = ['jpg', 'jpeg', 'png'];
            $ekstensiFoto = explode(".", $_FILES["foto"]["name"][$i]);
            $ekstensiFoto = strtolower(end($ekstensiFoto));
            if (!in_array($ekstensiFoto, $ekstensiFotoValid)) {
                echo "<script>
                        alert('Ekstensi Foto dilarang!');
                        </script>";
                return false; // agar fungsi tambah tidak dijalankan
            }
            move_uploaded_file($_FILES["foto"]["tmp_name"][$i], 'img/' . $_FILES["foto"]["name"][$i]);
            $namaFoto[] = $_FILES['foto']['name'][$i];
        }
        $foto = implode(",", $namaFoto);

        mysqli_query($conn, "UPDATE komponen SET isi_komponen = '$foto' WHERE nama_komponen = 'login_carousel'");
    }

    return mysqli_affected_rows($conn);
}

function ubah_fotoIndex()
{
    global $conn;
    if (!empty($_FILES["foto"]["name"][0])) {
        $result = [];
        for ($i = 0; $i < count($_FILES["foto"]["name"]); $i++) {
            $ekstensiFotoValid = ['jpg', 'jpeg', 'png'];
            $ekstensiFoto = explode(".", $_FILES["foto"]["name"][$i]);
            $ekstensiFoto = strtolower(end($ekstensiFoto));
            if (!in_array($ekstensiFoto, $ekstensiFotoValid)) {
                echo "<script>
                        alert('Ekstensi Foto dilarang!');
                        </script>";
                return false; // agar fungsi tambah tidak dijalankan
            }
            move_uploaded_file($_FILES["foto"]["tmp_name"][$i], 'img/' . $_FILES["foto"]["name"][$i]);
            $namaFoto[] = $_FILES['foto']['name'][$i];
        }
        $foto = implode(",", $namaFoto);

        mysqli_query($conn, "UPDATE komponen SET isi_komponen = '$foto' WHERE nama_komponen = 'foto_index'");
    }

    return mysqli_affected_rows($conn);
}

function ubah_guru($data)
{
    global $conn;

    $id = $_POST["id"];
    $nip = htmlspecialchars($data["nip"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);


    mysqli_query($conn, "UPDATE guru_pembina SET 
                 nip = '$nip',
                 nama_guru = '$nama', 
                 email = '$email'
                 WHERE id_guru = $id
                 ");

    return mysqli_affected_rows($conn);
}

function ubah_siswa($data)
{
    global $conn;

    $id = $data["id"];
    $kelas = $data["kelas"];
    $jurusan = $data["jurusan"];
    $nis = htmlspecialchars($data["nis"]);
    $nama = htmlspecialchars(ucwords($data["nama"]));
    $email = htmlspecialchars($data["email"]);
    $fotoLama = $data["fotoLama"];

    if ($_FILES["foto"]["error"] === 4) {
        $foto = $fotoLama;
    } else {
        $foto = upload();
    }

    if (!$foto) {
        return false;
    }

    mysqli_query($conn, "UPDATE siswa SET
                 id_kelas = '$kelas',
                 id_jurusan = '$jurusan',
                 nis = '$nis',
                 nama_siswa = '$nama',
                 email = '$email',
                 foto = '$foto'
                 WHERE id_siswa = $id
                 ");

    return mysqli_affected_rows($conn);
}

function ubah_password($data, $id)
{
    global $conn;

    $username = $data["username"];
    $pw_lama = htmlspecialchars($data["pw_lama"]);
    $pw_baru = htmlspecialchars($data["pw_baru"]);
    $con_pw_baru = htmlspecialchars($data["con_pw_baru"]);

    if (strlen($username) === 16 || strlen($username) === 18) {
        $passwordLama = mysqli_query($conn, "SELECT `password` FROM guru_pembina WHERE id_guru = $id")->fetch_assoc();
        if ($pw_lama === $passwordLama["password"]) {
            if ($pw_baru === $con_pw_baru) {
                mysqli_query($conn, "UPDATE guru_pembina SET `password` = $pw_baru WHERE id_guru = $id");
            } else {
                echo "<script>
                  alert('Konfirmasi password baru berbeda!');
                  </script>";
                return false;
            }
        } else {
            echo "<script>
                  alert('Password lama salah!');
                  </script>";
            return false;
        }
    } else {
        $passwordLama = mysqli_query($conn, "SELECT `password` FROM siswa WHERE id_siswa = $id")->fetch_assoc();
        if ($pw_lama === $passwordLama["password"]) {
            if ($pw_baru === $con_pw_baru) {
                mysqli_query($conn, "UPDATE siswa SET `password` = $pw_baru WHERE id_siswa = $id");
            } else {
                echo "<script>
                  alert('Konfirmasi password baru berbeda!');
                  </script>";
                return false;
            }
        } else {
            echo "<script>
                  alert('Password lama salah!');
                  </script>";
            return false;
        }
    }

    return mysqli_affected_rows($conn);
}

function ubah_foto($data, $id)
{
    global $conn;

    $fotoLama = $data["fotoLama"];

    if ($_FILES["foto"]["error"] === 4) {
        echo "<script>
              alert('Foto siswa wajib ada!');
              </script>";
        return false; // agar fungsi tambah tidak dijalankan
    } else {
        $foto = upload();
    }

    if (!$foto) {
        return false;
    }

    mysqli_query($conn, "UPDATE siswa SET `foto` = '$foto' WHERE id_siswa = $id");

    return mysqli_affected_rows($conn);
}

function tambah_prestasi($data, $id)
{
    global $conn;

    $prestasi = intval(($data["prestasi"]));
    $tgl_lomba = $data["tgl_prestasi"];
    $bukti = upload_doc();

    if (!$bukti) {
        return false;
    }

    $poin_bertambah = mysqli_query($conn, "SELECT poin_prestasi FROM ket_prestasi WHERE id_prestasi = $prestasi")->fetch_assoc();
    $poin_siswa = mysqli_query($conn, "SELECT jmlh_poin FROM siswa WHERE id_siswa = $id")->fetch_assoc();
    $poin_bertambah = $poin_bertambah["poin_prestasi"];
    $poin_siswa = $poin_siswa["jmlh_poin"];

    $poin_akhir = $poin_siswa + $poin_bertambah;

    mysqli_query($conn, "UPDATE siswa SET jmlh_poin = $poin_akhir WHERE id_siswa = $id");

    mysqli_query($conn, "INSERT INTO prestasi_siswa (`id_siswa`, `id_prestasi`, `poin_bertambah`, `tgl_prestasi`, `bukti`) VALUES ('$id', '$prestasi', '$poin_bertambah', '$tgl_lomba', '$bukti')");

    return mysqli_affected_rows($conn);
}

function reset_poin()
{
    global $conn;

    mysqli_query($conn, "UPDATE siswa SET `jmlh_poin` = 100");

    return mysqli_affected_rows($conn);
}

function upload()
{
    $namaFile = $_FILES["foto"]["name"];
    $ukuranFile = $_FILES["foto"]["size"];
    $error = $_FILES["foto"]["error"];
    $tmpName = $_FILES["foto"]["tmp_name"];

    // if ( $error === 4 ) {
    //     echo "<script>
    //           alert('Foto siswa wajib ada!');
    //           </script>";
    //     return false; // agar fungsi tambah tidak dijalankan
    // }

    $ekstensiFotoValid = ['jpg', 'jpeg', 'png'];
    $ekstensiFoto = explode(".", $namaFile);
    $ekstensiFoto = strtolower(end($ekstensiFoto));

    // fungsi in_array untuk mencari suatu kata di dalam array yang akan menghasilkan false jika tidak ada dan true jika ada
    if (!in_array($ekstensiFoto, $ekstensiFotoValid)) {
        echo "<script>
              alert('Jenis file dilarang!');
              </script>";
        return false; // agar fungsi tambah tidak dijalankan
    }

    // cek ukuran gambar
    if ($ukuranFile > 20480000) {
        echo "<script>
              alert('Ukuran file foto terlalu besar!');
              </script>";
        return false; // agar fungsi tambah tidak dijalankan
    }

    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiFoto;

    // destinasinya relatif terhadap file functions ini
    // move_uploaded_file($tmpName, 'C:/xampp/htdocs/website_pelanggaran_siswa/foto_siswa/' . $namaFile);
    move_uploaded_file($tmpName, '../foto_siswa/' . $namaFileBaru);

    // mengembalikan nama filenya untuk ditamngkap $gambar di function tambah() agar namanya disimpan di database
    return $namaFileBaru;
}

function upload_doc()
{
    $namaFile = $_FILES["dok"]["name"];
    $ukuranFile = $_FILES["dok"]["size"];
    $error = $_FILES["dok"]["error"];
    $tmpName = $_FILES["dok"]["tmp_name"];

    $ekstensiDokValid = ['docx', 'pdf', 'doc'];
    $ekstensiDok = explode(".", $namaFile);
    $ekstensiDok = strtolower(end($ekstensiDok));

    // fungsi in_array untuk mencari suatu kata di dalam array yang akan menghasilkan false jika tidak ada dan true jika ada
    if (!in_array($ekstensiDok, $ekstensiDokValid)) {
        echo "<script>
              alert('Jenis file dilarang!');
              </script>";
        return false; // agar fungsi tambah tidak dijalankan
    }

    // cek ukuran gambar
    if ($ukuranFile > 1000000000) {
        echo "<script>
              alert('Ukuran file foto terlalu besar!');
              </script>";
        return false; // agar fungsi tambah tidak dijalankan
    }

    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiDok;

    // destinasinya relatif terhadap file functions ini
    // move_uploaded_file($tmpName, 'C:/xampp/htdocs/website_pelanggaran_siswa/foto_siswa/' . $namaFile);
    move_uploaded_file($tmpName, '../dokumen/' . $namaFileBaru);

    // mengembalikan nama filenya untuk ditamngkap $gambar di function tambah() agar namanya disimpan di database
    return $namaFileBaru;
}
