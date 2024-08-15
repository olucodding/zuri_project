<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title> <!-- Dynamically set the page title -->
    <link rel="stylesheet" href="../css/styles2.css"> <!-- Link to your CSS file -->
</head>
<body>

<header>
    <div class="header-container">
        <div class="logo">
            <a href="./users/customer_login.php"><img src="../img/logo.png" alt="Website Logo"></a>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="../index.html">Home</a></li>
                <li><a href="./users/shop.php">Shop</a></li>
                <li><a href="./users/shopping_history.php">Order History</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="./users/user_dashboard.php">Dashboard</a></li>
                    <li><a href="./users/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="./users/customer_login.php">Login</a></li>
                    <li><a href="./users/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<div class="content-container"> <!-- Container for page-specific content -->

