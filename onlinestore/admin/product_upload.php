<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $supplier = $_POST['supplier'];

    // Handle product image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);

    // Insert new product into database
    $query = "INSERT INTO products (product_name, category, price, quantity, supplier, product_image) 
              VALUES ('$product_name', '$category', $price, $quantity, '$supplier', '$target_file')";
    mysqli_query($conn, $query);

    header("Location: product_list.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Product</title>
</head>
<body>
    <h2>Upload New Product</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required><br>
        <label for="category">Category:</label>
        <input type="text" name="category" required><br>
        <label for="price">Price:</label>
        <input type="number" name="price" required><br>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required><br>
        <label for="supplier">Supplier:</label>
        <input type="text" name="supplier" required><br>
        <label for="product_image">Product Image:</label>
        <input type="file" name="product_image" required><br>
        <input type="submit" value="Upload Product">
    </form>
</body>
</html>
