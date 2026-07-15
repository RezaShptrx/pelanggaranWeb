<?php
session_start();
require 'functions.php';

$carousel = mysqli_query($conn, "SELECT isi_komponen FROM komponen WHERE nama_komponen = 'login_carousel'")->fetch_assoc();

if (isset($_COOKIE["gk"]) && isset($_COOKIE["gu"]) && isset($_COOKIE["gr"])) {
    $gk = $_COOKIE["gk"];
    $gu = $_COOKIE["gu"];
    $gr = $_COOKIE["gr"];

    $query = mysqli_query($conn, "SELECT * FROM guru_pembina WHERE id_guru = $gk");
    $result = mysqli_fetch_assoc($query);

    if ($gu === hash('gost', $result["nip"])) {
        $_SESSION["login"] = true;
        $_SESSION["$gr"] = true;
        $_SESSION["id"] = $result["id_guru"];
        $_SESSION["nip"] = $result["nip"];
    }

    header("Location: ./login.php");
}

if (isset($_COOKIE["sk"]) && isset($_COOKIE["su"]) && isset($_COOKIE["sr"])) {
    $sk = $_COOKIE["sk"];
    $su = $_COOKIE["su"];
    $sr = $_COOKIE["sr"];

    $query = mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa = $sk");
    $result = mysqli_fetch_assoc($query);

    if ($su === hash('gost', $result["nis"])) {
        $_SESSION["login"] = true;
        $_SESSION["$sr"] = true;
        $_SESSION["id_siswa"] = $result["id_siswa"];
        $_SESSION["nis"] = $result["nis"];
        $_SESSION["id"] = $id_siswa;
    }
    header("Location: ./login.php");
}

