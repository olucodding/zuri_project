<?php
session_start();

// Include the database connection file
include 'includes/db.php';

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

<div class="container">
    <h2>Checkout</h2>
    <form action="checkout.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="payment_method">Select Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cod">Cash on Delivery</option>
            </select>
        </div>

        <div class="form-group" id="transfer_proof_group" style="display: none;">
            <label for="transfer_proof">Attach Bank Transfer Proof:</label>
            <input type="file" id="transfer_proof" name="transfer_proof" accept=".jpg,.png,.pdf" />
        </div>

        <button type="submit">Submit</button>
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
