<?php
session_start();
include '../includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's orders
$query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Tracking</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Order Tracking</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Details</th>
            </tr>
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo $order['total']; ?></td>
                    <td><a href="order_details.php?order_id=<?php echo $order['id']; ?>">View Details</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no orders to track.</p>
    <?php endif; ?>
</body>
</html>