if (isset($_SESSION["login"])) {
    header('Location: ./../index.php');
    exit;
}

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (strlen($username) === 16 || strlen($username) === 18) {
        $query = mysqli_query($conn, "SELECT `id_guru`, `nip`, `nama_guru`, `role` FROM guru_pembina WHERE nip = '$username' AND password = '$password'");

        if (mysqli_num_rows($query) === 1) {
            $result = mysqli_fetch_assoc($query);

            if ($result["role"] === "admin") {
                $_SESSION["admin"] = true;
                $_SESSION["admgr"] = true;
                $_SESSION["login"] = true;
                $_SESSION["id"] = $result["id_guru"];
                $_SESSION["nip"] = $result["nip"];
            } else {
                $_SESSION["login"] = true;
                $_SESSION["guru"] = true;
                $_SESSION["id"] = $result["id_guru"];
                $_SESSION["nip"] = $result["nip"];
            }

            $ip = $_SERVER['REMOTE_ADDR'];
            $role = $result["role"];
            $nama_user = $result["nama_guru"];

            if (isset($_POST["remember"])) {
                setcookie('gk', $result["id_guru"], time() + 60 * 30);
                setcookie('gu', hash('gost', $username), time() + 60 * 30);
                setcookie('gr', $role, time() + 60 * 30);
            }
            mysqli_query($conn, "INSERT INTO `user_log` (`ip_user`, `username`, `nama_user`, `role`) VALUES ('$ip', '$username', '$nama_user', '$role')");
            header('Location: ./../index.php');
        } else {
            $error = true;
        }
    } elseif (strlen($username) === 5) {
        $query = mysqli_query($conn, "SELECT `id_siswa`, `nis`, `nama_siswa`, `role` FROM siswa WHERE nis = '$username' AND password = '$password'");

        if (mysqli_num_rows($query) === 1) {
            $result = mysqli_fetch_assoc($query);

            $_SESSION["login"] = true;
            $_SESSION["nis"] = $result["nis"];

            $id_siswa = $result["id_siswa"];
            $ip = $_SERVER['REMOTE_ADDR'];
            $role = $result["role"];
            $nama_user = $result["nama_siswa"];
            $role = $result["role"];

            if ($role === "admin") {
                $_SESSION["admin"] = true;
                $_SESSION["login"] = true;
                $_SESSION["admsis"] = true;
                $_SESSION["id_siswa"] = $id_siswa;
            }

            if ($role === "osis") {
                $_SESSION["osis"] = true;
                $_SESSION["id_siswa"] = $id_siswa;
            }

            if ($role === "siswa") {
                $_SESSION["siswa"] = true;
                $_SESSION["id_siswa"] = $id_siswa;
            }

            if (isset($_POST["remember"])) {
                setcookie('sk', $id_siswa, time() + 60 * 30);
                setcookie('su', hash('gost', $username), time() + 60 * 30);
                setcookie('sr', $role, time() + 60 * 30);
            }

            mysqli_query($conn, "INSERT INTO `user_log` (`ip_user`, `username`, `nama_user`, `role`) VALUES ('$ip', '$username', '$nama_user', '$role')");
            if (isset($_SESSION["siswa"])) {
                header("Location: ./halaman_siswa.php");
            } else {
                header('Location: ./../index.php');
            }
        } else {
            $error = true;
        }
    } else {
        $unknown = true;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | OSIS SMKN 12 JAKARTA</title>
    
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
</head>
<body class="bg-[#fcfcfd] text-gray-800 font-sans antialiased selection:bg-primary selection:text-white flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <!-- Logo -->
                <a href="../index.php" class="flex items-center gap-4 group mx-auto md:mx-0">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center p-2 shadow-inner group-hover:shadow-md transition-all">
                        <img src="../img/logosmk12.png" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-serif font-bold text-2xl tracking-wide text-gray-900 group-hover:text-primary transition-colors">OSIS SMKN 12</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex items-center py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden flex flex-col md:flex-row">
                
                <!-- Left: Form -->
                <div class="w-full md:w-1/2 p-10 md:p-16 flex flex-col justify-center">
                    <div class="mb-8">
                        <h1 class="font-serif text-3xl font-bold text-gray-900 mb-2">Halaman Masuk</h1>
                        <p class="text-gray-500 text-sm">Silakan masuk menggunakan akun Anda.</p>
                    </div>

                    <?php if (isset($error)) : ?>
                        <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl text-sm font-medium border border-red-100 mb-6 flex items-center gap-2">
                            <i class="bi bi-exclamation-circle-fill"></i> Username atau password salah!
                        </div>
                    <?php endif; ?>

                    <?php if (isset($unknown)) : ?>
                        <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl text-sm font-medium border border-red-100 mb-6 flex items-center gap-2">
                            <i class="bi bi-exclamation-circle-fill"></i> Format data tidak sesuai!
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="space-y-6">
                        <div>
                            <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                            <input type="text" name="username" id="username" required autofocus autocomplete="off" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-3 border outline-none transition-all" placeholder="Masukkan username...">
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" id="password" required autocomplete="off" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-3 border outline-none transition-all" placeholder="••••••••">
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                            <label for="remember" class="ml-2 text-sm font-medium text-gray-600 cursor-pointer">Ingat Saya</label>
                        </div>
                        
                        <button type="submit" name="login" class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-sm text-sm font-bold text-white bg-primary hover:bg-secondary transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Masuk
                        </button>
                    </form>
                </div>

                <!-- Right: Carousel -->
                <div class="hidden md:block w-full md:w-1/2 relative bg-gray-50 min-h-[500px]">
                    <div id="loginCarousel" class="absolute inset-0 w-full h-full overflow-hidden">
                        <?php 
                        $foto = explode(',', $carousel["isi_komponen"]);
                        $hasPhotos = false;
                        foreach($foto as $index => $img) {
                            if (!empty(trim($img))) {
                                $hasPhotos = true;
                                $activeClass = $index === 0 ? "opacity-100 z-10 scale-100" : "opacity-0 z-0 scale-105";
                                
                                // Handling weird old image string concatenation from original file
                                $imgSrc = "../img/" . trim($img);
                                
                                echo '<img src="' . $imgSrc . '" class="carousel-slide absolute inset-0 w-full h-full object-cover transition-all duration-1000 ease-[cubic-bezier(0.4,0,0.2,1)] ' . $activeClass . '">';
                            }
                        }
                        if (!$hasPhotos) {
                            echo '<div class="absolute inset-0 flex items-center justify-center bg-gray-100 text-gray-400 font-medium"><img src="../img/logosmk12.png" class="w-32 h-32 opacity-20"></div>';
                        }
                        ?>
                        <!-- Overlay gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 via-transparent to-transparent z-20 pointer-events-none"></div>
                    </div>
                </div>
                
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-auto pt-8 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <img src="../img/logosmk12.png" alt="Logo" class="w-10 h-10 object-contain">
                    <h5 class="font-serif font-bold text-lg text-gray-900">OSIS SMKN 12 JAKARTA</h5>
                </div>
                <p class="text-gray-400 text-sm font-medium">&copy; Copyright 2022, RPL A0204. All rights reserved.</p>
                <p class="text-gray-400 text-sm font-medium">updated 2026, RPL R2809.</p>
            </div>
        </div>
    </footer>

    <!-- Carousel Script -->
    <script>
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