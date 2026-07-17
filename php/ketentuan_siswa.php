<?php
session_start();

if (!isset($_SESSION["login"])) {
    header('Location: ./login.php');
    exit;
}

if (!isset($_SESSION["siswa"])) {
    header("Location: ./../index.php"); 
    exit;
}

$link = "./halaman_siswa.php";

include('./functions.php');

$ktnpelanggaran = query("SELECT * FROM ket_pelanggaran");
$ktnprestasi = query("SELECT * FROM ket_prestasi");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ketentuan | SMKN 12 JAKARTA</title>
    
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
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        /* Accordion transition */
        .accordion-content {
            transition: max-height 0.4s ease-in-out, opacity 0.4s ease-in-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
        .accordion-content.expanded {
            max-height: 15000px;
            opacity: 1;
        }
    </style>
</head>
<body class="bg-[#fcfcfd] text-gray-800 font-sans antialiased selection:bg-primary selection:text-white flex flex-col min-h-screen">

    <!-- Top Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <!-- Logo -->
                <a href="<?= $link; ?>" class="flex items-center gap-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center p-2 shadow-inner group-hover:shadow-md transition-all">
                        <img src="../img/logosmk12.png" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-serif font-bold text-2xl tracking-wide text-gray-900 group-hover:text-primary transition-colors">OSIS SMKN 12</span>
                </a>
                
                <!-- Action -->
                <div class="flex items-center">
                    <a href="<?= $link; ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-50 text-gray-700 hover:bg-gray-100 font-bold transition-colors shadow-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
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
                            <h2 class="font-serif text-3xl font-bold text-gray-900 mb-2">Ketentuan Sekolah</h2>
                            <p class="text-gray-500">Daftar ketentuan prestasi dan pelanggaran tata tertib sekolah.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Accordions -->
                <div class="p-8 space-y-6">
                    
                    <!-- Accordion Prestasi -->
                    <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white">
                        <button onclick="toggleAccordion('prestasi-content', 'prestasi-icon')" class="w-full flex items-center justify-between px-6 py-4 bg-gray-50 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary">
                            <span class="font-bold text-gray-900 font-serif">Ketentuan Prestasi</span>
                            <i id="prestasi-icon" class="bi bi-chevron-down text-gray-500 transition-transform duration-300 transform rotate-180"></i>
                        </button>
                        
                        <div id="prestasi-content" class="accordion-content expanded bg-white border-t border-gray-100">
                            <div class="p-4 sm:p-6 bg-gray-50/30">
                                <!-- Desktop Table -->
                                <div class="hidden md:block overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600">
                                                <th class="py-4 px-6 font-bold tracking-wider uppercase text-center w-20">No</th>
                                                <th class="py-4 px-6 font-bold tracking-wider uppercase">Prestasi</th>
                                                <th class="py-4 px-6 font-bold tracking-wider uppercase text-center w-32">Poin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            <?php $n = 1; ?>
                                            <?php foreach ($ktnprestasi as $prestasi) : ?>
                                                <tr class="hover:bg-gray-50/80 transition-colors">
                                                    <td class="py-4 px-6 text-sm text-gray-500 text-center font-medium"><?= $n++; ?></td>
                                                    <td class="py-4 px-6 text-sm text-gray-800 font-medium"><?= ucfirst($prestasi["det_prestasi"]); ?></td>
                                                    <td class="py-4 px-6 text-center">
                                                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold ring-1 ring-inset ring-blue-600/20">
                                                            +<?= $prestasi["poin_prestasi"]; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Mobile Cards -->
                                <div class="md:hidden space-y-3">
                                    <?php $n = 1; ?>
                                    <?php foreach ($ktnprestasi as $prestasi) : ?>
                                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm flex flex-col gap-3 relative overflow-hidden">
                                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                                        <div class="flex justify-between items-start pl-2">
                                            <span class="text-xs font-bold text-gray-400">#<?= $n++; ?></span>
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-bold ring-1 ring-inset ring-blue-600/20">+<?= $prestasi["poin_prestasi"]; ?> Poin</span>
                                        </div>
                                        <p class="text-sm text-gray-800 font-semibold pl-2 leading-relaxed"><?= ucfirst($prestasi["det_prestasi"]); ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion Pelanggaran -->
                    <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white">
                        <button onclick="toggleAccordion('pelanggaran-content', 'pelanggaran-icon')" class="w-full flex items-center justify-between px-6 py-4 bg-gray-50 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary">
                            <span class="font-bold text-gray-900 font-serif">Ketentuan Pelanggaran</span>
                            <i id="pelanggaran-icon" class="bi bi-chevron-down text-gray-500 transition-transform duration-300 transform rotate-180"></i>
                        </button>
                        
                        <div id="pelanggaran-content" class="accordion-content expanded bg-white border-t border-gray-100">
                            <div class="p-4 sm:p-6 bg-gray-50/30">
                                
                                <?php
                                $batas = 25;
                                $halaman = isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                                $halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;
                                $previous = $halaman - 1;
                                $next = $halaman + 1;

                                $jumlah_data = count($ktnpelanggaran);
                                $total_halaman = ceil($jumlah_data / $batas);

                                $data_kntplgr = query("SELECT * FROM ket_pelanggaran ORDER BY poin_pelanggaran LIMIT $halaman_awal, $batas");
                                ?>

                                <!-- Desktop Table -->
                                <div class="hidden md:block overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600">
                                                <th class="py-4 px-6 font-bold tracking-wider uppercase text-center w-20">No</th>
                                                <th class="py-4 px-6 font-bold tracking-wider uppercase w-48">Jenis</th>
                                                <th class="py-4 px-6 font-bold tracking-wider uppercase">Pelanggaran</th>
                                                <th class="py-4 px-6 font-bold tracking-wider uppercase text-center w-32">Poin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            <?php $n = $halaman_awal + 1; ?>
                                            <?php foreach ($data_kntplgr as $plgr) : ?>
                                                <tr class="hover:bg-gray-50/80 transition-colors">
                                                    <td class="py-4 px-6 text-sm text-gray-500 text-center font-medium"><?= $n++; ?></td>
                                                    <td class="py-4 px-6 text-sm text-gray-900 font-bold"><?= ucwords($plgr["jenis_pelanggaran"]); ?></td>
                                                    <td class="py-4 px-6 text-sm text-gray-800 font-medium"><?= ucfirst($plgr["det_pelanggaran"]); ?></td>
                                                    <td class="py-4 px-6 text-center">
                                                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold ring-1 ring-inset ring-red-600/10">
                                                            -<?= $plgr["poin_pelanggaran"]; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Mobile Cards -->
                                <div class="md:hidden space-y-3">
                                    <?php $n = $halaman_awal + 1; ?>
                                    <?php foreach ($data_kntplgr as $plgr) : ?>
                                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm flex flex-col gap-3 relative overflow-hidden">
                                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                                        <div class="flex justify-between items-start pl-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-gray-400">#<?= $n++; ?></span>
                                                <span class="text-xs font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded"><?= ucwords($plgr["jenis_pelanggaran"]); ?></span>
                                            </div>
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-red-50 text-red-700 text-xs font-bold ring-1 ring-inset ring-red-600/10">-<?= $plgr["poin_pelanggaran"]; ?> Poin</span>
                                        </div>
                                        <p class="text-sm text-gray-800 font-semibold pl-2 leading-relaxed"><?= ucfirst($plgr["det_pelanggaran"]); ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Pagination Pelanggaran -->
                                <?php if($total_halaman > 1): ?>
                                <div class="mt-8 flex justify-center overflow-x-auto pb-4 custom-scrollbar">
                                    <nav class="inline-flex rounded-xl shadow-sm -space-x-px w-max" aria-label="Pagination">
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
                        <li><a href="./ketentuan_siswa.php" class="text-gray-500 hover:text-primary transition-colors font-medium">Ketentuan Pelanggaran</a></li>
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
        // Accordion functionality
        function toggleAccordion(contentId, iconId) {
            const content = document.getElementById(contentId);
            const icon = document.getElementById(iconId);
            
            content.classList.toggle('expanded');
            if (content.classList.contains('expanded')) {
                icon.classList.add('rotate-180');
            } else {
                icon.classList.remove('rotate-180');
            }
        }
    </script>
</body>
</html>
