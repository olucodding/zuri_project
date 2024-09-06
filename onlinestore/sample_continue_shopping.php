<?php
session_start();

// Include the database connection file
include 'includes/db.php';

// Check if the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: customer_login.php");
//     exit();
// }


// Fetch up to 40 products that are active and not hidden
$query = "SELECT * FROM products WHERE product_status = 'active' AND hidden = 0 LIMIT 40";
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($conn);

// Function to add product to the cart
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {
        // If the product is already in the cart, update the quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // If the product is not in the cart, add it with the specified quantity
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continue Shopping</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
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
            color: #2e1c5f;
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
            background-color: #133451;
        }
        .view-more:hover {
            background-color: #0103ec;
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

<div class="container mt-5">

    <div class="container">
        <div class="cart-checkout">
            <a href="new_cart.php">View Shopping Cart</a>
            <a href="new_order_history.php">View Order History</a>
            <a href="new_checkout.php">Checkout</a>
            <a href="customer_login.php">My Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <h2 class="text-center">Our Products</h2>

    <div class="row product-grid">
        <?php foreach ($products as $product): ?>
            <div class="col-md-3 mb-4">
                <div class="card product-item">
                    <img src="manager/uploads/products/<?php echo htmlspecialchars($product['product_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text">Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <p class="card-text">Category: <?php echo htmlspecialchars($product['category']); ?></p>
                        <p class="card-text">Brand: <?php echo htmlspecialchars($product['brand']); ?></p>
                        <form method="POST" action="new_add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <div class="form-group">
                                <label for="quantity-<?php echo $product['product_id']; ?>">Quantity:</label>
                                <input type="number" name="quantity" id="quantity-<?php echo $product['product_id']; ?>" class="form-control" value="1" min="1">
                            </div>
                            <button type="submit" name="add_to_cart" class="btn add-to-cart btn-block">Add to Cart</button>
                        </form>
                        <a href="new_view_product.php?id=<?php echo $product['product_id']; ?>" class="btn view-more btn-block mt-2">View More</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
