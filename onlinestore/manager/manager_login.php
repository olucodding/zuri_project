<?php
session_start();
include '../includes/db.php'; // Include the database connection file

// Check if the form is submitted
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to fetch the manager with the provided username
    $query = "SELECT * FROM managers WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $manager = mysqli_fetch_assoc($result);

        // Check the password
        if (password_verify($password, $manager['password'])) {
            // Check the status
            if ($manager['status'] == 'active') {
                // Start session and set session variables
                $_SESSION['manager_id'] = $manager['manager_id'];
                $_SESSION['username'] = $manager['username'];
                
                // Redirect to the manager dashboard
                header('Location: manager_dashboard.php');
                exit();
            } else {
                $error_message = "Your account is not active. Please contact support.";
            }
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Login</title>
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

</head>
<body>



<div class="login-container">
    <h2>Manager Login</h2>
    <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login">Login</button>
    </form>
</div>



</body>
</html>
