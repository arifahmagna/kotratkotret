<?php
if (isset($_POST['register'])) {
    include('../php/config.php');  // Pastikan koneksi ke database telah benar

    // Ambil data dari form
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $securityQuestionAnswer = $_POST['securityAnswer'];  // Jawaban dari pertanyaan keamanan
    $role = 'user';  // Role otomatis 'user'

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan data pengguna baru
    $sql = "INSERT INTO tbl_m_user (email_tmu, name_tmu, password_tmu, animal_tmu, role_tmu) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $email, $name, $hashed_password, $securityQuestionAnswer, $role);
    $stmt->execute();

    // Redirect setelah registrasi berhasil
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* Style untuk halaman registrasi */
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

        .register-container {
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

        .register-container h2 {
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
        input[type="password"],
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
        input[type="password"]:focus,
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

<div class="register-container">
    <h2>Register</h2>
    
    <!-- Tampilkan pesan error jika ada -->
    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form Register -->
    <form action="" method="POST">
        <label for="name">Nama Lengkap:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="securityAnswer">Hewan Imajiner (Pertanyaan Keamanan):</label>
        <input type="text" id="securityAnswer" name="securityAnswer" required>

        <button type="submit" name="register">Register</button>
    </form>

    <div class="login-link">
        <span>Sudah punya akun? </span><a href="login.php">Login sekarang</a>
    </div>
</div>

</body>
</html>
