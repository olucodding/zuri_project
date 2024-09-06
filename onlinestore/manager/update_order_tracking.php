<?php
session_start();

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: manager_login.php');
    exit();
}

// Include the database connection file
include '../includes/db.php';

// Handle form submission for multiple orders
if (isset($_POST['update_tracking'])) {
    foreach ($_POST['orders'] as $order) {
        $order_id = mysqli_real_escape_string($conn, $order['order_id']);
        $tracking_number = mysqli_real_escape_string($conn, $order['tracking_number']);
        $carrier = mysqli_real_escape_string($conn, $order['carrier']);
        $shipping_status = mysqli_real_escape_string($conn, $order['shipping_status']);

        // Update query for each order
        $update_query = "UPDATE orders SET tracking_number='$tracking_number', carrier='$carrier', shipping_status='$shipping_status' WHERE order_id='$order_id'";
        
        if (!mysqli_query($conn, $update_query)) {
            $error_message = "Error updating order tracking: " . mysqli_error($conn);
            break;
        }
    }

    if (!isset($error_message)) {
        $success_message = "Order tracking updated successfully!";
    }
}

// Fetch existing orders for the form
$order_query = "SELECT order_id, customer_name, tracking_number, carrier, shipping_status FROM orders";
$order_result = mysqli_query($conn, $order_query);

$orders = [];
if (mysqli_num_rows($order_result) > 0) {
    while ($row = mysqli_fetch_assoc($order_result)) {
        $orders[] = $row;
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Multiple Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h2>Update Multiple Order Tracking</h2>
    <?php if (isset($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <form method="post" action="update_order_tracking.php">
        <?php foreach ($orders as $order) { ?>
            <div class="order-form row">
                <input type="hidden" name="orders[<?php echo $order['order_id']; ?>][order_id]" value="<?php echo $order['order_id']; ?>">
                
                <div class="col-md-3 mb-3">
                    <label for="tracking_number_<?php echo $order['order_id']; ?>" class="form-label">Tracking Number</label>
                    <input type="text" class="form-control" id="tracking_number_<?php echo $order['order_id']; ?>" name="orders[<?php echo $order['order_id']; ?>][tracking_number]" value="<?php echo $order['tracking_number']; ?>" required>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="carrier_<?php echo $order['order_id']; ?>" class="form-label">Carrier</label>
                    <input type="text" class="form-control" id="carrier_<?php echo $order['order_id']; ?>" name="orders[<?php echo $order['order_id']; ?>][carrier]" value="<?php echo $order['carrier']; ?>" required>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="shipping_status_<?php echo $order['order_id']; ?>" class="form-label">Shipping Status</label>
                    <select class="form-select" id="shipping_status_<?php echo $order['order_id']; ?>" name="orders[<?php echo $order['order_id']; ?>][shipping_status]" required>
                        <option value="Pending" <?php echo ($order['shipping_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Shipped" <?php echo ($order['shipping_status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                        <option value="Delivered" <?php echo ($order['shipping_status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                        <option value="Returned" <?php echo ($order['shipping_status'] == 'Returned') ? 'selected' : ''; ?>>Returned</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Customer</label>
                    <p class="form-control-plaintext"><?php echo $order['customer_name']; ?></p>
                </div>
            </div>
        <?php } ?>
        
        <button type="submit" name="update_tracking" class="btn btn-primary">Update Tracking</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
