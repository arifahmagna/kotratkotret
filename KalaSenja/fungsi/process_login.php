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

        // Cek apakah password cocok menggunakan password_verify
        if (password_verify($password, $user['password_tmu'])) {
            // Jika password cocok, simpan informasi pengguna di sesi
            $_SESSION['user_id'] = $user['id_tmu'];
            $_SESSION['email'] = $user['email_tmu'];
            $_SESSION['name'] = $user['name_tmu'];
            $_SESSION['role'] = $user['role_tmu'];

            // Redirect berdasarkan role pengguna
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin_home.php");  // Redirect ke halaman Admin
            } elseif ($_SESSION['role'] == 'cashier') {
                header("Location: cashier_home.php");  // Redirect ke halaman Cashier
            } elseif ($_SESSION['role'] == 'user') {
                header("Location: user_home.php");  // Redirect ke halaman User
            } else {
                $error = "Role tidak dikenali!";
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
