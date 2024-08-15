<?php
session_start();
require '../includes/db.php'; // Include the database connection

// Fetch products from the database
$sql = "SELECT * FROM products WHERE quantity > 0"; // Only show products that are in stock
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continue Shopping</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>



<div class="shop-container">
    <h1>Shop</h1>

    <div class="products-grid">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="product-item">
                <img src="<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>" class="product-image">
                <h2><?php echo $row['product_name']; ?></h2>
                <p><?php echo $row['product_description']; ?></p>
                <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                    <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>">
                    
                    <!-- Quantity Input -->
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['quantity']; ?>" required>
                    
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<a href="checkout.php">Proceed to Checkout</a>

</body>


</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
