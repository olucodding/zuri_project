<?php
session_start();
require '../includes/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Fetch product details from the database
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    if ($product) {
        $product_name = $product['product_name'];
        $product_image = $product['product_image'];
        $price = $product['price'];
        $total_price = $price * $quantity;

        // Prepare the cart item
        $cart_item = [
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_image' => $product_image,
            'price' => $price,
            'quantity' => $quantity,
            'total_price' => $total_price,
        ];

        // Check if the cart session variable is set
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
        } else {
            $cart = [];
        }

        // Check if the product is already in the cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $product_id) {
                // If product is already in the cart, update the quantity and total price
                $item['quantity'] += $quantity;
                $item['total_price'] = $item['price'] * $item['quantity'];
                $found = true;
                break;
            }
        }

        // If the product is not in the cart, add it
        if (!$found) {
            $cart[] = $cart_item;
        }

        // Save the updated cart back to the session
        $_SESSION['cart'] = $cart;

        // Redirect to the cart page or show success message
        header("Location: view_cart.php?success=" . urlencode('Product added to cart successfully!'));
        exit();
    } else {
        // If product is not found, show an error
        header("Location: shop.php?error=" . urlencode('Product not found.'));
        exit();
    }
} else {
    // If accessed without POST request, redirect to the shop
    header("Location: shop.php");
    exit();
}
