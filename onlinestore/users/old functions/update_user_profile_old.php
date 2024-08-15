<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

// Fetch current user details
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $shipping_apt_no = $_POST['shipping_apt_no'];
    $shipping_street_address = $_POST['shipping_street_address'];
    $shipping_state = $_POST['shipping_state'];
    $shipping_country = $_POST['shipping_country'];
    $billing_apt_no = $_POST['billing_apt_no'];
    $billing_street_address = $_POST['billing_street_address'];
    $billing_state = $_POST['billing_state'];
    $billing_country = $_POST['billing_country'];
    $phone_number = $_POST['phone_number'];

    // Handle profile picture upload
    if ($_FILES['profile_picture']['name']) {
        $target_dir = "uploads/profile_pictures/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = $target_file;

        // Update query with profile picture
        $query = "UPDATE users SET name = '$name', email = '$email', shipping_apt_no = '$shipping_apt_no', shipping_street_address = '$shipping_street_address', shipping_state = '$shipping_state', shipping_country = '$shipping_country', billing_apt_no = '$billing_apt_no', billing_street_address = '$billing_street_address', billing_state = '$billing_state', billing_country = '$billing_country', phone_number = '$phone_number', profile_picture = '$profile_picture' WHERE id = $user_id";
    } else {
        // Update query without profile picture
        $query = "UPDATE users SET name = '$name', email = '$email', shipping_apt_no = '$shipping_apt_no', shipping_street_address = '$shipping_street_address', shipping_state = '$shipping_state', shipping_country = '$shipping_country', billing_apt_no = '$billing_apt_no', billing_street_address = '$billing_street_address', billing_state = '$billing_state', billing_country = '$billing_country', phone_number = '$phone_number' WHERE id = $user_id";
    }

    mysqli_query($conn, $query);
    header("Location: user_profile.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
</head>
<body>
    <h2>Update Profile</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

        <h3>Shipping Address</h3>
        <label for="shipping_apt_no">Apt No.:</label>
        <input type="text" name="shipping_apt_no" value="<?php echo $user['shipping_apt_no']; ?>"><br>
        <label for="shipping_street_address">Street Address:</label>
        <input type="text" name="shipping_street_address" value="<?php echo $user['shipping_street_address']; ?>" required><br>
        <label for="shipping_state">State:</label>
        <input type="text" name="shipping_state" value="<?php echo $user['shipping_state']; ?>" required><br>
        <label for="shipping_country">Country:</label>
        <input type="text" name="shipping_country" value="<?php echo $user['shipping_country']; ?>" required><br>

        <h3>Billing Address</h3>
        <label for="billing_apt_no">Apt No.:</label>
        <input type="text" name="billing_apt_no" value="<?php echo $user['billing_apt_no']; ?>"><br>
        <label for="billing_street_address">Street Address:</label>
        <input type="text" name="billing_street_address" value="<?php echo $user['billing_street_address']; ?>" required><br>
        <label for="billing_state">State:</label>
        <input type="text" name="billing_state" value="<?php echo $user['billing_state']; ?>" required><br>
        <label for="billing_country">Country:</label>
        <input type="text" name="billing_country" value="<?php echo $user['billing_country']; ?>" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo $user['phone_number']; ?>" required><br>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture"><br>

        <input type="submit" value="Update Profile">
    </form>
</body>
</html>
