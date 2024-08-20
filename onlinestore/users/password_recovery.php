<?php
session_start();
require '../includes/db.php'; // Include database connection

// Initialize variables
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Set token expiration time (1 hour from now)
        $expires_at = date("U") + 3600;

        // Insert token into the database
        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $email, $token, $expires_at);
        mysqli_stmt_execute($stmt);

        // Send password recovery email
        $reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;
        $subject = "Password Recovery";
        $message_body = "You requested a password reset. Click the following link to reset your password:\n\n" . $reset_link;
        $headers = "From: no-reply@yourdomain.com";

        if (mail($email, $subject, $message_body, $headers)) {
            $message = "A password recovery link has been sent to your email.";
        } else {
            $message = "Failed to send recovery email. Please try again.";
        }
    } else {
        $message = "Email not found. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
</head>
<body>

<h2>Password Recovery</h2>

<form action="password_recovery.php" method="POST">
    <div>
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <button type="submit">Send Recovery Email</button>
    </div>
</form>

<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

</body>
</html>
