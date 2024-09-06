<?php
session_start();

// Ensure the user is logged in before proceeding to payment
if (!isset($_SESSION['user_id'])) {
    header('Location: users/customer_login.php');
    exit();
}

// Include the database connection file
include '/includes/db.php';

// Include your payment gateway library (e.g., Stripe, PayPal)
require_once '../vendor/autoload.php';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_method'])) {
    // Collect and sanitize user input
    $user_id = $_SESSION['user_id'];
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $total_amount = floatval($_POST['total_amount']); // ensure the amount is properly validated

    // Validate and prepare the payment information
    try {
        // Example: Process payment with a payment gateway (e.g., Stripe)
        \Stripe\Stripe::setApiKey('your_stripe_secret_key');

        $payment_intent = \Stripe\PaymentIntent::create([
            'amount' => $total_amount * 100, // convert amount to cents for Stripe
            'currency' => 'usd',
            'payment_method' => $payment_method,
            'confirmation_method' => 'automatic',
            'confirm' => true,
        ]);

        // If payment succeeds
        if ($payment_intent->status == 'succeeded') {
            // Save payment information to the database
            $stmt = $conn->prepare("INSERT INTO payments (user_id, payment_method, amount, payment_status) VALUES (?, ?, ?, ?)");
            $payment_status = 'Completed';
            $stmt->bind_param("isds", $user_id, $payment_method, $total_amount, $payment_status);

            if ($stmt->execute()) {
                // Redirect to a success page
                header('Location: confirmation.php');
                exit();
            } else {
                $error_message = "Error processing payment: " . $stmt->error;
            }
        } else {
            $error_message = "Payment failed, please try again.";
        }
    } catch (Exception $e) {
        // Handle payment gateway exceptions
        $error_message = "Error processing payment: " . $e->getMessage();
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
    <title>Secure Payment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="content-container">
    <h2>Secure Payment</h2>
    <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

    <form method="post" action="payment.php">
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select id="payment_method" name="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="stripe">Stripe</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>
        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="number" id="total_amount" name="total_amount" required>
        </div>
        <button type="submit">Submit Payment</button>
    </form>
</div>

</body>
</html>
