<?php
    require 'session_checks.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Room</title>
    <link rel="stylesheet" href="Styles/NavigationBar.css" />
    <link rel="stylesheet" href="Styles/Room_style.css" />
     <script src="Javascript/onoffline.js"></script>
  </head>
  <body ononline="onFunction()" onoffline="offFunction()">
    
    <header class="header">
        <?php include 'header.php';?>
    </header>

    <h1 class="page-title">Room</h1>

    <div class="flexbox-container">
      <h4 class="main-content">
        We know how important a good night’s sleep can be and also how much
        difference real rest and recuperation can mean to you. At our hotel, we
        take great pride and care in providing you a plush sanctuary of
        uncompromised comfort for you to indulge in. Planning getaways or taking
        that well-deserved break now seems so much more intriguing with our
        hotel promotions at great value you will appreciate.Plan a perfect
        getaway with your loved ones and create a notable experience with us.
      </h4>
    </div>

    <div class="flexbox-container">
      <div class="flexbox">
        <div class="image img1"></div>
        <h3 class="sub-title">Single Room</h3>
        <p class="content">
          A room with the facility of single bed. It is meant for single
          occupancy. It has an attached bathroom, a small dressing table, a
          small bedside table, and a small writing table
        </p>
      </div>
      <div class="flexbox">
        <div class="image img2"></div>
        <h3 class="sub-title">Double Room</h3>
        <p class="content">
          A room with the facility of double bed. There are two variants in this
          type depending upon the size of the bed such as a room with king size
          double bed or room with queen size double bed.
        </p>
      </div>
      <div class="flexbox">
        <div class="image img3"></div>
        <h3 class="sub-title">Twin Room</h3>
        <p class="content">
          This room provides two single beds with separate headboards. It is
          meant for two independent people. It also has a single bedside table
          shared between the two beds.
        </p>
      </div>
      <div class="flexbox">
        <div class="image img4"></div>
        <h3 class="sub-title">Regular Suite</h3>
        <p class="content">
          It is composed of one or more bedrooms, a living room, and a dining
          area. It is excellent for the guests who prefer more space, wish to
          entertain their guests without interruption and giving up privacy.
        </p>
      </div>
    </div>
    <script src="https://kit.fontawesome.com/57086d82eb.js" crossorigin="anonymous"></script>
  </body>
</html>
