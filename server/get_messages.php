<?php
$contextOptions = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'Content-Type: text/html',
        'max_redirects' => 0,
        'timeout' => 0
    )
);
$context = stream_context_create($contextOptions);
$chatMessages = file_get_contents('messages.txt', false, $context);
echo nl2br($chatMessages);
?>
