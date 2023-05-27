<?php
session_start();
require_once 'config.php';

// Fetch the userlist from the database
$sql = "SELECT username, online FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output the userlist as an HTML list
    echo '<h4 style="color: #007bff;">User List ('.$result->num_rows.')</h4>';
    echo '<ul>';
    while ($row = $result->fetch_assoc()) {
        if($row['online'] == 0){
            $status = 'offline';
            $dot_color = 'red';
        } elseif ($row['online'] == 1) {
            $status = 'online';
            $dot_color = 'green';
        } else {
            $status = 'idle';
            $dot_color = 'yellow';
        }
        echo '<h5 style="color: #fff;"><span class="dot '.$dot_color.'"></span>'.$row['username'].'</h5>';
    }
    echo '</ul>';
} else {
    echo 'No users found.';
}
