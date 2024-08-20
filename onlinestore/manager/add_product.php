<?php
// Include database connection file
include('../includes/db.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $supplier = $_POST['supplier'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $product_status = $_POST['product_status'];

    // Handle file upload for product image
    $target_dir = "uploads/";
    $product_image = $target_dir . basename($_FILES["product_image"]["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($product_image, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if ($check !== false) {
        $upload_ok = 1;
    } else {
        echo "File is not an image.";
        $upload_ok = 0;
    }

    // Check if file already exists
    if (file_exists($product_image)) {
        echo "Sorry, file already exists.";
        $upload_ok = 0;
    }

    // Check file size
    if ($_FILES["product_image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $upload_ok = 0;
    }

    // Allow certain file formats
    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $upload_ok = 0;
    }

    // Check if $upload_ok is set to 0 by an error
    if ($upload_ok == 0) {
        echo "Sorry, your file was not uploaded.";
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $product_image)) {
            echo "The file " . htmlspecialchars(basename($_FILES["product_image"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Insert the data into the database
    $sql = "INSERT INTO products (product_name, category, supplier, quantity, price, description, product_image, product_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssidsis", $product_name, $category, $supplier, $quantity, $price, $description, $product_image, $product_status);

    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
    <h2>Add Product</h2>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required><br>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required><br>

        <label for="supplier">Supplier:</label>
        <input type="text" id="supplier" name="supplier" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="product_image">Product Image:</label>
        <input type="file" id="product_image" name="product_image" required><br>

        <label for="product_status">Product Status:</label>
        <select id="product_status" name="product_status" required>
            <option value="available">Available</option>
            <option value="out_of_stock">Out of Stock</option>
            <option value="discontinued">Discontinued</option>
        </select><br>

        <input type="submit" value="Add Product">
    </form>
</body>
</html>
