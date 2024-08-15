<?php
include('../includes/db.php'); // Database connection


// SQL query to fetch data from order_items and order_tracking
$sql = "
    SELECT 
        
        order_id,
        product_id,
        quantity,
        price,
        order_date

       
    FROM 
        order_items
    WHERE 
        MONTH(order_date) = MONTH(CURRENT_DATE())
    AND 
        YEAR(order_date) = YEAR(CURRENT_DATE())
    ";

$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$order_dates = [];
$order_totals = [];
foreach($orders as $order) {
    $order_dates[] = $order['created_at'];
    $order_totals[] = $order['total_price'];
}

?>

<script>
var ctx = document.getElementById('orderChart').getContext('2d');
var orderChart = new Chart(ctx, {
    type: 'bar', // or 'line', 'pie', etc.
    data: {
        labels: <?php echo json_encode($order_dates); ?>,
        datasets: [{
            label: 'Order Totals',
            data: <?php echo json_encode($order_totals); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Order Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Monthly Order Report</h2>
    <canvas id="orderChart" width="400" height="200"></canvas>
</body>
</html>






