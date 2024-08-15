<?php
session_start();
include '../includes/db.php';

// Fetch items from the cart
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM cart WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>
<body>
    <h2>Your Shopping Cart</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Product Image</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['product_name']; ?></td>
            <td><img src="<?php echo $row['product_image']; ?>" alt="Product Image" width="50"></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['total_price']; ?></td><br><br>
            <a href="update_cart.php?id=<?php echo $row['id']; ?>">Update Cart</a> | <a href="delete_from_cart.php?id=<?php echo $row['id']; ?>">Remove</a>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="checkout.php">Proceed to Checkout</a>
</body>
</html>
