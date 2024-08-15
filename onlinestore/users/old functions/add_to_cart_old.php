<?php
session_start();
include 'includes/db.php';

$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$product_name = $_POST['product_name'];
$product_category = $_POST['product_category'];
$user_id = $_SESSION['user_id'];

// Fetch product details
$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

$total_price = $product['price'] * $quantity;

// Insert into cart
$query = "INSERT INTO cart (user_id, product_id, product_name, product_image, quantity, total_price)
          VALUES ($user_id, $product_id, '{$product['product_name']}', '{$product['product_image']}', $quantity, $total_price)";
mysqli_query($conn, $query);

header("Location: shopping_cart.php");
?>
