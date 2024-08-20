<?php
session_start();
include('includes/db.php'); // Database connection

// SQL query to fetch data from order_items
$sql = "
    SELECT 
        DATE(created_at) as order_date,
        SUM(price * quantity) as total
    FROM 
        order_items
    WHERE 
        MONTH(created_at) = MONTH(CURRENT_DATE())
    AND 
        YEAR(created_at) = YEAR(CURRENT_DATE())
    GROUP BY 
        DATE(created_at)
    ORDER BY 
        DATE(created_at)
";

$result = $conn->query($sql);

$dates = [];
$totals = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dates[] = $row['order_date'];
        $totals[] = $row['total'];
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
    <title>Monthly Order Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            .card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Monthly Order Report</h2>

    <div class="card">
        <canvas id="orderChart"></canvas>
    </div>
</div>

<script>
    var ctx = document.getElementById('orderChart').getContext('2d');
    var orderChart = new Chart(ctx, {
        type: 'bar', // You can change to 'line', 'pie', etc.
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Order Totals',
                data: <?php echo json_encode($totals); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total ($)'
                    }
                }
            }
        }
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
