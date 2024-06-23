<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="Styles/NavigationBar.css">
    <link rel="stylesheet" href="Styles/Booking_style.css">
    <script src="Javascript/Book.js"></script>
    <script src="Javascript/onoffline.js"></script>
    <script src="Javascript/Hide_form.js"></script>

    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://ajax.googleapis.com https://kit.fontawesome.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;">
</head>

<body ononline="onFunction()" onoffline="offFunction()">

    <nav>
        <a class="logo-link" href="main.php">
            <div class="logo-container">
                <img class="logo-img" src="Image/Hotel logo.png" />
                <h4>Flower Hotel</h4>
            </div>
        </a>

        <input type="checkbox" id="click" />
        <label for="click" class="menu-btn">
            <i class="fas fa-bars"></i>
        </label>

        <ul class="list-link-container">
            <li><a class="pasive" href="main.php">Home</a></li>
            <li><a class="active" href="booking.php">Booking</a></li>
            <li><a class="pasive" href="Room.html">Room</a></li>
            <li><a class="pasive" href="Facility.html">Facility</a></li>
            <li><a class="pasive" href="About Us.html">About Us</a></li>
            <li><a class="pasive" href="Contact.html">Contact Us</a></li>
            <li><a class="pasive" href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Additional content can go here -->

    <script src="https://kit.fontawesome.com/57086d82eb.js" crossorigin="anonymous"></script>
</body>

</html>
