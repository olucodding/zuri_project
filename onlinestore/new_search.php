<?php
// Assume a connection to your database is established

// Fetch search query and filters
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$brandFilter = isset($_GET['brand']) ? $_GET['brand'] : '';
$priceMin = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$priceMax = isset($_GET['price_max']) ? $_GET['price_max'] : '';
$colorFilter = isset($_GET['color']) ? $_GET['color'] : '';
$sizeFilter = isset($_GET['size']) ? $_GET['size'] : '';

// SQL query to fetch filtered products (example)
$query = "SELECT * FROM products WHERE name LIKE '%$searchQuery%'";

if ($categoryFilter) {
    $query .= " AND category = '$categoryFilter'";
}
if ($brandFilter) {
    $query .= " AND brand = '$brandFilter'";
}
if ($priceMin && $priceMax) {
    $query .= " AND price BETWEEN '$priceMin' AND '$priceMax'";
}
if ($colorFilter) {
    $query .= " AND color = '$colorFilter'";
}
if ($sizeFilter) {
    $query .= " AND size = '$sizeFilter'";
}

// Execute the query and fetch the results
$results = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }
        .product-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <!-- Sidebar for advanced search options -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h5>Advanced Search</h5>
                    <form method="GET" action="search.php">
                        <input type="hidden" name="query" value="<?= htmlspecialchars($searchQuery) ?>">

                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">All Categories</option>
                                <!-- Example Categories -->
                                <option value="Electronics">Electronics</option>
                                <option value="Fashion">Fashion</option>
                                <option value="Home">Home</option>
                            </select>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <select name="brand" id="brand" class="form-select">
                                <option value="">All Brands</option>
                                <!-- Example Brands -->
                                <option value="Brand A">Brand A</option>
                                <option value="Brand B">Brand B</option>
                                <option value="Brand C">Brand C</option>
                            </select>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="d-flex">
                                <input type="number" name="price_min" class="form-control" placeholder="Min" value="<?= htmlspecialchars($priceMin) ?>">
                                <span class="mx-2">-</span>
                                <input type="number" name="price_max" class="form-control" placeholder="Max" value="<?= htmlspecialchars($priceMax) ?>">
                            </div>
                        </div>

                        <!-- Color Filter -->
                        <div class="mb-3">
                            <label for="color" class="form-label">Color</label>
                            <select name="color" id="color" class="form-select">
                                <option value="">All Colors</option>
                                <!-- Example Colors -->
                                <option value="Red">Red</option>
                                <option value="Blue">Blue</option>
                                <option value="Green">Green</option>
                            </select>
                        </div>

                        <!-- Size Filter -->
                        <div class="mb-3">
                            <label for="size" class="form-label">Size</label>
                            <select name="size" id="size" class="form-select">
                                <option value="">All Sizes</option>
                                <!-- Example Sizes -->
                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                                <option value="Large">Large</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>

            <!-- Main Content Area - Search Results -->
            <div class="col-md-9">
                <h3>Search Results for "<?= htmlspecialchars($searchQuery) ?>"</h3>
                <div class="row">
                    <?php if (mysqli_num_rows($results) > 0): ?>
                        <?php while ($product = mysqli_fetch_assoc($results)): ?>
                            <div class="col-md-4 product-card">
                                <div class="card">
                                    <img src="<?= $product['product_image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                        <p class="card-text">$<?= htmlspecialchars($product['price']) ?></p>
                                        <a href="product_page.php?id=<?= $product['id'] ?>" class="btn btn-primary">View More</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No products found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
