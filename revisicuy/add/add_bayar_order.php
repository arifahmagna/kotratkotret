<?php
session_start();
include('../php/config.php');

// Cek apakah data order ada di sesi
if (!isset($_SESSION['order_id'])) {
    die("Pesanan tidak ditemukan.");
}

// Ambil data pembayaran dari form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_SESSION['order_id'];
    $payment_method = mysqli_real_escape_string($conn, $_POST['paymentMethod']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $status_order = mysqli_real_escape_string($conn, $_POST['statusOrder']);
    $cash_nominal = mysqli_real_escape_string($conn, $_POST['cashNominal']);
    
    // Mulai transaksi
    mysqli_begin_transaction($conn);

    try {
        // Update status order dan metode pembayaran
        $update_order_query = "
            UPDATE tbl_m_order
            SET statusOrder_tmo = 'Closed', id_tmpm = '$payment_method', cashNominal_tmo = '$cash_nominal'
            WHERE id_tmo = '$order_id'
        ";
        if (!mysqli_query($conn, $update_order_query)) {
            throw new Exception("Error updating order status: " . mysqli_error($conn));
        }

        // Commit transaksi jika berhasil
        mysqli_commit($conn);

        // Hapus sesi setelah pembayaran berhasil
        unset($_SESSION['order_id']);
        unset($_SESSION['totalPayment']);

        // Redirect ke halaman konfirmasi atau sukses
        header("Location: ../php/success.php");
        exit();
    } catch (Exception $e) {
        // Rollback jika terjadi error
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Styling untuk halaman pembayaran */
        body {
            background-color: #22242f;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .payment-container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #2a2d3a;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .payment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .payment-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff6347;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #fff;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .form-group textarea {
            resize: vertical;
        }

        .btn-confirm {
            background-color: #ff6347;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        .btn-confirm:hover {
            background-color: #ff4500;
        }

        .cart-total {
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h2>Konfirmasi Pembayaran</h2>
        </div>

        <form id="payment-form" method="POST" action="add_bayar_order.php">
            <!-- Form Input untuk Pembayaran -->
            <div class="form-group">
                <label for="customer-name">Nama Pelanggan</label>
                <input type="text" id="customer-name" name="customer_name" required>
            </div>

            <div class="form-group">
                <label for="paymentMethod">Metode Pembayaran</label>
                <select name="paymentMethod" id="paymentMethod" required>
                    <!-- Loop untuk metode pembayaran -->
                    <?php
                    // Ambil daftar metode pembayaran dari tabel tbl_m_paymentmethod
                    $payment_methods_query = "SELECT * FROM tbl_m_paymentmethod";
                    $result = mysqli_query($conn, $payment_methods_query);
                    while ($method = mysqli_fetch_assoc($result)) {
                        echo "<option value=\"{$method['id_tmpm']}\">{$method['paymentMethod_tmpm']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="statusOrder">Status Pesanan</label>
                <select name="statusOrder" id="statusOrder" required>
                    <option value="Closed">Closed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cashNominal">Jumlah Pembayaran</label>
                <input type="number" id="cashNominal" name="cashNominal" required>
            </div>

            <button type="submit" class="btn-confirm">Konfirmasi Pembayaran</button>
        </form>

        <div class="cart-total">
            <span>Total Pembayaran: Rp. <?php echo number_format($_SESSION['totalPayment'], 0, ',', '.'); ?></span>
        </div>
    </div>
</body>
</html>
