<?php
include('../php/config.php'); // File untuk koneksi ke database

// Cek apakah sudah login, jika tidak redirect ke login page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika session tidak ada atau role bukan 'admin', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}

// Query untuk mengambil total pendapatan per hari dalam seminggu
$weeklyQuery = "
    SELECT DATE(dateOrder_tmo) AS orderDay, SUM(totalOrder_tmo) AS totalPendapatan
    FROM tbl_m_order
    WHERE statusOrder_tmo = 'Open' 
    AND dateOrder_tmo >= CURDATE() - INTERVAL 7 DAY
    GROUP BY DATE(dateOrder_tmo)
    ORDER BY orderDay ASC
";
$weeklyResult = $conn->query($weeklyQuery);

// Array untuk menyimpan data pendapatan per hari
$dailyPendapatan = [];
$dailyDays = [];

// Ambil hasil query dan masukkan ke dalam array
while ($row = $weeklyResult->fetch_assoc()) {
    $dailyPendapatan[] = $row['totalPendapatan'];
    $dailyDays[] = date('D', strtotime($row['orderDay'])); // Mengambil nama hari (Senin, Selasa, dst)
}

// Query untuk mengambil jumlah penjualan per hari dalam seminggu
$itemTotalQuery = "
    SELECT DATE(o.dateOrder_tmo) AS orderDay, SUM(t.quantity_tto) AS totalJumlahPenjualan
    FROM tbl_t_order t
    JOIN tbl_m_order o ON t.id_tmo = o.id_tmo
    WHERE o.statusOrder_tmo = 'Open'
    AND o.dateOrder_tmo >= CURDATE() - INTERVAL 7 DAY
    GROUP BY DATE(o.dateOrder_tmo)
    ORDER BY orderDay ASC
";
$itemTotalResult = $conn->query($itemTotalQuery);

// Array untuk menyimpan jumlah item per hari
$dailyJumlah = [];
$allDays = [];

// Membuat array default untuk semua hari
for ($i = 6; $i >= 0; $i--) {
    $allDays[date('Y-m-d', strtotime("-$i days"))] = 0; // Default nilai nol
}

// Menambahkan jumlah penjualan per hari
while ($row = $itemTotalResult->fetch_assoc()) {
    $date = $row['orderDay'];
    $allDays[$date] = $row['totalJumlahPenjualan'];
}

// Ambil data jumlah penjualan per hari dalam urutan yang benar
$dailyJumlah = array_values($allDays);

// Menutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Penjualan Mingguan</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link CSS eksternal jika ada -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js CDN -->
</head>
<body>
    <div class="container">
        <h1>Dashboard Penjualan Mingguan</h1>

        <h2>Total Pendapatan dan Jumlah Penjualan Minggu Ini</h2>

        <!-- Tempatkan grafik bar -->
        <canvas id="weeklySalesChart" width="400" height="200"></canvas>
    </div>

    <script>
        // Data dari PHP untuk hari-hari dalam seminggu
        var days = <?php echo json_encode($dailyDays); ?>;
        var totalPendapatan = <?php echo json_encode($dailyPendapatan); ?>;
        var totalJumlah = <?php echo json_encode($dailyJumlah); ?>;

        // Debugging untuk memastikan data sudah benar
        console.log("Days:", days);
        console.log("Total Pendapatan:", totalPendapatan);
        console.log("Total Jumlah Penjualan:", totalJumlah);

        // Membuat grafik batang (bar chart)
        var ctx = document.getElementById('weeklySalesChart').getContext('2d');
        var weeklySalesChart = new Chart(ctx, {
            type: 'bar', // Jenis grafik: bar chart
            data: {
                labels: days, // Hari-hari dalam seminggu (Senin, Selasa, dst)
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: totalPendapatan, // Data pendapatan per hari
                    backgroundColor: '#36A2EB', // Warna bar untuk pendapatan
                    borderColor: '#36A2EB',
                    borderWidth: 1
                }, {
                    label: 'Jumlah Penjualan',
                    data: totalJumlah, // Data jumlah penjualan per hari
                    backgroundColor: '#FF6384', // Warna bar untuk jumlah penjualan
                    borderColor: '#FF6384',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { 
                                // Format untuk pendapatan dengan Rupiah
                                if (this.datasetIndex === 0) {
                                    return 'Rp ' + value.toLocaleString(); 
                                }
                                return value;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                // Menampilkan data tooltip sesuai dataset
                                if (tooltipItem.datasetIndex === 0) {
                                    // Tooltip untuk pendapatan
                                    return tooltipItem.label + ': Rp ' + tooltipItem.raw.toLocaleString();
                                } else {
                                    // Tooltip untuk jumlah penjualan
                                    return tooltipItem.label + ': ' + tooltipItem.raw + ' item';
                                }
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>
</html>
