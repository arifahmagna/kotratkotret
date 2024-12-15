<?php
// Include backend logic
include('../php/config.php');

// Cek apakah sudah login, jika tidak redirect ke login page
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'];
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-icon" data-icon="home"><i class="ph ph-house"></i></div>
        <div class="sidebar-icon" data-icon="orders"><i class="ph ph-note"></i></div>
        <div class="sidebar-icon active" data-icon="users"><i class="ph ph-user"></i></div>
        <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
    </aside>

    <!-- Navbar untuk Mobile -->
    <div class="mobile-navbar">
        <div class="mobile-icon" data-icon="home"><i class="ph ph-house"></i></div>
        <div class="mobile-icon" data-icon="orders"><i class="ph ph-note"></i></div>
        <div class="mobile-icon active" data-icon="users"><i class="ph ph-user"></i></div>
        <div class="mobile-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
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
                            <input type="password" id="customer-password" name="password_tmu" value="" placeholder="Enter new password" class="profile-input">
                        </div>
                        <div class="info-item">
                            <input type="submit" value="Save Changes" class="btn">
                        </div>
                    </form>
                </div>
            </section>
            <!-- Edit and Logout Actions -->
            <div class="actions">
                <a href="../cashier/cashier_profile.php" class="btn">Cancel</a>
            </div>
        </main>
    </div>

</body>
</html>
