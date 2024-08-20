<?php
session_start();

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: manager_login.php');
    exit();
}

// Include the database connection file
include '../includes/db.php';

// Handle form submission
if (isset($_POST['update_tracking'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $tracking_number = mysqli_real_escape_string($conn, $_POST['tracking_number']);
    $carrier = mysqli_real_escape_string($conn, $_POST['carrier']);
    $shipping_status = mysqli_real_escape_string($conn, $_POST['shipping_status']);

    // Update query
    $update_query = "UPDATE orders SET tracking_number='$tracking_number', carrier='$carrier', shipping_status='$shipping_status' WHERE order_id='$order_id'";

    if (mysqli_query($conn, $update_query)) {
        $success_message = "Order tracking updated successfully!";
    } else {
        $error_message = "Error updating order tracking: " . mysqli_error($conn);
    }
}

// Fetch existing orders for the form (for selecting an order to update)
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
    <title>Update Order Tracking</title>
    <link rel="stylesheet" href="styles.css">

    <script>
        window.addEventListener('popstate', function(event) {
            if (sessionStorage.getItem('navigationHandled') !== 'true') {
                if (confirm("Are you sure you want to go back?")) {
                    sessionStorage.setItem('navigationHandled', 'true');
                    history.back();
                } else {
                    sessionStorage.setItem('navigationHandled', 'true');
                    history.pushState(null, null, window.location.href);
                }
            } else {
                sessionStorage.setItem('navigationHandled', 'false');
            }
        });

        history.pushState(null, null, window.location.href);
        sessionStorage.setItem('navigationHandled', 'false');
    </script>
</head>
<body>

<div class="tracking-container">
    <h2>Update Order Tracking</h2>
    <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="order_id">Order ID</label>
            <select id="order_id" name="order_id" required>
                <option value="">Select an Order</option>
                <?php foreach ($orders as $order) { ?>
                    <option value="<?php echo $order['order_id']; ?>">
                        Order ID: <?php echo $order['order_id']; ?> - Customer: <?php echo $order['customer_name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tracking_number">Tracking Number</label>
            <input type="text" id="tracking_number" name="tracking_number" placeholder="Enter tracking number" required>
        </div>
        <div class="form-group">
            <label for="carrier">Carrier</label>
            <input type="text" id="carrier" name="carrier" placeholder="Enter carrier name" required>
        </div>
        <div class="form-group">
            <label for="shipping_status">Shipping Status</label>
            <select id="shipping_status" name="shipping_status" required>
                <option value="Pending">Pending</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
                <option value="Returned">Returned</option>
            </select>
        </div>
        <button type="submit" name="update_tracking">Update Tracking</button>
    </form>
</div>

</body>
</html>
