<?php
include('../php/config.php'); // Koneksi ke database

// Query untuk mengambil data metode pembayaran
$query = "SELECT * FROM tbl_m_paymentmethod"; // Sesuaikan dengan nama tabel metode pembayaran Anda
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Metode Pembayaran - Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery CDN (untuk AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Styling untuk Body */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1b23;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        /* Header dan Title */
        .top-bar-payment {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #2c2f3b;
        }

        .payment-header h1 {
            margin: 0;
            color: #e5e5e5;
        }

        /* Profil dan Navbar */
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Tombol Add Payment Method */
        .add-payment-btn {
            margin: 20px 0;
            text-align: right;
        }

        .btn-add-payment {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 30px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .btn-add-payment:hover {
            background-color: #45a049;
        }

        /* Styling untuk Table */
        .payment-list {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #444;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        table td {
            background-color: #2c2f3b;
            color: #fff;
        }

        /* Styling untuk Action Button */
        .btn-edit, .btn-delete {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-edit {
            background-color: #3498db;
            color: #fff;
            border: none;
        }

        .btn-edit:hover {
            background-color: #2980b9;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: #fff;
            border: none;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        /* Responsive Design untuk Mobile */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .add-payment-btn {
                text-align: center;
            }

            .btn-add-payment {
                width: 45px;
                height: 45px;
                font-size: 25px;
            }

            table th, table td {
                padding: 8px;
                font-size: 12px;
            }

            table {
                font-size: 12px;
            }
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="../admin/admin_home.php">
            <div class="sidebar-icon active " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.php">
            <div class="sidebar-icon" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_settings.php">
            <div class="sidebar-icon " data-icon="users"><i class="ph ph-gear-six"></i></div>
        </a>
        <a href="../fungsi/logout.php">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </aside>

    <!-- Navbar untuk Mobile -->

    <div class="mobile-navbar">
        <a href="../admin/admin_home.php">
            <div class="mobile-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.php">
            <div class="mobile-icon active" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_settings.php">
            <div class="mobile-icon" data-icon="users"><i class="ph ph-gear-six"></i></div>
        </a>
        <a href="../fungsi/logout.php">
            <div class="mobile-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </div>

    <!-- Dashboard Content -->
    <div class="container">
        <main id="payment-methods" class="content-section active">
            <header>
                <div class="top-bar-payment">
                    <div class="payment-header">
                        <h1>Manajemen Metode Pembayaran</h1>
                        <?php
                            // Output the date in "Month Year" and "Day of the Month, Time" format
                            echo date('jS F Y');
                        ?>
                    </div>
                    <img src="../images/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <hr class="divider">
            </header>
            
            <!-- Add Payment Method Button -->
            <div class="add-payment-btn">
                <a href="../add/add_payment_methode.php"><button class="btn-add-payment">+</button></a>
            </div>

            <!-- Payment Methods Table -->
            <section class="payment-list">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Metode Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id_tmpm']; ?></td>
                                <td><?php echo $row['paymentMethod_tmpm']; ?></td>
                                <td>
                                    <a href="../update/update_payment_methode.php?id=<?php echo $row['id_tmpm']; ?>"><button class="btn-edit">Edit</button></a>
                                    <a href="../delete/delete_payment_methode.php?id=<?php echo $row['id_tmpm']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus?')"><button class="btn-delete">Hapus</button></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="../admin/admin_settings.php"><i class="ph ph-backspace"></i></a>
            </section>
        </main>
    </div>
</body>
</html>
