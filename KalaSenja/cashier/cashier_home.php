<?php
session_start(); // Memulai session

// Cek jika pengguna tidak login atau tidak memiliki hak akses 'cashier'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cashier') {
    // Jika session tidak ada atau role bukan 'cashier', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}

include('../php/config.php'); // Koneksi ke database

// Dapatkan session ID untuk pengguna saat ini
$session_id = session_id();

// Inisialisasi keranjang belanja untuk session ID yang spesifik
if (!isset($_SESSION['cart'][$session_id])) {
    $_SESSION['cart'][$session_id] = []; // Jika belum ada, inisialisasi keranjang untuk session ini
}

// Menambahkan item ke dalam cart jika form submit
if (isset($_POST['cart'])) {
    // Pastikan data yang dikirimkan valid
    if (isset($_POST['id_tmm'], $_POST['name'], $_POST['price'], $_POST['photo'], $_POST['stock_tmm'])) {
        // Data yang akan ditambahkan ke keranjang
        $item = [
            'id_tmm' => $_POST['id_tmm'],  // Pastikan menggunakan id_tmm
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'photo' => $_POST['photo'],
            'stock_tmm' => $_POST['stock_tmm'],  // Menambahkan stock_tmm
            'quantity' => 1 // Kuantitas awal adalah 1
        ];

        // Cek apakah item sudah ada dalam keranjang
        $found = false;
        foreach ($_SESSION['cart'][$session_id] as &$cart_item) {
            // Bandingkan menggunakan id_tmm
            if ($cart_item['id_tmm'] == $item['id_tmm']) {
                $cart_item['quantity'] += 1; // Jika sudah ada, tambahkan kuantitas
                $found = true;
                break;
            }
        }

        // Jika item belum ada dalam keranjang, tambahkan item baru
        if (!$found) {
            $_SESSION['cart'][$session_id][] = $item;
        }
    } else {
        echo "Error: Data tidak lengkap.";
    }
}

// Query untuk mengambil data menu dari tabel
$sql = "SELECT * FROM tbl_m_menu"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kala Senja Coffee - Cashier</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        /* Styling untuk tombol Checkout */
        .checkout-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #FF8C00;
            border: none;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .checkout-btn:hover {
            background-color: #e57f00;
        }

        .checkout-btn i {
            font-size: 24px;
        }

        a {
            text-decoration: none;
        }
        .category-select {
            font-size: 16px;
            padding: 8px;
            width: 200px;
            border-radius: 5px;
            border: 1px solidrgb(0, 19, 44);
            margin-bottom: 20px;
            background-color: #22242f;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="../cashier/cashier_home.php">
            <div class="sidebar-icon active" data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../cashier/cashier_kelola_order.php">
            <div class="sidebar-icon" data-icon="orders"><i class="ph ph-note"></i></div>
        </a>
        <a href="../cashier/cashier_profile.php">
            <div class="sidebar-icon" data-icon="users"><i class="ph ph-user"></i></div>
        </a>
        <a href="../fungsi/logout.php">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </aside>

    <!-- Navbar untuk Mobile -->
    <div class="mobile-navbar">
        <a href="../cashier/cashier_home.php">
            <div class="sidebar-icon active" data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../cashier/cashier_kelola_order.php">
            <div class="sidebar-icon" data-icon="orders"><i class="ph ph-note"></i></div>
        </a>
        <a href="../cashier/cashier_profile.php">
            <div class="sidebar-icon" data-icon="users"><i class="ph ph-user"></i></div>
        </a>
        <a href="../fungsi/logout.php">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </div>

    <!-- Dashboard Content -->
    <div class="container">
        <main id="dashboard" class="content-section active">
            <header>
                <div class="top-bar">
                    <div class="kala-senja">
                        <h1>Kala Senja Coffee</h1>
                        <?php
                            // Output the date in "Month Year" and "Day of the Month, Time" format
                            echo date('jS F Y');
                        ?>
                    </div>
                    <img src="../images/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <div>
                    <div class="menu-nav">
                        <h2>Choose Menu</h2>
                        <select class="category-select" id="category-select">
                            <option value="all">All</option>
                            <option value="Food">Food</option>
                            <option value="Beverage">Drink</option>
                        </select>
                    </div>
                </div>
            </header>

            <div class="dishes-grid" id="dishes-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='dish-card' data-category='" . $row['categoryMenu_tmm'] . "'>";
            echo "<img src='" . $row['photo_tmm'] . "' alt='Dish' class='dish-img'>";
            echo "<h3>" . $row['menu_tmm'] . "</h3>";
            echo "<p class='price'>Rp. " . number_format($row['price_tmm'], 2, ',', '.') . "</p>"; // Format harga

            // Form untuk menambahkan item ke dalam keranjang
            echo "<form action='../cashier/cashier_home.php' method='POST'>";
            // Ganti 'id' menjadi 'id_tmm' agar konsisten
            echo "<input type='hidden' name='id_tmm' value='" . $row['id_tmm'] . "'>";
            echo "<input type='hidden' name='name' value='" . htmlspecialchars($row['menu_tmm'], ENT_QUOTES) . "'>";
            echo "<input type='hidden' name='price' value='" . $row['price_tmm'] . "'>";
            echo "<input type='hidden' name='photo' value='" . htmlspecialchars($row['photo_tmm'], ENT_QUOTES) . "'>";
            echo "<input type='hidden' name='stock_tmm' value='" . $row['stock_tmm'] . "'>";

            // Tombol untuk menambahkan item ke keranjang
            echo "<button type='submit' class='add-cart-btn' name='cart'>Add to Cart</button>";
            echo "</form>";

            echo "</div>";
        }
    } else {
        echo "<p>No menu available.</p>";
    }
    ?>
</div>

        </main>
    </div>

    <!-- Tombol Checkout -->
    <a href="../add/add_order.php">
        <button class="checkout-btn" id="checkout-btn">
            <i class="ph ph-shopping-cart"></i>
            <span id="cart-count">0</span>
        </button>
    </a>
    <script>
                // Update jumlah item dalam keranjang
                function updateCartCount() {
            const cart = <?php echo json_encode($_SESSION['cart'][$session_id]); ?>;
            const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
            document.getElementById('cart-count').textContent = cartCount;
        }

        // Panggil fungsi untuk update jumlah keranjang saat halaman dimuat
        updateCartCount();
        // Filter menu berdasarkan kategori
        const categorySelect = document.getElementById('category-select');
        const dishesContainer = document.getElementById('dishes-container');
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            const dishCards = dishesContainer.getElementsByClassName('dish-card');

            Array.from(dishCards).forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (selectedCategory === 'all' || selectedCategory === cardCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>

<?php
$conn->close(); // Tutup koneksi database setelah selesai
?>
