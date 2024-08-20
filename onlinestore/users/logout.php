<?php
// session_start();

// if (isset($_POST['logout'])) {
//     session_unset();
//     session_destroy(); // Destroy all sessions

//     // Redirect to home page after logout
//     header("Location: index.html");
//     exit();
// }


session_start();

// Check if the user confirmed the logout
if (isset($_POST['confirm_logout'])) {
    session_unset();
    session_destroy(); // Destroy all sessions

    // Redirect to the home page after logout
    header("Location: ../index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .logout-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 8px;
        }
        .logout-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .logout-container form {
            display: flex;
            justify-content: space-around;
        }
        .logout-container button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-container button.confirm {
            background-color: #007bff;
            color: #fff;
        }
        .logout-container button.cancel {
            background-color: #ccc;
            color: #333;
        }
        .logout-container button.confirm:hover {
            background-color: #0056b3;
        }
        .logout-container button.cancel:hover {
            background-color: #999;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h1>Are you sure you want to log out?</h1>
        <form method="post">
            <button type="submit" name="confirm_logout" class="confirm">Yes, Logout</button>
            <button type="button" class="cancel" onclick="window.location.href='user_dashboard.php';">Cancel</button>
        </form>
    </div>
</body>
</html>






