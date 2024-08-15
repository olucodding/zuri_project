<?php
session_start();
require 'includes/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $shipping_address = $_POST['shipping_address'];
    $billing_address = $_POST['billing_address'];

    // Handling file upload for profile picture
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the uploaded file is an image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $target_file;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    } else {
        $profile_picture = $user['profile_picture']; // If no new image is uploaded, retain the old one
    }

    // Update user details in the database
    $sql = "UPDATE users SET full_name = ?, phone = ?, shipping_address = ?, billing_address = ?, profile_picture = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi", $full_name, $phone, $shipping_address, $billing_address, $profile_picture, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $success = "Profile updated successfully!";
        // Update session variables if needed
        $_SESSION['full_name'] = $full_name;
    } else {
        $error = "Error updating profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
</head>
<body>

<h2>Update Your Profile</h2>

<form action="user_profile.php" method="POST" enctype="multipart/form-data">
    <div>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required>
    </div>
    <div>
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
    </div>
    <div>
        <label for="shipping_address">Shipping Address:</label>
        <textarea id="shipping_address" name="shipping_address" required><?php echo $user['shipping_address']; ?></textarea>
    </div>
    <div>
        <label for="billing_address">Billing Address:</label>
        <textarea id="billing_address" name="billing_address" required><?php echo $user['billing_address']; ?></textarea>
    </div>
    <div>
        <label for="profile_picture">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture">
        <?php if ($user['profile_picture']): ?>
            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" style="max-width: 100px; max-height: 100px;">
        <?php endif; ?>
    </div>
    <div>
        <button type="submit">Update Profile</button>
    </div>
</form>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?php echo $success; ?></p>
<?php endif; ?>

<p><a href="user_dashboard.php">Back to Dashboard</a></p>

</body>
</html>
