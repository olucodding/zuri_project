<?php
session_start();

// Include the database connection file
include '../includes/db.php';

// Check if the user is logged in and has the appropriate role (e.g., admin or manager)
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: ../manager_login.php');
//     exit();
// }



// Handle delete action
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        header('Location: manage_product.php');
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }
    $stmt->close();
}

// Handle hide/unhide action
if (isset($_GET['action']) && isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $action = $_GET['action'] === 'hide' ? 1 : 0;

    $stmt = $conn->prepare("UPDATE products SET hidden = ? WHERE product_id = ?");
    $stmt->bind_param("ii", $action, $product_id);
    if ($stmt->execute()) {
        header('Location: manage_product.php');
        exit();
    } else {
        echo "Error updating product visibility: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
        .table-container {
            max-width: 1000px;
            margin: 0 auto;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 8px 12px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            margin-right: 5px;
            display: inline-block;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-hide {
            background-color: #ffc107;
        }
        .btn-hide:hover {
            background-color: #e0a800;
        }
        img {
            max-width: 50px;
        }
    </style>
</head>
<body>

<h2>Manage Products</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Visibility</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td>$<?php echo number_format($row['price'], 2); ?></td>
                <td><img src="uploads/products/<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>"></td>
                <td><?php echo $row['hidden'] ? 'Hidden' : 'Visible'; ?></td>
                <td>
                    <a href="edit_product_upload.php?product_id=<?php echo $row['product_id']; ?>" class="btn">Edit</a>
                    <?php if ($row['hidden']) { ?>
                    <a href="manage_product.php?action=unhide&product_id=<?php echo $row['product_id']; ?>" class="btn btn-hide">Unhide</a>
                    <?php } else { ?>
                    <a href="manage_product.php?action=hide&product_id=<?php echo $row['product_id']; ?>" class="btn btn-hide">Hide</a>
                    <?php } ?>
                    <a href="manage_product.php?delete=<?php echo $row['product_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
