<?php
session_start();

// Include the database connection file
include 'includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the customer login page
    header('Location: customer_login.php');
    exit();
}

// Check if the "Add to Cart" button was clicked and if any products were selected
if (isset($_POST['add_to_cart']) && !empty($_POST['selected_products'])) {
    foreach ($_POST['selected_products'] as $product_id) {
        $quantity = intval($_POST['quantity_' . $product_id]);

        // Add the product to the user's cart
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('{$_SESSION['user_id']}', '$product_id', '$quantity')";
        mysqli_query($conn, $query);
    }
    
    // Display a success message and prompt user action
    echo "<script>
            alert('Product(s) added to cart successfully!');
            if (confirm('Do you want to continue shopping?')) {
                window.location.href = 'category.php';
            } else {
                window.location.href = 'cart.php';
            }
          </script>";
} else {
    // Redirect back if no products were selected
    header('Location: continue_shopping.php');
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
