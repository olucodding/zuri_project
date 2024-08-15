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

    // Verify if the role_id exists in the roles table
    $role_check_query = "SELECT role_id FROM roles WHERE role_id = '$role_id'";
    $role_check_result = mysqli_query($conn, $role_check_query);

    if (mysqli_num_rows($role_check_result) > 0) {
        // Update user role
        $sql = "UPDATE users SET role_id = '$role_id' WHERE user_id = $user_id";
        if (mysqli_query($conn, $sql)) {
            $success_message = "User role updated successfully.";
        } else {
            $error_message = "Error updating user role: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Invalid role ID.";
    }

    // Refresh page to update user list
    header("Location: manage_users.php");
    exit;
}

// Fetch all users and roles from the database
$sql_users = "SELECT user_id, username, email, full_name, role_id FROM users";
$result_users = mysqli_query($conn, $sql_users);

$sql_roles = "SELECT role_id, role_name, description FROM roles";
$result_roles = mysqli_query($conn, $sql_roles);

if (!$result_users || !$result_roles) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Create an associative array of roles for easy lookup
$roles = [];
while ($row = mysqli_fetch_assoc($result_roles)) {
    $roles[$row['role_id']] = $row;
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
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result_users)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td>
                    <?php echo htmlspecialchars($roles[$row['role_id']]['role_name']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($roles[$row['role_id']]['description']); ?>
                </td>
                <td>
                    <form action="manage_users.php" method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                        <select name="role_id" required>
                            <?php foreach ($roles as $role_id => $role) { ?>
                                <option value="<?php echo htmlspecialchars($role_id); ?>" <?php echo $row['role_id'] == $role_id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($role['role_name']); ?>
                                </option>
                            <?php } ?>
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
