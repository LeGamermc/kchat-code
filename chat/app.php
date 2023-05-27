<?php
session_start();
require_once('config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}
// Get the blur setting for the current user
$username = $_SESSION['username'];
$query = "SELECT blur FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($blur);
$stmt->fetch();
$stmt->close();
// Retrieve user information from database
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Update user status to online
$query = "UPDATE users SET online = 1 WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$stmt->close();

// Handle user disconnect
function onUnload() {
    global $conn;
    $query = "UPDATE users SET online = 0 WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->close();
}

$username = $_SESSION['username'];
$query = "SELECT background, theme FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($background, $theme);
$stmt->fetch();
$stmt->close();

// Set the background image based on the username
if ($_SESSION['username'] === 'KosmoTheProtogen') {
    $background_image = '/chat/images/drg.png';
} else {
    $background_image = $background;    
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>kchat - mainchat</title>
    <meta charset="utf-8">
    <style>
        .white-text {
      	    color: #fff; /* Set the text color to white */
    	}
        body {
            font-family: sans-serif;
            background-image: url("<?php echo $background_image; ?>");
            background-size: cover;
            min-height:100vh;object-fit:cover;
            background-color: #f1f1f1;
        }


        #chat {
            border: 1px solid #ccc;
            background-color: #fff;
            height: 400px;
            overflow-y: scroll;
            padding: 10px;
            margin-bottom: 10px;
        }

        #chat p {
            margin: 0;
            padding: 5px;
            border-bottom: 1px solid #eee;
        }

        #chat2 {
            border: 1px solid #ccc;
            background-color: #fff;
            height: 400px;
            overflow-y: scroll;
            padding: 10px;
            margin-bottom: 10px;
        }

        #chat2 p {
            margin: 0;
            padding: 5px;
            border-bottom: 1px solid #eee;
        }

        #message {
            width: 100%;
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
        }
        .dot {
 	    display: inline-block;
  	    width: 19px;
            height: 19px;
            border-radius: <?php echo ($theme == 1) ? '0' : '5px'; ?>;
            margin-right: 5px;
        }

        .green {
	   background-image: url("images/statuson.png");
	   background-repeat: no-repeat;
	   background-size: cover;
        }

	.yellow { 
           background-image: url("images/statusina.png");
           background-repeat: no-repeat;
           background-size: cover;
	}

        .red { 
           background-image: url("images/statusoff.png");
           background-repeat: no-repeat;
           background-size: cover;
        }
        #loader {
            background-color: <?php echo ($blur== 0) ? 'rgba(0, 0, 0);' : 'rgba(0, 0, 0, 0.7); '; ?>; 
            backdrop-filter: blur(5px); 
	    position: fixed;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    z-index: 9999;
	    display: flex;
	    justify-content: center;
	    align-items: center;
        }
	.btn {
		padding: 0px;
		cursor: pointer;
		background-color: transparent;
                border: none;
	}

	/* Submenu style */
			/* Button style */
		.btn {
		        padding: 0px 0px;
			margin-left: 10px;
		}

		/* Submenu style */
		.dropdown {
			position: relative;
			display: inline-block;
		}

		.dropdown-content {
			display: none;
			width: 90px;
	                height: 30px;
			backdrop-filter: <?php echo ($blur== 0) ? '0' : 'blur(5px); '; ?>;
			position: absolute;
			outline: 1px solid white;
			z-index: 1;
		}

		.dropdown:hover .dropdown-content {
			display: block;
			background-color: <?php echo ($blur== 0) ? 'rgba(51, 51, 51);' : 'rgba(51, 51, 51, 0.4); '; ?>;
			backdrop-filter: blur(5px);  
			color: #fff; 
			padding: 20px 20px; 
			border-radius: <?php echo ($theme == 1) ? '0' : '7px'; ?>;
			margin-left: 10px; 
			text-decoration: none;
		}
		.rounded-left {
        	    border-top-left-radius: 0;
        	    border-top-right-radius: <?php echo ($theme == 1) ? '0' : '6px'; ?>;
        	    border-bottom-left-radius: 0;
        	    border-bottom-right-radius: 0;
        	    outline: 1px solid white;

        	}
                .rounded-right {
                    border-top-left-radius: <?php echo ($theme == 1) ? '0' : '6px'; ?>;
                    border-top-right-radius: 0;
                    border-bottom-left-radius: 0;
                    border-bottom-right-radius: 0;
                }

}
    </style>
</head>
<body onunload="onUnload()">
<div id="loader">
  <img src="load.gif" alt="Loading...">
</div>
<div style="display: flex; align-items: center;">
    <p style="background-color: <?php echo ($blur== 0) ? 'rgba(51, 51, 51);' : 'rgba(51, 51, 51, 0.4); '; ?>; backdrop-filter: blur(5px); color: #fff; padding: 8px 16px; outline: 1px solid white; border-radius: <?php echo ($theme == 1) ? '0' : '4px'; ?>; margin-left: 10px; text-decoration: none;">Welcome, <?php echo $_SESSION['username']; ?>!</p>
    <a href="logout.php" style="margin-left: 10px;"><img src="images/logout-btn.png" style="width: 38px; height: 38px; border-radius: <?php echo ($theme == 1) ? '0' : '4px'; ?>; outline: 1px solid white;"></a>
    	<div class="dropdown">
		<button class="btn"><img src="images/status-changer-btn.png" style="width: 38px; height: 38px; border-radius: <?php echo ($theme == 1) ? '0' : '4px'; ?>; outline: 1px solid white;"></button>
		<div class="dropdown-content">
                        <a style="cursor: pointer;" onclick="fetchPage(event, 'active.php')"><img src="images/onbtn.png"></a>
			<a style="cursor: pointer;" onclick="fetchPage(event, 'inactive.php')"><img src="images/inabtn.png"></a>
			<a style="cursor: pointer;" onclick="fetchPage(event, 'off.php')"><img src="images/offbtn.png"></a>
		</div>
	</div>
    <a href="themes.php" style="margin-left: 10px;"><img src="images/themeset_btn2.png" style="width: 38px; outline: 1px solid white; height: 38px; border-radius: <?php echo ($theme == 1) ? '0' : '4px'; ?>;"></a>
