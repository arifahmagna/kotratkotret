<?php
include('../php/config.php');

// Cek apakah ID diterima dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mengambil data pengguna berdasarkan ID
    $sql = "SELECT * FROM tbl_m_user WHERE id_tmu = $id"; // Ganti 'tbl_m_user' dengan nama tabel Anda
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}

// Proses update data pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update data pengguna ke database
    $update_sql = "UPDATE tbl_m_user SET name_tmu='$name', email_tmu='$email', role_tmu='$role' WHERE id_tmu=$id"; 
    if ($conn->query($update_sql) === TRUE) {
        // Redirect menggunakan header setelah berhasil update
        header("Location: ../admin/admin_user_management.php");
        exit(); // Pastikan eksekusi PHP berhenti setelah redirect
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Tambahkan SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h2>Edit User</h2>
    <form method="POST" onsubmit="return confirmUpdate(event)">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $user['name_tmu']; ?>" required><br><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $user['email_tmu']; ?>" required><br><br>
        <label>Role:</label>
        <select name="role">
            <option value="Admin" <?php echo ($user['role_tmu'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="User" <?php echo ($user['role_tmu'] == 'User') ? 'selected' : ''; ?>>User</option>
        </select><br><br>
        <button type="submit">Update</button>
    </form>

    <script>
        // Menampilkan SweetAlert konfirmasi sebelum mengirimkan form
        function confirmUpdate(event) {
            event.preventDefault(); // Mencegah pengiriman form langsung

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to update this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user memilih "Yes", kirimkan form
                    event.target.submit();
                }
            });
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
