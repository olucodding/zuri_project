<?php
// Include the database connection file
include 'includes/db.php';

// Fetch all categories
$category_query = "SELECT DISTINCT category FROM products WHERE product_status = 'available'";
$category_result = mysqli_query($conn, $category_query);

$categories = [];
if ($category_result) {
    while ($row = mysqli_fetch_assoc($category_result)) {
        $categories[] = $row['category'];
    }
}

// Fetch products for each category
$products_by_category = [];
foreach ($categories as $category) {
    $product_query = "SELECT * FROM products WHERE category = '$category' AND product_status = 'available'";
    $product_result = mysqli_query($conn, $product_query);

    $products_by_category[$category] = [];
    if ($product_result) {
        while ($row = mysqli_fetch_assoc($product_result)) {
            $products_by_category[$category][] = $row;
        }
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
    <title>Continue Shopping</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Continue Shopping</h2>
    <?php foreach ($products_by_category as $category => $products) { ?>
        <div class="category-section">
            <h3><?php echo htmlspecialchars($category); ?></h3>
            <div class="products">
                <?php foreach ($products as $product) { ?>
                    <div class="product">
                        <img src="<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <h4><?php echo htmlspecialchars($product['product_name']); ?></h4>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>$<?php echo htmlspecialchars($product['price']); ?></p>
                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="btn">View Details</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>
