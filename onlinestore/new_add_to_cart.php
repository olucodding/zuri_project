<?php
session_start();
include('includes/db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

// Validate the product_id and quantity
if (!is_numeric($product_id) || !is_numeric($quantity) || $quantity <= 0) {
    die("Invalid product or quantity.");
}

// Fetch product details from the products table
$product_query = "SELECT products.product_name, products.product_image, products.price 
                  FROM products 
                  WHERE products.product_id = ? AND products.product_status = 'active'";
$product_stmt = $conn->prepare($product_query);
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows === 0) {
    die("Product not found or inactive.");
}

$product = $product_result->fetch_assoc();
$product_name = $product['product_name'];
$product_image = $product['product_image'];
$product_price = $product['price'];
$total_price = $product_price * $quantity;

// Check if the product is already in the cart
$query = "SELECT cart.quantity 
          FROM cart 
          WHERE cart.user_id = ? AND cart.product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If the product is already in the cart, update the quantity and total_price
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;
    $new_total_price = $product_price * $new_quantity;

    $update_query = "UPDATE cart 
                     SET cart.quantity = ?, cart.total_price = ? 
                     WHERE cart.user_id = ? AND cart.product_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("idii", $new_quantity, $new_total_price, $user_id, $product_id);
    $update_stmt->execute();
    $update_stmt->close();
} else {
    // If the product is not in the cart, insert a new record
    $insert_query = "INSERT INTO cart (user_id, product_id, quantity, product_name, product_image, total_price, created_at, product_price) 
                     VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("iiissdi", $user_id, $product_id, $quantity, $product_name, $product_image, $total_price, $product_price);
    $insert_stmt->execute();
    $insert_stmt->close();
}


$query = "SELECT cart.product_id, products.product_name, cart.quantity, cart.total_price, cart.product_image 
          FROM cart 
          JOIN products ON cart.product_id = products.product_id 
          WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = $result->fetch_all(MYSQLI_ASSOC);


$stmt->close();
$product_stmt->close();
$conn->close();

// Redirect the user to the cart page
header("Location: new_cart.php");
exit();
?>
