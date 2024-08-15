<?php
include 'includes/db.php';
// session_start();

// At the top of your pages where authentication is required
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login if not logged in
    exit();
}

// Now you can access the logged-in user's details
echo "Welcome, " . $_SESSION['username'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the user from the database
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, start the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo "Login successful!";
        // Optionally redirect to the home page or dashboard
        // header('Location: index.php');
    } else {
        echo "Invalid username or password.";
    }
}
?>
