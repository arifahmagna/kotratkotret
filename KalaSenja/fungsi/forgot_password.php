<?php
session_start();
include('../php/config.php'); // Pastikan koneksi ke database telah benar

// Cek apakah form sudah disubmit
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $email = $_POST['email'];
    $imaginary_animal = $_POST['animal'];

    // Query untuk mencari pengguna berdasarkan email dan hewan imajiner
    $sql = "SELECT id_tmu, email_tmu, name_tmu, animal_tmu FROM tbl_m_user WHERE email_tmu = ? AND animal_tmu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $imaginary_animal);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah email dan hewan imajiner cocok
    if ($result->num_rows > 0) {
        // Jika cocok, tampilkan form reset password
        $_SESSION['email'] = $email; // Simpan email untuk proses reset
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "Email atau hewan imajiner tidak cocok!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        input[type="email"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            font-size: 14px;
        }

        input[type="email"]:focus,
        input[type="text"]:focus {
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
        <h2>Forgot Password</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="animal">Hewan Imajiner:</label>
            <input type="text" id="animal" name="animal" required>

            <button type="submit" name="submit">Submit</button>
        </form>

        <div class="login-link">
            <span>Sudah ingat password? </span><a href="login.php">Login sekarang</a>
        </div>
    </div>
</body>
</html>
