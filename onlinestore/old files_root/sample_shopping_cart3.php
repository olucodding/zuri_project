<?php
session_start();

// Include the database connection file
include 'includes/db.php';

// Initialize the cart array if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get product details from the database
$cart_products = [];
if (!empty($_SESSION['cart'])) {
    $product_ids = implode(',', $_SESSION['cart']);
    $query = "SELECT * FROM products WHERE product_id IN ($product_ids)";
    $result = mysqli_query($conn, $query);
    $cart_products = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Calculate total price
$total_price = 0;
foreach ($cart_products as $product) {
    $total_price += $product['price'];
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

<div class="container mt-5">
    <h2 class="text-center">Your Shopping Cart</h2>

    <?php if (empty($cart_products)): ?>
        <p class="text-center">Your cart is empty.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo htmlspecialchars($product['brand']); ?></td>
                        <td><img src="manager/uploads/products/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" width="100"></td>
                        <td>
                            <form method="POST" action="remove_from_cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <button type="submit" name="remove_from_cart" class="btn btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-right">
            <h4>Total: $<?php echo number_format($total_price, 2); ?></h4>
            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
        </div>
    <?php endif; ?>

    <div class="text-center mt-5">
        <a href="front_page.php" class="btn btn-secondary">Continue Shopping</a>
    </div>
</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
