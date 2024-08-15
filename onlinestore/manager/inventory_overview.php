<?php
// Include the database connection file
p
// Fetch all available products from the database
$query = "SELECT product_id, product_name, product_category, description, price, created_at, supplier, quantity, product_status, product_image FROM products WHERE product_status = 'available'";
$result = mysqli_query($conn, $query);

// Check if there are products available
if (mysqli_num_rows($result) > 0) {
    echo '<div class="product-overview">';
    echo '<h2>Inventory Overview</h2>';
    echo '<table>';
    echo '<tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Product Category</th>
            <th>Description</th>
            <th>Price</th>
            <th>Created At</th>
            <th>Supplier</th>
            <th>Quantity</th>
            <th>Product Status</th>
            <th>Product Image</th>
          </tr>';
    
    // Loop through each product and display its details
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['product_id'] . '</td>';
        echo '<td>' . $row['product_name'] . '</td>';
        echo '<td>' . $row['product_category'] . '</td>';
        echo '<td>' . $row['description'] . '</td>';
        echo '<td>$' . number_format($row['price'], 2) . '</td>';
        echo '<td>' . $row['created_at'] . '</td>';
        echo '<td>' . $row['supplier'] . '</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>' . ucfirst($row['product_status']) . '</td>';
        echo '<td><img src="/uploads/' . $row['product_image'] . '" alt="' . $row['product_name'] . '" width="100"></td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</div>';
} else {
    echo '<p>No products available in the inventory.</p>';
}

// Close the database connection
mysqli_close($conn);
?>
