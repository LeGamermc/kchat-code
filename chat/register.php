<?php
session_start();

// Include database configuration file
require_once 'config.php';

// Handle form submission
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']); // encrypt the password using sha256
    
    // Check if username and password meet the minimum length requirement
    if (strlen($username) < 5 || strlen($_POST['password']) < 5) {
        echo 'Error: Username and password must be at least 5 characters long.';
        exit;
    }
    
    // Prepare and execute database query to check if user already exists
    $query = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User already exists, show error message
        echo 'Error: User already exists.';
    } else {
        // User does not exist, insert new user into database
        $status = "0"; // set initial status as "online"
        $query = "INSERT INTO users (username, password, online, background, theme, blur) VALUES (?, ?, ?, 'back.png', '0', '1')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $password, $status);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            echo 'Error: ' . $stmt->error;
        }
    }
    $stmt->close();
}
?>


<html>
<head>
  <title>kchat - signup</title>
  <style>
    body {
      background-image: url("/chat/back.png");
      background-size: cover;
      background-color: black;
      color: white;
      min-height:100vh;object-fit:cover;
    }
    /* Center the signup form */
    .signup-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    /* Add a rounded square shape to the signup form */
    .signup-form form {
        border-radius: 10px;
        padding: 20px;
        outline: 1px solid white;

        background-color: rgba(51, 51, 51, 0.4);
        backdrop-filter: blur(5px);

    }
    /* Style the signup form fields */
    .signup-form label {
        display: block;
        margin-bottom: 10px;
    }
    .signup-form input[type="text"],
    .signup-form input[type="password"] {
        padding: 5px;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .signup-form input[type="submit"] {
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #000;
        cursor: pointer;
    }
    .signup-form input[type="submit"]:hover {
        background-color: #0069d9;
    }
  </style>
</head>
<body>
    <div class="signup-form">
	<center><img src="images/kchat-weblogo.png"></center>
        <h2>Kchat Beta</h2>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="username">
            <label>Password:</label>
            <input type="password" name="password">
            <input type="submit" style="color: white;" value="Sign up">
        </form>
	   <p>Already have an account? <a href="index.php" style="background-color: #007bff; color: #fff; padding: 8px 16px; border-radius: 4px; margin-left: 10px; text-decoration: none;">Login</a></p>
    </div>
</body>
</html>
