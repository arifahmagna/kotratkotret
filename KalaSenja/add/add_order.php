<?php
include('../php/config.php');
session_start(); // Memulai sesi untuk mengakses keranjang

$session_id = session_id();  // Mendapatkan session_id untuk pengguna saat ini

// Inisialisasi variabel total_price
$total_price = 0;
$cash_nominal=0;
// Mengambil data metode pembayaran dari database
$payment_methods = [];
try {
    $sql = "SELECT id_tmpm, paymentMethod_tmpm FROM tbl_m_paymentmethod";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $payment_methods[] = $row;
        }
    } else {
        echo "Tidak ada metode pembayaran yang ditemukan.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Pastikan $_SESSION['cart'] ada dan itemnya tidak kosong
if (!isset($_SESSION['cart'][$session_id])) {
    $_SESSION['cart'][$session_id] = []; // Inisialisasi keranjang jika kosong untuk session ini
}

// Cek apakah ada item dalam keranjang
if (!empty($_SESSION['cart'][$session_id])) {
    // Iterasi melalui setiap item dalam keranjang untuk menghitung total harga
    foreach ($_SESSION['cart'][$session_id] as $item) {
        $total_price += $item['price'] * $item['quantity']; // Menghitung total
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    /* Styling untuk halaman keranjang dengan tema warna gelap */
    body {
        background-color: #22242f;
        color: #fff;
        font-family: Arial, sans-serif;
    }

    .cart-container {
        width: 100%;
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background-color: #2a2d3a;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #444;
        padding-bottom: 10px;
    }

    .cart-header h2 {
        margin: 0;
        font-size: 2rem; /* Meningkatkan ukuran font judul */
        font-weight: bold;
        color: #ff6347;
    }

    #backspace-btn {
        font-size: 2.5rem; /* Meningkatkan ukuran ikon */
        color: #ff6347;
        padding: 10px;
        transition: color 0.3s ease;
    }

    #backspace-btn:hover {
        color: #e74c3c;
    }


    .cart-items {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #33353f;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease-in-out;
    }

    .cart-item:hover {
        transform: translateY(-5px);
    }

    .cart-item img {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 10px;
    }

    .cart-item .cart-item-details {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .cart-item h4 {
        margin: 0;
        font-size: 1rem;
        color: #fff;
        font-weight: 600;
    }

    .cart-item p {
        margin: 5px 0;
        font-size: 0.9rem;
        color: #bbb;
    }

    .cart-item input[type="number"] {
        width: 50px;
        padding: 5px;
        text-align: center;
        border: 1px solid #444;
        border-radius: 5px;
        background-color: #2a2d3a;
        color: #fff;
    }

    .cart-item form {
        display: flex;
        gap: 10px;
    }

    .cart-item .update-btn {
        background-color: #ff6347;
        color: white;
        padding: 8px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 0.9rem;
    }

    .cart-item .update-btn:hover {
        background-color: #ff4500;
    }

    .remove-item-btn {
        background: none;
        color: #e74c3c;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 5px;
        border-radius: 50%;
        border: 2px solid #e74c3c;
        transition: background-color 0.3s, color 0.3s;
    }

    .checkout-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #444;
    }

    .checkout-btn {
        background-color: #ff6347;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .checkout-btn:hover {
        background-color: #ff4500;
    }

    .cart-total {
        font-size: 1.2rem;
        font-weight: bold;
        color: #fff;
    }

    .cart-empty {
        text-align: center;
        font-size: 1.5rem;
        color: #bbb;
    }
    .form-group {
        margin-bottom: 15px;
    }

    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 10px;
        background-color: #2a2d3a;
        border: 1px solid #444;
        color: #fff;
        border-radius: 5px;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .checkout-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #444;
        flex-direction: column;
    }

    .checkout-btn {
        background-color: #ff6347;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .checkout-btn:hover {
        background-color: #ff4500;
    }

    .cart-total {
        font-size: 1.2rem;
        font-weight: bold;
        color: #fff;
        margin-top: 15px;
    }

    .note-input{
        background-color: #2a2d3a;
        border-radius: 5px;
        border: 1px solid #444;
        color: #fff;
    }

</style>

