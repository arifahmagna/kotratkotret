<?php
include('../php/config.php');
session_start();

// Cek jika pengguna tidak login atau tidak memiliki hak akses 'cashier'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cashier') {
    // Jika session tidak ada atau role bukan 'cashier', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}

// Menentukan filter yang digunakan
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Menentukan batas tanggal berdasarkan filter yang dipilih
switch ($filter) {
    case 'week':
        $dateStart = date('Y-m-d', strtotime('last Sunday'));
        $dateEnd = date('Y-m-d', strtotime('next Saturday'));
        $sql = "SELECT o.id_tmo, o.dateOrder_tmo, o.customerName_tmo, o.totalOrder_tmo, o.statusOrder_tmo, pm.paymentMethod_tmpm, o.typeOrder_tmo
                FROM tbl_m_order o
                JOIN tbl_m_user u ON o.id_tmu = u.id_tmu
                JOIN tbl_m_paymentmethod pm ON o.id_tmpm = pm.id_tmpm
                WHERE o.dateOrder_tmo BETWEEN '$dateStart' AND '$dateEnd'
                ORDER BY o.dateOrder_tmo DESC";
        break;
    case 'month':
        $dateStart = date('Y-m-01'); // Pertama bulan ini
        $dateEnd = date('Y-m-t');   // Terakhir bulan ini
        $sql = "SELECT o.id_tmo, o.dateOrder_tmo, o.customerName_tmo, o.totalOrder_tmo, o.statusOrder_tmo, pm.paymentMethod_tmpm, o.typeOrder_tmo
                FROM tbl_m_order o
                JOIN tbl_m_user u ON o.id_tmu = u.id_tmu
                JOIN tbl_m_paymentmethod pm ON o.id_tmpm = pm.id_tmpm
                WHERE o.dateOrder_tmo BETWEEN '$dateStart' AND '$dateEnd'
                ORDER BY o.dateOrder_tmo DESC";
        break;
    case 'today':
    default:
        $dateStart = date('Y-m-d'); // Hari ini
        $sql = "SELECT o.id_tmo, o.dateOrder_tmo, o.customerName_tmo, o.totalOrder_tmo, o.statusOrder_tmo, pm.paymentMethod_tmpm, o.typeOrder_tmo
                FROM tbl_m_order o
                JOIN tbl_m_user u ON o.id_tmu = u.id_tmu
                JOIN tbl_m_paymentmethod pm ON o.id_tmpm = pm.id_tmpm
                WHERE DATE(o.dateOrder_tmo) = ? 
                ORDER BY o.dateOrder_tmo DESC";
        break;
}

// Menyiapkan statement SQL menggunakan prepared statement untuk keamanan
$stmt = $conn->prepare($sql);

// Jika filter 'today' digunakan, binding parameter
if ($filter === 'today') {
    $stmt->bind_param("s", $dateStart); // Binding parameter untuk tanggal
}

// Eksekusi query
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/logout-confirmation.js"></script>

    <style>
        /* General Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background-color: #0e004a;
            color: #fff;
        }

        thead th {
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f4f4f2;
        }

        tbody tr:hover {
            background-color: #f1e7d3;
        }

        td {
            padding: 12px 15px;
            text-align: left;
            color: #000000;
        }

        th {
            padding: 12px 15px;
            text-align: left;
            color: #fff;
        }

        /* Status and Payment Method */
        tbody td:nth-child(6) {
            font-weight: bold;
            text-align: center;
        }

        tbody td:nth-child(6):contains("Close") {
            color: #27ae60;
        }

        tbody td:nth-child(6):contains("Open") {
            color: #c0392b;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="../cashier/cashier_home.php">
            <div class="sidebar-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../cashier/cashier_kelola_order.php">
            <div class="sidebar-icon active" data-icon="orders"><i class="ph ph-note"></i></div>
        </a>
        <a href="../cashier/cashier_profile.php">
            <div class="sidebar-icon" data-icon="users"><i class="ph ph-user"></i></div>
        </a>
        <a href="javascript:void(0)" id="logout-btn" class="logout-btn">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </aside>

    <!-- Navbar untuk Mobile -->
    <div class="mobile-navbar">
        <a href="../cashier/cashier_home.php">
            <div class="sidebar-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../cashier/cashier_kelola_order.php">
            <div class="sidebar-icon active" data-icon="orders"><i class="ph ph-note"></i></div>
        </a>
        <a href="../cashier/cashier_profile.php">
            <div class="sidebar-icon" data-icon="users"><i class="ph ph-user"></i></div>
        </a>
        <a href="javascript:void(0)" id="logout-btn" class="logout-btn">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </div>

    <!-- Dashboard Content -->
    <div class="container">
        <main id="users" class="content-section active">
            <header>
                <div class="top-bar-users">
                    <div class="users-header">
                        <h1>Data Pesanan</h1>
                        <?php echo date('jS F Y'); ?>
                    </div>
                    <img src="../uploads/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <hr class="divider">

                <!-- Filter Form -->
                <form method="GET" action="">
                    <label for="filter">Filter:</label>
                    <select name="filter" id="filter">
                        <option value="today" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'today') echo 'selected'; ?>>Hari Ini</option>
                        <option value="week" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'week') echo 'selected'; ?>>Minggu Ini</option>
                        <option value="month" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'month') echo 'selected'; ?>>Bulan Ini</option>
                    </select>
                    <button type="submit">Terapkan Filter</button>
                </form>
            </header>

            <!-- Tabel Data -->
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Tanggal</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Metode Pembayaran</th>
                        <th>Tipe Order</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ($result->num_rows > 0) {
                            // Output data setiap row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["id_tmo"] . "</td>
                                        <td>" . $row["customerName_tmo"] . "</td>
                                        <td>" . date('d-m-Y', strtotime($row["dateOrder_tmo"])) . "</td>
                                        <td>Rp. " . number_format($row["totalOrder_tmo"], 0, ',', '.') . "</td>
                                        <td>" . $row["statusOrder_tmo"] . "</td>
                                        <td>" . $row["paymentMethod_tmpm"] . "</td>
                                        <td>" . $row["typeOrder_tmo"] . "</td>
                                        <td><a href='../view/order_details.php?id=" . $row["id_tmo"] . "'>Detail</a></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>Tidak ada data untuk ditampilkan</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
