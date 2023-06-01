<?php
session_start();
if (isset($_POST['nickname'])) {
    $nickname = $_POST['nickname'];
    $_SESSION['nickname'] = $nickname;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = 'messages.txt';
    $timestamp = date("H:i:s");
    $nickname = $_SESSION['nickname'];
    $formattedMessage = "<p style='font-size: 10px;'><img src='./people.png' alt='wow' width='20' height='20'> ($timestamp)  <span style='color: #f54242;'>$nickname left the room</span></p>";
    file_put_contents('messages.txt', $formattedMessage . PHP_EOL, FILE_APPEND);
    fclose($handle);
    http_response_code(200);
} else {
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method.";
}
?>