</head>
<body>
    <div class="cart-container">
        <div class="cart-header">
            <h2>Your Cart</h2>
            <a href="../cashier/cashier_home.php" style="text-decoration: none;"><i class="ph ph-backspace" id="backspace-btn"></i></a>
        </div>

            <div class="cart-items" id="cart-items-container">
        <?php
        if (!empty($_SESSION['cart'][$session_id])) {
            foreach ($_SESSION['cart'][$session_id] as $index => $item) {
                $total_price == $item['price'] * $item['quantity'];
                $total_price_per_item = $item['price'] * $item['quantity'];

                echo "<div class='cart-item'>";
                echo "<img src='" . $item['photo'] . "' alt='" . htmlspecialchars($item['name']) . "'>";
                echo "<div class='cart-item-details'>";
                echo "<h4>" . htmlspecialchars($item['name']) . "</h4>";
                echo "<p>Price: Rp. " . number_format($item['price']) . "</p>";
                echo "<p class='total-price'>Total: Rp. " . number_format($total_price_per_item) . "</p>";

                // Menambahkan input quantity dan note
                echo "<form action='../update/update_cart.php' method='POST'>";
                echo "<input type='hidden' name='index' value='$index'>";
                echo "<label for='quantity'>Quantity:</label>";
                echo "<input type='number' name='quantity[$index]' value='" . $item['quantity'] . "' min='1' required>";
                echo "<label for='note'>Note:</label>";
                echo "<input class='note-input' type='text' name='note[$index]' value='" . htmlspecialchars($item['note'] ?? '') . "' placeholder='Catatan'>";

                // Submit form untuk mengupdate keranjang
                echo "<button type='submit' class='update-btn'>Update</button>";
                echo "</form>";

                // Form penghapusan
                echo "<form action='../delete/delete_order.php' method='POST' style='display: flex; justify-content: flex-end; align-items: center;'>";
                echo "<input type='hidden' name='index' value='$index'>";
                echo "<button type='submit' class='remove-item-btn'><i class='ph ph-trash'></i></button>";
                echo "</form>";

                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='cart-empty'>Your cart is empty.</p>";
        }
        ?>
    </div>

        <!-- Form Pembayaran di atas total dan tombol -->
        <form id="payment-form" method="POST" action="../fungsi/proces_payment.php">
            <!-- Form Input untuk Pembayaran -->
            <div class="form-group">
                <label for="customer-name">Nama Pelanggan</label>
                <input type="text" id="customer-name" name="customer_name" required>
            </div>

            <div class="form-group">
                <label for="typeOrder">Jenis Pesanan</label>
                <select name="typeOrder" id="typeOrder" required>
                    <option value="Dine-in">Dine-in</option>
                    <option value="Take away">Take away</option>
                </select>
            </div>

            <div class="form-group" style=display:none;>
                <label for="statusOrder">Status Pesanan</label>
                <select name="statusOrder" id="statusOrder" required>
                    <option>Open</option>
                    <option>Close</option>
                </select>
            </div>

            <div class="form-group">
                <label for="paymentMethod">Metode Pembayaran</label>
                <select name="paymentMethod" id="paymentMethod" required>
                    <?php foreach ($payment_methods as $method): ?>
                        <option value="<?= $method['id_tmpm']; ?>"><?= $method['paymentMethod_tmpm']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style=display:none;>
                <label for="totalAmount">Cash</label>
                <input type="text" id="totalAmount" name="totalAmount" value="Rp. <?= number_format($total_price, 2, ',', '.') ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="cashNominal">Cash</label>
                <input type="text" id="cashNominal" name="cashNominal" value="Rp. <?= number_format($cash_nominal, 2, ',', '.') ?>" required>
            </div>
            <div class="kembalian-container" id="kembalian">
                Kembalian: Rp. 0
            </div>
        </form>

        <div class="checkout-container">
        <div class="cart-total">
            <span>Total: Rp. <span id="cart-total"><?= number_format($total_price) ?></span></span>
        </div>
            <button class="checkout-btn" id="checkout" name="checkout" type="submit" form="payment-form">Checkout</button>
        </div>
    </div>
</body>
<script>
    // Fungsi untuk menghitung kembalian
    document.getElementById('cashNominal').addEventListener('input', function() {
        let cashNominal = parseFloat(this.value) || 0;
        let totalPrice = <?= $total_price ?>; // Ambil nilai total harga dari PHP
        let kembalian = cashNominal - totalPrice;

        // Update tampilan kembalian
        document.getElementById('kembalian').textContent = 'Kembalian: Rp. ' + (kembalian >= 0 ? kembalian.toLocaleString() : '0');
    });
</script>
</html>
