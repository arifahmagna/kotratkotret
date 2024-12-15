<?php
// Include database connection
include('../php/config.php');

// Query to fetch data from tbl_m_order and join with tbl_t_order
$sql = "SELECT o.id_tmo, o.dateOrder_tmo, u.name_tmu, o.totalOrder_tmo, o.statusOrder_tmo, pm.paymentMethod_tmpm, o.typeOrder_tmo
        FROM tbl_m_order o
        JOIN tbl_m_user u ON o.id_tmu = u.id_tmu
        JOIN tbl_m_paymentmethod pm ON o.id_tmpm = pm.id_tmpm
        ORDER BY o.dateOrder_tmo DESC"; // You can customize the order as per your requirement


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
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
            background-color: #0e004a; /* Coffee-themed header color */
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
            background-color: #f1e7d3; /* Light beige for hover effect */
        }

        td{
            padding: 12px 15px;
            text-align: left;
            color: #000000; /* Coffee-themed text color */
        }

        th {
            padding: 12px 15px;
            text-align: left;
            color: #fff; /* Coffee-themed text color */
        }
        /* Status and Payment Method */
        tbody td:nth-child(6) {
            font-weight: bold;
            text-align: center;
        }

        tbody td:nth-child(6):contains("Close") {
            color: #27ae60; /* Green for "Lunas" */
        }

        tbody td:nth-child(6):contains("Open") {
            color: #c0392b; /* Red for "Belum Lunas" */
        }

        a{
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
        <a href="../fungsi/logout.php">
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
        <a href="../fungsi/logout.php">
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
                        <?php
                            // Output the date in "Month Year" and "Day of the Month, Time" format
                            echo date('jS F Y');
                        ?>
                    </div>
                    <img src="../images/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <hr class="divider">
            </header>
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
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["id_tmo"] . "</td>
                                        <td>" . $row["name_tmu"] . "</td> <!-- Changed from username to name_tmu -->
                                        <td>" . date('d-m-Y', strtotime($row["dateOrder_tmo"])) . "</td>
                                        <td>Rp " . number_format($row["totalOrder_tmo"], 2, ',', '.') . "</td>
                                        <td class='" . ($row["statusOrder_tmo"] == 'Close' ? 'status-close' : 'status-open') . "'>" . $row["statusOrder_tmo"] . "</td>
                                        <td>" . $row["paymentMethod_tmpm"] . "</td>
                                        <td>" . $row["typeOrder_tmo"] . "</td>
                                        <td><a href='../view/order_details.php?id=" . $row["id_tmo"] . "'>View Details</a></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No orders found</td></tr>";
                        }
                        ?>
                    </tbody>
            </table>
        </main>
    </div>
</body>
</html>
