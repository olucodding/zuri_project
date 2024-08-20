<?php
session_start();
include('includes/db.php'); // Assuming you have a file to handle database connection

// Check if a product ID is provided
if (!isset($_GET['product_id'])) {
    die("Product not found.");
}

$product_id = intval($_GET['product_id']);

// Fetch product details from the database
$product_query = "SELECT * FROM products WHERE id = ?";
$product_stmt = $conn->prepare($product_query);
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows === 0) {
    die("Product not found.");
}

$product = $product_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?></title>
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
        .product-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        .product-image {
            flex: 1;
            max-width: 400px;
        }
        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-info {
            flex: 2;
            display: flex;
            flex-direction: column;
        }
        .product-info h1 {
            margin: 0;
            font-size: 28px;
        }
        .product-info p {
            font-size: 18px;
            color: #555;
        }
        .product-info .price {
            font-size: 24px;
            color: #e74c3c;
            margin: 10px 0;
        }
        .product-info .description {
            margin: 20px 0;
        }
        .product-info .buttons {
            display: flex;
            gap: 15px;
        }
        .product-info .buttons a {
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
        .view-cart {
            background-color: #007bff;
        }
        .view-cart:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="product-details">
            <div class="product-image">
                <img src="manager/uploads/products/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="buttons">
                    <a href="new_add_to_cart.php?product_id=<?php echo $product['id']; ?>" class="add-to-cart">Add to Cart</a>
                    <a href="new_cart.php" class="view-cart">View Cart</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$product_stmt->close();
$conn->close();
?>
