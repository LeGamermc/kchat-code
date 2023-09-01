<?php                                                                                                                                                                                    
// Connect to database                                                                                                                                                                                                                                                                                                                                                           
require_once 'config.php';
    
$username = $_SESSION['username'];
$query = "SELECT blur FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($theme);
$stmt->fetch();
$stmt->close();
                                                                                                                                                                                         
// Get messages from database                                                                                                                                                            
$query = "SELECT * FROM messages ORDER BY id DESC LIMIT 50";                                                                                                                             
$result = $conn->query($query);                                                                                                                                                          
                                                                                                                                                                                         
// Generate HTML code for the latest messages                                                                                                                                            
$html = '';                                                                                                                                                                              
if ($result->num_rows > 0) {                                                                                                                                                             
    while ($row = $result->fetch_assoc()) {                                                                                                                                              
        $username = $_SESSION['username'];                                                                                                                                               
        $html .= '<p><strong>' . $row['username'] . '</strong>: ' . $row['message'] . ' <span style="font-size: 8pt; top: 10px; background-color: grey; color: white; padding: 4px 8px; border-radius: 4px; margin-left: 10px; text-decoration: none;"><strong>(' . $row['timestamp'] . ')</strong></span></p>';                                                                                                                                                                                      
    }
} else {
    $html = '<p>No messages yet.</p>';
}

// Return the HTML code as the response
echo $html;

// Close database connection
$conn->close();
?>
