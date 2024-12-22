<?php
include('../php/config.php');

// Cek apakah sudah login, jika tidak redirect ke login page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika session tidak ada atau role bukan 'cashier', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id']; // Anggap sudah ada session user_id setelah login
$sql = "SELECT id_tmu, email_tmu, name_tmu, role_tmu, password_tmu, animal_tmu FROM tbl_m_user WHERE id_tmu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Ambil data pengguna
    $user = $result->fetch_assoc();
} else {
    echo "User tidak ditemukan";
}

$stmt->close();
$conn->close();
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
</head>
<style>
        .actions {
            display: flex;               /* Menggunakan Flexbox */
            justify-content: space-between;  /* Menjaga tombol di sisi kiri dan kanan */
            align-items: center;         /* Menyusun tombol secara vertikal di tengah */
            width: 100%;                 /* Membuat kontainer selebar 100% */
            padding: 10px;               /* Memberikan padding agar tidak terlalu rapat dengan tepi */
        }

        /* Styling untuk tombol kembali */
        .back-btn {
            font-size: 32px;             /* Ukuran ikon lebih besar */
            color: #fff;                 /* Warna ikon putih */
            text-decoration: none;       /* Menghapus garis bawah */
            transition: transform 0.3s ease, color 0.3s ease; /* Efek transisi */
        }

        .back-btn:hover {
            color: red;              /* Warna hijau saat hover */
            transform: scale(1.2);       /* Membesarkan ikon saat hover */
        }
        a{
            text-decoration: none;
        }

</style>
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
                <div class="top-bar-users">
                    <div class="users-header">
                        <h1>Admin Profile</h1>
                        <?php
                            // Output the date in "Month Year" and "Day of the Month, Time" format
                            echo date('jS F Y');
                        ?>
                    </div>
                    <img src="../uploads/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <hr class="divider">
            </header>
            <!-- Profile Section -->
            <section class="profile-section">
                <div class="profile-info">
                    <div class="info-item">
                    <label>Name</label>
                        <input type="text" id="customer-name" value="<?php echo htmlspecialchars($user['name_tmu']); ?>" readonly class="profile-input">
                    </div>
                    <div class="info-item">
                    <label>Email</label>
                        <input type="email" id="customer-email" value="<?php echo htmlspecialchars($user['email_tmu']); ?>" readonly class="profile-input">
                    </div>
                    <div class="info-item">
                    <label>Password</label>
                        <input type="password" id="customer-password" value="********" readonly class="profile-input"> <!-- Masked password -->
                    </div>
                </div>
            </section>
            <!-- Edit and Logout Actions -->
            <div class="actions">
                <a href="../update/update_profile_admin.php" class="btn"><button class="btn" id="edit-profile">Edit Profile</button></a>
                <a href="../admin/admin_settings.php" class="back-btn"><i class="ph ph-backspace"></i></a>
            </div>
        </main>
    </div>
</body>
</html>
