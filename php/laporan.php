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
    $osis = "hidden";
} else {
    $osis = "";
}

if (isset($_SESSION["siswa"])) {
    header("Location: ./php/data_siswa.php?id=" . $_SESSION["id_siswa"]);
}

include('./functions.php');

$pelanggaran_siswa = query("SELECT * FROM pelanggaran_siswa");
if (empty($pelanggaran_siswa)) {
    $kosong = true;
}

if (isset($_POST["tgl_plgr"])) {
    $tgl_plgr = $_POST["tanggal"];
    $plgr_siswa = query("SELECT * FROM pelanggaran_siswa WHERE waktu_pelanggaran = '$tgl_plgr'");
} else {
    $tgl_plgr = date("Y-m-d");
    $plgr_siswa = query("SELECT * FROM pelanggaran_siswa WHERE waktu_pelanggaran = '$tgl_plgr'");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggaran | OSIS SMKN 12 JAKARTA</title>
    
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
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
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
                <a href="../index.php" class="flex items-center gap-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center p-2 shadow-inner group-hover:shadow-md transition-all">
                        <img src="./../img/logosmk12.png" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-serif font-bold text-2xl tracking-wide text-gray-900 group-hover:text-primary transition-colors">OSIS SMKN 12</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="../index.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Beranda</a>
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
            <a href="../index.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Beranda</a>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-10 border-b border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary/10 text-primary mb-4">
                                <i class="bi bi-journal-text text-xl"></i>
                            </div>
                            <h2 class="font-serif text-3xl font-bold text-gray-900 mb-2">Laporan Pelanggaran</h2>
                            <p class="text-gray-500">Daftar riwayat pelanggaran tata tertib siswa.</p>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Search Form -->
                            <form action="" method="post" class="flex flex-col sm:flex-row items-end sm:items-center gap-3 bg-white p-2 rounded-2xl border border-gray-200 shadow-sm">
                                <div class="flex items-center w-full">
                                    <label for="tanggal" class="text-sm font-semibold text-gray-600 pl-3 pr-2 hidden sm:block whitespace-nowrap">Tanggal:</label>
                                    <input type="date" id="tanggal" name="tanggal" value="<?= $tgl_plgr; ?>" class="w-full sm:w-auto px-3 py-2 text-sm border-none bg-transparent focus:ring-0 text-gray-700 font-medium cursor-pointer" required>
                                </div>
                                <button type="submit" name="tgl_plgr" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-gray-900 font-bold hover:bg-secondary transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </form>
                            
                            <?php if (!isset($kosong) && !empty($plgr_siswa)) : ?>
                            <form action="export_pdf.php" method="post" target="_blank" class="flex">
                                <input type="hidden" name="tanggal" value="<?= $tgl_plgr; ?>">
                                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 whitespace-nowrap">
                                    <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Table Body -->
                <div class="p-8">
                    <div id="laporan">
                        <?php if (isset($kosong) || empty($plgr_siswa)) : ?>
                            <div class="text-center py-16 px-4 border border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white shadow-sm border border-gray-100 text-gray-300 mb-4">
                                    <i class="bi bi-inbox text-4xl"></i>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Data</h4>
                                <p class="text-gray-500">Tidak ada pelanggaran yang tercatat pada tanggal tersebut.</p>
                            </div>
                        <?php else : ?>
                            <div class="overflow-x-auto custom-scrollbar border border-gray-100 rounded-2xl">
                                <table class="w-full text-left border-collapse whitespace-nowrap">
                                    <thead>
                                        <tr class="bg-gray-50/80 text-gray-600 text-sm border-b border-gray-100">
                                            <th class="py-4 px-5 font-bold tracking-wider uppercase text-center w-16">No</th>
                                            <th class="py-4 px-5 font-bold tracking-wider uppercase">Tanggal</th>
                                            <th class="py-4 px-5 font-bold tracking-wider uppercase">Nama Pelanggar</th>
                                            <th class="py-4 px-5 font-bold tracking-wider uppercase min-w-[300px]">Pelanggaran</th>
                                            <th class="py-4 px-5 font-bold tracking-wider uppercase text-center">Poin</th>
                                            <th class="py-4 px-5 font-bold tracking-wider uppercase">Petugas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        <?php $nomor = 1; ?>
                                        <?php foreach ($plgr_siswa as $plgr) : ?>
                                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                                <td class="py-4 px-5 text-sm text-gray-500 text-center font-medium"><?= $nomor++; ?></td>
                                                <td class="py-4 px-5 text-sm text-gray-600 font-medium"><?= date("d M Y", strtotime($plgr["waktu_pelanggaran"])); ?></td>
                                                <td class="py-4 px-5">
                                                    <?php $nama = mysqli_query($conn, "SELECT nama_siswa FROM siswa WHERE id_siswa = " . $plgr["id_pelanggar"])->fetch_assoc(); ?>
                                                    <span class="text-sm font-bold text-gray-900 group-hover:text-primary transition-colors"><?= $nama["nama_siswa"]; ?></span>
                                                </td>
                                                <td class="py-4 px-5 whitespace-normal">
                                                    <?php $id_pelanggaran = $plgr["id_pelanggaran"]; ?>
                                                    <?php $pelanggaran = query("SELECT det_pelanggaran FROM ket_pelanggaran WHERE id_pelanggaran in ($id_pelanggaran)"); ?>
                                                    <ul class="list-disc list-inside space-y-1">
                                                        <?php foreach ($pelanggaran as $data) : ?>
                                                            <li class="text-sm text-gray-600 leading-relaxed"><?= $data["det_pelanggaran"]; ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </td>
                                                <td class="py-4 px-5 text-center">
                                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold ring-1 ring-inset ring-red-600/10">
                                                        -<?= $plgr["poin_berkurang"]; ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-5 text-sm text-gray-500">
                                                    <?php $id_pelapor = $plgr["id_pelapor"]; ?>
                                                    <?php if (strlen($id_pelapor) === 5) : ?>
                                                        <?php $pelapor = mysqli_query($conn, "SELECT nama_siswa FROM siswa WHERE nis = $id_pelapor")->fetch_assoc(); ?>
                                                        <?= $pelapor["nama_siswa"]; ?>
                                                    <?php else : ?>
                                                        <?php $pelapor = mysqli_query($conn, "SELECT nama_guru FROM guru_pembina WHERE nip = $id_pelapor")->fetch_assoc(); ?>
                                                        <?= $pelapor["nama_guru"]; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
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

        });
    </script>
</body>
</html>