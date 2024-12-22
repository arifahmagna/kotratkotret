<?php
// Include database connection
include('../php/config.php');

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    // Jika session tidak ada atau role bukan 'cashier', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Handle filter logic
$filter_condition = ""; // Default condition is no filter

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    // Modify SQL query based on filter selected
    if ($filter == 'today') {
        // Filter for today's orders
        $filter_condition = " AND DATE(o.dateOrder_tmo) = CURDATE()";
    } elseif ($filter == 'week') {
        // Filter for this week's orders
        $filter_condition = " AND YEARWEEK(o.dateOrder_tmo, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($filter == 'month') {
        // Filter for this month's orders
        $filter_condition = " AND MONTH(o.dateOrder_tmo) = MONTH(CURDATE()) AND YEAR(o.dateOrder_tmo) = YEAR(CURDATE())";
    }
}

// SQL query to fetch orders for the logged-in user with filter condition
$sql = "SELECT o.id_tmo, o.dateOrder_tmo, o.totalOrder_tmo, o.statusOrder_tmo, pm.paymentMethod_tmpm, o.typeOrder_tmo
        FROM tbl_m_order o
        JOIN tbl_m_user u ON o.id_tmu = u.id_tmu
        JOIN tbl_m_paymentmethod pm ON o.id_tmpm = pm.id_tmpm
        WHERE o.id_tmu = ? $filter_condition
        ORDER BY o.dateOrder_tmo DESC"; // You can customize the order as per your requirement

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the user_id to the query
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kala Senja Coffee - Order History</title>
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

        td {
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
        <a href="../costumer/cust_home.php">
            <div class="sidebar-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../costumer/cust_history.php">
            <div class="sidebar-icon active" data-icon="orders"><i class="ph ph-note"></i></div>
        </a>
        <a href="../costumer/cust_profile.php">
            <div class="sidebar-icon" data-icon="users"><i class="ph ph-user"></i></div>
        </a>
        <a href="javascript:void(0)" id="logout-btn" class="logout-btn">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </aside>

    <!-- Navbar untuk Mobile -->
    <div class="mobile-navbar">
        <a href="../costumer/cust_home.php">
            <div class="sidebar-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../costumer/cust_history.php">
            <div class="sidebar-icon active" data-icon="history"><i class="ph ph-clock-counter-clockwise"></i></i></div>
        </a>
        <a href="../costumer/cust_profile.php">
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
                        <h1>Your Order History</h1>
                        <?php
                            // Output the date in "Month Year" and "Day of the Month, Time" format
                            echo date('jS F Y');
                        ?>
                    </div>
                    <img src="../uploads/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <hr class="divider">
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

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Order Type</th>
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
                                    <td>" . date('d-m-Y', strtotime($row["dateOrder_tmo"])) . "</td>
                                    <td>Rp. " . number_format($row["totalOrder_tmo"], 0, ',', '.') . "</td>
                                    <td class='" . ($row["statusOrder_tmo"] == 'Close' ? 'Close' : 'Open') . "'>" . $row["statusOrder_tmo"] . "</td>
                                    <td>" . $row["paymentMethod_tmpm"] . "</td>
                                    <td>" . $row["typeOrder_tmo"] . "</td>
                                    <td><a href='../view/order_details_cust.php?id=" . $row["id_tmo"] . "'>View Details</a></td>
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
