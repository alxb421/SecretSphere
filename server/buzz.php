<?php
$file_path = __DIR__ . './messages.txt';
$timestamp = date('Y-m-d H:i:s');
$message = "[$timestamp] <span style='color: red;'><b><i><u>BUZZ!!!</b></i></u></span>";
file_put_contents($file_path, $message . PHP_EOL, FILE_APPEND);
exit;
?>
