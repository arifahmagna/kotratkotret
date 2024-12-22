<?php 
session_start();
include('../php/config.php'); // Koneksi ke database
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika session tidak ada atau role bukan 'admin', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ambil data metode pembayaran berdasarkan ID
    $query = "SELECT * FROM tbl_m_paymentmethod WHERE id_tmpm = '$id'";
    $result = mysqli_query($conn, $query);
    $paymentMethod = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newPaymentMethod = mysqli_real_escape_string($conn, $_POST['paymentMethod']);
        
        // Update nama metode pembayaran
        $updateQuery = "UPDATE tbl_m_paymentmethod SET paymentMethod_tmpm = '$newPaymentMethod' WHERE id_tmpm = '$id'";
        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>alert('Metode pembayaran berhasil diperbarui!'); window.location.href='../admin/admin_payment_methode.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui metode pembayaran.');</script>";
        }
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='../admin/admin_payment_methode.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Metode Pembayaran - Kala Senja Coffee</title>
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
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Metode Pembayaran</h1>
        <form action="" method="POST">
            <label for="paymentMethod">Nama Metode Pembayaran</label>
            <input type="text" id="paymentMethod" name="paymentMethod" value="<?php echo $paymentMethod['paymentMethod_tmpm']; ?>" required>

            <button type="submit">Simpan Perubahan</button>
            <a href="../admin/admin_payment_methode.php">Kembali</a>
        </form>
    </div>
</body>
</html>
