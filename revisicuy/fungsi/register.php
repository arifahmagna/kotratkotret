<?php
if (isset($_POST['register'])) {
    include('config.php');  // Pastikan koneksi ke database telah benar

    // Ambil data dari form
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $role = $_POST['role'];  // Misalnya 'user' atau 'admin'

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan data pengguna baru
    $sql = "INSERT INTO tbl_m_user (email_tmu, name_tmu, password_tmu, role_tmu) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $email, $name, $hashed_password, $role);
    $stmt->execute();

    // Redirect setelah registrasi berhasil
    header("Location: login.php");
    exit();
}
?>
