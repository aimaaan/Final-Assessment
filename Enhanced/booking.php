<?php
session_start();  // This should be at the very beginning

require 'db.php';
require 'security_config.php';

// CSRF Token Generation and Validation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function sanitizeInput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$error_first_name = '';
$error_last_name = '';
$error_phone = '';
$error_email = '';
$error_checkin = '';
$error_checkout = '';
$error_adult = '';
$error_children = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    $first_name = sanitizeInput(trim($_POST["first_name"]));
    $last_name = sanitizeInput(trim($_POST["last_name"]));
    $phone = sanitizeInput(trim($_POST["phone"]));
    $email = sanitizeInput(trim($_POST["email"]));
    $checkin = sanitizeInput(trim($_POST["checkin"]));
    $checkout = sanitizeInput(trim($_POST["checkout"]));
    $adult = sanitizeInput(trim($_POST["adult"]));
    $children = sanitizeInput(trim($_POST["children"]));

    if (empty($first_name)) {
        $error_first_name = "First Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
        $error_first_name = "Only letters and white space allowed";
    }

    if (empty($last_name)) {
        $error_last_name = "Last Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $last_name)) {
        $error_last_name = "Only letters and white space allowed";
    }

    if (empty($phone)) {
        $error_phone = "Phone is required";
    } elseif (!preg_match("/^\d{10}$/", $phone)) {
        $error_phone = "Invalid phone number format";
    }

    if (empty($email)) {
        $error_email = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_email = "Invalid email format";
    }

    if (empty($checkin)) {
        $error_checkin = "Check-in Date is required";
    }

    if (empty($checkout)) {
        $error_checkout = "Check-out Date is required";
    }

    if (empty($adult)) {
        $error_adult = "Number of Adults is required";
    } elseif (!ctype_digit($adult)) {
        $error_adult = "Please enter a valid integer";
    }

    if (empty($children)) {
        $error_children = "Number of Children is required";
    } elseif (!ctype_digit($children)) {
        $error_children = "Please enter a valid integer";
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

    <header class="header">
        <?php include 'header.php';?>
    </header>

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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
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

    <script src="https://kit.fontawesome.com/57086d82eb.js" crossorigin="anonymous"></script>
</body>
</html>
