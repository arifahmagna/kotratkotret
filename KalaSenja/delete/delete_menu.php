<?php
include('../php/config.php'); // Koneksi ke database

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $menu_id = $_GET['id'];

    // Mulai transaksi (optional untuk memastikan integritas data)
    $conn->begin_transaction();

    try {
        // Hapus data menu dari tabel tbl_m_menu
        $sql = "DELETE FROM tbl_m_menu WHERE id_tmm = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();

        // Commit transaksi
        $conn->commit();

        // Redirect kembali ke halaman admin home setelah sukses
        header('Location: ../admin/admin_home.php');
        exit;
    } catch (Exception $e) {
        // Jika terjadi error, rollback transaksi
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Menu ID is missing.";
}

$conn->close(); // Tutup koneksi database setelah selesai
?>
