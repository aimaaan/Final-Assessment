<?php
require 'db.php';
require 'vendor/autoload.php';
use PragmaRX\Google2FA\Google2FA;
use Endroid\QrCode\QrCode;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$google2fa = new Google2FA();

// Retrieve user data
$stmt = $pdo->prepare('SELECT google2fa_secret FROM users WHERE id = :id');
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$google2fa_secret = $user['google2fa_secret'];

if (!$google2fa_secret) {
    $google2fa_secret = $google2fa->generateSecretKey();
    $stmt = $pdo->prepare('UPDATE users SET google2fa_secret = :google2fa_secret WHERE id = :id');
    $stmt->bindParam(':google2fa_secret', $google2fa_secret, PDO::PARAM_STR);
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
}

$google2fa_url = $google2fa->getQRCodeUrl(
    'FlowerHotel',
    $_SESSION['username'],
    $google2fa_secret
);

$qrCode = new QrCode($google2fa_url);
$qrCode->setSize(300);
$qrCode->setMargin(10);

$qrCodeImage = $qrCode->writeDataUri();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup 2FA</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Setup Two-Factor Authentication</h2>
        <p>Scan the QR code below with your Google Authenticator app:</p>
        <img src="<?php echo $qrCodeImage; ?>" alt="QR Code">
        <p>Then enter the generated code below to complete the setup.</p>
        <form action="verify_2fa_setup.php" method="POST">
            <input type="text" name="google2fa_code" placeholder="2FA Code" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>
