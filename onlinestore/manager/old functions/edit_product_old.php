<?php
// Include the database connection file
include '../includes/db.php';

// Check if the product ID is set in the URL
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Fetch product details from the database
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Product not found.";
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $product_name = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $supplier = $_POST['supplier'];
        $quantity = $_POST['quantity'];
        $status = $_POST['status'];

        // Handle file upload for product image
        $image = $_FILES['product_image']['name'];
        if ($image) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($image);
            
            // Check if image upload is successful
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                $image_sql = ", image = '$image'";
            } else {
                echo "Error uploading image.";
                exit;
            }
        } else {
            $image_sql = "";
        }

        $sql = "UPDATE products SET product_name = '$product_name', description = '$description', price = '$price', category = '$category', supplier = '$supplier', quantity = '$quantity', status = '$status' $image_sql WHERE id = $product_id";
        
        if (mysqli_query($conn, $sql)) {
            echo "Product updated successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
    <h2>Edit Product</h2>
    <form action="edit_product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
        <div>
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>
        <div>
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
        </div>
        <div>
            <label for="supplier">Supplier:</label>
            <input type="text" id="supplier" name="supplier" value="<?php echo htmlspecialchars($product['supplier']); ?>" required>
        </div>
        <div>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
        </div>
        <div>
            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image" accept="image/*">
            <?php if ($product['image']): ?>
                <p>Current Image: <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="max-width: 100px;"></p>
            <?php endif; ?>
        </div>
        <div>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="available" <?php echo $product['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="out of stock" <?php echo $product['status'] == 'out of stock' ? 'selected' : ''; ?>>Out of Stock</option>
            </select>
        </div>
        <div>
            <button type="submit">Update Product</button>
        </div>
    </form>
</div>



</body>
</html>
