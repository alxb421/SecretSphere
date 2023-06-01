<?php
session_start();

if (isset($_POST['nickname'])) {
    $nickname = $_POST['nickname'];
    $_SESSION['nickname'] = $nickname;
}

if (isset($_POST['message'])) {
    if (isset($_SESSION['nickname'])) {
        $nickname = $_SESSION['nickname'];
        $timestamp = date("Y-m-d H:i:s");
        $message = $_POST['message'];
        $formattedMessage = "<p style='font-size: 10px;'>[$timestamp] $nickname: $message</p> \n";

        // Check if a file is uploaded
        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

            // Check if the uploaded file has a valid extension
            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                $uploadDirectory = 'media/'; // Directory to store the uploaded media files
                $uploadedFileName = uniqid() . '.' . $fileExtension;
                $uploadedFilePath = $uploadDirectory . $uploadedFileName;

                // Move the uploaded file to the media directory
                if (move_uploaded_file($_FILES['media']['tmp_name'], $uploadedFilePath)) {
                    $fileLink = $_SERVER['HTTP_HOST'] . '/' . $uploadedFilePath;
                    $formattedMessage .= "<p style='font-size: 10px; color: blue;'>[$timestamp] $nickname uploaded a file: <a href='$fileLink' target='_blank'>View File</a></p> \n";
                } else {
                    echo '<p>Failed to upload the media file.</p>';
                    exit;
                }
            } else {
                echo '<p>Invalid file type. Only images (JPEG, JPG, PNG, GIF) are allowed.</p>';
                exit;
            }
        }

        // Append the formatted message to the file
        file_put_contents('messages.txt', $formattedMessage, FILE_APPEND);
    } else {
        echo '<p>Session expired. Please start a new chat session.</p>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ksjabber - SecretSphere roomt</title>
    <style>
        body {
            background-color: #222;
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .ip-address {
            font-size: 24px;
            font-weight: bold;
            color: orange;
            cursor: pointer;
        }

        ol {
            list-style: none; /* Remove list bullets */
            padding-left: 0; /* Remove left indentation */
            text-align: center; /* Adjust text alignment */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ipAddressElement = document.querySelector('.ip-address');
            ipAddressElement.addEventListener('click', function() {
                var ipAddress = ipAddressElement.textContent;
                navigator.clipboard.writeText(ipAddress)
                    .then(function() {
                        console.log('IP address copied to clipboard: ' + ipAddress);
                        alert('IP address copied to clipboard: ' + ipAddress);
                    })
                    .catch(function(error) {
                        console.error('Failed to copy IP address to clipboard: ', error);
                    });
            });
        });
    </script>
</head>

<body>
    <h1>Welcome to the chat_room</h1>
    <p>To join the chat room, please follow the instructions below:</p>
    <ol>
        <li>Open SecretSphere</li>
        <li>Click the IP address below to copy it to the clipboard</li>
        <li>Start chatting with others in the room</li>
    </ol>
    <p class="">IP Address: </p>
    <p class ="ip-address">https://your-server.domain</p>
    <p class="">Ticks (update delay): 1000</p>
</body>

</html>

