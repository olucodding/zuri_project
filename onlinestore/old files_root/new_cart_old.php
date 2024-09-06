<?php
session_start();
include('includes/db.php'); // Assuming you have a file to handle database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You need to be logged in to view your cart.");
}

$user_id = $_SESSION['user_id'];

// Handle updates to quantities or removals
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        // Update quantities
        foreach ($_POST['quantities'] as $product_id => $quantity) {
            if ($quantity > 0) {
                $update_query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
                $update_stmt->execute();
            }
        }
    } elseif (isset($_POST['remove'])) {
        // Remove item from cart
        $product_id_to_remove = $_POST['product_id'];
        $remove_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $remove_stmt = $conn->prepare($remove_query);
        $remove_stmt->bind_param("ii", $user_id, $product_id_to_remove);
        $remove_stmt->execute();
    }
}

// Fetch cart items for the current user
$query = "SELECT product_id, quantity, product_name, product_price, product_image
          FROM cart 
          JOIN products ON product = product_id 
          WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total = 0;
$shipping_rate = 0.075; // 7.5% shipping rate

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total += $row['product_price'] * $row['quantity'];
}

$shipping_cost = $total * $shipping_rate;
$grand_total = $total + $shipping_cost;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Your Shopping Cart</h1>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty. <a href="new_continue_shopping.php">Continue shopping</a></p>
    <?php else: ?>
        <form method="post" action="cart.php">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50"></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                            </td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <button type="submit" name="remove" value="1">Remove</button>
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="totals">
                <p>Subtotal: $<?php echo number_format($total, 2); ?></p>
                <p>Shipping (7.5%): $<?php echo number_format($shipping_cost, 2); ?></p>
                <p>Total: $<?php echo number_format($grand_total, 2); ?></p>
            </div>

            <div class="actions">
                <button type="submit" name="update">Update Cart</button>
                <a href="new_checkout.php">Proceed to Checkout</a>
                <a href="new_continue_shopping.php">Continue Shopping</a>
            </div>
        </form>
    <?php endif; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
