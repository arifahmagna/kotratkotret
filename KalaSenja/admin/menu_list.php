<?php
include('../php/config.php'); // Koneksi ke database

// Query untuk mengambil data menu
$sql = "SELECT * FROM tbl_m_menu";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='menu-item'>";
        echo "<h3>" . $row['menu_tmm'] . "</h3>";
        echo "<p>" . $row['desc_tmm'] . "</p>";
        echo "<p>Price: Rp " . number_format($row['price_tmm'], 2) . "</p>";

        // Menampilkan gambar
        $photo_path = $row['photo_tmm']; // Mendapatkan path gambar dari database
        if ($photo_path) {
            echo "<img src='" . $photo_path . "' alt='Menu Photo' width='100' height='100'>";
        } else {
            echo "<p>No photo available.</p>";
        }

        echo "</div>";
    }
} else {
    echo "No menu items found.";
}

$conn->close();
?>
