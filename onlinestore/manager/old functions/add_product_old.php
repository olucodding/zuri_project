<?php
// Include the database connection file
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $product_category = $_POST['category'];
    $supplier = $_POST['supplier'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];

    // Handle file upload for product image
    $image = $_FILES['product_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    
    // Check if image upload is successful
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO products (product_name, description, price, category, supplier, quantity, image, status) 
                VALUES ('$product_name', '$description', '$price', '$category', '$supplier', '$quantity', '$image', '$status')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Product added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error uploading image.";
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
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css">

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


</head>
<body>



<div class="content-container">
    <h2>Add New Product</h2>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        <div>
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
        </div>
        <div>
            <label for="supplier">Supplier:</label>
            <input type="text" id="supplier" name="supplier" required>
        </div>
        <div>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
        </div>
        <div>
            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image" accept="image/*" required>
        </div>
        <div>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="available">Available</option>
                <option value="out of stock">Out of Stock</option>
            </select>
        </div>
        <div>
            <button type="submit">Add Product</button>
        </div>
    </form>
</div>



</body>
</html>
