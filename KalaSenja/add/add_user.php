<?php
include('../php/config.php');

// Proses penyimpanan data user baru
if (isset($_POST['submit'])) {
    $name_tmu = $_POST['name_tmu'];
    $email_tmu = $_POST['email_tmu'];
    $password_tmu = password_hash($_POST['password_tmu'], PASSWORD_DEFAULT); // Enkripsi password
    $role_tmu = $_POST['role_tmu'];

    // Cek apakah email sudah ada di database
    $check_email_sql = "SELECT * FROM tbl_m_user WHERE email_tmu = '$email_tmu'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        // Jika email sudah ada, tampilkan pesan kesalahan
        $error_message = "Error: Email '$email_tmu' is already in use. Please choose a different email.";
    } else {
        // Query untuk menambahkan user baru ke database
        $sql = "INSERT INTO tbl_m_user (name_tmu, email_tmu, password_tmu, role_tmu) 
                VALUES ('$name_tmu', '$email_tmu', '$password_tmu', '$role_tmu')";

        if ($conn->query($sql) === TRUE) {
            // Jika berhasil, redirect ke halaman user management
            header("Location: ../admin/admin_user_management.php");
            exit();
        } else {
            // Jika terjadi error, tampilkan pesan
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
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

        header h1 {
            font-size: 24px;
            color: #e5e5e5; /* Teks header putih terang */
            font-weight: bold;
            margin: 0;
        }

        /* Styling untuk Error Message */
        .error-message {
            background-color: #e74c3c; /* Merah terang untuk error */
            color: #fff;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 14px;
            color: #bbb; /* Warna label abu-abu terang */
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
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

        .form-group input:focus,
        .form-group select:focus {
            border-color: #3498db; /* Warna biru terang saat fokus */
            outline: none;
        }

        /* Styling untuk Tombol */
        .form-group button {
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

        .form-group button:hover {
            background-color: #45a049; /* Efek hover hijau lebih gelap */
        }

        /* Additional Responsive Design */
        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            header h1 {
                font-size: 20px;
            }

            .form-group input,
            .form-group select,
            .form-group button {
                font-size: 16px;
            }
        }

    </style>
</head>
<body>

    <div class="container">
        <main id="add-user" class="content-section active">
            <header>
                <h1>Add New User</h1>
            </header>

            <!-- Form untuk menambahkan user -->
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>
            
            <form action="add_user.php" method="POST">
                <div class="form-group">
                    <label for="name_tmu">Name</label>
                    <input type="text" id="name_tmu" name="name_tmu" required>
                </div>

                <div class="form-group">
                    <label for="email_tmu">Email</label>
                    <input type="email" id="email_tmu" name="email_tmu" required>
                </div>

                <div class="form-group">
                    <label for="password_tmu">Password</label>
                    <input type="password" id="password_tmu" name="password_tmu" required>
                </div>

                <div class="form-group">
                    <label for="role_tmu">Role</label>
                    <select id="role_tmu" name="role_tmu" required>
                        <option value="admin">admin</option>
                        <option value="cashier">cashier</option>
                        <option value="user">user</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" name="submit">Add User</button>
                </div>
            </form>
        </main>
    </div>

</body>
</html>
