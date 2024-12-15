<?php
// Include database connection
include('../php/config.php');

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
$sql_items = "SELECT t.id_tto, m.menu_tmm, t.quantity_tto, t.subTotal_tto
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
        .status-lunas { color: #27ae60; /* Green for 'Lunas' */ }
        .status-belum-lunas { color: #c0392b; /* Red for 'Belum Lunas' */ }
        .card-header { background-color: #0e004a; color: white; }
        .floating-label { position: relative; margin-bottom: 20px; }
        .floating-label input, .floating-label select { padding-top: 18px; padding-bottom: 8px; padding-left: 20px; }
        .floating-label label { position: absolute; top: 0; left: 20px; font-size: 12px; color: #aaa; transition: 0.2s; }
        .floating-label input:focus + label, .floating-label input:not(:placeholder-shown) + label { top: -10px; font-size: 10px; color: #0e004a; }
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
                                    <select name="statusOrder_tmo" class="form-control" required>
                                        <option value="Close" <?php echo ($order['statusOrder_tmo'] == 'Close' ? 'selected' : ''); ?>>Close</option>
                                        <option value="Open" <?php echo ($order['statusOrder_tmo'] == 'Open' ? 'selected' : ''); ?>>Open</option>
                                    </select>
                                    <label>Status</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="floating-label">
                                <select name="typeOrder_tmo" class="form-control" required>
                                    <option value="Dine-In" <?php echo ($order['typeOrder_tmo'] == 'Dine-In' ? 'selected' : ''); ?>>Dine-In</option>
                                    <option value="Take Away" <?php echo ($order['typeOrder_tmo'] == 'Take Away' ? 'selected' : ''); ?>>Take Away</option>
                                </select>

                                    <label>Order Type</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="floating-label">
                                    <select name="paymentMethod_tmpm" class="form-control" required>
                                        <?php
                                        while ($pm = $payment_methods_result->fetch_assoc()) {
                                            echo "<option value='" . $pm['id_tmpm'] . "' " . ($order['id_tmpm'] == $pm['id_tmpm'] ? 'selected' : '') . ">" . $pm['paymentMethod_tmpm'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <label>Payment Method</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">Update Order</button>
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
                        <a href="../cashier/cashier_kelola_order.php" class="btn btn-primary">Back to Orders</a>
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
