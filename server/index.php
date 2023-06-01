<?php
session_start();
set_time_limit(0);

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

        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                $uploadDirectory = 'media/'; // Directory to store the uploaded media files
                $uploadedFileName = uniqid() . '.' . $fileExtension;
                $uploadedFilePath = $uploadDirectory . $uploadedFileName;

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
        file_put_contents('messages.txt', $formattedMessage, FILE_APPEND);
    } else {
        echo '<p>Session expired. Please start a new chat session.</p>';
    }
}
?>
