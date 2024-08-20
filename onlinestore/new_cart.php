<?php
session_start();
include('includes/db.php'); // Assuming you have a file to handle database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle updates to quantities or removals
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        // Update quantities
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            if ($quantity > 0) {
                $update_query = "UPDATE cart SET quantity = ?, total_price = product_price * ? WHERE user_id = ? AND product_id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("iiii", $quantity, $quantity, $user_id, $product_id);
                $update_stmt->execute();
            }
        }
    } elseif (isset($_POST['remove']) && isset($_POST['product_ids'])) {
        // Remove selected items from cart
        foreach ($_POST['product_ids'] as $product_id_to_remove) {
            $remove_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
            $remove_stmt = $conn->prepare($remove_query);
            $remove_stmt->bind_param("ii", $user_id, $product_id_to_remove);
            $remove_stmt->execute();
        }
    }
}

// Fetch cart items for the current user
$query = "SELECT cart.product_id, cart.quantity, cart.product_name, cart.product_price, cart.product_image, cart.total_price
          FROM cart 
          WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total = 0;
$shipping_rate = 0.075; // 7.5% shipping rate

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total += $row['total_price'];
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
    <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Include Bootstrap CSS -->

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
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        table {
            width: 100%;
        }
        .table-responsive {
            margin-bottom: 30px;
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals p {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .actions button, .actions a {
            padding: 10px 20px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Your Shopping Cart</h1>

        <?php if (empty($cart_items)): ?>
            <p class="text-center">Your cart is empty. <a href="sample_continue_shopping.php">Continue shopping</a></p>
        <?php else: ?>
            <form method="post" action="new_cart.php">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Product</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="product_ids[]" value="<?php echo $item['product_id']; ?>">
                                    </td>
                                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                    <td><img src="manager/uploads/products/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="product-img"></td>
                                    <td>$<?php echo number_format($item['product_price'], 2); ?></td>
                                    <td>
                                        <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control">
                                    </td>
                                    <td>$<?php echo number_format($item['total_price'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="totals">
                    <p>Subtotal: $<?php echo number_format($total, 2); ?></p>
                    <p>Shipping (7.5%): $<?php echo number_format($shipping_cost, 2); ?></p>
                    <p>Total: $<?php echo number_format($grand_total, 2); ?></p>
                </div>

                <div class="actions">
                    <button type="submit" name="update" class="btn btn-primary">Update Cart</button>
                    <button type="submit" name="remove" class="btn btn-danger">Remove Selected Items</button>
                    <a href="new_checkout.php" class="btn btn-success">Proceed to Checkout</a>
                    <a href="sample_continue_shopping.php" class="btn btn-secondary">Continue Shopping</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script> <!-- Include Bootstrap JS -->
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
