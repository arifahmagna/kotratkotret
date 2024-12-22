// logout.php
<?php
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Redirect atau memberi respons sukses jika menggunakan AJAX
// Untuk AJAX, kita tidak perlu mengarahkan ke halaman baru secara otomatis, cukup kirimkan respons
echo 'Logout berhasil';

// Jika ingin mengarahkan pengguna ke halaman login secara otomatis setelah logout
header('Location: login.php');
// exit();
?>
