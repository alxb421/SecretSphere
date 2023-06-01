<?php
// Read the last displayed message ID from the request
if (isset($_POST['lastMessageId'])) {
    $lastMessageId = $_POST['lastMessageId'];

    // Set the timeout limit to infinity
    set_time_limit(0);

    // Define the path to the messages file
    $filePath = 'messages.txt';

    // Open the messages file in read-only mode
    $file = fopen($filePath, 'r');

    // Acquire an exclusive lock on the file
    flock($file, LOCK_SH);

    // Check if the messages file has been modified
    clearstatcache(); // Clear file status cache
    if (filemtime($filePath) > $lastMessageId) {
        // Seek to the beginning of the file
        fseek($file, 0);

        // Array to store new messages
        $newMessages = array();

        // Read the contents of the messages file
        while (!feof($file)) {
            $message = fgets($file);

            // Extract the message ID from the message
            preg_match('/^\[(\d+)\]/', $message, $matches);
            $messageId = isset($matches[1]) ? $matches[1] : '';

            // If the message ID is greater than the last displayed ID, it's a new message
            if ($messageId > $lastMessageId) {
                $newMessages[] = $message;
            }
        }

        // Output the new messages as HTML
        foreach ($newMessages as $message) {
            echo '<div class="message">' . htmlspecialchars($message) . '</div>';
        }
    }

    // Release the file lock
    flock($file, LOCK_UN);

    // Close the file
    fclose($file);
}
?>
