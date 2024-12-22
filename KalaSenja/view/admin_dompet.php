<?php
include('../php/config.php'); // File untuk koneksi ke database

// Cek apakah sudah login, jika tidak redirect ke login page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika session tidak ada atau role bukan 'admin', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}

// Mengambil daftar transaksi berdasarkan metode pembayaran
$paymentMethods = ['BCA', 'QRIS', 'DANA'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .card-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 280px;
            padding: 20px;
            margin: 10px;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .transaction {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .transaction p {
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            margin-top: 10px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Dashboard Admin - Total Transaksi</h1>

        <div class="card-container">
            <?php foreach ($paymentMethods as $method): ?>
                <div class="card">
                    <div class="card-header">
                        <?php echo $method; ?> - Total Nominal Transaksi
                    </div>
                    <div class="card-body">
                        <?php
                        // Query untuk menghitung total transaksi berdasarkan metode pembayaran
                        $query = "
                            SELECT SUM(o.totalOrder_tmo) AS total_transaksi 
                            FROM tbl_m_order o
                            JOIN tbl_m_paymentmethod p ON o.id_tmpm = p.id_tmpm
                            WHERE o.statusOrder_tmo = 'Open' AND p.paymentMethod_tmpm = '$method'
                            GROUP BY p.paymentMethod_tmpm
                        ";
                        $result = mysqli_query($conn, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        
                        // Gunakan nilai default 0 jika 'total_transaksi' tidak ada
                        $totalNominal = $row['total_transaksi'] ?? 0;

                        if ($totalNominal > 0) {
                            echo '<p><strong>Total Nominal:</strong> ' . number_format($totalNominal, 2, ',', '.') . '</p>';
                        } else {
                            echo '<p>Tidak ada transaksi untuk ' . $method . '.</p>';
                        }
                    } else {
                        echo '<p>Error: ' . mysqli_error($conn) . '</p>';
                    }

                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
