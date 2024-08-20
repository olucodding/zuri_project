<?php
session_start();
include('includes/db.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");
    exit();
}

// Check if product_id is passed in the URL
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    die("Invalid request.");
}

$product_id = intval($_GET['product_id']);
$user_id = $_SESSION['user_id'];

// Hide the product by updating its status in the database
$hide_product_query = "UPDATE products SET hidden = 1 WHERE product_id = ? AND user_id = ?";
$hide_product_stmt = $conn->prepare($hide_product_query);
$hide_product_stmt->bind_param("ii", $product_id, $user_id);

if ($hide_product_stmt->execute()) {
    $message = "Product successfully hidden.";
} else {
    $message = "Failed to hide the product.";
}

$hide_product_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hide Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        p {
            margin-bottom: 20px;
            font-size: 18px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hide Product</h1>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="manager/manage_product.php" class="btn">Back to Products</a>
    </div>
</body>
</html>
