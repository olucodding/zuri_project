<?php
session_start();
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.html');
//     exit();
// }

include 'includes/db.php';

$userId = $_SESSION['user_id'];

// Fetch user information
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>


    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        .dashboard { max-width: 800px; margin: auto; padding: 20px; }
        .section { margin-bottom: 20px; }
        .section h2 { border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 10px; }
    </style>


    <!-- <title></title>
    <link rel="stylesheet" href="styles.css"> -->

<!-- Mobile Specific Meta -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>User Dashboard</title>

    <!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
	<link rel="stylesheet" href="css/magnific-popup.css">
	<link rel="stylesheet" href="css/main.css">


</head>

<body>








    <div class="dashboard">
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>

        <!-- User Information Section -->
        <div class="section">
            <h2>Your Information</h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Account Created:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
            <a href="edit_profile.php">Edit Profile</a>
        </div>

        <!-- Order History Section -->
        <div class="section">
            <h2>Your Orders</h2>
            <?php
            // Fetch the user's orders
            $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
            $stmt->execute(['user_id' => $userId]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($orders):
            ?>
                <ul>
                    <?php foreach ($orders as $order): ?>
                        <li>
                            Order #<?php echo $order['id']; ?> - Total: $<?php echo number_format($order['total'], 2); ?>
                            <a href="order_details.php?id=<?php echo $order['id']; ?>">View Details</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You have no orders yet.</p>
            <?php endif; ?>
        </div>







        <!-- Logout Option -->
        <div class="section">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
