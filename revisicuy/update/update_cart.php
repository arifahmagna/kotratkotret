<?php
session_start();

// Pastikan keranjang ada
if (isset($_SESSION['cart'])) {
    // Ambil index dan data yang dikirimkan
    $index = $_POST['index'];
    $quantity = $_POST['quantity'][$index];
    $note = $_POST['note'][$index];

    // Perbarui item di dalam keranjang
    $_SESSION['cart'][$index]['quantity'] = $quantity;
    $_SESSION['cart'][$index]['note'] = $note;
}

// Kembali ke halaman keranjang
header('Location: ../add/add_order.php');
exit;
?>
