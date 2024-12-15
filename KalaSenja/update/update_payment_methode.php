<?php
include('../php/config.php');

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data menu berdasarkan ID
    $sql = "SELECT * FROM tbl_m_menu WHERE id_tmm = $id";
    $result = $conn->query($sql);
    $menu = $result->fetch_assoc();
}

// Proses update menu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_name = $_POST['menu'];
    $price = $_POST['price'];
    $desc = $_POST['desc'];

    // Cek apakah ada gambar yang diupload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_name = $_FILES['photo']['name'];
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        
        // Membuat nama file unik untuk gambar
        $photo_new_name = uniqid() . '-' . basename($photo_name);
        
        // Menentukan path gambar
        $photo_path = '../uploads/' . $photo_new_name;

        // Pindahkan gambar ke folder uploads
        if (move_uploaded_file($photo_tmp_name, $photo_path)) {
            $photo = $photo_path; // Path gambar yang disimpan
        } else {
            echo "<script>alert('Gagal mengunggah gambar.');</script>";
            exit;
        }
    } else {
        // Jika tidak ada gambar baru, gunakan gambar lama
        $photo = $menu['photo_tmm'];
    }

    // Update data menu
    $sql = "UPDATE tbl_m_menu SET menu_tmm='$menu_name', price_tmm='$price', desc_tmm='$desc', photo_tmm='$photo' WHERE id_tmm=$id";
    if ($conn->query($sql) === TRUE) {
        // Mengalihkan ke halaman admin_home.php setelah update berhasil
        echo "<script>alert('Menu updated successfully'); window.location.href = '../admin/admin_home.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
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
            <a href="../admin/admin_payment_method.php">Kembali</a>
        </form>
    </div>
</body>
</html>
