<?php
session_start();

if (!isset($_SESSION["login"])) {
    header('Location: ./login.php');
    exit;
}

if (isset($_SESSION["guru"])) {
    $guru = "hidden";
} else {
    $guru = "";
}

if (isset($_SESSION["osis"])) {
    header("Location: ./siswa.php");
}

if (isset($_SESSION["siswa"])) {
    $hide_siswa = "hidden";
    $link = "./data_siswa.php?id=" . $_SESSION["id_siswa"];
    $username = $_SESSION["nis"];
} else {
    $hide_siswa = "";
    $link = "./../index.php";
}

if (isset($_SESSION["admin"])) {
    $admin = "hidden";
} else {
    $admin = "";
}

include('./functions.php');
$id = $_SESSION["id_siswa"];

if (!$id) {
    header("Location: ./../index.php");
    exit;
}

$siswa = query("SELECT `id_kelas`, `id_jurusan`, `nis`, `nama_siswa`, `email`, `jmlh_poin`, `role`,`foto` FROM siswa WHERE id_siswa = $id")[0];
$kelas = query("SELECT nama_kelas FROM kelas WHERE id_kelas =" . $siswa["id_kelas"])[0];
$jurusan = query("SELECT nama_jurusan FROM jurusan WHERE id_jurusan = " . $siswa["id_jurusan"])[0];
$semua_pelanggaran_siswa = query("SELECT * FROM pelanggaran_siswa WHERE id_pelanggar = $id ORDER BY waktu_pelanggaran DESC");
$pelanggaran_siswa = array_slice($semua_pelanggaran_siswa, 0, 2);
$total_pelanggaran = count($semua_pelanggaran_siswa);
$prestasi_siswa  = query("SELECT * FROM prestasi_siswa WHERE id_siswa = $id");
$ket_prestasi = query("SELECT * FROM ket_prestasi");

