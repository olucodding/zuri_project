<?php
session_start();

// Include the database connection file
include 'includes/db.php';

// Check if order_id is passed in the URL
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Invalid request.");
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$order_query = "SELECT * FROM orders WHERE order_id = ?";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows == 0) {
    die("Order not found.");
}

$order = $order_result->fetch_assoc();

// Fetch order items
$order_items_query = "SELECT oi.*, p.product_name, p.product_image 
                      FROM order_items oi
                      JOIN products p ON oi.product_id = p.product_id 
                      WHERE oi.order_id = ?";
$order_items_stmt = $conn->prepare($order_items_query);
$order_items_stmt->bind_param("i", $order_id);
$order_items_stmt->execute();
$order_items_result = $order_items_stmt->get_result();

// Close the database connection at the end of the script
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
            height: auto;
            border-radius: 8px;
        }
        .btn {
            padding: 8px 12px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            margin-right: 5px;
            display: inline-block;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .back-btn {
            display: block;
            width: 100px;
            text-align: center;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Order Details</h2>

    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
    <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
    <p><strong>Total Amount:</strong> $<?php echo number_format($order['total'], 2); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($order['order_status']); ?></p>

    <h3>Order Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $order_items_result->fetch_assoc()) : ?>
            <tr>
                <td><img src="manager/uploads/products/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>"></td>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo intval($item['quantity']); ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="new_manage_orders.php" class="btn btn-primary back-btn">Back to Orders</a>
</div>

</body>
</html>
