<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
</head>
<body ononline="onFunction()" onoffline="offFunction()">

    <nav class="nav-container">
        <a class="logo-link" href="main.php">
            <div class="logo-container">
                <img class="logo-img" src="Image/Hotel logo.png" alt="Hotel Logo" />
                <h4>Flower Hotel</h4>
            </div>
        </a>
        <input type="checkbox" id="click" />
        <label for="click" class="menu-btn">
            <i class="fas fa-bars"></i>
        </label>
        <ul class="list-link-container">
            <li><a class="active" href="main.php">Home</a></li>
            <li><a class="pasive" href="Booking.html">Booking</a></li>
            <li><a class="pasive" href="Room.html">Room</a></li>
            <li><a class="pasive" href="Facility.html">Facility</a></li>
            <li><a class="pasive" href="About Us.html">About Us</a></li>
            <li><a class="pasive" href="Contact.html">Contact Us</a></li>
            <li><a class="pasive" href="logout.php">Logout</a></li> <!-- Added Logout Link -->
        </ul>
    </nav>

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