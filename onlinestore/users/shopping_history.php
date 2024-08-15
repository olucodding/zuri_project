<?php
session_start();
include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch order history
$query = "SELECT * FROM order_history WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
</head>
<body>
    <h2>Your Order History</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['order_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['order_date']; ?></td>
            <td><?php echo $row['order_status']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
