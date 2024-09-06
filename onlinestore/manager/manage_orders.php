<?php
session_start();

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: manager_login.php');
    exit();
}

// Include the database connection file
include '../includes/db.php';

// Handle form submission for updating product status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $product_status = mysqli_real_escape_string($conn, $_POST['product_status']);

    $update_query = "UPDATE order_tracking SET status = '$product_status' WHERE product_id = $product_id";
    if (mysqli_query($conn, $update_query)) {
        $success_message = "Product status updated successfully.";
    } else {
        $error_message = "Error updating product status: " . mysqli_error($conn);
    }
}

// Fetch orders and related product information
$sql = "SELECT orders.order_id, orders.order_date, orders.customer_name, products.product_id, products.product_name, products.product_status
        FROM orders
        INNER JOIN products ON orders.product_id = products.product_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
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
</head>
<body>

<div class="content-container">
    <h2>Manage Orders</h2>
    <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Customer Name</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['product_status']); ?></td>
                <td>
                    <form action="manage_orders.php" method="POST" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        <select name="product_status" required>
                            <option value="Visible" <?php echo $row['product_status'] == 'Visible' ? 'selected' : ''; ?>>Visible</option>
                            <option value="Hidden" <?php echo $row['product_status'] == 'Hidden' ? 'selected' : ''; ?>>Hidden</option>
                        </select>
                        <button type="submit">Update Status</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
