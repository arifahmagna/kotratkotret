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
    <style>
         /* Styling untuk Body */
         body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1b23; /* Warna latar belakang gelap */
            color: #fff; /* Warna teks putih agar kontras dengan latar belakang gelap */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background-color: #2c2f3b; /* Warna gelap yang lebih terang untuk form */
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header h2 {
            font-size: 24px;
            color: #e5e5e5; /* Teks header putih terang */
            font-weight: bold;
            margin: 0;
            text-align:center;
        }

        .label {
            font-size: 14px;
            color: #bbb; /* Warna label abu-abu terang */
            display: block;
            margin-bottom: 5px;
        }

        .input,
        .select {
            width: 100%;
            padding: 10px;
            border: 1px solid #444; /* Border abu-abu gelap */
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #3a3e49; /* Background input gelap */
            color: #fff; /* Warna teks putih untuk input */
            transition: border-color 0.3s ease;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4caf50; /* Warna hijau untuk tombol */
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049; /* Efek hover hijau lebih gelap */
        }


        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            header h1 {
                font-size: 20px;
            }

            .input,
            .select,
            .button {
                font-size: 16px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
    <h2>Edit User</h2>
    <form method="POST" onsubmit="return confirmUpdate(event)">
        <label class="label">Name:</label>
        <input type="text" name="name" class="input" value="<?php echo $user['name_tmu']; ?>" required><br><br>

        <label class="label">Email:</label>
        <input type="email" name="email" class="input" value="<?php echo $user['email_tmu']; ?>" required><br><br>

        <label class="label">Role:</label>
        <select name="role" class="select">
            <option value="Admin" <?php echo ($user['role_tmu'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="User" <?php echo ($user['role_tmu'] == 'User') ? 'selected' : ''; ?>>User</option>
        </select><br><br>
        
        <button type="submit">Update</button>
    </form>
    </div>

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
