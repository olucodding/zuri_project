<?php
session_start();
include('includes/db.php'); // Assuming you have a file to handle database connection

// Fetch active products from the database
$products_query = "SELECT * FROM products WHERE product_status = 'active'";
$products_result = $conn->query($products_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continue Shopping</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->

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
        /* Simple CSS for styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product-item {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-item h3 {
            margin: 10px 0;
            font-size: 20px;
        }
        .product-item p {
            font-size: 16px;
            color: #555;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .buttons a {
            padding: 10px 15px;
            text-decoration: none;
            color: #fff;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .add-to-cart {
            background-color: #28a745;
        }
        .add-to-cart:hover {
            background-color: #218838;
        }
        .view-more {
            background-color: #007bff;
        }
        .view-more:hover {
            background-color: #0056b3;
        }
        .cart-checkout {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .cart-checkout a {
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .cart-checkout a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Continue Shopping</h1>

        <div class="cart-checkout">
            <a href="new_cart.php">View Cart</a>
            <a href="new_checkout.php">Checkout</a>
        </div>

        <div class="product-grid">
            <?php if ($products_result->num_rows > 0): ?>
                <?php while ($product = $products_result->fetch_assoc()): ?>
                <div class="product-item">
                    <img src="manager/uploads/products/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>"></img>
                    <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p>$<?php echo number_format($product['price'], 2); ?></p>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <div class="buttons">
                        <a href="new_add_to_cart.php?product_id=<?php echo $product['id']; ?>" class="add-to-cart">Add to Cart</a>
                        <a href="new_view_product.php?product_id=<?php echo $product['id']; ?>" class="view-more">View More</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
