<?php
session_start();

if (!isset($_SESSION["login"])) {
    header('Location: ./php/login.php');
    exit;
}

if (isset($_SESSION["guru"])) {
    $guru = "hidden";
    $username = $_SESSION["nip"];
    $id = $_SESSION["id"];
} else {
    $guru = "";
}

if (isset($_SESSION["osis"])) {
    $osis = "hidden";
    $username = $_SESSION["nis"];
    $id = $_SESSION["id_siswa"];
} else {
    $osis = "";
}

if (isset($_SESSION["siswa"])) {
    header("Location: ./php/data_siswa.php?id=" . $_SESSION["id_siswa"]);
    exit;
}

if (isset($_SESSION["admsis"])) {
    $admin = "hidden";
    $username = $_SESSION["nis"];
    $id = $_SESSION["id_siswa"];
} else {
    $admin = "";
}
if (isset($_SESSION["admgr"])) {
    $admin = "hidden";
    $username = $_SESSION["nip"];
    $id = $_SESSION["id"];
} else {
    $admin = "";
}

require "php/functions.php";

if (isset($_POST["ubah_carousel"])) {
    if (ubah_carousel($_FILES) > 0) {
        echo "<script>
                alert('Foto berhasil diubah')
              </script> ";
    } else {
        echo "<script>
                alert('Foto gagal diubah')
              </script> ";
    }
}

if (isset($_POST["ubah_fotoIndex"])) {
    if (ubah_fotoIndex($_FILES) > 0) {
        echo "<script>
                alert('Foto berhasil diubah')
              </script> ";
    } else {
        echo "<script>
                alert('Foto gagal diubah')
              </script> ";
    }
}

// Handler fallback untuk modal ketiga jika tetap digunakan (ganti_foto)
if (isset($_POST["ubah_foto"])) {
    if (isset($_FILES) && function_exists('ubah_foto')) {
        ubah_foto($_FILES);
    }
}

