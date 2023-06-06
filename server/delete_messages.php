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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = 'messages.txt';
    $handle = fopen($file, 'w');
    if ($handle) {
        ftruncate($handle, 0);

        $timestamp = date("H:i:s");
        $formattedMessage = "<p style='font-size: 8px;'>($timestamp) <b>Welcome to the (room name). Basic rules to follow: 1; 2; 3; 4</b>
        <img src='./people.png' alt='wow' width='20' height='20'> ($timestamp)  <b><span style='color: #adacac;'>The privacy of this conversation is now:</span> <span style='color: #f54040;'><u>Unencrypted</u></span></b></p><br>";

        file_put_contents('messages.txt', $formattedMessage, FILE_APPEND);

        fclose($handle);
        http_response_code(200);
    } else {
        http_response_code(500); // Internal Server Error
        echo "Failed to delete messages.";
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method.";
}
?>
