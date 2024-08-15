<?php
session_start();
include 'db.php';

$cart_id = $_POST['cart_id'];
$quantity = $_POST['quantity'];

// Update cart item quantity
$query = "UPDATE cart SET quantity = $quantity WHERE id = $cart_id";
mysqli_query($conn, $query);

header("Location: shopping_cart.php");
?>
