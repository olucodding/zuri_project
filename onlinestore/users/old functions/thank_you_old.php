<?php
session_start();
include 'db.php';

// Assuming you are passing the order ID in the URL
$order_id = $_GET['order_id'];

// Fetch order details
$query = "SELECT oi.product_name, oi.product_image, oi.quantity, oi.price, o.total, o.order_date 
          FROM order_items oi 
          JOIN orders o ON oi.order_id = o.id 
          WHERE oi.order_id = $order_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You for Your Order!</title>
</head>
<body>
    <h2>Thank You for Your Order!</h2>

    <p>Your order has been placed successfully. Here are the details of your order:</p>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <table border="1">
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><img src="<?php echo $row['product_image']; ?>" alt="Product Image" width="100"></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <?php
            // Fetch order total and date
            $row = mysqli_fetch_assoc($result);
            echo "<p><strong>Total: </strong>" . $row['total'] . "</p>";
            echo "<p><strong>Order Date: </strong>" . $row['order_date'] . "</p>";
        ?>
    <?php else: ?>
        <p>No products found for this order.</p>
    <?php endif; ?>

    <a href="shopping_history.php">View Your Order History</a>
</body>
</html>
