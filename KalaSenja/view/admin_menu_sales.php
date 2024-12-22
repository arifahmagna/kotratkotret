<?php
// Include file konfigurasi database
include('../php/config.php'); 

// Memulai session untuk autentikasi
session_start();

// Cek apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../fungsi/login.php'); // Redirect ke halaman login jika tidak memenuhi syarat
    exit;
}

// Query untuk mendapatkan menu paling laris
$queryBestSeller = "
    SELECT m.menu_tmm, SUM(t.quantity_tto) AS total_quantity
    FROM tbl_m_menu m
    JOIN tbl_t_order t ON m.id_tmm = t.id_tmm
    GROUP BY m.id_tmm
    ORDER BY total_quantity DESC
    LIMIT 5";

// Query untuk menu yang kurang laris
$queryLeastSold = "
    SELECT menu_tmm, stock_tmm
    FROM tbl_m_menu
    WHERE stock_tmm > 0
    ORDER BY stock_tmm DESC
    LIMIT 5";

// Query untuk transaksi, menggabungkan menu yang sama
$queryTransactions = "
    SELECT m.menu_tmm, m.price_tmm, SUM(t.quantity_tto) AS total_quantity, 
           SUM(t.quantity_tto * m.price_tmm) AS total_price
    FROM tbl_t_order t
    JOIN tbl_m_menu m ON t.id_tmm = m.id_tmm
    GROUP BY m.id_tmm";

// Menjalankan query
$bestSellerResult = $conn->query($queryBestSeller);
$leastSoldResult = $conn->query($queryLeastSold);
$transactionsResult = $conn->query($queryTransactions);

// Inisialisasi total keseluruhan
$totalQuantity = 0;
$totalHargaKeseluruhan = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Paling Laris dan Kurang Laris</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Menu Paling Laris</h1>
<table>
    <thead>
        <tr>
            <th>Nama Menu</th>
            <th>Total Terjual</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($bestSellerResult->num_rows > 0) {
            while ($row = $bestSellerResult->fetch_assoc()) {
                echo "<tr><td>" . $row['menu_tmm'] . "</td><td>" . $row['total_quantity'] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Tidak ada data</td></tr>";
        }
        ?>
    </tbody>
</table>

<h1>Menu Kurang Laris (Stok Banyak)</h1>
<table>
    <thead>
        <tr>
            <th>Nama Menu</th>
            <th>Stok Tersedia</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($leastSoldResult->num_rows > 0) {
            while ($row = $leastSoldResult->fetch_assoc()) {
                echo "<tr><td>" . $row['menu_tmm'] . "</td><td>" . $row['stock_tmm'] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Tidak ada data</td></tr>";
        }
        ?>
    </tbody>
</table>

<h1>Detail Transaksi Menu</h1>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Menu</th>
            <th>Harga Satuan</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($transactionsResult->num_rows > 0) {
            $no = 1;
            while ($row = $transactionsResult->fetch_assoc()) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['menu_tmm']}</td>
                        <td>" . number_format($row['price_tmm'], 0, ',', '.') . "</td>
                        <td>{$row['total_quantity']}</td>
                        <td>" . number_format($row['total_price'], 0, ',', '.') . "</td>
                      </tr>";
                $totalQuantity += $row['total_quantity'];
                $totalHargaKeseluruhan += $row['total_price'];
                $no++;
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data transaksi</td></tr>";
        }
        ?>
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="3">Total</td>
            <td><?php echo $totalQuantity; ?></td>
            <td>Rp <?php echo number_format($totalHargaKeseluruhan, 0, ',', '.'); ?></td>
        </tr>
    </tfoot>
</table>

<!-- Tombol Cetak PDF -->
<form method="post" action="cetak_pdf.php">
    <button type="submit">Cetak PDF</button>
</form>

<?php
$conn->close();
?>
</body>
</html>
