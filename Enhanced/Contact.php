<?php
    require 'session_checks.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="Styles/NavigationBar.css">
    <link rel="stylesheet" href="Styles/Contact Us_style.css">
    
     <script src="Javascript/onoffline.js"></script>
</head>
<body ononline="onFunction()" onoffline="offFunction()">
   
    <header class="header">
        <?php include 'header.php';?>
    </header>

    <h1 class="title-page">Contact Us</h1>
   <div class="flexbox-container">
        <div class="flexbox-main">
            <h3 class="contact-label ">Social Media</h3>
            <div class="box">
                <div class="box-icon">
                    <div class="icon"><a href="https://www.google.com/gmail/"><i class="fas fa-envelope-square fa-5x"></i></a></div>
                    <div class="icon"><a href="https://www.instagram.com/"><i class="fab fa-instagram-square fa-5x"></i></a></div>
                    <div class="icon"><a href="https://twitter.com/"><i class="fab fa-twitter-square fa-5x"></i></a></div>
                    <div class="icon"><a href="https://www.facebook.com/"><i class="fab fa-facebook-square fa-5x"></i></a></div>
                </div>
            </div>

            <h3 class="contact-label">Contact</h3>
            <div class="box b1">
                <h4 class="content phoneN">Phone No.  : 09-XXXXXXX</h4>
            </div>

            <h3 class="contact-label">Address</h3>
            <div class="box">
                <h4 class="content address">Jalan Gombak, 53100, Selangor <br>
                    Universiti Islam Antarabangsa <br> Malaysia.</h4>
            </div>
        </div>

        <div class="flexbox-main">
            <h3 class="contact-label">Location</h3>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.3994748623677!2d101.73248081414566!3d3.2504820534292924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc38c2f70795af%3A0xf808897bf1163a3!2sUniversiti%20Islam%20Antarabangsa%20Malaysia!5e0!3m2!1sms!2smy!4v1640243472811!5m2!1sms!2smy" width="500" height="550" allowfullscreen="" loading="lazy"></iframe>
        
            </div>
   </div>
</div>

<script src="https://kit.fontawesome.com/57086d82eb.js" crossorigin="anonymous"></script>
</body>
</html>
