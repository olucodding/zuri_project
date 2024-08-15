<?php
session_start();

// Include the database connection file
include '../includes/db.php';

// Check if the user is logged in as a manager
if (isset($_SESSION['manager_id'])) {
    header('Location: manager_dashboard.php');
    exit();
}



// Handle form submission
if (isset($_POST['register_manager'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate password
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new manager into the database
        // $insert_query = "INSERT INTO managers (username, full_name, email, phone_number, password) VALUES ('$username', '$full_name', '$email', '$phone_number', '$hashed_password')";
        $insert_query = "INSERT INTO managers (username, full_name, email, phone_number, password, status) VALUES ('$username', '$full_name', '$email', '$phone_number', '$hashed_password', 'active')";

        if (mysqli_query($conn, $insert_query)) {
            $success_message = "Manager registered successfully!";
        } else {
            $error_message = "Error registering manager: " . mysqli_error($conn);
        }
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
    <title>Register Manager</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function showAlertAndRedirect(message, url) {
            alert(message);
            setTimeout(function() {
                window.location.href = url;
            }, 2000); // Redirect after 2 seconds
        }
    </script>

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


<div class="register-container">
    <h2>Register Manager</h2>
    <?php if (isset($success_message)) { ?>
        <script>
            window.onload = function() {
                showAlertAndRedirect("<?php echo $success_message; ?>", "manager_login.php");
            }
        </script>
    <?php } ?>
    <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" name="register_manager">Register</button>
    </form>
</div>

</body>
</html>
