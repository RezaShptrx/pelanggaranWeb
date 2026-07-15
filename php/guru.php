<?php
session_start();

if (!isset($_SESSION["login"])) {
    header('Location: ./login.php');
    exit;
}

if (isset($_SESSION["guru"])) {
    header("Location: ./../index.php");
}

if (isset($_SESSION["osis"])) {
    header("Location: ./../index.php");
}

if (isset($_SESSION["siswa"])) {
    header("Location: ./data_siswa.php?id=" . $_SESSION["id_siswa"]);
}

require 'functions.php';
$guru_sekolah = query("SELECT id_guru, nip, nama_guru, email FROM guru_pembina");

if (isset($_POST["tambah"])) {
    if (tambah_guru($_POST) > 0) {
        echo "<script>
              alert('Data berhasil ditambahkan!');
              document.location.href = './guru.php';
              </script>";
    } else {
        echo "<script>
             alert('Data gagal ditambahkan!');
             document.location.href = './guru.php';
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
    <title>Guru | SMKN 12 JAKARTA</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="icon" href="../img/logosmk12.png">

    <!-- jQuery for AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/guru.js"></script>

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
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
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
                        <img src="../img/logosmk12.png" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-serif font-bold text-2xl tracking-wide text-gray-900 group-hover:text-primary transition-colors">OSIS SMKN 12</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="../index.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Beranda</a>
                    <a href="./siswa.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Siswa</a>
                    <a href="./guru.php" class="text-sm font-semibold text-primary border-b-2 border-primary py-2 px-1">Guru</a>
                    <a href="./ktnpelanggaran.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Ketentuan</a>
                    
                    <!-- Dropdown -->
                    <div class="relative ml-4" id="userMenuContainer">
                        <button onclick="toggleDropdown('userMenu')" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors focus:ring-2 focus:ring-primary focus:outline-none">
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
                    <button onclick="toggleDropdown('mobileMenu')" class="text-gray-500 hover:text-gray-900 p-2 focus:outline-none focus:ring-2 focus:ring-primary rounded-lg">
                        <i class="bi bi-list text-3xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu Panel -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 pt-4 pb-6 space-y-2 shadow-xl">
            <a href="../index.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Beranda</a>
            <a href="./siswa.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Siswa</a>
            <a href="./guru.php" class="block px-4 py-3 rounded-xl bg-primary/10 text-primary font-semibold">Guru</a>
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
                                <i class="bi bi-person-badge-fill text-xl"></i>
                            </div>
                            <h2 class="font-serif text-3xl font-bold text-gray-900 mb-2">Daftar Guru</h2>
                            <p class="text-gray-500">Kelola dan lihat data guru pembina.</p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                            <!-- Search Form -->
                            <form action="" method="post" class="flex flex-col sm:flex-row items-center gap-3 bg-white p-2 rounded-2xl border border-gray-200 shadow-sm flex-grow">
                                <div class="flex items-center w-full px-2">
                                    <i class="bi bi-search text-gray-400"></i>
                                    <input type="text" name="keyword" placeholder="Cari guru..." autocomplete="off" class="keyword w-full px-3 py-2 text-sm border-none bg-transparent focus:ring-0 text-gray-700 outline-none">
                                </div>
                            </form>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button onclick="openModal('upload_excel')" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-blue-600 text-white font-bold transition-all hover:bg-blue-700 focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                                    <i class="bi bi-file-earmark-excel-fill"></i> Upload Excel
                                </button>
                                <button onclick="openModal('tambah_guru')" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-primary text-gray-900 font-bold transition-all hover:bg-secondary hover:text-white focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                    <i class="bi bi-person-plus-fill"></i> Tambah Guru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Table Body -->
                <div class="p-8">
                    <div class="overflow-x-auto custom-scrollbar border border-gray-100 rounded-2xl" id="container_guru">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50/80 text-gray-600 text-sm border-b border-gray-100">
                                    <th class="py-4 px-5 font-bold tracking-wider uppercase text-center w-16">No</th>
                                    <th class="py-4 px-5 font-bold tracking-wider uppercase">Nama</th>
                                    <th class="py-4 px-5 font-bold tracking-wider uppercase">NIP / NUPTK</th>
                                    <th class="py-4 px-5 font-bold tracking-wider uppercase text-center w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <?php
                                $batas = 10;
                                $halaman = isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                                $halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;
                                $previous = $halaman - 1;
                                $next = $halaman + 1;

                                $jumlah_data = count($guru_sekolah);
                                $total_halaman = ceil($jumlah_data / $batas);

                                $data_guru = query("SELECT id_guru, nip, nama_guru, email FROM guru_pembina LIMIT $halaman_awal, $batas");
                                $nomor = $halaman_awal + 1;
                                ?>
                                <?php foreach ($data_guru as $guru) : ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="py-4 px-5 text-sm text-gray-500 text-center font-medium"><?= $nomor++; ?></td>
                                        <td class="py-4 px-5">
                                            <a href="data_guru.php?id=<?= $guru["id_guru"]; ?>" class="text-sm font-bold text-gray-900 group-hover:text-primary transition-colors"><?= $guru["nama_guru"]; ?></a>
                                        </td>
                                        <td class="py-4 px-5 text-sm text-gray-600 font-medium">
                                            <?= $guru["nip"]; ?>
                                        </td>
                                        <td class="py-4 px-5 text-center relative dropdown-container">
                                            <button onclick="toggleDropdown('actionMenu_<?= $guru["id_guru"]; ?>')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <div id="actionMenu_<?= $guru["id_guru"]; ?>" class="hidden absolute right-12 top-10 mt-1 w-32 bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.1)] border border-gray-100 py-1 z-50 text-left">
                                                <a href="./ubah/ubah_guru.php?id=<?= $guru["id_guru"]; ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">Ubah</a>
                                                <a href="./hapus/hapus_guru.php?id=<?= $guru["id_guru"]; ?>" onclick="return confirm('Hapus data?')" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if($total_halaman > 1): ?>
                    <div class="mt-8 flex justify-center">
                        <nav class="inline-flex rounded-xl shadow-sm -space-x-px" aria-label="Pagination">
                            <a <?php if ($halaman > 1) { echo "href='?halaman=$previous'"; } ?> class="relative inline-flex items-center px-4 py-2 rounded-l-xl border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 cursor-pointer">
                                <span class="sr-only">Previous</span>
                                <i class="bi bi-chevron-left"></i>
                            </a>
                            
                            <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                                <?php if($i == $halaman): ?>
                                    <a href="?halaman=<?= $i; ?>" class="relative inline-flex items-center px-4 py-2 border border-primary bg-primary text-sm font-bold text-white z-10"><?= $i; ?></a>
                                <?php else: ?>
                                    <a href="?halaman=<?= $i; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-200 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"><?= $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <a <?php if ($halaman < $total_halaman) { echo "href='?halaman=$next'"; } ?> class="relative inline-flex items-center px-4 py-2 rounded-r-xl border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 cursor-pointer">
                                <span class="sr-only">Next</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </nav>
                    </div>
                    <?php endif; ?>
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
                        <img src="../img/logosmk12.png" alt="Logo" class="w-12 h-12 object-contain">
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

    <!-- Modal Tambah Guru -->
    <div id="tambah_guru" class="hidden fixed inset-0 z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('tambah_guru')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    <div class="bg-white px-6 pb-6 pt-8">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <h3 class="text-xl font-serif font-bold text-gray-900" id="modal-title">Tambah Guru Baru</h3>
                            <button type="button" onclick="closeModal('tambah_guru')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        <div class="mt-4">
                            <form action="" method="post" id="form_tambah_guru" class="space-y-4">
                                <div>
                                    <label for="nip" class="block text-sm font-semibold text-gray-700 mb-1.5">NIP / NUPTK</label>
                                    <input type="number" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" id="nip" placeholder="18 digit / 16 digit" name="nip" required autocomplete="off">
                                </div>
                                
                                <div>
                                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                                    <input type="text" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" id="nama" name="nama" required placeholder="Nama lengkap..." autocomplete="off">
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                                    <input type="email" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" id="email" name="email" required placeholder="email@contoh.com" autocomplete="off">
                                </div>
                                
                                <div>
                                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-1.5">Role</label>
                                    <select name="role" id="role" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all cursor-pointer bg-white">
                                        <option value="Guru">Guru</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" form="form_tambah_guru" name="tambah" class="inline-flex w-full justify-center rounded-xl bg-primary px-6 py-3 text-sm font-bold text-gray-900 shadow-sm hover:bg-secondary hover:text-white sm:ml-3 sm:w-auto transition-colors">Tambah</button>
                        <button type="button" onclick="closeModal('tambah_guru')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Excel Guru -->
    <div id="upload_excel" class="hidden fixed inset-0 z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('upload_excel')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                    <div class="bg-white px-6 pb-6 pt-8">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <h3 class="text-xl font-serif font-bold text-gray-900">Upload Data Guru (Excel)</h3>
                            <button type="button" onclick="closeModal('upload_excel')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        <div class="mt-4">
                            <div class="mb-4 p-4 rounded-xl bg-blue-50 border border-blue-100 text-sm text-blue-800 leading-relaxed">
                                <div class="flex justify-between items-start mb-2">
                                    <strong>Format Kolom Excel (.xlsx):</strong>
                                    <a href="download_template.php?type=guru" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                        <i class="bi bi-download"></i> Download Template
                                    </a>
                                </div>
                                Baris pertama adalah header/judul kolom (akan otomatis diabaikan sistem).<br><br>
                                <ul class="list-disc list-inside">
                                    <li><strong>Kolom 1:</strong> NIP / NUPTK (Angka/Teks)</li>
                                    <li><strong>Kolom 2:</strong> Nama Guru</li>
                                    <li><strong>Kolom 3:</strong> Email</li>
                                    <li><strong>Kolom 4:</strong> Role (guru / admin)</li>
                                </ul>
                                <p class="mt-2 text-xs italic">*Password default akan otomatis sama dengan NIP.</p>
                            </div>
                            <form action="./import_guru.php" method="post" enctype="multipart/form-data" id="form_upload_excel" class="space-y-4">
                                <div>
                                    <label for="file_excel" class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih File (.xlsx)</label>
                                    <input type="file" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer border border-gray-200 rounded-xl transition-colors" id="file_excel" name="file_excel" accept=".xlsx" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" form="form_upload_excel" name="upload" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto transition-colors">Upload & Import</button>
                        <button type="button" onclick="closeModal('upload_excel')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
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
            document.querySelectorAll('[id^="actionMenu_"], #userMenu, #mobileMenu').forEach(el => {
                if(el.id !== id && !el.classList.contains('hidden')) {
                    el.classList.add('hidden');
                }
            });
            dropdown.classList.toggle('hidden');
        }

        window.addEventListener('click', function(e) {
            // Close dropdowns if clicked outside
            if (!e.target.closest('.relative') && !e.target.closest('.dropdown-container') && !e.target.closest('#userMenuContainer')) {
                document.querySelectorAll('[id^="actionMenu_"], #userMenu').forEach(el => {
                    if(!el.classList.contains('hidden')) el.classList.add('hidden');
                });
            }
        });

        // Modal functions
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            document.querySelectorAll('[id^="actionMenu_"], #userMenu, #mobileMenu').forEach(el => el.classList.add('hidden'));
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
</body>
</html>