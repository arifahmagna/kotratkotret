<?php
session_start(); // Memulai session

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika session tidak ada atau role bukan 'admin', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}
include('../php/config.php'); // Koneksi ke database
$user_id = $_SESSION['user_id']; 
$sql = "SELECT id_tmu, animal_tmu FROM tbl_m_user WHERE id_tmu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); // Menyisipkan user_id sebagai parameter
$stmt->execute();
$result = $stmt->get_result();
// Periksa apakah data ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_tmu = $row['id_tmu'];
    $animal_tmu = $row['animal_tmu'];

    // Periksa apakah animal_tmu kosong atau NULL
    if (is_null($animal_tmu) || $animal_tmu == "") {
        $animal_tmu = null; // Menandakan bahwa animal_tmu kosong atau belum diisi
    }
} else {
    // Jika tidak ada data ditemukan untuk user_id, anggap animal_tmu sebagai NULL
    $animal_tmu = null;
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
    <title>Kala Senja Coffee - Admin Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/logout-confirmation.js"></script>
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
        /* Container to align h2 and button side by side */
.add-menu-btn-container {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 1rem; /* Adds space between the text and button */
}

/* Button styling */
.add-menu-btn {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.add-menu-btn:hover {
    background-color: #45a049; /* Darker green on hover */
}

/* Optional: Styling for h2 */
h2 {
    margin: 0; /* Remove default margin */
    font-size: 24px;
}

        @media (max-width: 600px) {
        .dishes-grid {
            grid-template-columns: repeat(auto-fill, minmax(125px, 1fr)); /* Two times smaller */
        }

        .dish-img {
            width: 75px; /* 2x smaller for mobile */
            height: 75px; /* 2x smaller for mobile */
        }
        .stock-display {
            margin: 0 5px;
            font-size: 7px;
        }
        .button-container {
            margin-top: 5px;
        }
        .button-container button {
            padding: 2px 2px;
            margin: 2px;
            border-radius: 2px;
        }

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
        .notification {
    background-color: #ff4d4d; /* Background merah terang */
    color: white; /* Teks berwarna putih */
    padding: 15px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px; /* Jarak antara notifikasi dan bagian header lainnya */
    border-radius: 5px; /* Sudut yang sedikit melengkung */
    position: fixed;
    top: 20px; /* Letakkan di atas halaman */
    left: 50%;
    transform: translateX(-50%); /* Posisikan di tengah secara horizontal */
    width: 90%; /* Ukuran lebar 90% dari layar */
    z-index: 9999; /* Pastikan berada di atas elemen lainnya */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Bayangan lembut */
}
@media (max-width: 600px) {
    .notification {
        width: 100%; /* Lebar 100% untuk perangkat mobile */
        font-size: 14px; /* Ukuran font lebih kecil */
    }
}
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="../admin/admin_home.php">
            <div class="sidebar-icon active " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.php">
            <div class="sidebar-icon" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_settings.php">
            <div class="sidebar-icon " data-icon="users"><i class="ph ph-gear-six"></i></div>
        </a>
        <a href="javascript:void(0)" id="logout-btn" class="logout-btn">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </aside>

    <!-- Navbar untuk Mobile -->

    <div class="mobile-navbar">
        <a href="../admin/admin_home.php">
            <div class="mobile-icon active " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.php">
            <div class="mobile-icon" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_settings.php">
            <div class="mobile-icon" data-icon="users"><i class="ph ph-gear-six"></i></div>
        </a>
        <a href="javascript:void(0)" id="logout-btn" class="logout-btn">
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
                        <?php
                            // Output the date in "Month Year" and "Day of the Month, Time" format
                            echo date('jS F Y');
                        ?>
                    </div>
                    <img src="../uploads/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <?php
                        // Cek apakah animal_tmu NULL dan tampilkan notifikasi
                        if (is_null($animal_tmu)) {
                            echo '<a href="../update/update_profile_admin.php">
                            <div class="notification"><i class="ph ph-warning"></i> Security question null</div>'
                            . '</a>';
                        }
                ?>
                <div class="menu-nav" style="align-items:baseline">
                        <h2>Manage Menu</h2>
                        <div class="add-menu-btn-container">
                        <a href="../add/add_menu.php"><button class="add-menu-btn">Add Menu</button></a>
                        </div>
                        <select class="category-select" id="category-select">
                            <option value="all">All</option>
                            <option value="Food">Food</option>
                            <option value="Beverage">Drink</option>
                            <option value="Snack">Snack</option>
                        </select>
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
        echo "<p style='font-size: 0.9rem;'>" . $row['desc_tmm'] . "</p>";

        // Display buttons +, - and Edit with stock info
        echo "<div class='button-container'>";
        echo "<button class='plus-btn' data-id='" . $row['id_tmm'] . "'>+</button>";
        echo "<span class='stock-display'>" . $stock . "</span>"; // Display stock
        echo "<button class='minus-btn' data-id='" . $row['id_tmm'] . "'>-</button>";
        // Edit button links to update_menu.php with menu ID
        echo "<a href='../update/update_menu.php?id=" . $row['id_tmm'] . "'><button class='edit-btn'>Edit</button></a>";
        echo "<a href='#' onclick='confirmDelete(" . $row['id_tmm'] . ")'><button class='delete-btn'>Delete</button></a>";
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
                        // Update stok yang ditampilkan di halaman tanpa reload
                        const stockElement = document.querySelector(`button[data-id='${menuId}']`).nextElementSibling;
                        stockElement.innerText = data.new_stock;
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Menangani klik tombol + dan - pada setiap item menu
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
        function confirmDelete(id) {
            // Menampilkan konfirmasi SweetAlert
            Swal.fire({
                title: 'Are you sure?',
                text: "This menu will be deleted permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna memilih 'Yes', arahkan ke halaman delete_menu.php dengan id
                    window.location.href = '../delete/delete_menu.php?id=' + id;
                } else {
                    // Jika pengguna memilih 'No', tidak terjadi apa-apa
                    Swal.fire(
                        'Cancelled',
                        'The menu was not deleted.',
                        'error'
                    )
                }
            });
        }
    </script>

</body>
</html>

<?php
$conn->close(); // Tutup koneksi database setelah selesai
?>