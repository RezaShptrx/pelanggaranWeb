<?php
session_start();

// memberhetntikan session agar balik ke halaman login
$_SESSION = [];
session_unset();
session_destroy();

// menghapus cookie
// valuenya dikosongkan dan waktunya dibuat -
setcookie('gk', '', time() - 3600);
setcookie('gu', '', time() - 3600);
setcookie('gr', '', time() - 3600);
setcookie('sk', '', time() - 3600);
setcookie('su', '', time() - 3600);
setcookie('sr', '', time() - 3600);

header('Location: ./login.php');
exit;
?>