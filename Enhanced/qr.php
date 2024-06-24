<?php
require 'db.php';
require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$google2fa = new GoogleAuthenticator();

// Retrieve user data
$stmt = $pdo->prepare('SELECT google2fa_secret FROM users WHERE id = :id');
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$google2fa_secret = $user['google2fa_secret'];

if (!$google2fa_secret) {
    $google2fa_secret = $google2fa->generateSecret();
    $stmt = $pdo->prepare('UPDATE users SET google2fa_secret = :google2fa_secret WHERE id = :id');
    $stmt->bindParam(':google2fa_secret', $google2fa_secret, PDO::PARAM_STR);
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
}

$qrCodeUrl = GoogleQrUrl::generate($_SESSION['username'], $google2fa_secret, 'FlowerHotel');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup 2FA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Setup Two-Factor Authentication</h2>
                <p class="text-center">Scan the QR code below with your Google Authenticator app:</p>
                <div class="text-center">
                    <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code" class="img-fluid">
                </div>
                <p class="text-center mt-3">Then enter the generated code below to complete the setup.</p>
                <form action="qr_verify.php" method="POST">
                    <div class="mb-3">
                        <label for="google2fa_code" class="form-label">2FA Code:</label>
                        <input type="text" name="google2fa_code" class="form-control" id="google2fa_code" placeholder="Enter 2FA Code" autocomplete="off" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Verify</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
