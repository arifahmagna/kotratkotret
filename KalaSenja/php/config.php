<?php
$host = "localhost";   // Ganti dengan host database Anda
$username = "root";    // Ganti dengan username database Anda
$password = "";        // Ganti dengan password database Anda jika ada
$dbname = "kalasenja_db";  // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
