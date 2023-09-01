<?php
function getServerUptime() {
    $uptime = shell_exec('uptime -s'); // Get the server's start time
    $startTime = strtotime($uptime);

    $now = time();
    $uptimeSeconds = $now - $startTime;

    $uptimeString = "";
    $uptimeString .= floor($uptimeSeconds / (60 * 60 * 24)) . " days, ";
    $uptimeString .= floor(($uptimeSeconds % (60 * 60 * 24)) / (60 * 60)) . " hours, ";
    $uptimeString .= floor(($uptimeSeconds % (60 * 60)) / 60) . " minutes";

    return $uptimeString;
}

$serverUptime = getServerUptime();
$cpuInfo = shell_exec('lscpu');
$krnlInfo = shell_exec('uname -a');
$memoryInfo = shell_exec('free -h');
$diskInfo = shell_exec('df -h');

// You can add more commands to fetch other hardware information as needed.

// Function to convert newlines to <br> tags for HTML display
function nl2br2($string) {
    return preg_replace('/\\r\\n?|\\n/', '<br>', $string);
}

// Sanitize and format the fetched information for HTML display
$cpuInfo = nl2br2(htmlspecialchars($cpuInfo));
$memoryInfo = nl2br2(htmlspecialchars($memoryInfo));
$diskInfo = nl2br2(htmlspecialchars($diskInfo));
?>

<!DOCTYPE html>
<style>
        body {
            background-image: url("https://lgrep.legamer4.repl.co/back.png");
            background-size: cover;
            min-height:100vh;object-fit:cover;
            color: white;
            background-color: black;
        }
                
        .rounded-bottom {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
        }

</style>
<html>
<head>
    <title>Server Hardware Information</title>
</head>
<body>
    <a href="../index.php" class="rounded-bottom" style="background-color: #007bff; color: #fff; padding: 8px 16px; margin-left: 5px; text-decoration: none;">Home</a>
    <h1>Server Hardware Information</h1>
    
    <h2>CPU Information</h2>
    <pre><?php echo $cpuInfo; ?></pre>

    <h2>Memory Information</h2>
    <pre><?php echo $memoryInfo; ?></pre>

    <h2>Disk Information</h2>
    <pre><?php echo $diskInfo; ?></pre>

    <h2>UPTime Information</h2>
    <pre><?php echo $serverUptime; ?></pre>

    <h2>Kernel Information</h2>
    <pre><?php echo $krnlInfo; ?></pre>


</body>
</html>
