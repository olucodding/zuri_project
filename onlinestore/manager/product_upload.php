<?php
// Include the database connection file
include '../includes/db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_products'])) {
    // Get the product details from the form
    $product_names = $_POST['product_name'];
    $categories = $_POST['category'];
    $descriptions = $_POST['description'];
    $prices = $_POST['price'];
    $quantities = $_POST['quantity'];
    
    // Process each product
    $total_products = count($product_names);
    for ($i = 0; $i < $total_products; $i++) {
        $product_name = mysqli_real_escape_string($conn, $product_names[$i]);
        $category = mysqli_real_escape_string($conn, $categories[$i]);
        $description = mysqli_real_escape_string($conn, $descriptions[$i]);
        $price = mysqli_real_escape_string($conn, $prices[$i]);
        $quantity = mysqli_real_escape_string($conn, $quantities[$i]);

        // Handle file upload
        $product_image = $_FILES['product_image']['name'][$i];
        $image_tmp_name = $_FILES['product_image']['tmp_name'][$i];
        $image_error = $_FILES['product_image']['error'][$i];

        if ($image_error === UPLOAD_ERR_OK) {
            $image_extension = pathinfo($product_image, PATHINFO_EXTENSION);
            $image_new_name = uniqid('', true) . '.' . $image_extension;
            $image_upload_path = '../uploads/' . $image_new_name;
            
            if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                $product_image_path = $image_new_name;
            } else {
                $product_image_path = null; // Handle the error appropriately
            }
        } else {
            $product_image_path = null; // Handle the error appropriately
        }

        // Insert the product into the database
        $sql = "INSERT INTO products (product_name, product_category, description, price, quantity, product_image) 
                VALUES ('$product_name', '$category', '$description', '$price', '$quantity', '$product_image_path')";
        
        if (!mysqli_query($conn, $sql)) {
            echo "Error: " . mysqli_error($conn);
            exit;
        }
    }
    
    echo "Products uploaded successfully.";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Multiple Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="upload-container">
        <h2>Upload Multiple Products</h2>
        <form action="product_upload.php" method="post" enctype="multipart/form-data">
            <div id="products-container">
                <div class="product-entry">
                    <h3>Product 1</h3>
                    <label for="product_name[]">Product Name:</label>
                    <input type="text" name="product_name[]" required>
                    
                    <label for="category[]">Category:</label>
                    <input type="text" name="category[]" required>
                    
                    <label for="description[]">Description:</label>
                    <textarea name="description[]" required></textarea>
                    
                    <label for="price[]">Price:</label>
                    <input type="number" step="0.01" name="price[]" required>
                    
                    <label for="quantity[]">Quantity:</label>
                    <input type="number" name="quantity[]" required>
                    
                    <label for="product_image[]">Product Image:</label>
                    <input type="file" name="product_image[]" accept="image/*" required>
                </div>
            </div>
            <button type="button" id="add-product">Add Another Product</button>
            <button type="submit" name="upload_products">Upload Products</button>
            <button type="reset" class="reset-button">Reset</button> <!-- Reset Button -->
        </form>
    </div>

    <script>
        document.getElementById('add-product').addEventListener('click', function() {
            let container = document.getElementById('products-container');
            let productCount = container.querySelectorAll('.product-entry').length + 1;
            let newProductEntry = `
                <div class="product-entry">
                    <h3>Product ${productCount}</h3>
                    <label for="product_name[]">Product Name:</label>
                    <input type="text" name="product_name[]" required>
                    
                    <label for="category[]">Category:</label>
                    <input type="text" name="category[]" required>
                    
                    <label for="description[]">Description:</label>
                    <textarea name="description[]" required></textarea>
                    
                    <label for="price[]">Price:</label>
                    <input type="number" step="0.01" name="price[]" required>
                    
                    <label for="quantity[]">Quantity:</label>
                    <input type="number" name="quantity[]" required>
                    
                    <label for="product_image[]">Product Image:</label>
                    <input type="file" name="product_image[]" accept="image/*" required>
                </div>`;
            container.insertAdjacentHTML('beforeend', newProductEntry);
        });
    </script>
</body>
</html>
