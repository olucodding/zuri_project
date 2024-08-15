<?php
// Include the database connection file
include '../includes/db.php';

// Define the current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Fetch all orders for the current month
$sql = "SELECT id, product_id, user_id, order_date, total
        FROM order_items
        WHERE MONTH(order_date) = $currentMonth 
        AND YEAR(order_date) = $currentYear";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}


// Fetch all orders status for the current month
$sql = "SELECT order_id, tracking_id, status, created_at, 
        FROM order_tracking
        WHERE MONTH(created_at) = $currentMonth 
        AND YEAR(created_at) = $currentYear";
$result = mysqli_query($conn, $sql);


if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}




// Initialize arrays for chart data
$orderStatuses = [];
$dailySales = [];

// Process data for the charts
while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['status'];
    $orderDate = date('d', strtotime($row['order_date']));
    $total = $row['total'];

    // Count order statuses
    if (isset($orderStatuses[$status])) {
        $orderStatuses[$status]++;
    } else {
        $orderStatuses[$status] = 1;
    }

    // Sum daily sales totals
    if (isset($dailySales[$orderDate])) {
        $dailySales[$orderDate] += $total;
    } else {
        $dailySales[$orderDate] = $total;
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
    <title>Monthly Order Report - <?php echo date('F Y'); ?></title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<body>



<div class="content-container">
    <h2>Monthly Order Report - <?php echo date('F Y'); ?></h2>

    <div class="chart-container">
        <h3>Order Status Distribution</h3>
        <canvas id="orderStatusChart"></canvas>
    </div>

    <div class="chart-container">
        <h3>Daily Sales Totals</h3>
        <canvas id="dailySalesChart"></canvas>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            mysqli_data_seek($result, 0); // Reset result pointer to the start
            while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo date('d M Y', strtotime($row['order_date'])); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>$<?php echo number_format($row['total'], 2); ?></td>
                <td><a href="order_details.php?order_id=<?php echo $row['id']; ?>">View Details</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>



<script>
    // Data for the Order Status Distribution pie chart
    const orderStatusData = {
        labels: <?php echo json_encode(array_keys($orderStatuses)); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($orderStatuses)); ?>,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
        }]
    };

    // Data for the Daily Sales Totals bar chart
    const dailySalesData = {
        labels: <?php echo json_encode(array_keys($dailySales)); ?>,
        datasets: [{
            label: 'Total Sales',
            data: <?php echo json_encode(array_values($dailySales)); ?>,
            backgroundColor: '#36A2EB',
        }]
    };

    // Create the Order Status Distribution pie chart
    const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'pie',
        data: orderStatusData
    });

    // Create the Daily Sales Totals bar chart
    const ctxSales = document.getElementById('dailySalesChart').getContext('2d');
    new Chart(ctxSales, {
        type: 'bar',
        data: dailySalesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
