<?php
session_start();
include('../php/config.php'); // Koneksi ke database

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika session tidak ada atau role bukan 'admin', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $menuId = $_GET['id'];
    $action = $_GET['action'];

    // Ambil stok menu saat ini
    $sql = "SELECT stock_tmm FROM tbl_m_menu WHERE id_tmm = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $menuId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentStock = $row['stock_tmm'];

        // Update stok sesuai aksi
        if ($action === 'plus') {
            $newStock = $currentStock + 1;
        } elseif ($action === 'minus' && $currentStock > 0) {
            $newStock = $currentStock - 1;
        } else {
            // Jika aksi minus tetapi stok sudah 0
            echo json_encode(['success' => false, 'message' => 'Stok tidak bisa lebih rendah dari 0']);
            exit;
        }

        // Update stok di database
        $updateSql = "UPDATE tbl_m_menu SET stock_tmm = ? WHERE id_tmm = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $newStock, $menuId);
        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'new_stock' => $newStock]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui stok']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Menu tidak ditemukan']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Parameter tidak lengkap']);
}

$conn->close();
?>
