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
$file_path = 'messages.txt';
$timestamp = date('Y-m-d H:i:s');
$message = "[$timestamp] <span style='color: red;'><b><i><u>BUZZ!!!</b></i></u></span>";
file_put_contents($file_path, $message . PHP_EOL, FILE_APPEND);
exit;
?>
