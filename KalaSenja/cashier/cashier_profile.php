<?php
include('../php/config.php');

// Cek apakah sudah login, jika tidak redirect ke login page
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id']; // Anggap sudah ada session user_id setelah login
$sql = "SELECT id_tmu, email_tmu, name_tmu, role_tmu, password_tmu FROM tbl_m_user WHERE id_tmu = ?";
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
                        <h1>Cashier Profile</h1>
                    </div>
                    <img src="../images/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <hr class="divider">
            </header>
            <!-- Profile Section -->
            <section class="profile-section">
                <div class="profile-info">
                    <div class="info-item">
                        <input type="text" id="customer-name" value="<?php echo htmlspecialchars($user['name_tmu']); ?>" readonly class="profile-input">
                    </div>
                    <div class="info-item">
                        <input type="email" id="customer-email" value="<?php echo htmlspecialchars($user['email_tmu']); ?>" readonly class="profile-input">
                    </div>
                    <div class="info-item">
                        <input type="password" id="customer-password" value="********" readonly class="profile-input"> <!-- Masked password -->
                    </div>
                </div>
            </section>
            <!-- Edit and Logout Actions -->
            <div class="actions">
                <a href="../update/update_profile.php" class="btn">
                <button class="btn" id="edit-profile">Edit Profile</button>
                </a>
            </div>
        </main>
    </div>

</body>
</html>
