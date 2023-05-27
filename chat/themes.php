<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Database configuration
require_once 'config.php';

// Handle form submission
if (isset($_POST['background'])) {
    $selectedImage = $_POST['background'];

    // Update the background setting in the database
    $username = $_SESSION['username'];
    $query = "UPDATE users SET background = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $selectedImage, $username);
    $stmt->execute();
    $stmt->close();
}

// Handle blur button click
if (isset($_POST['blur'])) {
    $blurValue = $_POST['blur'];

    // Update the blur setting in the database
    $username = $_SESSION['username'];
    $query = "UPDATE users SET blur = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $blurValue, $username);
    $stmt->execute();
    $stmt->close();
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


// Handle theme button click
if (isset($_POST['theme'])) {
    $themeValue = $_POST['theme'];

    // Update the theme setting in the database
    $username = $_SESSION['username'];
    $query = "UPDATE users SET theme = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $themeValue, $username);
    $stmt->execute();
    $stmt->close();
}


// Get the background and theme settings for the current user
$username = $_SESSION['username'];
$query = "SELECT background, theme FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($background, $theme);
$stmt->fetch();
$stmt->close();

// Get the available background images from the database
$query = "SELECT image_url FROM background_images";
$result = $conn->query($query);
$backgroundImages = $result->fetch_all(MYSQLI_ASSOC);
$result->free_result();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>kchat - Themes customisation</title>
    <style>
        .menu {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        #menuButton {
            position: absolute;
            top: 10px;
            left: 270px;
        }

        #backgroundButton {
            position: absolute;
            top: 10px;
            left: 110px;
        }

        #themeButton {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        #imageList {
            list-style-type: none;
            padding: 0;
            background-color: <?php echo ($blur== 0) ? 'rgba(51, 51, 51);' : 'rgba(51, 51, 51, 0.4); '; ?>;
            backdrop-filter: blur(5px); 
            border-radius: <?php echo ($theme == 1) ? '0' : '7px'; ?>;
            outline: 1px solid white;
            margin: 10px;
            display: none;
        }

        #imageList li {
            display: inline-block;
            margin: 10px;
            cursor: pointer;
        }

        #imageList li img {
            max-width: 100px;
            max-height: 100px;
        }

        .menu-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url(<?php echo $background; ?>) no-repeat center center fixed;
            background-size: cover;
            z-index: -1;
            border-radius: <?php echo ($theme == 1) ? '0' : '13px'; ?>;
            opacity: 0.3;
        }
        
        .rounded-bottom {
            border-top-left-radius: <?php echo ($theme == 1) ? '0' : '6px'; ?>;
            border-top-right-radius: <?php echo ($theme == 1) ? '0' : '6px'; ?>;
            border-bottom-left-radius: <?php echo ($theme == 1) ? '0' : '6px'; ?>;
            border-bottom-right-radius: <?php echo ($theme == 1) ? '0' : '6px'; ?>;
        }
    </style>
