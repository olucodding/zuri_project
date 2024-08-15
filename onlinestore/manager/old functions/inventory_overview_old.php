<?php
// Include the database connection file
include '../includes/db.php';

// Fetch all products from the database
$sql = "SELECT product_id, product_name, description, price, created_at, product_category, supplier, quantity, product_status, image
        FROM products";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Close the database connection
mysqli_close($conn);
?>

<!-- start of html -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Overview</title>
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
<!-- end of head -->

<!-- start of body -->

<body>



<div class="content-container">
    <h2>Inventory Overview</h2>
    <table>
        <thead>
            <tr>
                <th>Product Id</th>
                <th>Product Name</th>
                <th>Category</
                <th>Description</th>
                <th>price</th>
                <th>created at</th>
                <th>Supplier</th>
                <th>Quantity</th>
                <th>PRODUCT Status</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['ptice']); ?></td>
                <td><?php echo htmlspecialchars($row['created at']); ?></td>

                <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['product_status']); ?></td>
                <td><img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" width="50" height="50"></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


</body>

<!-- end of body -->

</html>
