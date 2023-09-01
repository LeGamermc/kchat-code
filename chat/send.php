<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Connect to database
$db_host = '127.0.0.1';
$db_name = 'kchats';
$db_user = 'legamer';
$db_pass = 'user';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check if database connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['message']) && !empty(trim($_POST['message']))) {
    $message = trim($_POST['message']);
    if (strlen($message) <= 151) {
        $username = $_SESSION['username'];

        // Sanitize the message to remove potentially unsafe HTML tags
        $sanitized_message = strip_tags($message, '<img>'); // Allow <img> tags for emojis and images

        // Replace emoji text with corresponding emoji images
        $emoji_map = array(
            ':(on):' => 'on.png',
            ':(off):' => 'off.png',
            ':(ina):' => 'ina.png',
            ':(cry):' => 'cry.png',
            ':(huh):' => 'huh.png',
            ':(wow):' => 'stareyes.png',
            ':(joy):' => 'joy.png',
            ':(smile):' => 'smile.png',
            // Add more emoji mappings here
        );
        $pattern = '/(:\([a-z]+\):)/i';
        $html_message = preg_replace_callback($pattern, function($matches) use ($emoji_map) {
            $emoji = $matches[1];
            if (array_key_exists($emoji, $emoji_map)) {
                $emoji_id = $emoji_map[$emoji];
                return '<img src="images/emojies/'.$emoji_id.'" alt="'.$emoji.'" style="max-width: 20%; border-radius: 10px;">';
            } else {
                return $emoji;
            }
        }, $sanitized_message);

        // Check if the message contains a URL with a PNG or JPG format
        $url_pattern = '/(http[s]?:\/\/[^\s]*\.(?:png|jpg|jpeg))/i';
        $html_message = preg_replace_callback($url_pattern, function($matches) use ($html_message) {
            $url = $matches[1];
            if (checkImageExists($url)) {
                return '<img onload="adjustImageSize(this)" src="'.$url.'" style="max-width: 20%; border-radius: 10px;">';
            } else {
                return $html_message; // Preserve the original message if image is not found
            }
        }, $html_message);

        // Insert the sanitized message into the database
        $query = "INSERT INTO messages (username, message) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $html_message);
        $stmt->execute();
        $stmt->close();
    }
}

// Get messages from database
$query = "SELECT * FROM messages ORDER BY id DESC LIMIT 50";
$result = $conn->query($query);

// Function to check if an image exists at the given URL
function checkImageExists($url) {
    $headers = get_headers($url);
    return stripos($headers[0], "200 OK") ? true : false;
}
?>
