<?php 
include('../php/config.php'); // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paymentMethod = mysqli_real_escape_string($conn, $_POST['paymentMethod']);

    // Cek apakah nama metode pembayaran sudah ada
    $checkQuery = "SELECT * FROM tbl_m_paymentmethod WHERE paymentMethod_tmpm = '$paymentMethod'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Metode pembayaran sudah ada!');</script>";
    } else {
        // Query untuk menambah metode pembayaran
        $query = "INSERT INTO tbl_m_paymentmethod (paymentMethod_tmpm) VALUES ('$paymentMethod')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Metode pembayaran berhasil ditambahkan!'); window.location.href='../admin/admin_payment_methode.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan metode pembayaran.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Metode Pembayaran - Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Global styles */
        body {
            background-color: #1a1b23;
            font-family: Arial, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        /* Container styling */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        /* Form styling */
        form {
            background-color: #2c2f3b;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            color: #333;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #28a745;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Metode Pembayaran</h1>
        <form action="" method="POST">
            <label for="paymentMethod">Nama Metode Pembayaran</label>
            <input type="text" id="paymentMethod" name="paymentMethod" required>

            <button type="submit">Tambah</button>
            <a href="../admin/admin_payment_methode.php">Kembali</a>
        </form>
    </div>
</body>
</html>
