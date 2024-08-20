<?php
session_start();
include '../includes/db.php'; // Include the database connection file

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: manager_login.php');
    exit();
}

// Fetch manager details
$manager_id = $_SESSION['manager_id'];
$query = "SELECT * FROM managers WHERE manager_id='$manager_id'";
$result = mysqli_query($conn, $query);
$manager = mysqli_fetch_assoc($result);

// Fetch low stock alerts
$low_stock_query = "SELECT * FROM products WHERE quantity < 10";
$low_stock_result = mysqli_query($conn, $low_stock_query);

// Fetch recent orders
$recent_orders_query = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 10";
$recent_orders_result = mysqli_query($conn, $recent_orders_query);

// Fetch order tracking details
$order_tracking_query = "SELECT * FROM order_tracking WHERE status='pending' ORDER BY created_at DESC";
$order_tracking_result = mysqli_query($conn, $order_tracking_query);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
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


<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($manager['full_name']); ?></h2>
    <p>Manage your store efficiently from here.</p>

    <div class="dashboard-menu">
        <ul>
            <li><a href="add_product.php">Add Product</a></li>
            <li><a href="manage_product.php">Manage Product</a></li>
            <li><a href="product_upload.php">Product Upload</a></li>
            <li><a href="upload_product.php">Upload Product</a></li>
            <li><a href="manage_orders.php">Manage Orders</a></li>
            <li><a href="inventory_overview.php">Inventory Overview</a></li>
            <li><a href="monthly_order_report.php">Monthly Order Report</a></li>
            <li><a href="order_alert.php">Order Alerts</a></li>
            <li><a href="low_quantity_alert.php">Low Quantity Alerts</a></li>
            <li><a href="update_order_tracking.php">Update Order Tracking</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="update_manager_profile.php">Update Profile</a></li>
            <li><a href="hide_products.php">Hide Products</a></li>
            <li><a href="manager_dashboard2.php">Manager Dashbpard2</a></li>
            <a href="logout.php" onclick="return confirmLogout();">Logout</a>
<script>
function confirmLogout() {
    return confirm("Are you sure you want to logout?");
}
</script>

        </ul>
    </div>

    <div class="dashboard-sections">
        <div class="section">
            <h3>Low Stock Alerts</h3>
            <?php if (mysqli_num_rows($low_stock_result) > 0): ?>
                <ul>
                    <?php while ($product = mysqli_fetch_assoc($low_stock_result)): ?>
                        <li><?php echo htmlspecialchars($product['product_name']); ?> - <?php echo htmlspecialchars($product['quantity']); ?> left</li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No low stock items.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>Recent Orders</h3>
            <?php if (mysqli_num_rows($recent_orders_result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Order Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($recent_orders_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                <td><?php echo htmlspecialchars($order['total']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No recent orders.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>Order Tracking</h3>
            <?php if (mysqli_num_rows($order_tracking_result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($tracking = mysqli_fetch_assoc($order_tracking_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tracking['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($tracking['status']); ?></td>
                                <td><?php echo htmlspecialchars($tracking['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders to track.</p>
            <?php endif; ?>
        </div>
    </div>
</div>



</body>
</html>
