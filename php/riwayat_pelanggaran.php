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

// Pagination
$jumlahDataPerHalaman = 6;
$jumlahData = count(query("SELECT * FROM pelanggaran_siswa WHERE id_pelanggar = $id"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET["halaman"])) ? (int)$_GET["halaman"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

$semua_pelanggaran_siswa = query("SELECT * FROM pelanggaran_siswa WHERE id_pelanggar = $id ORDER BY waktu_pelanggaran DESC LIMIT $awalData, $jumlahDataPerHalaman");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggaran | <?= $siswa["nama_siswa"]; ?></title>
    
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
        <a href="./halaman_siswa.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-50 text-gray-700 hover:bg-gray-100 font-bold transition-colors shadow-sm border border-gray-200">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Main Content -->
    <main class="flex-grow pt-12 pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Pelanggaran Section -->
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-rose-600 px-8 py-5 relative overflow-hidden flex items-center justify-between">
                    <h2 class="font-serif text-xl font-bold text-white relative z-10 flex items-center gap-3"><i class="bi bi-clock-history"></i> Riwayat Pelanggaran</h2>
                </div>
                
                <div class="p-8">
                    <?php if ($semua_pelanggaran_siswa) : ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($semua_pelanggaran_siswa as $plgr) : ?>
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

                        <!-- Pagination -->
                        <?php if ($jumlahHalaman > 1) : ?>
                        <div class="mt-8 flex justify-center gap-2">
                            <?php if ($halamanAktif > 1) : ?>
                                <a href="?halaman=<?= $halamanAktif - 1; ?>" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">&laquo;</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                                <?php if ($i == $halamanAktif) : ?>
                                    <a href="?halaman=<?= $i; ?>" class="px-4 py-2 bg-primary border border-primary rounded-lg text-white font-bold"><?= $i; ?></a>
                                <?php else : ?>
                                    <a href="?halaman=<?= $i; ?>" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors"><?= $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($halamanAktif < $jumlahHalaman) : ?>
                                <a href="?halaman=<?= $halamanAktif + 1; ?>" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">&raquo;</a>
                            <?php endif; ?>
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
</body>
</html>