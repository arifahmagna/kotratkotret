<?php
session_start(); // Memulai sesi untuk mengakses keranjang
$session_id = session_id(); 

// Pastikan role tersedia di session, misalnya 'role' di session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Periksa apakah ada item yang dihapus
if (isset($_POST['index'])) {
    $index = $_POST['index'];
    
    // Cek apakah session cart tersedia
    if (isset($_SESSION['cart'][$session_id])) {
        // Hapus item berdasarkan index
        unset($_SESSION['cart'][$session_id][$index]);
        
        // Redirect berdasarkan role
        if ($role == 'user') {
            // Untuk customer, redirect ke halaman add_order_cust.php
            header('Location: ../costumer/cust_home.php');
        } elseif ($role == 'cashier') {
            // Untuk cashier, redirect ke halaman add_order.php
            header('Location: ../add/add_order.php');
        } else {
            // Jika tidak ada role yang dikenali, bisa redirect ke halaman default
            header('Location: ../index.php');
        }
        exit();
    } else {
        echo "Keranjang kosong.";
    }
} else {
    echo "Tidak ada item yang dipilih untuk dihapus.";
}
?>
