<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Update the online status of the user
    $sql = "UPDATE users SET online = 0 WHERE username = '$username'";
    $conn->query($sql);

    // Destroy the session and redirect to the login page
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
