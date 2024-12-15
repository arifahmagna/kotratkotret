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
    $stmt->bind_param("s", $email); // Bind email parameter
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

            // Arahkan ke halaman berdasarkan peran (role)
            if ($_SESSION['role'] == 'admin') {
                header("Location: ../admin/admin_home.php");  // Admin masuk ke admin_home.php
            } elseif ($_SESSION['role'] == 'cashier') {
                header("Location: ../cashier/cashier_home.php");  // Cashier masuk ke cashier_home.php
            } else {
                header("Location: ../costumer/cust_home.php");  // User masuk ke user_home.php
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Mengatur box-sizing agar padding dan border dihitung dalam lebar dan tinggi elemen */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #1a1b23;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #2a2c36;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px; /* Maksimal lebar container */
            display: flex;
            flex-direction: column;
            align-items: center; /* Center konten secara horizontal */
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
            width: 100%; /* Pastikan label berada di atas input */
        }

        input[type="email"],
        input[type="password"] {
            width: 100%; /* Input mengambil lebar penuh container */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            font-size: 14px;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #5c6bc0;
        }

        button[type="submit"] {
            width: 100%; /* Tombol mengambil lebar penuh container */
            padding: 12px;
            background-color: #5c6bc0;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #3f51b5;
        }

        .error {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            width: 100%; /* Agar error message juga lebar penuh */
        }

        /* Styling untuk link forgot password dan register yang konsisten */
        .register-link,
        .forgot-password-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .register-link a,
        .forgot-password-link a {
            color: #5c6bc0;
            text-decoration: none;
        }

        .register-link a:hover,
        .forgot-password-link a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    
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

    <div class="register-link">
        <span>Belum punya akun? </span><a href="register.php">Daftar sekarang</a>
    </div>
    <div class="forgot-password-link">
    <span>Lupa Kata Sandi? </span><a href="forgot_password.php">Reset sekarang</a>
    </div>
</div>

</body>
</html>
