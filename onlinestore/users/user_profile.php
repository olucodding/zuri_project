<?php
session_start();
require '../includes/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: customer_login.html");
    exit();
}

// Fetch user details from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Update user profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Shipping Address
    $shipping_apt_no = $_POST['shipping_apt_no'];
    $shipping_street_address = $_POST['shipping_street_address'];
    $shipping_state = $_POST['shipping_state'];
    $shipping_country = $_POST['shipping_country'];

    // Billing Address
    $billing_apt_no = $_POST['billing_apt_no'];
    $billing_street_address = $_POST['billing_street_address'];
    $billing_state = $_POST['billing_state'];
    $billing_country = $_POST['billing_country'];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        $profile_picture = $user['profile_picture'];
    }

    // Update user details in the database
    $sql = "UPDATE users SET full_name = ?, email = ?, phone_number = ?, 
            shipping_apt_no = ?, shipping_street_address = ?, shipping_state = ?, shipping_country = ?, 
            billing_apt_no = ?, billing_street_address = ?, billing_state = ?, billing_country = ?, 
            profile_picture = ? WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssssss", $full_name, $email, $phone_number, 
                          $shipping_apt_no, $shipping_street_address, $shipping_state, $shipping_country, 
                          $billing_apt_no, $billing_street_address, $billing_state, $billing_country, 
                          $profile_picture, $username);
    if (mysqli_stmt_execute($stmt)) {
        echo "Profile updated successfully!";
        // Fetch updated user details
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>

<h2>User Profile</h2>

<form action="user_profile.php" method="POST" enctype="multipart/form-data">
    <div>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required>
    </div>
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" disabled>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
    </div>
    <div>
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo $user['phone_number']; ?>" required>
    </div>

    <!-- Shipping Address -->
    <h3>Shipping Address</h3>
    <div>
        <label for="shipping_apt_no">Apt No.:</label>
        <input type="text" id="shipping_apt_no" name="shipping_apt_no" value="<?php echo $user['shipping_apt_no']; ?>" required>
    </div>
    <div>
        <label for="shipping_street_address">Street Address:</label>
        <input type="text" id="shipping_street_address" name="shipping_street_address" value="<?php echo $user['shipping_street_address']; ?>" required>
    </div>
    <div>
        <label for="shipping_state">State:</label>
        <input type="text" id="shipping_state" name="shipping_state" value="<?php echo $user['shipping_state']; ?>" required>
    </div>
    <div>
        <label for="shipping_country">Country:</label>
        <input type="text" id="shipping_country" name="shipping_country" value="<?php echo $user['shipping_country']; ?>" required>
    </div>

    <!-- Billing Address -->
    <h3>Billing Address</h3>
    <div>
        <label for="billing_apt_no">Apt No.:</label>
        <input type="text" id="billing_apt_no" name="billing_apt_no" value="<?php echo $user['billing_apt_no']; ?>" required>
    </div>
    <div>
        <label for="billing_street_address">Street Address:</label>
        <input type="text" id="billing_street_address" name="billing_street_address" value="<?php echo $user['billing_street_address']; ?>" required>
    </div>
    <div>
        <label for="billing_state">State:</label>
        <input type="text" id="billing_state" name="billing_state" value="<?php echo $user['billing_state']; ?>" required>
    </div>
    <div>
        <label for="billing_country">Country:</label>
        <input type="text" id="billing_country" name="billing_country" value="<?php echo $user['billing_country']; ?>" required>
    </div>

    <!-- Profile Picture -->
    <div>
        <label for="profile_picture">Profile Picture:</label>
        <?php if ($user['profile_picture']) { ?>
            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="100">
        <?php } ?>
        <input type="file" id="profile_picture" name="profile_picture">
    </div>

    <div>
        <button type="submit">Update Profile</button>
    </div>
</form>

</body>
</html>
