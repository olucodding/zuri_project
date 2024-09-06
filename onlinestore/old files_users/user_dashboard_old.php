<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: customer_login.php');
    exit;
}

$username = $_SESSION['username'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
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

<script>
        var idleTime = 0;

        function resetTimer() {
            idleTime = 0;
        }

        // Increment the idle time counter every minute
        setInterval(timerIncrement, 60000); // 1 minute = 60000 ms

        function timerIncrement() {
            idleTime++;
            if (idleTime >= 3) { // 3 minutes of inactivity
                window.location.href = "logout.php";
            }
        }

        // Zero the idle timer on mouse movement or key press
        window.onload = function() {
            document.body.addEventListener('mousemove', resetTimer);
            document.body.addEventListener('keypress', resetTimer);
        }
    </script>

</head>


<body>

    <div class="navbar">
    <a href="user_dashboard.php" class="active">Dashboard</a><br>
    <div class="dropdown">
    <a href="update_user_profile.php">Edit Profile</a><br>
    <a href="order_details.php">Order Details</a><br>
    <a href="order_tracking.php">Order Tracking</a><br>
    <a href="shopping_cart.php">Shopping Cart</a><br>
    <a href="add_to_cart.php">Add To Cart</a><br>
    <a href="update_cart.php">Update Cart</a><br>
    <a href="shopping_history.php">Shopping History</a><br>
    <a href="checkout.php">Check Outt</a><br>
    <a href="logout.php" onclick="return confirmLogout();">Logout</a><br>
    </div>
    </div>
    
    <script>
function confirmLogout() {
    return confirm("Are you sure you want to logout?");
}
</script>


</div>

<div class="main-content">

<h1>Welcome to Your Dashboard <?php echo htmlspecialchars($username); ?>!</h1>
<!-- Main content of the dashboard will go here -->
</div>


</body>
</html>
