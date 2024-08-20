<?php
session_start();
require '../includes/db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the order_id is set in the URL
if (!isset($_GET['order_id'])) {
    header("Location: shop.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details from the database
$sql = "SELECT o.order_id, o.order_date, oi.product_name, oi.product_image, oi.quantity, oi.price 
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        WHERE o.user_id = ? AND o.order_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $user_id, $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if any order items are returned
if (mysqli_num_rows($result) == 0) {
    echo "No order details found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>

<?php include 'navbar.php'; ?> <!-- Include navigation bar -->

<div class="thank-you-container">
    <h1>Thank You for Your Order!</h1>
    <p>Your order has been placed successfully. Here are the details of your purchase:</p>

    <div class="order-details">
        <h2>Order #<?php echo $order_id; ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><img src="<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>" width="50"></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                        <td>$<?php echo number_format($row['quantity'] * $row['price'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="shop.php" class="btn">Continue Shopping</a>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
