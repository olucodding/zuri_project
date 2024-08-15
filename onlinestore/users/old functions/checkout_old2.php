<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit;
}

// Include the database connection file
include '../includes/db.php';

// Fetch the products in the cart
$cart_items = $_SESSION['cart'];
$subtotal = 0;
$products = [];

foreach ($cart_items as $product_id => $quantity) {
    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        $product['quantity'] = $quantity;
        $products[] = $product;
        $subtotal += $product['price'] * $quantity;
    }
}

// Calculate shipping rate (7.5% of the subtotal)
$shipping_rate = $subtotal * 0.075;
$total = $subtotal + $shipping_rate;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $zip_code = mysqli_real_escape_string($conn, $_POST['zip_code']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // Insert order details into the database
    $order_query = "INSERT INTO orders (user_id, total, shipping_address, payment_method) VALUES ('{$_SESSION['user_id']}', '$total', '$address, $city, $state, $zip_code', '$payment_method')";
    if (mysqli_query($conn, $order_query)) {
        $order_id = mysqli_insert_id($conn);

        // Insert order items into the database
        foreach ($cart_items as $product_id => $quantity) {
            $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '{$products[$product_id]['price']}')";
            mysqli_query($conn, $order_item_query);
        }


        // Process payment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    $order_id = $_SESSION['order_id']; // Assuming order ID is stored in session

    if ($payment_method == 'bank_transfer') {
        $file_name = $_FILES['transfer_proof']['name'];
        $file_tmp = $_FILES['transfer_proof']['tmp_name'];
        $upload_dir = 'uploads/transfer_proofs/';
        $file_path = $upload_dir . $file_name;

        // Move the uploaded file to the destination
        if (move_uploaded_file($file_tmp, $file_path)) {
            $sql = "INSERT INTO order_payment (order_id, payment_method, transfer_proof) VALUES ('$order_id', 'Bank Transfer', '$file_name')";
            if (mysqli_query($conn, $sql)) {
                echo "Payment proof uploaded and order updated.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Error uploading file.";
        }
    } elseif ($payment_method == 'cod') {
        $sql = "INSERT INTO order_payment (order_id, payment_method) VALUES ('$order_id', 'Cash on Delivery')";
        if (mysqli_query($conn, $sql)) {
            echo "Order updated for Cash on Delivery.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}


        // Clear the cart
        unset($_SESSION['cart']);

        // Redirect to a thank you or order confirmation page
        header("Location: thank_you.php?order_id=$order_id");
        exit;
    } else {
        $error_message = "Error placing your order: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="checkout-container">
    <h2>Checkout</h2>

    <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

    <h3>Order Summary</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                <td><?php echo $product['quantity']; ?></td>
                <td><?php echo number_format($product['price'], 2); ?></td>
                <td><?php echo number_format($product['price'] * $product['quantity'], 2); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="order-total">
        <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
        <p>Shipping: $<?php echo number_format($shipping_rate, 2); ?></p>
        <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
    </div>

    <h3>Billing and Shipping Information</h3>
    <form method="post" action="checkout.php">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" id="city" name="city" required>
        </div>
        <div class="form-group">
            <label for="state">State</label>
            <input type="text" id="state" name="state" required>
        </div>
        <div class="form-group">
            <label for="zip_code">ZIP Code</label>
            <input type="text" id="zip_code" name="zip_code" required>
        </div>

        <h3>Payment Method</h3>
        <div class="form-group">
            <label for="payment_method">Choose Payment Method</label>
            <select id="payment_method" name="payment_method" required>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cash_on_delivery">Cash on Delivery</option>
            </select>
        </div>
        <!-- bank transfer proof -->
        <div class="form-group" id="transfer_proof_group" style="display: none;">
            <label for="transfer_proof">Attach Bank Transfer Proof:</label>
            <input type="file" id="transfer_proof" name="transfer_proof" accept=".jpg,.png,.pdf" />
        </div>

        <button type="submit" class="btn">Place Order</button>
    </form>

</div>
<script>

// Show/hide bank transfer proof upload based on selected payment method
document.getElementById('payment_method').addEventListener('change', function() {
    const proofGroup = document.getElementById('transfer_proof_group');
    if (this.value === 'bank_transfer') {
        proofGroup.style.display = 'block';
    } else {
        proofGroup.style.display = 'none';
    }
});
</script>


</body>
</html>
