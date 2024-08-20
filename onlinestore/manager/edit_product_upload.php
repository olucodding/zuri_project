<?php
session_start();
include '../includes/db.php'; // Include the database connection file

// Check if the user is logged in and has the appropriate role (e.g., admin or manager)
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit;
// }

// Check if the product ID is provided
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch the product details from the database
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Check if the product exists
    if (!$product) {
        echo "Product not found.";
        exit;
    }
} else {
    header('Location: manage_product.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $product_image = $_FILES['product_image']['name'];
    
    // Update the product details in the database
    $sql = "UPDATE products SET product_name = ?, description = ?, price = ?, product_image = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdsi', $product_name, $description, $price, $product_image, $product_id);
    
    if ($stmt->execute()) {
        // Upload the new image if provided
        if ($product_image) {
            move_uploaded_file($_FILES['product_image']['tmp_name'], 'uploads/products/' . $product_image);
        }
        header('Location: manage_product.php');
        exit;
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        input[type="file"],
        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Edit Product</h2>

<div class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image">
            <?php if ($product['product_image']) { ?>
                <p>Current Image: <img src="uploads/products/<?php echo $product['product_image']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" width="50"></p>
            <?php } ?>
        </div>
        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
