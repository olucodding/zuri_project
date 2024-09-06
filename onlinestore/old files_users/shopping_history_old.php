<?php
session_start();
require 'includes/db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch order history for the logged-in user
$sql = "
    SELECT o.order_id, o.order_date, o.total_amount, o.status,
           oi.product_name, oi.product_image, oi.quantity
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$order_history = [];
while ($row = mysqli_fetch_assoc($result)) {
    $order_id = $row['order_id'];
    if (!isset($order_history[$order_id])) {
        $order_history[$order_id] = [
            'order_date' => $row['order_date'],
            'total_amount' => $row['total_amount'],
            'status' => $row['status'],
            'items' => []
        ];
    }
    $order_history[$order_id]['items'][] = [
        'product_name' => $row['product_name'],
        'product_image' => $row['product_image'],
        'quantity' => $row['quantity']
    ];
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping History</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>

<?php include 'includes/navbar.php'; ?> <!-- Include the navigation bar -->

<div class="container">
    <h2>Your Shopping History</h2>

    <?php if (empty($order_history)): ?>
        <p>You have no past orders.</p>
    <?php else: ?>
        <?php foreach ($order_history as $order_id => $order): ?>
            <div class="order">
                <h3>Order ID: <?= $order_id ?></h3>
                <p>Order Date: <?= $order['order_date'] ?></p>
                <p>Total Amount: $<?= number_format($order['total_amount'], 2) ?></p>
                <p>Status: <?= $order['status'] ?></p>

                <h4>Items:</h4>
                <ul>
                    <?php foreach ($order['items'] as $item): ?>
                        <li>
                            <img src="<?= $item['product_image'] ?>" alt="<?= $item['product_name'] ?>" width="50">
                            <?= $item['product_name'] ?> (Quantity: <?= $item['quantity'] ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>
</html>
