<?php
include('../php/config.php');

// Cek apakah sudah login, jika tidak redirect ke login page
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
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

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_tmu = $_POST['name_tmu'];
    $email_tmu = $_POST['email_tmu'];
    $password_tmu = $_POST['password_tmu'];
    $animal_tmu = $_POST['animal_tmu'];

    // Cek apakah password baru diisi
    if (!empty($password_tmu)) {
        // Jika password baru dimasukkan, kita verifikasi dengan password lama yang ada di database
        if (password_verify($password_tmu, $user['password_tmu'])) {
            // Jika password baru sama dengan password lama (setelah diverifikasi), tidak perlu di-hash lagi
            $hashed_password = $user['password_tmu'];  // Gunakan password yang lama
        } else {
            // Jika password baru berbeda, hash password baru
            $hashed_password = password_hash($password_tmu, PASSWORD_DEFAULT);
        }
    } else {
        // Jika password tidak diubah, gunakan password lama
        $hashed_password = $user['password_tmu'];
    }

    // Update data pengguna di database
    $update_sql = "UPDATE tbl_m_user SET name_tmu = ?, email_tmu = ?, password_tmu = ?, animal_tmu = ? WHERE id_tmu = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $name_tmu, $email_tmu, $hashed_password, $animal_tmu, $user_id);

    if ($stmt->execute()) {
        // Update berhasil, tampilkan SweetAlert
        echo "<script>
                alert('Profile updated successfully.');
                window.location.href = '../cashier/cashier_profile.php'; // Redirect to same page to see updated data
              </script>";
    } else {
        // Error saat update
        echo "<script>
                alert('Error updating profile.');
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>
