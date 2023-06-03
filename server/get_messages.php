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
// Set the stream context options
$contextOptions = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'Content-Type: text/html',
        'max_redirects' => 0,
        'timeout' => 0
    )
);

// Create the stream context
$context = stream_context_create($contextOptions);

// Read the contents of the messages file with the specified options
$chatMessages = file_get_contents('messages.txt', false, $context);

// Display the chat messages
echo nl2br($chatMessages);
?>
