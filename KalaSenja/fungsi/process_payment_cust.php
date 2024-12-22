<?php
include('../php/config.php');
session_start(); // Memulai sesi

if (isset($_POST['checkout'])) {
    // Ambil data dari form
    $customer_name = $_POST['customer_name'];
    $type_order = $_POST['typeOrder'];
    $status_order = $_POST['statusOrder'];
    $payment_method = $_POST['paymentMethod'];
    $total_amount = $_POST['totalAmount']; // Nominal uang yang dibayarkan
    $total_amount = str_replace('Rp. ', '', $total_amount); // Menghapus simbol Rp
    $total_amount = str_replace('.', '', $total_amount); // Menghapus titik untuk format ribuan
    $total_amount = (float) $total_amount;  // Pastikan menjadi float atau decimal

    // Ambil ID pengguna dari sesi
    $user_id = $_SESSION['user_id']; // Misalnya ID pengguna ada di sesi

    // ID metode pembayaran dari form
    $payment_method_id = $payment_method;

    // Pastikan keranjang tidak kosong
    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Keranjang kosong.'); window.location = '../cashier/cashier_home.php';</script>";
        exit;
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Query untuk menyimpan pesanan utama ke tbl_m_order
        $stmt = $conn->prepare("INSERT INTO tbl_m_order 
                               (dateOrder_tmo, typeOrder_tmo, statusOrder_tmo, id_tmu, id_tmpm, totalOrder_tmo, cashNominal_tmo, customerName_tmo) 
                               VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?)");
        // Menggunakan bind_param dengan tipe data yang benar
        $stmt->bind_param("sssiiis", $type_order, $status_order, $user_id, $payment_method_id, $total_amount, $total_amount, $customer_name);
        $stmt->execute();

        // Ambil ID pesanan yang baru saja disimpan
        $order_id = $conn->insert_id; // Ambil ID pesanan yang baru saja dibuat

        // Simpan item keranjang ke dalam tabel tbl_t_order
        foreach ($_SESSION['cart'] as $item) {
            // Pastikan id_tmm ada di setiap item
            if (!isset($item['id_tmm'])) {
                throw new Exception("ID menu tidak ditemukan pada item keranjang.");
            }

            $menu_id = $item['id_tmm']; // ID menu dari item
            $note = isset($item['note']) ? $item['note'] : ''; // Catatan item (jika ada)
            $quantity = $item['quantity']; // Jumlah pesanan
            $sub_total = $item['price'] * $quantity; // Menghitung subTotal per item

            // Query untuk menyimpan detail pesanan ke tbl_t_order
            $stmt_detail = $conn->prepare("INSERT INTO tbl_t_order 
                                          (id_tmm, note_tto, quantity_tto, subTotal_tto, id_tmo) 
                                          VALUES (?, ?, ?, ?, ?)");
            // Pastikan id_tto tetap konsisten (ID pesanan yang sama untuk semua item)
            $stmt_detail->bind_param("isdis", $menu_id, $note, $quantity, $sub_total, $order_id);
            $stmt_detail->execute();
            // Update stok menu di tbl_menu
            $stmt_update_stock = $conn->prepare("UPDATE tbl_m_menu SET stock_tmm = stock_tmm - ? WHERE id_tmm = ? AND stock_tmm >= ?");
            $stmt_update_stock->bind_param("iii", $quantity, $menu_id, $quantity);
            $stmt_update_stock->execute();

            // Cek apakah stok berhasil diupdate
            if ($stmt_update_stock->affected_rows === 0) {
                throw new Exception("Stok tidak mencukupi untuk item menu dengan ID: " . $menu_id);
            }
        }

        // Commit transaksi
        $conn->commit();

        // Kosongkan keranjang setelah checkout
        $_SESSION['cart'] = [];

        // Beri notifikasi sukses dan arahkan ke halaman menu
        echo "<script>alert('Checkout berhasil!'); window.location = '../cashier/cashier_home.php';</script>";
    } catch (Exception $e) {
        // Jika terjadi error, rollback transaksi
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>
