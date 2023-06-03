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

session_start();

if (isset($_POST['nickname'])) {
    $nickname = $_POST['nickname'];
    $_SESSION['nickname'] = $nickname;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR']; // Store the IP address in the session
}

if (isset($_POST['message'])) {
    if (isset($_SESSION['nickname'])) {
        $nickname = $_SESSION['nickname'];
        $ip = $_SESSION['ip']; // Retrieve the stored IP address from the session
        $timestamp = date("Y-m-d H:i:s");
        $message = $_POST['message'];
        $formattedMessage = "<p style='font-size: 10px;'>[$timestamp] $nickname ($ip): $message</p> \n";
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
@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
.gradient {
  --size: 250px;
  --speed: 50s;
  --easing: cubic-bezier(0.8, 0.2, 0.2, 0.8);
  width: var(--size);
  height: var(--size);
  filter: blur(calc(var(--size) / 5));
  background-image: linear-gradient(rgba(55, 235, 169, 0.85), #5b37eb);
  animation: rotate var(--speed) var(--easing) alternate infinite;
  border-radius: 30% 70% 70% 30%/30% 30% 70% 70%;
}

@media (min-width: 720px) {
  .gradient {
    --size: 500px;
  }
}
        body {
            background-color: #222;
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
            #padding-top: 100px;
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

/* This is just to transition when you change the viewport size. */
* {
  transition: all 0.25s ease-out;
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
    <div class="gradient">
    <h1>Welcome to the ksJabber</h1>
    <p>To join the chat room, please follow the instructions below:</p>
    </div>
    <ol>
        <li>Open SecretSphere</li>
        <li>Click the IP address below to copy it to the clipboard</li>
        <li>Start chatting with others in the room</li>
    </ol>
    <p class="">IP Address: </p>
    <p class ="ip-address">https://ksjabber.000webhostapp.com</p>
    <p class="">Ticks (update delay): 1000</p>
</body>

</html>