<?php
session_start();
include '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch items from the cart for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT c.id, c.quantity, c.total_price, p.product_name, p.product_image 
          FROM cart c
          JOIN products p ON c.product_id = p.product_id
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>

<div class="container mt-5">
    <h2>Your Shopping Cart</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Image</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><img src="../manager/uploads/products/<?php echo htmlspecialchars($row['product_image']); ?>" alt="Product Image" width="50"></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                    <td>
                        <a href="update_cart.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Update Cart</a>
                        <a href="delete_from_cart.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

<script src="../js/jquery-3.5.1.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
