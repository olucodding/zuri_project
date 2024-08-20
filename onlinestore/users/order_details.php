<?php
session_start();
include '../includes/db.php';

if (!isset($_GET['order_id'])) {
    header("Location: order_tracking.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details
$query = "SELECT * FROM order_items WHERE order_id = '$order_id'";
$result = mysqli_query($conn, $query);

// Fetch order info (e.g., status, date, etc.)
$order_query = "SELECT * FROM orders WHERE id = '$order_id'";
$order_result = mysqli_query($conn, $order_query);
$order_info = mysqli_fetch_assoc($order_result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
</head>
<body>
    <h2>Order Details for Order ID: <?php echo $order_id; ?></h2>
    <p><strong>Order Date:</strong> <?php echo $order_info['order_date']; ?></p>
    <p><strong>Status:</strong> <?php echo $order_info['status']; ?></p>
    <p><strong>Total:</strong> <?php echo $order_info['total']; ?></p>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table border="1">
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php while ($item = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><img src="<?php echo $item['product_image']; ?>" alt="Product Image" width="100"></td>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['price']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No items found for this order.</p>
    <?php endif; ?>
    <a href="order_tracking.php">Back to Order Tracking</a>
</body>
</html>
