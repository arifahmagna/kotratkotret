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
    $photo = $_FILES['photo']['tmp_name'] ? addslashes(file_get_contents($_FILES['photo']['tmp_name'])) : $menu['photo_tmm'];

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
</head>
<body>
    <h2>Edit Menu</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="menu">Menu Name:</label>
        <input type="text" name="menu" value="<?php echo $menu['menu_tmm']; ?>" required><br><br>
        <label for="price">Price:</label>
        <input type="text" name="price" value="<?php echo $menu['price_tmm']; ?>" required><br><br>
        <label for="desc">Description:</label>
        <textarea name="desc" required><?php echo $menu['desc_tmm']; ?></textarea><br><br>
        <label for="photo">Upload New Photo (optional):</label>
        <input type="file" name="photo" accept="image/*"><br><br>
        <button type="submit">Update Menu</button>
    </form>
</body>
</html>

<?php
$conn->close(); // Tutup koneksi database setelah selesai
?>