if (isset($_POST["tambah_prestasi"])) {
    if (tambah_prestasi($_POST, $id) > 0) {
        echo "<script>
        alert('Data berhasil ditambahkan!');
        document.location.href = 'data_siswa.php?id=" . $id . "';
        </script>";
    } else {
        echo "<script>
        alert('Data gagal ditambahkan!');
        document.location.href = 'data_siswa.php?id=" . $id . "';
        </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa | <?= $siswa["nama_siswa"]; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="icon" href="../img/logosmk12.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif'],
                        serif: ['"Lora"', 'serif'],
                    },
                    colors: {
                        primary: '#aacddc',
                        secondary: '#6FA8BF',
                        hover: '#5e8d9fff',
                        tertiary: '#EFBE9D',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#fcfcfd] text-gray-800 font-sans antialiased selection:bg-primary selection:text-white flex flex-col min-h-screen">

    <!-- Top Action Bar -->
    <div class="w-full flex justify-end gap-3 p-6">
        <a href="./ketentuan_siswa.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-secondary text-white hover:bg-hover font-bold transition-colors shadow-sm">
            <i class="bi bi-journal-text"></i> Ketentuan
        </a>
        <a href="./logout.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-50 text-red-400 hover:bg-red-100 font-bold transition-colors shadow-sm">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>

    <!-- Main Content -->
    <main class="flex-grow pt-12 pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Profil Siswa Card -->
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-secondary to-primary px-8 py-6 relative overflow-hidden">
                    <h1 class="font-serif text-2xl font-bold text-white relative z-10">Data Siswa</h1>
                </div>
                
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <!-- Profile Image -->
                        <div class="w-full md:w-1/3 flex justify-center md:justify-start">
                            <div class="relative w-48 h-48 sm:w-64 sm:h-64 rounded-3xl overflow-hidden shadow-lg border-4 border-white bg-gray-50">
                                <?php if (!$siswa["foto"]) : ?>
                                    <img src="./../img/LOGO SMKN 12.png" alt="<?= $siswa["nama_siswa"]; ?>" class="w-full h-full object-contain p-4">
                                <?php else : ?>
                                    <img src="./../foto_siswa/<?= $siswa["foto"]; ?>" alt="<?= $siswa["nama_siswa"]; ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Profile Data -->
                        <div class="w-full md:w-2/3">
                            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-6">
                                <dl class="grid grid-cols-1 gap-y-4 text-sm sm:text-base">
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="font-bold text-gray-500 col-span-1">Nama</dt>
                                        <dd class="font-semibold text-gray-900 col-span-2"><?= $siswa["nama_siswa"]; ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="font-bold text-gray-500 col-span-1">NIS</dt>
                                        <dd class="font-medium text-gray-900 col-span-2"><?= $siswa["nis"]; ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="font-bold text-gray-500 col-span-1">Kelas</dt>
                                        <dd class="font-medium text-gray-900 col-span-2"><?= $kelas["nama_kelas"]; ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="font-bold text-gray-500 col-span-1">Jurusan</dt>
                                        <dd class="font-medium text-gray-900 col-span-2"><?= $jurusan["nama_jurusan"]; ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="font-bold text-gray-500 col-span-1">Email</dt>
                                        <dd class="font-medium text-gray-900 col-span-2"><?= $siswa["email"]; ?></dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <dt class="font-bold text-gray-500 col-span-1">Poin</dt>
                                        <dd class="col-span-2">
                                            <?php if ($siswa["jmlh_poin"] >= 0) : ?>
                                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold bg-primary/10 text-black border border-primary/20">
                                                    <?= $siswa["jmlh_poin"]; ?>
                                                </span>
                                            <?php else : ?>
                                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-red-50 text-red-700 text-sm font-bold border border-red-200">
                                                    Drop Out
                                                </span>
                                            <?php endif; ?>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <!-- No Action Buttons -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prestasi Section -->
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-5 relative overflow-hidden flex items-center justify-between">
                    <h2 class="font-serif text-xl font-bold text-white relative z-10 flex items-center gap-3"><i class="bi bi-trophy-fill"></i> Prestasi</h2>
                </div>
                
                <div class="p-8">
                    <?php if ($prestasi_siswa) : ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($prestasi_siswa as $prestasi) : ?>
                                <div class="bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-md transition-shadow relative">
                                    <div class="absolute top-4 right-4" <?= $hide_siswa; ?>>
                                        <a href="hapus/hapus_prestasiSiswa.php?id_prestasi=<?= $prestasi["id_prestasi_siswa"]; ?>&id_siswa=<?= $id; ?>" onclick="return confirm('Hapus Prestasi?')" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                                            <i class="bi bi-x-lg text-sm"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">
                                            <?= date('d M Y', strtotime($prestasi["tgl_prestasi"])); ?>
                                        </span>
                                    </div>
                                    
                                    <?php $ket = mysqli_query($conn, "SELECT det_prestasi FROM ket_prestasi WHERE id_prestasi = " . $prestasi["id_prestasi"])->fetch_assoc(); ?>
                                    <h3 class="font-bold text-gray-900 mb-4"><?= $ket["det_prestasi"]; ?></h3>
                                    
                                    <a href="../dokumen/<?= $prestasi["bukti"]; ?>" target="_blank" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="bi bi-file-earmark-text"></i> Lihat Bukti Dokumen <i class="bi bi-box-arrow-up-right text-xs"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-300 mb-4">
                                <i class="bi bi-award text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Belum ada prestasi</h3>
                            <p class="text-gray-500 mt-1">Siswa ini belum memiliki catatan prestasi.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pelanggaran Section -->
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-rose-600 px-8 py-5 relative overflow-hidden flex items-center justify-between">
                    <h2 class="font-serif text-xl font-bold text-white relative z-10 flex items-center gap-3"><i class="bi bi-exclamation-triangle-fill"></i> Pelanggaran</h2>
                </div>
                
                <div class="p-8">
                    <?php if ($pelanggaran_siswa) : ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($pelanggaran_siswa as $plgr) : ?>
                                <div class="bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-md transition-shadow relative">
                                    <div class="absolute top-4 right-4" <?= $hide_siswa; ?>>
                                        <a href="hapus/hapus_plgrSiswa.php?id_plgr=<?= $plgr["id_pelanggaran_siswa"]; ?>&id_siswa=<?= $id; ?>&poin=<?= $plgr["poin_berkurang"]; ?>" onclick="return confirm('Hapus Pelanggaran?')" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                                            <i class="bi bi-x-lg text-sm"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mb-4 pe-10">
                                        <span class="text-xs font-bold text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full">
                                            <?= date('d M Y H:i', strtotime($plgr["waktu_pelanggaran"])); ?>
                                        </span>
                                        <span class="text-xs font-bold text-red-700 bg-red-50 px-2.5 py-1 rounded-full border border-red-100">
                                            -<?= $plgr["poin_berkurang"]; ?> Poin
                                        </span>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h4 class="text-sm font-bold text-gray-500 mb-2 uppercase tracking-wider">Detail Pelanggaran</h4>
                                        <ul class="space-y-1">
                                            <?php
                                            $id_plgr = $plgr["id_pelanggaran"];
                                            $pelanggaran = query("SELECT det_pelanggaran FROM ket_pelanggaran WHERE id_pelanggaran in ($id_plgr)");
                                            foreach ($pelanggaran as $det_plgr) :
                                            ?>
                                                <li class="text-gray-900 font-medium flex items-start gap-2">
                                                    <i class="bi bi-dash text-red-500"></i>
                                                    <?= $det_plgr["det_pelanggaran"]; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    
                                    <?php
                                    if (strlen($plgr["id_pelapor"]) === 5) {
                                        $pelapor = mysqli_query($conn, "SELECT nama_siswa FROM siswa WHERE nis =" . $plgr["id_pelapor"])->fetch_assoc()["nama_siswa"];
                                    } else {
                                        $pelapor = mysqli_query($conn, "SELECT nama_guru FROM guru_pembina WHERE nip =" . $plgr["id_pelapor"])->fetch_assoc()["nama_guru"];
                                    }
                                    ?>
                                    <div class="pt-4 border-t border-gray-100 flex items-center gap-2 text-sm text-gray-500" <?= $hide_siswa; ?>>
                                        <i class="bi bi-person-badge"></i>
                                        Dilaporkan oleh: <span class="font-semibold text-gray-700"><?= $pelapor; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if ($total_pelanggaran > 2) : ?>
                            <div class="mt-8 flex justify-center">
                                <a href="riwayat_pelanggaran.php" class="bg-red-50 hover:bg-red-100 text-red-600 font-medium px-6 py-2.5 rounded-xl transition-colors border border-red-100 flex items-center gap-2">
                                    Lihat Riwayat Pelanggaran <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-400 mb-4">
                                <i class="bi bi-shield-check text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Siswa Teladan</h3>
                            <p class="text-gray-500 mt-1">Siswa ini tidak memiliki catatan pelanggaran tata tertib.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-auto pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16 text-center md:text-left">
                <div class="md:col-span-5">
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-6">
                        <img src="../img/logosmk12.png" alt="Logo" class="w-12 h-12 object-contain">
                        <h5 class="font-serif font-bold text-xl text-gray-900">OSIS SMKN 12 JAKARTA</h5>
                    </div>
                    <p class="text-gray-500 leading-relaxed max-w-sm mx-auto md:mx-0">
                        Menjaga ketertiban dan kedisiplinan demi mewujudkan lingkungan belajar yang nyaman dan kondusif bagi seluruh siswa.
                    </p>
                </div>
                
                <div class="col-span-1 md:col-span-7 flex flex-row gap-4 justify-between text-left md:grid md:grid-cols-7 md:gap-12">
                    <div class="md:col-span-3 w-1/2 md:w-auto">
                        <h6 class="font-bold text-gray-900 mb-6 font-serif tracking-wide">Sosial Media</h6>
                        <ul class="space-y-4">
                            <li>
                                <a href="#" class="flex items-center justify-start gap-3 text-gray-500 hover:text-blue-600 transition-colors font-medium">
                                    <i class="bi bi-meta text-xl"></i> Meta
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center justify-start gap-3 text-gray-500 hover:text-pink-600 transition-colors font-medium">
                                    <i class="bi bi-instagram text-xl"></i> Instagram
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center justify-start gap-3 text-gray-500 hover:text-red-600 transition-colors font-medium">
                                    <i class="bi bi-youtube text-xl"></i> Youtube
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="md:col-span-4 w-1/2 md:w-auto">
                        <h6 class="font-bold text-gray-900 mb-6 font-serif tracking-wide">Tautan Cepat</h6>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-gray-500 hover:text-primary transition-colors font-medium">Tentang Kami</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-primary transition-colors font-medium">Pertanyaan Umum (FAQs)</a></li>
                            <li><a href="./ketentuan_siswa.php" class="text-gray-500 hover:text-primary transition-colors font-medium">Ketentuan Pelanggaran</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm font-medium">&copy; Copyright 2022, RPL A0204. All rights reserved.</p>
                <p class="text-gray-400 text-sm font-medium">updated 2026, RPL R2809.</p>
            </div>
        </div>
    </footer>

    <!-- Modal Ganti Password -->
    <div id="ganti_pw" class="hidden fixed inset-0 z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('ganti_pw')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    <div class="bg-white px-6 pb-6 pt-8">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <h3 class="text-xl font-serif font-bold text-gray-900" id="modal-title">Ganti Password</h3>
                            <button type="button" onclick="closeModal('ganti_pw')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        <div class="mt-4">
                            <form action="./ubah/ubah_password.php?id=<?= $id; ?>" method="post" id="form_ganti_pw" class="space-y-4">
                                <input type="hidden" name="username" value="<?= $username; ?>">
                                
                                <div>
                                    <label for="pw_lama" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Lama</label>
                                    <input type="password" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" id="pw_lama" placeholder="Masukkan password lama..." name="pw_lama" required autocomplete="off">
                                </div>
                                
                                <div>
                                    <label for="pw_baru" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                                    <input type="password" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" id="pw_baru" name="pw_baru" required placeholder="Masukkan password baru..." autocomplete="off">
                                </div>
                                
                                <div>
                                    <label for="con_pw_baru" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                                    <input type="password" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" id="con_pw_baru" name="con_pw_baru" required placeholder="Ulangi password baru..." autocomplete="off">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" form="form_ganti_pw" name="ganti" class="inline-flex w-full justify-center rounded-xl bg-primary px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-secondary sm:ml-3 sm:w-auto transition-colors">Simpan</button>
                        <button type="button" onclick="closeModal('ganti_pw')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ganti Foto -->
    <div id="ganti_foto" class="hidden fixed inset-0 z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('ganti_foto')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    <div class="bg-white px-6 pb-6 pt-8">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <h3 class="text-xl font-serif font-bold text-gray-900" id="modal-title">Ganti Foto Profil</h3>
                            <button type="button" onclick="closeModal('ganti_foto')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        <div class="mt-4">
                            <form action="./ubah/ubah_foto.php?id=<?= $id; ?>" method="post" enctype="multipart/form-data" id="form_ganti_foto" class="space-y-4">
                                <div>
                                    <label for="foto" class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Foto Baru</label>
                                    <input type="file" class="w-full rounded-xl border border-gray-200 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer text-sm text-gray-600 focus:outline-none transition-all" id="foto" name="foto" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" form="form_ganti_foto" name="ganti" class="inline-flex w-full justify-center rounded-xl bg-primary px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-secondary sm:ml-3 sm:w-auto transition-colors">Simpan Foto</button>
                        <button type="button" onclick="closeModal('ganti_foto')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Prestasi -->
    <div id="tambah_prestasi" class="hidden fixed inset-0 z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('tambah_prestasi')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    <div class="bg-white px-6 pb-6 pt-8">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <h3 class="text-xl font-serif font-bold text-gray-900" id="modal-title">Tambah Prestasi Siswa</h3>
                            <button type="button" onclick="closeModal('tambah_prestasi')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        <div class="mt-4">
                            <form action="" method="post" enctype="multipart/form-data" id="form_tambah_prestasi" class="space-y-4">
                                <div>
                                    <label for="prestasi" class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Prestasi</label>
                                    <select name="prestasi" id="prestasi" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all bg-white" required>
                                        <option value="">-- Pilih Prestasi --</option>
                                        <?php foreach ($ket_prestasi as $prestasi) : ?>
                                            <option value="<?= $prestasi["id_prestasi"]; ?>"><?= $prestasi["det_prestasi"]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="tgl_lomba" class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Prestasi</label>
                                    <input type="date" id="tgl_lomba" name="tgl_prestasi" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" required>
                                </div>
                                
                                <div>
                                    <label for="bukti" class="block text-sm font-semibold text-gray-700 mb-1.5">Bukti (Sertifikat/Piagam)</label>
                                    <input type="file" id="bukti" name="dok" class="w-full rounded-xl border border-gray-200 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer text-sm text-gray-600 focus:outline-none transition-all" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" form="form_tambah_prestasi" name="tambah_prestasi" class="inline-flex w-full justify-center rounded-xl bg-primary px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-secondary sm:ml-3 sm:w-auto transition-colors">Tambah Prestasi</button>
                        <button type="button" onclick="closeModal('tambah_prestasi')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dropdown functions
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            // close other dropdowns
            document.querySelectorAll('#userMenu, #mobileMenu').forEach(el => {
                if(el.id !== id && !el.classList.contains('hidden')) {
                    el.classList.add('hidden');
                }
            });
            dropdown.classList.toggle('hidden');
        }

        window.addEventListener('click', function(e) {
            // Close dropdowns if clicked outside
            if (!e.target.closest('.relative') && !e.target.closest('#userMenuContainer')) {
                document.querySelectorAll('#userMenu').forEach(el => {
                    if(!el.classList.contains('hidden')) el.classList.add('hidden');
                });
            }
        });

        // Modal functions
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            document.querySelectorAll('#userMenu, #mobileMenu').forEach(el => el.classList.add('hidden'));
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
</body>
</html>