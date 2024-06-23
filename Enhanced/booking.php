<?php
    require 'db.php';
    $error_first_name = '';
    $error_last_name = '';
    $error_phone = '';
    $error_email = '';
    $error_checkin = '';
    $error_checkout = '';
    $error_adult = '';
    $error_children = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $checkin = trim($_POST["checkin"]);
    $checkout = trim($_POST["checkout"]);
    $adult = trim($_POST["adult"]);
    $children = trim($_POST["children"]);

    if (empty($first_name)) {
        $error_first_name = "First Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
        $error_first_name = "Only letters and white space allowed";
    } else {
        $error_first_name = "";
    }

    if (empty($last_name)) {
        $error_last_name = "Last Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $last_name)) {
        $error_last_name = "Only letters and white space allowed";
    } else {
        $error_last_name = "";
    }

    if (empty($phone)) {
        $error_phone = "Phone is required";
    } elseif (!preg_match("/^\d{10}$/", $phone)) {
        $error_phone = "Invalid phone number format";
    } else {
        $error_phone = "";
    }    

    if (empty($email)) {
        $error_email = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_email = "Invalid email format";
    } else {
        $error_email = "";
    }

    if (empty($checkin)) {
        $error_checkin = "Check-in Date is required";
    } else {
        $error_checkin = "";
    }

    if (empty($checkout)) {
        $error_checkout = "Check-out Date is required";
    } else {
        $error_checkout = "";
    }

    if (empty($adult)) {
        $error_adult = "Number of Adults is required";
    } elseif (!ctype_digit($adult)) {
        $error_adult = "Please enter a valid integer";
    } else {
        $error_adult = "";
    }

    if (empty($children)) {
        $error_children = "Number of Children is required";
    } elseif (!ctype_digit($children)) {
        $error_children = "Please enter a valid integer";
    } else {
        $error_children = "";
    }

    if (empty($error_first_name) && empty($error_last_name) && empty($error_phone) && empty($error_email) && empty($error_checkin) && empty($error_checkout) && empty($error_adult) && empty($error_children)) {
        try {
            $query = $pdo->prepare('INSERT INTO booking (first_name, last_name, phone, email, check_in, check_out, adult, children) VALUES (:first_name, :last_name, :phone, :email, :checkin, :checkout, :adult, :children)');
            $query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $query->bindParam(':phone', $phone, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':checkin', $checkin);
            $query->bindParam(':checkout', $checkout);
            $query->bindParam(':adult', $adult, PDO::PARAM_INT);
            $query->bindParam(':children', $children, PDO::PARAM_INT);
            $query->execute();
            echo "<script>alert('Booking created successfully');</script>";
        } catch (PDOException $e) {
            echo 'Booking failed: ' . $e->getMessage();
        }
    }
}
?>
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
</head>

<body ononline="onFunction()" onoffline="offFunction()">

    <nav>
        <a class="logo-link" href="main.php">
            <nav class="nav-container">
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

    <div class="container-title">Booking</div>

    <div class="flexbox-container">
        <h4 class="main-content">
            <strong>FLOWER HOTEL 4-STAR HOTEL IN GOMBAK CITY , KUALA LUMPUR </strong> <br>
            FLOWER Hotel makes up one of the trifecta of hotels within Gombak City,
            offering greenest scenery ever to the customer with the great facilities
            that always been take care by our staffs. These include the East Mall
            and Melawati shopping Mall. With the added facilities and various event
            venues located within walking distance, FLOWER Hotel is ideal for guests
            travelling both for honeymoon and leisure. <br> <br> <strong> Come and join us.</strong> <br>Press the button below.
        </h4>
    </div>

    <div class="button-container">
        <button id="more">Book Now</button>
    </div> 

    <div class="body">
    <div class="container">
        <div class="content">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <div class="user_details">
                    <div class="input_box">
                        <label for="first_name">First Name :</label>
                        <input type="text" name="first_name" id="first_name" required>
                        <?php if (!empty($error_first_name)) : ?>
                            <p style="color: red;"><?php echo htmlspecialchars($error_first_name); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="input_box">
                        <label for="last_name">Last Name :</label>
                        <input type="text" name="last_name" id="last_name" required>
                        <?php if (!empty($error_last_name)) : ?>
                            <p style="color: red;"><?php echo htmlspecialchars($error_last_name); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="input_box">
                        <label for="phone">Phone no. :</label>
                        <input type="text" name="phone" id="phone" required>
                        <?php if (!empty($error_phone)) : ?>
                            <p style="color: red;"><?php echo htmlspecialchars($error_phone); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="input_box">
                        <label for="email">Email :</label>
                        <input type="email" name="email" id="email" required>
                        <?php if (!empty($error_email)) : ?>
                            <p style="color: red;"><?php echo htmlspecialchars($error_email); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="input_box">
                        <label for="checkin">Check-in :</label>
                        <input type="date" name="checkin" id="checkin" required>
                        <?php if (!empty($error_checkin)) : ?>
                            <p style="color: red;"><?php echo htmlspecialchars($error_checkin); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="input_box">
                        <label for="checkout">Check-out :</label>
                        <input type="date" name="checkout" id="checkout" required>
                        <?php if (!empty($error_checkout)) : ?>
                            <p style="color: red;"><?php echo htmlspecialchars($error_checkout); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="input_box">
                        <label for="adult">No. of Adults :</label>
                        <input type="number" name="adult" id="adult" required>
                        <?php if (!empty($error_adult)) : ?>
                            <p style="color: red;"><?php echo htmlspecialchars($error_adult); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="input_box">
                        <label for="children">No. of Children :</label>
                        <input type="number" name="children" id="children" required>
                        <?php if (!empty($error_children)) : ?>
                            <p style="color: red;"><?php echo htmlspecialchars($error_children); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="button">
                    <input type="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
    <script src="https://kit.fontawesome.com/57086d82eb.js" crossorigin="anonymous"></script>
</body>
</html>
