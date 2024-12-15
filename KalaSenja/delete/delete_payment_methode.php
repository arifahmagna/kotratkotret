<?php 
include('../php/config.php'); // Koneksi ke database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus metode pembayaran berdasarkan ID
    $deleteQuery = "DELETE FROM tbl_m_paymentmethod WHERE id_tmpm = '$id'";

    if (mysqli_query($conn, $deleteQuery)) {
        // Redirect ke halaman admin_payment_methode.php setelah berhasil menghapus
        header("Location: ../admin/admin_payment_methode.php");
        exit();
    } else {
        // Jika gagal menghapus, tampilkan alert dan redirect ke halaman yang sama
        echo "<script>alert('Gagal menghapus metode pembayaran.'); window.location.href='../admin/admin_payment_methode.php';</script>";
    }
} else {
    // Jika ID tidak ditemukan, tampilkan alert dan redirect ke halaman admin_payment_methode.php
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='../admin/admin_payment_methode.php';</script>";
}
?>
