<?php
// Database connection settings
$host = 'localhost';
$dbname = 'online_store';
$username = 'admin';
$password = 'Store@112@';

// try {
//     // Create a new PDO instance
//     $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
//     // Set PDO error mode to exception
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// } catch (PDOException $e) {
//     // Handle connection error
//     echo "Connection failed: " . $e->getMessage();
//     exit();
// }

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
