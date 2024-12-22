<?php
// Include database connection
include('../php/config.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    // Jika session tidak ada atau role bukan 'cashier', arahkan ke halaman login
    header('Location: ../fungsi/login.php');
    exit;
}

// Get the order ID from the URL
$order_id = $_GET['id'];

// Query to fetch the main order details
$sql = "SELECT o.id_tmo, o.dateOrder_tmo, u.name_tmu, o.totalOrder_tmo, o.statusOrder_tmo, pm.paymentMethod_tmpm, o.typeOrder_tmo, o.id_tmpm
        FROM tbl_m_order o
        JOIN tbl_m_user u ON o.id_tmu = u.id_tmu
        JOIN tbl_m_paymentmethod pm ON o.id_tmpm = pm.id_tmpm
        WHERE o.id_tmo = '$order_id'";

$result = $conn->query($sql);
$order = $result->fetch_assoc();

// Query to fetch the ordered items
$sql_items = "SELECT t.id_tto, m.menu_tmm, t.quantity_tto, t.subTotal_tto, t.note_tto
              FROM tbl_t_order t
              JOIN tbl_m_menu m ON t.id_tmm = m.id_tmm
              WHERE t.id_tmo = '$order_id'";

$items_result = $conn->query($sql_items);

// Process form submission for updating order details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['statusOrder_tmo'];
    $new_type = $_POST['typeOrder_tmo'];
    $new_payment_method = $_POST['paymentMethod_tmpm'];

    // Update the order details
    $update_sql = "UPDATE tbl_m_order
                   SET statusOrder_tmo = '$new_status', typeOrder_tmo = '$new_type', id_tmpm = '$new_payment_method'
                   WHERE id_tmo = '$order_id'";

    if ($conn->query($update_sql) === TRUE) {
        // Refresh the order data after update
        $order = $conn->query($sql)->fetch_assoc();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch all payment methods for the dropdown
$sql_payment_methods = "SELECT id_tmpm, paymentMethod_tmpm FROM tbl_m_paymentmethod";
$payment_methods_result = $conn->query($sql_payment_methods);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Kala Senja Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .order-details { margin-top: 20px; }
        .order-status { font-weight: bold; }
        .card-header { background-color: #3E5879; color: white; }
        .floating-label { position: relative; margin-bottom: 20px; }
        .floating-label input, .floating-label select { padding-top: 20px; padding-bottom: 8px; padding-left: 20px; }
        .floating-label label { position: absolute; top: 0; left: 28px; font-size: 12px; color: #aaa; transition: 0.2s; }
        .floating-label input:focus + label, .floating-label input:not(:placeholder-shown) + label { top: -10px; font-size: 10px; color: white; top: -15px;} 
        .floating-label select:focus + label,
        .floating-label select:not([value=""]) + label {
            top: -15px;
            font-size: 10px;
            color: white;
            transition: 0.2s;
        }
        body {
            background-color: #1F1D2B; /* Warna latar belakang baru */
            color: white; /* Warna teks agar kontras */
            justify-content: center;
            align-items: center;
        }

        .card{
            background-color: #252836; 
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;

        }

        .table {
        color: white; /* Warna teks tabel */
    }
    </style>
</head>
<body>

<div class="container order-details">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Order Details - ID: <?php echo $order['id_tmo']; ?></h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="floating-label">
                                    <input type="text" class="form-control" value="<?php echo $order['name_tmu']; ?>" readonly>
                                    <label>Username</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="floating-label">
                                    <input type="text" class="form-control" value="<?php echo date('d-m-Y', strtotime($order['dateOrder_tmo'])); ?>" readonly>
                                    <label>Date</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="floating-label">
                                    <input type="text" class="form-control" value="Rp <?php echo number_format($order['totalOrder_tmo'], 2, ',', '.'); ?>" readonly>
                                    <label>Total Amount</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="floating-label">
                                <input type="text" class="form-control" value="<?php echo $order['statusOrder_tmo']; ?>" readonly>
                                    <label>Status</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="floating-label">
                                <input type="text" class="form-control" value="<?php echo $order['typeOrder_tmo']; ?>" readonly>

                                    <label>Order Type</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="floating-label">
                                <input type="text" class="form-control" value="<?php echo $order['paymentMethod_tmpm']; ?>" readonly>
                                <label>Payment Method</label>
                                </div>
                            </div>
                        </div>
                    </form>

                    <h5 class="mt-4">Ordered Items</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Menu Name</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($items_result->num_rows > 0) {
                                $no = 1;
                                while ($item = $items_result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . $no++ . "</td>
                                            <td>" . $item['menu_tmm'] . "</td>
                                            <td>" . $item['quantity_tto'] . "</td>
                                            <td>Rp " . number_format($item['subTotal_tto'], 2, ',', '.') . "</td>
                                            <td>" . $item['note_tto'] . "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No items found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Back to Orders Button -->
                    <div class="mt-4">
                        <a href="../costumer/cust_history.php" class="btn btn-primary">Back to Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
