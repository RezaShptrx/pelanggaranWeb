<?php
session_start();

if (!isset($_SESSION["login"])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_SESSION["guru"])) {
    $guru = "hidden";
} else {
    $guru = "";
}

if (isset($_SESSION["osis"])) {
    header("Location: '../ktnpelanggaran.php");
}

if (isset($_SESSION["siswa"])) {
    header("Location: ../data_siswa.php?id=" . $_SESSION["id_siswa"]);
}

require '../functions.php';

$id = $_GET["id"];

if (!$id) {
    return header("Location: ../ktnpelanggaran.php");
}

$ktnplgr = query("SELECT * FROM ket_pelanggaran WHERE id_pelanggaran = $id")[0];

if (isset($_POST["ubah"])) {
    if (ubah_plgr($_POST) > 0) {
        echo "
            <script>
                alert('Data berhasil diubah!')
                // redirect versi javascript
                document.location.href = '../ktnpelanggaran.php';
                </script>
                ";
    } else {
        echo "
                <script>
                alert('Data gagal diubah!')
                // redirect versi javascript
                document.location.href = '../ktnpelanggaran.php';
            </script>
            ";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Ketentuan Pelanggaran</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="icon" href="../../img/logosmk12.png">

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
</head>
<body class="bg-[#fcfcfd] text-gray-800 font-sans antialiased selection:bg-primary selection:text-white flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <!-- Logo -->
                <a href="../../index.php" class="flex items-center gap-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center p-2 shadow-inner group-hover:shadow-md transition-all">
                        <img src="../../img/logosmk12.png" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-serif font-bold text-2xl tracking-wide text-gray-900 group-hover:text-primary transition-colors">OSIS SMKN 12</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="../../index.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Beranda</a>
                    <a href="../siswa.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Siswa</a>
                    <a href="../guru.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1" <?= $guru; ?>>Guru</a>
                    <a href="../ktnpelanggaran.php" class="text-sm font-semibold text-primary border-b-2 border-primary py-2 px-1">Ketentuan</a>
                    
                    <!-- Dropdown -->
                    <div class="relative ml-4" id="userMenuContainer">
                        <button onclick="toggleDropdown('userMenu')" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors focus:ring-2 focus:ring-primary focus:outline-none">
                            <i class="bi bi-list text-xl text-gray-600"></i>
                        </button>
                        <div id="userMenu" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 py-2 z-50 transform origin-top-right transition-all">
                            <?php if (isset($_SESSION["login"])) : ?>
                                <a href="../logout.php" class="w-full flex items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors font-medium"><i class="bi bi-box-arrow-right"></i> Keluar</a>
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
            <a href="../../index.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Beranda</a>
            <a href="../siswa.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Siswa</a>
            <a href="../guru.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium" <?= $guru; ?>>Guru</a>
            <a href="../ktnpelanggaran.php" class="block px-4 py-3 rounded-xl bg-primary/10 text-primary font-semibold">Ketentuan</a>
            <?php if (isset($_SESSION["login"])) : ?>
                <div class="border-t border-gray-100 my-4 pt-2"></div>
                <a href="../logout.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 font-bold"><i class="bi bi-box-arrow-right"></i> Keluar</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-12 pb-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-secondary to-primary px-8 py-6 relative overflow-hidden">
                    <h1 class="font-serif text-2xl font-bold text-white relative z-10 flex items-center gap-3">
                        <i class="bi bi-pencil-square"></i> Ubah Ketentuan Pelanggaran
                    </h1>
                </div>
                
                <div class="p-8">
                    <form action="" method="post" class="space-y-6">
                        <input type="hidden" name="id" value="<?= $ktnplgr["id_pelanggaran"]; ?>">
                        
                        <!-- Jenis Pelanggaran -->
                        <div>
                            <label for="jenis_plgr" class="block text-sm font-bold text-gray-700 mb-2">Jenis Pelanggaran</label>
                            <input type="text" id="jenis_plgr" name="jenis_plgr" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" required autocomplete="off" autofocus value="<?= $ktnplgr["jenis_pelanggaran"]; ?>" placeholder="kedisiplinan, kerapian ...">
                        </div>
                        
                        <!-- Detail Pelanggaran -->
                        <div>
                            <label for="det_plgr" class="block text-sm font-bold text-gray-700 mb-2">Detail Pelanggaran</label>
                            <input type="text" id="det_plgr" name="det_plgr" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" required autocomplete="off" value="<?= $ktnplgr["det_pelanggaran"]; ?>" placeholder="datang terlambat ...">
                        </div>
                        
                        <!-- Poin Pelanggaran -->
                        <div>
                            <label for="poin" class="block text-sm font-bold text-gray-700 mb-2">Poin Pelanggaran</label>
                            <input type="number" id="poin" name="poin" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 border outline-none transition-all" required autocomplete="off" value="<?= $ktnplgr["poin_pelanggaran"]; ?>" placeholder="1 / 2 / ...">
                        </div>
                        
                        <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row gap-3 justify-end">
                            <a href="../ktnpelanggaran.php" class="inline-flex justify-center items-center px-6 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition-colors focus:ring-2 focus:ring-gray-200 focus:ring-offset-2">Batal</a>
                            <button type="submit" name="ubah" class="inline-flex justify-center items-center px-6 py-2.5 rounded-xl bg-primary text-gray-900 font-bold hover:bg-secondary transition-colors focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <i class="bi bi-check-lg mr-2"></i> Simpan Perubahan
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
                        <img src="../../img/logosmk12.png" alt="Logo" class="w-12 h-12 object-contain">
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
                        <li><a href="../ktnpelanggaran.php" class="text-gray-500 hover:text-primary transition-colors font-medium">Ketentuan Pelanggaran</a></li>
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
    </script>
</body>
</html>