<?php
session_start();

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: manager_login.php');
    exit();
}

// Include the database connection file
include '../includes/db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_role'])) {
    $user_id = intval($_POST['user_id']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);

    // Update user role
    $sql = "UPDATE users SET role_id = '$role_id' WHERE user_id = $user_id";
    if (mysqli_query($conn, $sql)) {
        $success_message = "User role updated successfully.";
    } else {
        $error_message = "Error updating user role: " . mysqli_error($conn);
    }
    
    // Refresh page to update user list
    header("Location: manage_users.php");
    exit;
}

// Fetch all users from the database
$sql = "SELECT user_id, username, email, full_name, role_id FROM users";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

<div class="content-container">
    <h2>Manage Users</h2>
    <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['role_id']); ?></td>
                <td>
                    <form action="manage_users.php" method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                        <select name="role_id" required>
                            <option value="user" <?php echo $row['role_id'] == 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo $row['role_id'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                        <button type="submit" name="update_role">Update</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