</head>
<body style="background-color: black">
    <div class="menu">
        <button onclick="window.location.href = 'app.php';" style="border-radius: <?php echo ($theme == 1) ? '0' : '4px'; ?>; top: 10px; left: 10px; position: absolute; background-color: #007bff; color: #fff; padding: 8px 16px;  margin-left: 10px; text-decoration: none;">Go Back</button>
        <button id="backgroundButton" style="border-radius: <?php echo ($theme == 1) ? '0' : '4px'; ?>; top: 10px; left: 110px; position: absolute; background-color: #007bff; color: #fff; padding: 8px 16px; margin-left: 10px; text-decoration: none;">Select Background</button>
        <button id="menuButton" style="background-color: #007bff; color: #fff; padding: 8px 16px; border-radius: <?php echo ($theme == 1) ? '0' : '4px'; ?>; margin-left: 10px; text-decoration: none;">Appearance Menu</button>
        <ul id="imageList">
            <?php foreach ($backgroundImages as $image) : ?>
                <li onclick="changeBackground('<?php echo $image['image_url']; ?>')">
                    <img style="border-radius: <?php echo ($theme == 1) ? '0' : '7px'; ?>;" src="<?php echo $image['image_url']; ?>">
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="menu-background"></div>
    </div>

    <div id="menu" style=" background-color: <?php echo ($blur== 0) ? 'rgba(51, 51, 51);' : 'rgba(51, 51, 51, 0.4); '; ?>;  backdrop-filter: blur(5px); display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); outline: 1px solid white; padding: 150px; border-radius: <?php echo ($theme == 1) ? '0' : '10px'; ?>; ">
        <button id="themeButton" style="border-radius: <?php echo ($theme == 1) ? '0' : '6px'; ?>; top: 10px; left: 10px; position: absolute; background-color: #007bff; color: #fff; padding: 8px 16px; margin-left: 10px; text-decoration: none;" onclick="changeTheme()"><?php echo ($theme == 1) ? 'Modern Theme' : 'Classic Theme'; ?></button>
        <br>
	<button id="blurButton" style="border-radius: <?php echo ($theme == 1) ? '0' : '6px'; ?>; top: 10px; left: 150px; position: absolute; background-color: #007bff; color: #fff; padding: 8px 16px; margin-left: 10px; text-decoration: none;" onclick="changeBlur()"><?php echo ($blur == 1) ? 'Deactivate Blur' : 'Activate Blur'; ?></button>
    </div>

    <script>
        document.getElementById('backgroundButton').addEventListener('click', function() {
            var imageList = document.getElementById('imageList');
            imageList.style.display = imageList.style.display === 'none' ? 'block' : 'none';
        });

        document.getElementById('menuButton').addEventListener('click', function() {
            var menu = document.getElementById('menu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        });

        function changeBackground(imageUrl) {
            document.body.style.backgroundImage = 'url(' + imageUrl + ')';
            document.body.style.backgroundSize = 'cover';
            document.body.style.backgroundRepeat = 'no-repeat';
            document.body.style.backgroundPosition = 'center center';

            // Submit the form to update the background setting
            document.getElementById('backgroundInput').value = imageUrl;
            document.getElementById('backgroundForm').submit();
        }

        function changeTheme() {
            var themeButton = document.getElementById('themeButton');
            var themeValue = themeButton.innerHTML.includes('Modern') ? 0 : 1;

            // Update the theme button text
            themeButton.innerHTML = themeValue === 1 ? 'Modern Theme' : 'Classic Theme';

            // Update the border radius of the theme button and rounded buttons
            themeButton.style.borderRadius = themeValue === 1 ? '0' : '4px';
            var roundedButtons = document.getElementsByClassName('rounded-bottom');
            for (var i = 0; i < roundedButtons.length; i++) {
                roundedButtons[i].style.borderTopLeftRadius = themeValue === 1 ? '0' : '6px';
                roundedButtons[i].style.borderTopRightRadius = themeValue === 1 ? '0' : '6px';
                roundedButtons[i].style.borderBottomLeftRadius = themeValue === 1 ? '0' : '6px';
                roundedButtons[i].style.borderBottomRightRadius = themeValue === 1 ? '0' : '6px';
            }

            // Submit the form to update the theme setting
            document.getElementById('themeInput').value = themeValue;
            document.getElementById('themeForm').submit();
        }
        document.getElementById('blurButton').addEventListener('click', function() {
    var blurButton = document.getElementById('blurButton');
    var blurValue = blurButton.innerHTML.includes('Activate') ? 1 : 0;

    // Update the blur button text
    blurButton.innerHTML = blurValue === 1 ? 'Deactivate Blur' : 'Activate Blur';

    // Update the backdrop filter property
    var imageList = document.getElementById('imageList');
    imageList.style.backdropFilter = blurValue === 1 ? 'none' : 'blur(5px)';

    // Submit the form to update the blur setting
    document.getElementById('blurInput').value = blurValue;
    document.getElementById('blurForm').submit();
});

function changeBlur() {
	    var blurButton = document.getElementById('blurButton');
	    var blurValue = blurButton.innerHTML.includes('Activate') ? 1 : 0;

	    // Update the blur button text
	    blurButton.innerHTML = blurValue === 1 ? 'Deactivate Blur' : 'Activate Blur';

	    // Update the backdrop filter property
	    var imageList = document.getElementById('imageList');
	    imageList.style.backdropFilter = blurValue === 1 ? 'none' : 'blur(5px)';

	    // Submit the form to update the blur setting
	    document.getElementById('blurInput').value = blurValue;
	    document.getElementById('blurForm').submit();
	}

    </script>

    <form id="backgroundForm" method="post" style="display: none;">
        <input type="hidden" name="background" id="backgroundInput">
    </form>
    <form id="themeForm" method="post" style="display: none;">
        <input type="hidden" name="theme" id="themeInput">
    </form>
    <form id="blurForm" method="post" style="display: none;">
        <input type="hidden" name="blur" id="blurInput">
    </form>
</body>
</html>
