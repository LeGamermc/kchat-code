<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}
require_once 'config.php';

// Update user online status
$username = $_SESSION['username'];
$query = "UPDATE users SET online = 2 WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->close();

$conn->close();
?>
