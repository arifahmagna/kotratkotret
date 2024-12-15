
<?php
include('../php/config.php');

// Cek apakah sudah login, jika tidak redirect ke login page
session_start(); // Memulai session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    // Jika session tidak ada atau role bukan 'cashier', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id']; // Anggap sudah ada session user_id setelah login
$sql = "SELECT id_tmu, email_tmu, name_tmu, role_tmu FROM tbl_m_user WHERE id_tmu = ?";
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
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-icon" data-icon="home"><i class="ph ph-house"></i></div>
        <div class="sidebar-icon" data-icon="payments"><i class="ph ph-clock-counter-clockwise"></i></div>
        <div class="sidebar-icon active" data-icon="users"><i class="ph ph-user"></i></div>
        <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
    </aside>

    <!-- Navbar untuk Mobile -->
    <div class="mobile-navbar">
        <div class="mobile-icon" data-icon="home"><i class="ph ph-house"></i></div>
        <div class="mobile-icon" data-icon="payments"><i class="ph ph-clock-counter-clockwise"></i></div>
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
                        <?php
                            // Output the date in "Month Year" and "Day of the Month, Time" format
                            echo date('jS F Y');
                        ?>
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
                        <input type="tel" id="customer-phone" value="Not Available" readonly class="profile-input"> <!-- Menambahkan phone jika ada -->
                    </div>
                    <div class="info-item">
                        <input type="text" id="customer-member-since" value="Joined: January 2023" readonly class="profile-input"> <!-- Menambahkan informasi member sejak -->
                    </div>
                </div>
            </section>
            <!-- Edit and Logout Actions -->
            <div class="actions">
                <button class="btn" id="edit-profile">Edit Profile</button>
            </div>
        </main>
    </div>

</body>
</html>
