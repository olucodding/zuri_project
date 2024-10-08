<?php
session_start();
include('includes/db.php'); // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user information
$user_query = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Fetch cart items
$query = "SELECT cart.product_id, cart.quantity, cart.product_name, cart.product_price, cart.product_image, 
                 (cart.quantity * cart.product_price) AS total_price
          FROM cart
          JOIN products ON cart.product_id = products.product_id
          WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total = 0;
$shipping_rate = 0.075; // 7.5% shipping rate

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total += $row['total_price'];
}

$shipping_cost = $total * $shipping_rate;
$grand_total = $total + $shipping_cost;

// Error messages array
$errors = [];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $shipping_address = trim($_POST['shipping_address']);
    $payment_method = $_POST['payment_method'];
    $proof_of_payment = null;

    // Validate customer name
    if (empty($customer_name)) {
        $errors[] = "Your name is required.";
    }

    // Validate shipping address
    if (empty($shipping_address)) {
        $errors[] = "Shipping address is required.";
    }

    // Validate payment method and handle proof of payment
    if ($payment_method === 'Bank Transfer') {
        if (!isset($_FILES['proof_of_payment']) || $_FILES['proof_of_payment']['error'] != UPLOAD_ERR_OK) {
            $errors[] = "Proof of payment is required for bank transfer.";
        } else {
            $allowed_file_types = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!in_array($_FILES['proof_of_payment']['type'], $allowed_file_types)) {
                $errors[] = "Invalid file type for proof of payment. Allowed types: JPEG, PNG, PDF.";
            }

            // Validate file size (e.g., max 5MB)
            $max_file_size = 5 * 1024 * 1024;
            if ($_FILES['proof_of_payment']['size'] > $max_file_size) {
                $errors[] = "Proof of payment file size exceeds 5MB.";
            }

            // Move the uploaded file if no errors
            if (empty($errors)) {
                $target_dir = "manager/uploads/transfer_proofs/";
                $proof_of_payment = $target_dir . basename($_FILES['proof_of_payment']['name']);
                if (!move_uploaded_file($_FILES['proof_of_payment']['tmp_name'], $proof_of_payment)) {
                    $errors[] = "Failed to upload proof of payment.";
                }
            }
        }
    }

    // If no errors, proceed with order processing
    if (empty($errors)) {
        $conn->begin_transaction(); // Start transaction

        try {
            // Insert the order into the orders table
            $order_query = "INSERT INTO orders (user_id, total, customer_name, shipping_address, payment_method, shipping_status, created_at) 
                            VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";
            $order_stmt = $conn->prepare($order_query);
            $order_stmt->bind_param("idsss", $user_id, $grand_total, $customer_name, $shipping_address, $payment_method);
            $order_stmt->execute();
            $order_id = $order_stmt->insert_id;

            // Insert each item into the order_items table
            foreach ($cart_items as $item) {
                $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price, created_at) 
                               VALUES (?, ?, ?, ?, NOW())";
                $item_stmt = $conn->prepare($item_query);
                $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['product_price']);
                $item_stmt->execute();
            }

            // Clear the cart
            $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
            $clear_cart_stmt = $conn->prepare($clear_cart_query);
            $clear_cart_stmt->bind_param("i", $user_id);
            $clear_cart_stmt->execute();

            $conn->commit(); // Commit transaction

            // Redirect to order confirmation page
            header("Location: new_order_confirmation.php?order_id=" . $order_id);
            exit();
        } catch (Exception $e) {
            $conn->rollback(); // Rollback transaction
            $errors[] = "Failed to process the order: " . $e->getMessage();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <script>
        window.addEventListener('popstate', function(event) {
            if (sessionStorage.getItem('navigationHandled') !== 'true') {
                if (confirm("Are you sure you want to go back?")) {
                    sessionStorage.setItem('navigationHandled', 'true');
                    history.back();
                } else {
                    sessionStorage.setItem('navigationHandled', 'true');
                    history.pushState(null, null, window.location.href);
                }
            } else {
                sessionStorage.setItem('navigationHandled', 'false');
            }
        });

        history.pushState(null, null, window.location.href);
        sessionStorage.setItem('navigationHandled', 'false');
    </script>

    <style>
        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .error-messages {
            color: red;
            margin-bottom: 20px;
        }
        .totals {
            margin-top: 20px;
            font-size: 18px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        @media (max-width: 768px) {
            .product-img {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Checkout</h1>

        <?php
        // Display errors if any
        if (!empty($errors)) {
            echo '<div class="error-messages">';
            foreach ($errors as $error) {
                echo '<p>' . htmlspecialchars($error) . '</p>';
            }
            echo '</div>';
        }
        ?>

        <form method="post" action="new_checkout.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="customer_name">Your Name</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
            </div>

            <div class="form-group">
                <label for="shipping_address">Shipping Address</label>
                <textarea name="shipping_address" id="shipping_address" class="form-control" rows="4" required><?php echo htmlspecialchars($user['shipping_address']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-control" required onchange="toggleProofOfPayment()">
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <!-- Add more payment options as needed -->
                </select>
            </div>

            <div class="form-group" id="proof_of_payment_section" style="display:none;">
                <label for="proof_of_payment">Upload Proof of Payment</label>
                <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control-file" accept="image/*,application/pdf">
            </div>

            <h2 class="mt-5">Order Summary</h2>
            <?php if (!empty($cart_items)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td><img src="uploads/<?php echo htmlspecialchars($item['product_image']); ?>" class="product-img" alt="Product Image"></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>$<?php echo number_format($item['product_price'], 2); ?></td>
                                <td>$<?php echo number_format($item['total_price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="totals">
                    <p>Subtotal: $<?php echo number_format($total, 2); ?></p>
                    <p>Shipping (7.5%): $<?php echo number_format($shipping_cost, 2); ?></p>
                    <p><strong>Grand Total: $<?php echo number_format($grand_total, 2); ?></strong></p>
                </div>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>

                <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Place Order</button>
                <a href="sample_continue_shopping.php" class="btn btn-secondary">Continue Shopping</a>
                </div>
        </form>
    </div>

    <script>
        function toggleProofOfPayment() {
            const paymentMethod = document.getElementById('payment_method').value;
            const proofOfPaymentSection = document.getElementById('proof_of_payment_section');

            if (paymentMethod === 'Bank Transfer') {
                proofOfPaymentSection.style.display = 'block';
            } else {
                proofOfPaymentSection.style.display = 'none';
            }
        }

        // Trigger the toggle function on page load to handle default selection
        window.onload = function() {
            toggleProofOfPayment();
        };
    </script>
</body>
</html>
