<?php
session_start();
include('includes/db.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");
    exit();
}

// Check if order_id is passed in the URL
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Invalid request.");
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

// Fetch order details
$order_query = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("ii", $order_id, $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows == 0) {
    die("Order not found.");
}

$order = $order_result->fetch_assoc();

// Fetch order tracking information
$tracking_query = "SELECT * FROM order_tracking WHERE order_id = ?";
$tracking_stmt = $conn->prepare($tracking_query);
$tracking_stmt->bind_param("i", $order_id);
$tracking_stmt->execute();
$tracking_result = $tracking_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
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
        .order-info {
            margin-bottom: 20px;
        }
        .order-info p {
            font-size: 16px;
            margin: 8px 0;
        }
        .order-info strong {
            font-weight: bold;
        }
        .no-tracking {
            text-align: center;
            color: #666;
            font-size: 18px;
        }
        .back-to-orders {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-to-orders:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Tracking Details</h1>

        <div class="order-info">
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
            <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['order_status']); ?></p>
        </div>

        <?php if ($tracking_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Tracking Status</th>
                    <th>Date</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($tracking = $tracking_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tracking['status']); ?></td>
                    <td><?php echo htmlspecialchars($tracking['date']); ?></td>
                    <td><?php echo htmlspecialchars($tracking['location']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="no-tracking">No tracking information available for this order.</p>
        <?php endif; ?>

        <a href="order_history.php" class="back-to-orders">Back to Order History</a>
    </div>
</body>
</html>

<?php
$order_stmt->close();
$tracking_stmt->close();
$conn->close();
?>
