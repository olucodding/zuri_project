<?php
session_start();
include '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php"); // Redirect to login page if not logged in
    exit();
}

// Validate the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the cart item ID and new quantity from the form
    $cart_id = intval($_POST['cart_id']);
    $new_quantity = intval($_POST['quantity']);

    // Ensure quantity is greater than 0
    if ($new_quantity > 0) {
        // Update the cart item with the new quantity
        $query = "UPDATE cart SET quantity = ?, total_price = (SELECT price FROM products WHERE product_id = cart.product_id) * ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiii', $new_quantity, $new_quantity, $cart_id, $_SESSION['user_id']);
        $stmt->execute();

        // Redirect back to the cart page with a success message
        $_SESSION['message'] = "Cart updated successfully!";
        header("Location: shopping_cart.php");
    } else {
        // If quantity is less than or equal to 0, redirect with an error message
        $_SESSION['error'] = "Quantity must be greater than zero.";
        header("Location: shopping_cart.php");
    }
    $stmt->close();
} else {
    // If the request method is not POST, redirect to the cart page
    header("Location: sample_continue_shoping.php");
}

$conn->close();
exit();
?>

<form method="POST" action="update_cart.php">
    <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
    <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1" required>
    <button type="submit" class="btn btn-sm btn-primary">Update Cart</button>
</form>
