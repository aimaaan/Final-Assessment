<?php
require 'security_config.php';
require 'session_checks.php';

// Start a secure session and generate a CSRF token
startSecureSession();
$csrfToken = generateCsrfToken();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="robots" content="noindex" />
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Main Homepage</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="Styles/NavigationBar.css">
    <link rel="stylesheet" href="Styles/Index_style.css" />
    <script src="Javascript/onoffline.js"></script>
    <script src="Javascript/Index.js"></script>
    <script src="Javascript/Surprise.js"></script>
    <script src="Javascript/Index-alert.js"></script>
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://ajax.googleapis.com https://kit.fontawesome.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net;">
</head>

<body ononline="onFunction()" onoffline="offFunction()">

    <header class="header">
        <?php include 'header.php'; ?>
    </header>

    <section class="flexbox-container">
        <div class="flexbox f1">
            <img id="music-btn" class="logo-img1" src="Image/Hotel logo.png" alt="Hotel Logo" />
        </div>
        <div class="flexbox f2">
            <h1 class="title-logo">Flower Hotel</h1>
            <p class="slogon">Here where the story starts to bloom</p>
            <button class="learn-more" type="button" onclick="learn_more()">LEARN MORE</button>
            <p onclick="music_alert()" class="notice">Click Here </p>
        </div>
    </section>

    <audio id="music-box" loop>
        <source src="Audio/Flower (Crash Landing On You OST)-Instrumental.mp3" type="audio/mpeg">
    </audio>
    <script src="https://kit.fontawesome.com/57086d82eb.js" crossorigin="anonymous"></script>
</body>

</html>