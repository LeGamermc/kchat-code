<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: app.php');
    exit;
}
?>
<html>
<meta http-equiv="Bypass-Tunnel-Reminder" content="true">

<head>
  <title>kchat - login</title>
  <style>
    body {
      background-image: url("/chat/back.png");
      background-size: cover;
      min-height:100vh;object-fit:cover;
      color: white;
    }
        /* Center the login form */
        .login-form {
            display: flex;
            
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        
        /* Add a rounded square shape to the login form */
        .login-form form {
            border-radius: 10px;
            outline: 1px solid white;
            padding: 20px;
            background-color: rgba(51, 51, 51, 0.4);
            backdrop-filter: blur(5px);
        }
        
        /* Style the login form fields */
        .login-form label {
            display: block;
            margin-bottom: 10px;
        }
        
        .login-form input[type="text"],
        .login-form input[type="password"] {
            padding: 5px;
            border: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .login-form input[type="submit"] {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #000;
            cursor: pointer;
        }
        
        .login-form input[type="submit"]:hover {
            background-color: #0069d9;
        }
	.rounded-bottom {
     	    border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
        }
  </style>
</head>
<body style="background-color: black;">
<a href="../index.html" class="rounded-bottom" style="background-color: #007bff; color: #fff; padding: 8px 16px; margin-left: 5px; text-decoration: none;">Home</a>
    <div class="login-form">
	<center><img src="images/kchat-weblogo.png"></center>
        <h2>Kchat Beta</h2>
        <form method="post" action="login.php">
            <label>Username:</label>
            <input type="text" name="username">
            <label>Password:</label>
            <input type="password" name="password">
            <input type="submit" style="color: white;" value="Login">
        </form>
    <p class="white-text">Need an account? <a href="register.php" style="background-color: #007bff; color: #fff; padding: 8px 16px; border-radius: 4px; margin-left: 10px; text-decoration: none;">Register</a></p>
</p>
    </div>
</body>
</html>
