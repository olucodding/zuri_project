<?php
session_start();
require '../includes/db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: shopping_cart.php?error=" . urlencode('Your cart is empty.'));
    exit();
}

// Retrieve shipping and billing details from POST request (assuming the user filled out a form)
$shipping_address = $_POST['shipping_address'] ?? '';
$billing_address = $_POST['billing_address'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';

// Calculate the total amount
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Start a transaction
mysqli_begin_transaction($conn);

try {
    // Insert the order details into the orders table
    $sql = "INSERT INTO orders (user_id, order_date, total_amount, status, shipping_address, billing_address, phone_number) 
            VALUES (?, NOW(), ?, 'Pending', ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'idsss', $user_id, $total_amount, $shipping_address, $billing_address, $phone_number);
    mysqli_stmt_execute($stmt);

    // Get the generated order_id
    $order_id = mysqli_insert_id($conn);

    // Insert each item into the order_items table
    foreach ($_SESSION['cart'] as $item) {
        $sql = "INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, price) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iissid', $order_id, $item['product_id'], $item['product_name'], $item['product_image'], $item['quantity'], $item['price']);
        mysqli_stmt_execute($stmt);
    }

    // Clear the cart
    unset($_SESSION['cart']);

    // Commit the transaction
    mysqli_commit($conn);

    // Redirect to thank you page
    header("Location: thank_you.php?order_id=" . $order_id);
    exit();

} catch (Exception $e) {
    // Rollback the transaction in case of an error
    mysqli_rollback($conn);

    // Redirect to the cart page with an error message
    header("Location: shopping_cart.php?error=" . urlencode('An error occurred while processing your order. Please try again.'));
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
