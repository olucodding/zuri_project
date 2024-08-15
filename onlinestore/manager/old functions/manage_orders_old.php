<?php
// Include the database connection file
include '../includes/db.php';

// Fetch all orders from the database
$sql = "SELECT o.id, o.customer_name, o.order_date, o.status, o.total, GROUP_CONCAT(p.product_name SEPARATOR ', ') as products
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        GROUP BY o.id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    // Update order status
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $sql = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    if (mysqli_query($conn, $sql)) {
        echo "Order status updated successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    
    // Refresh page to update order list
    header("Location: manage_orders.php");
    exit;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
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


<div class="content-container">
    <h2>Manage Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['total']); ?></td>
                <td><?php echo htmlspecialchars($row['products']); ?></td>
                <td>
                    <form action="manage_orders.php" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="shipped" <?php echo $row['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="delivered" <?php echo $row['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="canceled" <?php echo $row['status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                        </select>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>



</body>
</html>
