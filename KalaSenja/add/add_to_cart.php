<?php
session_start(); // Mulai sesi untuk menyimpan data keranjang

// Mengecek apakah data yang diperlukan ada
if (isset($_POST['id'], $_POST['name'], $_POST['price'], $_POST['photo'])) {
    // Ambil data dari form POST
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $photo = $_POST['photo'];

    // Buat array item yang akan ditambahkan ke dalam keranjang
    $item = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'photo' => $photo
    ];

    // Jika keranjang belum ada dalam sesi, buat array baru
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Tambahkan item ke dalam keranjang
    $_SESSION['cart'][] = $item;

    // Redirect kembali ke halaman menu setelah menambahkan item ke keranjang
    header("Location: ../cashier/cashier_home.php"); 
    exit(); // Pastikan script berhenti setelah redirect
} else {
    // Jika data tidak lengkap, tampilkan pesan error
    echo "Invalid data!";
    exit();
}
?>
