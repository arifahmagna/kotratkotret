<?php
include('../php/config.php'); // Koneksi ke database

// Cek apakah ID metode pembayaran ada dalam URL
if (isset($_GET['id'])) {
    // Mengambil ID metode pembayaran yang akan dihapus
    $paymentMethodId = $_GET['id'];

    // Query untuk menghapus data metode pembayaran berdasarkan ID
    $query = "DELETE FROM tbl_m_paymentmethod WHERE id_tmpm = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $paymentMethodId);
    $stmt->execute();

    // Redirect kembali ke halaman admin home setelah sukses
    header('Location: ../admin/admin_payment_methode.php');
    exit;
} else {
    echo "ID metode pembayaran tidak ditemukan.";
}
$conn->close();
?>
