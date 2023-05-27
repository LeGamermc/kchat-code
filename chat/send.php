<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

require_once 'config.php';

// Handle form submission
if (isset($_POST['message']) && !empty(trim($_POST['message']))) {
    $message = trim($_POST['message']);
    if (strlen($message) <= 100) {
        $username = $_SESSION['username'];
        $html_message = parseMessage($message);
        
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

function parseMessage($message) {
    $pattern = '/(https?:\/\/\S+\.(?:png|gif|jpe?g))/i';
    $parsedMessage = preg_replace_callback($pattern, 'replaceImageCallback', $message);
    
    return $parsedMessage;
}

function replaceImageCallback($matches) {
    $imageUrl = $matches[0];
    $headers = get_headers($imageUrl);
    
    if ($headers && strpos($headers[0], '200') !== false) {
        return '<img src="' . $imageUrl . '" style="max-width: 20%;">';
    } else {
        return '<img src="images/image404.png" style="max-width: 20%;">';
    }
}
?>
