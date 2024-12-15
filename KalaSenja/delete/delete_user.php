<?php
include('../php/config.php');

// Cek apakah ada ID yang diterima via POST
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    // Menghapus data pengguna berdasarkan ID
    $sql = "DELETE FROM tbl_m_user WHERE id_tmu = $id";
    if ($conn->query($sql) === TRUE) {
        echo 'success'; // Kirim respons sukses
    } else {
        echo 'error'; // Kirim respons error
    }
}

$conn->close();
?>
