<?php
session_start();

// Include the database connection file
include 'includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: customer_login.php');
    exit();
}

// Check if a product ID is provided
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    // Fetch product details from the database
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        // Create cart session if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            // Update the quantity
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            $_SESSION['cart'][$product_id] = [
                'product_name' => $product['product_name'],
                'price' => $product['price'],
                'category' => $product['category'],
                'brand' => $product['brand'],
                'product_image' => $product['product_image'],
                'quantity' => $quantity
            ];
        }

        // Success message
        $_SESSION['message'] = "Product added to cart successfully!";
    } else {
        $_SESSION['message'] = "Product not found.";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

// Redirect back to the front page
header('Location: users/user_dashboard.php');
exit();
?>
