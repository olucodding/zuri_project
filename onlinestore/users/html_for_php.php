<?php
session_start();
include '../includes/db.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header('Location: customer_login.php');
    exit();
}

// Fetch user details using prepared statements
$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$user_query->bind_param('i', $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();
$user_query->close(); // Close the prepared statement

// Fetch low stock alerts using prepared statements
$low_stock_query = $conn->prepare("SELECT * FROM products WHERE quantity < ?");
$low_stock_limit = 10;
$low_stock_query->bind_param('i', $low_stock_limit);
$low_stock_query->execute();
$low_stock_result = $low_stock_query->get_result();
$low_stock_query->close(); // Close the prepared statement

// Fetch user's orders using prepared statements
$order_query = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$order_query->bind_param('i', $user_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order_query->close(); // Close the prepared statement

// Fetch order history using prepared statements
$order_history_query = $conn->prepare("SELECT * FROM order_history WHERE user_id = ?");
$order_history_query->bind_param('i', $user_id);
$order_history_query->execute();
$order_history_result = $order_history_query->get_result();
$order_history_query->close(); // Close the prepared statement

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../styles/styles.css"> <!-- Include your stylesheet -->
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h1>
        <nav>
            <ul>
                <li><a href="customer_dashboard.php">Dashboard</a></li>
                <li><a href="customer_orders.php">My Orders</a></li>
                <li><a href="customer_profile.php">Profile</a></li>
                <li><a href="customer_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Low Stock Alerts -->
        <section>
            <h2>Low Stock Alerts</h2>
            <?php if ($low_stock_result->num_rows > 0): ?>
                <ul>
                    <?php while ($product = $low_stock_result->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($product['product_name']); ?>: Only <?php echo htmlspecialchars($product['quantity']); ?> left in stock!</li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No low stock alerts at the moment.</p>
            <?php endif; ?>
        </section>

        <!-- User Orders -->
        <section>
            <h2>Your Orders</h2>
            <?php if ($order_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $order_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have no orders yet.</p>
            <?php endif; ?>
        </section>

        <!-- Order History -->
        <section>
            <h2>Order History</h2>
            <?php if ($order_history_result->num_rows > 0): ?>
                <ul>
                    <?php while ($history = $order_history_result->fetch_assoc()): ?>
                        <li>Order ID: <?php echo htmlspecialchars($history['order_id']); ?> - Date: <?php echo htmlspecialchars($history['order_date']); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No order history available.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Your Company Name. All rights reserved.</p>
    </footer>
</body>
</html>
