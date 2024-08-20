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

// Fetch order items
$order_items_query = "SELECT oi.*, p.product_name, p.product_image 
                      FROM order_items oi
                      JOIN products p ON oi.product_id = p.product_id 
                      WHERE oi.order_id = ?";
$order_items_stmt = $conn->prepare($order_items_query);
$order_items_stmt->bind_param("i", $order_id);
$order_items_stmt->execute();
$order_items_result = $order_items_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <script>

window.addEventListener('popstate', function(event) {
    if (sessionStorage.getItem('navigationHandled') !== 'true') {
        if (confirm("Are you sure you want to go back?")) {
            sessionStorage.setItem('navigationHandled', 'true');
            history.back();
        } else {
            sessionStorage.setItem('navigationHandled', 'true');
            history.pushState(null, null, window.location.href);
        }
    } else {
        sessionStorage.setItem('navigationHandled', 'false');
    }
});

history.pushState(null, null, window.location.href);
sessionStorage.setItem('navigationHandled', 'false');


    </script>


    <style>
        /* Responsive and user-friendly styles */
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
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table th {
            background-color: #f4f4f4;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        .responsive-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        a {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        a:hover {
            background-color: #0056b3;
        }
        @media (max-width: 600px) {
            table th, table td {
                padding: 8px;
                font-size: 14px;
            }
            a {
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You for Your Order!</h1>

        <p>Your order has been successfully placed. Below are the details of your order:</p>

        <h2>Order ID: <?php echo htmlspecialchars($order['order_id']); ?></h2>
        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
        <p><strong>Total Amount:</strong> $<?php echo number_format($order['total'], 2); ?></p>
        <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>

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
                    <td><img src="manager/uploads/products/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="responsive-img"></td>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo intval($item['quantity']); ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <p>If you have any questions or need further assistance, please contact our customer support.</p>

        <a href="sample_continue_shopping.php">Continue Shopping</a> | <a href="new_order_history.php">View Order History</a>
    </div>
</body>
</html>

<?php
$order_stmt->close();
$order_items_stmt->close();
$conn->close();
?>