$carousel = mysqli_query($conn, "SELECT * FROM komponen WHERE nama_komponen = 'login_carousel'")->fetch_assoc();
$foto_index = mysqli_query($conn, "SELECT * FROM komponen WHERE nama_komponen = 'foto_index'")->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS SMKN 12 JAKARTA</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/umum.css">
    <link rel="icon" href="img/logosmk12.png">

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
<body class="bg-[#fcfcfd] text-gray-800 font-sans antialiased selection:bg-primary selection:text-white">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <!-- Logo -->
                <a href="index.php" class="flex items-center gap-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center p-2 shadow-inner group-hover:shadow-md transition-all">
                        <img src="./img/logosmk12.png" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-serif font-bold text-2xl tracking-wide text-gray-900 group-hover:text-primary transition-colors">OSIS SMKN 12</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="index.php" class="text-sm font-semibold text-primary border-b-2 border-primary py-2 px-1">Beranda</a>
                    <a href="./php/siswa.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Siswa</a>
                    <a href="./php/guru.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1" <?= $guru; ?> <?= $osis; ?>>Guru</a>
                    <a href="./php/ktnpelanggaran.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors py-2 px-1">Ketentuan</a>
                    
                    <!-- Dropdown -->
                    <div class="relative ml-4" id="userMenuContainer">
                        <button onclick="toggleDropdown()" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors focus:ring-2 focus:ring-primary focus:outline-none">
                            <i class="bi bi-list text-xl text-gray-600"></i>
                        </button>
                        <div id="userMenu" class="hidden absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 py-2 z-50 transform origin-top-right transition-all">
                            <?php if (isset($_SESSION["login"])) : ?>
                                <button onclick="openModal('ganti_pw')" class="w-full flex items-center gap-3 text-left px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors"><i class="bi bi-key text-gray-400"></i> Ganti Password</button>
                                <button onclick="openModal('ganti_carousel')" class="w-full flex items-center gap-3 text-left px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors" <?= $guru; ?> <?= $osis; ?>><i class="bi bi-images text-gray-400"></i> Ganti Gambar Login</button>
                                <button onclick="openModal('ganti_foto_index')" class="w-full flex items-center gap-3 text-left px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors" <?= $guru; ?> <?= $osis; ?>><i class="bi bi-card-image text-gray-400"></i> Ganti Gambar Beranda</button>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="./php/logout.php" class="w-full flex items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors font-medium"><i class="bi bi-box-arrow-right"></i> Keluar</a>
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
            <a href="index.php" class="block px-4 py-3 rounded-xl bg-primary/10 text-primary font-semibold">Beranda</a>
            <a href="./php/siswa.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Siswa</a>
            <a href="./php/guru.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium" <?= $guru; ?> <?= $osis; ?>>Guru</a>
            <a href="./php/ktnpelanggaran.php" class="block px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Ketentuan</a>
            <?php if (isset($_SESSION["login"])) : ?>
                <div class="border-t border-gray-100 my-4 pt-2"></div>
                <button onclick="openModal('ganti_pw')" class="w-full flex items-center gap-3 text-left px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium"><i class="bi bi-key text-gray-400"></i> Ganti Password</button>
                <button onclick="openModal('ganti_carousel')" class="w-full flex items-center gap-3 text-left px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium" <?= $guru; ?> <?= $osis; ?>><i class="bi bi-images text-gray-400"></i> Ganti Gambar Login</button>
                <button onclick="openModal('ganti_foto_index')" class="w-full flex items-center gap-3 text-left px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium" <?= $guru; ?> <?= $osis; ?>><i class="bi bi-card-image text-gray-400"></i> Ganti Gambar Beranda</button>
                <a href="./php/logout.php" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 font-bold"><i class="bi bi-box-arrow-right"></i> Keluar</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-24 pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-12 gap-16 items-center">
                
                <!-- Text Content -->
                <div class="lg:col-span-5 z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-bold tracking-wide mb-8">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary"></span>
                        </span>
                        Sistem Informasi Kedisiplinan
                    </div>
                    
                    <h1 class="font-serif text-5xl lg:text-[4rem] text-gray-900 leading-[1.1] tracking-tight mb-8">
                        Budayakan <br>
                        <span class="text-primary italic">Tertib & Disiplin</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 mb-10 leading-relaxed max-w-lg">
                        Betapa indahnya sekolah yang tertib dan disiplin. Mari wujudkan lingkungan yang kondusif, harmonis, dan humanis di <strong class="text-gray-900">SMKN 12 Jakarta</strong>.
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="./php/lapor.php" class="px-8 py-4 rounded-xl bg-primary text-gray-900 font-bold transition-all hover:-translate-y-1 hover:bg-secondary hover:text-white shadow-sm hover:shadow-md">
                            Buat Laporan
                        </a>
                        <a href="./php/laporan.php" class="px-8 py-4 rounded-xl bg-white text-gray-700 font-bold border border-gray-200 hover:border-gray-300 hover:bg-gray-50 shadow-sm transition-all hover:-translate-y-1">
                            Lihat Laporan
                        </a>
                    </div>
                </div>

                <!-- Carousel / Images -->
                <div class="lg:col-span-6 lg:col-start-7 relative">
                    <!-- Decorative background blur -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent blur-3xl -z-10 rounded-[3rem] transform rotate-3"></div>
                    
                    <div class="relative rounded-[2rem] overflow-hidden shadow-2xl bg-gray-100 border border-gray-100/50 aspect-[4/3] max-w-[90%] ml-auto group">
                        <div id="imageCarousel" class="relative w-full h-full">
                            <?php 
                            $foto = explode(',', $foto_index["isi_komponen"]); 
                            $hasPhotos = false;
                            foreach($foto as $index => $img) {
                                if (!empty(trim($img))) {
                                    $hasPhotos = true;
                                    $activeClass = $index === 0 ? "opacity-100 z-10 scale-100" : "opacity-0 z-0 scale-105";
                                    echo '<img src="img/' . trim($img) . '" class="carousel-slide absolute inset-0 w-full h-full object-cover transition-all duration-1000 ease-[cubic-bezier(0.4,0,0.2,1)] ' . $activeClass . '">';
                                }
                            }
                            if (!$hasPhotos) {
                                echo '<div class="absolute inset-0 flex items-center justify-center text-gray-400 font-medium">Belum ada foto</div>';
                            }
                            ?>
                        </div>
                        
                        <!-- Overlay gradient for text accessibility -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-20 pointer-events-none"></div>
                    </div>
                    
                    <!-- Decorative elements -->
                    <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-[radial-gradient(#d1d5db_1.5px,transparent_1.5px)] [background-size:20px_20px] -z-10 opacity-70"></div>
                    <div class="absolute -top-8 -right-8 w-32 h-32 bg-[radial-gradient(#d1d5db_1.5px,transparent_1.5px)] [background-size:20px_20px] -z-10 opacity-70"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-20 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
                <div class="md:col-span-5">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="./img/logosmk12.png" alt="Logo" class="w-12 h-12 object-contain">
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
                        <li><a href="./php/ktnpelanggaran.php" class="text-gray-500 hover:text-primary transition-colors font-medium">Ketentuan Pelanggaran</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm font-medium">&copy; Copyright 2022, RPL A0204. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Modals -->

    <!-- Modal Ganti Password -->
    <div id="ganti_pw" class="hidden fixed inset-0 z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('ganti_pw')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    <div class="bg-white px-6 pb-6 pt-8">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 sm:mx-0">
                                <i class="bi bi-key text-primary text-2xl"></i>
                            </div>
                            <div class="mt-4 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-2xl font-serif font-bold text-gray-900" id="modal-title">Ganti Password</h3>
                                <p class="text-sm text-gray-500 mt-1">Pastikan password baru Anda aman dan mudah diingat.</p>
                                <div class="mt-6">
                                    <form action="php/ubah/ubah_password.php?id=<?= $id; ?>" method="post" id="form_ganti_pw">
                                        <input type="hidden" name="username" value="<?= $username; ?>">
                                        <div class="space-y-4">
                                            <div>
                                                <label for="pw_lama" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Lama</label>
                                                <input type="password" id="pw_lama" name="pw_lama" required autocomplete="off" autofocus class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-3 border outline-none transition-all" placeholder="Masukkan password lama...">
                                            </div>
                                            <div>
                                                <label for="pw_baru" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                                                <input type="password" id="pw_baru" name="pw_baru" required autocomplete="off" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-3 border outline-none transition-all" placeholder="Minimal 8 karakter...">
                                            </div>
                                            <div>
                                                <label for="con_pw_baru" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                                                <input type="password" id="con_pw_baru" name="con_pw_baru" required autocomplete="off" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-3 border outline-none transition-all" placeholder="Ulangi password baru...">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" form="form_ganti_pw" name="ganti" class="inline-flex w-full justify-center rounded-xl bg-primary px-6 py-3 text-sm font-bold text-gray-900 shadow-sm hover:bg-secondary hover:text-white sm:ml-3 sm:w-auto transition-colors">Simpan Password</button>
                        <button type="button" onclick="closeModal('ganti_pw')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ganti Carousel Login -->
    <div id="ganti_carousel" class="hidden fixed inset-0 z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('ganti_carousel')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    <div class="bg-white px-6 pb-6 pt-8">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 sm:mx-0">
                                <i class="bi bi-images text-primary text-2xl"></i>
                            </div>
                            <div class="mt-4 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-2xl font-serif font-bold text-gray-900" id="modal-title">Ganti Gambar Login</h3>
                                <p class="text-sm text-gray-500 mt-1">Perbarui gambar slider di halaman login.</p>
                                <div class="mt-6">
                                    <form action="" method="post" enctype="multipart/form-data" id="form_ganti_carousel">
                                        <div class="mb-5">
                                            <label for="foto_carousel" class="block text-sm font-semibold text-gray-700 mb-2">Unggah Foto <span class="text-gray-400 font-normal">(maksimal 5)</span></label>
                                            <input type="file" id="foto_carousel" name="foto[]" multiple autocomplete="off" class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer border border-gray-200 rounded-xl transition-colors">
                                        </div>
                                        <div class="mt-5 border-t border-gray-100 pt-5">
                                            <p class="text-sm font-semibold text-gray-700 mb-3">Foto Saat Ini</p>
                                            <div class="flex gap-3 flex-wrap">
                                                <?php $foto_c = explode(',', $carousel["isi_komponen"]); ?>
                                                <?php foreach ($foto_c as $img) : ?>
                                                    <?php if(!empty(trim($img))): ?>
                                                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                                        <img src="img/<?= trim($img); ?>" alt="current" class="w-full h-full object-cover">
                                                    </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" form="form_ganti_carousel" name="ubah_carousel" class="inline-flex w-full justify-center rounded-xl bg-primary px-6 py-3 text-sm font-bold text-gray-900 shadow-sm hover:bg-secondary hover:text-white sm:ml-3 sm:w-auto transition-colors">Simpan Gambar</button>
                        <button type="button" onclick="closeModal('ganti_carousel')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ganti Foto Index -->
    <div id="ganti_foto_index" class="hidden fixed inset-0 z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('ganti_foto_index')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    <div class="bg-white px-6 pb-6 pt-8">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 sm:mx-0">
                                <i class="bi bi-card-image text-primary text-2xl"></i>
                            </div>
                            <div class="mt-4 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-2xl font-serif font-bold text-gray-900" id="modal-title">Ganti Gambar Beranda</h3>
                                <p class="text-sm text-gray-500 mt-1">Perbarui gambar slider di halaman utama.</p>
                                <div class="mt-6">
                                    <form action="" method="post" enctype="multipart/form-data" id="form_ganti_foto_index">
                                        <div class="mb-5">
                                            <label for="foto_index_input" class="block text-sm font-semibold text-gray-700 mb-2">Unggah Foto <span class="text-gray-400 font-normal">(maksimal 5)</span></label>
                                            <input type="file" id="foto_index_input" name="foto[]" multiple autocomplete="off" class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer border border-gray-200 rounded-xl transition-colors">
                                        </div>
                                        <div class="mt-5 border-t border-gray-100 pt-5">
                                            <p class="text-sm font-semibold text-gray-700 mb-3">Foto Saat Ini</p>
                                            <div class="flex gap-3 flex-wrap">
                                                <?php $foto_i = explode(',', $foto_index["isi_komponen"]); ?>
                                                <?php foreach ($foto_i as $img) : ?>
                                                    <?php if(!empty(trim($img))): ?>
                                                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                                        <img src="img/<?= trim($img); ?>" alt="current" class="w-full h-full object-cover">
                                                    </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" form="form_ganti_foto_index" name="ubah_fotoIndex" class="inline-flex w-full justify-center rounded-xl bg-primary px-6 py-3 text-sm font-bold text-gray-900 shadow-sm hover:bg-secondary hover:text-white sm:ml-3 sm:w-auto transition-colors">Simpan Gambar</button>
                        <button type="button" onclick="closeModal('ganti_foto_index')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            // Tutup dropdown jika sedang terbuka
            document.getElementById('userMenu').classList.add('hidden');
            document.getElementById('mobileMenu').classList.add('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

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

        // Carousel Logic
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.carousel-slide');
            if (slides.length > 1) {
                let current = 0;
                setInterval(() => {
                    slides[current].classList.remove('opacity-100', 'z-10', 'scale-100');
                    slides[current].classList.add('opacity-0', 'z-0', 'scale-105');
                    
                    current = (current + 1) % slides.length;
                    
                    slides[current].classList.remove('opacity-0', 'z-0', 'scale-105');
                    slides[current].classList.add('opacity-100', 'z-10', 'scale-100');
                }, 5000);
            }
        });
    </script>
</body>
</html>