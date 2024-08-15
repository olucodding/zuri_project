<?php
session_start();
include '<includes/db.php';

// Assuming user is logged in and user ID is stored in session
$user_id = $_SESSION['user_id'];

// Step 1: Retrieve cart items from session (or database)
$cart_items = $_SESSION['cart_items']; // Assuming this is an associative array with product_id, quantity, etc.
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['quantity'] * $item['price'];
}

// Step 2: Validate cart and user information
if (empty($cart_items)) {
    // Handle empty cart case
    echo "Your cart is empty.";
    exit();
}

if (!$user_id) {
    // Handle case where user is not logged in
    echo "You must be logged in to checkout.";
    exit();
}

// Step 3: Insert the order into the orders table
$order_date = date('Y-m-d H:i:s');
$query = "INSERT INTO orders (user_id, total, order_date) VALUES ('$user_id', '$total_price', '$order_date')";
mysqli_query($conn, $query);

// Retrieve the newly created order ID
$order_id = mysqli_insert_id($conn);

// Step 4: Insert each cart item into the order_items table
foreach ($cart_items as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    $product_name = $item['product_name'];
    $product_image = $item['product_image'];

    $query = "INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, price, order_date, user_id) 
              VALUES ('$order_id', '$product_id', '$product_name', '$product_image', '$quantity', '$price', '$order_date', '$user_id')";
    mysqli_query($conn, $query);
}

// Step 5: Clear the cart (assuming cart is stored in session)
unset($_SESSION['cart_items']);

// Step 6: Redirect to the thank you page with order ID
header("Location: thank_you.php?order_id=$order_id");
exit();
?>