</div>
<div style="display: flex;">
    <div id="chat2" style="color: white; background-color: <?php echo ($blur== 0) ? 'rgba(51, 51, 51);' : 'rgba(51, 51, 51, 0.4); '; ?>;  backdrop-filter: blur(5px); width: 250px; height: calc(100vh - 165px); border: 1px solid #ccc; padding: 10px; margin-right: 10; border-radius: <?php echo ($theme == 1) ? '0' : '10px'; ?>;"></div>
    <div id="chat" style="flex-grow: 1;  background-color: <?php echo ($blur== 0) ? 'rgba(51, 51, 51);' : 'rgba(51, 51, 51, 0.4); '; ?>;  backdrop-filter: blur(5px); color: white; margin-left: 10px; height: calc(100vh - 165px); border-radius: <?php echo ($theme == 1) ? '0' : '10px'; ?>; width: 200px;"></div>
</div>
<form id="chat-form" style="position: fixed; bottom: 0; display: flex; align-items: center; width: 99%;">
    <textarea id="message" style="color: white; background-color: <?php echo ($blur== 0) ? 'rgba(51, 51, 51);' : 'rgba(51, 51, 51, 0.4); '; ?>; backdrop-filter: blur(5px); height: 54px; width: 100%; flex-grow: 1; margin: 0; resize: none;" name="message" placeholder="Enter your message" class="rounded-right"></textarea>
    <button id="submit" type="submit" style="background-color:  <?php echo ($blur== 0) ? 'rgba(51, 51, 51);' : 'rgba(51, 51, 51, 0.4); '; ?>; backdrop-filter: blur(5px); border: 1px solid black; box-sizing: border-box; height: 54px; flex-shrink: 0; margin: 0;" class="rounded-left"><img src="images/send.png"></button>
</form>
</body>
<script>
function adjustImageSize(img) {
  var containerWidth = img.parentElement.offsetWidth;
  var imageWidth = img.naturalWidth;
  
  if (imageWidth <= containerWidth * 0.3) {
    img.style.width = 'auto';
  }
}

window.addEventListener("load", function() {
  document.getElementById("loader").style.display = "none";
});
console.log("Welcome on kchat Beta \ninformation this chat appliction have been developed by LeGamer and is still in development so bug may occur \nDO NOT SHARE ANY INFORMATION SUCH AS PASSWORD")
window.addEventListener("load", function() {
  document.getElementById("loader").style.display = "none";
});

window.onbeforeunload = function(){
    onUnload();
};

function fetchPage(event, url) {
  event.preventDefault();
  const xhr = new XMLHttpRequest();
  xhr.open("GET", url, true);
  xhr.send();
}
var interval = 500;


// Fetch the latest messages from the server and update the chat box
function updateChat() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("chat").innerHTML = this.responseText;
        }
        else if (this.readyState == 4 && this.status != 200) {
        }
    };
    xhttp.open("GET", "fetch.php", true);
    xhttp.send();
}

// Fetch the list of online users and update the user list
function updateChat2() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("chat2").innerHTML = this.responseText;
        }
        else if (this.readyState == 4 && this.status != 200) {
        }
    };
    xhttp.open("GET", "userlist.php", true);
    xhttp.send();
}


// Call the updateChat and updateUserList functions every 'interval' milliseconds
setInterval(function() {
    updateChat();
    updateChat2();
}, interval);

// Handle the form submission using AJAX
document.getElementById("chat-form").addEventListener("submit", function(event) {
  event.preventDefault(); // Prevent the default form submission
  
  // Replace message box with the loading GIF
  var messageBox = document.getElementById("message");
  messageBox.disabled = true;
  messageBox.style.display = "block";
  // Disable the submit button while the message is being sent
  var submitButton = document.querySelector("button[type=submit]");
  submitButton.style.display = "none";
  submitButton.disabled = true;
  var loadingGif = document.createElement("img");
  loadingGif.src = "images/sending.gif";
  loadingGif.alt = "Sending...";
  loadingGif.style = "width: 3%; flex-grow: 1; margin-top: 2px; height: 48px;"
  submitButton.parentElement.insertBefore(loadingGif, submitButton);

  
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4) {
      // Remove the loading GIF and re-enable the message box and submit button
      loadingGif.parentElement.removeChild(loadingGif);
      messageBox.style.display = "block";
      submitButton.style.display = "block";
      messageBox.disabled = false;
      submitButton.disabled = false;
      
      if (this.status == 200) {
        messageBox.value = ""; // Clear the message input field
      } else {
        console.log("Error sending message: " + this.status);
      }
    }
  };
  xhttp.open("POST", "send.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("message=" + encodeURIComponent(messageBox.value));
});
</script>
</html>
