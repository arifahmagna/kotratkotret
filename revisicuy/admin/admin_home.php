<?php
session_start(); // Memulai session

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika session tidak ada atau role bukan 'admin', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}
include('../php/config.php'); // Koneksi ke database

// Query untuk mengambil data menu dari tabel
$sql = "SELECT * FROM tbl_m_menu"; 
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kala Senja Coffee - Admin Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        /* Styling untuk tampilan stok */
        .stock-display {
            margin: 0 10px;
            font-size: 14px;
            color: #fff; /* Ubah warna sesuai dengan desain */
            font-weight: bold;
        }
        .button-container {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .button-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .button-container button:hover {
            background-color: #45a049;
        }

        .edit-btn {
            background-color: #f8b400;
        }

        .edit-btn:hover {
            background-color: #e89e00;
        }
        .sidebar a {
    text-decoration: none; /* Removes the underline */
    }

    a{
        text-decoration: none;
    }

    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="../admin/admin_home.php">
            <div class="sidebar-icon active" data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.html">
            <div class="sidebar-icon" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_payment_method.php">
            <div class="sidebar-icon" data-icon="payments"><i class="ph ph-credit-card"></i></div>
        </a>
        <a href="../admin/admin_user_management.php">
            <div class="sidebar-icon" data-icon="users"><i class="ph ph-users"></i></div>
        </a>
        <a href="../fungsi/logout.php">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </aside>

    <!-- Navbar untuk Mobile -->
    <div class="mobile-navbar">
        <a href="../admin/admin_home.php">
            <div class="mobile-icon active" data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.html">
            <div class="mobile-icon" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_payment_method.php">
            <div class="mobile-icon" data-icon="payments"><i class="ph ph-credit-card"></i></div>
        </a>
        <a href="../admin/admin_user_management.php">
            <div class="mobile-icon" data-icon="users"><i class="ph ph-users"></i></div>
        </a>
        <a href="../fungsi/logout.php">
            <div class="mobile-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </div>

    <!-- Dashboard Content -->
    <div class="container">
        <main id="dashboard" class="content-section active">
            <header>
                <div class="top-bar">
                    <div class="kala-senja">
                        <h1>Kala Senja Coffee</h1>
                        <p class="timestamp" id="timestamp"></p>
                    </div>
                    <img src="../images/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <div>
                    <div class="menu-nav">
                        <nav class="container-nav-item">
                            <a href="#" class="nav-item active" data-category="All">All</a>
                            <a href="#" class="nav-item" data-category="Food">Food</a>
                            <a href="#" class="nav-item" data-category="Beverage">Beverage</a>
                        </nav>
                        <div style="flex-basis:30rem"></div>
                    </div>
                    <h2>Manage Dishes</h2>
                    <div class="add-menu-btn-container">
                        <a href="../add/add_menu.php"><button class="add-menu-btn">Add Menu</button></a>
                    </div>
                </div>
            </header>
            <div class="dishes-grid" id="dishes-container">
        <!-- Dish Cards -->
        <?php
if ($result->num_rows > 0) {
    // Loop through the results and display them in cards
    while ($row = $result->fetch_assoc()) {
        // Directly get the stock from tbl_m_menu
        $stock = $row['stock_tmm'] !== null ? $row['stock_tmm'] : 0; // Use 0 if stock_tmm is NULL

        echo "<div class='dish-card' data-category='" . $row['categoryMenu_tmm'] . "'>";
        echo "<img src='" . $row['photo_tmm'] . "' alt='Dish' class='dish-img'>";
        echo "<h3>" . $row['menu_tmm'] . "</h3>";
        echo "<p class='price'>Rp. " . number_format($row['price_tmm'], 2) . "</p>";

        // Display buttons +, - and Edit with stock info
        echo "<div class='button-container'>";
        echo "<button class='plus-btn' data-id='" . $row['id_tmm'] . "'>+</button>";
        echo "<span class='stock-display'>" . $stock . "</span>"; // Display stock
        echo "<button class='minus-btn' data-id='" . $row['id_tmm'] . "'>-</button>";
        // Edit button links to update_menu.php with menu ID
        echo "<a href='../update/update_menu.php?id=" . $row['id_tmm'] . "'><button class='edit-btn'>Edit</button></a>";
        echo "<a href='../delete/delete_menu.php?id=" . $row['id_tmm'] . "'><button class='delete-btn'>Delete</button></a>";
        echo "</div>";

        echo "</div>";
    }
} else {
    echo "<p>No menu available.</p>";
}
?>

    </div>

    <!-- JavaScript untuk menangani tombol + dan - menggunakan AJAX -->
    <script>
        // Fungsi untuk memperbarui stok menu menggunakan AJAX
        function updateStock(menuId, action) {
            fetch('../update/update_stock.php?id=' + menuId + '&action=' + action)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Stock updated successfully!');
                        // Update stok yang ditampilkan di halaman tanpa reload
                        const stockElement = document.querySelector(`button[data-id='${menuId}']`).nextElementSibling;
                        stockElement.innerText = data.new_stock;
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Menangani klik tombol + dan -
        document.querySelectorAll('.plus-btn').forEach(button => {
            button.addEventListener('click', function() {
                let menuId = this.getAttribute('data-id');
                updateStock(menuId, 'plus');
            });
        });

        document.querySelectorAll('.minus-btn').forEach(button => {
            button.addEventListener('click', function() {
                let menuId = this.getAttribute('data-id');
                updateStock(menuId, 'minus');
            });
        });
    </script>
</body>
</html>

<?php
$conn->close(); // Tutup koneksi database setelah selesai
?>