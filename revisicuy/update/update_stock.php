<?php
include('../php/config.php');

// Pastikan ID dan action ada di URL
if (isset($_GET['id']) && isset($_GET['action'])) {
    // Mengambil ID dan aksi dari URL dan membersihkan input untuk keamanan
    $menu_id = intval($_GET['id']);  // Menggunakan intval untuk memvalidasi ID sebagai integer
    $action = $_GET['action'];       // Bisa berupa 'plus' atau 'minus'

    // Query untuk mengambil stok saat ini
    $sql = "SELECT stock_tts FROM tbl_t_stock WHERE id_tmm = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika data stok ditemukan
        if ($result->num_rows > 0) {
            $stock = $result->fetch_assoc()['stock_tts'];

            // Update stok berdasarkan aksi
            if ($action == 'plus') {
                $new_stock = $stock + 1;
            } elseif ($action == 'minus' && $stock > 0) {
                $new_stock = $stock - 1;
            } else {
                $new_stock = $stock; // Jika minus tapi stok sudah 0, tidak mengubah stok
            }

            // Update stok di database
            $update_sql = "UPDATE tbl_t_stock SET stock_tts = ? WHERE id_tmm = ?";
            if ($update_stmt = $conn->prepare($update_sql)) {
                $update_stmt->bind_param("ii", $new_stock, $menu_id);
                if ($update_stmt->execute()) {
                    // Jika berhasil, kirim response JSON
                    echo json_encode(['success' => true, 'new_stock' => $new_stock]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update stock']);
                }
                $update_stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error preparing update query']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Menu not found in stock table']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing select query']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close(); // Menutup koneksi database
?>
