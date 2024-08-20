<?php
session_start();

// Check if the user is logged in and has the appropriate role (e.g., admin or manager)
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: ../index.php');
//     exit;
// }

// Include the database connection file
include '../includes/db.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    // $supplier = mysqli_real_escape_string($conn, $_POST['supplier']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    // $product_status = mysqli_real_escape_string($conn, $_POST['product_status']);

    // Handle the product image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $upload_dir = 'uploads/products/';
        $file_path = $upload_dir . $file_name;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Insert the product into the database
            $sql = "INSERT INTO products (product_name, description, price, category, quantity, image) 
                    VALUES ('$product_name', '$description', '$price', '$category', '$quantity', '$file_name')";

            if (mysqli_query($conn, $sql)) {
                $success_message = "Product uploaded successfully!";
            } else {
                $error_message = "Error uploading product: " . mysqli_error($conn);
            }
        } else {
            $error_message = "Error uploading file.";
        }
    } else {
        $error_message = "Please select a valid image file.";
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
    <title>Upload Product</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic styles for form and container */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .upload-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="file"] {
            border: none;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="upload-container">
    <h2>Upload Product</h2>

    <?php
    // Display success or error messages
    if (isset($success_message)) {
        echo "<p class='message success'>$success_message</p>";
    }
    if (isset($error_message)) {
        echo "<p class='message error'>$error_message</p>";
    }
    ?>

    <form method="post" action="product_upload_handler.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" id="product_name" name="product_name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price ($)</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select a category</option>
                <option value="electronics">Electronics</option>
                <option value="fashion">Fashion</option>
                <option value="home">Home</option>
                <!-- Add more categories as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="supplier">Supplier</label>
            <input type="text" id="supplier" name="supplier" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="product_status">Product Status</label>
            <select id="product_status" name="product_status" required>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn">Upload Product</button>
    </form>
</div>

</body>
</html>
