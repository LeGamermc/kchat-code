<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Connect to database
$db_host = '127.0.0.1';
$db_name = 'kchat';
$db_user = 'user';
$db_pass = 'pass';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check if database connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update user online status
$username = $_SESSION['username'];
$query = "UPDATE users SET online = 1 WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->close();

$conn->close();
?>
