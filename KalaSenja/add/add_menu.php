<?php
include('../php/config.php'); // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu = $_POST['menu'];
    $price = $_POST['price'];
    $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
    $categoryMenu = $_POST['categoryMenu'];
    $stock = isset($_POST['stock']) ? $_POST['stock'] : 0; // Default stock 0
    
    // Periksa apakah file foto diupload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Tentukan direktori untuk menyimpan gambar
        $upload_dir = "../uploads/"; // Folder untuk menyimpan gambar
        $file_name = $_FILES['photo']['name'];
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_path = $upload_dir . basename($file_name); // Path lengkap untuk menyimpan file

        // Cek ekstensi file
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_extensions)) {
            // Pindahkan file ke direktori yang ditentukan
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Simpan data ke database (termasuk stock_tmm di sini)
                $sql = "INSERT INTO tbl_m_menu (menu_tmm, price_tmm, desc_tmm, photo_tmm, categoryMenu_tmm, stock_tmm) 
                        VALUES (?, ?, ?, ?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    // Bind parameter
                    $stmt->bind_param("sssssi", $menu, $price, $desc, $file_path, $categoryMenu, $stock);

                    // Eksekusi query
                    if ($stmt->execute()) {
                        // Redirect setelah berhasil
                        header('Location: ../admin/admin_home.php');
                        exit;
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error: " . $conn->error;
                }
            } else {
                echo "Failed to upload image.";
            }
        } else {
            echo "Only JPG, JPEG, and PNG files are allowed.";
        }
    } else {
        echo "No file uploaded.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Styling untuk Body */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1b23; /* Warna latar belakang gelap */
            color: #fff; /* Warna teks putih */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Heading */
        h2 {
            font-size: 24px;
            color: #e5e5e5;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Container untuk form */
        .container {
            width: 100%;
            max-width: 600px; /* Ukuran maksimal card */
            background-color: #2c2f3b; /* Warna gelap untuk card */
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Efek bayangan untuk card */
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Memastikan tombol tidak tertutup */
        }

        /* Scrollable content */
        .form-content {
            overflow-y: auto;
            flex-grow: 1;
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
        .form-group textarea,
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
        .form-group textarea:focus,
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

        /* Error Message */
        .error-message {
            background-color: #e74c3c; /* Merah terang untuk error */
            color: #fff;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        /* Responsive Design untuk layar kecil */
        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            .form-group input,
            .form-group textarea,
            .form-group select,
            .form-group button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <h2>Add New Menu</h2>
    <div class="container">
        <!-- Form untuk menambahkan menu -->
        <form action="add_menu.php" method="POST" enctype="multipart/form-data">
            <!-- Form Content (Scrollable) -->
            <div class="form-content">
                <!-- Menu Name -->
                <div class="form-group">
                    <label for="menu">Menu Name:</label>
                    <input type="text" name="menu" id="menu" required>
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="desc">Description:</label>
                    <textarea name="desc" id="desc" rows="4" required></textarea>
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="categoryMenu">Category:</label>
                    <select name="categoryMenu" id="categoryMenu" required>
                        <option value="Food">Food</option>
                        <option value="Beverage">Drink</option>
                        <option value="Dessert">Snack</option>
                    </select>
                </div>

                <!-- Upload Photo -->
                <div class="form-group">
                    <label for="photo">Upload Photo:</label>
                    <input type="file" name="photo" id="photo" accept="image/*" required>
                </div>

                <!-- Stock (Optional, default 0) -->
                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" name="stock" id="stock" value="0">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">Add Menu</button>
            </div>
        </form>
    </div>

</body>
</html>
