<?php

// Database configuration
$servername = "127.0.0.1";
$username = "user";
$password = "pass";
$dbname = "kchat";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
