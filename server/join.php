<?php
$banFilePath = 'banned_users.txt';
$deny = array();

if (file_exists($banFilePath)) {
    $deny = file($banFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}
if (in_array($_SERVER['REMOTE_ADDR'], $deny)) {
    header("location: /ban_notice.html");
    exit();
}
session_start();
if (isset($_POST['nickname'])) {
    $nickname = $_POST['nickname'];
    $_SESSION['nickname'] = $nickname;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = 'messages.txt';
    $timestamp = date("H:i:s");
    $nickname = $_SESSION['nickname'];
    $formattedMessage = "<p style='font-size: 10px;'><img src='./people.png' alt='wow' width='20' height='20'> ($timestamp) <span style='color: green;'>$nickname joined the room</span></p>";
    
    // Define the maximum number of retries
    $maxRetries = -1; // Unlimited retries
    
    // Retry until successful
    while ($maxRetries !== 0) {
        // Attempt to write the message to the file
        $result = file_put_contents('messages.txt', $formattedMessage . PHP_EOL, FILE_APPEND);
        
        // Check if the write was successful
        if ($result !== false) {
            http_response_code(200);
            break; // Exit the loop
        }
        
        // Reduce the number of retries
        if ($maxRetries > 0) {
            $maxRetries--;
        }
    }
    
    if ($maxRetries === 0) {
        http_response_code(500); // Internal Server Error
        echo "Failed to write the message to the file after maximum retries.";
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method.";
}
?>
