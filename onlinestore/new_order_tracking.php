<?php
session_start();
include('includes/db.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user
$orders_query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$orders_stmt = $conn->prepare($orders_query);
$orders_stmt->bind_param("i", $user_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .no-orders {
            text-align: center;
            color: #666;
            font-size: 18px;
            margin-top: 20px;
        }
        .view-details, .track-order {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .view-details:hover, .track-order:hover {
            background-color: #0056b3;
        }
        .order-status {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Tracking</h1>

        <?php if ($orders_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Track Order</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                    <td><span class="order-status"><?php echo htmlspecialchars($order['order_status']); ?></span></td>
                    <td>
                        <a href="new_order_tracking_details.php?order_id=<?php echo $order['order_id']; ?>" class="track-order">Track Order</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="no-orders">You have no orders yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$orders_stmt->close();
$conn->close();
?>
