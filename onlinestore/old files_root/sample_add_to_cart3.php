<?php
session_start();

// Include the database connection file
include 'includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: customer_login.php');
    exit();
}

// Check if the product ID is set in the POST request
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the product is already in the cart
    $query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // If the product is already in the cart, increase the quantity
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
        mysqli_stmt_execute($stmt);
    } else {
        // If the product is not in the cart, add it
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
        mysqli_stmt_execute($stmt);
    }

    // Redirect back to the front page with a success message
    $_SESSION['cart_message'] = "Product added to cart successfully!";
    header('Location: sample_shopping_cart3.php');
    exit();
} else {
    // Redirect back to the front page if no product ID is provided
    header('Location: sample_continue_shopping.php');
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
