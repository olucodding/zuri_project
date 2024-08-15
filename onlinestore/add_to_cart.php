<?php
session_start();

// Include the database connection file
include 'includes/db.php';

if (isset($_POST['add_to_cart']) && !empty($_POST['selected_products'])) {
    foreach ($_POST['selected_products'] as $product_id) {
        $quantity = intval($_POST['quantity_' . $product_id]);
        
        // Add the product to the cart
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('{$_SESSION['user_id']}', '$product_id', '$quantity')";
        mysqli_query($conn, $query);
    }
    
    // Redirect to cart page or display a success message
    header('Location: cart.php');
    exit();
} else {
    // Redirect back if no products were selected
    header('Location: continue_shopping.php');
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
