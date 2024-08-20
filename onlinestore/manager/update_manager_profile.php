<?php
session_start();

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: manager_login.php');
    exit();
}

// Include the database connection file
include '../includes/db.php';

// Fetch current manager details
$manager_id = $_SESSION['manager_id'];
$query = "SELECT * FROM managers WHERE manager_id='$manager_id'";
$result = mysqli_query($conn, $query);
$manager = mysqli_fetch_assoc($result);

// Handle form submission
if (isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update_query = "UPDATE managers SET full_name='$full_name', email='$email', phone_number='$phone_number', status='$status' WHERE manager_id='$manager_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $success_message = "Profile updated successfully.";
    } else {
        $error_message = "Error updating profile: " . mysqli_error($conn);
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
    <title>Update Manager Profile</title>
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



<div class="profile-container">
    <h2>Update Profile</h2>
    <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($manager['full_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($manager['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($manager['phone_number']); ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="active" <?php echo ($manager['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($manager['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                <option value="suspended" <?php echo ($manager['status'] == 'suspended') ? 'selected' : ''; ?>>Suspended</option>
            </select>
        </div>
        <button type="submit" name="update_profile">Update Profile</button>
    </form>
</div>



</body>
</html>
