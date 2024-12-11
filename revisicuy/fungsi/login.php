<?php
session_start();
include('../php/config.php');  // Pastikan koneksi ke database telah benar

// Cek apakah form login sudah disubmit
if (isset($_POST['login'])) {
    // Ambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mencari pengguna berdasarkan email
    $sql = "SELECT id_tmu, email_tmu, name_tmu, password_tmu, role_tmu FROM tbl_m_user WHERE email_tmu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah pengguna ditemukan
    if ($result->num_rows > 0) {
        // Ambil data pengguna
        $user = $result->fetch_assoc();

        // Cek apakah password cocok
        if ($password == $user['password_tmu']) {  // Bandingkan password langsung
            // Jika password cocok, simpan informasi pengguna di sesi
            $_SESSION['user_id'] = $user['id_tmu'];
            $_SESSION['email'] = $user['email_tmu'];
            $_SESSION['name'] = $user['name_tmu'];
            $_SESSION['role'] = $user['role_tmu'];

            // Arahkan ke halaman berdasarkan peran (role)
            if ($_SESSION['role'] == 'admin') {
                header("Location: ../admin/admin_home.php");  // Admin masuk ke admin_home.php
            } elseif ($_SESSION['role'] == 'cashier') {
                header("Location: ../cashier/cashier_home.php");  // Cashier masuk ke cashier_home.php
            } else {
                header("Location: /user_home.php");  // User masuk ke user_home.php
            }
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!-- Tampilkan pesan error jika ada -->
<?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
<?php endif; ?>

<!-- Form Login -->
<form action="" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit" name="login">Login</button>
</form>
