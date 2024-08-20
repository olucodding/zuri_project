<?php
session_start();
include('db_connection.php'); // Assuming you have a file to handle database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You need to be logged in to place an order.");
}

$user_id = $_SESSION['user_id'];

// Fetch user information
$user_query = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Fetch cart items
$cart_query = "SELECT cart.product_id, cart.quantity, products.price 
               FROM cart 
               JOIN products ON cart.product_id = products.id 
               WHERE cart.user_id = ?";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_items = $cart_stmt->get_result();

if ($cart_items->num_rows == 0) {
    die("Your cart is empty.");
}

$total_amount = 0;
$cart_items_array = [];
while ($item = $cart_items->fetch_assoc()) {
    $total_amount += $item['price'] * $item['quantity'];
    $cart_items_array[] = $item; // Store cart items for later use
}

// Error messages array
$errors = [];

// Assume payment is processed successfully here
$payment_success = true; // This should be determined by your payment processing logic

if ($payment_success) {
    $conn->begin_transaction(); // Start transaction

    try {
        // Insert the order into the orders table
        $order_query = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, status, created_at) 
                        VALUES (?, ?, ?, ?, 'Pending', NOW())";
        $order_stmt = $conn->prepare($order_query);
        $order_stmt->bind_param("idss", $user_id, $total_amount, $user['shipping_address'], $_POST['payment_method']);
        $order_stmt->execute();
        $order_id = $order_stmt->insert_id;

        // Insert each item into the order_items table
        foreach ($cart_items_array as $item) {
            $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price, created_at) 
                           VALUES (?, ?, ?, ?, NOW())";
            $item_stmt = $conn->prepare($item_query);
            $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $item_stmt->execute();
        }

        // Clear the cart
        $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
        $clear_cart_stmt = $conn->prepare($clear_cart_query);
        $clear_cart_stmt->bind_param("i", $user_id);
        $clear_cart_stmt->execute();

        $conn->commit(); // Commit transaction

        // Redirect to order confirmation page
        header("Location: order_confirmation.php?order_id=" . $order_id);
        exit();
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction
        die("Failed to process the order: " . $e->getMessage());
    }
} else {
    // Handle payment failure (this could be more complex depending on your needs)
    die("Payment failed. Please try again.");
}

$conn->close();
?>
