<?php
// session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css"> <!-- Link to your CSS file -->
    <title>Navigation Bar</title>
</head>
<body>

<nav class="navbar">
    <div class="navbar-container">
        <a href="../index.html" class="navbar-logo">MyOnlineStore</a>
        
        <ul class="navbar-menu">
            <li class="navbar-item"><a href="../index.html">Home</a></li>
            <li class="navbar-item"><a href="./users/shop.php">Shop</a></li>
            <li class="navbar-item"><a href="./users/shopping_cart.php">Cart</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="navbar-item"><a href="./users/user_profile.php">Profile</a></li>
                <li class="navbar-item"><a href="./users/shopping_history.php">Order History</a></li>
                <li class="navbar-item"><a href="./users/logout.php">Logout</a></li>
            <?php else: ?>
                <li class="navbar-item"><a href="./users/customer_login.php">Login</a></li>
                <li class="navbar-item"><a href="./users/register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

</body>
</html>
