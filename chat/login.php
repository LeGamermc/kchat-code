<?php
session_start();
// Database configuration
$servername = "127.0.0.1";
$username = "user";
$password = "pass";
$dbname = "kchat";

// Create MySQL connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password_hash = hash('sha256', $_POST['password']);

// Query to check if user exists
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password_hash'";
$result = $conn->query($sql);

// Check if query returned a result
if ($result->num_rows > 0) {
  $_SESSION['username'] = $username;
  // Redirect to app.php if JavaScript is enabled
  echo '<script type="text/javascript">window.location.href = "app.php";</script>';
} else {
  echo 'Invalid username or password.';
}

// Redirect to adapp.php if JavaScript is disabled
echo '<noscript><meta http-equiv="refresh" content="0;url=adapp.php"><p>If you are not redirected automatically, follow this <a href="adapp.php">link</a>.</p></noscript>';

$conn->close();
?>
