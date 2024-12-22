<?php
session_start();
$session_id = session_id();

// Pastikan keranjang ada
if (isset($_SESSION['cart'][$session_id])) {
    // Ambil index dan data yang dikirimkan
    $index = $_POST['index'];
    $quantity = $_POST['quantity'][$index];
    $note = $_POST['note'][$index];

    // Perbarui item di dalam keranjang
    $_SESSION['cart'][$session_id][$index]['quantity'] = $quantity;
    $_SESSION['cart'][$session_id][$index]['note'] = $note;
}

// Cek peran pengguna untuk mengarahkan ke halaman yang sesuai
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'user') {
        // Jika role user adalah 'customer', arahkan ke halaman add_order_cust.php
        header('Location: ../add/add_order_cust.php');
    } elseif ($_SESSION['role'] == 'cashier') {
        // Jika role user adalah 'cashier', arahkan ke halaman add_order.php
        header('Location: ../add/add_order.php');
    } else {
        // Jika role tidak dikenali, bisa mengarahkan ke halaman default atau error
        header('Location: ../fungsi/login.php');
    }
} else {
    // Jika session role tidak ditemukan, arahkan ke halaman login
    header('Location: ../login.php');
}
exit;
?>
