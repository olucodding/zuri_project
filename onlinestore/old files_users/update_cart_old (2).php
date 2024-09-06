<?php
session_start();
require '../includes/db.php'; // Include database connection if needed

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // The 'cart' array is expected to have 'product_id' as the key and 'quantity' as the value
    if (isset($_POST['cart']) && is_array($_POST['cart'])) {
        foreach ($_POST['cart'] as $product_id => $quantity) {
            // Update the quantity for each product in the cart
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['product_id'] == $product_id) {
                        $item['quantity'] = $quantity;
                        $item['total_price'] = $item['price'] * $quantity;
                        break;
                    }
                }
            }
        }

        // Redirect to the cart view page with a success message
        header("Location: shopping_cart.php?success=" . urlencode('Cart updated successfully!'));
        exit();
    } else {
        // If the cart data is missing or invalid, redirect with an error message
        header("Location: shopping_cart.php?error=" . urlencode('Invalid cart data.'));
        exit();
    }
} else {
    // If accessed without POST request, redirect to the cart view page
    header("Location: shopping_cart.php");
    exit();
}
