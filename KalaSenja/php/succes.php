<?php
session_start();
include('../php/config.php');

// Pastikan bahwa pesanan berhasil diproses
if (!isset($_SESSION['order_id'])) {
    die("Pesanan tidak ditemukan.");
}

$order_id = $_SESSION['order_id'];
$total_payment = $_SESSION['totalPayment'];

// Ambil detail pesanan dari database
$order_query = "SELECT * FROM tbl_m_order WHERE id_tmo = '$order_id'";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    die("Pesanan tidak ditemukan di database.");
}

// Ambil daftar item pesanan
$order_items_query = "SELECT tto.*, tts.menuName_tts, tts.menuPrice_tts 
                      FROM tbl_t_order tto 
                      JOIN tbl_m_menu tts ON tto.id_tts = tts.id_tts 
                      WHERE tto.id_tmo = '$order_id'";
$order_items_result = mysqli_query($conn, $order_items_query);

// Mulai halaman HTML
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Sukses - Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-color: #22242f;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .success-container {
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #2a2d3a;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .success-header {
            margin-bottom: 20px;
            text-align: center;
        }

        .success-header h2 {
            font-size: 2rem;
            color: #ff6347;
        }

        .order-details {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #33353f;
            border-radius: 8px;
        }

        .order-details h3 {
            margin-bottom: 10px;
            color: #ff6347;
        }

        .order-details p {
            font-size: 1.1rem;
            color: #bbb;
        }

        .order-items-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .order-items-table th, .order-items-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #444;
        }

        .order-items-table th {
            background-color: #444;
            color: #fff;
        }

        .order-items-table td {
            background-color: #33353f;
        }

        .order-total {
            margin-top: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #ff6347;
        }

        .back-to-home-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff6347;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .back-to-home-btn:hover {
            background-color: #ff4500;
        }

    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-header">
            <h2>Pembayaran Berhasil</h2>
            <p>Terima kasih, pesanan Anda telah berhasil diproses.</p>
        </div>

        <div class="order-details">
            <h3>Detail Pesanan</h3>
            <p><strong>ID Pesanan:</strong> <?php echo $order['id_tmo']; ?></p>
            <p><strong>Nama Pelanggan:</strong> <?php echo $order['customerName_tmo']; ?></p>
            <p><strong>Status Pesanan:</strong> <?php echo $order['statusOrder_tmo']; ?></p>
            <p><strong>Metode Pembayaran:</strong> <?php
                $payment_method_query = "SELECT paymentMethod_tmpm FROM tbl_m_paymentmethod WHERE id_tmpm = '{$order['id_tmpm']}'";
                $payment_method_result = mysqli_query($conn, $payment_method_query);
                $payment_method = mysqli_fetch_assoc($payment_method_result);
                echo $payment_method['paymentMethod_tmpm'];
            ?></p>
            <p><strong>Total Pembayaran:</strong> Rp. <?php echo number_format($total_payment, 0, ',', '.'); ?></p>
        </div>

        <div class="order-items">
            <h3>Rincian Pesanan</h3>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_order = 0;
                    while ($item = mysqli_fetch_assoc($order_items_result)) {
                        $item_total = $item['subTotal_tto'];
                        $total_order += $item_total;
                        echo "<tr>
                                <td>{$item['menuName_tts']}</td>
                                <td>Rp. " . number_format($item['menuPrice_tts'], 0, ',', '.') . "</td>
                                <td>{$item['quantity_tto']}</td>
                                <td>Rp. " . number_format($item_total, 0, ',', '.') . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="order-total">
            <p><strong>Total Pembayaran: Rp. <?php echo number_format($total_order, 0, ',', '.'); ?></strong></p>
        </div>

        <a href="../index.php" class="back-to-home-btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
