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

$banFilePath = 'banned_users.txt';

if (isset($_POST['message'])) {
        $nickname = $_SESSION['nickname'];
        $ip = $_SESSION['ip']; // Retrieve the stored IP address from the session
        $timestamp = date("Y-m-d H:i:s");
        $message = $_POST['message'];
        $formattedMessage = "$message \n";

        file_put_contents('banned_users.txt', $formattedMessage, FILE_APPEND);
    } else {
        echo '<p>Session expired. Please start a new chat session.</p>';
    }
?>