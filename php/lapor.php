<?php
session_start();

if (!isset($_SESSION["login"])) {
    header('Location: ./login.php');
    exit;
}

if (isset($_SESSION["admsis"])) {
    $admin = "hidden";
    $pelapor =  $_SESSION["nis"];
} else {
    $admin = "";
}

if (isset($_SESSION["admgr"])) {
    $admin = "hidden";
    $pelapor =  $_SESSION["nip"];
} else {
    $admin = "";
}

if (isset($_SESSION["guru"])) {
    $guru = "hidden";
    $pelapor = $_SESSION["nip"];
} else {
    $guru = "";
}

if (isset($_SESSION["osis"])) {
    $osis = "hidden";
    $pelapor = $_SESSION["nis"];
} else {
    $osis = "";
}

if (isset($_SESSION["siswa"])) {
    header("Location: ./data_siswa.php?id=" . $_SESSION["id_siswa"]);
}


require 'functions.php';

$kelas_sekolah = query("SELECT * FROM kelas");
$ket_pelanggaran_keterlambatan = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='keterlambatan'");
$ket_pelanggaran_pakaian = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='pakaian'");
$ket_pelanggaran_upacara = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='upacara'");
$ket_pelanggaran_media_elektronik = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='media elektronik'");
$ket_pelanggaran_aksesoris = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='aksesoris'");
$ket_pelanggaran_kehadiran = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='kehadiran'");
$ket_pelanggaran_lingkungan_sekolah = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='lingkungan sekolah'");
$ket_pelanggaran_mencuri = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='mencuri'");
$ket_pelanggaran_merokok = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='merokok'");
$ket_pelanggaran_pornografi = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='pornografi'");
$ket_pelanggaran_senjata_tajam = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='senjata tajam'");
$ket_pelanggaran_perkelahian = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='perkelahian / tawuran'");
$ket_pelanggaran_narkoba = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='narkoba / miras'");
$ket_pelanggaran_kepribadian = query("SELECT * FROM ket_pelanggaran WHERE jenis_pelanggaran='kepribadian'");

if (isset($_POST["submit"])) {
    if (!empty($_POST["kelas"]) && !empty($_POST["jurusan"]) && !in_array("0", $_POST["pelanggaran"]) && $_POST["nama"] !== "0") {
        if (lapor($_POST, $pelapor) > 0) {
            echo "<script>
                        alert('Berhasil membuat laporan!');
                        document.location.href = '../index.php';
                        </script>";
        } else {
            echo "<script>
                        alert('Gagal membuat laporan!');
                        document.location.href = '../index.php';
                        </script>";
            // echo mysqli_error($conn);
        }
    } else {
        echo "<script>
              alert('Semua input wajib diisi!');
              document.location.href = './lapor.php';
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
    <title>Lapor | OSIS SMKN 12 JAKARTA</title>
    
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
                        tertiary: '#EFBE9D',
                    }
                }
            }
        }
    </script>
    <!-- jQuery for AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        /* Custom Scrollbar for listPelanggaran */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
