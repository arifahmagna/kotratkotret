<?php
session_start();
include('../php/config.php');

// Cek apakah email ada di sesi
if (!isset($_SESSION['email'])) {
    header("Location: forgot_password.php");
    exit();
}

// Cek apakah form reset password sudah disubmit
if (isset($_POST['reset'])) {
    // Ambil data dari form
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah password baru dan konfirmasi password cocok
    if ($new_password === $confirm_password) {
        $email = $_SESSION['email'];
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Query untuk memperbarui password pengguna
        $sql = "UPDATE tbl_m_user SET password_tmu = ? WHERE email_tmu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        // Hapus email dari sesi setelah reset berhasil
        unset($_SESSION['email']);

        // Redirect ke halaman login
        header("Location: login.php");
        exit();
    } else {
        $error = "Password dan konfirmasi password tidak cocok!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
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

        .container {
            background-color: #2a2c36;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
            width: 100%;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            font-size: 14px;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #5c6bc0;
        }

        button[type="submit"] {
            width: 100%;
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
            width: 100%;
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            color: #5c6bc0;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="new_password">Password Baru:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit" name="reset">Reset Password</button>
        </form>

        <div class="login-link">
            <span>Sudah punya akun? </span><a href="login.php">Login sekarang</a>
        </div>
    </div>
</body>
</html>
