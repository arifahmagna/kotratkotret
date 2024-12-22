<?php
// Pastikan Anda sudah melakukan koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tmo = $_POST['id_tmo'];
    $statusOrder = $_POST['statusOrder'];

    // Query untuk update status order
    $sql = "UPDATE orders_table SET statusOrder_tmo = ? WHERE id_tmo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $statusOrder, $id_tmo);

    if ($stmt->execute()) {
        // Redirect atau tampilkan pesan sukses
        header("Location: previous_page.php?update=success");
    } else {
        // Redirect atau tampilkan pesan error
        header("Location: previous_page.php?update=error");
    }
}
?>
