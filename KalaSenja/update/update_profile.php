<?php
// Include backend logic
include('../php/config.php');

// Cek apakah sudah login, jika tidak redirect ke login page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cashier') {
    // Jika session tidak ada atau role bukan 'admin', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'];
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
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update - Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/logout-confirmation.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<style>
    a{
        text-decoration: none;
    }
</style>
<body>
    <!-- Sidebar -->

    <aside class="sidebar">
        <a href="../cashier/cashier_home.php">
            <div class="sidebar-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../cashier/cashier_kelola_order.php">
            <div class="sidebar-icon" data-icon="orders"><i class="ph ph-note"></i></div>
        </a>
        <a href="../cashier/cashier_profile.php">
            <div class="sidebar-icon active" data-icon="users"><i class="ph ph-user"></i></div>
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
            <div class="sidebar-icon" data-icon="orders"><i class="ph ph-note"></i></div>
        </a>
        <a href="../cashier/cashier_profile.php">
            <div class="sidebar-icon active" data-icon="users"><i class="ph ph-user"></i></div>
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
                        <h1>Cashier Profile</h1>
                    </div>
                    <img src="../uploads/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <hr class="divider">
            </header>
            <!-- Profile Section -->
            <section class="profile-section">
                <div class="profile-info">
                    <form action="../fungsi/fungsi_update_profile.php" method="POST">
                        <div class="info-item">
                            <label for="name_tmu">Name</label>
                            <input type="text" id="customer-name" name="name_tmu" value="<?php echo htmlspecialchars($user['name_tmu']); ?>" class="profile-input" required>
                        </div>
                        <div class="info-item">
                            <label for="email_tmu">Email</label>
                            <input type="email" id="customer-email" name="email_tmu" value="<?php echo htmlspecialchars($user['email_tmu']); ?>" class="profile-input" required>
                        </div>
                        <div class="info-item">
                            <label for="password_tmu">Password</label>
                            <input type="password" id="customer-password" name="password_tmu" placeholder="Verifikasi Password" class="profile-input">
                        </div>
                        <div class="info-item">
                            <label for="animal_tmu">Jika anda memiliki hewan peliharaan imajiner, apa nama yang akan anda berikan?</label>
                            <input type="text" id="customer-animal" name="animal_tmu" value="<?php echo htmlspecialchars($user['animal_tmu'] ?? ''); ?>" required class="profile-input">
                        </div>
                        <div class="container-btn" style="display: flex; flex-direction: row; justify-content: space-between;">
                            <div class="info-item">
                                <input type="submit" value="Save Changes" class="btn">
                            </div>
                            <div class="info-item">
                                <a href="../cashier/cashier_profile.php" class="btn" style="text-decoration: none; padding: 8px 20px; cursor: pointer; background-color: white;">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>

</body>
</html>