</head>
<body class="bg-[#fcfcfd] text-gray-800 font-sans antialiased selection:bg-primary selection:text-white flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <!-- Logo -->
                <a href="./../index.php" class="flex items-center gap-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center p-2 shadow-inner group-hover:shadow-md transition-all">
                        <img src="./../img/logosmk12.png" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-serif font-bold text-2xl tracking-wide text-gray-900 group-hover:text-primary transition-colors">OSIS SMKN 12</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="./../index.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Beranda</a>
                    <a href="./siswa.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Siswa</a>
                    <a href="./guru.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1" <?= $guru; ?> <?= $osis; ?>>Guru</a>
                    <a href="./ktnpelanggaran.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Ketentuan</a>
                    
                    <!-- Dropdown -->
                    <div class="relative ml-4" id="userMenuContainer">
                        <button onclick="toggleDropdown()" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors focus:ring-2 focus:ring-primary focus:outline-none">
                            <i class="bi bi-list text-xl text-gray-600"></i>
                        </button>
                        <div id="userMenu" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 py-2 z-50 transform origin-top-right transition-all">
                            <?php if (isset($_SESSION["login"])) : ?>
                                <a href="./logout.php" class="w-full flex items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors font-medium"><i class="bi bi-box-arrow-right"></i> Keluar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button onclick="toggleMobileMenu()" class="text-gray-500 hover:text-gray-900 p-2 focus:outline-none focus:ring-2 focus:ring-primary rounded-lg">
                        <i class="bi bi-list text-3xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu Panel -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 pt-4 pb-6 space-y-2 shadow-xl">
            <a href="./../index.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Beranda</a>
            <a href="./siswa.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Siswa</a>
            <a href="./guru.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium" <?= $guru; ?> <?= $osis; ?>>Guru</a>
            <a href="./ktnpelanggaran.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Ketentuan</a>
            <?php if (isset($_SESSION["login"])) : ?>
                <div class="border-t border-gray-100 my-4 pt-2"></div>
                <a href="./logout.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 font-bold"><i class="bi bi-box-arrow-right"></i> Keluar</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-12 pb-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-10 border-b border-gray-100 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary mb-5">
                        <i class="bi bi-exclamation-triangle text-2xl"></i>
                    </div>
                    <h2 class="font-serif text-3xl font-bold text-gray-900 mb-2">Laporkan Pelanggaran</h2>
                    <p class="text-gray-500">Formulir pelaporan tindak pelanggaran tata tertib siswa.</p>
                </div>
                
                <!-- Form Body -->
                <div class="p-8 sm:p-12">
                    <form method="POST" id="formLapor">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            
                            <!-- Left Column: Siswa Details -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                                        <i class="bi bi-person-badge text-primary"></i> Data Siswa
                                    </h3>
                                </div>
                                
                                <div>
                                    <label for="kelas" class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                                    <select name="kelas" id="kelas" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-3 border outline-none transition-all cursor-pointer bg-white hover:border-gray-300" required>
                                        <option value="">Pilih kelas</option>
                                        <?php foreach ($kelas_sekolah as $kelas) : ?>
                                            <option value="<?= $kelas["id_kelas"]; ?>"><?= $kelas["nama_kelas"]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="jurusan" class="block text-sm font-semibold text-gray-700 mb-2">Jurusan</label>
                                    <select name="jurusan" id="jurusan" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-3 border outline-none transition-all cursor-pointer bg-gray-50 disabled:opacity-60 disabled:cursor-not-allowed hover:border-gray-300" disabled required>
                                        <option value="">Pilih jurusan</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Siswa</label>
                                    <select name="nama" id="nama" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-3 border outline-none transition-all cursor-pointer bg-gray-50 disabled:opacity-60 disabled:cursor-not-allowed hover:border-gray-300" required disabled>
                                        <option value="0">Pilih nama siswa</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Right Column: Pelanggaran Details -->
                            <div class="space-y-6">
                                <div class="flex items-center justify-between mb-5 border-b border-gray-100 pb-3">
                                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                                        <i class="bi bi-card-checklist text-primary"></i> Pelanggaran
                                    </h3>
                                    <button type="button" onclick="tambahPelanggaran()" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" title="Tambah Pelanggaran">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                
                                <div id="listPelanggaran" class="space-y-4 max-h-[250px] overflow-y-auto pr-3 custom-scrollbar">
                                    <div id="plgr1" class="relative group bg-gray-50 rounded-xl p-4 border border-gray-100">
                                        <label for="pelanggaran" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pelanggaran 1</label>
                                        <select name="pelanggaran[]" id="pelanggaran" class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-3 py-2.5 border outline-none transition-all cursor-pointer bg-white" required>
                                            <option value="0">Pilih pelanggaran</option>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_keterlambatan[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_keterlambatan as $keterlambatan) : ?>
                                                    <option value="<?= $keterlambatan["id_pelanggaran"]; ?>"><?= $keterlambatan["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_pakaian[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_pakaian as $pakaian) : ?>
                                                    <option value="<?= $pakaian["id_pelanggaran"]; ?>"><?= $pakaian["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_upacara[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_upacara as $upacara) : ?>
                                                    <option value="<?= $upacara["id_pelanggaran"]; ?>"><?= $upacara["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_media_elektronik[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_media_elektronik as $media_elektronik) : ?>
                                                    <option value="<?= $media_elektronik["id_pelanggaran"]; ?>"><?= $media_elektronik["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_aksesoris[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_aksesoris as $aksesoris) : ?>
                                                    <option value="<?= $aksesoris["id_pelanggaran"]; ?>"><?= $aksesoris["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_kehadiran[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_kehadiran as $kehadiran) : ?>
                                                    <option value="<?= $kehadiran["id_pelanggaran"]; ?>"><?= $kehadiran["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_lingkungan_sekolah[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_lingkungan_sekolah as $lingkungan_sekolah) : ?>
                                                    <option value="<?= $lingkungan_sekolah["id_pelanggaran"]; ?>"><?= $lingkungan_sekolah["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_mencuri[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_mencuri as $mencuri) : ?>
                                                    <option value="<?= $mencuri["id_pelanggaran"]; ?>"><?= $mencuri["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_merokok[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_merokok as $merokok) : ?>
                                                    <option value="<?= $merokok["id_pelanggaran"]; ?>"><?= $merokok["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_pornografi[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_pornografi as $pornografi) : ?>
                                                    <option value="<?= $pornografi["id_pelanggaran"]; ?>"><?= $pornografi["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_senjata_tajam[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_senjata_tajam as $senjata_tajam) : ?>
                                                    <option value="<?= $senjata_tajam["id_pelanggaran"]; ?>"><?= $senjata_tajam["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_perkelahian[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_perkelahian as $perkelahian) : ?>
                                                    <option value="<?= $perkelahian["id_pelanggaran"]; ?>"><?= $perkelahian["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_narkoba[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_narkoba as $narkoba) : ?>
                                                    <option value="<?= $narkoba["id_pelanggaran"]; ?>"><?= $narkoba["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="<?= ucwords($ket_pelanggaran_kepribadian[0]["jenis_pelanggaran"]); ?>">
                                                <?php foreach ($ket_pelanggaran_kepribadian as $kepribadian) : ?>
                                                    <option value="<?= $kepribadian["id_pelanggaran"]; ?>"><?= $kepribadian["det_pelanggaran"]; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-8 border-t border-gray-100 flex justify-end">
                            <button type="submit" name="submit" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-primary text-gray-900 font-bold transition-all hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <i class="bi bi-send-fill"></i> Laporkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-auto pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
                <div class="md:col-span-5">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="./../img/logosmk12.png" alt="Logo" class="w-12 h-12 object-contain">
                        <h5 class="font-serif font-bold text-xl text-gray-900">OSIS SMKN 12 JAKARTA</h5>
                    </div>
                    <p class="text-gray-500 leading-relaxed max-w-sm">
                        Menjaga ketertiban dan kedisiplinan demi mewujudkan lingkungan belajar yang nyaman dan kondusif bagi seluruh siswa.
                    </p>
                </div>
                
                <div class="md:col-span-3">
                    <h6 class="font-bold text-gray-900 mb-6 font-serif tracking-wide">Sosial Media</h6>
                    <ul class="space-y-4">
                        <li>
                            <a href="#" class="flex items-center gap-3 text-gray-500 hover:text-blue-600 transition-colors font-medium">
                                <i class="bi bi-meta text-xl"></i> Meta
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 text-gray-500 hover:text-pink-600 transition-colors font-medium">
                                <i class="bi bi-instagram text-xl"></i> Instagram
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 text-gray-500 hover:text-red-600 transition-colors font-medium">
                                <i class="bi bi-youtube text-xl"></i> Youtube
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="md:col-span-4">
                    <h6 class="font-bold text-gray-900 mb-6 font-serif tracking-wide">Tautan Cepat</h6>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-gray-500 hover:text-primary transition-colors font-medium">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-primary transition-colors font-medium">Pertanyaan Umum (FAQs)</a></li>
                        <li><a href="./ktnpelanggaran.php" class="text-gray-500 hover:text-primary transition-colors font-medium">Ketentuan Pelanggaran</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm font-medium">&copy; Copyright 2022, RPL A0204. All rights reserved.</p>
                <p class="text-gray-400 text-sm font-medium">updated 2026, RPL R2809.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar functions
        function toggleDropdown() {
            document.getElementById('userMenu').classList.toggle('hidden');
        }
        
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        }

        window.addEventListener('click', function(e) {
            if (!document.getElementById('userMenuContainer')?.contains(e.target)) {
                const userMenu = document.getElementById('userMenu');
                if (userMenu && !userMenu.classList.contains('hidden')) {
                    userMenu.classList.add('hidden');
                }
            }
        });

        // Form functions
        $("#kelas").change(function() {
            const id_kelas = $("#kelas").val();
            if(id_kelas !== "") {
                $("#jurusan").removeAttr("disabled");
                $("#jurusan").removeClass("bg-gray-50 disabled:opacity-60 disabled:cursor-not-allowed").addClass("bg-white");
            } else {
                $("#jurusan").attr("disabled", "disabled");
                $("#jurusan").addClass("bg-gray-50 disabled:opacity-60 disabled:cursor-not-allowed").removeClass("bg-white");
                $("#nama").attr("disabled", "disabled");
                $("#nama").addClass("bg-gray-50 disabled:opacity-60 disabled:cursor-not-allowed").removeClass("bg-white");
            }

            $.ajax({
                type: "POST",
                dataType: "html",
                url: "./data_lapor.php",
                data: "kelas=" + id_kelas,
                success: function(data) {
                    $("#jurusan").html(data);
                    $("#nama").html('<option value="0">Pilih nama siswa</option>');
                }
            });
        });

        $("#jurusan").change(function() {
            const id_jurusan = $("#jurusan").val();
            if(id_jurusan !== "") {
                $("#nama").removeAttr("disabled");
                $("#nama").removeClass("bg-gray-50 disabled:opacity-60 disabled:cursor-not-allowed").addClass("bg-white");
            } else {
                $("#nama").attr("disabled", "disabled");
                $("#nama").addClass("bg-gray-50 disabled:opacity-60 disabled:cursor-not-allowed").removeClass("bg-white");
            }

            $.ajax({
                type: "POST",
                dataType: "html",
                url: "./data_lapor.php",
                data: "jurusan=" + id_jurusan,
                success: function(data) {
                    $("#nama").html(data);
                }
            });
        });

        let indexPelanggaran = 1;
        function tambahPelanggaran() {
            const plgr = document.getElementById("plgr" + indexPelanggaran);
            const nextIndex = indexPelanggaran + 1;
            const element = `<div id="plgr${nextIndex}" class="relative group bg-gray-50 rounded-xl p-4 border border-gray-100 mt-4">
                                <label for="pelanggaran" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pelanggaran ${nextIndex}</label>
                                <select name="pelanggaran[]" id="pelanggaran" class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-3 py-2.5 border outline-none transition-all cursor-pointer bg-white" required>
                                    <option value="0">Pilih pelanggaran</option>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_keterlambatan[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_keterlambatan as $keterlambatan) : ?>
                                        <option value="<?= $keterlambatan["id_pelanggaran"]; ?>"><?= $keterlambatan["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_pakaian[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_pakaian as $pakaian) : ?>
                                        <option value="<?= $pakaian["id_pelanggaran"]; ?>"><?= $pakaian["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_upacara[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_upacara as $upacara) : ?>
                                        <option value="<?= $upacara["id_pelanggaran"]; ?>"><?= $upacara["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_media_elektronik[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_media_elektronik as $media_elektronik) : ?>
                                        <option value="<?= $media_elektronik["id_pelanggaran"]; ?>"><?= $media_elektronik["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_aksesoris[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_aksesoris as $aksesoris) : ?>
                                        <option value="<?= $aksesoris["id_pelanggaran"]; ?>"><?= $aksesoris["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_kehadiran[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_kehadiran as $kehadiran) : ?>
                                        <option value="<?= $kehadiran["id_pelanggaran"]; ?>"><?= $kehadiran["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_lingkungan_sekolah[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_lingkungan_sekolah as $lingkungan_sekolah) : ?>
                                        <option value="<?= $lingkungan_sekolah["id_pelanggaran"]; ?>"><?= $lingkungan_sekolah["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_mencuri[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_mencuri as $mencuri) : ?>
                                        <option value="<?= $mencuri["id_pelanggaran"]; ?>"><?= $mencuri["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_merokok[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_merokok as $merokok) : ?>
                                        <option value="<?= $merokok["id_pelanggaran"]; ?>"><?= $merokok["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_pornografi[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_pornografi as $pornografi) : ?>
                                        <option value="<?= $pornografi["id_pelanggaran"]; ?>"><?= $pornografi["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_senjata_tajam[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_senjata_tajam as $senjata_tajam) : ?>
                                        <option value="<?= $senjata_tajam["id_pelanggaran"]; ?>"><?= $senjata_tajam["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_perkelahian[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_perkelahian as $perkelahian) : ?>
                                        <option value="<?= $perkelahian["id_pelanggaran"]; ?>"><?= $perkelahian["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_narkoba[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_narkoba as $narkoba) : ?>
                                        <option value="<?= $narkoba["id_pelanggaran"]; ?>"><?= $narkoba["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="<?= ucwords($ket_pelanggaran_kepribadian[0]["jenis_pelanggaran"]); ?>">
                                        <?php foreach ($ket_pelanggaran_kepribadian as $kepribadian) : ?>
                                        <option value="<?= $kepribadian["id_pelanggaran"]; ?>"><?= $kepribadian["det_pelanggaran"]; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>`;
            plgr.insertAdjacentHTML('afterend', element);
            indexPelanggaran++;
            
            // Scroll to bottom of the list
            const list = document.getElementById('listPelanggaran');
            list.scrollTop = list.scrollHeight;
        }
    </script>
</body>
</html>