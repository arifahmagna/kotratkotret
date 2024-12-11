<?php
session_start(); // Memulai sesi untuk mengakses data keranjang

// Pastikan $_SESSION['cart'] ada dan setiap item memiliki 'quantity'
if (isset($_SESSION['cart'])) {
    // Cek setiap item dalam keranjang dan pastikan ada 'quantity'
    foreach ($_SESSION['cart'] as &$item) {
        if (!isset($item['quantity'])) {
            $item['quantity'] = 1; // Set default ke 1 jika 'quantity' tidak ada
        }
    }
}

// Menampilkan isi keranjang
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    foreach ($_SESSION['cart'] as $item) {
        echo "<p>Item: " . htmlspecialchars($item['name']) . "<br>";
        echo "Price: Rp. " . number_format($item['price'], 2) . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "<img src='" . htmlspecialchars($item['photo']) . "' alt='" . htmlspecialchars($item['name']) . "' style='width: 100px;'><br><br>";
    }
}

// Menambahkan tombol untuk checkout atau melanjutkan
echo "<a href='checkout.php'><button>Proceed to Checkout</button></a>";
?>
