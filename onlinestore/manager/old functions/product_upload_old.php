<?php
// Include the database connection file
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle file upload
    $product_id = intval($_POST['product_id']);
    $image = $_FILES['product_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES['product_image']['tmp_name']);
    if ($check !== false) {
        echo "File is an image - " . $check['mime'] . ".";
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES['product_image']['size'] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            // Update the product with the new image
            $sql = "UPDATE products SET image = '$image' WHERE id = $product_id";
            if (mysqli_query($conn, $sql)) {
                echo "The file ". htmlspecialchars($image). " has been uploaded.";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Multiple Products</title>
    <link rel="stylesheet" href="css/styles.css">
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
