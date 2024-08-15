<?php
session_start();
require '../includes/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $shipping_apt_no = $_POST['shipping_apt_no'];
    $shipping_street_address = $_POST['shipping_street_address'];
    $shipping_state = $_POST['shipping_state'];
    $shipping_country = $_POST['shipping_country'];
    $billing_apt_no = $_POST['billing_apt_no'];
    $billing_street_address = $_POST['billing_street_address'];
    $billing_state = $_POST['billing_state'];
    $billing_country = $_POST['billing_country'];

    // Combine shipping and billing address parts into full addresses
    $shipping_address = $shipping_apt_no . ', ' . $shipping_street_address . ', ' . $shipping_state . ', ' . $shipping_country;
    $billing_address = $billing_apt_no . ', ' . $billing_street_address . ', ' . $billing_state . ', ' . $billing_country;

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
        // Retain the old profile picture if no new one is uploaded
        $sql = "SELECT profile_picture FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $profile_picture = $row['profile_picture'];
    }

    // Update user details in the database
    $sql = "UPDATE users SET full_name = ?, phone = ?, shipping_address = ?, billing_address = ?, profile_picture = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi", $full_name, $phone, $shipping_address, $billing_address, $profile_picture, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $success = "Profile updated successfully!";
        // Update session variables if needed
        $_SESSION['full_name'] = $full_name;
        header("Location: user_profile.php?success=" . urlencode($success));
        exit();
    } else {
        $error = "Error updating profile. Please try again.";
    }
} else {
    header("Location: user_profile.php");
    exit();
}
