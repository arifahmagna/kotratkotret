<?php
include('../php/config.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika session tidak ada atau role bukan 'admin', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/logout-confirmation.js"></script>
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
        .top-bar-settings {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #2c2f3b;
        }

        .settings-header h1 {
            margin: 0;
            color: #e5e5e5;
        }

        /* Profil dan Navbar */
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
.container-button {
    display: flex;
    justify-content: space-around;
    gap: 20px;
    margin: 20px;
}
.container-button a {
    text-decoration: none;
    display: block;
    background-color: #2f3740;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 150px;  /* Adjust width as needed */
    text-align: center;
    transition: all 0.3s ease;
}
.container-button a .user-management,
.container-button a .payment_method,
.container-button a .profile {
    font-size: 2em;
    color: #fff;
    margin-bottom: 10px;
}

/* Hover Effect untuk Button */
.container-button a:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    background-color: #3d4754;
}
        a{
        text-decoration: none;
    }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="../admin/admin_home.php">
            <div class="sidebar-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.php">
            <div class="sidebar-icon" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_settings.php">
            <div class="sidebar-icon active" data-icon="users"><i class="ph ph-gear-six"></i></div>
        </a>
        <a href="javascript:void(0)" id="logout-btn" class="logout-btn">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </aside>

    <!-- Navbar untuk Mobile -->

    <div class="mobile-navbar">
        <a href="../admin/admin_home.php">
            <div class="mobile-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.php">
            <div class="mobile-icon" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_settings.php">
            <div class="mobile-icon active" data-icon="users"><i class="ph ph-gear-six"></i></div>
        </a>
        <a href="javascript:void(0)" id="logout-btn" class="logout-btn">
            <div class="mobile-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </div>

    <!-- Dashboard Content -->
    <div class="container">
        <main id="users" class="content-section active">
            <header>
                <div class="top-bar-settings">
                    <div class="settings-header">
                        <h1>Settings</h1>
                        <?php
                            // Output the date in "Month Year" and "Day of the Month, Time" format
                            echo date('jS F Y');
                        ?>
                    </div>
                    <img src="../uploads/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <hr class="divider">
            </header>
            <div class="container-button">
                <a href="../admin/admin_user_management.php">
                    <div class="user-management">
                        <i class="ph ph-users"></i>
                        <p style="font-size: 1rem;">User Management</p>
                    </div>
                </a>
                <a href="../admin/admin_payment_methode.php">
                    <div class="payment_method">
                        <i class="ph ph-credit-card"></i>
                        <p style="font-size: 1rem;">Payment Method</p>
                    </div>
                </a>
                <a href="../admin/admin_profile.php">
                    <div class="profile">
                        <i class="ph ph-user"></i>
                        <p style="font-size: 1rem;">Admin Profile</p>
                    </div>
                </a>
            </div>
        </main>
    </div>
</body>
</html>