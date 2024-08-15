<?php
// Include the database connection file
include '../includes/db.php';

// Check if the user is logged in as an admin
session_start();
// if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 'admin') {
//     header('Location: index.php'); // Redirect to login page if not admin
//     exit();
// }

// Handle form submission to update product visibility
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hide_products'])) {
    $hidden_product_ids = $_POST['hide_products']; // Get selected product IDs
    
    if (!empty($hidden_product_ids)) {
        // Prepare the list of product IDs for the SQL query
        $product_ids = implode(',', array_map('intval', $hidden_product_ids));
        
        // Update products to be hidden
        $sql = "UPDATE products SET product_status = 'Hidden' WHERE product_id IN ($product_ids)";
        if (mysqli_query($conn, $sql)) {
            $success_message = "Selected products have been hidden successfully.";
        } else {
            $error_message = "Error hiding products: " . mysqli_error($conn);
        }
    } else {
        $error_message = "No products selected.";
    }
}

// Fetch all products from the database
$sql = "SELECT product_id, product_name FROM products";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hide Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="hide-products-container">
        <h2>Hide Products</h2>
        <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

        <form action="hide_products.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Product ID</th>
                        <th>Product Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><input type="checkbox" name="product_id[]" value="<?php echo $row['product_id']; ?>"></td>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="submit" name="hide_products">Hide Selected Products</button>
        </form>
    </div>
</body>
</html>
