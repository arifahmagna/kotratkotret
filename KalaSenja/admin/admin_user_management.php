<?php include('../php/config.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika session tidak ada atau role bukan 'admin', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Kala Senja Coffee</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/logout-confirmation.js"></script>
    <!-- jQuery CDN (untuk AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Styling untuk Body */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1b23;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        /* Header dan Title */
        .top-bar-users {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #2c2f3b;
        }

        .users-header h1 {
            margin: 0;
            color: #e5e5e5;
        }

        /* Profil dan Navbar */
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Styling untuk Table */
        .user-list {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #444;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        table td {
            background-color: #2c2f3b;
            color: #fff;
        }

        /* Styling untuk Action Button */
        .btn-edit, .btn-delete {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-edit {
            background-color: #3498db;
            color: #fff;
            border: none;
        }

        .btn-edit:hover {
            background-color: #2980b9;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: #fff;
            border: none;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        /* Responsive Design untuk Mobile */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            table {
                font-size: 12px;
            }
        }
/* Container untuk kedua tombol */
.btn-container {
    display: flex;               /* Menggunakan Flexbox */
    justify-content: space-between;  /* Menjaga tombol di sisi kiri dan kanan */
    align-items: center;         /* Menyusun tombol secara vertikal di tengah */
    width: 100%;                 /* Membuat kontainer selebar 100% */
    padding: 10px;               /* Memberikan padding agar tidak terlalu rapat dengan tepi */
}

/* Styling untuk tombol kembali */
.back-btn {
    font-size: 32px;             /* Ukuran ikon lebih besar */
    color: #fff;                 /* Warna ikon putih */
    text-decoration: none;       /* Menghapus garis bawah */
    transition: transform 0.3s ease, color 0.3s ease; /* Efek transisi */
}

.back-btn:hover {
    color: red;              /* Warna hijau saat hover */
    transform: scale(1.2);       /* Membesarkan ikon saat hover */
}

/* Styling untuk tombol tambah */
.add-btn {
    font-size: 32px;             /* Ukuran ikon lebih besar */
    color: #fff;                 /* Warna ikon putih */
    text-decoration: none;       /* Menghapus garis bawah */
    transition: transform 0.3s ease, color 0.3s ease; /* Efek transisi */
}

.add-btn:hover {
    color: #4caf50;              /* Warna hijau saat hover */
    transform: scale(1.2);       /* Membesarkan ikon saat hover */
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
            <div class="sidebar-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.php">
            <div class="sidebar-icon" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_settings.php">
            <div class="sidebar-icon active" data-icon="users"><i class="ph ph-gear-six"></i></div>
        </a>
        <a href="javascript:void(0)" id="logout-btn" class="logout-btn">
            <div class="sidebar-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </aside>

    <!-- Navbar untuk Mobile -->

    <div class="mobile-navbar">
        <a href="../admin/admin_home.php">
            <div class="mobile-icon " data-icon="home"><i class="ph ph-house"></i></div>
        </a>
        <a href="../admin/admin_sales_report.php">
            <div class="mobile-icon" data-icon="report"><i class="ph ph-chart-line-up"></i></div>
        </a>
        <a href="../admin/admin_settings.php">
            <div class="mobile-icon active" data-icon="users"><i class="ph ph-gear-six"></i></div>
        </a>
        <a href="javascript:void(0)" id="logout-btn" class="logout-btn">
            <div class="mobile-icon" data-icon="logout"><i class="ph ph-sign-out"></i></div>
        </a>
    </div>
    <!-- Dashboard Content -->
    <div class="container">
        <main id="users" class="content-section active">
            <header>
                <div class="top-bar-users">
                    <div class="users-header">
                        <h1>User Management</h1>
                        <?php
                            // Output the date in "Month Year" and "Day of the Month, Time" format
                            echo date('jS F Y');
                        ?>
                    </div>
                    <img src="../uploads/logo.jpg" alt="Profile" class="profile-img">
                </div>
                <hr class="divider">
            </header>
            
            <!-- Add User Button -->
            <div class="btn-container">
                <a href="../admin/admin_settings.php" class="back-btn"><i class="ph ph-backspace"></i></a>
                <a href="../add/add_user.php" class="add-btn"><i class="ph ph-user-plus"></i></a>
            </div>

            <!-- Profile Section -->
            <section class="user-list">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query untuk mengambil data pengguna dari database
                        $sql = "SELECT * FROM tbl_m_user"; // Mengambil data dari tabel tbl_m_user
                        $result = $conn->query($sql);
                        
                        // Mengecek apakah ada hasil
                        if ($result->num_rows > 0) {
                            // Menampilkan data pengguna
                            while($row = $result->fetch_assoc()) {
                                echo "<tr id='user-{$row["id_tmu"]}'>";
                                echo "<td>" . $row["id_tmu"] . "</td>";
                                echo "<td>" . $row["name_tmu"] . "</td>";
                                echo "<td>" . $row["email_tmu"] . "</td>";
                                echo "<td>" . $row["role_tmu"] . "</td>";
                                echo "<td>
                                    <a href='../update/update_user.php?id=" . $row["id_tmu"] . "'><button class='btn-edit'>Edit</button></a>
                                    <button class='btn-delete' data-id='{$row["id_tmu"]}'>Delete</button>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        // Menampilkan SweetAlert konfirmasi sebelum menghapus data
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user memilih "Yes", kirimkan AJAX request untuk menghapus user
                        $.ajax({
                            url: '../delete/delete_user.php',
                            type: 'POST',
                            data: { delete_id: userId },
                            success: function(response) {
                                if (response === 'success') {
                                    // Hapus baris user yang dihapus dari tabel
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'The user has been deleted.',
                                        icon: 'success'
                                    }).then(function() {
                                        $('#user-' + userId).remove(); // Hapus baris dari tabel
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'There was an error deleting the user.',
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>